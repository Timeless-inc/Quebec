<x-auth-shell title="SRE | Verificação de requerimento" photo-title="Verificação de requerimento"
    photo-text="Confira se os dados ao lado batem com o documento que você recebeu.">

    <span class="flex size-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m5 12 5 5L20 7" />
        </svg>
    </span>

    <h1 class="mt-5 text-3xl font-semibold tracking-tight text-gray-900">Requerimento registrado</h1>
    <p class="mt-2 text-base text-gray-600">Este requerimento consta no Sistema de Requerimento Estudantil.</p>

    <dl class="mt-8 divide-y divide-gray-200 text-sm">
        @foreach ([
            'Estudante' => $nomeMascarado,
            'Matrícula' => $matriculaMascarada,
            'Tipo' => $requerimento->tipoRequisicao,
            'Campus' => $requerimento->campus,
            'Situação' => $statusTexto,
            'Emitido em' => $requerimento->created_at->format('d/m/Y'),
        ] as $rotulo => $valor)
            <div class="flex justify-between gap-6 py-3">
                <dt class="text-gray-600">{{ $rotulo }}</dt>
                <dd class="text-right font-medium text-gray-900">{{ $valor }}</dd>
            </div>
        @endforeach
        <div class="flex justify-between gap-6 py-3">
            <dt class="text-gray-600">Código</dt>
            <dd class="break-all text-right font-mono text-xs text-gray-900">{{ $requerimento->key }}</dd>
        </div>
    </dl>

    <p class="mt-8 rounded-[10px] bg-gray-50 p-4 text-sm text-gray-700">
        Confira estes dados com o documento em mãos. Se algum deles for diferente, não confie no documento.
    </p>
</x-auth-shell>
