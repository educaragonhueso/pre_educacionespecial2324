<?php

include('../funciones_soap.php');
##########################VALORES DE NIFS#################################

#CASO 1, FUNCIONA
$fecha_solicitud= "03/02/2023";
$nombre='RAMONA';
$apellido1='MARTINEZ';
$nif='87654321X';
$fnac= "1980-08-20";

//COMP PRIMER NIF
$respuesta=comprobarIdentidad($nif,$nombre,$apellido1,$fnac);
$respuestamod=procesarRespuestaIdentidad($respuesta,$nif,$nombre,$apellido1);
print_r("\nCOMPROBADO PRIMER NIF $nif:\n ");
print_r($respuestamod);

