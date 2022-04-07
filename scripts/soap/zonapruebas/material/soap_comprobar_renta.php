<?php

include("funciones_renta.php");
$año='2018';

/*JUEGO DE CASOS 1*/
//caso conjunta y varias casillas
$nif1='99999997E';
//$nif1='9999999E';
$nombre='JAIME';
$apellido1='CABRERA';
//caso conjunta sin imputaciones
$nif2='99999992V';
$nombre_tutor2='BEATRIZ';
$apellido1_tutor2='CASTILLO';
/*
//JUEGO DE CASOS 2
//caso conjunta con imputaciones
$nif1='Y9999995T';
$nombre_tutor1='PIERRETTE';
$apellido1_tutor1='MARGAN';
//caso conjunta con imputaciones
$nif1='X9999993F';
$nombre1='SHAORAN';
//JUEGO DE CASOS 3
//caso conjunta y varias casillas
$nif1='X999999F';
$nif1='X9999993F';
$nombre_tutor1='SHAORAN';
$apellido1_tutor1='QUANT';
//caso conjunta sin imputaciones
$nif2='99999992V';
$nombre_tutor2='BEATRIZ';
$apellido1_tutor2='CASTILLO';
$pruebas='SI';
*/

$resp=comprobarRenta($nif1,'2018',$nombre,$apellido1);
print($resp);
$r=calcularRenta($resp);

print($r);
