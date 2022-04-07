<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
######################################################################################
# script plantilla
######################################################################################
?>
