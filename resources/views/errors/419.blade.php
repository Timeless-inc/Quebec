@extends('errors.layout')

@section('code', '419')
@section('title', 'Sessão expirada')
@section('caption', 'Você ficou um tempo sem atividade')
@section('message', 'Por segurança, sua sessão expirou antes de o formulário ser enviado.')

@section('tips')
    <li>Volte, atualize a página e envie o formulário novamente.</li>
    <li>Se o problema continuar, entre de novo no sistema.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="history.back()">Voltar e corrigir</button>
    <a href="{{ url('/login') }}" class="button secondary">Fazer login</a>
@endsection
