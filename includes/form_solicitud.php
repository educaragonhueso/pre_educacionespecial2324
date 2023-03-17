<?php
$formsol='<tr id="filanuevasolicitud"><td colspan="12" style="width:inherit"><div id="filasolicitud"  class="container" id="tablasolicitud">
<div class="row">
<div class="col-md-12 mb-md-0 mb-5">
<form lang="es" id="fsolicitud"  class="was-validated formsolicitud"  name="contact-form"  method="POST" readonly>';

if($rol!='alumno' and $rol!='anonimo')
{
if($rol=='centro')
   $disabled='disabled';
else $disabled='';
$formsol.=
'<!--INICIO SECCION ESTADO-->
<div id="cajaestado">
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
				<label><input type="radio" name="estado_solicitud" value="duplicada"'.$disabled.'>DUPLICADA</label>
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
                           <div id="caja_fbaremo_identidad">            
                              <input value="ficheroidentidad" type="file" name="baremo_file_identidad" campobaremo="identidad" id="fbaremo_identidad" style="display:none" class="fbaremo">
                              <label for="fbaremo_identidad" id="fidentidad" class="botonform">Agrega fichero justificativo identidad</label>
                           </div>
                           <a id="enlacefjpruebas" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
                           <a id="borraridentidad" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
                    </div>
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                      	  Correo Electrónico (para recibir confirmación)
                            <input type="email" id="email" value="" name="email" placeholder="Correo alumno" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="md-form mb-0">
                           Fecha de nacimiento*
                            <input type="date" id="fnac" value="" placeholder="31/11/2005" name="fnac" placeholder="Fecha Nacimiento" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
<!--FIN FILA DATOS-->
                      <div class="row">
                          <div class="col-md-2">
                              <div class="md-form mb-0">
                                  <button name="boton_comprobar_identidad" type="button" class="btn btn-outline-dark comprobar" tipo="identidad">Comprobar identidad</button>
                              </div>
                          </div>
                      </div>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-4">
			               Nacionalidad
                        <div class="md-form mb-0" data-tip="Nacionalidad de origen">
                            <input type="text" id="nacionalidad" value="" name="nacionalidad" placeholder="Nacionalidad" class="form-control" title="paises" data-toggle="tooltip">
                        </div>
                    </div>
                    <div class="col-md-4">
			               Municipio
                        <div class="md-form mb-0" data-tip="">
                            <input type="text" id="municipionac" value="" name="municipionac" placeholder="Lugar de nacimiento (Municipio)" class="form-control" title="municipionac" data-toggle="tooltip">
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
                            <input type="text" id="calle_dfamiliar" value="" name="calle_dfamiliar" placeholder="Calle/Plaza/Avenida" class="form-control is-valid" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="md-form mb-0">
			Número*
			<input type="text" id="num_dfamiliar" value="" name="num_dfamiliar"  placeholder="Nº" pattern="[0-9]{1,3}"  class="form-control" required>
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
			<input type="text" id="cp_dfamiliar" value="" name="cp_dfamiliar" placeholder="CP" pattern="[0-9]{5}"  class="form-control" required>
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
			<input type="text" id="loc_dfamiliar" value="" name="loc_dfamiliar" placeholder="Localidad" class="form-control" required>
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
   <div class="md-form mb-0">
      <div class="radio">
         <label><input id="nuevaesc" type="radio" name="nuevaesc" value="1" data-reserva="1" class="nuevaesc">El alumno no está escolarizado en ningún centro en el curso '.$_SESSION["cursoanterior_largo"].' </label>
      </div>
      <div class="radio" data-target="tnuevaesc" >
         <label><input id="renesc" type="radio" name="nuevaesc" value="2" data-reserva="0">Alumno escolarizado curso '.$_SESSION["cursoanterior_largo"].'</label>
      </div>
   </div>
 </div>
<!--FIN FILA DATOS-->
		<br>
<div class="filarenesc" style="display:none">
<!--INICIO FILA DATOS-->
<div class="row filanuevaesc">
   <div class="col-md-12">
      <div class="md-form mb-0">
         Centro de estudios actual
         <input type="text"  id="id_centro_estudios_origen" value="" name="id_centro_estudios_origen" placeholder="Centro estudios actual" class="form-control centro_estudios_origen" required>
      </div>
   </div>
   <i><b>Si el alumno está escolarizado en un centro ordinario con aula de Educación Especial, elegirá el centro con *</b></i>
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
               <option value="especial-1ebo">INFANTIL-EBO</option>
               <option value="especial-1tva">TVA</option>
					<option value="especial-ldos">DOS AÑOS <i>Nacidos en 2020</i></option>
            </select>
         </div>
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
<!--INICIO FILA DATOS INDIVIDUAL O CONJUNTA-->
<div class="row">
  <div class="col-md-4">
         <div class="radio">
            <label><b>INDIVIDUAL O CONJUNTA</b></label>
         </div>
         <div class="radio">
            <label><input id="individual" type="radio" name="conjunta" value="no" checked="checked">Individual</label>
         </div>
         <div class="radio">
            <label><input id="conjunta" type="radio" name="conjunta" value="si">Conjunta</label>
         </div>
  </div>
</div>
<!--FIN FILA DATOS-->

<!--INICIO BLOQUE FILAS DATOS HERMANOS ADMISION CONJUNTA-->
<div class="bloque_hermanos_admision" style="display:none">

   <!--INICIO FILA DATOS HERMANOS ADMISION-->
   <div class="row">
     <div class="col-md-12">
      <p><b>Datos Hermano 1</b></p>
     </div>
   </div>
   <hr style="background-color:grey!important;height:1px;margin-left:35px">
   <!--FIN FILA DATOS HERMANOS ADMISION-->
   <!--INICIO FILA DATOS HERMANOS ADMISION-->
   <div class="row">
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_nombre1" value="" name="hermanos_admision_nombre1" placeholder="Nombre hermano" class="form-control">
             <input type="hidden" id="hermanos_admision_token1" value="0" name="hermanos_admision_token1" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_apellido11" value="" name="hermanos_admision_apellido11" placeholder="Primer Apellido hermano" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_apellido21" value="" name="hermanos_admision_apellido21" placeholder="Segundo Apellido hermano" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="date" id="hermanos_admision_fnac1" value="" name="hermanos_admision_fnac1" placeholder="Fecha Nacimiento" class="form-control" data-idhadmision="0">
         </div>
     </div>
   </div>
   <div class="row">
     <div class="col-md-3">
         <div class="md-form mb-0">
            <div class="form-group">
               Enseñanza solicitada del primer hermano
               <select class="form-control" id="hermanos_admision_tipoestudios1" value="" name="hermanos_admision_tipoestudios1">
					    <option class="hamo1" value="ebo">INFANTIL-EBO <i>(Nacidos de '.$_SESSION["ainicio_ebo"].' a '.$_SESSION["afin_ebo"].' ambos inclusive)</i></option>
					    <option class="hamo1" value="tva">TVA <i>(Nacidos de '.$_SESSION["ainicio_tva"].' a '.$_SESSION["afin_tva"].' ambos inclusive)</i></option>
               </select>
            </div>
         </div>
      </div>
     <div class="col-md-2">
         <div class="md-form mb-0">
            <div class="form-group">
               Solicita reserva de plaza
               <select class="form-control" id="hermanos_admision_reserva1" value="" name="hermanos_admision_reserva1">
					    <option class="resh1" value="0">NO SOLICITA RESERVA</option>
					    <option class="resh1" value="1">SÍ SOLICITA RESERVA</i></option>
               </select>
            </div>
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             DNI/NIE Alumno <i>(solo si es mayor de 14 años)</i> 
             <input type="text" id="hermanos_admision_dni_alumno1" value="" name="hermanos_admision_dni_alumno1" placeholder="DNI/NIE hermano" pattern="[a-zA-Z0-9]{9}" class="form-control" >
         </div>
      </div>
     <div class="col-md-4">
         <div class="md-form mb-0">
             Centro origen hermano 1
             <input type="text" id="hermanos_admision_id_centro_estudios_origen1" value="" name="hermanos_admision_id_centro_estudios_origen1" placeholder="Centro solicitado (por defecto igual que su hermano)" class="form-control" >
         </div>
      </div>
   </div>
   <!--FIN FILA DATOS HERMANOS ADMISION-->
   <!--INICIO FILA DATOS HERMANOS ADMISION-->
   <div class="row">
     <div class="col-md-12">
      <p><b>Datos Hermano 2</b></p>
     </div>
   </div>
   <hr style="background-color:grey!important;height:1px;margin-left:35px">
   <!--FIN FILA DATOS HERMANOS ADMISION-->
   <!--INICIO FILA DATOS HERMANOS ADMISION-->
   <div class="row">
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_nombre2" value="" name="hermanos_admision_nombre2" placeholder="Nombre hermano" class="form-control">
             <input type="hidden" id="hermanos_admision_token2" value="0" name="hermanos_admision_token2" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_apellido12" value="" name="hermanos_admision_apellido12" placeholder="Primer Apellido hermano" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_apellido22" value="" name="hermanos_admision_apellido22" placeholder="Segundo Apellido hermano" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="date" id="hermanos_admision_fnac2" value="" name="hermanos_admision_fnac2" placeholder="Fecha Nacimiento" class="form-control" data-idhadmision="0">
         </div>
     </div>
   </div>
   <div class="row">
     <div class="col-md-3">
         <div class="md-form mb-0">
            <div class="form-group">
               Enseñanza solicitada del segundo hermano
               <select class="form-control" id="hermanos_admision_tipoestudios2" value="" name="hermanos_admision_tipoestudios2">
					    <option class="hamo2" value="ebo">INFANTIL-EBO <i>(Nacidos de '.$_SESSION["ainicio_ebo"].' a '.$_SESSION["afin_ebo"].' ambos inclusive)</i></option>
					    <option class="hamo2" value="tva">TVA <i>(Nacidos de '.$_SESSION["ainicio_tva"].' a '.$_SESSION["afin_tva"].' ambos inclusive)</i></option>
               </select>
            </div>
         </div>
     </div>
     <div class="col-md-2">
         <div class="md-form mb-0">
            <div class="form-group">
               Solicita reserva de plaza
               <select class="form-control" id="hermanos_admision_reserva2" value="" name="hermanos_admision_reserva2">
					    <option class="resh2" value="0">NO SOLICITA RESERVA</option>
					    <option class="resh2" value="1">SÍ SOLICITA RESERVA</i></option>
               </select>
            </div>
         </div>
      </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             DNI/NIE Alumno <i>(solo si es mayor de 14 años)</i> 
             <input type="text" id="hermanos_admision_dni_alumno2" value="" name="hermanos_admision_dni_alumno2" placeholder="DNI/NIE alumno" pattern="[a-zA-Z0-9]{9}" class="form-control" >
         </div>
      </div>
     <div class="col-md-4">
         <div class="md-form mb-0">
             Centro origen hermano 2
             <input type="text" id="hermanos_admision_id_centro_estudios_origen2" value="" name="hermanos_admision_id_centro_estudios_origen2" placeholder="Centro solicitado (por defecto igual que su hermano)" class="form-control" >
         </div>
      </div>
   </div>
   <!--FIN FILA DATOS HERMANOS ADMISION-->
   <!--INICIO FILA DATOS HERMANOS ADMISION-->
   <!--INICIO FILA DATOS HERMANOS ADMISION-->
   <div class="row">
     <div class="col-md-12">
      <p><b>Datos Hermano 3</b></p>
     </div>
   </div>
   <hr style="background-color:grey!important;height:1px;margin-left:35px">
   <!--FIN FILA DATOS HERMANOS ADMISION-->
   <div class="row">
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_nombre3" value="" name="hermanos_admision_nombre3" placeholder="Nombre hermano" class="form-control">
             <input type="hidden" id="hermanos_admision_token3" value="0" name="hermanos_admision_token3" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_apellido13" value="" name="hermanos_admision_apellido13" placeholder="Primer Apellido hermano" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="text" id="hermanos_admision_apellido23" value="" name="hermanos_admision_apellido23" placeholder="Segundo Apellido hermano" class="form-control">
         </div>
     </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             <input type="date" id="hermanos_admision_fnac3" value="" name="hermanos_admision_fnac3" placeholder="Fecha Nacimiento" class="form-control" data-idhadmision="0">
         </div>
     </div>
   </div>
   <div class="row">
     <div class="col-md-3">
         <div class="md-form mb-0">
            <div class="form-group">
               Enseñanza solicitada del segundo hermano
               <select class="form-control" id="hermanos_admision_tipoestudios3" value="" name="hermanos_admision_tipoestudios3">
					    <option class="hamo3" value="ebo">INFANTIL-EBO <i>(Nacidos de '.$_SESSION["ainicio_ebo"].' a '.$_SESSION["afin_ebo"].' ambos inclusive)</i></option>
					    <option class="hamo3" value="tva">TVA <i>(Nacidos de '.$_SESSION["ainicio_tva"].' a '.$_SESSION["afin_tva"].' ambos inclusive)</i></option>
               </select>
            </div>
         </div>
     </div>
     <div class="col-md-2">
         <div class="md-form mb-0">
            <div class="form-group">
               Solicita reserva de plaza
               <select class="form-control" id="hermanos_admision_reserva3" value="" name="hermanos_admision_reserva3">
					    <option class="resh3" value="0">NO SOLICITA RESERVA</option>
					    <option class="resh3" value="1">SÍ SOLICITA RESERVA</i></option>
               </select>
            </div>
         </div>
      </div>
     <div class="col-md-3">
         <div class="md-form mb-0">
             DNI/NIE Alumno <i>(solo si es mayor de 14 años)</i> 
             <input type="text" id="hermanos_admision_dni_alumno3" value="" name="hermanos_admision_dni_alumno3" placeholder="DNI/NIE alumno" pattern="[a-zA-Z0-9]{9}" class="form-control" >
         </div>
      </div>
     <div class="col-md-4">
         <div class="md-form mb-0">
             Centro origen hermano 3
             <input type="text" id="hermanos_admision_id_centro_estudios_origen3" value="" name="hermanos_admision_id_centro_estudios_origen3" placeholder="Centro solicitado (por defecto igual que su hermano)" class="form-control" >
         </div>
      </div>
   </div>
   <!--FIN FILA DATOS HERMANOS ADMISION-->
</div>
<!--FIN BLOQUE HERMANOS ADMISION-->

<!--INICIO FILA DATOS-->
<div class="row">
  <div class="col-md-12">
   <p><b>Centro Solicitado</b></p>
  </div>
</div>
<hr style="background-color:grey!important;height:1px;margin-left:35px">
<div class="row">
   <div class="col-md-12">
      <div class="md-form mb-0">
         <input type="text"  id="id_centro_destino" value="" name="id_centro_destino" placeholder="Escribe uno o varios caracteres del centro solicitado" class="form-control" required>
      </div>
   </div>
</div>
<!--FIN FILA DATOS HERMANOS ADMISION-->
		<br>
<!--INICIO FILA DATOS-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form mb-0">
				<div class="form-group">
					<select class="form-control" name="tipoestudios" id="tipoestudios">
					    <option class="hamo" value="ebo">INFANTIL-EBO <i>(Nacidos de '.$_SESSION["ainicio_ebo"].' a '.$_SESSION["afin_ebo"].' ambos inclusive)</i></option>
					    <option class="hamo" value="tva">TVA <i>(Nacidos de '.$_SESSION["ainicio_tva"].' a '.$_SESSION["afin_tva"].' ambos inclusive)</i></option>
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
                            <input type="text" id="id_centro_destino1" value="" name="id_centro_destino1" placeholder="1 Nombre centro destino" class="form-control">
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
                            <input type="text" id="id_centro_destino6" value="" name="id_centro_destino6" placeholder="6 Nombre centro destino" class="form-control">
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
         <p><b>TODOS LOS FICHEROS QUE SE AGREGUEN DEBEN TENER FORMATO PDF O DE IMAGEN (jpg, png o jpeg) Y NO SUPERAR UN TAMAÑO DE 10MB</b></p>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="md-form mb-0">
         <input type="checkbox" id="baremo_marcado_proximidad_domicilio" name="baremo_marcado_proximidad_domicilio" value="1" data-baremo="1" >
         <label>Proximidad domicilio a efectos de baremo</label>
         <span class="ttip" data-toggle="tooltip" title=""></span>
         <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
         <div id="cajabaremo_domicilio" class="cajabaremo"> 
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
            
            <input type="hidden" id="baremo_validar_proximidad_domicilio" value="0" name="baremo_validar_proximidad_domicilio">
            <button name="boton_baremo_validar_proximidad_domicilio" type="button" class="btn btn-outline-dark validar">Validar domicilio</button>
            <button name="boton_baremo_comprobar_domicilio" type="button" class="btn btn-outline-dark comprobar" tipo="padron">Comprobar domicilio</button>
            <div id="caja_fbaremo_domicilio">            
                  <input value="ficherodomicilio" type="file" name="baremo_file_domicilio" campobaremo="domicilio" id="fbaremo_domicilio" style="display:none" class="fbaremo">
                  <label for="fbaremo_domicilio" id="fbaremo" class="botonform">Agrega fichero justificativo</label>
            </div>
            <a id="enlacefjdomicilio" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
            <a id="borrardomicilio" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
		   </div>
		</div>
    </div>
    <div class="col-md-3">
      <div class="md-form mb-0">
         <input type="checkbox" id="baremo_tutores_centro" name="baremo_tutores_centro" value="1" data-baremo="4" >
         <label  for="hermanos_baremo">Padre/madre/tutor trabaja en el centro</label>
      </div>
      <input type="hidden" id="baremo_validar_tutores_centro" value="0" name="baremo_validar_tutores_centro">
      <button name="boton_baremo_validar_tutores_centro" type="button" class="btn btn-outline-dark validar">Validar tutores trabajan centro</button>
    </div>

    <div class="col-md-3">
      <div class="md-form mb-0">
         <input type="checkbox" id="baremo_situacion_sobrevenida" name="baremo_situacion_sobrevenida" value="1" data-baremo="0" >
         <label for="situacion_sobrevenida">Situación sobrevenida (<i>solo para decidir en caso de empate</i>)</label>
      </div>
      <div id="caja_fbaremo_situacion_sobrevenida">            
            <input value="ficherosituacion_sobrevenida" type="file" name="baremo_file_situacion_sobrevenida" campobaremo="situacion_sobrevenida" id="fbaremo_situacion_sobrevenida" style="display:none" class="fbaremo">
            <label for="fbaremo_situacion_sobrevenida" id="afbaremo_situacion_sobrevenida" class="botonform" style="display:none">Agrega fichero justificativo situacion sobrevenida</label>
      </div>
      <a id="enlacefjsituacion_sobrevenida" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
      <a id="borrarsituacion_sobrevenida" class="enlacefbaremo enlaceborrarfichero" style="display:none" target="_blank">Retirar fichero</a>
      <input type="hidden" id="baremo_validar_situacion_sobrevenida" value="0" name="baremo_validar_situacion_sobrevenida">
      <button name="boton_baremo_validar_situacion_sobrevenida" type="button" class="btn btn-outline-dark validar">Validar situacion sobrevenida</button>
    </div>

    <div class="col-md-3">
      <div class="md-form mb-0">
         <input type="checkbox" id="baremo_renta_inferior" name="baremo_renta_inferior" value="1" data-baremo="5" >
         <label> Renta inferior IMV (Ingreso Mínimo vital)</label>
         <label id="msg_comprobacion_renta_inferior" class="botonform" style="display:none;color:red">Estado: PENDIENTE DE COMPROBAR</label>
         <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
      </div>
      <input type="hidden" id="baremo_validar_renta_inferior" value="0" name="baremo_validar_renta_inferior">
    </div>
   </div>
<!--FIN FILA DATOS-->
<hr>
<!--INICIO FILA DATOS NUEVOS BAREMO-->
	      <div class="row">
        	   <div class="col-md-3">
               <div class="md-form mb-0">
            	   <input type="checkbox" id="baremo_acogimiento" name="baremo_acogimiento" value="1" data-baremo="1"  >
      				<label>Situación de acogimiento</label>
                  <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
               </div>
               <input type="hidden" id="baremo_validar_acogimiento" value="0" name="baremo_validar_acogimiento">
               <button name="boton_baremo_validar_acogimiento" type="button" class="btn btn-outline-dark validar">Validar situación de acogimiento</button>
            </div>
        	   <div class="col-md-3">
               <div class="md-form mb-0">
                  <input type="checkbox" id="baremo_genero" name="baremo_genero" value="1" data-baremo="1">
                  <label>Víctima de violencias de género</label>
                  <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
               </div>
            <input type="hidden" id="baremo_validar_genero" value="0" name="baremo_validar_genero">
            <button name="boton_baremo_validar_genero" type="button" class="btn btn-outline-dark validar">Validar víctima de género</button>
            </div>
            <div class="col-md-3">
               <div class="md-form mb-0">
                  <input type="checkbox" id="baremo_terrorismo" name="baremo_terrorismo" value="1" data-baremo="1">
                  <label> Víctima de terrorismo</label>
                  <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
               </div>
               <input type="hidden" id="baremo_validar_terrorismo" value="0" name="baremo_validar_terrorismo">
               <button name="boton_baremo_validar_terrorismo" type="button" class="btn btn-outline-dark validar">Validar víctima de terrorismo</button>
            </div>
            <div class="col-md-3">
               <div class="md-form mb-0">
                  <input type="checkbox" id="baremo_parto" name="baremo_parto" value="1" data-baremo="1">
                  <label> Parto múltiple</label>
                  <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
               </div>
               <input type="hidden" id="baremo_validar_parto" value="0" name="baremo_validar_parto">
               <button name="boton_baremo_validar_parto" type="button" class="btn btn-outline-dark validar">Validar parto múltiple</button>
            </div>
        </div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS NUEVOS PARA FICHEROS JUSTIFICATIVOS-->
	      <div class="row">
        	   <div class="col-md-3">
               <div id="caja_fbaremo_acogimiento">            
                     <input value="ficheroacogimiento" type="file" name="baremo_file_acogimiento" campobaremo="acogimiento" id="fbaremo_acogimiento" style="display:none" class="fbaremo">
                     <label for="fbaremo_acogimiento" id="afbaremo_acogimiento" class="botonform" style="display:none">Agrega fichero justificativo acogimiento</label>
               </div>
               <a id="enlacefjacogimiento" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
               <a id="borraracogimiento" class="enlacefbaremo enlaceborrarfichero" style="display:none" target="_blank">Retirar fichero</a>
            </div>
        	   <div class="col-md-3">
               <div id="caja_fbaremo_genero">            
                     <input value="ficherogenero" type="file" name="baremo_file_genero" campobaremo="genero" id="fbaremo_genero" style="display:none" class="fbaremo">
                     <label for="fbaremo_genero" id="afbaremo_genero" class="botonform" style="display:none">Agrega fichero justificativo género</label>
               </div>
               <a id="enlacefjgenero" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
               <a id="borrargenero" class="enlacefbaremo enlaceborrarfichero" style="display:none" target="_blank">Retirar fichero</a>
            </div>
        	   <div class="col-md-3">
               <div id="caja_fbaremo_terrorismo">            
                     <input value="ficheroterrorismo" type="file" name="baremo_file_terrorismo" campobaremo="genero" id="fbaremo_terrorismo" style="display:none" class="fbaremo">
                     <label for="fbaremo_terrorismo" id="afbaremo_terrorismo" class="botonform" style="display:none">Agrega fichero justificativo terrorismo</label>
               </div>
               <a id="enlacefjterrorismo" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
               <a id="borrarterrorismo" class="enlacefbaremo enlaceborrarfichero" style="display:none" target="_blank">Retirar fichero</a>
            </div>
        	   <div class="col-md-3">
               <div id="caja_fbaremo_parto">            
                     <input value="ficheroparto" type="file" name="baremo_file_parto" campobaremo="parto" id="fbaremo_parto" style="display:none" class="fbaremo">
                     <label for="fbaremo_parto" id="afbaremo_parto" class="botonform" style="display:none">Agrega fichero justificativo parto múltiple</label>
               </div>
               <a id="enlacefjparto" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
               <a id="borrarparto" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
            </div>
        </div>
<!--FIN FILA DATOS-->
<hr>
<!--INICIO FILA DATOS-->
<div class="row">
<div class="col-md-12">
   <div class="md-form mb-0">
      <input type="checkbox" id="num_hbaremo" name="num_hbaremo" value="1" class="num_hbaremo" >
         <label for="hermanos_baremo">Tiene matriculados a los siguientes hermanos:</label>
   </div>
 </div>
</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS HERMANOS BAREMO-->
<div class="row hno_baremo" style="display:none">
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_nombre_baremo1" value="" name="hermanos_nombre_baremo1" data-baremo="8" placeholder="Nombre hermano" class="form-control" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_apellido1_baremo1" value="" name="hermanos_apellido1_baremo1" data-baremo="8" placeholder="Primer Apellido" class="form-control" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_apellido2_baremo1" value="" name="hermanos_apellido2_baremo1" data-baremo="8" placeholder="Segundo Apellido" class="form-control" >
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="date" id="hermanos_fnacimiento_baremo1" value="" name="hermanos_fnacimiento_baremo1" placeholder="Fecha Nacimiento" class="form-control">
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <select class="form-control" id="hermanos_modalidad_baremo1" value="" name="hermanos_modalidad_baremo1">
            <option value="nodata">Selecciona modalidad de origen</option>
            <option><b>MODALIDAD ESCOLARIZACION ORDINARIA</b></option>
            <optgroup label="2º CICLO INFANTIL"></optgroup>
            <option class="hb1" value="combinada-1infantil">1º INFANTIL</option>
            <option class="hb1" value="combinada-2infantil">2º INFANTIL</option>
            <option class="hb1" value="combinada-3infantil">3º INFANTIL</option>
            <optgroup label="PRIMARIA"></optgroup>
            <option class="hb1" value="combinada-1primaria">1º PRIMARIA</option>
            <option class="hb1" value="combinada-2primaria">2º PRIMARIA</option>
            <option class="hb1" value="combinada-3primaria">3º PRIMARIA</option>
            <option class="hb1" value="combinada-4primaria">4º PRIMARIA</option>
            <option class="hb1" value="combinada-5primaria">5º PRIMARIA</option>
            <option class="hb1" value="combinada-6primaria">6º PRIMARIA</option>
            <optgroup label="ESO"></optgroup>
            <option class="hb1" value="combinada-1eso">1º ESO</option>
            <option class="hb1" value="combinada-2eso">2º ESO</option>
            <option class="hb1" value="combinada-3eso">3º ESO</option>
            <option class="hb1" value="combinada-4eso">4º ESO</option>
            <option><b>MODALIDAD: EDUCACION ESPECIAL</option>
            <optgroup label="TIPO"></optgroup>
            <option class="hb1" value="especial-1ebo">INFANTIL-EBO</option>
            <option class="hb1" value="especial-1tva">TVA</option>
			   <option class="hb1" value="especial-ldos">DOS AÑOS <i>Nacidos en 2020</i></option>
         </select>
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <select class="form-control" id="hermanos_nivel_baremo1" value="" name="hermanos_nivel_baremo1">
            <option class="hbn1" value="nodata">Selecciona nivel origen</option>
            <option class="hbn1" value="infantil">INFANTIL</option>
            <option class="hbn1" value="primaria">PRIMARIA</option>
            <option class="hbn1" value="eso">ESO</option>
            <option class="hbn1" value="infantil-ebo">INFANTIL EBO</option>
            <option class="hbn1" value="tva">TVA</option>
            <option class="hbn1" value="dos">DOS AÑOS</option>
         </select>
      </div>
   </div>
   <br>
</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS HERMANOS BAREMO-->
<div class="row hno_baremo" style="display:none">
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_nombre_baremo2" value="" name="hermanos_nombre_baremo2" data-baremo="8" placeholder="Nombre hermano" class="form-control" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_apellido1_baremo2" value="" name="hermanos_apellido1_baremo2" data-baremo="8" placeholder="Primer Apellido" class="form-control" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_apellido2_baremo2" value="" name="hermanos_apellido2_baremo2" data-baremo="8" placeholder="Segundo Apellido" class="form-control" >
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="date" id="hermanos_fnacimiento_baremo2" value="" name="hermanos_fnacimiento_baremo2" placeholder="Fecha Nacimiento" class="form-control">
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <select class="form-control" id="hermanos_modalidad_baremo2" value="" name="hermanos_modalidad_baremo2">
            <option value="nodata">Selecciona modalidad de origen</option>
            <option><b>MODALIDAD ESCOLARIZACION ORDINARIA</b></option>
            <optgroup label="2º CICLO INFANTIL"></optgroup>
            <option class="hb2" value="combinada-1infantil">1º INFANTIL</option>
            <option class="hb2" value="combinada-2infantil">2º INFANTIL</option>
            <option class="hb2" value="combinada-3infantil">3º INFANTIL</option>
            <optgroup label="PRIMARIA"></optgroup>
            <option class="hb2" value="combinada-1primaria">1º PRIMARIA</option>
            <option class="hb2" value="combinada-2primaria">2º PRIMARIA</option>
            <option class="hb2" value="combinada-3primaria">3º PRIMARIA</option>
            <option class="hb2" value="combinada-4primaria">4º PRIMARIA</option>
            <option class="hb2" value="combinada-5primaria">5º PRIMARIA</option>
            <option class="hb2" value="combinada-6primaria">6º PRIMARIA</option>
            <optgroup label="ESO"></optgroup>
            <option class="hb2" value="combinada-1eso">1º ESO</option>
            <option class="hb2" value="combinada-2eso">2º ESO</option>
            <option class="hb2" value="combinada-3eso">3º ESO</option>
            <option class="hb2" value="combinada-4eso">4º ESO</option>
            <option><b>MODALIDAD: EDUCACION ESPECIAL</option>
            <optgroup label="TIPO"></optgroup>
            <option class="hb2" value="especial-1ebo">INFANTIL-EBO</option>
            <option class="hb2" value="especial-1tva">TVA</option>
            <option class="hbn2" value="dos">DOS AÑOS</option>
         </select>
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <select class="form-control" id="hermanos_nivel_baremo2" value="" name="hermanos_nivel_baremo2">
            <option value="nodata">Selecciona nivel origen</option>
            <option class="hbn2" value="infantil">INFANTIL</option>
            <option class="hbn2" value="primaria">PRIMARIA</option>
            <option class="hbn2" value="eso">ESO</option>
            <option class="hbn2" value="infantil-ebo">INFANTIL EBO</option>
            <option class="hbn2" value="tva">TVA</option>
            <option class="hbn2" value="dos">DOS AÑOS</option>
         </select>
      </div>
   </div>
   <br>
</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS HERMANOS BAREMO-->
<div class="row hno_baremo" style="display:none">
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_nombre_baremo3" value="" name="hermanos_nombre_baremo3" data-baremo="8" placeholder="Nombre hermano" class="form-control" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_apellido1_baremo3" value="" name="hermanos_apellido1_baremo3" data-baremo="8" placeholder="Primer Apellido" class="form-control" >
      </div>
   </div>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="text" id="hermanos_apellido2_baremo3" value="" name="hermanos_apellido2_baremo3" data-baremo="8" placeholder="Segundo Apellido" class="form-control" >
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <input type="date" id="hermanos_fnacimiento_baremo3" value="" name="hermanos_fnacimiento_baremo3" placeholder="Fecha Nacimiento" class="form-control">
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <select class="form-control" id="hermanos_modalidad_baremo3" value="" name="hermanos_modalidad_baremo3">
            <option value="nodata">Selecciona modalidad de origen</option>
            <option><b>MODALIDAD ESCOLARIZACION ORDINARIA</b></option>
            <optgroup label="2º CICLO INFANTIL"></optgroup>
            <option class="hb3" value="combinada-1infantil">1º INFANTIL</option>
            <option class="hb3" value="combinada-2infantil">2º INFANTIL</option>
            <option class="hb3" value="combinada-3infantil">3º INFANTIL</option>
            <optgroup label="PRIMARIA"></optgroup>
            <option class="hb3" value="combinada-1primaria">1º PRIMARIA</option>
            <option class="hb3" value="combinada-2primaria">2º PRIMARIA</option>
            <option class="hb3" value="combinada-3primaria">3º PRIMARIA</option>
            <option class="hb3" value="combinada-4primaria">4º PRIMARIA</option>
            <option class="hb3" value="combinada-5primaria">5º PRIMARIA</option>
            <option class="hb3" value="combinada-6primaria">6º PRIMARIA</option>
            <optgroup label="ESO"></optgroup>
            <option class="hb3" value="combinada-1eso">1º ESO</option>
            <option class="hb3" value="combinada-2eso">2º ESO</option>
            <option class="hb3" value="combinada-3eso">3º ESO</option>
            <option class="hb3" value="combinada-4eso">4º ESO</option>
            <option><b>MODALIDAD: EDUCACION ESPECIAL</option>
            <optgroup label="TIPO"></optgroup>
            <option class="hb3" value="especial-1ebo">INFANTIL-EBO</option>
            <option class="hb3" value="especial-1tva">TVA</option>
            <option class="hb3" value="dos">DOS AÑOS</option>
         </select>
      </div>
   </div>
   <br>
   <div class="col-md-2">
      <div class="md-form mb-0">
         <select class="form-control" id="hermanos_nivel_baremo3" value="" name="hermanos_nivel_baremo3">
            <option value="nodata">Selecciona nivel origen</option>
            <option class="hbn3" value="infantil">INFANTIL</option>
            <option class="hbn3" value="primaria">PRIMARIA</option>
            <option class="hbn3" value="eso">ESO</option>
            <option class="hbn3" value="infantil-ebo">INFANTIL EBO</option>
            <option class="hbn3" value="tva">TVA</option>
            <option class="hbn3" value="dos">DOS AÑOS</option>
         </select>
      </div>
   </div>
   <br>
</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
<div class="row hno_baremo" style="display:none">
   <div class="col-md-4">
      <input type="hidden" id="baremo_validar_hnos_centro" value="0" name="baremo_validar_hnos_centro">
      <button name="boton_baremo_validar_hnos_centro" type="button" class="btn btn-outline-dark validar">Validar hermanos</button>
   </div>
</div>
<hr>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS DISCAPACIDAD A EFECTOS DE BAREMO-->
<div class="row">
   <div class="col-md-12">
      <input type="checkbox" id="baremo_marcado_discapacidad" name="baremo_marcado_discapacidad" value="1" data-baremo="1" >
      <label>Discapacidad a efectos de baremo:</label>
      <br><span class="ttip" data-toggle="tooltip" title=""><i class="infobaremo">Ver información del baremo</i></span>
      <div id="cajabaremo_discapacidad" class="cajabaremo"> 
         <div class="radio">
            <label><b>DISCAPACIDAD</b></label>
         </div>
         <div class="radio">
            <label><input type="checkbox" name="baremo_discapacidad_alumno" value="1" data-baremo="1">Del alumno</label>
            <label id="msg_comprobacion_discapacidad_alumno" class="botonform" style="display:none;color:red;width:16%">Estado: PENDIENTE DE COMPROBAR</label>
         </div>
         <div id="caja_fbaremo_discapacidad_alumno">            
               <input value="ficherodiscapacidad_alumno" type="file" name="baremo_file_discapacidad_alumno" campobaremo="discapacidad_alumno" id="fbaremo_discapacidad_alumno" style="display:none" class="fbaremo">
               <label for="fbaremo_discapacidad_alumno" id="fbaremodiscapacidad_alumno" class="botonform">Agrega fichero justificativo discapacidad del alumno</label>
         </div>
         <a id="enlacefjdiscapacidad_alumno" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
         <a id="borrardiscapacidad_alumno" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
         <input type="hidden" id="baremo_validar_discapacidad_alumno" value="0" name="baremo_validar_discapacidad_alumno">
         <div class="radio">
            <label><input type="checkbox" name="baremo_discapacidad_hermanos" value="1" data-baremo="1">De progenitores/hermanos</label>
            <label id="msg_comprobacion_discapacidad_hermanos" class="botonform" style="display:none;color:red;width:16%">Estado: PENDIENTE DE COMPROBAR</label>
         </div>
         <div id="cajadatosdiscapacidad" style="display:none">
            <div class="row">
               <div class="col-md-3">
                  <input type="text" id="baremo_nombredisc1" value="" name="baremo_nombredisc1" placeholder="nombre" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="text" id="baremo_apellidodisc1" value="" name="baremo_apellidodisc1" placeholder="apellido" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="text" id="baremo_dnidisc1" value="" name="baremo_dnidisc1" placeholder="dni" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="date" id="baremo_fnacdisc1" value="" name="baremo_fnacdisc1" placeholder="Fecha nacimiento" class="form-control is-valid" required>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div id="caja_fbaremo_discapacidadh1">            
                        <input value="ficherodiscapacidad" type="file" name="baremo_file_discapacidadh1" campobaremo="discapacidadh1" id="fbaremo_discapacidadh1" style="display:none" class="fbaremo">
                        <label for="fbaremo_discapacidadh1" id="fbaremodiscapacidadh1" class="botonform">Agrega fichero justificativo discapacidad del primer hermano/progenitor</label>
                  </div>
                  <a id="enlacefjdiscapacidadh1" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
                  <a id="borrardiscapacidadh1" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
               </div>
            </div>
            <div class="row">
               <div class="col-md-3">
                  <input type="text" id="baremo_nombredisc2" value="" name="baremo_nombredisc2" placeholder="nombre" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="text" id="baremo_apellidodisc2" value="" name="baremo_apellidodisc2" placeholder="apellido" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="text" id="baremo_dnidisc2" value="" name="baremo_dnidisc2" placeholder="dni" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="date" id="baremo_fnacdisc2" value="" name="baremo_fnacdisc2" placeholder="Fecha nacimiento" class="form-control is-valid" required>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div id="caja_fbaremo_discapacidadh2">            
                        <input value="ficherodiscapacidad" type="file" name="baremo_file_discapacidadh2" campobaremo="discapacidadh2" id="fbaremo_discapacidadh2" style="display:none" class="fbaremo">
                        <label for="fbaremo_discapacidadh2" id="fbaremodiscapacidadh2" class="botonform">Agrega fichero justificativo discapacidad del segundo hermano/progenitores</label>
                  </div>
                  <a id="enlacefjdiscapacidadh2" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
                  <a id="borrardiscapacidadh2" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
               </div>
            </div>
            <div class="row">
               <div class="col-md-3">
                  <input type="text" id="baremo_nombredisc3" value="" name="baremo_nombredisc3" placeholder="nombre" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="text" id="baremo_apellidodisc3" value="" name="baremo_apellidodisc3" placeholder="apellido" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="text" id="baremo_dnidisc3" value="" name="baremo_dnidisc3" placeholder="dni" class="form-control is-valid" required>
               </div>
               <div class="col-md-3">
                  <input type="date" id="baremo_fnacdisc3" value="" name="baremo_fnacdisc3" placeholder="Fecha nacimiento" class="form-control is-valid" required>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div id="caja_fbaremo_discapacidadh3">            
                        <input value="ficherodiscapacidad" type="file" name="baremo_file_discapacidadh3" campobaremo="discapacidadh3" id="fbaremo_discapacidadh3" style="display:none" class="fbaremo">
                        <label for="fbaremo_discapacidadh3" id="fbaremodiscapacidadh3" class="botonform">Agrega fichero justificativo discapacidad del tercer hermano/progenitor</label>
                  </div>
                  <a id="enlacefjdiscapacidadh3" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero</a>
                  <a id="borrardiscapacidadh3" class="enlacefbaremo enlaceborrarfichero" style="display:none" class="enlacefbaremo" target="_blank">Retirar fichero</a>
               </div>
            </div>
<!--INICIO FILA DATOS-->
<div class="row">
   <div class="col-md-4">
      <input type="hidden" id="baremo_validar_discapacidad_hermanos" value="0" name="baremo_validar_discapacidad_hermanos">
   </div>
</div>
<!--FIN FILA DATOS-->
         </div>
      </div>
   </div>
</div>
<hr>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS BAREMO TIPO FAMILIA-->
<div class="row">
       <div class="col-md-4">
         <input type="checkbox" id="baremo_marcado_numerosa" name="baremo_marcado_numerosa" value="1" data-baremo="1" >
         <label>Familia numerosa a efectos de baremo:</label>
         <label id="msg_comprobacion_familia_numerosa" class="botonform" style="display:none;color:red;width:50%">Estado: PENDIENTE DE COMPROBAR</label>
         <div id="cajabaremo_numerosa" class="cajabaremo"> 
            <div class="radio">
               <label><b> FAMILIA NUMEROSA</b></label>
            </div>
            <div class="radio">
               <label><input type="radio" name="baremo_tipo_familia_numerosa" value="1" data-baremo="1">General</label>
            </div>
            <div class="radio">
               <label><input type="radio" name="baremo_tipo_familia_numerosa" value="2" data-baremo="2">Especial</label>
            </div>
         </div>
      </div>
       <div class="col-md-4">
         <input type="checkbox" id="baremo_marcado_monoparental" name="baremo_marcado_monoparental" value="1" data-baremo="1" >
         <label>Familia monoparental a efectos de baremo:</label>
         <label id="msg_comprobacion_familia_monoparental" class="botonform" style="display:none;color:red;width:55%">Estado: PENDIENTE DE COMPROBAR</label>
         <div id="cajabaremo_monoparental" class="cajabaremo"> 
            <div class="radio">
               <label><b> FAMILIA MONOPARENTAL</b></label>
            </div>
            <div class="radio">
               <label><input type="radio" name="baremo_tipo_familia_monoparental" value="1" data-baremo="1">General</label>
            </div>
            <div class="radio">
               <label><input type="radio" name="baremo_tipo_familia_monoparental" value="2" data-baremo="2">Especial</label>
            </div>
            <p>Los criterios de familia 1.6 y 1.7 no serán acumulables, siendo aplicable únicamente el concepto que otorgue mayor puntuación de acuerdo con lo previsto en el artículo 16 de la<a style="color:darkblue!important" href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&ved=2ahUKEwi_rLLotNLuAhXC8uAKHU5sDUwQFjABegQIBRAC&url=https%3A%2F%2Fwww.aragon.es%2Fdocuments%2F20127%2F16716070%2FBOA%2BORDEN%2BCDS-384-2019%2Bde%2B4%2Bde%2Babril%2Bfamilias%2Bmonoparentales%2Barag%25C3%25B3n.pdf%2F9cb6d8eb-b89c-171a-3c37-9c725fb53ef7%3Ft%3D1570188961989&usg=AOvVaw3CjtGi1we5poLb6wFfZxST" target="_blank"> Orden CDS/384/2019, de 4 de abril</a></p>
         </div>
      </div>
';
          
if($rol!='alumno' and $rol!='anonimo')
{
   if($rol=='centro')
      $disabled='disabled';
   else 
      $disabled='';
   if($rol=='admin' or $rol=='sp' or $rol=='centro')
   {
      $formsol.='
      <!--CRITERIOS-->
      <div class="col-md-4">
         <div class="md-form mb-0">
            <div class="radio">
               <label><b>CRITERIOS DE PRIORIDAD</b></label>
            </div>
         </div>
            <div class="radio">
               <label><input type="radio" name="transporte" value="1" '.$disabled.'>Con Ruta de Transporte</label>
            </div>
               <label><i style="font-size:14px">Nueva escolarización de localidades con ruta de transporte (art39) Decreto 51/2021 </i></label>
            <div class="radio">
               <label><input type="radio" name="transporte" value="2" '.$disabled.'>No prioridad</label>
            </div>
      </div>
      <!--FIN CRITERIOS-->
      ';
   }
}
$formsol.='
</div>
<!--FIN FILA DATOS-->
<!--INICIO FILA DATOS-->
<div class="row">
   <div class="col-md-4">
      <div class="md-form mb-0">
         <input type="hidden" id="baremo_validar_tipo_familia_numerosa" value="0" name="baremo_validar_tipo_familia_numerosa">
      </div>
   </div>
   <div class="col-md-4">
      <div class="md-form mb-0">
         <input type="hidden" id="baremo_validar_tipo_familia_monoparental" value="0" name="baremo_validar_tipo_familia_monoparental">
      </div>
   </div>
</div>
<!--FIN FILA DATOS-->
</div>
<!--FIN SECCION BAREMO-->
<!--INICIO SECCION DATOS: CARGA DOCUMENTOS-->
<!--FIN FILA DATOS-->
<br>
</div>
</div>
<!--FIN SECCION CARGA DOCUMENTACION-->
   <div class="row operacionesformulario">
	    <div class="text-center text-md-left">
         <!--<a class="btn btn-primary send" >GRABAR SOLICITUD</a>-->
       </div>';
if($rol!='admin' or $rol=='alumno')
{
$formsol.= ' 
	          <div class="text-center text-md-left">
             </div>';
}
$formsol.=
   '</div>
   <div class="status"></div>
</form> 
</div>
</div></td></tr>';    
