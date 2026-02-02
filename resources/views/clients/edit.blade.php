{{-- resources/views/clients/edit.blade.php --}}
@extends('layouts.app')

@section('title','Edit client')

@section('content')
    <h2>Editar cliente</h2>

    {{-- Formulario para editar cliente: incluimos el partial clients._form --}}
    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        @include('clients._form', [
            'client' => $client,
            'submitText' => 'Guardar cambios',
            'cancelUrl' => route('clients.show', $client)
        ])
    </form>
@endsection
