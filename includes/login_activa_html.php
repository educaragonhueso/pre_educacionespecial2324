<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Acceso inscripciones estudios de Educación Especial</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
      <style type="text/css">
         .wrapper
         { 
            width:450px;
            padding: 28px;
            padding-top: 28px;
            margin: auto;
            padding-top: 120px;		
	      }
         input[type=text], input[type=password] 
         {
             width: 450px;
             padding: 1px 10px;
             margin: 8px 0;
             display: inline-block;
             border: 1px solid #ccc;
             box-sizing: border-box;
         }

@media screen and (max-width : 600px) {
         .wrapper
         {
            width: 100%;
         } 
         input[type=text], input[type=password] {
            width: 100%;
         }
}
        </style>

   </head>
   <body>
      <div class="wrapper">
         <h2>ADMISIÓN CENTROS EDUCACIÓN ESPECIAL CURSO 2022/2023</h2><p>La fase de insciprción ha finalizado</p>
<?php //print_r($_SERVER);
?>
<?php if(($hoy>=DIA_INICIO_INSCRIPCION and $hoy <= DIA_FIN_INSCRIPCION and MANTENIMIENTO=='NO') OR IPREMOTA1==$_SERVER['HTTP_X_FORWARDED_FOR'] OR IPREMOTA2==$_SERVER['HTTP_X_FORWARDED_FOR']) 
      {
         echo "";
      }else
      {
         if($hoy> DIA_FIN_INSCRIPCION) echo '<h4>HA FINALIZADO EL PLAZO DE ADMISIÓN</h4>';
         if($hoy< DIA_INICIO_INSCRIPCION) echo '<h4>NO SE HA INICIADO EL PLAZO DE ADMISIÓN</h4>';
         }?>

<?php  if(MANTENIMIENTO=='NO' OR IPREMOTA1==$_SERVER['HTTP_X_FORWARDED_FOR'] OR IPREMOTA2==$_SERVER['HTTP_X_FORWARDED_FOR']){ ?>
<?php  if(IPREMOTA1==$_SERVER['HTTP_X_FORWARDED_FOR'] OR IPREMOTA2==$_SERVER['HTTP_X_FORWARDED_FOR']){ $_SESSION['acceso']='restringido';} ?>
         <div class="form-group">
            <input id="verfcredenciales" class="btn btn-primary" value="Acceder con credenciales">
         </div>
         <form action="" method="post">
            <div id="concredenciales" style="display:none">
               <p>Introduce tu nombre de  usuario y clave</p>
               <div class="form-group <?php echo (!empty($nombre_usuario_err)) ? 'has-error' : ''; ?>">
                  <label>Usuario</label>
                  <input type="text" name="nombre_usuario"class="form-control" value="<?php echo $nombre_usuario; ?>">
                  <span class="help-block"><?php echo $nombre_usuario_err; ?></span>
               </div>    
               <div class="form-group <?php echo (!empty($clave_err)) ? 'has-error' : ''; ?>">
                  <label>Clave</label>
                  <input type="password" name="clave" class="form-control">
                  <span class="help-block"><?php echo $clave_err; ?></span>
               </div>
               <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Acceder">
               </div>
            </div> 
         </form>
         <?php if($hoy<=DIA_FIN_INSCRIPCION){?>
            <div id="concorreo" style="">
               <p>Pulsa en 'Crear Solicitud' para acceder al formulario de creación de solicitudes.</p><p> Cuando lo completes recibirás un enlace en tu correo electrónico.</p><p> Dicho enlace te servirá para seguir todo el proceso incluyendo posibles modificaciones de solicitud, listados de admitidos etc...</p>
               <a href="index.php" class="btn btn-primary" value="Crear solicitud">Crear solicitud</a>
            </div>    
         <?php }?>
<?php }
else
   echo '<h4></h4>';

?>
      </div> <!--finn del wrapper-->   
      
      <footer class="page-footer font-small stylish-color-dark pt-4 mt-4">
       <div class="container text-center text-md-left">
         <div class="row">
         <hr>
         <div class="text-center py-3">
            <ul class="list-unstyled list-inline mb-0">
               <li class="list-inline-item">
                  <div class="footer-copyright py-3 text-center">
                     Registro e incidencias:
                   <a href="mailto:lhueso@aragon.es" style="color:black!important">lhueso@aragon.es </a>
                  </div>
               </li>
           </ul>
       </div>
       <hr>
      </footer>
   </body>
</html>

