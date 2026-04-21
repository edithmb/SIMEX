# Frontend — Vue 3 + Vite

SPA que consume las dos APIs del sistema.

## Stack

- **Vue 3** con `<script setup>` (Composition API).
- **Pinia** para estado (stores `auth`, `role`, `counter`).
- **Vue Router 5** con guard global para auth + roles.
- **Axios** con interceptores globales (ver `src/main.js`).
- **Vite 7** como bundler.
- **Vitest** + Vue Test Utils para tests.
- JavaScript puro (sin TypeScript) y Prettier con `semi: false`,
  comillas simples, `printWidth: 100`.

## Estructura

```
frontend/
├── src/
│   ├── App.vue                  # Raíz: layout + RouterView + estilos globales
│   ├── main.js                  # Bootstrap + interceptores axios
│   ├── router/index.js          # Rutas y guard
│   ├── stores/                  # auth.js, role.js, counter.js
│   ├── services/authService.js  # Wrapper fino sobre /login y /me
│   ├── components/
│   │   ├── layout/              # AppLayout, AppSidebar, AppTopbar
│   │   ├── common/              # Spinner
│   │   ├── dashboard/           # StatCard + charts
│   │   ├── clientes/ usuarios/  # Gestión
│   │   ├── solicitudes/ presupuestos/
│   │   ├── seguimiento/ documentos/
│   │   └── datos-maestros/
│   └── views/                   # Páginas (una por ruta)
├── DOCKERFILE                   # Multi-stage: build Vite → Nginx
├── vite.config.js
└── package.json
```

## Autenticación (cliente)

- El `useAuthStore` guarda el JWT en `localStorage` bajo `jwt_token`.
- Un interceptor **request** de axios añade `Authorization: Bearer <token>`
  a cada llamada.
- Un interceptor **response** fuerza logout y redirige a `/login` ante
  cualquier 401.
- El rol real del backend se persiste en `backend_is_admin` y se
  hidrata vía `/me` en login y al arrancar la app
  (`useAuthStore.initBackendRole`).

### Rol de vista vs rol real

- `useAuthStore.backendIsAdmin` → **rol real** emitido por el backend.
  No se modifica desde la UI.
- `useRoleStore.currentRole` → **rol de vista** (`'admin'` / `'cliente'`)
  que un admin puede alternar desde el selector del sidebar para previsualizar
  la experiencia de cliente. No concede privilegios reales.

## Router

Cada ruta declara `meta` con:
- `public: true` → accesible sin sesión (sólo `/login`).
- `title`, `breadcrumbParent` → usados por la topbar.
- `roles: ['admin']` → restricción dura (el guard redirige al dashboard).

## Integración con las 2 APIs

- `VITE_LARAVEL_API` — base de la API Laravel (todas las llamadas de
  datos de negocio).
- `VITE_NET_API` — base de la API .NET (documentos, notificaciones,
  status de operaciones, alta de empresas/usuarios).

Ambas se resuelven en build-time dentro del `DOCKERFILE` del frontend
(ver [[Despliegue]]).

## Desarrollo local

```bash
cd frontend
npm install
cp .env.example .env   # si lo tienes, con VITE_LARAVEL_API y VITE_NET_API
npm run dev            # Vite en http://localhost:5173
```

## Tests

```bash
npx vitest run         # Lo que usa el workflow de CI
npm run test           # Alias, si está definido en package.json
```

## Documentación del código

Todo el código en `src/` está documentado con **JSDoc en español**
(componente + funciones relevantes, props, emits y computed con lógica
no trivial).
