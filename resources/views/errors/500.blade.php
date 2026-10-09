@extends('errors.layout')

@section('code', '500')
@section('title', 'Erro interno do servidor')
@section('message', 'Algo deu errado do nosso lado. Tente novamente em instantes; se o problema continuar, avise a CRADT.')

@section('actions')
    <button type="button" class="button secondary" onclick="location.reload()">Tentar novamente</button>
    <a href="{{ url('/') }}" class="button">Página inicial</a>
@endsection
