<?php
//preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdldiv class="row fila_filtros" style="display:none">
// Create the object we'll pass back over the SOAP interface. This is the MAGIC!
/*
$advice = new StdClass();
$advice->anagrama ="lhueso@aragon.es";
$advice->user ="00000000T";
$advice->application ="GIR";
$advice->date ="09/02/2021";
$advice->description ="Aviso Pruebas Admision";
$advice->entityId ="10050";
$advice->id ="10050";
$advice->mailSubject ="Pruebas admisión";
$advice->subject ="Pruebas admisión subject";
$advice->phoneNumber ="670218415";
$advice->requestType ="pruebasdesp";
$advice->textSMS ="pruebasdesp";
$advice->type ="pruebas sms";
$advice->requestType ="pruebasdesp";
*/
$advice = new StdClass();
$advice->user ="00000000T";
$advice->idApplication ="GIR";
$advice->id ="10050";

$adviceSMS = new StdClass();
$adviceSMS->anagrama ="lhueso@aragon.es";
$adviceSMS->application ="GIR";
$adviceSMS->date ="09/02/2021";
$adviceSMS->description ="Aviso Pruebas Admision";
$adviceSMS->entityId ="10050";
$adviceSMS->mailSubject ="Pruebas admisión";
$adviceSMS->subject ="Pruebas admisión subject";
$adviceSMS->phoneNumber ="670218415";
$adviceSMS->requestType ="SMS";
$adviceSMS->textSMS ="Pincha aqui https://www.google.es";
$adviceSMS->type ="pruebas sms";

$advice->adviceSMS =$adviceSMS;;

$smt1 = new StdClass();
$smt1->arg0=$advice;

echo "Setting up SOAP options\n";
$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
// configure our WSDL location
echo "Configuring WSDL\n";
 
$wsdl ='https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl';
 
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
 
echo "Enviando SMS...\n";
try {
        //$result = $client->createAdvice(array("arg0"=>$paramcreateadvice));
        $result = $client->createAdviceSMS($smt1);
        // save our results to some variables
         echo "SMS ENVIUADO\n\n";
 
        // perform some logic, output the data to Asterisk, or whatever you want to do with it.
 
} catch (SOAPFault $f) {
        // handle the fault here
   print_r($f);
}
 
$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
var_dump($request);
var_dump($response);
echo "Script complete\n\n";

