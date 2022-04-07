<?php
$destino='./ficherosbaremo/'.$_POST["token"]."/";
$campobaremo=$_POST["campobaremo"];

if(!is_dir($destino))
   mkdir($destino);
// Upload directory
$upload_location = $destino;
// To store uploaded files path
$files_arr = array();

if(isset($_FILES['files']['name'][0]) && $_FILES['files']['name'][0] != '')
{
      // cambiamos el nombre

      $filename =$_FILES['files']['name'][0];

      // Get extension
      $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

      // Valid image extension
      $valid_ext = array("pdf","png","jpeg","jpg");

      // Check extension
      if(in_array($ext, $valid_ext)){
         // File path
         $filename =$campobaremo.".pdf";
         $path = $upload_location.$filename;
         // Upload file

         if(move_uploaded_file($_FILES['files']['tmp_name'][0],$path)){
            print($path);exit();
            $files_arr[] = $path;
         }
      }
}

echo json_encode($files_arr);
die;

