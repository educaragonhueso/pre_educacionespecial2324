<?php
class Solicitud {
   private $conexion; 
   public function __construct($conexion) 
	{
      $this->conexion=$conexion;
   }
     
  public function getCorreoCentro($cod_centro) 
  {
	$query="SELECT correo FROM centros WHERE id_centro='$cod_centro'";	
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->correo;
	else return -1;
      
  }
  public function getClave($token) 
  {
	$query="SELECT clave_original FROM alumnos a, usuarios u  WHERE a.id_usuario=u.id_usuario and token='$token'";	
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->clave_original;
	else return -1;
      
  }
  public function getReclamacion($id_alumno,$tipo) 
  {
      $query="SELECT motivo FROM reclamaciones WHERE tipo='$tipo' and id_alumno=$id_alumno";	
      $r=$this->conexion->query($query);
      $row = $r->fetch_object();
      if(isset($row))	
         return $row->motivo;
      else return 'No hay datos';
  }
  public function getNifTutor($token) 
  {
	$query="SELECT dni_tutor1 FROM alumnos WHERE token='$token'";	
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->dni_tutor1;
	else return -1;
      
  }
  public function getCorreo($token,$log) 
  {
	$query="SELECT email FROM alumnos WHERE token='$token'";	
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->email;
	else return -1;
      
  }
  public function getTokenCentro($id_centro) 
  {
	$query="SELECT token FROM centros WHERE id_centro=$id_centro";	
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->token;
	else return 0;
  }
  public function getTokenAlumno($id_alumno) 
  {
     $query="SELECT token FROM alumnos WHERE id_alumno=$id_alumno";	
     $r=$this->conexion->query($query);
     $row = $r->fetch_object();
     if(isset($row))	
      return $row->token;
     else return 0;
  }
  public function getTelefono($token,$log) 
  {
	$query="SELECT tel_dfamiliar1 FROM alumnos WHERE token='$token'";	
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->tel_dfamiliar1;
	else return -1;
  }
  public function getDocHtml($id_alumno,$dir,$rol,$doc='solicitud')
  {
   $extensiones=array("jpg","jpeg","png");
   $res="NO DOC";
   $ndir=$dir."/".$id_alumno;
   //return $ndir;
   $htmldoc='';
    if(file_exists($ndir)==0) return '';
    if ($handle = opendir($ndir)) 
    {
       while (false !== ($fichero = readdir($handle))) {
           if ($fichero != "." && $fichero != "..") {
                $ext = pathinfo(strtolower($fichero), PATHINFO_EXTENSION);
                if(in_array($ext, $extensiones))
                  $htmldoc.=$this->makeHtmlDoc($ndir,$fichero,'img',$ext,$id_alumno,$rol,$doc);
               else
                  $htmldoc.=$this->makeHtmlDoc($ndir,$fichero,'pdf',$ext,$id_alumno,$rol,$doc);
           }
       }
       closedir($handle);
    }
   $fimagen="<div style='width:70%'>"; 
   return $fimagen.$htmldoc."</div>";
  }
  public function makeHtmlDoc($ndir,$f,$tipo,$ext,$id_alumno,$rol,$doc)
  {
   $ret="";
   if($tipo=='pdf')
   {
      $ret.="<h5><b>Documento: </b></h5>";
      $idfile=str_replace('.','',$f);
      $idfile=str_replace(' ','_',$idfile);
      $ret.='<div>';
      $ret.="<p class='docpdf' id='".$idfile."'>$f</p>";
      if($doc=='solicitud')
         $ret.="<div class='verdocumentos'><a href='scripts/fetch/uploads/$id_alumno/".$f."' style='color:black!important' target='_blank'>Ver documento</a></div>";
      else if($doc=='baremo')
         $ret.="<div class='verdocumentos'><a href='scripts/fetch/reclamacionesbaremo/$id_alumno/".$f."' style='color:black!important' target='_blank'>Ver documento</a></div>";
      else if($doc=='provisional')
         $ret.="<div class='verdocumentos'><a href='scripts/fetch/reclamacionesprovisional/$id_alumno/".$f."' style='color:black!important' target='_blank'>DESCARGAR PDF $f</a></div>";
      //los centros no pueden modifciar los documentos
      if($rol!='centro')
         $ret.= "<button class='bdocfile' ficherooriginal='".$f."'  fichero='$idfile'>Retirar documento</button>";
      $ret.='</div>';
      $ret.='<hr>';
   }
   else
   {
      $ret.="<h5><b>Imagen: </b></h5>";
      $data = file_get_contents($ndir."/".$f);
      $idfile=str_replace('.','',$f);
      $idfile=str_replace(' ','_',$idfile);
      $imgbase64 = 'data:image/' . $ext . ';base64,' . base64_encode($data);
      if($doc=='solicitud')
         $ret.="<div class='verdocumentos'><a href='scripts/fetch/uploads/$id_alumno/".$f."' style='color:black!important' target='_blank'>VER IMAGEN </a></div>";
      else if($doc=='baremo')
         $ret.="<div class='verdocumentos'><a href='scripts/fetch/reclamacionesbaremo/$id_alumno/".$f."' style='color:black!important' target='_blank'>VER IMAGEN </a></div>";
      $ret.="<figure id='$idfile' class='containerZoom' style='background-image:url(".$imgbase64.");background-size: 150%;'>";
      $ret.="<img style='width:60%' src='".$imgbase64."'></figure><br>"; 
      if($rol!='centro')
         $ret.= "<button class='bdoc' ficherooriginal='".$f."' fichero='".$idfile."'>Retirar imagen</button>";
      $ret.='<hr>';
   }
   return $ret;
  }


  public function copiaTablaBaremacion($centro,$tipoa='alumnos_baremacion_final',$tipob='baremo_baremacion_final')
	{
		$tabla_destino_alumnos=$tipoa;
		$tabla_destino_baremo=$tipob;
		$sqla="SELECT * FROM alumnos";
		$sqlb="SELECT * FROM baremo";
		if($centro!=1)
		{
			$dsqla='DELETE from '.$tabla_destino_alumnos.' WHERE id_centro_destino='.$centro;
			$isqla='INSERT IGNORE INTO '.$tabla_destino_alumnos.' '.$sqla.' and id_centro_destino='.$centro;
			$dsqlb='DELETE from '.$tabla_destino_baremo.' WHERE id_centro_destino='.$centro;
			$isqlb='INSERT IGNORE INTO '.$tabla_destino_baremo.' '.$sqlb.' and id_centro_destino='.$centro;
		}
		else
		{
			$dsqla='DELETE from '.$tabla_destino_alumnos;
			$isqla='INSERT IGNORE INTO '.$tabla_destino_alumnos.' '.$sqla;
			$dsqlb='DELETE from '.$tabla_destino_baremo;
			$isqlb='INSERT IGNORE INTO '.$tabla_destino_baremo.' '.$sqlb;
		}
		if($this->db()->query($dsqla) and $this->db()->query($dsqlb) and $this->db()->query($isqla) and $this->db()->query($isqlb))
	      return 1;	
      else 
         return $this->db()->error;

	}
   public function copiaCentrosFinal()
   {
	   $sql="SELECT * FROM centros";
	   //volcamos la tabla con los datos de solicitudes y los del baremo tal como aparecen en el listado de provisionales o definitivosa
			$dsql='DELETE from centros_final';
			$isql="INSERT IGNORE INTO centros_final $sql";
		
		if($this->conexion->query($dsql))
			if($this->conexion->query($isql)) return 1;
			else return $this->conexion->error;
		else return $this->conexion->error;
	}
   public function copiaTablaFinal()
   {
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, tipo_modificacion,reserva FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='ebo' order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
	   $sql="SELECT a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.est_desp_sorteo,c.nombre_centro,b.*,a.id_centro_destino as id_centro_destino FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'";
	   //volcamos la tabla con los datos de solicitudes y los del baremo tal como aparecen en el listado de provisionales o definitivosa
			$dsql='DELETE from alumnos_fase2_final';
			$isql="INSERT IGNORE INTO alumnos_fase2_final $sql";
		
		if($this->conexion->query($dsql))
			if($this->conexion->query($isql)) return 1;
			else return $this->conexion->error;
		else return $this->conexion->error;
	}
   public function copiaTablaFase2Final($log)
   {
		$sql="SELECT a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.est_desp_sorteo,c.nombre_centro,b.*,a.id_centro_destino as id_centro_destino FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'";
	//volcamos la tabla con los datos de solicitudes y los del baremo tal como aparecen en el listado de provisionales o definitivosa
		if($centro!=1)
			{
			$dsql='DELETE from '.$tabla_destino.' WHERE id_centro_destino='.$centro;
			$isql='INSERT IGNORE INTO '.$tabla_destino.' '.$sql.' and id_centro_destino='.$centro;
			}
		else
			{
			$dsql='DELETE from '.$tabla_destino;
			$isql='INSERT IGNORE INTO '.$tabla_destino.' '.$sql;
			}
			########################################################################################
			$log->warning("CARGANDO TABLA DEFINITIVOS");
			$log->warning($isql);
			########################################################################################
		
		if($this->conexion->query($dsql))
			if($this->conexion->query($isql)) return 1;
			else return $this->conexion->error;
		else return $this->conexion->error;

	}
  public function copiaTablaCentro($centro,$tabla_destino,$log)
	{
		$sql="SELECT a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.est_desp_sorteo,c.nombre_centro,b.*,a.id_centro_destino as id_centro_destino FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'";
	//volcamos la tabla con los datos de solicitudes y los del baremo tal como aparecen en el listado de provisionales o definitivosa
		if($centro!=1)
			{
			$dsql='DELETE from '.$tabla_destino.' WHERE id_centro_destino='.$centro;
			$isql='INSERT IGNORE INTO '.$tabla_destino.' '.$sql.' and id_centro_destino='.$centro;
			}
		else
			{
			$dsql='DELETE from '.$tabla_destino;
			$isql='INSERT IGNORE INTO '.$tabla_destino.' '.$sql;
			}
			########################################################################################
			$log->warning("CARGANDO TABLA DEFINITIVOS");
			$log->warning($isql);
			########################################################################################
		
		if($this->conexion->query($dsql))
			if($this->conexion->query($isql)) return 1;
			else return $this->conexion->error;
		else return $this->conexion->error;

	}
  public function crearPdf($id,$log)
	{
	$file="sol".$id;
	$pdf = new PDF();
	$pdf->SetFont('Arial','',14);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$texto="We could have specified italics with I, underlined with U or a regular font with an empty string
 (or any combination). Note that the font size is given in points, not millimeters (or another user unit); 
it's the only exception. The other standard fonts are Times, Courier, Symbol and ZapfDingbats.
We can now print a cell with Cell(). A cell is a rectangular area, possibly framed, which contains a line of text. It is output at the current position. We specify its dimensions, its text (centered or aligned), if borders should be drawn, and where the current position moves after it (to the right, below or to the beginning of the next line). To add a frame, we would do this: ";
	$texto1="We could have specified italics with I, underlined with U or a regular font with an empty string";
	$pdf->MultiCell(0,10,$texto);

	$pdf->Output(DIR_BASE.'/scripts/datossalida/pdfsolicitudes/'.$file.'.pdf','F');
	$dsolicitud=$this->getSolData($id,'existente',0,'alumnos',$log);	
	return 1;
	}

  public function getFaseCentro($id_centro=1,$log){
	$query="SELECT fase_sorteo FROM centros WHERE id_centro=$id_centro";	
   $log->warning("CONSULTA FASECENTRO: ".$query);
	$r=$this->conexion->query($query);
	$row = $r->fetch_object();
	if(isset($row))	
		return $row->fase_sorteo;
	else return -1;
	}
  public function update($sol,$id,$token,$rol,$log)
  {
      //comprobamos si se ha marcado el check de hermanos en el baremo
      $check_hermanosbaremo=$sol['num_hbaremo'];
      //si el centro esta en estado de baremacion, ya se ha hecho el sorteo, pasamos a la tabal de provisionales
      $fase=0;
      //hay q descomentarlo pero falla cuando los campos están deshabilitados
      /*
      if(isset($sol['id_centro_destino']))
      {
         $fase=$this->getFaseCentro($sol['id_centro_destino'],$log);
         if($fase==-1) return 0;
      }
      */
      $query_alumnos="UPDATE alumnos SET ";
      $query_hermanos_admision="UPDATE alumnos SET ";
      $query_hermanos_baremo="UPDATE alumnos_hermanos_admision SET ";
      $query_baremo="UPDATE baremo SET ";
      $query_tributantes="UPDATE tributantes set ";
      //creamos la consulta para la tabla de alumnos
      //controlamos si hay algun cambio en los datos
      $dalumno=0;
      $dhermanos=0;
      $dbaremo=0;
      $numhermanos_baremo=0;
      $numhermanos_admision=0;
      $num_tributantes=0;
      $hermanos_admision=array();
      $hermanos_baremo=array();
      $tributantes=array();
      
      //generamos datos para la tabla baremo. Campos con prefijo baremo_
      foreach($sol as $key=>$elto)
      {
         if(strpos($key,'baremo_')!==false) 
         {
            if(strlen($elto)==0) continue;
            
            $key=str_replace("baremo_","",$key);	
            $key=str_replace($id,"",$key);	
            $query_baremo.=$key."='".$elto."',";	
            //contamos los hermanos que hay
            if(strpos($key,'hermanos_datos_baremo')!==false) $numhermanos_baremo++; 
            $dbaremo=1;
         }
      }
      if($dbaremo==1)
      {
    //cerramos actualizacion baremo
      //si el id del alumno es 0 usamo sel token
      if($id==0)
         $id=$this->getIdFromToken($token,$log);
      $log->warning("CONSULTA ACTUALIZACION BAREMO ID ALUMNO: ".$id);
      $sql_baremo=trim($query_baremo,',')." WHERE id_alumno=".$id;
      if($dbaremo==1) $update=$this->conexion->query($sql_baremo);
      $log->warning("CONSULTA ACTUALIZACION BAREMO");
      $log->warning($sql_baremo);
      }

      //generamos datos para la tabla alumnos. Sin campos con prefijo -hermanos_-
      foreach($sol as $key=>$elto)
      {
      if(strpos($key,'baremo_')!==false) continue;
      if(strpos($key,'fase_solicitud')!==false)
      {
         $query_alumnos.="fase_solicitud"."='".$elto."',";	
         continue;
      } 
      if($key==='reserva')
      {
         $query_alumnos.="reserva"."='".$elto."',";	
         continue;
      } 
      if(strpos($key,'transporte')!==false){
         $query_alumnos.="transporte"."='".$elto."',";	
               continue;
               } 
      if(strpos($key,'estado_solicitud')!==false){
         $query_alumnos.="estado_solicitud"."='".$elto."',";	
               continue;
               } 
      //capturamos datos de alumnos
      if(strpos($key,'hermanos')===false) 
      {
         if(strlen($elto)==0) continue;
         $key=str_replace($id,"",$key);	
         $query_alumnos.=$key."='".$elto."',";	
         $dalumnos=1;
      }
      //generamos array para datos de hermanos en admision
      elseif(strpos($key,'admision')!==false)
      {
            //obtenemos el numero de hermano y el nombre del campo
            $campo=str_replace('_admision','',$key);
            $campo=str_replace('hermanos_','',$campo);
            $idc=substr($campo,-1);
            $campo=substr($campo,0,-1);
            $hermanos_admision[$idc][$campo]=$sol[$key];
      }
      //generamos array para datos de hermanos en baremo
      elseif(strpos($key,'_baremo')!==false)
      {
            //obtenemos el numero de hermano y el nombre del campo
            $campo=str_replace('_baremo','',$key);
            $campo=str_replace('hermanos_','',$campo);
            $idc=substr($key,-1);
            //quitamos último carcater indicador del numero de hermano
            $campo=substr($campo,0,-1);
            $hermanos_baremo[$idc][$campo]=$sol[$key];
      }
    }
      //cerramos actualizacion alumno
      $sql=trim($query_alumnos,',')." WHERE id_alumno=".$id;
      if($dalumnos==1) $update=$this->conexion->query($sql);
      
      $log->warning("CONSULTA ACTUALIZACION SOLICITUD ALUMNOS");
      $log->warning($sql);
       
      $log->warning("DATOS ACTUALIZACION SOLICITUD HERMANOS ADMISION: ");
      $log->warning(print_r($hermanos_admision,true));
      $log->warning("DATOS ACTUALIZACION SOLICITUD, DATOS HERMANOS BAREMO: ");
      $log->warning(print_r($hermanos_baremo,true));

      //si el rol es de centro no borramos datos
      if($rol=='alumno' or $rol=='admin')
      {
         //eliminamos los hermanos para volver a insertarlos
         $sql="DELETE FROM alumnos_hermanos_baremo WHERE id_alumno=$id";
         $delete=$this->conexion->query($sql);
         $sdatosh="";
         $nh=0;

         foreach($hermanos_admision as $key=>$hermano)
         {
            $log->warning("PROCESANDO HERMANO ADMISION: ");
            $log->warning(print_r($hermano,1));
            if($key==0) continue;
            //comprobamos si hay hermanos en la tabla, en caso contrario hay q insertarlos
            $existe_hermano=$this->comprobarHermano('','admision',$hermano['token']);	
            if($existe_hermano==1)
            {
               if($hermano['nombre']=='nodata')
               {
                  //si algunon de los datos está vacío eliminamos el hermano
                  $sql="UPDATE alumnos set fase_solicitud='borrador' WHERE token='".$hermano['token']."'";
                  $update=$this->conexion->query($sql);
                  $log->warning("HERMANO ADMISON EXISTE, PERO DATOS INCOMPLETOS, SE PONE A BORRADOR: ".$sql);
                  $log->warning($sql);
                  continue;
               }
               else
               {
                  $id_centro_estudios_origen=$this->getIdCentro($hermano['id_centro_estudios_origen'],$log);
                  $sql=$query_hermanos_admision."apellido2='".$hermano['apellido2']."',apellido1='".$hermano['apellido1']."',nombre='".$hermano['nombre']."',fnac='".$hermano['fnac']."',tipoestudios='".$hermano['tipoestudios']."'".",reserva=".$hermano['reserva'];	
                  $sql=trim($sql,',')." WHERE token='".$hermano['token']."'";
                  $log->warning("HERMANO ADMISON EXISTE, CONSULTA ACTUALIZACION:");
                  $log->warning($sql);
                  $update=$this->conexion->query($sql);
               }
               if($nh==0)
                  $sdatosh.='id_alumno:clave:'.$hermano['token'];
               else
                  $sdatosh.=':id_alumno:clave:'.$hermano['token'];
               $nh++;
            }
            else //si no existe el hermano como alumno, lo generamos
            {         
               $log->warning("HERMANO ADMISION NO EXISTE");
               $log->warning("GENERANDO ACTUALIZACION SOLICITUD CONJUNTA PARA HERMANO\n ");
               $log->warning(print_r($hermano,true));
            
               $dni_tutor1=$sol['dni_tutor1'];
               if($hermano['nombre']=='' or $hermano['apellido1']=='' or $hermano['apellido2']=='' or $hermano['fnac']=='')
                  continue;
               $datos_usuario_hermano=$this->generarUsuario($dni_tutor1,$log);
               $dhc[0]['clave']=$datos_usuario_hermano['clave'];
               $dhc[0]['id_usuario']=$datos_usuario_hermano['id_usuario'];
               $id_usuario_hermano=$datos_usuario_hermano['id_usuario'];

               $log->warning("GENERADO USUARIO HERMANO CONJUNTA: ");
               $log->warning(print_r($datos_usuario_hermano,true));

               if($id_usuario_hermano==0) return 0;
               //filtramos los datos del  alumno
               $sol=$this->getDatosTabla($sol,'alumnos',0,'',$log);	
               $datos_hermano=$this->generarSolicitudHermano($sol,$hermano,$id_usuario_hermano,'update',$log);
               
               $log->warning("GENERADO HERMANO CONJUNTA: ");
               $log->warning(print_r($datos_hermano,true));

               $dhc[0]['id_hermano']=$datos_hermano['id_alumno'];
               $dhc[0]['token']=$datos_hermano['token'];
               //recogemos los valores para mostrar el enlace del nuevo hermano
               if($nh==0)
                  $sdatosh.=$datos_hermano['id_alumno'].':'.$datos_usuario_hermano['clave'].':'.$datos_hermano['token'];
               else
                  $sdatosh.=':'.$datos_hermano['id_alumno'].':'.$datos_usuario_hermano['clave'].':'.$datos_hermano['token'];
               $nh++; 
               $id_hermano=$datos_hermano['id_alumno'];
               if($id_hermano==0) return 0;

               $log->warning("GENERADA ACTUALIZACION SOLICITUD HERMANO");
               if($this->actualizarHermanoAdmision($id_hermano,$id,$log)!=1){$log->warning("error gen relacion"); return 0;}
               $log->warning("GENERADA RELACION HERMANOS");
               
               if($this->generarBaremoHermanoAdmision($id_hermano,$log)!=1){$log->warning("error gen baremo hermano admisión"); return 0;}
               $log->warning("GENERADO BAREMO HERMANOS ADMISIÓN");
            }
         }
         //creamos la consulta para la tabla de hermanos, para baremo
         foreach($hermanos_baremo as $hermano)
         {
            
            if($check_hermanosbaremo==0) break;
            if($hermano['fnacimiento']=='') $hermano['fnacimiento']='2000-01-01';
            $sql="INSERT INTO alumnos_hermanos_baremo(id_alumno,nombre,apellido1,apellido2,modalidad,nivel,fnacimiento,tipo) VALUES($id,'".$hermano['nombre']."','".$hermano['apellido1']."','".$hermano['apellido2']."','".$hermano['modalidad']."','".$hermano['nivel']."','".$hermano['fnacimiento']."','baremo')";
            $update=$this->conexion->query($sql);
            $log->warning("CONSULTA ACTUALIZACION SOLICITUD HERMANOS BAREMO");
            $log->warning($sql);
         }
      }
      return $sdatosh;
   }
   
   public function comprobarHermano($idregistro,$tipo,$token='')
   {
      if($tipo=='admision')
			$query="SELECT * FROM alumnos WHERE token='$token'";	
		else
         $query="SELECT * FROM hermanos WHERE id_registro=$idregistro";	
      $res=$this->conexion->query($query);
      if($res->num_rows>0) return 1;
      else return 0;
	 }
    public function getDatosSolicitud($id){
			$query="SELECT apellido1, apellido2, nombre FROM alumnos WHERE id_alumno='$id'";	
			$r=$this->conexion->query($query);
			$row = $r->fetch_object();
			if(isset($row))	return $row;
			else return 0;
		}
    public function get_asignado($tipo,$idcentro){
			$query="SELECT nasignado FROM alumnos WHERE id_centro_destino=$idcentro and tipoestudios='$tipo' order by nasignado desc limit 1";	
			$r=$this->conexion->query($query);
			$row = $r->fetch_object();
			if(isset($row))	return $row->nasignado;
			else return 0;
		}

    public function getDatosTabla($datos,$tabla,$id=0,$tipo,$log){
		$darray=array();
		//quitamos el id del array para recoger solo las claves de hermanos
		unset($datos['id_alumno']);
		if($tabla=='alumnos')
		{
         foreach($datos as $key=>$elto)
         {
            #recogemos la fase y el estado de la solicitud
            if(strpos($key,'transporte')!==FALSE){ $darray['transporte']=$elto;continue;}
            if(strpos($key,'fase_solicitud')!==FALSE){ $darray['fase_solicitud']=$elto;continue;}
            if(strpos($key,'estado_solicitud')!==FALSE){ $darray['estado_solicitud']=$elto;continue;}
            if(strpos($key,'tributantes_')!==FALSE)  continue;
            if(strpos($key,'baremo_')===FALSE && strpos($key,'hermanos_')===FALSE)   $darray[$key]=$elto;
         }
         //determinamos el tipo de alumno, de momento ponemos ebo
         $tipoestudios='ebo';
		}
		elseif($tabla=='hermanos')
		{
			foreach($datos as $key=>$elto)
			{
            if(strpos($key,$tabla)!==FALSE)
            {
               //distinguimos entre datos de hermanos para el baremo y para la admision
               if($tipo=='baremo')
               {
                  if(strpos($key,'_baremo')!==FALSE) 
                     $darray[$key]=$elto;
               }
               elseif($tipo=='admision')
               {
                  if(strpos($key,'_admision')!==FALSE) 
                     $darray[$key]=$elto;
               }
            }
		   }
			$darray['id_alumno']=$id;
		}
		elseif($tabla=='tributantes')
		{
			foreach($datos as $key=>$elto)
			{
				if(strpos($key,$tabla)!==FALSE)
				{
					$darray[$key]=$elto;
				}
			}
         $darray['id_alumno']=$id;
		}
		else //tabla de baremo
		{
         $darray['id_alumno']=$id;
         foreach($datos as $key=>$elto)
            if(strpos($key,'baremo_')!==FALSE) 
               $darray[$key]=$elto;
		}
	return $darray;
	}

  public function save_tributantes($data,$log)
	{
		$log->warning("DATOS INSERCION TRIBUTANTE:");
		$log->warning(print_r($data,true));

		$i=6;
		$guardar=1;
      foreach($data as $key=>$elto)
      {
         if($i%6==0) 
         {
            if($i!=6)
            {
               $query.=")";
               //solo guardamos si hay datos
               if($guardar==1)	
               {
                  $log->warning("CONSULTA INSERCION TRIBUTANTES: ");
                  $log->warning($query);
                  $savetributante=$this->conexion->query($query);
               }
               $guardar=1;
             }
            //solo guardamos si hay datos
            if($data[$key]=='') $guardar=0;
            $query="INSERT INTO tributantes(id_alumno,nombre,id_tributante,apellido1,apellido2,parentesco,dni) VALUES('".$data['id_alumno']."'"; 
            $query.=",'".$data[$key]."'";
            $i++;
         }
         else
         {
            if($data[$key]==''){ $data[$key]='DEFAULT';$query.=",".$data[$key];}
            else  $query.=",'".$data[$key]."'";
            $i++;
         }
      }
	return 1;
	}
   
   public function save_baremo($data,$log)
	{
		$query="INSERT INTO baremo("; 
		foreach($data as $key=>$elto)
	   {
         //Campos de tipo RADIO se llaman igual, hay q quitarles el codigo o id
         // if(strpos($key,'conjunta')!==FALSE)  $key='conjunta';
         if(strpos($key,'tutores_centro')!==FALSE and strpos($key,'validar')===FALSE)  $key='tutores_centro';
         if(strpos($key,'renta_inferior')!==FALSE and strpos($key,'validar')===FALSE)  $key='renta_inferior';
         if(strpos($key,'acogimiento')!==FALSE and strpos($key,'validar')===FALSE)  $key='acogimiento';
         if(strpos($key,'genero')!==FALSE and strpos($key,'validar')===FALSE)  $key='genero';
         if(strpos($key,'terrorismo')!==FALSE and strpos($key,'validar')===FALSE)  $key='terrorismo';
         if(strpos($key,'tipo_familia_numerosa')!==FALSE and strpos($key,'validar')===FALSE)  $key='tipo_familia_numerosa';
         if(strpos($key,'tipo_familia_monoparental')!==FALSE and strpos($key,'validar')===FALSE)  $key='tipo_familia_monoparental';
         if(strlen($elto)!=0) $query.=str_replace('baremo_','',$key).",";
		}
		$query=trim($query,',');
		$query.=") VALUES(";
		foreach($data as $key=>$elto)
		{
         if(strlen($elto)!=0) 
            $query.="'".$elto."',";
		}
		$query=trim($query,',');
		$query.=")";
		
		$savebaremo=$this->conexion->query($query);

		$log->warning("CONSULTA INSERCION BAREMO:");
		$log->warning($query);

		if($savebaremo) return 1;
		else return 0;
	}
   public function deleteHermanosBaremo($data,$log)
	{
		$log->warning("BORRANDO DATOS INSERCION HERMANOS BAREMO:");
		$log->warning(print_r($data,true));
      $query="DELETE FROM alumnos_hermanos_baremo WHERE id_alumno=".$data['id_alumno']; 
	   return 1;
   }
   public function saveHermanosBaremo($data,$log)
	{
		$log->warning("DATOS INSERCION HERMANOS BAREMO:");
		$log->warning(print_r($data,true));
		$i=6;
		$guardar=1;
      foreach($data as $key=>$elto)
      {
         if($i%6==0) 
         {
            if($i!=6)
            {
               $query.=")";
               //solo guardamos si hay datos
               if($guardar==1)	
               {
                  $log->warning("CONSULTA INSERCION HERMANOS BAREMO: ");
                  $log->warning($query);
                  $savehermano=$this->conexion->query($query);
               }
               $guardar=1;
            }
            //solo guardamos si hay datos
            if($data[$key]=='') $guardar=0;
            $query="INSERT INTO alumnos_hermanos_baremo(id_alumno,nombre,apellido1,apellido2,fnacimiento,modalidad,nivel) VALUES('".$data['id_alumno']."'"; 
            $query.=",'".$data[$key]."'";
            $i++;
            
         }
         else
         {
            if($data[$key]!='')  $query.=",'".$data[$key]."'";
            //if($data[$key]==''){$i++; continue;}
            $i++;
         }
      }
	}
  
   public function saveHermanosAdmision($sol,$data,$id_alumno,$dni_tutor1,$log)
   {
      $dhc=array();
      $idshermanos=array();
		$log->warning("ARRAY INSERCION HERMANOS ADMISION CONJUNTA:");
      $datoshermanos=$this->genDatosHermanosAdmision($data,$log);
      $log->warning(print_r($datoshermanos,true));
      if(isset($datoshermanos[0]))
      {
         $token_hermano=$datoshermanos[0][0];
         $res=$this->checkSolicitud($token_hermano);
		   $log->warning("RESULTADO COMPROBACION SOL PRIMER HERMANO:");
         $log->warning(print_r($res,true));
         //si no hay un token del hermano se genera solicitud nueva y la relación con el hermano
         if(sizeof($res)==0)
         {
            $log->warning("GENERANDO SOLICITUD CONJUNTA PARA PRIMER HERMANO\n ");
            $datos_usuario_hermano=$this->generarUsuario($dni_tutor1,$log);
            $dhc[0]['clave']=$datos_usuario_hermano['clave'];
            $dhc[0]['id_usuario']=$datos_usuario_hermano['id_usuario'];
            $id_usuario_hermano=$datos_usuario_hermano['id_usuario'];
   
            $log->warning("GENERADO USUARIO HERMANO CONJUNTA: ");
            $log->warning(print_r($datos_usuario_hermano,true));

            if($id_usuario_hermano==0) return 0;
            $datos_hermano=$this->generarSolicitudHermano($sol,$datoshermanos[0],$id_usuario_hermano,'save',$log);
            
            $log->warning("GENERADO HERMANO CONJUNTA: ");
            $log->warning(print_r($datos_hermano,true));

            $dhc[0]['id_hermano']=$datos_hermano['id_alumno'];
            $idshermanos[]=$datos_hermano['id_alumno'];

            $dhc[0]['token']=$datos_hermano['token'];
            $sdatosh=$datos_hermano['id_alumno'].':'.$datos_usuario_hermano['clave'].':'.$datos_hermano['token'];

            $id_hermano=$datos_hermano['id_alumno'];
            if($id_hermano==0) return 0;

            $log->warning("GENERADA SOLICITUD HERMANO");
            if($this->generarHermanoAdmision($id_hermano,$id_alumno,$log)!=1){$log->warning("error gen relacion"); return 0;}
            $log->warning("GENERADA RELACION HERMANOS");
            
            if($this->generarBaremoHermanoAdmision($id_hermano,$log)!=1){$log->warning("error gen baremo hermano admisión"); return 0;}
            $log->warning("GENERADO BARMEO HERMANOS ADMISIÓN");
         }
      }
      if(isset($datoshermanos[1]))
      {
         $token_hermano=$datoshermanos[1][0];
         $res=$this->checkSolicitud($token_hermano);
		   $log->warning("RESULTADO COMPROBACION SOL SEGUNDO HERMANO:");
         $log->warning(print_r($res,true));
         //si no hay un token del hermano se genera solicitud nueva y la relación con el hermano
         if(sizeof($res)==0)
         {
            $log->warning("GENERANDO SOLICITUD CONJUNTA PARA SEGUNDO HERMANO\n ");
            $datos_usuario_hermano=$this->generarUsuario($dni_tutor1,$log);
            $dhc[0]['clave']=$datos_usuario_hermano['clave'];
            $dhc[0]['id_usuario']=$datos_usuario_hermano['id_usuario'];
            $id_usuario_hermano=$datos_usuario_hermano['id_usuario'];
   
            $log->warning("GENERADO USUARIO SEGUNDO HERMANO CONJUNTA: ");
            $log->warning(print_r($datos_usuario_hermano,true));

            if($id_usuario_hermano==0) return 0;
            $datos_hermano=$this->generarSolicitudHermano($sol,$datoshermanos[1],$id_usuario_hermano,'save',$log);
            
            $log->warning("GENERADO SEGUNDO HERMANO CONJUNTA: ");
            $log->warning(print_r($datos_hermano,true));

            $dhc[0]['id_hermano']=$datos_hermano['id_alumno'];
            $idshermanos[]=$datos_hermano['id_alumno'];
            
            $dhc[0]['token']=$datos_hermano['token'];
            $sdatosh.=':'.$datos_hermano['id_alumno'].':'.$datos_usuario_hermano['clave'].':'.$datos_hermano['token'];

            $id_hermano=$datos_hermano['id_alumno'];
            if($id_hermano==0) return 0;

            $log->warning("GENERADA SOLICITUD SEGUNDO HERMANO");
            if($this->generarHermanoAdmision($id_hermano,$id_alumno,$log)!=1){$log->warning("error gen relacion"); return 0;}
            $log->warning("GENERADA RELACION SEGUNDO HERMANOS");
            
            if($this->generarBaremoHermanoAdmision($id_hermano,$log)!=1){$log->warning("error gen baremo hermano admisión"); return 0;}
            $log->warning("GENERADO BARMEO HERMANOS ADMISIÓN");
         }
      }
      if(isset($datoshermanos[2]))
      {
         $token_hermano=$datoshermanos[2][0];
         $res=$this->checkSolicitud($token_hermano);
		   $log->warning("RESULTADO COMPROBACION SOL TERCER HERMANO:");
         $log->warning(print_r($res,true));
         //si no hay un token del hermano se genera solicitud nueva y la relación con el hermano
         if(sizeof($res)==0)
         {
            $log->warning("GENERANDO SOLICITUD CONJUNTA PARA TERCER HERMANO\n ");
            $datos_usuario_hermano=$this->generarUsuario($dni_tutor1,$log);
            $dhc[0]['clave']=$datos_usuario_hermano['clave'];
            $dhc[0]['id_usuario']=$datos_usuario_hermano['id_usuario'];
            $id_usuario_hermano=$datos_usuario_hermano['id_usuario'];
   
            $log->warning("GENERADO USUARIO TERCER HERMANO CONJUNTA: ");
            $log->warning(print_r($datos_usuario_hermano,true));

            if($id_usuario_hermano==0) return 0;
            $datos_hermano=$this->generarSolicitudHermano($sol,$datoshermanos[2],$id_usuario_hermano,'save',$log);
            
            $log->warning("GENERADO TERCER HERMANO CONJUNTA: ");
            $log->warning(print_r($datos_hermano,true));

            $dhc[0]['id_hermano']=$datos_hermano['id_alumno'];
            $idshermanos[]=$datos_hermano['id_alumno'];
            
            $dhc[0]['token']=$datos_hermano['token'];
            $sdatosh.=':'.$datos_hermano['id_alumno'].':'.$datos_usuario_hermano['clave'].':'.$datos_hermano['token'];

            $id_hermano=$datos_hermano['id_alumno'];
            if($id_hermano==0) return 0;

            $log->warning("GENERADA SOLICITUD TERCER HERMANO");
            if($this->generarHermanoAdmision($id_hermano,$id_alumno,$log)!=1){$log->warning("error gen relacion"); return 0;}
            $log->warning("GENERADA RELACION TERCER HERMANO");
            
            if($this->generarBaremoHermanoAdmision($id_hermano,$log)!=1){$log->warning("error gen baremo hermano admisión"); return 0;}
            $log->warning("GENERADO BAREMO HERMANOS ADMISIÓN");
         }
      }
      //generamos relaciones entre hermanos
      foreach($idshermanos as $idh)
      {
         if(isset($idshermanos[0]) and isset($idshermanos[1]))
               $this->generarHermanoAdmision($idshermanos[0],$idshermanos[1],$log);
         if(isset($idshermanos[0]) and isset($idshermanos[2]))
               $this->generarHermanoAdmision($idshermanos[0],$idshermanos[2],$log);
         if(isset($idshermanos[0]) and isset($idshermanos[3]))
               $this->generarHermanoAdmision($idshermanos[0],$idshermanos[3],$log);
         if(isset($idshermanos[1]) and isset($idshermanos[2]))
               $this->generarHermanoAdmision($idshermanos[1],$idshermanos[2],$log);
         if(isset($idshermanos[1]) and isset($idshermanos[3]))
               $this->generarHermanoAdmision($idshermanos[1],$idshermanos[3],$log);
         if(isset($idshermanos[2]) and isset($idshermanos[3]))
               $this->generarHermanoAdmision($idshermanos[2],$idshermanos[3],$log);
      } 
      return $sdatosh; 
	}
   public function genDatosHermanosAdmision($data,$log)
   {
      $datoshermanos=array(); 
      $dhermano=array(); 
      //quitamos el id del alumno
      unset($data['id_alumno']);
		$log->warning("linea: 854 Solicitud.php GENERANDO DATOS INSERCION HERMANOS ADMISION CONJUNTA:");
		$log->warning(print_r($data,true));
		$ncampos=8;
      $i=9;
		$guardar=1;
      $j=0;
      foreach($data as $key=>$elto)
      {
         if($i%$ncampos==0) 
         {
            //hemos recogido todos los datos
            array_push($dhermano,$data[$key]);
            $comp=$this->checkArrayHermanoAdmision($dhermano);
            if($comp==1)
            {
		         $log->warning("DATOS HERMANO ADMISION:");
		         $log->warning(print_r($dhermano,true));
               $datoshermanos[$j]=$dhermano;
               $j++;
            }
            $dhermano=array();
            $i++;
         }
         else
         {
            array_push($dhermano,$data[$key]);
            $i++;
         }
      }
      return $datoshermanos;
	}
  
	public function checkArrayHermanoAdmision($array)
   {
      $i=0;
      if(sizeof($array)==0) return 0;
      foreach($array as $a) 
      {
         if($a=='' and $i<7) return 0;
         $i++;
      }
      return 1;
   }
	public function nvalores_array($array)
	{
		$nv=0;
		foreach($array as $key=>$elto)
		{
		if($elto!='') $nv++;
		}
	return $nv;
	}
	
	public function existeCuenta($clave=0,$usuario='')
	{
      $squery="SELECT nombre_usuario,clave_original FROM usuarios where nombre_usuario='".$usuario."' and clave_original=".$clave;
      $query=$this->conexion->query($squery);
      if($query->num_rows>0) return 0;
      else return 1;
	}
	public function save($sol,$id,$rol='centro',$log_nueva)
	{
      $nsol=$sol;
      $log_nueva->warning("ENTRANDO EN SAVEE");
      $hadmision='si';
      $hbaremo='si';

      if(!isset($sol['num_hbaremo']))  
      {
         $log_nueva->warning("NO HAY HERMANOS EN BAREMO");
         $hbaremo='no';
      }
      //filtramos los datos del  alumno
      $sol=$this->getDatosTabla($sol,'alumnos',0,'',$log_nueva);	
      
      $log_nueva->warning("DATOS DE ALUMNOS");
      $log_nueva->warning(json_encode($sol));
	  
      $dni_tutor1=$sol['dni_tutor1']; 
      $datos_usuario=$this->generarUsuario($dni_tutor1,$log_nueva);
      if($datos_usuario==-3) 
      {
         $log_nueva->warning("ERROR GENERANDO USUARIO: ");
         return -3;
      }
      $id_usuario=$datos_usuario['id_usuario'];

      $log_nueva->warning("GENERADO USUARIO: ");
      $log_nueva->warning($id_usuario);
	  
      //PROCESANDO ALUMNO
	   if($id_usuario>0)
		{
			$log_nueva->warning("USUARIO INSERTADO, ENTRANDO EN ALUMNO");
		
			$sol['id_usuario']=$id_usuario;
         $id_alumno=$this->generarAlumno($sol,$log_nueva); 
         //si obtenemos error de clave, el alumno scon mismo nombre, apellido, dni de tutor y fecha de nacimiento
         if($id_alumno=='E1062')
			   return -1;
         //grabamos datos del baremo y hermanos
			if($id_alumno>0)
			{
				//filtramos los datos del baremo
				$sol_baremo=$this->getDatosTabla($nsol,'baremo',$id_alumno,'baremo',$log_nueva);	

				$log_nueva->warning("DATOS DE BAREMO");
				$log_nueva->warning(json_encode($sol_baremo));
				
				//filtramos los datos de hermanso, de admision y baremo
				$solhermanos_admision=$this->getDatosTabla($nsol,'hermanos',$id_alumno,'admision',$log_nueva);	

				$log_nueva->warning("DATOS DE HERMANOS DE ADMISION");
				$log_nueva->warning(print_r($solhermanos_admision,true));

				$solhermanos_baremo=$this->getDatosTabla($nsol,'hermanos',$id_alumno,'baremo',$log_nueva);	
				$sol_tributantes=$this->getDatosTabla($nsol,'tributantes',$id_alumno,'tributantes',$log_nueva);	
            //si se ha marcado conjunta se guardan los hermanos
			   if($sol['conjunta']=='si')
            {
               $dni_tutor1=$sol['dni_tutor1'];
					$log_nueva->warning("GUARDANDO HERMANOS ADMISON CONJUNTA: ");
					$savehermanos_admision=$this->saveHermanosAdmision($sol,$solhermanos_admision,$id_alumno,$dni_tutor1,$log_nueva);

					$log_nueva->warning("RESULTADO GUARDAR HERMANOS CONJUNTA");
					$log_nueva->warning($savehermanos_admision);

               if($savehermanos_admision==0) return -4;
               
            }
				if($hbaremo=='si')
					$savehermanos_baremo=$this->saveHermanosBaremo($solhermanos_baremo,$log_nueva);
				else
               $savehermanos_baremo=$this->deleteHermanosBaremo($solhermanos_baremo,$log_nueva);
				
            if($this->save_baremo($sol_baremo,$log_nueva))
				{

					$log_nueva->warning("GUARDANDO BAREMO");

				   if($rol=='anonimo')
               {
                  $token=$sol['token'];
                  $clave=$this->getClave($token); 
                  return $id_alumno.':'.$clave.':'.$token.':'.$savehermanos_admision;
               }
				   else return $this->getSol($id_alumno,$log_nueva);
				}
				else
				{
					$log_nueva->warning("ERROR GUARDANDO BAREMO, CODIGO: ".$this->conexion->errno);
					$log_nueva->warning("ERROR GUARDANDO BAREMO, ERROR MSG: ".$this->conexion->error);
					return 0;
				}
		}
		else
		{ 
				$log_nueva->warning("ERROR GUARDANDO ALUMNO, CODIGO: ".$this->conexion->errno);
				$log_nueva->warning("ERROR GUARDANDO ALUMNO, ERROR MSG: ".$this->conexion->error);
				if($this->conexion->errno==1062) return -2;
				else if($this->conexion->errno==1262) return -9;
				else if($this->conexion->errno==1452) return -10;
				else return 0;
		}
		}//FIN SAVEUSUARIO
		else
		{	
				$log_nueva->warning("ERROR GUARDANDO USUARIO, ID USUARIO:  $id_usuario ");
				return $id_usuario;
		}
	return 0;
  }
//FIN FUNCION SAVE

   public function insertarAlumno($query,$log,$tipo='individual')
   {
      $log->warning("INSERCION ALUMNO $tipo");
      $log->warning("CONSULTA:");
      $log->warning($query);
      $log->warning($this->conexion->errno);

	   $r=$this->conexion->query($query);
	   if($r) return $this->conexion->insert_id;
	   else return "E".$this->conexion->errno;
	} 
  public function getLast($tipo='alumno',$clave='') 
	{
	if($tipo=='alumno')
	$query="select id_alumno from alumnos order by id_alumno desc limit 1";
	else
	$query="select id_usuario from usuarios where clave=md5('".$clave."')";
	
	$soldata=$this->conexion->query($query);
	if(!$soldata) return 0;
        if($row = $soldata->fetch_object()) {
           $solSet=$row;
       		if($tipo=='alumno') 	return $solSet->id_alumno;
       		else 	return $solSet->id_usuario;
        }
	else return 0;
	}
  //comprobar si la solicitud existe para el token dado
   public function checkSolicitud($token) 
   {
	   $avalidacion=array();
	   $query="SELECT id_alumno,id_centro_destino FROM alumnos where token='".$token."'";
      $valdata=$this->conexion->query($query);
      if($row = $valdata->fetch_object()) 
        $avalidacion=$row;
    return $avalidacion;
    }
   public function firmarSolicitud($id_alumno,$v=1) 
   {
      if($v==1) $fase='validada';
      else $fase='borrador';
	   $query="UPDATE alumnos SET firma=$v,fase_solicitud='$fase' WHERE id_alumno='".$id_alumno."'";
	   $res=$this->conexion->query($query);
      if($res) 
         return 1;
      else
         return 0;
    }
      
//comprobar si los datos corresponden a una solicitud existente
  public function compSol($d,$n,$a1) {
	$solSet=array();
	$query="select * from alumnos where dni_tutor1='".$d."' and nombre='".$n."' and apellido1='".$a1."'";
	$soldata=$this->db()->query($query);
        if($row = $soldata->fetch_object()) {
           $solSet=$row;
        }
        return $solSet;
    }
    public function getHermanos($tipo,$id,$log) 
	 {
      $resultSet=array();
		if($tipo=='tributantes')
		{
         $log->warning("TIPO TRIBUTANTES");
         $squery="SELECT id_tributante,nombre,apellido1,apellido2,parentesco,dni FROM tributantes where id_alumno=".$id;
         $query=$this->conexion->query($squery);
         $i=1;
         $sufijo=$tipo;
         while ($row = $query->fetch_object()) 
         {
              $resultSet['tributantes_id_tributante'.$i]=$row->id_tributante;
              $resultSet['tributantes_nombre'.$i]=$row->nombre;
              $resultSet['tributantes_apellido1'.$i]=$row->apellido1;
              $resultSet['tributantes_apellido2'.$i]=$row->apellido2;
              $resultSet['tributantes_parentesco'.$i]=$row->parentesco;
              $resultSet['tributantes_dni'.$i]=$row->dni;
                   $i++;
         }
         $r1=array('nombre1'=>'','apellido11'=>'','apellido21'=>'','parentesco1'=>'', 'dni1'=>'');
         $r2=array('nombre2'=>'','apellido12'=>'','apellido22'=>'','parentesco2'=>'','dni2'=>'');
         $r3=array('nombre3'=>'','apellido13'=>'','apellido23'=>'','parentesco3'=>'','dni3'=>'');
         
         if($query->num_rows==0)		{	$resultSet=array_merge($r1,$r2,$r3);}
         if($query->num_rows==1)		{	$resultSet=array_merge($resultSet,$r2,$r3);}
         if($query->num_rows==2)		{	$resultSet=array_merge($resultSet,$r3);}
		}
		else if($tipo=='admision')
		{
         $log->warning("TIPO ADMISION");
         $squery="SELECT nombre,apellido1,apellido2,fnac,tipoestudios,token,reserva FROM alumnos where id_alumno IN(SELECT id_hermano FROM alumnos_hermanos_admision ah WHERE ah.id_alumno=$id)";
         $log->warning("OBTENIENDO HERMANOS ADMISION:  $squery");
         $query=$this->conexion->query($squery);
         $i=1;
         $sufijo=$tipo;
         
         while ($row = $query->fetch_object()) 
         {
           $resultSet['hermanos_'.$sufijo.'_nombre'.$i]=$row->nombre;
           $resultSet['hermanos_'.$sufijo.'_apellido1'.$i]=$row->apellido1;
           $resultSet['hermanos_'.$sufijo.'_apellido2'.$i]=$row->apellido2;
           $resultSet['hermanos_'.$sufijo.'_fnac'.$i]=$row->fnac;
           $resultSet['hermanos_'.$sufijo.'_tipoestudios'.$i]=$row->tipoestudios;
           $resultSet['hermanos_'.$sufijo.'_token'.$i]=$row->token;
           $resultSet['hermanos_'.$sufijo.'_reserva'.$i]=$row->reserva;
           $i++;
         }
         $r1=array('hermanos_admision_nombre1'=>'','hermanos_admision_apellido11'=>'','hermanos_admision_apellido21'=>'','hermanos_admision_fnac1'=>'','hermanos_admision_tipoestudios1'=>'','hermanos_admision_reserva1'=>'');
         $r2=array('hermanos_admision_nombre2'=>'','hermanos_admision_apellido12'=>'','hermanos_admision_apellido22'=>'','hermanos_admision_fnac2'=>'','hermanos_admision_tipoestudios2'=>'','hermanos_admision_reserva2'=>'');
         $r3=array('hermanos_admision_nombre3'=>'','hermanos_admision_apellido13'=>'','hermanos_admision_apellido23'=>'','hermanos_admision_fnac3'=>'','hermanos_admision_tipoestudios3'=>'','hermanos_admision_reserva3'=>'');
       
         if($query->num_rows==0)		{	$resultSet=array_merge($r1,$r2,$r3);}
         if($query->num_rows==1)		{	$resultSet=array_merge($resultSet,$r2,$r3);}
         if($query->num_rows==2)		{	$resultSet=array_merge($resultSet,$r3);}
      }   
		else
		{
         $squery="SELECT * FROM alumnos_hermanos_baremo where id_alumno=".$id;
         $log->warning("CARGANDO HERMANOS TIPO BAREMO, CONSULTA: $squery");
         $query=$this->conexion->query($squery);
         $nr=$query->num_rows;
            $log->warning("NUMERO FILAS: $nr");
         $i=1;
         $sufijo=$tipo;
         while ($row = $query->fetch_object()) 
         {
            $log->warning(print_r($row,true));
           $resultSet['hermanos_nombre_baremo'.$i]=$row->nombre;
           $resultSet['hermanos_apellido1_baremo'.$i]=$row->apellido1;
           $resultSet['hermanos_apellido2_baremo'.$i]=$row->apellido2;
           $resultSet['hermanos_fnacimiento_baremo'.$i]=$row->fnacimiento;
           $resultSet['hermanos_modalidad_baremo'.$i]=$row->modalidad;
           $resultSet['hermanos_nivel_baremo'.$i]=$row->nivel;
            $i++;
         }
         $r1=array('hermanos_nombre_baremo1'=>'','hermanos_apellido1_baremo1'=>'','hermanos_fnacimiento_baremo1'=>'','hermanos_modalidad_baremo1'=>'','hermanos_nivel_baremo1'=>'');
         $r2=array('hermanos_nombre_baremo2'=>'','hermanos_apellido1_baremo2'=>'','hermanos_fnacimiento_baremo2'=>'','hermanos_modalidad_baremo2'=>'','hermanos_nivel_baremo2'=>'');
         $r3=array('hermanos_nombre_baremo3'=>'','hermanos_apellido1_baremo3'=>'','hermanos_fnacimiento_baremo3'=>'','hermanos_modalidad_baremo3'=>'','hermanos_nivel_baremo3'=>'');
         
         if($query->num_rows==0)		{	$resultSet=array_merge($r1,$r2,$r3);}
         if($query->num_rows==1)		{	$resultSet=array_merge($resultSet,$r2,$r3);}
         if($query->num_rows==2)		{	$resultSet=array_merge($resultSet,$r3);}
		}
      return $resultSet;
	}
  
	public function getSolData($id=0,$tiposol='nueva',$id_centro=0,$tabla_alumnos='alumnos',$log) 
	{
	   $datos_baremo=array();
	   $datos_alumno=array();
	   $datos_tributantes=array();
	   if($tiposol=='existente')
	   {
	      $query_baremo="SELECT b.* FROM $tabla_alumnos a LEFT JOIN baremo b on a.id_alumno=b.id_alumno where a.id_alumno=".$id;

	      $soldata=$this->conexion->query($query_baremo);
         if($row = $soldata->fetch_object()) 
         {
            $solSet_baremo=$row;
         }
	      $datos_baremo = json_decode(json_encode($solSet_baremo), True);
	      $datos_baremo=array_combine(array_map(function($k){ return 'baremo_'.$k;}, array_keys($datos_baremo)),$datos_baremo);
      }	

      $query_alumno="SELECT * FROM $tabla_alumnos a where a.id_alumno=".$id;
      $soldata=$this->conexion->query($query_alumno);
      if($row = $soldata->fetch_object()) {
         $solSet_alumno=$row;
      }
      $datos_alumno = json_decode(json_encode($solSet_alumno), True);
      $hermanos_admision=$this->getHermanos('admision',$id,$log);
      $log->warning("DATOS HERMANOS ADMISION");
      $log->warning(print_r($hermanos_admision,true));
      $hermanos_baremo=$this->getHermanos('baremo',$id,$log);
      $log->warning("DATOS HERMANOS BAREMO");
      $log->warning(print_r($hermanos_baremo,true));
      $tributantes=$this->getHermanos('tributantes',$id,$log);

      if(sizeof($datos_alumno)!=0)	$sol_completa=$datos_alumno;
      if(sizeof($datos_baremo)!=0)	$sol_completa=array_merge($datos_alumno,$datos_baremo);
      if(sizeof($hermanos_admision)!=0)	$sol_completa=array_merge($sol_completa,$hermanos_admision);
      if(sizeof($hermanos_baremo)!=0)	$sol_completa=array_merge($sol_completa,$hermanos_baremo);
      if(sizeof($tributantes)!=0)	$sol_completa=array_merge($sol_completa,$tributantes);
           
      //vaciamos el array en caso de que sea una solicitud nueva
      if($tiposol=='nueva')
      {
         foreach($sol_completa as $k=>$sol)
            $sol_completa[$k]='';
         //Obtenemos nombre del centro a partir dle id
         $nombre_centro=$this->getNombre($id_centro);
         $sol_completa['nombre_centro_destino']=$nombre_centro;
         $log->warning("NUEVA SOLICITUD,id centro/nombre centro: ".$id_centro.'/'.$nombre_centro);
      }
      else
      {
         //obtenmos nombres de los centros para mostrar
         if($sol_completa['id_centro_estudios_origen']!='') $sol_completa['id_centro_estudios_origen']=$this->getCentroNombre($sol_completa['id_centro_estudios_origen']); 
         if($sol_completa['id_centro_destino']!='') $sol_completa['id_centro_destino']=$this->getCentroNombre($sol_completa['id_centro_destino']); 
         if($sol_completa['id_centro_destino1']!='') $sol_completa['id_centro_destino1']=$this->getCentroNombre($sol_completa['id_centro_destino1']); 
         if($sol_completa['id_centro_destino2']!='') $sol_completa['id_centro_destino2']=$this->getCentroNombre($sol_completa['id_centro_destino2']); 
         if($sol_completa['id_centro_destino3']!='') $sol_completa['id_centro_destino3']=$this->getCentroNombre($sol_completa['id_centro_destino3']); 
         if($sol_completa['id_centro_destino4']!='') $sol_completa['id_centro_destino4']=$this->getCentroNombre($sol_completa['id_centro_destino4']); 
         if($sol_completa['id_centro_destino5']!='') $sol_completa['id_centro_destino5']=$this->getCentroNombre($sol_completa['id_centro_destino5']); 
         if($sol_completa['id_centro_destino6']!='') $sol_completa['id_centro_destino6']=$this->getCentroNombre($sol_completa['id_centro_destino6']); 
      }
      $log->warning("SOLICITUD COMPLETA");
      $log->warning(print_r($sol_completa,true));
	   return $sol_completa;
   }
  
	public function genOrdenSolicitudesAdmitidas($c=0,$log) 
	{
   //Obtenemos loas admitidas según criterios de desempate
   //recorremos todas las solicitudes asignando una puntuacción según los criterios
      $tabla='alumnos';
      $datos_solicitudes=$this->getDatosSolicitudes($c,$log);
      $delete="DELETE FROM alumnos_orden WHERE id_centro=$c"; 
		$qd=$this->conexion->query($delete);
      foreach($datos_solicitudes as $soli)
      {
         $tipoestudios=$soli->tipoestudios;    
         $tra=$soli->transporte;    
         $pv=$soli->puntos_validados;    

         $vhc=$soli->validar_hnos_centro;    
         $vtc=$soli->validar_tutores_centro;    
         $vhtc=0;
         if($vhc==1)
            $vhtc=$vhtc+8;
         if($vtc==1)
            $vhtc=$vhtc+4;
         
         if($soli->conjunta=='si')   
            $conjunta=4;
         else
            $conjunta=0;
                
         $pdo=$soli->proximidad_domicilio;
         if($soli->validar_proximidad_domicilio=='1')
         {
            if($pdo=='dfamiliar') $vpdo=5;
            else if($pdo=='dlaboral') $vpdo=4;
            else if($pdo=='dflimitrofe') $vpdo=3;
            else if($pdo=='dllimitrofe') $vpdo=2;
            else $vpdo=1;
         }
         else
            $vpdo=0;

         $vri=$soli->validar_renta_inferior;
         $vss=$soli->validar_situacion_sobrevenida;

         $vdisc=0;
         if($soli->validar_discapacidad_alumno=='1')
            $vdisc=$vdisc+1;
         if($soli->validar_discapacidad_hermanos=='1')
         {
            //CALCULAR HERMNOS EN DISCAPCIDAD
            if($soli->nombredisc1!='nodata' and $soli->nombredisc1!='')
               $vdisc=$vdisc+1;
            if($soli->nombredisc2!='nodata' and $soli->nombredisc2!='')
               $vdisc=$vdisc+1;
            if($soli->nombredisc3!='nodata' and $soli->nombredisc3!='')
               $vdisc=$vdisc+1;
         }
         
         $vfam=0;
         if($soli->validar_tipo_familia_numerosa=='1')
            $vfam=$vfam+$soli->tipo_familia_numerosa;
         if($soli->validar_tipo_familia_monoparental=='1')
            $vfam=$vfam+$soli->tipo_familia_monoparental;

         $norden=$soli->nordensorteo;    
         $id=$soli->id;    
         $insert="INSERT INTO alumnos_orden VALUES($c,'$tipoestudios',$id,$tra,$pv,$vhtc,$conjunta,$vpdo,$vri,$vss,$vdisc,$vfam,$norden)"; 
		   $qd=$this->conexion->query($insert);
         $log->warning("Insertando en orden $insert");
         
      }

      $log->warning("Consulta generación solicitudes admitidas centro $c");
	   return 1;
   }
	public function getSolAdmitidas($nvebo=0,$nvtva=0,$c=0,$log) 
	{
      $orden=" ORDER BY id_centro,tipoestudios, transporte asc,puntos_validados desc,hermanos_tutores desc,conjunta desc,proximidad desc,renta desc,sobrevenida desc,discapacidad desc,familia desc,orden asc ";

      $qebo="SELECT id_alumno FROM alumnos_orden WHERE id_centro=$c AND tipoestudios='ebo' $orden LIMIT $nvebo"; 
		$rqebo=$this->conexion->query($qebo);

      $log->warning("Consulta solicitudes admitidas centro $qebo");
      
      $qtva="SELECT id_alumno FROM alumnos_orden WHERE id_centro=$c AND tipoestudios='tva' $orden LIMIT $nvtva"; 
		$rqtva=$this->conexion->query($qtva);

      $log->warning("Consulta solicitudes admitidas centro $qtva");
      if($qebo and $qtva)
		{
		   if($rqebo->num_rows>0)
			   while ($row = $rqebo->fetch_object()) 
				   $resultSet['ebo'][]=$row;
		   if($rqtva->num_rows>0)
				   while ($row = $rqtva->fetch_object()) 
						 $resultSet['tva'][]=$row;
  		}     
	   if(!isset($resultSet)) return 0; 
	   else return $resultSet;
	   return 0;
   }
	public function setSolicitudesSorteo($c=1,$solicitudes=0,$nvebo=0,$nvtva=0,$log) 
	{
		$tabla='alumnos';
		$resultSet=array();
		//ponemos todas llas solicitudes a noadmitidos por si ya ha habido otro sorteo
		if($c!=1) $sql_excluida="UPDATE $tabla SET est_desp_sorteo='noadmitida' where id_centro_destino=$c";
		else $sql_excluida="UPDATE $tabla SET est_desp_sorteo='noadmitida'";
		//obtenemos los ids de solicitudes admitidas según ls criterios del baremo
		$resg=$this->genOrdenSolicitudesAdmitidas($c,$log);
		$ids=$this->getSolAdmitidas($nvebo,$nvtva,$c,$log);
		if($ids==0) return 0;
     
      $log->warning("Solicitudes admitidas:");
      $log->warning(print_r($ids,true));
		
		$idsebo='';
		if(isset($ids['ebo'])) 
			foreach($ids['ebo'] as $id)
				$idsebo.=$id->id_alumno.",";
		$idsebo=rtrim($idsebo,',');
	
		$idstva='';
		if(isset($ids['tva']))
			foreach($ids['tva'] as $id)
				$idstva.=$id->id_alumno.",";
			$idstva=rtrim($idstva,',');	
		$sql_actestebo='';
		$sql_actesttva='';
		if($c!=1)
		{
         //actualizamos campo de estado de la solicid despues del sorteo para marcar las sol admitidas, siempre excluyendo las borrador
         if(strlen($idsebo)>0)
            $sql_actestebo="UPDATE $tabla SET est_desp_sorteo='admitida' WHERE tipoestudios='ebo' and id_centro_destino=$c and fase_solicitud!='borrador' and id_alumno in(".$idsebo.")";
         if(strlen($idstva)>0)
            $sql_actesttva="UPDATE $tabla SET est_desp_sorteo='admitida' WHERE tipoestudios='tva' and id_centro_destino=$c and fase_solicitud!='borrador' and id_alumno in(".$idstva.")";
		}
		else
		{
         //actualizamos campo de estado de la solicid despues del sorteo para marcar las sol admitidas, siempre excluyendo las borrador
         if(strlen($idsebo)>0)
            $sql_actestebo="update $tabla set est_desp_sorteo='admitida' where tipoestudios='ebo' and  fase_solicitud!='borrador' and id_alumno in(".$idsebo.")";
         if(strlen($idstva)>0)
            $sql_actesttva="update $tabla set est_desp_sorteo='admitida' where tipoestudios='tva' and fase_solicitud!='borrador' and id_alumno in(".$idstva.")";
		}

		$query1=$this->conexion->query($sql_excluida);
		if(strlen($idsebo)>0)
			$query3=$this->conexion->query($sql_actestebo);
		else $query3=1;

		if(strlen($idstva)>0)
			$query4=$this->conexion->query($sql_actesttva);
		else $query4=1;

		if($query1 and $query3 and $query4)
			return 1;
		else 
		{
			return 0;
		}
	}
	public function setNordenSorteo($c=1,$numero=0,$solicitudes=0,$nvebo=0,$nvtva=0,$log) 
	{
		$tabla='alumnos';
		$resultSet=array();
		//ponemos todas llas solicitudes a noadmitidos por si ya ha habido otro sorteo
		$sql_excluida="update $tabla set est_desp_sorteo='noadmitida'";
		$sql1="UPDATE $tabla a set nordensorteo=$solicitudes-3+nasignado-$numero+1 where nasignado<$numero and fase_solicitud!='borrador' ";
		$sql2="UPDATE $tabla a set nordensorteo=nasignado-$numero+1 where nasignado>=$numero and fase_solicitud!='borrador' ";
      $log->warning("CONSULTA ASIGNACION NUMERO DE SORTEO: ".$sql1);
      $log->warning("CONSULTA ASIGNACION NUMERO DE SORTEO: ".$sql2);
		$query0=$this->conexion->query($sql_excluida);
		$query1=$this->conexion->query($sql1);
		$query2=$this->conexion->query($sql2);
		if($query0 and $query1 and $query2)
			return 1;
		else 
		{
			return 0;
		}
	}
	public function genSolDefinitivas($c=1,$nvebo=0,$nvtva=0,$fasecentro=1) 
	{
		//obtenemos la consulta q nos devuelve el listado ordenado de solicitudes admitidas teniendo en cuenta la fase, si es 2 usaremos como fuene la tabla provisioalm sino la de alumnos normal
		$ids=$this->getSolAdmitidas($nvebo,$nvtva,$c);

		$idsebo='';
		foreach($ids['ebo'] as $id)
			$idsebo.=$id->id_alumno.",";
		$idsebo=rtrim($idsebo,',');	
		$idstva='';
		foreach($ids['tva'] as $id)
			$idstva.=$id->id_alumno.",";
		$idstva=rtrim($idstva,',');	
		if($c==1)
		{
			//ponemos todas llas solicitudes a noadmitidas
			$sql_noadmitida="update alumnos set est_desp_sorteo='noadmitida'";
			$sqlebo="UPDATE alumnos a set est_desp_sorteo='admitida' where fase_solicitud!='borrador' and tipoestudios='ebo' and estado_solicitud='apta' and a.id_alumno in(".$idsebo.")";
			$sqltva="UPDATE alumnos a set est_desp_sorteo='admitida' where fase_solicitud!='borrador' and tipoestudios='tva' and estado_solicitud='apta' and a.id_alumno in(".$idstva.")";
		}
		elseif($c!=1)
		{
			//ponemos todas llas solicitudes a noadmitidas
			$sql_noadmitida="update alumnos set est_desp_sorteo='noadmitida' where id_centro_destino=$c";
			$sqlebo="UPDATE alumnos a set est_desp_sorteo='admitida' where fase_solicitud!='borrador' and tipoestudios='ebo' and id_centro_destino=$c and estado_solicitud='apta' and a.id_alumno in(".$idsebo.")";
			$sqltva="UPDATE alumnos a set est_desp_sorteo='admitida' where fase_solicitud!='borrador' and tipoestudios='tva' and id_centro_destino=$c and estado_solicitud='apta' and a.id_alumno in(".$idstva.")";

		}
		$this->log_listados_definitivos->warning($sql_noadmitida);
		$this->log_listados_definitivos->warning($sqlebo);
		$this->log_listados_definitivos->warning($sqltva);
		$noad=$this->conexion->query($sql_noadmitida);
		$qebo=$this->conexion->query($sqlebo);
		$qtva=$this->conexion->query($sqltva);
		if($qebo and $qtva and $noad)
			return 1;
		else return 0;

	}
   public function getNumSolicitudes()
	{
      $sql="SELECT count(*) as nsolicitudes FROM alumnos where fase_solicitud!='borrador'";
		$query=$this->conexion->query($sql);
		if($query) {$row = $query->fetch_object();return $row->nsolicitudes;}
		else return 0;
	}
	public function getSolicitudesBaremadas($id_centro,$estado_convocatoria,$subtipo_listado='normal',$tabla_alumnos='alumnos',$log,$id_alumno=0,$rol,$provincia) {
      $ares=array();
      $sql="No hay consulta";
	   $centro='id_centro_destino';
		//MOSTRAMOS DATOS VALIDADOS
		if($rol=='centro' or $rol=='alumno') //para centros o alumnos del centro
      {
         $log->warning("CONSULTA ROL $rol:");
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
         $sql="SELECT * FROM alumnos_baremada_final WHERE $centro=$id_centro ORDER BY apellido1,nombre";
      }
	   elseif($rol=='admin') //para administradorn
      {
         $log->warning("CONSULTA BAREMADAS, ROL ADMIN :");
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
            $sql="SELECT a.*,nombre_centro FROM alumnos_baremada_final a, centros bc WHERE bc.id_centro=a.id_centro_destino ORDER BY a.id_centro_destino,a.tipoestudios,a.apellido1";
      }
	   elseif($rol=='sp') //para servicio provincial
      {
         $log->warning("CONSULTA BAREMADAS, ROL SP :");
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
         $sql="SELECT * FROM alumnos_baremada_final a,centros c WHERE a.id_centro_destino=c.id_centro AND  c.provincia='$provincia' ORDER BY a.id_centro_destino,a.tipoestudios, a.apellido1";
      }
		$log->warning("DENTRO DE GET SOLICIUTES BAREMADAS, ID_CENTRO:$id_centro ID_ALUMNO: $id_alumno");
      $log->warning($sql);
      $query=$this->conexion->query($sql);
      if($query)
		   while ($row = $query->fetch_object()) 
			{
           $ares[]=$row;
         }
      return $ares;
	}
	public function getSolicitudesValidadas($id_centro,$estado_convocatoria,$subtipo_listado='normal',$tabla_alumnos='alumnos',$log,$id_alumno=0,$rol,$provincia) {
   //si estamos en peridodo de listados baremados provisionales accedemos a la tabla correspondiente
      $ares=array();
      $sql="No hay consulta";
	   $centro='id_centro_destino';
      if($subtipo_listado=='sor_det' or $subtipo_listado=='sor_bar' or $subtipo_listado=='sor_ale'){ 
         $noborradorc=" AND fase_solicitud!='borrador'";
         $noborradora=" WHERE fase_solicitud!='borrador'";
      }
      else{
          $noborradora='';
          $noborradorc='';
      }
      if($estado_convocatoria>=ESTADO_RECLAMACIONES_BAREMADAS AND $estado_convocatoria<ESTADO_RECLAMACIONES_PROVISIONAL)
         $filtrorec=" AND (rec.tipo='baremo' OR rec.tipo IS NULL)";
      else
         $filtrorec=" AND (rec.tipo='provisional' OR rec.tipo IS NULL)";
         
		//MOSTRAMOS DATOS VALIDADOS
		if($rol=='centro' or $rol=='alumno') //para centros o alumnos del centro
      {
         $log->warning("CONSULTA ROL $rol:");
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
         if($estado_convocatoria>=ESTADO_PUBLICACION_PROVISIONAL and $subtipo=='sor_bar')
            $sql="SELECT * FROM alumnos_baremada_provisional WHERE $centro=$id_centro";
         else
         {
		      $sql="SELECT a.conjunta, a.id_alumno as id_alumno,a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado,IFNULL(b.puntos_validados,0) as puntos_validados,a.token,b.*,rec.tipo FROM $tabla_alumnos a left join baremo b on a.id_alumno=b.id_alumno left join reclamaciones rec on rec.id_alumno=a.id_alumno where $centro=".$id_centro." $noborradorc $filtrorec  order by a.tipoestudios, a.apellido1,a.nombre,a.transporte asc,b.puntos_validados desc,b.hermanos_centro desc,b.proximidad_domicilio,b.renta_inferior,b.discapacidad_alumno,b.discapacidad_hermanos,b.tipo_familia_numerosa,b.tipo_familia_monoparental,a.nordensorteo asc,a.nasignado desc";
         }
      }
	   elseif($rol=='admin') //para administradorn
      {
         $log->warning("CONSULTA VALIDADAS, ROL ADMIN :".ESTADO_RECLAMACIONES_PROVISIONAL);
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
         if($estado_convocatoria>=ESTADO_PUBLICACION_PROVISIONAL and $subtipo=='sor_bar')
         {
            $sql="SELECT * FROM alumnos_baremada_provisional";
         }
         else
         {
            if($id_centro>0)
		         $sql="SELECT a.conjunta, a.id_alumno as id_alumno,a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado,IFNULL(b.puntos_validados,0) as puntos_validados,a.token,b.*,rec.tipo FROM $tabla_alumnos a left join baremo b on a.id_alumno=b.id_alumno left join reclamaciones rec on rec.id_alumno=a.id_alumno where $centro=".$id_centro." $noborradorc $filtrorec  order by a.tipoestudios, a.apellido1,a.nombre,a.transporte asc,b.puntos_validados desc,b.hermanos_centro desc,b.proximidad_domicilio,b.renta_inferior,b.discapacidad_alumno,b.discapacidad_hermanos,b.tipo_familia_numerosa,b.tipo_familia_monoparental,a.nordensorteo asc,a.nasignado desc";
           else
               $sql="SELECT a.id_centro_destino,c.nombre_centro, a.conjunta, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado, IFNULL(b.puntos_validados,0) as puntos_validados,a.token,b.*,rec.tipo FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join reclamaciones rec on rec.id_alumno=a.id_alumno left join centros c on c.id_centro=a.id_centro_destino $noborradora $filtrorec  order by a.id_centro_destino, a.tipoestudios, a.apellido1,a.nombre,a.transporte asc,b.puntos_validados desc,b.hermanos_centro desc,b.proximidad_domicilio,b.renta_inferior,b.discapacidad_alumno,b.discapacidad_hermanos,b.tipo_familia_numerosa,b.tipo_familia_monoparental,a.nordensorteo asc,a.nasignado desc";
         }
      }
	   elseif($rol=='sp') //para servicio provincial
      {
         $log->warning("CONSULTA VALIDADAS, ROL SP :");
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
         if($estado_convocatoria>=21 and $subtipo=='sor_bar')
         {
            $sql="SELECT * FROM alumnos_baremada_provisional";
         }
         else
         {
           if($id_centro>0) 
            $sql="SELECT id_centro_destino, a.conjunta, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado, IFNULL(b.puntos_validados,0) as puntos_validados,a.token,b.* FROM $tabla_alumnos a join centros c on c.id_centro=a.id_centro_destino left join baremo b on b.id_alumno=a.id_alumno WHERE fase_solicitud!='borrador' AND  provincia='$provincia' and c.id_centro=$id_centro  order by a.id_centro_destino, a.tipoestudios, a.apellido1,a.nombre,a.transporte asc,b.puntos_validados desc,b.hermanos_centro desc,b.proximidad_domicilio,b.renta_inferior,b.discapacidad_alumno,b.discapacidad_hermanos,b.tipo_familia_numerosa,b.tipo_familia_monoparental,a.nordensorteo asc,a.nasignado desc";
           else 
            $sql="SELECT a.id_centro_destino,c.nombre_centro, a.conjunta, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado, IFNULL(b.puntos_validados,0) as puntos_validados,a.token,b.* FROM $tabla_alumnos a join centros c on c.id_centro=a.id_centro_destino left join baremo b on b.id_alumno=a.id_alumno WHERE fase_solicitud!='borrador' AND provincia='$provincia'  order by a.id_centro_destino, a.tipoestudios, a.apellido1,a.nombre,a.transporte asc,b.puntos_validados desc,b.hermanos_centro desc,b.proximidad_domicilio,b.renta_inferior,b.discapacidad_alumno,b.discapacidad_hermanos,b.tipo_familia_numerosa,b.tipo_familia_monoparental,a.nordensorteo asc,a.nasignado desc";
         }
      }
		elseif($rol=='alumno') //para alumnos
      {
         /*
         $log->warning("CONSULTA BAREMADASi ROL ALUMNO 2021:");
         //si ya hemos llegado a provionales baremadas vamos directos a la tabla 
         if($estado_convocatoria>=21 and $subtipo=='sor_bar')
         {
            $sql="SELECT * FROM alumnos_baremada_provisional WHERE id_alumno=$id_alumno";
         }
         else
         {
         $sql="SELECT a.conjunta,a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.tipoestudios,nasignado, IFNULL(b.puntos_validados,0) as puntos_validados,a.token,b.* FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno WHERE a.id_alumno=$id_alumno";
         }
         */
      } 
		$log->warning("DENTRO DE GET SOLICIUTES VALIDADAS, ID_CENTRO:$id_centro ID_ALUMNO: $id_alumno");
      $log->warning($sql);
      $query=$this->conexion->query($sql);
      if($query)
		   while ($row = $query->fetch_object()) 
			{
           $ares[]=$row;
         }
      return $ares;
	}

	public function getAllTributantes($c=1,$tipo=0,$subtipo_listado='',$fase_sorteo=0,$estado_convocatoria=0,$provincia='todas') 
   {
		$sql="SELECT  t.*,c.nombre_centro FROM alumnos a join tributantes t  on a.id_alumno=t.id_alumno join centros c on c.id_centro=a.id_centro_destino";

      $query=$this->conexion->query($sql);
      if($query)
      while ($row = $query->fetch_object()) 
      {
         $resultSet[]=$row;
      }
      return $resultSet;

   }
   public function desmarcarValidados($c=1)
   {
   //desmarcamos los campos q no han sido validados en el baremo
      if($c==1)
      {
         $sql1="UPDATE baremo SET proximidad_domicilio='sindomicilio' WHERE validar_proximidad_domicilio=0";
         $sql2="UPDATE baremo SET tipo_familia='no' WHERE validar_tipo_familia=0";
         $sql3="UPDATE baremo SET discapacidad='no' WHERE validar_discapacidad=0";
      }
      else
      {
         $sql1="UPDATE baremo SET proximidad_domicilio='sindomicilio' WHERE
validar_proximidad_domicilio=0 and id_alumno in (select id_alumno from alumnos
WHERE id_centro_destino=$c)";
         $sql2="UPDATE baremo SET tipo_familia='no' WHERE validar_tipo_familia=0
and id_alumno in (select id_alumno from alumnos WHERE id_centro_destino=$c)";
         $sql3="UPDATE baremo SET discapacidad='no' WHERE validar_discapacidad=0
and id_alumno in (select id_alumno from alumnos WHERE id_centro_destino=$c)";
      }
      $query1=$this->conexion->query($sql1);
      $query2=$this->conexion->query($sql2);
      $query3=$this->conexion->query($sql3);
      if($query1 and $query2 and $query3) return 1;
      else return 0;
   }

	public function getTodasSolicitudes($c=1,$tipo=1,$subtipo_listado,$estado_convocatoria=0,$log='',$rol='alumno',$provincia) 
   {
		$tabla_alumnos='alumnos';
		
      $order=" order by c.id_centro,a.tipoestudios, a.transporte asc,$tablabaremo.puntos_validados desc,$tablabaremo.validar_hnos_centro
desc,$tablabaremo.validar_tutores_centro desc,$tablabaremo.validar_proximidad_domicilio
desc,FIELD($tablabaremo.proximidad_domicilio,'dfamiliar','dlaboral','dflimitrofe','dllimitrofe','sindomicilio'),$tablabaremo.validar_renta_inferior
desc,$tablabaremo.validar_discapacidad
desc,FIELD($tablabaremo.discapacidad,'alumno','hpadres','no'),$tablabaremo.validar_tipo_familia
desc,FIELD($tablabaremo.tipo_familia,'numerosa_especial','monoparental_especial','numerosa_general','monoparental_especial','no'),$tablabaremo.hermanos_centro
desc,a.tutores_centro desc,a.nordensorteo asc,a.nasignado desc";
		$log->warning("CCONSULTA SOLICITUDES TIPO $tipo ESTADO $estado_convocatoria rol $rol provincia $provincia");
		
      $resultSet=array();
		if($tipo==0) //todas las solicitudes, incluyendo las q están en borrador
		{
			if($rol=='admin')
			{
				if($subtipo_listado=='dup') //solicitudes duplicadas
					$sql="select a.apellido1,a.apellido2,a.tipoestudios,a.fnac,a.dni_tutor1,a.nombre,a.id_alumno,a.reserva,c.nombre_centro FROM alumnos a join (select apellido1,nombre FROM alumnos group by apellido1,nombre having count(*)>1) dup on a.apellido1=dup.apellido1 and dup.nombre=a.nombre join centros c on c.id_centro=a.id_centro_destino join baremo b on b.id_alumno=a.id_alumno order by c.id_centro,a.tipoestudios, b.puntos_validados desc";
				else  //solicitudes normales
					$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.loc_dfamiliar,a.nordensorteo,a.nasignado as nasignado,a.reserva,b.*,c.nombre_centro,c.provincia,c2.nombre_centro as nombre_centro_origen, conjunta FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro left join centros c2 on c2.id_centro=a.id_centro_estudios_origen  order by c.id_centro desc,a.tipoestudios asc, b.puntos_validados desc";
			}
			else if($rol=='sp')
				$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.reserva,b.*,c.nombre_centro,c.provincia,c2.nombre_centro as nombre_centro_origen,conjunta FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro left join centros c2 on c2.id_centro=a.id_centro_estudios_origen  where c.provincia='$provincia' order by c.id_centro,a.tipoestudios asc, b.puntos_validados desc";
			else
            $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.reserva,b.*,c.nombre_centro,c.provincia,c2.nombre_centro as nombre_centro_origen,conjunta FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro left join centros c2 on c2.id_centro=a.id_centro_estudios_origen  where c.id_centro=$c order by c.id_centro,a.tipoestudios asc, b.puntos_validados desc";
				
				$log->warning("CONSULTA SOLICITUDES CSV");
				$log->warning($sql);
				$log->warning("CONSULTA SOLICITUDES FASE2 SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
		}
         $query=$this->conexion->query($sql);
         if($query)
            while ($row = $query->fetch_object()) 
               $resultSet[]=$row;
        return $resultSet;
   }
	public function getSolicitudesFase2Finales($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log,$id_alumno) 
   {
		$log->warning("\nCONSULTA SOLICITUDES FASE2 FINAL ESTADO $estado_convocatoria rol $rol\n");
      $where="";
      //mostramos los alumnos q eleigieron ese centro o q lo han obtenido en la fase final
      if($rol=='centro')
         $where=" AND( id_centro=$id_centro OR id_centro_definitivo=$id_centro) ";
      if($rol=='alumno')
         $where=" AND id_alumno=$id_alumno ";
      
      $resultSet=array();
		if($subtipo_listado=='lfinal_sol_ebo')
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.* FROM alumnos_fase2_final a where a.tipoestudios='ebo' $where order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc";
   	elseif($subtipo_listado=='lfinal_sol_tva')
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.* FROM alumnos_fase2_final a where a.tipoestudios='tva' $where order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc";
		elseif($subtipo_listado=='csv_final') //para el csv 
      {
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM alumnos_fase2_final a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      }
		
      $log->warning("CONSULTA SOLICITUDES FINALES SUBTIPO: ".$subtipo_listado);
		$log->warning($sql);
		$query=$this->conexion->query($sql);
      if($query)
         while ($row = $query->fetch_object()) 
            $resultSet[]=$row;
      return $resultSet;
   }
	public function getSolicitudesFase2($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log) 
   {
      $resultSet=array();
		$log->warning("CONSULTA SOLICITUDES ESTADO $estado_convocatoria rol $rol");

		if($subtipo_listado=='lfase2_sol_ebo')
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, tipo_modificacion,reserva FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='ebo' order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
   	elseif($subtipo_listado=='lfase2_sol_tva')
  			$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,tipo_modificacion,reserva FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='tva' order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
		elseif($subtipo_listado=='csv_fase2') //para el csv 
      {
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      }
      elseif($subtipo_listado=='lfase2_sol_sor') //para el listado completo con botón de asignación automática de plazas
      {
      if($rol=='admin')     
         $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      else //para los centros o ciudadanos     
         $sql="SELECT 'centrosdisponibles' as centrosdisponibles,a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno WHERE a.id_centro=$id_centro order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      }
		$log->warning("CONSULTA SOLICITUDES FASE2 SUBTIPO: ".$subtipo_listado);
		$log->warning($sql);
		$query=$this->conexion->query($sql);
      if($query)
         while ($row = $query->fetch_object()) 
            $resultSet[]=$row;
      return $resultSet;
   }
	public function getSolicitudesFase3($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log) 
   {
      $resultSet=array();
		$log->warning("CONSULTA SOLICITUDES ESTADO $estado_convocatoria rol $rol");
		if($subtipo_listado=='lfase3_sol_ebo')
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, tipo_modificacion,reserva FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='ebo' order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
   	elseif($subtipo_listado=='lfase3_sol_tva')
  			$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,tipo_modificacion,reserva FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='tva' order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
		elseif($subtipo_listado=='csv_fase3') //para el csv 
      {
		   $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM alumnos_fase2 a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      }
      elseif($subtipo_listado=='lfase3_sol_sor') //para el listado completo con botón de asignación automática de plazas
      {
      if($rol=='admin')     
         $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      else //para los centros o ciudadanos     
         $sql="SELECT 'centrosdisponibles' as centrosdisponibles,a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno WHERE a.id_centro=$id_centro order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
      }
		$log->warning("CONSULTA SOLICITUDES FASE3 SUBTIPO: ".$subtipo_listado);
		$log->warning($sql);
		$query=$this->conexion->query($sql);
      if($query)
         while ($row = $query->fetch_object()) 
            $resultSet[]=$row;
      return $resultSet;
   }
/*
	public function getAllSolListados($c=1,$tipo=1,$subtipo_listado,$estado_convocatoria=0,$log='',$rol='alumno') 
   {
      $provincia=substr($rol,2);
      //si el estado de la convocatoria es previo a provisioonales la tabla del
      //baremo es la original
      $tablabaremo='b';
		if($tipo==1)
      {
         $tabla_alumnos='alumnos_provisional';
         if($estado_convocatoria>=30) $tablabaremo='a';
      }
		elseif($tipo==2) 
      {
         $tabla_alumnos='alumnos_definitiva';
         if($estado_convocatoria>=40) $tablabaremo='a';
      }
		elseif($estado_convocatoria>=50) $tabla_alumnos='alumnos_fase2';
		else $tabla_alumnos='alumnos';
		
      $order=" order by c.id_centro,a.tipoestudios, a.transporte
desc,$tablabaremo.puntos_validados desc,$tablabaremo.validar_hnos_centro
desc,$tablabaremo.validar_tutores_centro desc,$tablabaremo.validar_proximidad_domicilio
desc,FIELD($tablabaremo.proximidad_domicilio,'dfamiliar','dlaboral','dflimitrofe','dllimitrofe','sindomicilio'),$tablabaremo.validar_renta_inferior
desc,$tablabaremo.validar_discapacidad
desc,FIELD($tablabaremo.discapacidad,'alumno','hpadres','no'),$tablabaremo.validar_tipo_familia
desc,FIELD($tablabaremo.tipo_familia,'numerosa_especial','monoparental_especial','numerosa_general','monoparental_especial','no'),$tablabaremo.hermanos_centro
desc,a.tutores_centro desc,a.nordensorteo asc,a.nasignado desc";
		$log->warning("CCONSULTA SOLICITUDES TIPO $tipo ESTADO $estado_convocatoria rol $rol provincia $provincia");
		
      $resultSet=array();
		if($tipo==0) //todas las solicitudes, incluyendo las q están en borrador
		{
			if($rol=='admin')
			{
				if($subtipo_listado=='dup') //solicitudes duplicadas
					$sql="select a.apellido1,a.apellido2,a.tipoestudios,a.fnac,a.dni_tutor1,a.nombre,a.id_alumno,a.reserva,c.nombre_centro FROM alumnos a join (select apellido1,nombre FROM alumnos group by apellido1,nombre having count(*)>1) dup on a.apellido1=dup.apellido1 and dup.nombre=a.nombre join centros c on c.id_centro=a.id_centro_destino join baremo b on b.id_alumno=a.id_alumno order by c.id_centro,a.tipoestudios, b.puntos_validados desc";
				else  //solicitudes normales
					$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.loc_dfamiliar,a.nordensorteo,a.nasignado as nasignado,a.reserva,b.*,c.nombre_centro,c.provincia,c2.nombre_centro as nombre_centro_origen, conjunta FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro left join centros c2 on c2.id_centro=a.id_centro_estudios_origen  order by c.id_centro desc,a.tipoestudios asc, b.puntos_validados desc";
			}
			else if($rol=='sp')
				$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.reserva,b.*,c.nombre_centro,c.provincia,c2.nombre_centro as nombre_centro_origen,conjunta FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro left join centros c2 on c2.id_centro=a.id_centro_estudios_origen  where c.provincia='$provincia' order by c.id_centro,a.tipoestudios asc, b.puntos_validados desc";
			else
            $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.reserva,b.*,c.nombre_centro,c.provincia,c2.nombre_centro as nombre_centro_origen,conjunta FROM alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro left join centros c2 on c2.id_centro=a.id_centro_estudios_origen  where c.id_centro=$c order by c.id_centro,a.tipoestudios asc, b.puntos_validados desc";
				
				$log->warning("CONSULTA SOLICITUDES CSV");
				$log->warning($sql);
		}
		elseif($tipo==1) //provisionales
		{
			if($subtipo_listado=='admitidos_prov')
					if($c<=1)
						$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' $order";
					else
						$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='admitida' and c.id_centro=$c $order";
			elseif($subtipo_listado=='noadmitidos_prov')
					if($c<=1)
						$sql="SELECT
a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' $order";
					else
						$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' and c.id_centro=$c $order";
					
			elseif($subtipo_listado=='excluidos_prov')
					if($c<=1)
						$sql="SELECT
a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro, a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and( estado_solicitud='duplicada' or estado_solicitud='irregular') $order";
					else
						$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'  and( estado_solicitud='duplicada' or estado_solicitud='irregular') and c.id_centro=$c $order";
				$log->warning("CONSULTA SOLICITUDES PROVISIONALES SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
		}
		elseif($tipo==2) //definitivos
		{
			if($subtipo_listado=='admitidos_def' or $subtipo_listado=='lfase2_sol')
				if($c<=1)
					$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' $order";
				else
					$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='admitida' and c.id_centro=$c $order";
			elseif($subtipo_listado=='noadmitidos_def')
				if($c<=1)
			      $sql="SELECT
a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' $order";
				else
					$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' and id_centro=$c $order";
			elseif($subtipo_listado=='excluidos_def')
				if($c<=1)
					$sql="SELECT
a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro, a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and( estado_solicitud='duplicada' or estado_solicitud='irregular')  $order";
				else
					$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'  and( estado_solicitud='duplicada' or estado_solicitud='irregular') and c.id_centro=$c $order";
				$log->warning("CONSULTA SOLICITUDES DEFINITIVOS SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
				}
				elseif($tipo==3) //fase2 o fase3
				{
					if($subtipo_listado=='lfase2_sol_ebo')
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, tipo_modificacion,reserva FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='ebo' ".$wherecentro."  order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
						elseif($subtipo_listado=='lfase2_sol_tva')
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,tipo_modificacion,reserva FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='tva' ".$wherecentro." order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
						elseif($subtipo_listado=='lfase2_sol_sor') //para el listadonumero aleatorioa 
                  {
                  if($c<=1)     
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
                  else //para los centros o ciudadanos     
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles,
a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,
a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as
nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,
a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left
join baremo b on b.id_alumno=a.id_alumno WHERE a.id_centro=$c order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
                  }
						elseif($subtipo_listado=='fase2') //para el csv 
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,a.reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
						elseif($subtipo_listado=='lfase3_sol_ebo')							
                     $sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, tipo_modificacion,reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='ebo' and (estado_solicitud='irregular' or estado_solicitud='duplicada')  order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
						elseif($subtipo_listado=='lfase3_sol_tva')
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen,a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo,tipo_modificacion,reserva,a.reserva_original  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno where a.tipoestudios='tva' and (estado_solicitud='irregular' or estado_solicitud='duplicada') order by a.id_centro desc, a.tipoestudios asc,a.nordensorteo asc,a.transporte asc, b.puntos_validados desc";
						elseif($subtipo_listado=='fase3') //para el csv 
							$sql="SELECT 'centrosdisponibles' as centrosdisponibles, a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.localidad,a.calle_dfamiliar,a.centro_origen,a.id_centro_origen, a.nombre_centro,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,a.puntos_validados,a.id_centro,a.centro1,a.centro2,a.centro3,a.centro4,a.centro5,a.centro6,a.centro_definitivo,a.id_centro_definitivo, a.tipo_modificacion,reserva,a.reserva_original   FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno WHERE estado_solicitud='irregular' or estado_solicitud='duplicada'  order by a.id_centro desc, a.tipoestudios asc,a.transporte asc, b.puntos_validados desc";
				$log->warning("CONSULTA SOLICITUDES FASE2 SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
				}
				$query=$this->conexion->query($sql);
        		if($query)
               while ($row = $query->fetch_object()) 
               {
                  $resultSet[]=$row;
               }
        return $resultSet;



   }
*/
	public function resetBaremoProvisional() 
	{
      $sqldom1="UPDATE alumnos_provisional SET proximidad_domicilio='sindomicilio' WHERE validar_proximidad_domicilio=0";
      $sqldom2="UPDATE alumnos_provisional SET validar_proximidad_domicilio=0 WHERE proximidad_domicilio='sindomicilio'";

      $sqldis1="UPDATE alumnos_provisional SET discapacidad='no' WHERE validar_discapacidad=0";
      $sqldis2="UPDATE alumnos_provisional SET validar_discapacidad=0 WHERE discapacidad='no'";

      $sqlfam1="UPDATE alumnos_provisional SET tipo_familia='no' WHERE validar_tipo_familia=0";
      $sqlfam2="UPDATE alumnos_provisional SET validar_tipo_familia=0 WHERE tipo_familia='no'"; 


		if($this->conexion->query($sqldom1) and $this->conexion->query($sqldom2) and $this->conexion->query($sqldis1) and $this->conexion->query($sqldis2) and $this->conexion->query($sqldom1) and $this->conexion->query($sqldom2) and $this->conexion->query($sqlfam1) and $this->conexion->query($sqlfam2))
         return 1;
      else return 0;
   }
	public function resetBaremoDefinitivo() 
	{
      $sqldom1="UPDATE alumnos_definitiva SET proximidad_domicilio='sindomicilio' WHERE validar_proximidad_domicilio=0";
      $sqldom2="UPDATE alumnos_definitiva SET validar_proximidad_domicilio=0 WHERE proximidad_domicilio='sindomicilio'";

      $sqldis1="UPDATE alumnos_definitiva SET discapacidad='no' WHERE validar_discapacidad=0";
      $sqldis2="UPDATE alumnos_definitiva SET validar_discapacidad=0 WHERE discapacidad='no'";

      $sqlfam1="UPDATE alumnos_definitiva SET tipo_familia='no' WHERE validar_tipo_familia=0";
      $sqlfam2="UPDATE alumnos_definitiva SET validar_tipo_familia=0 WHERE tipo_familia='no'"; 


		if($this->conexion->query($sqldom1) and $this->conexion->query($sqldom2) and $this->conexion->query($sqldis1) and $this->conexion->query($sqldis2) and $this->conexion->query($sqldom1) and $this->conexion->query($sqldom2) and $this->conexion->query($sqlfam1) and $this->conexion->query($sqlfam2))
         return 1;
      else return 0;
   }
	public function getSolicitudesDefinitivas($c=1,$tipo=1,$subtipo_listado,$estado_convocatoria=0,$rol,$log) 
	{
		$log->warning("CCONSULTA SOLICITUDES DEFINITIVAS");
      //si el estado de la convocatoria es previo a provisioonales la tabla del
      //baremo es la original
      $tabla_alumnos='alumnos_definitiva';
		
      $orden=" ORDER BY id_centro,tipoestudios,ao.transporte asc,ao.puntos_validados desc,ao.hermanos_tutores desc,ao.conjunta desc,ao.proximidad desc,ao.renta desc,ao.sobrevenida desc,ao.discapacidad desc,ao.familia desc,ao.orden asc";
		
      $resultSet=array();
		if($subtipo_listado=='admitidos_def')
      {
		   if($rol=='admin' or $rol=='sp')
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a,alumnos_orden ao, centros c WHERE ao.id_alumno=a.id_alumno AND a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' $orden";
			else
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a,alumnos_orden ao, centros c WHERE ao.id_alumno=a.id_alumno AND a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' and c.id_centro=$c $orden";
			   //$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados FROM $tabla_alumnos a left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='admitida' and c.id_centro=$c $orden";
      }
		elseif($subtipo_listado=='noadmitidos_def')
      {
		   if($rol=='admin' or $rol=='sp')
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a,alumnos_orden ao, centros c WHERE ao.id_alumno=a.id_alumno AND a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='noadmitida' $orden";
				//$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' $orden";
		   else
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a,alumnos_orden ao, centros c WHERE ao.id_alumno=a.id_alumno AND a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='noadmitida' and c.id_centro=$c $orden";
			   //$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados,c.nombre_centro FROM $tabla_alumnos a left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' and c.id_centro=$c $orden";
      }
		elseif($subtipo_listado=='excluidos_def')
      {
		   if($rol=='admin' or $rol=='sp')
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro, a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and( estado_solicitud='duplicada' or estado_solicitud='irregular') $orden";
			else
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'  and( estado_solicitud='duplicada' or estado_solicitud='irregular') and c.id_centro=$c $orden";
      }
				$log->warning("CONSULTA SOLICITUDES DEFINITIVAS SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
		
				$query=$this->conexion->query($sql);
        		if($query)
               while ($row = $query->fetch_object()) 
               {
                  $resultSet[]=$row;
               }
        return $resultSet;
	}
	public function getSolicitudesProvisionales($c=1,$tipo=1,$subtipo_listado,$estado_convocatoria=0,$rol,$log) 
	{
		$log->warning("CCONSULTA SOLICITUDES PROVISIONALES");
      //si el estado de la convocatoria es previo a provisioonales la tabla del
      //baremo es la original
      //if($estado_convocatoria>=ESTADO_RECLAMACIONES_BAREMADAS and $estado_convocatoria<ESTADO_PUBLICACION_PROVISIONAL)
      //    $tabla_alumnos='alumnos';
      //else
      $tabla_alumnos='alumnos_provisional';
		
      $orden=" ORDER BY id_centro,tipoestudios,ao.transporte asc,ao.puntos_validados desc,ao.hermanos_tutores desc,ao.conjunta desc,ao.proximidad desc,ao.renta desc,ao.sobrevenida desc,ao.discapacidad desc,ao.familia desc,ao.orden asc";
		
      $resultSet=array();
		if($subtipo_listado=='admitidos_prov')
      {
		   if($rol=='admin' or $rol=='sp')
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a LEFT JOIN alumnos_orden ao ON ao.id_alumno=a.id_alumno, centros c WHERE a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' $orden";
			else
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a LEFT JOIN alumnos_orden ao ON ao.id_alumno=a.id_alumno, centros c WHERE a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' and c.id_centro=$c $orden";
      }
		elseif($subtipo_listado=='noadmitidos_prov')
      {
		   if($rol=='admin' or $rol=='sp')
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a LEFT JOIN alumnos_orden ao ON ao.id_alumno=a.id_alumno, centros c WHERE a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='noadmitida' $orden";
		   else
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a LEFT JOIN alumnos_orden ao ON a.id_alumno=ao.id_alumno, centros c WHERE a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='noadmitida' and c.id_centro=$c $orden";
      }
		elseif($subtipo_listado=='excluidos_prov')
      {
          if($rol=='admin' or $rol=='sp')
            $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro, a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a LEFT JOIN alumnos_orden ao ON ao.id_alumno=a.id_alumno, centros c WHERE a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador' AND( estado_solicitud='duplicada' or estado_solicitud='irregular') $orden";
         else
            $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a LEFT JOIN alumnos_orden ao ON ao.id_alumno=a.id_alumno,centros c WHERE a.id_centro_destino=c.id_centro AND fase_solicitud!='borrador'  and( estado_solicitud='duplicada' or estado_solicitud='irregular') and c.id_centro=$c $orden";

      }
				$log->warning("CONSULTA SOLICITUDES PROVISIONALES SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
		
				$query=$this->conexion->query($sql);
        		if($query)
               while ($row = $query->fetch_object()) 
                  $resultSet[]=$row;
        return $resultSet;
	}
	public function getSolicitudesMatriculaFinal($c=1,$subtipo_listado,$estado_convocatoria,$log,$rol,$id_alumno) 
	{
		$log->warning("CCONSULTA SOLICITUDES MATRICULA FINAL");
      $provincia=substr($rol,2);
      //si el estado de la convocatoria es previo a provisioonales la tabla del
      //baremo es la original
      $tablabaremo='b';
      $tabla_alumnos='alumnos_matricula_final';
      $order=" order by id_centro,tipoestudios";
		
      $resultSet=array();
		if($rol=='admin')
         $sql="SELECT * FROM alumnos_matricula_final $order";
		else if($rol=='centro')
		   $sql="SELECT a.tel_dfamiliar1,a.matricula,a.email,a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,b.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro=c.id_centro where c.id_centro=$c order by tipoestudios";
		else if($rol=='alumno')
         $sql="SELECT * FROM alumnos_matricula_final WHERE id_alumno=$id_alumno";
      else
         $sql="SELECT * FROM alumnos_matricula_final af JOIN centros c ON af.id_centro=c.id_centro and provincia='$provincia' ";
		$log->warning("CONSULTA SOLICITUDES MATRICULA FINAL SUBTIPO: ".$subtipo_listado);
		$log->warning($sql);

      $query=$this->conexion->query($sql);
      if($query)
         while ($row = $query->fetch_object()) 
         {
            $resultSet[]=$row;
         }
        return $resultSet;
	}
	public function getSolicitudesDefinitivas_old($c=1,$subtipo_listado,$estado_convocatoria,$log,$rol) 
	{
		$log->warning("CCONSULTA SOLICITUDES DEFINITIVAS");
      $provincia=substr($rol,2);
      //si el estado de la convocatoria es previo a provisioonales la tabla del
      //baremo es la original
      $tablabaremo='b';
      $tabla_alumnos='alumnos_definitiva';
		
      $order=" order by c.id_centro,a.tipoestudios, a.transporte
desc,$tablabaremo.puntos_validados desc,$tablabaremo.validar_hnos_centro
desc,$tablabaremo.validar_tutores_centro desc,$tablabaremo.validar_proximidad_domicilio
desc,FIELD($tablabaremo.proximidad_domicilio,'dfamiliar','dlaboral','dflimitrofe','dllimitrofe','sindomicilio'),$tablabaremo.validar_renta_inferior
desc,$tablabaremo.validar_discapacidad
desc,FIELD($tablabaremo.discapacidad,'alumno','hpadres','no'),$tablabaremo.validar_tipo_familia
desc,FIELD($tablabaremo.tipo_familia,'numerosa_especial','monoparental_especial','numerosa_general','monoparental_especial','no'),$tablabaremo.hermanos_centro
desc,a.tutores_centro desc,a.nordensorteo asc,a.nasignado desc";
		
      $resultSet=array();
		if($subtipo_listado=='admitidos_def')
		   if($c<=1)
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta' and est_desp_sorteo='admitida' $order";
			else
			   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado,c.nombre_centro,a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='admitida' and c.id_centro=$c $order";
			elseif($subtipo_listado=='noadmitidos_def')
			   if($c<=1)
				$sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro,a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' $order";
			   else
				   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and estado_solicitud='apta'  and est_desp_sorteo='noadmitida' and c.id_centro=$c $order";
			elseif($subtipo_listado=='excluidos_def')
			   if($c<=1)
				   $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado
as nasignado,c.nombre_centro, a.puntos_validados,a.id_centro_destino as id_centro  FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador' and( estado_solicitud='duplicada' or estado_solicitud='irregular') $order";
			   else
			      $sql="SELECT a.id_alumno,a.nombre,a.apellido1,a.apellido2,a.tipoestudios,a.fase_solicitud,a.estado_solicitud,a.transporte,a.nordensorteo,a.nasignado as nasignado, a.puntos_validados FROM $tabla_alumnos a left join baremo b on b.id_alumno=a.id_alumno left join centros c on a.id_centro_destino=c.id_centro where fase_solicitud!='borrador'  and( estado_solicitud='duplicada' or estado_solicitud='irregular') and c.id_centro=$c $order";
				$log->warning("CONSULTA SOLICITUDES DEFINITVO SUBTIPO: ".$subtipo_listado);
				$log->warning($sql);
		
				$query=$this->conexion->query($sql);
        		if($query)
               while ($row = $query->fetch_object()) 
               {
                  $resultSet[]=$row;
               }
        return $resultSet;
	}
	public function getTodasSolicitudes_old() 
	{
		$sql="SELECT a.* FROM alumnos a";

				$query=$this->conexion->query($sql);
        		if($query)
               while ($row = $query->fetch_object()) 
               {
                  $resultSet[]=$row;
               }
        return $resultSet;
	}
	//datos de la solicitud para mostrar en la web despues de crear una nueva solicitud
	public function getSol($id,$log) 
	{
		$sol_completa=array();
		$query="SELECT a.*,IFNULL(b.puntos_validados,0) as puntos_validados FROM alumnos a, baremo b  where a.id_alumno=b.id_alumno and b.id_alumno=$id";
		$log->warning("CONSULTA SOLICITUD CREADA: ".$query);
		$soldata=$this->conexion->query($query);
        	if($row = $soldata->fetch_object()) 
		{
           		$solSet=$row;
        	}
		//convertimos objeto en array
		foreach($solSet as $k=>$vsol)
			$sol_completa[$k]=$vsol;
		return $sol_completa;
    	}
	public function getDatosSolicitudes($id_centro,$log) 
	{
		$sol_completa=array();
		$query="SELECT a.tipoestudios,a.id_alumno as id,a.transporte,a.conjunta,a.nordensorteo,b.* FROM alumnos a LEFT JOIN baremo b ON a.id_alumno=b.id_alumno WHERE a.id_centro_destino=$id_centro and fase_solicitud!='borrador'";
		$log->warning("CONSULTA SOLICITUD CREADA: ".$query);
		$soldata=$this->conexion->query($query);
     	while($row = $soldata->fetch_object()) 
     	   $sol_completa[]=$row;
		return $sol_completa;
   }
	public function getTipoCentro($idcentro) 
	{
		//averiguamos si es de ed especial
		$query="select nombre_centro from centros c,centros_grupos cg where c.id_centro=cg.id_centro and c.id_centro='".$idcentro."' limit 1";
		$soldata=$this->conexion->query($query);
    if($soldata->num_rows==0) return 0;
		else return 1;
	}

	public function getEstadoSol($idsol) 
	{
		$query="select estado_solicitud from alumnos where id_alumno='".$idsol."'";

		$soldata=$this->conexion->query($query);
	  	if($soldata->num_rows==0) return 'noapta';
	  	if($row = $soldata->fetch_object()) 
		{
   	 	$solSet=$row;
		return $solSet->estado_solicitud;
    		}
		else return 'noapta';
    	}
	public function getFaseSol($idsol,$log_nueva) 
	{
		$query="SELECT fase_solicitud FROM alumnos WHERE id_alumno='".$idsol."'";

		$log_nueva->warning("CONSULTA FASE DE SOLICITUD: ".$query);

		$soldata=$this->conexion->query($query);
		$log_nueva->warning(print_r($soldata,true));
	  	if($soldata->num_rows==0) return 'noapta';
	  	if($row = $soldata->fetch_object()) 
		{
   	 	$solSet=$row;
		return $solSet->fase_solicitud;
    	}
		else return 'noapta';
   }
   public function generarHermanoAdmision($idh,$ida,$log)
   {
      $idsh=$this->getIdsHermanos($ida,$log);
      $sql1="INSERT INTO alumnos_hermanos_admision VALUES($ida,$idh)";
      $log->warning("CONSULTA RELACION HERMANOS");
      $log->warning($sql1);
      $sql2="INSERT INTO alumnos_hermanos_admision VALUES($idh,$ida)";
      $log->warning("CONSULTA INVERSA RELACION HERMANOS");
      $log->warning($sql2);
      $this->conexion->query($sql1);
      $this->conexion->query($sql2);
      return 1;
   }
   public function actualizarHermanoAdmision($idh,$ida,$log)
   {
      $idsh=$this->getIdsHermanos($ida,$log);
      $log->warning("DATOS HERMANOS");
      $log->warning(print_r($idsh,true));
      $sql1="INSERT INTO alumnos_hermanos_admision VALUES($ida,$idh)";
      $log->warning("CONSULTA ACTUALIZACION RELACION HERMANOS");
      $log->warning($sql1);
      $sql2="INSERT INTO alumnos_hermanos_admision VALUES($idh,$ida)";
      $log->warning("CONSULTA INVERSA ACTUALIZACION RELACION HERMANOS");
      $log->warning($sql2);
      $this->conexion->query($sql1);
      $this->conexion->query($sql2);
      foreach($idsh as $idhermanoadicional)
      {
         $sql1="INSERT INTO alumnos_hermanos_admision VALUES($idh,$idhermanoadicional)";
         $sql2="INSERT INTO alumnos_hermanos_admision VALUES($idhermanoadicional,$idh)";
         $this->conexion->query($sql1);
         $this->conexion->query($sql2);
         $log->warning("CONSULTA INVERSA RELACION HERMANOS ADICIONAL");
         $log->warning($sql1);
         $log->warning("CONSULTA INVERSA RELACION HERMANOS");
         $log->warning($sql2);
      }
      return 1;
   }
   public function generarBaremoHermanoAdmision($idh,$log)
   {
      $sql="INSERT INTO baremo(id_alumno,puntos_totales) VALUES($idh,4)";
      $log->warning("INSERCION BAREMO HERMANO ADMISION");
      $log->warning($sql);
      if($this->conexion->query($sql)){
          return 1;
      }
      else
      {
         $log->warning("ERROR CREANDO BAREMO HERMANO DE ADMISIÓN");
         $log->warning($this->conexion->error);
         $log->warning($this->conexion->errno);
         return 0;
      }
   }
   public function generarUsuario($dni_tutor1,$log)
   {
      $datos_usuario=array();
      $clave=rand(1000,9999);
      while($this->existeCuenta($clave,$dni_tutor1)==0)	
         $clave=rand(1000,9999);

      $datos_usuario['clave']=$clave;
      $log->warning("CLAVE GENERADA: ".$clave);
      if(strlen($dni_tutor1)==0) return -3; 
      
      $log->warning("CONSULTA INSERCION USUARIO:");

      $query="INSERT INTO usuarios(nombre_usuario,rol,clave,clave_original) VALUES('".$dni_tutor1."','alumno',md5('".$clave."'),$clave)";
      $log->warning($query);
      
      $saveusuario=$this->conexion->query($query);
      $id_usuario=$this->conexion->insert_id;
      $datos_usuario['id_usuario']=$id_usuario;
      return $datos_usuario;
   }
   public function generarSolicitudHermano($sol,$dh,$id_usuario,$tipo='save',$log)
   {
      $datos_hermano=array();
      $sol['token']=bin2hex(random_bytes(8));
      $datos_hermano['token']=$sol['token'];
      if($tipo=='update')
      {
         $sol['nombre']=$dh['nombre'];
         $sol['apellido1']=$dh['apellido1'];
         $sol['apellido2']=$dh['apellido2'];
         $sol['fnac']=$dh['fnac'];
         $sol['tipoestudios']=$dh['tipoestudios'];
         $sol['reserva']=$dh['reserva'];
         $sol['dni_alumno']=$dh['dni_alumno'];
      }
      else
      {      
         $sol['nombre']=$dh[0];
         $sol['apellido1']=$dh[2];
         $sol['apellido2']=$dh[3];
         $sol['fnac']=$dh[4];
         $sol['tipoestudios']=$dh[5];
         $sol['reserva']=$dh[6];
         $sol['dni_alumno']=$dh[7];
      }
      $sol['id_centro_estudios_origen']=$this->getIdCentro($sol['reserva'],$log);
      $sol['id_usuario']=$id_usuario;
      $log->warning("DATOS SOLICITUD HERMANO ADMISION"); 
      $log->warning(print_r($sol,true)); 

      $query="INSERT INTO alumnos(id_alumno,"; 
      foreach($sol as $key=>$elto)
         if(strlen($elto)!=0) $query.=$key.",";
      
      $query=trim($query,',');
      $query.=") VALUES(0,";
      //obtenemos los valores del alumno	
      foreach($sol as $key=>$elto)
      {
         if(strlen($elto)!=0)
         if($key=='id_centro_estudios_origen')
            $query.="'".trim($elto,'*')."',";
         else
            $query.="'".$elto."',";
      }
      $query=trim($query,',');
      $query.=")";
      $id_alumno=$this->insertarAlumno($query,$log,'conjunta');
      $datos_hermano['id_alumno']=$id_alumno;

      $log->warning("CONSULTA INSERCION HERMANO DE ALUMNO CONJUNTA:");
      $log->warning($query);
      $log->warning("ID INSERCION ALUMNO HERMANO CONJUNTA:");
      $log->warning($id_alumno);
      return $datos_hermano;
   }
   public function generarAlumno($sol,$log)
   {
			$query="INSERT INTO alumnos(id_alumno,"; 
			foreach($sol as $key=>$elto)
			   if(strlen($elto)!=0) $query.=$key.",";
			
         $query=trim($query,',');
			$query.=") VALUES(0,";
			//obtenemos los valores del alumno	
			foreach($sol as $key=>$elto)
		   {
		      if($key=='hadmision') continue;
				if(strlen($elto)!=0)
				if($key=='id_centro_estudios_origen')
				   $query.="'".trim($elto,'*')."',";
				else
				   $query.="'".$elto."',";
			}
			$query=trim($query,',');
			$query.=")";
			$id_alumno=$this->insertarAlumno($query,$log,'individual');

			$log->warning("ID INSERCION ALUMNO:");
			$log->warning($id_alumno);
      return $id_alumno;
   }
	public function getIdsHermanosAdmision_old($id_alumno) 
	{
      $aher=array();
		$query="select id_hermano from alumnos_hermanos_Admision where id_alumno=$id_alumno";

		$soldata=$this->conexion->query($query);
	   if($soldata->num_rows==0) return $aher;
	   while($row = $soldata->fetch_object()) 
   	   $aher[]=$row->id_hermano;
      return $aher;
   }
	public function getCentroNombre($idcentro) 
	{
		//averiguamos si es de ed especial
		if($this->getTipoCentro($idcentro)==1)
			//$query="select concat(nombre_centro,'*') as nombre_centro from centros c,centros_grupos cg where c.id_centro=cg.id_centro and c.id_centro='".$idcentro."' limit 1";
			$query="select concat(nombre_centro,'*') as nombre_centro from centros c where c.id_centro='".$idcentro."' limit 1";
		else
			//$query="select nombre_centro from centros c,centros_grupos cg where c.id_centro=cg.id_centro and c.id_centro='".$idcentro."'";
			$query="select nombre_centro from centros c where c.id_centro='".$idcentro."'";

		$soldata=$this->conexion->query($query);
	  if($soldata->num_rows==0);
	  if($row = $soldata->fetch_object()) 
		{
   	 	$solSet=$row;
		return $solSet->nombre_centro;
    		}
		else return 0;
    	}
   public function getCentroOrigenId($nombrecentro,$log)
   {
      $log->warning("CENTRO DESTINO: ");
      $query="SELECT id_centro FROM centros WHERE nombre_centro='".trim($nombrecentro,'*')."'";
      $soldata=$this->conexion->query($query);
      if($soldata->num_rows==0)
      {
         $log->warning("No hay datos");
         return 0;
      }
      if($row = $soldata->fetch_object())
      {
         $solSet=$row;
         return $solSet->id_centro;
      }
      else return 0;
    }

	public function getCentroId($nombrecentro,$log) 
   {
      $log->warning("CENTRO DESTINO: ");
		$query="SELECT id_centro FROM centros WHERE nombre_centro='".trim($nombrecentro,'*')."' AND clase_centro='especial'";
      $log->warning($query);
		$soldata=$this->conexion->query($query);
      if($soldata->num_rows==0) 
      {
		   $log->warning("No hay datos de centro de origen de clase especial");
         return 0;
      }
	   if($row = $soldata->fetch_object()) 
		{
   	   $solSet=$row;
		   return $solSet->id_centro;
      }
		else return 0;
    }

    public function getId() {
        return $this->id_alumno;
    }
    public function getIdFromToken($t,$log) {
		$query="SELECT id_alumno FROM alumnos WHERE token='$t'";
      $log->warning("CONSULTA ID ALUMNO FROM TOKEN: ".$query);
		$res=$this->conexion->query($query);
      if($res->num_rows==0) 
         return 0;
      else
         return $res->fetch_row()[0];
   }
   public function getIdFCentroromToken($t,$log) 
   {
		$query="SELECT cod_centro FROM centros WHERE token='$t'";
      $log->warning("CONSULTA ID CENTRO FROM TOKEN: ".$query);
		$res=$this->conexion->query($query);
      if($res->num_rows==0) 
         return 0;
      else
         return $res->fetch_row()[0];
   }
   public function getIdsHermanos($id,$log) 
   {
      $herids=array();
      $query="SELECT id_hermano FROM alumnos_hermanos_admision WHERE id_alumno=$id";
      $log->warning("MARCA ACT: ".$query);
      $res=$this->conexion->query($query);
      if($res->num_rows>0) 
         while($her=$res->fetch_row())
            array_push($herids,$her[0]);
      return $herids;
   }    
 
    public function setId($id) {
        $this->id_alumno = $id;
    }
   
   public function setValidada($token) 
   {
      $query="UPDATE alumnos SET fase_solicitud='validada' WHERE token='".$token."'";
      $soldata=$this->conexion->query($query);
      return 1;
   }
   public function getNombre($idc) 
   {
      $query="select nombre_centro from centros  where id_centro='".$idc."'";
      $soldata=$this->conexion->query($query);
      if($soldata->num_rows==0) return 0;
      if($row = $soldata->fetch_object()) 
      {
       $solSet=$row;
      return $solSet->nombre_centro;
      }
      else return 0;
   }
   public function getIdCentro($nombre,$log) 
   {
      $query="select id_centro from centros where nombre_centro='".$nombre."'";
      $log->warning("CONSULTA IDCENTRO: ".$query);
      $soldata=$this->conexion->query($query);
      if($soldata->num_rows==0) return 0;
      if($row = $soldata->fetch_object()) 
      {
       $solSet=$row;
      return $solSet->id_centro;
      }
      else return 0;
    }
   public function getIdCentroFromToken($token,$log) 
   {
      $query="select id_centro from centros where id_centro in(select id_centro_destino from alumnos where token='".$token."')";
      $log->warning("CONSULTA IDCENTRO: ".$query);
      $soldata=$this->conexion->query($query);
      if($soldata->num_rows==0) return 0;
      if($row = $soldata->fetch_object()) 
      {
       $solSet=$row;
      return $solSet->id_centro;
      }
      else return 0;
    }
 
   public function getFaseSolicitudFromToken($token,$log) 
   {
      $query="select fase_solicitud from alumnos where token='$token'";
      $log->warning("CONSULTA FASE: ".$query);
      $soldata=$this->conexion->query($query);
      if($soldata->num_rows==0) return 0;
      if($row = $soldata->fetch_object()) 
      {
       $solSet=$row;
      return $solSet->fase_solicitud;
      }
      else return 0;
    }
 
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
 
    public function getApellido1() {
        return $this->apellido1;
    }
    public function getApellido2() {
        return $this->apellido2;
    }
 
    public function setApellido1($apellido) {
        $this->apellido1 = $apellido;
    }
    public function setApellido2($apellido) {
        $this->apellido2 = $apellido;
    }
 
    public function getDni() {
        return $this->dni;
    }
 
    public function setDni($d) {
        $this->dni = $d;
    }
 
 
    public function getNacionalidad() {
        return $this->nacionalidad;
    }
 
    public function setNacionalidad($n) {
        $this->nacionalidad = $n;
    }
    public function getFnac() {
        return $this->fnac;
    }
 
    public function setFnac($n) {
        $this->fnac = $n;
    }
 
 
}
?>
