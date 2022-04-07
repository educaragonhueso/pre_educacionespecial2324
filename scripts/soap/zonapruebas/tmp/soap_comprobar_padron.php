<?php
include('../../config/config_soap.php');
##########################VALORES DE NIFS#################################
$apellido1='hueso';
$apellido2='ibañez';
$nombre='luis';
$fnac='1972-06-11';
//print_r($_POST);exit();
//$nif1='99999949C';
//COMP PRIMER NIF
$respuesta=comprobarPadron1('nodata',$nombre,$apellido1,$fnac);
print_r($respuesta);
//$respuestamod=procesarRespuesta($respuesta,'nodata','nodata',$nif1);
//print_r($respuestamod);
//print_r($respuestamod);

exit();
//COMP SEGUNDO NIF
if($nif2=='nodata')
{
   $respuestamod='No hay datos';
}
else
{
   $respuesta=vpad1($nif2,$nombre,$apellido1,$fnac);
   $respuestamod=procesarRespuesta($respuesta,'nodata','nodata',$nif2);
}
print_r("\nCOMPROBADO SEGUNDO NIF: $nif2\n ");
print_r($respuestamod);

//COMP POR NOMBRE
if($fnac=='nodata')
{
   $respuestamod='No hay datos';
}
else
{
   $respuesta=vpad1('nodata',$nombre,$apellido1,$fnac);
   $respuestamod=procesarRespuesta($respuesta,'nodata',$nombre,$apellido1);
   print_r("\nCOMPROBANDO POR APELLIDO Y FNAC:\n ");
   print_r($respuestamod);
}

function procesarRespuesta($respuesta,$nif,$nombre='',$apellido1='')
{
   $msgresp='';
   preg_match('/<literal>(.*?)<\/literal>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
   }
      preg_match('/<errorMsg>(.*?)<\/errorMsg>/s', $respuesta, $match);
      if(sizeof($match)>=2)
      {
         $error=$match[1];
         $msgresp.=$error;
      }
      else
      {
         if($nif!='nodata')
            $msgresp.='\r\nEl nif '.$nif.' está empadronado en la siguiente dirección: ';
         else
            $msgresp.='El alumno '.$nombre.' '.$apellido1.' está empadronado en la siguiente dirección: ';
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
return $msgresp;
}

//preg_match('/<name>(.*?)<\/name>/s', $padron, $match);
//print_r($match);
function comprobarPadron1($nif,$nombre,$apellido1,$fnac){
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVR= new StdClass();
   if($nif!='nodata')
   {
      $identificationData->documentNumber =$nif;
      $identificationData->documentType ="NIF";
   }
   else
   {
      $userData->name = $nombre;
      $userData->surname1 = $apellido1;
      $specificBirthDataVR->birthDate=$fnac;
   }


   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber ="001";
   $purposeData->procedureNumber ="466";//?
   $purposeData->purposeText ="Test";//?

   $residenceSpecificDataVR= new StdClass();

   //$specificBirthDataVR->birthDateSpecified = true;

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   //si da problemas quitar el segundo apellido
   //$userData->surname2 = "MARTIN";

   $residenceVerificationRequest= new StdClass();
   $residenceVerificationRequest->identificationData=$identificationData;
   $residenceVerificationRequest->purposeData=$purposeData;
   $residenceVerificationRequest->residenceSpecificDataVR=$residenceSpecificDataVR;
   $residenceVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
   $residenceVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
   $residenceVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId="GIR";
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->residenceVerificationRequest=$residenceVerificationRequest;

   $residenceVerification = new StdClass();
   $residenceVerification->arg0 =$arg0;

   $soap_options = array(
           'trace'       => 1,     // traces let us look at the actual SOAP messages later
           'exceptions'  => 1 );
    
   $wsdl =WSDL_PADRON;
    
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->residenceVerificationDate($residenceVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}

function comprobarPadron($nif,$nombre,$apellido1,$fnac=''){
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVR= new StdClass();
   if($nif!='nodata')
   {
      $identificationData->documentNumber =$nif;
      $identificationData->documentType ="NIF";
   }
   else
   {
      $userData->name = $nombre;
      $userData->surname1 = $apellido1;
      $specificBirthDataVR->birthDate=$fnac;
   }
   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber ="001";
   $purposeData->procedureNumber ="466";//?
   $purposeData->purposeText ="Test";//?

   $residenceSpecificDataVR= new StdClass();

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   $userData = new StdClass();
   //$userData->name = $nombre;
   //$userData->surname1 = $apellido1;
   //si da problemas quitar el segundo apellido
   //$userData->surname2 = "MARTIN";

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
    
   if (!class_exists('SoapClient'))
   {
           die ("You haven't installed the PHP-Soap module.");
   }
    
   // we use the WSDL file to create a connection to the web service
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->residenceVerificationDate($residenceVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}
