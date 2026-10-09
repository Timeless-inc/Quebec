@extends('errors.layout')

@section('code', '403')
@section('title', 'Acesso negado')
@section('message', $exception->getMessage() ?: 'Você não tem permissão para acessar esta página. Se acredita que isso é um engano, procure a CRADT.')
