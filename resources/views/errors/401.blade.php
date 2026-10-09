@extends('errors.layout')

@section('code', '401')
@section('title', 'Acesso não autenticado')
@section('caption', 'Identifique-se para continuar')
@section('message', 'Você precisa entrar no sistema para acessar esta página.')

@section('icon')
    <path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
@endsection

@section('tips')
    <li>Faça login com seu e-mail e senha.</li>
    <li>Se ainda não tem conta, cadastre-se na página inicial.</li>
@endsection

@section('actions')
    <a href="{{ url('/login') }}" class="button">Fazer login</a>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
