@extends('errors.layout')

@section('code', '405')
@section('title', 'Ação não permitida')
@section('caption', 'Este caminho não aceita isso')
@section('message', 'Esta página não pode ser acessada dessa forma. Isso costuma acontecer ao abrir diretamente o endereço de um formulário.')

@section('icon')
    <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
@endsection

@section('tips')
    <li>Volte e use os botões da própria tela.</li>
    <li>Evite atualizar a página logo depois de enviar um formulário.</li>
@endsection
