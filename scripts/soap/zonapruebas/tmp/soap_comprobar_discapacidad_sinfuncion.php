<?php
//funcona con el nif, de momento los datos de nombre y apellidos no fucionan
// Create the object we'll pass back over the SOAP interface. This is the MAGIC!
$identificationData = new StdClass();
$identificationData->documentNumber ="23456789D";
$identificationData->documentType ="NIF";

$purposeData = new StdClass();
$purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
$purposeData->fileNumber =FILE_NUMBER;
$purposeData->procedureNumber =PROC_NUMBER;//?
$purposeData->purposeText ="Test";//?
$purposeData->purposeValidationCode ="";//svcdProcedureNumber?

$specificDisabilityData = new StdClass();
$specificDisabilityData->autonomousCommunityCode=COM_NUMBER;//comunidad aragón
$specificDisabilityData->provinceCode='50';//provincia zaragoza

$userData = new StdClass();
$userData->name = "JÒRDI";
$userData->surname1 = "MARIÑÁ";

$disabilityQueryRequest = new StdClass();
$disabilityQueryRequest->identificationData=$identificationData;
$disabilityQueryRequest->purposeData=$purposeData;
$disabilityQueryRequest->specificDisabilityData=$specificDisabilityData;
$disabilityQueryRequest->userData=$userData;

$arg0 = new StdClass();
$arg0->applicationId="ADMEG";
$arg0->organismCode="ORG07458";
$arg0->userCode="00000000T";
$arg0->disabilityQueryRequest=$disabilityQueryRequest;

$disabilityQuery = new StdClass();
$disabilityQuery->arg0 =$arg0;

$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
echo "Configuring WSDL\n";
 
$wsdl ='https://preaplicaciones.aragon.es/svcd_core/services/DisabilityQuery?wsdl';
echo "Checking SoapClient exists\n";
if (!class_exists('SoapClient'))
{
        die ("You haven't installed the PHP-Soap module.");
}
$client = new SoapClient($wsdl,$soap_options);
echo "Enviando solicitud...\n";
try {
        $result = $client->disabilityQuery($disabilityQuery);
         echo "RESPUESTA SERVIDOR FAMILIA NUMEROSA\n\n";
} catch (SOAPFault $f) {
   print_r($f);
}
 
$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
var_dump($response);
echo "Script complete\n\n";

