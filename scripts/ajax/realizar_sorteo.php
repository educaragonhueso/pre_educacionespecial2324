<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

########################################################################################
$log_sorteo=new logWriter('log_sorteo',DIR_LOGS);
########################################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

//VARIABLES
$menu_provisionales=''; //añadirlo si se ha realizado el sorteo
$dia_sorteo=0;
$modo='presorteo';
$id_centro=$_POST['id_centro'];
$rol=$_POST['rol'];
$provincia='todas';
$estado_convocatoria=$_POST['estado_convocatoria'];

$hoy = date("Y/m/d");
$list=new ListadosController('alumnos',$conexion,$estado_convocatoria);

$tcentro=new Centro($conexion,1,'ajax');
$tsolicitud=new Solicitud($conexion);
$fase_sorteo=$tcentro->getFaseSorteo();// FASE0: no realizado, 1, dia sorteo pero asignaciones no realizadas, 2 numero asignado, 3 sorteo realizado
$nsolicitudes=$tcentro->getNumSolicitudes(1,$estado_convocatoria);
$ccentros=new CentrosController($conexion,$estado_convocatoria);

$log_sorteo->warning("SORTEO");
$log_sorteo->warning(print_r($_POST,true));
//Para el caso de acceso del administrador o servicios provinciales
if($_POST['rol']=='admin')
{
	//si se ha pulsado en el boton de asignar numero de sorteo
	if(isset($_POST['asignar'])) 
	{
      $log_sorteo->warning("INICIOLOG: ASIGNACION ALEATORIO");
		########################################################################################
		########################################################################################
		if($list->asignarNumSol($log_sorteo)!=1){ print("Error asignando numero para el sorteo");exit();}
      $log_sorteo->warning("REALIZADO ASIGNACION NUMERO");
		//actualizamos el centro para marcar la fase del sorteo
		$tcentro->setFaseSorteo(2);

		print("ASIGNACION REALIZADA");
		$log_sorteo->warning("NUMERO ALEATORIO ASIGNADO");
	}
	//si se ha enviado el numero de sorteo
	if(isset($_POST['nsorteo']))
	{
      //datos de todos los centros
	   $vacantes_centros=$list->getResumenMatriculaCentros($rol,$id_centro,$modo,$log_sorteo,$estado_convocatoria,$provincia); 
		########################################################################################
		$log_sorteo->warning("REALIZANDO SORTEO, DATOS VACANTES:");
		$log_sorteo->warning(print_r($vacantes_centros,true));
		########################################################################################
		$modo='sorteo';
		$nsorteo=$_POST['nsorteo'];
		//Actualizamos el numero de sorteo para el centro
		if($tcentro->setSorteo($nsorteo,1)==0) {print("ERROR SORTEO"); exit();}
		
		$vacantes_ebo=$vacantes_centros['ebo'];
		$vacantes_tva=$vacantes_centros['tva'];
		$vacantes_dos=$vacantes_centros['dos'];
		
		//asignamos numero de orden a las solicitudes segun el numero de sorteo	
		if($tsolicitud->setNordenSorteo($id_centro,$nsorteo,$nsolicitudes,$vacantes_ebo,$vacantes_tva,$log_sorteo)==0) 
			print("NO HAY VACANTES<br>");
		$tcentro->setFaseSorteo(3);

		//para cada centro calculamos solicitudes admitidas
		//Si hemos llegado al dia d elas provisionales o posterior, generamos la tabla de soliciutdes para los listados provisionales
		$acentros=array();
		$centros=$ccentros->getAllCentros('todas','especial');
		$ccentros=new CentrosController(0,$conexion);
		while($row = $centros->fetch_assoc()) { $acentros[]=$row;}
		
		foreach($acentros as $dcentro)
		{
			$id_centro=$dcentro['id_centro'];
			$centrotmp=new Centro($conexion,$dcentro['id_centro'],'no',0);
			$centrotmp->setId($dcentro['id_centro']);
			$centrotmp->setNombre();
			$id_centro=$dcentro['id_centro'];
			$nsolicitudescentro=$centrotmp->getNumSolicitudes($dcentro['id_centro'],1);
			if($nsolicitudescentro==0) continue;
			$nombrecentro=$centrotmp->getNombre();

			$log_sorteo->warning("NOMBRE: ".$nombrecentro.PHP_EOL);
			$log_sorteo->warning("FASE: ".$centrotmp->getFaseSorteo().PHP_EOL);
			$log_sorteo->warning("NSOLICITUDES: ".$nsolicitudescentro.PHP_EOL);
			$log_sorteo->warning("ENTRANDO SORTEO TABLA CENTRO: $nombrecentro");
		
			if($centrotmp->setSorteo($nsorteo,$id_centro)==0) {print("ERROR SORTEO"); exit();}
			if(!$centrotmp->setFaseSorteo(3))
			{
			   $log_sorteo->warning("ERROR ACT FASE: $nombrecentro");
			   return 0;
			}
			$dsorteo=$centrotmp->getVacantesCentro($log_sorteo);
			$vacantes_ebo=$dsorteo['ebo'];
			$vacantes_tva=$dsorteo['tva'];
		   $log_sorteo->warning(print_r($dsorteo,true));
         
			if($tsolicitud->setSolicitudesSorteo($id_centro,$nsolicitudescentro,$vacantes_ebo,$vacantes_tva,'provisional',$log_sorteo)==0) 
		      $log_sorteo->warning("NO HAY VACANTES EN EL CENTRO ");
		}	
		//copiamos todos los datos a tabla de provisionales	
		$ct=$tsolicitud->copiaTablaCentro(1,'alumnos_provisional',$log_sorteo);	
      //cambiamos los valroes de los campos validar proximidad domicilio,  validar discapacidad y validad familia
		//$updatebaremo=$tsolicitud->actualizaBaremoProvisional(1,'alumnos_provisional',$log_sorteo);	
		$log_sorteo->warning("RESULTADO COPIAR TABLA $ct ");
	}
}
else//accedemos como centro
{
print("NO ES UN USUARIO CON PERMISOS");
}
?>
