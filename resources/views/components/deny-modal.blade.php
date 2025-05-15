@props([
'requerimento',
])
<div class="modal fade" id="indeferimentoModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="indeferimentoModalLabel-{{ $requerimento->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-lg">
            <div class="modal-header p-4 border-b border-gray-200">
                <h5 class="text-xl font-bold text-gray-800" id="indeferimentoModalLabel-{{ $requerimento->id }}">Motivo do Indeferimento - Requerimento #{{ $requerimento->id }}</h5>
            </div>
            <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="resposta" class="block text-sm font-medium text-gray-700 mb-1">Motivo do indeferimento:</label>
                        <textarea class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500" id="resposta" name="resposta" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" data-bs-dismiss="modal" title="Cancelar">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    <button type="submit" name="status" value="indeferido" class="w-8 h-8 flex items-center justify-center text-white bg-red-600 rounded-md hover:bg-red-700" title="Confirmar Indeferimento">
                        <i class="fas fa-check text-lg"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>