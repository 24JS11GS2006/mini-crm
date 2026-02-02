{{-- resources/views/clients/create.blade.php --}}
@extends('layouts.app')

@section('title','Create client')

@section('content')
    <h2>Crear cliente</h2>

    {{-- Formulario para crear cliente: incluimos el partial clients._form --}}
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        @include('clients._form', [
            'submitText' => 'Crear cliente',
            'cancelUrl' => route('clients.index')
        ])
    </form>
@endsection
