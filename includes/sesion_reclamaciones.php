<?php
date_default_timezone_set("Europe/Madrid");
setlocale(LC_TIME, "spanish");
/*
Estado convocatoria: 
0 carga de matriculados

10 Inicio inscripcion
11 Fin inscripcion

15 dia sorteo

20 Inicio provisional
21 Fin provisional

30 Inicio definitivo
31 Fin definitivo
*/
$_SESSION['estado_convocatoria']=0;
$_SESSION['mantenimiento']=MANTENIMIENTO;

/*Rol por defecto anonimo. 
Otros:
   alumno   alumno dado de alta
   centro   centro educativo
   sp       servicio provincial - huesca, zaragoza y teruel
   admin    administrador
*/
$_SESSION['rol'] = 'anonimo';      
//ES un usuario autenticado?. Igual q el rol anónimo
$_SESSION['usuario_autenticado'] =0;
//provincia, todas zaragoza, huesca o teruel
$_SESSION['provincia']='todas';
//Versión PRE o PRO
$_SESSION['version']=VERSION;
//el id del centro en el caso de que el usuario tenga rol de alumno. en otro caso el id es negativo o -1
//if($_SESSION['rol']=='alumno' or $_SESSION['rol']=='centro')
$_SESSION['id_centro'] =-1;      
$_SESSION['fecha_actual'] = date("Y/m/d");      
$_SESSION['url_base'] =URL_BASE;    

if($_SESSION['fecha_actual']>=DIA_INICIO_INSCRIPCION and $_SESSION['fecha_actual']<=DIA_FIN_INSCRIPCION)       
 		$_SESSION['estado_convocatoria'] =10;//0. inicio inscripciones, 1. dia de sorteo, 2. baremacion, 3. Provisionales, 4. Definitivos      
if($_SESSION['fecha_actual']>DIA_FIN_INSCRIPCION and $_SESSION['fecha_actual']<DIA_INICIO_BAREMADAS)       
 		$_SESSION['estado_convocatoria'] =11;
if($_SESSION['fecha_actual']>=DIA_INICIO_BAREMADAS and $_SESSION['fecha_actual']<DIA_PUBLICACION_PROVISIONAL)       
{
   if($_SESSION['fecha_actual']>=DIA_INICIO_RECLAMACIONES_BAREMADAS and $_SESSION['fecha_actual']<=DIA_FIN_RECLAMACIONES_BAREMADAS)
 		$_SESSION['estado_convocatoria'] =21;
   else if($_SESSION['fecha_actual']==DIA_SORTEO)       
 	   $_SESSION['estado_convocatoria'] =22;
   else
 	  $_SESSION['estado_convocatoria'] =20;
}
if($_SESSION['fecha_actual']>=DIA_PUBLICACION_PROVISIONAL and $_SESSION['fecha_actual']<=DIA_FIN_RECLAMACIONES_PROVISIONAL)       
{
   if($_SESSION['fecha_actual']>=DIA_INICIO_RECLAMACIONES_PROVISIONAL and $_SESSION['fecha_actual']<=DIA_FIN_RECLAMACIONES_PROVISIONAL)
 		$_SESSION['estado_convocatoria'] =31;
 	else
      $_SESSION['estado_convocatoria'] =30;//primer dia publicaicon
}
if($_SESSION['fecha_actual']>DIA_FIN_PROVISIONAL)       
 		$_SESSION['estado_convocatoria'] =40;

      
