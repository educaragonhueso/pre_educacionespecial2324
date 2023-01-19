$(document).ready(function(){
$('body').on('change', 'input[type=checkbox][id=baremo_acogimiento]', function(e){
var bar_def=recalcular_baremo();
var val=$(this).attr("value");
var vrol=$("#rol").attr("value");
if(val=='0')
{
	$(this).attr('value','1');
	$('input[type=hidden][name=baremo_acogimiento]').attr('value','1');
   //mostramos la caja para añadir fichero
   $("#afbaremo_acogimiento").show();
   if(vrol!='alumno' & vrol!='anonimo')
      $("#msg_comprobacion_acogimiento").show();
}
else
{
   $(this).attr('value','0');
   $("button[name=boton_baremo_validar_acogimiento]").text('Validar situación laboral')
   $('#baremo_validar_acogimiento').val('0');
   $('input[type=hidden][name=baremo_acogimiento]').attr('value','0');
   //mostramos la caja para añadir fichero
   $("#afbaremo_acogimiento").hide();
   $("#enlacefjacogimiento").hide();
   $("#borraracogimiento").hide();
   if(vrol!='alumno' & vrol!='anonimo')
      $("#msg_comprobacion_acogimiento").hide();
   var vubicacion=$("#borraracogimiento").attr("data");
   if(vubicacion!=undefined)
      eliminarFichero(vubicacion);
}
});
});
