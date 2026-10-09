@extends('errors.layout')

@section('code', '401')
@section('title', 'Acesso não autenticado')
@section('caption', 'Identifique-se para continuar')
@section('message', 'Você precisa entrar no sistema para acessar esta página.')

@section('tips')
    <li>Faça login com seu e-mail e senha.</li>
    <li>Se ainda não tem conta, cadastre-se na página inicial.</li>
@endsection

@section('actions')
    <a href="{{ url('/login') }}" class="button">Fazer login</a>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
