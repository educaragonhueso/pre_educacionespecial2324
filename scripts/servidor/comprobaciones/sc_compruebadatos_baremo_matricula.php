<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
#operaciones antes de iniciar todo el proceso
#ACTUALIZAMOS LAS VACANTES DE TODOS LOS CENTROS en relacion a la  mtraitula existente

require_once 'UtilidadesAdmision.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$utils=new UtilidadesAdmision($conexion,'','');

$res=$utils->getSolicitudesComprobarBaremo();

foreach($res as $aldata)
{
   $dni=$aldata['dni_alumno'];
   $dni1=$aldata['dni_tutor1'];
   $dni2=$aldata['dni_tutor2'];
   $idal=$aldata['id_alumno'];

   $rcri=0;
   $sri=$aldata['renta_inferior'];
   if($sri==1)
   {
      print("\nPROCESANDO ALUMNO POR RENTA: $idal");
      $rcri=$utils->comprobarBaremo('imv',$dni,$dni1,$dni2);
      print("\n");
      print("--- imv $rcri");
      $ract=$utils->actualizaComprobaciones('comprobar_renta_inferior',$idal,$rcri);
   }
   $sda=$aldata['discapacidad_alumno'];
   if($sda==1)
   {
      print("\nPROCESANDO ALUMNO POR DISCAPACIDAD: $idal");
      $rcda=$utils->comprobarBaremo('discapacidad_alumno',$dni,$dni1,$dni2);
      print("\n");
      print("--- disc alumno $rcda");
      $ract=$utils->actualizaComprobaciones('comprobar_discapacidad_alumno',$idal,$rcda);
   }
}
/*
//comprobamos alumnos matriculados
$res=$utils->getMatriculaComprobarBaremo();

foreach($res as $aldata)
{
   $dni=$aldata['dni_alumno'];
   $dni1=$aldata['dni_tutor1'];
   $dni2=$aldata['dni_tutor2'];
   $idal=$aldata['id_alumno'];

   $rcri=0;
   print("\nPROCESANDO ALUMNO: $idal");
   $sri=$aldata['renta_inferior'];
   if($sri==1)
   {
      $rcri=$utils->comprobarBaremo('imv',$dni,$dni1,$dni2);
      print("\n");
      print("--- imv $rcri");
   }
   $ract=$utils->actualizaComprobaciones($idal,$rcri);
   print($ract);
}
*/
?>
