<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
#require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE."/config/config_global.php";
require_once DIR_BASE."/config/config_soap.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_BASE.'/clases/core/Notificacion.php';
require_once DIR_APP.'parametros.php';

######################################################################################
$log_nueva=new logWriter('log_nueva_solicitud',DIR_LOGS);
$log_actualizar=new logWriter('log_actualizar_solicitud',DIR_LOGS);
######################################################################################
//SECCION INSTANCIAS Y VARIABLES
######################################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$conexion->autocommit(FALSE);

$solicitud=new Solicitud($conexion);
$fecha=date();
$notificacion=new Notificacion(WSDL_CORREO,AP_ID,$fecha,0);
//SECCION ASIGNACION VARIABLES
//vemos si es añadir nueva o modificar existente
$modo=$_POST['modo'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$rol=$_POST['rol'];
$token=$_POST['token'];

//if($token==0)
  // $token=bin2hex(random_bytes(8));;
//obtenemos el correo para posible envio de firma
//directorio de documentacion
$dirbasedoc="../fetch/uploads/";
#mensaje de respuesta del envio de SMS
$ressms="Fase de pruebas";

require_once DIR_BASE.'/includes/form_solicitud.php';
$sc=new SolicitudController($rol,$conexion,$formsol,$log_nueva);

######################################################################################
if($modo=='GRABAR SOLICITUD')
{
   $log_nueva->warning("INICIO POST RECIBIDO NUEVA SOLICITUD:");
   $log_nueva->warning(print_r($_POST,true));
}
else
{
   $log_actualizar->warning("INICIO POST RECIBIDO ACTUALIZAR SOLICITUD:");
   $log_actualizar->warning(print_r($_POST,true));

   $id_alumno=$_POST['id_alumno'];
}
######################################################################################
//para el caso de alumnos que tienen centro de origen, este aparece con un asterisco
if(isset($_POST['id_centro_estudios_origen']))
	$_POST['id_centro_estudios_origen']=trim($_POST['id_centro_estudios_origen'],'*');
else $_POST['id_centro_estudios_origen']='';

$fsol_entrada=$_POST['fsol'];

//el capo baremo pts total no está en el formulario asi q lo añadimos
//$fsol_entrada.="&baremo_ptstotal=".$_POST['ptsbaremo'];
parse_str($fsol_entrada, $fsol_salida);

//SECCION PROCESO ENTRADA DATOS
######################################################################################
if($rol=='anonimo' or $rol=='alumno')
{
   //obtenemos el id del centro a partir del nombre indicado en el formualrio
   $id_centro_destino=$solicitud->getCentroId($fsol_salida['id_centro_destino'],$log_nueva);
}
else
{
   if($rol=='centro')
	   $id_centro_destino=$_POST['id_centro'];
	if($rol=='admin' or $rol=='spzaragoza' or $rol=='spteruel' or $rol=='sphuesca')
	{
	   parse_str($fsol_entrada, $fsol_tmp);
      if($modo=='ACTUALIZAR SOLICITUD')
		   $id_centro_destino=$solicitud->getCentroId($fsol_tmp['id_centro_destino'],$log_actualizar);
      else
		   $id_centro_destino=$solicitud->getCentroId($fsol_tmp['id_centro_destino'],$log_nueva);
   
	}
}

$fsol_salida['id_centro_destino']=$id_centro_destino;
######################################################################################
$log_actualizar->warning("ARRAY DE SALIDA:");
$log_actualizar->warning(print_r($fsol_salida,true));
######################################################################################
if($id_centro_destino==0) 
{
   print('ERROR GUARDANDO DATOS: EL CENTRO SOLICITADO NO EXISTE');
   exit();
}
//si el centro incluye un asterisco, para diferenciar ed especial del resto, lo quitamos
$fsol_salida['id_centro_estudios_origen']=trim($fsol_salida['id_centro_estudios_origen'],'*');
//procesamos los centros d elos hermanos de alumnos en admisión
//$fsol_salida['hermanos_admision_id_centro_origen1']=$solicitud->getCentroId($fsol_salida['hermanos_admision_id_centro_origen1'],$log_nueva);

//obtenemos los ids de los centros de origen según los ids recibidos
$fsol_salida['id_centro_estudios_origen']=$solicitud->getCentroId($fsol_salida['id_centro_estudios_origen'],$log_nueva);

//procesmoas los centros adicionales
for($i=1;$i<7;$i++)
	{
	$indice="id_centro_destino".$i;
	if($fsol_salida[$indice]!='') 
		{
		$valor=$solicitud->getCentroId(trim($fsol_salida[$indice],'*'),$log_nueva);
		if($valor!=0) $fsol_salida[$indice]=$valor;
		}
	}
//comprobamos los campos tipo check: padres trabajan en el cenntro y renta inferior
if(!isset($fsol_salida['baremo_marcado_proximidad_domicilio']))
	$fsol_salida['baremo_marcado_proximidad_domicilio']=0;
if(!isset($fsol_salida['baremo_proximidad_domicilio']))
	$fsol_salida['baremo_proximidad_domicilio']=0;
if(!isset($fsol_salida['baremo_tutores_centro']))
	$fsol_salida['baremo_tutores_centro']=0;
if(!isset($fsol_salida['baremo_renta_inferior']))
	$fsol_salida['baremo_renta_inferior']=0;
if(!isset($fsol_salida['baremo_acogimiento']))
	$fsol_salida['baremo_acogimiento']=0;
if(!isset($fsol_salida['baremo_genero']))
	$fsol_salida['baremo_genero']=0;
if(!isset($fsol_salida['baremo_terrorismo']))
	$fsol_salida['baremo_terrorismo']=0;
if(!isset($fsol_salida['baremo_parto']))
	$fsol_salida['baremo_parto']=0;

if(!isset($fsol_salida['baremo_marcado_numerosa']))
	$fsol_salida['baremo_marcado_numerosa']=0;
if(!isset($fsol_salida['baremo_tipo_familia_numerosa']))
	$fsol_salida['baremo_tipo_familia_numerosa']=0;

if(!isset($fsol_salida['baremo_marcado_monoparental']))
	$fsol_salida['baremo_marcado_monoparental']=0;
if(!isset($fsol_salida['baremo_tipo_familia_monoparental']))
	$fsol_salida['baremo_tipo_familia_monoparental']=0;
//comprobamos los campos tipo check: padres trabajan en el cenntro y renta inferior
if(!isset($fsol_salida['nuevaesc']))
	$fsol_salida['nuevaesc']=0;
if(!isset($fsol_salida['num_hbaremo']))
	$fsol_salida['num_hbaremo']=0;
if(!isset($fsol_salida['cumplen']))
	$fsol_salida['cumplen']=0;
if(!isset($fsol_salida['oponenautorizar']))
	$fsol_salida['oponenautorizar']=0;

$log_actualizar->warning("POST ACTUALIZAR");
if($modo=='GRABAR SOLICITUD')
{
######################################################################################
   $log_nueva->warning("DATOS ENTRADA:");
   $log_nueva->warning(print_r($fsol_entrada,true));
   $log_nueva->warning("DATOS PARSEADOS:");
   $log_nueva->warning(print_r($fsol_salida,true));
######################################################################################
   $fsol_salida['token']=$token;
   $res=$solicitud->save($fsol_salida,$_POST['idsol'],$rol,$log_nueva);
   if($res<=0) 
   {

      $log_nueva->warning("ERROR GRABANDO SOLICITUD");

      if($res==-1)	$res='ERROR GUARDANDO DATOS: Ya existe un alumno con esos datos';
      if($res==-2)	$res='ERROR GUARDANDO DATOS';
      if($res==-3)	$res='ERROR GUARDANDO DATOS: Falta dni del tutor';
      if($res==-4)	$res='ERROR GUARDANDO DATOS: Faltan datos de hermanos en admisión';
      print($res);
   }
   else
   { 
      $log_nueva->warning("SOLICITUD GUARDADADA ROL:MODO ".$rol.":".$modo);
      $log_nueva->warning(print_r($res,true));
      
      $aldata=explode(":",$res);
      if(isset($aldata[0])) $id_alumno=$aldata[0]; 
      else $id_alumno=$token;

      
      $correo=$solicitud->getCorreo($token,$log_nueva);
      $telefono=$solicitud->getTelefono($token,$log_nueva);
      $clave=$solicitud->getClave($token);
      $niftutor=$solicitud->getNifTutor($token);

      $enlacefirma_correo="<a href='https://".$_SERVER['SERVER_NAME']."/educacionespecial/index.php?firma=".$token."'>Firma</a>";
      $enlacefirma_sms="Usuario: $niftutor Clave: $clave\n";
      $enlacefirma_sms.="Pulsa en este enlace para firmar tu solicitud:\n";
      $enlacefirma_sms.="https://".$_SERVER['SERVER_NAME']."/educacionespecial/index.php?firma=".$token;
      $contenido="\n<br>Recuerda que puedes entrar cuando quieras para modificarla usando el usuario: $niftutor y clave: $clave\n<br>";

      $log_nueva->warning("OBTENIDO CORREO: ".$correo);
      if($rol!='admin' and $rol!='centro' and $rol!='sp')      
      {
         //$rescorreo=$notificacion->enviarCorreo($id_alumno,$enlacefirma_correo,$correo,$contenido);
         if($telefono!=0)
            $ressms=$notificacion->enviarSMS($telefono,$enlacefirma_sms);          
      }   
      
      $log_nueva->warning("CORREO ENVIADO A: ".$id_alumno);
      $log_nueva->warning("CORREO: ".$correo);
      $log_nueva->warning("TOKEN: ".$token);
      $log_nueva->warning(print_r($ressms,true));
		$log_nueva->warning("ENLACE FIRMA CORREO: ".$enlacefirma_correo);
		$log_nueva->warning("ENLACE FIRMA SMS: ".$enlacefirma_sms);
      
      //si es nueva y anonima se devuelve la clave para acceder despues y se cambia el directorio de documentos
      if($rol=='anonimo')
      {
         $dirdoc=$dirbasedoc.'/'.$token;
         if(!is_dir($dirdoc))
            mkdir($dirdoc);
         $nuevodirdoc=$dirbasedoc.'/'.$id_alumno;
         $log_nueva->warning("CAMBIADO DIRECTORIO DE DOC: ".$dirdoc.":".$nuevodirdoc);
   
         if(rename($dirdoc,$nuevodirdoc)) 
         {
            print($res);
            if (!$conexion->commit()) 
            {
               echo "Commit transaction failed";
               exit();
            }
         }
         else print("ERROR RENOMBRANDO DIRECTORIO");
      }
      else
      {
         $dirdoc=$dirbasedoc.'/'.$token;
         if(!is_dir($dirdoc))
            mkdir($dirdoc);
         $nuevodirdoc=$dirbasedoc.'/'.$id_alumno;
         $log_nueva->warning("CAMBIADO DIRECTORIO DE DOC: ".$dirdoc.":".$nuevodirdoc);
   
         if(rename($dirdoc,$nuevodirdoc)) 
         {
            print($res);
            if (!$conexion->commit()) 
            {
               echo "Commit transaction failed";
               exit();
            }
         }
         else print("ERROR RENOMBRANDO DIRECTORIO");
      }
   }
}
else 
{
   $correo=$solicitud->getCorreo($token,$log_actualizar);
   $telefono=$solicitud->getTelefono($token,$log_actualizar);
   
   ######################################################################################
      $log_actualizar->warning("DATOS ENTRADA:");
      $log_actualizar->warning(print_r($fsol_entrada,true));
      $log_actualizar->warning("DATOS PARSEADOS:");
      $log_actualizar->warning(print_r($fsol_salida,true));
   ######################################################################################
   #######################################################################################################
   $log_actualizar->warning("ACTUALIZANDO, OBTENIDO CORREO: ".$correo);
   $log_actualizar->warning("ACTUALIZANDO, OBTENIDO ROL: ".$rol);
   #######################################################################################################
   //modificamos solicitud teniendo en cuenta la fase en la q esta el centro y el estado de la convocatoria
   $res=$solicitud->update($fsol_salida,$id_alumno,$token,$log_actualizar);
   //si la modifica el alumno se marca validada y se envia correo al centro
   if($rol=='alumno' or $rol=='anonimo')
   {
      $rus=$solicitud->setValidada($token);
      $correo_centro=$solicitud->getCorreoCentro($id_centro_destino);
      if($correo_centro!=-1)
      {
         $contenido="El alumno $token ha modificado su solicitud";
         //$rescorreo=$notificacion->enviarCorreo($$enlacefirma_correo,$correo_centro,$contenido);          
      }
   }
   #######################################################################################################
   $log_actualizar->warning("RESULTADO ACTUALIZAR: $res");
   #######################################################################################################
   
   //al haberse actualizado debe firmarse de nuevo, pero solo si lo hace el ciudadano, no la administracion
   if($rol!='admin' and $rol!='centro' and $rol!='sp')
   {
      $clave=$solicitud->getClave($token);
      $niftutor=$solicitud->getNifTutor($token);
      
      $enlacefirma_correo="<a href='https://".$_SERVER['SERVER_NAME']."/educacionespecial/index.php?firma=".$token."'>Firma</a>";
      $enlacefirma_sms="Usuario: $niftutor Clave: $clave\n";
      $enlacefirma_sms.="Pulsa en este enlace para firmar tu solicitud:\n";
      $enlacefirma_sms.="https://".$_SERVER['SERVER_NAME']."/educacionespecial/index.php?firma=".$token;
   
      $log_actualizar->warning("ENLACE FIRMA: ".$enlacefirma_sms);
      $log_actualizar->warning("TELEFONO: ".$telefono);
   
      //$rescorreo=$notificacion->enviarCorreo($id_alumno,$enlacefirma_correo,$correo);          
      if($telefono!=0)
         $ressms=$notificacion->enviarSMS($telefono,$enlacefirma_sms);          

      #######################################################################################################
      $log_actualizar->warning("CORREO ENVIADO A: ".$id_alumno);
      $log_actualizar->warning("TOKEN: ".$token);
      $log_actualizar->warning(print_r($ressms,true));
      #######################################################################################################
   }
   if (!$conexion->commit()) 
   {
      echo "Commit transaction failed";
      exit();
   }
   print($res);
   print("OK ACTUALIZANDO");
}

?>
