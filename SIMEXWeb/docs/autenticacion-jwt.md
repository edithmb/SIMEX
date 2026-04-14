# Flujo de Autenticación JWT en SIMEX

## 1. ¿Qué es un JWT?

JWT significa **JSON Web Token**. Es un estándar (RFC 7519) para transmitir información de forma segura entre dos partes como un objeto JSON compacto y firmado digitalmente.

Un JWT tiene **tres partes** separadas por puntos (`.`):

```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9   ← HEADER
.
eyJzdWIiOiIxIiwicm9sZV9pZCI6MiwiaWF0IjoxNzEzMDAwMDAwfQ   ← PAYLOAD
.
SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c   ← SIGNATURE
```

### Header
Indica el algoritmo de firma usado. En SIMEX es **HS256** (HMAC + SHA-256):
```json
{
  "alg": "HS256",
  "typ": "JWT"
}
```

### Payload (claims)
Contiene los datos del usuario y metadatos del token:
```json
{
  "iss": "http://tu-app.com",    // quién emitió el token
  "iat": 1713000000,              // cuándo se creó (issued at)
  "exp": 1713003600,              // cuándo expira
  "nbf": 1713000000,              // no válido antes de
  "jti": "abc123",                // ID único del token
  "sub": "1"                      // ID del usuario (subject)
}
```

> **Importante:** El payload NO está cifrado, solo está codificado en Base64. Cualquiera puede leer su contenido. Por eso nunca se guarda información sensible (contraseñas, tarjetas...) dentro del token.

### Signature
Es la firma que garantiza que el token no fue alterado:
```
HMAC-SHA256(
  base64url(header) + "." + base64url(payload),
  JWT_SECRET
)
```
Solo el servidor conoce el `JWT_SECRET`, así que solo él puede verificar que la firma es válida.

---

## 2. La librería que usamos

SIMEX usa **`tymon/jwt-auth`** en el backend (Laravel). Puedes ver la dependencia en `backend/vendor/tymon/jwt-auth/`.

Esta librería se apoya en **`lcobucci/jwt`** para la construcción y parseo real de los tokens.

La configuración está en `backend/config/jwt.php` y el secreto se define en `.env`:
```
JWT_SECRET=tu_clave_secreta_aqui
```

---

## 3. Flujo completo paso a paso

```
FRONTEND (Vue)              BACKEND (Laravel API)          BASE DE DATOS
     │                              │                             │
     │  POST /login                 │                             │
     │  { email, password }         │                             │
     │─────────────────────────────>│                             │
     │                              │  SELECT * FROM users        │
     │                              │  WHERE email=? AND          │
     │                              │  is_active=true             │
     │                              │────────────────────────────>│
     │                              │<────────────────────────────│
     │                              │  Hash::check(password)      │
     │                              │  JWTAuth::fromUser($user)   │
     │                              │  (genera el token firmado)  │
     │                              │                             │
     │  200 OK                      │                             │
     │  { token, user, role }       │                             │
     │<─────────────────────────────│                             │
     │                              │                             │
     │  localStorage.setItem(       │                             │
     │    'jwt_token', token)       │                             │
     │                              │                             │
     │  GET /me                     │                             │
     │  Authorization: Bearer TOKEN │                             │
     │─────────────────────────────>│                             │
     │                              │  auth('api')->user()        │
     │                              │  (valida el token)          │
     │  200 OK { user + role }      │                             │
     │<─────────────────────────────│                             │
     │                              │                             │
     │  Guarda rol en Pinia store   │                             │
     │  y localStorage              │                             │
```

---

## 4. Detalle del backend

### 4.1 Endpoint de login
**Archivo:** `backend/app/Http/Controllers/AuthController.php`

```php
public function login(Request $request): JsonResponse
{
    // 1. Valida que lleguen email y password
    $request->validate([...]);

    // 2. Busca usuario ACTIVO por email
    $user = User::active()->where('email', $request->email)->first();

    // 3. Verifica la contraseña con bcrypt
    if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
        throw ValidationException::withMessages([...]);
    }

    // 4. Genera el JWT firmado con el JWT_SECRET del .env
    $token = JWTAuth::fromUser($user);

    // 5. Devuelve token + datos del usuario + su rol
    return response()->json([
        'token'      => $token,
        'token_type' => 'bearer',
        'expires_in' => config('jwt.ttl') * 60,  // TTL en segundos
        'user'       => [
            'id', 'first_name', 'last_name', 'email',
            'role' => ['id', 'name']
        ],
    ]);
}
```

### 4.2 Cómo el modelo User "habla" con JWT
**Archivo:** `backend/app/Models/User.php`

El modelo implementa la interfaz `JWTSubject`:

```php
class User extends Authenticatable implements JWTSubject
{
    // Le dice a JWT cuál es el identificador único del usuario (su PK)
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey(); // devuelve $user->id
    }

    // Aquí se pueden añadir "claims" extra al token (vacío por ahora)
    public function getJWTCustomClaims(): array
    {
        return [];
        // AQUÍ PODRÍAS METER EL ROL: return ['role' => $this->role->name];
    }
}
```

### 4.3 Protección de rutas
**Archivo:** `backend/routes/api.php`

```php
// Ruta PÚBLICA — no requiere token
Route::post('/login', [AuthController::class, 'login']);

// Rutas PROTEGIDAS — el middleware 'auth:api' valida el JWT en cada petición
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('countries', CountryController::class);
    // ... todas las demás rutas
});
```

El middleware `auth:api` hace lo siguiente en cada petición:
1. Lee el header `Authorization: Bearer <token>`
2. Decodifica el JWT
3. Verifica la firma con el `JWT_SECRET`
4. Comprueba que no ha expirado
5. Carga el usuario desde la base de datos según el `sub` (ID del usuario)
6. Si algo falla → devuelve `401 Unauthorized`

### 4.4 Relación User → Role en la BD

```
┌─────────────────┐         ┌─────────────┐
│     users       │         │    roles    │
├─────────────────┤         ├─────────────┤
│ id              │         │ id          │
│ role_id ────────┼────────>│ name        │
│ client_id       │         │ description │
│ first_name      │         └─────────────┘
│ last_name       │
│ email           │
│ password_hash   │
│ is_active       │
└─────────────────┘
```

Actualmente los roles posibles (en la lógica del frontend) son:
- `admin` — cualquier rol que NO sea "cliente"
- `cliente` — rol de cliente

---

## 5. Detalle del frontend

### 5.1 El token en `authStore`
**Archivo:** `frontend/src/stores/auth.js`

```javascript
const token = ref(localStorage.getItem('jwt_token') ?? null)
const isAuthenticated = computed(() => !!token.value)
```

El token se persiste en `localStorage` con la clave `jwt_token`. Si el usuario cierra y vuelve a abrir el navegador, sigue autenticado hasta que el token expire en el backend.

### 5.2 Datos que se guardan en localStorage tras el login

| Clave              | Valor                         | Descripción                          |
|--------------------|-------------------------------|--------------------------------------|
| `jwt_token`        | `eyJhbGci...`                 | El token JWT completo                |
| `user_role`        | `"admin"` / `"cliente"`       | Rol de vista (usado por `roleStore`) |
| `backend_is_admin` | `"true"` / `"false"`          | Si el backend dice que es admin      |

### 5.3 El `roleStore`
**Archivo:** `frontend/src/stores/role.js`

```javascript
const currentRole = ref(localStorage.getItem('user_role') ?? 'admin')
const isAdmin = computed(() => currentRole.value === 'admin')
const isCliente = computed(() => currentRole.value === 'cliente')
```

> **Atención:** este store controla la VISTA (qué menús se muestran), pero la autorización real de las acciones debe validarse en el backend.

### 5.4 Protección de rutas en Vue Router
**Archivo:** `frontend/src/router/index.js`

```javascript
router.beforeEach((to) => {
  const auth = useAuthStore()
  // Si la ruta NO es pública y NO hay token → redirige al login
  if (!to.meta.public && !auth.isAuthenticated) {
    return { name: 'login' }
  }
  // Si ya estás autenticado e intentas ir al login → redirige al dashboard
  if (to.name === 'login' && auth.isAuthenticated) {
    return { name: 'dashboard' }
  }
})
```

Actualmente ninguna ruta tiene `meta: { roles: [...] }`, por lo que el guard solo comprueba si hay sesión activa, no el rol.

### 5.5 Cómo se envía el token en cada petición

Actualmente se pasa manualmente en cada llamada:
```javascript
await axios.get(LARAVEL + '/me', {
  headers: { Authorization: `Bearer ${token.value}` },
})
```

> No hay un interceptor global de Axios configurado aún, lo que significa que hay que recordar añadir el header manualmente en cada request.

---

## 6. Ciclo de vida del token

```
Login               Token vivo             Expiración
  │                      │                      │
  ▼                      ▼                      ▼
Creado            Petición autenticada     Backend devuelve
(firmado con      → header Bearer          401 → frontend
JWT_SECRET)       → middleware verifica    debe hacer logout
                  → usuario cargado        o renovar token
```

El TTL se configura en `backend/config/jwt.php` → variable `JWT_TTL` en `.env` (en minutos). Por defecto es **60 minutos**.

---

## 7. Dónde tocar para implementar roles

### Objetivo: controlar quién puede hacer qué según su rol

### 7.1 Backend — Middleware por rol

Crear un middleware en Laravel que verifique el rol del usuario autenticado.

**Archivo a crear:** `backend/app/Http/Middleware/EnsureUserHasRole.php`

```php
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    $user = auth('api')->user();
    if (!$user || !in_array($user->role->name, $roles)) {
        return response()->json(['message' => 'No autorizado.'], 403);
    }
    return $next($request);
}
```

**Registrar en:** `backend/bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
})
```

**Usar en rutas:**
```php
// Solo admins pueden gestionar datos maestros
Route::middleware(['auth:api', 'role:admin,superadmin'])->group(function () {
    Route::apiResource('countries', CountryController::class);
});

// Los clientes solo pueden ver sus propias solicitudes
Route::middleware(['auth:api', 'role:cliente'])->group(function () {
    Route::get('/mis-solicitudes', [...]);
});
```

### 7.2 Backend — Añadir el rol al token (claims)

Para no hacer una consulta a la BD en cada petición solo para leer el rol, puedes meterlo en el JWT:

**Archivo:** `backend/app/Models/User.php`

```php
public function getJWTCustomClaims(): array
{
    return [
        'role' => $this->role->name,
        'role_id' => $this->role_id,
    ];
}
```

Así el middleware puede leer el rol directamente del token sin consultar la BD.

### 7.3 Frontend — Guard de rutas por rol

**Archivo:** `frontend/src/router/index.js`

Añadir `meta: { roles: [...] }` a las rutas y ampliar el guard:

```javascript
{
  path: '/datos-maestros',
  name: 'datos-maestros',
  component: () => import('@/views/DatosMaestrosView.vue'),
  meta: { title: 'Datos Maestros', roles: ['admin'] },  // ← añadir esto
},
```

```javascript
router.beforeEach((to) => {
  const auth = useAuthStore()
  const role = useRoleStore()

  if (!to.meta.public && !auth.isAuthenticated) {
    return { name: 'login' }
  }
  if (to.name === 'login' && auth.isAuthenticated) {
    return { name: 'dashboard' }
  }
  // Verificación de rol
  if (to.meta.roles && !to.meta.roles.includes(role.currentRole)) {
    return { name: 'dashboard' }  // o una vista de "Sin permiso"
  }
})
```

### 7.4 Frontend — Interceptor global de Axios (mejora recomendada)

Para no repetir el header en cada petición, añadir un interceptor en `main.js` o en un archivo `axios.js`:

```javascript
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

axios.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

// Interceptor de respuesta: si el backend devuelve 401, hacer logout
axios.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401) {
      const auth = useAuthStore()
      auth.logout()
    }
    return Promise.reject(err)
  }
)
```

---

## 8. Resumen visual de archivos clave

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AuthController.php       ← login / me / logout
│   │   └── Middleware/
│   │       └── (EnsureUserHasRole.php)  ← [CREAR PARA ROLES]
│   └── Models/
│       ├── User.php                     ← implementa JWTSubject
│       └── Role.php                     ← modelo de rol
├── config/
│   └── jwt.php                          ← TTL, algoritmo, secreto
└── routes/
    └── api.php                          ← protección con middleware('auth:api')

frontend/
└── src/
    ├── stores/
    │   ├── auth.js                      ← token, login(), logout()
    │   └── role.js                      ← rol de vista (admin/cliente)
    ├── router/
    │   └── index.js                     ← guard de autenticación
    └── services/
        └── authService.js               ← llamadas HTTP de auth
```

---

## 9. Puntos de atención actuales

| Punto | Estado actual | Recomendación |
|-------|--------------|---------------|
| Roles en el backend | Solo se comprueba autenticación | Añadir middleware de rol en rutas sensibles |
| Claims del JWT | Vacíos (`getJWTCustomClaims` devuelve `[]`) | Incluir `role` en el token |
| Header Axios | Se añade manualmente en cada petición | Configurar interceptor global |
| Guard de rutas frontend | Solo verifica `isAuthenticated` | Añadir verificación de `meta.roles` |
| `backend_is_admin` | Booleano binario (admin / no-admin) | Sustituir por el nombre real del rol cuando haya más roles |
