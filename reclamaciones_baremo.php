<?php
session_start();
print_r($_SESSION);
if(isset($_SESSION['convocatoria'])) 
   $convocatoria=$_SESSION['convocatoria'];
else
   $convocatoria='educacionespecial2223';

require_once "config/config_global.php";
require_once DIR_CORE.'/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_CLASES.'LOGGER.php';
$log_recbaremo=new logWriter('log_recbaremo',DIR_LOGS);

$conectar=new Conectar();
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);
$id_alumno=$_SESSION['id_alumno'];

if(isset($_GET['id_centro']) and isset($_GET['token']))
{
   $token=$_GET['token'];
   $tokencentro=$_GET['tokencentro'];
   $id_alumno=$solicitud->getIdFromToken($token,$log_recbaremo);
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
   $id_alumno=$solicitud->getIdFromToken($token,$log_recbaremo);
   if($id_alumno>0) 
   {
      $_SESSION['usuario_autenticado']=1;
      $_SESSION['rol']='alumno';
      $rol='alumno';
   }
   else header('/');
}

if($_SESSION['mantenimiento']=='SI') print_r($_SESSION);

include('includes/head.php');
include('includes/menusuperior.php');
$motivoreclamacion=$solicitud->getReclamacion($id_alumno,'baremo');
$dochtml=$solicitud->getDocHtml($id_alumno,'scripts/fetch/reclamacionesbaremo/','alumno','baremo');
?>
<div class="container ">
   <h1>Formulario de reclamaciones del baremo</h1> 
      <?php 
         include('includes/form_reclamacionbaremo.php');
	      $form_reclamaciones=str_replace("collapse","".$motivoreclamacion."'",$form_reclamaciones);
         $form_reclamaciones=str_replace("value","value='".$motivoreclamacion."'",$form_reclamaciones);
         if($rol!='alumno')
         {
             $form_reclamaciones=str_replace("input","input disabled",$form_reclamaciones);
             $form_reclamaciones=preg_replace("/<button.*<\/button>/","",$form_reclamaciones);
         }
         $form_reclamaciones=str_replace("</textarea>",$motivoreclamacion."</textarea>",$form_reclamaciones);
         $fr=str_replace("+idalumno","$id_alumno",$form_reclamaciones);
      
         print($fr); 
         if($dochtml!='')
         {
            print("<hr>");
            print("<h4>DOCUMENTOS GUARDADOS: </h4>");
            print_r($dochtml);
            /*
            $origen='<div id="gallery"></div>';
            $destino='<div id="gallery">'.$dochtml.'</div>';
	         $fr=str_replace($origen,$destino,$fr);
             $fr=preg_replace("/<button .*Retirar documento<\/button>/","",$fr);
            */
         }
      ?>
</div>
<script>

$('body').on('click', '.breclamaciones', function() {
   vid_alumno=$(this).attr("id");
   vid=vid_alumno.replace('reclamacion','');
   console.log("GUARDANDO RECLAMACION BAREMO, ID ALUMNO: "+vid);
   vmotivo=$("#motivo_reclamacion"+vid).val();
	$.ajax({
	  method: "POST",
	  data: {motivo:vmotivo,id_alumno:vid},
	  url:"./scripts/ajax/guardar_reclamacion.php",
	      success: function(data) {
            console.log(data);
            if(data.indexOf('1')!=-1)
				$.alert({
					title: "RECLAMACIÓN GUARDADA",
               content: "Continuar"
					});
		},error: function (request, status, error) {
        alert(error);
    }
	});
});
</script>
