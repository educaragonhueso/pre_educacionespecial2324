<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_CLASES.'LOGGER.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_APP.'/parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

######################################################################################
$log_listados_definitivos=new logWriter('log_listados_definitivos',DIR_LOGS);
$log_listados_definitivos->warning("OBTENIENDO DATOS DEFINITIVOS POST:");
$log_listados_definitivos->warning(print_r($_POST,true));
######################################################################################
//VARIABLES
$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/definitivos/';
$id_centro=$_POST['id_centro'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado
$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$list=new ListadosController('alumnos',$conexion);
$tcentro=new Centro($conexion,$_POST['id_centro'],'ajax');
$ccentros=new CentrosController($conexion);
$tcentro->setNombre();
$tsolicitud=new Solicitud($conexion);
$dvacantes=$tcentro->getVacantes($id_centro,$log_listados_definitivos);
//$vacantes_ebo=$dvacantes[0]->vacantes;
//$vacantes_tva=$dvacantes[1]->vacantes;

$titulo_listado="Listados definitivos";
$tipo_listado="Listados definitivos";

$rol=$_POST['rol'];
$provincia=$_POST['provincia'];

$log_listados_definitivos->warning("ACTUALIZANDO DEFINITIVOS, $estado_convocatoria");
//La convocatoria esta en definitivo según el dia programado
//si la convocatoria esta en definitivo, entramos una vez para copiar la tabla con los datos del centro
//si estamos en el periodo de provisionales actualizamos tablas de definitivos
if($estado_convocatoria>=ESTADO_PUBLICACION_PROVISIONAL and $estado_convocatoria<ESTADO_DEFINITIVOS)
{
	if($_POST['rol']=='centro')
	{
      $nsolicitudes=$tcentro->getNumSolicitudes($id_centro);
      $nsorteo=$tcentro->getNumeroSorteo();
      //$dsorteo=$tcentro->getVacantes('centro');
		$dsorteo=$tcentr->getVacantesCentro($log_listados_provisionales);
		$vacantes_ebo=$dsorteo['ebo'];
		$vacantes_tva=$dsorteo['tva'];
      
      //$vacantes_ebo=$dsorteo[0]->vacantes;
      //$vacantes_tva=$dsorteo[1]->vacantes;

      if($tsolicitud->setSolicitudesSorteo($id_centro,$nsolicitudes,$vacantes_ebo,$vacantes_tva,$log_listados_definitivos)==0) 
		########################################################################################
		$log_listados_definitivos->warning("NO HAY VACANTES CENTRO: ".$id_centro);
		########################################################################################
      $ct=$tsolicitud->copiaTablaCentro($id_centro,'alumnos_definitiva',$log_listados_definitivos);	
   }
	elseif($_POST['rol']=='admin' or $_POST['rol']=='sp')
	{
     
		//para cada centro calculamos solicitudes admitidas
		//Si hemos llegado al dia d elas provisionales o posterior, generamos la tabla de soliciutdes para los listados provisionales
		$acentros=array();
		$centros=$ccentros->getAllCentros($provincia,'especial');
		while($row = $centros->fetch_assoc()) { $acentros[]=$row;}
		
		foreach($acentros as $dcentro)
		{
			$id_centrotmp=$dcentro['id_centro'];
			########################################################################################
			$log_listados_definitivos->warning("INICIANDO GESTION CENTRO PARA DEFINITIVOS CENTRO: ".$id_centrotmp);
			########################################################################################
		
      	$centrotmp=new Centro($conexion,$id_centrotmp,'no',0);
			$centrotmp->setId($id_centrotmp);
			$centrotmp->setNombre();
			$nsolicitudescentro=$centrotmp->getNumSolicitudes($id_centrotmp,1);
			if($nsolicitudescentro==0) continue;
			$nombrecentro=$centrotmp->getNombre();
		
         $dsorteo=$centrotmp->getVacantesCentro($log_listados_definitivos);
         $vacantes_ebo=$dsorteo['ebo'];
         $vacantes_tva=$dsorteo['tva'];
      
			if($tsolicitud->setSolicitudesSorteo($id_centrotmp,$nsolicitudescentro,$vacantes_ebo,$vacantes_tva,$log_listados_definitivos)==0) 
			   $log_listados_definitivos->warning("NO HAY VACANTES CENTRO: ".$id_centrotmp);
		}
		//copiamos todos los datos a tabla de provisionales	
      $log_listados_definitivos->warning("COPIANDO TABLA DEFINITIVOS");
		$ct=$tsolicitud->copiaTablaCentro(1,'alumnos_definitiva',$log_listados_definitivos);	
   }
//actualizamos la tabla para poner los valores del baremo a mínimos, por ejemplo si validar está puesto a 0 tb el campo de validación sera el minimo
$updatebaremo=$tsolicitud->resetBaremoDefinitivo();	
}

$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;

######################################################################################
$log_listados_definitivos->warning("OBTENIENDO LISTADOS DEFINITIVOSS, CENTRO: ".$id_centro);
######################################################################################

//actualizamos solicitudes para tener en cuenta las que hayan cambiado
//Esto solo puede hacerse en el momento q finalice el plazo de provisionales!!!!!!!!
//$solicitudes=$solicitud->genSolDefinitivas($id_centro,$vacantes_ebo,$vacantes_tva,2); 
//mostramos las solitudes completas sin incluir borrador
$solicitudes=$list->getSolicitudes($id_centro,$estado_convocatoria,'definitivos',$subtipo_listado,$tsolicitud,$log_listados_definitivos,0,$rol,$provincia); 

if($_POST['pdf']==1)
{
   $formato=''; //formato listado en el pdf
   $anchuracelda=10;
   $primera_celda=20;
	$datos=array();
	$i=0;
	//extraemos los campos de datos q nos interesan
	foreach($solicitudes as $sol)
	{
		$datos[$i] = new stdClass;
		foreach($$camposdatos as $d)
		{
			$datos[$i]->$d=$sol->$d;
		}
	$i++;
	}
	$pdf = new PDF();
	$cab=$$cabecera;
	$pdf->SetFont('Helvetica','',8);
	$pdf->AddPage('L','',0,$titulo_listado);
	$pdf->BasicTable($cab,$datos,0,$anchuracelda,$formato,$primera_celda);
	$pdf->Ln(20);
	$pdf->SetFont('Arial','I',8);
	  // Page number
	$pdf->Cell(30);
	$pdf->Cell(40,10,'SELLO CENTRO',1,0,'C');
	$pdf->Cell(140,10,'En ______________________ a ____de________ de 2021',0,0,'C');
	$pdf->Cell(0,10,'Firmado:',0,0);
	$pdf->Ln();
	$pdf->Cell(220,10,'El Director/a',0,0,'R');
	$pdf->AddPage();
	$pdf->Output(DIR_PROV.$subtipo_listado.'.pdf','F');
}

if($subtipo_listado=='admitidos_def') $subtipo='ADMITIDOS DEFINITIVO';
if($subtipo_listado=='noadmitidos_def') $subtipo='NO ADMITIDOS DEFINITIVO';
if($subtipo_listado=='excluidos_def') $subtipo='EXCLUIDOS DEFINITIVO';

print("<button type='button' class='btn btn-info' onclick='window.open(\"".DIR_PROV_WEB.$subtipo_listado.".pdf\",\"_blank\");'>Descarga listado</button>");
#print($list->showFiltrosTipo());
#print($filtro_datos);
print("<div style='text-align:center'><h1>LISTADO ".strtoupper($tipo_listado)." ".strtoupper($subtipo)."</h1></div>");
print($list->showListado($solicitudes,$_POST['rol'],$$cabecera,$$camposdatos,$provisional=1));

?>
