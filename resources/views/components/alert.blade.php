@props(['autoClose' => false, 'type' => 'success', 'content' => 'Action succeeded'])

@php
    $color = match ($type) {
        'info' => 'blue',
        'success' => 'green',
        'danger' => 'red',
        'default' => 'orange'
    }
@endphp

<div class="alert-wrapper">
    <div id="alert-1"
         class="flex w-1/2 mx-auto my-2 p-4 mb-4 bg-{{$color}}-100 rounded-lg dark:bg-{{$color}}-200 relative overflow-hidden"
         role="alert">
        <svg class="flex-shrink-0 w-5 h-5 text-{{$color}}-700 dark:text-{{$color}}-800" fill="currentColor"
             viewBox="0 0 20 20"
             xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                  clip-rule="evenodd"></path>
        </svg>
        <div class="ml-3 text-sm font-medium text-{{$color}}-700 dark:text-{{$color}}-800">
            {{$content}}

            {{-- <a href="#"
                class="font-semibold underline hover:text-{{$color}}-800 dark:hover:text-{{$color}}-900">example
                 link</a>--}}
        </div>
        <button id="alertClose" type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-{{$color}}-100 text-{{$color}}-500 rounded-lg focus:ring-2 focus:ring-{{$color}}-400 p-1.5 hover:bg-{{$color}}-200 inline-flex h-8 w-8 dark:bg-{{$color}}-200 dark:text-{{$color}}-600 dark:hover:bg-{{$color}}-300"
                data-dismiss-target="#alert-1" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg id="alertCloseButton" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd"></path>
            </svg>
        </button>
        <div id="alert-loading-indicator"
             class="absolute transition-all mx-auto w-full border-solid border-amber-400 border-y-4 bottom-[-4px] left-0"></div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            $('body').click('#alertCloseButton', function () {
                $('body .alert-wrapper').remove();
            })
        })

    </script>
@endpush

{{--
@if($autoClose)
    @push('script')
        <script>
            $(function () {
                const currentMillis = Date.now()
                const timeOutTime = 2000;

                const indicateLoader =
                    setInterval(function () {
                        let diff = Date.now() - currentMillis
                        let percentage = 100 - ((diff / timeOutTime) * 100)
                        console.log('diff', percentage)
                        $('#alert-loading-indicator').css('width', percentage + '%')
                    }, 1)

                setTimeout(function () {
                    clearInterval(indicateLoader)
                    $('#alert-1').remove();
                }, timeOutTime + 250)
            })
        </script>
    @endpush
@endif--}}
