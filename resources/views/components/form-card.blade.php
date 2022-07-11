@props(['title'])
<div {{$attributes->merge(['class' => 'md:mt-8 sm:mt-2 flex flex-col sm:justify-center items-center pb-4 pt-6 sm:pt-0 bg-gray-100'])}}>
    <h2 class="tracking-wider font-extrabold">
        {{ strtoupper($title) }}
    </h2>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
