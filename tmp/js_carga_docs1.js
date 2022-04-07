$(document).ready(function(){
$('body').on('change', '#fileElem', function(e){
handleFiles(this.files);

});
function handleFiles(files) {
   console.log(files);
  //files.forEach(uploadFile)
  //files.forEach(previewFile)
}
console.log("Cargando draganddrop");
});
