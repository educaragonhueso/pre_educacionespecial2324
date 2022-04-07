<?php
// Create the object we'll pass back over the SOAP interface. This is the MAGIC!

$identificationData = new StdClass();
$identificationData->documentNumber ="77753921Y";
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
$specificLargeFamilyData->queryDate = "23/02/2020";
//$specificLargeFamilyData->titleNumber = "(SSCC)394-2015-00000039-1";
//$specificLargeFamilyData->userBirthDate = "05/09/1978";

$userData = new StdClass();
//$userData->name = "ELENA";
//$userData->surname1 = "PRUEBA SCSP00";
//$userData->surname2 = "MARTÍNEZ";

$largeFamilyQueryRequest = new StdClass();
$largeFamilyQueryRequest->identificationData=$identificationData;
$largeFamilyQueryRequest->purposeData=$purposeData;
$largeFamilyQueryRequest->specificLargeFamilyData=$specificLargeFamilyData;
$largeFamilyQueryRequest->userData=$userData;

$arg0 = new StdClass();
$arg0->applicationId="ADMEG";
$arg0->organismCode="ORG07458";
$arg0->userCode="00000000T";
$arg0->largeFamilyQueryRequest=$largeFamilyQueryRequest;

$largeFamilyQuery = new StdClass();
$largeFamilyQuery->arg0 =$arg0;



echo "Setting up SOAP options\n";
$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
// configure our WSDL location
echo "Configuring WSDL\n";
 
$wsdl ='https://preaplicaciones.aragon.es/svcd_core/services/LargeFamilyQuery?wsdl';
 
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
        $result = $client->largeFamilyQuery($largeFamilyQuery);
        // save our results to some variables
         echo "RESPUESTA SERVIDOR FAMILIA NUMEROSA\n\n";
 
        // perform some logic, output the data to Asterisk, or whatever you want to do with it.
 
} catch (SOAPFault $f) {
        // handle the fault here
   print_r($f);
}
 
$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
//var_dump($request);
var_dump($response);
echo "Script complete\n\n";

