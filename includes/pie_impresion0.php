<?php
echo '<div class="row" style="padding-top:70px;width:100%">';
echo '___________________________________________________________________________________________________________________________________________________________';
echo '</div>';

###############################################################OBLIGATORIA##############################################################################
echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b>DOCUMENTACION CARACTER OBLIGATORIO</b>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>Requisito de edad</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%>';
	echo '<div class="col-6">';
		echo '<div class="col-1" style="text-align:right">';
			echo '<input type="checkbox" name="re1" value="re1">';
		echo '</div>';
		echo '<div class="col">';
			echo '<label for="re1">Documento acreditativo de que el alumno/a cumple el requisito de edad. (Exigible para los solicitantes  que se escolarizan por primera vez en un Centro sostenido con fondos públicos de la Comunidad Autónoma de Aragón)</label><br>';
		echo '</div>';
	echo '</div>';
	echo '<div class="col-6">';
	   echo '<input type="checkbox" id="re2" name="re2" value="re2">';
		echo '<label for="re2">Documento acreditativo de que el alumno cumple el requisito edad</label><br>';
	echo '</div>';
echo '</div>';

echo '<div class="row" style="padding-top:10px">';
	echo '<b> Acceso a plazas reservadas</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px">';
	echo '<div>El Departamento de Educación, Cultura y Deporte comprobará de oficio la existencia de las Resoluciones de la Dirección del Servicio Provincial correspondiente, relativas a alumnado con necesidades educativas específicas; en las que deberá constar que ha sido propuesto para ser escolarizado en un centro de Educación Especial.
 </div>';		
echo '</div>';
echo '<div class="row" style="padding-top:70px;width:100%">';
echo '___________________________________________________________________________________________________________________________________________________________';
echo '</div>';

###############################################################---------------OPCIONAL----------------##############################################################################
echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b>DOCUMENTACION CARACTER OPCIONAL</b>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.1. Existencia de hermanos matriculados en el centro</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo  '<table class="table table-bordered">
       <thead>
         <tr style="color:white">
           <th>Apellidos y Nombre</th>
           <th>Fecha de nacimiento</th>
           <th>Curso actual</th>
           <th>Nivel educativo</th>
         </tr>
       </thead>
       <tbody>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
       </tbody>
     </table>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.2. Proximidad domiciliaria</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re1" style="padding-left:10px">Acreditación del domicilio familiar</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
					echo '<label for="re2" style="padding-left:10px">  La Administración Pública de la Comunidad Autónoma de Aragón realizará las consultas necesarias para comprobar la exactitud de los datos aportados.</label><br>';
					echo '<label for="re2" style="padding-left:10px"> En particular consultará la verificación y consulta de datos de residencia con fecha de última variación padronal (Instituto Nacional de Estadística).</label><br>';
					echo '<label for="re2" style="padding-left:10px">Los titulares de los datos o sus representantes legales (caso de menores de catorce años o incapacitados) podrán ejercer su derecho de oposición conforme al modelo específico para el ejercicio de este derecho, disponible en https://www.aragon.es/en/tramitador/-/tramite/proteccion-datos-ejercicio-derecho-oposicion.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En caso de ejercicio de su derecho de oposición motivada, deberá aportar el certificado volante de empadronamiento expedido por el órgano municipal correspondiente.</label><br>';
		echo '</div>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re1" style="padding-left:10px">Acreditación del domicilio laboral</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Anexo VI a) Certificado emitido por empresa a efectos de justificación de domicilio laboral (trabajadores por cuenta ajena)</label><br>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row" style="padding-top:10px;width:100%;">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Anexo VI b) Declaración responsable a efectos de valoración del criterio de domicilio laboral</label><br>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
echo '</div>';


echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>2.2. Rentas especialmente bajas de la unidad familiar</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
	echo '<div class="row" style="padding-top:10px;width:100%;">';
		echo '<div class="col-1" style="text-align:right">';
			echo '<input type="checkbox" name="re1" value="re1">';
		echo '</div>';
		echo '<div class="col">';
			echo 'La Renta Anual de la unidad familiar en el ejercicio 2018 fue INFERIOR O IGUAL al IPREM (Cuantía fijada para el índice IPREM 2018:  6.454,03 euros)<br>';
		echo '</div>';
	echo '</div>';

echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>INFORMACIÓN DE CARACTER TRIBUTARIO</b>';
echo '<i>(Cumplimentar únicamente en el caso de que el nivel de renta de la unidad familiar  en el año 2018 haya sido inferior o igual a 6.454,03 euros)</i>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
echo 'A los efectos de acreditación de la renta anual de la unidad familiar:';
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
	echo '<div class="col-1" style="text-align:right">';
		echo '<input type="checkbox" name="re1" value="re1">';
	echo '</div>';
	echo '<div class="col">';
		echo 'Los abajo firmantes declaran responsablemente que cumplen con sus obligaciones tributarias, así como que autorizan expresamente al Departamento de Educación, Cultura y Deporte para que recabe de la Agencia Estatal de Administración Tributaria (AEAT), la información de carácter tributario del ejercicio fiscal 2018. (Firmada por todos los miembros)';
	echo '</div>';
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
	echo '<div class="col-1" style="text-align:right">';
		echo '<input type="checkbox" name="re1" value="re1">';
	echo '</div>';
	echo '<div class="col">';
		echo 'Los abajo firmantes se oponen a autorizar expresamente al Departamento de Educación, Cultura y Deporte para que recabe, de la AEAT, la información de carácter tributario del ejercicio fiscal 2018, y aportAan certificación expedida por la AEAT de cada uno de los miembros de la unidad familiar, correspondiente al ejercicio fiscal 2018. Se hará constar los miembros computables de la familia a 31 de diciembre de 2018';
	echo '</div>';
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo  '<table class="table table-bordered">
       <thead>
         <tr style="color:white">
           <th>Nombre</th>
           <th>Primer Apellido</th>
           <th>Segundo Apellido</th>
           <th>Parentesco</th>
           <th>DNI</th>
           <th>Firma</th>
         </tr>
       </thead>
       <tbody>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
         <tr>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
           <td contenteditable="true"></td>
         </tr>
       </tbody>
     </table>';
echo '</div>';


echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>2.3. Existencia de padres o tutores que trabajen en el centro:</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
	echo '<div class="col-1" style="text-align:right">';
		echo '<input type="checkbox" name="re1" value="re1">';
	echo '</div>';
	echo '<div class="col">';
		echo 'El alumno/a tiene padres, madres o tutores/as legales trabajando en el centro al que se dirige esta solicitud, que van a continuar con tal condición en el curso académico para el que se solicita plaza. (Especificar en su caso)(5)';
	echo '</div>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
echo  '<table class="table table-bordered">
    <thead>
      <tr style="color:white">
        <th>Apellidos y Nombre</th>
        <th>DNI</th>
        <th>Centro Educativo Actual</th>
        <th>Fecha inicio relación laboral</th>
        <th>Duración prevista</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
      </tr>
      <tr>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
        <td contenteditable="true"></td>
      </tr>
    </tbody>
  </table>';
echo '</div>';


echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>2.4. Concurrencia de discapacidad, con un grado igual o superior al 33%, en el alumno o en alguno de sus padres o hermanos</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re1">El alumno/a o alguno de sus padres, madres, tutores/as o hermanos/as, tiene reconocido mediante dictamen emitido por Organismo público competente, un grado de discapacidad igual o superior al 33%.</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Certificado de reconocimiento del grado de discapacidad del alumno/a</label><br>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row" style="padding-top:10px;width:100%;">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Certificado de reconocimiento del grado de discapacidad de los padres, madres, tutores/as o hermanos/as</label><br>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>2.5. Pertenencia a familia numerosa</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Numerosa especial</label><br>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row" style="padding-top:10px;width:100%;">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Numerosa General</label><br>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re1">Documento acreditativo de familia numerosa</label><br>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<b>2.6. Pertenencia a familia monoparental</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Monoparental especial</label><br>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row" style="padding-top:10px;width:100%;">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Monoparental General</label><br>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re1">Documento acreditativo de familia monoparental</label><br>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
echo '</div>';

echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b>INFORMACIÓN SOBRE GRATUIDAD DE ENSEÑANZAS:</b>';
echo '</div>';

echo '<div class="row" style="padding-top:30px">';
echo 'Los abajo firmantes declaran estar informados de que en ningún caso los centros públicos y los privados concertados podrán percibir cantidades por las enseñanzas de carácter gratuito, imponer la obligación de hacer aportaciones a fundaciones o a asociaciones ni establecer servicios obligatorios asociados a las enseñanzas que requieran aportación económica.<br>';
echo '</div>';
echo '<div class="row" style="padding-top:70px">';
	echo '<div class="col-6">';
		echo '<div class="row" style="padding-top:20px">';
			echo '<div class="col">En........................................................a..............de 2020<br><br></div>';
		echo '</div>';
		echo '<div class="row" style="padding-top:20px">';
			echo '<div class="col">Fdo.:________________________________________<br>';																
			echo '<i style="font-size:9px">Firma del padre, madre o tutor/a (7)</i><br></div>';
		echo '</div>';
		echo '<div class="row" style="padding-top:20px">';
		echo '</div>';
	echo '</div>';
	echo '<div class="col-6">';
		echo '<div class="row" style="padding-top:20px">';
	echo '<img src="img/sellocentro.jpg" style="padding-left:250px">';
		echo '</div>';
	echo '</div>';
echo '</div>';


echo '<div class="row" style="padding-top:70px">';
echo 'Los datos recogidos en este formulario serán incorporados en el Registro de Actividades de Tratamiento de la Dirección General de Planificación y Equidad “PROCEDIMIENTO ESCOLARIZACIÓN ALUMNADO”. Sus datos personales serán tratados con el fin exclusivo de gestión del procedimiento de escolarización de alumnado en guarderías infantiles y centros docentes no universitarios de titularidad del Gobierno de Aragón, así como en centros docentes no universitarios privados concertados de Aragón.';
echo '</div>';

echo '<div class="row" style="padding-top:70px">';
echo 'El órgano responsable del Registro de Actividades de Tratamiento es la Dirección General Planificación y Equidad recogida en el Decreto 314/2015, de 15 de diciembre, del Gobierno de Aragón, por el que se aprueba la estructura orgánica del Departamento de Educación, Cultura y Deporte, con la configuración actual recogida en el Decreto 93/2019, de 8 de agosto, por el que se desarrolla la estructura orgánica básica de la Administración de la Comunidad Autónoma de Aragón. La licitud del tratamiento de los datos es necesaria para el cumplimiento de una obligación legal aplicable al responsable del tratamiento. Estos datos no se comunicarán a terceros, salvo obligación legal.';
echo '</div>';

echo '<div class="row" style="padding-top:70px">';
	echo 'Podrá usted ejercer sus derechos de acceso, rectificación, supresión y portabilidad de datos de carácter personal, así como de limitación y oposición a su tratamiento, ante la Dirección General de Planificación y Equidad (Avda. Ranillas, 5D, de Zaragoza, CP 50071) o en la dirección de correo electrónico educentros@aragon.es, de conformidad con lo dispuesto en el Reglamento General de Protección de Datos. Podrá consultar información adicional y detallada en el Registro de Actividades de Tratamiento del Gobierno de Aragón,  http://aplicaciones.aragon.es/notif_lopd_pub/  identificando la siguiente Actividad de Tratamiento, “PROCEDIMIENTO ESCOLARIZACIÓN ALUMNADO”.';
echo '</div>';

echo '<div class="row" style="padding-top:70px">';
	echo '<b>Advertencia.-  Quedarán excluidos del procedimiento los siguientes casos:</b>		
<br>Cuando se presenta más de una solicitud.																																																								
<br>Cuando se presente fuera del plazo de presentación de solicitudes.																																																								
<br>Cuando se aprecie la existencia de indicios razonados y suficientes de falsedad de la documentación aportada por el interesado o de los datos reflejados en la misma.																																																								
';
echo '<div class="row" style="padding-top:100px;width:100%;padding-left:700px">';
echo 'SR/A DIRECTOR/A O TITULAR DEL CENTRO   _________________________________________';

echo '</div>';
echo '</div>';


?>
