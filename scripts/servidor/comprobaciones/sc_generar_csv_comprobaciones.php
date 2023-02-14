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

$lineacsv="nombre;apellido1;apellido2;nombre_centro;dni;dni tutor1;dni tutor2;";
$lineacsv.="solicita renta;comprobacion renta;solicita discapacidad hermanos;comprobacion discapacidad hermanos;num hermanos;";
$lineacsv.="solicita discapacidad alumno;comprobacion discapacidad alumno;";
$lineacsv.="solicita familia numerosa;comprobacion familia numerosa;tipo familia numerosa;";
$lineacsv.="solicita familia monoparental;comprobacion familia monoparental;tipo familia monoparental\n";
print($lineacsv);
foreach($res as $aldata)
{
   
   $puntos_baremo=0;
   $puntos_baremo_validados=0;
   $nhdisc=0;
   //if($aldata['id_alumno']!=1322) continue;
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
   if($dnidisc1!='nodata' and $dnidisc1!='')
      $nhdisc++;
   $dnidisc2=$aldata['dnidisc2'];
   if($dnidisc2!='nodata' and $dnidisc2!='')
      $nhdisc++;
   $dnidisc3=$aldata['dnidisc3'];
   if($dnidisc3!='nodata' and $dnidisc3!='')
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

   $lineacsv=$nombre.";".$apellido1.";".$apellido2.";".$nombre_centro.";".$dni.";".$dni1.";".$dni2.";";
   $lineacsv.=$sri.";".$rri.";".$sdh.";".$rdh.";".$nhdisc.";";
   $lineacsv.=$sda.";".$rda.";";
   $lineacsv.=$sfn.";".$rfn.";".$tfn.";";
   $lineacsv.=$sfm.";".$rfm.";".$tfm."\n";
   //$resbaremo=$utils->recalcularBaremo($aldata);
   //$res=$utils->actualizarBaremo($resbaremo,$aldata['token']);
   print($lineacsv);
  // print("\n");
}
?>
