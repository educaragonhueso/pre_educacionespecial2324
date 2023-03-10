<?php
date_default_timezone_set("Europe/Madrid");
setlocale(LC_TIME, "spanish");

$_SESSION['ainicio_ebo']=AINICIO_EBO;
$_SESSION['afin_ebo']=AFIN_EBO;
$_SESSION['ainicio_tva']=AINICIO_TVA;
$_SESSION['afin_tva']=AFIN_TVA;
$_SESSION['ados']=ADOS;

$_SESSION['cursoanterior_largo']=CURSOANTERIOR_LARGO;
$_SESSION['curso_largo']=CURSO_LARGO;
$_SESSION['edicion']=EDICION;
$_SESSION['estado_convocatoria']=0;
$_SESSION['mantenimiento']=MANTENIMIENTO;
$_SESSION['rol'] = 'anonimo';      
//ES un usuario autenticado?. Igual q el rol anónimo
$_SESSION['usuario_autenticado'] =0;
//provincia, todas zaragoza, huesca o teruel
$_SESSION['provincia']='todas';
//Versión PRE o PRO
$_SESSION['version']=VERSION;
$_SESSION['id_centro'] =-1;      
$_SESSION['fecha_actual'] = date("Y/m/d H:i:s");      
$_SESSION['url_base'] =URL_BASE;    

if($_SESSION['fecha_actual']<DIA_INICIO_MATRICULA) 
 		$_SESSION['estado_convocatoria'] =ESTADO_PREMATRICULA;
else if($_SESSION['fecha_actual']>=DIA_INICIO_MATRICULA and $_SESSION['fecha_actual']<DIA_INICIO_INSCRIPCION)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PREINSCRIPCION;
else if($_SESSION['fecha_actual']>=DIA_INICIO_INSCRIPCION and $_SESSION['fecha_actual']<=DIA_FIN_INSCRIPCION)       
 		$_SESSION['estado_convocatoria'] =ESTADO_INSCRIPCION;
else if($_SESSION['fecha_actual']>DIA_FIN_INSCRIPCION and $_SESSION['fecha_actual']<DIA_PUBLICACION_BAREMADAS)       
 		$_SESSION['estado_convocatoria'] =ESTADO_FININSCRIPCION;
else if($_SESSION['fecha_actual']>=DIA_PUBLICACION_BAREMADAS and $_SESSION['fecha_actual']<DIA_INICIO_RECLAMACIONES_BAREMADAS)
      $_SESSION['estado_convocatoria'] =ESTADO_PUBLICACION_BAREMADAS;
else if($_SESSION['fecha_actual']>=DIA_INICIO_RECLAMACIONES_BAREMADAS and $_SESSION['fecha_actual']<=DIA_FIN_RECLAMACIONES_BAREMADAS)
      $_SESSION['estado_convocatoria'] =ESTADO_RECLAMACIONES_BAREMADAS;
else if($_SESSION['fecha_actual']>DIA_FIN_RECLAMACIONES_BAREMADAS and $_SESSION['fecha_actual']<DIA_ALEATORIO)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PREALEATORIO;
else if($_SESSION['fecha_actual']>DIA_ALEATORIO and $_SESSION['fecha_actual']<DIA_SORTEO)       
 		$_SESSION['estado_convocatoria'] =ESTADO_ALEATORIO;
else if($_SESSION['fecha_actual']>=DIA_SORTEO and $_SESSION['fecha_actual']<DIA_PUBLICACION_PROVISIONAL)
      $_SESSION['estado_convocatoria'] =ESTADO_SORTEO;
else if($_SESSION['fecha_actual']>=DIA_PUBLICACION_PROVISIONAL and $_SESSION['fecha_actual']<DIA_INICIO_RECLAMACIONES_PROVISIONAL)
      $_SESSION['estado_convocatoria'] =ESTADO_PUBLICACION_PROVISIONAL;
else if($_SESSION['fecha_actual']>=DIA_INICIO_RECLAMACIONES_PROVISIONAL and $_SESSION['fecha_actual']<=DIA_FIN_RECLAMACIONES_PROVISIONAL)
      $_SESSION['estado_convocatoria'] =ESTADO_RECLAMACIONES_PROVISIONAL;
else if($_SESSION['fecha_actual']>DIA_FIN_RECLAMACIONES_PROVISIONAL and $_SESSION['fecha_actual']<DIA_PUBLICACION_DEFINITIVOS)       
 		$_SESSION['estado_convocatoria'] =ESTADO_DEFINITIVOS;
else if($_SESSION['fecha_actual']>=DIA_PUBLICACION_DEFINITIVOS and $_SESSION['fecha_actual']<DIA_INICIO_ASIGNACIONES)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PUBLICACION_DEFINITIVOS;
else if($_SESSION['fecha_actual']>=DIA_INICIO_ASIGNACIONES and $_SESSION['fecha_actual']<DIA_PUBLICACION_ASIGNACIONES)       
 		$_SESSION['estado_convocatoria'] =ESTADO_ASIGNACIONES;
else if($_SESSION['fecha_actual']>=DIA_PUBLICACION_ASIGNACIONES and $_SESSION['fecha_actual']<DIA_PUBLICACION_ASIGNACIONES)       
 		$_SESSION['estado_convocatoria'] =ESTADO_PUBLICACION_ASIGNACIONES;
else
 		$_SESSION['estado_convocatoria'] =ESTADO_FIN;

      
