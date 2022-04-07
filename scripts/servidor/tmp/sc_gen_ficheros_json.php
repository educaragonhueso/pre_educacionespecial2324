<?php 
#GENERADOR DATOS WEB PARA LISTADOS Y TABLAS DE LA WEB DEL CIUDADANO
require_once('../clases/GENDATOS_JSON.php');

print("GENERANDO FICHEROS DE DATOS JSON".PHP_EOL);


#DATOS DEL FORMULARIO INSCRICPCION
#directorio para cargar datos de campos válidos
$ruta='../../datosweb/';

$dimension=1;	
$listado = new \GENDATOS_JSON();

#datos de centros de especial y municipios
$fdestino=$ruta."centros_especial_municipios";		
$sql="SELECT CONCAT('\"nc\"',':','\"',bc.nombre_centro,'\",','\"mu\"',':','\"',localidad,'\"') FROM centros bc WHERE nombre_centro not like 'sp%' AND nombre_centro not like '%global%' and clase_centro='especial'";
$res=$listado->genJsonData($sql,$fdestino);

#datos de centros y municipios
$fdestino=$ruta."centros_general_completo";		
$sql="SELECT CONCAT('\"nc\"',':','\"',bc.nombre_centro,'\",','\"mu\"',':','\"',localidad,'\"') FROM centros bc WHERE nombre_centro not like 'sp%' AND nombre_centro not like '%global%'";
$res=$listado->genJsonData($sql,$fdestino);

#datos de municipios
$fdestino=$ruta."municipios";		
$sql="SELECT CONCAT('\"mu\"',':','\"',municipio,'\"') FROM municipios";
$res=$listado->genJsonData($sql,$fdestino);

?>

