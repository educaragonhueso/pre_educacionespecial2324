-- MySQL dump 10.13  Distrib 5.6.50, for Linux (x86_64)
--
-- Host: localhost    Database: PRE_EDUCACIONESPECIAL2122
-- ------------------------------------------------------
-- Server version	5.6.50

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alumnos_fase2`
--

DROP TABLE IF EXISTS `alumnos_fase2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumnos_fase2` (
  `id_alumno` int(11) NOT NULL DEFAULT '0',
  `nombre` varchar(50) NOT NULL DEFAULT 'nodata',
  `apellido1` varchar(50) NOT NULL DEFAULT 'nodata',
  `apellido2` varchar(50) NOT NULL DEFAULT 'nodata',
  `localidad` char(50) DEFAULT NULL,
  `calle_dfamiliar` varchar(200) NOT NULL DEFAULT 'nodata',
  `coordenadas` char(100) DEFAULT NULL,
  `nombre_centro` varchar(100) DEFAULT NULL,
  `tipoestudios` enum('ebo','tva') DEFAULT 'ebo',
  `fase_solicitud` enum('borrador','validada','baremada') DEFAULT 'borrador',
  `estado_solicitud` enum('irregular','duplicada','apta') DEFAULT 'apta',
  `transporte` enum('1','2','3') DEFAULT '3',
  `nordensorteo` int(11) DEFAULT '0',
  `nasignado` int(11) DEFAULT '0',
  `puntos_validados` float DEFAULT NULL,
  `id_centro` int(11) NOT NULL DEFAULT '0',
  `centro1` varchar(100) NOT NULL,
  `id_centro1` int(11) DEFAULT '0',
  `centro2` varchar(100) DEFAULT NULL,
  `id_centro2` int(11) DEFAULT NULL,
  `centro3` varchar(100) DEFAULT NULL,
  `id_centro3` int(11) DEFAULT NULL,
  `centro4` varchar(100) DEFAULT NULL,
  `id_centro4` int(11) DEFAULT NULL,
  `centro5` varchar(100) DEFAULT NULL,
  `id_centro5` int(11) DEFAULT NULL,
  `centro6` varchar(100) DEFAULT NULL,
  `id_centro6` int(11) DEFAULT NULL,
  `centro_definitivo` varchar(100) DEFAULT 'nocentro',
  `id_centro_definitivo` int(11) DEFAULT NULL,
  `id_centro_origen` int(11) DEFAULT '0',
  `centro_origen` varchar(100) DEFAULT 'nocentro',
  `reserva` tinyint(4) DEFAULT '0',
  `reserva_original` tinyint(4) DEFAULT '0',
  `tipo_modificacion` enum('automatica','manual','nomodificada') DEFAULT 'nomodificada',
  `activo_fase3` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumnos_fase2`
--

LOCK TABLES `alumnos_fase2` WRITE;
/*!40000 ALTER TABLE `alumnos_fase2` DISABLE KEYS */;
INSERT INTO `alumnos_fase2` VALUES (234,'JEINABA','TOURAY','nodata','Zaragoza','EUGENIO LUCAS','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,8,50018131,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50010387,'ALBORADA',1,1,'automatica',0),(295,'Ana','Pardos','Guiu','Zaragoza','Plaza Santo Domingo','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,8,50018131,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(289,'LUCAS','LARGO','PÉREZ','ZARAGOZA','C/SAN JUAN DE LA PEÑA','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,8,50018131,'ALBORADA',50010387,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50008149,'EUGENIO LÓPEZ Y LÓPEZ',0,0,'automatica',0),(174,'DIANGO','GONZÁLEZ','GABARRE','Zaragoza','C/ LA FRAGUA','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,7,50018131,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(165,'Valentina Grace','Maza','de Pedro','Zaragoza','Calle Almonacid de la Sierra','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,7,50018131,'RINCÓN DE GOYA',50011537,'ALBORADA',50010387,'ALBORADA',50010387,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50018830,'VADORREY-LES ALLÉES',0,0,'automatica',0),(238,'HAJAR','OUBAHSYN','EL HAFID','ZARAGOZA','C/ MARÍA VIRTO','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,7,50018131,'ALBORADA',50010387,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50009749,'LA ESTRELLA',0,0,'automatica',0),(233,'SEYNABOU NDIAYE','MBAYE','nodata','Zaragoza','ANDRES VICENTE  ','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,5,50018131,'RINCÓN DE GOYA',50011537,'ANGEL RIVIÈRE',50017369,'ALBORADA',50010387,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50010478,'CIUDAD DE ZARAGOZA',0,0,'automatica',0),(235,'SEYDINA MOUHAMED','MBAYE','nodata','Zaragoza','ANDRES VICENTE','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,5,50018131,'RINCÓN DE GOYA',50011537,'ANGEL RIVIÈRE',50017369,'ALBORADA',50010387,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50010478,'CIUDAD DE ZARAGOZA',0,0,'automatica',0),(168,'Sara','Hernández ','Álvarez ','Zaragoza','Saturno','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,4,50018131,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,'LOS PUEYOS',50009488,NULL,NULL,NULL,NULL,'nocentro',0,50007376,'LA PURÍSIMA PARA NIÑOS SORDOS',1,1,'automatica',0),(205,'Alan','Llop','Altadill','Nonaspe','Calle Maestro','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,2,50018131,'MARÍA SORIANO',50009646,'GLORIA FUERTES',44004148,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50011343,'FABARA-NONASPE DOS AGUAS',0,0,'automatica',0),(240,'AITANA','HEREDERO','LAUDO','ZARAGOZA','MARIANO MALANDIA','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,1,50018131,'MARÍA SORIANO',50009646,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50006220,'HILARIÓN GIMENO',0,0,'automatica',0),(290,'Hugo','Las Heras ','Catalina','Maria de Huerva','C/ Rio Ebro','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,0,50018131,'RINCÓN DE GOYA',50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,'CEDES',50008630,NULL,NULL,NULL,NULL,'nocentro',0,50019251,'VAL DE LA ATALAYA',0,0,'automatica',0),(189,'Florin Emilian','Chirica','chirica','La Muela','Avenida Nuestra Señora la Sagrada','nodata','JEAN PIAGET','ebo','baremada','apta','1',0,0,0,50018131,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(274,'YOUSSEF ','El HOUSSINI ','El idrissi','ZARAGOZA','C/doce de octubre ','nodata','ANGEL RIVIÈRE','ebo','validada','apta','1',0,0,8,50017369,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,'RINCÓN DE GOYA',50011537,NULL,NULL,NULL,NULL,'nocentro',0,50007017,'MARÍA AUXILIADORA',0,0,'automatica',0),(267,'DIEGO','DE LUIS','ADAM','ZARAGOZA','C/ LAS ARMAS','nodata','ANGEL RIVIÈRE','ebo','validada','apta','1',0,0,7,50017369,'RINCÓN DE GOYA',50011537,'ALBORADA',50010387,'JEAN PIAGET',50018131,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(270,'Jaime','Martín','Mateos','Zaragoza','Paseo Infantes de España ','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,16,50011537,'RINCÓN DE GOYA',50011537,'LOS PUEYOS',50009488,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50007789,'SANTA MARÍA DEL PILAR',0,0,'automatica',0),(258,'AROA','LOPEZ','TEJERO','ZARAGOZA','TRAVESIA PUENTE VIRREY','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,9,50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,'MARÍA SORIANO',50009646,'LOS PUEYOS',50009488,'JEAN PIAGET',50018131,'ALBORADA',50010387,'ANGEL RIVIÈRE',50017369,'nocentro',0,22010876,'MARÍA MOLINER',0,0,'automatica',0),(247,'MUHAMMAD','ZEB','REHMAN','ZARAGOZA','Avenida Madrid','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,9,50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,'MARÍA SORIANO',50009646,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(241,'ALONSO','PAESA','NOVELLA','ZARAGOZA','C/ PILAR ARANDA','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,8,50011537,'RINCÓN DE GOYA',50011537,'JEAN PIAGET',50018131,'ALBORADA',50010387,'CEDES',50008630,'ANGEL RIVIÈRE',50017369,NULL,NULL,'nocentro',0,50019019,'JULIO VERNE',0,0,'automatica',0),(252,'JUAN','ROMERO','SANTIAGO','ZARAGOZA','CALLE GABRIELA MISTRAL','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,8,50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,'ALBORADA',50010387,'JEAN PIAGET',50018131,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(245,'CLARA VALERIA','TORREZ','PALMA','ZARAGOZA','CALLE AVILA','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,8,50011537,'LA PURÍSIMA PARA NIÑOS SORDOS',50007376,'ANGEL RIVIÈRE',50017369,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50006025,'JOSÉ MARÍA MIR',0,0,'automatica',0),(262,'Monica','Torres','Burgos','Zaragoza','Calle Cabezo de Buenavista','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,8,50011537,'ANGEL RIVIÈRE',50017369,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50007042,'LA MILAGROSA',0,0,'automatica',0),(185,'Liam','mateo','mateos','ZARAGOZA','calle osa mayor','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,7,50011537,'ANGEL RIVIÈRE',50017369,'JEAN PIAGET',50018131,'ALBORADA',50010387,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,'nocentro',0,50018271,'ROSALES DEL CANAL',0,0,'automatica',0),(257,'DIEGO','PINA','ALLO','ZARAGOZA','CALLE LOS PUENTES DE MADISON','nodata','RINCÓN DE GOYA','ebo','validada','apta','1',0,0,7,50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,'ALBORADA',50010387,'JEAN PIAGET',50018131,'JEAN PIAGET',50018131,NULL,NULL,NULL,NULL,'nocentro',0,50019299,'PILAR BAYONA',0,0,'automatica',0),(248,'Ian Aaron','Reyes','Martínez','Zaragoza','Paseo Cuéllar','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'RINCÓN DE GOYA',50011537,'JEAN PIAGET',50018131,'ANGEL RIVIÈRE',50017369,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,'ALBORADA',50010387,'SAN GREGORIO',22003343,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(265,'LEO','FERRER','RUBIO','Zaragoza','Calle Santander','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'JEAN PIAGET',50018131,'ANGEL RIVIÈRE',50017369,'ALBORADA',50010387,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50007376,'LA PURÍSIMA PARA NIÑOS SORDOS',1,1,'automatica',0),(249,'MOHAMED TAHA','RIAD','nodata','Zaragoza','CALLE OBISPO DEL TAJON','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'RINCÓN DE GOYA',50011537,'ANGEL RIVIÈRE',50017369,'ALBORADA',50010387,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(167,'Rosita','Owono ','Angüe','Zaragoza','Calle Fray Juan Regla ','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'ALBORADA',50010387,'ANGEL RIVIÈRE',50017369,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(293,'JUAN CARLOS','EKUA','LÓPEZ','Zaragoza','DAROCA','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,'ALBORADA',50010387,'ANGEL RIVIÈRE',50017369,'JEAN PIAGET',50018131,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(169,'Lucia Silvana','Tolon','Ena','Zaragoza','Calle del Baron de la Linde','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50006050,'LUIS VIVES',0,0,'automatica',0),(228,'Dennis Hernan','Ascuntar','Sinche','Zaragoza','Calle las rosas (Casablanca) ','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,6,50011537,'JEAN PIAGET',50018131,'ALBORADA',50010387,NULL,NULL,'ANGEL RIVIÈRE',50017369,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(236,'ADAM','BELLALI','nodata','ZARAGOZA','BELGICA','nodata','RINCÓN DE GOYA','ebo','validada','apta','1',0,0,6,50011537,'ANGEL RIVIÈRE',50017369,'ATADES, CENTRO DE EDUCACIÓN ESPECIAL SAN MARTÍN DE PORRES',50007674,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50009531,'RAMÓN SÁINZ DE VARANDA',0,0,'automatica',0),(260,'Raúl Marius','Gagiu','nodata','Casetas','Urbanizacion Alameda','nodata','RINCÓN DE GOYA','ebo','baremada','apta','1',0,0,3,50011537,'RINCÓN DE GOYA',50011537,'JEAN PIAGET',50018131,'ALBORADA',50010387,'ANGEL RIVIÈRE',50017369,NULL,NULL,NULL,NULL,'nocentro',0,50009786,'ANTONIO MARTÍNEZ GARAY',0,0,'automatica',0),(192,'ANDRII','NEPEYVODA','NEPEYVODA','PEDROLA','C/ ZARAGOZA','nodata','RINCÓN DE GOYA','tva','baremada','apta','2',0,0,1,50011537,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,0,'NOCENTRO',0,0,'automatica',0),(285,'ANDREA','VIGO','PEREZ','Ejea de los Caballeros','SALIENTE ','nodata','RECTOR MAMÉS ESPERABÉ','ebo','validada','apta','1',0,0,0,50008678,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'nocentro',0,50008678,'RECTOR MAMÉS ESPERABÉ',0,0,'automatica',0);
/*!40000 ALTER TABLE `alumnos_fase2` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-27 20:57:21
