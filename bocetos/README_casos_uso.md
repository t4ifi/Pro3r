# Casos de Uso del Sistema DentalSync

Este directorio contiene los diagramas de casos de uso del sistema DentalSync desarrollados en PlantUML.

## Archivos Incluidos

### 1. `casos_uso_dentalsync.puml`
Contiene todos los casos de uso detallados del sistema, organizados por módulos:

- **UC-01**: Autenticación - Iniciar Sesión
- **UC-02**: Gestión de Citas
- **UC-03**: Gestión de Pacientes  
- **UC-04**: Gestión de Tratamientos
- **UC-05**: Gestión de Pagos
- **UC-06**: Gestión de Usuarios
- **UC-07**: Búsqueda y Listado de Pacientes
- **UC-08**: Comunicación WhatsApp
- **UC-09**: Dashboard y Reportes
- **UC-10**: Historial Clínico

### 2. `vista_general_casos_uso.puml`
Diagrama general que muestra:
- Todos los actores del sistema (Administrador, Dentista, Recepcionista)
- Casos de uso principales agrupados por módulos
- Relaciones entre actores y casos de uso
- Descripción de roles y responsabilidades

### 3. `dependencias_modulos.puml`
Diagrama de componentes que ilustra:
- Dependencias entre módulos del sistema
- Flujo de información entre componentes
- Módulos centrales vs. módulos especializados

## Actores del Sistema

### 👨‍⚕️ Dentista
- Gestión clínica completa
- Diagnósticos y tratamientos
- Historial clínico
- Comunicación con pacientes
- Reportes especializados

### 👩‍💼 Recepcionista
- Gestión de citas
- Atención al paciente
- Facturación y pagos
- Comunicación básica

### 👨‍💻 Administrador
- Gestión completa del sistema
- Administración de usuarios
- Acceso a todos los reportes
- Configuración del sistema

## Módulos Principales

### 🔐 Autenticación (UC-01)
Módulo base que gestiona el acceso seguro al sistema.

### 📅 Gestión de Citas (UC-02)
Programación, modificación y control de citas dentales.

### 👥 Gestión de Pacientes (UC-03, UC-07)
Administración completa de información de pacientes y funciones de búsqueda.

### 🦷 Gestión de Tratamientos (UC-04)
Creación y seguimiento de planes de tratamiento dental.

### 💰 Gestión de Pagos (UC-05)
Facturación, cobranza y control financiero.

### 👤 Gestión de Usuarios (UC-06)
Administración de usuarios y permisos del sistema.

### 📱 Comunicación WhatsApp (UC-08)
Envío de mensajes, recordatorios y comunicación con pacientes.

### 📊 Dashboard y Reportes (UC-09)
Visualización de estadísticas y generación de reportes.

### 📋 Historial Clínico (UC-10)
Registro y seguimiento de información médica de pacientes.

## Cómo Visualizar los Diagramas

### Opción 1: Visual Studio Code
1. Instalar la extensión "PlantUML"
2. Abrir cualquier archivo `.puml`
3. Usar `Ctrl+Shift+P` y buscar "PlantUML: Preview Current Diagram"

### Opción 2: PlantUML Online
1. Ir a [http://www.plantuml.com/plantuml/uml/](http://www.plantuml.com/plantuml/uml/)
2. Copiar y pegar el contenido del archivo `.puml`
3. Visualizar el diagrama generado

### Opción 3: PlantUML Local
1. Instalar PlantUML localmente
2. Ejecutar: `java -jar plantuml.jar archivo.puml`
3. Se generará una imagen PNG del diagrama

## Convenciones Utilizadas

- **UC-XX**: Identificador único del caso de uso
- **<<include>>**: Relación de inclusión obligatoria
- **<<extend>>**: Relación de extensión condicional
- **Flujo Principal**: Secuencia normal de eventos
- **Precondiciones**: Condiciones que deben cumplirse antes
- **Postcondiciones**: Estado del sistema después de la ejecución

## Notas de Implementación

Estos diagramas sirven como base para:
- Desarrollo de la interfaz de usuario
- Implementación de la lógica de negocio
- Definición de APIs y servicios
- Pruebas funcionales del sistema
- Documentación técnica y de usuario

## Actualizaciones

Los diagramas deben actualizarse cuando:
- Se agreguen nuevas funcionalidades
- Se modifiquen flujos existentes
- Se cambien roles o permisos
- Se integren nuevos módulos

---

**Fecha de creación**: 5 de agosto de 2025  
**Versión**: 1.0  
**Estado**: Desarrollo
