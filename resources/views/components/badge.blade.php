@props(['type' => 'success', 'color', 'content' => ''])

@php
    $bgColor = $color ?? match ($type) {
        'info' => 'bg-blue-400',
        'primary' => 'bg-blue-600',
        'danger' => 'bg-red-600',
        'warning' => 'bg-orange-600',
        'special' => 'bg-green-600',
        default => 'bg-orange-600'
    }
@endphp

<span {{$attributes->merge(['class' => $bgColor." text-xs inline-block py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold  text-white rounded-full"])}}>
    {{$content}} {{$slot}}
</span>
