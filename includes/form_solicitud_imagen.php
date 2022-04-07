<?php
$formsol='<tr style="width:1300px"><td colspan="12" style="width:inherit"><div id="filasolicitud"  class="container" id="tablasolicitud">
<div class="row">
<div class="col-md-12 mb-md-0 mb-5">
<form lang="es" id="fsolicitud"  class="was-validated formsolicitud"  name="contact-form"  method="POST">';

if($rol!='alumno' and $rol!='anonimo')
{
$formsol.=
'<!--INICIO SECCION ESTADO-->
<p type="button" class="btn btn-primary bform" data-toggle="collapse" data-target="#estadosol">ESTADO<span> <i class="fas fa-angle-down"></i></span></p>
<div id="estadosol" class="collapse">
<!--INICIO FILA ESTADO-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form mb-0">
				<p>Fase solicitud</p>
				<div class="radio">
				<label><input type="radio" name="fase_solicitud" value="borrador">BORRADOR</label>
				</div>
				<div class="radio">
				<label><input type="radio" name="fase_solicitud" value="validada">VALIDADA</label>
				</div>
				<div class="radio">
				<label><input type="radio" name="fase_solicitud" value="baremada">BAREMADA</label>
				</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="md-form mb-0">
				<p>Estado solicitud</p>
				<div class="radio">
				<label><input type="radio" name="estado_solicitud" value="duplicada">DUPLICADA</label>
				</div>
				<div class="radio">
				<label><input type="radio" name="estado_solicitud" value="irregular">IRREGULAR</label>
				</div>
				<div class="radio">
				<label><input type="radio" name="estado_solicitud" value="apta">APTA</label>
				</div>
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
</div>
<!--FIN SECCION ESTADO-->';
}
$formsol.=
'<!--INICIO SECCION DATOS-->
<p type="button" class="btn btn-primary bform" data-toggle="collapse" data-target="#personales">DATOS PERSONALES<span> <i class="fas fa-angle-down"></i></span></p>
<div id="personales" class="collapse">
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
                        <div class="md-form mb-0">
			    Primer apellido*
                            <input type="text" id="apellido1" value="" name="apellido1" placeholder="Primer apellido"  class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
			    Segundo apellido
                            <input type="text" id="apellido2" value="" name="apellido2" placeholder="Segundo apellido"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
					Nombre*
                            <input type="text" id="nombre" value="" name="nombre" placeholder="Nombre"  class="form-control" required>
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
		<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                      	    DNI/NIE Alumno <i>(solo para mayores de 14 años)</i> 
                            <input type="text" id="dni_alumno" value="" name="dni_alumno" placeholder="DNI/NIE alumno" pattern="[a-zA-Z0-9]{9}" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                      	    Correo Electrónico
                            <input type="email" id="email" value="" name="email" placeholder="Correo alumno" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
      Fecha de nacimiento*
                            <input type="date" id="fnac" value="" name="fnac" placeholder="Fecha Nacimiento" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
			Nacionalidad
                        <div class="md-form mb-0" data-tip="This is the text of the tooltip2">
                            <input type="text" id="nacionalidad" value="" name="nacionalidad" placeholder="Nacionalidad" class="form-control" title="paises" data-toggle="tooltip">
                        </div>
                    </div>

                </div>
<!--FIN FILA DATOS-->
		<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-8">
                        <div class="md-form mb-0">
			    Nombre y apellidos madre/padre o tutor*
                            <input type="text" id="datos_tutor1" value="" name="datos_tutor1" placeholder="Nombre y apellidos madre/padre o tutor" class="form-control is-valid" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
			    NIF/NIE madre/padre o tutor/a*
                            <input type="text" id="dni_tutor1" value="" name="dni_tutor1" placeholder="NIF/NIE Tutor" pattern="[0-9a-zA-Z]{9}" class="form-control" required>
                        </div>
                    </div>

                </div>
<!--FIN FILA DATOS-->
		<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-8">
                        <div class="md-form mb-0">
			    Nombre y apellidos madre/padre o tutor
                            <input type="text" id="datos_tutor2" value="" name="datos_tutor2" placeholder="Nombre y apellidos madre/padre o tutor" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
			NIF/NIE madre/padre o tutor/a
			<input type="text" id="dni_tutor2" value="" name="dni_tutor2" placeholder="NIF/NIE tutor"   pattern="[0-9a-zA-Z]{9}"  class="form-control">
                        </div>
                    </div>

                </div>
		<br>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-5">
                        <div class="md-form mb-0">
			    Calle/Plaza/Avenida domicilio familiar*
                            <input type="text" id="calle_dfamiliar" value="" name="calle_dfamiliar" placeholder="Calle/Plaza/Avenida" class="form-control is-valid">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="md-form mb-0">
			Número*
			<input type="text" id="num_dfamiliar" value="" name="num_dfamiliar"  placeholder="Nº" pattern="[0-9]{1,3}"  class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="md-form mb-0">
			Piso/Casa*
			<input type="text" id="piso_dfamiliar" value="" name="piso_dfamiliar"  placeholder="Piso/Casa" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
			Codigo Postal*
			<input type="text" id="cp_dfamiliar" value="" name="cp_dfamiliar" placeholder="CP" pattern="[0-9]{5}"  class="form-control">
                        </div>
                    </div>

                </div>
		<br>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-2">
                        <div class="md-form mb-0">
			Localidad*
			<input type="text" id="loc_dfamiliar" value="" name="loc_dfamiliar" placeholder="Localidad"   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="md-form mb-0">
			Telefono habitual*
                        <input type="tel" id="tel_dfamiliar1" value="" name="tel_dfamiliar1" placeholder="Telefono 1" pattern="[0-9]{9}" class="form-control is-valid">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="md-form mb-0">
			Telefono adicional
			<input type="tel" id="tel_dfamiliar2" value="" name="tel_dfamiliar2"  placeholder="Telefono 2" pattern="[0-9]{9}" class="form-control">
                        </div>
                    </div>
                </div>
		<br>
<!--FIN FILA DATOS-->
</div>
<!--FIN SECCION DATOS-->
<!--INICIO SECCION DATOS: EXPONE-->
<p type="button" class="btn btn-primary bform" data-toggle="collapse" data-target="#expone">EXPONE<span> <i class="fas fa-angle-down"></i></span></p>
<div id="expone" class="collapse">
		<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
                            <input type="checkbox" class="nuevaesc" id="nuevaesc" value="0" name="nuevaesc" placeholder="" data-target="tnuevaesc" >
			Nueva escolarización
                    </div>
                </div>
<!--FIN FILA DATOS-->
		<br>
<!--INICIO FILA DATOS-->
                <div class="row filanuevaesc">
                    <div class="col-md-8">
                        <div class="md-form mb-0">
			Centro de estudios actual
                            <input type="text"  id="id_centro_estudios_origen" value="" name="id_centro_estudios_origen" placeholder="Centro estudios actual" class="form-control centro_estudios_origen" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
			Localidad
			     <input type="text" id="loc_centro_origen" value="" name="loc_centro_origen" placeholder="Localidad centro"  class="form-control" required>
                        </div>
                    </div>
			<i><b>* Centro de educación especial o con aulas de educación especial</b></i>
                </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
         <div class="row freserva" style="display:none">
         	<div class="col-md-4">
          	<div class="md-form mb-0">
							<div class="radio">
							<label><b>RESERVA</b></label>
							</div>
							<div class="radio">
								<label><input type="radio" name="reserva" value="1" data-reserva="1">RESERVA</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="reserva" value="0" data-reserva="0">NO RESERVA</label>
							</div>
						</div>
					</div>
				</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
   <div class="row filanuevaesc" id="tnuevaesc">
   	<div class="col-md-10">
    	<div class="md-form mb-0">
				<div class="form-group">
		Modalidad origen
					<select class="form-control" id="modalidad_origen" value="" name="modalidad_origen">
					    <option value="nodata">Selecciona modalidad de origen</option>

					    <option><b>MODALIDAD ESCOLARIZACION ORDINARIA</b></option>
					    <optgroup label="2º CICLO INFANTIL"></optgroup>
					    <option value="combinada-1infantil">1º INFANTIL</option>
					    <option value="combinada-2infantil">2º INFANTIL</option>
					    <option value="combinada-3infantil">3º INFANTIL</option>

					    <optgroup label="PRIMARIA"></optgroup>
					    <option value="combinada-1primaria">1º PRIMARIA</option>
					    <option value="combinada-2primaria">2º PRIMARIA</option>
					    <option value="combinada-3primaria">3º PRIMARIA</option>
					    <option value="combinada-4primaria">4º PRIMARIA</option>
					    <option value="combinada-5primaria">5º PRIMARIA</option>
					    <option value="combinada-6primaria">6º PRIMARIA</option>

					    <optgroup label="ESO"></optgroup>
					    <option value="combinada-1eso">1º ESO</option>
					    <option value="combinada-2eso">2º ESO</option>
					    <option value="combinada-3eso">3º ESO</option>
					    <option value="combinada-4eso">4º ESO</option>

					    <option><b>MODALIDAD: EDUCACION ESPECIAL</option>
					
					    <optgroup label="TIPO"></optgroup>
					    <option value="especial-1ebo">EBO</option>
					    <option value="especial-1tva">TVA</option>
					</select>
				</div>
      </div>
    </div>
   </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
                            <input type="checkbox" class="check_hadmision" id="hermanosadmision" value="0" name="num_hadmision" placeholder="" data-target="thermanosadmision" >Hermanos en el proceso de admisión
                    </div>
                </div>
<!--FIN FILA DATOS-->
<br>
<div id="thermanosadmision" style="display:none">
<!--INICIO FILA DATOS-->
               <div class="row">
                 <div class="col-md-4">
                        <div class="radio">
                           <label><b>INDIVIDUAL O CONJUNTA</b></label>
                        </div>
                        <div class="radio">
                           <label><input type="radio" name="conjunta" value="si">Individual</label>
                        </div>
                        <div class="radio">
                           <label><input type="radio" name="conjunta" value="no">Conjunta</label>
                        </div>
                 </div>
               </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_datos_admision1" value="" name="hermanos_datos_admision1" placeholder="Apellidos Nombre hermano" class="form-control">
                            <input type="hidden" id="hermanos_id_registro_admision1" value="0" name="hermanos_id_registro_admision1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                            <input type="date" id="hermanos_fnacimiento_admision1" value="" name="hermanos_fnacimiento_admision1" placeholder="Fecha Nacimiento" class="form-control" data-idhadmision="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_curso_admision1" value="" name="hermanos_curso_admision1" placeholder="Curso actual" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_nivel_educativo_admision1" value="" name="hermanos_nivel_educativo_admision1" placeholder="Nivel educativo" class="form-control" data-idhadmision="0">
                        </div>
                    </div>

                </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_datos_admision2" value="" name="hermanos_datos_admision2" placeholder="Apellidos Nombre hermano" class="form-control" >
                            <input type="hidden" id="hermanos_id_registro_admision2" value="0" name="hermanos_id_registro_admision2">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                            <input type="date" id="hermanos_fnacimiento_admision2" value="" name="hermanos_fnacimiento_admision2" placeholder="Fecha Nacimiento" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_curso_admision2" value="" name="hermanos_curso_admision2" placeholder="Curso actual" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_nivel_educativo_admision2" value="" name="hermanos_nivel_educativo_admision2" placeholder="Nivel educativo" class="form-control">
                        </div>
                    </div>

                </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_datos_admision3" value="" name="hermanos_datos_admision3" placeholder="Apellidos Nombre hermano" class="form-control" >
                            <input type="hidden" id="hermanos_id_registro_admision3" value="0" name="hermanos_id_registro_admision3">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                            <input type="date" id="hermanos_fnacimiento_admision3" value="" name="hermanos_fnacimiento_admision3" placeholder="Fecha Nacimiento" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_curso_admision3" value="" name="hermanos_curso_admision3" placeholder="Curso actual" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                            <input type="text" id="hermanos_nivel_educativo_admision3" value="" name="hermanos_nivel_educativo_admision3" placeholder="Nivel educativo" class="form-control" >
                        </div>
                    </div>

                </div>
<!--FIN FILA DATOS-->
</div>
</div>
<!--FIN SECCION EXPONE-->
<!--INICIO SECCION DATOS: SOLICITA-->
<p type="button" class="btn btn-primary bform" data-toggle="collapse" data-target="#solicita" aria-controls="solicita">SOLICITA<span> <i class="fas fa-angle-down"></i></span></p>
<div id="solicita" class="collapse">
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-8">
                        <div class="md-form mb-0">
			Centro Solicitado
                            <input type="text"  id="id_centro_destino" value="" name="id_centro_destino" placeholder="Centro estudios solicitado" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
			Localidad centro solicitado
			<input type="text" id="loc_centro_destino" value="" name="loc_centro_destino" placeholder="Localidad centro"  class="form-control" required>
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
		<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form mb-0">
				<div class="form-group">
					<select class="form-control" name="tipoestudios" id="tipoestudios" value="">
					    <option value="EBO">EBO <i>(Nacidos de 2004 a 2018 ambos inclusive)</i></option>
					    <option value="TVA">TVA <i>(Nacidos del 2003 al 2001)</i></option>
					</select>
				</div>
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
<div class="row">
<p>Datos Centros alternativos en orden de prioridad</p><br>
</div>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <input type="text" id="id_centro_destino1" value="" name="id_centro_destino1" placeholder="1 Nombre centro destino" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <input type="text" id="id_centro_destino4" value="" name="id_centro_destino4" placeholder="4 Nombre centro destino" class="form-control" >
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <input type="text" id="id_centro_destino2" value="" name="id_centro_destino2" placeholder="2 Nombre centro destino" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <input type="text" id="id_centro_destino5" value="" name="id_centro_destino5" placeholder="5 Nombre centro destino" class="form-control" >
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <input type="text" id="id_centro_destino3" value="" name="id_centro_destino3" placeholder="3 Nombre centro destino" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <input type="text" id="id_centro_destino6" value="" name="id_centro_destino6" placeholder="6 Nombre centro destino" class="form-control" >
                        </div>
                    </div>
                </div>
<!--FIN FILA DATOS-->
<br>
<!--INICIO FILA DATOS-->
</div>
<!--FIN SECCION SOLICITA-->

<!--INICIO SECCION DATOS: BAREMO-->
<p type="button" class="btn btn-primary bform crojo" id="labelbaremo" data-toggle="collapse" data-target="#baremo">BAREMO<span> <i class="fas fa-angle-down"></i></span>
<span>PUNTOS BAREMO TOTALES:<span id="id_puntos_baremo_totales">0</span> 
<span>PUNTOS BAREMO VALIDADOS:<span id="id_puntos_baremo_validados">0</span> 
</p>
	<input type="hidden" name="baremo_puntos_totales" value="0" id="btotales" class="bhiden">
	<input type="hidden" name="baremo_puntos_validados" value="0" id="bvalidados" class="bhiden">
<div id="baremo" class="collapse">
<!--INICIO FILA DATOS-->
	      <div class="row">
  	      <div class="col-md-5">
    	      <div class="md-form mb-0">
							<p>Proximidad domicilio a efectos de baremo:</p>
							<div class="radio">
							<label><input type="radio" name="baremo_proximidad_domicilio" value="dfamiliar" data-baremo="6" class="proxdomi">Domicilio familiar en zona de escolarización</label>
							</div>

							<div class="radio">
							<label><input type="radio" name="baremo_proximidad_domicilio" value="dlaboral" data-baremo="5" data-dom="laboral" class="proxdomi">Domicilio laboral en zona de escolarización</label>
							</div>
							<div id="calle_dlaboral" class="md-form mb-0" style="display:none">Calle/Plaza/Avenida domicilio laboral<input type="text" id="baremo_calle_dlaboral" name="baremo_calle_dlaboral" placeholder="Calle/Plaza/Avenida" class="form-control is-valid" required>
							</div>
	
							<div class="radio">
							<label><input type="radio" name="baremo_proximidad_domicilio" value="dflimitrofe" data-baremo="3" class="proxdomi">Domicilio familiar en zona limítrofe</label>
							</div>
							
							<div class="radio">
							<label><input type="radio" name="baremo_proximidad_domicilio" value="dllimitrofe" data-baremo="2" data-dom="limitrofe" class="proxdomi">Domicilio laboral en zona limítrofe</label>
							</div>
							<div id="calle_dllimitrofe" class="md-form mb-0" style="display:none">Calle/Plaza/Avenida domicilio laboral<input type="text" id="baremo_calle_dllimitrofe" name="baremo_calle_dllimitrofe" placeholder="Calle/Plaza/Avenida" class="form-control is-valid" required>
							</div>
							
							<div class="radio">
							<label><input type="radio" name="baremo_proximidad_domicilio" value="sindomicilio" data-baremo="0" class="proxdomi">Sin domicilio</label>
							</div>
							<input type="hidden" id="baremo_validar_proximidad_domicilio" value="0" name="baremo_validar_proximidad_domicilio">
							<button name="boton_baremo_validar_proximidad_domicilio" type="button" class="btn btn-outline-dark validar">Validar domicilio</button>

						</div>
          </div>
         	<div class="col-md-4">
            <div class="md-form mb-0">
            	<input type="checkbox" id="baremo_tutores_centro" value="0" name="baremo_tutores_centro" data-baremo="4" >
      				<label  for="hermanos_baremo">Padre/madre/tutor trabaja en el centro</label>
            </div>
							<input type="hidden" id="baremo_validar_tutores_centro" value="0" name="baremo_validar_tutores_centro">
							<button name="boton_baremo_validar_tutores_centro" type="button" class="btn btn-outline-dark">Validar tutores trabajan centro</button>
          </div>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="checkbox" id="baremo_renta_inferior" value="0" name="baremo_renta_inferior" data-baremo="1" >
      				<label for="baremo_renta_inferior"> Renta inferior IMV (Ingreso Mínimo vital)</label>
            </div>
						<input type="hidden" id="baremo_validar_renta_inferior" value="0" name="baremo_validar_renta_inferior">
						<button name="boton_baremo_validar_renta_inferior" type="button" class="btn btn-outline-dark">Validar renta</button>
          </div>
        </div>
<!--FIN FILA DATOS-->
<hr>
<!--INICIO FILA DATOS NUEVOS-->
	      <div class="row">
        	 <div class="col-md-4">
            <div class="md-form mb-0">
            	<input type="checkbox" id="baremo_acogimiento" value="0" name="baremo_acogimiento" data-baremo="1" >
      				<label>Situación de acogimiento</label>
            </div>
							<input type="hidden" id="baremo_validar_acogimiento" value="0" name="baremo_validar_acogimiento">
							<button name="boton_baremo_validar_acogimiento" type="button" class="btn btn-outline-dark">Validar situación de acogimiento</button>
          </div>
        	 <div class="col-md-4">
            <div class="md-form mb-0">
            	   <input type="checkbox" id="baremo_genero" value="0" name="baremo_genero" data-baremo="1" >
      			   <label>Víctima de género</label>
            </div>
							<input type="hidden" id="baremo_validar_genero" value="0" name="baremo_validar_genero">
							<button name="boton_baremo_validar_genero" type="button" class="btn btn-outline-dark">Validar víctima de género</button>
          </div>
          <div class="col-md-4">
          	<div class="md-form mb-0">
            	   <input type="checkbox" id="baremo_terrorismo" value="0" name="baremo_terrorismo" data-baremo="1" >
      				<label> Víctima de terrorismo</label>
            </div>
						<input type="hidden" id="baremo_validar_terrorismo" value="0" name="baremo_validar_terrorismo">
						<button name="boton_baremo_validar_terrorismo" type="button" class="btn btn-outline-dark">Validar víctima de terrorismo</button>
          </div>
        </div>
<!--FIN FILA DATOS-->
<hr>
<!--INICIO FILA DATOS-->
        <div class="row">
        	<div class="col-md-12">
          	<div class="md-form mb-0">
            	<input type="checkbox" id="num_hbaremo" value="0" name="num_hbaremo" class="num_hbaremo" >
      				<label for="hermanos_baremo">Tiene matriculados a los siguientes hermanos:</label>
            </div>
          </div>
       	</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
        <div class="row hno_baremo" style="display:none">
        	<div class="col-md-4">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_datos_baremo1" value="" name="hermanos_datos_baremo1" data-baremo="8" placeholder="Apellidos Nombre hermano" class="form-control" >
              <input type="hidden" id="hermanos_id_registro_baremo1" value="" name="hermanos_id_registro_baremo1">
            </div>
          </div>
					<br>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="date" id="hermanos_fnacimiento_baremo1" value="" name="hermanos_fnacimiento_baremo1" placeholder="Fecha Nacimiento" class="form-control">
            </div>
          </div>
					<br>
          <div class="col-md-2">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_curso_baremo1" value="" name="hermanos_curso_baremo1" placeholder="Curso actual" class="form-control">
            </div>
          </div>
					<br>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_nivel_educativo_baremo1" value="" name="hermanos_nivel_educativo_baremo1" placeholder="Nivel educativo" class="form-control">
            </div>
          </div>
					<br>
        </div>
<!--FIN FILA DATOS-->
<br>
<!--INICIO FILA DATOS-->
        <div class="row hno_baremo" style="display:none">
        	<div class="col-md-4">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_datos_baremo2" value="" name="hermanos_datos_baremo2"  data-baremo="1" placeholder="Apellidos Nombre hermano" class="form-control" >
              <input type="hidden" id="hermanos_id_registro_baremo2" value="" name="hermanos_id_registro_baremo2">
            </div>
          </div>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="date" id="hermanos_fnacimiento_baremo2" value="" name="hermanos_fnacimiento_baremo2" placeholder="Fecha Nacimiento" class="form-control">
            </div>
          </div>
          <div class="col-md-2">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_curso_baremo2" value="" name="hermanos_curso_baremo2" placeholder="Curso actual" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_nivel_educativo_baremo2" value="" name="hermanos_nivel_educativo_baremo2" placeholder="Nivel educativo" class="form-control">
            </div>
          </div>
        </div>
<!--FIN FILA DATOS-->
<br>
<!--INICIO FILA DATOS-->
        <div class="row hno_baremo" style="display:none">
        	<div class="col-md-4">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_datos_baremo3" value="" name="hermanos_datos_baremo3"  data-baremo="1" placeholder="Apellidos Nombre hermano" class="form-control" >
              <input type="hidden" id="hermanos_id_registro_baremo3" value="" name="hermanos_id_registro_baremo3">
            </div>
          </div>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="date" id="hermanos_fnacimiento_baremo3" value="" name="hermanos_fnacimiento_baremo3" placeholder="Fecha Nacimiento" class="form-control">
            </div>
          </div>
          <div class="col-md-2">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_curso_baremo3" value="" name="hermanos_curso_baremo3" placeholder="Curso actual" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
          	<div class="md-form mb-0">
            	<input type="text" id="hermanos_nivel_educativo_baremo3" value="" name="hermanos_nivel_educativo_baremo3" placeholder="Nivel educativo" class="form-control">
            </div>
          </div>
        </div>
<!--FIN FILA DATOS-->
<hr>
<br>
<!--INICIO FILA DATOS-->
                <div class="row hno_baremo" style="display:none">
                    <div class="col-md-4">
											<input type="hidden" id="baremo_validar_hnos_centro" value="0" name="baremo_validar_hnos_centro">
											<button name="boton_baremo_validar_hnos_centro" type="button" class="btn btn-outline-dark">Validar hermanos</button>
                    </div>

                </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
         <div class="row">
           <div class="col-md-4">
               <div class="md-form mb-0">
                  <div class="radio">
                     <label><b>DISCAPACIDAD</b></label>
                  </div>
                  <div class="radio">
                     <label><input type="radio" name="baremo_discapacidad" value="alumno" data-baremo="1">Del alumno</label>
                  </div>
                  <div class="radio">
                     <label><input type="radio" name="baremo_discapacidad" value="hpadres" data-baremo="0.75">De padres/hermanos</label>
                  </div>
                  <div class="radio">
                     <label><input type="radio" name="baremo_discapacidad" value="no" data-baremo="0">Ninguna</label>
                  </div>
			   </div>
			</div>

          <div class="col-md-4">
						<div class="md-form mb-0">
							<div class="radio">
								<label><b> FAMILIA NO NUMEROSA</b></label>
							</div>
							<div class="radio">
								<label><input type="radio" name="baremo_tipo_familia" value="no" data-baremo="0">No numerosa</label>
							</div>
							<div class="radio">
								<label><b> FAMILIA NUMEROSA</b></label>
							</div>
							<div class="radio">
								<label><input type="radio" name="baremo_tipo_familia" value="numerosa_general" data-baremo="1">General</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="baremo_tipo_familia" value="numerosa_especial" data-baremo="2">Especial</label>
							</div>
							<div class="radio">
								<label><b> FAMILIA MONOPARENTAL</b></label>
							</div>
							<div class="radio">
								<label><input type="radio" name="baremo_tipo_familia" value="monoparental_general" data-baremo="1">General</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="baremo_tipo_familia" value="monoparental_especial" data-baremo="2">Especial</label>
							</div>
						</div>
					</div>';
          
if($rol!='alumno' and $rol!='anonimo')
{
$formsol.='
					<div class="col-md-4">
						<div class="md-form mb-0">
							<div class="radio">

								<label><b>CRITERIOS DE PRIORIDAD</b></label>
							</div>
						</div>
							<div class="radio">
								<label><input type="radio" name="transporte" value="3">Con Ruta de Transporte</label>
							</div>
								<label><i style="font-size:14px">Nueva escolarización de localidades con ruta de transporte (art39) Decreto 30/2016 de 22 mayo</i></label>
							<div class="radio">
								<label><input type="radio" name="transporte" value="2">Nueva Escolarización</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="transporte" value="1">Cambio de centro</label>
							</div>
					</div>';
}
$formsol.='
</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
        <div class="row">
        	<div class="col-md-4">
          	<div class="md-form mb-0">
							<input type="hidden" id="baremo_validar_discapacidad" value="0" name="baremo_validar_discapacidad">
							<button name="boton_baremo_validar_discapacidad" type="button" class="btn btn-outline-dark validar">Validar discapacidad</button>
            </div>
          </div>
          <div class="col-md-4">
          	<div class="md-form mb-0">
							<input type="hidden" id="baremo_validar_tipo_familia" value="0" name="baremo_validar_tipo_familia">
							<button name="boton_baremo_validar_tipo_familia" type="button" class="btn btn-outline-dark validar">Validar familia</button>
						</div>
					</div>
        </div>
<!--FIN FILA DATOS-->
</div>
<!--FIN SECCION BAREMO-->
<!--INICIO SECCION DATOS: CARGA DOCUMENTOS-->
<p type="button" class="btn btn-primary bform" id="labeldocumentos" style="" data-toggle="collapse" data-target="#cdocumentos" >CARGA DOCUMENTOS<span> <i class="fas fa-angle-down"></i></span></p>
<div id="cdocumentos" class="collapse">
<!--INICIO FILA DATOS-->
   <div class="row">
      <div id="drop-area">
            <form id="idformfiledata" class="my-form">
               <p>Añade los documentos necesarios</p>
               <input type="file" id="fileElem" name="files[]" multiple accept="image/*">
               <label class="button" for="fileElem">Selecciona documentos (Haz click para deshacer o eliminar)</label>
            <input type="button" id="submit" value="Upload">
            </form>
<progress id="progress-bar" max=100 value=0></progress>
            <div id="gallery"><img src="';
include_once('pruebas/imgb64.php');
$formsol.=$imagen.'"> </div>
         </div>
   </div>
<!--FIN FILA DATOS-->
<br>
</div>
</div>
<!--FIN SECCION CARGA DOCUMENTACION-->

			<br>
	    <div class="text-center text-md-left">
                <a class="btn btn-primary send" >GRABAR SOLICITUD</a>
            </div>
            <div class="status"></div>
</form> 
</div>
</div></td></tr>';    

