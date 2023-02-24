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

define("M1","<p class='cajainfo' style='text-align:center'>En unos días se iniciará el proceso de inscripción y matrícula</p>");
define("M10","<p class='cajainfo' style='text-align:center'>Convocatoria en periodo de inscripción.<br> Finaliza el $din de $min a las $hfi </p>");
define("M20","<p class='cajainfo' style='text-align:center'>Estamos en baremación de solicitudes, el día ".convertirFecha(DIA_PUBLICACION_BAREMADAS)." se publicará el listado con el baremo provisional</p>");
define("M30","<p class='cajainfo' style='text-align:center'>Se ha publicado la listas baremadas, a partir de mañana, el día ".convertirFecha(DIA_INICIO_RECLAMACIONES_BAREMADAS)." se podrán hacer reclamaciones</p>");
define("M31","<p class='cajainfo' style='text-align:center'>Se ha iniciado el plazo para reclamaciones hasta el día ".convertirFecha(DIA_FIN_RECLAMACIONES_BAREMADAS)."</p>");
define("M40","<p class='cajainfo' style='text-align:center'>Se estan gestionando las reclamaciones, se publicará el listado con el número aleatorio el día ".convertirFecha(DIA_ALEATORIO)."</p>");
define("M41","<p class='cajainfo' style='text-align:center'>Se ha publicado el listado con el número aleatorio el día ".convertirFecha(DIA_SORTEO)." tendrá lugar el sorteo para determinar el orden de las solicitudes</p>");
define("M42","<p class='cajainfo' style='text-align:center'>Se ha realizado el sorteo, las listas provisionales se publicarán el día ".convertirFecha(DIA_PUBLICACION_PROVISIONAL)."</p>");
define("M50","<p class='cajainfo' style='text-align:center'>Se han publicado los listados provisionales</p>");
define("M51","<p class='cajainfo' style='text-align:center'>Estamos en proceso de reclamación de las listas provisionales</p>");

define("M60","<p class='cajainfo' style='text-align:center'>En revisión de reclamaciones listados provisionales</p>");
define("M61","<p class='cajainfo' style='text-align:center'>Publicados listados definitivos</p>");

define("M70","<p class='cajainfo' style='text-align:center'>Publicados listados matrícula fase2</p>");
define("M71","<p class='cajainfo' style='text-align:center'>Periodo de matriculación</p>");

define("M100","<p class='cajainfo' style='text-align:center'>El proceso ha finalizado</p>a");

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
        <td>Número aleatorio</td>
        <td>Fecha publicación: ".DIA_ALEATORIO."</td>
      </tr>
      <tr>
        <td></td>
        <td>Sorteo</td>
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
        <td>Inicio en : ".DIA_INICIO_ASIGNACIONES."</td>
      </tr>
      <tr>
        <td>MATRICULACIÓN</td>
        <td>Realización de matrícula alumnos admitidos</td>
        <td>Inicio en : ".DIA_MATRICULACION_ASIGNACIONES."</td>
      </tr>
    </tbody>
  </table>
";



define("PC",$procesocompleto);
