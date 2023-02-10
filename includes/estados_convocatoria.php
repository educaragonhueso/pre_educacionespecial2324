<?php

$dfi=substr(DIA_FIN_INSCRIPCION,0,-9);
$adfi=explode("/",$dfi);
$min=$adfi[1];
$din=$adfi[2];
$hfi=substr(DIA_FIN_INSCRIPCION,10);

function convertirFecha($f)
{
   $fecha=explode(' ',$f)[0];
   $año=explode('/',$fecha)[0];
   $mes=explode('/',$fecha)[1];
   $dia=explode('/',$fecha)[2];
  
   $hora=explode(' ',$f)[1];

   $sf=$dia." del ".$mes." a las ".$hora." h";
   return $sf;
}

define("M0","<p class='cajainfo' style='text-align:center'>Convocatoria en periodo de preinscripción, en unos días se iniciará el proceso de inscripción</p>");
define("M10","<p class='cajainfo' style='text-align:center'>Convocatoria en periodo de inscripción.<br> Finaliza el $din de $min a las $hfi </p>");
define("M20","<p class='cajainfo' style='text-align:center'>Estamos en baremación de solicitudes, el día ".convertirFecha(DIA_PUBLICACION_BAREMADAS)." se publicará el listado con el baremo provisional</p>");
define("M30","<p class='cajainfo' style='text-align:center'>Se ha publicado la listas baremadas, a partir de mañana, el día ".convertirFecha(DIA_INICIO_RECLAMACIONES_BAREMADAS)." se podrán hacer reclamaciones</p>");
define("M31","<p class='cajainfo' style='text-align:center'>Se ha iniciado el plazo para reclamaciones hasta el día ".convertirFecha(DIA_FIN_RECLAMACIONES_BAREMADAS)."</p>");
define("M40","<p class='cajainfo' style='text-align:center'>Se estan gestionando las reclamaciones, se publicará el listado con el número aleatorio el día ".convertirFecha(DIA_ALEATORIO)."</p>");
define("M41","<p class='cajainfo' style='text-align:center'>Se han gestionando las reclamaciones, se publicará el listado con el número aleatorio el día ".convertirFecha(DIA_ALEATORIO)."</p>");
define("M42","<p class='cajainfo' style='text-align:center'>Se ha realizado el sorteo, las istas provisionales se publicarán el día ".convertirFecha(DIA_PUBLICACION_PROVISIONAL)."</p>");

$dirb=substr(DIA_INICIO_RECLAMACIONES_BAREMADAS,0,-9);
$hirb=substr(DIA_INICIO_RECLAMACIONES_BAREMADAS,10);

$dfrb=substr(DIA_FIN_RECLAMACIONES_BAREMADAS,0,-9);
$hfrb=substr(DIA_FIN_RECLAMACIONES_BAREMADAS,10);

define("M20","<p class='cajainfo' style='text-align:center'>Publicado listado de solicitudes baremadas<br> A partir del día $dirb a las $hirb se inicia el periodo de reclamaciones hasta el día $dfrb a las $hfrb</p>");


$procesocompleto="
<table class='table table-striped tablainfo'>
    <thead>
      <tr>
        <th>Fase</th>
        <th>Nombre</th>
        <th>Descripción</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>FASE0</td>
        <td>Pre-inscripción</td>
        <td>Revisión alumnos matriculados para ver si continúan o no</td>
      </tr>
      <tr>
        <td>FASE1</td>
        <td>Inscripción</td>
        <td>Peridodo de inscripción, inicio: ".DIA_INICIO_INSCRIPCION."
        fin: ".DIA_FIN_INSCRIPCION." </td>
      </tr>
      <tr>
        <td></td>
        <td>Baremadas</td>
        <td>Periodo, publicación: ".DIA_PUBLICACION_BAREMADAS."
        <br>Periodo, inicio reclamaciones: ".DIA_INICIO_RECLAMACIONES_BAREMADAS."
        <br>Periodo, fin reclamaciones: ".DIA_FIN_RECLAMACIONES_BAREMADAS." </td>
      </tr>
      <tr>
        <td></td>
        <td>SORTEO</td>
        <td>Fecha sorteo: ".DIA_SORTEO."</td>
      </tr>
      <tr>
        <td></td>
        <td>Provisional</td>
        <td>Periodo, publicación: ".DIA_PUBLICACION_PROVISIONAL."
        <br>Periodo, inicio reclamaciones: ".DIA_INICIO_RECLAMACIONES_PROVISIONAL."
        <br>Periodo, fin reclamaciones: ".DIA_FIN_RECLAMACIONES_PROVISIONAL." </td>
      </tr>
      <tr>
        <td></td>
        <td>Definitivos</td>
        <td>Fecha de publicación: ".DIA_PUBLICACION_DEFINITIVOS."</td>
      </tr>
      <tr>
        <td>FASE2</td>
        <td>Asignación plazas restantes</td>
        <td>Inicio en : ".DIA_IN."</td>
      </tr>
    </tbody>
  </table>
";



define("PC",$procesocompleto);
