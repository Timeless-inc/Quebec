@extends('errors.layout')

@section('code', '503')
@section('title', 'Sistema em manutenção')
@section('message', 'O SRE está temporariamente indisponível para manutenção. Tente novamente em alguns minutos.')

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
@endsection
