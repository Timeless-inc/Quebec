@props([
    'values' => [],
    'inputClass' => 'block w-full rounded-md border-gray-300 shadow-xs focus:border-indigo-300 focus:ring-3 focus:ring-indigo-200/50',
])

@php
    $matriculas = (array) old('matriculas_adicionais', $values);
@endphp

<div data-matriculas-adicionais>
    <div class="space-y-2" data-lista>
        @foreach($matriculas as $indice => $matricula)
            <div data-item>
                <div class="flex gap-2">
                    <input type="text" name="matriculas_adicionais[]" value="{{ $matricula }}" placeholder="Matrícula adicional" aria-label="Matrícula adicional" class="{{ $inputClass }}">
                    <button type="button" data-remover class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-700 bg-red-50 border border-red-200 hover:bg-red-100">
                        Remover
                    </button>
                </div>
                @error("matriculas_adicionais.{$indice}")
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>

    <button type="button" data-adicionar class="mt-2 inline-flex items-center text-sm font-medium text-emerald-700 hover:text-emerald-800">
        + Adicionar matrícula
    </button>

    <template>
        <div data-item>
            <div class="flex gap-2">
                <input type="text" name="matriculas_adicionais[]" placeholder="Matrícula adicional" aria-label="Matrícula adicional" class="{{ $inputClass }}">
                <button type="button" data-remover class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-700 bg-red-50 border border-red-200 hover:bg-red-100">
                    Remover
                </button>
            </div>
        </div>
    </template>
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-matriculas-adicionais]').forEach(function(container) {
                const lista = container.querySelector('[data-lista]');
                const modelo = container.querySelector('template');

                container.querySelector('[data-adicionar]').addEventListener('click', function() {
                    lista.appendChild(modelo.content.cloneNode(true));
                    lista.lastElementChild.querySelector('input').focus();
                });

                lista.addEventListener('click', function(e) {
                    const botao = e.target.closest('[data-remover]');

                    if (botao) {
                        botao.closest('[data-item]').remove();
                    }
                });
            });
        });
    </script>
@endonce
