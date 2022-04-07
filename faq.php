<?php
include('includes/head.php');
include('includes/menusuperior.php');
$rol=$_SESSION['rol'];
?>
<!------ Include the above in your HEAD tag ---------->

<div class="container ">
   <h1>Preguntas frecuentes proceso admisión Educación Especial</h1> 
 <div class="panel-group" id="faqAccordion">
<br>   <?php include("faq_content.php");?>
<?php if($rol=='admin')
 {
   echo  '<h5><span id="nreg" class="button label label-primary">+</span></h5>';
   echo  '<h5><span id="guardar" class="button label label-primary">Guardar</span></h5>';
 }
?>
 </div>
   <h5><span id="nreg" class="button label label-primary">+</span></h5>
   <h5><span id="guardar" class="button label label-primary">Guardar</span></h5>
    <!--/panel-group-->
</div>
<script>
var q='<div class="panel panel-default "><div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question" contenteditable="true"><h4 class="panel-title titulofaq"><h3> <a href="#" class="in">P: Indica fase del proceso</a></h3></h4></div><div id="question" class="panel-collapse collapse" style="height: 0px;"><div class="panel-body" contenteditable="true"><h5><span class="label label-primary"></span></h5><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using, making it lorous versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>            </div>            </div>        </div>        <hr>';

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
	  data: {datos: htmldata,tipo:"faq"},
	  url:"./scripts/ajax/guardar_doc.php",
	      success: function(data) {
            console.log("Fichero modificado");return;
				$.alert({
					title: data
					});
		},error: function (request, status, error) {
        alert(error);
    }
	});
});
</script>
