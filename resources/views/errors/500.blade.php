@extends('errors.layout')

@section('code', '500')
@section('title', 'Erro interno do servidor')
@section('caption', 'O problema é do nosso lado')
@section('message', 'Algo deu errado ao processar seu pedido. Nenhuma ação sua causou este erro.')

@section('icon')
    <path d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z" />
@endsection

@section('tips')
    <li>Tente novamente em instantes.</li>
    <li>Se o erro continuar, avise a CRADT informando o que você estava fazendo.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
