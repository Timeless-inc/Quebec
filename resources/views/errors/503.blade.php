@extends('errors.layout')

@section('code', '503')
@section('title', 'Sistema em manutenção')
@section('caption', 'Voltamos já')
@section('message', 'O SRE está temporariamente indisponível para manutenção.')

@section('tips')
    <li>Tente novamente em alguns minutos.</li>
    <li>Seus requerimentos e dados continuam salvos.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
@endsection
