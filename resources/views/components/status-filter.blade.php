@props([
    'options',
    'current' => 'todos',
    'links' => false,
])

@php
    $cores = [
        'pendente' => 'bg-amber-500',
        'em_andamento' => 'bg-blue-500',
        'em_aberto' => 'bg-blue-500',
        'deferido' => 'bg-emerald-500',
        'processados' => 'bg-emerald-500',
        'indeferido' => 'bg-red-500',
        'encaminhado' => 'bg-violet-500',
        'devolvido' => 'bg-pink-500',
        'devolvidos' => 'bg-pink-500',
    ];

    $classes = 'filter-btn inline-flex items-center gap-1.5 whitespace-nowrap rounded-lg px-2.5 py-1.5 text-sm font-medium text-gray-500 no-underline transition-colors hover:text-gray-900 hover:no-underline focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 data-[active=true]:bg-white data-[active=true]:text-gray-900 data-[active=true]:shadow-sm';
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex flex-wrap items-center gap-1 rounded-xl bg-gray-100 p-1']) }} role="group" aria-label="Filtrar requerimentos por status">
    @foreach($options as $valor => $rotulo)
        @php
            $atributos = 'class="' . $classes . '" data-status="' . e($valor) . '" data-active="' . ($current === $valor ? 'true' : 'false') . '"';
        @endphp

        @if($links)
            <a href="?status={{ $valor }}" {!! $atributos !!}>
                @isset($cores[$valor])<span class="h-2 w-2 rounded-full {{ $cores[$valor] }}"></span>@endisset
                {{ $rotulo }}
            </a>
        @else
            <button type="button" onclick="filterJustificativas('{{ $valor }}')" {!! $atributos !!}>
                @isset($cores[$valor])<span class="h-2 w-2 rounded-full {{ $cores[$valor] }}"></span>@endisset
                {{ $rotulo }}
            </button>
        @endif
    @endforeach
</div>
