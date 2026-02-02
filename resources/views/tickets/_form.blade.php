{{-- resources/views/tickets/_form.blade.php --}}
{{-- Partial reutilizable para formularios de Ticket (create / edit).
     Requiere que la vista padre provea $clients (colección de Client).
     No incluye <form>, @csrf ni @method. --}}

<div style="max-width:800px;">

    {{-- Cliente --}}
    <div style="margin-bottom:10px;">
        <label for="client_id"><strong>Cliente</strong></label><br>
        <select id="client_id" name="client_id" style="width:100%;padding:8px;">
            <option value="">-- Seleccione un cliente --</option>
            @foreach($clients as $c)
                <option value="{{ $c->id }}"
                    {{ (string) old('client_id', isset($ticket) ? (string)$ticket->client_id : (string)request('client_id', '')) === (string)$c->id ? 'selected' : '' }}>
                    {{ $c->name }} ({{ $c->document_number }})
                </option>
            @endforeach
        </select>
        @error('client_id')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Title --}}
    <div style="margin-bottom:10px;">
        <label for="title"><strong>Título</strong></label><br>
        <input id="title" name="title" type="text"
               value="{{ old('title', $ticket->title ?? '') }}"
               placeholder="Breve título del problema" style="width:100%;padding:8px;">
        @error('title')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Description --}}
    <div style="margin-bottom:10px;">
        <label for="description"><strong>Descripción (opcional)</strong></label><br>
        <textarea id="description" name="description" rows="5" placeholder="Descripción detallada" style="width:100%;padding:8px;">{{ old('description', $ticket->description ?? '') }}</textarea>
        @error('description')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status --}}
    <div style="margin-bottom:10px;">
        <label for="status"><strong>Estado</strong></label><br>
        <select id="status" name="status" style="width:100%;padding:8px;">
            <option value="open" {{ old('status', $ticket->status ?? 'open') === 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ old('status', $ticket->status ?? '') === 'in_progress' ? 'selected' : '' }}>In progress</option>
            <option value="closed" {{ old('status', $ticket->status ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        @error('status')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Priority --}}
    <div style="margin-bottom:10px;">
        <label for="priority"><strong>Prioridad</strong></label><br>
        <select id="priority" name="priority" style="width:100%;padding:8px;">
            <option value="low" {{ old('priority', $ticket->priority ?? 'medium') === 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ old('priority', $ticket->priority ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ old('priority', $ticket->priority ?? '') === 'high' ? 'selected' : '' }}>High</option>
        </select>
        @error('priority')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Opened at --}}
    <div style="margin-bottom:10px;">
        <label for="opened_at"><strong>Fecha de apertura (opcional)</strong></label><br>
        <input id="opened_at" name="opened_at" type="datetime-local"
               value="{{ old('opened_at', isset($ticket) && $ticket->opened_at ? $ticket->opened_at->format('Y-m-d\TH:i') : '') }}"
               style="width:100%;padding:8px;">
        <small class="muted">Si se deja vacío, el sistema establecerá la fecha actual.</small>
        @error('opened_at')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Closed at --}}
    <div style="margin-bottom:14px;">
        <label for="closed_at"><strong>Fecha de cierre (opcional)</strong></label><br>
        <input id="closed_at" name="closed_at" type="datetime-local"
               value="{{ old('closed_at', isset($ticket) && $ticket->closed_at ? $ticket->closed_at->format('Y-m-d\TH:i') : '') }}"
               style="width:100%;padding:8px;">
        <small class="muted">Si el estado es 'closed' y dejas vacío, el sistema establecerá la fecha actual.</small>
        @error('closed_at')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Botones (se pueden sobreescribir desde la vista padre) --}}
    <div style="display:flex;gap:8px;">
        <button class="btn btn-primary" type="submit">{{ $submitText ?? 'Guardar' }}</button>
        <a href="{{ $cancelUrl ?? url()->previous() }}" class="btn">Cancelar</a>
    </div>
</div>