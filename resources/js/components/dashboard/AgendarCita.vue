<template>
  <div class="agendar-cita-form mejorada bg-white rounded-2xl shadow-2xl p-12 max-w-2xl mx-auto flex flex-col items-center">
    <h2 class="text-3xl font-extrabold mb-8 text-[#a259ff] text-center">Agendar Nueva Cita</h2>
    <form @submit.prevent="agendarCita" class="w-full flex flex-col gap-6">
      <div>
        <label class="block mb-2 font-semibold text-lg">Paciente</label>
        <select v-model="form.paciente" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" required>
          <option value="" disabled>Selecciona un paciente</option>
          <option v-for="p in pacientes" :key="p.id" :value="p.nombre_completo">{{ p.nombre_completo }}</option>
        </select>
      </div>
      <div>
        <label class="block mb-2 font-semibold text-lg">Fecha</label>
        <input v-model="form.fecha" type="date" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" required />
      </div>
      <div>
        <label class="block mb-2 font-semibold text-lg">Hora</label>
        <input v-model="form.hora" type="time" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" required />
      </div>
      <div>
        <label class="block mb-2 font-semibold text-lg">Motivo</label>
        <input v-model="form.motivo" type="text" class="w-full border-2 border-[#a259ff] rounded-xl px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-[#a259ff]" required placeholder="Motivo de la cita" />
      </div>
      <button type="submit" class="w-full py-3 rounded-xl bg-[#a259ff] text-white text-xl font-bold shadow hover:bg-[#7c3aed] transition-colors">Agendar</button>
    </form>
    <div v-if="exito" class="mt-6 text-green-600 font-bold text-lg">¡Cita agendada correctamente!</div>
    <div v-if="error" class="mt-6 text-red-600 font-bold text-lg">Error al agendar cita. Intenta nuevamente.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
const emit = defineEmits(['cita-agendada']);
const form = ref({ paciente: '', fecha: '', hora: '', motivo: '' });
const exito = ref(false);
const error = ref(false);
const pacientes = ref([]);

onMounted(async () => {
  try {
    const res = await fetch('/api/pacientes');
    pacientes.value = await res.json();
  } catch {
    pacientes.value = [];
  }
});

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
.agendar-cita-form.mejorada {
  margin-top: 60px;
  min-height: 600px;
  box-shadow: 0 8px 32px rgba(162,89,255,0.15);
  border: 1.5px solid #ece7fa;
}
</style>
