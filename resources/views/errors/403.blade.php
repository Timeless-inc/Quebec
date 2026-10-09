@extends('errors.layout')

@section('code', '403')
@section('title', 'Acesso negado')
@section('caption', 'Área restrita')
@section('message', $exception->getMessage() ?: 'Você não tem permissão para acessar esta página.')

@section('icon')
    <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
@endsection

@section('tips')
    <li>Confira se você entrou com a conta correta.</li>
    <li>Se acredita que deveria ter acesso, procure a CRADT.</li>
@endsection
