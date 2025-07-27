<template>
  <div class="min-h-screen bg-gray-50 p-4">
    <!-- Header -->
    <div class="max-w-6xl mx-auto mb-8">
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
              <i class='bx bx-trash-alt text-red-600 mr-3'></i>
              Eliminar Placas Dentales
            </h1>
            <p class="text-gray-600">Gestiona y elimina placas radiográficas</p>
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
              v-if="placasSeleccionadas.length > 0"
              @click="eliminarSeleccionadas"
              :disabled="eliminando"
              class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 disabled:bg-gray-400 transition-colors flex items-center"
            >
              <i v-if="eliminando" class='bx bx-loader-alt animate-spin mr-2'></i>
              <i v-else class='bx bx-trash mr-2'></i>
              Eliminar Seleccionadas ({{ placasSeleccionadas.length }})
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

      <!-- Lista de Placas -->
      <div v-else class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
          <label class="flex items-center text-sm font-medium text-gray-700">
            <input 
              type="checkbox" 
              :checked="todasSeleccionadas"
              @change="toggleTodasSeleccionadas"
              class="mr-3 h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
            />
            Seleccionar todas las placas visibles
          </label>
        </div>
        
        <div class="divide-y divide-gray-200">
          <div 
            v-for="placa in placasFiltradas" 
            :key="placa.id"
            :class="['p-6 transition-colors', placasSeleccionadas.includes(placa.id) ? 'bg-red-50 border-l-4 border-l-red-500' : 'hover:bg-gray-50']"
          >
            <div class="flex items-center space-x-4">
              <input 
                type="checkbox" 
                :value="placa.id"
                v-model="placasSeleccionadas"
                class="h-5 w-5 text-red-600 border-gray-300 rounded focus:ring-red-500"
              />
              
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
              
              <div class="text-right text-sm text-gray-500 flex-shrink-0">
                <p>{{ formatearFecha(placa.created_at) }}</p>
                <p class="text-xs">{{ placa.tipo_archivo }}</p>
              </div>
              
              <div class="flex gap-2">
                <button 
                  @click="previsualizarPlaca(placa)"
                  class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                  title="Ver placa"
                >
                  <i class='bx bx-show'></i>
                </button>
                
                <button 
                  @click="confirmarEliminacion(placa)"
                  :disabled="eliminando"
                  class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:text-gray-400"
                  title="Eliminar placa"
                >
                  <i class='bx bx-trash'></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmación -->
    <div v-if="mostrarConfirmacion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl max-w-md w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <i class='bx bx-error-circle text-red-500 text-3xl mr-4'></i>
            <h3 class="text-lg font-semibold text-gray-800">Confirmar Eliminación</h3>
          </div>
          
          <div v-if="placaAEliminar">
            <p class="text-gray-600 mb-4">
              ¿Estás seguro de que quieres eliminar la placa de 
              <strong>{{ placaAEliminar.paciente.nombre }} {{ placaAEliminar.paciente.apellido }}</strong>?
            </p>
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
              <p class="text-sm text-gray-700">
                <strong>Tipo:</strong> {{ formatTipo(placaAEliminar.tipo) }}<br>
                <strong>Fecha:</strong> {{ formatearFecha(placaAEliminar.created_at) }}
              </p>
            </div>
          </div>
          
          <div v-else>
            <p class="text-gray-600 mb-4">
              ¿Estás seguro de que quieres eliminar las 
              <strong>{{ placasSeleccionadas.length }} placas seleccionadas</strong>?
            </p>
          </div>
          
          <p class="text-sm text-red-600 mb-6">
            <i class='bx bx-error-circle mr-1'></i>
            Esta acción no se puede deshacer.
          </p>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
          <button 
            @click="cancelarEliminacion"
            class="px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 transition-colors"
          >
            Cancelar
          </button>
          
          <button 
            @click="ejecutarEliminacion"
            :disabled="eliminando"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-400 transition-colors flex items-center"
          >
            <i v-if="eliminando" class='bx bx-loader-alt animate-spin mr-2'></i>
            <i v-else class='bx bx-trash mr-2'></i>
            {{ eliminando ? 'Eliminando...' : 'Eliminar' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Previsualización -->
    <div v-if="placaPreview" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
          <div>
            <h3 class="text-xl font-semibold text-gray-800">{{ placaPreview.paciente.nombre }} {{ placaPreview.paciente.apellido }}</h3>
            <p class="text-sm text-gray-600">{{ formatTipo(placaPreview.tipo) }} - {{ formatearFecha(placaPreview.created_at) }}</p>
          </div>
          <button @click="cerrarPreview" class="text-gray-400 hover:text-gray-600">
            <i class='bx bx-x text-2xl'></i>
          </button>
        </div>
        
        <div class="p-6 text-center">
          <div v-if="placaPreview.url_archivo && esImagen(placaPreview.tipo_archivo)">
            <img 
              :src="placaPreview.url_archivo" 
              :alt="`Placa ${placaPreview.tipo}`"
              class="max-w-full h-auto rounded-lg shadow-md mx-auto"
            />
          </div>
          
          <div v-else class="py-12">
            <i class='bx bx-file text-gray-400 text-6xl mb-4'></i>
            <p class="text-gray-600">Vista previa no disponible para este tipo de archivo</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast de Notificación -->
    <div v-if="showToast" :class="['fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all transform', 
                                   toastType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white']">
      <div class="flex items-center">
        <i :class="['mr-3 text-xl', toastType === 'success' ? 'bx bx-check-circle' : 'bx bx-error-circle']"></i>
        <span>{{ toastMessage }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PlacasEliminar',
  data() {
    return {
      cargando: true,
      eliminando: false,
      placas: [],
      pacientes: [],
      placasSeleccionadas: [],
      mostrarConfirmacion: false,
      placaAEliminar: null,
      placaPreview: null,
      filtroTipo: '',
      filtroPaciente: '',
      busqueda: '',
      showToast: false,
      toastMessage: '',
      toastType: 'success'
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
    },
    todasSeleccionadas() {
      return this.placasFiltradas.length > 0 && 
             this.placasFiltradas.every(placa => this.placasSeleccionadas.includes(placa.id));
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
        this.mostrarToast('Error al cargar los datos', 'error');
      } finally {
        this.cargando = false;
      }
    },

    toggleTodasSeleccionadas() {
      if (this.todasSeleccionadas) {
        this.placasSeleccionadas = [];
      } else {
        this.placasSeleccionadas = this.placasFiltradas.map(placa => placa.id);
      }
    },

    confirmarEliminacion(placa) {
      this.placaAEliminar = placa;
      this.mostrarConfirmacion = true;
    },

    eliminarSeleccionadas() {
      if (this.placasSeleccionadas.length === 0) return;
      this.placaAEliminar = null;
      this.mostrarConfirmacion = true;
    },

    cancelarEliminacion() {
      this.mostrarConfirmacion = false;
      this.placaAEliminar = null;
    },

    async ejecutarEliminacion() {
      this.eliminando = true;
      
      try {
        const placasAEliminar = this.placaAEliminar 
          ? [this.placaAEliminar.id]
          : this.placasSeleccionadas;

        const eliminaciones = placasAEliminar.map(id => 
          fetch(`/api/placas/${id}`, { method: 'DELETE' })
        );

        const resultados = await Promise.all(eliminaciones);
        const exitosas = resultados.filter(r => r.ok).length;

        if (exitosas === placasAEliminar.length) {
          this.mostrarToast(`${exitosas} placa(s) eliminada(s) exitosamente`, 'success');
          await this.cargarDatos();
          this.placasSeleccionadas = [];
        } else {
          this.mostrarToast('Algunas placas no pudieron ser eliminadas', 'error');
        }
      } catch (error) {
        console.error('Error al eliminar placas:', error);
        this.mostrarToast('Error al eliminar las placas', 'error');
      } finally {
        this.eliminando = false;
        this.cancelarEliminacion();
      }
    },

    previsualizarPlaca(placa) {
      this.placaPreview = placa;
    },

    cerrarPreview() {
      this.placaPreview = null;
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
        year: 'numeric'
      });
    },

    mostrarToast(mensaje, tipo = 'success') {
      this.toastMessage = mensaje;
      this.toastType = tipo;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 4000);
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
</style>
