<?php

$dfi=substr(DIA_FIN_INSCRIPCION,0,-9);
$adfi=explode("/",$dfi);
$min=$adfi[1];
$din=$adfi[2];
$hfi=substr(DIA_FIN_INSCRIPCION,10);


define("M0","<p class='cajainfo' style='text-align:center'>Convocatoria en periodo de preinscripción, en unos días se iniciará el proceso de inscripción</p>");
define("M10","<p class='cajainfo' style='text-align:center'>Convocatoria en periodo de inscripción.<br> Finaliza el $din de $min a las $hfi </p>");
define("M20","<p class='cajainfo' style='text-align:center'>Estamos en baremación de solicitudes, el día ".DIA_PUBLICACION_BAREMADAS." se publicará el listado con el baremo provisional</p>");
define("M21","<p class='cajainfo' style='text-align:center'>Publicado listados baremados, se podrá reclamar a partir de ".DIA_INICIO_RECLAMACIONES_BAREMADAS."</p>");
define("M22","<p class='cajainfo' style='text-align:center'>En periodo de reclamaciones baremadas que finaliza el día ".DIA_FIN_RECLAMACIONES_BAREMADAS."</p>");
define("M23","<p class='cajainfo' style='text-align:center'>Se ha generado el número aleatorio <span class='lbaremadas' data-tipo='sorteo' data-subtipo='sor_ale' style='color:black;cursor:pointer;'>CONSULTA EL LISTADO</span></p>");
define("M30","<p class='cajainfo' style='text-align:center'>Se ha realizado el sorteo para determinar el orden de acceso<br>El día ".DIA_PUBLICACION_PROVISIONAL." podrás ver el listado provisional</p>");
define("M40","<p class='cajainfo' style='text-align:center'>En periodo de reclamaciones al listado provisional que finaliza el día ".DIA_FIN_RECLAMACIONES_PROVISIONAL."</p>");

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
