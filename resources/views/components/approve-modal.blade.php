@props([
    'requerimento',
])

<style>
    .form-control-sm::-webkit-file-upload-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 0.2rem;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .form-control-sm::-webkit-file-upload-button:hover {
        background-color: #0056b3;
    }

    .form-control-sm::-moz-file-upload-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.3rem 0.6rem;
        border-radius: 0.2rem;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .form-control-sm::-moz-file-upload-button:hover {
        background-color: #0056b3;
    }
</style>

<div class="modal fade" id="finalizacaoModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="finalizacaoModalLabel-{{ $requerimento->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white border-0 shadow-2xl rounded-lg overflow-hidden">
            <div class="modal-header bg-emerald-700 text-white border-0 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-xl font-bold mb-0" id="finalizacaoModalLabel-{{ $requerimento->id }}">Finalizar Requerimento</h5>
                        <p class="text-emerald-100 text-sm mb-0">#{{ $requerimento->id }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST" enctype="multipart/form-data" class="approve-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="finalizado">

                <div class="modal-body p-6">
                    <div class="mb-6">
                        <label for="resposta_finalizacao_{{ $requerimento->id }}" class="block text-sm font-semibold text-gray-700 mb-2">Resposta (opcional):</label>
                        <textarea class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" id="resposta_finalizacao_{{ $requerimento->id }}" name="resposta" rows="4" placeholder="Digite sua resposta aqui..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="anexos_finalizacao_{{ $requerimento->id }}" class="block text-sm font-semibold text-gray-700 mb-2">Anexar arquivos (opcional):</label>
                        <input type="file" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 form-control-sm transition-colors" id="anexos_finalizacao_{{ $requerimento->id }}" name="anexos_finalizacao[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/jpeg,image/png,image/webp">
                        <p class="text-xs text-gray-500 mt-2">PDF at&eacute; 5 MB. Imagens s&atilde;o otimizadas automaticamente quando necess&aacute;rio. Tipos permitidos: pdf, jpg, jpeg, png, webp.</p>
                    </div>
                </div>

                <div class="modal-footer bg-gray-50 border-t border-gray-200/50 px-6 py-4">
                    <button type="button" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-700 text-white font-semibold rounded-md hover:bg-emerald-800 transition-all duration-300 shadow-sm hover:shadow-md submit-btn">
                        <i class="fas fa-check mr-2"></i>Confirmar Finaliza&ccedil;&atilde;o
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
