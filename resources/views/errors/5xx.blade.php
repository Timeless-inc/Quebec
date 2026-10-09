@extends('errors.layout')

@section('code', $exception->getStatusCode())
@section('title', 'Ops! Algo deu errado')
@section('caption', 'Ops!')
@section('message', 'O sistema encontrou um problema e não conseguiu responder agora.')

@section('tips')
    <li>Tente novamente em instantes.</li>
    <li>Se o erro continuar, avise a CRADT.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
