@extends('errors.layout')

@section('code', '405')
@section('title', 'Ação não permitida')
@section('caption', 'Este caminho não aceita isso')
@section('message', 'Esta página não pode ser acessada dessa forma. Isso costuma acontecer ao abrir diretamente o endereço de um formulário.')

@section('tips')
    <li>Volte e use os botões da própria tela.</li>
    <li>Evite atualizar a página logo depois de enviar um formulário.</li>
@endsection
