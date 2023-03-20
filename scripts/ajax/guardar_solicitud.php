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
require_once DIR_BASE.'/clases/core/Comprobaciones.php';
require_once DIR_BASE.'/scripts/servidor/UtilidadesAdmision.php';
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
$fecha=date('d/m/Y');
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
$sc=new SolicitudController($rol,$conexion,$formsol,$estado_convocatoria,$log_nueva);
$utils=new UtilidadesAdmision($conexion,'','');
######################################################################################

$fsol_entrada=$_POST['fsol'];

parse_str($fsol_entrada, $fsol_salida);
if($modo=='GRABAR SOLICITUD')
{
   $log_nueva->warning("INICIO POST RECIBIDO NUEVA SOLICITUD:");
   $log_nueva->warning(print_r($_POST,true));

   //para el caso de alumnos que tienen centro de origen, este aparece con un asterisco
   if(isset($fsol_salida['id_centro_estudios_origen']))
      $fsol_salida['id_centro_estudios_origen']=trim($fsol_salida['id_centro_estudios_origen'],'*');
   else $fsol_salida['id_centro_estudios_origen']='';

   //comprobamos centro de origen, si hay reserva debe ser un centro válido
   if($fsol_salida['reserva']==1)
   {
      $id_centro_estudios_origen=$solicitud->getIdCentro($fsol_salida['id_centro_estudios_origen'],$log_nueva);
      if($id_centro_estudios_origen==0)
      {
         print("centroorigen".$fsol_salida['id_centro_estudios_origen']."_".$id_centro_estudios_origen);
         exit();
      }
   }
   //si el centro incluye un asterisco, para diferenciar ed especial del resto, lo quitamos
   $fsol_salida['id_centro_estudios_origen']=trim($fsol_salida['id_centro_estudios_origen'],'*');
   $fsol_salida['id_centro_estudios_origen']=$solicitud->getCentroOrigenId($fsol_salida['id_centro_estudios_origen'],$log_nueva);
}
else
{
   $log_actualizar->warning("INICIO POST RECIBIDO ACTUALIZAR SOLICITUD:");
   $log_actualizar->warning(print_r($_POST,true));
   $id_alumno=$_POST['id_alumno'];
}
######################################################################################
//SECCION PROCESO ENTRADA DATOS
######################################################################################
if($rol=='anonimo' or $rol=='alumno')
{
   //obtenemos el id del centro a partir del nombre indicado en el formualrio
   if($modo=='GRABAR SOLICITUD')
      $id_centro_destino=$solicitud->getCentroId($fsol_salida['id_centro_destino'],$log_nueva);
   else
      $id_centro_destino=$solicitud->getCentroId($fsol_salida['id_centro_destino'],$log_actualizar);
}
else
{
  // if($rol=='centro')
	   $id_centro_destino=$_POST['id_centro'];
}

$fsol_salida['id_centro_destino']=$id_centro_destino;

######################################################################################
if($id_centro_destino==0) 
{
   print('ERROR GUARDANDO DATOS: EL CENTRO SOLICITADO NO EXISTE O NO ES DE EDUCACIÓN ESPECIAL');
   exit();
}

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

$log_actualizar->warning("POST ACTUALIZAR");
if($modo=='GRABAR SOLICITUD')
{
   $fsol_salida=comprobarChecks($fsol_salida);
   ######################################################################################
   $log_nueva->warning("GRABANDO NUEVA SOLICITUD");
   $log_nueva->warning("======================================");
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
      $log_nueva->warning("SOLICITUD GUARDADADA CON ROL:MODO ".$rol.":".$modo);
      $log_nueva->warning(print_r($res,true));
      
      $aldata=explode(":",$res);
      if(isset($aldata[0])) $id_alumno=$aldata[0]; 
      else $id_alumno=$token;
      
      $correo=$solicitud->getCorreo($token,$log_nueva);
      $telefono=$solicitud->getTelefono($token,$log_nueva);
      $clave=$solicitud->getClave($token);
      $niftutor=$solicitud->getNifTutor($token);
         
      $url_solicitud_alumno=URL_BASE.EDICION."/index.php?token=$token";
      $enlace_solicitud_alumno="<a href='$url_solicitud_alumno' target='_blank'>ENLACE</a>";

      $enlace_correo="https://".$_SERVER['SERVER_NAME']."/".EDICION."/index.php?token=".$token;
      $contenido_correo="\nHas creado una nueva solicitud, para verla o modificarla debes usar este enlace:$enlace_solicitud_alumno ";
      $tipo_correo='Correo confirmación solicitud centros Educación Especial';
      
      if($rol!='admin' and $rol!='centro' and $rol!='sp')      
      {
         ######################################################################################
         $log_nueva->warning("ENVIANDO CORREO: $correo Contenido: $contenido_correo");
         $rescorreo=$notificacion->enviarCorreo('Confirmación Solicitud',$correo,$contenido_correo,$tipo_correo);
         $log_nueva->warning("RESPUESTA CORREO: ".$rescorreo);
         ######################################################################################
      }   
      
      ######################################################################################
      $log_nueva->warning("CORREO ENVIADO A: ".$id_alumno);
      $log_nueva->warning("CORREO: ".$correo);
      $log_nueva->warning("TOKEN: ".$token);
      ######################################################################################
      //si es nueva y anonima se devuelve la clave para acceder despues y se cambia el directorio de documentos
      if (!$conexion->commit()) 
      {
         echo "Commit transaction failed";
         exit();
      }
      print($res);
   }
}
else //MODIFICACION SOLICITUD
{
   //si el rol es de alumno modificamos los check ya q si no se han marcado no se modificarán
   if($rol=='alumno' or $rol=='anonimo')
   {
      //comprobamos los campos tipo check solo para usuarios alumnos 
      $fsol_salida=comprobarChecks($fsol_salida);
   }
   
   $correo=$solicitud->getCorreo($token,$log_actualizar);
   $telefono=$solicitud->getTelefono($token,$log_actualizar);
   
   ######################################################################################
   $log_actualizar->warning("DATOS ENTRADA:");
   $log_actualizar->warning(print_r($fsol_entrada,true));
   $log_actualizar->warning("DATOS PARSEADOS:");
   $log_actualizar->warning(print_r($fsol_salida,true));
   ######################################################################################
   //modificamos solicitud teniendo en cuenta la fase en la q esta el centro y el estado de la convocatoria
   $res=$solicitud->update($fsol_salida,$id_alumno,$token,$rol,$estado_convocatoria,$log_actualizar);
   //si la modifica el alumno se marca validada y se envia correo al centro
   if($rol=='alumno' or $rol=='anonimo')
   {
      $rus=$solicitud->setValidada($token);
      $correo_centro=$solicitud->getCorreoCentro($id_centro_destino);
      #!!!!!!!!!!!!!SOLO PARA PRUEBAS!!!!!!!!!!!!!!!!
      if(VERSION=='PRE')
         $correo_centro='educativosaragon@gmail.com'; 
      $token_centro=$solicitud->getTokenCentro($id_centro_destino);

      $log_actualizar->warning("DATOS CENTRO: correo: $correo_centro, token: $token_centro, id_centro: $id_centro_destino");
      if($correo_centro!=-1)
      {
         $url_solicitud_centro=URL_BASE.EDICION."/index.php?token=".$token."&tokencentro=$token_centro";
         $enlace_solicitud_centro="<a href='$url_solicitud_centro' target='_blank'>ENLACE</a>";
         //$contenido="Soliciutd modificada, pulsa en este $enlace_solicitud_centro para acceder";
         $contenido_correo="La solicitud se ha modificado, puedes acceder directamente desde este enlace: $enlace_solicitud_centro";
         $tipo_correo='Modificación solicitud Educación Especial curso 22/23';   
         $log_actualizar->warning("ENVIANDO CORREO CENTRO: contenido: $contenido_correo");
         $rescorreo=$notificacion->enviarCorreo('Solicitud modificada',$correo_centro,$contenido_correo,$tipo_correo);          
         $log_actualizar->warning("ENVIADO CORREO AL CENTRO, RESPUESTA: $rescorreo");
      }
      //enviamos modificaicon al alumnno
      $url_solicitud_alumno=URL_BASE.EDICION."/index.php?token=".$token;
      $enlace_solicitud_alumno="<a href='$url_solicitud_alumno' target='_blank'>ENLACE</a>";
      $contenido_correo_alumno="La solicitud se ha modificado, puedes acceder directamente desde este enlace: $enlace_solicitud_alumno";
      $tipo_correo='Modificación solicitud Educación Especial curso 22/23';   
      //$contenido="Soliciutd modificada, pulsa en este $enlace_solicitud_centro para acceder";
      #!!!!!!!!!!!!!SOLO PARA PRUEBAS!!!!!!!!!!!!!!!!
      if(VERSION=='PRE')
         $correo='educativosaragon@gmail.com'; 
      $log_actualizar->warning("ENVIANDO CORREO AL ALUMNO: $correo");
      $rescorreo=$notificacion->enviarCorreo('Solicitud modificada',$correo,$contenido_correo_alumno,$tipo_correo);          
      $log_actualizar->warning("ENVIADO CORREO AL ALUMNO, RESPUESTA: $rescorreo");
      //actualizamos el baremo para reflejar el valor final correcto
      //$aldata=$utils->getSolicitudesComprobarBaremo($token);
      //$dbaremo=$utils->recalcularBaremo($aldata[0]);
      //$utils->actualizarBaremo($dbaremo,$token,$log_actualizar);
   }
   //al haberse actualizado debe firmarse de nuevo, pero solo si lo hace el ciudadano, no la administracion
   if($rol!='admin' and $rol!='centro' and $rol!='sp')
   {
      $clave=$solicitud->getClave($token);
      $niftutor=$solicitud->getNifTutor($token);
      
      $enlacefirma_correo="<a href='https://".$_SERVER['SERVER_NAME']."/educacionespecial2223/index.php?firma=".$token."'>Firma</a>";
   
      $log_actualizar->warning("ENLACE FIRMA: ".$enlacefirma_sms);
      $log_actualizar->warning("TELEFONO: ".$telefono);
   
      #######################################################################################################
      $log_actualizar->warning("CORREO ENVIADO A: ".$id_alumno);
      $log_actualizar->warning("TOKEN: ".$token);
      #######################################################################################################
   }
   /*
   //modificamoss el baremo total y validado, tb para los hermanos
   //$aldata=$utils->getSolicitudesComprobarBaremo($token);
   //$log_actualizar->warning("ACT BAREMO: ");
   //$log_actualizar->warning(print_r($aldata,true));
   $dbaremo=$utils->recalcularBaremo($aldata[0]);
   $log_actualizar->warning("RES ACT BAREMO: ");
   $log_actualizar->warning(print_r($dbaremo,true));
   $utils->actualizarBaremo($dbaremo,$id_alumno,$log_actualizar);
   */
   if (!$conexion->commit()) 
   {
      echo "Commit transaction failed";
      exit();
   }
   print($res);
   print(":OK ACTUALIZANDO");
}

?>
