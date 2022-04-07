<?php
session_start();
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_CORE.'/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

$conectar=new Conectar();
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);
include('includes/head_reclamaciones.php');
include('includes/menusuperior.php');
if($_SESSION['mantenimiento']=='SI') print_r($_SESSION);

$id_alumno=$_SESSION['id_alumno'];
$motivoreclamacion=$solicitud->getReclamacion($id_alumno,'provisional');
$dochtml=$solicitud->getDocHtml($id_alumno,'scripts/fetch/reclamacionesprovisional/','alumno','provisional');
?>
<div class="container ">
   <h1>Formulario de reclamaciones del baremo</h1> 
      <?php 
         include('includes/form_reclamacionprovisional.php');
	      $form_reclamaciones=str_replace("collapse","'".$motivoreclamacion."'",$form_reclamaciones);
         if($dochtml!='')
         {
	         $form_reclamaciones=str_replace("cont></textarea>"," cont>$motivoreclamacion</textarea>",$form_reclamaciones);
	         $fr=str_replace("+idalumno","$id_alumno",$form_reclamaciones);
       
            $origen='<div id="gallery"></div>';
            $destino='<div id="gallery">'.$dochtml.'</div>';
	         $fr=str_replace($origen,$destino,$fr);
            print($fr);
         }
         else
         {
	         $fr=str_replace("+idalumno","$id_alumno",$form_reclamaciones);
            print($fr);
         }
      ?>
</div>
<script>

$('body').on('click', '.preclamaciones', function() {
            console.log("guardando rec prov");
   vid_alumno=$(this).attr("id");
   vid=vid_alumno.replace('reclamacion','');
   vmotivo=$("#motivo_reclamacion"+vid).val();
	$.ajax({
	  method: "POST",
	  data: {motivo:vmotivo,id_alumno:vid},
	  url:"./scripts/ajax/guardar_reclamacionprovisional.php",
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
