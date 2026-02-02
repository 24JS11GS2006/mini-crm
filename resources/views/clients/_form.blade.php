{{-- resources/views/clients/_form.blade.php --}}
{{-- Partial reutilizable para formularios de Client (create / edit).
     No incluye <form>, @csrf ni @method. Las vistas padre deben encargarse. --}}

<div style="max-width:600px;">

    {{-- Name --}}
    <div style="margin-bottom:10px;">
        <label for="name"><strong>Nombre</strong></label><br>
        <input id="name" name="name" type="text"
               value="{{ old('name', $client->name ?? '') }}"
               placeholder="Nombre completo" style="width:100%;padding:8px;">
        @error('name')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Document number --}}
    <div style="margin-bottom:10px;">
        <label for="document_number"><strong>Número de documento</strong></label><br>
        <input id="document_number" name="document_number" type="text"
               value="{{ old('document_number', $client->document_number ?? '') }}"
               placeholder="Documento (único)" style="width:100%;padding:8px;">
        @error('document_number')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div style="margin-bottom:10px;">
        <label for="email"><strong>Email (opcional)</strong></label><br>
        <input id="email" name="email" type="email"
               value="{{ old('email', $client->email ?? '') }}"
               placeholder="correo@dominio.com" style="width:100%;padding:8px;">
        @error('email')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Phone --}}
    <div style="margin-bottom:14px;">
        <label for="phone"><strong>Teléfono (opcional)</strong></label><br>
        <input id="phone" name="phone" type="text"
               value="{{ old('phone', $client->phone ?? '') }}"
               placeholder="+57 3xx xxx xxxx" style="width:100%;padding:8px;">
        @error('phone')
            <div style="color:#7f1d1d;margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>

    {{-- Botones (puedes sobreescribir o eliminar en la vista padre si quieres otro layout) --}}
    <div style="display:flex;gap:8px;">
        <button class="btn btn-primary" type="submit">{{ $submitText ?? 'Guardar' }}</button>
        <a href="{{ $cancelUrl ?? url()->previous() }}" class="btn">Cancelar</a>
    </div>
</div>