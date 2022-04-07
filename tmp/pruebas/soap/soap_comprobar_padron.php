<?php
$timezone = new DateTimeZone('UTC');
$time='11/11/2012';
$date = new DateTime($time,$timezone);
//print(gettype($date));
//exit();
$identificationData = new StdClass();
//$identificationData->documentNumber ="30000052W";
//$identificationData->documentType ="NIF";

$purposeData = new StdClass();
$purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
$purposeData->fileNumber ="001";
$purposeData->procedureNumber ="466";//?
$purposeData->purposeText ="Test";//?
$purposeData->purposeValidationCode ="";//svcdProcedureNumber?

$residenceSpecificDataVR= new StdClass();
$residenceSpecificDataVR->location = "297";
$residenceSpecificDataVR->province = "50";

$specificBirthDataVR= new StdClass();
//$specificBirthDataVR->birthDate =date("d-m-Y",strtotime("14-06-1972"));
//$specificBirthDataVR->birthDate =$date->format('d/m/Y');
$specificBirthDataVR->birthDate="1995-11-22";
$specificBirthDataVR->locationCode= "297";
$specificBirthDataVR->provinceCode= "50";

$userSpecificDataVR= new StdClass();
$userSpecificDataVR->nationality = "Espanyol";

$userData = new StdClass();
$userData->name = "EDUARDO";
$userData->surname1 = "SANCHEZ";
$userData->surname2 = "MARTIN";

$residenceVerificationRequest= new StdClass();
$residenceVerificationRequest->identificationData=$identificationData;
$residenceVerificationRequest->purposeData=$purposeData;
$residenceVerificationRequest->residenceSpecificDataVR=$residenceSpecificDataVR;
$residenceVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
$residenceVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
$residenceVerificationRequest->userData=$userData;

$arg0 = new StdClass();
$arg0->applicationId="GIR";
$arg0->organismCode="ORG07458";
$arg0->userCode="00000000T";
$arg0->residenceVerificationRequest=$residenceVerificationRequest;

$residenceVerification = new StdClass();
$residenceVerification->arg0 =$arg0;

echo "Setting up SOAP options\n";
$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
// configure our WSDL location
echo "Configuring WSDL\n";
 
$wsdl ='https://preaplicaciones.aragon.es/svcd_core/services/ResidenceVerificationDate?wsdl';
 
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
echo "Enviando solicitud padrón...\n";
try {
        //$result = $client->createAdvice(array("arg0"=>$paramcreateadvice));
        $result = $client->residenceVerificationDate($residenceVerification);
        // save our results to some variables
         echo "PADRON COMPROBADO\n\n";
 
        // perform some logic, output the data to Asterisk, or whatever you want to do with it.
 
} catch (SOAPFault $f) {
        // handle the fault here
   print_r($f);
}
 
$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
#var_dump($request);
var_dump(gettype($result));
var_dump($result);
echo "Script complete\n\n";

