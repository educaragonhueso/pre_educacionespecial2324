<?php

##########################VALORES DE NIFS#################################

$nif1='99999949C';

$identificationData = new StdClass();
$identificationData->documentNumber =$nif1;
$identificationData->documentType ="NIF";

$purposeData = new StdClass();
$purposeData->consent ="Si";//SvcdCtes.COD_CONSENT?
$purposeData->fileNumber ="001";
$purposeData->procedureNumber ="466";//?
$purposeData->purposeText ="Test";//?
//$purposeData->purposeValidationCode ="466";//svcdProcedureNumber?

$residenceSpecificDataVR= new StdClass();
//$residenceSpecificDataVR->province = "50";

$specificBirthDataVR= new StdClass();
//$specificBirthDataVR->birthDate="19951122";
//$specificBirthDataVR->birthDateSpecified = true;

$userSpecificDataVR= new StdClass();
$userSpecificDataVR->nationality = "Espanyol";

$userData = new StdClass();
//$userData->name = "EDUARDO";
//$userData->surname1 = "SANCHEZ";
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
$arg0->organismCode="ORG07458";
$arg0->userCode="00000000T";
$arg0->residenceVerificationRequest=$residenceVerificationRequest;

$residenceVerification = new StdClass();
$residenceVerification->arg0 =$arg0;

$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
 
$wsdl ='https://preaplicaciones.aragon.es/svcd_core/services/ResidenceVerificationDate?wsdl';
 
if (!class_exists('SoapClient'))
{
        die ("You haven't installed the PHP-Soap module.");
}
 
// we use the WSDL file to create a connection to the web service
$client = new SoapClient($wsdl,$soap_options);

$padron=vpad($residenceVerification,$client);
//print($padron);
//var_dump(json_encode($padron));

/*
$padron="<note>
<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body>Don't forget me this weekend!</body>
</note>";

$fp=fopen("padron.xml","w");
fwrite($fp,$padron);
fclose($fp);
$xml = simplexml_load_file("padron.xml"); 
print_r($xml);
*/
print($padron);
preg_match('/<name>(.*?)<\/name>/s', $padron, $match);
print_r($match);

function vpad1(){
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
return $client->__getLastResponse();
}


function vpad($residenceVerification,$client){
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
return $client->__getLastResponse();
}
