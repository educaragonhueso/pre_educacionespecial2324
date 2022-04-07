<?php
class ParamBase {

    protected $idApplication;
    protected $user;

    /**
     * Obtiene el valor de la propiedad idApplication.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public function getIdApplication() {
        return $this->idApplication;
    }

    /**
     * Define el valor de la propiedad idApplication.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public function setIdApplication($value) {
        $this->idApplication = $value;
    }

    /**
     * Obtiene el valor de la propiedad user.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Define el valor de la propiedad user.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public function setUser($value) {
        $this->user = $value;
    }

}


class ParamCreateAdvice extends ParamBase
{

    protected  $oAdvice;

    /**
     * Obtiene el valor de la propiedad oAdvice.
     * 
     * @return
     *     possible object is
     *     {@link Advice }
     *     
     */
    public function getOAdvice() {
        return $this->oAdvice;
    }

    /**
     * Define el valor de la propiedad oAdvice.
     * 
     * @param value
     *     allowed object is
     *     {@link Advice }
     *     
     */
    public function setOAdvice($v) {
        $this->oAdvice = $v;
    }

} 
//preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdldiv class="row fila_filtros" style="display:none">
// Create the object we'll pass back over the SOAP interface. This is the MAGIC!
$advice = new StdClass();
$advice->anagrama ="lhueso@aragon.es";
$advice->application ="GIR";
$advice->date ="05/02/2021";
$advice->description ="Aviso Pruebas Admision";
$advice->entityId ="10050";
$advice->mailSubject ="Pruebas admisión";
$advice->subject ="Pruebas admisión subject";
$advice->type ="pruebasdesp";


$pca = new ParamCreateAdvice();
$pca->setUser('00000000T');
$pca->setIdApplication('GIR');
$pca->setOAdvice($advice);
// setup some SOAP options


$params = new StdClass();
$params->arg0=$pca;

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
 
echo "Enviando correo...\n";
try {
        //$result = $client->createAdvice(array("arg0"=>$paramcreateadvice));
        $result = $client->createAdvice(array($params));
        // save our results to some variables
        $TransactionID = $result->createAdviceResult->TransactionID;
        $ResponseCode = $result->createAdviceResult->ResponseCode;
        $ResponseDetail = $result->createAdviceResult->ResponseDetail;
        $AddMinutes = $result->createAdviceResult->AddMinutes;
         echo "CORREO ENVIUADO\n\n";
 
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

