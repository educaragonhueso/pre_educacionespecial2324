<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_CLASES.'LOGGER.php';

######################################################################################
$log_mostrar_matriculados=new logWriter('log_mostrar_matriculados',DIR_LOGS);
######################################################################################

$rol=$_POST['rol'];
$estado_convocatoria=$_POST['estado_convocatoria'];

//if($rol=='sp')
//   $rol='admin';
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$list=new ListadosController('matricula',1,$estado_convocatoria);
$tcentro=new Centro($conexion,$_POST['id_centro'],'ajax');
$tcentro->setNombre();
$matriculas=$list->getMatriculadosCentro($_POST['id_centro'],$conexion,'centro');
$tablaresumen=$tcentro->getResumen('centro','matricula',$log_mostrar_matriculados);
print($list->showMatriculados($matriculas,$rol,$_POST['id_centro']));

?>
