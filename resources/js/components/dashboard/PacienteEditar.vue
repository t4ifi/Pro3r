<template>
  <div class="max-w-4xl mx-auto bg-white p-12 rounded-2xl shadow-2xl mt-8">
    <h2 class="text-3xl font-bold mb-8 text-[#a259ff] text-center">Editar Paciente</h2>
    <label class="block mb-4 font-semibold text-lg">Selecciona un paciente:</label>
    <select v-model="pacienteId" @change="cargarPaciente" class="w-full border rounded-lg px-4 py-3 mb-8 text-lg">
      <option value="" disabled>-- Selecciona --</option>
      <option v-for="p in pacientes" :key="p.id" :value="p.id">{{ p.nombre_completo }}</option>
    </select>
    <form v-if="pacienteSeleccionado" @submit.prevent="editarPaciente">
      <div class="mb-6">
        <label class="block mb-2 font-semibold text-lg">Nombre completo *</label>
        <input v-model="pacienteSeleccionado.nombre_completo" type="text" class="w-full border rounded-lg px-4 py-3 text-lg" required />
      </div>
      <div class="mb-6">
        <label class="block mb-2 font-semibold text-lg">Teléfono</label>
        <input v-model="pacienteSeleccionado.telefono" type="text" class="w-full border rounded-lg px-4 py-3 text-lg" />
      </div>
      <div class="mb-6">
        <label class="block mb-2 font-semibold text-lg">Fecha de nacimiento</label>
        <input v-model="pacienteSeleccionado.fecha_nacimiento" type="date" class="w-full border rounded-lg px-4 py-3 text-lg" />
      </div>
      <button type="submit" class="bg-[#a259ff] text-white px-6 py-3 rounded-xl font-bold w-full text-lg">Guardar cambios</button>
      <div v-if="error" class="text-red-600 mt-4 text-center text-lg">{{ error }}</div>
    </form>
    <div v-if="mostrarModalExito" class="modal-bg">
      <div class="modal-content">
        <h3 class="text-2xl font-bold text-green-600 mb-4">¡Cambios guardados!</h3>
        <button @click="cerrarModalExito" class="bg-green-600 text-white px-4 py-2 rounded font-bold">Cerrar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const pacientes = ref([]);
const pacienteId = ref('');
const pacienteSeleccionado = ref(null);
const error = ref('');
const exito = ref('');
const mostrarModalExito = ref(false);

async function cargarPacientes() {
  try {
    const res = await fetch('/api/pacientes');
    pacientes.value = await res.json();
  } catch (e) {
    pacientes.value = [];
  }
}

async function cargarPaciente() {
  error.value = '';
  exito.value = '';
  pacienteSeleccionado.value = null;
  if (!pacienteId.value) return;
  try {
    const res = await fetch(`/api/pacientes/${pacienteId.value}`);
    pacienteSeleccionado.value = await res.json();
  } catch (e) {
    error.value = 'No se pudo cargar el paciente.';
  }
}

async function editarPaciente() {
  error.value = '';
  exito.value = '';
  if (!pacienteSeleccionado.value.nombre_completo) {
    error.value = 'El nombre completo es obligatorio.';
    return;
  }
  try {
    const res = await fetch(`/api/pacientes/${pacienteId.value}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nombre_completo: pacienteSeleccionado.value.nombre_completo,
        telefono: pacienteSeleccionado.value.telefono,
        fecha_nacimiento: pacienteSeleccionado.value.fecha_nacimiento || null
      })
    });
    if (!res.ok) throw new Error('Error al editar paciente');
    mostrarModalExito.value = true;
  } catch (e) {
    error.value = 'No se pudo editar el paciente.';
  }
}

function cerrarModalExito() {
  mostrarModalExito.value = false;
}

onMounted(cargarPacientes);
</script>

<style scoped>
input, button, select {
  font-family: 'Montserrat', Arial, sans-serif;
  font-size: 1.1rem;
}
.modal-bg {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-content {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 16px rgba(34,197,94,0.12);
  padding: 32px 40px;
  text-align: center;
}
</style>
