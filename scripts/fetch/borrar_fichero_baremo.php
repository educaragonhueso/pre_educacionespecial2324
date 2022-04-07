<?php
//$dirbase="/datos/www/preadmespecial.aragon.es/public_admespecial/educacionespecial2223/";
$dirbase="/datos/www/admespecial.aragon.es/public_admespecial/";
$destino=$_POST["ubicacion"];
// Upload directory
   if(unlink($dirbase."/".$destino))
      print("El fichero se ha eliimnado");
   else
   {
      $fichero=$dirbase.'/'.$destino;
      print("errror borrando fichero $fichero");
   }

