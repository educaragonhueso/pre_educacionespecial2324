<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');
require_once DIR_CLASES.'/LOGGER.php';
require_once DIR_APP.'/parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_BASE.'/scripts/servidor/UtilidadesAdmision.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

require_once DIR_BASE.'/scripts/ajax/form_alumnofase2js.php';

######################################################################################
$log_listados_solicitudes_fase2_finales=new logWriter('log_listados_solicitudes_fase2_finales',DIR_LOGS);
$log_listados_solicitudes_fase2_finales->warning("INICIOLOG OBTENIENDO DATOS SOLICITUDES FINALEs:");
$log_listados_solicitudes_fase2_finales->warning(print_r($_POST,true));
######################################################################################

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
//VARIABLES
$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/finales/';
$estado_convocatoria=$_POST['estado_convocatoria'];
//comprobamos si es el dia de sorteo para la fase 2

$tipo_listado='solicitudes_finales';
$rol=$_POST['rol'];
$id_centro=$_POST['id_centro'];

if(isset($_POST['id_alumno']))
	$id_alumno=$_POST['id_alumno'];//dentro de cada tipo, el subtipo de listado, para ebo o tva
else $id_alumno='';
if(isset($_POST['subtipo']))
	$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado, para ebo o tva

if($subtipo_listado=='lfinal_sol_ebo')
 $nombre_listado='LISTADO DEFINITIVO FASE FINAL EBO';
else
 $nombre_listado='LISTADO DEFINITIVO FASE FINAL TVA';
$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';
$utils=new UtilidadesAdmision($conexion);
$list=new ListadosController('matricula',$conexion,$estado_convocatoria);
$tsolicitud=new Solicitud($conexion);
$tcentro=new Centro($conexion,1,'ajax');
$nsolicitudes=$tcentro->getNumSolicitudes();

if($subtipo_listado=='lfinal_sol_ebo_adjudicadas')
{
   $subtipo_listado='lfinal_sol_ebo';
   //mostramos las solitudes completas sin incluir borrador
   $solicitudes=$tsolicitud->getSolicitudesFase2FinalesAdjudicadas($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);
}
else if($subtipo_listado=='lfinal_sol_tva_adjudicadas')
{
   $subtipo_listado='lfinal_sol_tva';
   //mostramos las solitudes completas sin incluir borrador
   $solicitudes=$tsolicitud->getSolicitudesFase2FinalesAdjudicadas($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);
}
else if($subtipo_listado=='lfinal_sol_ebo_desplazados')
{
   $subtipo_listado='lfinal_sol_ebo';
   //mostramos las solitudes completas sin incluir borrador
   $solicitudes=$tsolicitud->getSolicitudesFase2FinalesDesplazados($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);
}
else if($subtipo_listado=='lfinal_sol_tva_desplazados')
{
   $subtipo_listado='lfinal_sol_tva';
   //mostramos las solitudes completas sin incluir borrador
   $solicitudes=$tsolicitud->getSolicitudesFase2FinalesDesplazados($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);
}
else if($subtipo_listado=='lfinal_sol_ebo_nomatricula')
{
   $subtipo_listado='lfinal_sol_ebo';
   //mostramos las solitudes completas sin incluir borrador
   $solicitudes=$tsolicitud->getSolicitudesFase2FinalesNoMatricula($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);
}
else if($subtipo_listado=='lfinal_sol_tva_nomatricula')
{
   $subtipo_listado='lfinal_sol_tva';
   //mostramos las solitudes completas sin incluir borrador
   $solicitudes=$tsolicitud->getSolicitudesFase2FinalesNoMatricula($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);
}
else
   $solicitudes=$tsolicitud->getSolicitudesFase2Finales($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno);

$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;

//mostramos las solitudes completas sin incluir borrador
//$solicitudes=$tsolicitud->getSolicitudesFase2Finales($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2_finales,$id_alumno); 

######################################################################################
$log_listados_solicitudes_fase2_finales->warning("OBTENIDAS $nsolicitudes SOLICITUDES FINALES:");
######################################################################################

$nombrefichero=$subtipo_listado.'_admin';
if($_POST['rol']=='admin' or $_POST['rol']=='sp' or $_POST['rol']=='centro')
{
   if($_POST['pdf']==1)
   {
      $log_listados_solicitudes_fase2_finales->warning("GFENERANDO FICHERO $nombrefichero");
         
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
      $pdf->AddPage('L','',0,$nombre_listado);
      $pdf->BasicTable($cab,$datos,0,30,'normal',40);
      $pdf->Ln(20);
       // Arial italic 8
      $pdf->SetFont('Arial','I',8);
        // Page number
      $pdf->Cell(40,10,'SELLO CENTRO',1,0,'C');
      $firma='En ______________________ a ____de________ de '.CURSO;
      $pdf->Cell(140,10,$firma,0,0,'C');
      $pdf->Cell(0,10,'Firmado:',0,0);
      $pdf->Ln();
      $pdf->Cell(220,10,'El Director/a',0,0,'R');
      $pdf->Output(DIR_SOR.$nombrefichero.'.pdf','F');
   }

   //$tablaresumen=$tcentro->getResumenFase2($_POST['rol']);
   $vacantes_centros=$tcentro->getVacantesCentros($log_listados_solicitudes_fase2_finales);
   print($list->showTablaResumenFase2($vacantes_centros,$ncol=1));
   #print($list->showFiltrosTipo());
   print($filtro_datos);
   print("<div id='listado_fasefinal' style='text-align:center'><h1>LISTADO LISTADO SOLICITUDES FINAL FASE II</h1></div>");
   $boton_descarga="<button type='button' class='btn btn-info' onclick='window.open(\"".DIR_SOR_WEB.$nombrefichero.".pdf\",\"_blank\");'>Descarga listado</button>";
   print($boton_descarga.'<br>'); //
}
//print($list->showListadoFase2($solicitudes,$rol,$$cabecera,$$camposdatos,$provisional=1,$subtipo_listado));
print($list->showListadoFase2Final($solicitudes,$rol,$$cabecera,$$camposdatos,$provisional=1,$subtipo_listado,$log_listados_solicitudes_fase2_finales,$vacantes_centros));
print($script);
?>
