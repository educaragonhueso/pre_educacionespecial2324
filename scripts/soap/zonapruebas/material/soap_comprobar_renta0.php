<?php

//caso con imputaciones
/*
$nif1='99999995C';
$inputyear='2016';
$nombre='SERGIO';
$apellido1='MIRANDA';
//caso con imputaciones
$nif1='99999999R';
$inputyear='2016';
$nombre='ELSA';
$apellido1='BARRAL';
*/


$año='2019';
//caso conjunta y varias casillas
$nif1='99999997E';
$nombre_tutor1='JAIME';
$apellido1_tutor1='CABRERA';
//caso conjunta sin imputaciones CONJUNTA
$nif2='99999992V';
$nombre_tutor2='BEATRIZ';
$apellido1_tutor2='CASTILLO';
//caso conjunta con imputaciones
$nif1='Y9999995T';
$nombre='PIERRETTE';
$apellido1='MARGAN';
//caso conjunta con imputaciones
$nif1='X9999993F';
$nombre1='SHAORAN';

$pruebas='SI';

$rentafinal==calcularRenta($nif1,$año,$nombre_tutor1,$apellido1_tutor1,$nif2,$nombre_tutor2,$apellido1_tutor2);

function calcularRenta($nif1,$nif2,$año,$nombre_tutor1,$apellido1_tutor1,$nombre_tutor2,$apellido1_tutor2)
{
   $respuesta_tutor1=comprobarRenta($nif1,$inputyear,$nombre_tutor1,$apellido1_tutor1);
   //comprobamos si es conjunta o no
   $tiporenta1=getTipoRenta($respuesta_tutor1);
   $renta1=getNivelRenta($respuesta_tutor);
   //si es conjunta tomamos la delcaración del primer tutor
   if($tiporenta=='CONJUNTA' or $pruebas=='SI')
      $rentafinal=$renta1;
   else if($tiporenta=='NO CONJUNTA')
   {
      $respuesta_tutor2=comprobarRenta($nif2,$inputyear,$nombre_tutor2,$apellido1_tutor2);
      $renta2=getNivelRenta($respuesta_tutor2);
      $rentafinal=$renta1+$renta2;

   }
   else return $tiporenta;
return $rentafinal;
}
function comprobarRenta($nif,$inputyear,$nombre,$apellido1)
{
   $identificationData = new StdClass();
   $identificationData->documentNumber=$nif;
   $identificationData->documentType="NIF";

   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber ="001";
   $purposeData->procedureNumber ="466";
   $purposeData->purposeText ="Test";
   $purposeData->purposeValidationCode ="466";

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
   $arg0->applicationId="GIR";
   $arg0->organismCode="ORG07458";
   $arg0->userCode="00000000T";
   $arg0->incomeLevelOfAEATRequest=$incomeLevelOfAEATRequest;

   $incomeLevelOfAEAT = new StdClass();
   $incomeLevelOfAEAT->arg0 =$arg0;

   $soap_options = array(
           'trace'       => 1,     // traces let us look at the actual SOAP messages later
           'exceptions'  => 1 );
   $wsdl ='https://preaplicaciones.aragon.es/svcd_core/services/IncomeLevelOfAEAT?wsdl';
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->incomeLevelOfAEAT($incomeLevelOfAEAT);
   } catch (SOAPFault $f) {
      print_r($f);
   }
   return $client->__getLastResponse();
}

function getTipoRenta($respuesta)
{
   $msgresp.="NO CONJUNTA";
   preg_match('/<descRespuesta>(.*?)<\/descRespuesta>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   return $msgresp;
   }
   preg_match('/<tributacion>(.*?)<\/tributacion>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp=$literal;
   }
return $msgresp;
}
function getNivelRenta($respuesta,$tipo)
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
   preg_match('/<descRespuesta>(.*?)<\/descRespuesta>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   return $msgresp;
   }
   //si hay datoseconomocmios es q no lleva nivel de renta
   preg_match('/<nivelRenta>(.*?)<\/nivelRenta>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $total=floatval(0);
      $literal=$match[1];
      $total=calculaCantidad($literal);
    return $total;
   }
   preg_match_all('/<datosEconomicos>(.*?)<\/datosEconomicos>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $total=floatval(0);
      foreach($match[1] as $m)
          $total+=calculaCantidad($m);
   return $total;
   }
return $msgresp;
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
   preg_match('/<descRespuesta>(.*?)<\/descRespuesta>/s', $respuesta, $match);
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
      $msgresp.="\nTIPO DECLARACION: \n $literal\n";
   }
   preg_match_all('/<datosEconomicos>(.*?)<\/datosEconomicos>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $total=floatval(0);
      foreach($match[1] as $m)
          $total+=calculaCantidad($m);
      $msgresp.="\nTOTAL RENTA: \n $total";
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
