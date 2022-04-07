<?php
include('includes/head.php');
include('includes/menusuperior.php');
?>
<!------ Include the above in your HEAD tag ---------->

<div class="container ">
    <div class="panel-group" id="faqAccordion">
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question0">
                 <h4 class="panel-title titulofaq">
                   <h3> <a href="#" class="in">P: Incio del proceso</a></h3>
              </h4>

            </div>
            <div id="question0" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body" contenteditable="true">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five <a href="http://jquery2dotnet.com/" class="label label-success">http://jquery2dotnet.com/</a> centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                </div>
            </div>
        </div>
        <hr>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question1">
                 <h4 class="panel-title titulofaq">
                   <h3> <a href="#" class="in">P: Primera fase del proceso</a></h3>
              </h4>

            </div>
            <div id="question1" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body" contenteditable="true">
                     <h5><span class="label label-primary">Answer</span></h5>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                </div>
            </div>
        </div>
        <hr>
        
    </div>
   <h5><span id="nreg" class="button label label-primary">+</span></h5>
   <h5><span id="guardar" class="button label label-primary">Guardar</span></h5>
    <!--/panel-group-->
</div>
<script>
var q='<div class="panel panel-default "><div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question" contenteditable="true"><h4 class="panel-title titulofaq"><h3> <a href="#" class="in">P: Tercera fase del proceso</a></h3></h4></div><div id="question" class="panel-collapse collapse" style="height: 0px;"><div class="panel-body" contenteditable="true"><h5><span class="label label-primary">Answer</span></h5><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using, making it lorous versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>            </div>            </div>        </div>        <hr>';

$('body').on('click', '#nreg', function() {
   var qidlast=$('.panel-collapse').last().attr("id");
   idlast=qidlast.replace("question","");
   idnuevo=parseInt(idlast)+1;
   q=q.replace("question","question"+idnuevo);
   console.log($("#faqAccordion").html());
   var htmldata=$("#faqAccordion").html();
   $("#faqAccordion").append(q);
});

$('body').on('click', '#guardar', function() {
   var htmldata=$("#faqAccordion").html();
	$.ajax({
	  method: "POST",
	  data: {datos: htmldata},
	  url:"./scripts/ajax/guardar_faq.php",
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
