<?php

Class repairModel Extends baseModel {
	protected $table = "repair";

	public function getAllRepair($data = null,$join = null) 
    {
        return $this->fetchAll($this->table,$data,$join);
    }

    public function createRepair($data) 
    {    
        /*$data = array(
        	'Repairname' => $data['Repairname'],
        	'password' => $data['password'],
        	'create_time' => $data['create_time'],
        	'role' => $data['role'],
        	);*/
        return $this->insert($this->table,$data);
    }
    public function updateRepair($data,$id) 
    {    
        if ($this->getRepairByWhere($id)) {
        	/*$data = array(
	        	'Repairname' => $data['Repairname'],
	        	'password' => $data['password'],
	        	'create_time' => $data['create_time'],
	        	'role' => $data['role'],
	        	);*/
	        return $this->update($this->table,$data,$id);
        }
        
    }
    public function deleteRepair($id){
    	if ($this->getRepair($id)) {
    		return $this->delete($this->table,array('repair_id'=>$id));
    	}
    }
    public function getRepair($id){
    	return $this->getByID($this->table,$id);
    }
    public function getRepairByWhere($where){
        return $this->getByWhere($this->table,$where);
    }
    public function getAllRepairByWhere($id){
        return $this->query('SELECT * FROM repair WHERE repair_id != '.$id);
    }
    public function getLastRepair(){
        return $this->getLast($this->table);
    }
    public function checkRepair($id,$seri){
        return $this->query('SELECT * FROM repair WHERE repair_id != '.$id.' AND repair_code = "'.$seri.'"');
    }
    public function queryRepair($sql){
        return $this->query($sql);
    }
}
?>