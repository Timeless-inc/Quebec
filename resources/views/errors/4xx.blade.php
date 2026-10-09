@extends('errors.layout')

@section('code', $exception->getStatusCode())
@section('title', 'Ops! Algo deu errado')
@section('caption', 'Ops!')
@section('message', 'Não conseguimos concluir o que você pediu.')

@section('icon')
    <path d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
@endsection

@section('tips')
    <li>Volte para a página anterior e tente de novo.</li>
    <li>Se o problema continuar, procure a CRADT.</li>
@endsection
