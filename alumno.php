<?php
#VARIABLES DE SESION
session_start();
if(!$_SESSION) 
   header("location: login_activa.php");
date_default_timezone_set("Europe/Madrid");
setlocale(LC_TIME, "spanish");

if(isset($_SESSION['dir_base']))
   $dir_base=$_SESSION['dir_base'];
else
   $dir_base='./';

if(isset($_SESSION['rol'])) 
   $rol=$_SESSION['rol'];
else
   $rol='anonimo';

$id_alumno=$_SESSION['id_alumno'];
$id_centro=$_SESSION['id_centro'];
$estado_convocatoria=$_SESSION['estado_convocatoria'];
#CLASES
require_once $dir_base.'/controllers/SolicitudController.php';
require_once $dir_base.'/controllers/ListadosController.php';
require_once $dir_base.'/clases/core/Conectar.php';
require_once $dir_base.'/clases/models/Centro.php';
require_once $dir_base.'/clases/models/Solicitud.php';
require_once $dir_base.'/scripts/clases/LOGGER.php';
require_once $dir_base.'/scripts/ajax/form_alumnojs.php';
require_once $dir_base.'/scripts/ajax/form_alumno_doc_js.php';
require_once 'config/config_global.php';
require_once $dir_base.'/includes/form_solicitud.php';
#LOGS
$DIR_LOGS=$dir_base.'/logs/';
$log_listados_solicitudes=new logWriter('log_listados_solicitudes',$DIR_LOGS);

#VARIABLES
$diruploads=$dir_base.'/scripts/fetch/uploads/';
$log_editar_solicitud_token=new logWriter('log_editar_solicitud_token',DIR_LOGS);
$conectar=new Conectar();
$conexion=$conectar->conexion();
$tcentro=new Centro($conexion,$id_centro,'ajax');
$scontroller=new SolicitudController($rol,$conexion,$formsol,$log_editar_solicitud_token);
$solicitud=new Solicitud($conexion);
##CABECERA##
include('includes/head.php');

##MENUS SUPERIOR##
include('includes/menusuperior.php');

//si el usuario ya existe, recogemos el token, si lo hay
if(isset($_GET['token']))
{
   $token=$_GET['token'];
   $validacion=$solicitud->checkSolicitud($token);
   $n=count((array)$validacion);
   if($n>0)
   { 
      $_SESSION['rol']='alumno';
      $rol='alumno';
      $_SESSION['id_alumno']=$validacion->id_alumno;
      $_SESSION['id_centro']=$validacion->id_centro_destino;
      $msg_validacion="<h1>LA SOLICITUD ES VALIDADA</h1>";

	   $estado_sol=$solicitud->getEstadoSol($id_alumno);
	   if($estado_sol=='validada' or $estado_convocatoria>=20) $solo_lectura=1;
      else $solo_lectura=1;
      //obtenemos formulario con los datos
      $sform=$scontroller->showFormSolicitud($id_alumno,$id_centro,$rol,1,$solo_lectura,$estado_convocatoria,$conexion,$log_editar_solicitud_token);
      $botonimp='<a href="imprimirsolicitud.php?id='.$id_alumno.'" target="_blank"><input class="btn btn-primary imprimirsolicitud"  type="button" value="Vista Previa Impresion Documento"/></a>';

      $repjs="#loc_dfamiliar".$id_alumno;
      $script=str_replace('.localidad',$repjs,$script);

      $repjs="#nacionalidad".$id_alumno;
      $script=str_replace('.nacionalidad',$repjs,$script);

      #cargamos el codigo html para ver los documentos
      $dochtml=$solicitud->getDocHtml($id_alumno,$diruploads,$rol,'solicitud');

      if($estado_sol=='apta') print("SOLICITUDAPTA");

      ######################################################################################
      $log_editar_solicitud_token->warning("EDITANDO ALUMNO, DATOS DOCUMENTACION.$estado_sol");
      $log_editar_solicitud_token->warning(print_r($dochtml,true));
      ######################################################################################

      #SALIDA DE DATOS
      ######################################################################################
      $contenido='<div id="gallery">'.$dochtml.'</div>';
      $sform=str_replace('<div id="gallery"></div>',$contenido,$sform);

      ######################################################################################
      $log_editar_solicitud_token->warning("FORMULARIO ORIGINAL: ".$sform);
      $log_editar_solicitud_token->warning("CONTENIDO HTML.$contenido");
      $log_editar_solicitud_token->warning("FORMULARIO:".$sform);
      ######################################################################################
   }
      else
         header("location: login_activa.php");
      
print($sform);
print($script);
}
   else
      header("location: login_activa.php");
   
?>

