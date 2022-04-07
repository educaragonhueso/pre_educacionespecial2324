<?php
##########################VALORES DE NIFS#################################
include('../funciones_soap.php');

#CASO 1, FUNCIONA
$fecha_solicitud= "03/01/2022";
$nombre='';
$apellido1='';
$apellido2='';
$nif='73410860N';
#CASO 2, NO FUNCIONA
$fecha_solicitud= "03/01/2022";
$nombre='JOSE LUIS';
$apellido1='BERRIEL';
$apellido2='RAVA';
$nif='77753921Y';
#CASO 3, FUNCIONA
$fecha_solicitud= "11/03/2022";
$nombre='LUIS';
$apellido1='HUESO';
$apellido2='IBÁÑEZ';
$nif='73243855X';
$fnac= "14/06/1972";

#CASO 3, FUNCIONA
$fecha_solicitud= "11/03/2022";
$nombre='ELENA';
$apellido1='MORENO';
$apellido2='NAVAL';
$nif='25136709D';
$fnac= "12/01/1964";
$respuesta=comprobarFamiliaNumerosa($nif,$fecha_solicitud,$nombre,$apellido1,$apellido2,$fnac);
#print_r($respuesta);
$respuesta_procesada=procesarRespuestaFamiliaNumerosa($respuesta);
print_r($respuesta_procesada);

