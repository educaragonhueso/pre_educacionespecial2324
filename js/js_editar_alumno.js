$(document).ready(function(){
let filesDone = 0
let filesToDo = 0
let progressBar = document.getElementById('progress-bar')
let token = $('#token').attr("value")
let dropArea = document.getElementById('drop-area');

var form_data=new FormData();
var totalfiles=0;
form_data.append("token",token);

$('body').on('change', '#fileElem', function(e){
   var ficheros=[];
   console.log("añadiendo ficheros")
   previewFiles(this.files);
   console.log("numero de ficheros: "+this.files.length)
   for (var index = 0; index < this.files.length; index++) 
   {
      form_data.append("files[]",this.files[index]);
   }
      uploadFile("f");
});

$('body').on('click', '.bdoc', function(e){
   if (!confirm("Se eliminará el fichero, continuar?")){
      return false;
      }
   
   console.log($(this).closest('div').find('figure'));
   $(this).closest('div').find('figure').remove();
   $(this).next().find('.imageZoom').remove();
   $(this).remove();
   
});

$('body').on('click', '.docalumno', function(e){
   console.log("quitando fichero: "+$(this).attr("id"))
if (!confirm("Se eliminará el fichero, continuar?")){
      return false;
    }
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

;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dropArea.addEventListener(eventName, preventDefaults, false)
})

});
