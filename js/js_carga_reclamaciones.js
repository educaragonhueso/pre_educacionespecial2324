$(document).ready(function(){
console.log("CARGANDO REC, CONVOCATORIA: "+convocatoria);
let filesDone = 0
let filesToDo = 0
let progressBar = document.getElementById('progress-bar')
let token = $('#token').attr("value")
let id_alumno = $('#id_alumno').attr('value')
let dropArea = document.getElementById('drop-area');

var form_data=new FormData();
var totalfiles=0;
form_data.append("token",token);
form_data.append("id_alumno",id_alumno);

$('body').on('change', '#freclamaciones', function(e){
   var ficheros=[];
   console.log("En rec, añadiendo ficheros baremo")
   previewFilesRec(this.files);
   console.log("numero de ficheros: "+this.files.length)
   for (var index = 0; index < this.files.length; index++) 
   {
      form_data.append("files[]",this.files[index]);
   }
      uploadFileRecBaremo("f");
});
//borrar eliminar fichero reclamaciones
$('body').on('click', '.bdoc,.bdocfile', function(e){
   if (!confirm("Se eliminará el fichero, continuar?")){
      return false;
      }
   var fichero=$(this).attr("fichero"); 
   var fichero_original=$(this).attr("ficherooriginal"); 
   var clase=$(this).attr("class");
   var rol=$("#rol").val();
   if(clase=='bdocfile')
   {
      console.log("ID FICHERO PDF A  ELIMINAR: "+fichero_original);
      $("#"+fichero).remove();
   }
   else
   {
      console.log("FICHERO A ELIMINAR: "+fichero_original);
      $("#"+fichero).remove();
   }
   $(this).parent('div').remove();
   $(this).find('div').remove();
   if(rol=='centro')
      var id_alumno=$("#id_alumno").attr("value");

   var id_alumno=$("#id_alumno").attr("value");
   eliminarFichero(fichero_original,id_alumno);   
});

function eliminarFichero (f,id) 
{
   var vrol=$("#rol").attr("value");
   console.log("eliminando doc de idalumno "+id_alumno);
	$.ajax({
	  method: "POST",
	  data: {id_alumno:id,fichero:f,token:token,rol:vrol},
	  url:'../'+convocatoria+'/scripts/ajax/borrar_reclamaciones.php',
	      success: function(data) {
				$.alert({
					title: data,
               content:''
					});
         
		},error: function (request, status, error) {
        alert(error);
    }
	});
}

$('body').on('click', '.docalumno', function(e){
   console.log("quitando fichero: "+$(this).attr("id"))
if (!confirm("Se eliminará el fichero, continuar?")){
      return false;
    }
   $(this).remove();
   
});


;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dropArea.addEventListener(eventName, preventDefaults, false)
})
function preventDefaults (e) {
  e.preventDefault()
  e.stopPropagation()
}

;['dragenter', 'dragover'].forEach(eventName => {
  dropArea.addEventListener(eventName, highlight, false)
})

;['dragleave', 'drop'].forEach(eventName => {
  dropArea.addEventListener(eventName, unhighlight, false)
})

function previewFilesRec(files)
{
  files = [...files]
  initializeProgress(files.length) // <- Add this line
  //files.forEach(uploadFile)
  files.forEach(previewFileRec)
}
function handleFiles(files)
{
  files = [...files]
  files.forEach(uploadFileRec)
}

//PROGRESO BAR
function initializeProgress(numfiles) {
   if(!progressBar) return;
   progressBar.value = 0
  filesDone = 0
  filesToDo = numfiles
}

function progressDone() {
  filesDone++
  progressBar.value = filesDone / filesToDo * 100
}


function highlight(e) {
  dropArea.classList.add('highlight')
}

function unhighlight(e) {
  dropArea.classList.remove('highlight')
}

dropArea.addEventListener('drop', handleDrop, false)


function handleDrop(e) {
  let dt = e.dataTransfer
  let files = dt.files

  handleFiles(files)
}

function uploadFileRecBaremo(file) {
let id_alumno = $('[name=breclamaciones]').attr('id')
id_alumno=id_alumno.replace('reclamacion','');
console.log("subiendo id_alumno: "+id_alumno);
form_data.append('id_alumno',id_alumno);
console.log(form_data);
$.ajax({
     url: 'scripts/fetch/get_reclamaciones_baremo.php', 
     type: 'post',
     data: form_data,
     contentType: false,
     processData: false,
     success: function (response) {
            console.log("En reclamaciones baremo, subido"+response);
     }
   });
}
function previewFileRec(file) {
   console.log("EN PREVIEW FILE INICIAL")
   var ext=file['name'].replace(/^.*\./, '');
  
  let reader = new FileReader()
  reader.readAsDataURL(file)
   
  let salto = document.createElement('br')
  nombrefichero=file['name']
  reader.onloadend = function() {
    if(ext=='pdf')
    {
      let idfile=nombrefichero.replace(".","")
      idfile=idfile.replace(" ","_")
      let doc = document.createElement('div')
      let pdf = document.createElement('p')
      let boton = document.createElement('button')
      $(pdf).attr("class","docpdf")
      $(pdf).attr("id",idfile)
      $(pdf).text(file['name'])
      $(boton).text("Retirar documento")
      $(boton).attr("class","bdocfile")
      $(boton).attr("ficherooriginal",nombrefichero)
      $(boton).attr("fichero",idfile)
      document.getElementById('gallery').appendChild(salto)
      $("#gallery").append(boton)
      $("#gallery").append(pdf)
    }
    else
    {   
       let img = document.createElement('img')
       let boton = document.createElement('button')
       let idimg=nombrefichero.replace(".","")
       idimg=idimg.replace(" ","_")
       $(boton).attr("class","bdoc")
       $(boton).attr("fichero",idimg)
       $(boton).attr("ficherooriginal",nombrefichero)
       $(boton).text("Retirar imagen")
       $(img).attr("id",idimg)
       img.src = reader.result
       document.getElementById('gallery').appendChild(salto)
       document.getElementById('gallery').appendChild(boton)
       document.getElementById('gallery').appendChild(salto)
       document.getElementById('gallery').appendChild(img)
       document.getElementById('gallery').appendChild(salto)
    }
  }
}
});
