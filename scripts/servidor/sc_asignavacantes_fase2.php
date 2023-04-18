<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Alumno.php';
#operaciones antes de iniciar la fase2
require_once 'UtilidadesAdmision.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$log_asigna_fase2=new logWriter('log_asigna_fase2',DIR_LOGS);
$ccentros=new CentrosController(0,$conexion);
$tcentros_fase2=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,'',$tcentros_fase2,1);
$post=0;
if(isset($_POST['subtipo']))
	$tipoestudios=str_replace('lfase2_sol_','',$_POST['subtipo']);
else{	$post=0;	$tipoestudios='ebo';}

$avac=10;
$j=0;
$post=1;

//VACIAMOS EL CAMPO DE RESERVAS D ELOS CENTROS PARA COMPUTAR LAS RESERVAS Q SE VAYAN GENERANDO
$utils->iniReservasCentros();
//OBTENEMOS DATOS DE LOS CENTROS DE ESPECIAL
$centros_fase2=$tcentros_fase2->getCentrosFase2();
foreach($centros_fase2 as $cf)
{
   print("ID CENTRO: ".$cf['id_centro']);
   print("NOMBRE CENTRO: ".$cf['nombre_centro']);
   $tcentros_fase2->setId($cf['id_centro']);
   $v=$tcentros_fase2->getVacantesCentroFase2($log_asigna_fase2);
   print_r($v);
   $res=$tcentros_fase2->setVacantes($v);
}
//ANTES DE EMPEZAR REPLCIAMOS LA TABAL DE ALUMNOS A LA TABAL TEMPORAL QUE
//USAREMOS EN EL RESETEO
if(!$utils->copiaTablaTmpFase2()) 
{
   $log_asigna_fase2->warning("ERROR COPIANDO TABAL TMP FASE2");
   exit();
}
//asignar vacantetes de cada centro a centro elegido en primera opcion (oopcion 0)
do{
   $log_asigna_fase2->warning("INICIOLOG INICIANDO PROCESO POR $j VECES");
	if(!$post) print("INICIANDO PROCESO POR $j VECES".PHP_EOL);
	if($avac==0) break;
	
	for($i=0;$i<=6;$i++)
	{
      $log_asigna_fase2->warning("EMPEZANDO CENTRO VACANTE NUMERO: $i");
		if(!$post) print("EMPEZANDO CENTRO $i, AVAC: $avac".PHP_EOL);
		
		$alumnos_fase2=$utils->getAlumnosFase2('actual');
		$centros_fase2=$tcentros_fase2->getCentrosFase2();
		$avac=$utils->asignarVacantesCentros($centros_fase2,$alumnos_fase2,$i,$tipoestudios,$post);
		
		if($avac==0) break;
		if($avac==-2)//si se ha liberado reserva 
      {
			$reset=$utils->resetAlumnosFase2();//recargamos de nuevo la tabla de laumnos fase2 con los valores originales previamente almacenados en la alumnos_fase2_tmp
         //VACIAMOS EL CAMPO DE RESERVAS D ELOS CENTROS
         //$utils->iniReservasCentros();
			$j++; 
			break;
		}
	}
}while($avac==-2);//mientras se este liberando una reserva hay q volver a empezar
//asignamos las vacantes a los alumnos en la tabla original
$utils->asignaVacantesAlumnos();
if($avac==1)
{
   $log_asigna_fase2->warning("Asignadas vacantes centros para fase 2");
	echo PHP_EOL."Asignadas vacantes centros para fase 2 a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
	return;	
}
elseif($avac=="NO CENTRO") print(PHP_EOL."Error asignando vacantes centros fase2, NO CENTRO");
elseif($avac==-1) print("Array de alumnos o de centros vacio");
elseif($avac==-2) print("Alumnos libera reserva");
elseif($avac==0) print("Error asignando");

exit();
?>
