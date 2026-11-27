@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-tinta']) }}>
    {{ $value ?? $slot }}
</label>
