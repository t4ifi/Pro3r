<template>
  <div>
    <transition name="fade-zoom">
      <div v-if="!loggedIn && !cargando" class="login-bg">
        <div class="login">
          <div class="login__content">
            <div class="login__img"></div>
            <div class="login__forms">
              <form @submit.prevent="login">
                <h1 class="login__title">Iniciar Sesión</h1>
                <div class="login__box">
                  <i class='bx bx-user login__icon'></i>
                  <input type="text" v-model="usuario" placeholder="Nombre de usuario" class="login__input" />
                </div>
                <div class="login__box">
                  <i class='bx bx-lock-alt login__icon'></i>
                  <input type="password" v-model="password" placeholder="Contraseña" class="login__input" />
                </div>
                <button type="submit" class="login__button">Entrar</button>
                <div v-if="error" class="error-message">{{ error }}</div>
                <div v-if="success" class="success-message success-message-small">{{ success }}</div>
              </form>
              <footer>© 2025 NullDevs. Todos los derechos reservados.</footer>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade-in">
      <div v-if="cargando || mostrandoLoader" class="loader-overlay">
        <div class="loader-spinner"></div>
      </div>
    </transition>
    <!-- El dashboard solo se muestra por router-view tras la redirección -->
  </div>
</template>

<script>
import axios from 'axios';
import Dashboard from './Dashboard.vue';
export default {
  components: { Dashboard },
  data() {
    return {
      usuario: '',
      password: '',
      error: '',
      success: '',
      loggedIn: false,
      usuarioGuardado: null,
      cargando: false,
      mostrandoLoader: false
    };
  },
  mounted() {
    if (localStorage.getItem('usuario')) {
      this.usuarioGuardado = JSON.parse(localStorage.getItem('usuario'));
      this.loggedIn = true;
    }
  },
  methods: {
    async login() {
      this.error = '';
      this.success = '';
      if (!this.usuario || !this.password) {
        this.error = 'Por favor, completá todos los campos.';
        return;
      }
      this.cargando = true;
      try {
        const response = await axios.post('/api/login', {
          usuario: this.usuario,
          password: this.password
        });
        localStorage.setItem('usuario', JSON.stringify(response.data));
        this.usuarioGuardado = response.data;
        this.success = response.data.message || 'Inicio de sesión exitoso.';
        this.loggedIn = true;
        this.cargando = false;
        this.mostrandoLoader = true;
        setTimeout(() => {
          if (this.usuarioGuardado.rol === 'dentista') {
            this.$router.push('/panel-dentista');
          } else {
            this.$router.push('/panel-recepcionista');
          }
        }, 700); // Espera 0.7 segundos antes de redirigir
      } catch (err) {
        this.error = err.response?.data?.message || 'Error al iniciar sesión.';
        this.cargando = false;
      }
    }
  }
};
</script>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
* { box-sizing: border-box; }
.login-bg {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f7f7f7;
}
.login {
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-wrap: wrap;
  overflow: hidden;
  width: 100%;
  max-width: 900px;
}
.login__content {
  display: flex;
  flex-wrap: wrap;
  width: 100%;
}
.login__img {
  flex: 1 1 300px;
  background-color: #a259ff;
  background-image: url('/LogoApp-Photoroom.png');
  background-repeat: no-repeat;
  background-position: center -20%;
  background-size: 120%;
  min-height: 400px;
  display: none;
}
@media (min-width: 768px) {
  .login__img {
    display: block;
  }
}
.login__forms {
  flex: 1 1 300px;
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background-color: #ffffff;
}
.login__title {
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #6b4eff;
  text-align: center;
}
.login__box {
  position: relative;
  margin-bottom: 1.5rem;
}
.login__icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #6b4eff;
  font-size: 1.2rem;
}
.login__input {
  width: 100%;
  padding: 12px 12px 12px 45px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #fff;
  color: #333;
  font-size: 1rem;
  transition: border-color 0.3s;
}
.login__input::placeholder {
  color: #888;
}
.login__input:focus {
  outline: none;
  border-color: #6b4eff;
}
.login__button {
  display: block;
  width: 100%;
  padding: 12px;
  background-color: #6b4eff;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  font-weight: bold;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s;
  border: none;
}
.login__button:hover {
  background-color: #573dd1;
}
.login__button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(162, 89, 255, 0.3);
}
.error-message {
  margin-top: 15px;
  text-align: center;
  color: #d33;
  font-size: 0.95rem;
}
.success-message {
  margin-top: 15px;
  text-align: center;
  color: #28a745;
  font-size: 0.95rem;
  min-height: 1.2em;
  display: block;
}
.success-message-small {
  color: #22c55e;
  font-size: 0.95rem;
  margin-top: 0.5rem;
  text-align: center;
  font-weight: 600;
}
footer {
  margin-top: 20px;
  text-align: center;
  color: #888;
  font-size: 0.85rem;
}
@media (max-width: 480px) {
  .login__forms {
    padding: 30px 20px;
  }
}
.fade-zoom-enter-active, .fade-zoom-leave-active {
  transition: opacity 0.7s, transform 0.7s;
}
.fade-zoom-enter, .fade-zoom-leave-to, .fade-out {
  opacity: 0;
  transform: scale(0.95);
}
.fade-in-enter-active {
  transition: opacity 1s;
}
.fade-in-enter {
  opacity: 0;
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
.bienvenida-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 60vh;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 0 20px rgba(0,0,0,0.08);
  margin: 40px auto;
  max-width: 400px;
  padding: 40px 30px;
}
.bienvenida-titulo {
  color: #28a745;
  font-size: 2rem;
  margin-bottom: 1rem;
}
.bienvenida-texto {
  color: #333;
  font-size: 1.1rem;
  text-align: center;
}
</style>