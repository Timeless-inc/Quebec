<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventController extends Controller
{
    public function store(Request $request)
    {
        // Log inicial com todos os dados recebidos
        Log::info('Tentativa de criação de evento', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $validated = $request->validate([
                'requisition_type_id' => 'required|integer',
                'title' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_active' => 'sometimes|boolean',
            ]);

            Log::info('Dados validados', $validated);

            // Preencher título se estiver vazio
            if (empty($validated['title'])) {
                $applicationController = app('App\Http\Controllers\ApplicationController');
                $tiposRequisicao = $applicationController->getTiposRequisicao();
                $validated['title'] = $tiposRequisicao[$validated['requisition_type_id']] ?? "Evento #" . $validated['requisition_type_id'];
                Log::info('Título gerado automaticamente', ['title' => $validated['title']]);
            }

            // Converter checkbox em bool
            $validated['is_active'] = isset($request->is_active) ? true : false;

            // Adicionar usuário que criou o evento
            $validated['created_by'] = Auth::id();

            Log::info('Antes de criar o evento', $validated);

            $event = Event::create($validated);

            Log::info('Evento criado com sucesso', ['event_id' => $event->id]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar evento', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao criar o evento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Event $event)
    {
        Log::info('Tentativa de atualização de evento', [
            'event_id' => $event->id,
            'request_data' => $request->all()
        ]);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_active' => 'sometimes|boolean',
            ]);

            // Converter checkbox em bool
            $validated['is_active'] = isset($request->is_active) ? true : false;

            $event->update($validated);

            Log::info('Evento atualizado com sucesso', ['event_id' => $event->id]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar evento', [
                'event_id' => $event->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao atualizar o evento: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        try {
            $eventId = $event->id;
            $event->delete();

            Log::info('Evento excluído com sucesso', ['event_id' => $eventId]);

            $this->updateAvailableRequisitionTypes();

            return redirect()->back()->with('success', 'Evento excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir evento', [
                'event_id' => $event->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erro ao excluir o evento: ' . $e->getMessage());
        }
    }

    // Este é o método que estava faltando
    private function updateAvailableRequisitionTypes()
    {
        try {
            $activeEvents = Event::where('is_active', true)
                ->whereDate('end_date', '>=', now())
                ->get();

            $eventTypes = $activeEvents->pluck('requisition_type_id')->toArray();

            Log::info('Atualizando cache de tipos de requisição disponíveis', ['types' => $eventTypes]);

            // Armazenar em cache por 1 hora
            Cache::put('event_requisition_types', $eventTypes, 3600);

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cache de tipos de requisição', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    public function cleanupExpiredEvents()
    {
        $deletedCount = Event::removeExpiredEvents();

        $this->updateAvailableRequisitionTypes();

        return response()->json([
            'message' => "Removed {$deletedCount} expired events",
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }
}
