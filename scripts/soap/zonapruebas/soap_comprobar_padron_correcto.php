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
#CASO , NO FUNCIONA
$fnac= "03/01/2022";
$nombre='';
$apellido1='';
$apellido2='';
$nif='99999949C';

//Español Tipo Identificador Identificador Nombre Primer Apellido Segundo Apellido Provincia Residencia Municipio Residencia Fecha Nacimiento Provincia Nacimiento Municipio/País Nacimiento
//S DNI 88889792J FICTICIO12 PARENTE ESPECIMEN12 50 297 19361105 32 043
//S DNI 88885950N FICTICIO17 LAMARCA ESPECIMEN17 22 204 19800602 01 059
//N NIE X8885945X FICTICIO51 IQBAL ESPECIMEN51 44 025 19840124 66 426


$fecha_solicitud= "03/01/2022";
$nombre='';
$apellido1='';
$apellido2='';
$nif='73243855X';

$fecha_solicitud= "03/01/2022";
$nombre='LUIS';
$apellido1='HUESO';
$apellido2='IBAÑEZ';
$fnac='1972-06-14';
$nif='';
$respuesta=comprobarPadron($nif,$nombre,$apellido1,$fnac);
print_r($respuesta);
$respuesta_procesada=procesarRespuestaPadron($respuesta,$nif,$nombre,$apellido1);
print_r($respuesta_procesada);

