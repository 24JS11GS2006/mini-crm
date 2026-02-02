<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Mostrar listado de clientes con búsqueda y paginación.
     *
     * - Soporta búsqueda por nombre o número de documento a través del query param "search".
     * - Ordena por nombre ascendente.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $clients = Client::query()
            ->when($search, function ($query, $search) {
                // Buscar por nombre o por documento (LIKE)
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Retornar vista (clients.index) con la colección paginada
        return view('clients.index', compact('clients', 'search'));
    }

    /**
     * Mostrar formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Almacenar un cliente recién creado en la base de datos.
     *
     * - Usa StoreClientRequest para validación.
     * - Redirige a index con mensaje flash.
     */
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        $client = Client::create($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Mostrar detalle de un cliente, incluyendo sus tickets asociados.
     *
     * - Carga los tickets con paginación para evitar sobrecargar la vista.
     */
    public function show(Client $client)
    {
        // Eager load tickets paginados (alternativa: simplePaginate)
        $tickets = $client->tickets()->orderByDesc('opened_at')->paginate(10);

        return view('clients.show', compact('client', 'tickets'));
    }

    /**
     * Mostrar formulario para editar un cliente.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Actualizar los datos de un cliente.
     *
     * - Usa StoreClientRequest (que debe contemplar la regla unique con exclusión del id).
     * - Redirige al detalle con mensaje.
     */
    public function update(StoreClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar un cliente.
     *
     * - La relación tickets debe manejarse por cascadeOnDelete en la migración.
     * - Recomiendo confirmar en frontend antes de llamar a esta ruta.
     */
    public function destroy(Client $client)
    {
        // opcional: podrías comprobar si tiene tickets y evitar borrado si quieres
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}