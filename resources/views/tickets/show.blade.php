@extends('layouts.app')

@section('title', 'Ticket detail')

@section('content')
    <h2>Ticket #{{ $ticket->id }}</h2>

    <div style="margin-top:12px;">
        <strong>Title:</strong> {{ $ticket->title }}<br>
        <strong>Client:</strong> {{ $ticket->client->name ?? '-' }}<br>
        <strong>Status:</strong> {{ $ticket->status }}<br>
        <strong>Priority:</strong> {{ $ticket->priority }}<br>

        @php
            $openedDisplay = '-';
            if (!empty($ticket->opened_at)) {
                try {
                    if ($ticket->opened_at instanceof \Illuminate\Support\Carbon) {
                        $openedDisplay = $ticket->opened_at->format('Y-m-d H:i');
                    } else {
                        $openedDisplay = \Illuminate\Support\Carbon::parse($ticket->opened_at)->format('Y-m-d H:i');
                    }
                } catch (\Throwable $e) {
                    $openedDisplay = '-';
                }
            }

            $closedDisplay = '-';
            if (!empty($ticket->closed_at)) {
                try {
                    if ($ticket->closed_at instanceof \Illuminate\Support\Carbon) {
                        $closedDisplay = $ticket->closed_at->format('Y-m-d H:i');
                    } else {
                        $closedDisplay = \Illuminate\Support\Carbon::parse($ticket->closed_at)->format('Y-m-d H:i');
                    }
                } catch (\Throwable $e) {
                    $closedDisplay = '-';
                }
            }
        @endphp

        <strong>Opened at:</strong> {{ $openedDisplay }}<br>
        <strong>Closed at:</strong> {{ $closedDisplay }}<br>
    </div>

    <p style="margin-top:20px;">
        <a href="{{ route('tickets.index') }}" class="btn">Back to tickets</a>
        <a href="{{ route('tickets.edit', $ticket) }}" class="btn">Edit</a>
    </p>
@endsection