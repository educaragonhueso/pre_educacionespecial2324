<?php
######################
# script para modificar/editar y crear solicitudes
######################

//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################

$ndata=$_POST['datos'];
$tipo=$_POST['tipo'];
$ndata='<div id="faqAccordion">'.$ndata."</div>";
if($tipo=='faq')
   $fw = fopen("../../faq_content.php", "w");
else
   $fw = fopen("../../hitos_content.php", "w");
fwrite($fw,$ndata);
fclose($fw);
?>
