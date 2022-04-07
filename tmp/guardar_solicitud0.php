<?php
######################
# script para modificar/editar y crear solicitudes
######################
/*
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/config/config_global.php";
require_once DIR_BASE.'core/ControladorBase.php';
require_once DIR_BASE.'core/EntidadBase.php';
require_once DIR_BASE.'controllers/SolicitudController.php';
*/

//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
######################################################################################

//SECCION INSTANCIAS Y VARIABLES
######################################################################################
$conexion=new Conectar();
$log_actualizar=new logWriter('log_actualizar_solicitud',DIR_LOGS);
$log_nueva=new logWriter('log_nueva_solicitud',DIR_LOGS);
######################################################################################

//SECCION PROCESO ENTRADA DATOS
######################################################################################

$log_nueva->warning(print_r($_POST,true));
exit();
$sc=new SolicitudController();
$conexion=$sc->getConexion();
$tsol=new Solicitud($conexion);

$modo=$_POST['modo'];
$rol=$_POST['rol'];
$estado_convocatoria=$_POST['estado_convocatoria'];

if(isset($_POST['id_centro_estudios_origen']))
	$_POST['id_centro_estudios_origen']=trim($_POST['id_centro_estudios_origen'],'*');
else $_POST['id_centro_estudios_origen']='';

$fsol_entrada=$_POST['fsol'];
######################################################################################
$log_actualizar->warning("POST RECIBIDO ACTUALIZANDO:");
$log_actualizar->warning(print_r($fsol_entrada,true));
######################################################################################
if($rol!='alumno')
	{
	if($rol=='centro')
		$id_centro_destino=$_POST['id_centro_destino'];
	if($rol=='admin')
		{
		parse_str($fsol_entrada, $fsol_tmp);
		$id_centro_destino=$tsol->getCentroId($fsol_tmp['id_centro_destino'],'especial');
		}
	}
$fsol_entrada.="&baremo_ptstotal=".$_POST['ptsbaremo'];

parse_str($fsol_entrada, $fsol_salida);
if($rol=='alumno')
	{
	$fase_sol=$tsol->getFaseSol($_POST['idsol']);
	$log_nueva->warning("OBTENIDA FASE SOLICITUD: ".$fase_sol);
	if($fase_sol=='validada') return 'ERROR, NO SE PUEDE MODIFICAR UNA SOLICITUD APTA';
	$log_nueva->warning("SOLICITUD NO ESTA EN ESTADO VALIDADA, ESTADO: ".$estado_sol);
	$id_centro_destino=$tsol->getCentroId($_POST['id_centro_destino'],'especial');
	if($id_centro_destino==0) 
		{
		print('ERROR GUARDANDO DATOS: EL CENTRO SOLICITADO NO EXISTE');
		exit();
		}
	$fsol_salida['id_centro_destino']=$id_centro_destino;
	$log_nueva->warning("SOLICITUD NUEVA DE ALUMNO, NOMBRE CENTRO: ".$_POST['id_centro_destino']);
	$log_nueva->warning("SOLICITUD NUEVA DE ALUMNO, ID CENTRO: ".$fsol_salida['id_centro_destino']);
	}
if(!isset($fsol_salida['id_centro_destino']) or $fsol_Salida['id_centro_destino']<=1)
	$fsol_salida['id_centro_destino']=$id_centro_destino;


$fsol_salida['id_centro_estudios_origen']=trim($fsol_salida['id_centro_estudios_origen'],'*');

######################################################################################
$log_actualizar->warning("VALOR CENTRO ORIGEN DESPUES:");
$log_actualizar->warning($fsol_salida['id_centro_estudios_origen']);
######################################################################################

//obtenemos los ids de los centros de origen según los ids recibidos
$fsol_salida['id_centro_estudios_origen']=$tsol->getCentroId($fsol_salida['id_centro_estudios_origen']);

//if($fsol_salida['id_centro_estudios_origen']==$fsol_salida['id_centro_destino']){ print("10 ERROR GUARDANDO: Centro origen y destino iguales"); exit();}

for($i=1;$i<7;$i++)
	{
	$indice="id_centro_destino".$i;
	if($fsol_salida[$indice]!='') 
		{
		$valor=$tsol->getCentroId(trim($fsol_salida[$indice],'*'));
		if($valor!=0) $fsol_salida[$indice]=$valor;
		}
	}
//comprobamos los campos tipo check: padres trabajan en el cenntro y renta inferior
if(!isset($fsol_salida['baremo_tutores_centro']))
	$fsol_salida['baremo_tutores_centro']=0;
if(!isset($fsol_salida['baremo_renta_inferior']))
	$fsol_salida['baremo_renta_inferior']=0;
//comprobamos los campos tipo check: padres trabajan en el cenntro y renta inferior
if(!isset($fsol_salida['nuevaesc']))
	$fsol_salida['nuevaesc']=0;
if(!isset($fsol_salida['num_hadmision']))
	$fsol_salida['num_hadmision']=0;
if(!isset($fsol_salida['num_hbaremo']))
	$fsol_salida['num_hbaremo']=0;
if(!isset($fsol_salida['cumplen']))
	$fsol_salida['cumplen']=0;
if(!isset($fsol_salida['oponenautorizar']))
	$fsol_salida['oponenautorizar']=0;
//Si es nueva solicitud
if($modo=="GRABAR SOLICITUD")
	{
		$log_nueva->warning("SOLICITUD NUEVA, FORMULARIO RECIBIDO - DATOS BRUTOS:");
		$log_nueva->warning($fsol_entrada);
		$log_nueva->warning("FORMULARIO RECIBIDO:".$modo);
		$log_nueva->warning(json_encode($fsol_salida));
		$log_nueva->warning("GRABANDO NUEVA SOLICITUD...");
		$log_nueva->warning(print_r($fsol_salida,true));
		$res=$tsol->save($fsol_salida,$_POST['idsol'],$rol);
		$log_nueva->warning("RESULTADO ACT: ".$res);
		$log_nueva->warning(print_r($res,true));
	}
else 
	{
		#######################################################################################################
		$log_actualizar->warning("ACTUALIZAR FORMULARIO RECIBIDO - DATOS BRUTOS:");
		$log_actualizar->warning($fsol_entrada);
		$log_actualizar->warning("ACTUALIZAR FORMULARIO RECIBIDO: ".$modo."ESTADO CONVOCATORIA: $estado_convocatoria ");
		$log_actualizar->warning(json_encode($fsol_salida));
		$log_actualizar->warning("ACTUALIZANDO SOLICITUD...");
		$log_actualizar->warning(print_r($fsol_salida,true));
		#######################################################################################################
		//modificamos solicitud teniendo en cuenta la fase en la q esta el centro y el estado de la convocatoria
		$res=$tsol->update($fsol_salida,$_POST['idsol']);
	}
if($res<=0) 
{
	if($res==-1)	print('ERROR GUARDANDO DATOS: YA EXISTE UN USUARIO CON ESE NOMBRE DE USUARIO');
	if($res==-2)	print('ERROR GUARDANDO DATOS: YA EXISTE UN ALUMNO CON ESOS DATOS');
	if($res==0)	print('ERROR GUARDANDO DATOS: CONTACTA CON EL AMDINISTRADOR, lhueso@aragon.es');
}
else
{ 
	//si es nueva y anonima se devuelve la clave para acceder despues
	if($modo=="GRABAR SOLICITUD" and $rol=='alumno')
		print($res);
	else
 		print_r($sc->showSolicitud($res));
}
?>
