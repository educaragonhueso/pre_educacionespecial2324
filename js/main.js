$(document).ready(function(){
//VARIABLE GLOBALES
var vficheros=[];
var form_data = new FormData();
var directoriobase=edicion;
var urlbase=url_base+edicion;
var urlbasesol=url_base+edicion;
/////////////////////////////////////////////////////////////////////////////////
//FUNCIN DE PRUEBA PARA SUBIR ARCHIVOS, DE MOMENTO SIN USAR
$('body').on('click', '#subirdoc', function(e){
      let url='../'+directoriobase+'/scripts/ajax/guardar_solicitud.php';
      let formData = new FormData()
      formData.append('file', this.files)
     fetch(url, {
       method: 'POST',
       body: fformData
     })
      .then(console.log("aañadido fichero"))
     .catch(() => {console.log("ERROR") /* Error. Inform the user */ })
  
});

//FUNCION GRABACION Y ACTUALIZACION SOLICITUD
$('body').on('click', '.send', function(e)
{
   //obtenemos datos necesarios para grabar  
   var vestado_convocatoria=$('#estado_convocatoria').attr("value");
   var vid_centro=$('#id_centro').text();
   var vrol=$('#rol').attr("value");
   var vtoken=$('#token').attr("value");
   var vid_alumno=$('#id_alumno').attr("value");
   var vmodo=$(this).text();                                     //modo: si es grabar o actualizar
   var vid=$(this).attr("data-idal");
   var vptsbaremo=$("#id_puntos_baremo_totales"+vid).text();
   var fsolicitud=$('#fsolicitud').serialize();
   //estudios del alumnos y posibles hermanos para ver si encaja con la fecha introducida
   var vtipoestudios = $('#tipoestudios :selected').val();
   var valid='1';
   var mensaje="";
   
   if(typeof vmodo === 'undefined' || vmodo === null) 
   {
      //si es para actualizar cargamos el token desde el boton 
      vmodo="ACTUALIZAR SOLICITUD";
      var vtoken=$(this).attr("id");
   }
   else if(vmodo === 'ACTUALIZAR SOLICITUD' & vrol!='anonimo' ) 
   {
      //si es una solicitud nueva q se actualiza, cargamos el token desde el boton 
      var vtoken=$(this).attr("id");
   }
   var validar_hermanos_discapacidad=validarHermanosDiscapacidad(fsolicitud);
   console.log("resultado validar hermanos: "+validar_hermanos_discapacidad);
   if(validar_hermanos_discapacidad!=1)
   {
   	mensaje=validar_hermanos_discapacidad;
   	$.alert({
       		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
	   return;
   } 

   validar_hermano=validarHermanoAdmision(fsolicitud);
   console.log("VALIDADO FORMULARIO HERMANOS ADMISION, RESULTADO: "+validar_hermano);
   validar_hermano_baremo=validarHermanoBaremo(fsolicitud);
   console.log("VALIDADO FORMULARIO HERMANOS BAREMO, RESULTADO: "+validar_hermano_baremo);
   
   if(validar_hermano!=1)
   {
   	mensaje=validar_hermano;
   	$.alert({
       		title: 'FORMULARIO INCOMPLETO, ERROR EN HERMANOS SOLICITUD CONJUNTA',
        		content: mensaje
    			});
	   return;
   } 
   if(validar_hermano_baremo!=1)
   {
   	mensaje=validar_hermano_baremo;
   	$.alert({
       		title: 'FORMULARIO INCOMPLETO, ERROR EN HERMANOS DE BAREMO',
        		content: mensaje
    			});
	   return;
   } 
   
   valid=validarFormulario(fsolicitud,vid,vtipoestudios);
   console.log("VALIDADO FORMULARIO RESULTADO: "+valid);
   if(valid.indexOf('fnac')!=-1)
   {
   	mensaje="Debes incluir una fecha de nacimiento";
   	$.alert({
       		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
		$('input[name=dni_alumno]').focus();	
	return;
  }
  if(valid.indexOf('noebo')!=-1)
  {
			mensaje="Si el alumno es EBO deben tener entre 3 y 17 años, ambos inclusive ";
    		$.alert({
        		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
			$('input[name=tipoestudios]').focus();	
   return;
  }
  if(valid.indexOf('notva')!=-1)
  {
			mensaje="Si el alumno es TVA debe tener entre 18 y 20 años, ambos inclusive ";
    		$.alert({
        		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
			$('input[name=tipoestudios]').focus();	
   return;
  }
  if(valid.indexOf('nodos')!=-1)
  {
			mensaje="El alumno no tiene dos años ";
    		$.alert({
        		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
			$('input[name=tipoestudios]').focus();	
   return;
  }
  if(valid.indexOf('Fecha nacimiento-fnac')!=-1)
  {
		mensaje=mensaje+valid.split('-')[0];
		mensaje="Debes incluir una fecha de nacimiento para el alumno y sus hermanos, si los hay";
		$('input[name='+valid.split('-')[1]+']').focus();	
    		$.alert({
        		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
	return 0;
	}
  if(valid.indexOf('14 años')!=-1)
  {
      $.alert({
         title: 'FORMULARIO INCOMPLETO',
         content: valid
         });
     return;
  }
   if(valid!=1)
   {
		mensaje=mensaje+valid.split('-')[0];
		$('input[name='+valid.split('-')[1]+']').focus();	
    		$.alert({
        		title: 'FORMULARIO INCOMPLETO',
        		content: mensaje
    			});
	return 0;

   } 
	else
	{
      $.ajax({
         type: "POST",
         data: {fsol:fsolicitud,idsol:vid,modo:vmodo,id_centro:vid_centro,ptsbaremo:vptsbaremo,rol:vrol,estado_convocatoria:vestado_convocatoria,id_alumno:vid_alumno,token:vtoken},
         url:'../'+edicion+'/scripts/ajax/guardar_solicitud.php',
         success: function(data) {
         if(data.indexOf('1062')!=-1) 
         {
            error='El dni del tutor ya existe';
            $('input[name=dni_tutor1]').focus();	
            $.alert({
               title: 'ERROR CREANDO SOLICITUD',
               content: error
               });
         }
         else if(data.indexOf('no_nombre_usuario')!=-1) 
         {
            error='debes introducir un nombre de usuario';
            $('input[name=dni_tutor1]').focus();	
            $.alert({
               title: 'ERROR CREANDO SOLICITUD',
               content: error
               });
         }
         else if(data.indexOf('centroorigen')!=-1) 
         {
            error='Debes introducir un centro de origen válido';
            $('input[name=id_centro_estudios_origen]').focus();	
            $.alert({
               title: 'ERROR CREANDO SOLICITUD',
               content: error
               });
         }
         else{
            if(vmodo=='GRABAR SOLICITUD')
            {
               if(vrol.indexOf('anonimo')!=-1)
               {	
                  if(data.indexOf('ERROR')!=-1) 
                  {
                     $.alert({
                        title: data+'</b>',
                        content: ''
                        });
                     return;
                  }
                  else
                  {
                     //sepearamos el id del alumno del la clva
                     var id_alumno=data.split(":")[0];
                     var clave_alumno=data.split(":")[1];
                     var token_alumno=data.split(":")[2];
            
                     var id_hermano1=data.split(":")[3];
                     var clave_hermano1=data.split(":")[4];
                     var token_hermano1=data.split(":")[5];
            
                     var id_hermano2=data.split(":")[6];
                     var clave_hermano2=data.split(":")[7];
                     var token_hermano2=data.split(":")[8];
            
                     var id_hermano3=data.split(":")[9];
                     var clave_hermano3=data.split(":")[10];
                     var token_hermano3=data.split(":")[11];

                     var enlace_solicitud=urlbasesol+"/index.php?token="+token_alumno;
                     var mensaje='Solicitud creada correctamente. Recibirás un correo con un enlace para poder modificarla. ';
                     var textoenlace='<br><b><a href="'+enlace_solicitud+'" class="btn_enlace">Enlace solicitud</a></b>'; 
                     if(token_hermano1!==undefined)
                     {
                        var enlace_solicitud_h1=urlbasesol+"/index.php?token="+token_hermano1;
                        textoenlace+='<a class="btn_enlace" href="'+enlace_solicitud_h1+'"<br>Enlace para el primer hermano<br></b></a>';
                     }
                     if(token_hermano2!==undefined)
                     {
                        var enlace_solicitud_h2=urlbasesol+"/index.php?token="+token_hermano2;
                        textoenlace+='<a class="btn_enlace" href="'+enlace_solicitud_h2+'"<br>Enlace para el segundo hermano<br></b></a>';
                     }
                     if(token_hermano3!==undefined)
                     {
                        var enlace_solicitud_h3=urlbasesol+"/index.php?token="+token_hermano3;
                        textoenlace+='<a class="btn_enlace" href="'+enlace_solicitud_h3+'"<br>Enlace para el segundo hermano<br></b></a>';
                     }
                     $.alert({
                     title: 'SOLICITUD GUARDADA CORRECTAMENTE.',
                     content: mensaje
                     });
                     //añadimos boton para imprimir
                     var bimp= $('<a target="_blank" href="imprimirsolicitud.php?id='+id_alumno+'"><input class="btn btn-primary imprimirsolicitud" style="background-color:brown;padding-left:20px" type="button" value="Vista Previa Impresion Documento"/></a>');
                     //id de alumno nuevo
                     $('#id_alumnonuevo').attr("value",id_alumno);
                     $('.send').text("ACTUALIZAR SOLICITUD");
                     $('.send').after(textoenlace);
                     $('.send').after(bimp);
                     $('#hermanos_admision_token1').val(token_hermano1);
                  }
            return;
            }
            else
                  $('.send').text("ACTUALIZAR SOLICITUD");
            if(data.indexOf('ERROR')!=-1){ alert(data);return ;}
            else {
                     $('#sol_table').find('tbody').prepend(data);
                        $.alert({
                           title: 'SOLICITUD GUARDADA CORRECTAMENTE',
                           content: ''
                           });
                  }
                  $('#fnuevasolicitud').remove();
            }
            else if(vmodo=='ACTUALIZAR SOLICITUD')
            {
               console.log(data); 
               if(data.indexOf("OK")!=-1)
               {
                  var mensaje="";
                  var aresp=data.split(":");
                  if(aresp.length>=2)
                  {      
                     var id_hermano1=data.split(":")[0];
                     var clave_hermano1=data.split(":")[1];
                     var token_hermano1=data.split(":")[2];
            
                     var id_hermano2=data.split(":")[3];
                     var clave_hermano2=data.split(":")[4];
                     var token_hermano2=data.split(":")[5];
            
                     var id_hermano3=data.split(":")[6];
                     var clave_hermano3=data.split(":")[7];
                     var token_hermano3=data.split(":")[8];

                     if(token_hermano1!==undefined)
                     {
                        var enlace_solicitud_h1=urlbasesol+"/index.php?token="+token_hermano1;
                        //mensaje+='<br>Enlace para el primer hermano: <br><b> '+enlace_solicitud_h1+'</b>';
                     }
                     if(token_hermano2!==undefined)
                     {
                        var enlace_solicitud_h2=urlbasesol+"/index.php?token="+token_hermano2;
                        //mensaje='<br>Enlace para el segundo hermano: <br><b> '+enlace_solicitud_h2+'</b>';
                     }
                     if(token_hermano3!==undefined)
                     {
                        var enlace_solicitud_h3=urlbasesol+"/index.php?token="+token_hermano3;
                        //mensaje+='<br>Enlace para el tercer hermano: <br><b> '+enlace_solicitud_h3+'</b>';
                     }
                  }
                  $.alert({
                  title: 'SOLICITUD ACTUALIZADA CORRECTAMENTE.',
                  content: mensaje
                  });
               }
               else
                  $.alert({
                  title: 'ERROR ACTUALIZANDO',
                  content: 'Revisa el centro solicitado o Contacta con el administrador lhueso@aragon.es'
                  });
            }
            }
         },
            error: function (request, status, error) {
                        alert(error);
                  }
	   });//fin llamada ajax
	}
});

//BOTON ELIMINAR SOLICITUD
$('body').on('click', '.beliminarsolicitud', function(e)
{
   alert("La solicitud se va a eliminar, estás seguro?");
   var vtoken=$("#token").val();
   console.log("ELIMINANDO SOLICITUD, TOKEN: "+vtoken);
   if(vtoken=='')
   {
      alert("La solicitud aún no se ha grabado");
      return;
   }
   $.ajax({
     method: "POST",
     data: { token:vtoken},
     url:'../'+directoriobase+'/scripts/ajax/eliminar_solicitud.php',
         success: function(data) 
         {
            if(data.indexOf('ERROR')!=-1){ alert("HA HABIDO UN ERROR ELIMINANDO");return;}
            else alert("SOLICITUD ELIMINADA");
         },
         error: function() 
            {
           alert('Error borrando solicitud!');
         }
      });
});

var botoncontrol="<button id='' type='button' class='btn btn-outline-dark'>Validar baremo</button>";
//METODOS DE CALCULO DE SORTEO

$('body').on('click', '#boton_asignar_numero', function(e){
var answer = window.confirm("Estas seguro? Esta operacion solo se puede realizar una vez")
if (!answer) 
{
   console.log("op cancelada");
   return;
}
var vrol=$('#rol').attr("value");
var vestado_convocatoria=$('#estado_convocatoria').attr("value");
var vidcentro=$('#id_centro').text();
	$.ajax({
	  method: "POST",
	  data: {asignar:'1',id_centro:vidcentro,rol:vrol,estado_convocatoria:vestado_convocatoria},
	  url:'../'+directoriobase+'/scripts/ajax/realizar_sorteo.php',
	      success: function(data) {
				$.alert({
					title: data,
				   content: ''
					});
   console.log("sorteo realizado");
            
				$('#num_sorteo').prop('disabled', false);
		},error: function (request, status, error) {
        alert(error);
    }
	});
});

//REALIZAR SORTEO

$('body').on('click', '#boton_realizar_sorteo', function(e){

var vid=$(this).attr("id");
var vrol=$('#rol').attr("value");
var vestadoconvocatoria=$('#estado_convocatoria').val();
var vidcentro=$('#id_centro').text();
var vsolicitudes=$(this).attr("data-solicitudes");
var vnum_sorteo=$('#num_sorteo').val();
var vnum_solicitudes=$('#num_solicitudes').val();
var isnum = /^\d+$/.test(vnum_sorteo);
if (!isnum) {
    alert('No es un numero');
return;
}
if (parseInt(vnum_solicitudes)<parseInt(vnum_sorteo) || parseInt(vnum_sorteo)<=0) {
    alert('Introduce un numero entre 1 y '+vnum_solicitudes);
return;
}
console.log("N SORTEO: "+vnum_sorteo);
	$.ajax({
	  method: "POST",
	  data: {id_centro:vidcentro,nsorteo:parseInt(vnum_sorteo),rol:vrol,estado_convocatoria:vestadoconvocatoria},
	  url:'../'+directoriobase+'/scripts/ajax/realizar_sorteo.php',
	      success: function(data) {
				if(data.indexOf('NO HAY VACANTES')!=-1)
				{
				$.alert({
					title: 'NO HAY VACANTES O NO HAY SOLCITUDES APTAS',
					content: ''
					});
				return;
				}
				else
				{
					$.alert({
						title: "SORTEO REALIZADO CON ÉXITO",
						content: ''
						});
					$('#num_sorteo').prop('disabled', true);
				}
		},
	      error: function() {
		alert('Problemas listando solicitud!');
	      }
	});
});

$('body').on('change', 'input[id*=email2]', function(e){
   var vid=$(this).attr("id");
   vid=vid.replace('email','');
   var email1=$("#email"+vid).val();
   var email2=$("#email2").val();
   //if(email1!=email2) alert('Los correos no coinciden');
   var vid =vid.substring(1, vid.length);
});
//METODOS DE CALCULO DE HERMANOS
function calcular_hermanos(id)
{
var total_hadmision=0;
var th1=$('#hermanos_datos_admision1'+id).val();
var th2=$('#hermanos_datos_admision2'+id).val();
var th3=$('#hermanos_datos_admision3'+id).val();

if(th1!='') total_hadmision++; 
if(th2!='') total_hadmision++; 
if(th3!='') total_hadmision++; 

return total_hadmision;
}

$('body').on('change', 'input[id*=hermanos_datos_admision]', function(e){
var vid=$(this).attr("id");
vid=vid.replace('hermanos_datos_admision','');
var vid =vid.substring(1, vid.length);
var nher=calcular_hermanos(vid);
$("#hermanosadmision"+vid).attr("value",calcular_hermanos(vid));
});

$('body').on('change', 'input[id*=num_hadmision],input[id*=num_hbaremo]', function(e){

var vid=$(this).attr("id");
vid=vid.replace('num_hadmision','');
vid=vid.replace('num_hbaremo','');
var nhermanosadmin=calcular_hermanos(vid);

$("#num_hadmision"+vid).attr('value',nhermanosadmin);
});

/*
$('body').on('click', 'input[type=radio][name*=conjunta]', function(e)
{
   console.log("pulsado conjunta");
   var val=$(this).attr("value");
   console.log(val);

   if(val=='no')
      $(this).attr('value','si');
   else
   {
      $(this).attr('value','no');
      //$("button[name=boton_baremo_validar_renta_inferior"+vid+"]").text('Validar renta')
      //$('#baremo_validar_renta_inferior'+vid).val('0');
   }
});
*/
//METODOS DE CALCULO DE BAREMO

//EVENTOS CHECK BAREMO
/////////////////////////////////////////////////////////////////////////////////////////////////

//Mostrar/ocultar domicilio laboral

$('body').on('change', 'input[type=radio][name=baremo_proximidad_domicilio]', function(e)
{
   var bar_def=recalcular_baremo();
   if($(this).attr("data-dom")=='laboral')
   {
      $("#calle_dlaboral").toggle('slow');
      $('#calle_dllimitrofe').hide('slow');
   }else if($(this).attr("data-dom")=='limitrofe')
   {
      $("#calle_dllimitrofe").toggle('slow');
      $('#calle_dlaboral').hide('slow');
   }
   else{
      $('#calle_dlaboral').hide('slow');
      $('#calle_dllimitrofe').hide('slow');
   }
});

$('body').on('change', 'input[type=checkbox][name=baremo_tutores_centro],input[type=checkbox][name=baremo_situacion_sobrevenida],input[type=checkbox][name=baremo_renta_inferior],input[type=checkbox][name=baremo_acogimiento],input[type=checkbox][name=baremo_genero],input[type=checkbox][name=baremo_terrorismo],input[type=checkbox][name=baremo_parto]', function(e)
{
   var valor=$(this).val();
   var vnombre=$(this).attr('name');
   console.log("VALOR: "+valor);
   console.log("VNOMBRE: "+vnombre);
   var bar_def=recalcular_baremo();
   idbaremo=vnombre.replace('baremo_',''); 
   if($(this).is(":checked")===false)
   {
      if(idbaremo.indexOf("tutores")!=-1)
         texto="Validar tutores trabajan centro";
      if(idbaremo.indexOf("situacion")!=-1)
         texto="Validar situacion sobrevenida";
      if(idbaremo.indexOf("renta")!=-1)
         texto="Validar renta";
      if(idbaremo.indexOf("acogimiento")!=-1)
         texto="Validar situación de acogimiento";
      if(idbaremo.indexOf("terrorismo")!=-1)
         texto="Validar víctima de terrorismo";
      if(idbaremo.indexOf("genero")!=-1)
         texto="Validar víctima de género";
      if(idbaremo.indexOf("parto")!=-1)
         texto="Validar parto múltiple";
      $('#baremo_validar_'+idbaremo).val('0');
      var idboton='boton_baremo_validar_'+idbaremo;
      $('[name='+idboton+']').text(texto);
      //mostramos boton agregar fichero
      $("#afbaremo_"+idbaremo).hide();
      if(vrol!='alumno' & vrol!='anonimo')
         $("#msg_comprobacion_"+idbaremo).show();
   }
   else
      $("#afbaremo_"+idbaremo).show();
});

//si pulsamos en 'ninguna' discapacidad no ponemos validacion
$('body').on('change', '[name=baremo_discapacidad_hermanos],[name=baremo_discapacidad_alumno]', function(e)
{
   var vname=$(this).attr("name");
   //var valorbaremo=$(this).attr("data-baremo");
   var cajadatosdisc=$(this).closest("div").next("div");
   console.log("comp disc");
   if(vname=='baremo_discapacidad_hermanos')
      $("#cajadatosdiscapacidad").slideToggle('slow');
   var bar_def=recalcular_baremo();
   $("#id_puntos_baremo").text(bar_def);
});

$('body').on('change', 'input[type=radio][name=baremo_tipo_familia_numerosa],input[type=radio][name=baremo_tipo_familia_monoparental]', function(e)
{
   var bar_def=recalcular_baremo();
   $("#id_puntos_baremo").text(bar_def);
});

$('body').on('change', 'input[id=hermanos_datos_baremo]', function(e){

//quitamos el primer caracter
var bar_def=recalcular_baremo();
$("#id_puntos_baremo").text(bar_def);
});

/////////////////////////////////////////////////////////////////////////////////////////////////
$('body').on('change', 'input[type=radio][name*=transporte]', function(e)
{
	var vid=$(this).attr("name");
	var vid=vid.replace('transporte','');
   console.log("transporte"+vid);
	var bar_def=recalcular_baremo(vid);

});

function recalcular_baremo(){
	var totalbaremo=0;
	var total_hbaremo=0;
	var total_baremo_validado=0;
   var puntos_conjunta=0;
   console.log("recalculando baremo");

   if($('#conjunta').is(':checked'))
      puntos_conjunta=4;
   
	var baremo1=$('input[name=baremo_proximidad_domicilio]:checked').attr("data-baremo");
	var baremo1_validado=$('#baremo_validar_proximidad_domicilio').val();

	var baremo2=$('input[id=baremo_tutores_centro]:checked').attr("data-baremo");
	var baremo2_validado=$('#baremo_validar_tutores_centro').val();

	var baremo3=$('input[id=baremo_renta_inferior]:checked').attr("data-baremo");
	var baremo3_validado=$('#msg_comprobacion_renta_inferior').text();

	var baremo4=$('input[name=baremo_tipo_familia]:checked').attr("data-baremo");
	var baremo4_validado=$('#baremo_validar_tipo_familia').val();

	var baremo5=$('input[id=baremo_acogimiento]:checked').attr("data-baremo");
	var baremo5_validado=$('#baremo_validar_acogimiento').val();
	
   var baremo6=$('input[id=baremo_genero]:checked').attr("data-baremo");
	var baremo6_validado=$('#baremo_validar_genero').val();

   var baremo7=$('input[id=baremo_terrorismo]:checked').attr("data-baremo");
	var baremo7_validado=$('#baremo_validar_terrorismo').val();

	var baremo8=$('input[name=baremo_discapacidad_alumno]:checked').attr("data-baremo");
	var baremo8_validado=$('#msg_comprobacion_discapacidad_alumno').text();
	
   var baremo9=$('input[name=baremo_discapacidad_hermanos]:checked').attr("data-baremo");
	var baremo9_validado=$('#msg_comprobacion_discapacidad_hermanos').text();
	
   var baremo10=$('input[id=baremo_parto]:checked').attr("data-baremo");
	var baremo10_validado=$('#baremo_validar_parto').val();
   
	var baremo11=$('input[name=baremo_tipo_familia_numerosa]:checked').attr("data-baremo");
	var baremo11_validado=$('#msg_comprobacion_familia_numerosa').text();

	var baremo12=$('input[name=baremo_tipo_familia_monoparental]:checked').attr("data-baremo");
	var baremo12_validado=$('#msg_comprobacion_familia_monoparental').text();

//   var baremo10=$('input[name=transporte'+id+']:checked').attr("value");
   var baremo_h1=$('#hermanos_nombre_baremo1').val();
	var baremo_h2=$('#hermanos_nombre_baremo2').val();
	var baremo_h3=$('#hermanos_nombre_baremo3').val();
	var baremo13_validado=$('#baremo_validar_hnos_centro').val();

	if(baremo1)
	{
		totalbaremo=totalbaremo+parseInt(baremo1);
		if(baremo1_validado==1) total_baremo_validado=total_baremo_validado+parseInt(baremo1);
	}
	if(baremo2)
	{
		totalbaremo=totalbaremo+parseInt(baremo2);
		if(baremo2_validado==1) {console.log("COMP TUT CENTRO POSTIVA "+baremo2); total_baremo_validado=total_baremo_validado+parseInt(baremo2);}
		else {console.log("COMP TUT CENTRO NEGATIVA "+baremo2);}
	}
	if(baremo3)
	{
		totalbaremo=totalbaremo+parseInt(baremo3);
		if(baremo3_validado.indexOf("POSITIVA")!=-1) {console.log("COMP POSITIVA "+baremo3);total_baremo_validado=total_baremo_validado+parseInt(baremo3);}
	}
	if(baremo4)
	{
		totalbaremo=totalbaremo+parseFloat(baremo4);
		if(baremo4_validado==1) 	total_baremo_validado=total_baremo_validado+parseFloat(baremo4);
	}
	if(baremo5)
	{
		totalbaremo=totalbaremo+parseFloat(baremo5);
		if(baremo5_validado==1) total_baremo_validado=total_baremo_validado+parseFloat(baremo5);
	}
	if(baremo6)
	{
		totalbaremo=totalbaremo+parseFloat(baremo6);
		if(baremo6_validado==1) total_baremo_validado=total_baremo_validado+parseFloat(baremo6);
	}
	if(baremo7)
	{
		totalbaremo=totalbaremo+parseFloat(baremo7);
		if(baremo7_validado==1) total_baremo_validado=total_baremo_validado+parseFloat(baremo7);
	}
	if(baremo8)
	{
		totalbaremo=totalbaremo+parseFloat(baremo8);
		if(baremo8_validado.indexOf("POSITIVA")!=-1) total_baremo_validado=total_baremo_validado+parseFloat(baremo8);
	}
	if(baremo9)
	{
		totalbaremo=totalbaremo+parseFloat(baremo9);
		if(baremo9_validado.indexOf("POSITIVA")!=-1) total_baremo_validado=total_baremo_validado+parseFloat(baremo9);
	}
	if(baremo10)
	{
		totalbaremo=totalbaremo+parseFloat(baremo10);
		if(baremo10_validado==1) total_baremo_validado=total_baremo_validado+parseFloat(baremo10);
	}
	if(baremo11)
	{
		totalbaremo=totalbaremo+parseFloat(baremo11);
		if(baremo11_validado.indexOf("POSITIVA")!=-1) total_baremo_validado=total_baremo_validado+parseFloat(baremo11);
      console.log("NUMEROSA marcado para el baremo");
	}
	if(baremo12)
	{
		totalbaremo=totalbaremo+parseFloat(baremo12);
		if(baremo12_validado.indexOf("POSITIVA")!=-1) total_baremo_validado=total_baremo_validado+parseFloat(baremo12);
	}
	//calculo baremo de hermanos en el centro
	if($('#num_hbaremo').is(':checked'))
	{
      console.log("marcado hermanos para el baremo");
	   if(baremo_h1.length>=2 | baremo_h2.length>=2 | baremo_h3.length>=2) 
      {
		   total_hbaremo=total_hbaremo+8;
      }
	   if(baremo13_validado==1)
	   	total_baremo_validado=total_baremo_validado+parseFloat(total_hbaremo);	
	}
	else
		total_hbaremo=0;
	totalbaremo=totalbaremo+total_hbaremo+puntos_conjunta;
	total_baremo_validado=total_baremo_validado+puntos_conjunta;

	$("#id_puntos_baremo_totales").text(totalbaremo);
	$("#id_puntos_baremo_validados").text(total_baremo_validado);
	//cambiamos valore sen campos de formulario
	$("#btotales").val(totalbaremo);
	$("#bvalidados").val(total_baremo_validado);
	comprobar_baremo(totalbaremo,total_baremo_validado);

return totalbaremo;
}
//METODOS PARA RECALCULAR BAREMO AL PONER HERMANOS
$('body').on('focusout', 'input[name*=hermanos_nombre_baremo]', function(e)
{
   var rb=recalcular_baremo();
});
//METODOS VALIDACION DE BAREMO
/////////////////////////////////////////////////////////////////////////////////////////////////
$('body').on('change', '#id_centro_estudios_origen', function(e){
   var centro=$(this).val();
   console.log("CENTRO: "+centro);
   if(centro.indexOf('*')==-1)
   {
      console.log("DESMARCANDO RESERVA");
      $("input[name='reserva']").prop('checked', false); 
    }
  return;
});


$('body').on('click', 'button[name=boton_baremo_validar_proximidad_domicilio]', function(e)
{
   var vid=$(this).attr("name");
   var texto=$(this).text();

   if(texto=='Validar domicilio')
   {
      var val_def=recalcular_validacion();
      if(val_def!=0)
      {
         $('#labelbaremo').removeClass('crojo');
         $('#labelbaremo').addClass('cverde');
      }
      //modificamos el campo oculto para el formulario
      $('#baremo_validar_proximidad_domicilio').val('1');
      $(this).text('Invalidar domicilio');
   }
   else
   {
      $(this).text('Validar domicilio');
      $('#baremo_validar_proximidad_domicilio').val('0');
      $('#labelbaremo').removeClass('cverde');
      $('#labelbaremo').addClass('crojo');
   }
   var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_comprobar_proximidad_domicilio]', function(e){
var vid=$(this).prev("button").attr("id");
var vid=$(this).prev("button").attr("name");
});


$('body').on('click', 'button[name=boton_baremo_validar_tutores_centro]', function(e)
{
   var vid=$(this).attr("name");
   vid=vid.replace('boton_baremo_validar_tutores_centro','');
   var texto=$(this).text();
   console.log("VALIDANDO TUTORES");
   if($("#baremo_tutores_centro").is(":checked")===false)
   {
      console.log("VALIDANDO TUTORESi: checked");
      return;
   }
   if(texto=='Validar tutores trabajan centro')
   {
      var val_def=recalcular_validacion(vid);

      if(val_def!=0)
      {
         $('#labelbaremo'+vid).removeClass('crojo');
         $('#labelbaremo'+vid).addClass('cverde');
      }

      if($("input[id=baremo_tutores_centro]").val()=='1')
      {
         $('#baremo_validar_tutores_centro').val('1');
         $(this).text('Invalidar tutores trabajan centro');
      }
   }
   else
   {
      $(this).text('Validar tutores trabajan centro');
      $('#baremo_validar_tutores_centro').val('0');
      $('#labelbaremo'+vid).removeClass('cverde');
      $('#labelbaremo'+vid).addClass('crojo');
   }
   var bar_def=recalcular_baremo(vid);
});

$('body').on('click', 'button[name=boton_baremo_validar_situacion_sobrevenida]', function(e)
{
   var vid=$(this).attr("name");
   vid=vid.replace('boton_baremo_validar_situacion_sobrevenida','');
   var texto=$(this).text();
   console.log("VALIDANDO SITUACION");
   if($("#baremo_situacion_sobrevenida").is(":checked")===false)
   {
      console.log("VALIDANDO SITUACION: No está marcado");
      return;
   }
   if(texto=='Validar situacion sobrevenida')
   {

      if($("input[id=baremo_situacion_sobrevenida]").val()=='1')
      {
         $('#baremo_validar_situacion_sobrevenida').val('1');
         $(this).text('Invalidar situacion sobrevenida');
      }
   }
   else
   {
      $(this).text('Validar situacion sobrevenida');
      $('#baremo_validar_situacion_sobrevenida').val('0');
   }
});

$('body').on('click', 'button[name=boton_baremo_validar_renta_inferior]', function(e){
var vid=$(this).attr("name");
vid=vid.replace('boton_baremo_validar_renta_inferior','');
var texto=$(this).text();

if($("#baremo_renta_inferior").is(":checked")===false)
   return;

if(texto=='Validar renta')
{
   var val_def=recalcular_validacion();
   if(val_def!=0)
   {
      $('#labelbaremo'+vid).removeClass('crojo');
      $('#labelbaremo'+vid).addClass('cverde');
   }
   $('#baremo_validar_renta_inferior').val('1');
   $(this).text('Invalidar renta');
}

else
{
	$(this).text('Validar renta');
	$('#baremo_validar_renta_inferior').val('0');
	$('#labelbaremo').removeClass('cverde');
	$('#labelbaremo').addClass('crojo');
}
   var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_acogimiento]', function(e){
var vid=$(this).attr("name");
vid=vid.replace('boton_baremo_validar_acogimiento','');
var texto=$(this).text();

if($("#baremo_acogimiento").is(":checked")===false)
   return;

if(texto=='Validar situación de acogimiento')
{
   var val_def=recalcular_validacion(vid);

   if(val_def!=0)
      {
      $('#labelbaremo'+vid).removeClass('crojo');
      $('#labelbaremo'+vid).addClass('cverde');
      }

   if($("input[id=baremo_acogimiento"+vid+"]").val()=='1')
   {
      $('#baremo_validar_acogimiento'+vid).val('1');
      $(this).text('Invalidar situación de acogimiento');
   }
}
else
{
	$(this).text('Validar situación de acogimiento');
	$('#baremo_validar_acogimiento'+vid).val('0');
	$('#labelbaremo'+vid).removeClass('cverde');
	$('#labelbaremo'+vid).addClass('crojo');
}
var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_genero]', function(e){
var vid=$(this).attr("name");
vid=vid.replace('boton_baremo_validar_genero','');
var texto=$(this).text();
if($("#baremo_genero").is(":checked")===false)
   return;

if(texto=='Validar víctima de género')
{
   var val_def=recalcular_validacion();

   if(val_def!=0)
      {
      $('#labelbaremo').removeClass('crojo');
      $('#labelbaremo').addClass('cverde');
      }

   if($("input[id=baremo_genero]").val()=='1')
   {
      $('#baremo_validar_genero').val('1');
      $(this).text('Invalidar víctima de género');
   }
}
else
{
	$(this).text('Validar víctima de género');
	$('#baremo_validar_genero').val('0');
	$('#labelbaremo').removeClass('cverde');
	$('#labelbaremo').addClass('crojo');
}
   var rb=recalcular_baremo(vid);
});

$('body').on('click', 'button[name=boton_baremo_validar_terrorismo]', function(e){
var texto=$(this).text();
if($("#baremo_terrorismo").is(":checked")===false)
   return;

if(texto=='Validar víctima de terrorismo')
{
   var val_def=recalcular_validacion();

   if(val_def!=0)
   {
      $('#labelbaremo').removeClass('crojo');
      $('#labelbaremo').addClass('cverde');
   }

   if($("input[id=baremo_terrorismo]").val()=='1')
   {
      $('#baremo_validar_terrorismo').val('1');
      $(this).text('Invalidar víctima de terrorismo');
   }
}
else
{
	$(this).text('Validar víctima de terrorismo');
	$('#baremo_validar_terrorismo').val('0');
	$('#labelbaremo').removeClass('cverde');
	$('#labelbaremo').addClass('crojo');
}
   var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_parto]', function(e)
{
   var texto=$(this).text();
   if($("#baremo_parto").is(":checked")===false)
      return;

   if(texto=='Validar parto múltiple')
   {
      var val_def=recalcular_validacion();

      if(val_def!=0)
         {
         $('#labelbaremo').removeClass('crojo');
         $('#labelbaremo').addClass('cverde');
         }

      if($("input[id=baremo_parto]").val()=='1')
      {
         $('#baremo_validar_parto').val('1');
         $(this).text('Invalidar parto múltiple');
      }
   }
   else
   {
      $(this).text('Validar parto múltiple');
      $('#baremo_validar_parto').val('0');
      $('#labelbaremo').removeClass('cverde');
      $('#labelbaremo').addClass('crojo');
   }
   var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_discapacidad_alumno]', function(e)
{
   console.log("validando disc alumno");
   var texto=$(this).text();
   if($("[name=baremo_discapacidad_alumno]").is(":checked")===false)
   {
      console.log("no se ha marcado el checl de disc alumno");
      return;
   }

   if(texto=='Validar discapacidad alumno')
   {
      var val_def=recalcular_validacion();
      if(val_def!=0)
      {
         $('#labelbaremo').removeClass('crojo');
         $('#labelbaremo').addClass('cverde');
      }
      var valor=$("input[name='baremo_discapacidad_alumno']:checked").val();
      if(valor!='no')
      {
         $('#baremo_validar_discapacidad_alumno').val('1');
         $(this).text('Invalidar discapacidad alumno');
      }
   }
   else
   {
      $(this).text('Validar discapacidad alumno');
      $('#baremo_validar_discapacidad_alumno').val('0');
      $('#labelbaremo').removeClass('cverde');
      $('#labelbaremo').addClass('crojo');
   }
   var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_hnos_centro]', function(e)
{
   var texto=$(this).text();
   if(texto=='Validar hermanos')
   {
   var val_def=recalcular_validacion();

   if(val_def!=0)
      {
      $('#labelbaremo').removeClass('crojo');
      $('#labelbaremo').addClass('cverde');
      }
   $('#baremo_validar_hnos_centro').val('1');
   $(this).text('Invalidar hermanos');
   }
   else
   {
      $(this).text('Validar hermanos');
      $('#baremo_validar_hnos_centro').val('0');
      $('#labelbaremo').removeClass('cverde');
      $('#labelbaremo').addClass('crojo');
   }
   var rb=recalcular_baremo();
});

/*
$('body').on('click', 'button[name=boton_baremo_validar_discapacidad_alumno]', function(e){
var vid=$(this).attr("name");
vid=vid.replace('boton_baremo_validar_discapacidad_alumno','');
var texto=$(this).text();

if(texto=='Validar discapacidad alumno')
{
   var val_def=recalcular_validacion();
   if(val_def!=0)
      {
      $('#labelbaremo').removeClass('crojo');
      $('#labelbaremo').addClass('cverde');
      }
   //modificamos el campo oculto para el formulario, solamente si no está
   //marcada la útlima opción
   var valor=$("input[name='baremo_discapacidad_alumno']:checked").val();
   if(valor!='no')
   {
      $('#baremo_validar_discapacidad_alumno').val('1');
      $(this).text('Invalidar discapacidad alumno');
   }
}
else
{
	$(this).text('Validar discapacidad alumno');
	$('#baremo_validar_discapacidad_alumno').val('0');
	$('#labelbaremo').removeClass('cverde');
	$('#labelbaremo').addClass('crojo');
}
   var rb=recalcular_baremo();
});
*/
$('body').on('click', 'button[name=boton_baremo_validar_discapacidad_hermanos]', function(e){
var vid=$(this).attr("name");
vid=vid.replace('boton_baremo_validar_discapacidad_hermanos','');
var texto=$(this).text();
   if($("[name=baremo_discapacidad_hermanos]").is(":checked")===false)
   {
      console.log("no se ha marcado el checl de disc alumno");
      return;
   }

if(texto=='Validar discapacidad progenitores/hermanos')
{
   var val_def=recalcular_validacion();
   if(val_def!=0)
      {
      $('#labelbaremo'+vid).removeClass('crojo');
      $('#labelbaremo'+vid).addClass('cverde');
      }
   //modificamos el campo oculto para el formulario, solamente si no está
   //marcada la útlima opción
   var valor=$("input[name='baremo_discapacidad_hermanos']:checked").val();
   if(valor!='no')
   {
      $('#baremo_validar_discapacidad_hermanos').val('1');
      $(this).text('Invalidar discapacidad progenitores/hermanos');
   }
}
else
{
	$(this).text('Validar discapacidad progenitores/hermanos');
	$('#baremo_validar_discapacidad_hermanos').val('0');
	$('#labelbaremo').removeClass('cverde');
	$('#labelbaremo').addClass('crojo');
}
var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_tipo_familia_numerosa]', function(e)
{
   var texto=$(this).text();
   if($("#baremo_marcado_numerosa").is(":checked")===false)
      return;

   if(texto=='Validar familia numerosa')
   {
      var val_def=recalcular_validacion();
      if(val_def!=0)
      {
         $('#labelbaremo').removeClass('crojo');
         $('#labelbaremo').addClass('cverde');
      }
      //modificamos el campo oculto para el formulario
      $('#baremo_validar_tipo_familia_numerosa').val('1');
      $(this).text('Invalidar familia numerosa');
   }
   else
   {
      $(this).text('Validar familia numerosa');
      $('#baremo_validar_tipo_familia_numerosa').val('0');
      $('#labelbaremo').removeClass('cverde');
      $('#labelbaremo').addClass('crojo');
   }
   var rb=recalcular_baremo();
});

$('body').on('click', 'button[name=boton_baremo_validar_tipo_familia_monoparental]', function(e){
   var texto=$(this).text();
   if($("#baremo_marcado_monoparental").is(":checked")===false)
      return;

   if(texto=='Validar familia monoparental')
   {
      var val_def=recalcular_validacion();
      if(val_def!=0)
      {
         $('#labelbaremo').removeClass('crojo');
         $('#labelbaremo').addClass('cverde');
      }
      //modificamos el campo oculto para el formulario
      $('#baremo_validar_tipo_familia_monoparental').val('1');
      $(this).text('Invalidar familia monoparental');
   }
   else
   {
      $(this).text('Validar familia monoparental');
      $('#baremo_validar_tipo_familia_monoparental').val('0');
      $('#labelbaremo').removeClass('cverde');
      $('#labelbaremo').addClass('crojo');
   }
   var rb=recalcular_baremo();
});
/////////////////////////////////////////////////////////////////////////////////////////////////
function comprobar_baremo(tb,tbv){
if(tb==tbv)
	{
	$('#labelbaremo').removeClass('crojo');
	$('#labelbaremo').addClass('cverde');
	}
else{
	$('#labelbaremo').removeClass('cverde');
	$('#labelbaremo').addClass('crojo');
}
return 1;
}

function recalcular_validacion()
{
   var totalvalido=1;
   var valido=0;
   var vpd=$('#baremo_validar_proximidad_domicilio').val();
   if(vpd==0) return 0;
   var vtc=$('#baremo_validar_tutores_centro').val();
   if(vtc==0) return 0;
   var vgen=$('#baremo_validar_genero').val();
   if(vgen==0) return 0;
   var vter=$('#baremo_validar_terrorismo').val();
   if(vter==0) return 0;
   var vpar=$('#baremo_validar_parto').val();
   if(vpar==0) return 0;
   var vr=$('#baremo_validar_renta_inferior').val();
   if(vr==0) return 0;
   var vr=$('#baremo_validar_discapacidad_alumno').val();
   if(vr==0) return 0;
   var vr=$('#baremo_validar_discapacidad_hermanos').val();
   if(vr==0) return 0;
   var vr=$('#baremo_validar_acogimiento').val();
   if(vr==0) return 0;
   var vr=$('#baremo_validar_tipo_familia_monoparental').val();
   if(vr==0) return 0;
   var vr=$('#baremo_validar_tipo_familia_numerosa').val();
   if(vr==0) return 0;
   return 1;
}
/////////////////////////////////////////////////////////////////////////////////////////////////

//CHECKS FILTRO SOLICITUDES USANDO CHECKS
$('body').on('click', '.filtrosol,.filtrosoltodas', function(e)
{
	var expression = false;
	var nombre = $(this).attr("id");
	var tipo = $(this).attr("data-tipo");

	filtrar_solicitudes(tipo,nombre);
});

function filtrar_solicitudes(t,n)
{
		var estado=$(this).prop('checked');
		n=n.toLowerCase();
		//si pulsamos en el check de todas se muestran todas y ponemo sel estado a false
    if(n.indexOf("todas")!=-1)
	{
    	$(".filasol").each(function () 
			{
	    	$(this).show();
				$(".filtrosol").prop('checked', false); 
	    });
		if(estado)
		{
	        $(".filtrosol").each(function () 
		{
    		if($(this).attr("id").indexOf('TODAS')==-1) $(this).prop('checked', false);
		});
		}	
		return;
	}
	//detectar los checks marcados
	var todas=$('#TODAS').prop('checked');
	var borrador=$('#Borrador').prop('checked');
	var validada=$('#Validada').prop('checked');
	var baremada=$('#Baremada').prop('checked');
	var ebo=$('#EBO').prop('checked');
	var tva=$('#TVA').prop('checked');
	var nb='nofase';
	var na='nofase';
	var nv='nofase';
	
	var nte='notipo';

	if(validada) na='validada';
	if(borrador) nb='borrador';
	if(baremada) nb='baremada';
	if(ebo) nte='ebo';
	if(tva) nte='tva';
	var finder = "";
	//si ambos, ebo y tva están pulsados mostramos todo
	var pulsados=0;
	if(ebo && tva) pulsados=1;

	$(".filasol").each(function () 
	{
  	var title = $(this).text();
		if(t.indexOf("fase")<0) var valor = $(this).children().eq(2).text();
		else var valor = $(this).children().eq(2).text();
				if (valor.toLowerCase().indexOf(nb.toLowerCase()) >= 0 || valor.toLowerCase().indexOf(na.toLowerCase()) >= 0 || valor.toLowerCase().indexOf(nv.toLowerCase()) >= 0) 
				{
						$(this).show();
				} 
				else 
				{
						$(this).hide();
				}
});
	$(".filasol").each(function () 
	{
  	var title = $(this).text();
		var valor = $(this).children().eq(3).text();
				if (valor.toLowerCase().indexOf(nte.toLowerCase()) >= 0) 
				{
						$(this).show();
				} 
				else if(pulsados==0 && !todas) 
				{
						$(this).hide();
				}
});
};
//filtro general de solicitudes
$('body').on('keyup', '#filtrosol', function(e){
		var expression = false;
            var value = $(this).val();
            if(value.length<=2){
            $(".filasol").each(function () {
	    			$(this).show();
	    			});
	    			}
            if(value.length<3) return;

	    var finder = "";
	    if (value.indexOf("\"") > -1 && value.lastIndexOf("\"") > 0) {
                finder = value.substring(eval(value.indexOf("\"")) + 1, value.lastIndexOf("\""));
                expression = true;
            }
            $(".filasol").each(function () {
                var title = $(this).text();
                if (expression) {
                    if ($(this).text().toLowerCase().search(finder.toLowerCase()) == -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                } else {
                    if (title.toLowerCase().indexOf(value.toLowerCase()) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                }
            });
        });
//FIN filtro solicitudes

//CHECKS FILTRO TIPO ENSENANZA


$('body').on('click', '.bform', function(e){
 $([document.documentElement, document.body]).animate({
        scrollTop: $(this).offset().top
    }, 1000);

});

//EVENTOS DE OCULTAR/MOSTRAR EN FORMULARIO
//Hermanos para el baremo
$('body').on('change', 'input[id=baremo_marcado_proximidad_domicilio]', function(e)
{
   console.log("pulsado p domi");
   if($(this).is(":checked")===false)
   {
      console.log("desmarcado prox domi");
      $("input:radio[name='baremo_proximidad_domicilio']").each(function(i) {
          this.checked = false;
      });
      $('#baremo_validar_proximidad_domicilio').val('0');
      $('[name=boton_baremo_validar_proximidad_domicilio]').text('Validar domicilio');
   }
   var cbaremo='baremo_marcado_proximidad_domicilio';
   var bar_def=recalcular_baremo();
   $("#id_puntos_baremo").text(bar_def);

   $(this).next().next().next().next().next(".cajabaremo").slideToggle('slow');
});

$('body').on('change', 'input[id=baremo_marcado_discapacidad],input[id=baremo_marcado_numerosa],input[id=baremo_marcado_monoparental]', function(e)
{
   var name=$(this).attr("name");
   if(name=='baremo_marcado_discapacidad')
      if($(this).is(":checked")===false)
      {
         console.log("desmarcado"+name);
         $("input:checkbox[name='baremo_discapacidad_alumno']").each(function(i) {
            console.log("desmarcado"+i);
             this.checked = false;
         });
         $("input:checkbox[name='baremo_discapacidad_hermanos']").each(function(i) {
            console.log("desmarcado"+i);
             this.checked = false;
         });
      }

   $(this).next().next().next().next(".cajabaremo").slideToggle('slow');
   $(this).next().next(".cajabaremo").slideToggle('slow');
   if(name=='baremo_marcado_numerosa')
   {
      $("#cajabaremo_numerosa").slideToggle('slow');
      if($(this).is(":checked")===false)
      {
         console.log("desmarcado"+name);
         $("input:radio[name='baremo_tipo_familia_numerosa']").each(function(i) {
            console.log("desmarcado"+i);
             this.checked = false;
         });
      }
      else
      {
         $('#baremo_marcado_monoparental').prop('checked',false);
         $('#cajabaremo_monoparental').hide();
         $("input:radio[name='baremo_tipo_familia_monoparental']").each(function(i) {
            console.log("desmarcado"+i);
             this.checked = false;
         });
      }
   }
   if(name=='baremo_marcado_monoparental')
   {
      $("#cajabaremo_monoparental").slideToggle('slow');
      if($(this).is(":checked")===false)
      {
         console.log("desmarcado"+name);
         $("input:radio[name='baremo_tipo_familia_monoparental']").each(function(i) {
            console.log("desmarcado"+i);
             this.checked = false;
         });
      }
      else
      {
         $('#baremo_marcado_numerosa').prop('checked',false);
         $('#cajabaremo_numerosa').hide();
         $("input:radio[name='baremo_tipo_familia_numerosa']").each(function(i) {
            console.log("desmarcado"+i);
             this.checked = false;
         });
      }
   }
   var bar_def=recalcular_baremo();
   $("#id_puntos_baremo").text(bar_def);

});

$('body').on('change', 'input[id=num_hbaremo]', function(e){

var bar_def=recalcular_baremo();
$("#id_puntos_baremo").text(bar_def);

$(".hno_baremo").slideToggle('slow');
});

//CAMPOS TIPO RADIO SECCION EXPONE
$('body').on('change', '#nuevaesc', function(e)
{
   var val=$(this).attr("value");
   var id=$(this).attr("data-reserva");
   var vid=id.replace('nuevaesc','');
   var tabla=".fila"+id;
   console.log("Ocultando reserva");	
   //$(this).attr('value','1');
   $(".filarenesc").hide('slow');
   //desmarcamos los checks de reservaa
   $("input[name='reserva']").prop('checked', false);
   $("#modalidad_origen").val('nodata');
   $("#id_centro_estudios_origen").val('');
});

$('body').on('change', '#reserva_pend', function(e)
{
   var val=$(this).attr("value");
   var id=$(this).attr("data-reserva");
   var vid=id.replace('nuevaesc','');
   var tabla=".fila"+id;
   console.log("IDRADIO: "+vid);	
   console.log("Ocultando reserva");	
//	$(this).attr('value','1');
	$(".filarenesc").show('slow');
});

$('body').on('change', '#renesc', function(e)
{
   var val=$(this).attr("value");
   var id=$(this).attr("data-reserva");
   var vid=id.replace('nuevaesc','');
   var tabla=".fila"+id;
   console.log("IDRADIO: "+vid);	
   console.log("Ocultando reserva");	
//	$(this).attr('value','1');
	$(".filarenesc").show('slow');
});

//CAMPOS TIPO RADIO SECCION SOLICITA
$('body').on('change', '[id=conjunta]', function(e)
{
   var r=recalcular_baremo();
   var id=$(this).attr("id");
   var vid=id.replace('conjunta','');
   console.log("Mostrando hermanos");	
   //$(this).attr('value','1');
   $(".bloque_hermanos_admision").show('slow');
});
$('body').on('change', '[id=individual]', function(e)
{
   var r=recalcular_baremo();
   var id=$(this).attr("id");
   var vid=id.replace('individual','');
   console.log("Mostrando hermanos");	
   //$(this).attr('value','1');
   $(".bloque_hermanos_admision").hide('slow');
});

//CAMPOS TIPO CHECKBOX

$('body').on('change', '.centro_estudios_origen', function(e)
{
   var centro=$(this).val()
   var id=$(this).attr("id");
   var vid=id.replace('id_centro_estudios_origen','');
   if(centro.indexOf("*")!=-1) $(".freserva"+vid).show('slow');
   else $(".freserva"+vid).hide('slow');
});

$('body').on('change', '.check_hadmision', function(e){
var val=$(this).attr("value");
var id=$(this).attr("id");
var tabla="#t"+id;
if(val=='0')
{	
	
	$(this).attr('value','1');
}
else
{
		$(this).attr('value','0');
}
$(tabla).slideToggle('slow');
});

$('body').on('change', '.oponenautorizar, .cumplen', function(e){
var val=$(this).attr("value");
var vcheck=$(this).attr("name");
var vid=$(this).attr("id");
vid=vid.replace('oponenautorizar','');
vid=vid.replace('cumplen','');
if(val=='0')
{
		$(this).attr('value','1');
		block(vcheck,vid,'1');
}
else
{
		block(vcheck,vid,'0');
		$(this).attr('value','0');
}

});


function block(c,id,n){
if(c.indexOf('oponenautorizar')!=-1)
{
if(n=='0') $("#cumplen"+id).attr('disabled', false);
else $("#cumplen"+id).attr('disabled', true);
}
else
{
if(n=='0') $("#oponenautorizar"+id).prop('disabled', false);
else $("#oponenautorizar"+id).prop('disabled', true);
}
}

//AÑADIR FORMULARIO DE MODIFICACION DE SOLICITUD
function disableForm(formID){
  $(formID).find(':input').attr('disabled', 'disabled');
}

$('body').on('click', '.calumno', function(e){
  var ots = $(this);
  var vmodo='normal';
  var vid=$(this).attr("data-idal");
  var vtoken=$(this).attr("data-token");
  var idappend="filasol"+vid;
  var vestado_convocatoria=$('#estado_convocatoria').attr("value");
  var vid_alumno=$('#id_alumno').attr("value");
  var vpin=$('#pin').attr("value");
  var vrol=$('#rol').attr("value");
  var vidcentro=$('#id_centro').text();
  console.log("idalumno fila "+vid);
  console.log("idalumno rol "+vid_alumno);
  console.log("rol "+vrol);
  console.log("ccentro "+vidcentro);
  console.log("token "+vtoken);

  //enviamos a la solicitud del alumno segun el token
  var enlacealumno=urlbase+'/index.php?token='+vtoken;
  console.log("directorio "+enlacealumno);
  window.open(enlacealumno,'_blank');
  return;
});
//FIN AÑADIR FORMULARIO DE MODIFICACION DE SOLICITUD

//////////////////////////////////////////////////////////////
//AÑADIR FORMULARIO NUEVA SOLICITUD
$('body').on('click', '#nuevasolicitud', function(e)
{
   //window.open(urlbase+'/index.php?solcentro=vsolcentro', '_blank');
   window.open(urlbase+'/index.php?solcentro=vsolcentro', '_blank');
   return;
});

//////////////////////////////////////////////////////////////
//LISTADOS GENERALES
//////////////////////////////////////////////////////////////

//LISTADO SOLICITUDES BRUTO
$(".show_solicitudes").click(function () {  
  var vid_centro=$('#id_centro').html();
  console.log("solicitudes id centro: "+vid_centro);
  var vrol=$('#rol').attr("value");
  var vprovincia=$('#provincia').attr("value");
  var vestado_convocatoria=$('#estado_convocatoria').attr("value");
   $.ajax({
     method: "POST",
     url: "../"+directoriobase+"/scripts/ajax/listados_solicitudes.php",
     data: {id_centro:vid_centro,rol:vrol,estado_convocatoria:vestado_convocatoria,provincia:vprovincia},
         success: function(data) {
               if(vrol=='admin' || vrol=='sp')
               {
                  $("#mapcontrol").hide();
                  $("#map-canvas").hide();
                  $(".tresumensol").remove();
                  $(".tresumenmat").remove();
                  $("#l_matricula").html(data);
                  $(".wrapper").html(data);
                  $("#filasolicitud").remove();
                  //$("#navgir").after(data);
               }
               else
               {
                  $("#mapcontrol").hide();
                  $("#map-canvas").hide();
                  $(".tresumensol").remove();
                  $(".tresumenmat").hide();
                  $("#tresumen").hide();
                  $("#l_matricula").html(data);
               }
         },
         error: function() {
           alert('Errorr listado solicitudes');
         }
   });
});

//LISTADO SOLICITUDES GENERALES
$(".lbaremadas").click(function () {  
   var vpdf='1';
   var vid_centro=$('#id_centro').text();
   var vrol=$('#rol').attr("value");
   var vprovincia=$('#provincia').attr("value");
   var vtipo=$(this).attr("data-tipo");
   var vsubtipo=$(this).attr("data-subtipo");
   var vestado_convocatoria=$('#estado_convocatoria').attr("value");
   $.ajax({
     method: "POST",
     url: "../"+directoriobase+"/scripts/ajax/listados_baremados.php",
     data: {id_centro:vid_centro,rol:vrol,tipo:vtipo,subtipo:vsubtipo,pdf:vpdf,estado_convocatoria:vestado_convocatoria,provincia:vprovincia},
         success: function(data) {
            if(vrol=='centro')
            {
               $("#l_matricula").html(data);
               $("#tresumen").hide();
            }
            else if(vrol=='alumno')
            {
               var botonversolicitud="<button class='btn' id='versolicitud'>VER SOLICITUD</button>";
               $(".container").hide();
               $('#navgir').after(data);
               $('#navgir').after(botonversolicitud);
               
            }
            else
            {
               console.log("vrol: "+data);
               //$("#sol_table").remove();
               //$(".titulolistado").remove();
               //$(".descargalistado").remove();
               //$("#filtrosol").remove();
               //$("#filanuevasolicitud").remove();
               if($("#l_matricula").length)
               {
                  $(".wrapper").html(data);
                  $("#l_matricula").remove();
               }
               else
               {
                  $("tresumensol").remove();
                  $("#cab_fnuevasolicitud").after(data);
                  $(".wrapper").html(data);
               }
            }
         },
         error: function() {
           alert('Error LISTANDO solicitudes: '+vsubtipo);
         }
   });
});
$('body').on('click', '#versolicitud', function(e)
{
   $(".titulolistado").toggle();
   $(".descargalistado").toggle();
   $(".container").toggle();
   $('#sol_table').toggle();
});
//LOS  TADO SOLICITUDES PROVISIONALES ACTUALIZADO
$('body').on('click', '.lprovisionales', function(e){
  var vpdf='1';
  var vid_centro=$('#id_centro').text();
  var vrol=$('#rol').attr("value");
  var vprovincia=$('#provincia').attr("value");
  var vtipo=$(this).attr("data-tipo");
  var vsubtipo=$(this).attr("data-subtipo");
  var vestado_convocatoria=$('#estado_convocatoria').attr("value");
  console.log("PROVINCIA: "+vprovincia);
$.ajax({
  method: "POST",
  url: "../"+directoriobase+"/scripts/ajax/listados_provisionales.php",
  data: {id_centro:vid_centro,rol:vrol,tipo:vtipo,subtipo:vsubtipo,pdf:vpdf,estado_convocatoria:vestado_convocatoria,provincia:vprovincia},
      success: function(data) {
         if(vrol=='centro')
         {
            $("#l_matricula").html(data);
            $("#tresumen").hide();
         }
         else
         {
            $("#l_matricula").html(data);
            $(".container").hide();
         }
      },
      error: function() {
        alert('Error LISTANDO solicitudes: '+vsubtipo);
      }
});
});
//LISTADOS DEFINITIVOS
$(".ldefinitivos").click(function () {  
   var vpdf='1';
   var vid_centro=$('#id_centro').text();
   var vrol=$('#rol').attr("value");
   var vprovincia=$('#provincia').attr("value");
   var vtipo=$(this).attr("data-tipo");
   var vsubtipo=$(this).attr("data-subtipo");
   var vestado_convocatoria=$('#estado_convocatoria').attr("value");
   $.ajax({
     method: "POST",
     url: "../"+directoriobase+"/scripts/ajax/listados_definitivos.php",
     data: {id_centro:vid_centro,rol:vrol,tipo:vtipo,subtipo:vsubtipo,pdf:vpdf,estado_convocatoria:vestado_convocatoria,provincia:vprovincia},
         success: function(data) {

               if(vrol=='centro')
               {
               $("#l_matricula").html(data);
               $("#tresumen").hide();
               }
               else
               {
               $("#mapcontrol").hide();
               $("#map-canvas").hide();
               $("#l_matricula").html(data);
               $(".container").hide();
               }
         },
         error: function() {
           alert('Error LISTANDO solicitudes: '+vsubtipo);
         }
   });
});

//LISTADO MATRICULA
$('body').on('click', '.show_matricula', function(e){
  var vid_centro=$('#id_centro').text();
  var vrol=$('#rol').attr("value");
  var vprovincia=$('#provincia').attr("value");
  var vestado_convocatoria=$('#estado_convocatoria').attr("value");
$.ajax({
  method: "POST",
  url: "../"+directoriobase+"/scripts/ajax/listados_matriculas.php",
  data: {id_centro:vid_centro,rol:vrol,provincia:vprovincia,estado_convocatoria:vestado_convocatoria},
      success: function(data) 
		{
         console.log("mat");
			if(vrol=='admin') 
			{
				$(".tresumensol").remove();
            console.log("admin");
				$("#mapcontrol").hide();
				$("#map-canvas").hide();
            if($("#l_matricula").length)
				   $("#l_matricula").html(data);
            else
            {
               console.log("borrando tresumensol");
				   $(".tresumensol").remove();
				   $("#cab_fnuevasolicitud").after(data);
            }
			}
			else
			{
				$("#mapcontrol").hide();
				$("#map-canvas").hide();
				$("#tresumen"+vid_centro).show();
				$("#l_matricula").html(data);
				$(".tresumenmat").show();
			}
      },
      error: function() {
        alert('Erorr LISTADO matricula');
      }
});
});

//MOSTRAR ALUMNOS MATRICULADOS DE CADA CENTRO
$('body').on('click', '.cabcenmat', function(e){
  var vid_centro=$(this).attr('id');
  vid_centro=vid_centro.replace('cabcen','');
  var vrol=$('#rol').attr("value");
$.ajax({
  method: "POST",
  url: "../"+directoriobase+"/scripts/ajax/mostrar_matriculados.php",
  data: {id_centro:vid_centro,rol:vrol},
      success: function(data) 
			{
            console.log(data);
				if(vrol.indexOf('admin')!=-1 || vrol.indexOf('sp')!=-1)
				{
				console.log("en matricula");
				console.log("MOSTRANDO MATRICULADOS CON ROL: "+vrol);
			   	if($('#mat_table'+vid_centro).length) $('#mat_table'+vid_centro).toggle();
				   else $('#table'+vid_centro).after(data).show('slow');
	
				}
				else
				{
				   $("#tresumen").show();
				   $("#l_matricula").html(data);
				}
      },
      error: function() {
        alert('Erorr LISTADO matricula');
      }
});
});
//MOSTRAR SOLICITUDES DE CADA CENTRO
$('body').on('click', '.cabcensol', function(e){
  var vid_centro=$(this).attr('id');
  vid_centro=vid_centro.replace('cabcensol','');
   console.log("mostrando solicitudes id centro: "+vid_centro);
  var vrol=$('#rol').attr("value");
  var vestado_convocatoria=$('#estado_convocatoria').attr("value");
  var vprovincia=$('#provincia').attr("value");
  if($("#sol_table"+vid_centro).length){ $("#sol_table"+vid_centro).toggle(); return;}
if(vrol=='centro') return;
$.ajax({
  method: "POST",
  url: "../"+directoriobase+"/scripts/ajax/mostrar_solicitudes.php",
  data: {id_centro:vid_centro,rol:vrol,provincia:vprovincia,estado_convocatoria:vestado_convocatoria},
      success: function(data) 
		{
         if(vrol.indexOf('admin')!=-1 || vrol.indexOf('sp')!=-1)
         {
            if($('#sol_table'+vid_centro).length) $('#sol_table'+vid_centro).hide();
            $('#sol_table'+vid_centro).remove();
            $('#table'+vid_centro).after(data);
         }
         else
         {
            $("#tresumen").show();
            $("#l_matricula").html(data);
         }
      },
      error: function() {
        alert('Error MOSTRANDO SOLICITUDES');
      }
});
});

/////////////////////////////////////////////////////////////
//GENERAR PDF
$("#pdff").click(function () {  
  var id=$(this).attr("id");
  var est=$('#'+id).text();
$.ajax({
  method: "POST",
  url: "../"+directoriobase+"/scripts/pdf/demo1.php",
  data: {estado:est},
      success: function(data) {
	$("#idpdf").remove();
	 var r= $('<br><a id="idpdf" href="scripts/pdf/out5.pdf" target="_blank"><input type="button" value="DESCARGA PDF"/></a>');
        $("body").append(r);
      },
      error: function() {
        alert('Erorr en la llamada AJAX');
      }
});
});


//CAMBIO ESTADO BOTONES
$('body').on('click', '.cambiar', function(e){
e.stopPropagation();
  //var vidcentro=$('#id_centro').text();
  //var vidcentro=$('.cabcenmat').attr("id");
  //vidcentro=vidcentro.replace('cabcen','');

  vidcentro=$(this).parent('td').parent('tr').parent('tbody').parent('table').attr('id');
  vidcentro=vidcentro.replace('mat_table','');
  var ots = $(this);
  var vid=$(this).attr("id");
  vid=vid.replace('cambiar','');
  var vcontinua=$("#estado"+vid).text();
  var vtipoalumno=$('#tipoalumno'+vid).text();
  var vestado_pulsado=$(this).text();
  var vestado_actual=$(this).parent('div').parent('div').attr("id");
$.ajax({
  method: "POST",
  data: { id_alumno:vid,estado_pulsado:vestado_pulsado,estado_actual:vestado_actual,id_centro:vidcentro,continua:vcontinua},
  url:'../'+directoriobase+'/scripts/ajax/cambio_estado_solicitud.php',
      success: function(data) 
      {
         if(vcontinua.indexOf('NO')!=-1){ alert("El alumno no continua, no afecta plazas vacantes");return;}
         cambiar_tipo(ots,vestado_pulsado,vid);
         var vacantes_ebo =data.split(":")[0];
         var vacantes_tva =data.split(":")[1];
         $('#vacantesmat_ebo_desk'+vidcentro).html(vacantes_ebo);
         $('#vacantesmat_tva_desk'+vidcentro).html(vacantes_tva);
         var  npo_ebo=$('#vacantesmat_ebo_desk'+vidcentro).prev().text();
         var  npo_tva=$('#vacantesmat_tva_desk'+vidcentro).prev().text();
         if(vestado_pulsado.indexOf('EBO')!=-1)
         {
            console.log("pulsado ebo");
            console.log(ots.parent('td').parent('tr').parent('tbody').parent('table').attr('id'));
            //$(this).closest('#vacantesmat_ebo_desk').prev().html(+npo_ebo+1);
            $('#vacantesmat_ebo_desk'+vidcentro).prev().html(+npo_ebo+1);
            $('#vacantesmat_tva_desk'+vidcentro).prev().html(+npo_tva-1);
            //$('#vacantesmat_tva_desk').prev().html(+npo_tva-1);
         }
         if(vestado_pulsado.indexOf('TVA')!=-1)
         {
            $('#vacantesmat_ebo_desk'+vidcentro).prev().html(+npo_ebo-1);
            $('#vacantesmat_tva_desk'+vidcentro).prev().html(+npo_tva+1);
         }
      },
      error: function() {
        alert('Problemas cambiando de estado!');
      }
});
});

function cambiar_tipo(t,est,id) {
	est=est.replace('CAMBIA A ','');
  $("#tipoalumno"+id).html(est);
		if(est=='EBO')
		$("#cambiar"+id).html("CAMBIA A TVA");
		else
		$("#cambiar"+id).html("CAMBIA A EBO");
}
$('body').on('click', '.continua', function(e){
  var ots = $(this);
  var id=$(this).attr("id");
  var est=$('#'+id).text();
	id=id.replace('estado','');
	id=id.replace('continua','');
	id=id.replace('cambiar','');
  var vidcentro=$(this).parent('td').parent('tr').parent('tbody').parent('table').attr('id');
  vidcentro=vidcentro.replace('mat_table','');
  var vtipoalumno=$('#tipoalumno'+id).text();
$.ajax({
  method: "POST",
  data: { id_alumno:id,estado:est,tipoalumno:vtipoalumno,id_centro:vidcentro},
  url:'../'+directoriobase+'/scripts/ajax/cambio_estado_continua.php',
      success: function(data) 
			{
	 if(data.indexOf('error')!=-1){ alert("No hay plazas vacantes");return;}
    console.log("RESPUESTA:");
    console.log(data);
	 var vacantes_ebo =data.split(":")[0];
	 var vacantes_tva =data.split(":")[1];
	  //cambiarboton(ots);
	  cambiarestado(id,est);
	  vtipoalumno=vtipoalumno.toLowerCase();
   	  $('#vacantesmat_ebo_desk'+vidcentro).html(vacantes_ebo);
   	  $('#vacantesmat_tva_desk'+vidcentro).html(vacantes_tva);
   	  var  numpzasocupadas=$('#vacantesmat_'+vtipoalumno+'_desk'+vidcentro).prev().text();
   	  var  numpuestos=$('#vacantesmat_'+vtipoalumno+'_desk'+vidcentro).prev().prev().text();
			//modificamos tabla vacantes
   	  if(est=='NO CONTINUA')
	     {
   	  	if(+numpzasocupadas-1>=0) $('#vacantesmat_'+vtipoalumno+'_desk'+vidcentro).prev().html(+numpzasocupadas-1);
   	  }
		  else  $('#vacantesmat_'+vtipoalumno+'_desk'+vidcentro).prev().html(+numpzasocupadas+1);
      },
      error: function() 
			{
        alert('Error cambiando estado!');
      }
});
});

function cambiarestado(id,est) 
{
	$("#estado"+id).html(est);
	if(est=='CONTINUA' || est=='NO CONTINUA')	
	{
		if(est=='NO CONTINUA')
		$("#continua"+id).html("CONTINUA");
		else
		$("#continua"+id).html("NO CONTINUA");
	
	}
	else
	{
		if(est=='CAMBIA A EBO')
		$("#cambiar"+id).html("CAMBIA A TVA");
		else
		$("#cambiar"+id).html("CAMBIA A EBO");
	
	}
}

function cambiarboton(t) {
       if(t.hasClass("btn-warning"))
       {
       		$(t).addClass("btn-danger");
       		$(t).removeClass("btn-warning");
       }
       else{
       		$(t).addClass("btn-warning");
       		$(t).removeClass("btn-danger");
       }
       $(t).text(function(i, v){
		   return v === 'CONTINUA' ? 'NO CONTINUA' : 'CONTINUA'
		});
    };

//OCULTAR PANEL LATERAL

$('#sidebarCollapse').on('click', function () 
{
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
});



///////////////////////////////////////////////////////////////////////////
//FORMULARUIO SOLICITUD
// INICIO TOOLTIP
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover()
})


//AUTOCOMPLETAR

var loc_options = 
	{
   url:urlbase+"/datosweb/localidades.json",
	getValue: "name",
		list: 
		{
			maxNumberOfElements: 10,
			match: 
			{
			enabled: true
			},
			onKeyEnterEvent: function() 
			{
			var vcentro = $('#localidad').getSelectedItemData().name;
			},
			onClickEvent: function() 
			{
			var vcentro = $('#localidad').getSelectedItemData().name;
			}
		}
	};
$("#localidad").easyAutocomplete(loc_options);
$("#localidad_origen").easyAutocomplete(loc_options);
$('input[id*=loc_dfamiliar]').easyAutocomplete(loc_options);

var loc_options = 
	{
	url: "../"+directoriobase+"/datosweb/municipios.json",
	getValue: "mu",
		list: 
		{
			maxNumberOfElements: 10,
			match: 
			{
			enabled: true
			},
			onKeyEnterEvent: function() 
			{
			var vcentro = $('#municipionac').getSelectedItemData().name;
			},
			onClickEvent: function() 
			{
			var vcentro = $('#municipionac').getSelectedItemData().name;
			}
		}
	};
$("#municipionac").easyAutocomplete(loc_options);

var cen_options_zaragoza = 
   {	
   url:urlbase+"/datosweb/centros_especial_zaragoza.json",
	getValue:"nombre_centro",
		list: 
		{
			onSelectItemEvent: function() {
			var nombre = $("#fcentrosadminzgz").getSelectedItemData().nombre_centro;
			var idcentro = $("#fcentrosadminzgz").getSelectedItemData().id_centro;
			$("#id_centro").text(idcentro);
			$("#id_centro").attr("value",idcentro);
			//$("#rol").text('centro');
			//$("#rol").attr("value","centro");
			},
			maxNumberOfElements: 10,
			match: 
			{
			enabled: true
			},
			onKeyEnterEvent: function() 
			{
			},
			onClickEvent: function() 
			{
			}
		}
	};
$("#fcentrosadminzgz").easyAutocomplete(cen_options_zaragoza);

var cen_options = 
	{
   url:urlbase+"/datosweb/centros_especial_municipios.json",
	getValue:"nc",
    template: {
        type: "description",
        fields: {
            description: "mu"
        }
      },
		list: 
		{
			onSelectItemEvent: function() {
			var nombre = $("#fcentrosadmin").getSelectedItemData().nombre_centro;
			var idcentro = $("#fcentrosadmin").getSelectedItemData().id_centro;
			$("#id_centro").text(idcentro);
			$("#id_centro").attr("value",idcentro);
			},
			maxNumberOfElements: 10,
			match: 
			{
			enabled: true
			},
			onKeyEnterEvent: function() 
			{
			var vcentro = $('#buscar_centros').getSelectedItemData().name;
			},
			onClickEvent: function() 
			{
			var vcentro = $('#buscar_centros').getSelectedItemData().name;
			}
		}
	};
$("#id_centro_destino").easyAutocomplete(cen_options);
$("#id_centro_destino1").easyAutocomplete(cen_options);
$("#id_centro_destino2").easyAutocomplete(cen_options);
$("#id_centro_destino3").easyAutocomplete(cen_options);
$("#id_centro_destino4").easyAutocomplete(cen_options);
$("#id_centro_destino5").easyAutocomplete(cen_options);
$("#id_centro_destino6").easyAutocomplete(cen_options);
$("#buscar_centros").easyAutocomplete(cen_options);
$("#fcentrosadmin").easyAutocomplete(cen_options);
$("#admin_centros").easyAutocomplete(cen_options);

var nac_options = 
	{
   url:urlbase+"/datosweb/nacionalidades.json",
	//url: "../"+directoriobase+"/datosweb/nacionalidades.json",
	getValue: "name_es",
		list: 
		{
			maxNumberOfElements: 10,
			match: 
			{
			enabled: true
			},
			onKeyEnterEvent: function() 
			{
			var vcentro = $('#buscar_centros').getSelectedItemData().name;
			},
			onClickEvent: function() 
			{
			var vcentro = $('#buscar_centros').getSelectedItemData().name;
			}
		}
	};
$("#nacionalidad").easyAutocomplete(nac_options);

var cen_estudios_options = 
	{
   url:urlbase+"/datosweb/centros_estudios_origen.json",
	getValue: "nc",
    template: {
        type: "description",
        fields: {
            description: "mu"
        }
      },
		list: 
		{
			maxNumberOfElements: 20,
			match: 
			{
			enabled: true
			},
			onKeyEnterEvent: function() 
			{
			var vcentro = $('#buscar_centros').getSelectedItemData().name;
			},
			onClickEvent: function() 
			{
			var vcentro = $('#buscar_centros').getSelectedItemData().name;
			}
		}
	};
$("#id_centro_estudios_origen").easyAutocomplete(cen_estudios_options);
$("#hermanos_admision_id_centro_estudios_origen1").easyAutocomplete(cen_estudios_options);
$("#hermanos_admision_id_centro_estudios_origen2").easyAutocomplete(cen_estudios_options);
$("#hermanos_admision_id_centro_estudios_origen3").easyAutocomplete(cen_estudios_options);
$('.centro_estudios_origen').easyAutocomplete(cen_estudios_options);
$('.id_centro_estudios_origen').easyAutocomplete(cen_estudios_options);
$("#cen_estudios_origen").easyAutocomplete(cen_estudios_options);
$('.hermanos_admision_id_centro_estudios_origen1').easyAutocomplete(cen_estudios_options);


//////////////////////////////////////////////
//DATOS TRIBUTARIOS
$('body').on('change', 'input[type=checkbox][id*=oponenautorizar]', function(e){

});

//////////////////////////////////////////////
//FUNCIONES DE EXPORTACION DATOS EXCEL CSV

$('body').on('click', '.exportcsv', function(e)
{
	var vrol=$('#rol').attr("value");
	var vid=$(this).attr("id");
	var vidcentro=$('#id_centro').text();
	var vid_alumno=$('#id_alumno').attr("value");
	var vprovincia=$('#provincia').attr("value");
	var vsubtipo=$(this).attr("data-subtipo");
	var vestado_convocatoria=$('#estado_convocatoria').attr("value");
	$.ajax({
	method: "POST",
	data: {id_centro:vidcentro,subtipo:vsubtipo,rol:vrol,estado_convocatoria:vestado_convocatoria,id_alumno:vid_alumno,provincia:vprovincia},
	url:'../'+directoriobase+'/scripts/ajax/gen_csvs.php',
	success: function(data) {
			console.log(data);
			window.open(data,'_blank');
	},
	error: function() {
	alert('Problemas generando csv!');
	}
	});

});
//FUNCIONES DE EXPORTACION DATOS PDF

$('body').on('click', '.exportpdf', function(e)
{
  	var vrol=$('#rol').attr("value");
  	var vprovincia=$('#provincia').attr("value");
	var vid=$(this).attr("id");
	var vidcentro=$('#id_centro').text();
   var vestado_convocatoria=$('#estado_convocatoria').attr("value");
  var vsubtipo=$(this).attr("data-subtipo");
	$.ajax({
	  method: "POST",
	  data: {id_centro:vidcentro,tipolistado:vsubtipo,rol:vrol,provincia:vprovincia},
	  url:'../'+directoriobase+'/scripts/ajax/gen_pdfs.php',
	      success: function(data) {
				window.open(data,'_blank');
		},
	      error: function() {
		alert('Problemas generando pdf');
	      }
	});

});


$('body').on('click', '.infobaremo', function(e)
{
   $("#descbaremo").trigger("click");
   $([document.documentElement, document.body]).animate({
        scrollTop: $("#descbaremo").offset().top
    }, 1000);
});
//////////////////////////////////////////////
//FUNCIONES DE IMPRESION
$('body').on('click', '.printsol', function(e)
{
   var vid=$(this).attr("id");
   vid=vid.replace("print",'');
   window.open('imprimirsolicitud.php?id='+vid,'_blank');
});


$('body').on('click', '.printsol_old', function(e){
var vid=$(this).attr("id");
vid=vid.replace("print",'');
	$.ajax({
	  method: "POST",
	  data: {id_alumno:vid},
	  url:'../'+directoriobase+'/scripts/ajax/print_solicitud.php',
	      success: function(data) {
				window.open('imprimirsolicitud.php?id='+vid,'_blank');
		},
	      error: function() {
		alert('Problemas imprimiendo solicitud!');
	      }
	});

});

$('body').on('click', '#descbaremo', function(e){
   $("#imgbaremo").toggle();
});

});

//////////////////////////////////////////////
//FUCNIONES DE AYUDA
function calcEdad(dstring) { // birthday is a date
 var dt = new Date();
  var fnac = dstring.split('-')[0];
  var actual =dt.getYear()+1900;
   return actual-fnac;
 }
function comprobarEmail(email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test(email);
}

function comprobar_nifnie(dni)
	{
	var numero;
	var let;
	var letra;
	var expresion_regular_dni;
      	if(dni.length==9) return 1;
	else return 0; 
	}
function comprobar_nif(dni){
       var numero;
       var let;
       var letra;
       var expresion_regular_dni;
      	//de momento marcamos q tenga 9 caracteres por incluir el nie
      	//if(dni.length==9) return 1;
//	else return 0; 
       expresion_regular_dni = /^\d{8}[a-zA-Z]$/;
       
       if(expresion_regular_dni.test (dni) == true){
          numero = dni.substr(0,dni.length-1);
          let = dni.substr(dni.length-1,1);
          let=let.toUpperCase();
          numero = numero % 23;
          letra='TRWAGMYFPDXBNJZSQVHLCKET';
          letra=letra.substring(numero,numero+1);
          if (letra!=let) {
		return 0;
          }else{
            return 1;
          }
       }else{
          return 0;
       }
       return 0;
}
