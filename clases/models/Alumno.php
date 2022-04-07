<?php
class Alumno{
    private $id_alumno;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $dni;
    private $fnac;
    private $nacionalidad;
     
    public function __construct($adapter,$tipo="matricula") {
        $table=$tipo;
    }
     
    public function getBaremos($c=0) {
				if($c!=0)
					$sql="select a.id_alumno,nombre, apellido1, apellido2,trans_cole,numero_sorteo,ptstotal from alumnos a left join baremo b on a.id_alumno=b.id_alumno left join sorteo s on a.id_alumno=s.id_alumno where a.id_centro_destino=$c";
        	$res=$this->db()->query($sql);
					while ($row = $res->fetch_object()) {
           $resultSet[]=$row;
        }
        return $resultSet;
    }
    public function getId() {
        return $this->id_alumno;
    }
 
    public function setId($id) {
        $this->id_alumno = $id;
    }
     
    public function getNombre() {
        return $this->nombre;
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
 
    public function save(){
        $query="INSERT INTO alumnostmp (id,nombre,apellido1,apellido2,dni,nacionalidad,fnac)
                VALUES(0,
                       '".$this->nombre."',
                       '".$this->apellido1."',
                       '".$this->apellido2."',
                       '".$this->dni."',
                       '".$this->nacionalidad."',
                       '2000-11-11')";
        $save=$this->db()->query($query);
    return $save;
    }
    public function getMatriculados($c=1)
	 {
      $resultSet=array();
	   if($this->table=='alumnos') $centro='id_centro_destino';
	   else $centro='id_centro';
      if($c==1)
      {
         if($this->table=='alumnos_fase2_tmp') 
            $sql='select id_alumno,nombre,id_centro1,id_centro2,id_centro3,id_centro4,id_centro5,id_centro6,id_centro,nombre_centro,centro_definitivo,tipoestudios,transporte,puntos_validados,nordensorteo from alumnos_fase2 where estado_solicitud="apta" order by transporte desc,puntos_validados desc,nordensorteo asc';
         else $sql="SELECT * FROM $this->table";
         $query=$this->db->query($sql);
      }
      else
      { 
         $sql="SELECT * FROM $this->table where $centro=".$c;
         $query=$this->db->query($sql);
      }
      if($query)
         while ($row = $query->fetch_object()) {
                 $resultSet[]=$row;
              }
         
              return $resultSet;
    }
 
}
?>
