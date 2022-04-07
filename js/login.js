$(document).ready(function(){
   console.log("Cargando credenciales");
$('body').on('click', '#verfcredenciales', function(e){
   console.log("credenciales");
   $('#concorreo').hide();
   $('#concredenciales').toggle();
});
$('body').on('click', '#verfcorreo', function(e){
   console.log("concorreo");
   $('#concredenciales').hide();
   $('#concorreo').toggle();
});
});

