<?php
//preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdldiv class="row fila_filtros" style="display:none">
// Create the object we'll pass back over the SOAP interface. This is the MAGIC!
$advice = new StdClass();
$advice->user ="29117207N";
$advice->idApplication ="GIR";
$advice->id ="8050";

$adviceSMS = new StdClass();
$adviceSMS->anagrama ="lhueso@aragon.es";
$adviceSMS->application ="GIR";
$adviceSMS->date ="06/05/2021";
$adviceSMS->description =
"Su solicitud acaba de ser admitido al centro JEAN PIAGET. Mañana viernes contacte con ellos para las modificaciones pertinenetes.Puedes acceder a tu solicitud con el usuario: ";
$adviceSMS->entityId ="10050";
$adviceSMS->mailSubject ="Admisión centros educaicón especial";
$adviceSMS->subject ="Admisión Ed Especial";
//$adviceSMS->phoneNumber ="609404619";
$adviceSMS->phoneNumber ="666356662";
//$adviceSMS->phoneNumber ="635542697";
$adviceSMS->requestType ="SMS";
$adviceSMS->textSMS ="Su solicitud acaba de ser admitido al centro JEAN PIAGET. Accede con usuario: 29117442V y clave: 4987 en la web: https://admespecial.aragon.es/educacionespecial";
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
 
$wsdl ='https://aplicaciones.aragon.es/sga_core/services/AdviceService?wsdl';
 
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

