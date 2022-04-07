<?php
$origen='servidor';
//si la ejecutamos desde el servidor, sin http, buscamos la conf en otro lugar
if(sizeof($_REQUEST)==0)
{
   print_r($_REQUEST);
   echo "sevidor activo";
   include '../../../config/config_global.php';
}
else  
   include '../../config/config_global.php';

require_once DIR_BASE."/config/config_soap.php";
//require_once '../../../config/config_soap.php';

function calcularBonificacion($renta,$nmunidad)
{
   if($nmunidad==0) return 0;
   print("\nCALCULANDO BONIFICACION\n");
   if(!is_float($renta)) return 'No hay renta disponible'; 
   $resultado=$renta/(12*$nmunidad); 
   print("\nCALCULO RENTA: $resultado\n");
   if($resultado<314) $bonificacion=0;
   else if($resultado>=314 and $resultado<=532) $bonificacion=59;
   else if($resultado>532 and $resultado<=763) $bonificacion=86;
   else $bonificacion=118;
return $bonificacion;
}
function calcularRenta($respuesta_tutor)
{
   $respuestafinal=""; 
   $rentafinal=0; 
   //$respuesta_tutor1=comprobarRenta($nif1,$año,$nombre_tutor1,$apellido1_tutor1);
   //print($respuesta_tutor1);return; 
   $error=checkError($respuesta_tutor);
   if($error) return $error;
   //comprobmaos si está tramitada
   $tramitada=checkTramitada($respuesta_tutor);
   if($tramitada!='TRAMITADA') return $tramitada;

   $renta1=getNivelRenta($respuesta_tutor);
   //si es conjunta se toma como la única
   $rentafinal=$renta1;
return floatval($rentafinal);
}
function calcularRenta0($nif1,$año,$nombre_tutor1,$apellido1_tutor1,$nif2,$nombre_tutor2,$apellido1_tutor2)
{
   $respuestafinal=""; 
   $rentafinal=0; 
   $respuesta_tutor1=comprobarRenta($nif1,$año,$nombre_tutor1,$apellido1_tutor1);
   //print($respuesta_tutor1);return; 
   $error=checkError($respuesta_tutor1);
   if($error) return $error;
   //comprobmaos si está tramitada
   $tramitada=checkTramitada($respuesta_tutor1);
   if($tramitada!='TRAMITADA') return $tramitada;

   $tiporenta=getTipoRenta($respuesta_tutor1);
   $renta1=getNivelRenta($respuesta_tutor1);
   //si es conjunta tomamos la delcaración del primer tutor
   if($tiporenta=='CONJUNTA')
   {
      print("\nRENTA CONJUNTA\n");
      $respuestafinal.="\nRENTA CONJUNTA\n";
      $rentafinal=$renta1;
      print("\n RENTA TOTAL:  $rentafinal\n");
      $respuestafinal.="\n RENTA TOTAL:  $rentafinal\n";
   }
   else if($tiporenta=='NO CONJUNTA')
   {
      print("\nRENTA NO CONJUNTA\n");
      $respuestafinal.="\nRENTA NO CONJUNTA\n";

      $respuesta_tutor1=comprobarRenta($nif1,$año,$nombre_tutor1,$apellido1_tutor1);
      $renta1=getNivelRenta($respuesta_tutor1);
      print("\n RENTA PRIMER TUTOR:  $renta1\n");
      $respuestafinal.="\n RENTA PRIMER TUTOR:  $renta1\n";
      
      $respuesta_tutor2=comprobarRenta($nif2,$año,$nombre_tutor2,$apellido1_tutor2);
      $error=checkError($respuesta_tutor2);
      if($error) return $error;
      //comprobmaos si está tramitada
      $tramitada=checkTramitada($respuesta_tutor2);
      if($tramitada!='TRAMITADA') return $tramitada;

      $renta2=getNivelRenta($respuesta_tutor2);
      print("\n RENTA SEGUNDO TUTOR:  $renta2\n");
      $respuestafinal.="\n RENTA SEGUNDO TUTOR:  $renta2\n";

      $rentafinal=floatval($renta1)+floatval($renta2);

      print("\n RENTA TOTAL:  $rentafinal\n");
      $respuestafinal.="\n RENTA TOTAL:  $rentafinal\n";

   }
return $respuestafinal;
}
function checkTramitada($respuesta)
{
   preg_match('/<description>(.+?)<\/description>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $desc=$literal;
   return $desc;
   }
  return false;
}
function checkError($respuesta)
{
   preg_match('/<errorMsg>(.+?)<\/errorMsg>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $error=$literal."\n";
   return $error;
   }
  return false;
}
function getTipoRenta($respuesta)
{
   $msgresp="NO CONJUNTA";
   preg_match('/<descRespuesta>(.+?)<\/descRespuesta>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp=$literal."\n";
   return $msgresp;
   }
   preg_match('/<tributacion>(.+?)<\/tributacion>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp=$literal;
   }
return $msgresp;
}
function getNivelRenta($respuesta)
{
   $msgresp='';
   preg_match('/<errorMsg>(.+?)<\/errorMsg>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
   return $msgresp;
   }
   preg_match('/<literalError>(.+?)<\/literalError>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
   return $msgresp;
   }
   preg_match('/<descRespuesta>(.+?)<\/descRespuesta>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
   return $msgresp;
   }
   preg_match('/<tipoRespuesta>(.+?)<\/tipoRespuesta>/s', $respuesta, $match);
   $tiporespuesta='no hay datos';
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
      $tiporespuesta=$msgresp;
   }
   //si es conjunta el tipo de respuesta tiene q ser el nivel de renta IR, sino IM
   if($tiporespuesta=='IM')
   {
      preg_match_all('/<datosEconomicos>(.*?)<\/datosEconomicos>/s', $respuesta, $match);
      if(sizeof($match)>=2)
      {
         $total=floatval(0);
         foreach($match[1] as $m)
             $total+=calculaCantidad($m);
         $msgresp=$total;
      }
   }
   else if($tiporespuesta=='IR')
   {
      preg_match('/<nivelRenta>(.*?)<\/nivelRenta>/s', $respuesta, $match);
      if(sizeof($match)>=2)
      {
         $total=floatval(0);
         $literal=$match[1];
         $msgresp="";
         preg_match('/<signo>(.+?)<\/signo>/s', $literal, $match);
         if(sizeof($match)>=2)
         {
            $signo=$match[1];
            $msgresp.=$signo;
         }
         preg_match('/<enteros>(.+?)<\/enteros>/s', $literal, $match);
         if(sizeof($match)>=2)
         {
            $enteros=$match[1];
            $msgresp.=$enteros;
         }
         preg_match('/<decimales>(.+?)<\/decimales>/s', $literal, $match);
         if(sizeof($match)>=2)
         {
            $decimales=$match[1];
            $msgresp.=','.$decimales;
         }
      }
   }
return $msgresp;
}
//FUNCIONES CALCULO RENTA
function comprobarRenta($nif,$inputyear,$nombre,$apellido1)
{
   $identificationData = new StdClass();
   $identificationData->documentNumber=$nif;
   $identificationData->documentType="NIF";

   $purposeData = new StdClass();
   $purposeData->consent =CONSENT;
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText =PURPOSE_TEXT;
   $purposeData->purposeValidationCode =PURPOSE_VALIDATION_CODE;

   $userData = new StdClass();
   $userData->name = $nombre;
   $userData->surname1 = $apellido1;
   
   $year = new StdClass();
   $year->year =$inputyear;

   $incomeLevelOfAEATRequest = new StdClass();
   $incomeLevelOfAEATRequest->identificationData=$identificationData;
   $incomeLevelOfAEATRequest->purposeData=$purposeData;
   $incomeLevelOfAEATRequest->userData=$userData;
   $incomeLevelOfAEATRequest->year=$year;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->incomeLevelOfAEATRequest=$incomeLevelOfAEATRequest;

   $incomeLevelOfAEAT = new StdClass();
   $incomeLevelOfAEAT->arg0 =$arg0;

   $soap_options = array(
           'trace'       => 1,
           'exceptions'  => 1 );
   $wsdl =WSDL_RENTA;
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->incomeLevelOfAEAT($incomeLevelOfAEAT);
   } catch (SOAPFault $f) {
      return $f;
   }
   return $client->__getLastResponse();
}
function procesarRespuestaRenta($respuesta)
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
   $msgresp.="\nRESULTADO COMPROBACION RENTA:  \n";
   preg_match('/<tributacion>(.*?)<\/tributacion>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nTIPO DELCARACION: \n $literal\n";
   }
   preg_match_all('/<datosEconomicos>(.*?)<\/datosEconomicos>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $total=floatval(0);
      foreach($match[1] as $m)
          $total+=calculaCantidad($m);
      $msgresp.="\nTOTAL ENTRADAS: \n $total";
   }
   preg_match('/<nivelRenta>(.*?)<\/nivelRenta>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $total=floatval(0);
      $literal=$match[1];
      $total=calculaCantidad($literal);
      $msgresp.="\nNIVEL DE RENTA: \n $total";
   }
return $msgresp;
}
function calculaCantidad($m)
{
   $cantidad='';
   preg_match('/<enteros>(.*?)<\/enteros>/s', $m, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $cantidad.=$literal.".";
   }
   preg_match('/<decimales>(.*?)<\/decimales>/s', $m, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $cantidad.=$literal;
   }
return floatval($cantidad);
}
