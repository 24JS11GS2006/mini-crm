@extends('layouts.app')

@section('title','Clients')

@section('content')
    <div class="top-actions" style="align-items:flex-start;">
        <div>
            <h2>Clients</h2>
            <p class="muted">Lista de clientes. Puedes crear un cliente rápido desde aquí.</p>
        </div>

        <div style="text-align:right;">
            <a href="{{ route('clients.create') }}" class="btn btn-primary" style="margin-bottom:8px;">New Client (full form)</a>
        </div>
    </div>

<button
    type="button"
    class="btn btn-secondary"
    data-toggle="quick-form"
    data-target="quick-client-form">
    Quick create client
</button>

    {{-- Formulario inline rápido para crear cliente usando el partial --}}
    <div
    id="quick-client-form"
    style="margin-top:12px;padding:12px;background:#f1f5f9;border-radius:8px;
           display: {{ $errors->any() ? 'block' : 'none' }};">

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        @include('clients._form', [
            'submitText' => 'Crear cliente',
            'cancelUrl' => route('clients.index')
        ])
    </form>
</div>


    <hr>

    {{-- Formulario de búsqueda (GET) por nombre o documento --}}
    <form method="GET" action="{{ route('clients.index') }}" style="margin-top:12px;">
        <input type="text" name="search" placeholder="Buscar por nombre o documento" value="{{ old('search', $search ?? '') }}">
        <button class="btn btn-secondary" type="submit">Search</button>
        @if(request('search'))
            <a href="{{ route('clients.index') }}" class="btn">Clear</a>
        @endif
    </form>

    {{-- Tabla de resultados --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Document</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Tickets</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->document_number }}</td>
                    <td>{{ $client->email ?? '-' }}</td>
                    <td>{{ $client->phone ?? '-' }}</td>
                    <td>{{ $client->tickets()->count() }}</td>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary">View</a>
                        <a href="{{ route('clients.edit', $client) }}" class="btn">Edit</a>

                        {{-- Formulario para eliminar (DELETE) --}}
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente? Esta acción es irreversible.');" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No clients found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación --}}
    <div style="margin-top:12px;">
        {{ $clients->links() }}
    </div>
@endsection
