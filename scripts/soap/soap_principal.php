<?php
include("funciones_soap.php");
##########################VALORES DE NIFS#################################
//print_r($_POST);
$nif1=$_POST['nif1'];
$nif2=$_POST['nif2'];

if(isset($_POST['nif_alumno']))
   $nif_alumno=$_POST['nif_alumno'];
else
   $nif_alumno='';
   
$nombre=$_POST['nombre'];
$apellido1=$_POST['apellido1'];
$apellido2=$_POST['apellido2'];

if(isset($_POST['fnac']))
{
   $fnac=$_POST['fnac'];
   //$fnac=modfecha($fnac);
}
else
   $fnac='';

$tipo=$_POST['tipo'];
 
$fecha=date("d/m/Y"); 

$respuesta="";
if($tipo=='identidad')
{
   //COMP POR NOMBRE
   if($fnac!='nodata' and $fnac!='')
   {
      $respuesta.="\n\nCOMPROBADA IDENTIDAD BASADA EN LOS DATOS DEL ALUMNO:\n $nombre, $apellido1,\nFecha nacimiento:\n $fnac\n ";
      $respuesta.="\n\n-------------------------------------------------------------------------\n";
      $respuestaalumno=comprobarIdentidadConPadron('nodata',$nombre,$apellido1,$fnac);
      //print_r($respuestaalumno);exit();
      $respuesta.=procesarRespuestaIdentidadconPadron($respuestaalumno,'nodata',$nombre,$apellido1);
   }
   /*
   else if($dni_alumno!='nodata' and $dni_alumno!='')
   {
      $respuesta.="\n\nCOMPROBADA IDENTIDAD BASADA EN EL IDENTIFICADOR DEL ALUMNO:\n ";
      $respuesta.="\n\n-------------------------------------------------------------------------\n";
      $respuestaalumno=comprobarIdentidadConPadron($dni_alumno,$nombre,$apellido1,$fnac);
      //print_r($respuestaalumno);exit();
      $respuesta.=procesarRespuestaIdentidadconPadron($respuestaalumno,'nodata',$nombre,$apellido1);
   }
   */
}
else if($tipo=='padron')
{
   $respuesta="\nRESULTADO COMPROBACIÓN PADRÓN\n";
   $respuesta.="=================================================\n";
   if(($nif1=='' or $nif1!='nodata') and ($nif2=='' or $nif2=='nodata') and ($nombre=='' or $apellido1==''))
   {
      $respuesta="NO TENEMOS DATOS PARA COMPROBAR PADRÓN";
      print($respuesta);
      exit();
   }
   $respuesta.="\nCOMPROBADO PADRÓN BASADA EN IDENTIFICADOR DEL PRIMER TUTOR: \n";
   if($nif1!='' and $nif1!='nodata'){
      $respuestaproc=comprobarPadron($nif1,$nombre,$apellido1,$fnac);
      $respuesta.=procesarRespuestaPadron($respuestaproc,$nif1,'nodata','nodata','tutor');
   }
   else
      $respuesta.="\nNO HAY IDENTIFICADOR DEL PRIMER TUTOR\n ";
   $respuesta.="\nCOMPROBADO PADRÓN BASADA EN EL IDENTIFICADOR DEL SEGUNDO TUTOR:\n ";
   if($nif2!='' and $nif2!='nodata')
   { 
      $respuestaproc=comprobarPadron($nif2,$nombre,$apellido1,$fnac);
      $respuesta.=procesarRespuestaPadron($respuestaproc,$nif2,'nodata','nodata','tutor');
   }
   else
      $respuesta.="\nNO HAY IDENTIFICADOR DEL SEGUNDO TUTOR\n ";
   $respuesta.="\nCOMPROBADO PADRÓN BASADA EN EL IDENTIFICADOR DEL ALUMNO:\n ";
   if($nif_alumno!='' and $nif_alumno!='nodata')
   { 
      $respuestaproc=comprobarPadron($nif_alumno,$nombre,$apellido1,$fnac);
      $respuesta.=procesarRespuestaPadron($respuestaproc,$nif2,'nodata','nodata','alumno');
   }
   else
   { 
      $respuestaproc=comprobarPadron('nodata',$nombre,$apellido1,$fnac);
      $respuesta.=procesarRespuestaPadron($respuestaproc,$nif_alumno,'nodata','nodata','alumno');
   }
}
else if($tipo=='discapacidad')
{
      $fnac=modfecha($fnac);
   if($nif_alumno!='' and $nif_alumno!='nodata')
   {  
      $respuesta="\nRESULTADO COMPROBACIÓN DISCAPACIDAD POR DNI $nif_alumno FECHA: $fnac\n";
      $respuesta.="=============================\n";
      if(is_numeric($nif_alumno[0]))
         $tipo='NIF';
      else
         $tipo='NIE';
      $fnac='';
      $nombre='';
      $apellido1='';
      $respuestaproc=comprobarDiscapacidad($nif_alumno,$tipo,$nombre,$apellido1,$fnac,$fecha);
      $respuesta.=procesarRespuestaDiscapacidad($respuestaproc,$nif1,'nodata','nodata');
   
   }
   else if($nombre!='' and $apellido1!='')
   {
      $respuesta="\nRESULTADO COMPROBACIÓN DISCAPACIDAD POR NOMBRE Y PRIMER APELLIDO\n";
      $respuesta.="=============================\n";
      $tipo='NOID';
      $respuestaproc=comprobarDiscapacidad($nif_alumno,$tipo,$nombre,$apellido1,$fnac,$fecha);
      $respuesta.=procesarRespuestaDiscapacidad($respuestaproc,$nif1,'nodata','nodata');
   
   }
   else
      $respuesta="No hay datos suficientes para comprobar la discapacidad";
}
else if($tipo=='familianumerosa')
{
   $respuesta="\nRESULTADO COMPROBACIÓN FAMILIA NUMEROSA\n";
   $respuesta.="=====================================================\n";
   $fnac=modfecha($fnac);
   if($nif1!='' and $nif1!='nodata')
   {
      $respuesta.="\nCOMPROBANDO FAMILIA NUMEROSA BASADA EN EL NIF DEL PRIMER TUTOR: $nif1 \n  ";
      $respuestaproc=comprobarFamiliaNumerosa($nif1,$fecha,$nombre,$apellido1,$apellido2,$fnac);
      $respuesta.=procesarRespuestaFamiliaNumerosa($respuestaproc,$nif1,'nodata','nodata');
   }
   if($nif2!='' and $nif2!='nodata')
   { 
      $respuesta.="\nCOMPROBANDO FAMILIA NUMEROSA BASADA EN EL NIF DEL SEGUNDO TUTOR: $nif2\n ";
      $respuestaproc=comprobarFamiliaNumerosa($nif2,$fecha,$nombre,$apellido1,$apellido2,$fnac);
      $respuesta.=procesarRespuestaFamiliaNumerosa($respuestaproc,$nif2,'nodata','nodata');
   }
   if($nif_alumno!='' and $nif_alumno!='nodata')
   { 
      $respuesta.="\nCOMPROBANDO FAMILIA NUMEROSA BASADA EN EL NIF DEL ALUMNO: $nif_alumno\n ";
      $respuestaproc=comprobarFamiliaNumerosa($nif_alumno,$fecha,$nombre,$apellido1,$apellido2,$fnac);
      $respuesta.=procesarRespuestaFamiliaNumerosa($respuestaproc,$nif_alumno,'nodata','nodata');
   }

}
else if($tipo=='imv')
{
   $respuesta="\nRESULTADO COMPROBACIÓN INGRESO MÍNIMO VITAL\n";
   $respuesta.="=====================================================\n";
   if($nif1!='' and $nif1!='nodata')
   {
      $respuesta.="\nCOMPROBANDO IMV BASADA EN EL NIF DEL PRIMER TUTOR: $nif1\n  ";
      $respuestaproc=comprobarImv($nif1);
      $respuesta.=procesarRespuestaImv($respuestaproc);
   }
   if($nif2!='' and $nif2!='nodata')
   {
      $respuesta.="\nCOMPROBANDO IMV BASADA EN EL NIF DEL SEGUNDO TUTOR: $nif2\n ";
      $respuestaproc=comprobarImv($nif2);
      $respuesta.=procesarRespuestaImv($respuestaproc);
   }

}


print($respuesta);


