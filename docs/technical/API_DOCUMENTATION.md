# 🛠️ API de WhatsApp - Documentación Técnica

## 📋 **ÍNDICE**
1. [Autenticación](#autenticación)
2. [Endpoints de Conversaciones](#endpoints-de-conversaciones)
3. [Endpoints de Plantillas](#endpoints-de-plantillas)
4. [Modelos de Datos](#modelos-de-datos)
5. [Códigos de Error](#códigos-de-error)
6. [Ejemplos de Uso](#ejemplos-de-uso)

---

## 🔐 **AUTENTICACIÓN**

### Headers Requeridos
```http
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: {csrf_token}
Authorization: Bearer {token} // Si aplica
```

### Obtener CSRF Token
```javascript
// Frontend (Vue.js)
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
```

---

## 💬 **ENDPOINTS DE CONVERSACIONES**

### **GET** `/api/whatsapp/conversaciones`
Obtiene lista de conversaciones con filtros opcionales.

**Parámetros Query:**
- `busqueda` (string, opcional): Buscar por nombre o teléfono
- `estado` (string, opcional): Filtrar por estado (`activa`, `pausada`, `cerrada`, `bloqueada`)

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "paciente_id": 5,
      "nombre": "María González",
      "telefono": "+57 300 123 4567",
      "estado": "activa",
      "ultimoMensaje": {
        "texto": "Gracias por la información",
        "timestamp": "2025-07-26T10:30:00.000Z",
        "esPropio": false
      },
      "mensajesNoLeidos": 2,
      "fechaCreacion": "2025-07-26T09:15:00.000Z"
    }
  ]
}
```

---

### **POST** `/api/whatsapp/conversaciones`
Crea una nueva conversación con un paciente.

**Body:**
```json
{
  "paciente_id": 5,
  "mensaje": "Hola María, te recordamos tu cita de mañana a las 10:00 AM"
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "conversacion": {
      "id": 12,
      "nombre": "María González",
      "telefono": "+57 300 123 4567",
      "estado": "activa"
    },
    "mensaje": {
      "id": 45,
      "estado": "enviado"
    }
  }
}
```

**Errores Posibles:**
- `422`: Paciente no existe o validación fallida
- `500`: Error interno del servidor

---

### **GET** `/api/whatsapp/conversaciones/{id}/mensajes`
Obtiene el historial de mensajes de una conversación.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 45,
      "texto": "Hola María, te recordamos tu cita",
      "timestamp": "2025-07-26T10:30:00.000Z",
      "esPropio": true,
      "estado": "leido",
      "tipo": "texto",
      "metadata": {}
    },
    {
      "id": 46,
      "texto": "Perfecto, nos vemos mañana",
      "timestamp": "2025-07-26T10:35:00.000Z",
      "esPropio": false,
      "estado": "recibido",
      "tipo": "texto",
      "metadata": {}
    }
  ]
}
```

---

### **POST** `/api/whatsapp/conversaciones/{id}/mensajes`
Envía un nuevo mensaje en una conversación existente.

**Body:**
```json
{
  "mensaje": "¿Necesitas que confirmemos algo más para tu cita?",
  "tipo": "texto"
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "messageId": 47,
    "whatsappId": "sim_66a3c2b7f1234",
    "estado": "enviado",
    "timestamp": "2025-07-26T10:40:00.000Z"
  }
}
```

---

### **PUT** `/api/whatsapp/conversaciones/{id}/estado`
Actualiza el estado de una conversación.

**Body:**
```json
{
  "estado": "cerrada"
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Estado actualizado correctamente"
}
```

---

### **GET** `/api/whatsapp/conversaciones/estadisticas`
Obtiene estadísticas generales del sistema.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "total": 45,
    "activas": 32,
    "conMensajesNoLeidos": 8,
    "mensajesHoy": 156,
    "mensajesEnviados": 1240,
    "mensajesRecibidos": 890
  }
}
```

---

## 📝 **ENDPOINTS DE PLANTILLAS**

### **GET** `/api/whatsapp/plantillas`
Obtiene lista de plantillas de mensajes.

**Parámetros Query:**
- `categoria` (string, opcional): Filtrar por categoría
- `activa` (boolean, opcional): Solo plantillas activas
- `busqueda` (string, opcional): Buscar en nombre o contenido

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Recordatorio de Cita",
      "categoria": "recordatorio",
      "contenido": "🦷 Hola {nombre}, te recordamos tu cita para {fecha} a las {hora}",
      "variables": ["nombre", "fecha", "hora"],
      "activa": true,
      "usos_totales": 145,
      "fechaCreacion": "2025-07-20T14:20:00.000Z"
    }
  ]
}
```

---

### **POST** `/api/whatsapp/plantillas`
Crea una nueva plantilla de mensaje.

**Body:**
```json
{
  "nombre": "Confirmación de Pago",
  "categoria": "pago",
  "contenido": "✅ Hola {nombre}, hemos recibido tu pago de ${monto}. ¡Gracias!",
  "activa": true
}
```

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "data": {
    "id": 8,
    "nombre": "Confirmación de Pago",
    "categoria": "pago",
    "contenido": "✅ Hola {nombre}, hemos recibido tu pago de ${monto}. ¡Gracias!",
    "variables": ["nombre", "monto"],
    "activa": true,
    "usos_totales": 0
  }
}
```

---

### **GET** `/api/whatsapp/plantillas/{id}`
Obtiene una plantilla específica.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nombre": "Recordatorio de Cita",
    "categoria": "recordatorio",
    "contenido": "🦷 Hola {nombre}, te recordamos tu cita para {fecha} a las {hora}",
    "variables": ["nombre", "fecha", "hora"],
    "activa": true,
    "usos_totales": 145,
    "fechaCreacion": "2025-07-20T14:20:00.000Z",
    "fechaActualizacion": "2025-07-25T09:30:00.000Z"
  }
}
```

---

### **PUT** `/api/whatsapp/plantillas/{id}`
Actualiza una plantilla existente.

**Body:**
```json
{
  "nombre": "Recordatorio de Cita Actualizado",
  "contenido": "🦷 Estimado/a {nombre}, le recordamos su cita programada para {fecha} a las {hora}. Por favor confirme su asistencia.",
  "categoria": "recordatorio",
  "activa": true
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nombre": "Recordatorio de Cita Actualizado",
    "contenido": "🦷 Estimado/a {nombre}, le recordamos su cita programada para {fecha} a las {hora}. Por favor confirme su asistencia.",
    "variables": ["nombre", "fecha", "hora"],
    "activa": true
  }
}
```

---

### **DELETE** `/api/whatsapp/plantillas/{id}`
Elimina una plantilla.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Plantilla eliminada correctamente"
}
```

---

### **POST** `/api/whatsapp/plantillas/{id}/duplicar`
Duplica una plantilla existente.

**Body:**
```json
{
  "nombre": "Copia de Recordatorio de Cita"
}
```

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "data": {
    "id": 9,
    "nombre": "Copia de Recordatorio de Cita",
    "categoria": "recordatorio",
    "contenido": "🦷 Hola {nombre}, te recordamos tu cita para {fecha} a las {hora}",
    "variables": ["nombre", "fecha", "hora"],
    "activa": false,
    "usos_totales": 0
  }
}
```

---

### **PUT** `/api/whatsapp/plantillas/{id}/toggle`
Activa o desactiva una plantilla.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "activa": false,
    "message": "Plantilla desactivada"
  }
}
```

---

### **GET** `/api/whatsapp/plantillas/categorias/list`
Obtiene lista de categorías disponibles.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": "recordatorio",
      "nombre": "Recordatorios",
      "descripcion": "Plantillas para recordar citas y eventos",
      "total": 5
    },
    {
      "id": "pago",
      "nombre": "Pagos",
      "descripcion": "Plantillas relacionadas con pagos",
      "total": 3
    },
    {
      "id": "confirmacion",
      "nombre": "Confirmaciones",
      "descripcion": "Plantillas de confirmación",
      "total": 4
    }
  ]
}
```

---

### **GET** `/api/whatsapp/plantillas/estadisticas/resumen`
Obtiene estadísticas de uso de plantillas.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "data": {
    "totalPlantillas": 12,
    "plantillasActivas": 9,
    "usosUltimoMes": 456,
    "masUsadas": [
      {
        "id": 1,
        "nombre": "Recordatorio de Cita",
        "usos": 145
      },
      {
        "id": 3,
        "nombre": "Confirmación de Pago",
        "usos": 89
      }
    ],
    "porCategoria": {
      "recordatorio": 245,
      "pago": 123,
      "confirmacion": 88
    }
  }
}
```

---

## 📊 **MODELOS DE DATOS**

### **Conversación**
```json
{
  "id": "integer",
  "paciente_id": "integer",
  "telefono": "string",
  "nombre_contacto": "string",
  "estado": "enum[activa,pausada,cerrada,bloqueada]",
  "ultimo_mensaje_fecha": "datetime|null",
  "ultimo_mensaje_texto": "text|null",
  "ultimo_mensaje_propio": "boolean",
  "mensajes_no_leidos": "integer",
  "metadata": "object|null",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### **Mensaje**
```json
{
  "id": "integer",
  "conversacion_id": "integer",
  "contenido": "text",
  "es_propio": "boolean",
  "estado": "enum[enviando,enviado,entregado,leido,error]",
  "tipo": "enum[texto,imagen,documento,audio,video]",
  "fecha_envio": "datetime",
  "mensaje_whatsapp_id": "string|null",
  "metadata": "object|null",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### **Plantilla**
```json
{
  "id": "integer",
  "usuario_id": "integer",
  "nombre": "string",
  "categoria": "string",
  "contenido": "text",
  "variables": "array",
  "activa": "boolean",
  "usos_totales": "integer",
  "metadata": "object|null",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

---

## ⚠️ **CÓDIGOS DE ERROR**

### **400 - Bad Request**
```json
{
  "success": false,
  "message": "Solicitud malformada",
  "errors": {
    "field": ["Error específico del campo"]
  }
}
```

### **404 - Not Found**
```json
{
  "success": false,
  "message": "Recurso no encontrado"
}
```

### **422 - Validation Error**
```json
{
  "success": false,
  "message": "Error de validación",
  "errors": {
    "mensaje": ["El campo mensaje es requerido"],
    "paciente_id": ["El paciente seleccionado no existe"]
  }
}
```

### **500 - Internal Server Error**
```json
{
  "success": false,
  "message": "Error interno del servidor",
  "error": "Descripción del error (solo en modo debug)"
}
```

---

## 🔧 **EJEMPLOS DE USO**

### **JavaScript/Vue.js**

#### Obtener Conversaciones
```javascript
async function obtenerConversaciones() {
  try {
    const response = await fetch('/api/whatsapp/conversaciones', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error obteniendo conversaciones:', error);
    throw error;
  }
}
```

#### Enviar Mensaje
```javascript
async function enviarMensaje(conversacionId, mensaje) {
  try {
    const response = await fetch(`/api/whatsapp/conversaciones/${conversacionId}/mensajes`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        mensaje: mensaje,
        tipo: 'texto'
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error enviando mensaje:', error);
    throw error;
  }
}
```

#### Crear Plantilla
```javascript
async function crearPlantilla(plantilla) {
  try {
    const response = await fetch('/api/whatsapp/plantillas', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(plantilla)
    });
    
    const data = await response.json();
    
    if (data.success) {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error creando plantilla:', error);
    throw error;
  }
}
```

### **PHP/Laravel**

#### Usar desde otro Controller
```php
use App\Http\Controllers\Api\WhatsappConversacionController;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function enviarRecordatorio($citaId)
    {
        $cita = Cita::findOrFail($citaId);
        
        // Crear request simulado
        $request = new Request([
            'paciente_id' => $cita->paciente_id,
            'mensaje' => "Recordatorio: Su cita es mañana a las {$cita->hora}"
        ]);
        
        $whatsappController = new WhatsappConversacionController();
        $response = $whatsappController->crear($request);
        
        return $response;
    }
}
```

#### Usar Modelos Directamente
```php
use App\Models\WhatsappConversacion;
use App\Models\WhatsappMensaje;

// Crear conversación
$conversacion = WhatsappConversacion::create([
    'paciente_id' => $paciente->id,
    'telefono' => $paciente->telefono,
    'nombre_contacto' => $paciente->nombre_completo,
    'estado' => 'activa'
]);

// Enviar mensaje
$mensaje = $conversacion->mensajes()->create([
    'contenido' => 'Hola, este es un mensaje de prueba',
    'es_propio' => true,
    'estado' => 'enviado',
    'fecha_envio' => now()
]);

// Actualizar último mensaje
$conversacion->actualizarUltimoMensaje($mensaje);
```

---

## 🧪 **TESTING CON POSTMAN**

### **Collection Base URL**
```
http://localhost:8000/api/whatsapp
```

### **Headers Globales**
```
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: {{csrf_token}}
```

### **Variables de Entorno**
```json
{
  "base_url": "http://localhost:8000",
  "csrf_token": "obtenido-del-frontend",
  "conversacion_id": "1",
  "plantilla_id": "1"
}
```

### **Tests de Ejemplo**

#### Test: Obtener Conversaciones
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has success property", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('success');
    pm.expect(jsonData.success).to.be.true;
});

pm.test("Response has data array", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData.data).to.be.an('array');
});
```

---

## 📋 **CHECKLIST DE IMPLEMENTACIÓN**

### **Backend**
- [x] Migraciones ejecutadas
- [x] Modelos creados con relaciones
- [x] Controladores con validaciones
- [x] Rutas API configuradas
- [x] Manejo de errores
- [x] Logging implementado

### **Frontend**
- [x] Componentes Vue.js creados
- [x] Servicios de comunicación
- [x] Manejo de estados reactivos
- [x] Validaciones de formularios
- [x] Interfaz responsive
- [x] Indicadores de carga

### **Integración**
- [x] Comunicación Frontend-Backend
- [x] Manejo de tokens CSRF
- [x] Fallbacks para errores
- [x] Transformación de datos
- [x] Cache de configuración

### **Testing**
- [x] Tests manuales de endpoints
- [x] Verificación de validaciones
- [x] Pruebas de interfaz
- [x] Manejo de errores
- [ ] Tests automatizados (futuro)

---

**📚 Documentación completa y actualizada al 26/07/2025**
