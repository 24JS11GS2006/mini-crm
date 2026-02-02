@extends('layouts.app')

@section('title', 'Client detail')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center">
        <h2>Client: {{ $client->name }}</h2>
        <div>
            <a href="{{ route('clients.edit', $client) }}" class="btn">Edit client</a>
            <a href="{{ route('tickets.create') }}?client_id={{ $client->id }}" class="btn btn-primary">New Ticket (full)</a>
        </div>
    </div>

    <div style="margin-top:12px;">
        <strong>Document:</strong> {{ $client->document_number }}<br>
        <strong>Email:</strong> {{ $client->email ?? '-' }}<br>
        <strong>Phone:</strong> {{ $client->phone ?? '-' }}<br>
        <small class="muted">Created at: {{ $client->created_at->format('Y-m-d H:i') }}</small>
    </div>

    <hr>

    <button
    type="button"
    class="btn btn-secondary"
    data-toggle="quick-form"
    data-target="quick-ticket-form">
    Quick create ticket
</button>


    {{-- Quick create ticket: formulario inline para crear ticket para este cliente usando el partial --}}
    <div style="margin-bottom:18px;padding:12px;background:#f1f5f9;border-radius:8px;">
        <strong>Quick create ticket for {{ $client->name }}</strong>

        <div
    id="quick-ticket-form"
    style="margin-top:12px;padding:12px;background:#f1f5f9;border-radius:8px;
           display: {{ $errors->any() ? 'block' : 'none' }};">

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        @include('tickets._form', [
            'clients' => collect([$client]),
            'submitText' => 'Crear ticket',
            'cancelUrl' => route('clients.show', $client)
        ])
    </form>
</div>

    </div>

    <h3>Tickets</h3>

    {{-- Lista paginada de tickets del cliente --}}
    @if($tickets->count())
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Opened</th>
                    <th>Closed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->status }}</td>
                        <td>{{ $ticket->priority }}</td>
                        <td>{{ $ticket->opened_at ? $ticket->opened_at->format('Y-m-d') : '-' }}</td>
                        <td>{{ $ticket->closed_at ? $ticket->closed_at->format('Y-m-d') : '-' }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">View</a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $tickets->links() }}
        </div>
    @else
        <p>No tickets for this client yet.</p>
    @endif

    <p style="margin-top:20px;">
        <a href="{{ route('clients.index') }}" class="btn">Back to clients</a>
    </p>
@endsection