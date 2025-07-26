# DentalSYNC2

Sistema de gestión dental desarrollado con Laravel 12, Vue 3 y MariaDB.

## Tecnologías

- **Backend**: Laravel 12
- **Frontend**: Vue 3 + Vite
- **Base de datos**: MariaDB
- **Estilos**: Tailwind CSS
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
