/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: dentalsync2
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel-cache-login-attempts:127.0.0.1','i:2;',1754419808),
('laravel-cache-login-attempts:127.0.0.1:timer','i:1754419808;',1754419808);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citas`
--

DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `citas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `motivo` text NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','atendida') NOT NULL DEFAULT 'pendiente',
  `fecha_atendida` datetime DEFAULT NULL,
  `paciente_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `citas_paciente_id_foreign` (`paciente_id`),
  KEY `citas_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `citas_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `citas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citas`
--

LOCK TABLES `citas` WRITE;
/*!40000 ALTER TABLE `citas` DISABLE KEYS */;
/*!40000 ALTER TABLE `citas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuotas_pago`
--

DROP TABLE IF EXISTS `cuotas_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuotas_pago` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pago_id` bigint(20) unsigned NOT NULL,
  `numero_cuota` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('pendiente','pagada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cuotas_pago_pago_id_foreign` (`pago_id`),
  CONSTRAINT `cuotas_pago_pago_id_foreign` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuotas_pago`
--

LOCK TABLES `cuotas_pago` WRITE;
/*!40000 ALTER TABLE `cuotas_pago` DISABLE KEYS */;
INSERT INTO `cuotas_pago` VALUES
(1,2,1,200.00,'2025-08-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(2,2,2,200.00,'2025-09-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(3,2,3,200.00,'2025-10-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(4,2,4,200.00,'2025-11-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(5,2,5,200.00,'2025-12-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(6,2,6,200.00,'2026-01-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(7,5,1,200.00,'2025-08-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(8,5,2,200.00,'2025-09-05','pagada','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(9,5,3,200.00,'2025-10-05','pendiente','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(10,5,4,200.00,'2025-11-05','pendiente','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(11,5,5,200.00,'2025-12-05','pendiente','2025-08-05 21:38:13','2025-08-05 21:38:13'),
(12,5,6,200.00,'2026-01-05','pendiente','2025-08-05 21:38:13','2025-08-05 21:38:13');
/*!40000 ALTER TABLE `cuotas_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pagos`
--

DROP TABLE IF EXISTS `detalle_pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pagos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pago_id` bigint(20) unsigned NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_parcial` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_pago` enum('cuota_fija','pago_variable','pago_completo') NOT NULL DEFAULT 'pago_completo',
  `numero_cuota` int(11) DEFAULT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_pagos_usuario_id_foreign` (`usuario_id`),
  KEY `detalle_pagos_pago_id_fecha_pago_index` (`pago_id`,`fecha_pago`),
  KEY `detalle_pagos_tipo_pago_index` (`tipo_pago`),
  KEY `detalle_pagos_numero_cuota_index` (`numero_cuota`),
  CONSTRAINT `detalle_pagos_pago_id_foreign` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_pagos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pagos`
--

LOCK TABLES `detalle_pagos` WRITE;
/*!40000 ALTER TABLE `detalle_pagos` DISABLE KEYS */;
INSERT INTO `detalle_pagos` VALUES
(1,1,'2025-08-05',150.00,'Pago completo en efectivo','pago_completo',NULL,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(2,2,'2025-08-06',200.00,'Cuota #1 de 6','cuota_fija',1,2,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(3,2,'2025-08-07',200.00,'Cuota #2 de 6','cuota_fija',2,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(4,3,'2025-07-26',1000.00,'Pago inicial del implante','pago_variable',NULL,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(5,4,'2025-07-31',150.00,'Pago completo en efectivo','pago_completo',NULL,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(6,5,'2025-08-03',200.00,'Cuota #1 de 6','cuota_fija',1,2,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(7,5,'2025-08-04',200.00,'Cuota #2 de 6','cuota_fija',2,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(8,6,'2025-07-26',150.00,'Pago completo en efectivo','pago_completo',NULL,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(9,2,'2025-08-05',200.00,'Cuota 3 de 6','cuota_fija',3,1,'2025-08-05 21:40:51','2025-08-05 21:40:51'),
(10,2,'2025-08-05',200.00,'Cuota 4 de 6','cuota_fija',4,1,'2025-08-05 21:40:55','2025-08-05 21:40:55'),
(11,2,'2025-08-05',200.00,'Cuota 5 de 6','cuota_fija',5,1,'2025-08-05 21:40:58','2025-08-05 21:40:58'),
(12,2,'2025-08-05',200.00,'Cuota 6 de 6','cuota_fija',6,1,'2025-08-05 21:41:00','2025-08-05 21:41:00');
/*!40000 ALTER TABLE `detalle_pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_clinico`
--

DROP TABLE IF EXISTS `historial_clinico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_clinico` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_visita` date NOT NULL,
  `tratamiento` text NOT NULL,
  `observaciones` text DEFAULT NULL,
  `paciente_id` bigint(20) unsigned NOT NULL,
  `tratamiento_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_clinico_paciente_id_foreign` (`paciente_id`),
  KEY `historial_clinico_tratamiento_id_foreign` (`tratamiento_id`),
  CONSTRAINT `historial_clinico_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historial_clinico_tratamiento_id_foreign` FOREIGN KEY (`tratamiento_id`) REFERENCES `tratamientos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_clinico`
--

LOCK TABLES `historial_clinico` WRITE;
/*!40000 ALTER TABLE `historial_clinico` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_clinico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2025_07_22_190310_create_usuarios_table',1),
(2,'2025_07_22_190311_create_pacientes_table',1),
(3,'2025_07_22_190312_create_tratamientos_table',1),
(4,'2025_07_22_190313_create_historial_clinico_table',1),
(5,'2025_07_22_190314_create_pagos_table',1),
(6,'2025_07_22_190317_create_citas_table',1),
(7,'2025_07_22_190318_create_cuotas_pago_table',1),
(8,'2025_07_22_190318_create_placas_dentales_table',1),
(9,'2025_07_22_192336_create_sessions_table',1),
(10,'2025_07_26_000001_create_whatsapp_conversaciones_table',1),
(11,'2025_07_26_000002_create_whatsapp_mensajes_table',1),
(12,'2025_07_26_000003_create_whatsapp_plantillas_table',1),
(13,'2025_07_26_000004_create_whatsapp_automatizaciones_table',1),
(14,'2025_07_26_000005_create_whatsapp_envios_programados_table',1),
(15,'2025_07_26_145815_create_cache_table',1),
(16,'2025_07_26_200000_update_pagos_table_for_payment_system',1),
(17,'2025_07_26_200001_create_detalle_pagos_table',1),
(18,'2025_07_26_223029_add_additional_fields_to_pacientes_table',1),
(19,'2025_07_26_223740_remove_unused_fields_from_pacientes_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pacientes`
--

DROP TABLE IF EXISTS `pacientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pacientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `motivo_consulta` text DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `ultima_visita` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pacientes`
--

LOCK TABLES `pacientes` WRITE;
/*!40000 ALTER TABLE `pacientes` DISABLE KEYS */;
INSERT INTO `pacientes` VALUES
(1,'María García López','1234567890',NULL,NULL,NULL,'1990-05-15',NULL,'2025-08-05 21:37:40','2025-08-05 21:37:40'),
(2,'Juan Pérez Martínez','0987654321',NULL,NULL,NULL,'1985-03-10',NULL,'2025-08-05 21:37:40','2025-08-05 21:37:40'),
(3,'Ana Rodríguez Silva','5556667777',NULL,NULL,NULL,'1992-08-22',NULL,'2025-08-05 21:37:40','2025-08-05 21:37:40');
/*!40000 ALTER TABLE `pacientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_pago` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `saldo_restante` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cuotas` int(11) DEFAULT NULL,
  `estado_pago` enum('pendiente','pagado_parcial','pagado_completo','vencido') NOT NULL DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `descripcion` text NOT NULL,
  `modalidad_pago` enum('pago_unico','cuotas_fijas','cuotas_variables') NOT NULL DEFAULT 'pago_unico',
  `paciente_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_paciente_id_foreign` (`paciente_id`),
  KEY `pagos_usuario_id_foreign` (`usuario_id`),
  KEY `pagos_modalidad_pago_index` (`modalidad_pago`),
  KEY `pagos_estado_pago_index` (`estado_pago`),
  CONSTRAINT `pagos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
INSERT INTO `pagos` VALUES
(1,'2025-08-05',150.00,150.00,0.00,1,'pagado_completo','Pago completo al contado','Limpieza dental','pago_unico',1,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(2,'2025-08-05',1200.00,1200.00,0.00,6,'pagado_completo','Pago en 6 cuotas de $200 c/u','Tratamiento de ortodoncia','cuotas_fijas',1,1,'2025-08-05 21:38:13','2025-08-05 21:41:00'),
(3,'2025-07-26',2500.00,1000.00,1500.00,4,'pagado_parcial','Pago inicial de $1000, resto en cuotas variables','Implante dental','cuotas_variables',1,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(4,'2025-07-31',150.00,150.00,0.00,1,'pagado_completo','Pago completo al contado','Limpieza dental','pago_unico',2,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(5,'2025-08-02',1200.00,400.00,800.00,6,'pagado_parcial','Pago en 6 cuotas de $200 c/u','Tratamiento de ortodoncia','cuotas_fijas',2,1,'2025-08-05 21:38:13','2025-08-05 21:38:13'),
(6,'2025-07-26',150.00,150.00,0.00,1,'pagado_completo','Pago completo al contado','Limpieza dental','pago_unico',3,1,'2025-08-05 21:38:13','2025-08-05 21:38:13');
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `placas_dentales`
--

DROP TABLE IF EXISTS `placas_dentales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `placas_dentales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `archivo_url` varchar(500) DEFAULT NULL,
  `paciente_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `placas_dentales_paciente_id_foreign` (`paciente_id`),
  CONSTRAINT `placas_dentales_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `placas_dentales`
--

LOCK TABLES `placas_dentales` WRITE;
/*!40000 ALTER TABLE `placas_dentales` DISABLE KEYS */;
/*!40000 ALTER TABLE `placas_dentales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('SKqWnIbZnQvzObx9cYSC0GS7vrWqrBx1AFDpAenS',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXdVdFNqRHlRdmxxTEt1dmdyU09HaXNDcWZkSVd5Q2xjWVhOZlh3OCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kaWVudGUtZmF2aWNvbi5wbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1754419772);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tratamientos`
--

DROP TABLE IF EXISTS `tratamientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tratamientos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `estado` enum('activo','finalizado') NOT NULL DEFAULT 'activo',
  `paciente_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tratamientos_paciente_id_foreign` (`paciente_id`),
  KEY `tratamientos_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `tratamientos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tratamientos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tratamientos`
--

LOCK TABLES `tratamientos` WRITE;
/*!40000 ALTER TABLE `tratamientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tratamientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `rol` enum('dentista','recepcionista') NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_usuario_unique` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'admin','Administrador del Sistema','dentista','$2y$12$NYH5aosSPsPPT8AHCktyzuFmlEEp7ABuTPtiijGdrO7adZFhchT.S',1,'2025-08-05 21:36:42','2025-08-05 21:36:42'),
(2,'dr.martinez','Dr. Carlos Martínez','dentista','$2y$12$xyivL3lIEO1ZIaA6q/qkaOwRaDbqq/7Kjwfvg1QPwvh7fKEoXsjjW',1,'2025-08-05 21:36:42','2025-08-05 21:36:42'),
(3,'dra.lopez','Dra. María López','dentista','$2y$12$gd0VsjyjHb/8rxca1awzKubHLrmzDk8e.nN/GDwlyBK3A266KHGGS',1,'2025-08-05 21:36:42','2025-08-05 21:36:42'),
(4,'recepcion1','Ana García','recepcionista','$2y$12$jB8JekHffjAVbYxdT15HFe8BnOXXmKxyPD8kcMcglly3MZj0x3PYC',1,'2025-08-05 21:36:42','2025-08-05 21:36:42'),
(5,'recepcion2','Laura Rodríguez','recepcionista','$2y$12$ScfRjzc.EK91yvC261DgOOAnN6tyVaqVwSiLGDY.0DE5Z7I.trcQ6',1,'2025-08-05 21:36:42','2025-08-05 21:36:42'),
(6,'dr.inactivo','Dr. Usuario Inactivo','dentista','$2y$12$gUXrphtuB2b1CRNRliIdSuQT8YoX2EFYwfvtKIsvu8ueBKMNSWj6a',0,'2025-08-05 21:36:42','2025-08-05 21:36:42');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_automatizaciones`
--

DROP TABLE IF EXISTS `whatsapp_automatizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `whatsapp_automatizaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('recordatorio','seguimiento','bienvenida','cumpleanos','pago') NOT NULL DEFAULT 'recordatorio',
  `condicion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`condicion`)),
  `audiencia` enum('todos','nuevos','recurrentes','activos') NOT NULL DEFAULT 'todos',
  `mensaje` text NOT NULL,
  `estado` enum('activa','inactiva','pausada') NOT NULL DEFAULT 'activa',
  `limite_envios` tinyint(1) NOT NULL DEFAULT 0,
  `max_envios_paciente` int(11) NOT NULL DEFAULT 1,
  `ejecutada` int(11) NOT NULL DEFAULT 0,
  `exitosas` int(11) NOT NULL DEFAULT 0,
  `fallidas` int(11) NOT NULL DEFAULT 0,
  `ultimo_ejecutado` timestamp NULL DEFAULT NULL,
  `creado_por` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `whatsapp_automatizaciones_creado_por_foreign` (`creado_por`),
  KEY `whatsapp_automatizaciones_estado_tipo_index` (`estado`,`tipo`),
  CONSTRAINT `whatsapp_automatizaciones_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_automatizaciones`
--

LOCK TABLES `whatsapp_automatizaciones` WRITE;
/*!40000 ALTER TABLE `whatsapp_automatizaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `whatsapp_automatizaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_conversaciones`
--

DROP TABLE IF EXISTS `whatsapp_conversaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `whatsapp_conversaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` bigint(20) unsigned NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `nombre_contacto` varchar(255) NOT NULL,
  `estado` enum('activa','pausada','cerrada','bloqueada') NOT NULL DEFAULT 'activa',
  `ultimo_mensaje_fecha` timestamp NULL DEFAULT NULL,
  `ultimo_mensaje_texto` text DEFAULT NULL,
  `ultimo_mensaje_propio` tinyint(1) NOT NULL DEFAULT 0,
  `mensajes_no_leidos` int(11) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `whatsapp_conversaciones_paciente_id_telefono_unique` (`paciente_id`,`telefono`),
  KEY `whatsapp_conversaciones_estado_ultimo_mensaje_fecha_index` (`estado`,`ultimo_mensaje_fecha`),
  KEY `whatsapp_conversaciones_telefono_index` (`telefono`),
  CONSTRAINT `whatsapp_conversaciones_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_conversaciones`
--

LOCK TABLES `whatsapp_conversaciones` WRITE;
/*!40000 ALTER TABLE `whatsapp_conversaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `whatsapp_conversaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_envios_programados`
--

DROP TABLE IF EXISTS `whatsapp_envios_programados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `whatsapp_envios_programados` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `telefono` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_programada` timestamp NOT NULL,
  `estado` enum('pendiente','enviado','error','cancelado') NOT NULL DEFAULT 'pendiente',
  `tipo_envio` enum('individual','masivo') NOT NULL DEFAULT 'individual',
  `automatizacion_id` bigint(20) unsigned DEFAULT NULL,
  `destinatarios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`destinatarios`)),
  `error_mensaje` text DEFAULT NULL,
  `fecha_envio` timestamp NULL DEFAULT NULL,
  `creado_por` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `whatsapp_envios_programados_automatizacion_id_foreign` (`automatizacion_id`),
  KEY `whatsapp_envios_programados_creado_por_foreign` (`creado_por`),
  KEY `whatsapp_envios_programados_estado_fecha_programada_index` (`estado`,`fecha_programada`),
  CONSTRAINT `whatsapp_envios_programados_automatizacion_id_foreign` FOREIGN KEY (`automatizacion_id`) REFERENCES `whatsapp_automatizaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `whatsapp_envios_programados_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_envios_programados`
--

LOCK TABLES `whatsapp_envios_programados` WRITE;
/*!40000 ALTER TABLE `whatsapp_envios_programados` DISABLE KEYS */;
/*!40000 ALTER TABLE `whatsapp_envios_programados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_mensajes`
--

DROP TABLE IF EXISTS `whatsapp_mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `whatsapp_mensajes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conversacion_id` bigint(20) unsigned NOT NULL,
  `mensaje_whatsapp_id` varchar(255) DEFAULT NULL,
  `contenido` text NOT NULL,
  `es_propio` tinyint(1) NOT NULL DEFAULT 1,
  `estado` enum('enviando','enviado','entregado','leido','error') NOT NULL DEFAULT 'enviando',
  `tipo` enum('texto','imagen','documento','audio','video') NOT NULL DEFAULT 'texto',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `fecha_envio` timestamp NOT NULL,
  `fecha_entregado` timestamp NULL DEFAULT NULL,
  `fecha_leido` timestamp NULL DEFAULT NULL,
  `error_mensaje` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `whatsapp_mensajes_conversacion_id_fecha_envio_index` (`conversacion_id`,`fecha_envio`),
  KEY `whatsapp_mensajes_es_propio_estado_index` (`es_propio`,`estado`),
  KEY `whatsapp_mensajes_mensaje_whatsapp_id_index` (`mensaje_whatsapp_id`),
  CONSTRAINT `whatsapp_mensajes_conversacion_id_foreign` FOREIGN KEY (`conversacion_id`) REFERENCES `whatsapp_conversaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_mensajes`
--

LOCK TABLES `whatsapp_mensajes` WRITE;
/*!40000 ALTER TABLE `whatsapp_mensajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `whatsapp_mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_plantillas`
--

DROP TABLE IF EXISTS `whatsapp_plantillas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `whatsapp_plantillas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` enum('recordatorio','confirmacion','pago','tratamiento','bienvenida','general') NOT NULL DEFAULT 'general',
  `contenido` text NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `usos` int(11) NOT NULL DEFAULT 0,
  `variables_detectadas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables_detectadas`)),
  `creado_por` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `whatsapp_plantillas_creado_por_foreign` (`creado_por`),
  KEY `whatsapp_plantillas_categoria_activa_index` (`categoria`,`activa`),
  CONSTRAINT `whatsapp_plantillas_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_plantillas`
--

LOCK TABLES `whatsapp_plantillas` WRITE;
/*!40000 ALTER TABLE `whatsapp_plantillas` DISABLE KEYS */;
/*!40000 ALTER TABLE `whatsapp_plantillas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-06  7:47:31
