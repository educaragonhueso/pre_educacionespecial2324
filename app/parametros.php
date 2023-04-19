<?php
define("DIR_CSVS",DIR_BASE.'/scripts/datossalida/listadoscsv/');
define("DIR_PROV",DIR_BASE.'/scripts/datossalida/pdflistados/provisionales/');
define("DIR_PROV_WEB",EDICION.'/scripts/datossalida/pdflistados/provisionales/');
define("DIR_CSVS_WEB",EDICION.'/scripts/datossalida/listadoscsv/');
define("DIR_SOR",DIR_BASE.'/scripts/datossalida/pdflistados/sorteo/');
define("DIR_SOR_WEB",EDICION.'/scripts/datossalida/pdflistados/sorteo/');

//Arrays de campos cabecera para los listados
//NUMERO ASIGNADO DE SORTEO
$campos_cabecera_sor_ale=array('Primer apellido','Segundo apellido','Nombre','Tipo enseñanza','Numero aleatorio');
$campos_bbdd_sor_ale=array('apellido1','apellido2','nombre','tipoestudios','nasignado');

//SOLICITUDES BAREMADAS
$campos_cabecera_sor_bar=array('Estado solicitud','Primer apellido','Segundo apellido','Nombre','Enseñanza','Prioridad','Total baremo');
$campos_bbdd_sor_bar=array('estado_solicitud','apellido1','apellido2','nombre','tipoestudios','transporte','puntos_validados');

//DETALLE BAREMO
$campos_cabecera_sor_det=array('Primer apellido','Segundo apellido','Nombre','Prox. dom.','Tut. centro','Renta','Disc.Al','Disc. Her','FN','FM','Hermanos','Acog.','Género','Terr.','Parto','Sobrev.','Conjunta');
$campos_bbdd_sor_det=array('apellido1','apellido2','nombre','proximidad_domicilio','validar_tutores_centro','comprobar_renta_inferior','comprobar_discapacidad_alumno','comprobar_discapacidad_hermanos','comprobar_familia_numerosa','comprobar_familia_monoparental','validar_hnos_centro','validar_acogimiento','validar_genero','validar_terrorismo','validar_parto','validar_situacion_sobrevenida','conjunta');

//DATOS PARA EXPORTAR DATOS CSVS
$campos_cabecera_csv_fase2=array('Tipo','Ap1','Ap2','Nombre','Loc','Calle','Reserva','Reserva Original','Centro origen','Cen.Pref','Cen.Alt1','Cen.Alt2','Cen.Alt3','Cen.Alt4','Cen.Alt5','Cen.Alt6','Baremo Fase2','Baremo Original','Prioridad','Estado','Centro definitivo','Numero de sorteo asignado','Modificacion');
$campos_bbdd_csv_fase2=array('tipoestudios','apellido1','apellido2','nombre','localidad','calle_dfamiliar','reserva','reserva_original','centro_origen','nombre_centro','centro1','centro2','centro3','centro4','centro5','centro6','puntos_validados','puntos_validados_originales','transporte','estado_solicitud','centro_definitivo','nasignado','tipo_modificacion');

$campos_cabecera_csv_fase3=array('Tipo','Primer apellido','Segundo apellido','Nombre','Centro Adjudicado');
$campos_bbdd_csv_fase3=array('tipoestudios','apellido1','apellido2','nombre','centro_definitivo');

$campos_cabecera_csv_mat=array('Centro','Grupos EBO','Puestos EBO','Plazas Ocupadas EBO','Solicitudes EBO','Vacantes_EBO','Grupos TVA','Puestos TVA','Plazas Ocupadas TVA','Solicitudes TVA','Vacantes_TVA');
$campos_bbdd_csv_mat=array('nombre_centro','gruposebo','puestosebo','matriculaactualebo','solicitudesebo','vacantesebo','grupostva','puestostva','matriculaactualtva','solicitudestva','vacantestva');

$campos_cabecera_csv_sol=array('Centro','Primer apellido','Segundo apellido','Nombre','Enseñanza','Criterios prioritarios','Localidad domicilio familiar','Fase','Estado','Puntos validados','Numero sorteo asignado','Centro Origen','Reserva de Plaza','Conjunta','Admitida');
$campos_bbdd_csv_sol=array('nombre_centro','apellido1','apellido2','nombre','tipoestudios','transporte','loc_dfamiliar','fase_solicitud','estado_solicitud','puntos_validados','nasignado','nombre_centro_origen','reserva','conjunta','est_desp_sorteo');

//listado de tributantes
$campos_cabecera_csv_tri=array('Primer apellido','Segundo
apellido','Nombre','Parentesco','DNI','Nombre Centro');
$campos_bbdd_csv_tri=array('apellido1','apellido2','nombre','parentesco','dni','nombre_centro');
//listado de alumnos que promocionan, la matricula
$campos_cabecera_csv_pro=array('Centro','Apellidos','Nombre','Enseñanza','Fecha Nacimiento','estado');
$campos_bbdd_csv_pro=array('nombre_centro','apellidos','nombre','tipo_alumno_actual','fnac','estado');

//listado de alumnos duplicados
$campos_cabecera_csv_dup=array('Centro','Primer apellido','Segundo apellido','Nombre','Enseñanza','Fecha Nacimiento','DNI Tutor');
$campos_bbdd_csv_dup=array('nombre_centro','apellido1','apellido2','nombre','tipoestudios','fnac','dni_tutor1');

//DATOS PARA EXPORTAR DATOS PDF
$campos_cabecera_pdf_mat=array('Centro','Grupos EBO','Puestos EBO','Plazas Ocupadas EBO','Vacantes_EBO','Grupos TVA','Puestos TVA','Plazas Ocupadas TVA','Vacantes_TVA');
$campos_bbdd_pdf_mat=array('nombre_centro','gruposebo','puestosebo','plazasactualesebo','vacantesebo','grupostva','puestostva','plazasactualestva','vacantestva');

//DATOS PARA LISTADOS PROVISIONALES
$campos_cabecera_admitidos_prov=array('Nombre centro','Tipo','Nº Orden','NºAleatorio','Primer Apellido','Segundo apellido','Nombre','Criterios prioritarios','Puntos Baremo');
$campos_bbdd_admitidos_prov=array('nombre_centro','tipoestudios','nordensorteo','nasignado','apellido1','apellido2','nombre','transporte','puntos_validados',);

$campos_cabecera_noadmitidos_prov=array('Nombre centro','Tipo','Nº Orden','NºAleatorio','Primer Apellido','Segundo apellido','Nombre','Criterios prioritarios','Puntos Baremo');
$campos_bbdd_noadmitidos_prov=array('nombre_centro','tipoestudios','nordensorteo','nasignado','apellido1','apellido2','nombre','transporte','puntos_validados');

$campos_cabecera_excluidos_prov=array('Nombre centro','Tipo','Nº Orden','NºAleatorio','Primer Apellido','Segundo apellido','Nombre','Criterios prioritarios','Puntos Baremo');
$campos_bbdd_excluidos_prov=array('nombre_centro','tipoestudios','nordensorteo','nasignado','apellido1','apellido2','nombre','transporte','puntos_validados');

//DATOS PARA LISTADOS DEFINITIVOS
$campos_cabecera_admitidos_def=array('Nombre centro','Tipo','Nº Orden','NºAleatorio','Primer Apellido','Segundo apellido','Nombre','Criterios prioritarios','Puntos Baremo');
$campos_bbdd_admitidos_def=array('nombre_centro','tipoestudios','nordensorteo','nasignado','apellido1','apellido2','nombre','transporte','puntos_validados');

$campos_cabecera_noadmitidos_def=array('Nombre centro','Tipo','Nº Orden','NºAleatorio','Primer Apellido','Segundo apellido','Nombre','Criterios prioritarios','Puntos Baremo');
$campos_bbdd_noadmitidos_def=array('nombre_centro','tipoestudios','nordensorteo','nasignado','apellido1','apellido2','nombre','transporte','puntos_validados');

$campos_cabecera_excluidos_def=array('Nombre centro','Tipo','Nº Orden','NºAleatorio','Primer Apellido','Segundo apellido','Nombre','Criterios prioridad','Puntos Baremo');
$campos_bbdd_excluidos_def=array('nombre_centro','tipoestudios','nordensorteo','nasignado','apellido1','apellido2','nombre','transporte','puntos_validados');

//DATOS PARA LISTADOS SOLICITUDES FASE II
//listado sorteo fase2
$campos_cabecera_lfase2_sol_sor=array('Ap1','Ap2','Nombre','Tipo estudios','nasignado');
$campos_bbdd_lfase2_sol_sor=array('apellido1','apellido2','nombre','tipoestudios','nasignado');

/*
$campos_cabecera_lfase2_sol_ebo=array('Ap1','Ap2','Nombre','Cen.Adj');
$campos_bbdd_lfase2_sol_ebo=array('apellido1','apellido2','nombre','centro_definitivo');
*/
$campos_cabecera_lfase2_sol_ebo=array('Ap1','Ap2','Nombre','Loc','Calle','Cen.Ori','Cen.Pref','Cen.Alt','Baremo','Prioridad','Estado','Cen.Adj','NSorteo','Centros','Reserva');
$campos_bbdd_lfase2_sol_ebo=array('apellido1','apellido2','nombre','localidad','calle_dfamiliar','centro_origen','nombre_centro','centro1','puntos_validados','transporte','estado_solicitud','centro_definitivo','nasignado','centrosdisponibles','centro_origen');

$campos_cabecera_lfase2_sol_tva=array('Ap1','Ap2','Nombre','Loc','Calle','Cen.Pref','Cen.Alt','Baremo','Prioridad','Estado','Cen.Adj','NSorteo','Centros','Reserva');
$campos_bbdd_lfase2_sol_tva=array('apellido1','apellido2','nombre','localidad','calle_dfamiliar','nombre_centro','centro1','puntos_validados','transporte','estado_solicitud','centro_definitivo','nasignado','centrosdisponibles','centro_origen');

//DATOS PARA LISTADOS SOLICITUDES FASE II FINAL
$campos_cabecera_lfinal_sol_ebo=array('Primer Apellido','Segundo Apellido','Nombre','Centro Solicitado','Centro Definitivo','Teléfono','Correo');
$campos_bbdd_lfinal_sol_ebo=array('apellido1','apellido2','nombre','centro_solicitado','centro_definitivo','telefono','correo');

$campos_cabecera_lfinal_sol_tva=array('Primer Apellido','Segundo Apellido','Nombre','Centro Solicitado','Centro Definitivo','Telefono','Correo');
$campos_bbdd_lfinal_sol_tva=array('apellido1','apellido2','nombre','cetro_solicitado','centro_definitivo','telefono','correo');

//DATOS PARA LISTADOS DE MATRICULA FINAL
$campos_cabecera_mat_final=array('Primer Apellido','Segundo apellido','Nombre','tel_dfamiliar1','email','Puntos Baremo','Matricular');
$campos_bbdd_mat_final=array('apellido1','apellido2','nombre','tel_dfamiliar1','email','puntos_validados');

$campos_cabecera_csv_mat_final=array('Primer Apellido','Segundo apellido','Nombre','tel_dfamiliar1','email','Puntos Baremo');
$campos_bbdd_csv_mat_final=array('apellido1','apellido2','nombre','tel_dfamiliar1','email','puntos_validados');

//DATOS PARA LISTADOS SOLICITUDES FASE III
$campos_cabecera_lfase3_sol_ebo=array('Ap1','Ap2','Nombre','Loc','Calle','Cen.Pref','Cen.Alt','Baremo','Prioridad','Estado','Cen.Adj','NSorteo','Centros','Reserva','nordsor');
$campos_bbdd_lfase3_sol_ebo=array('apellido1','apellido2','nombre','localidad','calle_dfamiliar','nombre_centro','centro1','puntos_validados','transporte','estado_solicitud','centro_definitivo','nasignado','centrosdisponibles','centro_origen','nordensorteo');

$campos_cabecera_lfase3_sol_tva=array('Ap1','Ap2','Nombre','Loc','Calle','Cen.Pref','Cen.Alt','Baremo','Prioridad','Estado','Cen.Adj','NSorteo','Centros','Reserva');
$campos_bbdd_lfase3_sol_tva=array('apellido1','apellido2','nombre','localidad','calle_dfamiliar','nombre_centro','centro1','puntos_validados','transporte','estado_solicitud','centro_definitivo','nasignado','centrosdisponibles','centro_origen');
?>
