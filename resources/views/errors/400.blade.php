@extends('errors.layout')

@section('code', '400')
@section('title', 'Requisição inválida')
@section('caption', 'Não entendemos o pedido')
@section('message', 'O sistema não conseguiu entender esta requisição. O link pode estar incompleto ou os dados foram enviados em um formato inesperado.')

@section('tips')
    <li>Volte para a página anterior e tente de novo.</li>
    <li>Se você digitou o endereço, confira se ele está completo.</li>
@endsection
