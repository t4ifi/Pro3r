# Casos de Uso - Sistema DentalSync

## UC-01: Iniciar Sesión

```plantuml
@startuml UC-01

title Módulo de Autenticación - Iniciar Sesión

actor Usuario

rectangle "Sistema DentalSync" {
  
  usecase "UC-01\nIniciar sesión" as UC01
  usecase "UC-01.1\nIngresar\ncredenciales" as UC01_1
  usecase "UC-01.2\nValidar\nusuario" as UC01_2
  usecase "UC-01.3\nAcceder al\nsistema" as UC01_3
  
  usecase "UC-01.E1\nCredenciales\ninválidas" as UC01_E1
  usecase "UC-01.E2\nUsuario\ninexistente" as UC01_E2
  usecase "UC-01.E3\nCampos\nvacíos" as UC01_E3
}

Usuario --> UC01

UC01 --> UC01_1 : <<include>>
UC01 --> UC01_2 : <<include>>
UC01 --> UC01_3 : <<include>>

UC01_1 --> UC01_E3 : <<extend>>
UC01_2 --> UC01_E1 : <<extend>>
UC01_2 --> UC01_E2 : <<extend>>

note right of UC01 : **FLUJO PRINCIPAL:**\n1. Ingresar usuario y contraseña\n2. Validar credenciales\n3. Autenticar usuario\n4. Redirigir al dashboard

note bottom of UC01 : **PRECONDICIONES:**\n• Usuario registrado en sistema\n• Aplicación disponible\n\n**POSTCONDICIONES:**\n• Sesión iniciada\n• Acceso al sistema\n• Usuario autenticado

@enduml
```

## UC-02: Gestionar Dashboard

```plantuml
@startuml UC-02

title Módulo Dashboard - Visualizar Panel de Control

actor Dentista

rectangle "Sistema DentalSync" {
  
  usecase "UC-02\nVisualizar\ndashboard" as UC02
  usecase "UC-02.1\nMostrar\nestadísticas" as UC02_1
  usecase "UC-02.2\nListar próximas\ncitas" as UC02_2
  usecase "UC-02.3\nMostrar actividad\nreciente" as UC02_3
  usecase "UC-02.4\nNavegar a\nmódulos" as UC02_4
  
  usecase "UC-02.E1\nError carga\ndatos" as UC02_E1
}

Dentista --> UC02

UC02 --> UC02_1 : <<include>>
UC02 --> UC02_2 : <<include>>
UC02 --> UC02_3 : <<include>>
UC02 --> UC02_4 : <<include>>

UC02_1 --> UC02_E1 : <<extend>>
UC02_2 --> UC02_E1 : <<extend>>

note right of UC02 : **FLUJO PRINCIPAL:**\n1. Cargar estadísticas generales\n2. Mostrar próximas citas\n3. Listar actividad reciente\n4. Habilitar navegación

note bottom of UC02 : **PRECONDICIONES:**\n• Sesión iniciada como dentista\n• Base de datos disponible\n\n**POSTCONDICIONES:**\n• Dashboard cargado\n• Datos actualizados\n• Navegación habilitada

@enduml
```

## UC-03: Gestionar Citas

```plantuml
@startuml UC-03

title Módulo de Citas - Gestionar Citas

actor Dentista
actor Paciente

rectangle "Sistema DentalSync" {
  
  usecase "UC-03\nGestionar\ncitas" as UC03
  usecase "UC-03.1\nAgendar\nnueva cita" as UC03_1
  usecase "UC-03.2\nModificar\ncita existente" as UC03_2
  usecase "UC-03.3\nCancelar\ncita" as UC03_3
  usecase "UC-03.4\nConsultar\ncalendario" as UC03_4
  usecase "UC-03.5\nBuscar\ncitas" as UC03_5
  
  usecase "UC-03.E1\nHorario no\ndisponible" as UC03_E1
  usecase "UC-03.E2\nPaciente no\nencontrado" as UC03_E2
  usecase "UC-03.E3\nCita ya\npasada" as UC03_E3
}

Dentista --> UC03
Paciente --> UC03_1

UC03 --> UC03_1 : <<include>>
UC03 --> UC03_2 : <<include>>
UC03 --> UC03_3 : <<include>>
UC03 --> UC03_4 : <<include>>
UC03 --> UC03_5 : <<include>>

UC03_1 --> UC03_E1 : <<extend>>
UC03_1 --> UC03_E2 : <<extend>>
UC03_2 --> UC03_E3 : <<extend>>

note right of UC03 : **FLUJO PRINCIPAL:**\n1. Seleccionar paciente\n2. Elegir fecha y hora\n3. Definir tipo tratamiento\n4. Confirmar cita\n5. Enviar notificación

note bottom of UC03 : **PRECONDICIONES:**\n• Sesión iniciada\n• Paciente registrado\n• Horarios disponibles\n\n**POSTCONDICIONES:**\n• Cita agendada\n• Calendario actualizado\n• Notificación enviada

@enduml
```

## UC-04: Gestionar Pacientes

```plantuml
@startuml UC-04

title Módulo de Pacientes - Gestionar Información de Pacientes

actor Dentista
actor Recepcionista

rectangle "Sistema DentalSync" {
  
  usecase "UC-04\nGestionar\npacientes" as UC04
  usecase "UC-04.1\nRegistrar\nnuevo paciente" as UC04_1
  usecase "UC-04.2\nBuscar\npaciente" as UC04_2
  usecase "UC-04.3\nEditar información\npaciente" as UC04_3
  usecase "UC-04.4\nEliminar\npaciente" as UC04_4
  usecase "UC-04.5\nVer perfil\npaciente" as UC04_5
  usecase "UC-04.6\nFiltrar\npacientes" as UC04_6
  
  usecase "UC-04.E1\nDatos\nincompletos" as UC04_E1
  usecase "UC-04.E2\nPaciente\nduplicado" as UC04_E2
  usecase "UC-04.E3\nPaciente no\nencontrado" as UC04_E3
}

Dentista --> UC04
Recepcionista --> UC04

UC04 --> UC04_1 : <<include>>
UC04 --> UC04_2 : <<include>>
UC04 --> UC04_3 : <<include>>
UC04 --> UC04_4 : <<include>>
UC04 --> UC04_5 : <<include>>
UC04 --> UC04_6 : <<include>>

UC04_1 --> UC04_E1 : <<extend>>
UC04_1 --> UC04_E2 : <<extend>>
UC04_2 --> UC04_E3 : <<extend>>

note right of UC04 : **FLUJO PRINCIPAL:**\n1. Acceder módulo pacientes\n2. Seleccionar acción\n3. Ingresar/modificar datos\n4. Validar información\n5. Guardar cambios

note bottom of UC04 : **PRECONDICIONES:**\n• Sesión iniciada\n• Permisos de gestión\n• Formularios disponibles\n\n**POSTCONDICIONES:**\n• Información actualizada\n• Base de datos sincronizada\n• Historial registrado

@enduml
```

## UC-05: Gestionar Tratamientos

```plantuml
@startuml UC-05

title Módulo de Tratamientos - Gestionar Tratamientos Dentales

actor Dentista

rectangle "Sistema DentalSync" {
  
  usecase "UC-05\nGestionar\ntratamientos" as UC05
  usecase "UC-05.1\nCrear\nplan tratamiento" as UC05_1
  usecase "UC-05.2\nAsignar\ntratamiento" as UC05_2
  usecase "UC-05.3\nSeguimiento\ntratamiento" as UC05_3
  usecase "UC-05.4\nRegistrar\nprogreso" as UC05_4
  usecase "UC-05.5\nFinalizar\ntratamiento" as UC05_5
  
  usecase "UC-05.E1\nTratamiento\nincompatible" as UC05_E1
  usecase "UC-05.E2\nPaciente no\napto" as UC05_E2
}

Dentista --> UC05

UC05 --> UC05_1 : <<include>>
UC05 --> UC05_2 : <<include>>
UC05 --> UC05_3 : <<include>>
UC05 --> UC05_4 : <<include>>
UC05 --> UC05_5 : <<include>>

UC05_1 --> UC05_E1 : <<extend>>
UC05_2 --> UC05_E2 : <<extend>>

note right of UC05 : **FLUJO PRINCIPAL:**\n1. Evaluar paciente\n2. Definir tratamiento\n3. Crear plan detallado\n4. Asignar recursos\n5. Iniciar tratamiento

note bottom of UC05 : **PRECONDICIONES:**\n• Sesión iniciada como dentista\n• Paciente evaluado\n• Diagnóstico realizado\n\n**POSTCONDICIONES:**\n• Tratamiento planificado\n• Recursos asignados\n• Cronograma definido

@enduml
```

## UC-06: Gestionar Pagos

```plantuml
@startuml UC-06

title Módulo de Pagos - Gestionar Pagos y Facturación

actor Dentista
actor Recepcionista
actor Paciente

rectangle "Sistema DentalSync" {
  
  usecase "UC-06\nGestionar\npagos" as UC06
  usecase "UC-06.1\nRegistrar\npago" as UC06_1
  usecase "UC-06.2\nGenerar\nfactura" as UC06_2
  usecase "UC-06.3\nConsultar\nestado cuenta" as UC06_3
  usecase "UC-06.4\nProcesar\nreembolso" as UC06_4
  usecase "UC-06.5\nPlan de\npagos" as UC06_5
  
  usecase "UC-06.E1\nPago\ninsuficiente" as UC06_E1
  usecase "UC-06.E2\nError\nprocesamiento" as UC06_E2
}

Dentista --> UC06
Recepcionista --> UC06
Paciente --> UC06_3

UC06 --> UC06_1 : <<include>>
UC06 --> UC06_2 : <<include>>
UC06 --> UC06_3 : <<include>>
UC06 --> UC06_4 : <<include>>
UC06 --> UC06_5 : <<include>>

UC06_1 --> UC06_E1 : <<extend>>
UC06_1 --> UC06_E2 : <<extend>>

note right of UC06 : **FLUJO PRINCIPAL:**\n1. Calcular monto\n2. Seleccionar método pago\n3. Procesar transacción\n4. Generar comprobante\n5. Actualizar estado cuenta

note bottom of UC06 : **PRECONDICIONES:**\n• Tratamiento realizado\n• Monto definido\n• Sistema de pagos activo\n\n**POSTCONDICIONES:**\n• Pago registrado\n• Factura generada\n• Cuenta actualizada

@enduml
```

## UC-07: Comunicación WhatsApp

```plantuml
@startuml UC-07

title Módulo WhatsApp - Gestionar Comunicación

actor Dentista
actor Recepcionista
actor Paciente

rectangle "Sistema DentalSync" {
  
  usecase "UC-07\nGestionar\nWhatsApp" as UC07
  usecase "UC-07.1\nEnviar\nrecordatorios" as UC07_1
  usecase "UC-07.2\nConfirmar\ncitas" as UC07_2
  usecase "UC-07.3\nEnviar\nresultados" as UC07_3
  usecase "UC-07.4\nGestionar\nplantillas" as UC07_4
  usecase "UC-07.5\nMensajería\ndirecta" as UC07_5
  
  usecase "UC-07.E1\nNúmero\ninválido" as UC07_E1
  usecase "UC-07.E2\nError\nenvío" as UC07_E2
}

Dentista --> UC07
Recepcionista --> UC07
Paciente --> UC07_2

UC07 --> UC07_1 : <<include>>
UC07 --> UC07_2 : <<include>>
UC07 --> UC07_3 : <<include>>
UC07 --> UC07_4 : <<include>>
UC07 --> UC07_5 : <<include>>

UC07_1 --> UC07_E1 : <<extend>>
UC07_5 --> UC07_E2 : <<extend>>

note right of UC07 : **FLUJO PRINCIPAL:**\n1. Seleccionar contacto\n2. Elegir plantilla\n3. Personalizar mensaje\n4. Enviar mensaje\n5. Confirmar recepción

note bottom of UC07 : **PRECONDICIONES:**\n• WhatsApp Business configurado\n• Contactos registrados\n• Plantillas creadas\n\n**POSTCONDICIONES:**\n• Mensaje enviado\n• Historial actualizado\n• Respuesta registrada

@enduml
```

## UC-08: Gestionar Usuarios

```plantuml
@startuml UC-08

title Módulo de Usuarios - Administrar Usuarios del Sistema

actor Administrador

rectangle "Sistema DentalSync" {
  
  usecase "UC-08\nGestionar\nusuarios" as UC08
  usecase "UC-08.1\nCrear\nusuario" as UC08_1
  usecase "UC-08.2\nAsignar\npermisos" as UC08_2
  usecase "UC-08.3\nModificar\nusuario" as UC08_3
  usecase "UC-08.4\nEliminar\nusuario" as UC08_4
  usecase "UC-08.5\nGestionar\nroles" as UC08_5
  
  usecase "UC-08.E1\nUsuario\nduplicado" as UC08_E1
  usecase "UC-08.E2\nPermisos\ninsuficientes" as UC08_E2
}

Administrador --> UC08

UC08 --> UC08_1 : <<include>>
UC08 --> UC08_2 : <<include>>
UC08 --> UC08_3 : <<include>>
UC08 --> UC08_4 : <<include>>
UC08 --> UC08_5 : <<include>>

UC08_1 --> UC08_E1 : <<extend>>
UC08_2 --> UC08_E2 : <<extend>>

note right of UC08 : **FLUJO PRINCIPAL:**\n1. Acceder panel admin\n2. Seleccionar gestión usuarios\n3. Realizar operación\n4. Configurar permisos\n5. Confirmar cambios

note bottom of UC08 : **PRECONDICIONES:**\n• Sesión admin activa\n• Permisos administrativos\n• Sistema disponible\n\n**POSTCONDICIONES:**\n• Usuario gestionado\n• Permisos asignados\n• Sistema actualizado

@enduml
```

## UC-09: Gestionar Historial Clínico

```plantuml
@startuml UC-09

title Módulo de Historial Clínico - Gestionar Historial Médico

actor Dentista

rectangle "Sistema DentalSync" {
  
  usecase "UC-09\nGestionar\nhistorial clínico" as UC09
  usecase "UC-09.1\nCrear\nentrada historial" as UC09_1
  usecase "UC-09.2\nRegistrar\ndiagnóstico" as UC09_2
  usecase "UC-09.3\nDocumentar\nsíntomas" as UC09_3
  usecase "UC-09.4\nAdjuntar\nimágenes" as UC09_4
  usecase "UC-09.5\nConsultar\nhistorial" as UC09_5
  
  usecase "UC-09.E1\nPaciente no\nencontrado" as UC09_E1
  usecase "UC-09.E2\nDatos\nincompletos" as UC09_E2
}

Dentista --> UC09

UC09 --> UC09_1 : <<include>>
UC09 --> UC09_2 : <<include>>
UC09 --> UC09_3 : <<include>>
UC09 --> UC09_4 : <<include>>
UC09 --> UC09_5 : <<include>>

UC09_1 --> UC09_E1 : <<extend>>
UC09_2 --> UC09_E2 : <<extend>>

note right of UC09 : **FLUJO PRINCIPAL:**\n1. Buscar paciente\n2. Acceder historial\n3. Crear nueva entrada\n4. Registrar información\n5. Guardar cambios

note bottom of UC09 : **PRECONDICIONES:**\n• Sesión iniciada como dentista\n• Paciente existe\n• Cita realizada\n\n**POSTCONDICIONES:**\n• Entrada registrada\n• Historial actualizado\n• Documentos adjuntados

@enduml
```

## UC-10: Cerrar Sesión

```plantuml
@startuml UC-10

title Módulo de Autenticación - Cerrar Sesión

actor Usuario

rectangle "Sistema DentalSync" {
  
  usecase "UC-10\nCerrar sesión" as UC10
  usecase "UC-10.1\nGuardar\ncambios pendientes" as UC10_1
  usecase "UC-10.2\nCerrar\nsesión activa" as UC10_2
  usecase "UC-10.3\nRedirigir a\nlogin" as UC10_3
  
  usecase "UC-10.E1\nCambios sin\nguardar" as UC10_E1
}

Usuario --> UC10

UC10 --> UC10_1 : <<include>>
UC10 --> UC10_2 : <<include>>
UC10 --> UC10_3 : <<include>>

UC10_1 --> UC10_E1 : <<extend>>

note right of UC10 : **FLUJO PRINCIPAL:**\n1. Solicitar cierre sesión\n2. Verificar cambios pendientes\n3. Invalidar sesión\n4. Limpiar datos temporales\n5. Redirigir a login

note bottom of UC10 : **PRECONDICIONES:**\n• Sesión activa\n• Usuario autenticado\n\n**POSTCONDICIONES:**\n• Sesión cerrada\n• Datos protegidos\n• Acceso restringido

@enduml
```
