# DentalSync - Carpeta de Proyecto

---

## 1. Carátula
- **Nombre del Proyecto:** DentalSync
- **Nombre del Equipo:** NullDevs
- **Integrantes del Equipo:**
  - Fernández, Ana
  - García, Juan
  - López, María
  - Pérez, Carlos
- **Institución, Curso, Grado y Grupo:** Escuela Técnica Superior de Melo, EMT Informática - 3°
- **Fecha:** Septiembre 2025

---

## 2. Índice
1. Carátula
2. Índice
3. Introducción
4. Desarrollo del Proyecto
5. Conclusiones
6. Anexos
7. Ejecutables

---

## 3. Introducción
### a. Sumario
DentalSync es una aplicación web integral para la gestión de clínicas odontológicas. Permite registrar pacientes, gestionar citas, administrar pagos y tratamientos, y mantener la seguridad de los datos. El objetivo es digitalizar y optimizar los procesos administrativos y clínicos, facilitando el trabajo diario y mejorando la experiencia tanto de profesionales como de pacientes.

### b. El Equipo
El equipo NullDevs está conformado por cuatro estudiantes, cada uno con roles definidos: desarrollo backend, frontend, documentación y testing. El trabajo colaborativo permitió abordar el proyecto de manera integral y eficiente.

### c. Objetivo General
Desarrollar una aplicación web segura y robusta que permita a clínicas odontológicas gestionar de manera eficiente la información de pacientes, citas, pagos y tratamientos, garantizando la integridad y confidencialidad de los datos, y facilitando la experiencia de uso tanto para el personal administrativo como para los profesionales de la salud.

### d. Objetivos Específicos
- Implementar un sistema de autenticación y gestión de sesiones seguro.
- Permitir el registro, edición y consulta de pacientes y sus tratamientos.
- Gestionar citas y pagos de manera eficiente y trazable.
- Garantizar la protección de los datos mediante buenas prácticas de seguridad.
- Facilitar la administración mediante una interfaz intuitiva.
- Documentar y testear exhaustivamente todas las funcionalidades.

---

## 4. Desarrollo del Proyecto
### a. Análisis del Problema o Realidad
En muchas clínicas odontológicas, la gestión de pacientes y pagos se realiza en papel o con sistemas poco seguros, lo que genera riesgos de pérdida de información, errores administrativos y dificultades para el seguimiento de tratamientos. Se detectó la necesidad de una solución digital segura, centralizada y fácil de usar.

### b. Planteo de la Solución
DentalSync digitaliza la gestión clínica, permitiendo:
- Registro y edición de pacientes
- Gestión de citas y agenda
- Administración de pagos y cuotas
- Registro y seguimiento de tratamientos
- Seguridad avanzada: autenticación, sesiones, logs y protección de datos
- Acceso multiusuario con roles diferenciados

### c. Desarrollo de la Solución
#### i. Planificación
- Se utilizó un diagrama de Gantt para planificar las etapas: análisis, diseño, desarrollo, pruebas y documentación.
- El diagrama PERT ayudó a identificar tareas críticas y dependencias.

#### ii. Programación y Diseño Web
- **Lenguajes/Frameworks:** PHP (Laravel), JavaScript (Vite), HTML5, CSS3
- **Librerías:** Laravel Breeze, Vite, Bootstrap
- **Algoritmos destacados:**
  - Middleware de autenticación y rate limiting
  - Algoritmo de expiración de sesión por inactividad
  - Validación robusta de formularios y datos

#### iii. Bases de Datos
- **Gestor:** MySQL
- **Diseño:**
  - MER y esquema relacional (ver anexos)
  - Restricciones: claves primarias, foráneas, unicidad, no nulos
- **Scripts SQL:**
  - DDL y DML incluidos en anexos

#### iv. Sistemas Operativos
- **Servidor:** Linux (Ubuntu Server)
- **Instalación y configuración:**
  - Instalación de Apache, PHP, MySQL
  - Configuración de virtual hosts y permisos
- **Scripts de mantenimiento:**
  - Backups automáticos (ver scripts en anexos)

#### v. Gestión de Proyectos
- **Metodología:** Design Thinking + Scrum
- **Licencia:** MIT
- **Casos de uso:** Registro de pacientes, gestión de pagos, agenda de citas, administración de usuarios
- **Usabilidad:** Interfaz clara, validaciones en tiempo real, mensajes de error amigables
- **Testeo:** Pruebas unitarias y funcionales con PHPUnit y testeo manual

#### vi. Otras asignaturas y requerimientos
- Documentación técnica y manuales según requerimientos de la institución

### d. Manuales
- **Manual de Usuario:**
  - Ingreso al sistema, registro de pacientes, gestión de citas y pagos, cierre de sesión
- **Manual Técnico:**
  - Requisitos: PHP 8+, MySQL 8+, Composer, Node.js
  - Instalación: clonar repositorio, instalar dependencias, configurar .env, migrar base de datos
  - Ejecución: `php artisan serve`, `npm run dev`

---

## 5. Conclusiones
- **Conclusión Grupal:**
  - El desarrollo de DentalSync permitió al equipo aplicar conocimientos técnicos y metodológicos, logrando una solución robusta y segura para la gestión clínica.
- **Conclusiones Individuales:**
  - (Aquí cada integrante debe agregar su reflexión personal)

---

## 6. Anexos
- **Bitácora:** Registro de avances y reuniones
- **Documentos del desarrollo:** Bocetos, diagramas, esquemas de base de datos
- **Código fuente:** [Repositorio GitHub](https://github.com/t4ifi/Pro3r) (o QR)
- **Scripts SQL y de servidor:** Incluidos en la carpeta `/database` y `/scripts`

---

## 7. Ejecutables
- Archivos ejecutables y scripts de instalación disponibles en la carpeta `/scripts` y `/public`

---

> **Nota:** Este documento es una guía base y debe ser completado con los datos y archivos específicos de tu equipo y proyecto.
