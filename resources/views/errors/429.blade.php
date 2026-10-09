@extends('errors.layout')

@section('code', '429')
@section('title', 'Muitas tentativas')
@section('caption', 'Vamos com calma')
@section('message', 'Você fez muitas requisições em pouco tempo e o acesso foi pausado por alguns instantes.')

@section('icon')
    <path d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
@endsection

@section('tips')
    <li>Aguarde cerca de um minuto antes de tentar de novo.</li>
    <li>Evite clicar várias vezes no mesmo botão.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="location.reload()">Tentar novamente</button>
    <button type="button" class="button secondary" onclick="history.back()">Voltar</button>
@endsection
