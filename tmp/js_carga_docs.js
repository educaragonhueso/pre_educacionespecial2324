$(document).ready(function(){
let idalumno="idalumno2"
let filesDone = 0
let filesToDo = 0
let progressBar = document.getElementById('progress-bar')
let token = $('#token').attr("value")

$('body').on('change', '#fileElem', function(e){
handleFiles(this.files);

});
$('body').on('click', 'img', function(e){
$(this).remove();

});
function handleFiles0(files) {
  ([...files]).forEach(uploadFile)
}

function handleFiles(files)
{
  files = [...files]
  initializeProgress(files.length) // <- Add this line
  files.forEach(uploadFile0)
  files.forEach(previewFile)
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
  let url = 'scripts/fetch/get_docs.php'
  let formData = new FormData()
  formData.append('file', file)
  formData.append('token', token)
   console.log("Añadiendo alumno: "+idalumno);
   console.log(formData);

}
function uploadFile0(file) {
  let url = 'scripts/fetch/get_docs.php'
  let formData = new FormData()
  formData.append('file', file)
  formData.append('token', token)
   console.log("Añadiendo file: "+file);
   console.log("Añadiendo alumno: "+idalumno);
   console.log(formData);

  fetch(url, {
    method: 'POST',
    body: formData
  })
   .then(progressDone)
   .then(console.log("añadido fichero"))
  .catch(() => {console.log("ERROR") /* Error. Inform the user */ })
}
function previewFile(file) {
  let reader = new FileReader()
  reader.readAsDataURL(file)
  reader.onloadend = function() {
    let img = document.createElement('img')
    img.src = reader.result
    document.getElementById('gallery').appendChild(img)
  }
}
});
