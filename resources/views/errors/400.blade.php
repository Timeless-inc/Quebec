@extends('errors.layout')

@section('code', '400')
@section('title', 'Requisição inválida')
@section('caption', 'Não entendemos o pedido')
@section('message', 'O sistema não conseguiu entender esta requisição. O link pode estar incompleto ou os dados foram enviados em um formato inesperado.')

@section('icon')
    <path d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
@endsection

@section('tips')
    <li>Volte para a página anterior e tente de novo.</li>
    <li>Se você digitou o endereço, confira se ele está completo.</li>
@endsection
