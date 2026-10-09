@extends('errors.layout')

@section('code', '422')
@section('title', 'Dados inválidos')
@section('caption', 'Confira as informações')
@section('message', 'Alguns dados enviados não puderam ser aceitos pelo sistema.')

@section('tips')
    <li>Volte e revise os campos do formulário.</li>
    <li>Confira os campos obrigatórios e os formatos de CPF, datas e anexos.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="history.back()">Voltar e corrigir</button>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
