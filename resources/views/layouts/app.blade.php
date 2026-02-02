<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mini CRM')</title>

    {{-- Vite: incluye tus assets JS/CSS (Asegúrate de tener vite configurado o reemplázalo por links a CSS si no usas Vite) --}}
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
/* Estilos para los flashes */
.flash { padding:12px; border-radius:8px; margin-bottom:12px; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
.flash-success { background:#d1fae5; color:#065f46; }   /* verde */
.flash-error   { background:#fee2e2; color:#7f1d1d; }   /* rojo */
.flash-warning { background:#fff7ed; color:#92400e; }   /* amarillo/ámbar */
.flash-info    { background:#e6f0ff; color:#063970; }   /* azul suave */

/* Transición y pequeño comportamiento */
#flash-message { transition: transform 0.2s ease, opacity 0.2s ease; }
</style>


    <style>
        /* Estilos mínimos inline para que la UI sea legible sin depender de frameworks */
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f7fafc; color:#1a202c; margin:0; padding:0;}
        .navbar { background:#0f172a; color:#fff; padding:10px 16px; }
        .nav-link { color: #cbd5e1; margin-right:12px; text-decoration:none; }
        .container { max-width: 1100px; margin:20px auto; padding:16px; background:#fff; border-radius:8px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
        table { width:100%; border-collapse:collapse; margin-top:12px;}
        th, td { padding:8px 10px; border-bottom:1px solid #e6edf3; text-align:left; }
        .btn { display:inline-block; padding:6px 10px; border-radius:6px; text-decoration:none; font-size:14px; }
        .btn-primary { background:#0ea5a3; color:#fff; }
        .btn-secondary { background:#64748b; color:#fff; }
        .btn-danger { background:#ef4444; color:#fff; }
        .flash { padding:10px; border-radius:6px; margin-bottom:12px; }
        .flash-success { background:#d1fae5; color:#065f46; }
        .flash-error { background:#fee2e2; color:#7f1d1d; }
        .filters { display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-top:8px; }
        form.inline { display:inline; }
        .muted { color:#64748b; font-size:13px; }
        .top-actions { display:flex; gap:8px; align-items:center; justify-content:space-between; }

    </style>
</head>
<body>
    <nav class="navbar">
        <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;justify-content:space-between">
            <div>
                <a href="{{ route('clients.index') }}" class="nav-link">Mini CRM</a>
                <a href="{{ route('clients.index') }}" class="nav-link">Clients</a>
                <a href="{{ route('tickets.index') }}" class="nav-link">Tickets</a>
            </div>
            <div>
                {{-- Si usas auth, muestra username / logout aquí. Para la prueba puedes dejar enlaces simples --}}
                <span class="muted">SENA - APLICASOFTWARE</span>
            </div>
        </div>
    </nav>

    <main class="container">
        {{-- Mensajes Flash (success / error) --}}
        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="flash flash-error">
                <strong>Se encontraron errores:</strong>
                <ul style="margin:6px 0 0 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- en layouts/app.blade.php, dentro del <main class="container"> justo antes de @yield('content') --}}
@include('partials.flash')


        {{-- Contenido principal de cada vista --}}
        @yield('content')
    </main>

<script>
/**
 * Toggle simple para formularios "quick"
 * No usa frameworks, solo JS vanilla
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-toggle="quick-form"]').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const form = document.getElementById(targetId);

            if (!form) return;

            form.style.display = (form.style.display === 'none' || form.style.display === '')
                ? 'block'
                : 'none';
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const flash = document.getElementById('flash-message');
    if (!flash) return;

    const closeBtn = document.getElementById('flash-close-btn');

    // Auto hide después de X ms (6000 ms = 6s). Solo si el usuario no tiene errores críticos.
    const AUTO_HIDE_MS = 6000;

    // Si hay errores de validación, NO auto-hide para que el usuario pueda leerlos
    const hasValidationErrors = {!! $errors->any() ? 'true' : 'false' !!};

    if (!hasValidationErrors) {
        // programar auto-hide
        const hideTimer = setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            setTimeout(() => { if (flash.parentNode) flash.parentNode.removeChild(flash); }, 300);
        }, AUTO_HIDE_MS);

        // permitir cancelar auto-hide si el usuario pasa el mouse
        flash.addEventListener('mouseenter', () => clearTimeout(hideTimer));
    }

    // Cerrar con el botón
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            setTimeout(() => { if (flash.parentNode) flash.parentNode.removeChild(flash); }, 220);
        });
    }
});
</script>


</body>
</html>