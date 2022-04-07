<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

//SECCION CARGA CLASES Y CONFIGURACIÓN
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'/LOGGER.php';
require_once DIR_APP.'/parametros.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';

######################################################################################
$log_listados_baremados=new logWriter('log_listados_baremados',DIR_LOGS);
$log_listados_baremados->warning("OBTENIENDO SOLICITUDES BAREMADAS");
$log_listados_baremados->warning(print_r($_POST,true));
######################################################################################

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);

//VARIABLES
$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/';
$id_centro=$_POST['id_centro'];
$rol=$_POST['rol'];
$provincia=$_POST['provincia'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$tipo_listado=$_POST['tipo'];//listados del sorteo, provisionales o definitivos
$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado
$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';
$list=new ListadosController('alumnos',$conexion);
$tcentro=new Centro($conexion,$_POST['id_centro'],'ajax');
$tcentro->setNombre();
$nombre_centro=$tcentro->getNombre();

$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;

$formato=''; //formato listado en el pdf
$anchuracelda=10;
$primera_celda=20;
if($subtipo_listado=='sor_ale') {$nombre_listado='LISTADO ALUMNOS SEGUN NUMERO ALEATORIO PARA SORTEO';$formato='numero_aleatorio';}
if($subtipo_listado=='sor_bar') {$nombre_listado='LISTADO SOLICITUDES BAREMADAS';$formato='numero_aleatorio';}
if($subtipo_listado=='sor_det') {$nombre_listado='LISTADO DETALLE BAREMO';$formato='detalle_baremo';}


#SI ES ANTES DEL SORTEO
$log_listados_baremados->warning("LOGINICIO: OBTENIENDO SOLICITUDES BAREMADAS SUBTIPO: $subtipo_listado ESTADO CONVOCATORIA: $estado_convocatoria, ID CENTRO: $id_centro formato: $formato, provincia: $provincia");

if($estado_convocatoria<ESTADO_PUBLICACION_PROVISIONAL)
   $solicitudes=$list->getSolicitudes($id_centro,$estado_convocatoria,'normal',$subtipo_listado,$solicitud,$log_listados_baremados,0,$rol,$provincia); 
else
   $solicitudes=$list->getSolicitudes($id_centro,$estado_convocatoria,'baremadas',$subtipo_listado,$solicitud,$log_listados_baremados,0,$rol,$provincia); 
   
######################################################################################
$log_listados_baremados->warning("OBBTENIDAS SOLICITUDES GENERALES BAREMADAS");
######################################################################################

if($_POST['pdf']==1)
{
	$datos=array();
	$i=0;
	//extraemos los campos de datos q nos interesan
	foreach($solicitudes as $sol)
	{
		$datos[$i] = new stdClass;
      $valorfamilia=0;
      if($sol->validar_tipo_familia_numerosa==1) $valorfamilia=$sol->tipo_familia_numerosa;
      if($sol->validar_tipo_familia_monoparental==1) $valorfamilia=$sol->tipo_familia_monoparental;
      
      
      $valordiscapacidad=0;
      if($sol->validar_discapacidad_alumno==1) $valordiscapacidad=$valordiscapacidad+$sol->discapacidad_alumno;
      if($sol->validar_discapacidad_hermanos==1) $valordiscapacidad=$valordiscapacidad+$sol->discapacidad_hermanos;
      if($sol->proximidad_domicilio=='') $proximidad_domicilio='sindomicilio';
      else $proximidad_domicilio=$sol->proximidad_domicilio;
		
      foreach($$camposdatos as $d)
		{
         if($d=='validar_discapacidad') 
			   $datos[$i]->$d=$valordiscapacidad;
         else if($d=='validar_tipo_familia') 
			   $datos[$i]->$d=$valorfamilia;
         else if($d=='proximidad_domicilio') 
			   $datos[$i]->$d=$proximidad_domicilio;
         else
			   $datos[$i]->$d=$sol->$d;
		}
	$i++;
	}
	$pdf = new PDF();
	$cab=$$cabecera;
	$pdf->SetFont('Helvetica','',8);
	$pdf->AddPage('L','',0,$nombre_listado);
	$pdf->BasicTable($cab,$datos,0,$anchuracelda,$formato,$primera_celda);
	$pdf->Ln(20);
	 // Arial italic 8
	$pdf->SetFont('Arial','I',8);
	  // Page number
	$pdf->Cell(40,10,'SELLO CENTRO',1,0,'C');
	$pdf->Cell(140,10,'En ______________________ a ____de________ de 2022',0,0,'C');
	$pdf->Cell(0,10,'Firmado:',0,0);
	$pdf->Ln();
	$pdf->Cell(220,10,'El Director/a',0,0,'R');
	$pdf->Output(DIR_SOR.$subtipo_listado.'.pdf','F');
}

if($subtipo_listado=='sor_ale') $subtipo='Nº ALEATORIO';
if($subtipo_listado=='sor_bar') $subtipo='SOLICITUDES BAREMADAS';
if($subtipo_listado=='sor_det') $subtipo='SOLICITUDES DETALLE BAREMO';

print("<button class='descargalistado' type='button' class='btn btn-info' onclick='window.open(\"".DIR_SOR_WEB.$subtipo_listado.".pdf\",\"_blank\");'>Descarga listado</button>");
print($filtro_datos);
print("<div class='titulolistado' style='text-align:center'><h1>LISTADO ".strtoupper($tipo_listado)." ".strtoupper($subtipo)."</h1></div>");
print("<div class='titulolistado' style='text-align:center'><h1>CENTRO: "." ".strtoupper($nombre_centro)."</h1></div>");
//mostramos listados con el campo final a 1 para no ermitir editar el registro
print($list->showListado($solicitudes,$_POST['rol'],$$cabecera,$$camposdatos,$tipo_listado,$subtipo_listado));

?>
