<?php

$url = 'https://secure.softwarekey.com/solo/webservices/XmlCustomerService.asmx?WSDL';
$client = new SoapClient($url);
$client = new SoapClient('https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl');

$xmlr = new SimpleXMLElement("<createAdvice></createAdvice>");
$xmlr->addChild('AuthorID', 1);
$xmlr->addChild('UserID', 'mchojrin');
$xmlr->addChild('UserPassword', '1234');
$xmlr->addChild('Email', 'mauro.chojrin@leewayweb.com');

$params = new stdClass();
$params->xml = $xmlr->asXML(); // OJO: La propiedad xml es particular de este WebService, debes reemplazarla por el nombre del parámetro que espera recibir el servicio al que buscas conectarte

$result = $client->CustomerSearchS($params);

print_r($result);

echo PHP_EOL;

