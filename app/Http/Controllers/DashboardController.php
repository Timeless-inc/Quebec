<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\ProfileChangeRequest;
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
        
        if ($user->role === 'Cradt' || $user->role === 'Manager') {
            $profileRequests = ProfileChangeRequest::with('user')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return view('dashboard.index', [
                'requerimentos' => $requerimentos,
                'events' => $events,
                'currentStatus' => $status,
                'profileRequests' => $profileRequests
            ]);
        } else {
            $profileRequests = ProfileChangeRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            return view('dashboard.index', [
                'requerimentos' => $requerimentos,
                'events' => $events,
                'currentStatus' => $status,
                'profileRequests' => $profileRequests
            ]);
        }
    }
}