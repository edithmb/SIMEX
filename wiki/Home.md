# SIMEX — Sistema de Gestión Logística y Comercio Exterior

SIMEX es una plataforma de gestión integral para empresas de logística y comercio exterior (freight forwarding / agencias de aduanas). Permite gestionar todo el ciclo operativo: desde que un cliente solicita un presupuesto hasta que la mercancía llega a destino.

---

## ¿Para qué sirve?

SIMEX cubre las siguientes áreas de negocio:

| Módulo | Descripción |
|---|---|
| **Dashboard** | Panel de control con KPIs: envíos activos, ofertas pendientes, operaciones completadas, total de clientes |
| **Seguimiento e Incoterms** | Seguimiento en tiempo real de operaciones logísticas con sus Incoterms asociados |
| **Solicitudes de clientes** | Canal para que los clientes pidan un nuevo servicio o envío |
| **Presupuestos (Ofertas comerciales)** | Creación, aprobación y rechazo de ofertas comerciales para los clientes |
| **Gestión de clientes** | CRUD completo de clientes con NIF/VAT, dirección, contacto (solo administradores) |
| **Documentos** | Gestión y visualización de documentos asociados a operaciones y personas |
| **Datos Maestros** | Administración de países, ciudades, puertos, aeropuertos, navieras, transportistas y tipos de contenedor |
| **Configuración del sistema** | Ajustes globales del sistema (solo administradores) |

---

## ¿A quién va dirigida?

La plataforma tiene dos tipos de usuario:

- **Agente / Operario**: Accede al dashboard, seguimiento, solicitudes, presupuestos y documentos. Puede crear ofertas comerciales y gestionar solicitudes.
- **Administrador**: Tiene acceso completo incluyendo gestión de clientes, datos maestros y configuración del sistema.

Adicionalmente existe una **app móvil Android (SIMEXMOVIL)** orientada a los clientes finales y agentes en campo, que permite consultar operaciones, hacer seguimiento, enviar nuevas solicitudes y chatear en tiempo real.

---

## Componentes del sistema

```
SIMEX
├── SIMEXWeb/          → Aplicación web (Vue 3 + Laravel)
│   ├── frontend/      → Vue 3 + Vite + Pinia
│   └── backend/       → Laravel 12 + PHP 8.2
├── API_MOVIL/         → API REST para la app móvil (.NET 8 + SignalR)
├── SIMEXMOVIL/        → App Android (Kotlin + Retrofit)
├── Juego_Unity/       → Módulo de gamificación en Unity
└── script.sql         → Schema completo de la base de datos SQL Server
```

---

## Páginas de esta Wiki

- [Arquitectura del sistema](Arquitectura)
- [Despliegue en local (Docker)](Despliegue-Local)
- [API Reference](API-Reference)
- [Base de datos](Base-de-datos)
