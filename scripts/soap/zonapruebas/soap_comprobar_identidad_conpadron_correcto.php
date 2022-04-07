<?php

include('../funciones_soap.php');
##########################VALORES DE NIFS#################################

#CASO 1, FUNCIONA
$fecha_solicitud= "03/01/2022";
$nombre='LUIS';
$apellido1='HUESO';
$nif='';
$fnac= "1972-06-14";

//COMP PRIMER NIF
$respuesta=comprobarIdentidadconPadron($nif,$nombre,$apellido1,$fnac);
$respuestamod=procesarRespuestaPadron($respuesta,'nodata','nodata',$nif);
print_r("\nCOMPROBADO PRIMER NIF $nif:\n ");
print_r($respuestamod);

