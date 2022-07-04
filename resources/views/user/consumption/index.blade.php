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
                            <table class="w-full text-left rounded-lg">
                                <thead>
                                <tr class="text-gray-800 text-center border border-b-0">
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">NIC</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">{{ucfirst(strtolower($fqs->basis->name))}} Consumption (lr)
                                    </th>
                                    <th class="px-4 py-3">Remaining</th>
                                    <th class="px-4 py-3">Add Consumption</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr class="w-full font-light text-gray-700 text-center bg-gray-100 whitespace-no-wrap border border-b-0 hover:bg-gray-50">
                                        <td class="px-4 py-4">{{$loop->iteration}}</td>
                                        <td class="px-4 py-4">{{$user->nic}}</td>
                                        <td class="px-4 py-4">{{$user->name}}</td>
                                        <td class="px-4 py-4" id="user-{{$user->id}}-consumption">
                                            <x-badge :content="number_format($user->currentPlanFuelConsumptionAmount, 2) ?? 0.00"
                                                     type="warning"/>
                                        </td>
                                        <td class="px-4 py-4" id="user-{{$user->id}}-remaining">
                                            <x-badge :content="number_format($user->remaining_fuel_quota, 2)" type="primary"/>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($user->remaining_fuel_quota > 0)
                                                <a href="#" id=''
                                                   data="{{$user->id}}"
                                                   class="fuel-add-button text-center inline-block text-green-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </a>
                                            @else
                                                <x-badge content="Limit Exceeded" type="danger"/>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach


                                {{-- <tr class="w-full font-light text-gray-700 bg-gray-100 whitespace-no-wrap border">
                                     <td class="px-4 py-4">3</td>
                                     <td class="px-4 py-4">Adam wathan</td>
                                     <td class="px-4 py-4">
                                         <img class="h-6 w-6 rounded-full"
                                              src="/assets/docs/master/image-01.jpg">
                                     </td>
                                     <td class="px-4 py-4">tmgbedu@gmail.com</td>
                                     <td class="px-4 py-4">
                                         <span class="text-sm bg-red-500 text-white rounded-full px-2 py-1">Not Active</span>
                                     </td>
                                     <td class="text-center py-4">
                                         <a href="#"><span class="fill-current text-green-500 material-icons">edit</span></a>
                                         <a href="#"><span class="fill-current text-red-500 material-icons">highlight_off</span></a>
                                     </td>
                                 </tr>--}}
                                </tbody>
                            </table>
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
                    let route = "{{route('users.index')}}" + "?search=" + searchVal;

                    $('#table-wrapper').load(`${route} #table-wrapper`, function () {
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
                    console.log(selectedFuelAddButton)
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
                    let route = "{{route('api.users.consumptions.store', ['user' => ':id'])}}"
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
                            console.log('prese', previousConsumption);
                            consumptionElm.text(previousConsumption + parseFloat(addedAmount))

                            const remainingElm = $(`#user-${userId}-remaining span`)
                            const previousRemaining = parseFloat(remainingElm.text())
                            remainingElm.text(previousRemaining - parseFloat(addedAmount))

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
                            console.log('limit exceedededd')
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

