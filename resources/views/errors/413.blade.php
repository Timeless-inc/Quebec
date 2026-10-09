@extends('errors.layout')

@section('code', '413')
@section('title', 'Arquivo muito grande')
@section('caption', 'Passou do limite de envio')
@section('message', 'Os arquivos enviados ultrapassam o tamanho máximo aceito pelo sistema.')

@section('icon')
    <path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
@endsection

@section('tips')
    <li>Envie imagens de até 5 MB e PDFs de até 2 MB.</li>
    <li>Reduza a resolução da foto ou comprima o PDF antes de anexar.</li>
    <li>Se houver vários anexos, tente enviar menos arquivos por vez.</li>
@endsection

@section('actions')
    <button type="button" class="button" onclick="history.back()">Voltar e corrigir</button>
    <a href="{{ url('/') }}" class="button secondary">Página inicial</a>
@endsection
