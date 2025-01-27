<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JustificativaController extends Controller
{
    public function index()
{
    $justificativas = Justificativa::all();
    return view('cradt.index', ['justificativas' => $justificativas]);
}
    public function updateStatus(Request $request, $id)
{
    $justificativa = Justificativa::findOrFail($id);
    $justificativa->andamento = $request->status;
    $justificativa->save();

    return response()->json(['success' => true]);
}

}


