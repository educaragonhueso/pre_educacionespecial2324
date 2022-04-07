<?php
//alumno lucas arellano
//nif de tutor
$nif1='72965899D';
//nif de alumno
$nif1='Y2443966T';
$nif1='77760435b';
if(is_numeric($nif1[0]))
   $tipo='NIF';
else
   $tipo='NIE';

print($tipo);
$respuesta=comprobarDiscapacidad($nif1,$tipo);
$respuestamod=procesarRespuestaDiscapacidad($respuesta);
print_r("\nCOMPROBADO PRIMER NIF $nif1:\n ");
print_r($respuesta);

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

function comprobarDiscapacidad($nif,$tipo)
{
   $identificationData = new StdClass();
   $identificationData->documentNumber =$nif;
   $identificationData->documentType =$tipo;

   $purposeData = new StdClass();
   $purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
   $purposeData->fileNumber ="001";
   $purposeData->procedureNumber ="466";//?
   $purposeData->purposeText ="Test";//?
   $purposeData->purposeValidationCode ="";//svcdProcedureNumber?

   $specificLargeFamilyData = new StdClass();
   //FECHA DE PETICION, DEBE SER MENOR A LA DE CADUCIDAD, EN ESTE CASO 18/05/2020
   $specificLargeFamilyData->queryDate = "23/02/2021";

   $specificDisabilityData = new StdClass();
   $specificDisabilityData->autonomousCommunityCode='02';
   //$specificDisabilityData->provinceCode='44';

   $userData = new StdClass();
   //$userData->name = "GIANATELLI";
   //$userData->surname1 = "ARIEL HERNAN";

   $disabilityQueryRequest = new StdClass();
   $disabilityQueryRequest->identificationData=$identificationData;
   $disabilityQueryRequest->purposeData=$purposeData;
   $disabilityQueryRequest->specificDisabilityData=$specificDisabilityData;
   $disabilityQueryRequest->userData=$userData;

   $arg0 = new StdClass();
   $arg0->applicationId="GIR";
   $arg0->organismCode="ORG17544";
   $arg0->userCode="25159988N";
   $arg0->disabilityQueryRequest=$disabilityQueryRequest;

   $disabilityQuery = new StdClass();
   $disabilityQuery->arg0 =$arg0;

   $soap_options = array(
           'trace'       => 1,     // traces let us look at the actual SOAP messages later
           'exceptions'  => 1 );
   $wsdl ='https://aplicaciones.aragon.es/svcd_core/services/DisabilityQuery?wsdl';
   // Make sure the PHP-Soap module is installed
   echo "Checking SoapClient exists\n";
   if (!class_exists('SoapClient'))
   {
           die ("You haven't installed the PHP-Soap module.");
   }
    
   // we use the WSDL file to create a connection to the web service
   echo "Creating webservice connection to $wsdl\n";
   $client = new SoapClient($wsdl,$soap_options);
   //$client = new SoapClient($wsdl);
   echo "Enviando solicitud...\n";
   try {
           //$result = $client->createAdvice(array("arg0"=>$paramcreateadvice));
           $result = $client->disabilityQuery($disabilityQuery);
           // save our results to some variables
            echo "RESPUESTA SERVIDOR FAMILIA NUMEROSA\n\n";
    
           // perform some logic, output the data to Asterisk, or whatever you want to do with it.
    
   } catch (SOAPFault $f) {
           // handle the fault here
      print_r($f);
   }
    
return $client->__getLastResponse();
}
