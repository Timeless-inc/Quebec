@extends('errors.layout')

@section('code', '403')
@section('title', 'Acesso negado')
@section('caption', 'Área restrita')
@section('message', $exception->getMessage() ?: 'Você não tem permissão para acessar esta página.')

@section('tips')
    <li>Confira se você entrou com a conta correta.</li>
    <li>Se acredita que deveria ter acesso, procure a CRADT.</li>
@endsection
