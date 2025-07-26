<template>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="user-info">
        <div class="avatar-circle">
          <i class='bx bx-user'></i>
        </div>
        <div class="user-name">{{ usuarioGuardado.nombre }}</div>
        <div class="user-role">{{ usuarioGuardado.rol }}</div>
      </div>
      <nav>
        <!-- Citas -->
        <div class="sidebar-group">
          <div
            :class="['sidebar-link sidebar-link-group', activeGroup==='citas' ? 'active-menu' : '']"
            @click="usuarioGuardado.rol==='dentista' ? $router.push('/citas/calendario') : toggleMenu('citas')"
            style="cursor: pointer;"
          >
            <i class='bx bx-calendar'></i>
            <span class="sidebar-title">Citas</span>
            <i v-if="usuarioGuardado.rol!=='dentista'" :class="['bx', openMenu==='citas' ? 'bx-chevron-up' : 'bx-chevron-down', 'chevron']"></i>
          </div>
          <div v-if="openMenu==='citas' && usuarioGuardado.rol!=='dentista'" class="sidebar-submenu">
            <router-link :to="{ path: '/citas/calendario' }" class="sidebar-sublink" :class="$route.path === '/citas/calendario' ? 'active-sublink' : ''">Ver Calendario y citas</router-link>
            <router-link :to="{ path: '/citas/agendar' }" class="sidebar-sublink" :class="$route.path === '/citas/agendar' ? 'active-sublink' : ''">Agendar Nueva Cita</router-link>
          </div>
        </div>
        <!-- Pacientes -->
        <div class="sidebar-group">
          <div :class="['sidebar-link sidebar-link-group', activeGroup==='pacientes' ? 'active-menu' : '']" @click="toggleMenu('pacientes')">
            <i class='bx bx-user'></i>
            <span class="sidebar-title">Pacientes</span>
            <i :class="['bx', openMenu==='pacientes' ? 'bx-chevron-up' : 'bx-chevron-down', 'chevron']"></i>
          </div>
          <div v-if="openMenu==='pacientes'" class="sidebar-submenu">
            <router-link :to="{ path: '/citas/ver-pacientes' }" class="sidebar-sublink" :class="$route.path === '/citas/ver-pacientes' ? 'active-sublink' : ''">Ver Pacientes</router-link>
            <router-link v-if="usuarioGuardado.rol==='dentista'" :to="{ path: '/citas/editar-pacientes' }" class="sidebar-sublink" :class="$route.path === '/citas/editar-pacientes' ? 'active-sublink' : ''">Editar Pacientes</router-link>
            <router-link v-if="usuarioGuardado.rol==='recepcionista'" :to="{ path: '/citas/crear-paciente' }" class="sidebar-sublink" :class="$route.path === '/citas/crear-paciente' ? 'active-sublink' : ''">Registrar Paciente</router-link>
            <router-link v-if="usuarioGuardado.rol==='recepcionista'" :to="{ path: '/citas/editar-paciente' }" class="sidebar-sublink" :class="$route.path === '/citas/editar-paciente' ? 'active-sublink' : ''">Editar Paciente</router-link>
          </div>
        </div>
        <!-- Tratamientos (solo dentista) -->
        <div v-if="usuarioGuardado.rol==='dentista'" class="sidebar-group">
          <div :class="['sidebar-link sidebar-link-group', activeGroup==='tratamientos' ? 'active-menu' : '']" @click="toggleMenu('tratamientos')">
            <i class='bx bx-clipboard'></i>
            <span class="sidebar-title">Tratamientos</span>
            <i :class="['bx', openMenu==='tratamientos' ? 'bx-chevron-up' : 'bx-chevron-down', 'chevron']"></i>
          </div>
          <div v-if="openMenu==='tratamientos'" class="sidebar-submenu">
            <router-link to="#" class="sidebar-sublink">Registrar Tratamiento y observaciones</router-link>
            <router-link to="#" class="sidebar-sublink">Ver Tratamientos y observaciones</router-link>
          </div>
        </div>
        <!-- Pagos -->
        <div class="sidebar-group">
          <div :class="['sidebar-link sidebar-link-group', activeGroup==='pagos' ? 'active-menu' : '']" @click="toggleMenu('pagos')">
            <i class='bx bx-dollar-circle'></i>
            <span class="sidebar-title">Pagos</span>
            <i :class="['bx', openMenu==='pagos' ? 'bx-chevron-up' : 'bx-chevron-down', 'chevron']"></i>
          </div>
          <div v-if="openMenu==='pagos'" class="sidebar-submenu">
            <router-link to="#" class="sidebar-sublink">Registrar pago</router-link>
            <router-link to="#" class="sidebar-sublink">Ver Pagos</router-link>
            <router-link to="#" class="sidebar-sublink">Registrar Pago De Cuota</router-link>
          </div>
        </div>
        <!-- Usuarios (solo dentista) -->
        <div v-if="usuarioGuardado.rol==='dentista'" class="sidebar-group">
          <div :class="['sidebar-link sidebar-link-group', activeGroup==='usuarios' ? 'active-menu' : '']" @click="toggleMenu('usuarios')">
            <i class='bx bx-user-circle'></i>
            <span class="sidebar-title">Usuarios</span>
            <i :class="['bx', openMenu==='usuarios' ? 'bx-chevron-up' : 'bx-chevron-down', 'chevron']"></i>
          </div>
          <div v-if="openMenu==='usuarios'" class="sidebar-submenu">
            <router-link to="#" class="sidebar-sublink">Ver Usuarios</router-link>
            <router-link to="#" class="sidebar-sublink">Editar Usuarios</router-link>
            <router-link to="#" class="sidebar-sublink">Crear Usuarios</router-link>
          </div>
        </div>
        <!-- Mensajes (solo recepcionista) -->
        <div v-if="usuarioGuardado.rol==='recepcionista'" class="sidebar-group">
          <router-link to="#" class="sidebar-link">
            <i class='bx bx-message'></i> <span>Mensajes</span>
          </router-link>
        </div>
      </nav>
      <button class="logout-btn" @click="cerrarSesion">
        <i class='bx bx-door-open' style="color:#d32f2f; font-size:1.5rem; margin-right:8px;"></i>
        <span style="color:#d32f2f; font-weight:bold; font-size:1.1rem;">Cerrar Sesión</span>
      </button>
    </aside>
    <main class="dashboard-main">
      <router-view v-slot="{ Component }">
        <component :is="Component" />
      </router-view>
      <template v-if="$route.path === '/citas'">
        <Citas v-if="moduloActivo==='citas'" />
        <PacienteVer v-if="moduloActivo==='verPacientes'" />
        <PacienteCrear v-if="moduloActivo==='crearPaciente'" />
        <PacienteEditar v-if="moduloActivo==='editarPacientes'" />
      </template>
    </main>
    <transition name="fade-in">
      <div v-if="cargando" class="loader-overlay">
        <div class="loader-spinner"></div>
      </div>
    </transition>
  </div>
</template>

<script>
import Citas from './dashboard/Citas.vue';
import PacienteVer from './dashboard/PacienteVer.vue';
import PacienteCrear from './dashboard/PacienteCrear.vue';
import PacienteEditar from './dashboard/PacienteEditar.vue';
export default {
  components: { Citas, PacienteVer, PacienteCrear, PacienteEditar },
  data() {
    return {
      cargando: false,
      openMenu: null,
      moduloActivo: 'citas', // Por defecto, mostrar Citas
      usuarioGuardado: JSON.parse(localStorage.getItem('usuario') || '{}')
    };
  },
  mounted() {
    this.syncMenuWithRoute();
  },
  watch: {
    '$route.path': function() {
      this.syncMenuWithRoute();
    }
  },
  computed: {
    activeGroup() {
      const path = this.$route.path;
      if (path.startsWith('/citas') && !(path.startsWith('/citas/ver-pacientes') || path.startsWith('/citas/editar-pacientes') || path.startsWith('/citas/crear-paciente') || path.startsWith('/citas/editar-paciente'))) {
        return 'citas';
      } else if (
        path.startsWith('/citas/ver-pacientes') ||
        path.startsWith('/citas/editar-pacientes') ||
        path.startsWith('/citas/crear-paciente') ||
        path.startsWith('/citas/editar-paciente')
      ) {
        return 'pacientes';
      } else if (
        path.startsWith('/tratamientos') ||
        path.includes('tratamiento')
      ) {
        return 'tratamientos';
      } else if (
        path.startsWith('/pagos') ||
        path.includes('pago')
      ) {
        return 'pagos';
      } else if (
        path.startsWith('/usuarios') ||
        path.includes('usuario')
      ) {
        return 'usuarios';
      } else if (
        path.startsWith('/mensajes') ||
        path.includes('mensaje')
      ) {
        return 'mensajes';
      } else {
        return null;
      }
    }
  },
  methods: {
    cerrarSesion() {
      this.cargando = true;
      setTimeout(() => {
        localStorage.removeItem('usuario');
        window.location.reload();
      }, 1000);
    },
    toggleMenu(menu) {
      this.openMenu = this.openMenu === menu ? null : menu;
    },
    setModulo(mod) {
      this.moduloActivo = mod;
      if (["verPacientes", "crearPaciente", "editarPacientes"].includes(mod)) {
        this.openMenu = "pacientes";
      } else if (["tratamientos", "placas", "pagos", "mensajes"].includes(mod)) {
        this.openMenu = null;
      } else {
        this.openMenu = null;
      }
    },
    irACitas() {
      window.location.href = '/citas';
    },
    syncMenuWithRoute() {
      // Obtiene el grupo de menú según la ruta actual
      const path = this.$route.path;
      if (path.startsWith('/citas')) {
        if (path === '/citas/calendario' && this.usuarioGuardado.rol === 'dentista') {
          this.openMenu = null;
        } else {
          this.openMenu = 'citas';
        }
      } else if (
        path.startsWith('/citas/ver-pacientes') ||
        path.startsWith('/citas/editar-pacientes') ||
        path.startsWith('/citas/crear-paciente') ||
        path.startsWith('/citas/editar-paciente')
      ) {
        this.openMenu = 'pacientes';
      } else if (
        path.startsWith('/tratamientos') ||
        path.includes('tratamiento')
      ) {
        this.openMenu = 'tratamientos';
      } else if (
        path.startsWith('/pagos') ||
        path.includes('pago')
      ) {
        this.openMenu = 'pagos';
      } else if (
        path.startsWith('/usuarios') ||
        path.includes('usuario')
      ) {
        this.openMenu = 'usuarios';
      } else if (
        path.startsWith('/mensajes') ||
        path.includes('mensaje')
      ) {
        this.openMenu = 'mensajes';
      } else {
        this.openMenu = null;
      }
    },
  }
};
</script>

<style scoped>
.dashboard-container {
  display: flex;
  min-height: 100vh;
  background: linear-gradient(90deg, #f6f6f6 60%, #f3eaff 100%);
}
.sidebar {
  width: 270px;
  background: #fff;
  border-right: 1px solid #ececec;
  box-shadow: 2px 0 12px rgba(162,89,255,0.07);
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  position: relative;
}
.user-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 32px 0 16px 0;
}
.avatar-circle {
  width: 90px;
  height: 90px;
  background: #fff; /* Fondo blanco, sin morado */
  border: 2.5px solid #ece7fa; /* Borde sutil morado claro */
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  margin-bottom: 10px;
  box-shadow: 0 2px 8px rgba(162,89,255,0.04);
}
.user-name {
  font-weight: bold;
  font-size: 1.2rem;
  color: #3a3a3a;
  margin-bottom: 2px;
}
.user-role {
  font-size: 1rem;
  color: #a259ff;
  margin-bottom: 8px;
}
.sidebar-group {
  margin-bottom: 18px;
}
.sidebar-link-group {
  font-size: 1.2rem;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px 24px 8px 24px;
  cursor: pointer;
  border-radius: 12px;
  transition: background 0.2s, border 0.2s;
  border-left: 4px solid transparent;
  background: #fff;
}
.active-menu {
  background: #fff; /* Sin fondo morado */
  color: #a259ff;
  border-left: 4px solid #a259ff; /* Borde morado sutil */
  box-shadow: 0 2px 8px rgba(162,89,255,0.08);
}
.sidebar-title {
  flex: 1;
}
.sidebar-sublink {
  font-size: 1.08rem;
  padding: 12px 32px;
  border-radius: 8px;
  margin-bottom: 6px;
  transition: background 0.2s, color 0.2s;
  display: block;
  color: #3a3a3a;
  text-decoration: none;
  background: #fff;
}
.sidebar-sublink.active-sublink, .sidebar-sublink:hover {
  background: #ece7fa;
  color: #a259ff;
}
.sidebar-link-group i, .sidebar-link i {
  color: #a259ff;
}
.logout-btn {
  position: fixed;
  bottom: 5px;
  left: 0;
  width: 270px;
  background: #ffeded;
  color: #d32f2f;
  font-weight: bold;
  border-radius: 8px;
  padding: 12px 0;
  box-shadow: 0 2px 8px rgba(211,47,47,0.08);
  cursor: pointer;
  transition: box-shadow 0.2s;
  z-index: 10;
}
.logout-btn:hover, .logout-btn:active {
  box-shadow: 0 4px 16px rgba(211,47,47,0.18);
}
.dashboard-main {
  flex: 1;
  background: #f6f6f6;
  border-radius: 0;
  margin: 0;
  box-shadow: none;
  padding: 24px;
}
.loader-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(255,255,255,0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}
.loader-spinner {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #6b4eff;
  border-radius: 50%;
  width: 70px;
  height: 70px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
