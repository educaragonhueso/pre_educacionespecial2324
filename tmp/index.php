<?php
#VVARIABLES DE SESION
session_start();
if(!$_SESSION) 
   header("location: login_activa.php");
date_default_timezone_set("Europe/Madrid");
setlocale(LC_TIME, "spanish");

if(isset($_SESSION['dir_base']))
   $dir_base=$_SESSION['dir_base'];
else
   $dir_base='./';

if(isset($_GET['tokencentro']))
   $rol='centro';
if(isset($_SESSION['rol'])) 
   $rol=$_SESSION['rol'];
else
   $rol='anonimo';
if(isset($_SESSION['convocatoria'])) 
   $convocatoria=$_SESSION['convocatoria'];
else
   $convocatoria='educacionespecial2223';

if(isset($_SESSION['id_centro']))
   $id_centro=$_SESSION['id_centro'];
else
   $id_centro=-1;
   
if(isset($_GET['token']) and $_SESSION['rol']=='anonimo')
{
   $_SESSION['rol']='alumno';
   $_SESSION['usuario_autenticado']=1;
}
if(isset($_GET['provincia']))
   $provincia=$_SESSION['provincia'];
 
 
$estado_convocatoria=$_SESSION['estado_convocatoria'];
#CLASES
require_once $dir_base.'/controllers/SolicitudController.php';
require_once $dir_base.'/controllers/ListadosController.php';
require_once $dir_base.'/clases/core/Conectar.php';
require_once $dir_base.'/clases/models/Centro.php';
require_once $dir_base.'/clases/models/Solicitud.php';
require_once $dir_base.'/scripts/clases/LOGGER.php';
require_once 'config/config_global.php';
#LOGS
$DIR_LOGS=$dir_base.'/logs/';
$log_listados_solicitudes=new logWriter('log_listados_solicitudes',$DIR_LOGS);
$log_editar_solicitud=new logWriter('log_editar_solicitud',$DIR_LOGS);
#VARIABLES
$conectar=new Conectar();
$conexion=$conectar->conexion();
$tcentro=new Centro($conexion,$id_centro,'ajax');
$url='login_activa.php';
if(isset($_GET['tokencentro']))
{
   $check=1;
   if(isset($_GET['token']))
      $check=$tcentro->checkAlumnoCentro($_GET['tokencentro'],$_GET['token']);   

   $id_centro=$tcentro->getIdCentroFromToken($_GET['tokencentro']);   
   if($id_centro==0 or $check==0) 
   {
      print(PHP_EOL."EL CENTRO O EL ALUMNO NO ES VÁLIDO".PHP_EOL); 
      print(PHP_EOL."REDIRIEGIENDO PÁGINA PRINCIPAL...".PHP_EOL); 
      header("Refresh:3; URL=$url");
      exit();
   }
   else
   {
      $tcentro->setIdCentro($id_centro);
      $rol='centro';
      $_SESSION['rol']='centro';
   }
}
$nombre_centro=$tcentro->getNombre();
$msg_validacion="";
$solicitud=new Solicitud($conexion);

if(isset($_GET['token']))
{
   $id_centro=$solicitud->getIdCentroFromToken($_GET['token'],$log_editar_solicitud);
   $token=$_GET['token'];
   $id_alumno=$solicitud->getIdFromToken($token,$log_editar_solicitud);
   $_SESSION['id_alumno']=$id_alumno;
   $_SESSION['token']=$_GET['token'];
}
$_SESSION['id_centro']=$id_centro;
$solo_lectura=0;
$solcentro=0;
if(isset($_GET['solcentro']))
   $solcentro=1;

if($_SESSION['version']=='PRE' or $_SESSION['mantenimiento']=='SI')
   print_r($_SESSION);
##CABECERA##
include('includes/head.php');
##MENUS SUPERIOR##
include('includes/menusuperior.php');
include('includes/form_solicitud.php');

//si el usuario ya existe, recogemos el token, si lo hay
if(isset($_GET['token']) or $rol=='alumno')
{
   
   //añadimos htmlk para despues poder agregar datos de solicitudes y matriucla desde el rol de admin
   $cablistados='<div id="l_matricula" style="width:100%">';
   $scontroller=new SolicitudController($rol,$conexion,$formsol,$log_editar_solicitud);
 
   if(isset($_GET['token']))
   {
      $id_centro=$solicitud->getIdCentroFromToken($token,$log_editar_solicitud);
      $fase_solicitud=$solicitud->getFaseSolicitudFromToken($token,$log_editar_solicitud);
      if($fase_solicitud=='borrador')
         header("Refresh:3; URL=$url");
   }
   $log_editar_solicitud->warning(" LOGINICIO: EDITANTO SOLICITUD, id_alumno: ".$id_alumno." id centro:".$id_centro);   

   if($estado_convocatoria>ESTADO_FININSCRIPCION or $rol=='centro') 
      $solo_lectura=1;
   
   $sform=$scontroller->showFormSolicitud($id_alumno,$id_centro,$rol,1,0,$estado_convocatoria,$conexion,$convocatoria,$log_editar_solicitud,$solo_lectura);
   $botonimp='<a href="imprimirsolicitud.php?id='.$id_alumno.'" target="_blank"><input class="btn btn-primary imprimirsolicitud"  type="button" value="Vista Previa Impresion Documento"/></a>';
   $tokenhtml='<input type="hidden" id="token" name="ntoken" value="'.$token.'">';
   if($rol!='alumno' and $rol!='anonimo')
      print_r($tokenhtml.$cablistados.$sform.$botonimp);
   else if($rol=='alumno' or $rol=='anonimo')
   {
      $sform=preg_replace('/<span>PUNTOS BAREMO VALIDADOS:<span id="id_puntos_baremo_validados">.*<\/span>/','',$sform);
      print_r($tokenhtml);
      print_r($cablistados);
      print_r($sform.$botonimp);
      print_r('</div>');
   }
   else
      print_r($tokenhtml.$sform.$botonimp);
}
else
{
   /*
   if(isset($_GET['firma']) and $estado_convocatoria<10)
   {
      $validacion=$solicitud->checkSolicitud($_GET['firma']);
      $n=count((array)$validacion);
      if($n>0)
      { 
         $firma=$solicitud->firmarSolicitud($validacion->id_alumno);
         $_SESSION['rol']='alumno';
         $rol='alumno';
         $_SESSION['id_alumno']=$validacion->id_alumno;
         $_SESSION['id_centro']=$validacion->id_centro_destino;
         if($firma)
            $msg_firma="<h1>LA SOLICITUD ESTA FIRMADA</h1>";
         else
            $msg_firma="<h1>LA SOLICITUD NO SE HA FIRMADO</h1>";
      echo "$msg_firma";
      }

   }
   */
   $lcontroller=new ListadosController('alumnos',$conexion);
   //mostramos las solicitudes según el rol
   $listado_solicitudes=$lcontroller->showListadoSolicitudes($rol,$id_centro,$estado_convocatoria,$solicitud,$log_listados_solicitudes,$id_alumno,$provincia);
   $tablaresumen=$tcentro->getResumen($rol,'alumnos',$log_listados_solicitudes);
   $tablaresumen=$lcontroller->showTablaResumenSolicitudes($tablaresumen,$nombre_centro,$id_centro);
   ##FILTROS DE OPCIONES DE VALIDACION Y DE COMPROBACION 
   if(($rol=='anonimo' and $estado_convocatoria<=ESTADO_FININSCRIPCION) or ($rol=='centro' and $solcentro==1))
   {
      //si es un alumno nuevo o entramos si indicar el token, generamos uno nuevo
      $token=bin2hex(random_bytes(8));;
      $tokenhtml='<input type="hidden" id="token" name="custId" value="'.$token.'">';
      //$solicitud->getFormularioSolicitud()
      if($rol!='centro')
      {
         $formsol=preg_replace('/<button name="boton.* class="btn btn-outline-dark validar".*<\/button>/','',$formsol);
         $formsol=preg_replace('/<button name="boton.* class="btn btn-outline-dark comprobar".*<\/button>/','',$formsol);
         $formsol=preg_replace('/<button name="boton.* class="btn btn-outline-dark".*<\/button>/','',$formsol);
         $formsol=preg_replace('/<button name="boton_comprobaridentidad" type="button" class="btn btn-outline-dark comprobar">Comprobar identidad<\/button>/','',$formsol);
         $formsol=preg_replace('/<button name="boton_baremo_comprobar_proximidad_domicilio" type="button" class="btn btn-outline-dark comprobar">Comprobar domicilio<\/button>/','',$formsol);
      }
      print($formsol.$tokenhtml);
   }
   else if($rol=='alumno' and $estado_convocatoria>ESTADO_FININSCRIPCION)
   {
      if($estado_convocatoria<30)
         print("<h1>LA CONVOCATORIA HA FINALIZADO</h1>");  
      print("<div id='wrapper'>");
      print("<div id='content'>");
      echo '<div id="l_matricula" style="width:100%">';
         print($msg_validacion.$listado_solicitudes);
      echo '</div>';
      if($estado_convocatoria<30)
         print("<h3>Pulsa en la pestaña 'Lista baremo' para ver el listado baremado</h3>");  
      print("</div>");
   }
   else
   {
      echo '<body>';
         echo '<div class="wrapper">';
            echo '<div id="content">';
               echo '<div id="l_matricula" style="width:100%">';
               if($rol=='alumno')
                  print($msg_validacion.$listado_solicitudes);
               else
                  print($msg_validacion.$tablaresumen.$listado_solicitudes);
               echo '</div>';
               echo '</div>';
         echo '</div>';
      echo '</body>';
   }
}
?>

