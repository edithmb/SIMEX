# Despliegue

El proyecto se despliega como **tres contenedores independientes**
(frontend, backend, .NET) orquestados por **Dockploy** sobre un servidor
Docker. Dockploy se encarga de:

1. Clonar el repositorio de GitHub.
2. Construir o descargar la imagen correspondiente.
3. Inyectar las variables de entorno que configuramos en la UI.
4. Arrancar el contenedor y mantenerlo vivo (`restart: always`).

## Servicios en Dockploy

| Servicio   | Fuente de la imagen                          | Puerto interno |
|------------|----------------------------------------------|----------------|
| frontend   | `ddelacortep/simex-daw:frontend-latest`     | 80 (Nginx)     |
| backend    | `ddelacortep/simex-daw:backend-latest`      | 80 (Nginx + PHP-FPM) |
| .NET       | Imagen propia del microservicio             | (según dockerfile .NET) |

Cada servicio lleva su propia red/URL en el servidor; el frontend
habla con los otros dos mediante las variables `VITE_LARAVEL_API` y
`VITE_NET_API` **fijadas al hacer build** de la imagen del frontend.

## Dockerfiles del proyecto

### `backend/DOCKERFILE`
- Base: `php:8.2-fpm`.
- Instala extensiones PHP necesarias (`pdo_dblib`, `mbstring`, `gd`…).
- Copia `freetds.conf` en `/etc/freetds/` para SQL Server.
- Configura **Nginx** embebido (`nginx.conf` + `default.conf`).
- Ejecuta `composer install --no-dev --optimize-autoloader`.
- Arranca con `docker-entrypoint.sh`, que antes de Nginx corre:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php-fpm -D
  exec nginx -g "daemon off;"
  ```

### `frontend/DOCKERFILE`
- **Multi-stage**: etapa de build con `node:lts-alpine` → etapa final con
  `nginx:stable-alpine`.
- Lee `VITE_LARAVEL_API` y `VITE_NET_API` como `ARG`/`ENV` para que
  queden horneadas en el bundle generado por `npm run build`.
- Expone el puerto 80.

## Variables de entorno

Los `.env` los gestionamos desde la UI de Dockploy por servicio (no se
suben al repo). Plantillas mínimas:

### Backend (`.env`)
```env
APP_NAME=SIMEX
APP_ENV=production
APP_KEY=base64:...          # php artisan key:generate
APP_DEBUG=false
APP_URL=https://backend.midominio.tld

# Base de datos SQL Server via FreeTDS
DB_CONNECTION=dblib
DB_HOST=sqlserver.midominio.tld
DB_PORT=1433
DB_DATABASE=simex
DB_USERNAME=...
DB_PASSWORD=...

# JWT
JWT_SECRET=...              # php artisan jwt:secret
JWT_TTL=60

# URL de la API .NET (si backend la llama)
NET_API_BASE=https://net.midominio.tld/api
```

### Frontend (`.env`, aplicada en build)
```env
VITE_LARAVEL_API=https://backend.midominio.tld/api
VITE_NET_API=https://net.midominio.tld/api
```

### .NET
Variables propias del microservicio (cadena de conexión, credenciales).
**Lo importante**: la URL pública del .NET debe coincidir con lo que el
frontend tiene en `VITE_NET_API` y lo que Laravel tiene en
`NET_API_BASE`.

## Cómo actualizamos un servicio en producción

1. Merge a `main` en GitHub → el workflow (ver [[CI-CD]]) corre tests,
   construye las imágenes y las publica en Docker Hub.
2. En Dockploy pulsamos **Redeploy** sobre el servicio afectado.
   Dockploy hace `pull` de la imagen `latest` y reinicia el contenedor.
3. Para despliegues con cambios de `.env`: editar variables en Dockploy
   **antes** de redeploy.

## Despliegue local con docker-compose

En el repo hay un `SIMEXWeb/docker-compose.yml` de referencia que:
- Levanta un `nginx-proxy` en `:80`.
- Usa las imágenes publicadas en Docker Hub (`frontend-latest`,
  `backend-latest`).
- Monta `./backend/.env` en el servicio backend y fuerza
  `APP_ENV=production`.

Para probar en un servidor sencillo:
```bash
docker compose up -d
```

## Solución de problemas

- **Backend no conecta a SQL Server**: revisa `freetds.conf` dentro del
  contenedor y que `DB_HOST`/`DB_PORT` sean accesibles desde la red de
  Dockploy.
- **Frontend con URLs incorrectas**: las `VITE_*` están quemadas en el
  bundle. Hay que reconstruir la imagen (rebuild en CI) tras cambiarlas.
- **401 en todas las llamadas**: comprueba `JWT_SECRET` (debe ser el
  mismo que el usado al emitir tokens) y que la hora del servidor esté
  sincronizada (NTP).
