<div class="modal fade" id="encaminharModal-{{ $requerimento->id }}" tabindex="-1" aria-labelledby="encaminharModalLabel-{{ $requerimento->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="encaminharModalLabel-{{ $requerimento->id }}">Encaminhar Requerimento #{{ $requerimento->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('forwardings.store', $requerimento->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @php
                        // Busca todos os usuários que podem receber encaminhamentos
                        $forwardableLabels = \App\Models\Role::getAllForwardableRoleLabels();
                        $allReceivers = \App\Models\User::whereIn('role', $forwardableLabels)
                            ->orderBy('role')
                            ->orderBy('name')
                            ->get()
                            ->groupBy('role');
                    @endphp

                    @if($allReceivers->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Não há destinatários disponíveis. Verifique se existem usuários com cargos que permitem receber encaminhamentos.
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="receiver_id_fw_{{ $requerimento->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Destinatário:
                            </label>
                            <select class="form-control" id="receiver_id_fw_{{ $requerimento->id }}" name="receiver_id" required>
                                <option value="">Selecione o destinatário</option>
                                @foreach($allReceivers as $roleName => $users)
                                    <optgroup label="{{ $roleName }}">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" @if($allReceivers->isEmpty()) disabled @endif>Encaminhar</button>
                </div>
            </form>
        </div>
    </div>
</div>