<template>
  <div class="tratamiento-registrar">
    <div class="page-header">
      <h1>
        <i class='bx bx-plus-medical'></i>
        Registrar Tratamiento y Observaciones
      </h1>
      <p>Registra nuevos tratamientos y observaciones para los pacientes</p>
    </div>
    
    <div class="content-card">
      <div class="coming-soon">
        <i class='bx bx-wrench'></i>
        <h2>Módulo en Desarrollo</h2>
        <p>Esta funcionalidad estará disponible próximamente.</p>
        <p>Aquí podrás registrar tratamientos dentales y observaciones clínicas.</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TratamientoRegistrar'
}
</script>

<style scoped>
.tratamiento-registrar {
  padding: 20px;
}

.page-header h1 {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #2c3e50;
  margin-bottom: 8px;
}

.page-header h1 i {
  color: #a259ff;
  font-size: 1.8rem;
}

.page-header p {
  color: #7f8c8d;
  margin-bottom: 24px;
}

.content-card {
  background: white;
  border-radius: 12px;
  padding: 40px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.coming-soon {
  text-align: center;
}

.coming-soon i {
  font-size: 4rem;
  color: #a259ff;
  margin-bottom: 20px;
}

.coming-soon h2 {
  color: #2c3e50;
  margin-bottom: 15px;
}

.coming-soon p {
  color: #7f8c8d;
  margin-bottom: 10px;
}
</style>
