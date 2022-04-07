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
$nif1='73243855X';
$nombre='';
$apellido1='';
$apellido2='';
//$fechainicio="01/01/2021";
$fechainicio="";
$fechafin="";

//$respuesta=comprobarImv($nif1,$nombre,$apellido1,$apellido2,$fechainicio,$fechafin);
$respuesta=comprobarImv($nif1);
print_r($respuesta);
exit();
$prespuesta=procesarRespuestaImv($respuesta);
print_r($prespuesta);


