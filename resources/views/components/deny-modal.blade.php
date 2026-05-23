@props([
    'requerimento',
])

<div class="modal fade" id="indeferimentoModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="indeferimentoModalLabel-{{ $requerimento->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white border-0 shadow-2xl rounded-lg overflow-hidden">
            <div class="modal-header bg-red-700 text-white border-0 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-md flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-xl font-bold mb-0" id="indeferimentoModalLabel-{{ $requerimento->id }}">Indeferir Requerimento</h5>
                        <p class="text-red-100 text-sm mb-0">#{{ $requerimento->id }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST" class="deny-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="indeferido">

                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label for="resposta_indeferimento_{{ $requerimento->id }}" class="block text-sm font-semibold text-gray-700 mb-2">Motivo do indeferimento:</label>
                        <textarea class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" id="resposta_indeferimento_{{ $requerimento->id }}" name="resposta" rows="4" placeholder="Descreva o motivo do indeferimento..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-gray-50 border-t border-gray-200/50 px-6 py-4">
                    <button type="button" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-700 text-white font-semibold rounded-md hover:bg-red-800 transition-all duration-300 shadow-sm hover:shadow-md submit-btn">
                        <i class="fas fa-check mr-2"></i>Confirmar Indeferimento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
