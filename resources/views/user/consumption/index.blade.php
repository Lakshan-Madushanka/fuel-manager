<x-app-layout>

    <div id="alert-wrapper"></div>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Consumption') }}
            </h2>
            @inject('fqs', '\App\Services\FuelQuotaService')
            <p>Basis
                <x-badge :content='$fqs->basis->name' type="info"/>
            </p>
            <p>Start Date
                <x-badge :content="$fqs->getCurrentPlanDates()['startDate']->toDayDateTimeString()" type="info"/>
            </p>
            <p>End date
                <x-badge :content="$fqs->getCurrentPlanDates()['endDate']->toDayDateTimeString()" type="info"/>
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg h-full">
                <x-search class="w-1/2"/>
                <div id="result-loading-indicator" class="mx-auto flex justify-center"></div>
                <div id='table-wrapper--primary' class="bg-white rounded-lg shadow-lg py-6">
                    <div id='table-wrapper' class="block overflow-x-auto mx-6">
                        @if($users->isEmpty())
                            <p class="bg-yellow-400 mx-auto w-1/2 text-center p-2 my-4 text-lg">No records found</p>
                        @else
                            <x-table.index>
                                <x-slot:columns>
                                    <x-table.column>#</x-table.column>
                                    <x-table.column>NIC</x-table.column>
                                    <x-table.column>Name</x-table.column>
                                    <x-table.column>
                                        {{ucfirst(strtolower($fqs->basis->name))}} Consumption (lr)
                                    </x-table.column>
                                    <x-table.column>Remaining</x-table.column>
                                    <x-table.column>Add Consumption</x-table.column>
                                    <x-table.column>Consumption History</x-table.column>
                                </x-slot:columns>
                                <x-slot:rows>
                                    @foreach($users as $user)
                                        <x-table.row>
                                            <x-table.row-item>{{$loop->iteration}}</x-table.row-item>
                                            <x-table.row-item>{{$user->nic}}</x-table.row-item>
                                            <x-table.row-item>{{$user->name}}</x-table.row-item>
                                            <x-table.row-item id="user-{{$user->id}}-consumption" v="opa">
                                                <x-badge
                                                        :content="number_format($user->currentPlanFuelConsumptionAmount, 2) ?? 0.00"
                                                        type="warning"/>
                                            </x-table.row-item>
                                            <x-table.row-item id="user-{{$user->id}}-remaining">
                                                <x-badge :content="number_format($user->remaining_fuel_quota, 2)"
                                                         type="primary"/>
                                            </x-table.row-item>
                                            <x-table.row-item>
                                                @if ($user->remaining_fuel_quota > 0)
                                                    <a href="#!"
                                                       data="{{$user->id}}"
                                                       class="fuel-add-button text-center w-full inline-block text-green-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 mx-auto w-6"
                                                             fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <x-badge content="Limit Exceeded" type="danger"/>
                                                @endif
                                            </x-table.row-item>
                                            <x-table.row-item>
                                                <a href="{{route('users.consumptions.index', ['user' => $user->id])}}"
                                                   class="text-center inline-block text-black-600 w-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto"
                                                         viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                        <path fill-rule="evenodd"
                                                              d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                            </x-table.row-item>
                                        </x-table.row>
                                    @endforeach
                                </x-slot:rows>
                            </x-table.index>
                    </div>
                    @if(! $users->isEmpty())
                        <div class="mt-4 px-4">
                            {{$users->links()}}
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div id='show-modal' class="hidden modal">
        <x-modal class="block mt-20" title="Add amount">
            <x-jet-label for="amount (lr)" value="{{ __('Amount') }}"></x-jet-label>
            <x-jet-input id="amount" class="block mt-1 w-1/2" type="number"
                         placeholder='Amount' name="amount" required autofocus/>
            <div id='amount-errors'>
                <div id='amount-error'>
                </div>
                <div id='quota-limit-exceeded-error'>
                </div>
            </div>

        </x-modal>
    </div>

    @push('script')
        <script>
            $(document).ready(function () {
                /**
                 *
                 * search functionality
                 */
                const searchBtnElm = $("button[type=submit]");
                let searchLoading = false;

                const modal = $('#show-modal')
                const tableWrapperElm = $('#table-wrapper--primary');
                let selectedFuelAddButton;

                searchBtnElm.click(function (event) {
                    event.preventDefault();
                    prepareContentForSearch(event)
                });

                function prepareContentForSearch(event) {
                    const searchVal = $('#default-search').val();

                    if (searchLoading) {
                        return;
                    }

                    searchLoading = true;
                    searchBtnElm.toggleClass('cursor-not-allowed');
                    $('#result-loading-indicator').append(`<x-spinner/>`)

                    loadSearchResults(searchVal);
                }

                // make ajax request to server to load search results
                function loadSearchResults(searchVal) {
                    let route = "{{route('admin.users.index')}}" + "?search=" + searchVal;

                    $('#table-wrapper--primary').load(`${route} #table-wrapper`, function () {
                        $('#result-loading-indicator').empty();
                        searchBtnElm.toggleClass('cursor-not-allowed');
                        searchLoading = false;
                    })
                }


                /**
                 *
                 * end of search functionality
                 */


                /**
                 *  modal functionality
                 */
                tableWrapperElm.on('click', '.fuel-add-button', function () {
                    modal.toggleClass('hidden')
                    selectedFuelAddButton = $(this);

                })

                $('#modal-save-button').click(function () {
                    const userId = selectedFuelAddButton.attr('data');

                    let addedAmount = $('#amount').val()

                    sendConsumedPostRequest(userId, addedAmount);
                })

                $('#modal-close').click(function () {
                    modal.addClass('hidden');
                    clearErrors()
                })

                function clearErrors() {
                    $('#amount-error').empty()
                    $('#quota-limit-exceeded-error').empty()
                }

                function sendConsumedPostRequest(userId, addedAmount) {
                    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let route = "{{route('api.admin.users.consumptions.store', ['user' => ':id'])}}"
                    route = route.replace(':id', userId)

                    if (addedAmount === '') {
                        clearErrors()
                        $('#amount-error').append(`<x-input-error message="Invalid amount"></x-input-error>`)
                        return
                    }

                    $('.modal-footer').append(`<x-spinner/>`)
                    $('.modal-footer button').toggleClass('hidden')
                    clearErrors()

                    $.ajax({
                        type: 'POST',
                        url: route,
                        data: {
                            token: CSRF_TOKEN,
                            amount: addedAmount
                        },
                        headers: {
                            accept: 'application/json'
                        },
                        success: function () {
                            const consumptionElm = $(`#user-${userId}-consumption span`)
                            const previousConsumption = parseFloat(consumptionElm.text())
                            const currentConsumption = (previousConsumption + parseFloat(addedAmount)).toLocaleString(
                                undefined,
                                {minimumFractionDigits: 2}
                            )
                            consumptionElm.text(currentConsumption)

                            const remainingElm = $(`#user-${userId}-remaining span`)
                            const previousRemaining = parseFloat(remainingElm.text().replace(',', ''))
                            const currentRemaining = (previousRemaining - parseFloat(addedAmount)).toLocaleString(
                                undefined,
                                {minimumFractionDigits: 2}
                            )
                            remainingElm.text(currentRemaining)

                            modal.toggleClass('hidden')
                            $('input').val('');
                            clearErrors()
                            $('#alert-wrapper').append(`<x-alert  type='success'
                                 content='Added new consumption for the selected user !'/>`)
                            loadAlertProgressIndicator()

                        }
                    }).fail(function (error) {
                        const status = error.status;
                        const errorData = JSON.parse(error.responseText)
                        clearErrors();
                        if (status === 422) {
                            console.log(errorData.errors)
                            $('#amount-error').append(`<x-input-error message="Invalid amount"></x-input-error>`)
                        }

                        if (errorData.error && errorData.error.toLowerCase().includes('limit')) {
                            $('#quota-limit-exceeded-error').append(` <x-input-error message=${errorData.message}></x-input-error>`)
                        }
                    }).always(function () {
                        $('#spinner').remove()
                        $('.modal-footer button').toggleClass('hidden')
                    });
                }

                /**
                 * end of modal functionality
                 */


            })
        </script>
    @endpush
</x-app-layout>

