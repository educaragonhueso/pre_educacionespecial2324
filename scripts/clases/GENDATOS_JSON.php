<?php 
//include '/datos/www/preestadisticaeducativa.aragon.es/public_estadisticaeducativa/scripts/config.php';
class GENDATOS_JSON {
  public $dim_form; 
  public $dim_campos; 
  public $query; 
  public function __construct($ruta='',$dim_form=array(),$dim_campos=array(),$tabla='',$tipo='graficos',$post=0,$cevolutivo=''){
   $db_cfg=require_once('../../config/config_database.php');

   $user=$db_cfg["user"];
   $pass=$db_cfg["pass"];
   $database=$db_cfg["database"];
	$this->conexion = new \mysqli('127.0.0.1',$user,$pass,$database);
	
	if (!$this->conexion->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	    	exit();
	}
	if ($this->conexion->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
	} 
	}

  public function genJsonData($sql='',$f)
  {
      //generamos csv y json	
      $fcsv=$f.".csv";
      $csv=$this->genCsv($fcsv,$sql);
      print("Generado csv, generando json...$csv");
      $fjson=$f.".json";
      
      $cjson_php=$this->genJsonPhp($fcsv,$fjson,1,$sql);
      print($cjson_php);
      //print("FICHEROi JSON: ");print($this->fjson);exit();
      return $fjson;
	}
  public function ejecutarConsulta($q){
		if(!$this->post) print("EJECUTANDO CONSULTA: ".PHP_EOL);
		if(!$this->post) print($q.PHP_EOL);
		$result= $this->conexion->query($q);
		if($result->num_rows > 0) 
		{
    		while($row = $result->fetch_assoc()) {
					$res[]=$row;
				}
			return $res;
		}
		else
		{
			print("ERROR"); 
			print($result->error); 
			return 0;
		}
	}
   public function executeQuery($q)
   {
	   print("\nEJECUTANDO CONSULTA\n");
      print($q.PHP_EOL);;
      $resq='';
      $cabecera='';
      $i=1;
      $head='no';
      $result= $this->conexion->query($q);
      print("\nEJECUTADA CONSULTA, $q \n RESULTADO:\n");
      print_r($result);
      if($result->num_rows > 0) 
      {
         while($row = $result->fetch_assoc()) 
         {
            foreach($row as $k=>$v)
            {
               if($i==1 and $head=='si')
               {
                     $cabecera=implode(';',array_keys($row));
                        $resq.=$cabecera;
                        $resq.="\n";
                     $i=0;
               }
                     $resq.=$row[$k].";";
            }
                     $resq=substr($resq,0,-1);
                     $resq.="\n";
         }
      } 
      else return 0;
	return $resq;
	}
   
   public function genCsv($fcsv,$consulta)
   {
      $resq=$this->executeQuery($consulta);
      $fp = fopen($fcsv, 'w');
      fwrite($fp,$resq);
      fclose($fp);
   return $fcsv;
   }

  public function makeUtf8(){
			$data = file_get_contents($this->fcsv);
			//$data = mb_convert_encoding($data, 'UTF-8', 'ANSI_X3.4-1968');
			file_put_contents('tmp/temporal', $data);
			
			}
  public function genFicheroJson1nivel($fcsv='',$fjson=''){
				$nl=0;
				$lf='';

				$fdestino = fopen($fjson, "w");
				$handle = fopen($fcsv, "r");
				while (($line = fgets($handle)) !== false) {
					$nl++;
					$line=str_replace("\n","",$line);
					$l2=$line;
					$l3=explode("-n-",$l2);

					$lactual=$l3[0];
					if($nl==1)
					{
						$lf="[{".$lactual."}";
						continue;
					}
				   $lf.=",{".$lactual."}";

				}
				fclose($handle);
				$lf.="]";
				$lf=str_replace('""','"',$lf);
				$res=fwrite($fdestino,$lf);
				fclose($fdestino);
	}
  public function genFicheroJson2niveles($fcsv='',$fjson=''){
				$nl=0;
				$lf='';

				$fdestino = fopen($fjson, "w");
				$handle = fopen($fcsv, "r");
				while (($line = fgets($handle)) !== false) {
					$nl++;
					$line=str_replace("\n","",$line);
					$l2=$line;
					$l3=explode("-n-",$l2);

					$lactual=$l3[0];
					
					$children1=$l3[1];

					if($nl==1)
					{
						$lf="[{".$lactual;
						$lf.=",\"children1\":[{".$children1."}";
						$lant=$lactual;
						continue;
					}
					if($lactual==$lant)
					{
						$lf.=",{".$children1."}";
					}
					else
					{
						$lf.="]},{".$lactual.",\"children1\":[{".$children1."}";
					}
					$lant=$lactual;

				}
				fclose($handle);
				$lf.="]}]";
				$lf=str_replace('""','"',$lf);
				$res=fwrite($fdestino,$lf);
				fclose($fdestino);
	}
  public function genFicheroJson3niveles($fcsv='',$fjson='')
  {
   $nl=0;
   $lf='';
   //$fcsv=$argv[1];
   if(!$post) print("DENTRO DE GEN JSON");
   $fdestino = fopen($fjson, "w");
   $handle = fopen($fcsv, "r");
   while (($line = fgets($handle)) !== false) {
  
      $nl++;
      $line=str_replace("\n","",$line);
      $l2=$line;
      $l3=explode("-n-",$l2);

      $lactual=$l3[0];
      
      $children1=$l3[1];
      $children2=$l3[2];

      if($nl==1)
      {
         $lf="[{".$lactual;
         $lf.=",\"children1\":[{".$children1;
         $lant=$lactual;
         $lantchildren1=$children1;
         $lf.=",\"children2\":[{".$children2."}";
         continue;
      }
      if($lactual==$lant)
      {
         if($children1==$lantchildren1)
            $lf.=",{".$children2."}";
         else		
         {
            $lf.="]},{".$children1;
            $lf.=",\"children2\":[{".$children2."}";
         }
      }
      else
      {
         $lf.="]}]},{".$lactual.",\"children1\":[{".$children1;
         $lf.=",\"children2\":[{".$children2."}";
      }
      $lant=$lactual;
      $lantchildren1=$children1;
   }
   fclose($handle);
   $lf.="]}]}]";
   $res=fwrite($fdestino,$lf);
   fclose($fdestino);
return;
	}
  public function genFicheroJson4niveles($fcsv='',$fjson=''){
				$nl=0;
				$lf='';
				$fdestino = fopen($fjson, "w");
				$handle = fopen($fcsv, "r");
				while (($line = fgets($handle)) !== false) {
					$nl++;
					$line=str_replace("\n","",$line);
					$l2=$line;
					$l3=explode("-n-",$l2);

					$lactual=$l3[0];
					
					$children1=$l3[1];
					$children2=$l3[2];
					$children3=$l3[3];

					if($nl==1)
					{
						$lf="[{".$lactual;
						$lf.=",\"children1\":[{".$children1;
						$lant=$lactual;
						$lantchildren1=$children1;
						$lantchildren2=$children2;
						$lf.=",\"children2\":[{".$children2;
						$lf.=",\"children3\":[{".$children3."}";
						continue;
					}
					if($lactual==$lant)
					{
						if($children1==$lantchildren1)
						{
							if($children2==$lantchildren2)
								$lf.=",{".$children3."}";
							else		
							{
								$lf.="]},{".$children2;
								$lf.=",\"children3\":[{".$children3."}";
							}
						}
						else		
						{
							$lf.="]}]},{".$children1;
							$lf.=",\"children2\":[{".$children2;
							$lf.=",\"children3\":[{".$children3."}";
						}
					}
					else
					{
						$lf.="]}]}]},{".$lactual.",\"children1\":[{".$children1;
						$lf.=",\"children2\":[{".$children2;
						$lf.=",\"children3\":[{".$children3."}";
					}
					$lant=$lactual;
					$lantchildren1=$children1;
					$lantchildren2=$children2;
				}
				fclose($handle);
				$lf.="]}]}]}]";
				fwrite($fdestino,$lf);
						
				fclose($fdestino);
		return;
	}
  public function genJsonPhp($fcsv='',$fjson='',$nd,$sql=''){
			$res='';
			$dirppal='';

			if($nd==1)
				$res=$this->genFicheroJson1nivel($fcsv,$fjson);
			elseif($nd==2)
			{
				$pycom='gen_json_dosniveles.py';
				$res=$this->genFicheroJson2niveles($fcsv,$fjson);
			}
			elseif($nd==3)
			{
            if(!$post) print("GENERANDO JSON A PARITR DE: ".$this->fcsv." EN: ".$this->fjson);
				$res=$this->genFicheroJson3niveles($fcsv,$fjson);
			}
			elseif($nd==4)
			{
				$pycom='gen_json_cuatroniveles.py';
				$res=$this->genFicheroJson4niveles($fcsv,$fjson);
			}
				return 1;
  }
  public function genJson($fam='',$sql=''){
			$res='';
			$dirppal='';
			//print("Generando json dimension: ".$this->dimension);exit();
			if($this->dimension==1)
				$pycom='gen_json_unnivel.py';
			elseif($this->dimension==2)
				$pycom='gen_json_dosniveles.py';
			elseif($this->dimension==3)
				$pycom='gen_json_tresniveles.py';
			elseif($this->dimension==4)
				$pycom='gen_json_cuatroniveles.py';

			//$comando_json="/usr/bin/python3 $com $dirppal$this->fcsv > $dirppal$this->fjson";
			if($this->post)
			{
				$comando_delutf8="rm -f  datos_listados/utf8.csv";
				$res0=shell_exec($comando_delutf8);
				$comando_utf8="iconv -t ascii//TRANSLIT -f utf8  $this->fcsv -o  datos_listados/utf8.csv";
				//$comando_utf8="iconv -c -f UTF-8 -t ISO-8859-1//TRANSLIT  $this->fcsv -o  datos_listados/utf8.csv";
				//$this->makeUtf8();
				chmod("datos_listados/utf8.csv", 0777);
				//$comando_utf8="cp  $this->fcsv  datos_listados/utf8.csv";
				$res1=shell_exec($comando_utf8);
				$comando_json="/usr/bin/python3 $pycom  datos_listados/utf8.csv > $dirppal$this->fjson";
				//$comando_json="sudo -u fpleaks /usr/bin/python3 $pycom $this->fcsv > $dirppal$this->fjson";
				//$comando_json="/usr/bin/python3 $pycom $this->fcsv > $dirppal$this->fjson";
				fwrite($this->logfile,$comando_json);
			}
			else
			{
				$comando_json="/usr/bin/python3 $pycom $this->fcsv > $dirppal$this->fjson";
			}
			try{
				$res=system($comando_json );
				//$res=shell_exec ($comando_json );
				//$res=exec ($comando_json );
				fwrite($this->logfile,"ejecutado comando\n");
			}
			catch (Exception $e) 
			{
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				fwrite($this->logfile,"error".$e->getMessage());
			}
			//$comando_comprobacion="php comprobacion_json.php ".$this->fjson;
			//$rescjson=shell_exec ($comando_comprobacion );
			
			//if($rescjson)
				fclose($this->logfile);
				return 1;
			//else return 0;
  }
  public function genJson_old($fam='',$sql=''){
			$res='';
			$comando_utf8="iconv -f utf8 -t ascii//TRANSLIT  $this->fcsv -o  datos_listados/utf8.csv";
			
			$res1=shell_exec ($comando_utf8);
#$comando_json="python3 gen_json_dosniveles.py $this->fcsv > $this->fjson";
			$comando_json="python3 gen_json_dosniveles.py datos_listados/utf8.csv > $this->fjson";
			//$comando_json="/usr/bin/python3 gen_pruebas.py ".$this->fcsv.">".$this->fjson ;
			try{
				$res=shell_exec ($comando_json );
			}
			catch (Exception $e) 
			{
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
			$comando_comprobacion="php comprobacion_json.php ".$this->fjson;
			$rescjson=shell_exec ($comando_comprobacion );
			
			if($rescjson)
				return 1;
			else return 0;
  }
}
 
?>
