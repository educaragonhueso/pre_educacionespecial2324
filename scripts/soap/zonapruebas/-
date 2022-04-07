<?php
include('../funciones_soap.php');

$nif1='09749212H';
$nombre='BONIFACIA';
$apellido1='PASCUAL';
$apellido2='PICO';
$fechainicio="01/01/2021";
$fechafin="01/01/2022";
$respuesta=comprobarBeneficiosHistoricos($nif1,$nombre,$apellido1,$apellido2,$fechainicio,$fechafin);

print_r($respuesta);

function procesarRespuestaBeneficiosHistoricos($respuesta)
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


