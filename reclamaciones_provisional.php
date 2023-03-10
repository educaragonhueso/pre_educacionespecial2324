<?php
session_start();
if(isset($_SESSION['convocatoria'])) 
   $convocatoria=$_SESSION['convocatoria'];
else
   $convocatoria='educacionespecial2223';

require_once "config/config_global.php";
#require_once "includes/sesion.php";
$estado_convocatoria=$_SESSION['estado_convocatoria'];
require_once DIR_CORE.'/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_CLASES.'LOGGER.php';
$log_recprovisional=new logWriter('log_recprovisional',DIR_LOGS);

$conectar=new Conectar();
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);
$id_alumno=$_SESSION['id_alumno'];

if(isset($_GET['id_centro']) and isset($_GET['token']))
{
   $token=$_GET['token'];
   #$tokencentro=$_GET['tokencentro'];
   $id_alumno=$solicitud->getIdFromToken($token,$log_recprovisional);
   $id_centro=$_GET['id_centro'];
   if($id_alumno>0 and $id_centro>0) 
   {
      $_SESSION['usuario_autenticado']=1;
      $_SESSION['rol']='centro';
      $rol='centro';
   }
   else header('/');
}
else if(isset($_GET['token']))
{
   $token=$_GET['token'];
   $id_alumno=$solicitud->getIdFromToken($token,$log_recprovisional);
   if($id_alumno>0) 
   {
      $_SESSION['usuario_autenticado']=1;
      $_SESSION['rol']='alumno';
      $rol='alumno';
   }
   else header('/');
}
else if($_SESSION['token'])
{
   $rol=$_SESSION['rol'];
   $id_alumno=$_SESSION['id_alumno'];
}
if($_SESSION['mantenimiento']=='SI') print_r($_SESSION);

include('includes/head.php');
include('includes/head_reclamaciones.php');
include('includes/menusuperior.php');
$motivoreclamacion=$solicitud->getReclamacion($id_alumno,'provisional');
$dochtml=$solicitud->getDocHtml($id_alumno,'scripts/fetch/reclamacionesprovisional/','alumno','provisional');
?>
<div class="container ">
   <h1>Formulario de reclamaciones listado provisional</h1> 
      <?php 
         include('includes/form_reclamacionprovisional.php');
	      $form_reclamaciones=str_replace("collapse","".$motivoreclamacion."'",$form_reclamaciones);
         $form_reclamaciones=str_replace("inputtype='tarea' value=''","inputtype='tarea' value='".$motivoreclamacion."'",$form_reclamaciones);
         if($rol!='alumno' or $estado_convocatoria>=ESTADO_DEFINITIVOS)
         {
             $form_reclamaciones=str_replace("input type=\"file\"","input type=\"file\" disabled ",$form_reclamaciones);
             $form_reclamaciones=str_replace("<textarea","<textarea disabled ",$form_reclamaciones);
             $form_reclamaciones=preg_replace("/<button.*<\/button>/","",$form_reclamaciones);
         }
         $form_reclamaciones=str_replace("</textarea>",$motivoreclamacion."</textarea>",$form_reclamaciones);
         $fr=str_replace("+idalumno","$id_alumno",$form_reclamaciones);
         print($fr); 
      ?>
</div>
<script>

$('body').on('click', '.breclamaciones', function() {
   vid_alumno=$(this).attr("id");
   vid=vid_alumno.replace('reclamacion','');
   console.log("GUARDANDO RECLAMACION PROVISIONAL, ID ALUMNO: "+vid);
   vmotivo=$("#motivo_reclamacion"+vid).val();
	$.ajax({
	  method: "POST",
	  data: {motivo:vmotivo,id_alumno:vid,tiporec:'provisional'},
	  url:"./scripts/ajax/guardar_reclamacion.php",
	      success: function(data) {
            console.log(data);
            if(data.indexOf('1')!=-1)
				$.alert({
					title: "RECLAMACI??N GUARDADA",
               content: "Recibir??s un correo confirmatorio"
					});
		},error: function (request, status, error) {
        alert(error);
    }
	});
});
</script>
