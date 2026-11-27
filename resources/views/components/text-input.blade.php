@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-musgo/30 focus:border-esmeralda focus:ring-campus rounded-md shadow-sm']) }}>
