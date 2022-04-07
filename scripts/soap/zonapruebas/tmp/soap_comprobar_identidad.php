<?php

##########################VALORES DE NIFS#################################

$nif1='73243855X';
//COMP PRIMER NIF
$respuesta=comprobarPadron($nif1,$nombre,$apellido1,$fnac);
$respuestamod=procesarRespuesta($respuesta,'nodata','nodata',$nif1);
print_r("\nCOMPROBADO PRIMER NIF $nif1:\n ");
print_r($respuesta);

function procesarRespuesta($respuesta,$nif,$nombre='',$apellido1='')
{
   preg_match('/<literal>(.*?)<\/literal>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp=$literal;
   }
   else
   {
      preg_match('/<errorMsg>(.*?)<\/errorMsg>/s', $respuesta, $match);
      if(sizeof($match)>=2)
      {
         $error=$match[1];
         $msgresp=$error;
      }
      else
      {
         if($nif!='nodata')
            $msgresp='El nif '.$nif.' está empadronado en la siguiente dirección: ';
         else
            $msgresp='El alumno '.$nombre.' '.$apellido1.' está empadronado en la siguiente dirección: ';
         preg_match('/<viaAddressName>(.*?)<\/viaAddressName>/s', $respuesta, $match);
         $calle=$match[1];
         $msgresp.=$calle;   

         $anumero=preg_match('/<doorway>(.*?)<\/doorway>/s', $respuesta, $match);
         $numero=$match[1];
         $msgresp.=" Número: $numero";

         $aplanta=preg_match('/<floor>(.*?)<\/floor>/s', $respuesta, $match);
         $planta=$match[1];
         $msgresp.=" Planta: $planta";

         $aletra=preg_match('/<door>(.*?)<\/door>/s', $respuesta, $match);
         $puerta=$match[1];
         $msgresp.=" Puerta: $puerta";
         
         $amunicipio=preg_match('/<locationAddress>(.*?)<\/locationAddress>/s', $respuesta, $match);
         $municipio=$match[1];
         $msgresp.=" En : $municipio";
      }
   }
return $msgresp;
}

//preg_match('/<name>(.*?)<\/name>/s', $padron, $match);
//print_r($match);

function comprobarPadron($nif,$nombre,$apellido1,$fnac=''){
   $identificationData = new StdClass();
   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber ="001";
   $purposeData->procedureNumber ="466";//?
   $purposeData->purposeText ="Test";//?
   
   if($nif!='nodata')
   {
      $identificationData->documentNumber =$nif;
      $identificationData->documentType ="NIF";
   }

   $residenceSpecificDataVR= new StdClass();

   $specificBirthDataVR= new StdClass();
   //$specificBirthDataVR->birthDate=$fnac;
   //$specificBirthDataVR->birthDateSpecified = true;

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   $userData = new StdClass();
   $userData->name = $nombre;
   $userData->surname1 = $apellido1;

   $residenceVerificationRequest= new StdClass();
   $residenceVerificationRequest->identificationData=$identificationData;
   $residenceVerificationRequest->purposeData=$purposeData;
   $residenceVerificationRequest->residenceSpecificDataVR=$residenceSpecificDataVR;
   $residenceVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
   $residenceVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
   $residenceVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId="GIR";
   $arg0->organismCode="ORG17544";
   $arg0->userCode="25159988N";
   $arg0->residenceVerificationRequest=$residenceVerificationRequest;

   $residenceVerification = new StdClass();
   $residenceVerification->arg0 =$arg0;

   $soap_options = array(
           'trace'       => 1,     // traces let us look at the actual SOAP messages later
           'exceptions'  => 1 );
    
   $wsdl ='https://aplicaciones.aragon.es/svcd_core/services/ResidenceVerificationDate?wsdl';
    
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->residenceVerificationDate($residenceVerification);
   } catch (SOAPFault $f) {
      return $f;
   }
return $client->__getLastResponse();
}
