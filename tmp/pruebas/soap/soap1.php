<?php
$client = new SoapClient('https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl');

$params = array(
'anagrama'=>'lhueso@aragon.es', 'application'=>'GIR','idApplication'=>'GIR','date'=>'27/01/2021',
'description'=>'Aviso pruebas admision edespecial', 'entityId'=>'10050','user'=>'00000000T',
'mailSubject'=>'Prueba aviso', 'subject'=>'Subject aviso','type'=>'pruebasedesp'
);
try
{
   $res=$client->__soapCall('createAdvice', array($params));
}
catch(Exception $e)
{
   print_r($e);
}
?>

