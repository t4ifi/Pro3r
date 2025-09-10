#!/bin/bash

# Script para mover toda la documentación a la carpeta /docs organizada
# Creado el 10 de septiembre de 2025

echo "🚀 Iniciando reorganización de documentación..."

# Directorio base
BASE_DIR="/home/t4ifi/Andres/Proyecto/Pro3r/Pro3r"
DOCS_DIR="$BASE_DIR/docs"

cd "$BASE_DIR"

echo "📁 Moviendo archivos de controladores..."
# Controladores
[ -f "DOCUMENTACION_CONTROLADORES.md" ] && mv "DOCUMENTACION_CONTROLADORES.md" "$DOCS_DIR/controllers/" && echo "  ✅ DOCUMENTACION_CONTROLADORES.md"

echo "📁 Moviendo archivos técnicos..."
# Documentación técnica
[ -f "ESTRUCTURA_PROYECTO_COMPLETA.md" ] && mv "ESTRUCTURA_PROYECTO_COMPLETA.md" "$DOCS_DIR/technical/" && echo "  ✅ ESTRUCTURA_PROYECTO_COMPLETA.md"
[ -f "GUIA_ESTRUCTURA_PROYECTO.md" ] && mv "GUIA_ESTRUCTURA_PROYECTO.md" "$DOCS_DIR/technical/" && echo "  ✅ GUIA_ESTRUCTURA_PROYECTO.md"
[ -f "DOCUMENTACION_SEGURIDAD.md" ] && mv "DOCUMENTACION_SEGURIDAD.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_SEGURIDAD.md"
[ -f "DOCUMENTACION_TECNICA_PACIENTE_EDITAR.md" ] && mv "DOCUMENTACION_TECNICA_PACIENTE_EDITAR.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_TECNICA_PACIENTE_EDITAR.md"
[ -f "CODE_DOCUMENTATION.md" ] && mv "CODE_DOCUMENTATION.md" "$DOCS_DIR/technical/" && echo "  ✅ CODE_DOCUMENTATION.md"
[ -f "DOCUMENTACION_COMPLETA_SISTEMA.md" ] && mv "DOCUMENTACION_COMPLETA_SISTEMA.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_COMPLETA_SISTEMA.md"
[ -f "DOCUMENTACION_EDITAR_PACIENTES.md" ] && mv "DOCUMENTACION_EDITAR_PACIENTES.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_EDITAR_PACIENTES.md"
[ -f "DOCUMENTACION_PAGOS.md" ] && mv "DOCUMENTACION_PAGOS.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_PAGOS.md"
[ -f "DOCUMENTACION_PDF_PACIENTES.md" ] && mv "DOCUMENTACION_PDF_PACIENTES.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_PDF_PACIENTES.md"
[ -f "DOCUMENTACION_PDF_PAGOS.md" ] && mv "DOCUMENTACION_PDF_PAGOS.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_PDF_PAGOS.md"
[ -f "DOCUMENTACION_REGISTRO_PACIENTES_MEJORADO.md" ] && mv "DOCUMENTACION_REGISTRO_PACIENTES_MEJORADO.md" "$DOCS_DIR/technical/" && echo "  ✅ DOCUMENTACION_REGISTRO_PACIENTES_MEJORADO.md"

echo "📁 Moviendo archivos de base de datos..."
# Base de datos (algunos ya están en docs/)
[ -f "docs/Database-Documentation.md" ] && mv "docs/Database-Documentation.md" "$DOCS_DIR/database/" && echo "  ✅ Database-Documentation.md"

echo "📁 Moviendo changelogs..."
# Changelogs
[ -f "CHANGELOG.md" ] && mv "CHANGELOG.md" "$DOCS_DIR/changelogs/" && echo "  ✅ CHANGELOG.md"
[ -f "CAMBIOS_RECIENTES.md" ] && mv "CAMBIOS_RECIENTES.md" "$DOCS_DIR/changelogs/" && echo "  ✅ CAMBIOS_RECIENTES.md"
[ -f "CHANGELOG_REGISTRO_PACIENTES.md" ] && mv "CHANGELOG_REGISTRO_PACIENTES.md" "$DOCS_DIR/changelogs/" && echo "  ✅ CHANGELOG_REGISTRO_PACIENTES.md"

echo "📁 Moviendo reportes..."
# Reportes
[ -f "REPORTE_EJECUTIVO_PAGOS.md" ] && mv "REPORTE_EJECUTIVO_PAGOS.md" "$DOCS_DIR/reports/" && echo "  ✅ REPORTE_EJECUTIVO_PAGOS.md"
[ -f "RESUMEN_EJECUTIVO_REGISTRO_PACIENTES.md" ] && mv "RESUMEN_EJECUTIVO_REGISTRO_PACIENTES.md" "$DOCS_DIR/reports/" && echo "  ✅ RESUMEN_EJECUTIVO_REGISTRO_PACIENTES.md"
[ -f "LOGROS_JULIO_2025.md" ] && mv "LOGROS_JULIO_2025.md" "$DOCS_DIR/reports/" && echo "  ✅ LOGROS_JULIO_2025.md"
[ -f "SECURITY_IMPROVEMENTS_REPORT.md" ] && mv "SECURITY_IMPROVEMENTS_REPORT.md" "$DOCS_DIR/reports/" && echo "  ✅ SECURITY_IMPROVEMENTS_REPORT.md"
[ -f "PROJECT_SUMMARY.md" ] && mv "PROJECT_SUMMARY.md" "$DOCS_DIR/reports/" && echo "  ✅ PROJECT_SUMMARY.md"
[ -f "IMPLEMENTACION_COMPLETADA.md" ] && mv "IMPLEMENTACION_COMPLETADA.md" "$DOCS_DIR/reports/" && echo "  ✅ IMPLEMENTACION_COMPLETADA.md"
[ -f "MEJORAS_GESTION_PAGOS.md" ] && mv "MEJORAS_GESTION_PAGOS.md" "$DOCS_DIR/reports/" && echo "  ✅ MEJORAS_GESTION_PAGOS.md"
[ -f "INFORME_MATEMATICAS_PROYECTO.md" ] && mv "INFORME_MATEMATICAS_PROYECTO.md" "$DOCS_DIR/reports/" && echo "  ✅ INFORME_MATEMATICAS_PROYECTO.md"
[ -f "ERRORES_SISTEMA_PAGOS.md" ] && mv "ERRORES_SISTEMA_PAGOS.md" "$DOCS_DIR/reports/" && echo "  ✅ ERRORES_SISTEMA_PAGOS.md"
[ -f "GUIA_IMPLEMENTACION_PAGOS.md" ] && mv "GUIA_IMPLEMENTACION_PAGOS.md" "$DOCS_DIR/reports/" && echo "  ✅ GUIA_IMPLEMENTACION_PAGOS.md"

echo "📁 Moviendo planificación..."
# Planificación
[ -f "PLANIFICACION.md" ] && mv "PLANIFICACION.md" "$DOCS_DIR/planning/" && echo "  ✅ PLANIFICACION.md"
[ -f "BITACORA_PROYECTO.md" ] && mv "BITACORA_PROYECTO.md" "$DOCS_DIR/planning/" && echo "  ✅ BITACORA_PROYECTO.md"
[ -f "BITACORA_PROYECTO_SEMANAL.md" ] && mv "BITACORA_PROYECTO_SEMANAL.md" "$DOCS_DIR/planning/" && echo "  ✅ BITACORA_PROYECTO_SEMANAL.md"
[ -f "DEBUGGING_LOG.md" ] && mv "DEBUGGING_LOG.md" "$DOCS_DIR/planning/" && echo "  ✅ DEBUGGING_LOG.md"

echo "📁 Moviendo archivos de API y otros técnicos desde docs/..."
# Archivos ya en docs/ que necesitan reorganizarse
[ -f "docs/API_DOCUMENTATION.md" ] && mv "docs/API_DOCUMENTATION.md" "$DOCS_DIR/technical/" && echo "  ✅ API_DOCUMENTATION.md"
[ -f "docs/EXECUTIVE_SUMMARY.md" ] && mv "docs/EXECUTIVE_SUMMARY.md" "$DOCS_DIR/reports/" && echo "  ✅ EXECUTIVE_SUMMARY.md"
[ -f "docs/WHATSAPP_INTEGRATION.md" ] && mv "docs/WHATSAPP_INTEGRATION.md" "$DOCS_DIR/technical/" && echo "  ✅ WHATSAPP_INTEGRATION.md"

echo "📁 Moviendo archivos de placas dentales..."
# Placas dentales
[ -f "docs/placas-dentales.md" ] && mv "docs/placas-dentales.md" "$DOCS_DIR/technical/" && echo "  ✅ placas-dentales.md"
[ -f "docs/placas-dentales-desarrollo.md" ] && mv "docs/placas-dentales-desarrollo.md" "$DOCS_DIR/technical/" && echo "  ✅ placas-dentales-desarrollo.md"
[ -f "docs/placas-dentales-manual-usuario.md" ] && mv "docs/placas-dentales-manual-usuario.md" "$DOCS_DIR/technical/" && echo "  ✅ placas-dentales-manual-usuario.md"

echo "📁 Moviendo archivos de TratamientoVer..."
# TratamientoVer
[ -f "docs/TratamientoVer-Documentation.md" ] && mv "docs/TratamientoVer-Documentation.md" "$DOCS_DIR/technical/" && echo "  ✅ TratamientoVer-Documentation.md"
[ -f "docs/TratamientoVer-TechnicalCode.md" ] && mv "docs/TratamientoVer-TechnicalCode.md" "$DOCS_DIR/technical/" && echo "  ✅ TratamientoVer-TechnicalCode.md"
[ -f "docs/TratamientoVer-ExecutiveSummary.md" ] && mv "docs/TratamientoVer-ExecutiveSummary.md" "$DOCS_DIR/reports/" && echo "  ✅ TratamientoVer-ExecutiveSummary.md"
[ -f "docs/TratamientoVer-ErrorLog.md" ] && mv "docs/TratamientoVer-ErrorLog.md" "$DOCS_DIR/planning/" && echo "  ✅ TratamientoVer-ErrorLog.md"

echo "📁 Moviendo archivos de proyecto..."
# Proyecto
[ -f "docs/Proyecto-Egreso-NullDevs.md" ] && mv "docs/Proyecto-Egreso-NullDevs.md" "$DOCS_DIR/reports/" && echo "  ✅ Proyecto-Egreso-NullDevs.md"

echo "📁 Manteniendo archivos principales..."
# Mantener archivos importantes en raíz
echo "  📄 Manteniendo README.md en raíz"
echo "  📄 Manteniendo README_SISTEMA_COMPLETO.md en raíz"

echo ""
echo "📊 Resumen de la reorganización:"
echo "  📁 /docs/controllers/    - Documentación de controladores"
echo "  📁 /docs/technical/      - Documentación técnica"
echo "  📁 /docs/database/       - Documentación de base de datos"
echo "  📁 /docs/changelogs/     - Historial de cambios"
echo "  📁 /docs/reports/        - Reportes ejecutivos"
echo "  📁 /docs/planning/       - Planificación y bitácoras"
echo ""
echo "✅ ¡Reorganización completada exitosamente!"
echo "📖 Consulta /docs/README.md para navegar por la documentación"
