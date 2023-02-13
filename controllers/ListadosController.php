<?php
class ListadosController{
   public $conexion;
   public $allalumnos; 
   public $tabla; 
   public function __construct($tabla="matricula", $conexion=1,$estado_convocatoria) 
   {
      $this->tabla=$tabla;
      $this->conexion=$conexion;
      $this->estado_convocatoria=$estado_convocatoria;
   }
   public function showListadoSolicitudes($rol,$id_centro,$solicitud,$log,$id_alumno,$provincia)
   {
      $res='';
      $filtro_solicitudes='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno o centro"><small id="emailHelp" class="form-text text-muted"></small>';
      $log->warning("ENTRANDO EN SHOWLISTADOSOLICITUDES, rol: $rol, idcentro: $id_centro estado: $this->estado_convocatoria");
	   $solicitudes=$this->getSolicitudes($id_centro,'normal','normal',$solicitud,$log,$id_alumno,$rol,$provincia); 
      
      if($rol!='alumno' and $rol!='anonimo')
		   $res.=$filtro_solicitudes;
	   $res.=$this->showSolicitudes($solicitudes,$rol);
      return $res;
   }
  	public function getMatriculadosCentro($centro,$conexion,$rol=1)
	{
     $resultSet=array();
	   if($rol=='admin')
	   {
         $sql="SELECT * FROM alumnos";
         $query=$conexion->query($sql);
	   }
      else
	   { 
         $sql="SELECT * FROM matricula where id_centro=".$centro;
         $query=$conexion->query($sql);
	   }
      if($query)
      while ($row = $query->fetch_object())
        $resultSet[]=$row;
   return $resultSet;
	}
   public function getDefinitivos($id_centro)
	{
		//Creamos el objeto alumno
    	$alumno=new Alumno($this->conexion,'alumnos');
		  //Conseguimos todos los datos de baremacion de los alumnos
    	$allbaremos=$alumno->getBaremos($id_centro);
 
	return $allbaremos;
	}

#FUNCIONES PARA EL SORTEO Y ASIGNACIÓN DE NÚMERO ALEATORIO
######################################################################################
   public function asignarNumSol($log)
   {
      $sql="SET @r := 0";
      $this->conexion->query($sql);
      //ponemos todas a cero para evitar inconsistencias
      $sql1="UPDATE  alumnos SET nasignado =0";
      $sql2="UPDATE  alumnos SET nasignado = (@r := @r + 1) where fase_solicitud!='borrador' ORDER BY  RAND()";
      $sqltmp="CREATE table  alumnos_nasignado SELECT id_alumno,nasignado,conjunta FROM alumnos WHERE fase_solicitud!='borrador'";

      if($this->conexion->query($sql1) and $this->conexion->query($sql2))
      {
         $this->conexion->query($sqltmp);
         $r=$this->asignarNumConjuntas($log);
         return $r;
      }
      else{
         return 0;
      }
   }
   public function asignarNumConjuntas($log)
   {
      $nhermanos_modificados=0;
      $idsalumnos=array();
      $sql="SELECT id_alumno,nasignado FROM alumnos where fase_solicitud!='borrador' ORDER BY nasignado asc";
      $i=0;
      $r=$this->conexion->query($sql);
      while ($obj = $r->fetch_object())
      {
         $id_alumno_actual=$obj->id_alumno;
         $nasignado_actual=$obj->nasignado;
         $nasignado_actual=$this->getNasignado($id_alumno_actual);
         $hermanos_actual=$this->getHermanos($id_alumno_actual,$log);
         $log->warning("COMENZANDO, HERMANO DE: $id_alumno_actual\n");
         $log->warning(print_r($hermanos_actual,true));
         if(sizeof($hermanos_actual)>=1)
         {
            $log->warning("HAY HERMANOS\n");
            $log->warning("NASIGNADO $nasignado_actual\n");
            $log->warning("COMPROBANDO HERMANOS DE $id_alumno_actual\n");
            $data_hermano=$this->checkHermanos($hermanos_actual,$idsalumnos,$log);
            $log->warning("DATOS DE HERMANOS DE $id_alumno_actual\n");
            $log->warning(print_r($data_hermano,true));
            $id_hermano=$data_hermano['id_alumno'];
            $n_hermano=$this->getNasignado($id_hermano);
            $log->warning("id hermano: $id_hermano\n");
            if($id_hermano!=0)
            {
               $nhermanos_modificados++;
               $log->warning("HAY HERMANOS ANTERIORES\n");

               $sql="UPDATE alumnos SET nasignado=$n_hermano WHERE id_alumno=$id_alumno_actual and fase_solicitud!='borrador'";
               $log->warning("MODIFICANDO NUMERO A HERMANO:\n");
               $log->warning($sql);
               $res=$this->conexion->query($sql);

               $nnasignado=$nasignado_actual-$nhermanos_modificados; 
               $sql="UPDATE alumnos SET nasignado=nasignado-1 WHERE nasignado>$nasignado_actual and fase_solicitud!='borrador'";
               $log->warning("MODIFICANDO NUMERO AL RESTO DE NUMEROS MAYORES:\n");
               $log->warning($sql);
               $res=$this->conexion->query($sql);
            }
         }
         else
            $log->warning("NO HAY HERMANOS DE: $id_alumno_actual\n");
         $idsalumnos[$i]['id_alumno']=$id_alumno_actual;
         $idsalumnos[$i]['nasignado']=$nasignado_actual;
         $i++;
      }
      return 1;
   }
    public function getNasignado($id_alumno)
    {
      if($id_alumno==0) return 0;
      $sql="SELECT nasignado FROM alumnos WHERE id_alumno=$id_alumno";
      $r=$this->conexion->query($sql);
      if($obj = $r->fetch_object())
         return $obj->nasignado;
      else return 0;
  }
    public function getHermanos($id_alumno,$log)
   {
      $hermanos=array();
      $sql="SELECT h.id_hermano as id_hermano FROM alumnos a,alumnos_hermanos_admision h WHERE h.id_alumno=a.id_alumno AND a.id_alumno=$id_alumno";
      $r=$this->conexion->query($sql);
      while ($obj = $r->fetch_object())
         $hermanos[]=$obj->id_hermano;
      return $hermanos;
  }
   public function checkHermanos($h,$ids,$log)
   {
     //comprobamos si se ha procesado el hermano
    //  $log->warning("COMPROBANDO HERMANOS");
    //  $log->warning(print_r($h,true));
    //  $log->warning(print_r($ids,true));
      foreach($h as $hermano)
         foreach($ids as $al)
            if($hermano==$al['id_alumno'])
               return $al;
      return 0;
  }

   public function asignarNumSol_old()
	{
		$sql="SET @r := 0";
		$this->conexion->query($sql);
		//ponemos todas a cero para evitar inconsistencias
	   $sql1="UPDATE  alumnos SET nasignado =0";
		$sql2="UPDATE  alumnos SET nasignado = (@r := @r + 1) where fase_solicitud!='borrador' ORDER BY  RAND()";
		
		if($this->conexion->query($sql1) and $this->conexion->query($sql2))
		{
			return 1;
		}
		else{ 
			return 0;
		}
	}
  public function actualizaSolicitudesSorteo($id_centro,$numero,$solicitudes,$nvebo=0,$nvtva=0,$fasecentro=1)
	{
		//Creamos el objeto solicitud
    		$solicitud=new Solicitud($this->conexion);
 		$res=$solicitud->actualizaSolSorteo($id_centro,$numero,$solicitudes,$nvebo,$nvtva,$fasecentro);
		return $res;
	}
  public function getMatriculas($id_centro=1)
	{
	   $matricula=new Matricula($this->conexion);
      $allmatriculas=$matricula->getMatriculados($id_centro);
	return $allmatriculas;
	}
  public function getSolicitudes($id_centro,$modo,$subtipo_listado,$solicitud,$log,$id_alumno,$rol,$provincia)
  {
      $estado_convocatoria=$this->estado_convocatoria;
      $log->warning("ENTRANDO EN GET SOLICITUDEs, subtipo: $subtipo_listado estado: $estado_convocatoria modo $modo rol $rol, Provincia: $provincia");
		if($modo=='normal')// listados previos al sorteo
    	{
         $log->warning("ENTRANDO EN LISTADO DE SOLICITUDES VALIDADAS");
         $allsolicitudes=$solicitud->getSolicitudesValidadas($id_centro,$estado_convocatoria,$subtipo_listado,'alumnos',$log,$id_alumno,$rol,$provincia);
 		}
		elseif($modo=='baremadas')
		{
         $log->warning("ENTRANDO EN LISTADO DE SOLICITUDES BAREMADAS");
         $allsolicitudes=$solicitud->getSolicitudesBaremadas($id_centro,$estado_convocatoria,$subtipo_listado,'alumnos',$log,$id_alumno,$rol,$provincia);
		}
		elseif($modo=='csv')
		{
         $log->warning("ENTRANDO EN LISTADO DE SOLICITUDES CSV");
         $allsolicitudes=$solicitud->getTodasSolicitudes($id_centro,0,$subtipo_listado,$estado_convocatoria,$log,$rol,$provincia);
		}
		elseif($modo=='provisionales')
		{
			$log->warning('OBTENIENDO PROVISIONALES GETALLSOLLISTADOS');
    		$allsolicitudes=$solicitud->getSolicitudesProvisionales($id_centro,1,$subtipo_listado,$estado_convocatoria,$rol,$log);
		}
		elseif($modo=='definitivos')
		{
			$log->warning('OBTENIENDO DEFINITIVOS ESTADO: '.$estado_convocatoria);
		   $allsolicitudes=$solicitud->getSolicitudesDefinitivas($id_centro,1,$subtipo_listado,$estado_convocatoria,$rol,$log);
		}
		elseif($modo=='matriculafinal')
		{
			$log->warning('OBTENIENDO DEFINITIVOS ESTADO: '.$estado_convocatoria);
		   $allsolicitudes=$solicitud->getSolicitudesMatriculaFinal($id_centro,$subtipo_listado,$estado_convocatoria,$log,$rol,$id_alumno);
		}
		elseif($modo=='fase2' || $modo=='fase3')
		{
         //ya no se usa
			$log->warning("Funcion: getSolicitudes FASE II:");
		   //$allsolicitudes=$solicitud->getAllSolListados($id_centro,3,$subtipo_listado,$fase_sorteo,$estado_convocatoria);
		   $allsolicitudes=$solicitud->getSolicitudesFase2($id_centro,3,$subtipo_listado,$fase_sorteo,$estado_convocatoria);
		}
		elseif($modo=='fase3')
		{
         //ya no se usa
			$log->warning("Funcion: getSolicitudes FASE III:");
		   $allsolicitudes=$solicitud->getSolicitudesFase3($id_centro,3,$subtipo_listado,$fase_sorteo,$estado_convocatoria);
		}
	return $allsolicitudes;
	}
   public function getAlumnos(){
	//Creamos el objeto alumno
     $alumno=new Alumno($this->conexion,$this->tabla);
     //Conseguimos todos los alumnos
     $allalumnos=$alumno->getAll();
     //Cargamos la vista index y le pasamos valores
	return $allalumnos;
	}

  public function showFormulariosolicitud(){
		require_once DIR_BASE.'/includes/form_solicitud.php';
		return $formsol;
	}
  public function showDefinitivos($a,$rol='centro'){
	//codigo para mostrar alumnos según tipo de inscripcion
		$li='';
		$html='<section class="col-lg-12 usuario" style="height:400px;">';
		$cabecera="<div class='filasol' id='cab_solicitudes'>";
		if($rol=='admin') $cabecera.="<span><b>CENTRO</b></span>";
                $cabecera.="<span class='cab_class dalumnofirst' data-idal=''><b>ALUMNO</b></span>";
                $cabecera.="<span class='cab_class dalumno' data-idal=''><b>CRITERIOS DE PRIORIDAD</b></span>";
                $cabecera.="<span class='cab_class dalumno' data-idal=''><b>NORDEN</b></span>";
                $cabecera.="<span class='cab_class dalumno' data-idal=''><b>BAREMO</b></span>";
                $cabecera.='<div class="right" id=""><span><b>CAMBIO ESTADO</b></span></div>&nbsp';
                $cabecera.='</div><hr/>';
   foreach($a as $user) 
   {
    $i= $user->id_alumno;
		$li.="<div class='filasol' id='filasol".$user->id_alumno."'>";
                $li.="<span class='calumno dalumnofirst' data-idal='".$i."'>".$user->id_alumno."-".strtoupper($user->apellido1).",".strtoupper($user->apellido2);
                $li.="<span class='trans_cole dalumno' data-idal='".$i."'>".$user->trans_cole."</span";
                $li.="<span class='trans_cole dalumno' data-idal='".$i."'>".$user->numero_sorteo."</span";
                $li.="<span class='trans_cole dalumno' data-idal='".$i."'>".$user->ptstotal."</span";
                $li.='<span><div class="right" id="'.$user->estado.'"><a  class="btn btn-danger estado" id="'.$i.'" >BORRADOR</a></div>&nbsp
                <div class="right" id="'.$user->estado.'"><a  class="btn btn-info estado" id="'.$i.'" >PROVISIONAL</a></div>&nbsp;
                <div class="right" id="'.$user->estado.'"><a  class="btn btn-success estado" id="'.$i.'" >DEFINITIVO</a></div>
                <hr/></span></div>';
   }
        $html.=$cabecera.$li;
        $html.='</section>';

	return $html;
	}
  public function showFiltrosTipo()
	{
			$botones=$this->check('TODAS');
			$botones.=$this->check('EBO','tipoestudio');
			$botones.=$this->check('TVA','tipoestudio');
			return $botones;
	}

  public function showFiltrosCheck()
	{
			$botones="<div id='filtroscheck'>".$this->check('TODAS');
			$botones.="<br>FASE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$botones.=$this->check('Borrador','fase');
			$botones.=$this->check('Validada','fase');
			$botones.=$this->check('Baremada','fase');
			$botones.="<br>ESTADO:&nbsp;&nbsp;";
			$botones.=$this->check('Irregular','estado');
			$botones.=$this->check('Duplicada','estado');
			$botones.=$this->check('Apta','estado');
			return $botones."</div>";
	}
  public function showBotones()
	{
	$botones=$this->boton('TODAS');
	$botones.=$this->boton('Borrador');
	$botones.=$this->boton('Validada irregular');
	$botones.=$this->boton('Validada duplicada');
	$botones.=$this->boton('Validada');
	$botones.=$this->boton('Baremada');
	return $botones;;
	}
  public function check($texto,$tipo='')
	{
		$ret='<label for="'.$texto.'" class="labelcheck">'.$texto.'</label>';
		if(strpos('TODAS',$texto)!==FALSE) return '<input value="0" class="filtrosoltodas" data-tipo="'.$tipo.'" id="'.$texto.'" type="checkbox">'.$ret;
		else return '<input value="0" class="filtrosol" data-tipo="'.$tipo.'" id="'.$texto.'" type="checkbox">'.$ret;

	}
  public function boton($texto)
	{
	return '<button type="button" class="btn btn-outline-dark filtrosol" id="'.$texto.'">'.$texto.'</button>';
	}
  public function showSolicitud($sol){
	
		$li="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:black'>";
      $li.="<td class='calumno dalumno prueba' data-token='".$sol->token."'  data-idal='".$sol->id_alumno."'>".$sol->id_alumno."-".strtoupper($sol->apellido1).",".strtoupper($sol->nombre)."</td>";
      $token=$sol->token;
      if(isset($sol->id_centro_destino))
         $id_centro=$sol->id_centro_destino;
      else
         $id_centro=$sol->id_centro;
     
      if($this->estado_convocatoria>=ESTADO_RECLAMACIONES_BAREMADAS AND $this->estado_convocatoria<ESTADO_RECLAMACIONES_PROVISIONAL)
      { 
         if($sol->tipo=='baremo')
            $rec="Ver reclamación listado baremado";
         else
            $rec="No hay reclamación baremada";
         $enlacerec="reclamaciones_baremo.php?id_centro=$id_centro&token=$token";
      }
      if($this->estado_convocatoria==ESTADO_RECLAMACIONES_PROVISIONAL)
      { 
         if($sol->tipo=='provisional')
            $rec="Ver reclamación listado provisional";
         else
            $rec="No hay reclamación provisional";
         $enlacerec="reclamaciones_provisional.php?id_centro=$id_centro&token=$token";
      }
         
		$li.="<td id='$token' class='reclamacion'><a href='$enlacerec'>$rec</a></td>";
		$li.="<td id='print".$sol->id_alumno."' class='fase printsol'><i class='fa fa-print psol' aria-hidden='true'></i></td>";
		$li.="<td id='fase".$sol->id_alumno."' class='fase'>".$sol->fase_solicitud."</td>";
		$li.="<td id='estado".$sol->id_alumno."' class='estado'>".$sol->estado_solicitud."</td>";
		$li.="<td id='tipoens".$sol->id_alumno."' class='estado'>".$sol->tipoestudios."</td>";
		$li.="<td id='transporte".$sol->id_alumno."'>".$sol->transporte."</td>";
		$li.="<td id='pvalidados".$sol->id_alumno."'>".$sol->puntos_validados."</td>";
		$li.="<td id='nordsorteo".$sol->id_alumno."'>".$sol->nordensorteo."</td>";
		$li.="<td id='nasignado".$sol->id_alumno."'>".$sol->nasignado."</td>";
		$li.="</tr>";
	return $li;
	}
  public function showSolicitudes($a,$rol='centro',$id_centro=-1)
	{
   
	//codigo para mostrar alumnos según tipo de inscripcion
	$html="<table class='table table-striped' id='sol_table$id_centro' id_centro='$id_centro' style='color:white'>";
	$html.="<thead>
      <tr>
        <th>DATOS ALUMNO</th>
        <th></th>
        <th></th>
        <th>FASE</th>
        <th>ESTADO</th>
        <th>TIPO</th>
        <th>CRITERIOS DE PRIORIDAD</th>
        <th>BAREMO</th>
        <th>NORDEN</th>
        <th>NALEATORIO</th>
      </tr>
    </thead>";
	$html.="<tbody>";
		foreach($a as $user) 
		{
			$html.=$this->showSolicitud($user);	
		}
	$html.="</tbody>";
	$html.='</table>';

	return $html;
	}
  public function showSolicitudListadoMatriculaFinal($sol,$datos,$htmldatoscentros='',$fase=2){
		$i=0;	
		//los listados provisionales no permiten acceder a los datos de la solicitud
		$class='';
      if($sol->matricula=='no') $matricular='Matricular'; else $matricular='Desmatricular';

		$li="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:black'>";
		foreach($datos as $d)
		{
			$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$sol->$d."</td>";
			$i++;
		}
      
			//$li.='<td><button data-tipo="'.$sol->tipoestudios.'" data-idcentro="'.$sol->$d.'"  id="matricular'.$sol->id_alumno.'"  class="matriculafinal" value="matricularfinal">'.$matricular.'</button></td> ';
		$li.="</tr>";
	return $li;
	}
   public function showSolicitudListado($sol,$datos,$provisional=0,$htmldatoscentros='',$fase=2)
   {
		$i=0;	
		//los listados provisionales no permiten acceder a los datos de la solicitud
		if($provisional>=1) $class='';
		else $class='';

      //tratamos cada campo por separado, solo mostramos los q están validados
      $valorfamilia_numerosa=0;
      if($sol->comprobar_familia_numerosa==2)
         $valorfamilia_numerosa=$sol->tipo_familia_numerosa;
      $valorfamilia_monoparental=0;
      if($sol->comprobar_familia_monoparental==2)
         $valorfamilia_monoparental=$sol->tipo_familia_monoparental;
      
      $valorrenta_inferior=0;
      if($sol->comprobar_renta_inferior==2) $valorrenta_inferior=1;
      $valordiscapacidad_alumno=0;
      if($sol->comprobar_discapacidad_alumno==2) $valordiscapacidad_alumno=1;
      $valordiscapacidad_hermanos=0;
      if($sol->comprobar_discapacidad_hermanos==2) $valordiscapacidad_hermanos=1;
      if($sol->proximidad_domicilio=='') $proximidad_domicilio='sindomicilio';
      else $proximidad_domicilio=$sol->proximidad_domicilio;
		$li="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:black'>";
		foreach($datos as $d)
		{
			if($i==0)
			{
  		  		$li.="<td class='".$class." dalumno ".$d."' data-token='".$sol->token."' data-idal='".$sol->id_alumno."'>".strtoupper($sol->$d)."</td>";
					
			}
			else
				if($d=='centro1')
				{
				$html="<select>";
  				if(isset($sol->centro1)) $html.="<option>".substr($sol->centro1,0,10)."</option>";
  				if(isset($sol->centro2)) $html.="<option>".substr($sol->centro2,0,10)."</option>";
  				if(isset($sol->centro3)) $html.="<option>".substr($sol->centro3,0,10)."</option>";
  				if(isset($sol->centro4)) $html.="<option>".substr($sol->centro4,0,10)."</option>";
  				if(isset($sol->centro5)) $html.="<option>".substr($sol->centro5,0,10)."</option>";
  				if(isset($sol->centro6)) $html.="<option>".substr($sol->centro6,0,10)."</option>";
				$html.="</select>";
				$li.="<td id='".$d.$sol->id_alumno."'>".$html."</td>";
				
				}
				elseif($d=='centro_definitivo')
				{
				$li.="<td id='".$d.$sol->id_alumno."' data-idcactual='idcactual".$sol->id_centro_definitivo."'>".$sol->$d."</td>";
				}
				elseif($d=='centrosdisponibles')
				{
				$select='';
				//Para la fase 2 incluimos centros disponibles
				if($fase==2){
					$select.="<div id='".$d.$sol->id_alumno."' class='listacentros'><select id='selectcentro".$sol->id_alumno."'>".$htmldatoscentros."</select></div>";
					$select.='<button data-tipo="'.$sol->tipoestudios.'" data-idcentro="'.$sol->$d.'"  id="'.$sol->id_alumno.'"  class="cdefinitivo" value="Cambiar">Cambiar</button> ';
				}
				elseif($fase==3){
					$select.='<button data-tipo="'.$sol->tipoestudios.'" data-idcentro="'.$sol->$d.'"  id="'.$sol->id_alumno.'"  class="activarfase3" value="activar">Activar</button> ';
				}
				$li.="<td id='".$d.$sol->id_alumno."'>".$select."</td>";
				}
				elseif($d=='centro_origen')
				{
					$li.="<td id='".$d.$sol->id_alumno."' data-reserva='reserva".$sol->reserva."' data-idcorigen='idcorigen".$sol->id_centro_origen."'>".$sol->$d."</td>";
				}
				elseif($d=='comprobar_renta_inferior')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valorrenta_inferior."</td>";
				elseif($d=='comprobar_discapacidad_alumno')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valordiscapacidad_alumno."</td>";
				elseif($d=='comprobar_discapacidad_hermanos')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valordiscapacidad_hermanos."</td>";
				elseif($d=='comprobar_familia_numerosa')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valorfamilia_numerosa."</td>";
				elseif($d=='comprobar_familia_monoparental')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valorfamilia_monoparental."</td>";
				elseif($d=='validar_tipo_familia')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valorfamilia."</td>";
				elseif($d=='validar_discapacidad')
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$valordiscapacidad."</td>";
				elseif($d=='proximidad_domicilio')
            {
               if($sol->validar_proximidad_domicilio==0)
					   $li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>0</td>";
               else
					   $li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$proximidad_domicilio."</td>";
            }
            else
					$li.="<td id='".$d.$sol->id_alumno."' class='".$d."'>".$sol->$d."</td>";
			$i++;
			}
		$li.="</tr>";
	return $li;
	}
   public function showListado($a,$rol='centro',$cabecera=array(),$camposdatos=array(),$tipolistado=0,$subtipo='')
   {
		$centros=$this->getCentrosNombreVacantes();
		$htmlcentros="";
		$fase=2;
		//preparamos desplegable con centros y vacantes 
		if($subtipo=='lfase2_sol_ebo')
		{
         foreach($centros as $centro)
         {
            foreach($centros as $centro)
            {
               $cdata_parcial=substr($centro['nombre_centro'],0,10).":".$centro['vacantes_ebo'];
               $cdata_completo=$centro['nombre_centro'].":".$centro['vacantes_ebo'];
               $htmlcentros.="<option class='vacantesebo".$centro['id_centro']."' value='$cdata_completo'>".$cdata_parcial."</option>";
            }
         }
      }
		elseif($subtipo=='lfase2_sol_tva')
      {
         foreach($centros as $centro)
         {
            $cdata_parcial=substr($centro['nombre_centro'],0,10).":".$centro['vacantes_tva'];
            $cdata_completo=$centro['nombre_centro'].":".$centro['vacantes_tva'];
            $htmlcentros.="<option class='vacantestva".$centro['id_centro']."' value='$cdata_completo'>".$cdata_parcial."</option>";
         }
      }
	
	$centroanterior='';
	$centroactual='';
   $tipoestudios_anterior=$tipoestudios_actual='';

	$ncolumnas=sizeof($cabecera);
	$colspan=$ncolumnas-1;
	$html='<table class="table table-striped" id="sol_table" iiid_centro style="color:white">';
	$html.="<thead>
      <tr>";
	foreach($cabecera as $cab)
		$html.="<th style='color:black'>".$cab."</th>";

   $html.="</tr></thead><tbody>";
	$cabadmin=0;
	$cab=0;

	foreach($a as $sol) 
	{
		if($rol=='admin' || $rol=='sp')
		{
			$centroanterior=$centroactual;
			$tipoestudios_anterior=$tipoestudios_actual;
         $tipoestudios_actual=$sol->tipoestudios;
         if(isset($sol->id_centro_destino))
            $centroactual=$sol->id_centro_destino;
         else
            $centroactual=$sol->id_centro;
	
			if($sol->tipoestudios=='tva' and $cabadmin==0)
			{
				$cabadmin=1;
			}
			if($centroactual!=$centroanterior)
			{
	         $cab=0;
				$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color:#141259;'><td colspan='".$ncolumnas."'><b>".$sol->nombre_centro."</b></td></tr>";
            $html.="<tr>";
	         foreach($cabecera as $cab)
		         $html.="<th style='color:black'>".$cab."</th>";
            $html.="</tr>";
            $html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
			}
			if($centroactual==$centroanterior and $tipoestudios_actual!=$tipoestudios_anterior)
			{
		       $html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
         }
		}
		else
      {
			$tipoestudios_anterior=$tipoestudios_actual;
         $tipoestudios_actual=$sol->tipoestudios;
			if($tipoestudios_actual!=$tipoestudios_anterior)
         {
            $html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
            $cab=1;
         }
      }
      if($tipolistado=='matriculafinal')
		   $html.=$this->showSolicitudListadoMatriculaFinal($sol,$camposdatos,$htmlcentros,$fase);	
		else
         $html.=$this->showSolicitudListado($sol,$camposdatos,$provisional,$htmlcentros,$fase);	
	}
	$html.="</tbody>";
	$html.='</table>';

	return $html;
	}
   public function showListadoFase2($a,$rol='centro',$cabecera=array(),$camposdatos=array(),$provisional=0,$subtipo='',$log,$vacantes_centros)
   {
		//$centros=$this->getCentrosNombreVacantesFase2();
		$centros=$vacantes_centros;
      $log->warning("CENTROS: ");
      $log->warning(print_r($centros,true));
		$htmlcentros="";
		$fase=2;
		//preparamos desplegable con centros y vacantes 
		if($subtipo=='lfase2_sol_ebo')
		{
         foreach($centros as $centro)
         {
               $cdata_parcial=substr($centro['nombre_centro'],0,10).":".$centro['vacantes_ebo'];
               $cdata_completo=$centro['nombre_centro'].":".$centro['vacantes_ebo'];
               $htmlcentros.="<option class='vacantesebo".$centro['id_centro']."' value='$cdata_completo'>".$cdata_parcial."</option>";
         }
      }
		elseif($subtipo=='lfase2_sol_tva')
      {
         foreach($centros as $centro)
         {
            $cdata_parcial=substr($centro['nombre_centro'],0,10).":".$centro['vacantes_tva'];
            $cdata_completo=$centro['nombre_centro'].":".$centro['vacantes_tva'];
            $htmlcentros.="<option class='vacantestva".$centro['id_centro']."' value='$cdata_completo'>".$cdata_parcial."</option>";
         }
      }
	
	$centroanterior='';
	$centroactual='';

	$ncolumnas=sizeof($cabecera);
	$colspan=$ncolumnas-1;
	$html='<table class="table table-striped" id="sol_table" cccd_centro style="color:white">';
	$html.="<thead>
      <tr>";
	foreach($cabecera as $cab)
		$html.="<th>".$cab."</th>";

  $html.="</tr></thead><tbody>";
	$cabadmin=0;
	$cab=0;
	if($rol=='centro' or $rol=='admin')
			$html.="<tr class='filasol' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>EBO</b></td></tr>";

	foreach($a as $sol) 
	{
		if($rol=='admin' || $rol=='sp')
		{
			$centroanterior=$centroactual;
			$centroactual=$sol->id_centro;
			if($sol->tipoestudios=='tva' and $cabadmin==0)
			{
				$cabadmin=1;
			}
			if($centroactual!=$centroanterior)
				{
	         $cab=0;
				$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color:#141259;'><td colspan='".$ncolumnas."'><b>".$sol->nombre_centro."</b></td></tr>";
				$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
				//$cabadmin=0;
				}
		}
		if($sol->tipoestudios=='tva' and $cab==0)
		{
			$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
			$cab=1;
		}
		else if($sol->tipoestudios=='ebo' and $cab==0)
		{
			$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
			$cab=1;
		}
		$html.=$this->showSolicitudListado($sol,$camposdatos,$provisional,$htmlcentros,$fase);	
	}
	$html.="</tbody>";
	$html.='</table>';

	return $html;
	}
   public function showListadoFase3($a,$rol='centro',$cabecera=array(),$camposdatos=array(),$provisional=0,$subtipo='')
	{
		$centros=$this->getCentrosNombreVacantes();
		$htmlcentros="";
		$fase=3;
		//preparamos desplegable con centros y vacantes 
		if($subtipo=='lfase3_sol_ebo')
		{
         foreach($centros as $centro)
         {
            foreach($centros as $centro)
            {
               $cdata_parcial=substr($centro['nombre_centro'],0,10).":".$centro['vacantes_ebo'];
               $cdata_completo=$centro['nombre_centro'].":".$centro['vacantes_ebo'];
               $htmlcentros.="<option class='vacantesebo".$centro['id_centro']."' value='$cdata_completo'>".$cdata_parcial."</option>";
            }
         }
      }
		elseif($subtipo=='lfase3_sol_tva')
      {
         foreach($centros as $centro)
         {
            $cdata_parcial=substr($centro['nombre_centro'],0,10).":".$centro['vacantes_tva'];
            $cdata_completo=$centro['nombre_centro'].":".$centro['vacantes_tva'];
            $htmlcentros.="<option class='vacantestva".$centro['id_centro']."' value='$cdata_completo'>".$cdata_parcial."</option>";
         }
      }
      /*
		//preparamos boton para activar o desactivar dupllicado o sol irregular
		if($subtipo=='lfase3_sol_ebo' or $subtipo=='lfase3_sol_tva')
		{
			$fase=3;
		}
      */
	
	$centroanterior='';
	$centroactual='';

	$ncolumnas=sizeof($cabecera);
	$colspan=$ncolumnas-1;
	$html='<table class="table table-striped" id="sol_table" mmmid_centro style="color:white">';
	$html.="<thead>
      <tr>";
	foreach($cabecera as $cab)
		$html.="<th>".$cab."</th>";

  $html.="</tr></thead><tbody>";
	$cabadmin=0;
	$cab=0;
	if($rol=='centro' or $rol=='admin')
			$html.="<tr class='filasol' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>EBO</b></td></tr>";

	foreach($a as $sol) 
	{
		if($rol=='admin' || $rol=='sp')
		{
			$centroanterior=$centroactual;
			$centroactual=$sol->id_centro;
			if($sol->tipoestudios=='tva' and $cabadmin==0)
			{
				$cabadmin=1;
			}
			if($centroactual!=$centroanterior)
				{
	         $cab=0;
				$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color:#141259;'><td colspan='".$ncolumnas."'><b>".$sol->nombre_centro."</b></td></tr>";
				$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
				//$cabadmin=0;
				}
		}
		if($sol->tipoestudios=='tva' and $cab==0)
		{
			$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
			$cab=1;
		}
		else if($sol->tipoestudios=='ebo' and $cab==0)
		{
			$html.="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:white;background-color: #84839e;'><td colspan='".$ncolumnas."'><b>".strtoupper($sol->tipoestudios)."</b></td></tr>";
			$cab=1;
		}
		$html.=$this->showSolicitudListado($sol,$camposdatos,$provisional,$htmlcentros,$fase);	
	}
	$html.="</tbody>";
	$html.='</table>';

	return $html;
	}
  public function showSolicitudes_old($a,$rol='centro')
	{
	//codigo para mostrar alumnos según tipo de inscripcion
	$li='';
	$html='<section class="col-lg-12 usuario" style="height:400px;">';
	$cabecera="<div class='filasol' id='cab_solicitudes'>";
	if($rol=='admin') $cabecera.="<span><b>CENTRO</b></span>";
	$cabecera.="<span><b>ESTADO</b></span>";
							$cabecera.="<span class='cab_class dalumno' data-idal=''><b>DATOS ALUMNO</b></span>";
							$cabecera.='<div class="right" id=""><span><b>CAMBIO ESTADO</b></span></div>&nbsp';
							$cabecera.='</div><hr/>';
					foreach($a as $user) 
					{
							$i= $user->id_alumno;
	                  $li.="<div class='filasol' id='filasol".$user->id_alumno."'>";
                  	$li.="<span id='estado".$user->id_alumno."'>".$user->estado."</span>";
							$li.="<span class='calumno dalumno' data-idal='".$i."'>".$user->id_alumno."-".strtoupper($user->apellido1).",";
							$li.= strtoupper($user->apellido1);
							$li.='<div class="right" id="'.$user->estado.'"><a  class="btn btn-danger estado" id="'.$i.'" >BORRADOR</a></div>&nbsp
							<div class="right" id="'.$user->estado.'"><a  class="btn btn-info estado" id="'.$i.'" >PROVISIONAL</a></div>&nbsp;
							<div class="right" id="'.$user->estado.'"><a  class="btn btn-success estado" id="'.$i.'" >DEFINITIVO</a></div>
							<hr/></span></div>';
					}
			$html.=$cabecera.$li;
			$html.='</section>';

	return $html;
	}
  public function showMatriculado($mat,$rol)
	{
      $class='continua';
      $i=$mat->id_alumno;
      if($mat->estado=='continua') $estado='NO CONTINUA'; 
      else {$estado='CONTINUA';}

      $li='<tr>';
      $li.='<td>'.strtoupper($mat->apellidos).'</td>';
      $li.= '<td>'.strtoupper($mat->nombre).'</td>';
      $li.='<td id="tipoalumno'.$i.'">'.strtoupper($mat->tipo_alumno_actual).'</td>';
      $li.= '<td id="estado'.$i.'">'.strtoupper($mat->estado).'</td>';
     
			if($mat->tipo_alumno_actual=='tva')
			{ 
            //if($rol=='admin' || strpos($rol,'sp')!=false)
		      if($rol=='admin' || $rol=='sp')
               $li.= '<td><button type="button" class="btn btn-info cambiar" id="cambiar'.$i.'">CAMBIA A EBO</button></td>';
            $li.= '<td><button type="button" class="btn btn-info continua" id="continua'.$i.'">'.$estado.'</button></td>';
			}
			if($mat->tipo_alumno_actual=='ebo')
			{ 
            //if($rol=='admin' || strpos($rol,'sp')!=false)
		      if($rol=='admin' || $rol=='sp')
               $li.= '<td><button type="button" class="btn btn-info cambiar" id="cambiar'.$i.'">CAMBIA A TVA</button></td>';
            $li.= '<td><button type="button" class="btn btn-info continua" id="continua'.$i.'">'.$estado.'</button></td>';
			}
			$li.='</tr>';
		return $li;
	}
  public function showMatriculados($a,$rol='centro',$id_centro=9999)
	{
	//codigo para mostrar alumnos según tipo de inscripcion
		$tmat='<table id="mat_table'.$id_centro.'" class="table usuario table-striped">
			    <thead class="theadmat">
			      <tr>
				<th>APELLIDOS</th>
				<th>NOMBRE</th>
				<th>TIPO</th>
				<th>ESTADO</th>';
      print($rol);
		if($rol=='admin' || $rol=='sp')
			$tmat.='<th>CAMBIO TIPO</th>';
		$tmat.='<th>CAMBIO CONTINUA</th></tr></thead><tbody>';

		foreach($a as $matricula) 
		{
		$tmat.=$this->showMatriculado($matricula,$rol);    
    }
    $tmat.='</tbody></table>';

	return $tmat;
	}
  
	public function listadoMatriculados($a)
	{
	//codigo para mostrar alumnos según tipo de inscripcion
	$li='<section class="col-lg-12 usuario" style="height:400px;">';
            foreach($a as $user) 
		{
                $i= $user->id_alumno;
                $li.= $user->id_alumno."-".$user->nombre."-";
                $li.= $user->apellido1."-";
                $li.=$user->nacionalidad;
                $li.='<div class="right"><a  class="btn btn-danger continua" id="'.$i.'" >NOCONTINUA</a></div>
                <hr/>';
            	}
        $li.='</section>';

	return $li;
	}
    
  public function showTablaResumenSolicitudesCentros($a,$nombre_centro='')
	{
		$centros=$this->getCentrosIds();	
		foreach($centros as $centro)
		{
		}
	}
  public function showTablaResumenFase2($a,$col=1)
	{
	$tres='<button class="btn" data-toggle="collapse" data-target="#tablafase2" aria-expanded="false"><h2>VACANTES CENTROS</h2></button>';
	#tabla para modo escritorio
	$tres.='<table class="table table-dark table-striped desk collapse" id="tablafase2">
	    <thead>
	      <tr>
		<th class="tf2centro">Centro</th>
		<th >Vacanes EBO</th>
		<th>Vacantes TVA</th>
		<th class="tf2centro">Centro</th>
		<th >Vacanes EBO</th>
		<th>Vacantes TVA</th>
		<th class="tf2centro">Centro</th>
		<th >Vacanes EBO</th>
		<th>Vacantes TVA</th>
	      </tr>
	    </thead>
	    <tbody>';
	$i=0;
	foreach($a as $obj)
	{
		if($i%3==0){ $tres.="<tr>";$j=1;}
		$tres.="<td style='width: 16.66%'>".$obj['nombre_centro']."</td><td id='ebo".$obj['id_centro']."'>".$obj['vacantes_ebo']."</td><td id='tva".$obj['id_centro']."'>".$obj['vacantes_tva']."</td>";
		if($j%3==0) $tres.="</tr>";
		$i++;$j++;
	}
    	$tres.="</tbody> </table></div>";
		
	return $tres;
	}
  public function showTablaResumenSolicitudes($a,$nombre_centro='',$id_centro)
	{
      $tres='<div id="tresumen'.$id_centro.'" class="container tresumensol"><h2>SOLICITUDES <span class="cabcensol" id="cabcensol'.$id_centro.'">'.strtoupper($nombre_centro).'</span></h2>';
      $movil=array();
      if(sizeof($a)>0)
      {
         foreach($a as $key => $row) 
         { 
            foreach($row as $field => $value) 
               $movil[$field][] = $value; 
         }
      }
      $tres.='<table class="table table-dark table-striped mov">
       <thead>
         <tr>
           <th>CENTRO</th>
           <th>NUMERO SOLICITUDES</th>
         </tr>
       </thead>
       <tbody>';
      $i=0;
      $campos=array('Borrador','Validadas','Baremadas');
      foreach($movil as $me)
      {
      if($i==0) {$i++; continue;}
      $tres.="<tr>
            <td>".$campos[$i-1]."</td>
            <td>".$me[0]."</td>
               </tr>";
      $i++;
      }
         $tres.="</tbody> </table>";
      #tabla para modo escritorio
      $tres.='<table class="table table-dark table-striped desk" id="table'.$id_centro.'">
       <thead>
         <tr>
           <th>Centro</th>
           <th>Borrador</th>
           <th>Validadas</th>
           <th>Baremadas</th>
         </tr>
       </thead>
       <tbody>';
       
      foreach($a as $obj)
      $tres.="<tr>
           <td style='width: 16.66%'>".$obj->centro."</td>
           <td>".$obj->borrador."</td>
           <td>".$obj->validada."</td>
           <td>".$obj->baremada."</td>
            </tr>";
      
         $tres.="</tbody> </table></div>";
         
	return $tres;
	}
  public function showTablaResumenMatriculaEspecial($a,$nombre_centro='',$rol='centro',$cabecera='si',$id_centro){
	$display='';
	$tres='<div id="table'.$id_centro.'" class="container tresumenmat"><h2>MATRÍCULA -- <span  class="cabcenmat" id="cabcen'.$id_centro.'">'.strtoupper($nombre_centro).'</span></h2>';
 	$tres.='<table class="table table-dark table-striped desk" id="table'.$id_centro.'">';
  	if($cabecera=='si')
		 $tres.='<thead>
		      <tr>
			<th>Tipo</th>
			<th>Grupos</th>
			<th>Puestos</th>
			<th>Plazas Ocupadas</th>
			<th>Vacantes</th>
		      </tr>
		    </thead>
		    <tbody>';
			else
			 $tres.='<thead>
		      <tr>
			<th>Tipo</th>
			<th>Grupos</th>
			<th>Puestos</th>
			<th>Plazas Ocupadas</th>
			<th>Vacantes</th>
		      </tr>
		    </thead>
		    <tbody>';
 
	foreach($a as $obj)
	{
	if($obj->ta=='') $obj->ta='tva';
	$tres.="<tr>
        <td>".$obj->ta."</td>
        <td>".$obj->grupo."</td>
        <td>".$obj->puestos."</td>
        <td>".$obj->plazasactuales."</td>
        <td id='vacantesmat_".$obj->ta."_desk".$id_centro."'>".$obj->vacantes."</td>
      	</tr>";
	}
    	$tres.="</tbody> </table></div>";
		
	return $tres;
	}
    public function vlistadoMatriculados(){
	//Creamos el objeto alumno
        $alumno=new Alumno($this->conexion);
         
        //Conseguimos todos los alumnos
        $this->allalumnos=$alumno->getAll();
 
        //Cargamos la vista index y le pasamos valores
        $this->view("index",array(
            "allalumnos"=>$this->allalumnos,
	    "listadohtml"=>$this->listadoInscritos($this->allalumnos)
        ));
	
	}
    public function listadoInscritos($a){
	//codigo para mostrar alumnos según tipo de inscripcion
	$li='<section class="col-lg-12 usuario" style="height:400px;">';
            foreach($a as $user) 
		{
                $i= $user->id_alumno;
                $li.= $user->id_alumno."-".$user->nombre."-";
                $li.= $user->apellido1."-";
                $li.=$user->nacionalidad;
                $li.='<div class="right"><a  class="btn btn-danger continua" id="'.$i.'" >NOCONTINUA</a></div>
                <hr/>';
            	}
        $li.='</section>';

	return $li;
	}
 
   public function genCsv($solicitudes,$idcentro=1,$tipo,$cab=array(),$datos=array(),$dir,$log)
	{
         $log->warning("SOLICITUDES TOTALES");
         $log->warning(print_r($solicitudes,true));
      
      $linea=array();
      $nfichero=$dir.'/'.$tipo.'.csv';
      $fp = fopen($nfichero, 'w'); 
      //grabamos cabecera
      fputcsv($fp,$cab,';');
      foreach($solicitudes as $sol)
      {
         $linea=array();
         $sol=(array) $sol;
         foreach($datos as $k)
         {
            $linea[$k]=utf8_decode($sol[$k]);
         }
         $log->warning("LINEA A ESCRIBIR: ");
         $log->warning(print_r($linea,true));
         fputcsv($fp,$linea,';');
      }
      fclose($fp);
   return $tipo.'.csv';
   }

   public function getCentrosNombreVacantesFase2()
   {
		$sql="SELECT id_centro,nombre_centro,vacantes_ebo,vacantes_tva from centros WHERE clase_centro='especial'";
		$r=$this->conexion->query($sql);
		while ($obj = $r->fetch_object()) 
		{
    	   $ares[]=(array)$obj;
    	}				
			return $ares;
	}
   public function getCentrosNombreVacantes($provincia='todas')
   {
	   if($provincia!='todas')
		   $sql="SELECT  id_centro,nombre_centro,vacantes_ebo,vacantes_tva from centros c, centros c where clase_centro='especial' and c.id_centro=cg.id_centro and provincia='$provincia'";
		else
		   $sql="SELECT id_centro,nombre_centro,vacantes_ebo,vacantes_tva from centros WHERE clase_centro='especial'";
		$r=$this->conexion->query($sql);
		while ($obj = $r->fetch_object()) 
		{
    	   $ares[]=(array)$obj;
    	}				
			return $ares;
	}

    public function getCentrosIds($rol='admin',$provincia='todas',$log)
		{
			if($rol=='sp')
				$sql="SELECT distinct(cg.id_centro) from centros_grupos cg, centros c where c.id_centro=cg.id_centro and provincia='$provincia'";
			else
				$sql="SELECT distinct(id_centro) from centros_grupos";
         $log->warning($sql);
			$r=$this->conexion->query($sql);
			while ($obj = $r->fetch_object()) 
    			   $ares[]=$obj;
			return $ares;
		}
    public function getResumenMatriculaCentros($rol,$id_centro=1,$modo='csv',$log,$estado_convocatoria=0,$provincia)
	 {
      $i=0;
      $amatcentros=array();	
      $vacantes_total=array('ebo'=>0,'tva'=>0,'dos'=>0);
      $centros=$this->getCentrosIds($rol,$provincia,$log);
      foreach($centros as $c)
      {
         $centro=new Centro($this->conexion,$c->id_centro,'no');
         $centro->setNombre();
         $matcentros=$centro->getDatosMatriculaCentro($log);
         $amatcentros[$i]['nombre_centro']=str_replace(',','',$centro->getNombre());
         $amatcentros[$i]['gruposebo']=$matcentros['gruposebo'];
         $amatcentros[$i]['puestosebo']=$matcentros['plazasebo'];
         $amatcentros[$i]['matriculaactualebo']=$matcentros['matriculaactualebo'];
         $amatcentros[$i]['vacantesebo']=$matcentros['plazasebo']-$matcentros['matriculaactualebo'];
         
         $amatcentros[$i]['solicitudesebo']=$centro->getSolicitudes($c->id_centro,'ebo',$log,$estado_convocatoria);
         
         $amatcentros[$i]['grupostva']=$matcentros['grupostva'];
         $amatcentros[$i]['puestostva']=$matcentros['plazastva'];
         $amatcentros[$i]['matriculaactualtva']=$matcentros['matriculaactualtva'];
         $amatcentros[$i]['vacantestva']=$matcentros['plazastva']-$matcentros['matriculaactualtva'];
         
         $amatcentros[$i]['solicitudestva']=$centro->getSolicitudes($c->id_centro,'tva',$log,$estado_convocatoria);
         
         $amatcentros[$i]['gruposdos']=$matcentros['gruposdos'];
         $amatcentros[$i]['puestosdos']=$matcentros['plazasdos'];
         $amatcentros[$i]['matriculaactualdos']=$matcentros['matriculaactualdos'];
         $amatcentros[$i]['vacantesdos']=$matcentros['plazasdos']-$matcentros['matriculaactualdos'];
         
         $amatcentros[$i]['solicitudesdos']=$centro->getSolicitudes($c->id_centro,'dos',$log,$estado_convocatoria);
         
         $vacantes_total['ebo']=$vacantes_total['ebo']+$amatcentros[$i]['vacantesebo'];
         $vacantes_total['tva']=$vacantes_total['tva']+$amatcentros[$i]['vacantestva'];
         $vacantes_total['dos']=$vacantes_total['dos']+$amatcentros[$i]['vacantesdos'];
         
         $i++;
         if($c->id_centro==22002338)
         {
            $log->warning("OBTENIENDO MATRICULA DE  CENTROS CONVOCATORIA: $estado_convocatoria");
            $log->warning(print_r($matcentros,true));
         }
      }
      if($rol=='admin') return $vacantes_total;
      return $amatcentros;
	}
    public function getUsuarios($rol,$id_centro,$log,$provincia)
	 {
         $log->warning("OBTENIENDO USUARIOS ID_CENTRO ROL: ".$rol);
			$i=0;
			$ausuarioscentros=array();	
				$centros=$this->getCentrosIds($rol,$provincia,$log);
				foreach($centros as $c)
				{
					if($c->id_centro<=1) continue;
               if($rol=='centro' and $c->id_centro!=$id_centro) continue;
					$centro=new Centro($this->conexion,$c->id_centro,'no');
					$centro->setNombre();
					$usucentros=$centro->getUsuariosCentro('centro',$c->id_centro,$log);
               
					foreach($usucentros as $u)
					{
						$ausuarioscentros[$i]['nombre_centro']=str_replace(',','',$centro->getNombre());
						$ausuarioscentros[$i]['alumno']=$u->nombre;
						//$ausuarioscentros[$i]['telefono']=$u->tel_dfamiliar1;
						$ausuarioscentros[$i]['token']='https://preadmespecial.aragon.es/educacionespecial2223/index.php?token='.$u->token;
						$i++;
					}
				}
			return $ausuarioscentros;
		
		}
    public function listado(){
        //Creamos el objeto centro
        $centro=new Centro($this->conexion);
        
	//obtenemos resumen
	$resumencentros=$centro->getResumen();
        
	//Creamos el objeto usuario
        $alumno=new Alumno($this->conexion);
         
        //Conseguimos todos los usuarios
        $allalumnos=$alumno->getAll();
 
        //Cargamos la vista index y le pasamos valores
        $this->view("listado",array(
            "allalumnos"=>$allalumnos,
            "resumencentros"=>$resumencentros
        ));
    }
    public function index(){
        //Creamos el objeto centro
        $centro=new Centro($this->conexion);
        
        //obtenemos resumen
	$resumencentros=$centro->getResumen();
        
	//Creamos el objeto alumno
        $alumno=new Alumno($this->conexion);
         
        //Conseguimos todos los alumnos
        $allalumnos=$alumno->getAll();
 
        //Cargamos la vista index y le pasamos valores
        $this->view("index",array(
            "allalumnos"=>$allalumnos,
            "resumencentros"=>$resumencentros
        ));
    }
     
     
}
?>
