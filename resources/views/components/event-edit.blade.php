<div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-labelledby="editEventModalLabel{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('events.update', $event->id) }}" method="POST" class="event-edit-form">
                @csrf
                @method('PUT')
                <div class="modal-header bg-{{ $event->is_exception ? 'yellow' : 'blue' }}-600 text-white">
                    <h5 class="modal-title flex items-center" id="editEventModalLabel{{ $event->id }}">
                        <i class="fas {{ $event->is_exception ? 'fa-exclamation-triangle' : 'fa-edit' }} mr-2"></i>
                        Editar {{ $event->is_exception ? 'Evento de Exceção' : 'Evento' }}
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-gray-50 p-4">
                    @if($event->is_exception)
                    <div class="alert alert-warning mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Este é um evento de exceção para um usuário específico.</span>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <label for="requisition_type_id{{ $event->id }}" class="form-label font-medium text-gray-700 mb-1 block">
                            <i class="fas fa-list-alt mr-1"></i> Tipo de Requerimento
                        </label>
                        <select class="form-select w-full py-2 px-3 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" id="requisition_type_id{{ $event->id }}" disabled>
                            @php
                                $appController = app('App\Http\Controllers\ApplicationController');
                                $tiposRequisicao = $appController->getTiposRequisicao();
                            @endphp
                            
                            @foreach($tiposRequisicao as $id => $tipo)
                                <option value="{{ $id }}" {{ $event->requisition_type_id == $id ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="requisition_type_id" value="{{ $event->requisition_type_id }}">
                        <small class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            O tipo de requerimento não pode ser alterado depois da criação.
                        </small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="title{{ $event->id }}" class="form-label font-medium text-gray-700 mb-1 block">
                            <i class="fas fa-heading mr-1"></i> Título do Evento
                        </label>
                        <input 
                            type="text" 
                            class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 focus:border-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 transition-colors" 
                            id="title{{ $event->id }}" 
                            name="title" 
                            value="{{ $event->title }}" 
                            required
                        >
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="start_date{{ $event->id }}" class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-calendar-day mr-1"></i> Data de Início
                            </label>
                            <input 
                                type="date" 
                                class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 focus:border-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 transition-colors" 
                                id="start_date{{ $event->id }}" 
                                name="start_date" 
                                value="{{ $event->start_date->format('Y-m-d') }}" 
                                required
                            >
                        </div>
                        <div class="mb-4">
                            <label for="end_date{{ $event->id }}" class="form-label font-medium text-gray-700 mb-1 block">
                                <i class="fas fa-calendar-check mr-1"></i> Data de Término
                            </label>
                            <input 
                                type="date" 
                                class="form-control w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 focus:border-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 transition-colors" 
                                id="end_date{{ $event->id }}" 
                                name="end_date" 
                                value="{{ $event->end_date->format('Y-m-d') }}" 
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="mt-4 mb-2">
                        <div class="bg-white p-3 rounded-md border border-gray-200 hover:bg-{{ $event->is_exception ? 'yellow' : 'blue' }}-50 hover:border-{{ $event->is_exception ? 'yellow' : 'blue' }}-200 transition-colors cursor-pointer group" data-toggle="collapse" data-target="#eventDetails{{ $event->id }}" aria-expanded="false" aria-controls="eventDetails{{ $event->id }}">
                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer w-full" for="eventIsActive{{ $event->id }}">
                                    <div class="mr-3">
                                        <i class="fas {{ $event->is_active ? 'fa-toggle-on text-green-500' : 'fa-toggle-off text-gray-500' }} group-hover:text-{{ $event->is_exception ? 'yellow' : 'blue' }}-500 transition-colors text-xl toggle-icon-{{ $event->id }}"></i>
                                    </div>
                                    <div>
                                        <span class="font-medium">{{ $event->is_active ? 'Evento ativado' : 'Evento desativado' }}</span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $event->is_active ? 'Este evento está disponível para os alunos' : 'Este evento está indisponível para os alunos' }}</p>
                                    </div>
                                </label>
                                <input class="form-check-input sr-only" type="checkbox" name="is_active" id="eventIsActive{{ $event->id }}" {{ $event->is_active ? 'checked' : '' }} value="1">
                                <div class="ml-2 {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-xs font-medium px-2.5 py-0.5 rounded-full toggle-status-{{ $event->id }}">
                                    {{ $event->is_active ? 'Ativado' : 'Desativado' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-100 flex justify-between">
                    @if($event->is_exception && isset($event->exceptionUser))
                        <span class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-user mr-1"></i> 
                            Exceção para: {{ $event->exceptionUser->name }}
                        </span>
                    @else
                        <span></span>
                    @endif
                    <div>
                        <button type="button" class="btn btn-outline-secondary mr-2 hover:bg-gray-200 transition-colors" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn bg-{{ $event->is_exception ? 'yellow' : 'blue' }}-600 text-white hover:bg-{{ $event->is_exception ? 'yellow' : 'blue' }}-700 transition-colors submit-btn">
                            <i class="fas fa-save mr-1"></i> Atualizar Evento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
