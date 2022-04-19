<?php
date_default_timezone_set("Europe/Madrid");
setlocale(LC_TIME, "spanish");
$_SESSION['estado_convocatoria']=0;
$_SESSION['mantenimiento']=MANTENIMIENTO;
$_SESSION['rol'] = 'anonimo';      
//ES un usuario autenticado?. Igual q el rol anónimo
$_SESSION['usuario_autenticado'] =0;
//provincia, todas zaragoza, huesca o teruel
$_SESSION['provincia']='todas';
//Versión PRE o PRO
$_SESSION['version']=VERSION;
$_SESSION['convocatoria']=CONVOCATORIA;
$_SESSION['id_centro'] =-1;      
$_SESSION['fecha_actual'] = date("Y/m/d");      
$_SESSION['url_base'] =URL_BASE;    

if($_SESSION['fecha_actual']<DIA_INICIO_INSCRIPCION)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PREINSCRIPCION;
else if($_SESSION['fecha_actual']>=DIA_INICIO_INSCRIPCION and $_SESSION['fecha_actual']<=DIA_FIN_INSCRIPCION)       
 		$_SESSION['estado_convocatoria'] =ESTADO_INSCRIPCION;
else if($_SESSION['fecha_actual']>DIA_FIN_INSCRIPCION and $_SESSION['fecha_actual']<DIA_PUBLICACION_BAREMADAS)       
 		$_SESSION['estado_convocatoria'] =ESTADO_FININSCRIPCION;
else if($_SESSION['fecha_actual']>=DIA_PUBLICACION_BAREMADAS and $_SESSION['fecha_actual']<DIA_FIN_RECLAMACIONES_BAREMADAS)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PUBLICACION_BAREMADAS;
else if($_SESSION['fecha_actual']>DIA_FIN_RECLAMACIONES_BAREMADAS and $_SESSION['fecha_actual']<DIA_PUBLICACION_DEFINITIVOS)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PUBLICACION_PROVISIONAL;//valor 60
else if($_SESSION['fecha_actual']>=DIA_PUBLICACION_DEFINITIVOS and $_SESSION['fecha_actual']<DIA_FASE3)       
 		$_SESSION['estado_convocatoria'] =ESTADO_DEFINITIVOS;
else
 		$_SESSION['estado_convocatoria'] =ESTADO_FASE2;

      
