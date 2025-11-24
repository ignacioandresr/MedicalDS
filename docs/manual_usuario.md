# Manual de Usuario

## 1. Introducción
Bienvenido al sistema médico. Esta aplicación permite gestionar pacientes, citas, diagnósticos, síntomas, prescripciones y casos clínicos. También ofrece un modo visitante (entrenamiento) con contenido educativo. El sistema está disponible en español y en ruso (visitantes).

## 2. Acceso y Roles
- **Usuario estándar / Médico:** Acceso a pacientes, diagnósticos, síntomas, citas, prescripciones y tickets de soporte.
- **Administrador:** Además puede gestionar casos clínicos y asignar roles.
- **Visitante:** Acceso restringido a secciones de entrenamiento y casos clínicos en modo lectura.

### Registro
- Administradores: usar la ruta especial `Registrar Admin` (`/register/admin`) después de pasar la “puerta” (`/admin/gate`).
- Visitantes: registro simplificado en `/visitor/register`.

### Inicio de Sesión
1. Ir a `Iniciar Sesión`.
2. Ingresar correo y contraseña.
3. Al acceder verás el panel inicial (Home).

### Cambio de Idioma
- Español forzado al visitar `/`.
- Para cambiar a ruso (solo si está habilitado): enlace que apunta a `/lang/ru`. Para volver a español: `/lang/es`.

## 3. Navegación Principal
Al iniciar sesión encontrarás el menú con secciones principales:
- Pacientes
- Diagnósticos
- Diagnósticos Generales
- Historiales (Records)
- Síntomas
- Citas (Appointments)
- Prescripciones
- Soporte (Tickets)
- Casos Clínicos (solo admin)

## 4. Gestión de Pacientes
1. Ir a `Pacientes`.
2. Botón `Agregar Paciente`.
3. Completar RUT (el sistema lo formatea automáticamente), nombres, fecha de nacimiento, género y dirección.
4. Guardar.

### Edición
- Seleccionar paciente en la lista → `Editar`.
- Modificar los campos necesarios y guardar.

### Búsqueda
- Usa el cuadro de búsqueda (si disponible) por nombre o RUT.

## 5. Diagnósticos
### Crear Diagnóstico
1. Ir a `Diagnósticos` → `Agregar`.
2. Seleccionar paciente (autocompletado por nombre/RUT).
3. Ingresar descripción y fecha.
4. Elegir síntomas (mantén Ctrl para seleccionar varios).
5. Guardar.

### Sugerencias
- Botón o enlace `Sugerir` abre una interfaz que puede mostrar diagnósticos o síntomas sugeridos (función interna `diagnostics/suggest`).

### Edición / Eliminación
- En la lista de diagnósticos: usar `Editar` o `Eliminar` según corresponda.

## 6. Diagnósticos Generales
Base de conocimiento. Útil para referencia rápida.
- Crear: `Diagnósticos Generales` → `Agregar`.
- Añadir título y descripción ampliada.

## 7. Historial Clínico (Records)
Registros asociados a la evolución del paciente.
1. Ir a `Historiales`.
2. Crear nuevo registro asociando paciente y (opcional) diagnóstico.
3. Incluir tratamientos / notas y fecha.

## 8. Síntomas
Catálogo editable.
- `Síntomas` → `Agregar`: nombre y descripción.
- Asignación a diagnósticos se hace desde la pantalla de creación de diagnóstico.
- Sugerencias disponibles vía `symptoms/suggest` (por similitud o frecuencia).

## 9. Citas (Appointments)
1. Ir a `Citas` → `Agregar`.
2. Seleccionar paciente, fecha, hora y notas.
3. Estado inicial: Agendado.

### Actualizar Estado
- Editar cita y cambiar a `Completada`, `Cancelada` o `Pendiente` según situación.

## 10. Prescripciones
Vinculadas a citas.
- Crear desde una cita (si la UI lo permite) o en `Prescripciones` → `Agregar`.
- Detallar medicamentos, dosis, duración y recomendaciones.
- Para ver detalles: lista → `Ver`.

## 11. Tickets de Soporte
Para reportar incidencias o solicitar ayuda.
1. Ir a `Soporte` → `Nuevo Ticket`.
2. Describir problema y prioridad.
3. En la lista se muestra estado (Abierto, En Proceso, Cerrado).

## 12. Casos Clínicos (Admin / Visitante Entrenamiento)
- Administración: `Casos Clínicos` → CRUD completo para crear escenarios.
- Visitante: puede ver casos y realizar intentos de diagnóstico (formulario entrenamiento).

## 13. Modo Visitante
Accesos especiales en rutas `/visitor/...`.
- Registro / login propio.
- Vistas: bienvenida, entrenamiento, casos.
- Para salir: botón `Salir` que limpia sesión visitante y regresa a español.

## 14. Simulación Offline
- Activar: visitar enlace `Simular Offline` (`/visitor/simulate-offline/on`).
- Desactivar: `/visitor/simulate-offline/off`.
El sistema usa un Service Worker que permite carga básica de páginas y recursos cacheados.

## 15. Consejos de Uso
- Mantén datos actualizados del paciente antes de registrar diagnósticos.
- Usa diagnósticos generales como referencia educativa.
- Registra síntomas detallados para mejorar sugerencias futuras.
- Cierra tickets de soporte cuando se resuelvan para mantener orden.
- Revisa prescripciones antes de marcar una cita como `Completada`.

## 16. Preguntas Frecuentes
Q: No veo la opción de casos clínicos.
A: Requiere rol administrador.

Q: El RUT se muestra con puntos y guion aunque lo escribí simple.
A: El sistema lo formatea automáticamente para claridad.

Q: No puedo acceder a pacientes en modo visitante.
A: Es intencional; modo visitante es sólo lectura educativa.

Q: ¿Cómo cambio de idioma?
A: Enlace de cambio (ES/RU) o usando las rutas `lang/es` y `lang/ru`.

## 17. Soporte
- Crear ticket en sección `Soporte`.
- Adjuntar la mayor cantidad de detalle (fecha, acción que realizabas, mensaje de error exacto).

## 18. Buenas Prácticas
- Verificar antes de eliminar: las eliminaciones pueden afectar historiales.
- Usar estados de cita coherentes para estadísticas futuras.
- Mantener diagnósticos claros y completos para seguimiento longitudinal.

## 19. Próximas Mejoras (Visibles al Usuario)
- Búsqueda avanzada de pacientes por múltiples criterios.
- Exportación PDF de historial y prescripciones.
- Notificaciones de citas próximas.

---
Última actualización: Noviembre 2025.
