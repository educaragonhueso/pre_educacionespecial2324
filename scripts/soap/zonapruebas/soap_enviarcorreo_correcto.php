<?php
$oadvice = new StdClass();
$oadvice->anagrama ="lhueso@aragon.es";
$oadvice->application ="ADMEG";
//$oadvice->idApplication ="ADMEG";
$oadvice->date ="20/12/2021";
$oadvice->description ="Pincha aqui <a href='https://admespecial.aragon.es/educacionespecial/index.php?firma=67d2373ee8a6d1b9'>Pincha</a>";
$oadvice->id ="0";
//$oadvice->entityId ="0";
$oadvice->mailSubject ="Pruebas admision";
$oadvice->subject ="Pruebas admisión subject";
$oadvice->type ="pruebas correo";

$arg0 = new StdClass();
$arg0->oAdvice=$oadvice;
//$arg0->user="10050";
$arg0->idApplication="ADMEG";

$createAdvice = new StdClass();
$createAdvice->arg0=$arg0;

$soap_options = array('trace'=> 1,'exceptions'=>1);
$wsdl ='https://aplicaciones.aragon.es/sga_core/services/AdviceService?wsdl';
$client = new SoapClient($wsdl,$soap_options);
 
try {
   $result = $client->createAdvice($createAdvice);
   echo "CORREO ENVIADO\n\n";
}catch (SOAPFault $f) {
   print_r($f);
}

$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
var_dump($request);
var_dump($response);
echo "Script finalizado\n\n";

