{{-- resources/views/tickets/index.blade.php --}}
@extends('layouts.app')

@section('title','Tickets')

@section('content')
    <div class="top-actions">
        <h2>Tickets</h2>
        <div>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">New Ticket (full)</a>
        </div>
    </div>

    {{-- Quick create ticket inline --}}
    <div style="margin-top:12px;padding:12px;background:#f1f5f9;border-radius:8px;">
        <strong>Quick create ticket</strong>
        <form action="{{ route('tickets.store') }}" method="POST" style="margin-top:8px;">
            @csrf
            @include('tickets._form', [
                'clients' => $clients,
                'submitText' => 'Crear ticket (rápido)',
                'cancelUrl' => route('tickets.index')
            ])
        </form>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('tickets.index') }}" style="margin-top:8px;">
        <div class="filters">
            <select name="status">
                <option value="">All statuses</option>
                <option value="open" {{ (request('status') == 'open' || (isset($status) && $status == 'open')) ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ (request('status') == 'in_progress' || (isset($status) && $status == 'in_progress')) ? 'selected' : '' }}>In progress</option>
                <option value="closed" {{ (request('status') == 'closed' || (isset($status) && $status == 'closed')) ? 'selected' : '' }}>Closed</option>
            </select>

            <select name="priority">
                <option value="">All priorities</option>
                <option value="low" {{ (request('priority') == 'low' || (isset($priority) && $priority == 'low')) ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ (request('priority') == 'medium' || (isset($priority) && $priority == 'medium')) ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ (request('priority') == 'high' || (isset($priority) && $priority == 'high')) ? 'selected' : '' }}>High</option>
            </select>

            <select name="client_id">
                <option value="">All clients</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ (request('client_id') == $c->id || (isset($clientId) && $clientId == $c->id)) ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('tickets.index') }}" class="btn">Clear</a>
        </div>
    </form>

    {{-- Tabla de tickets --}}
    <table style="margin-top:12px;">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Client</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Opened</th>
                <th>Closed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                @php
                    // Guard seguro para opened_at: puede ser Carbon o string o null
                    $openedDisplay = '-';
                    if (!empty($ticket->opened_at)) {
                        try {
                            if ($ticket->opened_at instanceof \Illuminate\Support\Carbon) {
                                $openedDisplay = $ticket->opened_at->format('Y-m-d');
                            } else {
                                // si viene como string, intentar parsear
                                $openedDisplay = \Illuminate\Support\Carbon::parse($ticket->opened_at)->format('Y-m-d');
                            }
                        } catch (\Throwable $e) {
                            $openedDisplay = '-';
                        }
                    }

                    // Guard seguro para closed_at
                    $closedDisplay = '-';
                    if (!empty($ticket->closed_at)) {
                        try {
                            if ($ticket->closed_at instanceof \Illuminate\Support\Carbon) {
                                $closedDisplay = $ticket->closed_at->format('Y-m-d');
                            } else {
                                $closedDisplay = \Illuminate\Support\Carbon::parse($ticket->closed_at)->format('Y-m-d');
                            }
                        } catch (\Throwable $e) {
                            $closedDisplay = '-';
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->client->name ?? '-' }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->priority }}</td>
                    <td>{{ $openedDisplay }}</td>
                    <td>{{ $closedDisplay }}</td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">View</a>
                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn">Edit</a>

                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline" style="display:inline-block" onsubmit="return confirm('¿Eliminar ticket?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No tickets found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $tickets->links() }}
    </div>
@endsection