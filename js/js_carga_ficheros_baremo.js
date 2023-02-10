$(document).ready(function(){
console.log("CARGANDO FUNC BAREMO");
var urlbase=edicion;
var fbaremohtml='<form enctype="multipart/form-data" action="/scripts/ajax/subirfichero.php"  method="post" id="fpruebas"><input type="file" name="baremo_file_domicilio" id="fbaremo_domicilio"></form>';

$('body').on('change', '.fbaremo_old', function(e)
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
					title: data,
               content: ''
					});
		},error: function (request, status, error) {
        alert(error);
    }
	});
}

function subirfichero(fdata,token,tipo) {
   console.log("subiendo fichero de tipo: "+tipo);
   $.ajax({
        url: 'scripts/fetch/subir_fichero_baremo.php', 
        type: 'post',
        data: fdata,
        contentType: false,
        processData: false,
        success: function (response) {
         var extension=response.substr(response.length - 4);
         console.log("subiendo fichero de tipo: "+extension);
         console.log("subido urlbase: "+urlbase);
         stipo=tipo.replace("fbaremo_","");
         var ubicacionweb='scripts/fetch/ficherosbaremo/'+token+'/'+tipo+extension;
         var ubicacionsistema=directorio_base+'/scripts/fetch/ficherosbaremo/'+token+'/'+tipo+extension;
         var subicacionsistema=directorio_base+'/scripts/fetch/ficherosbaremo/'+token+'/'+stipo+extension;
         var enlacefichero='<a id="enlacefj'+stipo+'" class="enlacefbaremo" href="'+ubicacionweb+'" target="_blank">Ver fichero</a>';
         var enlace_borrar_fichero='<a id="borrar'+stipo+'" class="enlacefbaremo enlaceborrarfichero" data="'+ubicacionsistema+'" target="_blank">Retirar fichero</a>';
         $("#caja_"+tipo).after(enlace_borrar_fichero);
         $("#caja_"+tipo).after(enlacefichero);
        }
      });
}
function subirfichero_old(fdata,token,tipo) {
   console.log("subiendo fichero a: "+token);
   
   var ubicacion='/scripts/fetch/ficherosbaremo/'+token+'/'+tipo+'.pdf';
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
