@props(['name', 'label', 'type' => 'text', 'hint' => null, 'value' => null])

{{-- Precisa estar dentro de um escopo Alpine com `errors` (loginForm ou registerForm). --}}
<div x-data="{ shown: false }">
    <label for="{{ $name }}" class="block text-sm font-semibold text-tinta">{{ $label }}</label>

    <div class="relative mt-1.5">
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
            @if ($type !== 'password') value="{{ old($name, $value) }}" @else :type="shown ? 'text' : 'password'" @endif
            :aria-invalid="errors.{{ $name }} ? 'true' : 'false'"
            aria-describedby="{{ $name }}-message"
            {{ $attributes->class(['auth-input', 'pr-12' => $type === 'password']) }}>

        @if ($type === 'password')
            <button type="button" @click="shown = !shown" :aria-pressed="shown" :aria-label="shown ? 'Ocultar senha' : 'Mostrar senha'"
                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-[10px] text-musgo transition-colors hover:text-floresta focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-campus/30">
                <svg x-show="!shown" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <svg x-show="shown" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9.9 4.24A10.4 10.4 0 0 1 12 4c6.5 0 10 8 10 8a17.6 17.6 0 0 1-2.16 3.19M6.61 6.61A17.1 17.1 0 0 0 2 12s3.5 8 10 8a9.7 9.7 0 0 0 5.39-1.61" />
                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                    <path d="m2 2 20 20" />
                </svg>
            </button>
        @endif
    </div>

    <p id="{{ $name }}-message" x-cloak x-show="errors.{{ $name }} || @js($hint)" x-text="errors.{{ $name }} || @js($hint)"
        aria-live="polite" class="mt-1.5 text-sm" :class="errors.{{ $name }} ? 'font-medium text-red-700' : 'text-musgo'"></p>
</div>
