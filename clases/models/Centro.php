<?php
class Centro{
    private $id_centro;
    private $id_usuario;
    private $localidad;
    private $provincia;
    private $nombre_centro;
    
   public function __construct($conexion,$id_centro='',$ajax='no',$estadocentro=0) 
	{
			$table="centros";
			$this->id_centro=$id_centro;
			$this->estadocentro=$estadocentro;
			$this->conexion=$conexion;
   }
   public function checkAlumnoCentro($tc,$ta) 
   {
	   $query="SELECT count(*) n FROM centros c,alumnos a WHERE a.id_centro_destino=c.id_centro AND c.token='$tc' AND a.token='$ta'";
	   $soldata=$this->conexion->query($query);
      if($soldata->fetch_object()->n==0) return 0;
      else return 1;
   }
   public function getIdNombre($n) 
   {
	$query="select id_centro from centros where nombre_centro='".$n."' and clase_centro='especial' limit 1";
	
	$soldata=$this->conexion->query($query);
	if($soldata->num_rows==0) return 0;
	if($row = $soldata->fetch_object()) 
	{
	 $solSet=$row;
	return $solSet->id_centro;
	}
	else return 0;
    }
   //devolvemos los datos de centros y vancantes definitivas para asignar plazas fase2
    public function getCentrosFase2($c=1)
		{
			$sql="SELECT * FROM centros where clase_centro='especial'";
			$sol_fase2=array();
			$query=$this->conexion->query($sql);
			if(!$query) return $sol_fase2;
			while($row = $query->fetch_assoc())
				$sol_fase2[]=$row;
			return $sol_fase2;
		}
   public function getNumSolicitudesAdmitidas($id_centro,$tipoestudios,$log)
	{
      $sql="SELECT count(*) as nsol FROM alumnos where id_centro_destino=$id_centro and tipoestudios='$tipoestudios' and fase_solicitud!='borrador' and est_desp_sorteo='admitida' and estado_solicitud='apta'";
      $log->warning("CONSULTA SOLICITUDES ADMITIDAS CENTRO: $sql");
      $query=$this->conexion->query($sql);
      if($row = $query->fetch_assoc())
      {
         $log->warning(print_r($row,true));
         return $row['nsol'];
      }
      else return 0;
	}
   public function getNumSolicitudesAdmitidasFase2($id_centro,$tipoestudios,$log)
	{
      $sql="SELECT ifnull(count(*),0) as nsol FROM alumnos_fase2 where id_centro_definitivo=$id_centro and tipoestudios='$tipoestudios' and fase_solicitud!='borrador' AND estado_solicitud='apta'";
      $log->warning("CONSULTA SOLICITUDES ADMITIDAS FASE 2 CENTRO: $sql");
      $query=$this->conexion->query($sql);
      if($row = $query->fetch_assoc())
      {
         $log->warning(print_r($row,true));
         return $row['nsol'];
      }
      else return 0;
	}
   //OLD
    public function getSolicitudes($id_centro,$tipoestudios,$log,$estado_convocatoria=0)
		{
         //if($estado_convocatoria>=50)
         if($estado_convocatoria>=ESTADO_DEFINITIVOS)
			   $sql="SELECT count(*) as nsol FROM alumnos_definitiva where id_centro_destino=$id_centro and tipoestudios='$tipoestudios' and fase_solicitud!='borrador' and est_desp_sorteo='admitida'";
			else
            $sql="SELECT count(*) as nsol FROM alumnos where id_centro_destino=$id_centro and tipoestudios='$tipoestudios' and fase_solicitud!='borrador' and est_desp_sorteo='admitida'";
		   $log->warning("CONSULTA SOLICITUDES CENTROX: $estado_convocatoria $sql");
      	$query=$this->conexion->query($sql);
			if($row = $query->fetch_assoc())
         {
		      $log->warning(print_r($row,true));
			   return $row['nsol'];
         }
			else return 0;
		}
   //devolvemos las vacantes en cada tpo de estudios 
   public function getNombreAdjudicado($id_alumno)
	{
		$sql="SELECT nombre_centro  FROM alumnos_matricula_final where id_alumno=$id_alumno";

		$query=$this->conexion->query($sql);
		if($query) {$row = $query->fetch_object();return $row->nombre_centro;}
		else return 0;
		}
   public function getNumSolicitudes($c=1,$estado_convocatoria=0)
	{
	   $where='';
		$where=" WHERE fase_solicitud!='borrador' ";
		
		$sql="SELECT count(*) as nsolicitudes FROM alumnos $where";

		$query=$this->conexion->query($sql);
		if($query) {$row = $query->fetch_object();return $row->nsolicitudes;}
		else return 0;
		}
    public function getNumeroSorteo()
	{
			$sql="select num_sorteo from centros where id_centro=0";


			$query=$this->conexion->query($sql);
			if($query)
			return $query->fetch_object()->num_sorteo;
			else return 0;
	}
 	public function resetSorteoFase2(){
		$sql=" update centros set asignado_num_fase2=0,num_sorteo_fase2=0";
		$res=$this->conexion->query($sql);
		if($res) return 1;
      else return 0;
	}
    public function getVacantesFase2($idcentro=1,$tipo='ebo'){
			$tvacantes="vacantes_".$tipo;
			if(!isset($id_centro)) $id_centro=$this->id_centro;
			$sql="select $tvacantes from centros where id_centro=$id_centro";


			$query=$this->conexion->query($sql);
			if($query)
			return $query->fetch_object()->$tvacantes;
			else return 0;
		}
   public function setVacantes($v)
   {
      $vacantes_ebo=$v['ebo'];
      $vacantes_tva=$v['tva'];
      $sqlebo="UPDATE centros set vacantes_ebo=$vacantes_ebo, vacantes_ebo_original=$vacantes_ebo WHERE id_centro=".$this->id_centro;
      $sqltva="UPDATE centros set vacantes_tva=$vacantes_tva ,vacantes_tva_oroginal=$vacantes_tva WHERE id_centro=".$this->id_centro;
		
      $queryebo=$this->conexion->query($sqlebo);
		$querytva=$this->conexion->query($sqltva);
      
      if($queryebo and $querytva)
         return 1;
      else 
         return 0;
   }
    public function setNoAdmitidas($c,$log)
   {
      $sql="update alumnos set est_desp_sorteo='noadmitida' where id_centro_destino=$c";
      if($c=='22002511')
         $log->warning("SET NO ADMI: $sql");
      $query1=$this->conexion->query($sql);
      return 1;
   }

   public function getVacantesCentrosFase0($rol='centro',$log)
	{
      if($rol=='centro')
         $sql="SELECT vacantes_ebo,vacantes_tva  from centros where id_centro=".$this->id_centro;
      else
         $sql="SELECT vacantes_ebo,vacantes_tva  from centros";
      $query=$this->conexion->query($sql);
      $log->warning("CONSULTA VACANTES DEFINITIVOS: $sql");	
      print($sql);
		if($query)
    		{
				while ($row = $query->fetch_object()) 
				{
					$resultSet[]=$row;
				}
			}
      		return $resultSet;
	} 
   public function getVacantesCentro($log)
	{
      $vacantes_total=array('ebo'=>0,'tva'=>0);
      
      $matcentros=$this->getDatosMatriculaCentro($log);
      
      $vacantesebo=$matcentros['plazasebo']-$matcentros['matriculaactualebo'];
      $solicitudesebo=$this->getNumSolicitudesAdmitidas($this->id_centro,'ebo',$log);
      
      $vacantestva=$matcentros['plazastva']-$matcentros['matriculaactualtva'];
      $solicitudestva=$this->getNumSolicitudesAdmitidas($this->id_centro,'tva',$log);
      
      $vacantes_total['ebo']=$vacantesebo-$solicitudesebo;
      $vacantes_total['tva']=$vacantestva-$solicitudestva;

      $log->warning("VACANTES CENTRO: ".$this->id_centro);
      $log->warning(print_r($vacantes_total,true));
      return $vacantes_total;
   } 
   public function getVacantesCentros($log)
	{
      $vacantes_totales=array();
      $ids=$this->getCentrosIds();
      //$plazasliberadas=$this->getPlazasLiberadasCentros($log);
      $k=0;
      foreach($ids as $id)
      {
         $this->id_centro=$id[0]; 
         $this->setNombre(); 
         $log->warning("ID CENTRO"); 
         $log->warning($id[0]); 
         $vacantes_total=array('ebo'=>0,'tva'=>0);
         
         $matcentros=$this->getDatosMatriculaCentro($log);
         
         $vacantesebo=$matcentros['plazasebo']-$matcentros['matriculaactualebo'];
         $solicitudesebo=$this->getNumSolicitudesAdmitidas($this->id_centro,'ebo',$log);
         $admitidas_fase2_ebo=$this->getNumSolicitudesAdmitidasFase2($this->id_centro,'ebo',$log);
         
         $vacantestva=$matcentros['plazastva']-$matcentros['matriculaactualtva'];
         $solicitudestva=$this->getNumSolicitudesAdmitidas($this->id_centro,'tva',$log);
         $admitidas_fase2_tva=$this->getNumSolicitudesAdmitidasFase2($this->id_centro,'tva',$log);
         
         $vacantes_total['ebo']=$vacantesebo-$solicitudesebo-$admitidas_fase2_ebo;
         $vacantes_total['tva']=$vacantestva-$solicitudestva-$admitidas_fase2_tva;

         $log->warning("VVACANTES CENTRO: ".$this->nombre_centro);
         $log->warning("VACANTES INICIALES CENTRO: ".$this->nombre_centro." $vacantesebo");
         $log->warning(print_r($vacantes_total,true));
         $vacantes_totales[$k]['id_centro']=$id[0];
         $vacantes_totales[$k]['nombre_centro']=$this->nombre_centro;
         $vacantes_totales[$k]['vacantes_ebo']=$vacantes_total['ebo'];
         $vacantes_totales[$k]['vacantes_tva']=$vacantes_total['tva'];
         $k++;
      }
      return $vacantes_totales;
   } 
   public function getVacantes($rol='centro',$log)
	{
      $resultSet=array();
		if($rol=='centro')
		   $sql="select ifnull(IF(t3.plazas-t2.np<0,0,t3.plazas-t2.np),t3.plazas) as vacantes from (select tipoestudios ta,num_grupos as ng,plazas from centros_grupos ce where ce.id_centro=".$this->id_centro." ) as t3 left join(select  tipo_alumno_actual as tf, ifnull(count(*),0) as np from matricula where id_centro=".$this->id_centro." and estado='continua' group by tipo_alumno_actual ) as t2  on t3.ta=t2.tf";
		else
			$sql="select ifnull(IF(t3.plazas-t2.np<0,0,t3.plazas-t2.np),t3.plazas) as vacantes from (select tipo_alumno ta,sum(plazas) as plazas from centros_grupos ce group by ta ) as t3 left join (select  tipo_alumno_actual as tf, ifnull(count(*),0) as np from matricula where estado='continua' group by tipo_alumno_actual ) as t2  on t3.ta=t2.tf";		
			$query=$this->conexion->query($sql);
      $log->warning("CONSULTA VACANTES DEFINITIVOS: $sql");	
		if($query)
    	{
		   while ($row = $query->fetch_object()) 
			   $resultSet[]=$row;
		}
      return $resultSet;
	} 
   public function getDatosMatriculaCentro($log)
   {
      $datosmatricula=array();
      $datosmatricula['nombre_centro']=$this->getNombre();  

      $dcg=$this->getDatosCentrosGrupos('ebo');  
         
      $log->warning("OBTENIDOS DATOS CENTROS GRUPOS");
		$log->warning(print_r($dcg,true));
      
      $datosmatricula['gruposebo']=$dcg[0]->num_grupos;  
      $datosmatricula['plazasebo']=$dcg[0]->plazas;  
      $datosmatricula['matriculaactualebo']=$this->getMatricula('ebo');  
      
      $dcg=$this->getDatosCentrosGrupos('tva');  
      $datosmatricula['grupostva']=$dcg[0]->num_grupos;  
      $datosmatricula['plazastva']=$dcg[0]->plazas;  
      $datosmatricula['matriculaactualtva']=$this->getMatricula('tva');  
		
      $dcg=$this->getDatosCentrosGrupos('dos');  
      $datosmatricula['gruposdos']=$dcg[0]->num_grupos;  
      $datosmatricula['plazasdos']=$dcg[0]->plazas;  
      $datosmatricula['matriculaactualdos']=$this->getMatricula('dos');  
		
      $log->warning("OBTENIDOS DATOS MATRICULA CENTRO");
		$log->warning(print_r($datosmatricula,true));
      return $datosmatricula;
	}
   public function getResumenFase2($rol) 
	{
			$resultSet=array();
			if($rol=='admin') 
				$sql="SELECT nombre_centro,IFNULL(vacantes_ebo,0) as vacantes_ebo,IFNULL(vacantes_tva,0) as vacantes_tva ,id_centro FROM centros WHERE clase_centro='especial'";
			$query=$this->conexion->query($sql);
			if($query)
    			{
				while ($row = $query->fetch_object()) 
				{
					$resultSet[]=$row;
				}
			}
      			return $resultSet;
   }
    public function getUsuariosCentro($rol,$c,$log) 
		{
			$resultSet=array();
			if($rol=='admin') 
			{
				$sql="select nombre,nombre_usuario,clave_original,a.tel_dfamiliar1,token FROM alumnos a, usuarios u WHERE a.id_usuario=u.id_usuario";
			}
			elseif($rol=='centro') 
			{
				$sql="select nombre,nombre_usuario,clave_original,a.tel_dfamiliar1,token FROM alumnos a, usuarios u WHERE a.id_usuario=u.id_usuario AND a.id_centro_destino=$c";
			}
         $log->warning("\nCONSULTA USUSARIOS CENTRO: ".$sql.PHP_EOL);	
			$query=$this->conexion->query($sql);
			if($query)
    			{
				while ($row = $query->fetch_object()) 
				{
					$resultSet[]=$row;
				}
			}
      return $resultSet;
   }
   public function getResumen($rol,$t,$log='') 
   {
			$resultSet=array();
         $sql='';
	      $log->warning("CSV MATRICULA, ROL: ".$rol);
	      $log->warning("CSV MATRICULA, TABLA: ".$t);
			if($rol=='admin') 
			{
				if($t=='matricula')
					{
					$sql="
                  SELECT 
                  t3.ta,t3.ng as grupo,t3.plazas as puestos,IFNULL(t2.np,0) as plazasactuales,IFNULL(IF(t3.plazas-t2.np<0,0,t3.plazas-t2.np),0) as vacantes
                  FROM 
                  (SELECT tipoestudios ta,sum(num_grupos) as ng,sum(plazas) as plazas from centros_grupos ce group by ta) as t3
                  JOIN
                  (select  tipo_alumno_actual as tf, count(*) as np from matricula where estado='continua' group by tipo_alumno_actual ) as t2
                  ON 
                  t3.ta=t2.tf
                  JOIN
                  (select  tipo_alumno_actual as ta, count(*) as np from matricula m group by tipo_alumno_actual  ) as t1 on t1.ta=t3.ta;
               ";
					}
					elseif($t=='alumnos')
					{
					return "tabla:".$t;
					$sql="select t1.ta,'0' as grupo,'0' as puestos,t1.np as plazasactuales,t1.np-t2.np as vacantes from
					(select  tipo_alumno_actual as ta, count(*) as np from matricula group by tipo_alumno_actual ) as t1
					join
					(select  tipo_alumno_actual as tf, count(*) as np from matricula where estado='continua'  group by tipo_alumno_actual ) as t2
					on t1.ta=t2.tf";
					}
			}
			elseif($rol=='centro') 
			{
				if($t=='matricula')
				{
						$sql="
						select t3.ta,t3.ng as grupo,t3.plazas as puestos,IFNULL(t2.np,0) as plazasactuales,
						IFNULL(IF(ifnull(t3.plazas,0)-ifnull(t2.np,0)<0,0,ifnull(t3.plazas,0)-ifnull(t2.np,0)),0
						) 
						as vacantes from 
						(select tipoestudios ta,num_grupos as ng,plazas from centros_grupos ce where ce.id_centro=".$this->id_centro.") as t3 
						left join 
						(select  tipo_alumno_actual as tf, count(*) as np from matricula where id_centro=".$this->id_centro." and estado='continua' group by tipo_alumno_actual ) as t2  
						on t3.ta=t2.tf 
						left join 
						(select  tipo_alumno_actual as ta, count(*) as np from matricula m,centros ce where m.id_centro=ce.id_centro and ce.id_centro=".$this->id_centro." group by tipo_alumno_actual  ) as t1 
						on t1.ta=t3.ta
						";
				}
					elseif($t=='alumnos')	
					{
					$sql="
					select t1.nc centro,t1.nb borrador,t2.np validada, t3.nd baremada
					from (select nombre_centro nc,count(*) as nb from centros c, alumnos a where a.id_centro_destino=c.id_centro and id_centro=".$this->id_centro." and fase_solicitud='borrador') t1 
					join
					(select nombre_centro nc,count(*) as np from centros c, alumnos a where a.id_centro_destino=c.id_centro and id_centro=".$this->id_centro." and fase_solicitud='validada') t2 on t1.nc=t2.nc
					join
					(select nombre_centro nc,count(*) as nd from centros c, alumnos a where a.id_centro_destino=c.id_centro and id_centro=".$this->id_centro." and fase_solicitud='baremada') t3 on t2.nc=t3.nc";
					}
			}
	      $log->warning("CSV MATRICULA, CONSULTA: ".$sql);
			$query=$this->conexion->query($sql);
			if($query)
    			{
				while ($row = $query->fetch_object()) 
				{
					$resultSet[]=$row;
				}
			}
      return $resultSet;
   }

    public function setSorteo($ns=0,$c=1) 
		{
			if($c!=1)
				$sql="update centros set num_sorteo=$ns WHERE id_centro=$c";
			else
				$sql="update centros set num_sorteo=$ns";

		
			$query=$this->conexion->query($sql);
			if($query)
				return 1;
			else{ 
				
						return 0;
				}
		}
    public function getFases($c=1) 
		{
		$resultado=array(0,0,0);
		$sql="select nombre_centro nc,count(*) as nb,fase_solicitud from centros c, alumnos a where a.id_centro_destino=c.id_centro and id_centro=$this->id_centro  group by nombre_centro,fase_solicitud";


			$query=$this->conexion->query($sql);
		if($query)
    		{
			while ($row = $query->fetch_object()) 
			{
					if($row->fase_solicitud=='borrador')	$resultado[0]=$row->nb;
					if($row->fase_solicitud=='validada')	$resultado[1]=$row->nb;
					if($row->fase_solicitud=='baremada')	$resultado[2]=$row->nb;
			}
			}
			else 
			
			return  $resultado;
    		}
   public function getMatricula($t) 
	{
	   $sql="SELECT count(*) as matricula FROM matricula WHERE id_centro=$this->id_centro AND tipo_alumno_actual='".$t."' AND estado='continua'";
      $query=$this->conexion->query($sql);
		if($query->num_rows>0)
		  return $query->fetch_object()->matricula;
      else return 0;
   }
   public function getDatosCentrosGrupos($t) 
	{
	   $sql="SELECT * FROM centros_grupos WHERE id_centro=$this->id_centro AND tipoestudios='".$t."'";
      $query=$this->conexion->query($sql);
		if($query)
    	{
		   while ($row = $query->fetch_object()) 
			   $resultSet[]=$row;
		}
		return  $resultSet;
    }
    public function getDatosSorteo($c=1,$tipo='') 
		{
			$sql="select vacantes_ebo,vacantes_tva,num_sorteo_ebo,num_sorteo_tva,solicitudes_ebo,solicitudes_tva from centros where id_centro=$c";
			$query=$this->conexion->query($sql);
			if($query)
    	{
				while ($row = $query->fetch_object()) 
				{
					$resultSet[]=$row;
				}
			}
			
			return  $resultSet;
    }
    public function setFaseSorteo($f) 
    {
			$sql="update centros set fase_sorteo='$f'";
			$query=$this->conexion->query($sql);
			if($query)
			return  1;
			else return 0;
    }
    public function getFaseSorteo() 
    {
			$sql="SELECT fase_sorteo FROM centros WHERE id_centro=$this->id_centro";
			$query=$this->conexion->query($sql);


			$query=$this->conexion->query($sql);
			if($query)
    			{
			return  $query->fetch_object()->fase_sorteo;
			}
			else return 0;
			

    }
    public function setEstado($e) {
    }
    public function getEstado() {
	$ec = $this->conexion->query("SELECT num_sorteo FROM centros WHERE id_centro =".$this->id_centro)->fetch_object()->num_sorteo; 
        return $ec;
    }
  public function getCentrosIds()
	{
      $ares=array();
      $sql="SELECT id_centro FROM centros where clase_centro='especial'";
      $res=$this->conexion->query($sql);
      if(!$res) return $this->conexion->error;
      while($row=$res->fetch_row())
         $ares[]=$row;
      return  $ares;
	} 

    public function getDb() {
        return $this->db;
    }
    public function getId() {
        return $this->id_centro;
    }

    public function setId($id) {
        $this->id_centro = $id;
    }
    
    public function getNombre() {
	if(!$this->nombre_centro)
		return 0;	
        return $this->nombre_centro;
    }

    public function setNombre() {
	//si el centros es -1,-2 o -3 es un servicio provincial asi iq no tiene nombre
	if($this->id_centro==-1) $this->nombre_centro='Servicio Provincial de Zaragoza';
	if($this->id_centro==-2) $this->nombre_centro='Servicio Provincial de Huesca';
	if($this->id_centro==-3) $this->nombre_centro='Servicio Provincial de Teruel';
	else
	{
	$nombre_centro = $this->conexion->query("SELECT nombre_centro FROM centros WHERE id_centro =".$this->id_centro)->fetch_object()->nombre_centro; 
	$this->nombre_centro = $nombre_centro;
	}
    }

    public function getPassword() {
        return $this->password;
    }
    public function getIdCentroFromToken($t) {
	   $id_centro = $this->conexion->query("SELECT id_centro FROM centros WHERE token ='".$t."'");
      if($id_centro) 
	   return  $this->conexion->query("SELECT id_centro FROM centros WHERE token ='".$t."'")->fetch_object()->id_centro; 
      else
         return 0;
    }

    public function actualizaVacantes($vebo,$vtva,$tipo=0,$inc) {
			if($tipo==0)
			$sql="update centros set vacantes_ebo=$vebo,
vacantes_ebo_original=$vebo, vacantes_tva=$vtva, vacantes_tva_original=$vtva where id_centro='$this->id_centro'";
			elseif($tipo==1)
			$sql="update centros set vacantes_ebo_original=vacantes_ebo_original".$inc."1, vacantes_ebo=vacantes_ebo".$inc."1 where id_centro='$this->id_centro'";
			elseif($tipo==2)
			$sql="update centros set vacantes_tva_original=vacantes_tva_original".$inc."1, vacantes_tva=vacantes_tva".$inc."1 where id_centro='$this->id_centro'";

			$query=$this->conexion->query($sql);
			if($query)
			return  1;
			else return 0;
        $this->password = $password;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setIdCentro($id) {
        $this->id_centro = $id;
    }

}
?>
