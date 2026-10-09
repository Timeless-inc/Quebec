@extends('errors.layout')

@section('code', '419')
@section('title', 'Sessão expirada')
@section('message', 'Sua sessão expirou por inatividade. Volte, atualize a página e envie o formulário novamente.')

@section('actions')
    <button type="button" class="button secondary" onclick="history.back()">Voltar</button>
    <a href="{{ url('/login') }}" class="button">Fazer login</a>
@endsection
