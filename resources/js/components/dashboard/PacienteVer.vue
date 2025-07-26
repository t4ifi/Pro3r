<template>
  <div class="max-w-4xl mx-auto bg-white p-12 rounded-2xl shadow-2xl mt-8">
    <h2 class="text-3xl font-bold mb-8 text-[#a259ff] text-center">Listado de Pacientes</h2>
    <input v-model="busqueda" type="text" placeholder="Buscar por nombre..." class="w-full mb-6 px-4 py-3 border rounded-lg text-lg" />
    <div v-if="loading" class="text-gray-500 text-center">Cargando pacientes...</div>
    <div v-else>
      <div v-if="filtrados.length === 0" class="text-gray-500 text-center">No hay pacientes registrados.</div>
      <table v-else class="w-full border rounded-xl overflow-hidden shadow">
        <thead class="bg-[#f3eaff]">
          <tr>
            <th class="py-3 px-5 text-left text-lg">Nombre completo</th>
            <th class="py-3 px-5 text-left text-lg">Teléfono</th>
            <th class="py-3 px-5 text-left text-lg">Fecha de nacimiento</th>
            <th class="py-3 px-5 text-left text-lg">Última visita</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in filtrados" :key="p.id" class="border-b hover:bg-[#f6f6f6] transition">
            <td class="py-3 px-5 font-semibold text-base">{{ p.nombre_completo }}</td>
            <td class="py-3 px-5 text-base">{{ p.telefono || '-' }}</td>
            <td class="py-3 px-5 text-base">{{ p.fecha_nacimiento || '-' }}</td>
            <td class="py-3 px-5 text-base">{{ p.ultima_visita || '-' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
const pacientes = ref([]);
const loading = ref(true);
const busqueda = ref('');

const filtrados = computed(() => {
  if (!busqueda.value) return pacientes.value;
  return pacientes.value.filter(p =>
    p.nombre_completo.toLowerCase().includes(busqueda.value.toLowerCase())
  );
});

async function cargarPacientes() {
  loading.value = true;
  try {
    const res = await fetch('/api/pacientes');
    pacientes.value = await res.json();
  } catch (e) {
    pacientes.value = [];
  } finally {
    loading.value = false;
  }
}

onMounted(cargarPacientes);
</script>

<style scoped>
table {
  font-family: 'Montserrat', Arial, sans-serif;
  border-collapse: collapse;
}
th, td {
  border: none;
}
input {
  font-size: 1.1rem;
}
</style>
