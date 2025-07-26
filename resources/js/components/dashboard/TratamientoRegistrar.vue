<template>
  <div class="tratamiento-registrar">
    <div class="page-header">
      <h1>
        <i class='bx bx-plus-medical'></i>
        Registrar Tratamiento y Observaciones
      </h1>
      <p>{{ getCurrentDate() }}</p>
    </div>
    
    <!-- Selector de Paciente -->
    <div class="content-card">
      <div class="form-section">
        <h3>
          <i class='bx bx-user'></i>
          Seleccionar Paciente
        </h3>
        <div class="form-group">
          <label for="paciente">Paciente:</label>
          <select 
            id="paciente" 
            v-model="selectedPacienteId" 
            @change="onPacienteChange"
            :disabled="isLoading"
            class="form-select"
          >
            <option value="">Selecciona un paciente...</option>
            <option 
              v-for="paciente in pacientes" 
              :key="paciente.id" 
              :value="paciente.id"
            >
              {{ paciente.nombre_completo }} - {{ paciente.telefono }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Estado de carga -->
    <div v-if="isLoading" class="loading-state">
      <div class="spinner"></div>
      <p>Cargando información...</p>
    </div>

    <!-- Formulario de registro de tratamiento -->
    <div v-if="selectedPacienteId && !isLoading" class="content-card">
      <div class="form-section">
        <h3>
          <i class='bx bx-plus-circle'></i>
          Nuevo Tratamiento
        </h3>
        
        <form @submit.prevent="registrarTratamiento" class="treatment-form">
          <div class="form-row">
            <div class="form-group">
              <label for="descripcion">Descripción del Tratamiento: *</label>
              <textarea 
                id="descripcion"
                v-model="nuevoTratamiento.descripcion"
                placeholder="Describe el tratamiento a realizar..."
                required
                rows="3"
                class="form-textarea"
              ></textarea>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="fecha_inicio">Fecha de Inicio: *</label>
              <input 
                type="date" 
                id="fecha_inicio"
                v-model="nuevoTratamiento.fecha_inicio"
                required
                :max="today"
                class="form-input"
              >
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="observaciones">Observaciones Iniciales:</label>
              <textarea 
                id="observaciones"
                v-model="nuevoTratamiento.observaciones"
                placeholder="Observaciones clínicas adicionales..."
                rows="3"
                class="form-textarea"
              ></textarea>
            </div>
          </div>

          <div class="form-actions">
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="btn btn-primary"
            >
              <i class='bx bx-save'></i>
              {{ isSubmitting ? 'Registrando...' : 'Registrar Tratamiento' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tratamientos existentes del paciente -->
    <div v-if="selectedPacienteId && tratamientosPaciente.length > 0" class="content-card">
      <div class="form-section">
        <h3>
          <i class='bx bx-history'></i>
          Tratamientos Activos
        </h3>
        
        <div class="treatments-list">
          <div 
            v-for="tratamiento in tratamientosPaciente.filter(t => t.estado === 'activo')" 
            :key="tratamiento.id"
            class="treatment-item"
          >
            <div class="treatment-info">
              <h4>{{ tratamiento.descripcion }}</h4>
              <p><strong>Fecha inicio:</strong> {{ formatDate(tratamiento.fecha_inicio) }}</p>
              <p><strong>Dentista:</strong> {{ tratamiento.dentista }}</p>
              <span class="status-badge active">{{ tratamiento.estado }}</span>
            </div>
            
            <div class="treatment-actions">
              <button 
                @click="openObservacionModal(tratamiento)"
                class="btn btn-sm btn-secondary"
              >
                <i class='bx bx-note'></i>
                Agregar Observación
              </button>
              <button 
                @click="finalizarTratamiento(tratamiento.id)"
                class="btn btn-sm btn-success"
              >
                <i class='bx bx-check'></i>
                Finalizar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para agregar observaciones -->
    <div v-if="showObservacionModal" class="modal-overlay" @click="closeObservacionModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>
            <i class='bx bx-note'></i>
            Agregar Observación
          </h3>
          <button @click="closeObservacionModal" class="modal-close">
            <i class='bx bx-x'></i>
          </button>
        </div>
        
        <div class="modal-body">
          <p><strong>Tratamiento:</strong> {{ selectedTratamiento?.descripcion }}</p>
          
          <form @submit.prevent="agregarObservacion" class="observacion-form">
            <div class="form-group">
              <label for="fecha_visita">Fecha de la Visita: *</label>
              <input 
                type="date" 
                id="fecha_visita"
                v-model="nuevaObservacion.fecha_visita"
                required
                :max="today"
                class="form-input"
              >
            </div>
            
            <div class="form-group">
              <label for="observacion_text">Observación: *</label>
              <textarea 
                id="observacion_text"
                v-model="nuevaObservacion.observaciones"
                placeholder="Escribe las observaciones de la visita..."
                required
                rows="4"
                class="form-textarea"
              ></textarea>
            </div>
            
            <div class="modal-actions">
              <button type="button" @click="closeObservacionModal" class="btn btn-secondary">
                Cancelar
              </button>
              <button type="submit" :disabled="isSubmitting" class="btn btn-primary">
                <i class='bx bx-save'></i>
                {{ isSubmitting ? 'Guardando...' : 'Guardar Observación' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Manejo de errores -->
    <div v-if="errorMessages.length > 0" class="error-container">
      <div class="error-box">
        <h4>
          <i class='bx bx-error'></i>
          Errores de validación:
        </h4>
        <ul>
          <li v-for="error in errorMessages" :key="error">{{ error }}</li>
        </ul>
      </div>
    </div>

    <!-- Modal de éxito -->
    <div v-if="showSuccessModal" class="modal-overlay success-modal" @click="closeSuccessModal">
      <div class="modal-content success-content" @click.stop>
        <div class="success-icon">
          <i class='bx bx-check-circle'></i>
        </div>
        <h3>¡Éxito!</h3>
        <p>{{ successMessage }}</p>
        <button @click="closeSuccessModal" class="btn btn-primary">
          Continuar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

// Estados reactivos
const pacientes = ref([])
const selectedPacienteId = ref('')
const tratamientosPaciente = ref([])
const isLoading = ref(false)
const isSubmitting = ref(false)
const errorMessages = ref([])
const showSuccessModal = ref(false)
const successMessage = ref('')
const showObservacionModal = ref(false)
const selectedTratamiento = ref(null)

// Formulario de nuevo tratamiento
const nuevoTratamiento = ref({
  descripcion: '',
  fecha_inicio: '',
  observaciones: ''
})

// Formulario de nueva observación
const nuevaObservacion = ref({
  fecha_visita: '',
  observaciones: ''
})

// Fecha de hoy
const today = computed(() => {
  return new Date().toISOString().split('T')[0]
})

// Cargar pacientes al montar el componente
onMounted(async () => {
  await cargarPacientes()
  // Establecer fecha de inicio por defecto
  nuevoTratamiento.value.fecha_inicio = today.value
  nuevaObservacion.value.fecha_visita = today.value
})

// Funciones
const getCurrentDate = () => {
  const now = new Date()
  const options = { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    timeZone: 'America/Lima'
  }
  return now.toLocaleDateString('es-PE', options)
}

const cargarPacientes = async () => {
  try {
    isLoading.value = true
    const response = await axios.get('/api/tratamientos/pacientes')
    pacientes.value = response.data
  } catch (error) {
    console.error('Error al cargar pacientes:', error)
    errorMessages.value = ['Error al cargar la lista de pacientes']
  } finally {
    isLoading.value = false
  }
}

const onPacienteChange = async () => {
  if (selectedPacienteId.value) {
    await cargarTratamientosPaciente()
  } else {
    tratamientosPaciente.value = []
  }
  // Limpiar formulario
  limpiarFormulario()
}

const cargarTratamientosPaciente = async () => {
  try {
    isLoading.value = true
    const response = await axios.get(`/api/tratamientos/paciente/${selectedPacienteId.value}`)
    tratamientosPaciente.value = response.data
  } catch (error) {
    console.error('Error al cargar tratamientos del paciente:', error)
    errorMessages.value = ['Error al cargar los tratamientos del paciente']
  } finally {
    isLoading.value = false
  }
}

const registrarTratamiento = async () => {
  try {
    errorMessages.value = []
    isSubmitting.value = true

    const datosCompletos = {
      ...nuevoTratamiento.value,
      paciente_id: selectedPacienteId.value
    }

    const response = await axios.post('/api/tratamientos', datosCompletos)
    
    if (response.data.success) {
      successMessage.value = 'Tratamiento registrado exitosamente'
      showSuccessModal.value = true
      limpiarFormulario()
      await cargarTratamientosPaciente() // Recargar tratamientos
    }
  } catch (error) {
    console.error('Error al registrar tratamiento:', error)
    if (error.response?.data?.details) {
      errorMessages.value = Object.values(error.response.data.details).flat()
    } else {
      errorMessages.value = [error.response?.data?.error || 'Error al registrar el tratamiento']
    }
  } finally {
    isSubmitting.value = false
  }
}

const openObservacionModal = (tratamiento) => {
  selectedTratamiento.value = tratamiento
  nuevaObservacion.value.fecha_visita = today.value
  nuevaObservacion.value.observaciones = ''
  showObservacionModal.value = true
}

const closeObservacionModal = () => {
  showObservacionModal.value = false
  selectedTratamiento.value = null
  nuevaObservacion.value = {
    fecha_visita: today.value,
    observaciones: ''
  }
}

const agregarObservacion = async () => {
  try {
    errorMessages.value = []
    isSubmitting.value = true

    const response = await axios.post(
      `/api/tratamientos/${selectedTratamiento.value.id}/observacion`,
      nuevaObservacion.value
    )
    
    if (response.data.success) {
      successMessage.value = 'Observación agregada exitosamente'
      showSuccessModal.value = true
      closeObservacionModal()
    }
  } catch (error) {
    console.error('Error al agregar observación:', error)
    if (error.response?.data?.details) {
      errorMessages.value = Object.values(error.response.data.details).flat()
    } else {
      errorMessages.value = [error.response?.data?.error || 'Error al agregar la observación']
    }
  } finally {
    isSubmitting.value = false
  }
}

const finalizarTratamiento = async (tratamientoId) => {
  if (confirm('¿Estás seguro de que deseas finalizar este tratamiento?')) {
    try {
      const response = await axios.put(`/api/tratamientos/${tratamientoId}/finalizar`)
      
      if (response.data.success) {
        successMessage.value = 'Tratamiento finalizado exitosamente'
        showSuccessModal.value = true
        await cargarTratamientosPaciente() // Recargar tratamientos
      }
    } catch (error) {
      console.error('Error al finalizar tratamiento:', error)
      errorMessages.value = ['Error al finalizar el tratamiento']
    }
  }
}

const limpiarFormulario = () => {
  nuevoTratamiento.value = {
    descripcion: '',
    fecha_inicio: today.value,
    observaciones: ''
  }
  errorMessages.value = []
}

const closeSuccessModal = () => {
  showSuccessModal.value = false
  successMessage.value = ''
}

const formatDate = (dateString) => {
  const date = new Date(dateString + 'T00:00:00')
  return date.toLocaleDateString('es-PE', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<style scoped>
.tratamiento-registrar {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 30px;
}

.page-header h1 {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #2c3e50;
  margin-bottom: 8px;
  font-size: 2.2rem;
}

.page-header h1 i {
  color: #a259ff;
  font-size: 2.4rem;
}

.page-header p {
  color: #7f8c8d;
  font-size: 1.1rem;
  margin: 0;
}

.content-card {
  background: white;
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 25px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  border: 1px solid #f1f3f4;
}

.form-section h3 {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #2c3e50;
  margin-bottom: 20px;
  font-size: 1.4rem;
}

.form-section h3 i {
  color: #a259ff;
  font-size: 1.6rem;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #2c3e50;
  font-weight: 600;
}

.form-select,
.form-input,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  font-size: 16px;
  transition: all 0.3s ease;
  background: #fafbfc;
}

.form-select:focus,
.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #a259ff;
  background: white;
  box-shadow: 0 0 0 4px rgba(162, 89, 255, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

@media (min-width: 768px) {
  .form-row {
    grid-template-columns: 1fr 1fr;
  }
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-primary {
  background: linear-gradient(135deg, #a259ff, #7c3aed);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #7c3aed, #5b21b6);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(162, 89, 255, 0.3);
}

.btn-secondary {
  background: #6b7280;
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background: #4b5563;
  transform: translateY(-2px);
}

.btn-success {
  background: #10b981;
  color: white;
}

.btn-success:hover:not(:disabled) {
  background: #059669;
  transform: translateY(-2px);
}

.btn-sm {
  padding: 8px 16px;
  font-size: 14px;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
}

.loading-state {
  text-align: center;
  padding: 40px;
  color: #7f8c8d;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f4f6;
  border-left: 4px solid #a259ff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.treatments-list {
  display: grid;
  gap: 16px;
}

.treatment-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
}

.treatment-info h4 {
  color: #2c3e50;
  margin: 0 0 10px 0;
  font-size: 1.1rem;
}

.treatment-info p {
  margin: 5px 0;
  color: #64748b;
  font-size: 14px;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-badge.active {
  background: #d1fae5;
  color: #059669;
}

.treatment-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 16px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px 30px 20px;
  border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #2c3e50;
}

.modal-header h3 i {
  color: #a259ff;
}

.modal-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #6b7280;
  cursor: pointer;
  padding: 5px;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.modal-close:hover {
  background: #f3f4f6;
  color: #374151;
}

.modal-body {
  padding: 30px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
}

.success-modal .modal-overlay {
  background: rgba(16, 185, 129, 0.1);
}

.success-content {
  text-align: center;
  padding: 40px 30px;
}

.success-icon {
  font-size: 4rem;
  color: #10b981;
  margin-bottom: 20px;
}

.success-content h3 {
  color: #2c3e50;
  margin-bottom: 15px;
}

.success-content p {
  color: #64748b;
  margin-bottom: 25px;
}

.error-container {
  margin-top: 20px;
}

.error-box {
  background: #fee2e2;
  border: 1px solid #fecaca;
  border-radius: 12px;
  padding: 20px;
}

.error-box h4 {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #dc2626;
  margin: 0 0 15px 0;
}

.error-box h4 i {
  font-size: 1.2rem;
}

.error-box ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.error-box li {
  color: #dc2626;
  margin-bottom: 5px;
  padding-left: 20px;
  position: relative;
}

.error-box li:before {
  content: "•";
  position: absolute;
  left: 0;
  color: #dc2626;
}

@media (max-width: 768px) {
  .tratamiento-registrar {
    padding: 15px;
  }
  
  .treatment-item {
    flex-direction: column;
    align-items: stretch;
  }
  
  .treatment-actions {
    justify-content: flex-start;
  }
  
  .modal-content {
    margin: 10px;
  }
  
  .modal-header,
  .modal-body {
    padding: 20px;
  }
}
</style>
