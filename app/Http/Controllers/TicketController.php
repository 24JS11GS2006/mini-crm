<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Client;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Mostrar listado de tickets con filtros.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $priority = $request->query('priority');
        $clientId = $request->query('client_id');

        $tickets = Ticket::with('client')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($priority, fn($q) => $q->where('priority', $priority))
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->orderByDesc('opened_at')
            ->paginate(20)
            ->withQueryString();

        $clients = Client::orderBy('name')->get();

        return view('tickets.index', compact('tickets', 'clients', 'status', 'priority', 'clientId'));
    }

    /**
     * Mostrar formulario para crear ticket.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('tickets.create', compact('clients'));
    }

    /**
     * Almacenar ticket nuevo.
     */
    public function store(StoreTicketRequest $request)
{
    $validated = $request->validated();

    // Normalizar fechas antes de guardar
    $normalizedOpened = $this->normalizeDatetimeInput($validated['opened_at'] ?? null);
    $normalizedClosed = $this->normalizeDatetimeInput($validated['closed_at'] ?? null);

    // Si no se envió opened_at, establecer la fecha actual (columna NOT NULL)
    if (is_null($normalizedOpened)) {
        $normalizedOpened = \Carbon\Carbon::now();
    }

    $validated['opened_at'] = $normalizedOpened;
    $validated['closed_at'] = $normalizedClosed; // puede ser null

    $ticket = Ticket::create($validated);

    return redirect()->route('tickets.show', $ticket)
        ->with('success', 'Ticket creado correctamente.');
}

    /**
     * Mostrar detalle de un ticket.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('client');
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Mostrar formulario para editar ticket.
     */
    public function edit(Ticket $ticket)
    {
        $clients = Client::orderBy('name')->get();
        return view('tickets.edit', compact('ticket', 'clients'));
    }

    /**
     * Actualizar ticket existente.
     */
    public function update(StoreTicketRequest $request, Ticket $ticket)
{
    $validated = $request->validated();

    // Normalizar fechas recibidas
    $normalizedOpened = $this->normalizeDatetimeInput($validated['opened_at'] ?? null);
    $normalizedClosed = $this->normalizeDatetimeInput($validated['closed_at'] ?? null);

    // Decidir si el request incluyó esos campos (para no sobreescribir con null)
    $hasOpenedField = $request->has('opened_at');
    $hasClosedField = $request->has('closed_at');

    // Estado: si pasa a 'closed' y no se envió closed_at, lo ponemos ahora
    $statusSetToClosed = isset($validated['status']) && $validated['status'] === 'closed';

    // Preparamos el array final a actualizar: solo ponemos claves cuando corresponda
    $toUpdate = $validated; // empezamos con las validadas (client_id, title, etc.)

    // opened_at: actualizar solo si el request lo incluyó
    if ($hasOpenedField) {
        // Si parseo falló ($normalizedOpened === null), usamos la fecha actual para evitar NOT NULL
        $toUpdate['opened_at'] = $normalizedOpened ?? $ticket->opened_at ?? \Carbon\Carbon::now();
    } else {
        // El usuario no envió el campo: no tocar opened_at en la DB
        unset($toUpdate['opened_at']);
    }

    // closed_at: si el request incluye closed_at, aplicarlo (puede ser null)
    if ($hasClosedField) {
        $toUpdate['closed_at'] = $normalizedClosed;
    } else {
        // Si el user no envió closed_at pero el status se setea a closed ahora, asignar ahora
        if ($statusSetToClosed) {
            $toUpdate['closed_at'] = $normalizedClosed ?? \Carbon\Carbon::now();
        } else {
            // No tocar closed_at si no fue enviado y el estado no requiere cambio
            unset($toUpdate['closed_at']);
        }
    }

    DB::transaction(function () use ($ticket, $toUpdate) {
        $ticket->update($toUpdate);
    });

    return redirect()->route('tickets.show', $ticket)
        ->with('success', 'Ticket actualizado correctamente.');
}

    /**
     * Eliminar ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket eliminado correctamente.');
    }

    /**
     * Cambiar estado rápidamente (opcional).
     */
    public function changeStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:open,in_progress,closed']);

        $newStatus = $request->input('status');

        DB::transaction(function () use ($ticket, $newStatus) {
            $data = ['status' => $newStatus];

            if ($newStatus === 'closed' && $ticket->status !== 'closed') {
                $data['closed_at'] = Carbon::now();
            }

            if ($newStatus !== 'closed') {
                $data['closed_at'] = null;
            }

            $ticket->update($data);
        });

        return back()->with('success', 'Estado del ticket actualizado.');
    }

    /**
     * Helper: normaliza un input de fecha proveniente de formulario.
     * - null o '' => null
     * - formato datetime-local (Y-m-d\TH:i) => Carbon
     * - string parseable => Carbon::parse()
     * Retorna Carbon o null.
     */
    protected function normalizeDatetimeInput($value)
{
    if (is_null($value) || $value === '') {
        return null;
    }

    if ($value instanceof \DateTime || $value instanceof \Illuminate\Support\Carbon) {
        return $value;
    }

    try {
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value)) {
            return \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $value);
        }

        return \Carbon\Carbon::parse($value);
    } catch (\Throwable $e) {
        return null;
    }
}
}