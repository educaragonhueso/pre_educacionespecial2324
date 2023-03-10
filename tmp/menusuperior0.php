<?php 
if(isset($_SESSION['provincia'])) {$provincia=$_SESSION['provincia'];} else $provincia='todas';
$listado='disponible';
$directoriobase=$_SESSION['edicion'];
$ficheroebo='scripts/datossalida/pdflistados/sorteo/lfase2_sol_ebo_admin.pdf';
$token=$_SESSION['token'];
if($_SESSION['version']=='PRE')
   print_r($_SESSION);
if (!file_exists($ficheroebo))
   $listado='';
?>            
<h2 style='text-align:center;'>ADMISION ALUMNOS EDUCACION ESPECIAL CURSO <?php echo $_SESSION['curso_largo']; ?> </h2>
<p hidden id='id_centro'><?php echo $_SESSION['id_centro'];?></p> 
<p hidden id='estado_convocatoria' value='<?php echo $_SESSION['estado_convocatoria'];?>'></p> 
<p hidden id='rol' value='<?php echo $_SESSION['rol'];?>'></p> 
<p hidden id='id_alumno' value='<?php echo $_SESSION['id_alumno'];?>'></p> 
<p hidden id='numero_sorteo' value='<?php echo $_SESSION['numero_sorteo'];?>'></p> 
<p hidden id='provincia' value='<?php echo $_SESSION['provincia'];?>'></p> 
<p hidden id='id_alumnonuevo' value='-1'></p> 
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
<!--elementos a la izda-->
   <ul class="navbar-nav">
     <li class="nav-item  msuperior">
      <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'/';?>' target='_blank'>INICIO</a>
     </li>
     <li class="nav-item  msuperior">
      <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'/hitos.php';?>' target='_blank'>HITOS</a>
     </li>
     <li class="nav-item  msuperior">
      <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'/faq.php';?>' target='_blank'>FAQ</a>
     </li>
     <li class="nav-item  msuperior">
      <a style='color:white!important;float:left!important;padding-top:9px'  href='https://t.me/edespecial2223' target='_blank'>TELEGRAM</a>
     </li>
     <li class="nav-item  msuperior">
      <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'/login_out.php';?>'>SALIR</a>
     </li>
   </ul>
  </div>  
<!--elementos a la dcha-->
<?php    if($_SESSION['rol']!='anonimo'){?>
<nav id='navgir' class="navbar navbar-expand-md navbar-dark bg-dark">
        <ul class="navbar-nav mr-auto">
           <li class="nav-item  msuperior">
            <a style='color:white!important;float:left!important;padding-top:9px'  href='<?php echo $_SESSION['url_base'].'/'.$directoriobase.'';?>'>INICIO</a>
           </li>
           <li class="nav-item active msuperior dropdown">
             <a class="nav-link dropdown-toggle desplegable" id="navbardrop" data-toggle="dropdown">Documentaci??n</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="documentacion/a3a_domlaboralcajena_2223.pdf" id="doca4" target="_blank">Anexo IV A Certificado Domicilio Laborali Cuenta Ajena</a>
                <a class="dropdown-item" href="documentacion/a3a_domlaboralcpropia_2223.pdf" id="doca4" target="_blank">Anexo IV B Responsable Domicilio Laboral Cuenta Propia</a>
                <a class="dropdown-item" href="documentacion/a4_reaghermanos_2223.pdf" id="doca4" target="_blank">Anexo IV Solicitud Reagrupamiento de Hermanos</a>
                <a class="dropdown-item" href="documentacion/a1a_calendario_2223.pdf" target="_blank">Calendario Admisi??n Educaci??n Especial</a>;
                <?php if($_SESSION['rol']=='admin'){?>
                  <a class="dropdown-item" href="documentacion/doc_pruebas.html" id="ccen" target="_blank">Claves centros</a>;
                  <a class="dropdown-item" href="documentacion/csv_comprobaciones.csv" id="csvc" target="_blank">CSV Comprobaciones</a>;
               <?php } ?>
			    </div>
         </li>
       </ul>
  <?php }
?>
<?php 
   if($_SESSION['usuario_autenticado'])
   {

      if(($_SESSION['rol']=='alumno' and $_SESSION['estado_convocatoria']==ESTADO_RECLAMACIONES_BAREMADAS) or $_SESSION['rol']=='admin')
      {
      echo '<li class="nav-item msuperior dropdown">';
            if($_SESSION['estado_convocatoria']==ESTADO_RECLAMACIONES_BAREMADAS or $_SESSION['estado_convocatoria']==ESTADO_RECLAMACIONES_PROVISIONAL )
         echo '<a class="show_provisionales nav-link dropdown-toggle desplegable" id="navbardrop" data-toggle="dropdown" href="#">Formulario reclamaciones</a>';
         echo '<div class="dropdown-menu">';
            if($_SESSION['estado_convocatoria']==ESTADO_RECLAMACIONES_BAREMADAS)
               echo "<a id='reclamacion_baremo'  class='reclamacion dropdown-item' href='reclamaciones_baremo.php?token=$token' target='_blank'>Reclamaci??n baremo </a>";
            if($_SESSION['estado_convocatoria']==ESTADO_RECLAMACIONES_PROVISIONAL)
               echo '<a id="reclamacion_listaprovisional" class="reclamacion dropdown-item" href="reclamaciones_provisional.php" target="_blank">Reclamaci??n listado provisional </a>';
         echo '</div>';
      echo '</li>';
      }
      if($_SESSION['rol']!='alumno' and $_SESSION['estado_convocatoria']>=0)
      {
      echo '<li class="nav-item msuperior dropdown">';
         echo '<a class="show_provisionales nav-link dropdown-toggle desplegable" id="navbardrop" data-toggle="dropdown" href="#">Exportar datos</a>';
         echo '<div class="dropdown-menu">';
            echo '<a class="exportpdf dropdown-item" href="#" id="pdf_usu" data-tipo="pdf" data-subtipo="pdf_usu">Listado usuarios (pdf)  </a>';
               if($_SESSION['rol']=='admin' or $rol=='sp' or $rol=='centro')
               { 
                  echo '<a class="exportcsv dropdown-item" href="#" id="csv_mat" data-tipo="csv" data-subtipo="csv_mat">Listado vacantes (csv)  </a>';
                  echo '<a class="exportcsv dropdown-item" href="#" id="csv_mat_final" data-tipo="csv" data-subtipo="csv_mat_final">Listado matr??cula final (csv)  </a>';
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
      echo '<li class="nav-item active msuperior">';
         echo '<a class="show_matricula nav-link" href="#">Matricula</a>';
      echo '</li>';
      echo '<li class="nav-item active msuperior">';
         echo '<a class="show_solicitudes nav-link" href="#">Solicitudes</a>';
      echo '</li>';
      }
      if($_SESSION['estado_convocatoria']>=ESTADO_FININSCRIPCION or ($rol!='alumno' and $rol!='anonimo'))
      {
   ?>
         <li class="nav-item active msuperior dropdown" id="msorteo">
         <?php if($_SESSION['estado_convocatoria']>=ESTADO_FININSCRIPCION OR ($_SESSION['rol']=='alumno' AND $_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_BAREMADAS)){?>
             <a class="show_provisionales nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Lista baremo</a>
             <div class="dropdown-menu">

            <?php if($_SESSION['estado_convocatoria']>=ESTADO_PUBLICACION_PROVISIONAL AND $_SESSION['rol']!='alumno'){?>
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
      
		<?php if($_SESSION['estado_convocatoria']>=DIA_PUBLICACION_BAREMADAS AND $_SESSION['rol']!='alumno') {?>
            <li class="nav-item active msuperior dropdown" id="mprovisional">
               <a class="show_provisionales nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Provisional</a>
               <div class="dropdown-menu">
                  <a class="lprovisionales dropdown-item" href="#" data-subtipo="admitidos_prov">Admitidos provisional</a>
                  <a class="lprovisionales dropdown-item" href="#" data-subtipo="noadmitidos_prov">No admitidos provisional</a>
                  <a class="lprovisionales dropdown-item" href="#" data-subtipo="excluidos_prov">Excluidos provisional</a>
               </div>
            </li>
			<?php }?>
		<?php if(($_SESSION['estado_convocatoria']>=ESTADO_RECLAMACIONES_PROVISIONAL and $_SESSION['rol']!='alumno' and $_SESSION['rol']!='anonimo')){?>
		   <?php if(($_SESSION['estado_convocatoria']>=40 and $_SESSION['rol']=='alumno') or $_SESSION['rol']!='alumno'){?>
                            <li class="nav-item active msuperior dropdown" id="mdefinitivo">
                                 <a class="show_definitivos nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">Definitivos</a>
		                 <div class="dropdown-menu">
				 <a class="ldefinitivos dropdown-item" href="#" data-subtipo="admitidos_def">Admitidos definitivo</a>
				 <a class="ldefinitivos dropdown-item" href="#" data-subtipo="noadmitidos_def">No admitidos definitivo</a>
				 <a class="ldefinitivos dropdown-item" href="#" data-subtipo="excluidos_def">Excluidos definitivo</a>
				 </div>
                            </li>
		   <?php }?>
		<?php }?>
		<?php if(($_SESSION['rol']=='admin' or $_SESSION['rol']=='sp') and ($_SESSION['estado_convocatoria']>=ESTADO_ASIGNACION_AUTOMATICA)) 
            {
             echo '<li class="nav-item active msuperior dropdown" id="mdefinitivo">';
             echo '<a class="nav-link dropdown-toggle desplegable2" id="navbardrop" data-toggle="dropdown" href="#">FASE II</a>';
		       echo '<div class="dropdown-menu">';
               if($_SESSION['estado_convocatoria']<40)
               {
				      echo '<a class="lfase2 dropdown-item" href="documentacion/vacantes_especial_fase2.JPG">VACANTES FASE2</a>';
				      echo '<a class="lfase2 dropdown-item" href="#" data-subtipo="lfase2_sol_sor">Listado Numero aleatorio fase2</a>';
               }
               if($_SESSION['rol']!='alumno')
               {
				      echo '<a class="lfase2 dropdown-item" href="#" data-subtipo="lfase2_sol_ebo">Listado Solicitudes fase2 EBO</a>';
				      echo '<a class="lfase2 dropdown-item" href="#" data-subtipo="lfase2_sol_tva">Listado Solicitudes fase2 TVA</a>';
				   }
             echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_ebo">Listado Solicitudes finales EBO</a>';
				 echo '<a class="lfinales dropdown-item" href="#" data-subtipo="lfinal_sol_tva">Listado Solicitudes finales TVA</a>';
				 echo '</div>';
             echo '</li>';
             echo '<li class="nav-item active msuperior dropdown" id="mdefinitivo">';
             echo '</li>';
               
		      }
		      if($_SESSION['estado_convocatoria']>=100 and $_SESSION['rol']!='alumno' and $_SESSION['rol']!='anonimo')
            {
               echo '<li class="nav-item active msuperior" id="matriculafinal">';
                  echo '<a class="show_matricula_final nav-link" data-subtipo="mat_final" href="#">Matr??cula final</a>';
               echo '</li>';
		      }
      }?>
        </ul>
</nav>
