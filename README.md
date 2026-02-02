# Mini CRM — Laravel 12

Aplicación sencilla para gestionar clientes y tickets de soporte : CRUD de clientes, tickets con estado/priority, filtros, trazabilidad de fechas y seeders para datos de prueba.

---

## ✅ Tecnologías

* Laravel 12
* PHP 8.4 (recomendado)
* Composer 2.x
* Node (solo si trabajas assets / Vite) — Node 18+ (Node 25 funciona, pero si hay incompatibilidades usa Node LTS)
* Base de datos: SQLite (fácil para pruebas) o MySQL 8+

---

## 📋 ¿Qué incluye este repo?

* Migrations, Models (Client, Ticket) y relaciones Eloquent.
* Form Requests para validación.
* Controllers para CRUD (ClientController, TicketController).
* Vistas Blade (layout y vistas CRUD).
* Factories + Seeder (`DatabaseSeeder`) para generar datos de ejemplo.
* Partials reutilizables para formularios y flashes.

---

## Requisitos (local)

* PHP >= 8.4 con extensiones: `pdo`, `pdo_sqlite` (si usas SQLite) o `pdo_mysql` (si usas MySQL), `mbstring`, `openssl`, `tokenizer`, `filesystem`.
* Composer instalado.
* (Opcional) Node + npm si vas a compilar assets.

---

## Instalación (pasos exactos)

Ejecuta desde la raíz del proyecto (donde está `artisan`):

1. Clona el repositorio:

```bash
git clone <URL_DEL_REPO>
cd <nombre-del-repo>
```

2. Instala dependencias PHP:

```bash
composer install
```

3. (Si usas assets) instala dependencias JS:

```bash
npm install
# y para desarrollo:
npm run dev
```

4. Copia el `.env` de ejemplo y genera key:

```bash
cp .env.example .env
php artisan key:generate
```

5. Si usas **SQLite** (recomendado en pruebas locales):

```bash
# crear archivo sqlite vacío
touch database/database.sqlite
# en .env configura:
# DB_CONNECTION=sqlite
# (o edítalo manualmente)
```

Si usas **MySQL**, edita `.env` con tus credenciales `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

6. Migrar y seedear:

```bash
php artisan migrate --seed
# o si quieres recrear todo:
php artisan migrate:fresh --seed
```

7. Levantar servidor de desarrollo:

```bash
php artisan serve
# luego abrir: http://127.0.0.1:8000
```

---

## Datos de prueba

El seeder crea clientes y tickets de ejemplo (ej.: 20 clientes y varios tickets por cliente). Revisa `database/seeders/DatabaseSeeder.php`.

Para crear un usuario demo (si agregas autenticación) puedes crear uno con tinker:

```bash
php artisan tinker
>>> \App\Models\User::factory()->create(['email' => 'demo@example.com', 'password' => bcrypt('secret')]);
```

> En este proyecto básico **las rutas se dejaron públicas** para facilitar la prueba técnica. Si quieres protegerlas con autenticación, instala Breeze/Jetstream o envuelve routes en `middleware('auth')`.

---

## Uso rápido

* Visitar `GET /clients` → lista de clientes, búsqueda.
* `GET /clients/{id}` → detalle del cliente + tickets asociados + quick-create ticket.
* `GET /tickets` → listado general de tickets con filtros.
* Crear/Editar/Eliminar desde las vistas.

---

## Archivos importantes (para revisar rápido)

* `app/Models/Client.php`
* `app/Models/Ticket.php` — **Asegúrate** de que contiene:

```php
protected $casts = [
    'opened_at' => 'datetime',
    'closed_at' => 'datetime',
];
```

* `app/Http/Controllers/ClientController.php`
* `app/Http/Controllers/TicketController.php`
* `app/Http/Requests/StoreClientRequest.php`
* `app/Http/Requests/StoreTicketRequest.php`
* `routes/web.php`
* `resources/views/...` (layouts, clients/*, tickets/*, partials)

---

## Solución de problemas comunes (rápido y copiables)

### 1) Pantalla blanca o error 500 al actualizar/mostrar ticket

**Síntoma**: `Call to a member function format() on string` o pantalla blanca al abrir `/tickets` o `/tickets/{id}`.
**Causa**: `opened_at` o `closed_at` no está siendo devuelto como `Carbon` (es string o null inesperado).
**Solución**:

* En `app/Models/Ticket.php` añade:

```php
protected $casts = [
    'opened_at' => 'datetime',
    'closed_at' => 'datetime',
];
```

* Asegúrate de normalizar dates al guardar/actualizar (ver `TicketController::store` y `update`) y limpiar caches:

```bash
composer dump-autoload
php artisan optimize:clear
php artisan serve
```

---

### 2) Error `Target class [App\Http\Controllers\TicketController] does not exist.`

**Causa**: archivo `TicketController.php` contiene código suelto o sintaxis rota (clase no encontrada).
**Solución**:

* Reemplaza `app/Http/Controllers/TicketController.php` por el archivo correcto (ver versión completa entregada).
* Verifica sintaxis:

```bash
php -l app/Http/Controllers/TicketController.php
```

* Limpia cachés y reinicia.

---

### 3) 404 al abrir `/`

**Causa**: no hay ruta para `/` o todas las rutas están protegidas por `auth` y no tienes login.
**Solución**:

* En `routes/web.php` añade:

```php
Route::get('/', function(){ return redirect()->route('clients.index'); });
Route::resource('clients', ClientController::class);
Route::resource('tickets', TicketController::class);
```

(o quita temporalmente el `middleware('auth')` para pruebas).

---

### 4) Error SQLite NOT NULL constraint failed: tickets.opened_at

**Síntoma**: al actualizar ticket se lanza `Integrity constraint violation: tickets.opened_at`.
**Causa**: el controlador intentó escribir `NULL` en `opened_at` y la columna no permite null.
**Solución** (definitiva sin perder datos):

* En `StoreTicketRequest` añade reglas:

```php
'opened_at' => 'nullable|date',
'closed_at' => 'nullable|date',
```

* En `TicketController@update` **no** sobrescribas `opened_at` con null si el request no incluye ese campo; actualiza sólo si `$request->has('opened_at')`. Si creas nuevo ticket y no viene `opened_at`, setea `Carbon::now()`.

---

### 5) No se escriben logs / `storage/logs/laravel.log` vacío

**Verifica permisos** (Linux/macOS):

```bash
chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

Prueba escribir en log con tinker:

```bash
php artisan tinker
>>> \Log::error('Prueba de logging');
```

Revisa `tail -n 50 storage/logs/laravel.log`.
