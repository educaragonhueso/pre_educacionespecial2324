$(document).ready(function(){
console.log("CARGANDO FUNC BAREMO");
var urlbase='educacionespecial2223/';
var fbaremohtml='<form enctype="multipart/form-data" action="/scripts/ajax/subirfichero.php"  method="post" id="fpruebas"><input type="file" name="baremo_file_domicilio" id="fbaremo_domicilio"></form>';

$('body').on('change', '.fbaremo', function(e)
{
   var form_data=new FormData();
   alert("Solo puedes subir un fichero, si ya hay alguno será sustituido por el que agregues a continuación");
   console.log("añadiendo fichero de baremo")
   $(this).parent().parent().next(".enlacefbaremo").remove();
   var campobaremo=$(this).attr("id");
   var token=$("#token").attr("value");
   console.log("token: "+token)
   form_data.append('campobaremo',campobaremo);
   form_data.append('token',token);
   form_data.append("files[]",this.files[0]);
   console.log(this.files);
   var tipo=$(this).attr("id");
   //primero borramos los ficheros q haya
   $(this).parent().next('.enlacefbaremo').remove();
   $(this).parent().next('.enlaceborrarfichero').remove();
   subirfichero(form_data,token,tipo);
   var tjustificante=tipo.substring(8);
   console.log("JUSTIFICANTE: "+tjustificante);
   document.getElementById('fbaremo_'+tjustificante).value = null;
});

$('body').on('click', '.enlaceborrarfichero', function(e)
{
   if (!confirm("Se eliminará el fichero, continuar?"))
      return false;
   $(this).prev('a').remove();
   $(this).remove();
   var vubicacion=$(this).attr("data");
   eliminarFichero(vubicacion);
  
});

function eliminarFichero (ub) 
{
   console.log("eliminando doc de baremo: "+ub);
	$.ajax({
	  method: "POST",
	  data: {ubicacion:ub},
     url: 'scripts/fetch/borrar_fichero_baremo.php', 
	      success: function(data) {
            console.log(data);
				$.alert({
					title: data
					});
		},error: function (request, status, error) {
        alert(error);
    }
	});
}

function subirfichero(fdata,token,tipo) {
   console.log("subiendo fichero a: "+token);
   
   var ubicacion='/educacionespecial2223/scripts/fetch/ficherosbaremo/'+token+'/'+tipo+'.pdf';
   console.log("subiendo fichero a: "+ubicacion);
   var enlacefichero='<a class="enlacefbaremo" href="'+ubicacion+'" target="_blank">Ver fichero</a>';
   var enlace_borrar_fichero='<a class="enlacefbaremo enlaceborrarfichero" data="'+ubicacion+'" target="_blank">Retirar fichero</a>';
   $.ajax({
        url: 'scripts/fetch/subir_fichero_baremo.php', 
        type: 'post',
        data: fdata,
        contentType: false,
        processData: false,
        success: function (response) {
         console.log("subido"+response);
         console.log("tipo"+tipo);
         $("#caja_"+tipo).after(enlace_borrar_fichero);
         $("#caja_"+tipo).after(enlacefichero);
        }
      });
}
});
