<?php
######################
# script para listar solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE."/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
//require_once DIR_BASE.'models/Solicitud.php';

########################################################################################
$log_listados_solicitudes=new logWriter('log_listados_solicitudes',DIR_LOGS);
########################################################################################

//VARIABLES
########################################################################################
$menu_provisionales=''; //añadirlo si se ha realizado el sorteo
$modo='presorteo';
$id_centro=$_POST['id_centro'];
$rol=$_POST['rol'];
$estado_convocatoria=$_POST['estado_convocatoria'];

$hoy = date("Y/m/d");
$form_nuevasolicitud='<div class="input-group-append" id="cab_fnuevasolicitud"><button class="btn btn-outline-info" id="nuevasolicitud" type="button">Nueva solicitud</button></div>';
$filtro_solicitudes='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno o centro"><small id="emailHelp" class="form-text text-muted"></small>';
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$list=new ListadosController('alumnos',$conexion,$estado_convocatoria);

$solicitud=new Solicitud($conexion);
$tcentro=new Centro($conexion,$id_centro,'ajax');

$provincia='todas';
if(isset($_POST['provincia']))
	$provincia=$_POST['provincia'];

$tsolicitud=new Solicitud($conexion);
$tcentro->setNombre();
$nombre_centro=$tcentro->getNombre();
//Segun el estado de la convocatoria deshabilitamos el sorteo
if($estado_convocatoria==DIA_SORTEO) $disabled='';
else $disabled='disabled';
########################################################################################
$log_listados_solicitudes->warning("OBTENIENDO SOLICITUDES CON ROL: ".$_POST['rol']);
########################################################################################
//Para el caso de acceso del administrador o servicios provinciales
if($rol=='admin' or $rol=='sp')
{
   ########################################################################################
  if($rol=='admin')
  { 
      $nsolicitudes=$solicitud->getNumSolicitudes($id_centro,$estado_convocatoria);
      $form_sorteo_parcial='<div id="form_sorteo_parcial" class="input-group mb-3">
         <div class="input-group-append">
         </div>
         <div class="input-group-append">
            <button class="btn" type="submit" id="boton_realizar_sorteo">Realizar sorteo</button>
         </div>
         <input type="text" id="num_sorteo" name="num_sorteo" value="" style="width:400px;" placeholder="NUMERO OBTENIDO, DEBE ESTAR ENTRE 1 y '.$nsolicitudes.'" '.$disabled.'>
         <input type="hidden" id="num_solicitudes" name="num_solicitudes" value="'.$nsolicitudes.'" placeholder="NUMERO OBTENIDO" '.$disabled.'>
      </div>';
      $form_sorteo_completo='<div id="form_sorteo" class="input-group mb-3">
         <div class="input-group-append">
            <button class="btn btn-success" type="submit" id="boton_asignar_numero">Asignar numero</button>
         </div>
         <div class="input-group-append">
            <button class="btn btn-success" type="submit" id="boton_realizar_sorteo">Realizar sorteo</button>
         </div>
         <input type="text" id="num_sorteo" name="num_sorteo" value="" placeholder="NUMERO OBTENIDO">
         <input type="hidden" id="num_solicitudes" name="num_solicitudes" value="'.$nsolicitudes.'" placeholder="NUMERO OBTENIDO">
      </div>';
	   print($form_sorteo_completo);
   if($estado_convocatoria==ESTADO_INICIO_FASE_SORTEO)
	   print($form_sorteo_completo);
	}
   if($estado_convocatoria>=ESTADO_INSCRIPCION) print($form_nuevasolicitud);
   $centros=$list->getCentrosIds($rol,$provincia,$log_listados_solicitudes);	
      $log_listados_solicitudes->warning(print_r($centros,true));
	foreach($centros as $centro)
	{
      ########################################################################################
      $log_listados_solicitudes->warning("OBTENIENDO NOMBRE CENTRO para".$_POST['rol']);
      $log_listados_solicitudes->warning(print_r($centro,true));
      ########################################################################################
      
      $tcentro->setId($centro->id_centro);
      $tcentro->setNombre();
      $nombre_centro=$tcentro->getNombre();
      $tablaresumen=$tcentro->getResumen('centro','alumnos',$log_listados_solicitudes);
      
      ########################################################################################
      $log_listados_solicitudes->warning("fichero listados solicitudes: OBTENIENDO SOLICITUDES COMO: ".$_POST['rol']);
      $log_listados_solicitudes->warning("tabla resumen: ".$nombre_centro);
      $log_listados_solicitudes->warning(print_r($tablaresumen,true));
      ########################################################################################
      
      print($list->showTablaResumenSolicitudes($tablaresumen,$nombre_centro,$centro->id_centro));
	}
}
else//accedemos como centro
{
	//SECCION OBTENCION DATOS
	//obtenemos solicitudes normales
	$solicitudes=$list->getSolicitudes($id_centro,'normal','normal',$solicitud,$log_listados_solicitudes,0,$rol,$provincia); 

	$tablaresumen=$tcentro->getResumen($_POST['rol'],'alumnos',$log_listados_solicitudes);
	$nombre_centro=$tcentro->getNombre();
	//SECCION MOSTAR DATOS
	#Mostramos formulario para el sorteo si es el dia correcto
   /*
	if($estado_convocatoria<ESTADO_INSCRIPCION)
	{
      print($list->showTablaResumenSolicitudes($tablaresumen,$nombre_centro,$id_centro));
      if($estado_convocatoria<=ESTADO_INSCRIPCION) print($form_nuevasolicitud);
      print('<br>');
      print($filtro_solicitudes);
      print($list->showSolicitudes($solicitudes,'centro'));
	}
	elseif($estado_convocatoria>=ESTADO_INSCRIPCION)
	{
      //print($menu_provisionales);
      if($_POST['id_centro']>='1') print($list->showTablaResumenSolicitudes($tablaresumen,$nombre_centro,$id_centro));
      print($form_nuevasolicitud);
      print($list->showSolicitudes($solicitudes,$_POST['rol']));
	}
   */
      print($list->showTablaResumenSolicitudes($tablaresumen,$nombre_centro,$id_centro));
      print($form_nuevasolicitud);
      print($list->showSolicitudes($solicitudes,$_POST['rol']));
}


?>
