<?php
class EntidadBase{
    protected $table;
    protected $db;
    private $conectar;

    public function __construct($adapter,$table) {
        $this->table=(string) $table;
	$this->conectar = null;
	$this->db = $adapter;
	require_once DIR_CLASES.'LOGGER.php';
	require_once DIR_APP.'parametros.php';
    }
    
    public function getConetar(){
        return $this->conectar;
    }
    
    public function db(){
        return $this->db;
    }
    
    public function getAll($c=1)
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
    
    public function getById($id){
        $query=$this->db->query("SELECT * FROM $this->table WHERE id=$id");

        if($row = $query->fetch_object()) {
           $resultSet=$row;
        }
        
        return $resultSet;
    }
    
    public function getBy($column,$value){
        $query=$this->db->query("SELECT * FROM $this->table WHERE $column='$value'");

        while($row = $query->fetch_object()) {
           $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    public function deleteById($id){
        $query=$this->db->query("DELETE FROM $this->table WHERE id=$id"); 
        return $query;
    }
    
    public function deleteBy($column,$value){
        $query=$this->db->query("DELETE FROM $this->table WHERE $column='$value'"); 
        return $query;
    }
    

    /*
     * Aqui podemos montarnos un monton de métodos que nos ayuden
     * a hacer operaciones con la base de datos de la entidad
     */
    
}
?>
