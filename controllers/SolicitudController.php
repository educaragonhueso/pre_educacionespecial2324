<?php
class SolicitudController{
    public $conectar;
    public $lastid;
    public $datos_solicitud;
 
    public function __construct($rol='centro',$conexion,$formulario,$estado_convocatoria,$log) 
	 {
        $this->conectar=$conexion;
        $this->formulario=$formulario;
        $this->rol=$rol;
        $this->estado_convocatoria=$estado_convocatoria;
        $this->log=$log;
    }
 
   function convertirFecha($f)
   {
      $fecha=explode(' ',$f)[0];
      $año=explode('/',$fecha)[0];
      $mes=explode('/',$fecha)[1];
      $dia=explode('/',$fecha)[2];
     
      $hora=explode(' ',$f)[1];

      $sf=$dia." del ".$mes." a las ".$hora." h";
      return $sf;
   }
   public function getEstadoAlumno($token)
	{
         $centro_alumno=$this->getCentroAdmisionAlumno($token);
         if($this->estado_convocatoria==ESTADO_PUBLICACION_BAREMADAS)
            $msg= "<div class='cajainfo'>PUBLICADAS LISTADAS BAREMADAS, CONSÚLTALAS EN EL ENLACE SUPERIOR DERECHO O <a class='lbaremadas' data-subtipo='sor_bar' style='color:darkblue;background-color:black;padding:6px'>EN ESTE ENLACE</a></div>";
         if($this->estado_convocatoria==ESTADO_RECLAMACIONES_BAREMADAS)
            $msg= "<div class='cajainfo'>PUEDES HACER TU RECLAMACIÓN DESDE EL ENLACE SUPERIOR DERECHO O <a href='".URL_BASE.EDICION."/reclamaciones_baremo.php?token=$token' style='color:darkblue;background-color:black;padding:6px'> DESDE ESTE ENLACE</a></div>";
         if($this->estado_convocatoria==ESTADO_ALEATORIO)
            $msg= "<div class='cajainfo'>PUBLICADOS LISTADOS DE NÚMERO ALEATORIO, CONSÚLTALO EN EL ENLACE SUPERIOR DERECHO O <a class='lbaremadas' data-subtipo='sor_ale' style='color:darkblue;background-color:black;padding:6px'>EN ESTE ENLACE</a><p><i style='font-size:15px'>Este número junto con el número de sorteo permite generar un orden de solicitudes en caso de empate</i></p></div>";
         if($this->estado_convocatoria==ESTADO_SORTEO)
            $msg= "<div class='cajainfo'>SE HA REALIZADO EL SORTEO, LOS LISTADOS PROVISIONALES SE PUBLICARÁN EL ".$this->convertirFecha(DIA_PUBLICACION_PROVISIONAL)."</div>";
         if($this->estado_convocatoria==ESTADO_PUBLICACION_PROVISIONAL)
            //$msg= "<div class='cajainfo'>PUBLICADAS LISTAS PROVISIONALES, PUEDES CONSULTARLAS DESDE EL ENLACE SUPERIOR DERECHO. EL PERIODO DE RECLAMACIONES ES DESDE EL ".$this->convertirFecha(DIA_INICIO_RECLAMACIONES_PROVISIONAL)." HASTA EL ".$this->convertirFecha(DIA_FIN_RECLAMACIONES_PROVISIONAL)."<br></div><div style='padding:10px;margin-left:40%'> PUEDES RECLAMAR DESDE ESTE ENLACE <a href='https://preadmespecial.aragon.es/educacionespecial2324/reclamaciones_provisional.php?token=$token' style='color:darkblue;background-color:black;padding:6px'> DESDE ESTE ENLACE</a></div>";
            $msg= "<div class='cajainfo'>PUBLICADAS LISTAS PROVISIONALES, PUEDES CONSULTARLAS DESDE EL ENLACE SUPERIOR DERECHO. EL PERIODO DE RECLAMACIONES ES DESDE EL ".$this->convertirFecha(DIA_INICIO_RECLAMACIONES_PROVISIONAL)." HASTA EL ".$this->convertirFecha(DIA_FIN_RECLAMACIONES_PROVISIONAL)."<br></div>";
         if($this->estado_convocatoria==ESTADO_RECLAMACIONES_PROVISIONAL)
            $msg.= "<div style='padding:10px;margin-left:40%'> PUEDES RECLAMAR DESDE ESTE ENLACE <a href='".URL_BASE.EDICION."/reclamaciones_provisional.php?token=$token' style='color:darkblue;background-color:black;padding:6px'> DESDE ESTE ENLACE</a></div>";
         if($this->estado_convocatoria==ESTADO_PUBLICACION_DEFINITIVOS)
            $msg= "<div class='cajainfo'>PUBLICADOS LISTADOS DEFINITIVOS, PUEDES CONSULTARLOS DESDE EL ENLACE SUPERIOR DERECHO.</div>";
         if($this->estado_convocatoria>=ESTADO_PUBLICACION_ASIGNACIONES)
         {
            $msg= "<div class='cajainfo'>";
            $msg.= "TU SOLICITUD HA SIDO ADMITIDA EN EL CENTRO:<b> $centro_alumno</b> </div>";
         }
	   return $msg;
   }
   public function getCentroAdmisionAlumno($token)
	{
	   $sql="SELECT c.nombre_centro FROM alumnos a, centros c WHERE a.id_centro_final=c.id_centro AND a.token='$token'";
 		$query=$this->getConexion()->query($sql);
		if($query)
    	   return $query->fetch_object()->nombre_centro;
		else return 0;
	}
    public function getIdAlumnoPin($pin)
		{
			$sql="select a.id_alumno from usuarios u, alumnos a where a.id_usuario=u.id_usuario and u.clave_original=$pin";
 			$query=$this->getConexion()->query($sql);
				if($query)
    	  return $query->fetch_object()->id_alumno;
			else return 0;
		}
    public function comprobarSolicitud($dni_tutor,$nombre_alumno,$apellido1)
		{
        	$solicitud=new Solicitud($this->adapter);
        	$dsolicitud=$solicitud->compSol($dni_tutor,$nombre_alumno,$apellido1);
		return $dsolicitud;
		}
    public function getIdSolicitud($id)
		{
		$sql="SELECT concat(a.id_alumno,a.id_centro_destino) as id_solicitud  from alumnos a where a.id_alumno=$id";
 		$query=$this->getConexion()->query($sql);
		if($query)
	    		return $query->fetch_object()->id_solicitud;
		else return 0;
		}

    public function imprimirSolicitud($id)
    {
      return $this->showFormSolicitud($id,$id_centro=0,$this->rol,$collapsed=0,$imprimir=1,$this->conectar,'dirbase',$this->log,0);
	 }
    public function aplicarComprobaciones($id,$dsolicitud,$log)
    {
      $acom=$this->getComprobaciones($id);
      $log->warning("COMPROBANDO COMPROBACIONES");
      $log->warning(print_r($acom,true));
      //comprobamos el estado de la renta, 
      if($dsolicitud['baremo_renta_inferior']==1)
      {
         $soriginal='<label id="msg_comprobacion_renta_inferior" class="botonform" style="display:none;color:red">Estado: PENDIENTE DE COMPROBAR</label>';
         if($acom["comprobar_renta_inferior"]==0)
            $sdestino='<label id="msg_comprobacion_renta_inferior" class="botonform" style="display:block;color:red">Estado: PENDIENTE DE COMPROBAR</label>';
         elseif($acom["comprobar_renta_inferior"]==1)
             $sdestino='<label id="msg_comprobacion_renta_inferior" class="botonform" style="color:red; display:block;">Estado: COMPROBACIÓN NEGATIVA</label>';
         else
            $sdestino='<label id="msg_comprobacion_renta_inferior" class="botonform" style="color:green;display: block;">Estado: COMPROBACIÓN POSITIVA</label>';
         $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
      }
      if($dsolicitud['baremo_discapacidad_alumno']==1)
      {
         $soriginal='<label id="msg_comprobacion_discapacidad_alumno" class="botonform" style="display:none;color:red;width:16%">Estado: PENDIENTE DE COMPROBAR</label>';
         if($acom["comprobar_discapacidad_alumno"]==0)
            $sdestino='<label id="msg_comprobacion_discapacidad_alumno" class="botonform" style="display:block;color:red;width:16%">Estado: PENDIENTE DE COMPROBAR</label>';
         elseif($acom["comprobar_discapacidad_alumno"]==1)
            $sdestino='<label id="msg_comprobacion_discapacidad_alumno" class="botonform" style="color:red; display:block;width:16%">Estado: COMPROBACIÓN NEGATIVA</label>';
         else
            $sdestino='<label id="msg_comprobacion_discapacidad_alumno" class="botonform" style="color:green;display: block;width:16%">Estado: COMPROBACIÓN POSITIVA</label>';
         $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
      }
      if($dsolicitud['baremo_discapacidad_hermanos']==1)
      {
         $soriginal='<label id="msg_comprobacion_discapacidad_hermanos" class="botonform" style="display:none;color:red;width:16%">Estado: PENDIENTE DE COMPROBAR</label>';
         if($acom["comprobar_discapacidad_hermanos"]==0)
            $sdestino='<label id="msg_comprobacion_discapacidad_hermanos" class="botonform" style="display:block;color:red;width:16%">Estado: PENDIENTE DE COMPROBAR</label>';
         elseif($acom["comprobar_discapacidad_hermanos"]==1)
            $sdestino='<label id="msg_comprobacion_discapacidad_hermanos" class="botonform" style="color:red; display:block;width:16%">Estado: COMPROBACIÓN NEGATIVA</label>';
         else
            $sdestino='<label id="msg_comprobacion_discapacidad_hermanos" class="botonform" style="color:green;display: block;width:16%">Estado: COMPROBACIÓN POSITIVA</label>';
         $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
      }
      if($dsolicitud['baremo_marcado_numerosa']==1)
      {
         $soriginal='<label id="msg_comprobacion_familia_numerosa" class="botonform" style="display:none;color:red;width:50%">Estado: PENDIENTE DE COMPROBAR</label>';
         if($acom["comprobar_familia_numerosa"]==0)
            $sdestino='<label id="msg_comprobacion_familia_numerosa" class="botonform" style="display:block;color:red;width:50%">Estado: PENDIENTE DE COMPROBAR</label>';
         elseif($acom["comprobar_familia_numerosa"]==1)
            $sdestino='<label id="msg_comprobacion_familia_numerosa" class="botonform" style="color:red; display:block;width:50%">Estado: COMPROBACIÓN NEGATIVA</label>';
         else
            $sdestino='<label id="msg_comprobacion_familia_numerosa" class="botonform" style="color:green;display: block;width:50%">Estado: COMPROBACIÓN POSITIVA</label>';
         $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
      }
      if($dsolicitud['baremo_marcado_monoparental']==1)
      {
         $soriginal='<label id="msg_comprobacion_familia_monoparental" class="botonform" style="display:none;color:red;width:55%">Estado: PENDIENTE DE COMPROBAR</label>';
         if($acom["comprobar_familia_monoparental"]==0)
            $sdestino='<label id="msg_comprobacion_familia_monoparental" class="botonform" style="display:block;color:red;width:55%">Estado: PENDIENTE DE COMPROBAR</label>';
         elseif($acom["comprobar_familia_monoparental"]==1)
            $sdestino='<label id="msg_comprobacion_familia_monoparental" class="botonform" style="color:red; display:block;width:55%">Estado: COMPROBACIÓN NEGATIVA</label>';
         else
            $sdestino='<label id="msg_comprobacion_familia_monoparental" class="botonform" style="color:green;display: block;width:55%">Estado: COMPROBACIÓN POSITIVA</label>';
         $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
      }
      
    }
    public function procesarFormularioExistente($id,$dsolicitud,$collapsed=1,$rol,$imprimir=0,$dirbase,$log,$solo_lectura)
	 {
      //aplicamos comprobaciones para mostrar en el formulario, solo para centros y admin
      if($rol!='alumno')
         $this->aplicarComprobaciones($id,$dsolicitud,$log);

      //añadimo sidentificador al formulario completo
      $original='id="filasolicitud"';
      $destino='id="filasolicitud'.$id.'"';
      $this->formulario=str_replace($original,$destino,$this->formulario);
    
      //deshabilitamos el bootn de duplicada para centros y alumnos 
      if($rol=='alumno' or $rol=='anonimo' or $rol=='centro')
      {
         $original='<input type="radio" name="estado_solicitud" value="duplicada">';
         $destino='<input type="radio" name="estado_solicitud" value="duplicada" disabled>';
         $this->formulario=str_replace($original,$destino,$this->formulario);
         //deshabilitamos el bootn de duplicada para centros y alumnos 
         $original='<input type="radio" name="transporte"';
         $destino='<input disabled type="radio" name="transporte" ';
         //$this->formulario=str_replace($original,$destino,$this->formulario);
      }
      //si es para solo lectura desactivamos controles de entrada
      if($solo_lectura==1) 
      {
         //$this->formulario=preg_replace('/<p type="button" class="btn btn-primary bform" id="labeldocumentos".*<\/p>/','',$this->formulario);
         $this->formulario=str_replace('<div id="cdocumentos" class="collapse">','<div id="cdocumentos" class="collapse show" style="display:none">',$this->formulario);
         
         $this->formulario=preg_replace('/<button name="boton_baremo_comprobar_padron" type="button" class="btn btn-outline-dark comprobar">Comprobar domicilio<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton_baremo_comprobar_discapacidad" type="button" class="btn btn-outline-dark comprobar">Comprobar discapacidad<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton_baremo_comprobar_familianumerosa" type="button" class="btn btn-outline-dark comprobar">Comprobar familia numerosa<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton_comprobar_identidad" type="button" class="btn btn-outline-dark comprobar">Comprobar identidad<\/button>/','',$this->formulario);
         #bloqueamos los campos de formulario
         $this->formulario=str_replace('input','input disabled',$this->formulario);
         $this->formulario=str_replace('input disabled type="hidden"','input type="hidden"',$this->formulario);
         $this->formulario=str_replace('select','select disabled',$this->formulario);
         if($rol=='centro' or $rol=='sp' or $rol=='admin')
         { 
            #bloqueamos los campos de formulario de estado de solicitud
            $this->formulario=str_replace('input disabled type="radio" name="fase_solicitud" value="validada"','input type="radio" name="fase_solicitud" value="validada"',$this->formulario);
            $this->formulario=str_replace('input disabled type="radio" name="fase_solicitud" value="baremada"','input type="radio" name="fase_solicitud" value="baremada"',$this->formulario);
            
            $this->formulario=str_replace('input disabled type="radio" name="estado_solicitud" value="irregular"','input type="radio" name="estado_solicitud" value="irregular"',$this->formulario);
            $this->formulario=str_replace('input disabled type="radio" name="estado_solicitud" value="apta"','input type="radio" name="estado_solicitud" value="apta"',$this->formulario);
            if($rol=='sp' or $rol=='admin')
               $this->formulario=str_replace('input disabled type="radio" name="transporte"','input type="radio" name="transporte"',$this->formulario);
         }
         #bloqueamos los botones de agregar doc
         //$this->formulario=preg_replace('/<a id="enlacefj.*\/a>/','',$this->formulario);
         $this->formulario=preg_replace('/<a id="borrar.*\/a>/','',$this->formulario);
         $this->formulario=preg_replace('/<label for="fbaremo.*<\/label>/','',$this->formulario);
      }
      //si es para imprimir
      if($imprimir==1 and $rol!='admin' and $rol!='sp') 
      {
         //$this->formulario=preg_replace('/<p type="button" class="btn btn-primary bform" id="labeldocumentos".*<\/p>/','',$this->formulario);
         $this->formulario=str_replace('<div id="cdocumentos" class="collapse">','<div id="cdocumentos" class="collapse show" style="display:none">',$this->formulario);
         
         $this->formulario=preg_replace('/<button name="boton_baremo_comprobar_padron" type="button" class="btn btn-outline-dark comprobar">Comprobar domicilio<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton_baremo_comprobar_discapacidad" type="button" class="btn btn-outline-dark comprobar">Comprobar discapacidad<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton_baremo_comprobar_familianumerosa" type="button" class="btn btn-outline-dark comprobar">Comprobar familia numerosa<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton_comprobar_identidad" type="button" class="btn btn-outline-dark comprobar">Comprobar identidad<\/button>/','',$this->formulario);
         //mantemos la parte de stado para el rol de servicio provincial y admin
         if($rol=='alumno' or $rol=='anonimo')
         { 
            $this->formulario=preg_replace('/<!--INICIO SECCION ESTADO.*<!--FIN SECCION ESTADO-->/sU','',$this->formulario);
            $this->formulario=preg_replace('/<!--CRITERIOS.*<!--FIN CRITERIOS-->/sU','',$this->formulario);
         }
         //ocultamos botones de actualizar y grabar
         $this->formulario=str_replace('<a class="btn btn-primary send" >GRABAR SOLICITUD</a>','',$this->formulario);
         $this->formulario=str_replace('<div class="row operacionesformulario">','<div class="row operacionesformulario" style="display:none">',$this->formulario);
         #bloqueamos los campos de formulario
         $this->formulario=str_replace('input','input disabled',$this->formulario);
         $this->formulario=str_replace('select','select disabled',$this->formulario);
         if($rol=='centro')
         { 
         #bloqueamos los campos de formulario
            $this->formulario=str_replace('input disabled type="radio" name="fase_solicitud"','input type="radio" name="fase_solicitud" ',$this->formulario);
         }
         #bloqueamos los botones de agregar doc
         //$this->formulario=preg_replace('/<a id="enlacefj.*\/a>/','',$this->formulario);
         $this->formulario=preg_replace('/<a id="borrar.*\/a>/','',$this->formulario);
         $this->formulario=preg_replace('/<label for="fbaremo.*<\/label>/','',$this->formulario);
      }
      //si collapsed es 0 lo mostramos desplegado
      if($collapsed==0) 
      {
         $this->formulario=str_replace('data-toggle="collapse"','data-toggle="collapse" aria-expanded="true"',$this->formulario);
         $this->formulario=str_replace('class="collapse"','class="collapse show"',$this->formulario);
      }
      //Ocultamos boton de validacion y comprobacion para alumnos
      if($rol=='alumno' or $rol=='anonimo')
      {
         $this->formulario=preg_replace('/<button name="boton.* class="btn btn-outline-dark validar".*<\/button>/','',$this->formulario);
         $this->formulario=preg_replace('/<button name="boton.* class="btn btn-outline-dark comprobar".*<\/button>/','',$this->formulario);
      }
      //comprobamos si los pts validados y de baremo son iguales para poner en verde el titulo
      if($dsolicitud['baremo_puntos_totales']==$dsolicitud['baremo_puntos_validados'])
      {
         $this->formulario=str_replace('class="btn btn-primary bform crojo"','class="btn btn-primary bform cverde"',$this->formulario);
      }		
     
      if($rol=='alumno' and $this->estado_convocatoria>=ESTADO_INSCRIPCION) 
      {  
         $origen='<a class="btn btn-primary send" >GRABAR SOLICITUD</a>';
         if($this->estado_convocatoria==ESTADO_INSCRIPCION)
            $destino='<a class="btn btn-primary send" >ACTUALIZAR SOLICITUD</a>';
         else
            $destino='';
         $this->formulario=str_replace($origen,$destino,$this->formulario);
      }
		$this->formulario=str_replace('GRABAR','ACTUALIZAR',$this->formulario);
   
      $token=$dsolicitud['token'];
      foreach($dsolicitud as $skey=>$sval)
      {
         //añadimos el valor del token
         if($skey=='token') 
         {
            $origen='<a class="btn btn-primary send"';
            $destino='<a class="btn btn-primary send" id="'.$token.'"';
            
            $this->formulario=str_replace($origen,$destino,$this->formulario);
            continue;
         }
         //calculo escolariazacion
         if($skey=='nuevaesc') 
         {
            if($sval==1)
               $this->formulario=str_replace('id="nuevaesc"','id="nuevaesc" checked="checked" ',$this->formulario);
            else
            {
               $this->formulario=str_replace('id="renesc"','id="renesc" checked="checked" ',$this->formulario);
               $this->formulario=str_replace('class="filarenesc"','class="filarenesc" style="display:block"',$this->formulario);
               //$this->formulario=str_replace('class="row filanuevaesc','class="row filanuevaesc" style="display:none!important"',$this->formulario);
            }
            continue;
         }
         if($skey=='num_hadmision') 
         {
            if($sval==0) $check="";
            else $check="checked";
            $this->formulario=str_replace('value="0" name="num_hadmision"','value="'.$sval.'" name="num_hadmision" '.$check,$this->formulario);
            if($check=="checked")
            {
               $this->formulario=str_replace('id="thermanosadmision" style="display:none"','id="thermanosadmision" style=""',$this->formulario);
            }
            continue;
         }
         //Para el caso de campos de opciones
         if($skey=='modalidad_origen')
         {
            //$this->formulario=str_replace('id="'.$skey.'"','id="'.$skey.$id.'"',$this->formulario);
            $this->formulario=str_replace('option value="'.$sval.'"','option value="'.$sval.'" selected',$this->formulario);
            continue;
         }
         //CAMPOS DE SOLICITA
         if($skey=='hermanos_admision_tipoestudios1')
         {
            $origen='class="hamo1" value="'.$sval.'"';
            $destino='class="hamo1" selected value="'.$sval.'"';
            $this->formulario=str_replace($origen,$destino,$this->formulario);
            continue;
         }
         if($skey=='hermanos_admision_tipoestudios2')
         {
            $origen='class="hamo2" value="'.$sval.'"';
            $destino='selected value="'.$sval.'"';
            //$destino="<option selected>$val</option>";
            $this->formulario=str_replace($origen,$destino,$this->formulario);
            continue;
         }
         if($skey=='hermanos_admision_tipoestudios3')
         {
            $origen='class="hamo3" value="'.$sval.'"';
            $destino='selected value="'.$sval.'"';
            $this->formulario=str_replace($origen,$destino,$this->formulario);
            continue;
         }
         if($skey=='hermanos_admision_reserva1' or $skey=='hermanos_admision_reserva2' or $skey=='hermanos_admision_reserva3' )
         {
            $indice=substr($skey, -1);
            $origen='class="resh'.$indice.'" value="'.$sval.'"';
            $destino='selected value="'.$sval.'"';
            $this->formulario=str_replace($origen,$destino,$this->formulario);
            continue;
         }
         if($skey=='tipoestudios')
         {	
            $val=strtolower($sval);
            $origen='class="hamo" value="'.$val.'">';
            $destino='selected class="hamo" value="'.$val.'">';
            $this->formulario=str_replace($origen,$destino,$this->formulario);
            continue;
         }
         //CAMPOS DE BAREMACION
         if($skey=='baremo_marcado_proximidad_domicilio') 
         {
            if($sval==1)
            { 
               $origen='<div id="cajabaremo_domicilio"';
               $destino='<div id="cajabaremo_domicilio" style="display:block"';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
            }
         }
         if($skey=='baremo_marcado_discapacidad') 
         {
            if($sval==1)
            { 
               $origen='<div id="cajabaremo_discapacidad"';
               $destino='<div id="cajabaremo_discapacidad" style="display:block"';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
            }
         }
         if($skey=='baremo_marcado_numerosa') 
         {
            if($sval==1)
            { 
               $origen='<div id="cajabaremo_numerosa"';
               $destino='<div id="cajabaremo_numerosa" style="display:block"';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
            }
         }
         if($skey=='baremo_marcado_monoparental') 
         {
            if($sval==1)
            { 
               $origen='<div id="cajabaremo_monoparental"';
               $destino='<div id="cajabaremo_monoparental" style="display:block"';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
            }
         }
         if($skey=='transporte') 
         {
               $origen='name="transporte" value="'.$sval.'"';
               $destino='name="transporte" value="'.$sval.'" checked ';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
         }
         //calculo puntos baremo
         if($skey=='baremo_puntos_totales' or $skey=='baremo_puntos_validados') 
         {
            if($skey=='baremo_puntos_validados')
            {	
               $origen='<span id="id_puntos_baremo_validados">0</span>';
               $destino='<span id="id_puntos_baremo_validados">'.$sval.'</span>';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
            }
            if($skey=='baremo_puntos_totales')
            {	
               $origen='<span id="id_puntos_baremo_totales">0</span>';
               $destino='<span id="id_puntos_baremo_totales">'.$sval.'</span>';
               $this->formulario=str_replace($origen,$destino,$this->formulario);
            }
            $this->formulario=str_replace('name="'.$skey.'" value="0"','name="'.$skey.'", value="'.$sval.'"'.$check,$this->formulario);
            continue;
         }
         //calculo hermanos baremo
         if($skey=='num_hbaremo') 
         {
            if($sval==0) $check="";
            else $check="checked";
            $this->formulario=str_replace('name="num_hbaremo" value="1"','name="num_hbaremo" value="1" '.$check,$this->formulario);
            if($check=="checked")
               $this->formulario=str_replace('class="row hno_baremo"','class="row hno_baremo" style="display:flex!important"',$this->formulario);
            continue;
         }
         if($skey=='hermanos_modalidad_baremo1' or $skey=='hermanos_modalidad_baremo2' or $skey=='hermanos_modalidad_baremo3') 
         {
            $indice=substr($skey,-1);
            $this->formulario=str_replace('class="hb'.$indice.'" value="'.$sval.'"','class="hb'.$indice.'" value="'.$sval.'" selected',$this->formulario);
            continue;
         }
         if($skey=='hermanos_nivel_baremo1' or $skey=='hermanos_nivel_baremo2' or $skey=='hermanos_nivel_baremo3') 
         {
            $indice=substr($skey,-1);
            $this->formulario=str_replace('class="hbn'.$indice.'" value="'.$sval.'"','class="hbn'.$indice.'" value="'.$sval.'" selected',$this->formulario);
            continue;
         }
         //controles de formulario tipo radio
         if($skey=='baremo_proximidad_domicilio' or $skey=='baremo_discapacidad' or $skey=='baremo_tipo_familia_monoparental' or $skey=='baremo_tipo_familia_numerosa' or $skey=='transporte' or $skey=='fase_solicitud' or $skey=='estado_solicitud' or $skey=='reserva' or $skey=='conjunta')
         {
            $check="";
            //if($sval>0 or $sval=='dfamiliar' or $sval=='dlaboral' or $sval=='dllimitrofe' or $sval=='dflimitrofe')
            if($sval!='0')
            {
               $check="checked";
               $this->formulario=str_replace('name="'.$skey.'" value="'.$sval.'"','name="'.$skey.'" value="'.$sval.'" '.$check,$this->formulario);
            }
            //else
              // $this->formulario=str_replace('name="'.$skey.'" value="'.$sval.'"','name="'.$skey.'" value="'.$sval.'"',$this->formulario);
            //$this->formulario=str_replace('name="'.$skey.'"','name="'.$skey.'"',$this->formulario);
            if($sval=='dllimitrofe')
               $this->formulario=str_replace('id="calle_dllimitrofe" class="md-form mb-0" style="display:none"','id="calle_dllimitrofe" class="md-form mb-0" style="display:block"',$this->formulario);
            if($sval=='dlaboral')
               $this->formulario=str_replace('id="calle_dlaboral" class="md-form mb-0" style="display:none"','id="calle_dlaboral" class="md-form mb-0" style="display:block"',$this->formulario);
            if($sval=='hpadres')
               $this->formulario=str_replace('id="cajadatosdiscapacidad" class="row" style="display:none"','id="cajadatosdiscapacidad" style="display:block"',$this->formulario);
            if($skey=='reserva' and strrpos($dsolicitud['id_centro_estudios_origen'],'*')!==FALSE)	
            {
               $this->formulario=str_replace('class="row freserva" style="display:none"','class="row freserva"',$this->formulario);
               $check="checked";
               $this->formulario=str_replace('name="'.$skey.'" value="'.$sval.'"','name="'.$skey.'" value="'.$sval.'" '.$check,$this->formulario);
            }
            if($skey=='conjunta' and $sval=='si')
            {
               $this->formulario=str_replace('name="'.$skey.'" value="'.$sval.'"','name="'.$skey.'" value="'.$sval.'" checked="checked"',$this->formulario);
               $this->formulario=str_replace('class="bloque_hermanos_admision"','class="bloque_hermanos_admision" style="display:block"',$this->formulario);
            }
            continue;
         }
         if($skey=='baremo_calle_dlaboral' or $skey=='baremo_calle_dllimitrofe' or $skey=='baremo_nombreapellidosdisc' or $skey=='baremo_fnacdisc' or $skey=='baremo_dnidisc' )
         {
            $this->formulario=str_replace('name="'.$skey.'"','name="'.$skey.'" value="'.$sval.'"',$this->formulario);
            $this->formulario=str_replace('id="'.$skey.'"','id="'.$skey.'"',$this->formulario);
            continue;
         }
         //controles de validacion de baremo tipo radio
         if($skey=='baremo_validar_proximidad_domicilio' or $skey=='baremo_validar_discapacidad_hermanos' or $skey=='baremo_validar_discapacidad_alumno' or $skey=='baremo_validar_tipo_familia_numerosa' or $skey=='baremo_validar_tipo_familia_monoparental' or $skey=='baremo_validar_situacion_sobrevenida')
         {
               $soriginal='<input type="hidden" id="'.$skey.'" value="0" name="'.$skey.'">';
               $sdestino='<input type="hidden" id="'.$skey.'" value="'.$sval.'" name="'.$skey.'">';
               $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
            if($sval==1)
            {
                  $tval=end(explode('_',$skey));
                  $tval=str_replace("baremo_validar_","",$skey);
                  $tval=str_replace("_"," ",$tval);
                  $soriginal='<button name="boton_'.$skey.'" type="button" class="btn btn-outline-dark validar">Validar';
                  $sdestino='<button name="boton_'.$skey.'" type="button" class="btn btn-outline-dark validar">Invalidar';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
            }
            continue;
         }
         //controles de validacion de baremo tipo check
         if($skey=='baremo_validar_tutores_centro' or $skey=='baremo_validar_situacion_sobrevenida' or $skey=='baremo_validar_renta_inferior' or $skey=='baremo_validar_hnos_centro' or $skey=='baremo_validar_genero' or $skey=='baremo_validar_terrorismo' or $skey=='baremo_validar_parto' or $skey=='baremo_validar_acogimiento')
         {
            $soriginal='<input type="hidden" id="'.$skey.'" value="0" name="'.$skey.'">';
            $sdestino='<input type="hidden" id="'.$skey.'" value="'.$sval.'" name="'.$skey.'">';
            $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
            if($sval==1)
            {
               if(strpos('tutores',$skey)!==FALSE) $cval="tutores trabajan centro";	
               if(strpos('renta',$skey)!==FALSE) $cval="renta";	
               if(strpos('hnos',$skey)!==FALSE) $cval="hermanos";
               if(strpos('acogimiento',$skey)!==FALSE) $cval="acogimiento";
               if(strpos('genero',$skey)!==FALSE) $cval="genero";
               if(strpos('terrorismo',$skey)!==FALSE) $cval="terrorismo";

               $soriginal='<button name="boton_'.$skey.'" type="button" class="btn btn-outline-dark validar">Validar';
               $sdestino='<button name="boton_'.$skey.'" type="button" class="btn btn-outline-dark validar">Invalidar';
               $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
            }
            //botones de validacion de baremo
            $origen='<button name="boton_'.$skey.'" type="button" class="btn btn-outline-dark validar">';
            $destino='<button name="boton_'.$skey.'" type="button" class="btn btn-outline-dark validar">';
            $this->formulario=str_replace($origen,$destino,$this->formulario);
               continue;
         }
         if($skey=='baremo_tutores_centro' or $skey=='baremo_situacion_sobrevenida' or $skey=='baremo_renta_inferior' or $skey=='oponenautorizar' or $skey=='cumplen' or $skey=='baremo_acogimiento' or $skey=='baremo_genero' or $skey=='baremo_terrorismo' or $skey=='baremo_marcado_proximidad_domicilio' or $skey=='baremo_marcado_discapacidad' or $skey=='baremo_marcado_monoparental' or $skey=='baremo_marcado_numerosa' or $skey=='baremo_discapacidad_alumno' or $skey=='baremo_discapacidad_hermanos' or $skey=='baremo_numerosa' or $skey=='baremo_parto' or $skey=='baremo_monoparental')
         {
            if($sval==0) $check="";
            else
               $check="checked";
            if($skey=='baremo_discapacidad_hermanos')
            {
               if($sval==1)
               {
                  $soriginal='<div id="cajadatosdiscapacidad" style="display:none">';
                  $sdestino='<div id="cajadatosdiscapacidad" style="display:block">';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }
            }
            //para el caso de q haya marcado la proximidad de domicilio vemos si hay fichero justificativo
            if($skey=='baremo_marcado_proximidad_domicilio')
            {
               $enlacefichero1="scripts/fetch/ficherosbaremo/".$token."/fbaremo_domicilio.pdf";
               $enlacefichero2=$dirbase."/scripts/fetch/ficherosbaremo/".$token."/fbaremo_domicilio.pdf";
               if(file_exists($enlacefichero1))
               {
                  if($imprimir==1)
                  {
                     $soriginal='id="enlacefjdomicilio" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero';
                     $sdestino='id="enlacefjdomicilio" style="display:inline-block" href="'.$enlacefichero1.'" class="enlacefbaremo" target="_blank">Fichero justificativo subido en: '.$enlacefichero1;
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  else
                  {
                     $soriginal='id="enlacefjdomicilio" style="display:none"';
                     $sdestino='id="enlacefjdomicilio" style="display:inline-block" href="'.$enlacefichero1.'"';
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  $soriginal='id="borrardomicilio" class="enlacefbaremo enlaceborrarfichero" style="display:none"';
                  $sdestino='id="borrardomicilio" class="enlacefbaremo enlaceborrarfichero" data="'.$enlacefichero2.'" display:inline-block';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }
            }
            else if($skey=='baremo_situacion_sobrevenida' or  $skey=='baremo_renta_inferior' or $skey=='baremo_acogimiento' or $skey=='baremo_genero' or $skey=='baremo_terrorismo' or $skey=='baremo_parto' or $skey=='baremo_discapacidad_alumno')
            {
                  //añadimos Agegar fichero si está marcado
                  if($sval==1)
                  {
                     $soriginal='id="af'.$skey.'" class="botonform" style="display:none"';
                     $sdestino='id="af'.$skey.'" class="botonform" style="display:block"';
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
               //procesamos los ficheros justificativos
               $ftipo=str_replace('baremo_','',$skey);
               $enlacefichero1="scripts/fetch/ficherosbaremo/".$token."/fbaremo_".$ftipo.".pdf";
               $enlacefichero2=$dirbase."/scripts/fetch/ficherosbaremo/".$token."/fbaremo_".$ftipo.".pdf";
               if(file_exists($enlacefichero1))
               {
                  //enlace para ver fichero
                  $soriginal='id="enlacefj'.$ftipo.'" style="display:none"';
                  $sdestino='id="enlacefj'.$ftipo.'"style="display:inline-block" href="'.$enlacefichero1.'"';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  
                  //enlace para borrar fichero
                  $soriginal='id="borrar'.$ftipo.'" class="enlacefbaremo enlaceborrarfichero" style="display:none"';
                  $sdestino='id="borrar'.$ftipo.'" class="enlacefbaremo enlaceborrarfichero" data="'.$enlacefichero2.'" display:inline-block';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }
            }
            else if($skey=='baremo_marcado_numerosa')
            {
               $enlacefichero1="scripts/fetch/ficherosbaremo/".$token."/fbaremo_numerosa.pdf";
               $enlacefichero2=$dirbase."/scripts/fetch/ficherosbaremo/".$token."/fbaremo_numerosa.pdf";
               if(file_exists($enlacefichero1))
               {
                  if($imprimir==1)
                  {
                     $soriginal='id="enlacefjnumerosa" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero';
                     $sdestino='id="enlacefjnumerosa" style="display:inline-block" href="'.$enlacefichero1.'" class="enlacefbaremo" target="_blank">Fichero justificativo subido en: '.$enlacefichero1;
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  else
                  {
                     $soriginal='id="enlacefjnumerosa" style="display:none"';
                     $sdestino='id="enlacefjnumerosa" style="display:inline-block" href="'.$enlacefichero1.'"';
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  $soriginal='id="borrarnumerosa" class="enlacefbaremo enlaceborrarfichero" style="display:none"';
                  $sdestino='id="borrarnumerosa" class="enlacefbaremo enlaceborrarfichero" data="'.$enlacefichero2.'" display:inline-block';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }
            }
            else if($skey=='baremo_marcado_monoparental')
            {
               $enlacefichero1="scripts/fetch/ficherosbaremo/".$token."/fbaremo_monoparental.pdf";
               $enlacefichero2=$dirbase."/scripts/fetch/ficherosbaremo/".$token."/fbaremo_monoparental.pdf";
               if(file_exists($enlacefichero1))
               {
                  if($imprimir==1)
                  {
                     $soriginal='id="enlacefjmonoparental" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero';
                     $sdestino='id="enlacefjmonoparental" style="display:inline-block" href="'.$enlacefichero1.'" class="enlacefbaremo" target="_blank">Fichero justificativo subido en: '.$enlacefichero1;
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  else
                  {
                     $soriginal='id="enlacefjmonoparental" style="display:none"';
                     $sdestino='id="enlacefjmonoparental" style="display:inline-block" href="'.$enlacefichero1.'"';
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  $soriginal='id="borrarmonoparental" class="enlacefbaremo enlaceborrarfichero" style="display:none"';
                  $sdestino='id="borrarmonoparental" class="enlacefbaremo enlaceborrarfichero" data="'.$enlacefichero2.'" display:inline-block';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }
            }
            else if($skey=='baremo_discapacidad_hermanos')
            {
               $enlacefichero1="scripts/fetch/ficherosbaremo/".$token."/fbaremo_discapacidadh1.pdf";
               $enlacefichero2=$dirbase."/scripts/fetch/ficherosbaremo/".$token."/fbaremo_discapacidadh1.pdf";
               if(file_exists($enlacefichero1))
               {
                  if($imprimir==1)
                  {
                     $soriginal='id="enlacefjdiscapacidadh1" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero';
                     $sdestino='id="enlacefjdiscapacidadh1" style="display:inline-block" href="'.$enlacefichero1.'" class="enlacefbaremo" target="_blank">Fichero justificativo subido en: '.$enlacefichero1;
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  else
                  {
                     $soriginal='id="enlacefjdiscapacidadh1" style="display:none"';
                     $sdestino='id="enlacefjdiscapacidadh1" style="display:inline-block" href="'.$enlacefichero1.'"';
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  $soriginal='id="borrardiscapacidadh1" class="enlacefbaremo enlaceborrarfichero" style="display:none"';
                  $sdestino='id="borrardiscapacidadh1" class="enlacefbaremo enlaceborrarfichero" data="'.$enlacefichero2.'" display:inline-block';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }


               $enlacefichero1="scripts/fetch/ficherosbaremo/".$token."/fbaremo_discapacidadh2.pdf";
               $enlacefichero2=$dirbase."/scripts/fetch/ficherosbaremo/".$token."/fbaremo_discapacidadh2.pdf";
               if(file_exists($enlacefichero1))
               {
                  if($imprimir==1)
                  {
                     $soriginal='id="enlacefjdiscapacidadh2" style="display:none" class="enlacefbaremo" target="_blank">Ver fichero';
                     $sdestino='id="enlacefjdiscapacidadh2" style="display:inline-block" href="'.$enlacefichero1.'" class="enlacefbaremo" target="_blank">Fichero justificativo subido en: '.$enlacefichero1;
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  else
                  {
                     $soriginal='id="enlacefjdiscapacidadh2" style="display:none"';
                     $sdestino='id="enlacefjdiscapacidadh2" style="display:inline-block" href="'.$enlacefichero1.'"';
                     $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
                  }
                  $soriginal='id="borrardiscapacidadh2" class="enlacefbaremo enlaceborrarfichero" style="display:none"';
                  $sdestino='id="borrardiscapacidadh2" class="enlacefbaremo enlaceborrarfichero" data="'.$enlacefichero2.'" display:inline-block';
                  $this->formulario=str_replace($soriginal,$sdestino,$this->formulario);
               }





            }
            $this->formulario=str_replace('name="'.$skey.'" value="1"','name="'.$skey.'" value="1"'.$check,$this->formulario);
            continue;	
         }

         $this->formulario=str_replace('id="'.$skey.'" value=""','id="'.$skey.'" value="'.$sval.'"',$this->formulario);
         $this->formulario=str_replace('id="'.$skey.'" value="0"','id="'.$skey.'" value="'.$sval.'"',$this->formulario);
         }
		}

    public function procesarFormularioNuevo($nuevoid,$dsolicitud,$rol='alumno')
		{
         //deshabilitamos el bootn de duplicada para centros y alumnos 
         if($rol!='sp' and $rol!='admin')
         {
            $original='<input type="radio" name="estado_solicitud" value="duplicada">';
            $destino='<input type="radio" name="estado_solicitud" value="duplicada" disabled>';
            $this->formulario=str_replace($original,$destino,$this->formulario);
            
            $original='<input type="radio" name="transporte"';
            $destino='<input type="radio" name="transporte" disabled';
            $this->formulario=str_replace($original,$destino,$this->formulario);
         }

			$this->formulario=str_replace('name="fase_solicitud"','name="fase_solicitud'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('name="estado_solicitud"','name="estado_solicitud'.$nuevoid.'"',$this->formulario);
		
			//ponemos valores por defecto en la fase y estado de solicitud
			$this->formulario=str_replace('value="borrador"','value="borrador" checked="checked"',$this->formulario);
			$this->formulario=str_replace('value="normal"','value="normal" checked="checked"',$this->formulario);
			
			$this->formulario=str_replace('div class="container" id="tablasolicitud"','div class="container" id="fnuevasolicitud"',$this->formulario);
			$this->formulario=str_replace('form lang="es" id="fsolicitud"','form id="fsolicitud'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('send"','send" data-idal="'.$nuevoid.'"',$this->formulario);

			//validacion del baremo

			//Ocultamos boton de validacion  para alumnos o usuarios nuevos o anonimos
			if($rol=='alumno' or $rol=='anonimo')
         {
            $this->formulario=preg_replace('/<button name="boton.* class="btn btn-outline-dark validar".*<\/button>/','',$this->formulario);
            $this->formulario=preg_replace('/<button name="boton.* class="btn btn-outline-dark".*<\/button>/','',$this->formulario);
         }
			$this->formulario=str_replace('name="boton_baremo_validar_proximidad_domicilio"','name="boton_baremo_validar_proximidad_domicilio'.$nuevoid.'"',$this->formulario);
			//$this->formulario=str_replace('id="labelbaremo"','id="labelbaremo'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('id="baremo_validar_proximidad_domicilio"','id="baremo_validar_proximidad_domicilio'.$nuevoid.'"',$this->formulario);
			
			$this->formulario=str_replace('name="boton_baremo_validar_renta_inferior"','name="boton_baremo_validar_renta_inferior'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('id="baremo_validar_renta_inferior"','id="baremo_validar_renta_inferior'.$nuevoid.'"',$this->formulario);
			
			$this->formulario=str_replace('name="boton_baremo_validar_hnos_centro"','name="boton_baremo_validar_hnos_centro'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('id="baremo_validar_hnos_centro"','id="baremo_validar_hnos_centro'.$nuevoid.'"',$this->formulario);
			
			$this->formulario=str_replace('name="boton_baremo_validar_discapacidad"','name="boton_baremo_validar_discapacidad'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('id="baremo_validar_discapacidad"','id="baremo_validar_discapacidad'.$nuevoid.'"',$this->formulario);

			$this->formulario=str_replace('name="boton_baremo_validar_tipo_familia"','name="boton_baremo_validar_tipo_familia'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('id="baremo_validar_tipo_familia"','id="baremo_validar_tipo_familia'.$nuevoid.'"',$this->formulario);
			
			$this->formulario=str_replace('id="id_puntos_baremo_totales"','id="id_puntos_baremo_totales'.$nuevoid.'"',$this->formulario);
			$this->formulario=str_replace('id="id_puntos_baremo_validados"','id="id_puntos_baremo_validados'.$nuevoid.'"',$this->formulario);
			
			$this->formulario=str_replace('class="proxdomi"','class="proxdomi'.$nuevoid.'"',$this->formulario);
			
			foreach($dsolicitud as $skey=>$sval)
			{
				
				if($skey=='id_centro_destino' and $rol!='alumno' and $rol!='admin' and $rol!='sp')
					$this->formulario=str_replace('id="'.$skey.'" value=""','id="'.$skey.$nuevoid.'" value="'.$dsolicitud['nombre_centro_destino'].'" disabled',$this->formulario);
				else $this->formulario=str_replace('id="'.$skey.'"','id="'.$skey.$nuevoid.'"',$this->formulario);
			}
		return 1;
		}

    public function showFormSolicitud($id=0,$id_centro=0,$rol,$collapsed=1,$imprimir=0,$conexion,$dirbase,$log,$solo_lectura=0)
	 {
      //Creamos una nueva solicitud
      $solicitud=new Solicitud($conexion);
      //nueva solicitud
      if($id==0)
      { 
         $this->lastid=$solicitud->getLast();	
         $nuevoid=$this->lastid+1;
         $dsolicitud=$solicitud->getSolData($this->lastid,'nueva',$id_centro,'alumnos',$log);
         $this->procesarFormularioNuevo($nuevoid,$dsolicitud,$rol);
      }
      //modificacion solicitud
      else
      {
         $dsolicitud=$solicitud->getSolData($id,'existente',0,'alumnos',$log);
         $this->procesarFormularioExistente($id,$dsolicitud,$collapsed,$rol,$imprimir,$dirbase,$log,$solo_lectura);
      }
		return $this->formulario;
   }

  public function showSolicitud($sol){
		
		$sol=(object)$sol;
		$li="<tr class='filasol' id='filasol".$sol->id_alumno."' style='color:black'>";
		$li.="<td class='calumno dalumno' data-idal='".$sol->id_alumno."'>".$sol->id_alumno."-".strtoupper($sol->apellido1).",".strtoupper($sol->nombre)."</td>";
		$li.="<td id='print".$sol->id_alumno."' class='fase printsol'><i class='fa fa-print psol' aria-hidden='true'></i></td>";
		$li.="<td id='fase".$sol->id_alumno."' class='fase'>".$sol->fase_solicitud."</td>";
		$li.="<td id='estado".$sol->id_alumno."' class='estado'>".$sol->estado_solicitud."</td>";
		$li.="<td id='tipoens".$sol->id_alumno."'>".$sol->tipoestudios."</td>";
		$li.="<td id='transporte".$sol->id_alumno."'>".$sol->transporte."</td>";
		$li.="<td id='baremo".$sol->id_alumno."'>".$sol->puntos_validados."</td>";
		$li.="<td id='nordensorteo".$sol->id_alumno."'>".$sol->nordensorteo."</td>";
		$li.="<td id='nasignado".$sol->id_alumno."'>".$sol->nasignado."</td>";
		$li.="</tr>";
	return $li;
	}
		public function getConexion()
		{
			return $this->conectar;
		}
     
  public function crear(){
  	if(isset($_POST["nombre"]))
		{
					//Creamos un usuario
					$alumno=new Alumno($this->adapter);
					$alumno->setNombre($_POST["nombre"]);
					$alumno->setApellido1($_POST["apellido1"]);
					$alumno->setApellido2($_POST["apellido2"]);
					$alumno->setDni($_POST["dni"]);
					$alumno->setFnac($_POST["fnac"]);
					$alumno->setNacionalidad($_POST["nacionalidad"]);
					$save=$alumno->save();
        }
       // $this->redirect("Alumnos", "index");
    }
     
    public function borrar(){
        if(isset($_GET["id"])){
            $id=(int)$_GET["id"];
             
            $alumno=new Alumno($this->adapter);
            $alumno->deleteById($id);
        }
        $this->redirect();
    }
   public function getComprobaciones($id)
	{
      $comp=array();
		$sql="SELECT * FROM baremo WHERE id_alumno=$id";
 		$query=$this->getConexion()->query($sql);
		if($query)
      {
         while($r=$query->fetch_object())
         {
            $comp["comprobar_renta_inferior"]=$r->comprobar_renta_inferior;
            $comp["comprobar_discapacidad_alumno"]=$r->comprobar_discapacidad_alumno;
            $comp["comprobar_discapacidad_hermanos"]=$r->comprobar_discapacidad_hermanos;
            $comp["comprobar_familia_numerosa"]=$r->comprobar_familia_numerosa;
            $comp["comprobar_familia_monoparental"]=$r->comprobar_familia_monoparental;
         }
         return $comp;
      }
		else return 0;
	}
     
}
?>
