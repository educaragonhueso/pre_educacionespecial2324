<?php
$client = new SoapClient('https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl');

$params = array(
$params = array(
'Anagrama'=>'lhueso@aragon.es','IdApplication'=>'GIR','Application'=>'GIR','Date'=>'03/02/2021',
'Description'=>'Aviso pruebas admision edespecial', 'EntityId'=>'10050','User'=>'00000000T',
'MailSubject'=>'Prueba aviso', 'Subject'=>'Subject aviso','Type'=>'pruebasedesp'
);
'Anagrama'=>'lhueso@aragon.es','IdApplication'=>'GIR','Application'=>'GIR','Date'=>'03/02/2021',
'Description'=>'Aviso pruebas admision edespecial', 'EntityId'=>'10050','User'=>'00000000T',
'MailSubject'=>'Prueba aviso', 'Subject'=>'Subject aviso','Type'=>'pruebasedesp'
);
try
{
   $res=$client->createAdvice($params);
}
catch(Exception $e)
{
   print_r($e);
}
#var_dump($res);
?>

