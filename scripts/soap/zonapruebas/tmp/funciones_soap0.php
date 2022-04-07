<?php
######################
# funcionaes acceso a datos de SVCD y SGA (correo y sms)
######################

//DISCAPACIDAD
function comprobarDiscapacidad($nif,$tipo,$provincia,$nombre,$apellido1,$fnac)
{
   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";
   $purposeData->purposeValidationCode ="";

   $specificDisabilityData = new StdClass();
   $specificDisabilityData->autonomousCommunityCode=COM_CODE;

   $disabilityQueryRequest = new StdClass();
   if($nif!='')
   {
      $identificationData = new StdClass();
      $identificationData->documentNumber =$nif;
      $identificationData->documentType =$tipo;
      $disabilityQueryRequest->identificationData=$identificationData;
   }
   $disabilityQueryRequest->purposeData=$purposeData;
   $disabilityQueryRequest->specificDisabilityData=$specificDisabilityData;
   if($nombre!='')
   {
      $userData = new StdClass();
      $userData->name = $nombre;
      $userData->surname1 =$apellido1 ;
      $userData->birthDate = $fnac;
      $disabilityQueryRequest->userData=$userData;
   }


   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->disabilityQueryRequest=$disabilityQueryRequest;

   $disabilityQuery = new StdClass();
   $disabilityQuery->arg0 =$arg0;

   $soap_options = array('trace'=>1,'exceptions'=>1);
   $wsdl =WSDL_DISCAPACIDAD;
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->disabilityQuery($disabilityQuery);
    
   } catch (SOAPFault $f) {
      return $f;
   }
   return $client->__getLastResponse();
}
//FUNCIONES FAMILIA NUMEROSA
function procesarRespuestaFamiliaNumerosa($respuesta)
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
   
   preg_match('/<categoria>(.*?)<\/categoria>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
   if($literal=='G')
      $msgresp.="\nFAMILIA DE TIPO GENERAL\n";
   else
      $msgresp.="\nFAMILIA DE TIPO ESPECIAL\n";
   }
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

function comprobarFamiliaNumerosa($nif)
{
   $identificationData = new StdClass();
   $identificationData->documentNumber =$nif;
   $identificationData->documentType ="NIF";

   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber ="001";
   $purposeData->procedureNumber ="466";//?
   $purposeData->purposeText ="Test";//?
   $purposeData->purposeValidationCode ="";//svcdProcedureNumber?

   $specificLargeFamilyData = new StdClass();
   $specificLargeFamilyData->autonomousCommunityCode = "02";
   //FECHA DE PETICION, DEBE SER MENOR A LA DE CADUCIDAD, EN ESTE CASO 18/05/2020
   $specificLargeFamilyData->queryDate = "23/02/2021";
   //$specificLargeFamilyData->titleNumber = "(SSCC)394-2015-00000039-1";

   $userData = new StdClass();

   $largeFamilyQueryRequest = new StdClass();
   $largeFamilyQueryRequest->identificationData=$identificationData;
   $largeFamilyQueryRequest->purposeData=$purposeData;
   $largeFamilyQueryRequest->specificLargeFamilyData=$specificLargeFamilyData;
   $largeFamilyQueryRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId="GIR";
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->largeFamilyQueryRequest=$largeFamilyQueryRequest;

   $largeFamilyQuery = new StdClass();
   $largeFamilyQuery->arg0 =$arg0;
   $soap_options = array(
           'trace'       => 1,     // traces let us look at the actual SOAP messages later
           'exceptions'  => 1 );
    
   $wsdl =WSDL_FAMILIANUMEROSA;
    
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->largeFamilyQuery($largeFamilyQuery);
    
   } catch (SOAPFault $f) {
      print_r($f);
   }
    
   $response = $client->__getLastResponse();
  return $response;
}
//FUNCIONES IDENTIDAD

function procesarRespuestaIdentidad($respuesta,$nif,$nombre='',$apellido1='')
{
   $msgresp='';
   preg_match('/<literal>(.*?)<\/literal>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
      if($msgresp=='TRAMITADA') $msgresp='La edad del alumno es correcta';
      else $msgresp='La edad del alumno no es correcta';
   }
   else
      $msgresp="Error en la consulta";
return $msgresp;
}

function comprobarIdentidad($nif,$nombre,$apellido1,$fnac){
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


//FUNCIONES PADRON

function procesarRespuestaPadron($respuesta,$nif,$nombre='',$apellido1='')
{
   $msgresp='';
   preg_match('/<literal>(.*?)<\/literal>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
   }
   //return strlen(strpos($msgresp,'Titular no Identificado')));
   if(strpos($msgresp,'Titular no Identificado')!==FALSE) return $msgresp;
   if(strpos($msgresp,'DOCUMENTO CON MÁS DE UN IDENTIFICADOR')!==FALSE) return $msgresp;

   preg_match('/<errorMsg>(.*?)<\/errorMsg>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $error=$match[1];
      $msgresp.=$error;
      return $msgresp;
   }
   if($nif!='nodata')
      $msgresp.="\nEl tutor con nif '.$nif.' está empadronado en la siguiente dirección:\n\n ";
   else
      $msgresp.="\nEl alumno '.$nombre.' '.$apellido1.' está empadronado en la siguiente dirección: \n\n";
    
   preg_match('/<birthDate>(.*?)<\/birthDate>/s', $respuesta, $match);
   $fnac=$match[1];
   if($fnac!='')
      $msgresp.="Fecha Nacimiento: ".$fnac;
   
   preg_match('/<viaAddressName>(.*?)<\/viaAddressName>/s', $respuesta, $match);
   $calle=$match[1];
   if($calle!='')
      $msgresp.="Calle: ".$calle;   
   $anumero=preg_match('/<doorway>(.*?)<\/doorway>/s', $respuesta, $match);
   $numero=$match[1];
   if($numero!='')
      $msgresp.="Número: $numero";
   $aplanta=preg_match('/<floor>(.*?)<\/floor>/s', $respuesta, $match);
   $planta=$match[1];
   if($planta!='')
      $msgresp.=", Planta: $planta";
   $aletra=preg_match('/<door>(.*?)<\/door>/s', $respuesta, $match);
   $puerta=$match[1];
   $msgresp.=", Puerta: $puerta";
   $amunicipio=preg_match('/<locationAddress>(.*?)<\/locationAddress>/s', $respuesta, $match);
   $municipio=$match[1];
   if($municipio!='')
      $msgresp.="\n\nEn el municipio de: $municipio";
return $msgresp;
}

function comprobarPadron($nif,$nombre,$apellido1,$fnac){
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

//FUNCIONES DISCAPACIDAD

function procesarRespuestaDiscapacidad($respuesta)
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
   $msgresp.="\nRESULTADO COMPROBACION DISCAPACIDAD:  \n";
   preg_match('/<description>(.*?)<\/description>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   }
   preg_match('/<name>(.*?)<\/name>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nNOMBRE: \n $literal";
   }
   preg_match('/<surname1>(.*?)<\/surname1>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nPRIMER APELLIDO: \n $literal";
   }
   preg_match('/<surname2>(.*?)<\/surname2>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nSEGUNDO APELLIDO: \n $literal";
   }
return $msgresp;
}

