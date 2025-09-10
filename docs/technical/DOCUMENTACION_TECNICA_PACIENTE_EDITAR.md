# 🔧 Documentación Técnica: PacienteEditar.vue

## 📋 Información General

| Propiedad | Valor |
|-----------|-------|
| **Componente** | PacienteEditar.vue |
| **Framework** | Vue 3 (Composition API) |
| **Estilos** | Tailwind CSS + CSS Scoped |
| **Tipo** | Single File Component (SFC) |
| **Propósito** | Edición de información de pacientes |

## 🏗️ Arquitectura del Componente

### Estados Reactivos

```javascript
// Referencias reactivas principales
const pacientes = ref([]);          // Lista de todos los pacientes
const pacienteId = ref('');         // ID del paciente seleccionado
const pacienteSeleccionado = ref(null); // Datos del paciente a editar
const cargando = ref(false);        // Estado de carga de datos
const guardando = ref(false);       // Estado de guardado
const mostrarModalExito = ref(false); // Control del modal de éxito
const errores = ref([]);            // Array de errores de validación
```

### Propiedades Computadas

```javascript
// Fecha máxima permitida (hoy)
const fechaMaxima = computed(() => {
  return new Date().toISOString().split('T')[0];
});
```

## 🔄 Ciclo de Vida del Componente

### onMounted()
```javascript
/**
 * Se ejecuta al montar el componente
 * Carga inicial de la lista de pacientes desde la API
 */
onMounted(async () => {
  try {
    const response = await fetch('/api/pacientes');
    if (response.ok) {
      pacientes.value = await response.json();
    } else {
      console.error('Error al cargar pacientes');
    }
  } catch (error) {
    console.error('Error de conexión:', error);
  }
});
```

## 🎯 Funciones Principales

### 1. cargarPaciente()

```javascript
/**
 * Carga información detallada del paciente seleccionado
 * @async
 * @returns {Promise<void>}
 */
async function cargarPaciente() {
  if (!pacienteId.value) return;
  
  cargando.value = true;
  errores.value = [];
  
  try {
    const response = await fetch(`/api/pacientes/${pacienteId.value}`);
    if (response.ok) {
      const data = await response.json();
      pacienteSeleccionado.value = {
        ...data,
        // Formatear fechas para input type="date"
        fecha_nacimiento: data.fecha_nacimiento ? 
          data.fecha_nacimiento.split('T')[0] : '',
        ultima_visita: data.ultima_visita ? 
          data.ultima_visita.split('T')[0] : ''
      };
    } else {
      errores.value = ['Error al cargar información del paciente'];
    }
  } catch (error) {
    errores.value = ['Error de conexión al cargar paciente'];
    console.error('Error:', error);
  } finally {
    cargando.value = false;
  }
}
```

**Características:**
- ✅ Validación de entrada (pacienteId debe existir)
- ✅ Estados de carga visual
- ✅ Formateo automático de fechas para inputs HTML5
- ✅ Manejo de errores con feedback visual
- ✅ Limpieza de estados anteriores

### 2. editarPaciente()

```javascript
/**
 * Guarda los cambios del paciente en la base de datos
 * @async
 * @returns {Promise<void>}
 */
async function editarPaciente() {
  if (!pacienteSeleccionado.value) return;
  
  guardando.value = true;
  errores.value = [];
  
  try {
    const response = await fetch(`/api/pacientes/${pacienteSeleccionado.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        nombre_completo: pacienteSeleccionado.value.nombre_completo,
        telefono: pacienteSeleccionado.value.telefono,
        fecha_nacimiento: pacienteSeleccionado.value.fecha_nacimiento,
        ultima_visita: pacienteSeleccionado.value.ultima_visita
      })
    });
    
    const data = await response.json();
    
    if (response.ok && data.success) {
      mostrarModalExito.value = true;
      
      // Actualizar datos locales con respuesta del servidor
      pacienteSeleccionado.value = {
        ...data.paciente,
        fecha_nacimiento: data.paciente.fecha_nacimiento ? 
          data.paciente.fecha_nacimiento.split('T')[0] : '',
        ultima_visita: data.paciente.ultima_visita ? 
          data.paciente.ultima_visita.split('T')[0] : ''
      };
      
      // Sincronizar lista de pacientes
      const index = pacientes.value.findIndex(p => p.id === pacienteSeleccionado.value.id);
      if (index !== -1) {
        pacientes.value[index] = { ...data.paciente };
      }
    } else {
      // Manejo de errores de validación del servidor
      if (data.details) {
        errores.value = Object.values(data.details).flat();
      } else {
        errores.value = [data.error || 'Error al actualizar paciente'];
      }
    }
  } catch (error) {
    errores.value = ['Error de conexión al guardar cambios'];
    console.error('Error:', error);
  } finally {
    guardando.value = false;
  }
}
```

**Características:**
- ✅ Validación previa de datos
- ✅ Headers HTTP correctos
- ✅ Manejo diferenciado de errores (validación vs conexión)
- ✅ Actualización optimista de datos locales
- ✅ Sincronización entre vista detallada y lista
- ✅ Modal de confirmación

### 3. Funciones Utilitarias

#### calcularEdad()
```javascript
/**
 * Calcula la edad basada en la fecha de nacimiento
 * @returns {number|string} Edad en años o 'N/A'
 */
function calcularEdad() {
  if (!pacienteSeleccionado.value?.fecha_nacimiento) return 'N/A';
  
  const hoy = new Date();
  const fechaNac = new Date(pacienteSeleccionado.value.fecha_nacimiento);
  let edad = hoy.getFullYear() - fechaNac.getFullYear();
  const mes = hoy.getMonth() - fechaNac.getMonth();
  
  // Ajuste si no ha cumplido años este año
  if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
    edad--;
  }
  
  return edad;
}
```

#### formatearFecha()
```javascript
/**
 * Formatea fecha para mostrar en español
 * @param {string} fecha - Fecha en formato ISO
 * @returns {string} Fecha formateada o 'N/A'
 */
function formatearFecha(fecha) {
  if (!fecha) return 'N/A';
  return new Date(fecha).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}
```

#### Funciones de Control
```javascript
/**
 * Cancela la edición y limpia el formulario
 */
function cancelarEdicion() {
  pacienteId.value = '';
  pacienteSeleccionado.value = null;
  errores.value = [];
}

/**
 * Cierra el modal de éxito
 */
function cerrarModalExito() {
  mostrarModalExito.value = false;
}
```

## 🎨 Estructura del Template

### Secciones Principales

1. **Header**: Título y fecha actual
2. **Selector**: Dropdown de pacientes con loading state
3. **Formulario**: Campos editables con validación
4. **Acciones**: Botones cancelar/guardar
5. **Errores**: Lista de errores de validación
6. **Modal**: Confirmación de éxito
7. **Placeholder**: Estado vacío cuando no hay selección

### Estados Condicionales

```vue
<!-- Estado de carga -->
<div v-if="cargando" class="...">
  <div class="animate-spin..."></div>
  <span>Cargando información del paciente...</span>
</div>

<!-- Formulario activo -->
<form v-if="pacienteSeleccionado && !cargando" @submit.prevent="editarPaciente">
  <!-- Campos del formulario -->
</form>

<!-- Estado vacío -->
<div v-if="!pacienteSeleccionado && !cargando" class="...">
  <i class='bx bx-user-plus'></i>
  <p>Selecciona un paciente para comenzar a editar</p>
</div>

<!-- Errores de validación -->
<div v-if="errores.length > 0" class="...">
  <ul>
    <li v-for="error in errores" :key="error">{{ error }}</li>
  </ul>
</div>

<!-- Modal de éxito -->
<div v-if="mostrarModalExito" class="fixed inset-0...">
  <!-- Contenido del modal -->
</div>
```

## 🎭 Estilos y Animaciones

### CSS Scoped
```css
.editar-paciente-container {
  min-height: 600px;
  box-shadow: 0 8px 32px rgba(162,89,255,0.15);
  border: 1px solid #ece7fa;
}

.selector-section {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Animación de carga */
@keyframes spin {
  to { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Efectos interactivos */
input:focus, select:focus {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(162,89,255,0.15);
}

button:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

button:disabled:hover {
  transform: none;
  box-shadow: none;
}
```

### Clases Tailwind Utilizadas

#### Layout y Espaciado
- `max-w-4xl mx-auto`: Contenedor centrado con ancho máximo
- `grid grid-cols-1 md:grid-cols-2 gap-6`: Grid responsivo
- `space-y-6`: Espaciado vertical consistente
- `p-8`, `px-4`, `py-3`: Padding variado

#### Tipografía
- `text-3xl font-extrabold`: Títulos principales
- `text-lg font-semibold`: Labels y subtítulos
- `text-sm text-gray-500`: Texto secundario

#### Colores
- `text-[#a259ff]`: Color principal del tema
- `bg-white`, `bg-gray-50`: Fondos
- `border-[#a259ff]`: Bordes temáticos
- `text-red-700`, `bg-red-50`: Estados de error
- `text-green-600`, `bg-green-100`: Estados de éxito

#### Efectos y Transiciones
- `rounded-xl`, `rounded-2xl`: Bordes redondeados
- `shadow-2xl`: Sombras profundas
- `transition-colors`: Transiciones suaves
- `hover:bg-gray-300`: Estados hover

## 🔐 Validaciones

### Frontend (HTML5 + Vue)
```vue
<!-- Campo requerido -->
<input 
  v-model="pacienteSeleccionado.nombre_completo" 
  type="text" 
  required 
  placeholder="Ingrese el nombre completo"
/>

<!-- Validación de fecha -->
<input 
  v-model="pacienteSeleccionado.fecha_nacimiento" 
  type="date" 
  :max="fechaMaxima"
/>

<!-- Formato de teléfono -->
<input 
  v-model="pacienteSeleccionado.telefono" 
  type="tel" 
  placeholder="Ej: +1 234 567 8900"
/>
```

### Backend (Laravel)
```php
$validated = $request->validate([
    'nombre_completo' => 'required|string|max:255',
    'telefono' => 'nullable|string|max:20',
    'fecha_nacimiento' => 'nullable|date|before:today',
    'ultima_visita' => 'nullable|date',
]);
```

## 📱 Responsividad

### Breakpoints
- **sm**: 640px+ (smartphones)
- **md**: 768px+ (tablets)
- **lg**: 1024px+ (desktop)

### Adaptaciones
```vue
<!-- Grid responsivo -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Nombre completo ocupa toda la fila -->
  <div class="md:col-span-2">
    <input />
  </div>
  
  <!-- Teléfono y fecha en columnas separadas en desktop -->
  <div><!-- Teléfono --></div>
  <div><!-- Fecha --></div>
</div>

<!-- Información calculada responsiva -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <!-- Se apilan en mobile, 3 columnas en desktop -->
</div>
```

## 🚀 Optimizaciones Implementadas

### Performance
- ✅ `ref()` para estados primitivos
- ✅ `computed()` para cálculos derivados
- ✅ Lazy loading de datos de paciente
- ✅ Cleanup de estados en funciones de limpieza

### UX/UI
- ✅ Estados de carga visuales
- ✅ Feedback inmediato en acciones
- ✅ Prevención de doble click con estados disabled
- ✅ Validación en tiempo real
- ✅ Formateo automático de fechas

### Manejo de Errores
- ✅ Try-catch en todas las operaciones async
- ✅ Diferentes tipos de error (conexión, validación, servidor)
- ✅ Logging para debugging
- ✅ Fallbacks para datos faltantes

## 🧪 Casos de Prueba

### Funcionalidad Básica
- [ ] Carga inicial de lista de pacientes
- [ ] Selección de paciente desde dropdown
- [ ] Carga de datos detallados del paciente
- [ ] Edición de campos del formulario
- [ ] Cálculo automático de edad
- [ ] Guardado exitoso de cambios
- [ ] Cancelación de edición

### Validaciones
- [ ] Campo nombre completo requerido
- [ ] Validación de fechas futuras
- [ ] Formato de teléfono
- [ ] Manejo de campos vacíos

### Estados de Error
- [ ] Error de conexión en carga inicial
- [ ] Error 404 al cargar paciente específico
- [ ] Errores de validación del servidor
- [ ] Timeout de conexión

### Responsive Design
- [ ] Layout en móvil (320px)
- [ ] Layout en tablet (768px)
- [ ] Layout en desktop (1024px+)
- [ ] Interacciones táctiles

## 🔍 Debugging

### Logs del Navegador
```javascript
// Verificar carga de pacientes
console.log('Pacientes cargados:', pacientes.value);

// Verificar selección de paciente
console.log('Paciente seleccionado:', pacienteSeleccionado.value);

// Verificar errores
console.log('Errores de validación:', errores.value);
```

### Vue DevTools
- Inspeccionar estados reactivos
- Verificar eventos emitidos
- Monitorear cambios en tiempo real

### Network Panel
- Verificar requests a `/api/pacientes`
- Inspeccionar headers y payloads
- Monitorear tiempos de respuesta

---

**📝 Documentación generada el 26 de julio de 2025**  
**🔧 Componente: PacienteEditar.vue v1.0.0**
