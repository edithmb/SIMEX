# Problemas de la Base de Datos SIMEX06

## Estado: Pendiente

---

### 1. [ ] Solo existe 1 rol ("admin")
Todos los usuarios son admin. Faltan roles como `operador`, `cliente`, `comercial` para diferenciar permisos.

### 2. [ ] `personal_documents_types` vacía
La tabla `personal_documents` tiene FK a esta tabla, pero no hay tipos definidos. El módulo de documentos personales no funciona sin ellos.

### 3. [ ] Problema de encoding (UTF-8)
Los datos con caracteres especiales se ven mal (`Logística` → `Log�stica`, `París` → `Par�s`). El collation o la conexión no usa UTF-8.

### 4. [ ] `tracking_steps.status` es `nchar(20)` con valor "1"
Debería ser `bit` o `varchar` corto. El tipo actual desperdicia espacio y el valor "1" como string no es claro.

### 5. [ ] `client_requests.estado` en español, resto en inglés
Inconsistencia de naming. Debería ser `status` para coherencia con el resto de la BD.

### 6. [ ] `clients.country` es texto libre en vez de FK
Es un `varchar(255)` pero ya existe la tabla `countries`. Debería ser un FK a `countries.id`.

### 7. [ ] Tablas operativas vacías (sin flujo probado)
No hay solicitudes, ofertas, operaciones ni sesiones. Solo datos maestros/catálogo. Falta probar el flujo completo.

### 8. [ ] `migrations` vacía - BD creada manualmente
La BD se creó con scripts SQL directos, no con el sistema de migraciones de Laravel. Recomendable empezar a usarlas.

### 9. [ ] 3 de 5 usuarios desactivados (`is_active = 0`)
Daniel, Miquel y Edith están desactivados. Solo Test User y Ledys están activos.

### 10. [ ] Datos de ejemplo ficticios (puertos/aeropuertos)
"Puerto de Madrid" y "Puerto de París" no son reales. Para producción se necesitan datos reales (Valencia, Algeciras, Le Havre, etc.).
