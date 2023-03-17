<?php
require_once '../UtilidadesAdmision.php';
include('../../soap/funciones_soap.php');

require_once "../../../config/config_global.php";
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
//falta el fichero de respuesta de pilar mora
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
   //calcuklamos el baremo de los campos q no se compruebasn
   $sitlaboral=$aldata['sitlaboral'];
   $vsitlaboral=$aldata['validar_sitlaboral'];

   $tutrescentro=$aldata['tutores_centro'];
   $vtutorescentro=$aldata['validar_tutores_centro'];

   $acog=$aldata['acogimiento'];
   $vacog=$aldata['validar_acogimiento'];

   $genero=$aldata['genero'];
   $vgenero=$aldata['validar_genero'];

   $ter=$aldata['terrorismo'];
   $vter=$aldata['validar_terrorismo'];

   $parto=$aldata['parto'];
   $vparto=$aldata['validar_parto'];
   
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
   
   $sfn=$aldata['marcado_numerosa'];
   $tfn=$aldata['tipo_familia_numerosa'];
   $sfm=$aldata['marcado_monoparental'];
   $tfm=$aldata['tipo_familia_monoparental'];

   $idal=$aldata['id_alumno'];


   $rfn=0;
   if($sfn==1)
   {
      $rfn=$utils->comprobarBaremo('familia_numerosa',$dni,$dni1,$dni2,$csvfam,$csv);
      $resdh=$utils->actualizaComprobaciones('comprobar_familia_numerosa',$idal,$rfn);
   }
   else
      $resdh=$utils->actualizaComprobaciones('comprobar_familia_numerosa',$idal,0);
      
}
?>
