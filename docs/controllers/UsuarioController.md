# UsuarioController - Documentación Técnica Completa

## 📋 Información General

| Propiedad | Valor |
|-----------|--------|
| **Controlador** | `App\Http\Controllers\UsuarioController` |
| **Propósito** | Gestión integral de usuarios del sistema DentalSync |
| **Funcionalidad Principal** | Administración completa de cuentas de usuario, roles y permisos |
| **Tipo de Gestión** | CRUD completo + funcionalidades administrativas avanzadas |
| **Integración** | Sistema de autenticación, control de acceso, auditoría de usuarios |

## 🏗️ Arquitectura del Controlador

### Dependencias y Imports
```php
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
```

### Estructura de Métodos
1. **index()** - Listado completo de usuarios del sistema
2. **store(Request $request)** - Creación de nuevos usuarios
3. **show($id)** - Visualización de usuario específico
4. **update(Request $request, $id)** - Actualización de datos de usuario
5. **destroy($id)** - Eliminación segura de usuarios
6. **toggleStatus($id)** - Activación/desactivación de usuarios
7. **statistics()** - Estadísticas del sistema de usuarios
8. **getEstadisticas()** - Alias para estadísticas (compatibilidad frontend)

## 📊 Análisis Detallado de Métodos

### 1. index() - Listado de Usuarios

```php
public function index()
```

#### **Propósito**
Proporciona un listado completo y ordenado de todos los usuarios del sistema con información formateada para el frontend.

#### **Funcionalidades Implementadas**

##### **Ordenamiento Temporal**
- Ordenamiento por `created_at` descendente (usuarios más recientes primero)
- Facilita identificación rápida de usuarios nuevos
- Mejora la experiencia de administración

##### **Formateo de Datos para Frontend**
```php
$usuarios = $usuarios->map(function($usuario) {
    return [
        'id' => $usuario->id,
        'usuario' => $usuario->usuario,
        'nombre' => $usuario->nombre,
        'rol' => $usuario->rol,
        'activo' => $usuario->activo,
        'created_at' => $usuario->created_at,
        'updated_at' => $usuario->updated_at,
    ];
});
```

#### **Características de Seguridad**
- **Exclusión de Contraseñas**: No expone hashes de contraseña
- **Datos Sanitizados**: Solo información necesaria para el frontend
- **Estado de Activación**: Incluye información de estado para control de acceso

#### **Formato de Respuesta**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "usuario": "admin",
            "nombre": "Administrador Principal",
            "rol": "dentista",
            "activo": true,
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-15T10:30:00Z"
        },
        {
            "id": 2,
            "usuario": "recepcion01",
            "nombre": "María González",
            "rol": "recepcionista",
            "activo": true,
            "created_at": "2024-01-14T09:15:00Z",
            "updated_at": "2024-01-14T09:15:00Z"
        }
    ]
}
```

### 2. store(Request $request) - Creación de Usuarios

```php
public function store(Request $request)
```

#### **Propósito**
Maneja la creación completa de nuevos usuarios del sistema con validación exhaustiva, encriptación segura de contraseñas y asignación de roles.

#### **Validaciones Exhaustivas**

##### **Reglas de Validación**
```php
$request->validate([
    'usuario' => 'required|string|max:100|unique:usuarios,usuario',
    'nombre' => 'required|string|max:255',
    'rol' => 'required|in:dentista,recepcionista',
    'password' => 'required|string|min:6',
    'activo' => 'boolean'
]);
```

##### **Mensajes Personalizados**
```php
[
    'usuario.required' => 'El nombre de usuario es obligatorio',
    'usuario.unique' => 'Este nombre de usuario ya está en uso',
    'nombre.required' => 'El nombre completo es obligatorio',
    'rol.required' => 'Debe seleccionar un rol',
    'rol.in' => 'El rol debe ser dentista o recepcionista',
    'password.required' => 'La contraseña es obligatoria',
    'password.min' => 'La contraseña debe tener al menos 6 caracteres'
]
```

#### **Sistema de Roles**

##### **Roles Disponibles**
- **dentista**: Acceso completo al sistema clínico
- **recepcionista**: Acceso limitado a funciones administrativas

##### **Jerarquía de Permisos**
- **Dentista**: Gestión completa de pacientes, tratamientos, placas, pagos
- **Recepcionista**: Gestión de citas, información básica de pacientes, pagos

#### **Seguridad de Contraseñas**
```php
'password_hash' => Hash::make($request->password)
```
- **Algoritmo Bcrypt**: Encriptación robusta con sal automática
- **No Almacenamiento en Texto Plano**: Seguridad total de credenciales
- **Verificación Automática**: Compatibilidad con sistema de autenticación

#### **Proceso de Creación**
1. **Validación de Datos**: Verificación completa de entrada
2. **Verificación de Unicidad**: Control de nombres de usuario duplicados
3. **Encriptación de Contraseña**: Hash seguro con bcrypt
4. **Creación de Registro**: Inserción en base de datos
5. **Respuesta Formateada**: Confirmación sin datos sensibles

#### **Respuesta de Éxito**
```json
{
    "success": true,
    "message": "Usuario creado exitosamente",
    "data": {
        "id": 15,
        "usuario": "dentista03",
        "nombre": "Dr. Carlos Mendoza",
        "rol": "dentista",
        "activo": true,
        "created_at": "2024-01-15T14:30:00Z"
    }
}
```

#### **Manejo de Errores de Validación**
```json
{
    "success": false,
    "message": "Datos de validación incorrectos",
    "errors": {
        "usuario": ["Este nombre de usuario ya está en uso"],
        "password": ["La contraseña debe tener al menos 6 caracteres"]
    }
}
```

### 3. show($id) - Visualización de Usuario

```php
public function show($id)
```

#### **Propósito**
Obtiene los detalles completos de un usuario específico para visualización o edición, excluyendo información sensible.

#### **Funcionalidades**
- **Búsqueda Segura**: Utiliza `findOrFail` para manejo automático de errores
- **Datos Completos**: Incluye toda la información no sensible
- **Formato Consistente**: Estructura de respuesta estandarizada

#### **Información Incluida**
- ID del usuario
- Nombre de usuario (login)
- Nombre completo
- Rol asignado
- Estado de activación
- Timestamps de creación y actualización

#### **Respuesta Típica**
```json
{
    "success": true,
    "data": {
        "id": 5,
        "usuario": "recepcion02",
        "nombre": "Ana Rodríguez",
        "rol": "recepcionista",
        "activo": true,
        "created_at": "2024-01-10T08:45:00Z",
        "updated_at": "2024-01-12T16:20:00Z"
    }
}
```

#### **Manejo de Usuario Inexistente**
```json
{
    "success": false,
    "message": "Usuario no encontrado"
}
```

### 4. update(Request $request, $id) - Actualización

```php
public function update(Request $request, $id)
```

#### **Propósito**
Permite la actualización completa o parcial de información de usuarios existentes, incluyendo cambio opcional de contraseña.

#### **Validaciones Dinámicas**

##### **Reglas de Validación Contextual**
```php
$request->validate([
    'usuario' => [
        'required',
        'string',
        'max:100',
        Rule::unique('usuarios')->ignore($usuario->id)
    ],
    'nombre' => 'required|string|max:255',
    'rol' => 'required|in:dentista,recepcionista',
    'password' => 'nullable|string|min:6',
    'activo' => 'boolean'
]);
```

##### **Validación de Unicidad Inteligente**
- **Exclusión de ID Actual**: Permite mantener el mismo nombre de usuario
- **Verificación de Unicidad**: Previene duplicados con otros usuarios
- **Flexibilidad de Actualización**: No requiere cambio de usuario en cada edición

#### **Actualización Selectiva de Contraseña**
```php
// Solo actualizar contraseña si se proporciona
if ($request->filled('password')) {
    $dataToUpdate['password_hash'] = Hash::make($request->password);
}
```

#### **Características de Actualización**
- **Campos Opcionales**: Solo actualiza campos proporcionados
- **Preservación de Datos**: Mantiene valores existentes si no se especifican nuevos
- **Contraseña Opcional**: Permite actualizar sin cambiar contraseña
- **Validación Contextual**: Reglas adaptadas para actualización

#### **Proceso de Actualización**
1. **Localización de Usuario**: Verificación de existencia
2. **Validación de Datos**: Reglas específicas para actualización
3. **Preparación de Datos**: Construcción selectiva del array de actualización
4. **Actualización de Base de Datos**: Aplicación de cambios
5. **Respuesta Confirmada**: Datos actualizados sin información sensible

#### **Respuesta de Actualización Exitosa**
```json
{
    "success": true,
    "message": "Usuario actualizado exitosamente",
    "data": {
        "id": 5,
        "usuario": "recepcion02_updated",
        "nombre": "Ana Rodríguez Martínez",
        "rol": "dentista",
        "activo": true,
        "updated_at": "2024-01-15T16:45:00Z"
    }
}
```

### 5. destroy($id) - Eliminación Segura

```php
public function destroy($id)
```

#### **Propósito**
Elimina usuarios del sistema con protecciones de seguridad para evitar la eliminación del último usuario activo.

#### **Protecciones de Seguridad**

##### **Validación de Último Usuario Activo**
```php
$usuariosActivos = Usuario::where('activo', true)->count();
if ($usuariosActivos === 1 && $usuario->activo) {
    return response()->json([
        'success' => false,
        'message' => 'No se puede eliminar el último usuario activo del sistema'
    ], 400);
}
```

#### **Características de Protección**
- **Prevención de Bloqueo**: Evita eliminación del último acceso administrativo
- **Verificación de Estado**: Solo protege usuarios activos
- **Continuidad Operacional**: Garantiza acceso continuo al sistema

#### **Proceso de Eliminación**
1. **Localización de Usuario**: Verificación de existencia
2. **Validación de Seguridad**: Verificación de último usuario activo
3. **Eliminación de Registro**: Remoción de base de datos
4. **Confirmación**: Respuesta de éxito

#### **Respuesta de Eliminación Exitosa**
```json
{
    "success": true,
    "message": "Usuario eliminado exitosamente"
}
```

#### **Respuesta de Protección Activada**
```json
{
    "success": false,
    "message": "No se puede eliminar el último usuario activo del sistema"
}
```

### 6. toggleStatus($id) - Control de Estado

```php
public function toggleStatus($id)
```

#### **Propósito**
Permite activar o desactivar usuarios del sistema manteniendo protecciones de seguridad operacional.

#### **Funcionalidades de Toggle**

##### **Cambio de Estado Inteligente**
```php
$usuario->activo = !$usuario->activo;
```
- **Inversión de Estado**: Cambia automáticamente entre activo/inactivo
- **Simplicidad de Uso**: Un endpoint para ambas acciones
- **Estado Dinámico**: Respuesta basada en el nuevo estado

##### **Protección de Último Usuario Activo**
```php
if ($usuario->activo) {
    $usuariosActivos = Usuario::where('activo', true)->count();
    if ($usuariosActivos === 1) {
        return response()->json([
            'success' => false,
            'message' => 'No se puede desactivar el último usuario activo del sistema'
        ], 400);
    }
}
```

#### **Casos de Uso**
- **Suspensión Temporal**: Desactivar usuarios sin eliminarlos
- **Reactivación**: Restaurar acceso de usuarios previamente suspendidos
- **Gestión de Personal**: Control de acceso por cambios de personal

#### **Respuesta de Toggle Exitoso**
```json
{
    "success": true,
    "message": "Usuario activado",
    "data": {
        "id": 8,
        "activo": true
    }
}
```

### 7. statistics() - Estadísticas del Sistema

```php
public function statistics()
```

#### **Propósito**
Proporciona métricas importantes sobre la composición y estado del sistema de usuarios para dashboard administrativo.

#### **Métricas Calculadas**

##### **Contadores Básicos**
- **Total de Usuarios**: Número total de cuentas en el sistema
- **Usuarios Activos**: Cuentas habilitadas para acceso
- **Usuarios Inactivos**: Cuentas suspendidas o deshabilitadas

##### **Distribución por Roles**
- **Dentistas**: Número de usuarios con rol de dentista
- **Recepcionistas**: Número de usuarios con rol de recepcionista

#### **Implementación de Contadores**
```php
$totalUsuarios = Usuario::count();
$usuariosActivos = Usuario::where('activo', true)->count();
$usuariosInactivos = Usuario::where('activo', false)->count();
$dentistas = Usuario::where('rol', 'dentista')->count();
$recepcionistas = Usuario::where('rol', 'recepcionista')->count();
```

#### **Respuesta de Estadísticas**
```json
{
    "success": true,
    "data": {
        "total": 25,
        "activos": 22,
        "inactivos": 3,
        "dentistas": 15,
        "recepcionistas": 10
    }
}
```

### 8. getEstadisticas() - Alias de Compatibilidad

```php
public function getEstadisticas()
{
    return $this->statistics();
}
```

#### **Propósito**
Proporciona compatibilidad con frontend que pueda usar nomenclatura en español para el endpoint de estadísticas.

## 🔒 Aspectos de Seguridad

### Gestión de Contraseñas
- **Encriptación Bcrypt**: Algoritmo robusto con sal automática
- **Longitud Mínima**: Requerimiento de 6 caracteres mínimo
- **No Exposición**: Hashes nunca se devuelven en respuestas API

### Control de Acceso
- **Roles Cerrados**: Lista limitada de roles disponibles
- **Estado de Activación**: Control granular de acceso por usuario
- **Protección de Continuidad**: Prevención de bloqueo del sistema

### Validación de Datos
- **Unicidad de Usuarios**: Prevención de nombres duplicados
- **Longitudes Controladas**: Límites en nombres de usuario y nombres completos
- **Tipos de Datos Validados**: Verificación estricta de tipos y formatos

## 📈 Casos de Uso del Sistema

### 1. Creación de Nuevo Dentista
```json
{
    "usuario": "dr_martinez",
    "nombre": "Dr. Roberto Martínez",
    "rol": "dentista",
    "password": "segura123",
    "activo": true
}
```

### 2. Actualización de Rol de Usuario
```json
{
    "rol": "dentista",
    "nombre": "María González Pérez (Dra.)"
}
```

### 3. Suspensión Temporal de Usuario
```http
POST /usuarios/5/toggle-status
```

### 4. Consulta de Estadísticas para Dashboard
```http
GET /usuarios/statistics
```

## 🔧 Configuración y Dependencias

### Dependencias del Modelo
- **Usuario**: Modelo principal de usuarios
- **Hash**: Facade para encriptación de contraseñas
- **Rule**: Utilidad para validaciones complejas

### Configuración de Hash
```php
// config/hashing.php
'driver' => 'bcrypt',
'bcrypt' => [
    'rounds' => env('BCRYPT_ROUNDS', 10),
],
```

### Migraciones Relacionadas
- Tabla `usuarios` con campos: id, usuario, nombre, rol, password_hash, activo
- Índices únicos en campo `usuario`
- Índices para búsquedas por rol y estado

## 🧪 Testing y Validación

### Casos de Prueba Esenciales

#### Test de Creación de Usuario
```php
public function test_store_creates_user_with_valid_data()
{
    $userData = [
        'usuario' => 'test_user',
        'nombre' => 'Usuario de Prueba',
        'rol' => 'dentista',
        'password' => 'password123',
        'activo' => true
    ];
    
    $response = $this->postJson('/api/usuarios', $userData);
    
    $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'data']);
    
    $this->assertDatabaseHas('usuarios', [
        'usuario' => 'test_user',
        'nombre' => 'Usuario de Prueba',
        'rol' => 'dentista'
    ]);
}
```

#### Test de Protección de Último Usuario
```php
public function test_cannot_delete_last_active_user()
{
    // Crear un solo usuario activo
    $usuario = Usuario::factory()->create(['activo' => true]);
    Usuario::where('id', '!=', $usuario->id)->update(['activo' => false]);
    
    $response = $this->deleteJson("/api/usuarios/{$usuario->id}");
    
    $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'No se puede eliminar el último usuario activo del sistema'
            ]);
}
```

#### Test de Validación de Unicidad
```php
public function test_username_must_be_unique()
{
    Usuario::factory()->create(['usuario' => 'existing_user']);
    
    $response = $this->postJson('/api/usuarios', [
        'usuario' => 'existing_user',
        'nombre' => 'Nuevo Usuario',
        'rol' => 'recepcionista',
        'password' => 'password123'
    ]);
    
    $response->assertStatus(422)
            ->assertJsonValidationErrors(['usuario']);
}
```

#### Test de Toggle de Estado
```php
public function test_toggle_status_changes_user_active_state()
{
    $usuario = Usuario::factory()->create(['activo' => false]);
    
    $response = $this->postJson("/api/usuarios/{$usuario->id}/toggle-status");
    
    $response->assertStatus(200);
    $this->assertTrue($usuario->fresh()->activo);
}
```

#### Test de Estadísticas
```php
public function test_statistics_returns_correct_counts()
{
    Usuario::factory()->count(5)->create(['rol' => 'dentista', 'activo' => true]);
    Usuario::factory()->count(3)->create(['rol' => 'recepcionista', 'activo' => true]);
    Usuario::factory()->count(2)->create(['activo' => false]);
    
    $response = $this->getJson('/api/usuarios/statistics');
    
    $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'total' => 10,
                    'activos' => 8,
                    'inactivos' => 2,
                    'dentistas' => 5,
                    'recepcionistas' => 3
                ]
            ]);
}
```

## 📊 Métricas y Monitoreo

### Métricas Importantes
- **Intentos de Login Fallidos**: Monitoreo de seguridad
- **Usuarios Creados por Período**: Análisis de crecimiento
- **Distribución de Roles**: Balance operacional
- **Usuarios Inactivos**: Limpieza y optimización

### Logs de Auditoría
```php
\Log::info('UsuarioController@store - Nuevo usuario creado', [
    'usuario_id' => $usuario->id,
    'usuario_nombre' => $usuario->usuario,
    'rol' => $usuario->rol,
    'creado_por' => auth()->id()
]);

\Log::warning('UsuarioController@destroy - Intento de eliminar último usuario', [
    'usuario_id' => $id,
    'intentado_por' => auth()->id()
]);
```

## 🔄 Flujo de Trabajo Típico

### 1. Incorporación de Nuevo Personal
1. Administrador accede al módulo de usuarios
2. Completa formulario con datos del nuevo empleado
3. Asigna rol apropiado (dentista/recepcionista)
4. Genera contraseña temporal segura
5. Sistema crea cuenta y confirma

### 2. Gestión de Accesos
1. Revisión periódica de usuarios activos
2. Desactivación de usuarios que salen de la organización
3. Actualización de roles según cambios de responsabilidades
4. Monitoreo de estadísticas de usuarios

### 3. Mantenimiento de Seguridad
1. Auditoría regular de cuentas de usuario
2. Verificación de contraseñas seguras
3. Revisión de permisos por rol
4. Limpieza de cuentas obsoletas

## 🚀 Optimizaciones y Mejoras Futuras

### Seguridad Avanzada
- **Autenticación de Dos Factores**: Capa adicional de seguridad
- **Políticas de Contraseñas**: Reglas más robustas de contraseñas
- **Sesiones Limitadas**: Control de tiempo de sesión por rol
- **Logging de Accesos**: Auditoría completa de actividades

### Funcionalidades Administrativas
- **Permisos Granulares**: Control detallado de accesos por función
- **Perfiles de Usuario**: Información adicional y preferencias
- **Notificaciones de Sistema**: Alertas para administradores
- **Backup de Configuraciones**: Respaldo de configuraciones de usuario

### Optimizaciones de Performance
- **Caché de Roles**: Optimización de consultas de permisos
- **Índices Avanzados**: Mejora de velocidad de búsqueda
- **Paginación Inteligente**: Manejo eficiente de listas grandes
- **Lazy Loading**: Carga optimizada de relaciones

## 📋 Resumen de Funcionalidades

| Función | Implementada | Descripción |
|---------|-------------|-------------|
| ✅ Listado de Usuarios | SÍ | Lista completa ordenada cronológicamente |
| ✅ Creación de Usuarios | SÍ | Validación completa, encriptación, roles |
| ✅ Visualización Individual | SÍ | Detalles sin información sensible |
| ✅ Actualización Completa | SÍ | Actualización selectiva con validaciones |
| ✅ Eliminación Protegida | SÍ | Prevención de bloqueo del sistema |
| ✅ Control de Estado | SÍ | Activación/desactivación inteligente |
| ✅ Estadísticas del Sistema | SÍ | Métricas completas para dashboard |
| ✅ Gestión de Contraseñas | SÍ | Encriptación segura, actualización opcional |
| ✅ Sistema de Roles | SÍ | Dentista y recepcionista con validación |
| ✅ Validaciones Robustas | SÍ | Unicidad, formatos, longitudes |

Este UsuarioController implementa un sistema completo y seguro de gestión de usuarios, con todas las protecciones necesarias para un entorno clínico profesional, incluyendo validaciones de seguridad, control de acceso y métricas administrativas.
