$(document).ready(function(){
//LISTADO SOLICITUDES FASEII
$(".lfinales").click(function () {
 console.log("en fase final"); 
  $("#mapcontrol").hide();
  $("#map-canvas").hide();
  var vpdf='1';
  var vrol=$('#rol').attr("value");
  var vidcentro=$('#id_centro').html();
  var vid_alumno=$('#id_alumno').attr("value");
  var vsubtipo=$(this).attr("data-subtipo");
   console.log("id ade alumno final: "+vid_alumno);
  var vestado_convocatoria=$('#estado_convocatoria').val();
	$.ajax({
	  method: "POST",
	  url: "../"+edicion+"/scripts/ajax/listados_solicitudes_fase2_finales.php",
	  data: {rol:vrol,subtipo:vsubtipo,pdf:vpdf,estado_convocatoria:vestado_convocatoria,id_centro:vidcentro,id_alumno:vid_alumno},
	  success: function(data) {
				$("#mapcontrol").hide();
				$("#map-canvas").hide();
				$("#l_matricula").html(data);
				$(".container").hide();
	      },
	      error: function() {
		alert('Error LISTANDO solicitudes: '+vsubtipo);
	      }
	});
});
//LISTADO SOLICITUDES PARA MATRICULA EN FASE FINAL
$(".show_matricula_final").click(function () {
  var vrol=$('#rol').attr("value");
  var vidcentro=$('#id_centro').html();
  var vid_alumno=$('#id_alumno').attr("value");
  var vsubtipo=$(this).attr("data-subtipo");
   console.log("id ade alumno final: "+vid_alumno);
  var vestado_convocatoria=$('#estado_convocatoria').val();
	$.ajax({
	  method: "POST",
	  url: "../educacionespecial/scripts/ajax/listados_solicitudes_matricula_final.php",
	  data: {rol:vrol,subtipo:vsubtipo,estado_convocatoria:vestado_convocatoria,id_centro:vidcentro,id_alumno:vid_alumno},
	  success: function(data) {
				$("#mapcontrol").hide();
				$("#map-canvas").hide();
				$("#l_matricula").html(data);
				$(".container").hide();
	      },
	      error: function() {
		alert('Error LISTANDO solicitudes: '+vsubtipo);
	      }
	});
});
//MATRICULAR FASE FINAL
$('body').on('click', '.matriculafinal', function(e){
 console.log("en matricula fase final"); 
  var vrol=$('#rol').attr("value");
  var vid_alumno=$(this).attr("id");
  vid_alumno=vid_alumno.replace("matricular","");
  var vestado=$(this).text();
	$.ajax({
	  method: "POST",
	  url: "../educacionespecial/scripts/ajax/matricular_alumno_fasefinal.php",
	  data: {rol:vrol,id_alumno:vid_alumno,estado:vestado},
	  success: function(data) {
            console.log(data);
            console.log(vid_alumno);
	         alert("Alumno Matriculado/Desmatriculado correctamente");
            if(vestado=='Matricular')
            $("#matricular"+vid_alumno).html("Desmatricular");
            else
            $("#matricular"+vid_alumno).html("Matricular");
         },
	      error: function() {
		alert('Error matriculando/desmatriculando alumno');
	      }
	});
});

});//FIN GETREADY
