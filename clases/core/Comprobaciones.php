<?php
function comprobarChecks($fsol_salida) 
{
   //comprobamos los campos tipo check
   if(!isset($fsol_salida['baremo_marcado_proximidad_domicilio']))
      $fsol_salida['baremo_marcado_proximidad_domicilio']=0;
   if(!isset($fsol_salida['baremo_proximidad_domicilio']))
      $fsol_salida['baremo_proximidad_domicilio']=0;
   if(!isset($fsol_salida['baremo_tutores_centro']))
      $fsol_salida['baremo_tutores_centro']=0;
   if(!isset($fsol_salida['baremo_situacion_sobrevenida']))
      $fsol_salida['baremo_situacion_sobrevenida']=0;
   if(!isset($fsol_salida['baremo_renta_inferior']))
      $fsol_salida['baremo_renta_inferior']=0;
   if(!isset($fsol_salida['baremo_acogimiento']))
      $fsol_salida['baremo_acogimiento']=0;
   if(!isset($fsol_salida['baremo_genero']))
      $fsol_salida['baremo_genero']=0;
   if(!isset($fsol_salida['baremo_terrorismo']))
      $fsol_salida['baremo_terrorismo']=0;
   if(!isset($fsol_salida['baremo_parto']))
      $fsol_salida['baremo_parto']=0;
   if(!isset($fsol_salida['baremo_discapacidad_hermanos']))
      $fsol_salida['baremo_discapacidad_hermanos']=0;
   if(!isset($fsol_salida['baremo_discapacidad_alumno']))
      $fsol_salida['baremo_discapacidad_alumno']=0;
   if(!isset($fsol_salida['baremo_marcado_numerosa']))
      $fsol_salida['baremo_marcado_numerosa']=0;
   if(!isset($fsol_salida['baremo_tipo_familia_numerosa']))
      $fsol_salida['baremo_tipo_familia_numerosa']=0;
   if(!isset($fsol_salida['baremo_marcado_monoparental']))
      $fsol_salida['baremo_marcado_monoparental']=0;
   if(!isset($fsol_salida['baremo_tipo_familia_monoparental']))
      $fsol_salida['baremo_tipo_familia_monoparental']=0;
   if(!isset($fsol_salida['nuevaesc']))
      $fsol_salida['nuevaesc']=0;
   if(!isset($fsol_salida['num_hbaremo']))
      $fsol_salida['num_hbaremo']=0;
   if(!isset($fsol_salida['cumplen']))
      $fsol_salida['cumplen']=0;
   if(!isset($fsol_salida['oponenautorizar']))
      $fsol_salida['oponenautorizar']=0;
   if(!isset($fsol_salida['reserva']))
      $fsol_salida['reserva']=0;

   return $fsol_salida;
}
