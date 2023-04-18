<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once 'UtilidadesAdmision.php';
require_once DIR_BASE.'/clases/models/Centro.php';
#operaciones antes de iniciar la fase2
#ACTUALIZAMOS LAS VACANTES DE TODOS LOS CENTROS TENIENDO EN CUENTA Q LAS
//RESERVAS NO GENERAN VACANTES
//LIBERAMOS LAS VACANTES DE ALUMNOS Q HAN OBTENIDO PLAZA EN EL PROCESO PREVIO  

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$tipo='fase2';
$res2=0;
$res1=0;

$ccentros=new CentrosController($conexion);
$centro=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,$ccentros,$centro);

//print($utils->calculaPlazasTotalesCentroFase2('50000850','ebo'));
//exit();


//quitar puntos de hermanos, son 8, para los q tengan esa opciónpor ejemplo jaime mateos
#$res1=$utils->quitaPuntosHermanosFase2();
//actualizar vacantes de centros
#$res1=$utils->actualizaVacantesCentros();
//revisamos solicitudes para incluir vacantes de alumnos q teniendo plaza definitiva liberan plazas de reserva
$res2=$utils->liberaVacantesAlumnos();

if($res1==1 or $res2==2)
{
	echo PHP_EOL."Actualizadas vacantes centros para fase 2 a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
	
}
else print("Error actualizando vacantes centros: ".$res1.$res2);
//copiar tabla de solicitudes definitivas a la tabla de fase2
print("Copiando tabla fase2....".PHP_EOL);
$res=$utils->copiaTablaFase2('fase2',0);
$res=$utils->copiaTablaFase2('fase2_tmp',0);
if($res==1) echo "Copia tabla solicitudes".$tipo." realizada corectamente a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
else "Error copiando tabla $tipo, ERROR: $res";
?>
