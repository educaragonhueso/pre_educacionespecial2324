<?php
session_start();
include('includes/sesion.php');
include('includes/head.php');
include('includes/menusuperior.php');
print_r($_SESSION);
?>

<div class="container ">
   <h1>Formulario de reclamaciones lista provisional</h1> 
   <div class="row">
     <div class="col-md-4">
         <div class="md-form mb-0">
         Describe el motivo de la reclamación
             <input type="text" id="motivo_reclamacion" value="" name="nombre" placeholder="Motivo reclamación"  class="form-control" required>
         </div>
     </div>
   </div>
   <div class="row">
      <div id="drop-area" style="margin:25px">';
         <form id="idformfiledata" class="my-form">
            <p>Añade los documentos necesarios</p>
            <input type="file" id="fileElem" name="files[]" multiple accept="pdf,image/*">
            <label class="button" for="fileElem">Selecciona documentos</label>
         </form>
         <progress id="progress-bar" max=100 value=0></progress>';
         <div id="reclamaciones_baremo"></div>
      </div>
   </div>
</div>
<script>

$('body').on('click', '#nreg', function() {
   var qidlast=$('.panel-collapse').last().attr("id");
   idlast=qidlast.replace("question","");
   idnuevo=parseInt(idlast)+1;
   q=q.replace("question","question"+idnuevo);
   var htmldata=$("#faqAccordion").html();
   $("#faqAccordion").append(q);
});

$('body').on('click', '#guardar', function() {
   var htmldata=$("#faqAccordion").html();
	$.ajax({
	  method: "POST",
	  data: {datos: htmldata,tipo:"hitos"},
	  url:"./scripts/ajax/guardar_doc.php",
	      success: function(data) {
            console.log("Fichero modificado"+data);return;
				$.alert({
					title: data
					});
		},error: function (request, status, error) {
        alert(error);
    }
	});
});
</script>
