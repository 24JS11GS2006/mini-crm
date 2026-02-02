{{-- resources/views/partials/flash.blade.php --}}
{{-- Partial para mostrar mensajes flash (success / error / info / warning).
     Comentarios en español; variables/keys en English (session keys).
     Muestra errores de validación resumidos cuando existan. --}}

@php
    // Mapeo de tipos a clases (puedes expandirlo si quieres)
    $types = [
        'success' => 'flash-success',
        'error' => 'flash-error',
        'warning' => 'flash-warning',
        'info' => 'flash-info',
    ];

    // Buscar la primera sesión con alguno de los tipos
    $flashType = null;
    $flashMessage = null;
    foreach (array_keys($types) as $t) {
        if (session()->has($t)) {
            $flashType = $t;
            $flashMessage = session($t);
            break;
        }
    }
@endphp

@if($flashType || $errors->any())
    <div id="flash-message" class="flash {{ $flashType ? $types[$flashType] : 'flash-error' }}" role="alert" style="position:relative;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
            <div style="flex:1;">
                {{-- Título corto según tipo --}}
                <strong style="display:block;margin-bottom:6px;">
                    @if($flashType === 'success') Éxito
                    @elseif($flashType === 'warning') Atención
                    @elseif($flashType === 'info') Información
                    @else Error
                    @endif
                </strong>

                {{-- Mensaje de sesión (si lo hay) --}}
                @if($flashMessage)
                    <div>{!! nl2br(e($flashMessage)) !!}</div>
                @endif

                {{-- Si hay errores de validación, listarlos compactamente --}}
                @if($errors->any())
                    <div style="margin-top:8px;">
                        <strong>Errores:</strong>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Botón cerrar --}}
            <button id="flash-close-btn" type="button" aria-label="Cerrar" style="background:none;border:none;cursor:pointer;font-weight:bold;font-size:16px;color:inherit;">
                &times;
            </button>
        </div>
    </div>
@endif