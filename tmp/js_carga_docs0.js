$(document).ready(function(){
let idalumno="idalumno2"
let filesDone = 0
let filesToDo = 0
let progressBar = document.getElementById('progress-bar')
let token = $('#token').attr("value")

var form_data=new FormData();
var totalfiles=0;
form_data.append("token",token);

$('#submiti1').click(function(){
   // Read selected files
   var fileList = $('#fileElem').prop("files");
   var form_data =  "";

   form_data = new FormData();
   totalfiles = fileList.length;
   console.log("TOTAL FICHEROS: "+totalfiles);
   for (var index = 0; index < totalfiles; index++) 
   {
      console.log("Añadiendo fichero: "+index);
      
      form_data.append("files[]", fileList);
      uploadFile(fileList[index]);
   }
  //ficheros = [...ficheros]
  //ficheros.forEach(uploadFile)
});
$('#submit0').click(function(){
   // Read selected files
   var fileList = $('#fileElem').prop("files");
   var form_data =  "";

   form_data = new FormData();
   form_data.append("files[]", fileList[cl]);
   totalfiles = ficheros.length;
   console.log("TOTAL FICHEROS: "+totalfiles);
   for (var index = 0; index < totalfiles; index++) 
   {
      console.log("Añadiendo fichero: "+index);
      
      form_data.append("files[]", document.getElementById('fileElem').files[index]);
      uploadFile(ficheros[index]);
   }
  //ficheros = [...ficheros]
  //ficheros.forEach(uploadFile)
});

$('body').on('change', '#fileElem', function(e){
   var ficheros=[];
   previewFiles(this.files);
   console.log("numero de ficheros: "+this.files.length)
   var file = document.getElementById('fileElem').files;
      form_data.append("files[]",this.files[0]);
      uploadFile("f");
      form_data.append("files[]",this.files[1]);
      uploadFile("f");
   ficheros.push(file);
   console.log(ficheros.length);
   totalfiles = ficheros.length;
   console.log("TOTAL FICHEROS: "+totalfiles);
   for (var index = 0; index < totalfiles; index++) 
   {
      console.log("Añadiendo fichero: "+index);
      
      form_data.append("files[]", document.getElementById('fileElem').files[index]);
      uploadFile("f");
   }
   //ficheros = [...ficheros]
   //ficheros.forEach(uploadFile)
});

$('body').on('click', '#ssubirdocs', function(e){
console.log("uploading");
var totalfiles = document.getElementById('fileElem').files.length;
var tfiles = document.getElementById('fileElem').files;
  console.log(totalfiles);
//handleFiles(tfiles);

});
$('body').on('click', 'img', function(e){
   console.log("quitando fichero: "+$(this).attr("id"))
   $(this).remove();
   for (var index = 0; index < totalfiles; index++) 
   {
      print("fff"+index);
      //print(form_data);
   }
   
});
$('body').on('click', '.docpdf', function(e){
   console.log("quitando fichero: "+$(this).attr("id"))
   $(this).remove();

});

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
  progressBar.value = 0
  filesDone = 0
  filesToDo = numfiles
}

function progressDone() {
  filesDone++
  progressBar.value = filesDone / filesToDo * 100
}

console.log("Cargando draganddrop");
let dropArea = document.getElementById('drop-area')

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

function uploadFile(file) {
console.log("subiendo");
$.ajax({
     url: 'scripts/fetch/get_docs2.php', 
     type: 'post',
     data: form_data,
     contentType: false,
     processData: false,
     success: function (response) {
            console.log("subido"+response);
     }
   });
}
function previewFile(file) {
   console.log(file)
   var ext=file['name'].replace(/^.*\./, '');
  
  let reader = new FileReader()
  reader.readAsDataURL(file)
   
  nombrefichero=file['name']
  reader.onloadend = function() {
    if(ext=='pdf')
    {
      let pdf = document.createElement('p')
      $(pdf).attr("class","docpdf")
      $(pdf).attr("id",nombrefichero)
      $(pdf).text(file['name'])
      document.getElementById('gallery').appendChild(pdf)
    }
    else
    {   
    let img = document.createElement('img')
    $(img).attr("id",nombrefichero)
    img.src = reader.result
    document.getElementById('gallery').appendChild(img)
    }
  }
}
});
