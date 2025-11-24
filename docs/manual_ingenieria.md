# Manual de Ingeniería

## 1. Resumen del Sistema
Sistema web médico construido sobre Laravel que gestiona pacientes, diagnósticos generales y específicos, síntomas, citas, prescripciones, historiales clínicos, casos clínicos para entrenamiento y tickets de soporte. Incluye modo visitante (locale ruso) con restricciones, asignación de roles y autenticación vía Laravel Sanctum para APIs.

## 2. Stack Tecnológico
- **Backend:** PHP 8.x (Laravel Framework)
- **Autenticación:** Laravel Auth + Sanctum (`auth:sanctum` en rutas API)
- **Autorización/Roles:** Spatie Permission (archivo `config/permission.php` y middleware `role:admin`)
- **Base de Datos:** MySQL/MariaDB (migraciones en `database/migrations/`)
- **Front-end:** Blade Templates + Laravel Mix (Webpack) para compilar assets (`webpack.mix.js`)
- **Internacionalización:** Archivos en `resources/lang/` con soporte para `es` y `ru`
- **Testing:** PHPUnit (`phpunit.xml`), Factories y Seeders
- **Herramientas adicionales:** Service Worker (`public/sw.js`) para offline parcial; cookie `simulate_offline` controla comportamiento.

## 3. Estructura Principal
```
app/
  Http/
    Controllers/        # Controladores Web y API (subcarpetas Api/)
    Middleware/         # Ej. block.martian, visitor.auth, deny.standard.visitor
  Models/               # Modelos Eloquent: Patient, Appointment, Diagnostic, etc.
  Providers/            # Service Providers
routes/
  web.php               # Rutas para interfaz web y visitante
  api.php               # Endpoints REST (Sanctum, prefijo /api)
resources/views/        # Vistas Blade
config/                 # Configuración global
public/                 # Document root (index.php, assets compilados)
database/migrations/    # Esquema y cambios de BD
database/seeders/       # Seeders (ej. AssignRoleSeeder)
docs/                   # Documentación (este manual y usuario)
```

### Modelos Clave
- `Patient`: campos `rut`, nombres y fecha nacimiento; mutators para formateo del RUT.
- `Diagnostic` y `GeneralDiagnostic`: diagnósticos específicos y base de conocimiento general.
- `Symptom`: catálogo de síntomas; relación N:M con diagnósticos (tabla pivote).
- `Record`: historial clínico vinculado a paciente y diagnóstico.
- `Appointment`: citas con estado (`scheduled`, `completed`, etc.) y acceso a prescripciones.
- `Prescription`: recetas médicas asociadas a citas.
- `ClinicalCase`: casos para entrenamiento de visitantes.
- `SupportTicket`: soporte interno.

## 4. Modelo Entidad-Relación (Resumen)
Basado en `MER_actualizado.puml` y documentación `README.md`:
- Paciente 1:N Diagnóstico
- Paciente 1:N Historial (Records)
- Usuario 1:N Diagnóstico
- Diagnóstico N:M Síntoma (tabla pivote)
- Paciente 1:N Cita (Appointment)
- Cita 1:N Prescripción

## 5. Rutas y Middleware
### Web (`routes/web.php`)
Agrupadas bajo middleware `auth` y `block.martian` para restringir visitantes en ciertas secciones.
Recursos: `patients`, `diagnostics`, `general-diagnostics`, `records`, `symptoms`, `appointments`, `prescriptions`, `support`.
Funciones especiales:
- Sugerencias: `diagnostics/suggest`, `symptoms/suggest`
- Cambio idioma: `lang/{locale}` (valores permitidos: es, ru)
- Flujos visitante: registro/login personalizado, vistas de entrenamiento y casos clínicos.
- Control de acceso admin: `role:admin` para `clinical_cases` y gestión de usuarios.

### API (`routes/api.php`)
- `GET /api/patients` (Sanctum + role:admin)
- `GET /api/general-diagnostics`
- `GET /api/records`
- `GET /api/prescriptions/{id}` (auth:sanctum)
- `GET /api/user` (perfil autenticado por token)
Todas las rutas `apiResource` exponen métodos CRUD estándar (index, store, show, update, destroy).

### Middleware Relevante
- `auth`, `auth:sanctum`: sesiones vs tokens.
- `role:admin`: verificación de rol (Spatie Permission).
- `block.martian`: bloquea visitantes (locale ruso) en secciones protegidas.
- `deny.standard.visitor` / `visitor.auth`: controla ingreso y flujo visitante.

## 6. Seguridad
- Hash de contraseñas mediante Laravel (bcrypt / Argon2 según configuración).
- Tokens de API mediante Sanctum; usar encabezado `Authorization: Bearer <token>`.
- Sanitización RUT (mutator en `Patient`).
- Políticas de roles y permisos (asignación vía seeder `AssignRoleSeeder`).
- CSRF protección automática en formularios Blade.

## 7. Flujo de Desarrollo Local
### Prerrequisitos
- PHP >= 8.x con extensiones: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON
- Composer
- Node.js + NPM
- MySQL/MariaDB

### Pasos Iniciales
```bash
composer install
cp .env.example .env
php artisan key:generate
# Configurar variables DB en .env
php artisan migrate --seed
npm install
npm run dev   # o npm run prod para producción
php artisan serve  # http://127.0.0.1:8000
```
Para servir bajo XAMPP usar `public/` como document root y asegurarse que `storage/` y `bootstrap/cache` tengan permisos de escritura.

## 8. Despliegue
1. Compilar assets: `npm ci && npm run prod`
2. `composer install --no-dev --optimize-autoloader`
3. Configurar `.env` productivo (DB, MAIL, CACHE_DRIVER=redis/memcached según disponibilidad).
4. Ejecutar migraciones: `php artisan migrate --force`
5. Cacheo: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. Supervisar colas (si se usan): `php artisan queue:work --daemon` bajo `supervisord` o `systemd`.
7. Revisar permisos: `storage`, `bootstrap/cache` (www-data).

## 9. Estándares de Código
- PSR-12 (formateo). Mantener clases en `App\Models` y `App\Http\Controllers`.
- Nombres en inglés para código, UI traducida mediante archivos de idioma.
- Evitar lógica de negocio en controladores extensa: preferir Services (crear carpeta `app/Services` si crece).
- Validación con Form Requests personalizados para escalabilidad futura.
- Usar mutators/casts Eloquent para normalizar datos (RUT, fechas).

## 10. Testing
- Ejecutar pruebas: `php artisan test` o `vendor/bin/phpunit`.
- Crear factories para nuevos modelos (`database/factories`).
- Tests de integración en `tests/Feature/`, unitarios en `tests/Unit/`.
- Añadir seeds mínimos para roles y permisos antes de pruebas (usar `RefreshDatabase`).

## 11. API: Ejemplos
### Listar pacientes (admin)
```
GET /api/patients
Authorization: Bearer <token>
```
Respuesta ejemplo:
```json
[
  {"id":1,"rut":"12.345.678-9","name":"Ana","gender":"F"}
]
```
### Crear diagnóstico general
```
POST /api/general-diagnostics
Content-Type: application/json
{
  "name": "Hipertensión primaria",
  "description": "Presión arterial elevada sostenida"
}
```
### Obtener prescripción
```
GET /api/prescriptions/15
Authorization: Bearer <token>
```

## 12. Internacionalización
- Cambios de idioma vía `GET /lang/{locale}`.
- Forzar español en raíz `/` y en salida de visitante.

## 13. Off-line y Modo Visitante
- Service Worker (`public/sw.js`) usa cookie `simulate_offline` para pruebas: activar rutas `/visitor/simulate-offline/on` / `off`.
- Modo visitante aislado: vistas prefijadas `visitor.*` con entrenamiento y casos clínicos, restringido para recursos administrativos.

## 14. Extensiones Futuras
- Separar capa de servicios (Domain Services) y DTOs.
- Añadir versionado de API (/api/v1/...).
- Integrar herramientas de observabilidad (Logstash, Sentry).
- Implementar caching granular para listados frecuentes.

## 15. Mantenimiento
- Actualizar dependencias: `composer outdated` y `npm outdated`.
- Auditoría seguridad: `composer audit` y revisar CVEs.
- Rotar claves y tokens periódicamente.
- Backup BD: programar dumps diarios y almacenarlos cifrados.

---
Última actualización: Noviembre 2025.
