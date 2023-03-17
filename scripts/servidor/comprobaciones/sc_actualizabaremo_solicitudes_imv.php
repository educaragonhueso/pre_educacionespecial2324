<?php
require_once '../UtilidadesAdmision.php';
include('../../soap/funciones_soap.php');
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
#operaciones antes de iniciar todo el proceso
#ACTUALIZAMOS LAS VACANTES DE TODOS LOS CENTROS en relacion a la  mtraitula existente


$conectar=new Conectar('../../../config/config_database.php');
$conexion=$conectar->conexion();
$utils=new UtilidadesAdmision($conexion,'','');

$res=$utils->getSolicitudesComprobarBaremo();
$csvimv="../../datos/datos_comprobaciones/imv_17marzo.csv";
foreach($res as $aldata)
{
   $nombre_centro=$aldata['nombre_centro'];
   $dni=str_replace('"','',$aldata['dni_alumno']);
   $dni1=str_replace('"','',$aldata['dni_tutor1']);
   $dni2=str_replace('"','',$aldata['dni_tutor2']);
   $sri=$aldata['renta_inferior'];
   print("PROBANDO DNI: $dni\n");
   print("DNI1: $dni1\n");
   print("DNI2: $dni2\n");
   $idal=$aldata['id_alumno'];

   $rri=0;
   if($sri==1)
   {
      $rri=$utils->comprobarBaremo('imv',$dni,$dni1,$dni2,$csvimv);
      print("RESP: $rri\n");
      $resri=$utils->actualizaComprobaciones('comprobar_renta_inferior',$idal,$rri);
   }
   else
      $resri=$utils->actualizaComprobaciones('comprobar_renta_inferior',$idal,0);
}
?>
