<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Consumption History') }}
                </h2>

            </div>
        </h2>
    </x-slot>

    <div class="py-12">
        <div id='main-wrapper' class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden flex flex-col items-center h-full shadow-xl sm:rounded-lg">

                @if($consumptions->isEmpty())
                    <p class="bg-yellow-400 mx-auto w-1/2 text-center p-2 my-4 text-lg">No records found</p>
                @else
                    <x-list class="my-12 w-1/2 shadow-xl">
                        <x-list-item class="flex justify-between text-blue-700 font-bold text-lg">
                        <span>
                            Amount (lr)
                        </span>
                            <span>
                            Consumed At
                        </span>
                        </x-list-item>
                        @foreach($consumptions as $consumption)
                            <x-list-item class="flex justify-between ">
                                <span>{{number_format($consumption->amount, 2)}}</span>
                                <span>{{$consumption->consumed_at->diffForHumans()}}</span>
                            </x-list-item>
                        @endforeach
                    </x-list>
                    <div id='paginator' class="mb-4 flex justify-around">
                        <x-jet-button next-page="{{$consumptions->nextPageUrl()	}}">
                            Load next &nbsp; <em>{{$consumptions->perPage()}} records</em>
                        </x-jet-button>
                    </div>
                @endif
            </div>
        </div>

        @push('script')
            <script>
                $(function () {

                    $('#main-wrapper').click('#paginator button', function () {
                        const paginatorBtnElm = $('#paginator button')
                        const nextPageUrl = paginatorBtnElm.attr('next-page')
                        paginatorBtnElm.remove();
                        $('#main-wrapper #paginator').append(`<x-spinner/>`)
                        loadPaginatorNextPage(nextPageUrl)
                    })

                    function loadPaginatorNextPage(nextPageUrl) {
                        const urlParams = new URLSearchParams(nextPageUrl.split('?')[1]);
                        const cursor = urlParams.get('cursor');
                        const url = window.location.href + '?cursor=' + cursor;


                        $.get(url, function (data) {
                            const li = $('ul li:not(:first-of-type)', data);
                            const button = $('#paginator button', data);

                            $('ul').append(li);
                            $('#spinner-wrapper').remove();

                            if (button.attr('next-page')) {
                                $('#main-wrapper #paginator').append(button)

                            }
                        })
                    }
                })
            </script>
    @endpush

</x-app-layout>

