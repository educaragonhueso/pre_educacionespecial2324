<?php
session_start();
if(isset($_SESSION['dir_base']))
   $dir_base=$_SESSION['dir_base'];
else
   $dir_base='/datos/www/admespecial.aragon.es/public_admespecial/educacionespecial2223';
   
require_once $dir_base."/config/config_global.php";
require_once $dir_base.'/clases/core/Conectar.php';
require_once $dir_base.'/scripts/clases/LOGGER.php';
require_once $dir_base.'/app/parametros.php';
require_once $dir_base.'/controllers/ListadosController.php';
require_once $dir_base.'/controllers/SolicitudController.php';
require_once $dir_base.'/clases/models/Centro.php';
require_once $dir_base.'/clases/models/Solicitud.php';
require_once $dir_base.'/includes/form_solicitud.php';
$rol='anonimo';

######################################################################################
$log_imprimir_solicitud=new logWriter('log_imprimir_solicitud',DIR_LOGS);
######################################################################################

$conectar=new Conectar('config/config_database.php');
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);

$sc=new SolicitudController('alumno',$conexion,$formsol,$log_imprimir_solicitud);
$conexion=$sc->getConexion();
include('includes/head.php');
?>
<body>
   <div class="wrapper">
      <div id="content">
			<a href="<?php echo URL_BASE.'/'.CONVOCATORIA ?>"><button class="btn btn-outline-info" id="inicio" type="button">INICIO</button></a>
			<button class="btn btn-primary" id="bimprimir">IMPRIMIR</button>
	  	<span type="hidden" id="estado_convocatoria" name="estado_convocatoria" value="<?php echo $_SESSION['estado_convocatoria']; ?>"></span>
	  	<span type="hidden" id="rol" name="rol" value="<?php echo $_SESSION['rol']; ?>"></span> 
		<?php 
		   echo '<div class="row" style="padding-top:10px;padding-bottom:10px;">';
         echo '<div class="col text-right"><b>NUMERO IDENTIFICADOR DE SOLICITUD:</b><br> '.$sc->getIdSolicitud($_GET['id']).' </b></div>';
         echo '</div>';
         include 'includes/cabecera_impresion.php';
		?>
		<div class="row ">
		   <div id="headimp" style="width:100%">
            <?php echo $sc->imprimirSolicitud($_GET['id']);?>			
            <?php //$datos=$solicitud->getSolData($_GET['id'],'existente',1,'alumnos',$log_imprimir_solicitud);?>			
            <?php //$datoshermanos=$solicitud->getHermanos($_GET['id'],'existente',1,'alumnos',$log_imprimir_solicitud);?>			
         </div>
		</div>
		<?php// include 'includes/pie_impresion.php';?>
   </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<!-- jQuery Custom Scroller CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<style>
   @media print 
   {
      *{
      overflow: visible !important;
      }
   }
</style>

<script>
   $('#bimprimir').click(function()
   {
      document.body.style.zoom = "80%"; 
      $('#inicio').hide();
      $('#imprimir').hide();
      window.print();
      $('#inicio').show();
      $('#imprimir').show();
   });
</script>
</body>
</html>
