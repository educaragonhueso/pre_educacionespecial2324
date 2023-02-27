<?php
#carga de datos de matrícula del directorio de ficheros de matricula por mesa
//los datos se cargan en la tabla matríucla q previamente se borra
$basedatos=require_once '../../config/config_database.php';
require_once '../clases/ACCESO.php';

$fdatos='../../scripts/datos/datos_entrada/matricula_ee2324_27f.csv';

$helper=new ACCESO($fdatos,$basedatos);
$fecha='2023-12-31';
$curso='2023';

$res=$helper->cargaMatriculaEspecial($fecha,$curso);
print(PHP_EOL."fin carga matricula, total: ".$res[0]);
print(PHP_EOL."filas omitidas: ".$res[1]);
print(PHP_EOL."TOTAL:  ".$res[2]);

?>
