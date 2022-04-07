<?php
class UtilidadesGmaps{
    private $con;
    private $key='AIzaSyDORxJ68R5GU5pNKhO0fT_icSShE9c94Ic';
   public function __construct($adapter='',$centros_controller='',$centro='',$post=0) 
	{
	   $this->con=$adapter;	
   }
   public function getCoordenadas($direccion){
		$dir = str_replace(' ','+',$direccion);
      $url="https://maps.googleapis.com/maps/api/geocode/json?address=$dir&key=$this->key";
      $result = file_get_contents($url);
      $ll=json_decode($result, true);

      return $ll['results'][0]['geometry']['location'];
   }
   public function getDistanciaLineal($coordorigen,$coorddestino,$unidades){
      $coord1=explode(':',$coordorigen);
      $lat1=$coord1[0];
      $lon1=$coord1[1];
      $coord2=explode(':',$coorddestino);
      $lat2=$coord2[0];
      $lon2=$coord2[1];
      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
      }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) *
cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unidades = strtoupper($unidades);

    if ($unidades == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
   }
}
   public function
getDistanciaGoogle($origen,$destino,$tipoapi='dir',$mode='walking')
   {
      if($tipoapi=='dir')
      {
	      $url =
         "https://maps.googleapis.com/maps/api/directions/json?mode=$mode&origin=".str_replace(' ',
         '+', $origen)."&destination=".str_replace(' ', '+',
      $destino)."&sensor=false&key=$this->key";
      }
      else
      {
      $url
="https://maps.googleapis.com/maps/api/distancematrix/json?mode=$mode&units=imperial&origins=$origen&destinations=$destino&key=$this->key";
      }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      $response = curl_exec($ch);
      curl_close($ch);
      $response_all = json_decode($response);
      $distance = $response_all;
   return $distance;
	}
  public function getDistancia_geo($addressFrom,$addressTo){

		 //Change address format
		    $formattedAddrFrom = str_replace(' ','+',$addressFrom);
		    $formattedAddrTo = str_replace(' ','+',$addressTo);
		    
		    //Send request and receive json data
		    $geocodeFrom =file_get_contents("http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key=$this->key");
		    $outputFrom = json_decode($geocodeFrom);
		    $geocodeTo =file_get_contents("http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key=$this->key");
		    $outputTo = json_decode($geocodeTo);
		    
		    //Get latitude and longitude from geo data
		    $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
		    $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
		    $latitudeTo = $outputTo->results[0]->geometry->location->lat;
		    $longitudeTo = $outputTo->results[0]->geometry->location->lng;
		    
		    //Calculate distance from latitude and longitude
		    $theta = $longitudeFrom - $longitudeTo;
		    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
		    $dist = acos($dist);
		    $dist = rad2deg($dist);
		    $miles = $dist * 60 * 1.1515;
		    $unit = strtoupper($unit);
		    if ($unit == "K") {
			return ($miles * 1.609344).' km';
		    } else if ($unit == "N") {
			return ($miles * 0.8684).' nm';
		    } else {
			return $miles.' mi';
		    }

	}
  public function getCentrosIds()
	{
	$ares=array();
	$sql="SELECT id_centro FROM centros where fase_sorteo<2";
	$res=$this->con->query($sql);
	if(!$res) return $this->con->error;
	while($row=$res->fetch_row())
		$ares[]=$row;
	return  $ares;
	} 
 
}
?>
