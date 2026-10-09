@extends('errors.layout')

@section('code', '404')
@section('title', 'Página não encontrada')
@section('caption', 'Nada por aqui')
@section('message', 'O endereço acessado não existe ou foi movido.')

@section('icon')
    <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
@endsection

@section('tips')
    <li>Confira se o endereço foi digitado corretamente.</li>
    <li>Use o menu do sistema para chegar à página desejada.</li>
@endsection
