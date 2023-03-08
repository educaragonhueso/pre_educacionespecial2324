<?php
//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/scripts/ajax/form_alumnojs.php';
require_once DIR_BASE.'/scripts/ajax/form_alumno_doc_js.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_BASE.'/includes/form_reclamacionbaremo.php';
require_once DIR_BASE.'/includes/form_reclamacionprovisional.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
######################################################################################
$consulta=$_POST['modo'];
$id_alumno=$_POST['id_alumno'];
$rol=$_POST['rol'];
$id_centro=$_POST['id_centro'];
$estado_convocatoria=$_POST['estado_convocatoria'];

$estado_sol='irregular';
$solo_lectura=0;

$diruploads=DIR_BASE.'/scripts/fetch/uploads/';
$fconf=DIR_BASE.'/config/config_database.php';

$log_editar_solicitud=new logWriter('log_editar_solicitud',DIR_LOGS);

$conectar=new Conectar($fconf);
$conexion=$conectar->conexion();

$solicitud=new Solicitud($conexion);

require_once DIR_BASE.'/includes/form_solicitud.php';


######################################################################################
$log_editar_solicitud->warning("INICIOLOG EDITAR ALUMNO");
######################################################################################
$scontroller=new SolicitudController($rol,$conexion,$formsol,$estado_convocatoria,$log_editar_solicitud);
$tsol=new Solicitud($conexion);

if($rol=='alumno')
{
	$fase_sol=$tsol->getEstadoSol($id_alumno);
	if($fase_sol=='validada' or $estado_convocatoria>=ESTADO_FININSCRIPCION) $solo_lectura=1;
}
//obtenemos formulario con los datos
$sform=$scontroller->showFormSolicitud($id_alumno,$id_centro,$rol,1,$solo_lectura,$conexion,$log_editar_solicitud,0);
$botonimp='<a href="imprimirsolicitud.php?id='.$id_alumno.'" target="_blank"><input class="btn btn-primary imprimirsolicitud"  type="button" value="Vista Previa Impresion Documento"/></a>';

//Si el id es cero obentemos el nuevo id
if($id_alumno==0) $id_alumno=$scontroller->lastid+1;

$repjs="#loc_dfamiliar".$id_alumno;
$script=str_replace('.localidad',$repjs,$script);

$repjs="#nacionalidad".$id_alumno;
$script=str_replace('.nacionalidad',$repjs,$script);

#cargamos el codigo html para ver los documentos
$dochtml=$tsol->getDocHtml($id_alumno,$diruploads,$rol,'solicitud');

if($estado_sol=='apta') print("SOLICITUDAPTA");

#SALIDA DE DATOS
######################################################################################
$contenido='<div id="gallery">'.$dochtml.'</div>';
$sform=str_replace('<div id="gallery"></div>',$contenido,$sform);

if($rol!='alumno' and $estado_convocatoria==21)
{
   $cabecerareclamaciones='<div id="filarecbaremo'.$id_alumno.'" type="button" class="btn btn-primary formrecbaremo" data-toggle="collapse" data-target="#recbaremo'.$id_alumno.'">RECLAMACIONES BAREMO<span> <i class="fas fa-angle-down"></i></span></div>';
   $motivoreclamacion=$solicitud->getReclamacion($id_alumno,'baremo');
   $dochtml=$solicitud->getDocHtml($id_alumno,'../fetch/reclamaciones/','alumno','baremo');
	$form_reclamaciones=str_replace("value","value='".$motivoreclamacion."'",$form_reclamaciones);
	$fr=str_replace("+idalumno","$id_alumno",$form_reclamaciones);
       
   $origen='<div id="gallery"></div>';
   $destino='<div id="gallery">'.$dochtml.'</div>';
   $fr=str_replace($origen,$destino,$fr);
   $fr=str_replace("Guardar datos","",$fr);
   print("<div id='datosalumno".$id_alumno."'>".$cabecerareclamaciones.$fr);
   print($sform);
   print("</div>");
}
else if($rol!='alumno' and $estado_convocatoria==31)
{
   $cabecerareclamaciones='<div id="filarecprovisional'.$id_alumno.'" type="button" class="btn btn-primary formrecprovisional" data-toggle="collapse" data-target="#recprovisional'.$id_alumno.'">RECLAMACIONES LISTADO PROVISIONAL<span> <i class="fas fa-angle-down"></i></span></div>';
   $motivoreclamacion=$solicitud->getReclamacion($id_alumno,'provisional');
   $dochtml=$solicitud->getDocHtml($id_alumno,'../fetch/reclamacionesprovisional/','alumno','provisional');
	$form_reclamaciones=str_replace("cont></textarea>"," cont>$motivoreclamacion</textarea>",$form_reclamaciones);
	$fr=str_replace("+idalumno","$id_alumno",$form_reclamaciones);
       
   $origen='<div id="gallery"></div>';
   $destino='<div id="gallery">'.$dochtml.'</div>';
   $fr=str_replace($origen,$destino,$fr);
   $fr=str_replace("Guardar datos","",$fr);
   print("<div id='datosalumno".$id_alumno."'>".$cabecerareclamaciones.$fr);
   print($sform);
   print("</div>");
}
else
   print($sform);
if($rol=='centro')
{
   print($script);
   print($script_doc_alumno);
}
else
{
   print($script);
   //print($script_doc_alumno);
}
print("##".$id_alumno);
?>
