import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import CitasCalendario from './components/dashboard/CitasCalendario.vue';
import CitasAgendar from './components/dashboard/CitasAgendar.vue';
import PacienteVer from './components/dashboard/PacienteVer.vue';
import PacienteCrear from './components/dashboard/PacienteCrear.vue';

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/citas',
    name: 'Citas',
    component: Dashboard,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'calendario',
        name: 'CitasCalendario',
        component: CitasCalendario,
        meta: { requiresAuth: true }
      },
      {
        path: 'agendar',
        name: 'CitasAgendar',
        component: CitasAgendar,
        meta: { requiresAuth: true }
      },
      {
        path: 'ver-pacientes',
        name: 'PacienteVer',
        component: PacienteVer,
        meta: { requiresAuth: true }
      },
      {
        path: 'crear-paciente',
        name: 'PacienteCrear',
        component: PacienteCrear,
        meta: { requiresAuth: true }
      }
    ]
  },
  {
    path: '/panel-dentista',
    name: 'PanelDentista',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/panel-recepcionista',
    name: 'PanelRecepcionista',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/',
    redirect: '/citas/calendario'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const usuario = localStorage.getItem('usuario');
  if (to.meta.requiresAuth && !usuario) {
    next('/login');
  } else if (to.path === '/login' && usuario) {
    next('/citas/calendario');
  } else if (to.path === '/panel-recepcionista') {
    next('/citas/calendario');
  } else if (to.path === '/panel-dentista') {
    next('/citas/calendario');
  } else {
    next();
  }
});

export default router;
