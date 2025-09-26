# Modelo Entidad-Relación (MER) del Sistema Médico

Este documento describe el Modelo Entidad-Relación (MER) del sistema médico, definido en el archivo `MER.puml` utilizando PlantUML. El diagrama representa las entidades principales y sus relaciones dentro de la base de datos del sistema.

## Entidades

### Paciente
Representa a los individuos que reciben atención médica.
- `id_paciente` (INT): Identificador único del paciente (Clave Primaria).
- `nombre` (VARCHAR): Nombre completo del paciente.
- `rut` (VARCHAR): Rol Único Tributario del paciente.
- `edad` (INT): Edad del paciente.
- `direccion` (VARCHAR): Dirección de residencia del paciente.

### Sintoma
Describe los síntomas que un paciente puede presentar.
- `id_sintoma` (INT): Identificador único del síntoma (Clave Primaria).
- `nombre` (VARCHAR): Nombre del síntoma.
- `descripcion` (TEXT): Descripción detallada del síntoma.

### Diagnostico
Registra los diagnósticos realizados a los pacientes.
- `id_diagnostico` (INT): Identificador único del diagnóstico (Clave Primaria).
- `descripcion` (TEXT): Descripción del diagnóstico.
- `fecha` (DATE): Fecha en que se realizó el diagnóstico.
- `paciente_id` (INT): Clave Foránea que referencia al `Paciente` al que se le realizó el diagnóstico.
- `usuario_id` (INT): Clave Foránea que referencia al `Usuario` (médico) que realizó el diagnóstico.

### Historial
Almacena el historial médico de los pacientes.
- `id_historial` (INT): Identificador único del historial (Clave Primaria).
- `paciente_id` (INT): Clave Foránea que referencia al `Paciente` al que pertenece el historial.
- `diagnostico_id` (INT): Clave Foránea que referencia al `Diagnostico` asociado a este historial.
- `tratamientos` (TEXT): Descripción de los tratamientos aplicados.
- `fecha` (DATE): Fecha del registro en el historial.

### Usuario
Representa a los usuarios del sistema, como médicos o visitantes.
- `id_usuario` (INT): Identificador único del usuario (Clave Primaria).
- `nombre` (VARCHAR): Nombre del usuario.
- `email` (VARCHAR): Correo electrónico del usuario.
- `password` (VARCHAR): Contraseña del usuario (debería estar hasheada).
- `rol` (ENUM('medico', 'visitante')): Rol del usuario en el sistema.

### Diagnostico_Sintoma
Tabla de unión para la relación muchos a muchos entre `Diagnostico` y `Sintoma`.
- `id_diagnostico` (INT): Clave Foránea que referencia al `Diagnostico`.
- `id_sintoma` (INT): Clave Foránea que referencia al `Sintoma`.

## Relaciones

- **Paciente tiene Diagnostico (1:N):** Un paciente puede tener múltiples diagnósticos, pero un diagnóstico pertenece a un solo paciente.
- **Paciente posee Historial (1:N):** Un paciente puede tener múltiples registros en su historial, pero un registro de historial pertenece a un solo paciente.
- **Diagnostico genera Historial (1:N):** Un diagnóstico puede generar múltiples entradas en el historial (aunque en este modelo parece ser 1:1 o 1:N dependiendo de la interpretación, se asume que un diagnóstico puede ser parte de varios historiales o un historial puede contener varios diagnósticos).
- **Usuario crea Diagnostico (1:N):** Un usuario (médico) puede crear múltiples diagnósticos, pero un diagnóstico es creado por un solo usuario.
- **Diagnostico incluye Sintoma (N:M a través de Diagnostico_Sintoma):** Un diagnóstico puede estar asociado a múltiples síntomas, y un síntoma puede estar presente en múltiples diagnósticos.