@extends('errors.layout')

@section('code', '500')
@section('title', 'Erro interno do servidor')
@section('caption', 'O problema é do nosso lado')
@section('message', 'Algo deu errado ao processar seu pedido. Nenhuma ação sua causou este erro.')

@section('tips')
    <li>Tente novamente em instantes.</li>
    <li>Se o erro continuar, avise a CRADT informando o que você estava fazendo.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
