<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $event = Event::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Evento criado com sucesso!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Evento removido com sucesso!');
    }

    public function update(Request $request, Event $event)
    {
        $event->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return redirect()->back()->with('success', 'Evento atualizado com sucesso!');
    }
}
