<?php
//$dirbase="/datos/www/preadmespecial.aragon.es/public_admespecial/educacionespecial2223/";
require_once "../../config/config_global.php";
$dirbase=DIR_BASE;
$dirbase="/datos/www/admespecial.aragon.es/public_admespecial/";
$destino=$_POST["ubicacion"];
// Upload directory
   if(unlink($destino))
      print("El fichero se ha eliminado correctamente");
   else
   {
      $fichero=$dirbase.'/'.$destino;
      $fichero='/'.$destino;
      print("errror borrando fichero $fichero");
   }

