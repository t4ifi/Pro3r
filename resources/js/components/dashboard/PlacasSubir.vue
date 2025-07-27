<template>
  <div class="min-h-screen bg-gray-50 p-4">
    <!-- Header -->
    <div class="max-w-6xl mx-auto mb-8">
      <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
              <i class='bx bx-cloud-upload text-purple-600 mr-3'></i>
              Subir Placa Dental
            </h1>
            <p class="text-gray-600">Carga y gestiona placas radiográficas de pacientes</p>
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

    <!-- Formulario de Subida -->
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-xl shadow-lg p-8">
        <form @submit.prevent="subirPlaca" class="space-y-6">
          <!-- Selección de Paciente -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class='bx bx-user mr-2'></i>Seleccionar Paciente
              </label>
              <select 
                v-model="placaData.paciente_id" 
                required
                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-purple-500 transition-colors"
              >
                <option value="">Seleccione un paciente...</option>
                <option v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id">
                  {{ paciente.nombre }} {{ paciente.apellido }} - {{ paciente.cedula }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class='bx bx-category mr-2'></i>Tipo de Placa
              </label>
              <select 
                v-model="placaData.tipo" 
                required
                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-purple-500 transition-colors"
              >
                <option value="">Seleccione el tipo...</option>
                <option value="periapical">Periapical</option>
                <option value="bitewing">Bitewing</option>
                <option value="panoramica">Panorámica</option>
                <option value="oclusal">Oclusal</option>
                <option value="cefalometrica">Cefalométrica</option>
              </select>
            </div>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class='bx bx-text mr-2'></i>Descripción / Observaciones
            </label>
            <textarea 
              v-model="placaData.descripcion"
              rows="3"
              placeholder="Describe los hallazgos o el propósito de la placa..."
              class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-purple-500 transition-colors resize-none"
            ></textarea>
          </div>

          <!-- Zona de Arrastre para Archivo -->
          <div class="space-y-4">
            <label class="block text-sm font-semibold text-gray-700">
              <i class='bx bx-image mr-2'></i>Archivo de Placa
            </label>
            
            <div 
              @drop="onDrop"
              @dragover.prevent
              @dragenter.prevent
              :class="['border-2 border-dashed rounded-lg p-8 text-center transition-colors cursor-pointer', 
                       isDragging ? 'border-purple-500 bg-purple-50' : 'border-gray-300 hover:border-purple-400']"
              @click="$refs.fileInput.click()"
            >
              <div v-if="!placaData.archivo">
                <i class='bx bx-cloud-upload text-6xl text-gray-400 mb-4'></i>
                <p class="text-lg font-medium text-gray-600 mb-2">
                  Arrastra tu archivo aquí o haz clic para seleccionar
                </p>
                <p class="text-sm text-gray-500">
                  Formatos soportados: JPG, PNG, JPEG, DICOM (Máx. 10MB)
                </p>
              </div>
              
              <div v-else class="space-y-4">
                <i class='bx bx-check-circle text-6xl text-green-500'></i>
                <div>
                  <p class="text-lg font-medium text-gray-700">{{ placaData.archivo.name }}</p>
                  <p class="text-sm text-gray-500">{{ formatBytes(placaData.archivo.size) }}</p>
                </div>
                <button 
                  type="button"
                  @click.stop="removeFile"
                  class="text-red-600 hover:text-red-800 font-medium"
                >
                  <i class='bx bx-trash mr-1'></i>Eliminar archivo
                </button>
              </div>
            </div>
            
            <input 
              ref="fileInput"
              type="file" 
              @change="onFileSelect"
              accept=".jpg,.jpeg,.png,.dcm"
              class="hidden"
            />
          </div>

          <!-- Vista previa de imagen -->
          <div v-if="previewUrl" class="bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-gray-700 mb-3">Vista previa:</h3>
            <div class="max-w-md mx-auto">
              <img :src="previewUrl" alt="Vista previa" class="w-full rounded-lg shadow-md">
            </div>
          </div>

          <!-- Botones de Acción -->
          <div class="flex gap-4 pt-6 border-t border-gray-200">
            <button 
              type="submit"
              :disabled="cargando || !placaData.paciente_id || !placaData.tipo || !placaData.archivo"
              class="flex-1 bg-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors flex items-center justify-center"
            >
              <i v-if="cargando" class='bx bx-loader-alt animate-spin mr-2'></i>
              <i v-else class='bx bx-cloud-upload mr-2'></i>
              {{ cargando ? 'Subiendo...' : 'Subir Placa' }}
            </button>
            
            <button 
              type="button"
              @click="limpiarFormulario"
              class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:border-gray-400 transition-colors flex items-center"
            >
              <i class='bx bx-refresh mr-2'></i>
              Limpiar
            </button>
          </div>
        </form>
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
  name: 'PlacasSubir',
  data() {
    return {
      cargando: false,
      isDragging: false,
      pacientes: [],
      showToast: false,
      toastMessage: '',
      toastType: 'success',
      previewUrl: null,
      placaData: {
        paciente_id: '',
        tipo: '',
        descripcion: '',
        archivo: null
      }
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
    }
  },
  mounted() {
    this.cargarPacientes();
  },
  methods: {
    async cargarPacientes() {
      try {
        const response = await fetch('/api/pacientes');
        if (response.ok) {
          this.pacientes = await response.json();
        }
      } catch (error) {
        console.error('Error al cargar pacientes:', error);
        this.mostrarToast('Error al cargar la lista de pacientes', 'error');
      }
    },

    onDrop(event) {
      event.preventDefault();
      this.isDragging = false;
      const files = event.dataTransfer.files;
      if (files.length > 0) {
        this.procesarArchivo(files[0]);
      }
    },

    onFileSelect(event) {
      const file = event.target.files[0];
      if (file) {
        this.procesarArchivo(file);
      }
    },

    procesarArchivo(file) {
      // Validar tamaño (10MB máximo)
      if (file.size > 10 * 1024 * 1024) {
        this.mostrarToast('El archivo es demasiado grande. Máximo 10MB.', 'error');
        return;
      }

      // Validar tipo de archivo
      const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'application/dicom'];
      if (!tiposPermitidos.includes(file.type) && !file.name.toLowerCase().endsWith('.dcm')) {
        this.mostrarToast('Formato de archivo no soportado.', 'error');
        return;
      }

      this.placaData.archivo = file;

      // Crear vista previa para imágenes
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.previewUrl = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        this.previewUrl = null;
      }
    },

    removeFile() {
      this.placaData.archivo = null;
      this.previewUrl = null;
      this.$refs.fileInput.value = '';
    },

    async subirPlaca() {
      this.cargando = true;

      try {
        const formData = new FormData();
        formData.append('paciente_id', this.placaData.paciente_id);
        formData.append('tipo', this.placaData.tipo);
        formData.append('descripcion', this.placaData.descripcion);
        formData.append('archivo', this.placaData.archivo);

        const response = await fetch('/api/placas', {
          method: 'POST',
          body: formData
        });

        if (response.ok) {
          this.mostrarToast('Placa subida exitosamente', 'success');
          this.limpiarFormulario();
        } else {
          throw new Error('Error en la respuesta del servidor');
        }
      } catch (error) {
        console.error('Error al subir placa:', error);
        this.mostrarToast('Error al subir la placa. Inténtalo de nuevo.', 'error');
      } finally {
        this.cargando = false;
      }
    },

    limpiarFormulario() {
      this.placaData = {
        paciente_id: '',
        tipo: '',
        descripcion: '',
        archivo: null
      };
      this.previewUrl = null;
      this.$refs.fileInput.value = '';
    },

    formatBytes(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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
</style>
