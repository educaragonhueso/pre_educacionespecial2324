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
//no funciona el acceso remoto
$res=$utils->getSolicitudesComprobarBaremo();
foreach($res as $aldata)
{
   $nhdisc=0;
   $nombre=$aldata['nombre'];
   $apellido1=$aldata['apellido1'];
   $apellido2=$aldata['apellido2'];
   $nombre_centro=$aldata['nombre_centro'];
   $dni=$aldata['dni_alumno'];
   $fnac=$aldata['fnac'];
   $dni1=$aldata['dni_tutor1'];
   $dni2=$aldata['dni_tutor2'];
   
   $dnidisc1=$aldata['dnidisc1'];
   if($dnidisc1!='nodata' and $dnidisc1!='')
      $nhdisc++;
   $dnidisc2=$aldata['dnidisc2'];
   if($dnidisc2!='nodata' and $dnidisc2!='')
      $nhdisc++;
   $dnidisc3=$aldata['dnidisc3'];
   if($dnidisc3!='nodata' and $dnidisc3!='')
      $nhdisc++;
   $sri=$aldata['renta_inferior'];
   
   $sdh=$aldata['discapacidad_hermanos'];
   $sda=$aldata['discapacidad_alumno'];
   
   $idal=$aldata['id_alumno'];

   $fecha=date("d/m/Y"); 
      
   $rda=0;
   if($sda==1)
   {
      $resp=comprobarDiscapacidad($dni,'dni',$nombre,$apellido1,$fnac,$fecha);
      $rda=procesarRespuestaDiscapacidad($resp);
      $resda=$utils->actualizaComprobaciones('comprobar_discapacidad_alumno',$idal,$rda);
   }
   else
      $resda=$utils->actualizaComprobaciones('comprobar_discapacidad_alumno',$idal,0);
   
   $rdh=0;
   if($sdh==1)
   {
      $rdh=$utils->comprobarBaremoDiscapacidad('discapacidad_hermanos',$dnidisc1,$dnidisc2,$dnidisc3,$csv);
      $resdh=$utils->actualizaComprobaciones('comprobar_discapacidad_hermanos',$idal,$rdh);
   }
   else
      $resdh=$utils->actualizaComprobaciones('comprobar_discapacidad_hermanos',$idal,0);

}
?>
