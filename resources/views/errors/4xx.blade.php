@extends('errors.layout')

@section('code', $exception->getStatusCode())
@section('title', 'Ops! Algo deu errado')
@section('caption', 'Ops!')
@section('message', 'Não conseguimos concluir o que você pediu.')

@section('tips')
    <li>Volte para a página anterior e tente de novo.</li>
    <li>Se o problema continuar, procure a CRADT.</li>
@endsection
