@extends('errors.layout')

@section('code', '408')
@section('title', 'Tempo esgotado')
@section('caption', 'A resposta demorou demais')
@section('message', 'A requisição demorou mais do que o esperado e foi interrompida.')

@section('icon')
    <path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
@endsection

@section('tips')
    <li>Verifique sua conexão com a internet.</li>
    <li>Tente novamente em alguns instantes.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
    <button type="button" class="button secondary" onclick="history.back()">Voltar</button>
@endsection
