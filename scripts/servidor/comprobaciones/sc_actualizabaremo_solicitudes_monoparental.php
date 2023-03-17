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
//falta el fichero de respuestas de pilar mora
$csvfam="../../datos/datos_comprobaciones/familias_17marzo.csv";
foreach($res as $aldata)
{
   
   $nhdisc=0;
   $nombre=$aldata['nombre'];
   $apellido1=$aldata['apellido1'];
   $apellido2=$aldata['apellido2'];
   $nombre_centro=$aldata['nombre_centro'];
   $dni=$aldata['dni_alumno'];
   $dni1=$aldata['dni_tutor1'];
   $dni2=$aldata['dni_tutor2'];
   
   $sfm=$aldata['marcado_monoparental'];
   $tfm=$aldata['tipo_familia_monoparental'];

   $idal=$aldata['id_alumno'];
      
   $rfm=0;
   if($sfm==1)
   {
      $rfm=$utils->comprobarBaremo('familia_monoparental',$dni,$dni1,$dni2,$csvfam);
      $resdh=$utils->actualizaComprobaciones('comprobar_familia_monoparental',$idal,$rfm);
   }
   else
      $resdh=$utils->actualizaComprobaciones('comprobar_familia_monoparental',$idal,0);
      
}
?>
