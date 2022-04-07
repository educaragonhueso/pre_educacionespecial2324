<?php
$script_doc_alumno="
<script>
let filesDone = 0
let filesToDo = 0
var form_data=new FormData();
let progressBar2 = document.getElementById('progress-bar')
let dropArea2 = document.getElementById('drop-area');
let id_alumno = $('#id_alumno').attr('value')
console.log('alumno id: ');
form_data.append('id_alumno',id_alumno);


;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dropArea2.addEventListener(eventName, preventDefaults, false)
})
;['dragenter', 'dragover'].forEach(eventName => {
  dropArea2.addEventListener(eventName, highlight, false)
})

;['dragleave', 'drop'].forEach(eventName => {
  dropArea2.addEventListener(eventName, unhighlight, false)
})

function previewFiles(files)
{
  files = [...files]
  initializeProgress(files.length) // <- Add this line
  //files.forEach(uploadFile)
  files.forEach(previewFile)
}
function handleFiles(files)
{
  files = [...files]
  files.forEach(uploadFile)
}

//PROGRESO BAR
function initializeProgress(numfiles) {
   progressBar2.value = 0
  filesDone = 0
  filesToDo = numfiles
}

function progressDone() {
  filesDone++
  progressBar2.value = filesDone / filesToDo * 100
}


function highlight(e) {
  dropArea2.classList.add('highlight')
}

function unhighlight(e) {
  dropArea2.classList.remove('highlight')
}

dropArea2.addEventListener('drop', handleDrop, false)


function handleDrop(e) {
  let dt = e.dataTransfer
  let files = dt.files

  handleFiles(files)
}

function uploadFile(file) {
console.log('subiendo fichero ....');
$.ajax({
     url: 'scripts/fetch/get_docs2.php', 
     type: 'post',
     data: form_data,
     contentType: false,
     processData: false,
     success: function (response) {
            console.log('subido'+response);
     }
   });
}
function previewFile(file) {
   console.log('EN PREVIEW FILE DINAMICO')
   var ext=file['name'].replace(/^.*\./, '');
  
  let reader = new FileReader()
  reader.readAsDataURL(file)
   
  let salto = document.createElement('br')
  nombrefichero=file['name']
  reader.onloadend = function() {
    if(ext=='pdf')
    {
      let doc = document.createElement('div')
      let pdf = document.createElement('p')
      let boton = document.createElement('button')
      $(pdf).attr('class','docpdf')
      $(pdf).attr('id',nombrefichero)
      $(pdf).text(file['name'])
      $(boton).text('Retirar documento')
      document.getElementById('gallery').appendChild(salto)
      $('#gallery').append(boton)
      $('#gallery').append(pdf)
      //document.getElementById('gallery').appendChild(titulo)
    }
    else
    {   
    let img = document.createElement('img')
    let boton = document.createElement('button')
    $(boton).attr('class','bdoc')
    $(boton).attr('fichero',nombrefichero)
    $(boton).text('Retirar imagen')
    $(img).attr('id',nombrefichero)
    $(img).attr('width','400')
    img.src = reader.result
    document.getElementById('gallery').appendChild(salto)
    document.getElementById('gallery').appendChild(boton)
    document.getElementById('gallery').appendChild(salto)
    document.getElementById('gallery').appendChild(img)
    }
  }
}
</script>

";
?>

