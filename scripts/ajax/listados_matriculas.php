<?php
######################
# script para modificar alumnos matriculados
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Alumno.php';

######################################################################################
$log_listados_matricula=new logWriter('log_listados_matricula',DIR_LOGS);
######################################################################################

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$rol=$_POST['rol']; 
$id_centro=$_POST['id_centro']; 
$provincia=$_POST['provincia']; 

if($rol=='admin' or $rol=='sp') 
{
   //sp y admin son equivalentes en este caso
  // $rol='admin';
  // $provincia='todas';
   $log_listados_matricula->warning("OBTENIENDO DATOS DE MATRICULA");
	$cencont=new CentrosController($conexion);
	print($cencont->showTablas($rol,$id_centro,'matricula',$provincia,'especial',$log_listados_matricula));
}
else
{
	$list=new ListadosController('matricula',$conexion);
	$tcentro=new Centro($conexion,$_POST['id_centro'],'ajax');
	$tcentro->setNombre();
	$nombre_centro=$tcentro->getNombre();
	$matriculas=$list->getMatriculadosCentro($_POST['id_centro'],$conexion,$rol);
	$tablaresumen=$tcentro->getResumen('centro','matricula',$log_listados_matricula);
   print($list->showTablaResumenMatriculaEspecial($tablaresumen,$nombre_centro,'centro','si',$id_centro));
	print($list->showMatriculados($matriculas,'centro',$_POST['id_centro']));
}
?>
