<?php
######################
# script para modificar estado solicitudes en fase 2, o indicar el centro
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'/LOGGER.php';
require_once DIR_BASE.'/clases//models/Centro.php';
require_once DIR_BASE.'/clases//models/Solicitud.php';
require_once DIR_BASE.'/scripts/servidor/UtilidadesAdmision.php';

######################################################################################
$log_cambio_centro_fase2=new logWriter('log_cambio_centro_fase2',DIR_LOGS);
$log_cambio_centro_fase2->warning("CAMBIANDO CENTRO");
$log_cambio_centro_fase2->warning(print_r($_POST,true));
######################################################################################

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);

$centro_origen=new Centro($conexion,'','ajax',0);//centro q tiene asignado en la actualidad
$centro_destino=new Centro($conexion,'','ajax',0);//cenro al q se le mueve o cambia
$centro_estudios_origen=new Centro($conexion,'','ajax',0);//centro del q proviene
$utils=new UtilidadesAdmision($conexion);

$centroactual=$_POST['centroactual'];
$id_centroactual=$_POST['idcentroactual'];
//centro elegido
$centroelegido=$_POST['centrodefinitivo'];
$id_centroelegido=$_POST['idcentrodefinitivo'];
$vacantes_centroelegido=$_POST['vacdefinitivo'];
$tipoestudios=$_POST['tipoestudios'];

$id_centro_origen=$_POST['idcorigen'];
$reserva=$_POST['reserva'];

$centro_origen->setId($id_centroactual);
$centro_destino->setId($id_centroelegido);
$centro_destino->setNombre($id_centroelegido);

$nombre_centro_destino=$centro_destino->getNombre();

//SI EL CENTRO ORIGEN Y DEF ES EL MISMO Y HAY RESERVA NO HACEMOS NADA
if($id_centro_origen==$id_centroelegido and $id_centro_origen!=0 and $reserva=='reserva1')
{
   $sql="UPDATE alumnos_fase2 SET centro_definitivo='".$nombre_centro_destino."',id_centro_definitivo=$id_centroelegido,tipo_modificacion='manual' where id_alumno=".$_POST['id_alumno'];
   $result=$conexion->query($sql);
   $conexion->close();
	print("OK");
   exit();
}

if($_POST['tipoestudios']=='ebo') $tipo=1;
else $tipo=2;

//comprobamos si libera plaza, obtenemos id del centro si lo hay y comproabmos si la reserva se ha liberado o no
$acestudiosorigen=$utils->getReservaPlaza($_POST['id_alumno'],'');

$areserva=$utils->checkReservaPlaza($_POST['id_alumno']);
$idcentro_estudios_origen=$acestudiosorigen[0];
$reserva=$areserva[0];
//incrementamos vacantes en centro de estudios origen si es q existe, no es cero
if($idcentro_estudios_origen!=0 and $reserva==1)
{
	$centro_estudios_origen->setId($idcentro_estudios_origen);
	$lr=$utils->liberaReserva($_POST['id_alumno']);
	$vo=$centro_estudios_origen->actualizaVacantes(0,0,$tipo,'+');
}

//decrementamos vacantes en centro destino
$vacantesd=$centro_destino->actualizaVacantes(0,0,$tipo,'-');
if(strtoupper($_POST['centroactual'])!='NOCENTRO')
{
//incrementamos vacantes en centro original
$vacanteso=$centro_origen->actualizaVacantes(0,0,$tipo,'+');
}
$sql="UPDATE alumnos_fase2 SET centro_definitivo='".$nombre_centro_destino."',id_centro_definitivo=$id_centroelegido,tipo_modificacion='manual' where id_alumno=".$_POST['id_alumno'];
$result=$conexion->query($sql);
$conexion->close();

$log_cambio_centro_fase2->warning("CONSULTA: \n$sql");
if ($result)
	print("OK");
	else     
	{
//	echo "ERROR No results".$sql;
	print_r($sql);
	}
?>
