<?php
require_once "../../../config/config_global.php";
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
#ACTUALIZAMOS EL BAREMO DE TODAS LAS SOLICITUDES TENIENDO EN CUENTA LAS COMPROBACIONES DE RENTA ETC...

require_once '../UtilidadesAdmision.php';

$conectar=new Conectar('../../../config/config_database.php');
$conexion=$conectar->conexion();
$utils=new UtilidadesAdmision($conexion,'','');

$res=$utils->getSolicitudesComprobarBaremo();

foreach($res as $aldata)
{
   $puntos_baremo=0;
   $puntos_baremo_validados=0;
   $nhdisc=0;
   if($aldata['id_alumno']!=1468) continue;

   $resbaremo=$utils->recalcularBaremo($aldata);
   $res=$utils->actualizarBaremo($resbaremo,$aldata['id_alumno']);
   print($aldata['apellido1']."-".$aldata['apellido2']."-".$aldata['nombre']."\n");
   print("\nPUNTOS BAREMO: ");
   print($resbaremo['pb']);
   print("\nPUNTOS BAREMO VALIDADOS: ");
   print($resbaremo['pbv']);
   print("\n");
   print("=======================\n");
}
?>
