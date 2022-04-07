<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once 'UtilidadesAdmision.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$log_fase_provisional=new logWriter('log_fase_provisional',DIR_LOGS);

$tipo='provisional';

$ccentros=new CentrosController($conexion);
$centro=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,$ccentros,$centro);
$tsolicitud=new Solicitud($conexion);
//$ct=$tsolicitud->copiaTablaCentro(0,'alumnos_provisional');	

$acentros=array();
$centros=$ccentros->getAllCentros('todas','especial');
$ccentros=new CentrosController(0,$conexion);
if($tsolicitud->desmarcarValidados(1)==0)
  print("NO HAY VALIDADOS<br>");
while($row = $centros->fetch_assoc()) { $acentros[]=$row;}

foreach($acentros as $dcentro)
{
	$id_centro=$dcentro['id_centro'];
	if($id_centro<=1) continue;
	########################################################################################
	$log_fase_provisional->warning("INICIANDO GESTION CENTRO");
	$log_fase_provisional->warning(print_r($dcentro,true));
	########################################################################################
	$centrotmp=new Centro($conexion,$dcentro['id_centro'],'no',0);
	$centrotmp->setId($dcentro['id_centro']);
	$centrotmp->setNombre();
	$id_centro=$dcentro['id_centro'];
	$nsolicitudescentro=$centrotmp->getNumSolicitudes($dcentro['id_centro'],1);
	if($nsolicitudescentro==0) continue;
	$nombrecentro=$centrotmp->getNombre();
   
   ########################################################################################
	$log_fase_provisional->warning("NOMBRE: ".$nombrecentro.PHP_EOL);
	$log_fase_provisional->warning("FASE: ".$centrotmp->getFaseSorteo().PHP_EOL);
	$log_fase_provisional->warning("NSOLICITUDES: ".$nsolicitudescentro.PHP_EOL);
   ########################################################################################

	$dsorteo=$centrotmp->getVacantes('centro');
	$vacantes_ebo=$dsorteo[0]->vacantes;
	$vacantes_tva=$dsorteo[1]->vacantes;
	$log_fase_provisional->warning("VACANTES EBO: $vacantes_ebo".PHP_EOL);
	if($tsolicitud->setSolicitudesSorteo($id_centro,$nsolicitudescentro,$vacantes_ebo,$vacantes_tva)==0) 
		print("NO HAY VACANTES EN $id_centro");
}	
//copiamos todos los datos a tabla de provisionales	
$ct=$tsolicitud->copiaTablaCentro(1,'alumnos_provisional');	
$log_fase_provisional->warning("RESULTADO COPIAR TABLA $ct ");
/*
########################################################################################
########################################################################################
//Si hemos llegado al dia d elas provisionales o posterior, generamos la tabla de soliciutdes para los listados provisionales
$acentros=array();
$centros=$ccentros->getAllCentros('todas','especial');
		while($row = $centros->fetch_assoc()) { $acentros[]=$row;}
		foreach($acentros as $dcentro)
		{
		$centrotmp=new Centro($conexion,$dcentro['id_centro'],'no',0);
		$centrotmp->setId($dcentro['id_centro']);
		$centrotmp->setNombre();
		$id_centro=$dcentro['id_centro'];
		//if($id_centro!=50019408) continue;
		//if($id_centro!=50017369) continue;
		$fase=$centrotmp->getFaseSorteo();
		print(PHP_EOL."CENTRO: ".$dcentro['id_centro'].PHP_EOL." FASE: ".$fase.PHP_EOL);
		$nsolicitudes=$centrotmp->getNumSolicitudes($dcentro['id_centro']);
		$nombrecentro=$centrotmp->getNombre();
		print("NOMBRE: ".$centrotmp->getNombre().PHP_EOL);
		print("FASE: ".$centrotmp->getFaseSorteo().PHP_EOL);
		print("NSOLICITUDES: ".$nsolicitudes.PHP_EOL);
//PARA LOS CENTROS Q NO HAYAN HECHO SORTEO, OSE A Q ESTEN EN FASE <2, LO HACEMOS NOSOTROS
		$log_provisional_fase2->warning("ENTRANDO SORTEO TABLA CENTRO: $nombrecentro");
		
		if($fase<2) //centros q no hayan realizado el sorteo
		{
			$nsorteo=rand(1,$nsolicitudes);
			print("SORTEO REALIZADO $nsorteo");
			########################################################################################
			$log_provisional_fase2->warning("SORTEO REALIZADO");
			########################################################################################
			$modo='sorteo';
			if($list->asignarNumSol($id_centro)!=1){ print("Error asignando numero para el sorteo");exit();}
			$fase=$centrotmp->setFaseSorteo(1);
		
			if($centrotmp->setSorteo($nsorteo,$id_centro)==0) {print("ERROR SORTEO"); exit();}
			$centrotmp->setFaseSorteo(2);
		}	
		$nsorteo=$centrotmp->getNumeroSorteo();
		$dsorteo=$centrotmp->getVacantes($id_centro);
		$vacantes_ebo=$dsorteo[0]->vacantes;
		$vacantes_tva=$dsorteo[1]->vacantes;
		if($list->actualizaSolicitudesSorteo($id_centro,$nsorteo,$nsolicitudes,$vacantes_ebo,$vacantes_tva)==0) 
			print("NO HAY VACANTES<br>");
		$ct=$tsolicitud->copiaTablaCentro($id_centro,'alumnos_provisional_final');	
		}
*/

echo PHP_EOL."Copia tabla solicitudes provisionales realizada corectamente a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
?>
