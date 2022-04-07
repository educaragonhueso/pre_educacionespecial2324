<?php
#carga de datos de matrícula del directorio de ficheros de matricula por mes
$basedatos=require_once '../../config/config_database.php';
require_once '../clases/ACCESO.php';

$fdatos='../../scripts/datos/datos_entrada/matricula_cursoanterior_final2223.csv';

$helper=new ACCESO($fdatos,$basedatos);
$fecha='2022-12-31';
$res=$helper->cargaMatriculaEspecial($fecha);
print(PHP_EOL."fin carga matricula, total: ".$res[0]);
print(PHP_EOL."filas omitidas: ".$res[1]);
print(PHP_EOL."TOTAL:  ".$res[2]);

?>
