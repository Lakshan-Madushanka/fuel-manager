<x-app-layout>

    <div id="alert-wrapper"></div>
    <x-slot name="header" class="flex">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('quota Plans') }}
            </h2>

            <form method="get" action="{{route('admin.quotas.create')}}">
            <x-jet-button type="submit">
                Create new plan
            </x-jet-button>
            </form>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg h-full">
                @if($plans->isEmpty())
                    <p class="bg-yellow-400 mx-auto w-1/2 text-center p-2 my-4 text-lg">No records found</p>
                @else
                <x-table.index>
                    <x-slot:columns>
                        <x-table.column>#</x-table.column>
                        <x-table.column>Basis</x-table.column>
                        <x-table.column>Special Amount</x-table.column>
                        <x-table.column>Regular Amount</x-table.column>
                        <x-table.column>Created at</x-table.column>
                    </x-slot:columns>
                    <x-slot:rows>
                        @foreach($plans as $plan)
                            <x-table.row>
                                <x-table.row-item>{{$loop->iteration}}</x-table.row-item>
                                <x-table.row-item>
                                    <x-badge type="info" :content="$plan->basis->name"/>
                                </x-table.row-item>
                                <x-table.row-item>{{$plan->regular_amount}}</x-table.row-item>
                                <x-table.row-item>{{$plan->special_amount}}</x-table.row-item>
                                <x-table.row-item>{{$plan->created_at}}</x-table.row-item>
                            </x-table.row>
                        @endforeach
                    </x-slot:rows>
                </x-table.index>
                @endif
            </div>
        </div>
    </div>


</x-app-layout>

