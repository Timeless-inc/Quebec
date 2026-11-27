@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-sm text-esmeralda']) }}>
        {{ $status }}
    </div>
@endif
