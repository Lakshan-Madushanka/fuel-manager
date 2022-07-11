<x-app-layout>

    <div id="alert-wrapper"></div>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>
            <div class="flex justify-between space-x-8">
                <div id='change-role' class="hidden">
                    <form id='mass-delete' method="post" action="{{route('admin.users.massDelete')}}">
                        @csrf
                        <input type="hidden" id='deleteBtn-checkbox' name='deleteIds'/>
                        <x-jet-button>Change Role</x-jet-button>
                    </form>
                </div>
                <div id='delete-selected' class="hidden">
                    <form id='mass-delete' method="post" action="{{route('admin.users.massDelete')}}">
                        @csrf
                        <input type="hidden" id='deleteBtn-checkbox' name='deleteIds'/>
                        <x-jet-button>Delete</x-jet-button>
                    </form>
                </div>
            </div>


        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg h-full">
                <x-search class="w-1/2 !mt-6" method="GET" action="{{route('admin.users.index')}}"/>
                @if($users->isEmpty())
                    <p class="bg-yellow-400 mx-auto w-1/2 text-center p-2 text-lg my-8">No records found</p>
                @else
                    <x-table.index class="!w-[1800px]">
                        <x-slot:columns>
                            <x-table.column>
                                <x-jet-checkbox id="main-checkbox" value='' name="ids"/>
                            </x-table.column>
                            <x-table.column>#</x-table.column>
                            <x-table.column>NIC</x-table.column>
                            <x-table.column>Name</x-table.column>
                            <x-table.column>Email</x-table.column>
                            <x-table.column>Status</x-table.column>
                            <x-table.column>Type</x-table.column>
                            <x-table.column>Role</x-table.column>
                            <x-table.column>Actions</x-table.column>
                            <x-table.column>Created at</x-table.column>
                        </x-slot:columns>
                        <x-slot:rows>
                            @foreach($users as $user)
                                <x-table.row>
                                    <x-table.row-item>
                                        <x-jet-checkbox id="row-check-box-{{$user->id}}" name="ids" :value="$user->id"/>
                                    </x-table.row-item>
                                   <x-table.row-item> {{$loop->iteration}} </x-table.row-item>
                                    <x-table.row-item>{{$user->nic}}</x-table.row-item>
                                    <x-table.row-item>{{$user->name}}</x-table.row-item>
                                    <x-table.row-item>{{$user->email}}</x-table.row-item>
                                    <x-table.row-item>
                                        <x-badge color="bg-green-600" :content="$user->status->name"/>
                                    </x-table.row-item>
                                    <x-table.row-item>
                                        <x-badge color="bg-green-600" :content="$user->type->name"/>
                                    </x-table.row-item>
                                    <x-table.row-item>
                                        <x-badge color="bg-green-600" :content="$user->role->name"/>
                                    </x-table.row-item>
                                    <x-table.row-item>
                                        <div class="flex ">
                                            <form method="POST"
                                                  action="{{route('admin.account.approve', ['user' => $user->id])}}">
                                                @csrf
                                                <x-action-button class="p-1 !bg-cyan-400 mr-1">Approve</x-action-button>
                                            </form>
                                            <div class="mr-1 border-amber-600 border-r-4 border-solid"></div>
                                            <form method="POST"
                                                  action="{{route('admin.account.block', ['user' => $user->id])}}">
                                                <x-action-button class="p-1 !bg-cyan-500">Block</x-action-button>
                                                @csrf
                                            </form>
                                            <div class="mx-1 border-amber-600 border-r-4 border-solid"></div>
                                            <form method="POST"
                                                  action="{{route('admin.users.destroy', ['user' => $user->id])}}">
                                                @method('delete')
                                                <x-action-button class="p-1 !bg-red-600">Delete</x-action-button>
                                                @csrf
                                            </form>
                                        </div>
                                    </x-table.row-item>
                                    <x-table.row-item>{{$user->created_at->diffForHumans()}}</x-table.row-item>
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

    @push('script')
        <script>
            $(function () {
                const mainCheckBoxElm = $('#main-checkbox');
                const deleteButton = $('#delete-selected')
                const checkBoxElements = $('input[name=ids]');
                let selectedIdsToDelete = [];
                const changeRoleBtn = $('#change-role');

                $('tbody tr input[type=checkbox]').click(function (e) {
                    e.stopPropagation();

                })

                $('tbody tr').click(function (e) {
                    const checkBoxElm = $(this).find('input[type=checkbox]');
                    if (checkBoxElm.is(':checked')) {
                        checkBoxElm.prop('checked', false);
                    } else {
                        checkBoxElm.prop('checked', true)
                    }

                })

                mainCheckBoxElm.change(function () {
                    if ($(this).is(':checked')) {
                        checkBoxElements.each(function () {
                            $(this).prop('checked', true);
                        })
                        deleteButton.removeClass('hidden')
                    } else {
                        checkBoxElements.each(function () {
                            $(this).prop('checked', false);
                        })
                        deleteButton.addClass('hidden')
                    }
                })

                checkBoxElements.change(function () {
                    let checkedElements = 0;
                    selectedIdsToDelete = [];
                    checkBoxElements.each(function (i, element) {
                        if (element.checked) {
                            checkedElements +=1
                          selectedIdsToDelete.push($(this).val())
                        }
                    })

                    if(checkedElements === 1) {
                        changeRoleBtn.removeClass('hidden')
                    }else{
                        changeRoleBtn.addClass('hidden')

                    }

                    if (checkedElements > 1) {
                        deleteButton.removeClass('hidden')
                    } else {
                        deleteButton.addClass('hidden')
                    }
                })

                deleteButton.click(function (e) {
                    e.preventDefault();
                    $('#deleteBtn-checkbox').val(selectedIdsToDelete.toString())
                    $('#mass-delete').submit();

                });
            })
        </script>
    @endpush
</x-app-layout>

