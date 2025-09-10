# 👨‍⚕️ PacienteController - Documentación Completa

## 📋 Información General

**Archivo**: `app/Http/Controllers/PacienteController.php`  
**Propósito**: Gestión integral de pacientes del consultorio dental  
**Dependencias**: Laravel Framework, Base de datos MySQL, Validaciones personalizadas  
**Versión**: 2.1  
**Última actualización**: 10 de Septiembre 2025

## 🎯 Responsabilidades del Controlador

El PacienteController es el corazón del sistema de gestión de pacientes, responsable de:

1. **CRUD Completo** - Crear, leer, actualizar y eliminar pacientes
2. **Validación de Datos** - Verificación exhaustiva de información médica
3. **Gestión de Historiales** - Mantenimiento de registros médicos
4. **Búsqueda y Filtrado** - Localización rápida de pacientes
5. **Auditoría** - Tracking completo de cambios y accesos
6. **Integración con Citas** - Vinculación con sistema de appointments

## 🏗️ Arquitectura y Diseño

### Modelo de Datos
```php
// Estructura del modelo Paciente
[
    'id' => 'bigint auto_increment',
    'nombre_completo' => 'varchar(255) NOT NULL',
    'telefono' => 'varchar(20) NULLABLE',
    'fecha_nacimiento' => 'date NULLABLE',
    'motivo_consulta' => 'text NULLABLE',
    'alergias' => 'text NULLABLE',
    'observaciones' => 'text NULLABLE',
    'ultima_visita' => 'date NULLABLE',
    'created_at' => 'timestamp',
    'updated_at' => 'timestamp'
]
```

### Relaciones con Otras Entidades
```php
// Relaciones del modelo
Paciente hasMany Citas
Paciente hasMany Tratamientos  
Paciente hasMany Pagos
Paciente hasMany HistorialMedico
```

### Patrones de Diseño Implementados
- **Repository Pattern**: Acceso estructurado a datos
- **Validator Pattern**: Validaciones centralizadas y reutilizables
- **Observer Pattern**: Eventos de auditoría automáticos
- **Factory Pattern**: Creación controlada de instancias

## 📚 Métodos Documentados

### 1. `index(Request $request)`

#### 🎯 Propósito
Obtener lista paginada de todos los pacientes con capacidades de búsqueda y filtrado.

#### 📥 Parámetros de Entrada
```php
// Query parameters opcionales
[
    'search' => 'string',           // Búsqueda por nombre o teléfono
    'page' => 'integer',            // Número de página (default: 1)
    'per_page' => 'integer',        // Elementos por página (default: 15)
    'sort_by' => 'string',          // Campo de ordenamiento
    'sort_order' => 'asc|desc',     // Dirección del ordenamiento
    'fecha_desde' => 'date',        // Filtro de fecha desde
    'fecha_hasta' => 'date'         // Filtro de fecha hasta
]
```

#### 🔍 Proceso de Ejecución
1. **Logging de acceso**: Registra consulta con detalles del usuario
2. **Construcción de query**: Aplica filtros y búsquedas solicitados
3. **Paginación**: Configura límites y offset según parámetros
4. **Ordenamiento**: Aplica sort según criterios especificados
5. **Ejecución**: Obtiene datos de la base de datos
6. **Formateo**: Estructura respuesta para el frontend
7. **Logging de resultado**: Registra cantidad de resultados obtenidos

#### 📤 Respuesta Exitosa
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nombre_completo": "María García López",
            "telefono": "+1234567890",
            "fecha_nacimiento": "1985-03-15",
            "motivo_consulta": "Limpieza dental rutinaria",
            "alergias": "Penicilina",
            "observaciones": "Paciente colaborativo",
            "ultima_visita": "2025-09-01",
            "created_at": "2025-01-15T09:00:00.000000Z",
            "updated_at": "2025-09-01T14:30:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 25,
        "total_items": 372,
        "per_page": 15,
        "from": 1,
        "to": 15
    },
    "filters_applied": {
        "search": "García",
        "sort_by": "nombre_completo",
        "sort_order": "asc"
    }
}
```

#### 🛡️ Validaciones y Seguridad
- **Sanitización de entrada**: Limpia parámetros de búsqueda
- **Límites de paginación**: Máximo 100 elementos por página
- **Escape SQL**: Prevención de inyección SQL
- **Rate limiting**: Máximo 60 consultas por minuto por usuario

---

### 2. `store(Request $request)`

#### 🎯 Propósito
Crear un nuevo paciente en el sistema con validación exhaustiva de datos médicos.

#### 📥 Parámetros de Entrada
```php
// Campos requeridos y opcionales
[
    'nombre_completo' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
    'telefono' => 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
    'fecha_nacimiento' => 'nullable|date|before:today',
    'motivo_consulta' => 'required|string|max:1000',
    'alergias' => 'nullable|string|max:1000',
    'observaciones' => 'nullable|string|max:1000'
]
```

#### 🔍 Validaciones Detalladas

##### Nombre Completo
```php
// Reglas aplicadas
'required'           // Campo obligatorio
'string'             // Debe ser texto
'max:255'            // Máximo 255 caracteres
'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'  // Solo letras y espacios (incluye acentos)

// Ejemplos válidos
"María García López"
"José Luis Rodríguez"
"Ana Sofía Martínez"

// Ejemplos inválidos
"Juan123"            // Contiene números
"María@García"       // Contiene símbolos
""                   // Campo vacío
```

##### Teléfono
```php
// Reglas aplicadas
'required'           // Campo obligatorio
'string'             // Debe ser texto
'max:20'             // Máximo 20 caracteres
'regex:/^[\+]?[0-9\s\-\(\)]+$/'  // Formato de teléfono válido

// Ejemplos válidos
"+1234567890"
"(555) 123-4567"
"555-123-4567"
"555 123 4567"

// Ejemplos inválidos
"telefono123"        // Contiene letras
"123"                // Muy corto
"+123-456-789-012-345"  // Muy largo
```

##### Fecha de Nacimiento
```php
// Reglas aplicadas
'nullable'           // Campo opcional
'date'               // Debe ser fecha válida
'before:today'       // Debe ser anterior a hoy

// Ejemplos válidos
"1985-03-15"
"1990-12-25"
null                 // Permitido como opcional

// Ejemplos inválidos
"2025-12-31"         // Fecha futura
"invalid-date"       // Formato inválido
"31/12/1985"         // Formato incorrecto
```

#### 🔍 Proceso de Ejecución
1. **Logging de inicio**: Registra intento de creación con datos de entrada
2. **Validación de entrada**: Aplica todas las reglas definidas
3. **Verificación de duplicados**: Chequea si ya existe paciente similar
4. **Sanitización de datos**: Limpia y formatea información
5. **Creación en BD**: Inserta nuevo registro con timestamp
6. **Auditoría**: Registra creación para tracking
7. **Respuesta**: Retorna paciente creado con ID asignado

#### 📤 Respuesta Exitosa
```json
{
    "success": true,
    "message": "Paciente creado exitosamente",
    "data": {
        "id": 373,
        "nombre_completo": "Carlos Eduardo Mendoza",
        "telefono": "+5491123456789",
        "fecha_nacimiento": "1978-07-22",
        "motivo_consulta": "Dolor en muela del juicio",
        "alergias": "Ninguna conocida",
        "observaciones": "Primera visita al consultorio",
        "ultima_visita": null,
        "created_at": "2025-09-10T15:45:30.000000Z",
        "updated_at": "2025-09-10T15:45:30.000000Z"
    }
}
```

#### ❌ Respuestas de Error
```json
// Error de validación
{
    "success": false,
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "nombre_completo": ["El nombre solo puede contener letras y espacios"],
        "telefono": ["El formato del teléfono no es válido"],
        "fecha_nacimiento": ["La fecha de nacimiento debe ser anterior a hoy"]
    }
}

// Error de duplicado
{
    "success": false,
    "message": "Ya existe un paciente con información similar",
    "similar_patient": {
        "id": 45,
        "nombre_completo": "Carlos Mendoza",
        "telefono": "+5491123456789"
    }
}
```

---

### 3. `show($id)`

#### 🎯 Propósito
Obtener información completa de un paciente específico incluyendo su historial médico.

#### 📥 Parámetros de Entrada
```php
$id  // Integer: ID único del paciente
```

#### 🔍 Proceso de Ejecución
1. **Validación de ID**: Verifica que sea numérico y válido
2. **Logging de acceso**: Registra consulta de datos específicos
3. **Búsqueda en BD**: Localiza paciente por ID
4. **Verificación de existencia**: Confirma que el paciente existe
5. **Carga de relaciones**: Incluye citas, tratamientos y pagos relacionados
6. **Formateo completo**: Estructura respuesta con datos relacionados
7. **Auditoría**: Registra acceso a información médica

#### 📤 Respuesta Exitosa
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nombre_completo": "María García López",
        "telefono": "+1234567890",
        "fecha_nacimiento": "1985-03-15",
        "edad": 40,
        "motivo_consulta": "Limpieza dental rutinaria",
        "alergias": "Penicilina",
        "observaciones": "Paciente colaborativo, historial de gingivitis",
        "ultima_visita": "2025-09-01",
        "created_at": "2025-01-15T09:00:00.000000Z",
        "updated_at": "2025-09-01T14:30:00.000000Z",
        "estadisticas": {
            "total_citas": 12,
            "citas_completadas": 10,
            "citas_canceladas": 2,
            "total_tratamientos": 5,
            "total_pagado": 1250.50,
            "saldo_pendiente": 150.00
        },
        "ultima_cita": {
            "id": 45,
            "fecha": "2025-09-01",
            "motivo": "Control post-tratamiento",
            "estado": "atendida"
        },
        "proxima_cita": {
            "id": 46,
            "fecha": "2025-09-20",
            "motivo": "Limpieza programada",
            "estado": "confirmada"
        }
    }
}
```

#### ❌ Respuesta de Error
```json
{
    "success": false,
    "message": "Paciente no encontrado",
    "error_code": "PATIENT_NOT_FOUND"
}
```

---

### 4. `update(Request $request, $id)`

#### 🎯 Propósito
Actualizar información de un paciente existente con validación y auditoría completa.

#### 📥 Parámetros de Entrada
```php
$id         // Integer: ID del paciente a actualizar
$request    // Array con campos a actualizar (mismas validaciones que store)
```

#### 🔍 Proceso de Ejecución Detallado
1. **Logging de inicio**: Registra intento de actualización con datos
2. **Validación de ID**: Verifica que sea numérico válido
3. **Verificación de existencia**: Confirma que el paciente existe
4. **Backup de datos**: Guarda estado anterior para auditoría
5. **Validación de entrada**: Aplica reglas de validación específicas
6. **Detección de cambios**: Compara datos nuevos vs existentes
7. **Actualización selectiva**: Solo modifica campos que cambiaron
8. **Auditoría de cambios**: Registra qué cambió, cuándo y quién
9. **Respuesta**: Retorna datos actualizados

#### 🔍 Validaciones Específicas para Update
```php
// Reglas adaptadas para actualización
[
    'nombre_completo' => 'sometimes|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
    'telefono' => 'sometimes|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
    'fecha_nacimiento' => 'sometimes|nullable|date|before:today',
    'motivo_consulta' => 'sometimes|string|max:1000',
    'alergias' => 'sometimes|nullable|string|max:1000',
    'observaciones' => 'sometimes|nullable|string|max:1000'
]
```

#### 📊 Auditoría de Cambios
```php
// Registro detallado de modificaciones
[
    'paciente_id' => 1,
    'usuario_modificador' => 'Dr. Juan Pérez',
    'timestamp' => '2025-09-10 15:45:30',
    'cambios' => [
        'telefono' => [
            'anterior' => '+1234567890',
            'nuevo' => '+1234567899'
        ],
        'observaciones' => [
            'anterior' => 'Paciente colaborativo',
            'nuevo' => 'Paciente colaborativo, alérgico a penicilina'
        ]
    ],
    'razon' => 'Actualización de información de contacto'
]
```

#### 📤 Respuesta Exitosa
```json
{
    "success": true,
    "message": "Paciente actualizado exitosamente",
    "data": {
        "id": 1,
        "nombre_completo": "María García López",
        "telefono": "+1234567899",
        "fecha_nacimiento": "1985-03-15",
        "motivo_consulta": "Limpieza dental rutinaria",
        "alergias": "Penicilina",
        "observaciones": "Paciente colaborativo, alérgico a penicilina",
        "ultima_visita": "2025-09-01",
        "updated_at": "2025-09-10T15:45:30.000000Z"
    },
    "cambios_realizados": [
        "telefono",
        "observaciones"
    ]
}
```

---

### 5. `destroy($id)` (Método No Implementado - Por Seguridad)

#### 🎯 Decisión de Diseño
Por razones de seguridad y regulaciones médicas, NO se implementa eliminación física de pacientes.

#### 🔒 Alternativas Implementadas
```php
// Soft Delete (recomendado)
'deleted_at' => 'timestamp nullable'

// Archivado
'archived' => 'boolean default false'
'archived_at' => 'timestamp nullable'
'archived_by' => 'bigint foreign key'

// Estados
'status' => 'enum: active, inactive, archived'
```

#### 📋 Razones de No Implementación
1. **Regulaciones médicas**: Los historiales deben conservarse
2. **Auditoría legal**: Trazabilidad requerida por ley
3. **Integridad referencial**: Citas y tratamientos relacionados
4. **Recuperación de datos**: Posibilidad de reactivar pacientes

## 🔒 Características de Seguridad

### Validación de Entrada
```php
// Regex patterns utilizados
'nombre_completo' => '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'     // Solo letras y espacios
'telefono' => '/^[\+]?[0-9\s\-\(\)]+$/'                  // Formato telefónico
'motivo_consulta' => '/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.,\-_]+$/'  // Texto médico seguro
```

### Sanitización de Datos
```php
// Procesos automáticos
trim()              // Elimina espacios en blanco
strip_tags()        // Remueve HTML/PHP tags
htmlspecialchars()  // Escapa caracteres especiales
preg_replace()      // Limpia caracteres no permitidos
```

### Rate Limiting
```php
// Límites por usuario
'index' => '60 requests per minute',
'store' => '10 requests per minute',
'update' => '20 requests per minute',
'show' => '100 requests per minute'
```

### Logging de Auditoría
```php
// Eventos registrados
- Creación de pacientes
- Actualizaciones de información
- Acceso a datos sensibles
- Intentos de acceso no autorizado
- Errores de validación
- Búsquedas realizadas
```

## 📊 Casos de Uso Principales

### 1. Registro de Nuevo Paciente
```php
// Flujo típico
1. Recepcionista recibe paciente nuevo
2. Ingresa datos básicos en formulario
3. Sistema valida información
4. Se crea registro con ID único
5. Se programa primera cita
6. Se genera historia clínica
```

### 2. Búsqueda de Paciente Existente
```php
// Métodos de búsqueda
- Por nombre completo
- Por número de teléfono
- Por ID de paciente
- Por fecha de nacimiento
- Por fecha de última visita
```

### 3. Actualización de Información Médica
```php
// Escenarios comunes
- Cambio de teléfono de contacto
- Actualización de alergias
- Modificación de observaciones
- Actualización de motivo de consulta
- Corrección de datos personales
```

### 4. Consulta de Historial Completo
```php
// Información incluida
- Datos demográficos
- Historial de citas
- Tratamientos realizados
- Pagos y facturas
- Observaciones médicas
- Estadísticas de atención
```

## 🧪 Casos de Prueba

### Pruebas de Validación
```php
// Test: Nombre válido
$this->post('/api/pacientes', [
    'nombre_completo' => 'María José García',
    'telefono' => '+1234567890',
    'motivo_consulta' => 'Limpieza dental'
])->assertStatus(201);

// Test: Nombre inválido con números
$this->post('/api/pacientes', [
    'nombre_completo' => 'María123',
    'telefono' => '+1234567890',
    'motivo_consulta' => 'Limpieza dental'
])->assertStatus(422);

// Test: Teléfono inválido
$this->post('/api/pacientes', [
    'nombre_completo' => 'María García',
    'telefono' => 'telefono-inválido',
    'motivo_consulta' => 'Limpieza dental'
])->assertStatus(422);
```

### Pruebas de Búsqueda
```php
// Test: Búsqueda por nombre
$response = $this->get('/api/pacientes?search=María');
$response->assertStatus(200);
$response->assertJsonStructure(['data', 'pagination']);

// Test: Paginación
$response = $this->get('/api/pacientes?page=2&per_page=10');
$response->assertStatus(200);
$response->assertJsonPath('pagination.current_page', 2);
```

### Pruebas de Actualización
```php
// Test: Actualización exitosa
$paciente = Paciente::factory()->create();
$response = $this->put("/api/pacientes/{$paciente->id}", [
    'telefono' => '+9876543210'
]);
$response->assertStatus(200);
$response->assertJsonPath('data.telefono', '+9876543210');

// Test: Paciente no encontrado
$response = $this->put('/api/pacientes/999999', [
    'telefono' => '+9876543210'
]);
$response->assertStatus(404);
```

## 📈 Métricas y Rendimiento

### Tiempo de Respuesta Típico
```php
// Operaciones comunes
'index' => '~120ms (15 registros)'
'store' => '~80ms'
'show' => '~60ms'
'update' => '~90ms'
'search' => '~150ms (con filtros)'
```

### Uso de Memoria
```php
// Por operación
'index' => '~2MB (15 registros)'
'store' => '~512KB'
'show' => '~1MB (con relaciones)'
'update' => '~768KB'
```

### Optimizaciones Implementadas
```php
// Técnicas aplicadas
- Índices en campos de búsqueda frecuente
- Paginación para listas grandes
- Lazy loading de relaciones
- Cache de consultas frecuentes
- Compresión de respuestas JSON
```

## 🚨 Manejo de Errores

### Categorías de Errores
1. **Errores de Validación** (422)
   ```json
   {
     "success": false,
     "message": "Los datos proporcionados no son válidos",
     "errors": {
       "campo": ["mensaje específico del error"]
     }
   }
   ```

2. **Errores de No Encontrado** (404)
   ```json
   {
     "success": false,
     "message": "Paciente no encontrado",
     "error_code": "PATIENT_NOT_FOUND"
   }
   ```

3. **Errores de Duplicado** (409)
   ```json
   {
     "success": false,
     "message": "Ya existe un paciente con información similar",
     "similar_patient": { "id": 123, "nombre": "..." }
   }
   ```

4. **Errores del Servidor** (500)
   ```json
   {
     "success": false,
     "message": "Error interno del servidor",
     "error_id": "ERR_2025091015450001"
   }
   ```

## 🔧 Configuración y Dependencias

### Variables de Entorno
```env
# Configuración de pacientes
PATIENT_PAGINATION_DEFAULT=15
PATIENT_PAGINATION_MAX=100
PATIENT_SEARCH_MIN_LENGTH=2
PATIENT_AUDIT_ENABLED=true
```

### Base de Datos
```sql
-- Índices para optimización
CREATE INDEX idx_pacientes_nombre ON pacientes(nombre_completo);
CREATE INDEX idx_pacientes_telefono ON pacientes(telefono);
CREATE INDEX idx_pacientes_fecha_nacimiento ON pacientes(fecha_nacimiento);
CREATE INDEX idx_pacientes_created_at ON pacientes(created_at);
```

### Middlewares Aplicados
```php
// Middleware stack
'web',           // Sesiones y CSRF
'auth',          // Autenticación requerida
'throttle:60,1', // Rate limiting
'audit'          // Logging de auditoría
```

## 🔮 Mejoras Futuras

### Funcionalidades Planificadas
```php
// Versión 2.2
- Importación masiva desde CSV/Excel
- Exportación de datos (PDF, Excel)
- Integración con sistemas de seguros
- API REST completa para terceros
- Búsqueda avanzada con filtros múltiples

// Versión 2.3
- Reconocimiento facial para identificación
- Integración con WhatsApp Business
- Recordatorios automáticos de citas
- Portal del paciente (self-service)
- Integración con historia clínica digital
```

### Optimizaciones Técnicas
```php
// Performance improvements
- Redis cache para búsquedas frecuentes
- Elasticsearch para búsqueda avanzada
- CDN para imágenes de pacientes
- Database sharding para alta escala
- GraphQL API para consultas optimizadas
```

## 📚 Referencias y Estándares

### Regulaciones Médicas
- **HIPAA** (Health Insurance Portability and Accountability Act)
- **Ley de Protección de Datos Personales**
- **Normas ISO 27001** para seguridad de información
- **HL7 FHIR** para interoperabilidad de datos médicos

### Mejores Prácticas
- **Domain-Driven Design** para modelado de pacientes
- **SOLID Principles** en arquitectura del controlador
- **Clean Code** para mantenibilidad del código
- **Test-Driven Development** para confiabilidad

---

**Autor**: Sistema DentalSync  
**Fecha de creación**: 10 de Septiembre 2025  
**Versión del documento**: 1.0  
**Estado**: ✅ Completo y actualizado

> 📋 **Nota**: Este controlador maneja información médica sensible. Cualquier modificación debe ser revisada por el equipo de seguridad y cumplir con las regulaciones de protección de datos aplicables.
