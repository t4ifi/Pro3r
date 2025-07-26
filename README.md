# 🦷 DentalSYNC2 - Sistema de Gestión Dental

## 📋 **Descripción**
Sistema completo de gestión dental desarrollado con **Laravel 12** y **Vue.js 3** que permite administrar pacientes, citas, tratamientos y personal dental.

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

Accede a ellas desde VS Code: `Ctrl+Shift+P` → `Tasks: Run Task`

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
