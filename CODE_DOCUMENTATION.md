# 📚 Documentación de Código - DentalSYNC2

## 🎯 **Guía de Desarrollo**

### 📁 **Estructura de Controladores**

#### CitaController.php
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;

class CitaController extends Controller
{
    /**
     * Listar todas las citas con filtro opcional por fecha
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $fecha = $request->query('fecha');
        $query = Cita::with(['paciente', 'usuario']);
        
        if ($fecha) {
            $query->whereDate('fecha', $fecha);
        }
        
        $citas = $query->orderBy('fecha')->get();
        
        // Mapear datos para el frontend
        $citas = $citas->map(function($cita) {
            return [
                'id' => $cita->id,
                'fecha' => $cita->fecha,
                'motivo' => $cita->motivo,
                'estado' => $cita->estado,
                'nombre_completo' => $cita->paciente?->nombre_completo,
                'usuario_nombre' => $cita->usuario?->nombre,
            ];
        });
        
        return response()->json($citas);
    }

    /**
     * Crear nueva cita
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validación de entrada
            $validated = $request->validate([
                'fecha' => 'required|date',
                'motivo' => 'required|string',
                'nombre_completo' => 'required|string',
                'estado' => 'string|in:pendiente,confirmada,cancelada,atendida',
            ]);

            // Buscar o crear paciente
            $paciente = \App\Models\Paciente::firstOrCreate(
                ['nombre_completo' => $validated['nombre_completo']],
                [
                    'telefono' => null,
                    'fecha_nacimiento' => null,
                    'ultima_visita' => now()->toDateString(),
                ]
            );

            // Crear cita
            $cita = Cita::create([
                'fecha' => $validated['fecha'],
                'motivo' => $validated['motivo'],
                'estado' => $validated['estado'] ?? 'pendiente',
                'paciente_id' => $paciente->id,
                'usuario_id' => 3, // ID del dentista por defecto
            ]);

            return response()->json([
                'success' => true, 
                'cita' => $cita->fresh(['paciente', 'usuario'])
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al crear cita:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}
```

---

### 🗃️ **Estructura de Modelos**

#### Cita.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $fillable = [
        'fecha',
        'motivo', 
        'estado',
        'fecha_atendida',
        'paciente_id',
        'usuario_id'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'fecha_atendida' => 'datetime',
    ];

    /**
     * Relación con paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Relación con usuario (dentista)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por fecha
     */
    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }
}
```

#### Paciente.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $fillable = [
        'nombre_completo',
        'telefono',
        'fecha_nacimiento', 
        'ultima_visita'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'ultima_visita' => 'date',
    ];

    /**
     * Relación con citas
     */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Obtener próxima cita
     */
    public function proximaCita()
    {
        return $this->citas()
            ->where('fecha', '>=', now())
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->first();
    }
}
```

---

### 🌐 **Estructura de Componentes Vue**

#### AgendarCita.vue
```vue
<template>
  <div class="agendar-cita-form bg-white rounded-2xl shadow-2xl p-12 max-w-2xl mx-auto">
    <h2 class="text-3xl font-extrabold mb-8 text-[#a259ff] text-center">
      Agendar Nueva Cita
    </h2>
    
    <form @submit.prevent="agendarCita" class="w-full flex flex-col gap-6">
      <!-- Selector de Paciente -->
      <div>
        <label class="block mb-2 font-semibold text-lg">Paciente</label>
        <select 
          v-model="form.paciente" 
          class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" 
          required
        >
          <option value="" disabled>Selecciona un paciente</option>
          <option 
            v-for="p in pacientes" 
            :key="p.id" 
            :value="p.nombre_completo"
          >
            {{ p.nombre_completo }}
          </option>
        </select>
      </div>

      <!-- Campo de Fecha -->
      <div>
        <label class="block mb-2 font-semibold text-lg">Fecha</label>
        <input 
          v-model="form.fecha" 
          type="date" 
          class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" 
          required 
        />
      </div>

      <!-- Campo de Hora -->
      <div>
        <label class="block mb-2 font-semibold text-lg">Hora</label>
        <input 
          v-model="form.hora" 
          type="time" 
          class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" 
          required 
        />
      </div>

      <!-- Campo de Motivo -->
      <div>
        <label class="block mb-2 font-semibold text-lg">Motivo</label>
        <input 
          v-model="form.motivo" 
          type="text" 
          class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" 
          required 
          placeholder="Motivo de la cita" 
        />
      </div>

      <!-- Botón de Envío -->
      <button 
        type="submit" 
        class="w-full py-3 rounded-xl bg-[#a259ff] text-white text-xl font-bold shadow hover:bg-[#7c3aed] transition-colors"
      >
        Agendar
      </button>
    </form>

    <!-- Mensajes de Estado -->
    <div v-if="exito" class="mt-6 text-green-600 font-bold text-lg">
      ¡Cita agendada correctamente!
    </div>
    <div v-if="error" class="mt-6 text-red-600 font-bold text-lg">
      Error al agendar cita. Intenta nuevamente.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Eventos emitidos
const emit = defineEmits(['cita-agendada']);

// Estado reactivo
const form = ref({ 
  paciente: '', 
  fecha: '', 
  hora: '', 
  motivo: '' 
});
const exito = ref(false);
const error = ref(false);
const pacientes = ref([]);

// Cargar pacientes al montar componente
onMounted(async () => {
  try {
    const res = await fetch('/api/pacientes');
    pacientes.value = await res.json();
  } catch {
    pacientes.value = [];
  }
});

/**
 * Función para agendar cita
 */
async function agendarCita() {
  exito.value = false;
  error.value = false;
  
  try {
    const res = await fetch('/api/citas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nombre_completo: form.value.paciente,
        fecha: form.value.fecha + 'T' + form.value.hora,
        motivo: form.value.motivo,
        estado: 'pendiente'
      })
    });
    
    if (res.ok) {
      exito.value = true;
      emit('cita-agendada');
      // Limpiar formulario
      form.value = { paciente: '', fecha: '', hora: '', motivo: '' };
    } else {
      error.value = true;
    }
  } catch {
    error.value = true;
  }
}
</script>

<style scoped>
.agendar-cita-form {
  margin-top: 60px;
  min-height: 600px;
  box-shadow: 0 8px 32px rgba(162,89,255,0.15);
  border: 1.5px solid #ece7fa;
}
</style>
```

---

### 🛣️ **Configuración de Rutas**

#### router.js
```javascript
import { createRouter, createWebHistory } from 'vue-router';

// Importar componentes
import Dashboard from './components/Dashboard.vue';
import Login from './components/Login.vue';

// Módulos del Dashboard
import AgendarCita from './components/dashboard/AgendarCita.vue';
import ListaCitas from './components/dashboard/ListaCitas.vue';
import ListaPacientes from './components/dashboard/ListaPacientes.vue';
import RegistrarPaciente from './components/dashboard/RegistrarPaciente.vue';
import ListaPlacas from './components/dashboard/ListaPlacas.vue';

const routes = [
  // Ruta de login
  { 
    path: '/login', 
    name: 'Login', 
    component: Login 
  },
  
  // Dashboard principal
  { 
    path: '/', 
    name: 'Dashboard', 
    component: Dashboard,
    children: [
      // Módulo de Citas
      { 
        path: 'agendar-cita', 
        name: 'AgendarCita', 
        component: AgendarCita 
      },
      { 
        path: 'lista-citas', 
        name: 'ListaCitas', 
        component: ListaCitas 
      },
      
      // Módulo de Pacientes
      { 
        path: 'lista-pacientes', 
        name: 'ListaPacientes', 
        component: ListaPacientes 
      },
      { 
        path: 'registrar-paciente', 
        name: 'RegistrarPaciente', 
        component: RegistrarPaciente 
      },
      
      // Módulo de Placas
      { 
        path: 'lista-placas', 
        name: 'ListaPlacas', 
        component: ListaPlacas 
      },
      
      // Redirección por defecto
      { 
        path: '', 
        redirect: '/agendar-cita' 
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
```

---

### 🔗 **API Routes**

#### api.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PlacaController;

// Autenticación
Route::post('/login', [AuthController::class, 'login']);

// Gestión de Citas
Route::apiResource('citas', CitaController::class);

// Gestión de Pacientes  
Route::apiResource('pacientes', PacienteController::class);

// Gestión de Placas Dentales
Route::apiResource('placas', PlacaController::class);

/*
Rutas generadas automáticamente por apiResource:

CITAS:
GET    /api/citas         - index()    Lista todas las citas
POST   /api/citas         - store()    Crear nueva cita
GET    /api/citas/{id}    - show()     Mostrar cita específica
PUT    /api/citas/{id}    - update()   Actualizar cita
DELETE /api/citas/{id}    - destroy()  Eliminar cita

PACIENTES:
GET    /api/pacientes         - index()    Lista todos los pacientes
POST   /api/pacientes         - store()    Crear nuevo paciente
GET    /api/pacientes/{id}    - show()     Mostrar paciente específico
PUT    /api/pacientes/{id}    - update()   Actualizar paciente
DELETE /api/pacientes/{id}    - destroy()  Eliminar paciente

PLACAS:
GET    /api/placas         - index()    Lista todas las placas
POST   /api/placas         - store()    Crear nueva placa
GET    /api/placas/{id}    - show()     Mostrar placa específica
PUT    /api/placas/{id}    - update()   Actualizar placa
DELETE /api/placas/{id}    - destroy()  Eliminar placa
*/
```

---

### 🗄️ **Migraciones de Base de Datos**

#### create_citas_table.php
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->string('motivo');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'atendida'])
                  ->default('pendiente');
            $table->dateTime('fecha_atendida')->nullable();
            
            // Claves foráneas
            $table->foreignId('paciente_id')
                  ->constrained('pacientes')
                  ->onDelete('cascade');
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            // Índices para optimización
            $table->index('fecha');
            $table->index('estado');
            $table->index(['paciente_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
```

---

### 🎨 **Estilos CSS con Tailwind**

#### Clases Útiles
```css
/* Colores del tema */
.color-primary {
  @apply text-[#a259ff];
}

.bg-primary {
  @apply bg-[#a259ff];
}

.border-primary {
  @apply border-[#a259ff];
}

/* Botones */
.btn-primary {
  @apply bg-[#a259ff] text-white px-6 py-3 rounded-xl font-bold 
         hover:bg-[#7c3aed] transition-colors;
}

.btn-secondary {
  @apply bg-gray-200 text-gray-800 px-4 py-2 rounded-lg 
         hover:bg-gray-300 transition-colors;
}

/* Formularios */
.input-field {
  @apply w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 
         focus:outline-none focus:ring-2 focus:ring-[#a259ff];
}

.form-container {
  @apply bg-white rounded-2xl shadow-2xl p-12 max-w-2xl mx-auto;
}

/* Cards */
.card {
  @apply bg-white rounded-xl shadow-lg p-6 border border-gray-200;
}

.card-header {
  @apply text-xl font-bold text-[#a259ff] mb-4;
}

/* Estados */
.status-pendiente {
  @apply bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm;
}

.status-confirmada {
  @apply bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm;
}

.status-atendida {
  @apply bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm;
}

.status-cancelada {
  @apply bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm;
}
```

---

### 🧪 **Ejemplos de Testing**

#### Test de API
```bash
# Probar endpoint de citas
curl -X GET "http://127.0.0.1:8000/api/citas" \
  -H "Accept: application/json"

# Crear nueva cita
curl -X POST "http://127.0.0.1:8000/api/citas" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombre_completo": "Juan Pérez",
    "fecha": "2025-07-30T10:00:00",
    "motivo": "Consulta general",
    "estado": "pendiente"
  }'

# Filtrar citas por fecha
curl -X GET "http://127.0.0.1:8000/api/citas?fecha=2025-07-30" \
  -H "Accept: application/json"
```

---

### 📝 **Buenas Prácticas**

#### Backend (Laravel)
1. **Validación**: Siempre validar datos de entrada
2. **Relaciones**: Usar Eloquent relationships
3. **Logs**: Registrar errores para debugging
4. **Response**: Devolver respuestas consistentes
5. **Cache**: Limpiar caches después de cambios

#### Frontend (Vue.js)
1. **Composition API**: Usar setup() para lógica reactiva
2. **Props/Events**: Comunicación clara entre componentes
3. **Loading states**: Mostrar estados de carga
4. **Error handling**: Manejar errores de API
5. **Responsividad**: Diseño adaptable

#### Base de Datos
1. **Migrations**: Versionar cambios de schema
2. **Foreign Keys**: Mantener integridad referencial
3. **Indexes**: Optimizar consultas frecuentes
4. **Seeders**: Datos de prueba consistentes

---

**📚 Esta documentación cubre los aspectos principales del desarrollo en DentalSYNC2. Para más detalles técnicos, revisar el código fuente.**
