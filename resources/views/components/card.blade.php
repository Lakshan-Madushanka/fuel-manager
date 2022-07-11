<div {{$attributes->merge(['class' => ''])}}>
    <div class="block rounded-lg shadow-lg bg-white  text-center">
        <div {{$title->attributes->merge(['class' => 'py-3 px-6 border-b border-gray-300'])}}>
            {{$title}}
        </div>
        <div class="p-6">
            <h5 class="text-gray-900 text-xl font-medium mb-2">{{$bodyTitle ?? ''}}</h5>
            <p class="text-gray-700 text-base mb-4">
                {{$bodyContent}}
            </p>
        </div>
        <div class="py-3 px-6 border-t border-gray-300 text-gray-600">
            {{$footer ?? ''}}
        </div>
    </div>
</div>