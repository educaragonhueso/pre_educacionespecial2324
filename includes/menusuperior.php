<?php 
if(isset($_SESSION['provincia'])) {$provincia=$_SESSION['provincia'];} else $provincia='todas';
$listado='disponible';
$directoriobase=$_SESSION['edicion'];
$ficheroebo='scripts/datossalida/pdflistados/sorteo/lfase2_sol_ebo_admin.pdf';
if(isset($_SESSION))
   $token=$_SESSION['token'];
else
   $token='';
/*
if($_SESSION['version']=='PRE' or $_SESSION['mantenimiento']=='SI')
   print_r($_SESSION);
*/
if (!file_exists($ficheroebo))
   $listado='';
if(isset($_SESSION))
   $rol=$_SESSION['rol'];
else
   $rol='anonimo';
if(isset($_GET['token']))
   $_SESSION['token']=$_GET['token'];
elseif((($rol=='anonimo' OR $rol=='alumno') and $estado_convocatoria<=ESTADO_FININSCRIPCION))
   $token=bin2hex(random_bytes(8));;
?>            
<h2 style='text-align:center;'>ADMISION ALUMNOS EDUCACION ESPECIAL CURSO <?php echo $_SESSION['curso_largo']; ?> </h2>
<p hidden id='id_centro'><?php echo $_SESSION['id_centro'];?></p> 
<p hidden id='estado_convocatoria' value='<?php echo $_SESSION['estado_convocatoria'];?>'></p> 
<p hidden id='rol' value='<?php echo $_SESSION['rol'];?>'></p> 
<p hidden id='id_alumno' value='<?php echo $_SESSION['id_alumno'];?>'></p> 
<p hidden id='token' value='<?php echo $token;?>'></p> 
<p hidden id='numero_sorteo' value='<?php echo $_SESSION['numero_sorteo'];?>'></p> 
<p hidden id='provincia' value='<?php echo $_SESSION['provincia'];?>'></p> 
<p hidden id='id_alumnonuevo' value='-1'></p> 
<!--elementos a la izda del segundo menu-->
<nav id='navgir' class="navbar navbar-expand-md navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
     <ul class="navbar-nav" style="margin-right:12%">
        <li class="nav-item  msuperior">
         <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'';?>'>INICIO</a>
        </li>
        <li class="nav-item  msuperior">
         <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'/login_out.php';?>'>SALIR</a>
        </li>
        <li class="nav-item active msuperior dropdown">
          <a class="nav-link dropdown-toggle desplegable" id="navbardrop" data-toggle="dropdown">Documentación</a>
           <div class="dropdown-menu">
             <a class="dropdown-item" href="documentacion/orden_escolarizacion_2324.pdf" id="doca4" target="_blank">Orden Escolarización 23/24</a>
             <a class="dropdown-item" href="documentacion/res_unificacion_eespecial.pdf" id="doca4" target="_blank">Resolución unificación centros Especial</a>
             <a class="dropdown-item" href="documentacion/a3a_domlaboralcajena_2324.pdf" id="doca4" target="_blank">Anexo III A Certificado Domicilio Laborali Cuenta Ajena</a>
             <a class="dropdown-item" href="documentacion/a3a_domlaboralcpropia_2324.pdf" id="doca4" target="_blank">Anexo III B Responsable Domicilio Laboral Cuenta Propia</a>
             <a class="dropdown-item" href="documentacion/a7_suspension_matricula_2324.pdf" id="doca4" target="_blank">Anexo VII suspensión temporal matrícula</a>
             <a class="dropdown-item" href="documentacion/a1a_calendario_2324.pdf" target="_blank">Calendario Admisión Educación Especial</a>;
             <a class="dropdown-item" href="documentacion/rel_centro_pref_localidad.pdf" target="_blank">Relación centro preferencia localidad</a>;
             <?php if($_SESSION['rol']=='admin'){?>
               <a class="dropdown-item" href="documentacion/doc_pruebas.html" id="ccen" target="_blank">Claves centros</a>;
               <a class="dropdown-item" href="documentacion/csv_comprobaciones.csv" id="csvc" target="_blank">CSV Comprobaciones</a>;
               <a class="dropdown-item" href="documentacion/csv_comprobaciones_discapacidad.csv" id="csvc" target="_blank">CSV Comprobaciones Discapacidad</a>;
               <a class="dropdown-item" href="documentacion/csv_comprobaciones_imv.csv" id="csvc" target="_blank">CSV Comprobaciones IMV</a>;
               <a class="dropdown-item" href="documentacion/datos_baremo_totales.html" id="csvc" target="_blank">Datos baremo totales</a>;
            <?php } ?>
          </div>
         </li>
      </ul>
<?php 
   if($_SESSION['usuario_autenticado']==1)
   {

      if(($_SESSION['rol']=='alumno' and ($_SESSION['estado_convocatoria']>=ESTADO_RECLAMACIONES_BAREMADAS)) or $_SESSION['rol']=='admin')
      {
      echo '<li class="nav-item msuperior dropdown itemderecho">';
            if($_SESSION['estado_convocatoria']>=ESTADO_RECLAMACIONES_BAREMADAS)
         echo '<a class="show_provisionales nav-link dropdown-toggle desplegable" id="navbardrop" data-toggle="dropdown" href="#">Formulario reclamaciones</a>';
         echo '<div class="dropdown-menu">';
            if($_SESSION['estado_convocatoria']>=ESTADO_RECLAMACIONES_BAREMADAS AND $_SESSION['estado_convocatoria']<=ESTADO_ALEATORIO)
               echo "<a id='reclamacion_baremo'  class='reclamacion dropdown-item' href='reclamaciones_baremo.php?token=$token' target='_blank'>Reclamación baremo </a>";
            if($_SESSION['estado_convocatoria']>=ESTADO_RECLAMACIONES_PROVISIONAL)
               echo '<a id="reclamacion_listaprovisional" class="reclamacion dropdown-item" href="reclamaciones_provisional.php" target="_blank">Reclamación listado provisional </a>';
         echo '</div>';
      echo '</li>';
      }
      if($_SESSION['rol']!='alumno' and $_SESSION['estado_convocatoria']>=0)
      {
      echo '<li class="nav-item msuperior dropdown itemderecho">';
         echo '<a class="show_provisionales nav-link dropdown-toggle desplegable" id="navbardrop" data-toggle="dropdown" href="#">Exportar datos</a>';
         echo '<div class="dropdown-menu">';
            echo '<a class="exportpdf dropdown-item" href="#" id="pdf_usu" data-tipo="pdf" data-subtipo="pdf_usu">Listado usuarios (pdf)  </a>';
               if($_SESSION['rol']=='admin' or $rol=='sp' or $rol=='centro')
               { 
                  echo '<a class="exportcsv dropdown-item" href="#" id="csv_mat" data-tipo="csv" data-subtipo="csv_mat">Listado vacantes (csv)  </a>';
                  echo '<a class="exportcsv dropdown-item" href="#" id="csv_mat_final" data-tipo="csv" data-subtipo="csv_mat_final">Listado matrícula final (csv)  </a>';
               }
               echo '<a class="exportcsv dropdown-item" href="#" id="csv_sol"
                     data-tipo="csv" data-subtipo="csv_sol">Listado solicitudes (csv)</a>';
               echo '<a class="exportcsv dropdown-item" href="#" id="csv_pro"
                     data-tipo="csv" data-subtipo="csv_pro">Listado alumnos promocionan (csv)</a>';
            
               if($_SESSION['rol']=='admin' or $rol=='sp')
               { 
                  echo '<a class="exportcsv dropdown-item" href="#" id="csv_dup" data-tipo="csv" data-subtipo="csv_dup">Listado duplicados (csv) </a>';
                  if($_SESSION['estado_convocatoria']>=30)
                  {
                     echo '<a class="exportcsv dropdown-item" href="#" id="csv_dup" data-tipo="csv" data-subtipo="csv_fase2">Listado Fase 2 (csv) </a>';
                     echo '<a class="exportcsv dropdown-item" href="#" id="csv_dup" data-tipo="csv" data-subtipo="csv_fase3">Listado Fase 3 (csv) </a>';
                  }
               }
         echo '</div>';
      echo '</li>';
      echo '<li class="nav-item active msuperior itemderecho">';
         echo '<a class="show_matricula nav-link" href="#">Matricula</a>';
      echo '</li>';
      echo '<li class="nav-item active msuperior itemderecho">';
         echo '<a class="show_solicitudes nav-link" href="#">Solicitudes</a>';
      echo '</li>';
      }
      if($_SESSION['estado_convocatoria']>=ESTADO_FININSCRIPCION or ($rol!='alumno' and $rol!='anonimo'))
      {
   ?>
         <li class="nav-item active msuperior dropdown itemderecho" id="msorteo">
         <?php if(($_SESSION['estado_convocatoria']>=ESTADO_FININSCRIPCION AND $rol!='alumno') OR ($_SESSION['rol']=='alumno' AND $_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_BAREMADAS)){?>
             <a class="show_provisionales nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Lista baremo</a>
             <div class="dropdown-menu">

            <?php if(($_SESSION['estado_convocatoria']>=ESTADO_PREALEATORIO AND $_SESSION['rol']!='alumno' AND $_SESSION['rol']!='anonimo') OR ($_SESSION['estado_convocatoria']>=ESTADO_ALEATORIO )){?>
               <a class="lbaremadas dropdown-item" href="#" id="sor_ale" data-subtipo="sor_ale" data-tipo="sorteo">Numero aleatorio </a>;
             <?php }?>
             
             <?php if($_SESSION['rol']!='alumno' OR ($_SESSION['rol']=='alumno' AND $_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_BAREMADAS)){?>
               <a class="lbaremadas dropdown-item" href="#" data-tipo="sorteo" data-subtipo="sor_bar">Solicitudes baremadas</a>
             <?php }?>
             <?php if($_SESSION['rol']!='alumno'){?>
               <a class="lbaremadas dropdown-item" href="#" data-tipo="sorteo" data-subtipo="sor_det">Detalle baremo</a>
             <?php }?>
             </div>
         <?php }?>
         </li>
      <?php }?>
      
		<?php if(($_SESSION['estado_convocatoria']>=ESTADO_SORTEO AND $_SESSION['rol']!='alumno') OR $_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_PROVISIONAL) {?>
            <li class="nav-item active msuperior dropdown itemderecho" id="mprovisional">
               <a class="show_provisionales nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Provisional</a>
               <div class="dropdown-menu">
                  <a class="lprovisionales dropdown-item" href="#" data-subtipo="admitidos_prov">Admitidos provisional</a>
                  <a class="lprovisionales dropdown-item" href="#" data-subtipo="noadmitidos_prov">No admitidos provisional</a>
                  <a class="lprovisionales dropdown-item" href="#" data-subtipo="excluidos_prov">Excluidos provisional</a>
               </div>
            </li>
			<?php }?>
		<?php if($_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_PROVISIONAL){?>
		   <?php if(($_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_DEFINITIVOS and $_SESSION['rol']=='alumno') or ($_SESSION['rol']!='alumno') and $_SESSION['rol']!='anonimo'){?>
                            <li class="nav-item active msuperior dropdown itemderecho" id="mdefinitivo">
                                 <a class="show_definitivos nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Definitivos</a>
		                 <div class="dropdown-menu">
				 <a class="ldefinitivos dropdown-item" href="#" data-subtipo="admitidos_def">Admitidos definitivo</a>
				 <a class="ldefinitivos dropdown-item" href="#" data-subtipo="noadmitidos_def">No admitidos definitivo</a>
				 <a class="ldefinitivos dropdown-item" href="#" data-subtipo="excluidos_def">Excluidos definitivo</a>
				 </div>
                            </li>
		   <?php }?>
		<?php }?>
		<?php 
            if((($_SESSION['rol']=='admin' or $_SESSION['rol']=='sp') and ($_SESSION['estado_convocatoria']>=ESTADO_ASIGNACIONES)) OR ($_SESSION['rol']=='centro' and $_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_ASIGNACIONES)) 
            {
                echo '<li class="nav-item active msuperior dropdown itemderecho" id="mdefinitivo">';
                echo '<a class="nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Adjudicación Servicio Provincial</a>';
                echo '<div class="dropdown-menu">';
                if(($_SESSION['rol']=='admin' or $_SESSION['rol']=='sp'))
                {
                  echo '<a class="lfase2 dropdown-item" href="#" data-subtipo="lfase2_sol_ebo">Listado Solicitudes fase2 EBO</a>';
                  echo '<a class="lfase2 dropdown-item" href="#" data-subtipo="lfase2_sol_tva">Listado Solicitudes fase2 TVA</a>';
                }
                echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_ebo_adjudicadas">Listado Solicitudes adjudicadas EBO</a>';
                echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_tva_adjudicadas">Listado Solicitudes adjudicadas TVA</a>';
                echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_ebo_desplazados">Listado Solicitudes desplazados fase2 EBO</a>';
                echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_tva_desplazados">Listado Solicitudes desplazados fase2 TVA</a>';
                echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_ebo_nomatricula">Listado Solicitudes no matrícula fase2 EBO</a>';
                echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_tva_nomatricula">Listado Solicitudes no matrícula fase2 TVA</a>';
                echo '</div>';
                echo '</li>';
                echo '<li class="nav-item active msuperior dropdown" id="mdefinitivo">';
                echo '</li>';
		      }
		      if($_SESSION['estado_convocatoria']>=ESTADO_FIN and $_SESSION['rol']!='alumno' and $_SESSION['rol']!='anonimo')
            {
               echo '<li class="nav-item active msuperior itemderecho" id="matriculafinal">';
                  echo '<a class="show_matricula_final nav-link" data-subtipo="mat_final" href="#">Matrícula final</a>';
               echo '</li>';
		      }
      }?>
        </ul>
</nav>
</div>
