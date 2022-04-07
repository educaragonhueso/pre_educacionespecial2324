<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';

$log_enviosmss=new logWriter('log_enviosmss',DIR_LOGS);

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$wsdl ='https://aplicaciones.aragon.es/sga_core/services/AdviceService?wsdl';
include("../../clases/core/Notificacion.php");
$notificacion=new Notificacion($wsdl);
$tsolicitud=new Solicitud($conexion);
//$ct=$tsolicitud->copiaTablaCentro(0,'alumnos_provisional');	

$asolicitudes=array();
$asolicitudes=$tsolicitud->getTodasSolicitudes();
//preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdldiv class="row fila_filtros" style="display:none">
// Create the object we'll pass back over SOAP interface. This is the MAGIC!
foreach($asolicitudes as $sol)
{
   $token=$sol->token;
   $usuario=$sol->dni_tutor1;
   $usuario=$sol->dni_tutor1;
   $token=$sol->token;
   $clave=$tsolicitud->getClave($token);
  //print_r($token);exit();
   $telefono=$sol->tel_dfamiliar1;
   $enlacefirma="Usuario: $usuario Clave: $clave\n";
   $enlacefirma.="Pulsa para firmar tu solicitud de Ed. Especial:\n https://admespecial.aragon.es/educacionespecial/index.php?firma=$token";
   $ressms=$notificacion->enviarSMS($telefono,$enlacefirma);
   print("ENVIADO SMS A: ".PHP_EOL);
   print($enlacefirma.PHP_EOL);
   print($telefono.PHP_EOL);
   print_r($ressms);
   sleep(1);
}

/*
$res=$notificacion->enviarCorreo(0,'enlace','obabakoak@gmail.com','contenido');
print_r($res);
$advice = new StdClass();
$advice->user ="29117207N";
$advice->idApplication ="GIR";
$advice->id ="8050";

$adviceSMS = new StdClass();
$adviceSMS->anagrama ="lhueso@aragon.es";
$adviceSMS->application ="GIR";
$adviceSMS->date ="24/02/2021";
$adviceSMS->description ="Aviso Pruebas Admision";
$adviceSMS->entityId ="10050";
$adviceSMS->mailSubject ="Pruebas admisión";
$adviceSMS->subject ="Pruebas admisión subject";
//$adviceSMS->phoneNumber ="609404619";
$adviceSMS->phoneNumber ="670218415";
//$adviceSMS->phoneNumber ="635542697";
$adviceSMS->requestType ="SMS";
$adviceSMS->textSMS ="pruebas admision";
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
*/
