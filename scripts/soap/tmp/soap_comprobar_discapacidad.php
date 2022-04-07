<?php
include('../funciones_soap.php');

#CASO 1, TODOS LOS DATOS, FUNCIONA
$nif='23456789D';
$provincia="50";
$nombre= "JÒRDI";
$apellido1= "MARIÑÁ";
$fnac= "03/09/1925";
$fecha_solicitud= "03/01/2022";
#CASO 3, SOLO NIF, FUNCIONA
$nif='23456789D';
$nombre= "";
$apellido1= "";
$fnac= "";
$fecha_solicitud= "";
#CASO 2, NO HAY NIF NI FNAC, FUNCIONA
$nif='';
$nombre= "JÒRDI";
$apellido1= "MARIÑÁ";
$fnac= "";
$fecha_solicitud= "03/01/2022";


if(is_numeric($nif[0]))
   $tipo='NIF';
else
   $tipo='NIE';

print("\nTIPO DOCUMENTO: $tipo\n");
$r=comprobarDiscapacidad($nif,$tipo,$nombre,$apellido1,$fnac,$fecha_solicitud);
$respuesta=procesarRespuestaDiscapacidad($r);
print_r($respuesta);

