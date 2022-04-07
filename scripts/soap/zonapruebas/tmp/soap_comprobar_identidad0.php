<?php
//a paritr del nif, obligatorio, obtenemos datos de su identidad incluyendo padres etc..
##########################VALORES DE NIFS#################################
$identificationData = new StdClass();
$identificationData->documentNumber ="30000260A";
$identificationData->documentType ="NIF";

$purposeData = new StdClass();
$purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
$purposeData->fileNumber ="001";
$purposeData->procedureNumber ="466";//?
$purposeData->purposeText ="Test";//?
//$purposeData->purposeValidationCode ="466";//svcdProcedureNumber?

$residenceSpecificDataVR= new StdClass();
//$residenceSpecificDataVR->province = "50";

$specificIdentificationData=new StdClass();
$specificIdentificationData->supportNumber='';

$specificBirthDataVI= new StdClass();
//$specificBirthDataVI->birthDate="19951122";
//$specificBirthDataVI->birthDateSpecified = true;

$userSpecificDataVR= new StdClass();
$userSpecificDataVR->nationality = "Espanyol";

$userData = new StdClass();
$userData->name = "JUAN";
$userData->surname1 = "GARCIA";
$userData->surname2 = "SOTO";

$identitySearchRequest= new StdClass();
$identitySearchRequest->identificationData=$identificationData;
$identitySearchRequest->purposeData=$purposeData;
$identitySearchRequest->specificIdentificationData=$specificIdentificationData;
$identitySearchRequest->specificBirthDataVI=$specificBirthDataVI;
$identitySearchRequest->userData=$userData;

$arg0 = new StdClass();
$arg0->applicationId="GIR";
$arg0->organismCode="ORG07458";
$arg0->userCode="00000000T";
$arg0->identitySearchRequest=$identitySearchRequest;

$identityQuery = new StdClass();
$identityQuery->arg0 =$arg0;

$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
 
$wsdl ='https://preaplicaciones.aragon.es/svcd_core/services/IdentityQuery?wsdl';
 
if (!class_exists('SoapClient'))
{
        die ("You haven't installed the PHP-Soap module.");
}
 
// we use the WSDL file to create a connection to the web service
$client = new SoapClient($wsdl,$soap_options);

$padron=verificarIdentidad($identityQuery,$client);
print($padron);

function verificarIdentidad($identityQuery,$client){
try {
        //$result = $client->createAdvice(array("arg0"=>$paramcreateadvice));
        $result = $client->identityqueryExt($identityQuery);
        // save our results to some variables
         echo "IDENTIDAD COMPROBADO\n\n";
        // perform some logic, output the data to Asterisk, or whatever you want to do with it.
} catch (SOAPFault $f) {
        // handle the fault here
   print_r($f);
}
return $client->__getLastResponse();
}

