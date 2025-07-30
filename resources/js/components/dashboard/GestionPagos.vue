<template>
  <div class="pagos-container">
    <!-- Header -->
    <div class="header-section">
      <h2 class="title">💰 Gestión de Pagos</h2>
      <p class="subtitle">Sistema integral de pagos y financiamiento</p>
    </div>

    <!-- Opciones principales -->
    <div class="opciones-principales">
      <button 
        @click="mostrarOpcion('registrar')"
        :class="['opcion-btn', { active: opcionActiva === 'registrar' }]"
      >
        <i class='bx bx-plus-circle'></i>
        Registrar Nuevo Pago
      </button>
      
      <button 
        @click="mostrarOpcion('ver')"
        :class="['opcion-btn', { active: opcionActiva === 'ver' }]"
      >
        <i class='bx bx-search'></i>
        Ver Pagos de Paciente
      </button>
      
      <button 
        @click="mostrarOpcion('cuota')"
        :class="['opcion-btn', { active: opcionActiva === 'cuota' }]"
      >
        <i class='bx bx-credit-card'></i>
        Registrar Pago de Cuota
      </button>
    </div>

    <!-- Resumen de pagos -->
    <div v-if="resumen" class="resumen-pagos">
      <div class="resumen-card">
        <i class='bx bx-money'></i>
        <div>
          <h3>${{ formatearMonto(resumen.total_pagos_pendientes) }}</h3>
          <p>Pagos Pendientes</p>
        </div>
      </div>
      
      <div class="resumen-card">
        <i class='bx bx-trending-up'></i>
        <div>
          <h3>${{ formatearMonto(resumen.total_pagos_mes) }}</h3>
          <p>Ingresos del Mes</p>
        </div>
      </div>
      
      <div class="resumen-card">
        <i class='bx bx-user'></i>
        <div>
          <h3>{{ resumen.pacientes_con_deuda }}</h3>
          <p>Pacientes con Deuda</p>
        </div>
      </div>
      
      <div class="resumen-card alert" v-if="resumen.cuotas_vencidas > 0">
        <i class='bx bx-error'></i>
        <div>
          <h3>{{ resumen.cuotas_vencidas }}</h3>
          <p>Cuotas Vencidas</p>
        </div>
      </div>
    </div>

    <!-- Contenido según opción seleccionada -->
    <div class="contenido-principal">
      
      <!-- OPCIÓN 1: Registrar Nuevo Pago -->
      <div v-if="opcionActiva === 'registrar'" class="seccion-registrar">
        <h3>📝 Registrar Nuevo Pago</h3>
        
        <form @submit.prevent="registrarPago" class="form-pago">
          <!-- Selección de paciente -->
          <div class="form-group">
            <label>👤 Paciente</label>
            <select v-model="nuevoPago.paciente_id" required>
              <option value="">Seleccionar paciente...</option>
              <option v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id">
                {{ paciente.nombre_completo }}
              </option>
            </select>
          </div>

          <!-- Descripción del tratamiento -->
          <div class="form-group">
            <label>📋 Descripción del Tratamiento</label>
            <textarea 
              v-model="nuevoPago.descripcion" 
              placeholder="Ej: Ortodoncia completa, Implante dental, etc."
              required
            ></textarea>
          </div>

          <!-- Monto total -->
          <div class="form-group">
            <label>💵 Monto Total</label>
            <input 
              type="text" 
              v-model="nuevoPago.monto_total" 
              @input="formatearInputMonto($event, 'monto_total')"
              placeholder="0"
              required
            >
          </div>

          <!-- Modalidad de pago -->
          <div class="form-group">
            <label>💳 Modalidad de Pago</label>
            <select v-model="nuevoPago.modalidad_pago" required>
              <option value="">Seleccionar modalidad...</option>
              <option value="pago_unico">Pago Único</option>
              <option value="cuotas_fijas">Pago en Cuotas Fijas</option>
              <option value="cuotas_variables">Pago con Cuotas Variables</option>
            </select>
          </div>

          <!-- Configuración para cuotas fijas -->
          <div v-if="nuevoPago.modalidad_pago === 'cuotas_fijas'" class="form-group">
            <label>🔢 Cantidad de Cuotas</label>
            <input 
              type="number" 
              v-model="nuevoPago.total_cuotas" 
              min="1" 
              max="60"
              placeholder="Ej: 6"
              required
            >
            <div v-if="nuevoPago.total_cuotas && nuevoPago.monto_total" class="cuota-info">
              <p>💡 Cada cuota será de: <strong>${{ calcularMontoCuota() }}</strong></p>
            </div>
          </div>

          <!-- Fecha de pago -->
          <div class="form-group">
            <label>📅 Fecha de Pago</label>
            <input 
              type="date" 
              v-model="nuevoPago.fecha_pago"
              required
            >
          </div>

          <!-- Observaciones -->
          <div class="form-group">
            <label>📝 Observaciones (Opcional)</label>
            <textarea 
              v-model="nuevoPago.observaciones" 
              placeholder="Observaciones adicionales..."
            ></textarea>
          </div>

          <button type="submit" class="btn-registrar" :disabled="cargando">
            <i class='bx bx-save'></i>
            {{ cargando ? 'Registrando...' : 'Registrar Pago' }}
          </button>
        </form>
      </div>

      <!-- OPCIÓN 2: Ver Pagos de Paciente -->
      <div v-if="opcionActiva === 'ver'" class="seccion-ver">
        <h3>🔍 Ver Pagos de Paciente</h3>
        
        <!-- Selector de paciente -->
        <div class="form-group">
          <label>👤 Seleccionar Paciente</label>
          <select v-model="pacienteSeleccionado" @change="cargarPagosPaciente">
            <option value="">Seleccionar paciente...</option>
            <option v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id">
              {{ paciente.nombre_completo }}
            </option>
          </select>
        </div>

        <!-- Información del paciente y sus pagos -->
        <div v-if="pagosPaciente" class="info-paciente">
          <div class="paciente-header">
            <div class="header-content">
              <h4>👤 {{ pagosPaciente.paciente.nombre_completo }}</h4>
              <button @click="exportarPagosPDF" class="btn-pdf" :disabled="cargando">
                <i class='bx bx-file-export'></i>
                {{ cargando ? 'Generando...' : 'Exportar PDF' }}
              </button>
            </div>
            <div class="totales-paciente">
              <div class="total-item">
                <span>Total Tratamientos:</span>
                <strong>${{ formatearMonto(pagosPaciente.totales.monto_total_tratamientos) }}</strong>
              </div>
              <div class="total-item">
                <span>Total Pagado:</span>
                <strong>${{ formatearMonto(pagosPaciente.totales.monto_total_pagado) }}</strong>
              </div>
              <div class="total-item">
                <span>Saldo Restante:</span>
                <strong class="saldo-restante">${{ formatearMonto(pagosPaciente.totales.saldo_total_restante) }}</strong>
              </div>
            </div>
          </div>

          <!-- Lista de pagos/tratamientos -->
          <div class="tratamientos-lista">
            <div v-for="pago in pagosPaciente.pagos" :key="pago.id" class="tratamiento-card">
              <div class="tratamiento-header">
                <h5>{{ pago.descripcion }}</h5>
                <span :class="['estado-badge', pago.estado_pago]">
                  {{ obtenerTextoEstado(pago.estado_pago) }}
                </span>
              </div>

              <div class="tratamiento-info">
                <div class="info-row">
                  <span>💵 Monto Total:</span>
                  <strong>${{ formatearMonto(pago.monto_total) }}</strong>
                </div>
                <div class="info-row">
                  <span>💳 Modalidad:</span>
                  <strong>{{ obtenerTextoModalidad(pago.modalidad_pago) }}</strong>
                </div>
                <div class="info-row">
                  <span>✅ Pagado:</span>
                  <strong>${{ formatearMonto(pago.monto_pagado) }}</strong>
                </div>
                <div class="info-row">
                  <span>⏳ Restante:</span>
                  <strong>${{ formatearMonto(pago.saldo_restante) }}</strong>
                </div>
              </div>

              <!-- Barra de progreso -->
              <div class="progreso-pago">
                <div class="progreso-bar">
                  <div 
                    class="progreso-fill" 
                    :style="{ width: calcularPorcentajePago(pago) + '%' }"
                  ></div>
                </div>
                <span class="progreso-texto">{{ calcularPorcentajePago(pago) }}% pagado</span>
              </div>

              <!-- Cuotas fijas -->
              <div v-if="pago.modalidad_pago === 'cuotas_fijas' && pago.cuotas" class="cuotas-fijas">
                <h6>📊 Plan de Cuotas:</h6>
                <div class="cuotas-grid">
                  <div 
                    v-for="cuota in pago.cuotas" 
                    :key="cuota.id" 
                    :class="['cuota-item', cuota.estado]"
                  >
                    <span class="cuota-numero">{{ cuota.numero_cuota }}</span>
                    <span class="cuota-monto">${{ formatearMonto(cuota.monto) }}</span>
                    <span class="cuota-vencimiento">{{ formatearFecha(cuota.fecha_vencimiento) }}</span>
                    <span :class="['cuota-estado', cuota.estado]">
                      {{ cuota.estado === 'pagada' ? '✅' : '⏳' }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Historial de pagos -->
              <div v-if="pago.detalles_pagos && pago.detalles_pagos.length > 0" class="historial-pagos">
                <h6>📝 Historial de Pagos:</h6>
                <div class="pagos-timeline">
                  <div 
                    v-for="detalle in pago.detalles_pagos" 
                    :key="detalle.id" 
                    class="pago-item"
                  >
                    <div class="pago-fecha">{{ formatearFecha(detalle.fecha_pago) }}</div>
                    <div class="pago-monto">${{ formatearMonto(detalle.monto_parcial) }}</div>
                    <div class="pago-descripcion">{{ detalle.descripcion }}</div>
                    <div class="pago-usuario">Por: {{ detalle.nombre_usuario }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- OPCIÓN 3: Registrar Pago de Cuota -->
      <div v-if="opcionActiva === 'cuota'" class="seccion-cuota">
        <h3>💳 Registrar Pago de Cuota</h3>
        
        <!-- Selector de paciente para ver sus pagos pendientes -->
        <div class="form-group">
          <label>👤 Paciente</label>
          <select v-model="pacienteCuota" @change="cargarPagosPendientes">
            <option value="">Seleccionar paciente...</option>
            <option v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id">
              {{ paciente.nombre_completo }}
            </option>
          </select>
        </div>

        <!-- Lista de pagos pendientes -->
        <div v-if="pagosPendientes.length > 0" class="pagos-pendientes">
          <h4>📋 Tratamientos con Pagos Pendientes:</h4>
          
          <div v-for="pago in pagosPendientes" :key="pago.id" class="pago-pendiente">
            <div class="pago-header">
              <h5>{{ pago.descripcion }}</h5>
              <span class="saldo">Saldo: ${{ formatearMonto(pago.saldo_restante) }}</span>
            </div>
            
            <form @submit.prevent="registrarCuota(pago)" class="form-cuota">
              <div class="form-row">
                <div class="form-group">
                  <label>💵 Monto a Pagar</label>
                  <input 
                    type="text" 
                    v-model="pago.monto_cuota" 
                    @input="formatearInputMonto($event, 'monto_cuota', pago)"
                    placeholder="0"
                    required
                  >
                  <div v-if="pago.monto_cuota && !validarMontoCuota(pago)" class="error-monto">
                    ⚠️ El monto no puede exceder el saldo restante (${{ formatearMonto(pago.saldo_restante) }})
                  </div>
                </div>
                
                <div class="form-group">
                  <label>📅 Fecha de Pago</label>
                  <input 
                    type="date" 
                    v-model="pago.fecha_cuota"
                    required
                  >
                </div>

                <div v-if="pago.modalidad_pago === 'cuotas_fijas'" class="form-group">
                  <label>🔢 Número de Cuota</label>
                  <select v-model="pago.numero_cuota">
                    <option value="">Cuota...</option>
                    <option v-for="n in pago.total_cuotas" :key="n" :value="n">
                      Cuota {{ n }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label>📝 Descripción</label>
                <input 
                  type="text" 
                  v-model="pago.descripcion_cuota" 
                  placeholder="Descripción del pago..."
                >
              </div>

              <button type="submit" class="btn-pagar" :disabled="cargando || !validarMontoCuota(pago)">
                <i class='bx bx-credit-card'></i>
                {{ cargando ? 'Procesando...' : 'Registrar Pago' }}
              </button>
            </form>
          </div>
        </div>

        <div v-else-if="pacienteCuota" class="no-pagos">
          <p>✅ Este paciente no tiene pagos pendientes.</p>
        </div>
      </div>
    </div>

    <!-- Mensajes de éxito/error -->
    <div v-if="mensaje" :class="['mensaje', mensaje.tipo]">
      <i :class="mensaje.tipo === 'exito' ? 'bx bx-check-circle' : 'bx bx-error-circle'"></i>
      {{ mensaje.texto }}
    </div>
  </div>
</template>

<script>
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import axios from 'axios';

export default {
  name: 'GestionPagos',
  data() {
    return {
      opcionActiva: 'registrar',
      cargando: false,
      mensaje: null,
      
      // Datos generales
      pacientes: [],
      resumen: null,
      
      // Registro de nuevo pago
      nuevoPago: {
        paciente_id: '',
        descripcion: '',
        monto_total: '',
        modalidad_pago: '',
        total_cuotas: '',
        fecha_pago: new Date().toISOString().split('T')[0],
        observaciones: ''
      },
      
      // Ver pagos de paciente
      pacienteSeleccionado: '',
      pagosPaciente: null,
      
      // Registrar cuota
      pacienteCuota: '',
      pagosPendientes: []
    }
  },
  
  mounted() {
    this.inicializar();
  },
  
  methods: {
    // === MÉTODOS PRINCIPALES ===
    async inicializar() {
      try {
        await this.cargarDatos();
      } catch (error) {
        this.mostrarMensaje('Error al inicializar la aplicación', 'error');
      }
    },
    
    async cargarDatos() {
      try {
        this.cargando = true;
        
        // Cargar pacientes
        const resPacientes = await axios.get('/api/pagos/pacientes');
        if (resPacientes.data.success) {
          this.pacientes = resPacientes.data.pacientes;
        }
        
        // Cargar resumen
        const resResumen = await axios.get('/api/pagos/resumen');
        if (resResumen.data.success) {
          this.resumen = resResumen.data.resumen;
        }
        
      } catch (error) {
        this.mostrarMensaje('Error al cargar datos', 'error');
      } finally {
        this.cargando = false;
      }
    },

    mostrarOpcion(opcion) {
      this.opcionActiva = opcion;
      this.limpiarMensaje();
    },
    
    async registrarPago() {
      try {
        this.cargando = true;
        
        // Preparar datos limpiando los montos formateados
        const datosLimpios = {
          ...this.nuevoPago,
          monto_total: this.limpiarMonto(this.nuevoPago.monto_total)
        };
        
        const response = await axios.post('/api/pagos/registrar', datosLimpios);
        
        if (response.data.success) {
          this.mostrarMensaje('Pago registrado exitosamente', 'exito');
          this.limpiarFormulario();
          this.cargarDatos(); // Actualizar resumen
        } else {
          this.mostrarMensaje(response.data.message || 'Error al registrar pago', 'error');
        }
        
      } catch (error) {
        this.mostrarMensaje('Error de conexión', 'error');
      } finally {
        this.cargando = false;
      }
    },
    
    async cargarPagosPaciente() {
      if (!this.pacienteSeleccionado) {
        this.pagosPaciente = null;
        return;
      }
      
      try {
        this.cargando = true;
        
        const response = await axios.get(`/api/pagos/paciente/${this.pacienteSeleccionado}`);
        
        if (response.data.success) {
          this.pagosPaciente = response.data;
        } else {
          this.mostrarMensaje(response.data.message || 'Error al cargar pagos', 'error');
        }
        
      } catch (error) {
        this.mostrarMensaje('Error de conexión', 'error');
      } finally {
        this.cargando = false;
      }
    },
    
    async cargarPagosPendientes() {
      if (!this.pacienteCuota) {
        this.pagosPendientes = [];
        return;
      }
      
      try {
        const response = await axios.get(`/api/pagos/paciente/${this.pacienteCuota}`);
        
        if (response.data.success) {
          // Filtrar solo pagos pendientes
          this.pagosPendientes = response.data.pagos
            .filter(pago => pago.estado_pago !== 'pagado_completo')
            .map(pago => ({
              ...pago,
              monto_cuota: '',
              fecha_cuota: new Date().toISOString().split('T')[0],
              numero_cuota: '',
              descripcion_cuota: ''
            }));
        }
        
      } catch (error) {
        this.mostrarMensaje('Error al cargar pagos pendientes', 'error');
      }
    },
    
    async registrarCuota(pago) {
      try {
        // Validación del lado del cliente
        const montoLimpio = parseFloat(this.limpiarMonto(pago.monto_cuota));
        const saldoRestante = parseFloat(pago.saldo_restante);
        
        if (!montoLimpio || montoLimpio <= 0) {
          this.mostrarMensaje('El monto debe ser mayor a 0', 'error');
          return;
        }
        
        if (montoLimpio > saldoRestante) {
          this.mostrarMensaje(`El monto no puede exceder el saldo restante ($${this.formatearMonto(saldoRestante)})`, 'error');
          return;
        }
        
        this.cargando = true;
        
        const response = await axios.post('/api/pagos/cuota', {
          pago_id: pago.id,
          monto_cuota: this.limpiarMonto(pago.monto_cuota),
          fecha_pago: pago.fecha_cuota,
          descripcion: pago.descripcion_cuota,
          numero_cuota: pago.numero_cuota
        });
        
        if (response.data.success) {
          this.mostrarMensaje('Pago de cuota registrado exitosamente', 'exito');
          this.cargarPagosPendientes(); // Recargar lista
          this.cargarDatos(); // Actualizar resumen
        } else {
          this.mostrarMensaje(response.data.message || 'Error al registrar cuota', 'error');
        }
        
      } catch (error) {
        this.mostrarMensaje('Error de conexión', 'error');
      } finally {
        this.cargando = false;
      }
    },
    
    calcularMontoCuota() {
      if (!this.nuevoPago.monto_total || !this.nuevoPago.total_cuotas) return '0';
      const montoLimpio = this.limpiarMonto(this.nuevoPago.monto_total);
      const monto = parseFloat(montoLimpio) / parseInt(this.nuevoPago.total_cuotas);
      return this.formatearMontoInput(monto);
    },
    
    formatearInputMonto(event, campo, objeto = null) {
      let valor = event.target.value;
      
      // Remover todo excepto números
      valor = valor.replace(/[^\d]/g, '');
      
      // Si está vacío, mantener vacío
      if (!valor) {
        if (objeto) {
          objeto[campo] = '';
        } else {
          this.nuevoPago[campo] = '';
        }
        return;
      }
      
      // Validar límite para pagos de cuotas
      if (objeto && campo === 'monto_cuota' && objeto.saldo_restante) {
        const montoNumerico = parseInt(valor);
        const saldoMaximo = parseFloat(objeto.saldo_restante);
        
        if (montoNumerico > saldoMaximo) {
          // No permitir escribir un monto mayor al saldo
          valor = Math.floor(saldoMaximo).toString();
        }
      }
      
      // Formatear con separadores de miles
      const numeroFormateado = this.formatearMontoInput(parseInt(valor));
      
      // Actualizar el valor
      if (objeto) {
        objeto[campo] = numeroFormateado;
      } else {
        this.nuevoPago[campo] = numeroFormateado;
      }
    },
    
    formatearMontoInput(numero) {
      if (!numero) return '';
      return numero.toLocaleString('en-US');
    },
    
    limpiarMonto(montoFormateado) {
      if (!montoFormateado) return '0';
      return montoFormateado.toString().replace(/,/g, '');
    },
    
    calcularPorcentajePago(pago) {
      if (pago.monto_total === 0) return 0;
      return Math.round((pago.monto_pagado / pago.monto_total) * 100);
    },
    
    formatearMonto(monto) {
      return parseFloat(monto || 0).toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    },
    
    formatearFecha(fecha) {
      return new Date(fecha).toLocaleDateString('es-AR');
    },
    
    obtenerTextoEstado(estado) {
      const estados = {
        'pendiente': 'Pendiente',
        'pagado_parcial': 'Parcial',
        'pagado_completo': 'Completo',
        'vencido': 'Vencido'
      };
      return estados[estado] || estado;
    },
    
    obtenerTextoModalidad(modalidad) {
      const modalidades = {
        'pago_unico': 'Pago Único',
        'cuotas_fijas': 'Cuotas Fijas',
        'cuotas_variables': 'Cuotas Variables'
      };
      return modalidades[modalidad] || modalidad;
    },
    
    limpiarFormulario() {
      this.nuevoPago = {
        paciente_id: '',
        descripcion: '',
        monto_total: '',
        modalidad_pago: '',
        total_cuotas: '',
        fecha_pago: new Date().toISOString().split('T')[0],
        observaciones: ''
      };
    },
    
    mostrarMensaje(texto, tipo) {
      this.mensaje = { texto, tipo };
      setTimeout(() => {
        this.mensaje = null;
      }, 5000);
    },
    
    limpiarMensaje() {
      this.mensaje = null;
    },
    
    // Validar si el monto de cuota es válido
    validarMontoCuota(pago) {
      if (!pago.monto_cuota) return false;
      const montoLimpio = parseFloat(this.limpiarMonto(pago.monto_cuota));
      const saldoRestante = parseFloat(pago.saldo_restante);
      return montoLimpio > 0 && montoLimpio <= saldoRestante;
    },
    
    // Exportar pagos a PDF
    async exportarPagosPDF() {
      try {
        if (!this.pagosPaciente) {
          this.mostrarMensaje('No hay datos para exportar', 'error');
          return;
        }
        
        this.cargando = true;
        
        // Obtener información del usuario actual
        const usuarioActual = await this.obtenerUsuarioActual();
        
        const doc = new jsPDF();
        
        // === CONFIGURACIÓN DEL DOCUMENTO ===
        const fechaActual = new Date().toLocaleDateString('es-AR', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
        
        // === ENCABEZADO ===
        doc.setFillColor(162, 89, 255);
        doc.rect(0, 0, 210, 40, 'F');
        
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(22);
        doc.setFont('helvetica', 'bold');
        doc.text('REPORTE DE PAGOS DE PACIENTE', 105, 20, { align: 'center' });
        
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text(`Generado el: ${fechaActual}`, 105, 30, { align: 'center' });
        
        // === INFORMACIÓN DEL PACIENTE ===
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.text('INFORMACIÓN DEL PACIENTE', 20, 55);
        
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text(`Paciente: ${this.pagosPaciente.paciente.nombre_completo}`, 20, 70);
        
        // === RESUMEN FINANCIERO ===
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.text('RESUMEN FINANCIERO', 20, 90);
        
        const resumenData = [
          ['Total Tratamientos', `$${this.formatearMonto(this.pagosPaciente.totales.monto_total_tratamientos)}`],
          ['Total Pagado', `$${this.formatearMonto(this.pagosPaciente.totales.monto_total_pagado)}`],
          ['Saldo Restante', `$${this.formatearMonto(this.pagosPaciente.totales.saldo_total_restante)}`]
        ];
        
        autoTable(doc, {
          startY: 95,
          head: [['Concepto', 'Monto']],
          body: resumenData,
          theme: 'striped',
          headStyles: {
            fillColor: [162, 89, 255],
            textColor: [255, 255, 255],
            fontStyle: 'bold'
          },
          styles: {
            fontSize: 11,
            cellPadding: 8
          },
          columnStyles: {
            1: { halign: 'right', fontStyle: 'bold' }
          }
        });
        
        let yPosition = doc.lastAutoTable.finalY + 20;
        
        // === DETALLE DE TRATAMIENTOS ===
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.text('DETALLE DE TRATAMIENTOS Y PAGOS', 20, yPosition);
        
        yPosition += 10;
        
        // Procesar cada tratamiento
        for (const pago of this.pagosPaciente.pagos) {
          // Verificar si necesitamos nueva página
          if (yPosition > 250) {
            doc.addPage();
            yPosition = 20;
          }
          
          // Información del tratamiento
          const tratamientoData = [
            ['Descripción', pago.descripcion],
            ['Monto Total', `$${this.formatearMonto(pago.monto_total)}`],
            ['Modalidad', this.obtenerTextoModalidad(pago.modalidad_pago)],
            ['Estado', this.obtenerTextoEstado(pago.estado_pago)],
            ['Monto Pagado', `$${this.formatearMonto(pago.monto_pagado)}`],
            ['Saldo Restante', `$${this.formatearMonto(pago.saldo_restante)}`],
            ['% Completado', `${this.calcularPorcentajePago(pago)}%`]
          ];
          
          autoTable(doc, {
            startY: yPosition,
            head: [['Tratamiento', 'Información']],
            body: tratamientoData,
            theme: 'grid',
            headStyles: {
              fillColor: [52, 152, 219],
              textColor: [255, 255, 255],
              fontStyle: 'bold'
            },
            styles: {
              fontSize: 10,
              cellPadding: 6
            },
            columnStyles: {
              0: { fontStyle: 'bold', cellWidth: 40 },
              1: { cellWidth: 130 }
            }
          });
          
          yPosition = doc.lastAutoTable.finalY + 5;
          
          // Historial de pagos del tratamiento
          if (pago.detalles_pagos && pago.detalles_pagos.length > 0) {
            if (yPosition > 240) {
              doc.addPage();
              yPosition = 20;
            }
            
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.text('Historial de Pagos:', 20, yPosition);
            yPosition += 5;
            
            const pagosData = pago.detalles_pagos.map(detalle => [
              this.formatearFecha(detalle.fecha_pago),
              `$${this.formatearMonto(detalle.monto_parcial)}`,
              detalle.descripcion || 'Pago parcial',
              detalle.nombre_usuario || 'Sistema'
            ]);
            
            autoTable(doc, {
              startY: yPosition,
              head: [['Fecha', 'Monto', 'Descripción', 'Registrado por']],
              body: pagosData,
              theme: 'striped',
              headStyles: {
                fillColor: [46, 204, 113],
                textColor: [255, 255, 255],
                fontStyle: 'bold'
              },
              styles: {
                fontSize: 9,
                cellPadding: 5
              },
              columnStyles: {
                0: { cellWidth: 25 },
                1: { cellWidth: 25, halign: 'right' },
                2: { cellWidth: 60 },
                3: { cellWidth: 40 }
              }
            });
            
            yPosition = doc.lastAutoTable.finalY + 10;
          }
          
          // Información de cuotas para pagos en cuotas fijas
          if (pago.modalidad_pago === 'cuotas_fijas' && pago.cuotas && pago.cuotas.length > 0) {
            if (yPosition > 240) {
              doc.addPage();
              yPosition = 20;
            }
            
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.text('Plan de Cuotas:', 20, yPosition);
            yPosition += 5;
            
            const cuotasData = pago.cuotas.map(cuota => [
              `Cuota ${cuota.numero_cuota}`,
              `$${this.formatearMonto(cuota.monto)}`,
              this.formatearFecha(cuota.fecha_vencimiento),
              cuota.estado === 'pagada' ? 'PAGADA' : 'PENDIENTE'
            ]);
            
            autoTable(doc, {
              startY: yPosition,
              head: [['Cuota', 'Monto', 'Vencimiento', 'Estado']],
              body: cuotasData,
              theme: 'striped',
              headStyles: {
                fillColor: [155, 89, 182],
                textColor: [255, 255, 255],
                fontStyle: 'bold'
              },
              styles: {
                fontSize: 9,
                cellPadding: 5
              },
              columnStyles: {
                0: { cellWidth: 30 },
                1: { cellWidth: 30, halign: 'right' },
                2: { cellWidth: 35 },
                3: { cellWidth: 30, halign: 'center' }
              }
            });
            
            yPosition = doc.lastAutoTable.finalY + 15;
          }
        }
        
        // === PIE DE PÁGINA ===
        const totalPages = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPages; i++) {
          doc.setPage(i);
          
          // Información del usuario que generó el reporte
          doc.setFontSize(8);
          doc.setFont('helvetica', 'normal');
          doc.setTextColor(100, 100, 100);
          doc.text(`Reporte generado por: ${usuarioActual.nombre} (${usuarioActual.email})`, 20, 285);
          doc.text(`Página ${i} de ${totalPages}`, 170, 285);
          
          // Línea decorativa
          doc.setDrawColor(162, 89, 255);
          doc.setLineWidth(0.5);
          doc.line(20, 280, 190, 280);
        }
        
        // Guardar el archivo
        const nombreArchivo = `Pagos_${this.pagosPaciente.paciente.nombre_completo.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(nombreArchivo);
        
        this.mostrarMensaje('PDF exportado exitosamente', 'exito');
        
      } catch (error) {
        console.error('Error al exportar PDF:', error);
        this.mostrarMensaje('Error al generar el PDF', 'error');
      } finally {
        this.cargando = false;
      }
    },
    
    // Obtener información del usuario actual
    async obtenerUsuarioActual() {
      try {
        const response = await axios.get('/api/user');
        return {
          nombre: response.data.user?.name || 'Usuario no identificado',
          email: response.data.user?.email || 'email@noidentificado.com'
        };
      } catch (error) {
        return {
          nombre: 'Usuario no identificado',
          email: 'email@noidentificado.com'
        };
      }
    }
  }
}
</script>

<style scoped>
.pagos-container {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.header-section {
  text-align: center;
  margin-bottom: 30px;
}

.title {
  color: #a259ff;
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.subtitle {
  color: #666;
  font-size: 1.1rem;
}

.opciones-principales {
  display: flex;
  gap: 15px;
  margin-bottom: 30px;
  justify-content: center;
  flex-wrap: wrap;
}

.opcion-btn {
  background: white;
  border: 2px solid #e1e5e9;
  padding: 15px 25px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
}

.opcion-btn:hover {
  border-color: #a259ff;
  color: #a259ff;
  transform: translateY(-2px);
}

.opcion-btn.active {
  background: #a259ff;
  color: white;
  border-color: #a259ff;
}

.resumen-pagos {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.resumen-card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  gap: 15px;
}

.resumen-card.alert {
  border-left: 4px solid #ef4444;
}

.resumen-card i {
  font-size: 2rem;
  color: #a259ff;
}

.resumen-card.alert i {
  color: #ef4444;
}

.resumen-card h3 {
  margin: 0;
  font-size: 1.8rem;
  color: #333;
}

.resumen-card p {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.contenido-principal {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-pago {
  max-width: 600px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 12px;
  border: 2px solid #e1e5e9;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #a259ff;
}

.form-group textarea {
  resize: vertical;
  min-height: 80px;
}

.cuota-info {
  margin-top: 10px;
  padding: 10px;
  background: #f8f9fa;
  border-radius: 6px;
  border-left: 4px solid #a259ff;
}

.btn-registrar,
.btn-pagar {
  background: #a259ff;
  color: white;
  padding: 12px 30px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: background 0.3s ease;
}

.btn-registrar:hover,
.btn-pagar:hover {
  background: #8a47e8;
}

.btn-registrar:disabled,
.btn-pagar:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.info-paciente {
  margin-top: 20px;
}

.paciente-header {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  flex-wrap: wrap;
  gap: 15px;
}

.paciente-header h4 {
  margin: 0;
  color: #333;
}

.btn-pdf {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.btn-pdf:hover:not(:disabled) {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.btn-pdf:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.totales-paciente {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.total-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background: white;
  border-radius: 6px;
}

.saldo-restante {
  color: #ef4444;
}

.tratamiento-card {
  border: 1px solid #e1e5e9;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
}

.tratamiento-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.tratamiento-header h5 {
  margin: 0;
  color: #333;
}

.estado-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.estado-badge.pendiente {
  background: #fef3c7;
  color: #92400e;
}

.estado-badge.pagado_parcial {
  background: #dbeafe;
  color: #1e40af;
}

.estado-badge.pagado_completo {
  background: #d1fae5;
  color: #065f46;
}

.tratamiento-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 10px;
  margin-bottom: 15px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
}

.progreso-pago {
  margin: 15px 0;
}

.progreso-bar {
  width: 100%;
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.progreso-fill {
  height: 100%;
  background: #a259ff;
  transition: width 0.3s ease;
}

.progreso-texto {
  font-size: 0.9rem;
  color: #666;
  margin-top: 5px;
  display: block;
}

.cuotas-fijas h6,
.historial-pagos h6 {
  margin: 15px 0 10px 0;
  color: #333;
}

.cuotas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 10px;
}

.cuota-item {
  background: #f8f9fa;
  padding: 12px;
  border-radius: 8px;
  text-align: center;
  border: 2px solid #e1e5e9;
}

.cuota-item.pagada {
  background: #d1fae5;
  border-color: #10b981;
}

.cuota-numero {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.cuota-monto {
  display: block;
  color: #333;
  margin-bottom: 5px;
}

.cuota-vencimiento {
  display: block;
  font-size: 0.8rem;
  color: #666;
  margin-bottom: 5px;
}

.cuota-estado {
  font-size: 1.2rem;
}

.pago-item {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  border-left: 4px solid #a259ff;
  margin-bottom: 10px;
}

.pago-fecha {
  font-weight: bold;
  color: #333;
}

.pago-monto {
  font-size: 1.1rem;
  color: #059669;
  font-weight: bold;
}

.pago-descripcion {
  color: #666;
  margin: 5px 0;
}

.pago-usuario {
  font-size: 0.9rem;
  color: #9ca3af;
}

.pagos-pendientes {
  margin-top: 20px;
}

.pago-pendiente {
  border: 1px solid #e1e5e9;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
}

.pago-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.pago-header h5 {
  margin: 0;
  color: #333;
}

.saldo {
  font-weight: bold;
  color: #ef4444;
}

.form-cuota {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
  margin-bottom: 15px;
}

.no-pagos {
  text-align: center;
  padding: 40px;
  color: #666;
}

.mensaje {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px 20px;
  border-radius: 8px;
  color: white;
  font-weight: 500;
  z-index: 1000;
  display: flex;
  align-items: center;
  gap: 10px;
}

.mensaje.exito {
  background: #10b981;
}

.mensaje.error {
  background: #ef4444;
}

.error-monto {
  color: #ef4444;
  font-size: 0.85rem;
  margin-top: 5px;
  padding: 5px 10px;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 4px;
  display: flex;
  align-items: center;
  gap: 5px;
}

/* Responsive */
@media (max-width: 768px) {
  .opciones-principales {
    flex-direction: column;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .totales-paciente {
    grid-template-columns: 1fr;
  }
  
  .tratamiento-info {
    grid-template-columns: 1fr;
  }
  
  .cuotas-grid {
    grid-template-columns: 1fr;
  }
  
  .header-content {
    flex-direction: column;
    align-items: stretch;
  }
  
  .btn-pdf {
    justify-content: center;
  }
}

/* === ESTILOS PARA CONTROL DE SESIÓN === */
.session-status {
  margin-top: 15px;
  padding: 15px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14px;
  font-weight: 500;
}

.session-active {
  background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
  color: #155724;
  border: 1px solid #c3e6cb;
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
}

.session-inactive {
  background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
  color: #721c24;
  border: 1px solid #f5c6cb;
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
}

.session-status i {
  font-size: 18px;
}

.btn-logout, .btn-login {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.btn-logout {
  background: #dc3545;
  color: white;
}

.btn-logout:hover {
  background: #c82333;
  transform: translateY(-1px);
}

.btn-login {
  background: #007bff;
  color: white;
}

.btn-login:hover:not(:disabled) {
  background: #0056b3;
  transform: translateY(-1px);
}

.btn-login:disabled {
  background: #6c757d;
  cursor: not-allowed;
}
</style>
