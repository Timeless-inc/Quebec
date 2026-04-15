<div class="modal fade" id="reencaminharModal-{{ $forwarding->id }}" tabindex="-1" aria-labelledby="reencaminharModalLabel-{{ $forwarding->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reencaminharModalLabel-{{ $forwarding->id }}">
                    <i class="fas fa-share-square mr-2"></i>
                    Reencaminhar Requerimento #{{ $forwarding->requerimento->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('forwardings.reforward.store', $forwarding->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Informações do Requerimento -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detalhes do Requerimento
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Aluno:</strong> {{ $forwarding->requerimento->nomeCompleto }}<br>
                                    <strong>Tipo:</strong> {{ $forwarding->requerimento->tipoRequisicao }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Matrícula:</strong> {{ $forwarding->requerimento->matricula }}<br>
                                    <strong>Data:</strong> {{ $forwarding->requerimento->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Seleção de Destinatário -->
                    @php
                        // Busca todos os usuários que podem receber encaminhamentos (excluindo o usuário atual)
                        $forwardableLabels = \App\Models\Role::getAllForwardableRoleLabels();
                        $availableReceivers = \App\Models\User::whereIn('role', $forwardableLabels)
                            ->where('id', '!=', Auth::id())
                            ->orderBy('role')
                            ->orderBy('name')
                            ->get()
                            ->groupBy('role');
                    @endphp

                    @if($availableReceivers->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Não há destinatários disponíveis para reencaminhamento. Verifique se existem usuários com cargos que permitem receber encaminhamentos.
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="receiver_id_{{ $forwarding->id }}" class="form-label">
                                <i class="fas fa-users mr-2"></i>
                                Destinatário:
                            </label>
                            <select class="form-control" id="receiver_id_{{ $forwarding->id }}" name="receiver_id" required>
                                <option value="">Selecione o destinatário</option>
                                @foreach($availableReceivers as $roleName => $users)
                                    <optgroup label="{{ $roleName }}">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Mensagem Interna -->
                    <div class="mb-4">
                        <label for="internal_message_{{ $forwarding->id }}" class="form-label">
                            <i class="fas fa-comment mr-2"></i>
                            Mensagem Interna (opcional):
                        </label>
                        <textarea class="form-control" id="internal_message_{{ $forwarding->id }}" name="internal_message" rows="3"
                                  placeholder="Adicione uma mensagem interna para o destinatário..."></textarea>
                        <small class="form-text text-muted">
                            Esta mensagem será visível apenas para o destinatário do encaminhamento.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" @if($availableReceivers->isEmpty()) disabled @endif>
                        <i class="fas fa-paper-plane mr-2"></i>
                        Reencaminhar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
