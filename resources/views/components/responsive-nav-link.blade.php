@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-esmeralda text-start text-base font-semibold text-floresta bg-menta/60 focus:outline-none focus:bg-menta focus:border-floresta transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-musgo hover:text-floresta hover:bg-menta/50 hover:border-musgo/30 focus:outline-none focus:text-floresta focus:bg-menta/50 focus:border-musgo/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
