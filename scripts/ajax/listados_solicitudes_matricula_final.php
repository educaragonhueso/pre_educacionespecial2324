<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_APP.'/parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

######################################################################################
$log_listados_matricula_final=new logWriter('log_listados_matricula_final',DIR_LOGS);
$log_listados_matricula_final->warning("OBTENIENDO DATOS DEFINITIVOS POST:");
$log_listados_matricula_final->warning(print_r($_POST,true));
######################################################################################
//VARIABLES
$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/definitivos/';
$id_centro=$_POST['id_centro'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado
$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$list=new ListadosController('alumnos',$conexion,$estado_convocatoria);
$tcentro=new Centro($conexion,$_POST['id_centro'],'ajax');
$ccentros=new CentrosController($conexion,$estado_convocatoria);
$tcentro->setNombre();
$id_alumno=$_POST['id_alumno'];
$nombre_centro=$tcentro->getNombreAdjudicado($id_alumno);
$tsolicitud=new Solicitud($conexion);
$dvacantes=$tcentro->getVacantes($id_centro,$log_listados_matricula_final);
$vacantes_ebo=$dvacantes[0]->vacantes;
$vacantes_tva=$dvacantes[1]->vacantes;

$titulo_listado="Listados definitivos";
$tipo_listado="Listados definitivos";

$rol=$_POST['rol'];
if($rol=='admin')
   $provincia='todas';
else
   $provincia=substr($rol,2);

//La convocatoria esta en definitivo según el dia programado
//si la convocatoria esta en definitivo, entramos una vez para copiar la tabla con los datos del centro
//si estamos en el periodo de provisionales actualizamos tablas de definitivos

$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;

######################################################################################
$log_listados_matricula_final->warning("OBTENIENDO LISTADOS MATRICULA FINAL, CENTRO: ".$id_centro);
######################################################################################

//actualizamos solicitudes para tener en cuenta las que hayan cambiado
//Esto solo puede hacerse en el momento q finalice el plazo de provisionales!!!!!!!!
//$solicitudes=$solicitud->genSolDefinitivas($id_centro,$vacantes_ebo,$vacantes_tva,2); 
//mostramos las solitudes completas sin incluir borrador
$solicitudes=$list->getSolicitudes($id_centro,'matriculafinal',$subtipo_listado,$tsolicitud,$log_listados_matricula_final,$id_alumno,$rol); 
$subtipo='ADMITIDOS MATRÍCULA FINAL';

print("<button type='button' class='btn btn-info' onclick='window.open(\"".DIR_PROV_WEB.$subtipo_listado.".pdf\",\"_blank\");'>Descarga listado</button>");
print($list->showFiltrosTipo());
#print($filtro_datos);
print("<div style='text-align:center'><h1>LISTADO ".strtoupper($tipo_listado)." ".strtoupper($subtipo)."</h1></div>");
print("<div style='text-align:center'><h1>CENTRO : ".strtoupper($nombre_centro)."</h1></div>");
print($list->showListado($solicitudes,$_POST['rol'],$$cabecera,$$camposdatos,'matriculafinal'));

?>
