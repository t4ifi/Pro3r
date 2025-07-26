<template>
  <div class="crear-paciente-form bg-white p-12 rounded-2xl shadow-2xl mt-12 max-w-2xl mx-auto flex flex-col items-center">
    <h2 class="text-3xl font-extrabold mb-8 text-[#a259ff] text-center">Crear Paciente</h2>
    <form @submit.prevent="crearPaciente" class="w-full flex flex-col gap-6">
      <div>
        <label class="block mb-2 font-semibold text-lg">Nombre completo *</label>
        <input v-model="nombre_completo" type="text" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" required />
      </div>
      <div>
        <label class="block mb-2 font-semibold text-lg">Teléfono</label>
        <input v-model="telefono" type="text" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" />
      </div>
      <div>
        <label class="block mb-2 font-semibold text-lg">Fecha de nacimiento</label>
        <input v-model="fecha_nacimiento" type="date" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" />
      </div>
      <button type="submit" class="bg-[#a259ff] text-white px-4 py-3 rounded-xl font-bold w-full text-xl shadow hover:bg-[#7c3aed] transition-colors">Crear paciente</button>
      <div v-if="error" class="text-red-600 mt-2 text-center text-lg">{{ error }}</div>
      <div v-if="exito" class="text-green-600 mt-2 text-center text-lg">{{ exito }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const nombre_completo = ref('');
const telefono = ref('');
const fecha_nacimiento = ref('');
const error = ref('');
const exito = ref('');

async function crearPaciente() {
  error.value = '';
  exito.value = '';
  if (!nombre_completo.value) {
    error.value = 'El nombre completo es obligatorio.';
    return;
  }
  try {
    const res = await fetch('/api/pacientes', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nombre_completo: nombre_completo.value,
        telefono: telefono.value,
        fecha_nacimiento: fecha_nacimiento.value || null
      })
    });
    if (!res.ok) throw new Error('Error al crear paciente');
    exito.value = 'Paciente creado exitosamente.';
    nombre_completo.value = '';
    telefono.value = '';
    fecha_nacimiento.value = '';
  } catch (e) {
    error.value = 'No se pudo crear el paciente.';
  }
}
</script>

<style scoped>
.crear-paciente-form {
  min-height: 500px;
}

input, button {
  font-family: 'Montserrat', Arial, sans-serif;
}
</style>
