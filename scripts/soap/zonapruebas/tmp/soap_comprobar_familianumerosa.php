<?php
##########################VALORES DE NIFS#################################
include('../funciones_soap.php');
//nif alumno arellano
$nif1='73410860N';
//nif tutor arellano
$nif1='72965899D';
$fecha_solicitud= "03/01/2022";
//print_r($_POST);exit();
//COMP PRIMER NIF
$respuesta=comprobarFamiliaNumerosa($nif1,$fecha_solicitud);
$respuesta_procesada=procesarRespuestaFamiliaNumerosa($respuesta);
print_r("\nCOMPROBADO PRIMER NIF $nif1:\n ");
print_r($respuesta);
print_r($respuesta_procesada);
//print_r($respuestamod);

function procesarRespuesta($respuesta)
{
   $msgresp='';
   preg_match('/<errorMsg>(.*?)<\/errorMsg>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   return $msgresp;
   }
   preg_match('/<literalError>(.*?)<\/literalError>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   return $msgresp;
   }
   $msgresp.="\nNÚMERO TÍTULO FAMILIA\n";
   preg_match('/<numeroTitulo>(.*?)<\/numeroTitulo>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   }
   preg_match('/<tituloVigente>(.*?)<\/tituloVigente>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
   }
   if($literal=='N')
      $msgresp.="\nEL TÍTULO NO TIENE VIGENCIA\n";
   else
      $msgresp.="\nEL TÍTULO ESTA VIGENTE\n";
   //BENEFICIARIOS
   preg_match_all('/<beneficiarioFamiliaNumerosa>(.*?)<\/beneficiarioFamiliaNumerosa>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $ben=$match[1];
      foreach($ben as $b)
      {
         $msgresp.="\nDATOS BENEFICIARIO\n";
         preg_match('/<apellido1>(.*?)<\/apellido1>/s', $b, $match);
         if(sizeof($match)>=2)
         {
            $literal=$match[1];
            $msgresp.=$literal.", ";
         }
         preg_match('/<apellido2>(.*?)<\/apellido2>/s', $b, $match);
         if(sizeof($match)>=2)
         {
            $literal=$match[1];
            $msgresp.=$literal.", ";
         }
         preg_match('/<nombre>(.*?)<\/nombre>/s', $b, $match);
         if(sizeof($match)>=2)
         {
            $literal=$match[1];
            $msgresp.=$literal;
         }
      }
   }
return $msgresp;
}
