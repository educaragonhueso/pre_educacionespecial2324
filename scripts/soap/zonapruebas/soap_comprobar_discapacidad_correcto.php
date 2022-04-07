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

#CASO PRUEBAS
$nif='73243855X';
$nif='73273927K';//eric zaldivar, hijo de inma
$fecha_solicitud= "01/01/2022";
#CASO PRUEBAS
$nif='73243855X';


#caso eric 
//$nif='73273927K';//eric zaldivar, hijo de inma
$nif='';
$nombre="ÉRIC";
$apellido1="ZALDÍVAR";
$fnac="";

$nif='73161210G';//eric zaldivar, hijo de inma
$nombre= "";
$apellido1= "";
$fecha_solicitud= "21/03/2022";
//$nif='76920539Y';//hno silvia
$nif='';//hno silvia
$nombre="JAVIER IGNACIO";
$apellido1="PALOS";
$fnac="";
$fecha_solicitud= "21/03/2022";

$nif='76920539Y';//hno silvia
$nombre="";
$apellido1="";
$fnac="29/10/2019";
$fecha_solicitud= "21/03/2022";

if(is_numeric($nif[0]))
   $tipo='NIF';
else
   $tipo='NIE';

print("\nTIPO DOCUMENTO: $tipo\n");
$r=comprobarDiscapacidad($nif,$tipo,$nombre,$apellido1,$fnac,$fecha_solicitud);
print_r($r);
$respuesta=procesarRespuestaDiscapacidad($r);
print_r($respuesta);

