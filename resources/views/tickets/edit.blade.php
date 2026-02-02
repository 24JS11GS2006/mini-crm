{{-- resources/views/tickets/edit.blade.php --}}
@extends('layouts.app')

@section('title','Edit ticket')

@section('content')
    <h2>Editar ticket #{{ $ticket->id }}</h2>

    {{-- Formulario para editar ticket: incluimos el partial tickets._form --}}
    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
        @csrf
        @method('PUT')
        @include('tickets._form', [
            'clients' => $clients,
            'ticket' => $ticket,
            'submitText' => 'Guardar ticket',
            'cancelUrl' => route('tickets.show', $ticket)
        ])
    </form>
@endsection
