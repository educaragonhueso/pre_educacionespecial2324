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
      <?php include("includes/estados_convocatoria.php");?>
      <?php 
         $rr=20;
         if($_SESSION['estado_convocatoria']==0)  echo M0;
         if($_SESSION['estado_convocatoria']==10) echo M10;
         if($_SESSION['estado_convocatoria']==19) echo M19;
         if($_SESSION['estado_convocatoria']==20) echo M20;
         if($_SESSION['estado_convocatoria']==21) echo M21;
         if($_SESSION['estado_convocatoria']==30) echo M30;
         if($_SESSION['estado_convocatoria']==40) echo M40;
      ?>
      <div class="wrapper">

<?php  
if(MANTENIMIENTO=='NO' OR IPREMOTA1==$_SERVER['HTTP_X_FORWARDED_FOR'] OR IPREMOTA2==$_SERVER['HTTP_X_FORWARDED_FOR'])
{
   if($_SESSION['estado_convocatoria']==10)
   {
       echo "<p>Pulsa en 'Crear Solicitud' para acceder al formulario de creación de solicitudes.</p><p> Cuando lo completes recibirás un enlace en tu correo electrónico.</p><p> Dicho enlace te servirá para seguir todo el proceso incluyendo posibles modificaciones de solicitud, listados de admitidos etc...</p>";

       echo '<a href="index.php" class="btn btn-primary" value="Crear solicitud" style="margin-bottom:10px">Crear solicitud</a>';
   }
}
else echo '<h4>PAGINA EN MANTENIMIENTO</h4>';
?>
         <div class="form-group">
            <button id="verfcredenciales" class="btn btn-primary" value="Acceder con credenciales">Acceder con credenciales <p style="font-size:10px"><i>(solo para personal de la administración)</i></p></button>
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
      <div class="form-group">
         <button id="verprocesocompleto" class="btn btn-primary" value="proceso completo">Ver insecuencia del proceso completo</button>
         <div id="tabla_pc" style="display:none"><?php echo PC;?></div>
      </div>
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

