<?php
class UtilidadesAdmision{
    private $con;
     
   public function __construct($adapter='',$centros_controller='',$centro='',$post=0) 
	{
      $this->conexion=$adapter;	
      $this->centros=$centros_controller;	
      $this->centro=$centro;	
      $this->post=$post;	
      require_once DIR_CLASES.'LOGGER.php';
      require_once DIR_APP.'parametros.php';
         
      $this->log_fase2=new logWriter('log_fase2',DIR_LOGS);
      $this->log_asigna_fase2=new logWriter('log_asigna_fase2',DIR_LOGS);
      $this->log_sorteo_fase2=new logWriter('log_sorteo_fase2',DIR_LOGS);
  }
  public function getMatriculaComprobarBaremo()
  {
   $ares=array();
   $sql="SELECT a.id_alumno,dni_alumno,dni_tutor1,dni_tutor2,sitlaboral,renta_inferior
         FROM alumnos a,baremo b
         WHERE a.id_alumno=b.id_alumno AND fase_solicitud!='borrador' AND estado_solicitud='apta' ";      
	$res=$this->conexion->query($sql);
	if(!$res) return $this->conexion->error;
	while($row=$res->fetch_assoc())
      $ares[]=$row;
   return  $ares;

  }
  public function getSolicitudesComprobarBaremo($token='')
  {
   $ares=array();
   if($token=='')
   {
      $sql="SELECT *
            FROM alumnos a,baremo b,centros c
            WHERE a.id_alumno=b.id_alumno AND c.id_centro=a.id_Centro_destino AND fase_solicitud!='borrador' AND estado_solicitud='apta' ";      
   }
   else  
   {
      $sql="SELECT *
            FROM alumnos a,baremo b,centros c
            WHERE a.id_alumno=b.id_alumno AND c.id_centro=a.id_Centro_destino AND fase_solicitud!='borrador' AND estado_solicitud='apta' AND a.token='$token'";      
   }
	$res=$this->conexion->query($sql);
	if(!$res) return $this->conexion->error;
	while($row=$res->fetch_assoc())
      $ares[]=$row;
   return  $ares;

  }
  public function recalcularBaremo($aldata)
  {
   $r=array();   
   $puntos_baremo=0;
   $puntos_baremo_validados=0;
   $nhdisc=0;
   $nombre=$aldata['nombre'];
   $apellido1=$aldata['apellido1'];
   $apellido2=$aldata['apellido2'];
   $nombre_centro=$aldata['nombre_centro'];
   $dni=$aldata['dni_alumno'];
   $dni1=$aldata['dni_tutor1'];
   $dni2=$aldata['dni_tutor2'];
   
   $sri=$aldata['renta_inferior'];
   $rri=$aldata['comprobar_renta_inferior'];

   $sdh=$aldata['discapacidad_hermanos'];
   $rdh=$aldata['comprobar_discapacidad_hermanos'];

   $sda=$aldata['discapacidad_alumno'];
   $rda=$aldata['comprobar_discapacidad_alumno'];
   $dnidisc1=$aldata['dnidisc1'];
   if($dnidisc1!='nodata' and $dnidisc1!='')
      $nhdisc++;
   $dnidisc2=$aldata['dnidisc2'];
   if($dnidisc2!='nodata' and $dnidisc2!='')
      $nhdisc++;
   $dnidisc3=$aldata['dnidisc3'];
   if($dnidisc3!='nodata' and $dnidisc3!='')
      $nhdisc++;
   
   print("NDISC: ".$nhdisc);
   $sfn=$aldata['marcado_numerosa'];
   $rfn=$aldata['comprobar_familia_numerosa'];
   $tfn=$aldata['tipo_familia_numerosa'];
   
   $sfm=$aldata['marcado_monoparental'];
   $rfm=$aldata['comprobar_familia_monoparental'];
   $tfm=$aldata['tipo_familia_monoparental'];
   
   //calcuklamos el baremo de los campos q no se compruebasn
   $pdom=$aldata['marcado_proximidad_domicilio'];
   $vpdom=$aldata['validar_proximidad_domicilio'];
   $valorpdom=$aldata['proximidad_domicilio'];
   if($pdom==1)
   {
      if($valorpdom=='dfamiliar')
         $puntos=6;
      if($valorpdom=='dlaboral')
         $puntos=5;
      if($valorpdom=='dflimitrofe')
         $puntos=3;
      if($valorpdom=='dllimitrofe')
         $puntos=2;
      $puntos_baremo=$puntos_baremo+$puntos;
      if($vpdom==1)
      {
         $puntos_baremo_validados=$puntos_baremo_validados+$puntos;
      }
   }

   $tutorescentro=$aldata['tutores_centro'];
   $vtutorescentro=$aldata['validar_tutores_centro'];
   if($tutorescentro==1)
   {
      $puntos_baremo=$puntos_baremo+4;
      if($vtutorescentro==1)
         $puntos_baremo_validados=$puntos_baremo_validados+4;
   }
   if($sri==1)
   {
      $puntos_baremo=$puntos_baremo+5;
      if($rri==2)
         $puntos_baremo_validados=$puntos_baremo_validados+5;
   }

   $acog=$aldata['acogimiento'];
   $vacog=$aldata['validar_acogimiento'];
   if($acog==1)
   {
      $puntos_baremo=$puntos_baremo+1;
      if($vacog==1)
         $puntos_baremo_validados=$puntos_baremo_validados+1;
   }

   $genero=$aldata['genero'];
   $vgenero=$aldata['validar_genero'];
   if($genero==1)
   {
      $puntos_baremo=$puntos_baremo+1;
      if($vgenero==1)
         $puntos_baremo_validados=$puntos_baremo_validados+1;
   }

   $ter=$aldata['terrorismo'];
   $vter=$aldata['validar_terrorismo'];
   if($ter==1)
   {
      $puntos_baremo=$puntos_baremo+1;
      if($vter==1)
         $puntos_baremo_validados=$puntos_baremo_validados+1;
   }      

   $parto=$aldata['parto'];
   $vparto=$aldata['validar_parto'];
   if($parto==1)
   {
      $puntos_baremo=$puntos_baremo+1;
      if($vparto==1)
         $puntos_baremo_validados=$puntos_baremo_validados+1;
   }
   if($sda==1)
   {
      $puntos_baremo=$puntos_baremo+1;
      if($rda==2)
         $puntos_baremo_validados=$puntos_baremo_validados+1;
   }
   if($sdh==1)
   {
      $puntos_baremo=$puntos_baremo+$nhdisc;
      if($rdh==2)
         $puntos_baremo_validados=$puntos_baremo_validados+$nhdisc;
   }
   if($sfn==1)
   {
      if($tfn==1) $pf=1;
      else        $pf=2;
      $puntos_baremo=$puntos_baremo+$pf;
      if($rfn==2)
         $puntos_baremo_validados=$puntos_baremo_validados+$pf;
   }
   if($sfm==1)
   {
      if($tfm==1) $pf=1;
      else        $pf=2;
      $puntos_baremo=$puntos_baremo+$pf;
      if($rfm==2)
         $puntos_baremo_validados=$puntos_baremo_validados+$pf;
   }
   $r['rda']=$rda;   
   $r['pb']=$puntos_baremo;   
   $r['pbv']=$puntos_baremo_validados;   
   return $r;   
  }
  public function comprobarBaremo($tipo,$dni,$dni1,$dni2)
  {
      //0 no comprobado, 1 comp negativa, 2 comp positiva
      $r=rand(1,2);
      return $r;
  }
  public function actualizarBaremo($dbaremo,$id_alumno,$log='')
  {
   $pb=$dbaremo['pb'];
   $pbv=$dbaremo['pbv'];
   $sql="UPDATE baremo SET puntos_validados=$pbv,puntos_totales=$pb WHERE id_alumno=$id_alumno";      
   if($log!='')
      $log->warning("ACTUALIZANDO BAREMO FINAL: ".$sql);
	$res=$this->conexion->query($sql);
	if(!$res) return $this->conexion->error;
   return  1;
   
  }
  public function actualizarBaremo_old($dbaremo,$id_alumno,$log='')
  {
   $pb=$dbaremo['pb'];
   $pbv=$dbaremo['pbv'];
   $rda=$dbaremo['rda'];
   $token_hermanos=$this->getTokenHermanosAdmision($id_alumno); 
   //$datos_baremo=$this->getDatosBaremoAlumno($id_alumno); 
   if($log!='')
   {
      $log->warning("ACTUALIZANDO BAREMO FINAL TOKEN HERMANOS: ");
      $log->warning(print_r($token_hermanos,true));
   }
   if(sizeof($token_hermanos)>0)
   {
      foreach($token_hermanos as $t)
      {
         $token_hermano=$t->token;
         //primero borramos la entrada de baremo del hermano
         //$dql="DELETE FROM baremo WHERE id_alumno IN (SELECT id_alumno FROM alumnos WHERE token='$token_hermano')";      
	      //$res=$this->conexion->query($dql);
         print("\nACT HERMANO");
         $sql="UPDATE baremo SET comprobar_discapacidad_alumno=$rda, puntos_validados=$pbv,puntos_totales=$pb WHERE id_alumno IN (SELECT id_alumno FROM alumnos WHERE token='$token_hermano')";      
         $log->warning("ACTUALIZANDO COMPPP HERMANOS: ".$sql);
         print($sql);
	      $res=$this->conexion->query($sql);
      }
   }     
   $sql="UPDATE baremo SET puntos_validados=$pbv,puntos_totales=$pb WHERE id_alumno=$id_alumno";      
   print("\nACT ALUMNO");
   print($sql);
   if($log!='')
      $log->warning("ACTUALIZANDO BAREMO FINAL: ".$sql);
	$res=$this->conexion->query($sql);
	if(!$res) return $this->conexion->error;
   return  1;
   
  }
  public function actualizaComprobaciones($tipo,$id,$vc)
  {
   $ares=array();
   $sql="UPDATE baremo SET $tipo=$vc WHERE id_alumno=$id";      
	$res=$this->conexion->query($sql);
	if(!$res) return $this->conexion->error;
   return  1;
  }
	public function setDuplicados() 
	{
		$alumnos=$this->getAlumnosParaDuplicados();
	   foreach($alumnos as $a)
      {
         $nal=$this->getDuplicados($a->nombre,$a->apellido1,$a->apellido2,$a->fnac);print("NALUMNOS: $nal".PHP_EOL);
         if($nal>=2)
            $sql="UPDATE alumnos set estado_solicitud='duplicada' WHERE id_alumno=$a->id_alumno"; print("DUPLICADO !!!!!". $sql.PHP_EOL);
      }
     return 1;
	}
   public function getDuplicados($nombre,$apellido1,$apellido2,$fnac)
	{
		$resultSet=array();
		$sql="SELECT count(*) as duplicados FROM alumnos WHERE nombre='$nombre' and apellido1='$apellido1' and apellido2='$apellido2' and fnac='$fnac'";
	   //print("SQL DUPLICADOS: ".$sql.PHP_EOL);
   	$query=$this->conexion->query($sql);
		if($query)
		$row = $query->fetch_object();
		return $row->duplicados;
    }
   public function getAlumnosParaDuplicados()
	{
		$resultSet=array();
		$sql="SELECT id_alumno,nombre,apellido1,apellido2,fnac FROM alumnos";
		$query=$this->conexion->query($sql);
		if($query)
		while ($row = $query->fetch_object()) {
		   $resultSet[]=$row;
		}
		return $resultSet;
    }
   public function getTokenHermanosAdmision($id_alumno)
	{
		$resultSet=array();
		$sql="SELECT token FROM alumnos WHERE id_alumno IN(SELECT id_hermano FROM alumnos_hermanos_admision ah WHERE ah.id_alumno=$id_alumno)";
      print($sql);
		$query=$this->conexion->query($sql);
		if($query)
		while ($row = $query->fetch_object()) {
		   $resultSet[]=$row;
		}
		return $resultSet;
    }
   public function copiaTablaMatriculaFinal()
   {
      //copiamos solicitudes ya admitidas
	   $sql1="SELECT a.tel_dfamiliar1,a.email,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.est_desp_sorteo,c.nombre_centro,b.*,a.id_centro_destino as id_centro,'no' FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and est_desp_sorteo='admitida' and a.id_alumno not in(SELECT aff.id_alumno FROM alumnos_fase2_final aff WHERE aff.centro_definitivo!='nocentro' )";
	   //volcamos la tabla con los datos de solicitudes y los del baremo tal como aparecen en el listado de provisionales o definitivosa
		$dsql='DELETE from alumnos_matricula_final';
		$isql1="INSERT IGNORE INTO alumnos_matricula_final $sql1";
      print($isql1);
      //copiamos solicitudes admitidas por los servicios provinciales
	   $sql2="SELECT a.tel_dfamiliar1,a.email,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.est_desp_sorteo,af.centro_definitivo as nombre_centro,b.*,af.id_centro_definitivo as id_centro,'no' FROM alumnos a join  alumnos_fase2 af on a.id_alumno=af.id_alumno  left join baremo b on b.id_alumno=af.id_alumno ";
	   //volcamos la tabla con los datos de solicitudes y los del baremo tal como aparecen en el listado de provisionales o definitivosa
		$isql2="INSERT IGNORE INTO alumnos_matricula_final $sql2";
      print(PHP_EOL.$isql2);
		
		if($this->conexion->query($dsql))
			if($this->conexion->query($isql1) and $this->conexion->query($isql2)) return 1;
	   	else return $this->conexion->error;
		else return $this->conexion->error;
	}
   public function copiaTablaTmpFase2()
   {
      $sqlt1="DELETE FROM  alumnos_fase2_tmp";
		$sqlt2="INSERT INTO alumnos_fase2_tmp SELECT * FROM alumnos_fase2";
      if($this->conexion->query($sqlt1) and $this->conexion->query($sqlt2))
		{
			$this->log_sorteo_fase2->warning("OK COPIANDO TABLA TMP");
			return 1;
		}
		else
      { 
			$this->log_sorteo_fase2->warning("ERROR COPIANDO TMP ");
			$this->log_sorteo_fase2->warning($sqlt2);
			return 0;
		}
   }
  public function asignarNumSorteoFase2($asig=0){
		$this->log_sorteo_fase2->warning("ASIGNANDO NUMERO SORTEO");
		$sql="SET @r := 0";
		$this->conexion->query($sql);
		//ponemos todas a cero para evitar inconsistencias
		$sql1="UPDATE  alumnos_fase2 SET nasignado =0";
		$sql2="UPDATE  alumnos_fase2 SET nasignado = (@r := @r + 1) ORDER BY  RAND()";
		$sql3="UPDATE  centros SET asignado_num_fase2=$asig";
		$this->log_sorteo_fase2->warning($sql1);
		$this->log_sorteo_fase2->warning($sql2);
		$this->log_sorteo_fase2->warning($sql3);
		if($this->conexion->query($sql1) and $this->conexion->query($sql2) and
      $this->conexion->query($sql3))
		{
			$this->log_sorteo_fase2->warning("OK ASIGNANDO NUM SORTEO EN FASE2");
			return 1;
		}
		else{ 
			$this->log_sorteo_fase2->warning("ERROR ASIGNANDO NUM SORTEO FASE2: ");
			$this->log_sorteo_fase2->warning($sql1);
			$this->log_sorteo_fase2->warning($sql2);
			$this->log_sorteo_fase2->warning($sql3);
			return 0;
		}
		return 0;
	}
	public function actualizarSolSorteoFase2($c=1,$numero=0,$solicitudes=0) 
	{
		$resultSet=array();
		
		$sql1="UPDATE alumnos_fase2 a set nordensorteo=$solicitudes+nasignado-$numero+1 where nasignado<$numero";
		$sql2="UPDATE alumnos_fase2 a set nordensorteo=nasignado-$numero+1 where nasignado>=$numero";
		$sql3="UPDATE  centros SET num_sorteo_fase2=$numero";

		$this->log_sorteo_fase2->warning("ASIGNANDO NUMERO DESPUES DE SORTEO $sql1 -- $sql2");
		if(!$this->conexion->query($sql1) or !$this->conexion->query($sql2) or
!$this->conexion->query($sql3)) return $this->conexion->error;
		else return 1;
	}
 	public function checkSorteoFase2(){
		$sql="SELECT num_sorteo_fase2 as ns,asignado_num_fase2 as na FROM centros WHERE
id_centro=1";
		$res=$this->conexion->query($sql);
		if($res) $row=$res->fetch_row();
		$this->log_sorteo_fase2->warning("ESTADO SORTEO:");
		$this->log_sorteo_fase2->warning(print_r($row,true));
      if($row[0]!=0 and $row[1]!=0) return 1;
      else return 0;
	}
 	public function calculaPlazasTotalesCentroFase2($id_centro,$tipoestudios){
	//calculamos plazas ocupadas de matrócula y de solicitudes admitidas
      //primero las plazas q hay
		$sql="select plazas from centros_grupos where id_centro=$id_centro and tipo_alumno='$tipoestudios'";
      print("\nCONSULTA PLAZAS CENTRO: $id_centro . \n$sql\n");  
		$res=$this->conexion->query($sql);
		if($res)
      {
         $row=$res->fetch_row();
         $plazas=$row[0];
      }
		else return 0;
	   $total=0;	
      //alumnos admisitods en solicitudes
		$sql="SELECT count(*) as nalumnos_admitidos FROM alumnos WHERE id_centro_destino=$id_centro and est_desp_sorteo='admitida' and tipoestudios='$tipoestudios'";
      print("\nCONSULTA ADMITIDOS CENTRO: $id_centro . \n$sql\n");  
		$res=$this->conexion->query($sql);
		if($res)
      {
         $row=$res->fetch_row();
         $nalsol=$row[0];
      }
		else return 0;
      //alumnos matriculados
		$sql="SELECT count(*) as nalumnos_matriculados FROM matricula WHERE id_centro=$id_centro and tipo_alumno_actual='$tipoestudios' and estado='continua'";
      print("\nCONSULTA MATRICULADOS CENTRO: $id_centro . \n$sql\n");  
		$res=$this->conexion->query($sql);
		if($res)
      {
         $row=$res->fetch_row();
         $nalm=$row[0];
      }
		else return 0;
   $total=$plazas-$nalsol-$nalm;
   return $total;
	}
 	public function setVacantesCentroFase2($idc,$v,$t,$inc=0){
		if($inc==1) //si inc es 1 se incrementan/decrementan las vacantes
		{
			$voriginal="vacantes_$t"."_original";
			$sql="UPDATE centros set vacantes_$t=vacantes_$t+1,$voriginal=$voriginal+1 where id_centro=$idc";
			
		}
		else
			$sql="UPDATE centros set vacantes_$t=$v where id_centro=$idc";
		$this->log_fase2->warning("ACTUALIZANDO VACANTES CENTROS, VACANTES: $v, INC: $inc CONSULTA: ".$sql);
		if(!$this->post) print(PHP_EOL."ACTUALIZANDO VACANTES CENTROS, VACANTES: $v, INC: $inc CONSULTA: ".$sql);
		if(!$this->conexion->query($sql)) return $this->conexion->error;
		else return 1;
	}
 	public function setAlumnoCentroFase2($ida,$idc,$nc){
		//modificamos el centro en a tabla de alumnos_fase2
		$sql="UPDATE alumnos_fase2 set id_centro_definitivo=$idc,centro_definitivo='$nc' where id_alumno=$ida";
		if(!$this->post) print("CONSULTA ASIGNACION PLAZA: $sql");
		if(!$this->conexion->query($sql)) return $this->conexion->error;
		else return 1;
	}
 	public function checkDistancia($idalumno,$idcentro){
		$sql="SELECT id_alumno FROM distancias WHERE id_alumno=$idalumno and
id_centro=$idcentro";
		$res=$this->conexion->query($sql);
		if(!$res) return $this->conexion->error;
		if($res->num_rows>0) return 1;
		else return 0;
	}
 	public function setDistancia($idalumno,$idcentro,$data){
		$sql="INSERT INTO distancias VALUES($idalumno,$idcentro,DEFAULT,";
      foreach ($data as $k=>$v)
         $sql.="'$v',";
      $sql=substr($sql,0,-1);
      $sql.=")";
      print($sql);
		if(!$this->conexion->query($sql)) return $this->conexion->error;
		else return 1;
	}
 	public function setCoordenadas($id,$coord,$tipo='alumno'){
      if($tipo=='alumno')
		$sql="UPDATE alumnos_fase2 set coordenadas='$coord' WHERE id_alumno=$id";
      else
		$sql="UPDATE centros set coordenadas='$coord' WHERE id_centro=$id";
		if(!$this->conexion->query($sql)) return $this->conexion->error;
		else return 1;
	}
 	public function liberaReserva($ida){
		//modificamos el centro en a tabla de alumnos_fase2
		$sql1="UPDATE alumnos_fase2 set reserva=0 where id_alumno=$ida";
		$sql2="UPDATE alumnos_fase2_tmp set reserva=0 where id_alumno=$ida";
		if(!$this->conexion->query($sql1)) return $this->conexion->error;
		if(!$this->conexion->query($sql2)) return $this->conexion->error;
		return 1;
	}
 	public function checkReservaPlaza($ida){
		//comprobamos si ell alumno tiene una reserva para el tipo determinado
		//si la tiene devolvemos el id del centro origen, siempre q sea de tipo especial
		$sql="SELECT reserva FROM alumnos_fase2 where id_alumno=$ida"; 
		$res=$this->conexion->query($sql);
		if($res) return $res->fetch_row();
		else return $this->conexion->error;
	}
	public function restaurarVacantesCentroFase2(){
		$sql="UPDATE centros SET vacantes_ebo=vacantes_ebo_original, vacantes_tva=vacantes_tva_original";
		$this->log_fase2->warning("RESTAURANDO VACANTES CENTROS, CONSULTA: $sql");
		if(!$this->conexion->query($sql)) return $this->conexion->error;
		else return 1;
	}
 	public function getReservaPlaza($ida,$tipo=''){
		//comprobamos si ell alumno tiene una reserva para el tipo determinado
		//si la tiene devolvemos el id del centro origen, siempre q sea de tipo especial
		$sql="SELECT id_centro_origen,reserva FROM alumnos_fase2 where id_alumno=$ida"; 
		$res=$this->conexion->query($sql);
		if($res) return $res->fetch_row();
		else return $this->conexion->error;
	}
 	public function quitaPuntosHermanosFase2(){
		//quitmaos 8 puntos a todos los alumnos ocn puntos de hermanos q no estén admitidos
		$sql="UPDATE alumnos_fase2 set puntos_validados=puntos_validados-8 where id_alumno in( select id_alumno from alumnos_definitiva where validar_hnos_centro=1 and est_Desp_sorteo='noadmitida')";
		$res=$this->conexion->query($sql);
		if($res) return 1;
		else return 0;
	}
 	public function resetAlumnosFase2(){
		//recargamos tabla de alumnso con los valores originales
		$sql1="DELETE FROM alumnos_fase2";
		$sql2="INSERT INTO alumnos_fase2 SELECT * FROM alumnos_fase2_tmp"; 
		$res1=$this->conexion->query($sql1);
		$res2=$this->conexion->query($sql2);
		$this->log_asigna_fase2->warning("RESETEANDO TABLA ALUMNOS $sql1 $sql2");
		if($res1 and $res2) return 1;
		else return 0;
	}
 	public function asignarVacantesCentros($centros_fase2=array(),$alumnos_fase2=array(),$centro_alternativo=0,$tipoestudios,$post=0)
	{
		if(sizeof($centros_fase2)==0 or sizeof($alumnos_fase2)==0){print("ARRAY VACIO"); return -1;}

		if(!$post) print(PHP_EOL."ASIGNANDO VACANTES FASE2 CENTRO ALT: $centro_alternativo TIPOESTUDIOS: $tipoestudios".PHP_EOL);
		$this->log_asigna_fase2->warning("ASIGNANDO VACANTES FASE2 CENTRO ALT: $centro_alternativo TIPOESTUDIOS: $tipoestudios");
		if(!$post) print(PHP_EOL."INCIANDO ASIGNACION RONDA: $centro_alternativo FECHAHORA: ".date("Y-m-d h:i:sa").PHP_EOL);

		if($centro_alternativo==0)
			$indicecentro='id_centro';
		else
			$indicecentro='id_centro'.$centro_alternativo;

		//empezamos con las ebo
		foreach($centros_fase2 as $centro)
		{			
			$this->centro->setId($centro['id_centro']);
			$this->centro->setNombre();

			$nombrecentro=$this->centro->getNombre();
			if(!$nombrecentro) return "NO HAY NOMBRE DE CENTRO";
			if(strtoupper($nombrecentro)=='NOCENTRO') continue;

			$vacantes=$this->centro->getVacantesFase2(1,$tipoestudios);
			if($vacantes<=0) continue;

			$vasignadaebo=1;

			$this->log_asigna_fase2->warning("ENTRANDO CENTRO $nombrecentro FASE2 $tipoestudios, plazas: $vacantes");
			if(!$post) print(PHP_EOL."ENTRANDO CENTRO $nombrecentro, idcentro ".$centro['id_centro']." FASE2 $tipoestudios, plazas: $vacantes".PHP_EOL);

			$idcentro=$centro['id_centro'];
			$vasignada=1;
			while($vacantes>0 and $vasignada==1)
			{
				if(!$post) print("HAY VACANTES, COMPROBANDO ALUMNOS");

				$vasignada=0;
				//revisar cada alumno (hay q considerar el orden de elección del alumno, el sorteo etc.) y si ha solicitado plaza en primera opción
				foreach($alumnos_fase2 as $alumno)
				{
					
					if(!$post) print(PHP_EOL."ENTRANDO ALUMNO, centro: ".strtoupper($alumno->centro_definitivo)." ".$alumno->tipoestudios." ".$tipoestudios.PHP_EOL);
					$this->log_asigna_fase2->warning("ENTRANDO ALUMNO, centro: ".strtoupper($alumno->centro_definitivo)." ".$alumno->tipoestudios." ".$tipoestudios);
					
					if($alumno->tipoestudios!=$tipoestudios)
					{
					
					if(!$post) print(PHP_EOL."SALIENDO, centro alumno: ".strtoupper($alumno->centro_definitivo)." ".$alumno->tipoestudios." ".$tipoestudios);
					$this->log_asigna_fase2->warning("SALIENDO, centro alumno: ".strtoupper($alumno->centro_definitivo)." ".$alumno->tipoestudios." ".$tipoestudios);
					
					continue;
					}
				
               $this->log_asigna_fase2->warning("DATOS ALUMNO: ");	
               $this->log_asigna_fase2->warning(print_r($alumno,true));	
					$this->log_asigna_fase2->warning("ID ALUMNO EN PROCESO: ".$alumno->id_alumno." NOMBRE ALUMNO: ".$alumno->nombre." INDICE CENTRO ALTERNATIVO: ".$centro_alternativo);
					if(!$post) print(PHP_EOL."ID ALUMNO EN PROCESO: ".$alumno->id_alumno." INDICE CENTRO ALTERNATIVO: ".$centro_alternativo.PHP_EOL);
					$this->log_asigna_fase2->warning("CENTRO ACTUAL EN PROCESO: $nombrecentro $idcentro CENTRO PEDIDO ALUMNO: ".$alumno->{$indicecentro}." NOMBRE ALUMNO: ".$alumno->nombre);
               
					$this->log_asigna_fase2->warning("INDICE CENTRO: ".$idcentro);
					$this->log_asigna_fase2->warning("INDICE CENTRO ALUMNO: ".$alumno->$indicecentro);
					$this->log_asigna_fase2->warning(" CENTRO DEFINITIVO ALUMNO: ".$alumno->centro_definitivo);
					if(!$post) print("CENTRO ACTUAL: $nombrecentro $idcentro CENTRO PEDIDO ALUMNO: ".$alumno->{$indicecentro}." NOMBRE ALUMNO: ".$alumno->nombre." CENTRO DEFINITIVO ALUMNO: ".$alumno->centro_definitivo.PHP_EOL);
				
					//solo asignamos plaza a alumnos sin centro definitivo	
					if($alumno->$indicecentro==$idcentro and strtoupper($alumno->centro_definitivo)=='NOCENTRO')
					{ 
						//comprobamos si tenía reserva de plaza, en caso afirmativo, se genera nueva plaza
						$reserva=$this->getReservaPlaza($alumno->id_alumno,$tipoestudios);
						
						$this->setAlumnoCentroFase2($alumno->id_alumno,$centro['id_centro'],$nombrecentro);
						$asignada=1;
						$vacantes--;
					
						if(!$post) print(PHP_EOL."COINCIDENCIA: $alumno->id_alumno CENTRO: $nombrecentro".PHP_EOL);
						$this->log_asigna_fase2->warning("COINCIDENCIA: $alumno->id_alumno CENTRO: $nombrecentro");
					
							
						if(!$post) print(PHP_EOL."ENTREGADA PLAZA DEL CENTRO $nombrecentro A: ".$alumno->id_alumno.PHP_EOL);
						$this->log_asigna_fase2->warning("ENTREGADA PLAZA DEL CENTRO $nombrecentro A: $alumno->id_alumno");
						//si habia reserva de plaza tenemos que actualizar las vacantes para ese centro y volver a procesarlo,reserva[0] es el id centro y reserva[1] el q dice si se ha liberado ya o no
						if(!$post) print(PHP_EOL."COMPROBANDO RESERVA DE PLAZA $reserva[0]".PHP_EOL);
						$this->log_asigna_fase2->warning("COMPROBANDO RESERVA DE PLAZA $reserva[0]");
					
						if($reserva[0]!=0 and $reserva[1]==1 and $alumno->$indicecentro!=$reserva[0])
						{
							//se ha liberado vacante con lo q hay q restaurar las vacantes de nuevo incrementando una
							if($this->restaurarVacantesCentroFase2()!=1) return 0;
							
							$this->log_asigna_fase2->warning("REINICIANDO PROCESO, RESTAURADAS VACANTES");
							if(!$post) print(PHP_EOL."REINICIANDO PROCESO, RESTAURADAS VACANTES");
							
							//añadimos la q se ha liberado y marcamos al alumno como q la ha liberado para no voolver a tenrla en cuenta
							if($this->setVacantesCentroFase2($reserva[0],0,$tipoestudios,1)!=1) return 0;
						
							//marcamos la reserva q deja el alumno en las tabal //temporal y actual, como reserva liberada, en las dos tablas de alumnos	
							if($this->liberaReserva($alumno->id_alumno)!=1) return 0;

							if(!$post) print(PHP_EOL."LIBERADA RESERVA CENTRO $reserva[0] ALUMNO: ".$alumno->id_alumno);
							$this->log_asigna_fase2->warning("LIBERADA RESERVA CENTRO $reserva[0] ALUMNO: ".$alumno->id_alumno);
	
							return -2;
						}
					}
					//si se acaban las vacantes del centro
					if($vacantes==0)
						{ 
						$this->log_asigna_fase2->warning("TERMINADO CENTRO: $nombrecentro");
						if($this->setVacantesCentroFase2($centro['id_centro'],$vacantes,$tipoestudios)!=1) return 0;
						if(!$post) print(PHP_EOL."Terminado centro: $nombrecentro".PHP_EOL);
						break;
						}
				}
			}
			//actualizamos las vacantes en el centro cuyas plazas se han procesado
			if($this->setVacantesCentroFase2($centro['id_centro'],$vacantes,$tipoestudios)!=1) return 0;
			}
		//print("FIN asignaciones $tipoestudios".PHP_EOL);
		return 1;
	}
   public function getDatosBaremoAlumno($id_alumno)
	{
		$resultSet=array();
		$sql="SELECT * FROM baremo WHERE id_alumno=$id_alumno";
		$query=$this->conexion->query($sql);
		if($query)
		while ($row = $query->fetch_object()) {
		   $resultSet[]=$row;
		}
		return $resultSet;
    }
   public function getAlumnosReserva()
	{
		$resultSet=array();
		$sql="SELECT id_alumno,nombre,id_centro_estudios_origen,id_centro_destino,tipoestudios FROM alumnos where est_desp_sorteo='admitida' or (est_desp_sorteo='noadmitida' and reserva=0)";
		$query=$this->conexion->query($sql);
		if($query)
		while ($row = $query->fetch_object()) {
		   $resultSet[]=$row;
		}
	   print($sql);	
		return $resultSet;
    }
   public function getAlumnosFase2($t='tmp')
	{
		$resultSet=array();
		if($t=='tmp') $tabla='alumnos_fase2_tmp';
		else $tabla='alumnos_fase2';
		$sql="SELECT coordenadas,calle_dfamiliar,localidad,id_alumno,nombre,id_centro1,id_centro2,id_centro3,id_centro4,id_centro5,id_centro6,id_centro,nombre_centro,centro_definitivo,tipoestudios,transporte,puntos_validados,nordensorteo FROM $tabla where estado_solicitud='apta' order by transporte desc,puntos_validados desc,nordensorteo asc";

		$query=$this->conexion->query($sql);

		if($query)
		while ($row = $query->fetch_object()) {
		   $resultSet[]=$row;
		}
		
		return $resultSet;
    }
  public function liberaVacantesAlumnos()
	{
		$alumnosreserva=$this->getAlumnosReserva();
		$alumnosreserva=(array)$alumnosreserva;
		foreach($alumnosreserva as $a)
		{
			$a=(array)$a;
			$corigen=$a['id_centro_estudios_origen'];
			$cdestino=$a['id_centro_destino'];
			$tipoestudios=$a['tipoestudios'];
			//comporbar cada alumno y si itiene reserva actualizar plaza en el centro de origen
         //solo liberamos plaza si no coincide con el q ha solicitado y si quedan plazas
         $plazaslibres=$this->calculaPlazasTotalesCentroFase2($corigen,$tipoestudios);
         //$plazaslibres=1;
         print("\nPROBANDO LIBERAR VACANTES DE RESERVA EN CENTRO $corigen, PLAZAS LIBRES: $plazaslibres \n");
			if($corigen!=0 and $cdestino!=$corigen and $plazaslibres>=0)
			{
            print("LIBERANDO VACANTES DE RESERVA EN CENTRO $corigen, ALUMNOS: ");
            print_r($a);
            //print_r($a);exit();
            if($this->setVacantesCentroFase2($corigen,0,$tipoestudios,1)!=1) return 0;
			}
		}
	return 1;
	}
   public function getPlazasDefinitiva($idcentro)
	{

		$resultSet=array();
      //Plazas ebo
		$sqlebo="SELECT count(id_alumno) as plazas FROM alumnos_definitiva where id_centro_destino=$idcentro and est_desp_sorteo='admitida' and tipoestudios='ebo'";
		$sqltva="SELECT count(id_alumno) as plazas FROM alumnos_definitiva where id_centro_destino=$idcentro and est_desp_sorteo='admitida' and tipoestudios='tva'";
		$queryebo=$this->conexion->query($sqlebo);
		$querytva=$this->conexion->query($sqltva);
      print($sqlebo);
		if($queryebo and $querytva)
		{   
         $row=$queryebo->fetch_object(); 
         $resultSet[]=$row->plazas;
		   
         $row=$querytva->fetch_object(); 
         $resultSet[]=$row->plazas;
      }
   return $resultSet;
   }
  public function actualizaVacantesCentros($centro=0)
  {
		$acentros=array();
		$centros=$this->centros->getAllCentros('todas','especial');
		while($row = $centros->fetch_assoc()) { $acentros[]=$row;}
		foreach($acentros as $centro)
		{
         print_r($centro);
         //obtenemos las plazas ocupadas de la lista definitiva, en cada centro
			$plazas=$this->getPlazasDefinitiva($centro['id_centro']);
			$this->centro->setId($centro['id_centro']);

			//completamos el campo de cada centro para incluir las vacantes definitivas
			$vacantes=$this->centro->getVacantesCentrosFase0('centro',$this->log_sorteo_fase2);
         print("VACANTES: ");         print_r($vacantes);         print("PLAZAS: ");         print_r($plazas);
         $vac_final_ebo=$vacantes[0]->vacantes_ebo-$plazas[0];
         $vac_final_tva=$vacantes[0]->vacantes_tva-$plazas[1];

         if($vac_final_ebo<0 or $vac_final_tva<0)
         {
            print("ERROR: VACANTES NEGATIVAS CENTRO: ".$centro['id_centro']);
         }
			$sql="UPDATE centros set vacantes_ebo_original=".$vac_final_ebo.",vacantes_ebo=".$vac_final_ebo.",vacantes_tva_original=".$vac_final_tva.",vacantes_tva=".$vac_final_tva." where id_centro=".$centro['id_centro'];
         print("\n".$sql."\n");
			if(!$this->conexion->query($sql)){ return $this->conexion->error;print("ERROR ACTUALIZANDO VACANTES");}
      }
	return 1;
	}
   
   public function genBaremadas()
   {
      $sql="DROP TABLE alumnos_baremada_tmp";
		if(!$this->conexion->query($sql)) return 0;
      
      //creamos tabla temportal de alumnos
      $sql="CREATE TABLE alumnos_baremada_tmp SELECT * FROM alumnos";
		print(PHP_EOL);
      print($sql);
		print(PHP_EOL);
		if(!$this->conexion->query($sql)) return 0;
      
      $sql="DELETE FROM alumnos_baremada_tmp";
		print(PHP_EOL);
		print($sql);
		print(PHP_EOL);
		if(!$this->conexion->query($sql)) return 0;
      //quitamos el campo id de alumno
      $sql="INSERT INTO alumnos_baremada_tmp SELECT * FROM alumnos";
		print(PHP_EOL);
		print($sql);
		print(PHP_EOL);
		if(!$this->conexion->query($sql)) return 0;
      
      $sql="ALTER TABLE alumnos_baremada_tmp CHANGE COLUMN id_alumno id_alumno_baremada int";
		print(PHP_EOL);
		print($sql);
		print(PHP_EOL);
		if(!$this->conexion->query($sql)) return 0;
  
      $sql="CREATE TABLE IF NOT EXISTS alumnos_baremada_final select a.*,b.* FROM alumnos_baremada_tmp a left join baremo b on b.id_alumno=a.id_alumno_baremada WHERE fase_solicitud!='borrador'"; 
		print(PHP_EOL);
		print($sql);
		print(PHP_EOL);
      if(!$this->conexion->query($sql)) return 0;
      
      $sql="DELETE FROM alumnos_baremada_final";
		print(PHP_EOL);
		print($sql);
		print(PHP_EOL);
		if(!$this->conexion->query($sql)) return 0;
      
      $sql="INSERT INTO alumnos_baremada_final select a.*,b.* FROM alumnos_baremada_tmp a left join baremo b on b.id_alumno=a.id_alumno_baremada WHERE fase_solicitud!='borrador'"; 
		print(PHP_EOL);
		print($sql);
		print(PHP_EOL);
		if(!$this->conexion->query($sql)) return 0;
      return 1;
	}
  public function copiaTabla($tipo,$centro=0)
	{
		if($tipo=='definitivo') $tabla_origen='alumnos_provisional';
		elseif($tipo=='provisional') $tabla_origen='alumnos';
		//copiamos registros de centros que todavía no han realizado el sorteo o q están en fase menos q 2
		$tabla_destino="alumnos_".$tipo;
		if($tipo=='provisional')
			$sql="INSERT IGNORE INTO $tabla_destino SELECT a.* from $tabla_origen a, centros c WHERE a.fase_solicitud!='borrador' and a.id_centro_destino=c.id_centro and c.fase_Sorteo<'2'";
		else
			$sql="INSERT IGNORE INTO $tabla_destino SELECT a.* from $tabla_origen a WHERE a.fase_solicitud!='borrador'";
		if($this->conexion->query($sql)) return 1;
		else return $this->conexion->error;

	}
  public function copiaTablaFase2($tipo,$centro=0)
	{
		$tabla="alumnos_".$tipo;
		$sql='DELETE from '.$tabla;
		$res=$this->conexion->query($sql);
		if(!$res) return $this->conexion->error;
		$sqlfase2="SELECT
t1.id_alumno,t1.nombre,t1.apellido1,t1.apellido2,t1.localidad,t1.calle_dfamiliar,'nodata'
as coordenadas,t1.nombre_centro,t1.tipoestudios,t1.fase_solicitud,t1.estado_solicitud,t1.transporte,t1.nordensorteo,t1.nasignado,t1.puntos_validados,t1.id_centro,t2.centro1,t2.id_centro1,t3.centro2,t3.id_centro2,t4.centro3,t4.id_centro3,t5.centro4,t5.id_centro4,t6.centro5,t6.id_centro5,t7.centro6,t7.id_centro6, 'nocentro' as centro_definitivo, '0' as id_centro_definitivo,t1.id_centro_estudios_origen as id_centro_origen,t8.centro_origen,t1.reserva,t1.reserva as reserva_original,'automatica' as tipo_modificacion,'0' as activado_fase3 FROM 
	(SELECT a.id_alumno, a.nombre, a.apellido1, a.apellido2,a.loc_dfamiliar as localidad,a.calle_dfamiliar,c.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,b.puntos_validados,a.id_centro_destino as id_centro,a.id_centro_estudios_origen,a.est_desp_sorteo,a.reserva FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno 
	left join centros c on a.id_centro_destino=c.id_centro  order by c.id_centro desc, a.tipoestudios asc,a.transporte desc, b.puntos_validados desc)
	as t1 
	left join 
	(SELECT a.id_alumno,c.id_centro as id_centro1, c.nombre_centro as centro1 from alumnos a, centros c where c.id_centro=a.id_centro_destino1) 
	as t2 on t1.id_alumno=t2.id_alumno
left join 
	(SELECT a.id_alumno,c.id_centro as id_centro2, c.nombre_centro as centro2 from alumnos a, centros c where c.id_centro=a.id_centro_destino2) 
	as t3 on t1.id_alumno=t3.id_alumno
left join 
	(SELECT a.id_alumno,c.id_centro as id_centro3, c.nombre_centro as centro3 from alumnos a, centros c where c.id_centro=a.id_centro_destino3) 
	as t4 on t1.id_alumno=t4.id_alumno
left join 
	(SELECT a.id_alumno,c.id_centro as id_centro4, c.nombre_centro as centro4 from alumnos a, centros c where c.id_centro=a.id_centro_destino4) 
	as t5 on t1.id_alumno=t5.id_alumno
left join 
	(SELECT a.id_alumno,c.id_centro as id_centro5, c.nombre_centro as centro5 from alumnos a, centros c where c.id_centro=a.id_centro_destino5) 
	as t6 on t1.id_alumno=t6.id_alumno
left join 
	(SELECT a.id_alumno,c.id_centro as id_centro6, c.nombre_centro as centro6 from alumnos a, centros c where c.id_centro=a.id_centro_destino6) 
	as t7 on t1.id_alumno=t7.id_alumno
left join 
	(SELECT a.id_alumno,c.id_centro as id_centro_origen, c.nombre_centro as centro_origen from alumnos a, centros c where c.id_centro=a.id_centro_estudios_origen) 
	as t8 on t1.id_alumno=t8.id_alumno WHERE t1.fase_solicitud!='borrador' and t1.est_desp_sorteo='noadmitida'
";
		$sql='INSERT INTO '.$tabla.' '.$sqlfase2;
		print(PHP_EOL.$sql.PHP_EOL);
		if($this->conexion->query($sql)) return 1;
		else return $this->conexion->error;

	}
  public function compruebaReservas($centro=0)
	{
//si alguien reserva una plaza se supone q el centro ha marcado q continua asi q no genera vacante
//si alguien reserva laza incorrectamente debe anularse esa reserva y anotarse en un fichero asi como en la base de datos, añadiendo un campo llamado reserva incorrecta
//en el formulario de fase 2 deberá aparecer si tiene o no reserva y esta es correcta 
	}
  public function copiaTablaProvisionales($centro=0)
	{
		$sql_provisionales='DELETE from alumnos_provisional';
		if($this->conexion->query($sql_provisionales)==0) return 0;

		$sql_provisionales='INSERT IGNORE INTO alumnos_provisional SELECT * from alumnos';
		if($this->conexion->query($sql_provisionales)) return 1;
		else return 0;

	}
  public function getCentrosIds()
	{
	$ares=array();
	$sql="SELECT id_centro FROM centros where fase_sorteo<2";
	$res=$this->conexion->query($sql);
	if(!$res) return $this->conexion->error;
	while($row=$res->fetch_row())
		$ares[]=$row;
	return  $ares;
	} 
 
}
?>
