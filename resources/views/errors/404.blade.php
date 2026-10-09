@extends('errors.layout')

@section('code', '404')
@section('title', 'Página não encontrada')
@section('caption', 'Nada por aqui')
@section('message', 'O endereço acessado não existe ou foi movido.')

@section('tips')
    <li>Confira se o endereço foi digitado corretamente.</li>
    <li>Use o menu do sistema para chegar à página desejada.</li>
@endsection
