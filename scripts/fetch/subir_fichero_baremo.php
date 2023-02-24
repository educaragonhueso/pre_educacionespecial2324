<?php
$destino='./ficherosbaremo/'.$_POST["token"]."/";
$campobaremo=$_POST["campobaremo"];
$tammax=10000000;
$ntammax=round($tammax/1024/1024);
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
      $size =$_FILES['files']['size'][0];
      $nsize=round($size/1024);
      if($size>=$tammax){ print("ERROR FICHERO DEMASIADO GRANDE, $nsize KB, TAMAÑO MÁXIMO $ntammax MB");exit();}
      // Get extension
      $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
      // Valid image extension
      $valid_ext = array("pdf","png","jpeg","jpg");
      // Check extension
      if(in_array($ext, $valid_ext))
      {
         // File path
         $filename =$campobaremo.".".$ext;
         $path = $upload_location.$filename;
         // Upload file
         if(move_uploaded_file($_FILES['files']['tmp_name'][0],$path)){
            print($size.$path);exit();
            $files_arr[] = $path;
         }
      }
}

echo json_encode($files_arr);
die;

