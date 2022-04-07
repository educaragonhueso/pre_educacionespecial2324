<?php
include('../funciones_soap.php');
#CASO 1, FUNCIONA
$nif1='09749212H';
$nombre='BONIFACIA';
$apellido1='PASCUAL';
$apellido2='PICO';
$fechainicio="01/01/2021";
$fechafin="01/01/2022";

$nif1='09749212H';
$nombre='BONIFACIA';
$apellido1='PASCUAL';
$apellido2='PICO';
$fechainicio="01/01/2021";
$fechafin="";

$nif1='X5128683M';
$nombre='';
$apellido1='';
$apellido2='';
$fechainicio="01/01/2021";
$fechafin="";

$respuesta=comprobarBeneficiosHistoricos($nif1,$nombre,$apellido1,$apellido2,$fechainicio,$fechafin);

print_r($respuesta);



