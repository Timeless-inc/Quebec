@extends('errors.layout')

@section('code', '408')
@section('title', 'Tempo esgotado')
@section('caption', 'A resposta demorou demais')
@section('message', 'A requisição demorou mais do que o esperado e foi interrompida.')

@section('tips')
    <li>Verifique sua conexão com a internet.</li>
    <li>Tente novamente em alguns instantes.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
    <button type="button" class="button secondary" onclick="history.back()">Voltar</button>
@endsection
