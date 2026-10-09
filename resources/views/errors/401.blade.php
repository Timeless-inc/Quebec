@extends('errors.layout')

@section('code', '401')
@section('title', 'Acesso não autenticado')
@section('message', 'Você precisa entrar no sistema para acessar esta página.')

@section('actions')
    <a href="{{ url('/login') }}" class="button">Fazer login</a>
@endsection
