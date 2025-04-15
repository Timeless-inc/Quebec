<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $request = request();
        
        $status = $request->input('status', 'todos');
        
        $query = ApplicationRequest::query();
        
        if ($user->cpf) {
            $query->where('cpf', $user->cpf);
        } elseif ($user->email) {
            $query->where('email', $user->email);
        }
        
        if ($status && $status !== 'todos') {
            if ($status === 'em_aberto') {
                $query->whereIn('status', ['em_andamento', 'pendente']);
            } else {
                $query->where('status', $status);
            }
        }
        
        $requerimentos = $query->latest()->paginate(10);
        
        $requerimentos->appends(['status' => $status]);
        
        $events = Event::orderBy('start_date')->get();
        
        return view('dashboard.index', [
            'requerimentos' => $requerimentos,
            'events' => $events,
            'currentStatus' => $status
        ]);
    }
}