# Bitácora Semanal Detallada - Proyecto DentalSync

---

## Semana 1 (6 al 12 de mayo 2025)
- **Reunión inicial:** Presentación de integrantes, definición de roles (Ana: backend, Juan: frontend, María: documentación, Carlos: testing y soporte).
- **Lluvia de ideas:** Se discuten posibles módulos: pacientes, pagos, agenda, reportes, seguridad.
- **Bocetos en papel:** Primeros wireframes de la pantalla de login y dashboard.
- **Problema:** Dificultad para definir el alcance mínimo viable (MVP). Debate sobre incluir pagos en la primera versión.
- **Solución:** Se realiza una votación y se decide priorizar pacientes y agenda, dejando pagos para la segunda iteración.
- **Tarea:** Cada miembro investiga sistemas similares y trae ejemplos para la próxima reunión.

## Semana 2 (13 al 19 de mayo 2025)
- **Investigación:** Se analizan 4 sistemas de gestión odontológica. Se detectan problemas comunes: interfaces poco intuitivas, falta de logs y seguridad débil.
- **Entrevistas a usuarios:** Se realizan 3 entrevistas a odontólogos y 2 a administrativos. Se recopilan frases clave y necesidades.
- **Mapa de Empatía:**
  - *Piensa y siente:* "Me preocupa la seguridad de los datos de mis pacientes."
  - *Ve:* "Muchos papeles y planillas, sistemas viejos."
  - *Dice y hace:* "Quiero digitalizar, pero no perder el control."
  - *Oye:* "Colegas recomiendan digitalizar, pero advierten sobre la complejidad."
  - *Duele:* "Errores en cobros, pérdida de información, olvidos de citas."
  - *Gana:* "Más tiempo para pacientes, menos errores, mejor imagen profesional."
- **Problema:** Usuarios temen perder datos y no poder usar el sistema por falta de capacitación.
- **Solución:** Se decide incluir un manual de usuario y un sistema de backups automáticos.

## Semana 3 (20 al 26 de mayo 2025)
- **Requerimientos:** Se redactan 12 requerimientos funcionales y 6 no funcionales. Se priorizan por impacto y dificultad.
- **Presentación de anteproyecto:** El docente sugiere agregar módulo de pagos y reportes.
- **Problema:** Dudas sobre la integración de pagos online y la seguridad de los datos.
- **Solución:** Se investiga sobre APIs de pago y se consulta a un experto en seguridad.
- **Tarea:** Juan y Ana investigan Stripe y MercadoPago; Carlos revisa OWASP Top 10.

## Semana 4 (27 de mayo al 2 de junio 2025)
- **Análisis de procesos:** Se documentan los flujos actuales en clínicas reales. Se identifican puntos críticos: registro manual, errores en cobros, duplicidad de datos.
- **Diagramas:** Se crean diagramas de casos de uso y flujo de datos en draw.io.
- **Problema:** Dificultad para modelar el flujo de pagos y su relación con los tratamientos.
- **Solución:** Se realiza una reunión extra con una administrativa de clínica para clarificar procesos.

## Semana 5 (3 al 9 de junio 2025)
- **Modelo Entidad-Relación (MER):** Se diseña el MER en MySQL Workbench. Se definen entidades: Paciente, Cita, Pago, Tratamiento, Usuario.
- **Bocetos digitales:** María realiza wireframes en Figma. Se revisan en grupo y se ajustan colores y tipografías.
- **Problema:** Desacuerdo sobre la estructura de la base de datos (relación entre pagos y tratamientos).
- **Solución:** Se hace una votación y se documenta la decisión.

## Semana 6 (10 al 16 de junio 2025)
- **Planificación:** Se elaboran diagramas de Gantt y PERT. Se definen entregas parciales y milestones.
- **Trello:** Se crea un tablero para asignar tareas y hacer seguimiento.
- **Problema:** Sobrecarga de tareas en Ana (backend).
- **Solución:** Juan asume parte del backend y Ana ayuda en frontend.

## Semana 7 (17 al 23 de junio 2025)
- **Entorno de desarrollo:** Instalación de Laravel, MySQL, Vite, Node.js. Configuración de GitHub y reglas de commits.
- **Primer commit:** Se sube la estructura base del proyecto.
- **Problema:** Errores de dependencias en Composer y Node.
- **Solución:** Se actualizan versiones y se documentan los pasos de instalación.

## Semana 8 (24 al 30 de junio 2025)
- **Modelos y migraciones:** Se crean modelos Eloquent y migraciones para todas las entidades.
- **Autenticación básica:** Se implementa Laravel Breeze para login y registro.
- **Problema:** Error 500 al registrar usuarios (campo nulo en la tabla usuarios).
- **Solución:** Se corrige la migración y se agrega validación en el formulario.

## Semana 9 (1 al 7 de julio 2025)
- **Controladores y rutas:** Se desarrollan controladores REST y rutas protegidas.
- **Validaciones:** Se agregan reglas de validación en backend y mensajes personalizados.
- **Problema:** Las validaciones no se reflejan en el frontend.
- **Solución:** Juan implementa manejo de errores y mensajes en la interfaz.

## Semana 10 (8 al 14 de julio 2025)
- **Vistas y frontend:** Se crean vistas con Vite y Bootstrap. Se integran formularios de registro y login.
- **Problema:** Conflicto de estilos entre Bootstrap y CSS propio.
- **Solución:** Refactorización de clases y uso de variables CSS personalizadas.

## Semana 11 (15 al 21 de julio 2025)
- **Gestión de pacientes y tratamientos:** Se implementan CRUD completos y relaciones.
- **Pruebas unitarias:** Carlos desarrolla tests para modelos y controladores.
- **Problema:** Error en relaciones Eloquent (belongsTo mal definido).
- **Solución:** Se corrigen claves foráneas y se agregan factories para pruebas.

## Semana 12 (22 al 28 de julio 2025)
- **Módulo de pagos y agenda:** Se desarrollan controladores y vistas para pagos y citas.
- **Pruebas funcionales:** Se testean flujos completos de registro, pago y agendamiento.
- **Problema:** Solapamiento de citas en la agenda.
- **Solución:** Se implementa validación de disponibilidad antes de guardar.

## Semana 13 (29 de julio al 4 de agosto 2025)
- **Middlewares de seguridad:** Se agregan CSRF, rate limiting y logs de auditoría.
- **Pruebas de login/logout:** Se testean sesiones y expiración por inactividad.
- **Problema:** Las sesiones no persistían correctamente en producción.
- **Solución:** Ajuste en configuración de cookies y almacenamiento de sesiones.

## Semana 14 (5 al 11 de agosto 2025)
- **Documentación técnica:** María documenta endpoints y flujos de autenticación.
- **Logs:** Se revisan logs y se ajustan niveles para evitar saturación.
- **Problema:** Logs demasiado verbosos y difíciles de leer.
- **Solución:** Se implementa rotación diaria y filtrado por nivel.

## Semana 15 (12 al 18 de agosto 2025)
- **Pruebas de stress:** Carlos ejecuta pruebas con Apache Benchmark y detecta cuellos de botella.
- **Optimización:** Ana agrega índices en campos de búsqueda y paginación en reportes.
- **Problema:** Lentitud en reportes de pagos con muchos registros.
- **Solución:** Se optimizan consultas y se cachean resultados frecuentes.

## Semana 16 (19 al 25 de agosto 2025)
- **Manuales:** Se redactan manuales de usuario y técnico. Se prueba la instalación en entorno limpio.
- **Problema:** Error en migraciones por versión de MySQL diferente.
- **Solución:** Se ajustan scripts y se documentan requisitos mínimos.

## Semana 17 (26 de agosto al 1 de septiembre 2025)
- **Integración final:** Se integran todos los módulos y se realiza prueba beta con usuarios reales.
- **Feedback:** Se reciben sugerencias para mejorar mensajes de error y accesibilidad.
- **Problema:** Formularios no accesibles por teclado.
- **Solución:** Se agregan atributos ARIA y navegación con tabulador.

## Semana 18 (2 al 8 de septiembre 2025)
- **Revisión final:** Se revisa seguridad, usabilidad y documentación.
- **Preparación de anexos:** Se recopilan bocetos, diagramas, scripts SQL y logs.
- **Problema:** Diferencias menores entre entornos de desarrollo y producción (paths, permisos).
- **Solución:** Se crea checklist de configuración y scripts de deploy automatizados.

## Semana 19 (9 al 15 de septiembre 2025)
- **Pruebas de recuperación ante fallos:** Simulación de caídas y restauración desde backups.
- **Problema:** Backup automático no se ejecutaba por permisos.
- **Solución:** Se corrigen permisos y se documenta el proceso.

## Semana 20 (16 al 22 de septiembre 2025)
- **Preparación de presentación:** Ensayo de demo, revisión de slides y manuales.
- **Problema:** Dudas sobre la explicación de la arquitectura.
- **Solución:** Se prepara un diagrama visual y se asignan partes de la exposición a cada miembro.

## Semana 21 (23 al 29 de septiembre 2025)
- **Entrega final:** Se suben ejecutables, documentación y anexos al repositorio.
- **Reunión de cierre:** Reflexión grupal sobre aprendizajes y mejoras para futuros proyectos.

---

> **Nota:** Esta bitácora semanal es simulada y debe ser adaptada con los eventos reales y específicos de tu equipo y proyecto. Incluye detalles técnicos, problemas reales de programación, gestión y soluciones implementadas.
