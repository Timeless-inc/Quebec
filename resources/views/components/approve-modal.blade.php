@props([
'requerimento',
])
<div class="modal fade" id="finalizacaoModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="finalizacaoModalLabel-{{ $requerimento->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-lg shadow-lg">
                    <div class="modal-header p-4 border-b border-gray-200">
                        <h5 class="text-xl font-bold text-gray-800" id="finalizacaoModalLabel-{{ $requerimento->id }}">Finalizar Requerimento #{{ $requerimento->id }}</h5>
                        <button type="button" class="text-gray-400 hover:text-gray-600" data-bs-dismiss="modal" aria-label="Close">
                            <span class="text-2xl">×</span>
                        </button>
                    </div>
                    <form action="{{ route('application.updateStatus', $requerimento->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label for="resposta" class="block text-sm font-medium text-gray-700 mb-1">Resposta (opcional):</label>
                                <textarea class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" id="resposta" name="resposta" rows="4"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="anexos_finalizacao" class="block text-sm font-medium text-gray-700 mb-1">Anexar arquivos (opcional):</label>
                                <input type="file" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" id="anexos_finalizacao" name="anexos_finalizacao[]" multiple>
                                <p class="text-xs text-gray-500 mt-1">Formatos aceitos: PDF, JPG, PNG (máx. 2MB por arquivo)</p>
                            </div>
                        </div>
                        <div class="modal-footer p-4 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" data-bs-dismiss="modal" title="Cancelar">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                            <button type="submit" name="status" value="finalizado" class="w-8 h-8 flex items-center justify-center text-white bg-green-600 rounded-md hover:bg-green-700" title="Confirmar Finalização">
                                <i class="fas fa-check text-lg"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>