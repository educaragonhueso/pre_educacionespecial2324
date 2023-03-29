<?php
$origen='servidor';
//si la ejecutamos desde el servidor, sin http, buscamos la conf en otro lugar
if(sizeof($_REQUEST)==0)
   include '../../../config/config_global.php';
else  
   include '../../config/config_global.php';
   
//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
//include DIR_BASE.'/config/config_global.php';

//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
#require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE."/config/config_soap.php";

function modfecha($f)
{
   $af=explode('-',$f);
   $nf=$af[2]."/".$af[1]."/".$af[0];
   return $nf;
}
function procesarRespuestaImv($respuesta)
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
   $msgresp.="\nRESULTADO COMPROBACION INGRESO MÍNIMO VITAL:  \n";
   preg_match('/<description>(.*?)<\/description>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
   }
   preg_match('/<nombre>(.*?)<\/nombre>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nNOMBRE: \n $literal";
   }
   preg_match('/<apellido1>(.*?)<\/apellido1>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nPRIMER APELLIDO: \n $literal";
   }
   preg_match('/<codPres>(.*?)<\/codPres>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.="\nPRESTACIÓN: \n $literal";
   }
return $msgresp;
}

//FUNCIONES SVCD
######################################################################################
//CCAA Comunidades autoonómas
//FAMILIA NUMEROSA
function comprobarFamiliaNumerosa($nif,$fecha,$nombre,$apellido1,$apellido2,$fnac)
{
   $identificationData = new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }

   $purposeData = new StdClass();
   $purposeData->consent =CONSENT;
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;//?
   $purposeData->purposeText =PURPOSE_TEXT;
   $purposeData->purposeValidationCode =PURPOSE_VALIDATION_CODE;

   $specificLargeFamilyData = new StdClass();
   $specificLargeFamilyData->autonomousCommunityCode = "02";
   $specificLargeFamilyData->userBirthDate =$fnac;
   //FECHA DE PETICION, DEBE SER MENOR A LA DE CADUCIDAD, EN ESTE CASO 18/05/2020
   $specificLargeFamilyData->queryDate =$fecha;
   //$specificLargeFamilyData->titleNumber = "(SSCC)394-2015-00000039-1";

   $userData = new StdClass();
   if($nombre!='' and $apellido1!='' and $apellido2!='')
   {
      $userData->name =$nombre;
      $userData->surname1 =$apellido1;
      $userData->surname2 =$apellido2;
   }
   $largeFamilyQueryRequest = new StdClass();
   $largeFamilyQueryRequest->identificationData=$identificationData;
   $largeFamilyQueryRequest->purposeData=$purposeData;
   $largeFamilyQueryRequest->specificLargeFamilyData=$specificLargeFamilyData;
   $largeFamilyQueryRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->largeFamilyQueryRequest=$largeFamilyQueryRequest;

   $largeFamilyQuery = new StdClass();
   $largeFamilyQuery->arg0 =$arg0;
   $soap_options = array('trace'=>1,'exceptions'  => 1 );
    
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
//#########################################################################FUNCIONES DE SVCD CCAA#############################################################################################
//FUNCIONES DISCAPACIDAD
function comprobarDiscapacidad($nif,$tipo,$nombre,$apellido1,$fnac,$fecha)
{
   //sirve el nif o nombre y apellido y fnac
   // Create the object we'll pass back over the SOAP interface. This is the MAGIC!
   $identificationData = new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      $identificationData->documentType =$tipo;
   }

   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";
   $purposeData->purposeValidationCode ="";

   $specificDisabilityData = new StdClass();
   $specificDisabilityData->autonomousCommunityCode='02';
   $specificDisabilityData->queryDate=$fecha;
   $specificDisabilityData->userBirthDate=$fnac;

   $userData = new StdClass();
   $userData->name = $nombre;
   $userData->surname1 = $apellido1;

   $disabilityQueryRequest = new StdClass();
   $disabilityQueryRequest->identificationData=$identificationData;
   $disabilityQueryRequest->purposeData=$purposeData;
   $disabilityQueryRequest->specificDisabilityData=$specificDisabilityData;
   $disabilityQueryRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->disabilityQueryRequest=$disabilityQueryRequest;

   $disabilityQuery = new StdClass();
   $disabilityQuery->arg0 =$arg0;

   $soap_options = array('trace'=> 1,'exceptions'  => 1 );
   $wsdl =WSDL_DISCAPACIDAD;
    
   // we use the WSDL file to create a connection to the web service
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->disabilityQuery($disabilityQuery);
    
   } catch (SOAPFault $f) {
      print_r($f);
   }
    
   return $client->__getLastResponse();
}
function procesarRespuestaDiscapacidadConNumero($respuesta)
{
   $msgresp='';
   preg_match('/<errorMsg>(.*?)<\/errorMsg>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
      if(strpos('El parámetro numDocumento no contiene un',$literal)==0)
         return "2:0:0:N";
   }
   //no existe certificado
   preg_match('/<literalError>(.*?)<\/literalError>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
      if(strpos('NO EXISTE EL CERTIFICADO',$literal)==0)
         return "2:0:0:N";
   }
   preg_match('/<disabilityGrade>(.*?)<\/disabilityGrade>/s', $respuesta, $match);
   if(sizeof($match)>=2)
      $gradodisc=$match[1];
   preg_match('/<indefiniteValidity>(.*?)<\/indefiniteValidity>/s', $respuesta, $match);
   $indefinida='N';
   if(sizeof($match)>=2)
      $indefinida=$match[1];
   preg_match('/<checkUpDate>(.*?)<\/checkUpDate>/s', $respuesta, $match);
   $fechalimite="0";
   if(sizeof($match)>=2)
      $fechalimite=$match[1];
   preg_match('/<description>(.*?)<\/description>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal."\n";
      if(strpos('EXISTE EL CERTIFICADO',$literal)==0)
         return "3:$gradodisc:$fechalimite:$indefinida";
   }
return "2:0:0:N";
}

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
   else
      $literal='N';
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
######################################################################################
//INE Instituto nacional estadística
//#########################################################################FUNCIONES DE SVCD INE#############################################################################################
//IDENTIDAD
function comprobarIdentidad($nif,$nombre,$apellido1,$fnac)
{
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVR= new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }
   else
   {
      $userData->name = $nombre;
      $userData->surname1 = $apellido1;
      $specificBirthDataVR->birthDate=$fnac;
   }
   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";

   $identitySpecificDataVR= new StdClass();

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   $identityVerificationRequest= new StdClass();
   $identityVerificationRequest->identificationData=$identificationData;
   $identityVerificationRequest->purposeData=$purposeData;
   $identityVerificationRequest->identitySpecificDataVR=$identitySpecificDataVR;
   $identityVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
   $identityVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
   $identityVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   //$arg0->userCode=USER_CODE;
   $arg0->identityVerificationRequest=$identityVerificationRequest;

   $identityVerification = new StdClass();
   $identityVerification->arg0 =$arg0;

   $soap_options = array('trace'=> 1,'exceptions'  => 1 );
   $wsdl =WSDL_IDENTIDAD;
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->identityVerification($identityVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}
//FUNCIONES PADRON
function comprobarPadron($nif,$nombre,$apellido1,$fnac)
{
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVR= new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }
   else
   {
      $userData->name = $nombre;
      $userData->surname1 = $apellido1;
      $specificBirthDataVR->birthDate=$fnac;
   }
   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";

   $residenceSpecificDataVR= new StdClass();

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   $residenceVerificationRequest= new StdClass();
   $residenceVerificationRequest->identificationData=$identificationData;
   $residenceVerificationRequest->purposeData=$purposeData;
   $residenceVerificationRequest->residenceSpecificDataVR=$residenceSpecificDataVR;
   $residenceVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
   $residenceVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
   $residenceVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->residenceVerificationRequest=$residenceVerificationRequest;

   $residenceVerification = new StdClass();
   $residenceVerification->arg0 =$arg0;

   $soap_options = array('trace'=> 1,'exceptions'  => 1 );
   $wsdl =WSDL_PADRON;
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->residenceVerificationDate($residenceVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}
function comprobarIdentidadConPadron($nif,$nombre,$apellido1,$fnac)
{
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVR= new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }
   else
   {
      $userData->name = $nombre;
      $userData->surname1 = $apellido1;
      $specificBirthDataVR->birthDate=$fnac;
   }
   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";

   $residenceSpecificDataVR= new StdClass();

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   $residenceVerificationRequest= new StdClass();
   $residenceVerificationRequest->identificationData=$identificationData;
   $residenceVerificationRequest->purposeData=$purposeData;
   $residenceVerificationRequest->residenceSpecificDataVR=$residenceSpecificDataVR;
   $residenceVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
   $residenceVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
   $residenceVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->residenceVerificationRequest=$residenceVerificationRequest;

   $residenceVerification = new StdClass();
   $residenceVerification->arg0 =$arg0;

   $soap_options = array('trace'=> 1,'exceptions'  => 1 );
   $wsdl =WSDL_PADRON;
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->residenceVerificationDate($residenceVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}

function comprobarPadron_old($nif,$nombre,$apellido1,$fnac)
{
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVR= new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }
   else
   {
      $userData->name = $nombre;
      $userData->surname1 = $apellido1;
      $specificBirthDataVR->birthDate=$fnac;
   }
   $purposeData = new StdClass();
   $purposeData->consent ="Si";
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";

   $identitySpecificDataVR= new StdClass();

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   $identityVerificationRequest= new StdClass();
   $identityVerificationRequest->identificationData=$identificationData;
   $identityVerificationRequest->purposeData=$purposeData;
   $identityVerificationRequest->identitySpecificDataVR=$identitySpecificDataVR;
   $identityVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
   $identityVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
   $identityVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->identityVerificationRequest=$identityVerificationRequest;

   $identityVerification = new StdClass();
   $identityVerification->arg0 =$arg0;

   $soap_options = array('trace'=> 1,'exceptions'  => 1 );
   $wsdl =WSDL_PADRON;
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->identityVerificationDate($identityVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}
function procesarRespuestaPadron($respuesta,$nif,$nombre='',$apellido1='',$origen='')
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
      $msgresp.="\nEl $origen con nif '.$nif.' está empadronado en la siguiente dirección:\n\n ";
   else
      $msgresp.="\nEl $origen '.$nombre.' '.$apellido1.' está empadronado en la siguiente dirección: \n\n";
    
   preg_match('/<birthDate>(.*?)<\/birthDate>/s', $respuesta, $match);
   if(isset($match[1]))
      $fnac=$match[1];
   else
      $fnac='';
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
function procesarRespuestaIdentidadConPadron($respuesta,$nif,$nombre='',$apellido1='')
{
   $msgresp='';
   preg_match('/<literal>(.*?)<\/literal>/s', $respuesta, $match);
   if(sizeof($match)>=2)
   {
      $literal=$match[1];
      $msgresp.=$literal;
   }
   //return strlen(strpos($msgresp,'Titular no Identificado')));
   if(strpos($msgresp,'Titular no Identificado')!==FALSE or strpos($msgresp,'Titular no identificado')!==FALSE) return $msgresp;
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
   if(isset($match[1]))
      $fnac=$match[1];
   else
      $fnac='';
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

function comprobarIdentidad_old($nif,$nombre,$apellido1,$fnac)
{
   $identificationData = new StdClass();
   $userData = new StdClass();
   $specificBirthDataVI= new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }
   else return 0;

   $userData->name = $nombre;
   $userData->surname1 = $apellido1;
   $specificBirthDataVI->birthDate=$fnac;

   $purposeData = new StdClass();
   $purposeData->consent =CONSENT;
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;
   $purposeData->purposeText ="Test";

   $identitySpecificDataVR= new StdClass();

   //$specificBirthDataVR->birthDateSpecified = true;

   $userSpecificDataVR= new StdClass();
   $userSpecificDataVR->nationality = "Espanyol";

   //si da problemas quitar el segundo apellido
   //$userData->surname2 = "MARTIN";

   $identityVerificationRequest= new StdClass();
   $identityVerificationRequest->identificationData=$identificationData;
   $identityVerificationRequest->purposeData=$purposeData;
   $identityVerificationRequest->identitySpecificDataVI=$identitySpecificDataVI;
   $identityVerificationRequest->specificBirthDataVI=$specificBirthDataVI;
   $identityVerificationRequest->userSpecificDataVI=$userSpecificDataVI;
   $identityVerificationRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->identityVerificationRequest=$identityVerificationRequest;

   $identityVerification = new StdClass();
   $identityVerification->arg0 =$arg0;

   $soap_options = array('trace'=>1,'exceptions'  => 1 );
    
   $wsdl =WSDL_PADRON;
    
   $client = new SoapClient($wsdl,$soap_options);

   try {
           $result = $client->identityVerificationDate($identityVerification);
   } catch (SOAPFault $f) {
      print_r($f);
   }
return $client->__getLastResponse();
}
######################################################################################
//TGSS Seguridad Social
//BENEFICIOS HISTORICOS
//#########################################################################FUNCIONES DE SVCD INSS#############################################################################################
function comprobarImv($nif)
{
   // IBenefitsQueryINSSServicePortType, benefitsQueryINSS.
   $identificationData = new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }

   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;//?
   $purposeData->purposeText ="Test";//?
   //$purposeData->purposeValidationCode ="";//svcdProcedureNumber?

   $userData = new StdClass();
   //$userData->name=$nombre;
   //$userData->surname1=$apellido1;
   //$userData->surname2=$apellido2;

   $benefitsQueryINSSRequest = new StdClass();
   $benefitsQueryINSSRequest->identificationData=$identificationData;
   $benefitsQueryINSSRequest->purposeData=$purposeData;
   $benefitsQueryINSSRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->benefitsQueryINSSRequest=$benefitsQueryINSSRequest;

   $benefitsQueryINSS= new StdClass();
   $benefitsQueryINSS->arg0 =$arg0;
   $soap_options = array('trace'=> 1,'exceptions'  => 1 );

   $wsdl =WSDL_IMV;

   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->benefitsQueryINSS($benefitsQueryINSS);

   } catch (SOAPFault $f) {
      print_r($f);
   }

   $response = $client->__getLastResponse();
  return $response;
}

function comprobarBeneficiosHistoricos($nif,$nombre,$apellido1,$apellido2,$fechainicio,$fechafin)
{
   $identificationData = new StdClass();
   if($nif!='nodata' and $nif!='')
   {
      $identificationData->documentNumber =$nif;
      if(is_numeric($nif[0]))
         $identificationData->documentType ="NIF";
      else
         $identificationData->documentType ="NIE";
   }

   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber =FILE_NUMBER;
   $purposeData->procedureNumber =PROC_NUMBER;//?
   $purposeData->purposeText ="Test";//?
   //$purposeData->purposeValidationCode ="";//svcdProcedureNumber?

   $userData = new StdClass();
   $userData->name=$nombre;
   $userData->surname1=$apellido1;
   $userData->surname2=$apellido2;
  
   $specificHistoricalBenefitsINSSData=new StdClass();
   $specificHistoricalBenefitsINSSData->startDate=$fechainicio;
   $specificHistoricalBenefitsINSSData->endDate=$fechafin;
 
   $historicalBenefitsINSSRequest = new StdClass();
   $historicalBenefitsINSSRequest->identificationData=$identificationData;
   $historicalBenefitsINSSRequest->purposeData=$purposeData;
   $historicalBenefitsINSSRequest->userData=$userData;
   $historicalBenefitsINSSRequest->specificHistoricalBenefitsINSSData=$specificHistoricalBenefitsINSSData;

   $arg0 = new StdClass();
   $arg0->applicationId=AP_ID;
   $arg0->organismCode=ORG_CODE;
   $arg0->userCode=USER_CODE;
   $arg0->historicalBenefitsINSSRequest=$historicalBenefitsINSSRequest;

   $historicalBenefitsINSS= new StdClass();
   $historicalBenefitsINSS->arg0 =$arg0;
   $soap_options = array('trace'=> 1,'exceptions'  => 1 );
    
   $wsdl =WSDL_BENEFICIOS_HISTORICOS;
    
   $client = new SoapClient($wsdl,$soap_options);
   try {
           $result = $client->historicalBenefitsINSS($historicalBenefitsINSS);
    
   } catch (SOAPFault $f) {
      print_r($f);
   }
    
   $response = $client->__getLastResponse();
  return $response;
}
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

