<?php
class Notificacion{
    private $advice,$client;
  
    public function __construct($wsdl,$apid,$fecha,$idmail=0) {
      //$wsdl ='https://preaplicaciones.aragon.es/sga_core/services/AdviceService?wsdl';
      $soap_options = array('trace'=>1,'exceptions'=>1);
      $this->client = new SoapClient($wsdl,$soap_options);
      $advice = new StdClass();
      //$advice->anagrama ="lhueso@aragon.es";
      $advice->application =$apid;
      $advice->date =$fecha;
      $advice->description ="Aviso Pruebas Admision";
      $advice->id =$idmail;
      $arg0 = new StdClass();
      $arg0->oAdvice=$advice;
      $arg0->idApplication=$apid;
      
      $this->notificacion = new StdClass();
      $this->notificacion->arg0=$arg0;
    }
   public function enviarCorreo($subject,$correo,$contenido='',$tipo)
   {
      $this->notificacion->arg0->oAdvice->description=$contenido;
      $this->notificacion->arg0->oAdvice->type=$tipo;
      $this->notificacion->arg0->oAdvice->mailSubject =$subject;
      $this->notificacion->arg0->oAdvice->subject =$subject;
      $this->notificacion->arg0->oAdvice->anagrama =$correo;
      try 
      {
        $result = $this->client->createAdvice($this->notificacion);
       } catch (SOAPFault $f) {
         return $f;
      }
      $res = $this->client->__getLastResponse();
    return $res;
    }
   public function enviarSMS($telefono='',$contenido='')
   {
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
      $adviceSMS->phoneNumber =$telefono;
      $adviceSMS->requestType ="SMS";
      $adviceSMS->textSMS =$contenido;
      $adviceSMS->type ="pruebas sms";
      
      $advice->adviceSMS =$adviceSMS;;

      $sms = new StdClass();
      $sms->arg0=$advice;

      try 
      {
        $result = $this->client->createAdviceSMS($sms);
       } catch (SOAPFault $f) {
         print_r($f);        
         // handle the fault here
         return 0;
      }
      $res = $this->client->__getLastResponse();
    return $res;
    }
}
?>
