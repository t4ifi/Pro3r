# 🦷 DentalSYNC2 - Sistema de Gestión Dental

## 📋 **Descripción**
# 🦷 DentalSYNC2 - Sistema de Gestión Dental

## 📋 Descripción

DentalSYNC2 es un sistema integral de gestión para consultorios dentales, desarrollado con **Laravel 12** y **Vue.js 3**. Permite a los dentistas gestionar pacientes, citas, tratamientos y más, con una interfaz moderna y funcionalidades avanzadas.

## ✨ Características Principales

### 🏥 Gestión de Pacientes
- ✅ **Listado completo** de pacientes con información detallada
- ✅ **Edición avanzada** con validaciones robustas y UX moderna
- ✅ **Cálculo automático** de edad y datos derivados
- ✅ **Búsqueda y filtrado** dinámico
- ✅ **Validaciones** frontend y backend

### 📅 Sistema de Citas
- ✅ **Calendario interactivo** para gestión de citas
- ✅ **Estados de citas** (confirmada, pendiente, completada, cancelada)
- ✅ **Filtrado por estado** y búsqueda avanzada
- ✅ **Gestión completa** CRUD de citas

### 🔐 Autenticación y Roles
- ✅ **Sistema de login** seguro
- ✅ **Roles diferenciados** (dentista, asistente, administrador)
- ✅ **Protección de rutas** basada en roles

### 🎨 Interfaz Moderna
- ✅ **Diseño responsivo** con Tailwind CSS
- ✅ **Iconografía** con BoxIcons
- ✅ **Estados de carga** y feedback visual
- ✅ **Animaciones** y transiciones suaves

## 🛠️ Tecnologías

### Backend
- **Laravel 12** - Framework PHP moderno
- **MySQL/MariaDB** - Base de datos relacional
- **API RESTful** - Arquitectura de servicios

### Frontend
- **Vue.js 3** - Framework JavaScript reactivo
- **Composition API** - Patrón moderno de Vue
- **Vue Router** - Enrutamiento del lado del cliente
- **Tailwind CSS** - Framework de utilidades CSS
- **BoxIcons** - Librería de iconos

### Herramientas de Desarrollo
- **Vite** - Build tool y servidor de desarrollo
- **Laravel Artisan** - CLI de Laravel
- **NPM** - Gestor de paquetes

## 🚀 Instalación

### Prerrequisitos
- PHP 8.1 o superior
- Composer
- Node.js 18 o superior
- NPM o Yarn
- MySQL/MariaDB

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [url-del-repositorio]
cd Pro3r
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de JavaScript**
```bash
npm install
```

4. **Configuración del entorno**
```bash
cp .env.example .env
```

Editar `.env` con la configuración de tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dentalsync2
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. **Generar clave de aplicación**
```bash
php artisan key:generate
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
```

7. **Crear datos de prueba**
```bash
php artisan patients:create-test
```

8. **Compilar assets**
```bash
npm run dev
```

9. **Iniciar servidores**

Terminal 1 (Laravel):
```bash
php artisan serve
```

Terminal 2 (Vite):
```bash
npm run dev
```

La aplicación estará disponible en `http://127.0.0.1:8000`

## 📁 Estructura del Proyecto

```
Pro3r/
├── app/
│   ├── Http/Controllers/        # Controladores de la API
│   ├── Models/                  # Modelos Eloquent
│   └── Console/Commands/        # Comandos Artisan personalizados
├── database/
│   ├── migrations/              # Migraciones de base de datos
│   └── seeders/                 # Seeders para datos de prueba
├── resources/
│   ├── js/
│   │   ├── components/          # Componentes Vue.js
│   │   │   └── dashboard/       # Componentes del dashboard
│   │   ├── router.js           # Configuración de rutas
│   │   └── app.js              # Punto de entrada JavaScript
│   ├── css/                    # Estilos CSS
│   └── views/                  # Plantillas Blade
├── routes/
│   ├── api.php                 # Rutas de API
│   └── web.php                 # Rutas web
└── public/                     # Assets públicos
```

## 🎯 Funcionalidades Detalladas

### 📝 Edición de Pacientes

La funcionalidad de edición de pacientes incluye:

- **Selector dinámico**: Lista actualizada de pacientes desde la base de datos
- **Formulario reactivo**: Campos editables con validación en tiempo real
- **Información calculada**: Edad automática, fechas de registro y modificación
- **Validaciones robustas**: Frontend y backend sincronizadas
- **Estados visuales**: Carga, éxito, error con feedback claro
- **Modal de confirmación**: Confirmación visual de operaciones exitosas

#### Campos Editables:
- ✅ Nombre completo (requerido)
- ✅ Teléfono (opcional)
- ✅ Fecha de nacimiento (opcional)
- ✅ Última visita (opcional)

### 📅 Gestión de Citas

- **Dashboard de citas**: Vista general con estadísticas
- **Estados múltiples**: Confirmada, pendiente, completada, cancelada
- **Filtrado avanzado**: Por estado, fecha, paciente
- **Operaciones CRUD**: Crear, leer, actualizar, eliminar citas

## 🗄️ Base de Datos

### Tablas Principales

#### `pacientes`
- `id` - Identificador único
- `nombre_completo` - Nombre del paciente
- `telefono` - Número de contacto
- `fecha_nacimiento` - Fecha de nacimiento
- `ultima_visita` - Fecha de última consulta
- `created_at`, `updated_at` - Timestamps

#### `citas`
- `id` - Identificador único
- `paciente_id` - Referencia al paciente
- `fecha_hora` - Fecha y hora de la cita
- `estado` - Estado de la cita
- `motivo` - Motivo de la consulta
- `created_at`, `updated_at` - Timestamps

## 🔧 Comandos Artisan Personalizados

### Crear Pacientes de Prueba
```bash
php artisan patients:create-test
```
Crea 5 pacientes de prueba con información realista para desarrollo y testing.

## 📡 API Endpoints

### Pacientes
```http
GET    /api/pacientes           # Listar todos los pacientes
GET    /api/pacientes/{id}      # Obtener paciente específico
POST   /api/pacientes           # Crear nuevo paciente
PUT    /api/pacientes/{id}      # Actualizar paciente
DELETE /api/pacientes/{id}      # Eliminar paciente
```

### Citas
```http
GET    /api/citas               # Listar todas las citas
POST   /api/citas               # Crear nueva cita
PUT    /api/citas/{id}          # Actualizar cita
DELETE /api/citas/{id}          # Eliminar cita
```

### Autenticación
```http
POST   /api/login               # Iniciar sesión
```

## 🎨 Tema y Diseño

### Paleta de Colores
- **Principal**: `#a259ff` (Morado vibrante)
- **Secundario**: `#7c3aed` (Morado oscuro)
- **Éxito**: `#22c55e` (Verde)
- **Error**: `#ef4444` (Rojo)
- **Información**: `#3b82f6` (Azul)

### Principios de Diseño
- **Responsivo**: Funciona en dispositivos móviles, tablets y desktop
- **Moderno**: Bordes redondeados, sombras suaves, gradientes
- **Accesible**: Contrastes adecuados, navegación por teclado
- **Intuitivo**: Iconografía clara, feedback visual inmediato

## 🧪 Testing

### Datos de Prueba
- 9 pacientes con información realista
- Diferentes rangos de edad y fechas
- Números de teléfono y fechas de visita variados

### Casos de Prueba Manual
- [ ] Autenticación de usuarios
- [ ] Carga y navegación del dashboard
- [ ] CRUD completo de pacientes
- [ ] CRUD completo de citas
- [ ] Filtrado y búsqueda
- [ ] Responsividad en diferentes dispositivos

## 🐛 Solución de Problemas Comunes

### Error 404 en API
- Verificar que el servidor Laravel esté ejecutándose
- Verificar rutas con `php artisan route:list`
- Confirmar que existen datos en la base de datos

### Compilación de Assets
```bash
# Limpiar cache de Vite
npm run build
rm -rf node_modules/.vite

# Reinstalar dependencias
npm install
npm run dev
```

### Problemas de Base de Datos
```bash
# Refrescar migraciones
php artisan migrate:fresh

# Crear datos de prueba
php artisan patients:create-test
```

## 📈 Roadmap

### Próximas Funcionalidades
- [ ] Sistema de tratamientos y procedimientos
- [ ] Gestión de pagos y facturación
- [ ] Historial clínico completo
- [ ] Sistema de recordatorios automáticos
- [ ] Reportes y estadísticas avanzadas
- [ ] Integración con sistemas de imagen dental
- [ ] Aplicación móvil nativa

### Mejoras Técnicas
- [ ] Tests automatizados (PHPUnit, Jest)
- [ ] Cache de datos con Redis
- [ ] API rate limiting
- [ ] Logs estructurados
- [ ] Monitoreo de performance
- [ ] CI/CD pipeline

## 🤝 Contribución

1. Fork el proyecto
2. Crear branch de feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit los cambios (`git commit -m 'Agregar nueva funcionalidad'`)
4. Push al branch (`git push origin feature/nueva-funcionalidad`)
5. Abrir Pull Request

### Estándares de Código
- **PHP**: PSR-12
- **JavaScript**: ESLint + Prettier
- **Vue**: Vue 3 Composition API Style Guide
- **CSS**: Tailwind CSS utilities preferred

## 📄 Documentación Adicional

- [`DOCUMENTACION_EDITAR_PACIENTES.md`](./DOCUMENTACION_EDITAR_PACIENTES.md) - Documentación completa del sistema de edición de pacientes
- [`DOCUMENTACION_TECNICA_PACIENTE_EDITAR.md`](./DOCUMENTACION_TECNICA_PACIENTE_EDITAR.md) - Documentación técnica detallada del componente PacienteEditar.vue

## 📞 Soporte

Para reportar bugs, solicitar funcionalidades o hacer preguntas:
- Crear un issue en este repositorio
- Incluir información detallada del problema
- Proporcionar pasos para reproducir bugs

## 📝 Changelog

### v1.0.0 (2025-07-26)
- ✅ Sistema base de autenticación
- ✅ Dashboard principal con navegación
- ✅ Gestión completa de citas con filtrado
- ✅ Sistema avanzado de edición de pacientes
- ✅ API RESTful completa
- ✅ Interfaz responsiva con Tailwind CSS
- ✅ Comandos Artisan personalizados
- ✅ Documentación completa

---

## 🏆 Créditos

**Desarrollado con ❤️ para la gestión moderna de consultorios dentales**

### Tecnologías Utilizadas
- [Laravel](https://laravel.com/) - Framework PHP
- [Vue.js](https://vuejs.org/) - Framework JavaScript
- [Tailwind CSS](https://tailwindcss.com/) - Framework CSS
- [BoxIcons](https://boxicons.com/) - Librería de iconos
- [Vite](https://vitejs.dev/) - Build tool

**© 2025 DentalSYNC2 - Todos los derechos reservados**

---

## 🚀 **Características Principales**

### 👥 **Gestión de Usuarios**
- Sistema de roles: **Dentista** y **Recepcionista**
- Autenticación segura
- Dashboard personalizado por rol

### 📅 **Gestión de Citas**
- Agendar citas con pacientes existentes o nuevos
- Calendario interactivo
- Estados: Pendiente, Confirmada, Cancelada, Atendida
- Filtrado por fecha

### 🦷 **Gestión de Pacientes**
- Registro completo de pacientes
- Historial de visitas
- Información de contacto
- Creación automática desde citas

### 🔧 **Módulos Adicionales**
- **Placas Dentales**: Gestión de placas y aparatos ortodónticos
- **Tratamientos**: Catálogo de servicios dentales
- **Pagos**: Sistema de facturación y cuotas
- **Historial Clínico**: Registro médico completo

---

## 💻 **Tecnologías Utilizadas**

### Backend
- **Laravel 12** - Framework PHP
- **MySQL/MariaDB** - Base de datos
- **Eloquent ORM** - Mapeo objeto-relacional
- **Laravel Artisan** - CLI de comandos

### Frontend
- **Vue.js 3** - Framework JavaScript reactivo
- **Vue Router** - Enrutamiento SPA
- **Vite** - Bundler y servidor de desarrollo
- **Tailwind CSS** - Framework de estilos

### Dependencias Adicionales
- **BoxIcons** - Librería de iconos
- **Vue-Cal** - Componente de calendario

---

## ⚙️ **Instalación y Configuración**

### Prerrequisitos
- PHP 8.4+ con extensiones: mbstring, openssl, pdo, mysql
- Composer
- Node.js y npm
- MySQL/MariaDB

### 1. Clonar el Repositorio
```bash
git clone https://github.com/t4ifi/Pro3r.git
cd Pro3r
```

### 2. Instalar Dependencias Backend
```bash
composer install
```

### 3. Configurar Base de Datos
```bash
# Copiar archivo de configuración
cp .env.example .env

# Editar .env con credenciales de BD
DB_DATABASE=dentalsync2
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Ejecutar Migraciones
```bash
php artisan key:generate
php artisan migrate
```

### 5. Instalar Dependencias Frontend
```bash
npm install
```

### 6. Ejecutar Servidores
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Servidor Vite
npm run dev
```

### 7. Acceder al Sistema
- **URL**: http://127.0.0.1:8000
- **Usuario Demo**: Ver datos en migraciones

---

## 📁 **Estructura del Proyecto**

```
├── app/
│   ├── Http/Controllers/          # Controladores API
│   │   ├── CitaController.php     # Gestión de citas
│   │   ├── PacienteController.php # Gestión de pacientes
│   │   └── PlacaController.php    # Gestión de placas
│   └── Models/                    # Modelos Eloquent
│       ├── Cita.php
│       ├── Paciente.php
│       └── Usuario.php
├── database/
│   └── migrations/                # Migraciones de BD
├── resources/
│   ├── js/
│   │   ├── components/            # Componentes Vue
│   │   │   └── dashboard/         # Módulos del dashboard
│   │   ├── router.js              # Configuración de rutas
│   │   └── app.js                 # Aplicación principal
│   └── views/
│       └── app.blade.php          # Template principal
├── routes/
│   ├── api.php                    # Rutas API
│   └── web.php                    # Rutas web
└── public/                        # Archivos públicos
```

---

## 🔧 **API Endpoints**

### Autenticación
```http
POST /api/login                    # Iniciar sesión
```

### Citas
```http
GET    /api/citas                  # Listar citas
POST   /api/citas                  # Crear cita
PUT    /api/citas/{id}             # Actualizar cita
DELETE /api/citas/{id}             # Eliminar cita
```

### Pacientes
```http
GET    /api/pacientes              # Listar pacientes
GET    /api/pacientes/{id}         # Obtener paciente
POST   /api/pacientes              # Crear paciente
PUT    /api/pacientes/{id}         # Actualizar paciente
```

### Placas Dentales
```http
GET    /api/placas                 # Listar placas
POST   /api/placas                 # Crear placa
PUT    /api/placas/{id}            # Actualizar placa
DELETE /api/placas/{id}            # Eliminar placa
```

---

## 🧪 **Comandos Útiles**

### Laravel
```bash
# Limpiar caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Verificar migraciones
php artisan migrate:status

# Acceder a consola interactiva
php artisan tinker

# Ver logs en tiempo real
tail -f storage/logs/laravel.log
```

### Base de Datos
```bash
# Crear nueva migración
php artisan make:migration create_table_name

# Ejecutar migraciones
php artisan migrate

# Rollback migraciones
php artisan migrate:rollback
```

### Frontend
```bash
# Desarrollo
npm run dev

# Producción
npm run build

# Linter
npm run lint
```

---

## 🐛 **Resolución de Problemas**

### Error 500 en API
1. Verificar logs: `storage/logs/laravel.log`
2. Limpiar caches: `php artisan config:clear`
3. Verificar extensiones PHP: `php -m`
4. Comprobar conexión BD: `php artisan tinker`

### Frontend no carga
1. Verificar compilación: `npm run dev`
2. Revisar consola del navegador
3. Comprobar rutas en `router.js`

### Base de datos
1. Verificar credenciales en `.env`
2. Comprobar migraciones: `php artisan migrate:status`
3. Revisar relaciones en modelos

---

## 📝 **Contribución**

1. Fork del repositorio
2. Crear rama feature: `git checkout -b feature/nueva-funcionalidad`
3. Commit cambios: `git commit -m 'Add nueva funcionalidad'`
4. Push a la rama: `git push origin feature/nueva-funcionalidad`
5. Crear Pull Request

---

## 📄 **Licencia**

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

---

## 👥 **Autores**

- **Desarrollo Principal**: [@t4ifi](https://github.com/t4ifi)
- **Depuración y Documentación**: GitHub Copilot

---

## 📞 **Soporte**

Para reportar bugs o solicitar funcionalidades:
- **Issues**: [GitHub Issues](https://github.com/t4ifi/Pro3r/issues)
- **Documentación**: Ver `DEBUGGING_LOG.md` para detalles técnicos

---

**🦷 ¡Gracias por usar DentalSYNC2!**
- **Herramientas de desarrollo**: Laravel Sail, Artisan, NPM

## Requisitos del sistema

- PHP 8.4+
- Node.js 18+
- MariaDB 10.6+
- Composer 2.8+

## Instalación

### 1. Clonar e instalar dependencias

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install
```

### 2. Configurar entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 3. Configurar base de datos

Editar el archivo `.env` con los datos de tu MariaDB:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dentalsync2
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Crear base de datos y ejecutar migraciones

```bash
# Crear la base de datos (si no existe)
mysql -u root -p -e "CREATE DATABASE dentalsync2;"

# Ejecutar migraciones
php artisan migrate
```

### 5. Compilar assets

```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

## Comandos de desarrollo

### Servidor Laravel
```bash
php artisan serve
```

### Servidor de desarrollo frontend (con hot reload)
```bash
npm run dev
```

### Compilar assets para producción
```bash
npm run build
```

### Migraciones
```bash
# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Refrescar todas las migraciones
php artisan migrate:fresh
```

## Estructura del proyecto

```
├── app/                 # Lógica de la aplicación Laravel
├── database/           # Migraciones, seeders y factories
├── public/             # Assets públicos
├── resources/
│   ├── css/           # Estilos CSS
│   ├── js/            # JavaScript y Vue components
│   └── views/         # Plantillas Blade
├── routes/            # Definición de rutas
└── vite.config.js     # Configuración de Vite
```

## Rutas principales

- `/` - Página de bienvenida Laravel
- `/app` - Aplicación Vue principal

## Desarrollo

Este proyecto está configurado para usar:

- **Vite** para el bundling y hot reload
- **Tailwind CSS** para estilos
- **Vue 3** con Composition API
- **Laravel 12** con las últimas características

## Tareas de VS Code

El proyecto incluye tareas preconfiguradas de VS Code:

- `Serve Laravel` - Inicia el servidor Laravel
- `Vite Dev Server` - Inicia el servidor de desarrollo con hot reload
- `Build Assets` - Compila assets para producción
- `Run Migrations` - Ejecuta migraciones
- `Fresh Migration` - Refresca todas las migraciones