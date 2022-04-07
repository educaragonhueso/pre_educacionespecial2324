<?php
include("funciones_soap.php");
##########################VALORES DE NIFS#################################
//print_r($_POST);
$nif1=$_POST['nif1'];
$nif2=$_POST['nif2'];

$nif_alumno=$_POST['nif_alumno'];
$nombre=$_POST['nombre'];
$apellido1=$_POST['apellido1'];
$apellido2=$_POST['apellido2'];
$fnac=$_POST['fnac'];
$tipo=$_POST['tipo'];
$nifdisc=$_POST['nifdisc'];

if($tipo=='identidad')
{
   //COMP POR NOMBRE
   if($fnac!='nodata' and $fnac!='')
   {
      $respuesta.="\n\nCOMPROBADA IDENTIDAD BASADA EN LOS DATOS DEL ALUMNO:\n ";
      $respuesta.="\n\n-------------------------------------------------------------------------\n";
      $respuestaalumno=comprobarIdentidad('nodata',$nombre,$apellido1,$fnac);
      //print_r($respuestaalumno);exit();
      $respuesta.=procesarRespuestaIdentidad($respuestaalumno,'nodata',$nombre,$apellido1);
   }
}
if($tipo=='padron')
{
   $respuesta="\nRESULTADO COMPROBACIÓN PADRÓN\n";
   $respuesta.="=================================================\n";
   if(($nif1=='' or $nif1!='nodata') and ($nif2=='' or $nif2=='nodata') and ($nombre=='' or $apellido1==''))
   {
      $respuesta="NO TENEMOS DATOS PARA COMPROBAR PADRÓN";
      print($respuesta);
      exit();
   }
   if($nif1!='' and $nif1!='nodata'){
      $respuesta="\nCOMPROBADO PADRÓN BASADA EN EL NIF DEL PRIMER TUTOR: \n";
      $respuestaproc=comprobarPadron($nif1,$nombre,$apellido1,$fnac);
      $respuesta.=procesarRespuestaPadron($respuestaproc,$nif1,'nodata','nodata');
   }
   if($nif2!='' and $nif2!='nodata')
   { 
      $respuesta.="\nCOMPROBADO PADRÓN BASADA EN EL NIF DEL SEGUNDO TUTOR:\n ";
      $respuestaproc=comprobarPadron($nif2,$nombre,$apellido1,$fnac);
      $respuesta.=procesarRespuestaPadron($respuestaproc,$nif2,'nodata','nodata');
   }
   //COMP POR NOMBRE
   if($fnac!='nodata' and $fnac!='')
   {
      $respuesta.="\n\nCOMPROBADA IDENTIDAD BASADA EN LOS DATOS DEL ALUMNO:\n ";
      $respuestaalumno=comprobarPadron('nodata',$nombre,$apellido1,$fnac);
      //print_r($respuestaalumno);exit();
      $respuesta.=procesarRespuestaPadron($respuestaalumno,'nodata',$nombre,$apellido1);
   }
}
if($tipo=='discapacidad')
{
   $respuesta="\nRESULTADO COMPROBACIÓN DISCAPACIDAD\n";
   $respuesta.="=============================\n";
   if($nifdisc!='' and $nifdisc!='nodata')
   {
         if(is_numeric($nifdisc[0]))
            $tipo='NIF';
         else
            $tipo='NIE';
         $respuestaproc=comprobarDiscapacidad($nifdisc,$tipo);
         $respuesta.=procesarRespuestaDiscapacidad($respuestaproc,$nif1,'nodata','nodata');
   }
   else
   {
      if($nif_alumno!='' and $nif_alumno!='nodata')
      {
         if(is_numeric($nif_alumno[0]))
            $tipo='NIF';
         else
            $tipo='NIE';
         $respuestaproc=comprobarDiscapacidad($nif_alumno,$tipo);
         $respuesta.=procesarRespuestaDiscapacidad($respuestaproc,$nif1,'nodata','nodata');
      
      }
      else
         $respuesta.="No hay datos del DNI del alumno";
   }

}
if($tipo=='familianumerosa')
{
   $respuesta="\nRESULTADO COMPROBACIÓN FAMILIA NUMEROSA\n";
   $respuesta.="=====================================================\n";
   if($nif1!='' and $nif1!='nodata')
   {
      $respuesta.="\nCOMPROBANDO FAMILIA NUMEROSA BASADA EN EL NIF DEL PRIMER TUTOR: $nif1\n  ";
      $respuestaproc=comprobarFamiliaNumerosa($nif1);
      $respuesta.=procesarRespuestaFamiliaNumerosa($respuestaproc,$nif1,'nodata','nodata');
   }
   if($nif2!='' and $nif2!='nodata')
   { 
      $respuesta.="\nCOMPROBANDO FAMILIA NUMEROSA BASADA EN EL NIF DEL SEGUNDO TUTOR: $nif2\n ";
      $respuestaproc=comprobarFamiliaNumerosa($nif2);
      $respuesta.=procesarRespuestaFamiliaNumerosa($respuestaproc,$nif2,'nodata','nodata');
   }
   if($nif_alumno!='' and $nif_alumno!='nodata')
   { 
      $respuesta.="\nCOMPROBANDO FAMILIA NUMEROSA BASADA EN EL NIF DEL ALUMNO: $nif_alumno\n ";
      $respuestaproc=comprobarFamiliaNumerosa($nif_alumno);
      $respuesta.=procesarRespuestaFamiliaNumerosa($respuestaproc,$nif_alumno,'nodata','nodata');
   }

}

print($respuesta);


