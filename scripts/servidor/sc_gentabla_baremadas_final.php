<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';

require_once 'UtilidadesAdmision.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$ccentros=new CentrosController($conexion);
$centro=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,$ccentros,$centro);

//falta termanira//consulta:
//consulta:
//create table alumnos_baremada_provisional SELECT a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado,b.* FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno  WHERE fase_solicitud!='borrador'  order by a.tipoestudios, a.apellido1,a.nombre,a.transporte desc,b.puntos_validados desc,b.hermanos_centro desc,b.proximidad_domicilio,b.renta_inferior,b.discapacidad,b.tipo_familia,a.nordensorteo asc,a.nasignado desc;

//actualizar vacantes de centros
$res=$utils->genBaremadas();

if($res==1) echo "Generada tabla baremadas final a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
else "Error generando provisionales: $res";
?>
