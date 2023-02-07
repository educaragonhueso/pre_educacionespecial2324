<?php
require_once "../../config/config_global.php";
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
#ACTUALIZAMOS EL BAREMO DE TODAS LAS SOLICITUDES TENIENDO EN CUENTA LAS COMPROBACIONES DE RENTA ETC...

require_once 'UtilidadesAdmision.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$utils=new UtilidadesAdmision($conexion,'','');

$res=$utils->getSolicitudesComprobarBaremo();

foreach($res as $aldata)
{
   $puntos_baremo=0;
   $puntos_baremo_validados=0;
   $nhdisc=0;
   //if($aldata['id_alumno']!=1361) continue;
   $nombre=$aldata['nombre'];
   $apellido1=$aldata['apellido1'];
   $apellido2=$aldata['apellido2'];
   $nombre_centro=$aldata['nombre_centro'];
   $dni=$aldata['dni_alumno'];
   $dni1=$aldata['dni_tutor1'];
   $dni2=$aldata['dni_tutor2'];
   
   $sri=$aldata['renta_inferior'];
   $rri=$aldata['comprobar_renta_inferior'];

   $sdh=$aldata['discapacidad_hermanos'];
   $rdh=$aldata['comprobar_discapacidad_hermanos'];
   
   $nhdisc=0;
   $dnidisc1=$aldata['dnidisc1'];
   if($dnidisc1!='nodata' and $dnidisc1==0)
      $nhdisc++;
   $dnidisc2=$aldata['dnidisc2'];
   if($dnidisc2!='nodata' and $dnidisc2==0)
      $nhdisc++;
   $dnidisc3=$aldata['dnidisc3'];
   if($dnidisc3!='nodata' and $dnidisc3==0)
      $nhdisc++;

   $sda=$aldata['discapacidad_alumno'];
   $rda=$aldata['comprobar_discapacidad_alumno'];
   
   
   $sfn=$aldata['marcado_numerosa'];
   $rfn=$aldata['comprobar_familia_numerosa'];
   $tfn=$aldata['tipo_familia_numerosa'];
   
   $sfm=$aldata['marcado_monoparental'];
   $rfm=$aldata['comprobar_familia_monoparental'];
   $tfm=$aldata['tipo_familia_monoparental'];
   
   //calcuklamos el baremo de los campos q no se compruebasn
   $pdom=$aldata['marcado_proximidad_domicilio'];
   $vpdom=$aldata['validar_proximidad_domicilio'];
   $valorpdom=$aldata['proximidad_domicilio'];

   $tutorescentro=$aldata['tutores_centro'];
   $vtutorescentro=$aldata['validar_tutores_centro'];

   $sitlaboral=$aldata['sitlaboral'];
   $vsitlaboral=$aldata['validar_sitlaboral'];
   $tutorescentro=$aldata['tutores_centro'];
   $vtutorescentro=$aldata['validar_tutores_centro'];

   $resbaremo=$utils->recalcularBaremo($aldata);
   $res=$utils->actualizarBaremo($resbaremo,$aldata['id_alumno']);
   print($aldata['apellido1']);
   print("\nPUNTOS BAREMO: \n");
   print($resbaremo['pb']);
   print("\nPUNTOS BAREMO VALIDADOS: \n");
   print($resbaremo['pbv']);
   print("\n");
   print("\n");
}
?>
