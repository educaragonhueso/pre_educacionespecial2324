<?php
class CentrosController{
   public $conexion;
	
   public function __construct($conexion=null) 
   {
     $this->conexion=$conexion;
   }
	public function index()
	{
		//Creamos el objeto centro
		$centro=new Centro($this->adapter);
		//Conseguimos todos los usuarios
		$allcentros=$centro->getAll();
		//Cargamos la vista index y le pasamos valores
		$this->view("index",array(
		));
	}
    
   public function showTimeline($rol='centro',$id_centro='22000251',$datos='matricula')
   {
      $tline=' <div class="row progreso"><div class="col">INICIO</div>
			<div class="col">PROVISIONALES</div>
			<div class="col">DEFINITIVAS</div>
			</div><div class="row" style="background-color: #1b1b33;"> <hr width="60%" align="left"></div>';
      return $tline;
   }
    public function getDatosMapa() {
      $r=array();
	$ec = $this->adapter->query("SELECT
nombre_centro,coordenadas,vacantes_ebo,vacantes_tva FROM centros WHERE
coordenadas!='nodata'");
      while ($row = $ec->fetch_object()) 
      {
         $r[]=$row;
      }
   //   while($row=$ec->fetch_object())
     //    $r[]=$row; 
    return $r;
    }
   public function getAllCentros_old($provincia='todas',$clase='todos')
   {
      if($clase=='todos')
      {
         if($provincia=='todas')	$sql="SELECT id_centro FROM centros where id_centro>1";
         else	$sql="SELECT id_centro FROM centros where id_centro>1 and id_centro in(select id_centro from matricula) and provincia='$provincia'";
      }
      elseif($clase=='especial')
      {
         if($provincia=='todas')	$sql="SELECT id_centro FROM centros where id_centro>1 and clase_centro='especial' and id_centro in(select id_centro from matricula)";
         else	$sql="SELECT id_centro FROM centros where id_centro>1 and clase_centro='especial' and id_centro in(select id_centro from matricula) and provincia='$provincia'";
      }
      $query=$this->conexion->query($sql);
      if($query) return $query;
      else return 0; 
   }
   public function getAllCentros($provincia='todas',$clase='todos')
   {
      if($clase=='todos')
      {
         if($provincia=='todas')	$sql="SELECT id_centro FROM centros where id_centro>1";
         else	$sql="SELECT id_centro FROM centros where id_centro>1 and provincia='$provincia'";
      }
      elseif($clase=='especial')
      {
         if($provincia=='todas')	$sql="SELECT id_centro FROM centros where id_centro>1 and clase_centro='especial'";
         else	$sql="SELECT id_centro FROM centros where id_centro>1 and clase_centro='especial' and provincia='$provincia'";
      }
      $query=$this->conexion->query($sql);
      if($query) return $query;
      else return 0; 
   }
   public function getCentrosData($datos='matricula')
   {
      $centro=new Centro($this->adapter,'','no');
      $lcentros=array();
      $cabecera='si';
      if($rol=='admin') 
      {
         $cabecera='no';
         $centros=$this->getAllCentros('todas');
      }
      while ($row = $centros->fetch_object()) 
      {
         $centro->setId($row->id_centro);
         $lcentros[]=$centro->getResumen('centro','matricula');
      }
      return $lcentros;
   }
   public function showTablas($rol,$id_centro,$datos,$provincia,$clase,$log)
   {
      $log->warning("Obteniedo datos centros, rol: $rol");
      $lcentros='';
      $cabecera='si';
      if($rol=='sp') 
      {
         $cabecera='no';
         $centros=$this->getAllCentros($provincia,$clase);
      }
      elseif($rol=='admin') 
      {
         $cabecera='no';
         $centros=$this->getAllCentros($provincia,$clase);
      }
      while ($row = $centros->fetch_object()) 
      {
         $lcentros.=$this->showTabla('centro',$row->id_centro,'matricula',$cabecera,$log);
      }
      return $lcentros;
   }
   public function showTabla($rol='centro',$id_centro='',$datos='matricula',$cabecera='si',$log)
   {
      $list=new ListadosController($datos,0);
      //Creamos el objeto centro
      $centro=new Centro($this->conexion,$id_centro);
      $centro->setNombre();
      $nsorteo=$centro->getNumeroSorteo();
      //obtenemos resumen
      $tablamatricula=$centro->getResumen($rol,$datos,$log);
    return($list->showTablaResumenMatriculaEspecial($tablamatricula,$centro->getNombre(),$rol,$cabecera,$id_centro));
   }
   public function crear()
   {
      if(isset($_POST["nombre"]))
      {
         //Creamos un usuario
         $usuario=new Usuario();
         $usuario->setNombre($_POST["nombre"]);
         $usuario->setApellido($_POST["apellido"]);
         $usuario->setEmail($_POST["email"]);
         $usuario->setPassword(sha1($_POST["password"]));
         $save=$usuario->save();
      }
      $this->redirect("Usuarios", "index");
   }
   public function borrar()
   {
      if(isset($_GET["id"]))
      { 
         $id=(int)$_GET["id"];
         $usuario=new Usuario();
         $usuario->deleteById($id); 
      }
      $this->redirect();
   }
   public function hola()
   {
     $usuarios=new UsuariosModel($this->adapter);
     $usu=$usuarios->getUnUsuario();
     var_dump($usu);
   }
}

