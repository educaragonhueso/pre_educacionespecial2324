<?php
$oadvice = new StdClass();
$oadvice->anagrama ="lhueso@aragon.es";
$oadvice->application ="ADMEG";
$oadvice->idApplication ="ADMEG";
$oadvice->date ="20/12/2021";
$oadvice->description ="Pincha aqui <a href='https://preadmespecial.aragon.es/educacionespecial/index.php?firma=67d2373ee8a6d1b9'>Pincha</a>";
$oadvice->id ="10050";
$oadvice->entityId ="10050";
$oadvice->mailSubject ="Pruebas admision";
$oadvice->subject ="Pruebas admisión subject";
$oadvice->type ="pruebasdesp";

$smt = new StdClass();
$smt->arg0=$oadvice;

$soap_options = array('trace'=> 1,'exceptions'=>1);
$wsdl ='https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl';
$client = new SoapClient($wsdl,$soap_options);
 
try {
   $result = $client->createAdvice($smt);
   echo "CORREO ENVIADO\n\n";
}catch (SOAPFault $f) {
   print_r($f);
}

$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
var_dump($request);
var_dump($response);
echo "Script finalizado\n\n";

