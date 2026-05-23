<div class="modal fade" id="reencaminharModal-{{ $forwarding->id }}" tabindex="-1" aria-labelledby="reencaminharModalLabel-{{ $forwarding->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white border-0 shadow-2xl rounded-lg overflow-hidden">
            <div class="modal-header bg-purple-600 text-white border-0 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-md flex items-center justify-center">
                        <i class="fas fa-share-square text-white text-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-xl font-bold mb-0" id="reencaminharModalLabel-{{ $forwarding->id }}">Reencaminhar Requerimento</h5>
                        <p class="text-purple-100 text-sm mb-0">#{{ $forwarding->requerimento->id }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('forwardings.reforward.store', $forwarding->id) }}" method="POST">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-6 rounded-md border border-purple-100 bg-purple-50 p-4">
                        <h6 class="mb-3 flex items-center text-sm font-bold text-purple-800">
                            <i class="fas fa-info-circle mr-2"></i>Detalhes do Requerimento
                        </h6>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold text-purple-600">Aluno</p>
                                <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->nomeCompleto }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-purple-600">Matr&iacute;cula</p>
                                <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->matricula }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-purple-600">Tipo</p>
                                <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->tipoRequisicao }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-purple-600">Data</p>
                                <p class="text-sm font-medium text-gray-900">{{ $forwarding->requerimento->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $forwardableLabels = \App\Models\Role::getAllForwardableRoleLabels();
                        $availableReceivers = \App\Models\User::whereIn('role', $forwardableLabels)
                            ->where('id', '!=', Auth::id())
                            ->orderBy('role')
                            ->orderBy('name')
                            ->get()
                            ->groupBy('role');
                    @endphp

                    @if($availableReceivers->isEmpty())
                        <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            N&atilde;o h&aacute; destinat&aacute;rios dispon&iacute;veis para reencaminhamento. Verifique se existem usu&aacute;rios com cargos que permitem receber encaminhamentos.
                        </div>
                    @else
                        <div class="mb-6">
                            <label for="receiver_id_{{ $forwarding->id }}" class="mb-2 flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-users mr-2 text-purple-600"></i>Destinat&aacute;rio:
                            </label>
                            <select class="w-full rounded-md border border-gray-300 bg-white p-3 text-sm text-gray-800 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500" id="receiver_id_{{ $forwarding->id }}" name="receiver_id" required>
                                <option value="">Selecione o destinat&aacute;rio</option>
                                @foreach($availableReceivers as $roleName => $users)
                                    <optgroup label="{{ $roleName }}">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="internal_message_{{ $forwarding->id }}" class="mb-2 flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-comment mr-2 text-purple-600"></i>Mensagem Interna (opcional):
                        </label>
                        <textarea class="w-full rounded-md border border-gray-300 p-3 text-sm text-gray-800 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500" id="internal_message_{{ $forwarding->id }}" name="internal_message" rows="3" placeholder="Adicione uma mensagem interna para o destinatario..."></textarea>
                        <p class="mt-2 text-xs text-gray-500">Esta mensagem ser&aacute; vis&iacute;vel apenas para o destinat&aacute;rio do encaminhamento.</p>
                    </div>
                </div>

                <div class="modal-footer bg-gray-50 border-t border-gray-200/50 px-6 py-4">
                    <button type="button" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition-all duration-300 shadow-sm hover:shadow-md" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-md hover:bg-purple-700 transition-all duration-300 shadow-sm hover:shadow-md disabled:cursor-not-allowed disabled:opacity-60" @if($availableReceivers->isEmpty()) disabled @endif>
                        <i class="fas fa-paper-plane mr-2"></i>Reencaminhar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
