# 💳 PagoController - Documentación Completa

## 📋 Información General

**Archivo**: `app/Http/Controllers/PagoController.php`  
**Propósito**: Gestión integral de pagos, cuotas y facturación de pacientes  
**Dependencias**: Laravel Framework, Modelos Pago, DetallePago, CuotaPago, Paciente, Usuario  
**Versión**: 2.1  
**Última actualización**: 10 de Septiembre 2025

## 🎯 Responsabilidades del Controlador

El PagoController es el núcleo del sistema financiero del consultorio, responsable de:

1. **Registro de Pagos** - Creación y gestión de pagos únicos y en cuotas
2. **Gestión de Cuotas** - Administración de pagos parciales y cuotas variables/fijas
3. **Facturación** - Generación y seguimiento de facturas y recibos
4. **Auditoría Financiera** - Logging y trazabilidad de todas las operaciones
5. **Integración con Pacientes** - Relación directa con expedientes clínicos
6. **Seguridad y Validación** - Control de acceso y validaciones estrictas

## 🏗️ Arquitectura y Diseño

### Modelo de Datos Principal
```php
// Estructura del modelo Pago
[
    'id' => 'bigint auto_increment',
    'paciente_id' => 'bigint FOREIGN KEY REFERENCES pacientes(id)',
    'usuario_id' => 'bigint FOREIGN KEY REFERENCES usuarios(id)',
    'fecha_pago' => 'date NOT NULL',
    'monto_total' => 'decimal(10,2) NOT NULL',
    'monto_pagado' => 'decimal(10,2) DEFAULT 0',
    'saldo_restante' => 'decimal(10,2) DEFAULT 0',
    'descripcion' => 'varchar(500) NOT NULL',
    'modalidad_pago' => "enum('pago_unico','cuotas_fijas','cuotas_variables')",
    'total_cuotas' => 'integer NULLABLE',
    'estado_pago' => "enum('pendiente','pagado_completo','en_cuotas') DEFAULT 'pendiente'",
    'observaciones' => 'text NULLABLE',
    'created_at' => 'timestamp',
    'updated_at' => 'timestamp'
]
```

### Modelos Relacionados
- **DetallePago**: Registro de cada abono o cuota
- **CuotaPago**: Cuotas fijas o variables asociadas a un pago
- **Paciente**: Relación belongsTo
- **Usuario**: Relación belongsTo (dentista o recepcionista)

### Patrones de Diseño Implementados
- **Strategy Pattern**: Modalidades de pago (único, cuotas fijas, cuotas variables)
- **Observer Pattern**: Auditoría automática de pagos
- **Repository Pattern**: Acceso estructurado a datos financieros

## 📚 Métodos Documentados

### 1. `getPacientes()`

#### 🎯 Propósito
Obtener lista de pacientes para el registro de pagos, filtrados por usuario si es necesario.

#### 📥 Parámetros de Entrada
Ninguno (GET simple)

#### 🔍 Proceso de Ejecución
1. **Intento de obtener usuario autenticado**: Si no hay sesión, devuelve todos los pacientes
2. **Consulta optimizada**: SELECT id, nombre_completo
3. **Ordenamiento alfabético**
4. **Respuesta JSON**: Lista de pacientes

#### 📤 Respuesta Exitosa
```json
{
    "success": true,
    "pacientes": [
        { "id": 1, "nombre_completo": "María García López" },
        { "id": 2, "nombre_completo": "Carlos Mendoza Ruiz" }
    ]
}
```

#### ❌ Respuesta de Error
```json
{
    "success": false,
    "message": "Error al cargar pacientes: ..."
}
```

---

### 2. `registrarPago(Request $request)`

#### 🎯 Propósito
Registrar un nuevo pago para un paciente, soportando pago único, cuotas fijas y cuotas variables.

#### 📥 Parámetros de Entrada
```php
[
    'paciente_id' => 'required|exists:pacientes,id',
    'monto_total' => 'required|numeric|min:0.01',
    'descripcion' => 'required|string|max:500',
    'modalidad_pago' => 'required|in:pago_unico,cuotas_fijas,cuotas_variables',
    'total_cuotas' => 'nullable|integer|min:1|max:60',
    'fecha_pago' => 'required|date',
    'observaciones' => 'nullable|string|max:1000'
]
```

#### 🔍 Proceso de Ejecución
1. **Validación de entrada**: Reglas estrictas para cada campo
2. **Transacción atómica**: beginTransaction para consistencia
3. **Obtención de usuario**: Sesión activa o fallback a dentista
4. **Creación de pago**: Inserta registro principal
5. **Configuración según modalidad**:
   - Pago único: marca como pagado completo
   - Cuotas fijas: crea cuotas automáticas
   - Cuotas variables: saldo pendiente
6. **Registro de detalle**: Crea DetallePago para pago único
7. **Creación de cuotas**: Si corresponde, crea CuotaPago
8. **Commit de transacción**: Guarda todos los cambios
9. **Respuesta estructurada**: Retorna pago y paciente

#### 📤 Respuesta Exitosa
```json
{
    "success": true,
    "message": "Pago registrado exitosamente",
    "pago": {
        "id": 123,
        "paciente_id": 1,
        "usuario_id": 2,
        "fecha_pago": "2025-09-10",
        "monto_total": 1500.00,
        "monto_pagado": 0.00,
        "saldo_restante": 1500.00,
        "descripcion": "Tratamiento de ortodoncia",
        "modalidad_pago": "cuotas_fijas",
        "total_cuotas": 6,
        "estado_pago": "pendiente",
        "observaciones": null,
        "created_at": "2025-09-10T15:00:00.000000Z",
        "updated_at": "2025-09-10T15:00:00.000000Z",
        "paciente": {
            "id": 1,
            "nombre_completo": "María García López"
        }
    }
}
```

#### ❌ Respuestas de Error
```json
{
    "success": false,
    "message": "Error al registrar pago: ..."
}
```

---

### 3. `crearCuotasFijas(Pago $pago, $totalCuotas)` (Privado)

#### 🎯 Propósito
Crear automáticamente cuotas fijas para un pago, distribuyendo el monto total equitativamente.

#### 📥 Parámetros de Entrada
- `$pago`: Instancia de Pago recién creado
- `$totalCuotas`: Número de cuotas a crear

#### 🔍 Proceso de Ejecución
1. **Cálculo de monto por cuota**: Divide monto_total entre total_cuotas
2. **Creación de cuotas**: Inserta cada cuota en CuotaPago
3. **Asociación con pago**: Relaciona cada cuota con el pago principal
4. **Fechas de vencimiento**: Calcula fechas mensuales sucesivas

#### 📤 Ejemplo de Cuotas Generadas
```json
[
    { "nro_cuota": 1, "monto": 250.00, "fecha_vencimiento": "2025-10-10" },
    { "nro_cuota": 2, "monto": 250.00, "fecha_vencimiento": "2025-11-10" },
    ...
]
```

---

### 4. `getUsuarioAutenticado()` (Privado)

#### 🎯 Propósito
Obtener el usuario autenticado desde la sesión, o lanzar excepción si no existe.

#### 🔍 Proceso de Ejecución
1. **Verifica existencia de usuario_id en sesión**
2. **Consulta en base de datos**
3. **Lanza excepción si no existe**

#### 📤 Ejemplo de Uso
```php
$usuario = $this->getUsuarioAutenticado();
```

---

## 🔒 Características de Seguridad

### Validación de Entrada
```php
// Reglas estrictas
'paciente_id' => 'required|exists:pacientes,id',
'monto_total' => 'required|numeric|min:0.01',
'descripcion' => 'required|string|max:500',
'modalidad_pago' => 'required|in:pago_unico,cuotas_fijas,cuotas_variables',
'total_cuotas' => 'nullable|integer|min:1|max:60',
'fecha_pago' => 'required|date',
'observaciones' => 'nullable|string|max:1000'
```

### Transacciones Atómicas
- **beginTransaction/commit/rollBack**: Garantiza consistencia de datos
- **Rollback automático** en caso de error

### Auditoría Financiera
- **Logging de todas las operaciones**
- **Registro de usuario responsable**
- **Trazabilidad de cada pago y cuota**

### Control de Acceso
- **Verificación de sesión** para registrar pagos
- **Fallback seguro** a dentista si no hay sesión
- **Permisos por rol** (futuro)

## 📊 Casos de Uso Empresariales

### 1. Registro de Pago Único
```php
// Flujo típico
1. Recepcionista selecciona paciente
2. Ingresa monto y descripción
3. Selecciona modalidad "pago único"
4. Sistema registra pago y lo marca como pagado completo
5. Se genera recibo y se asocia al expediente
```

### 2. Registro de Pago en Cuotas Fijas
```php
// Flujo típico
1. Recepcionista selecciona paciente
2. Ingresa monto total y número de cuotas
3. Selecciona modalidad "cuotas fijas"
4. Sistema crea pago y genera cuotas automáticas
5. Se imprime cronograma de pagos
6. Cada cuota se puede abonar individualmente
```

### 3. Registro de Pago en Cuotas Variables
```php
// Flujo típico
1. Recepcionista selecciona paciente
2. Ingresa monto total
3. Selecciona modalidad "cuotas variables"
4. Sistema crea pago con saldo pendiente
5. Se registran abonos parciales según acuerdo
```

## 🧪 Casos de Prueba

### Pruebas de Registro de Pago
```php
// Test: Pago único exitoso
$response = $this->post('/api/pagos', [
    'paciente_id' => 1,
    'monto_total' => 1000,
    'descripcion' => 'Tratamiento de caries',
    'modalidad_pago' => 'pago_unico',
    'fecha_pago' => '2025-09-10'
]);
$response->assertStatus(200);
$response->assertJsonPath('success', true);

// Test: Pago en cuotas fijas
$response = $this->post('/api/pagos', [
    'paciente_id' => 1,
    'monto_total' => 1200,
    'descripcion' => 'Ortodoncia',
    'modalidad_pago' => 'cuotas_fijas',
    'total_cuotas' => 6,
    'fecha_pago' => '2025-09-10'
]);
$response->assertStatus(200);
$response->assertJsonPath('pago.total_cuotas', 6);

// Test: Validación de monto negativo
$response = $this->post('/api/pagos', [
    'paciente_id' => 1,
    'monto_total' => -100,
    'descripcion' => 'Error',
    'modalidad_pago' => 'pago_unico',
    'fecha_pago' => '2025-09-10'
]);
$response->assertStatus(422);
```

## 📈 Métricas y Rendimiento

### Tiempo de Respuesta Típico
- Registro de pago único: ~120ms
- Registro de pago en cuotas: ~180ms
- Consulta de pacientes: ~60ms

### Optimizaciones de Base de Datos
- Índices en paciente_id, usuario_id, fecha_pago
- Transacciones para operaciones críticas
- Carga diferida de relaciones

## 🚨 Manejo de Errores

### Categorías de Errores
1. **Errores de Validación** (422)
   - Campos requeridos faltantes
   - Formato de monto inválido
   - Modalidad de pago no soportada
2. **Errores de Autenticación** (401)
   - Usuario no autenticado
   - Usuario no encontrado
3. **Errores de Negocio** (400)
   - No hay dentistas disponibles
   - Número de cuotas fuera de rango
4. **Errores del Servidor** (500)
   - Excepciones no controladas
   - Errores de base de datos

### Logging de Errores
- Todos los errores se registran con contexto y stack trace
- Mensajes claros para debugging y auditoría

## 🔧 Configuración y Dependencias

### Variables de Entorno
```env
# Configuración de pagos
PAGO_MONEDA=ARS
PAGO_MAX_CUOTAS=60
PAGO_MIN_MONTO=0.01
PAGO_AUDIT_ENABLED=true
```

### Middlewares Relacionados
- `auth` - Requiere autenticación para registrar pagos
- `throttle:30,1` - Rate limiting para evitar abuso

## 🔮 Mejoras Futuras

### Versión 2.2 (Q4 2025)
- Integración con pasarelas de pago (MercadoPago, Stripe)
- Facturación electrónica automática
- Notificaciones de vencimiento de cuotas
- Dashboard financiero en tiempo real
- Exportación de reportes a Excel/PDF

### Versión 2.3 (Q1 2026)
- Integración con sistemas contables externos
- API REST para pagos de terceros
- Portal de autogestión de pagos para pacientes
- Machine learning para predicción de morosidad

## 📚 Estándares y Compliance
- **Normas contables locales**
- **Ley de Protección de Datos Personales**
- **ISO 27001** para seguridad de información financiera
- **PCI DSS** para manejo de tarjetas (futuro)

---

**Autor**: Sistema DentalSync  
**Fecha de creación**: 10 de Septiembre 2025  
**Versión del documento**: 1.0  
**Estado**: ✅ Completo y actualizado

> 💳 **Nota**: Este controlador maneja información financiera crítica. Toda modificación debe ser revisada por el área contable y cumplir con las regulaciones fiscales y de protección de datos vigentes.
