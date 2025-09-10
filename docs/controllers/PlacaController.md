# PlacaController - Documentación Técnica Completa

## 📋 Información General

| Propiedad | Valor |
|-----------|--------|
| **Controlador** | `App\Http\Controllers\PlacaController` |
| **Propósito** | Gestión integral de placas dentales (radiografías) |
| **Funcionalidad Principal** | Subida, almacenamiento, visualización y administración de imágenes radiográficas dentales |
| **Tipo de Datos** | Archivos multimedia (imágenes y PDFs) con metadatos clínicos |
| **Integración** | Sistema de gestión de pacientes, almacenamiento de archivos, validación médica |

## 🏗️ Arquitectura del Controlador

### Dependencias y Imports
```php
use App\Models\PlacaDental;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
```

### Estructura de Métodos
1. **index()** - Listado de placas con filtros y paginación
2. **show($id)** - Visualización individual de placa dental
3. **store(Request $request)** - Subida y registro de nuevas placas
4. **update(Request $request, $id)** - Actualización de metadatos y archivos
5. **destroy($id)** - Eliminación segura de placas y archivos

## 📊 Análisis Detallado de Métodos

### 1. index() - Listado y Filtrado de Placas

```php
public function index(Request $request)
```

#### **Propósito**
Proporciona un sistema completo de listado y filtrado de placas dentales con capacidades de búsqueda avanzada, paginación y ordenamiento.

#### **Funcionalidades Implementadas**

##### **Sistema de Filtros Múltiples**
- **Filtro por Paciente**: `paciente_id` - Filtra placas de un paciente específico
- **Filtro por Tipo**: `tipo` - Filtra por tipo radiográfico específico
- **Filtro por Rango de Fechas**: `fecha_inicio` y `fecha_fin` - Búsqueda temporal
- **Búsqueda de Texto**: `search` - Búsqueda en lugar y nombre de paciente

##### **Tipos de Radiografías Soportados**
- **panoramica**: Radiografía panorámica completa
- **periapical**: Radiografía de raíz específica
- **bitewing**: Radiografía de mordida
- **lateral**: Radiografía lateral de cráneo
- **oclusal**: Radiografía oclusal

##### **Sistema de Paginación**
- Paginación configurable (por defecto 10 elementos)
- Ordenamiento por fecha de creación (más recientes primero)
- Carga optimizada con relaciones Eloquent

#### **Validaciones de Entrada**
```php
$validatedData = $request->validate([
    'paciente_id' => 'sometimes|exists:pacientes,id',
    'tipo' => 'sometimes|in:panoramica,periapical,bitewing,lateral,oclusal',
    'fecha_inicio' => 'sometimes|date',
    'fecha_fin' => 'sometimes|date|after_or_equal:fecha_inicio',
    'search' => 'sometimes|string|max:255',
    'per_page' => 'sometimes|integer|min:1|max:100'
]);
```

#### **Formato de Respuesta**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "fecha": "2024-01-15",
            "lugar": "Clínica Principal",
            "tipo": "panoramica",
            "paciente_id": 5,
            "paciente_nombre": "Juan Pérez",
            "archivo_url": "http://localhost/storage/placas_dentales/uuid.jpg",
            "created_at": "2024-01-15T10:30:00Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 25,
        "last_page": 3
    }
}
```

### 2. show($id) - Visualización Individual

```php
public function show($id)
```

#### **Propósito**
Obtiene los detalles completos de una placa dental específica, incluyendo metadatos y URL del archivo para visualización.

#### **Funcionalidades**
- **Carga de Relaciones**: Incluye información del paciente asociado
- **Manejo de Archivos**: Genera URL públicas para acceso a archivos
- **Validación de Existencia**: Valida que la placa exista (findOrFail)

#### **Características de Seguridad**
- Validación automática de existencia del registro
- URLs de storage seguras generadas dinámicamente
- Protección contra acceso a archivos inexistentes

#### **Formato de Respuesta**
```json
{
    "id": 1,
    "fecha": "2024-01-15",
    "lugar": "Clínica Principal",
    "tipo": "panoramica",
    "paciente_id": 5,
    "paciente_nombre": "Juan Pérez González",
    "archivo_url": "http://localhost/storage/placas_dentales/unique-id.jpg",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
}
```

### 3. store(Request $request) - Subida de Nuevas Placas

```php
public function store(Request $request)
```

#### **Propósito**
Maneja la subida completa de nuevas placas dentales, incluyendo validación de archivos, almacenamiento seguro y registro en base de datos.

#### **Validaciones Exhaustivas**

##### **Validaciones de Datos**
```php
$request->validate([
    'paciente_id' => 'required|exists:pacientes,id',
    'fecha' => 'required|date',
    'lugar' => 'required|string|max:255',
    'tipo' => 'required|in:panoramica,periapical,bitewing,lateral,oclusal',
    'archivo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240'
]);
```

##### **Validaciones de Archivo**
- **Formatos Permitidos**: JPG, JPEG, PNG, PDF
- **Tamaño Máximo**: 10MB (10,240 KB)
- **Validación de Tipo MIME**: Verificación del tipo real del archivo
- **Obligatoriedad**: El archivo es requerido para crear la placa

#### **Sistema de Almacenamiento**

##### **Generación de Nombres Únicos**
```php
$nombreArchivo = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
```
- Utiliza UUID para garantizar nombres únicos
- Preserva la extensión original del archivo
- Evita conflictos de nombres y sobrescritura

##### **Estructura de Almacenamiento**
- **Disco**: `public` (accesible vía web)
- **Directorio**: `placas_dentales/`
- **Nomenclatura**: UUID + extensión original
- **Ejemplo**: `placas_dentales/123e4567-e89b-12d3-a456-426614174000.jpg`

#### **Proceso de Creación**
1. Validación de datos de entrada
2. Subida y almacenamiento del archivo
3. Creación del registro en base de datos
4. Carga de relaciones (paciente)
5. Respuesta con datos completos

#### **Manejo de Errores**
- Captura de excepciones completa
- Logging detallado de errores
- Respuestas estructuradas para el frontend
- Rollback automático en caso de falla

#### **Respuesta de Éxito**
```json
{
    "success": true,
    "message": "Placa dental subida correctamente",
    "placa": {
        "id": 25,
        "fecha": "2024-01-15",
        "lugar": "Clínica Principal",
        "tipo": "panoramica",
        "paciente_id": 5,
        "paciente_nombre": "Juan Pérez González",
        "archivo_url": "http://localhost/storage/placas_dentales/uuid.jpg",
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

### 4. update(Request $request, $id) - Actualización

```php
public function update(Request $request, $id)
```

#### **Propósito**
Permite la actualización parcial o completa de metadatos de placas dentales, incluyendo reemplazo de archivos.

#### **Validaciones de Actualización**
```php
$request->validate([
    'paciente_id' => 'sometimes|exists:pacientes,id',
    'fecha' => 'sometimes|date',
    'lugar' => 'sometimes|string|max:255',
    'tipo' => 'sometimes|in:panoramica,periapical,bitewing,lateral,oclusal',
    'archivo' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:10240'
]);
```

#### **Características de Actualización**

##### **Actualización Parcial**
- **Campo por Campo**: Solo actualiza campos proporcionados
- **Validación `sometimes`**: Campos opcionales en actualización
- **Preservación de Datos**: Mantiene valores existentes si no se proporcionan nuevos

##### **Reemplazo de Archivos**
```php
if ($request->hasFile('archivo')) {
    // Eliminar archivo anterior
    if ($placa->archivo_url) {
        Storage::disk('public')->delete($placa->archivo_url);
    }
    
    // Subir nuevo archivo
    $archivo = $request->file('archivo');
    $nombreArchivo = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
    $rutaArchivo = $archivo->storeAs('placas_dentales', $nombreArchivo, 'public');
    $placa->archivo_url = $rutaArchivo;
}
```

#### **Gestión de Archivos en Actualización**
- **Eliminación Segura**: Elimina archivo anterior antes de subir nuevo
- **Atomicidad**: Solo elimina si la subida del nuevo es exitosa
- **Limpieza de Storage**: Evita acumulación de archivos huérfanos

#### **Respuesta de Actualización**
```json
{
    "success": true,
    "message": "Placa dental actualizada correctamente",
    "placa": {
        "id": 25,
        "fecha": "2024-01-16",
        "lugar": "Clínica Secundaria",
        "tipo": "periapical",
        "paciente_id": 5,
        "paciente_nombre": "Juan Pérez González",
        "archivo_url": "http://localhost/storage/placas_dentales/new-uuid.jpg",
        "updated_at": "2024-01-16T14:45:00Z"
    }
}
```

### 5. destroy($id) - Eliminación Segura

```php
public function destroy($id)
```

#### **Propósito**
Elimina completamente una placa dental, incluyendo el archivo del storage y el registro de base de datos.

#### **Proceso de Eliminación**
1. **Verificación de Existencia**: Encuentra el registro o falla
2. **Eliminación de Archivo**: Remueve archivo del storage
3. **Eliminación de Registro**: Remueve registro de base de datos
4. **Confirmación**: Respuesta de éxito o error

#### **Seguridad en Eliminación**
- **Transaccional**: Elimina archivo y registro o falla completamente
- **Validación Previa**: Verifica existencia antes de eliminar
- **Limpieza Completa**: No deja archivos huérfanos en storage

#### **Código de Eliminación**
```php
// Eliminar archivo del storage
if ($placa->archivo_url) {
    Storage::disk('public')->delete($placa->archivo_url);
}

// Eliminar registro de la base de datos
$placa->delete();
```

#### **Respuesta de Eliminación**
```json
{
    "success": true,
    "message": "Placa dental eliminada correctamente"
}
```

## 🔒 Aspectos de Seguridad

### Validación de Archivos
- **Tipos MIME Verificados**: Validación estricta de formatos
- **Tamaño Controlado**: Límite de 10MB por archivo
- **Nombres Seguros**: UUID previene ataques de path traversal

### Gestión de Storage
- **Disk Público**: Archivos accesibles pero controlados
- **URLs Dinámicas**: Generación segura de URLs de acceso
- **Limpieza Automática**: Eliminación de archivos en actualizaciones

### Validación de Datos
- **Existencia de Pacientes**: Validación de FK antes de asignación
- **Tipos Médicos**: Lista cerrada de tipos radiográficos
- **Fechas Válidas**: Validación de formato de fechas

## 📈 Casos de Uso del Sistema

### 1. Subida de Radiografía Panorámica
```json
{
    "paciente_id": 15,
    "fecha": "2024-01-15",
    "lugar": "Clínica Principal - Sala 2",
    "tipo": "panoramica",
    "archivo": "[FILE_UPLOAD]"
}
```

### 2. Búsqueda de Placas por Paciente
```http
GET /placas?paciente_id=15&tipo=panoramica&fecha_inicio=2024-01-01
```

### 3. Actualización de Metadatos
```json
{
    "lugar": "Clínica Secundaria - Sala 1",
    "fecha": "2024-01-16"
}
```

### 4. Reemplazo de Archivo Radiográfico
```json
{
    "archivo": "[NEW_FILE_UPLOAD]"
}
```

## 🔧 Configuración y Dependencias

### Dependencias del Modelo
- **PlacaDental**: Modelo principal
- **Paciente**: Relación belongsTo
- **Storage**: Sistema de archivos Laravel

### Configuración de Storage
```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
]
```

### Migraciones Relacionadas
- Tabla `placas_dentales` con campos: id, paciente_id, fecha, lugar, tipo, archivo_url
- FK a tabla `pacientes`
- Índices en paciente_id y fecha

## 🧪 Testing y Validación

### Casos de Prueba Esenciales

#### Test de Subida de Archivo
```php
public function test_store_placa_with_valid_file()
{
    $paciente = Paciente::factory()->create();
    $file = UploadedFile::fake()->image('radiografia.jpg', 800, 600);
    
    $response = $this->postJson('/api/placas', [
        'paciente_id' => $paciente->id,
        'fecha' => '2024-01-15',
        'lugar' => 'Clínica Test',
        'tipo' => 'panoramica',
        'archivo' => $file
    ]);
    
    $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'placa']);
}
```

#### Test de Validación de Formato
```php
public function test_store_rejects_invalid_file_format()
{
    $paciente = Paciente::factory()->create();
    $file = UploadedFile::fake()->create('documento.txt', 100, 'text/plain');
    
    $response = $this->postJson('/api/placas', [
        'paciente_id' => $paciente->id,
        'fecha' => '2024-01-15',
        'lugar' => 'Clínica Test',
        'tipo' => 'panoramica',
        'archivo' => $file
    ]);
    
    $response->assertStatus(422);
}
```

#### Test de Eliminación de Archivo
```php
public function test_destroy_removes_file_and_record()
{
    Storage::fake('public');
    $placa = PlacaDental::factory()->create([
        'archivo_url' => 'placas_dentales/test-file.jpg'
    ]);
    
    Storage::disk('public')->put('placas_dentales/test-file.jpg', 'test content');
    
    $response = $this->deleteJson("/api/placas/{$placa->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('placas_dentales', ['id' => $placa->id]);
    Storage::disk('public')->assertMissing('placas_dentales/test-file.jpg');
}
```

## 📊 Métricas y Monitoreo

### Métricas Importantes
- **Tamaño Promedio de Archivos**: Monitoreo de uso de storage
- **Tipos de Radiografías Más Comunes**: Análisis de distribución
- **Frecuencia de Actualizaciones**: Seguimiento de cambios
- **Errores de Subida**: Monitoring de fallos

### Logs de Auditoría
```php
\Log::info('PlacaController@store - Nueva placa creada', [
    'placa_id' => $placa->id,
    'paciente_id' => $placa->paciente_id,
    'tipo' => $placa->tipo,
    'archivo_size' => $archivo->getSize(),
    'usuario_id' => auth()->id()
]);
```

## 🔄 Flujo de Trabajo Típico

### 1. Registro de Nueva Radiografía
1. Dentista toma radiografía del paciente
2. Digitaliza o tiene archivo digital
3. Accede al sistema y selecciona paciente
4. Sube archivo con metadatos (fecha, lugar, tipo)
5. Sistema valida, almacena y confirma

### 2. Consulta de Historial Radiográfico
1. Usuario busca paciente específico
2. Filtra por tipo de radiografía o fecha
3. Visualiza lista de placas disponibles
4. Accede a archivos individuales para diagnóstico

### 3. Actualización de Información
1. Usuario identifica necesidad de corrección
2. Accede a placa específica
3. Actualiza metadatos o reemplaza archivo
4. Sistema preserva historial de cambios

## 🚀 Optimizaciones y Mejoras Futuras

### Optimizaciones de Rendimiento
- **Compresión de Imágenes**: Reducir tamaño sin perder calidad diagnóstica
- **Thumbnails**: Generar previsualizaciones para listados
- **CDN Integration**: Distribución de archivos optimizada
- **Lazy Loading**: Carga bajo demanda de archivos grandes

### Funcionalidades Avanzadas
- **Anotaciones en Imágenes**: Marcado de áreas de interés
- **Comparación Temporal**: Visualización de evolución
- **Exportación Masiva**: Descarga de conjuntos de placas
- **Integración DICOM**: Soporte para formatos médicos estándar

### Mejoras de Seguridad
- **Cifrado de Archivos**: Protección adicional de datos médicos
- **Watermarking**: Marcado de autoría y trazabilidad
- **Access Control Lists**: Permisos granulares por archivo
- **Audit Trail Completo**: Seguimiento detallado de accesos

## 📋 Resumen de Funcionalidades

| Función | Implementada | Descripción |
|---------|-------------|-------------|
| ✅ Listado con Filtros | SÍ | Búsqueda avanzada por paciente, tipo, fecha, texto |
| ✅ Visualización Individual | SÍ | Detalles completos con URL de archivo |
| ✅ Subida de Archivos | SÍ | Validación completa, almacenamiento seguro |
| ✅ Actualización Parcial | SÍ | Metadatos y reemplazo de archivos |
| ✅ Eliminación Segura | SÍ | Limpieza completa de archivos y registros |
| ✅ Validación de Formatos | SÍ | JPG, PNG, PDF con límites de tamaño |
| ✅ Gestión de Storage | SÍ | URLs dinámicas, nombres únicos UUID |
| ✅ Relaciones con Pacientes | SÍ | Integración completa con sistema de pacientes |

Este PlacaController representa un sistema robusto y completo para la gestión de radiografías dentales, con todas las características necesarias para un entorno clínico profesional, incluyendo validaciones médicas, seguridad de datos y optimización de archivos.
