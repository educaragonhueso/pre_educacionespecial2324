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
$log_listados_solicitudes_fase2=new logWriter('log_listados_solicitudes_fase2',DIR_LOGS);
$log_listados_solicitudes_fase2->warning("OBTENIENDO DATOS SOLICITUDES FASE II:");
$log_listados_solicitudes_fase2->warning(print_r($_POST,true));
######################################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
//VARIABLES
$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/fase2/';
$estado_convocatoria=$_POST['estado_convocatoria'];
//comprobamos si es el dia de sorteo para la fase 2

$tipo_listado='solicitudes_fase2';
$rol=$_POST['rol'];
$id_centro=$_POST['id_centro'];

if(isset($_POST['subtipo']))
	$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado, para ebo o tva

if($subtipo_listado=='lfase2_sol_ebo')
 $nombre_listado='LISTADO DEFINITIVO FASE II EBO';
else
 $nombre_listado='LISTADO DEFINITIVO FASE II TVA';
$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';
$utils=new UtilidadesAdmision($conexion);
$list=new ListadosController('matricula',$conexion,$estado_convocatoria);
$tsolicitud=new Solicitud($conexion);
$tcentro=new Centro($conexion,1,'ajax');
$nsolicitudes=$tcentro->getNumSolicitudes();

$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;

$boton_asignar_automatica='<div id="form_asignarfase2" class="input-group mb-3"
style="margin-top:5px">
				<div class="input-group-append">
				<button class="btn btn-success" type="submit" id="boton_asignar_plazas_fase2" data-subtipo="'.$subtipo_listado.'">Asignar Vacantes</button>
				</div>
		          </div>';

//if($subtipo_listado!='lfase2_sol_sor') print($boton_asignar_automatica); //mostramos formulario sorteo solo si no se ha hecho ya
//mostramos las solitudes completas sin incluir borrador
$solicitudes=$tsolicitud->getSolicitudesFase2($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase2); 
######################################################################################
$log_listados_solicitudes_fase2->warning("OBTENIDAS $nsolicitudes SOLICITUDES FASE II:");
######################################################################################
$nombrefichero=$subtipo_listado.'_admin';
if($_POST['rol']=='admin' or $_POST['rol']=='sp' or $_POST['rol']=='centro')
{
   if($_POST['pdf']==1)
   {
      $log_listados_solicitudes_fase2->warning("GFENERANDO FICHERO $nombrefichero");
         
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
      $pdf->Cell(140,10,'En ______________________ a ____de________ de 2022',0,0,'C');
      $pdf->Cell(0,10,'Firmado:',0,0);
      $pdf->Ln();
      $pdf->Cell(220,10,'El Director/a',0,0,'R');
#resumen=$tcentro->getResumenFase2($_POST['rol']);;
      $pdf->Output(DIR_SOR.$nombrefichero.'.pdf','F');
   }

$tablaresumen=$tcentro->getResumenFase2($_POST['rol']);
$vacantes_centros=$tcentro->getVacantesCentros($log_listados_solicitudes_fase2);
$log_listados_solicitudes_fase2->warning("RESUMEN VACANTES TODOS CENTROS:");
$log_listados_solicitudes_fase2->warning(print_r($vacantes_centros,true));
print($list->showTablaResumenFase2($vacantes_centros,$ncol=1));
#print($list->showFiltrosTipo());
print($filtro_datos);
print("<div id='listado_fase2' style='text-align:center'><h1>LISTADO LISTADO SOLICITUDES NO ADMITIDAS FASE 2</h1></div>");
$boton_descarga="<button type='button' class='btn btn-info' onclick='window.open(\"".DIR_SOR_WEB.$nombrefichero.".pdf\",\"_blank\");'>Descarga listado</button>";
if($subtipo_listado!='lfase2_sol_sor') print($boton_descarga.'<br>'); //
}
print($list->showListadoFase2($solicitudes,$_POST['rol'],$$cabecera,$$camposdatos,$provisional=1,$subtipo_listado,$log_listados_solicitudes_fase2,$vacantes_centros));
print($script);
?>
