{{-- resources/views/tickets/create.blade.php --}}
@extends('layouts.app')

@section('title','Create ticket')

@section('content')
    <h2>Crear ticket</h2>

    {{-- Formulario para crear ticket: incluimos el partial tickets._form --}}
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        @include('tickets._form', [
            'clients' => $clients,
            'submitText' => 'Crear ticket',
            'cancelUrl' => route('tickets.index')
        ])
    </form>
@endsection
