$(document).ready(function(){
//var directoriobase='educacionespecial2223';
var directoriobase='educacionespecial2223';
var urlbase='https://preadmespecial.aragon.es/educacionespecial2223/';
//////////////////////////////////////////////////////////////
//COMPROBACIONES DE DATOS VIA SOAP
//COMPROBAR DATOS DE IDENTIDAD, PADRON, FAMILIA NUMEROSA Y DISCAPACIDAD
$('body').on('click', '.comprobar', function(e)
{
   $(this).prop('disabled', true);
   var vid=$(this).attr("name");
   var vtipocomprobacion=$(this).attr("tipo");
   if(vtipocomprobacion=='identidad')
      vid=vid.replace('boton_comprobar_'+vtipocomprobacion,'');
   else
      vid=vid.replace('boton_baremo_comprobar_'+vtipocomprobacion,'');
   vnif_alumno=$("#dni_alumno").val();
   vnif1=$("#dni_tutor1").val();
   vnif2=$("#dni_tutor2").val();
   vnombre=$("#nombre").val();
   vfnac=$("#fnac").val();
   vapellido1=$("#apellido1").val();
   vapellido2=$("#apellido2").val();
   var vestado_convocatoria=$('#estado_convocatoria').attr("value");
   var vrol=$('#rol').attr("value");
      $.ajax({
        method: "POST",
        url: "../"+directoriobase+"/scripts/soap/soap_principal.php",
        data: {id_alumno:vid,rol:vrol,estado_convocatoria:vestado_convocatoria,nif1:vnif1,nif2:vnif2,apellido1:vapellido1,apellido2:vapellido2,nombre:vnombre,fnac:vfnac,tipo:vtipocomprobacion,nif_alumno:vnif_alumno},
            success: function(data) {
               console.log(data);
               alert(data);
               $('.comprobar').prop('disabled', false);
            },
            error: function() {
               alert('Error comprobando identidad');
               $(this).prop('disabled', false);
            }
      });
});
$('body').on('click', 'button[name*=boton_comprobar_identidadss]', function(e)
{
var vid=$(this).attr("name");
vid=vid.replace('boton_comprobar_identidad','');
vnif1=$("#dni_tutor1").val();
vnif2=$("#dni_tutor2").val();
vnombre=$("#nombre").val();
vfnac=$("#fnac").val();
vapellido1=$("#apellido1").val();
vapellido2=$("#apellido2").val();
console.log("nif tutor1: "+vnif1);
var vestado_convocatoria=$('#estado_convocatoria').attr("value");
var vrol=$('#rol').attr("value");
	$.ajax({
	  method: "POST",
	  url: "../"+directoriobase+"/scripts/soap/soap_comprobar_identidad.php",
	  data: {id_alumno:vid,rol:vrol,estado_convocatoria:vestado_convocatoria,nif1:vnif1,nif2:vnif2,apellido1:vapellido1,apellido2:vapellido2,nombre:vnombre,fnac:vfnac},
	      success: function(data) {
	         console.log(data);
            alert('Respuesta comprobación identidad: '+data);
         },
	      error: function() {
		alert('Eror comprobando identidad');
	      }
	});
});

$('body').on('click', 'button[name*=boton_baremo_comprobar_padronss]', function(e)
{
var vid=$(this).attr("name");
vid=vid.replace('boton_baremo_comprobar_padron','');
vnif1=$("#dni_tutor1").val();
vnif2=$("#dni_tutor2").val();
vnombre=$("#nombre").val();
vfnac=$("#fnac").val();
vapellido1=$("#apellido1").val();
vapellido2=$("#apellido2").val();
console.log("nif tutor1: "+vnif1);
var vestado_convocatoria=$('#estado_convocatoria').attr("value");
var vrol=$('#rol').attr("value");
	$.ajax({
	  method: "POST",
	  //url: "../'+directoriobase+'/scripts/ajax/comprobar_padron.php",
	  url: "../'+directoriobase+'/scripts/soap/soap_comprobar_padron.php",
	  data: {id_alumno:vid,rol:vrol,estado_convocatoria:vestado_convocatoria,nif1:vnif1,nif2:vnif2,apellido1:vapellido1,apellido2:vapellido2,nombre:vnombre,fnac:vfnac},
	      success: function(data) {
	         console.log(data);
            alert('Respuesta comprobación padrón: '+data);
         },
	      error: function() {
		alert('Eror comprobando padrón');
	      }
	});
});

});
