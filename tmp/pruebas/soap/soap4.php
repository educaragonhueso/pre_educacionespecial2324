<?php
$client = new SoapClient('https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl', array('trace' => 1));
$params = array(
'Anagrama'=>'lhueso@aragon.es','IdApplication'=>'GIR','Application'=>'GIR','Date'=>'03/02/2021',
'Description'=>'Aviso pruebas admision edespecial', 'EntityId'=>'10050','User'=>'00000000T',
'MailSubject'=>'Prueba aviso', 'Subject'=>'Subject aviso','Type'=>'pruebasedesp'
);

#$res = $client->__soapCall('createAdvice', array($params));
try
{
   $res=$client->createAdvice($params);
}
catch(Exception $e)
{
   print_r($e);
$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
$client = new SoapClient('https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl');
var_dump($request);
var_dump($response);
}
/*
$request = $client->__getLastRequest();
$response = $client->__getLastResponse();
var_dump($request);
var_dump($response);
*/
?>
