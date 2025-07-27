<template>
  <div class="min-h-screen bg-gray-50 p-4">
    <!-- Header -->
    <div class="max-w-6xl mx-auto mb-8">
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
              <i class='bx bx-show text-purple-600 mr-3'></i>
              Visualizar Placas Dentales
            </h1>
            <p class="text-gray-600">Consulta y gestiona las placas radiográficas</p>
          </div>
          <div class="hidden md:block">
            <div class="text-right text-sm text-gray-500">
              <p>{{ fechaActual }}</p>
              <p class="font-medium text-purple-600">Panel de Dentista</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="max-w-6xl mx-auto mb-6">
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
          <div class="flex gap-4 flex-wrap">
            <select v-model="filtroTipo" class="border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500">
              <option value="">Todos los tipos</option>
              <option value="periapical">Periapical</option>
              <option value="bitewing">Bitewing</option>
              <option value="panoramica">Panorámica</option>
              <option value="oclusal">Oclusal</option>
              <option value="cefalometrica">Cefalométrica</option>
            </select>
            
            <select v-model="filtroPaciente" class="border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500">
              <option value="">Todos los pacientes</option>
              <option v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id">
                {{ paciente.nombre }} {{ paciente.apellido }}
              </option>
            </select>
            
            <div class="relative">
              <input 
                v-model="busqueda" 
                type="text" 
                placeholder="Buscar por descripción..."
                class="border-2 border-gray-300 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:border-purple-500"
              />
              <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
            </div>
          </div>
          
          <div class="flex gap-2">
            <button 
              @click="cambiarVista"
              :class="['px-4 py-2 rounded-lg font-medium transition-colors flex items-center',
                       vistaGrilla ? 'bg-purple-600 text-white' : 'border-2 border-purple-600 text-purple-600 hover:bg-purple-50']"
            >
              <i :class="['mr-2', vistaGrilla ? 'bx bx-grid-alt' : 'bx bx-list-ul']"></i>
              {{ vistaGrilla ? 'Vista Grilla' : 'Vista Lista' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-6xl mx-auto">
      <div v-if="cargando" class="text-center py-12">
        <i class='bx bx-loader-alt animate-spin text-4xl text-purple-600 mb-4'></i>
        <p class="text-gray-600">Cargando placas...</p>
      </div>

      <div v-else-if="placasFiltradas.length === 0" class="text-center py-12">
        <i class='bx bx-image text-gray-400 text-6xl mb-4'></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay placas</h3>
        <p class="text-gray-500">No se encontraron placas que coincidan con los filtros seleccionados</p>
      </div>

      <!-- Vista Grilla -->
      <div v-else-if="vistaGrilla" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="placa in placasFiltradas" 
          :key="placa.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow cursor-pointer"
          @click="abrirPlaca(placa)"
        >
          <div class="relative h-48 bg-gray-100">
            <img 
              v-if="placa.url_archivo && esImagen(placa.tipo_archivo)"
              :src="placa.url_archivo" 
              :alt="`Placa ${placa.tipo}`"
              class="w-full h-full object-cover"
            />
            <div v-else class="flex items-center justify-center h-full">
              <i class='bx bx-file text-gray-400 text-4xl'></i>
            </div>
            
            <div class="absolute top-2 right-2">
              <span :class="['px-2 py-1 rounded-full text-xs font-semibold text-white', getTipoColor(placa.tipo)]">
                {{ formatTipo(placa.tipo) }}
              </span>
            </div>
          </div>
          
          <div class="p-4">
            <h3 class="font-semibold text-gray-800 mb-1">{{ placa.paciente.nombre }} {{ placa.paciente.apellido }}</h3>
            <p class="text-sm text-gray-600 mb-2">{{ placa.paciente.cedula }}</p>
            <p class="text-sm text-gray-700 line-clamp-2 mb-3">{{ placa.descripcion || 'Sin descripción' }}</p>
            <div class="flex items-center justify-between text-xs text-gray-500">
              <span>{{ formatearFecha(placa.created_at) }}</span>
              <span>{{ placa.tipo_archivo }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Vista Lista -->
      <div v-else class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="divide-y divide-gray-200">
          <div 
            v-for="placa in placasFiltradas" 
            :key="placa.id"
            class="p-6 hover:bg-gray-50 transition-colors cursor-pointer"
            @click="abrirPlaca(placa)"
          >
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                <img 
                  v-if="placa.url_archivo && esImagen(placa.tipo_archivo)"
                  :src="placa.url_archivo" 
                  :alt="`Placa ${placa.tipo}`"
                  class="w-full h-full object-cover"
                />
                <div v-else class="flex items-center justify-center h-full">
                  <i class='bx bx-file text-gray-400 text-xl'></i>
                </div>
              </div>
              
              <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-3 mb-1">
                  <h3 class="font-semibold text-gray-800">{{ placa.paciente.nombre }} {{ placa.paciente.apellido }}</h3>
                  <span :class="['px-2 py-1 rounded-full text-xs font-semibold text-white', getTipoColor(placa.tipo)]">
                    {{ formatTipo(placa.tipo) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mb-1">{{ placa.paciente.cedula }}</p>
                <p class="text-sm text-gray-700 line-clamp-1">{{ placa.descripcion || 'Sin descripción' }}</p>
              </div>
              
              <div class="text-right text-sm text-gray-500">
                <p>{{ formatearFecha(placa.created_at) }}</p>
                <p class="text-xs">{{ placa.tipo_archivo }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Visualización -->
    <div v-if="placaSeleccionada" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
          <div>
            <h3 class="text-xl font-semibold text-gray-800">{{ placaSeleccionada.paciente.nombre }} {{ placaSeleccionada.paciente.apellido }}</h3>
            <p class="text-sm text-gray-600">{{ formatTipo(placaSeleccionada.tipo) }} - {{ formatearFecha(placaSeleccionada.created_at) }}</p>
          </div>
          <button @click="cerrarModal" class="text-gray-400 hover:text-gray-600">
            <i class='bx bx-x text-2xl'></i>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[70vh]">
          <div v-if="placaSeleccionada.url_archivo && esImagen(placaSeleccionada.tipo_archivo)" class="text-center mb-6">
            <img 
              :src="placaSeleccionada.url_archivo" 
              :alt="`Placa ${placaSeleccionada.tipo}`"
              class="max-w-full h-auto rounded-lg shadow-md mx-auto"
            />
          </div>
          
          <div v-else class="text-center py-12 mb-6">
            <i class='bx bx-file text-gray-400 text-6xl mb-4'></i>
            <p class="text-gray-600">Vista previa no disponible para este tipo de archivo</p>
            <button 
              @click="descargarArchivo(placaSeleccionada)"
              class="mt-4 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors"
            >
              <i class='bx bx-download mr-2'></i>
              Descargar Archivo
            </button>
          </div>
          
          <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-700 mb-2">Información de la Placa</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div>
                <span class="font-medium text-gray-600">Paciente:</span>
                <p>{{ placaSeleccionada.paciente.nombre }} {{ placaSeleccionada.paciente.apellido }}</p>
              </div>
              <div>
                <span class="font-medium text-gray-600">Cédula:</span>
                <p>{{ placaSeleccionada.paciente.cedula }}</p>
              </div>
              <div>
                <span class="font-medium text-gray-600">Tipo:</span>
                <p>{{ formatTipo(placaSeleccionada.tipo) }}</p>
              </div>
              <div>
                <span class="font-medium text-gray-600">Fecha:</span>
                <p>{{ formatearFecha(placaSeleccionada.created_at) }}</p>
              </div>
            </div>
            <div v-if="placaSeleccionada.descripcion" class="mt-4">
              <span class="font-medium text-gray-600">Descripción:</span>
              <p class="mt-1">{{ placaSeleccionada.descripcion }}</p>
            </div>
          </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
          <button 
            @click="descargarArchivo(placaSeleccionada)"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center"
          >
            <i class='bx bx-download mr-2'></i>
            Descargar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PlacasVer',
  data() {
    return {
      cargando: true,
      vistaGrilla: true,
      placas: [],
      pacientes: [],
      placaSeleccionada: null,
      filtroTipo: '',
      filtroPaciente: '',
      busqueda: ''
    };
  },
  computed: {
    fechaActual() {
      return new Date().toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    placasFiltradas() {
      let filtradas = this.placas;

      if (this.filtroTipo) {
        filtradas = filtradas.filter(placa => placa.tipo === this.filtroTipo);
      }

      if (this.filtroPaciente) {
        filtradas = filtradas.filter(placa => placa.paciente_id == this.filtroPaciente);
      }

      if (this.busqueda) {
        const termino = this.busqueda.toLowerCase();
        filtradas = filtradas.filter(placa => 
          placa.descripcion?.toLowerCase().includes(termino) ||
          placa.paciente.nombre.toLowerCase().includes(termino) ||
          placa.paciente.apellido.toLowerCase().includes(termino)
        );
      }

      return filtradas.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }
  },
  mounted() {
    this.cargarDatos();
  },
  methods: {
    async cargarDatos() {
      this.cargando = true;
      try {
        const [placasResponse, pacientesResponse] = await Promise.all([
          fetch('/api/placas'),
          fetch('/api/pacientes')
        ]);

        if (placasResponse.ok && pacientesResponse.ok) {
          this.placas = await placasResponse.json();
          this.pacientes = await pacientesResponse.json();
        }
      } catch (error) {
        console.error('Error al cargar datos:', error);
      } finally {
        this.cargando = false;
      }
    },

    cambiarVista() {
      this.vistaGrilla = !this.vistaGrilla;
    },

    abrirPlaca(placa) {
      this.placaSeleccionada = placa;
    },

    cerrarModal() {
      this.placaSeleccionada = null;
    },

    formatTipo(tipo) {
      const tipos = {
        'periapical': 'Periapical',
        'bitewing': 'Bitewing',
        'panoramica': 'Panorámica',
        'oclusal': 'Oclusal',
        'cefalometrica': 'Cefalométrica'
      };
      return tipos[tipo] || tipo;
    },

    getTipoColor(tipo) {
      const colores = {
        'periapical': 'bg-blue-500',
        'bitewing': 'bg-green-500',
        'panoramica': 'bg-purple-500',
        'oclusal': 'bg-orange-500',
        'cefalometrica': 'bg-red-500'
      };
      return colores[tipo] || 'bg-gray-500';
    },

    esImagen(tipoArchivo) {
      return tipoArchivo && tipoArchivo.startsWith('image/');
    },

    formatearFecha(fecha) {
      return new Date(fecha).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },

    descargarArchivo(placa) {
      if (placa.url_archivo) {
        const link = document.createElement('a');
        link.href = placa.url_archivo;
        link.download = `placa_${placa.paciente.nombre}_${placa.tipo}_${placa.id}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    }
  }
};
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.min-h-screen {
  animation: fadeIn 0.6s ease-out;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
