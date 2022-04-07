<?php
class Verificacion{
    private $advice,$client,$identificationData,$purposeData,$residenceSpecificDataVR,$specificBirthDataVR;
    private $wsdlpadron ='https://preaplicaciones.aragon.es/svcd_core/services/ResidenceVerificationDate?wsdl';
  
    public function __construct($destino='',$app='GIR',$fecha='',$descripcion='',$entityId='',$id='',$mailSubject='',$type='') {
      $this->soap_options = array('trace'=>1,'exceptions'=>1);
      
    }
   public function verificarPadron($id_alumno)
   {
      $soap_options = array('trace'=>1,'exceptions'=>1);
      $wsdlpadron ='https://preaplicaciones.aragon.es/svcd_core/services/ResidenceVerificationDate?wsdl';
      $identificationData = new StdClass();
      $identificationData->documentNumber ="30000052W";
      $identificationData->documentType ="NIF";
      
      $purposeData = new StdClass();
      $purposeData->consent ="Si";
      $purposeData->fileNumber ="001";
      $purposeData->procedureNumber ="466";
      $purposeData->purposeText ="Test";
      $purposeData->purposeValidationCode ="466";
      
      $residenceSpecificDataVR= new StdClass();
      $residenceSpecificDataVR->province = "50";
      
      $userSpecificDataVR= new StdClass();
      $userSpecificDataVR->nationality = "Espanyol";
     
      $userData = new StdClass();
      $userData->name = "EDUARDO";
      $userData->surname1 = "SANCHEZ";
      $userData->surname2 = "MARTIN";
      
      $residenceVerificationRequest= new StdClass();
      $residenceVerificationRequest->identificationData=$identificationData;
      $residenceVerificationRequest->purposeData=$this->purposeData;
      $residenceVerificationRequest->residenceSpecificDataVR=$residenceSpecificDataVR;
      $residenceVerificationRequest->specificBirthDataVR=$specificBirthDataVR;
      $residenceVerificationRequest->userSpecificDataVR=$userSpecificDataVR;
      $residenceVerificationRequest->userData=$userData;
      
      $arg0 = new StdClass();
      $arg0->applicationId="GIR";
      $arg0->organismCode="ORG07458";
      $arg0->userCode="00000000T";
      $arg0->residenceVerificationRequest=$residenceVerificationRequest;

      $residenceVerification = new StdClass();
      $residenceVerification->arg0 =$arg0;
      $clientesoap = new SoapClient($wsdlpadron,$soap_options);
      try {
         $result = $clientesoap->residenceVerificationDate($residenceVerification);
      } catch (SOAPFault $f) {
         print_r($f);
      }
      $res = $this->client->__getLastResponse();
    return $res;
    }
}
?>
