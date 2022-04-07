<?php 
session_start();

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial2223/config/config_global.php";
require_once DIR_CORE.'/Conectar.php';
require_once 'includes/sesion.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_BASE.'/clases/models/Centro.php';

##CABECERA##
##include('includes/head.php');

$log_acceso=new logWriter('log_acceso',DIR_LOGS);

$_SESSION['dir_base']=DIR_BASE;
$_SESSION['id_alumno']=0;

date_default_timezone_set('Europe/Madrid');
$hoy=date("Y/m/d");      
$conectar=new Conectar();
$conexion=$conectar->conexion();
$centro=new Centro($conexion,0,'ajax');
$numero_sorteo=$centro->getNumeroSorteo();
$_SESSION['numero_sorteo']=$numero_sorteo;
header('Content-Type: text/html; charset=UTF-8');  
if(($_SESSION['version']=='PRE' or $_SESSION['mantenimiento']=='SI') and $_SESSION['rol']!='alumno' and $_SESSION['rol']!='anonimo') print_r($_SESSION);

// Define variables and initialize with empty values
$nombre_usuario = $clave = "";
$nombre_usuario_err = $clave_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
// Check if username is empty
   if(empty(trim($_POST["nombre_usuario"])))
   {
      $nombre_usuario_err = 'Intro nombre de usuario';
   } 
   else
   {
      $nombre_usuario = trim($_POST["nombre_usuario"]);
   }
// Check if password is empty
   if(empty(trim($_POST['clave'])))
   {
      $clave_err = 'Intro clave';
   } 
   else
   {
      $clave = trim($_POST['clave']);
   }
   if(empty($nombre_usuario_err) && empty($clave_err))
   {
         ######################################################################################
         $log_acceso->warning("ENTRANDO USUARIO: ");
         $log_acceso->warning(print_r($_POST,true));
         ######################################################################################
      $sql = "SELECT nombre_usuario, clave,rol,nombre_centro,id_centro,primera_conexion,num_sorteo,fase_sorteo FROM usuarios u left join centros c  ON u.id_usuario=c.id_usuario WHERE  u.nombre_usuario = ? and u.clave= ?";
      $sql_alumno = "SELECT id_centro_destino,id_alumno,token FROM usuarios u  join alumnos a on u.id_usuario=a.id_usuario where u.nombre_usuario= ? and u.clave= ?";
      if($stmt = $conexion->prepare($sql))
      {
      // Bind variables to the prepared statement as parameters
         $stmt->bind_param("ss", $param_usuario,$param_clave);
         // Set parameters
         $param_usuario = $nombre_usuario;
         $param_clave = md5($clave);
         // Attempt to execute the prepared statement
         if($stmt->execute())
         {
         // Store result
            $stmt->store_result();
            // Check if username exists, if yes then verify password
               if($stmt->num_rows == 1)
               {                    
               // Bind result variables
               $stmt->bind_result($nombre_usuario, $hashed_clave,$rol,$nombre_centro,$id_centro,$primera_conexion,$num_sorteo,$fase_sorteo);
               if($stmt->fetch())
               {
                  if(md5(strtoupper($clave))== $hashed_clave || md5($clave)== $hashed_clave)
                  {
                    $_SESSION['nombre_usuario'] = $nombre_usuario; 
                     if($nombre_usuario=='sphuesca') $_SESSION['provincia']='huesca';     
                     if($nombre_usuario=='spzaragoza') $_SESSION['provincia']='zaragoza';     
                     if($nombre_usuario=='spteruel') $_SESSION['provincia']='teruel';     
                    $_SESSION['clave'] = $clave;      
                    $_SESSION['rol'] = $rol;  
                    $_SESSION['nombre_centro'] = $nombre_centro;      
                    $_SESSION['id_centro'] = $id_centro;
                    //$_SESSION['num_sorteo'] = $num_sorteo;      
                    $_SESSION['usuario_autenticado'] =1;//si es un usuario autenticado o no      
                  
                    $log_acceso->warning("DENTRO DE LA CONSULTA PRIMERA:");
                    $log_acceso->warning(print_r($_SESSION,true));
                    
                    if($rol=='alumno')
                    {
                     if($stmt_alumno = $conexion->prepare($sql_alumno))
                     {
                    
                     $log_acceso->warning("DENTRO DE LA CONSULTAi DE ALUMNO:".$nombre_usuario);
                        // Bind variables to the prepared statement as parameters
                        $stmt_alumno->bind_param("ss", $param_usuario,$param_clave);
                        // Set parameters
                        $param_usuario = $nombre_usuario;
                        $param_clave = md5($clave);
                        // Attempt to execute the prepared statement
                        if($stmt_alumno->execute())
                        {
                           // Store result
                           $stmt_alumno->store_result();
                           // Check if username exists, if yes then verify password
                           if($stmt_alumno->num_rows == 1)
                           {                    
                              $log_acceso->warning("OK CONSULTA DE ALUMNO:".$nombre_usuario."-".$clave);
                              // Bind result variables
                              $stmt_alumno->bind_result($id_centro_destino,$id_alumno,$token);
                              if($stmt_alumno->fetch())
                              {
                                 $_SESSION['id_centro'] =$id_centro_destino;      
                                 $_SESSION['id_alumno'] =$id_alumno;      
                                 $_SESSION['token'] =$token;      
                                 header("location: index.php");
                              }
                           }
                           else echo "ES posible que haya más de un usuario con ese nombre";
                        }
                      }
                     }    
                     elseif($rol=='centro' and  $primera_conexion=='si')
                     {
                        $log_acceso->warning("PRIMERA CONEXION; ACCEDIENTO A FORMULARIO, ID CENTRO: $id_centro");
                        //de momento lo enviamos al normal, luego lo pondremos para pconexion
                        header("location: login_pconexion.php");
                        //header("location: index.php");
                     }
                     elseif($rol!='anonimo')
                     { 
                        $_SESSION['id_alumno'] =0;      
                        header("location: index.php");
                     }
                    } 
                    else
                    {
                        // Display an error message if password is not valid
                        $password_err = 'Clave o Usuario incorrectos';
                        print("Error ".$password_err);
                    }
                 }
               } 
               else
               {
                  // Display an error message if username doesn't exist
                  $nombre_usuario_err = 'No existe una cuenta para este usuario '.$nombre_usuario;
               }
            }
            else
            {
               echo "Algo falló, prueba otra vez más tarde o habla con el administrador: lhueso@aragon.es";
            }
            $stmt->close();
         } //FIN conexion-prepare 
         else 
         {
            echo "No ha podido comprobarse las credenciales, prueba más tarde o consulta al administrador lhueso@aragon.es";
         }
   }//FIN empty nombre de usuario
   // Close connection
   $conexion->close();
}//FIN request
include('includes/head.php');
include('includes/menusuperior.php');
include('includes/login_activa_html.php');
?>


