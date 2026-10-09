@extends('errors.layout')

@section('code', '413')
@section('title', 'Arquivo muito grande')
@section('caption', 'Passou do limite de envio')
@section('message', 'Os arquivos enviados ultrapassam o tamanho máximo aceito pelo sistema.')

@section('tips')
    <li>Envie imagens de até 5 MB e PDFs de até 2 MB.</li>
    <li>Reduza a resolução da foto ou comprima o PDF antes de anexar.</li>
    <li>Se houver vários anexos, tente enviar menos arquivos por vez.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="history.back()">Voltar e corrigir</button>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
