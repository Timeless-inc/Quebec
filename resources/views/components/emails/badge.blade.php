@props(['status'])
@php
    $normalized = str_replace([' ', '-'], '_', \Illuminate\Support\Str::ascii(mb_strtolower(trim((string) $status))));
    $colors = match ($normalized) {
        'deferido', 'finalizado' => ['#e0f1e5', '#22603b'],
        'indeferido' => ['#fbe7e7', '#9b3535'],
        'pendente', 'devolvido' => ['#fff0d5', '#825b17'],
        'encaminhado', 'reencaminhado' => ['#e7edf9', '#395a91'],
        default => ['#e8eeea', '#405a48'],
    };
    $label = in_array($normalized, ['em_aberto', 'em_analise', 'em_andamento'], true)
        ? 'Em Aberto'
        : \App\Services\StatusFormatter::format($normalized);
@endphp
<span style="display: inline-block; padding: 5px 11px; border-radius: 6px; background-color: {{ $colors[0] }}; color: {{ $colors[1] }}; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 19px; font-weight: 700;">{{ $label }}</span>
