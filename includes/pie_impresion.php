<?php
echo '<div class="row" style="padding-top:70px;width:100%">';
echo '___________________________________________________________________________________________________________________________________________________________';
echo '</div>';

###############################################################OBLIGATORIA##############################################################################
echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b>1. DOCUMENTACION CARACTER OBLIGATORIO</b>';
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>1.1. Requisito de edad</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" checked>';
		         echo '<label for="re2">Documento acreditativo de que el alumno cumple el requisito edad</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
					echo '<label for="re2" style="padding-left:10px">  La Administración Pública de la Comunidad Autónoma de Aragón realizará las consultas necesarias para comprobar la exactitud de los datos aportados.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En particular consultará el requisito de edad (Instituto Nacional de Estadística o Registro Civil).</label><br>';
					echo '<label for="re2" style="padding-left:10px">Los titulares de los datos o sus representantes legales (caso de menores de catorce años o incapacitados) podrán ejercer su derecho de oposición conforme al modelo específico para el ejercicio de este derecho, disponible en https://www.aragon.es/en/tramitador/-/tramite/proteccion-datos-ejercicio-derecho-oposicion.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En caso de ejercicio de su derecho de oposición motivada, deberá aportar el certificado volante de empadronamiento expedido por el órgano municipal correspondiente.</label><br>';
		echo '</div>';
echo '</div>';
###############################################################---------------OPCIONAL----------------##############################################################################
#comprbamos si hay hermanos
if($datos['num_hbaremo']>=1)
   $hns='checked';
else  $hns='';

$datoshermanosbaremo1=$datos['hermanos_datos_baremo1'];
$fnachermanosbaremo1=$datos['hermanos_fnacimiento_baremo1'];
$cursohermanosbaremo1=$datos['hermanos_curso_baremo1'];
$nivelhermanosbaremo1=$datos['hermanos_nivel_educativo_baremo1'];

$datoshermanosbaremo2=$datos['hermanos_datos_baremo2'];
$fnachermanosbaremo2=$datos['hermanos_fnacimiento_baremo2'];
$cursohermanosbaremo2=$datos['hermanos_curso_baremo2'];
$nivelhermanosbaremo2=$datos['hermanos_nivel_educativo_baremo2'];

$datoshermanosbaremo3=$datos['hermanos_datos_baremo3'];
$fnachermanosbaremo3=$datos['hermanos_fnacimiento_baremo3'];
$cursohermanosbaremo3=$datos['hermanos_curso_baremo3'];
$nivelhermanosbaremo3=$datos['hermanos_nivel_educativo_baremo3'];

$datoshermanosbaremo4=$datos['hermanos_datos_baremo4'];
$fnachermanosbaremo4=$datos['hermanos_fnacimiento_baremo4'];
$cursohermanosbaremo4=$datos['hermanos_curso_baremo4'];
$nivelhermanosbaremo4=$datos['hermanos_nivel_educativo_baremo4'];



echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b>2. DOCUMENTACION CARACTER OPCIONAL</b>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.1. Existencia de hermanos matriculados en el centro</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
echo '<input type="checkbox" name="re1" value="re1" '.$hns.'>';
echo '<label for="re2">El alumno/a tiene hermanos/as matriculados/as en el centro al que dirige esta solicitud, o en un centro de educación infantil, primaria o secundaria de la misma zona del centro al que se dirige la solicitud (Zonificación de infantil y primaria), y que van a continuar con tal condición en el curso académico para el que se solicita plaza. (Especificar, en su caso).</label><br>';
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
           <td contenteditable="true">'.$datoshermanosbaremo1.'</td>
           <td contenteditable="true">'.$fnachermanosbaremo1.'</td>
           <td contenteditable="true">'.$cursohermanosbaremo1.'</td>
           <td contenteditable="true">'.$nivelhermanosbaremo1.'</td>
         </tr>
         <tr>
           <td contenteditable="true">'.$datoshermanosbaremo2.'</td>
           <td contenteditable="true">'.$fnachermanosbaremo2.'</td>
           <td contenteditable="true">'.$cursohermanosbaremo2.'</td>
           <td contenteditable="true">'.$nivelhermanosbaremo2.'</td>
         </tr>
         <tr>
           <td contenteditable="true">'.$datoshermanosbaremo3.'</td>
           <td contenteditable="true">'.$fnachermanosbaremo3.'</td>
           <td contenteditable="true">'.$cursohermanosbaremo3.'</td>
           <td contenteditable="true">'.$nivelhermanosbaremo3.'</td>
         </tr>
         <tr>
           <td contenteditable="true">'.$datoshermanosbaremo4.'</td>
           <td contenteditable="true">'.$fnachermanosbaremo4.'</td>
           <td contenteditable="true">'.$cursohermanosbaremo4.'</td>
           <td contenteditable="true">'.$nivelhermanosbaremo4.'</td>
         </tr>
       </tbody>
     </table>';
echo '</div>';

#FILA_________________________________________________________________________________________________________________________________________________________
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.2. Proximidad domiciliaria</b>';		
echo '</div>';

$checkedlaboral=$checkedfamiliar='';
if($datos['baremo_validar_proximidad_domicilio']==1){
   if($datos['baremo_proximidad_domicilio']=='dlaboral') 
      $checkedlaboral='checked';
   elseif($datos['baremo_proximidad_domicilio']=='dfamiliar') 
      $checkedfamiliar='checked';
}
echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkedfamiliar.'>';
					echo '<label for="re1" style="padding-left:10px">Acreditación del domicilio familiar</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
					echo '<label for="re2" style="padding-left:10px">  La Administración Pública de la Comunidad Autónoma de Aragón realizará las consultas necesarias para comprobar la exactitud de los datos aportados.</label><br>';
					echo '<label for="re2" style="padding-left:10px"> En particular consultará la verificación y consulta de datos de residencia con fecha de última variación padronal (Instituto Nacional de Estadística).</label><br>';
					echo '<label for="re2" style="padding-left:10px">Los titulares de los datos o sus representantes legales (caso de menores de catorce años o incapacitados) podrán ejercer su derecho de oposición conforme al modelo específico para el ejercicio de este derecho, disponible en https://www.aragon.es/en/tramitador/-/tramite/proteccion-datos-ejercicio-derecho-oposicion.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En caso de ejercicio de su derecho de oposición motivada, deberá aportar el certificado volante de empadronamiento expedido por el órgano municipal correspondiente.</label><br>';
		echo '</div>';
echo '</div>';

#FILA_________________________________________________________________________________________________________________________________________________________
echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkedlaboral.'>';
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
#FILA_________________________________________________________________________________________________________________________________________________________
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.3. Existencia de progenitores o representantes legales que trabajen en el centro</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
	echo '<div class="col-12" style="display:flex">';
      echo '<input type="checkbox" name="re1" value="re1" '.$hns.'>';
	   echo '<label for="re2" style="padding-left:10px">El alumno/a tiene hermanos/as matriculados/as en el centro al que dirige esta solicitud, o en un centro de educación infantil, primaria o secundaria de la misma zona del centro al que se dirige la solicitud (Zonificación de infantil y primaria), y que van a continuar con tal condición en el curso académico para el que se solicita plaza. (Especificar, en su caso).</label><br>';
   echo '</div>';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo  '<table class="table table-bordered">
       <thead>
         <tr style="color:white">
           <th>Apellidos y Nombre</th>
           <th>DNI</th>
           <th>Centro educativo actual</th>
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

#FILA_________________________________________________________________________________________________________________________________________________________
if($datos['baremo_validar_renta_inferior']==1){
      $checkedminimovital='checked';
}
   $checkedminimovital='';
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.4. Rentas de la unidad familiar</b>';		
echo '</div>';
echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkedminimovital.'>';
					echo '<label for="re1" style="padding-left:10px">Ingreso Mínimo Vital o Renta Social.</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
			echo '<div class="row" style="padding-top:10px;width:100%">';
				echo '<div class="col-1">';
					echo '<input type="checkbox" name="re1" value="re1">';
				echo '</div>';
				echo '<div class="col">';
					echo '<label for="re2">Documento que acredite que está percibiendo una renta social o Ingreso Mínimo Vital.</label><br>';
				echo '</div>';
		   echo '</div>';
		echo '</div>';
echo '</div>';
#FILA_________________________________________________________________________________________________________________________________________________________
if($datos['baremo_validar_discapacidad']==1){
      $checkeddiscapacidad='checked';
}
else
   $checkeddiscapacidad='';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.5. Concurrencia de discapacidad, con un grado igual o superior al 33%, en el alumno/a o en alguno de sus progenitores, representantes legales o  hermanos/as</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkeddiscapacidad.'>';
		         echo '<label for="re2">El alumno/a o alguno de sus progenitores, representantes legales o hermanos/as, tiene reconocido mediante dictamen emitido por Organismo público competente, un grado de discapacidad igual o superior al 33%.</label><br>';
         echo '<div class="row" style="padding-top:10px;width:100%">';
            echo  '<table class="table table-bordered">
                <thead>
                  <tr style="color:white">
                    <th>Apellidos y Nombre</th>
                    <th>Fecha Nacimiento</th>
                    <th>DNI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                  </tr>
                  <tr>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                  </tr>
                </tbody>
              </table>';
         echo '</div>';
		echo '</div>';
		echo '<div class="col-6">';
					echo '<label for="re2" style="padding-left:10px">  La Administración Pública de la Comunidad Autónoma de Aragón realizará las consultas necesarias para comprobar la exactitud de los datos aportados.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En particular consultará el requisito de edad (Instituto Nacional de Estadística o Registro Civil).</label><br>';
					echo '<label for="re2" style="padding-left:10px">Los titulares de los datos o sus representantes legales (caso de menores de catorce años o incapacitados) podrán ejercer su derecho de oposición conforme al modelo específico para el ejercicio de este derecho, disponible en https://www.aragon.es/en/tramitador/-/tramite/proteccion-datos-ejercicio-derecho-oposicion.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En caso de ejercicio de su derecho de oposición motivada, deberá aportar el certificado volante de empadronamiento expedido por el órgano municipal correspondiente.</label><br>';
		echo '</div>';
echo '</div>';


#FILA_________________________________________________________________________________________________________________________________________________________
if($datos['baremo_validar_tipo_familia']==1){
      $checkedtipofamilia='checked';
      $checkedespecialfam=$checkedgeneralfam=$checkedespecialmon=$checkedgeneralmon='';

   if($datos['baremo_tipo_familia']=='numerosa_general')
   {
      if(strpos($datos['baremo_tipo_familia'],'especial')!='FALSE'){
         $checkedespecialfam='checked';
      }  
      else
         $checkedgeneralfam='checked';
   }
   elseif($datos['baremo_tipo_familia']=='monoparental_general')
   {
      if(strpos($datos['baremo_tipo_familia'],'especial')!='FALSE'){
         $checkedespecialmon='checked';
      }  
      else
         $checkedgeneralmon='checked';
   }
   
}
else
   $checkedtipofamilia='';
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.6. Pertenencia a familia numerosa</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkedtipofamilia.' >';
					echo '<label for="re1" style="padding-left:10px">La unidad familiar tiene la condición de familia numerosa</label><br>';
         echo '<div class="row" style="padding-top:10px;width:100%">';
               echo '<div class="col-6">';
                        echo '<input type="checkbox" name="re1" value="re1" '.$checkedgeneralfam.'>';
                        echo '<label for="re1" style="padding-left:10px">General</label><br>';
		         echo '</div>';
               echo '<div class="col-6">';
                        echo '<input type="checkbox" name="re1" value="re1" '.$checkedespecialfam.'>';
                        echo '<label for="re1" style="padding-left:10px">Especial</label><br>';
		         echo '</div>';
		   echo '</div>';
		echo '</div>';
		echo '<div class="col-6">';
					echo '<label for="re2" style="padding-left:10px">  La Administración Pública de la Comunidad Autónoma de Aragón realizará las consultas necesarias para comprobar la exactitud de los datos aportados.</label><br>';
					echo '<label for="re2" style="padding-left:10px"> En particular consultará el certificado de fmailia numerosa (Aragón/CCAA).</label><br>';
					echo '<label for="re2" style="padding-left:10px">Los titulares de los datos o sus representantes legales (caso de menores de catorce años o incapacitados) podrán ejercer su derecho de oposición conforme al modelo específico para el ejercicio de este derecho, disponible en https://www.aragon.es/en/tramitador/-/tramite/proteccion-datos-ejercicio-derecho-oposicion.</label><br>';
					echo '<label for="re2" style="padding-left:10px">En caso de ejercicio de su derecho de oposición motivada, deberá aportar el certificado volante de empadronamiento expedido por el órgano municipal correspondiente.</label><br>';
		echo '</div>';
echo '</div>';

#FILA_________________________________________________________________________________________________________________________________________________________
echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.7. Pertenencia a familia monoparental</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re1" style="padding-left:10px">La unidad familiar tiene la condición de familia numerosa</label><br>';
         echo '<div class="row" style="padding-top:10px;width:100%">';
               echo '<div class="col-6">';
                        echo '<input type="checkbox" name="re1" value="re1" '.$checkedgeneralfam.'>';
                        echo '<label for="re1" style="padding-left:10px">General</label><br>';
		         echo '</div>';
               echo '<div class="col-6">';
                        echo '<input type="checkbox" name="re1" value="re1" '.$checkedespecialfam.'>';
                        echo '<label for="re1" style="padding-left:10px">Especial</label><br>';
		         echo '</div>';
		   echo '</div>';
		echo '</div>';
		echo '<div class="col-6">';
               echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re2" style="padding-left:10px">Documento acreditativo de familia monoparental.</label><br>';
		echo '</div>';
echo '</div>';
#FILA_________________________________________________________________________________________________________________________________________________________
if($datos['baremo_validar_acogimiento']==1){
      $checkedacogimiento='checked';
}
echo '<div class="row" style="padding-top:10px;width:100%">';
echo '______________________________________________________________________________________________________________________________________________________________________________________________________';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.8. situación de acogimiento familiar</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkacogimiento.' >';
					echo '<label for="re1" style="padding-left:10px">Condición de acogimiento familiar</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
               echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re2" style="padding-left:10px">Certificados emitido por las Subdelegaciones de Protección a la Infancia y Tutela del I.A.S.S.</label><br>';
		echo '</div>';
echo '</div>';
#FILA_________________________________________________________________________________________________________________________________________________________
if($datos['baremo_validar_genero']==1){
      $checkedgenero='checked';
}

echo '<div class="row" style="padding-top:10px;width:100%">';
echo '______________________________________________________________________________________________________________________________________________________________________________________________________';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.9. Condición de víctima de violencia de género</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1" '.$checkedgenero.' >';
					echo '<label for="re1" style="padding-left:10px">Condición de víctima de violenacia de género</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
               echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re2" style="padding-left:10px">Sentencia condenatoria o documento equivalente.</label><br>';
		echo '</div>';
echo '</div>';
#FILA_________________________________________________________________________________________________________________________________________________________
$checkedterrorismo='';
if($datos['baremo_validar_terrorismo']==1){
      $checkedterrorismo='checked';
}

echo '<div class="row" style="padding-top:10px;width:100%">';
echo '______________________________________________________________________________________________________________________________________________________________________________________________________';
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
   echo '<b>2.10. Condición de víctima de terrorismo</b>';		
echo '</div>';

echo '<div class="row" style="padding-top:10px;width:100%">';
		echo '<div class="col-6">';
					echo '<input type="checkbox" name="re1" value="re1"  '.$checkedterrorismo.' >';
					echo '<label for="re1" style="padding-left:10px">Condición de víctima de terrorismo</label><br>';
		echo '</div>';
		echo '<div class="col-6">';
               echo '<input type="checkbox" name="re1" value="re1">';
					echo '<label for="re2" style="padding-left:10px">Certificado o resolución de víctima de terroristo expedido por el Ministerio de Interior.</label><br>';
		echo '</div>';
echo '</div>';


#FILA_________________________________________________________________________________________________________________________________________________________
echo '<div class="row" style="padding-top:10px;width:100%">';
echo '______________________________________________________________________________________________________________________________________________________________________________________________________';
echo '</div>';
echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b><h2> INFORMACIÓN SOBRE GRATUIDAD DE ENSEÑANZAS</h2></b>';
echo '</div>';
echo '<div class="row" style="padding-top:30px">';
echo 'Los abajo firmantes declaran estar informados de que en ningún caso los centros públicos y los privados concertados podrán percibir cantidades por las enseñanzas de carácter gratuito, imponer la obligación de hacer aportaciones a fundaciones o a asociaciones ni establecer servicios obligatorios asociados a las enseñanzas que requieran aportación económica.<br>';
echo '</div>';
echo '<div class="row" style="padding-top:70px">';
	echo '<div class="col-6">';
		echo '<div class="row" style="padding-top:20px">';
			echo '<div class="col">En........................................................a..............de 2021<br><br></div>';
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
   echo 'El responsable del tratamiento de tus datos personales es la Dirección General de Planificación y Equidad.
<br>La finalidad de este tratamiento es la gestión de la escolarización de alumnado en guarderías infantiles y centros docentes no universitarios de titularidad del Gobierno de Aragón, así como en centros docentes no universitarios privados concertados de Aragón.
<br>La legitimación para realizar el tratamiento de datos la da el cumplimiento de una obligación legal.
<br>Sus datos personales no se comunicarán a terceros destinatarios salvo obligación legal.
<br>Podrá ejercer sus derechos de acceso, rectificación, supresión y portabilidad de los datos o de limitación y oposición a su tratamiento, así como a no ser objeto de decisiones individuales automatizadas a través de la sede electrónica de la Administración de la Comunidad Autónoma de Aragón con los formularios normalizados disponibles.
<br>Podrá consultar la información adicional y detallada sobre esta actividad de tratamiento en https://aplicaciones.aragon.es/notif_lopd_pub/details.action?fileId=59';
echo '</div>';

#FILA_________________________________________________________________________________________________________________________________________________________
echo '<div class="row" style="padding-top:10px;width:100%">';
echo '______________________________________________________________________________________________________________________________________________________________________________________________________';
echo '</div>';
echo '<div class="row" style="padding-top:30px;width:100%">';
echo '<b><h2> INSTRUCCIONES PARA CUMPLIMENTAR LA SOLICITUD</h2></b>';
echo '</div>';

echo '<div class="row" style="padding-top:10px">';
   echo 
'(1) Para el alumnado con doble nacionalidad, si tiene la española, sólo se pondrá ésta.';
echo '</div>';
echo '<div class="row" style="padding-top:10px">';
   echo 
'(2) Se presentará una única solicitud dirigida al centro en el que solicitan plaza en primera opción. Si se presenta más de una solicitud serán excluidas del procedimiento.';
echo '</div>';
echo '<div class="row" style="padding-top:10px">';
   echo 
'(3) En caso de que se desee la baremación de estos criterios se deberá marcar en la solicitud: En los apartados 2.1 y 2.3 no será necesario presentar documento justificativa de esta circunstancia. En los apartados 2.2.proximidad al domicilio familiar, 2.5 y 2.6 la Administración Pública de la Comunidad Autónoma de Aragón realizará las consultas necesarias para comprobar la exactitud de los datos aportados o en caso de ejercicio de su derecho de oposición motivada, deberá aportar el certificado correspondiente. En los apartados 2.2.proximidad al domicilio laboral, 2.4, 2.7, 2.8, 2.9 y 2.10 el solicitante deberá aportar la documentación correspondiente.';
echo '</div>';
echo '<div class="row" style="padding-top:10px">';
   echo 
'(4) La opción señalada será la tenida en cuenta a efectos de aplicación del baremo, tanto para el primer centro solicitado como para el resto de los centros alternativos manifestados en esta solicitud.  Asimismo, será la tenida en cuenta para el caso de las adjudicaciones de los Servicios Provinciales y de posibles cambios de centro durante el curso escolar 2021/22.';
echo '</div>';

echo '<div class="row" style="padding-top:10px">';
   echo 
'(5) Acreditación del domicilio laboral:<br>                                                       
      a. Trabajadores/as por cuenta ajena:  Será necesario presentar el Anexo VI a) de la Orden, además de certificación de Vida Laboral o documento oficial equivalente.                                                                                                                <br>
      b. Trabajadores/as por cuenta propia:  Será necesario presentar el Anexo VI b) de la Orden, además de (elegir una de las siguientes opciones):                                                                                                                                        <br>                         
                  <br>                                                                                                                                                      
               -  Copia autenticada de la correspondiente licencia de apertura expedida por el Ayuntamiento respectivo.                                                                                                                                                      <br> 
               -  Copia del alta en la Seguridad Social, en el régimen correspondiente o documento oficial equivalente.                                                                                                                                                       <br>
               -  Copia del documento que acredite estar de alta en el Impuesto de Actividades Económicas en el que conste el lugar donde se desarrolle dicha actividad. (Modelos 036 o 037, Declaraciones censales de alta o modificación en el censo de obligados tributarios).';
echo '</div>';
echo '<div class="row" style="padding-top:10px">';
   echo 
'(6)  El progenitor solicitante se compromete a informar al otro progenitor de la presentación de la solicitud, salvo imposibilidad material, privación o limitación de patria potestad por disposición judicial. El progenitor solicitante asume la responsabilidad que pueda derivarse de la solicitud presentada.';
echo '</div>';

echo '<div class="row" style="padding-top:10px">';
   echo 
'Advertencia.-<br><br>  1) Quedarán excluidos del procedimiento los siguientes casos: <br><br>                                                                                                                                                                   
                   - Cuando se presenta más de una solicitud. <br>                                                                                                                                                   
                   - Cuando se presente fuera del plazo de presentación de solicitudes.
                  <br>
                   - Cuando se aprecie la existencia de indicios razonados y suficientes de falsedad de la documentación aportada por el interesado o de los datos reflejados en la misma.   <br>                                                                                                                                                                       
                <br>  2) Este impreso de solicitud es un modelo a efectos informativos. El documento de solicitud se cumplimenta y presenta en forma telemática dentro de los plazos indicados en convocatoria.';
echo '</div>';

?>
