<?php

Class customersubModel Extends baseModel {
	protected $table = "customer_sub";

	public function getAllCustomer($data = null,$join = null) 
    {
        return $this->fetchAll($this->table,$data,$join);
    }

    public function createCustomer($data) 
    {    
        /*$data = array(
        	'staff_id' => $data['staff_id'],
        	'staff_name' => $data['staff_name'],
        	'staff_birth' => $data['staff_birth'],
        	'staff_gender' => $data['staff_gender'],
            'staff_address' => $data['staff_address'],
            'staff_phone' => $data['staff_phone'],
            'staff_email' => $data['staff_email'],
            'cmnd' => $data['cmnd'],
            'bank' => $data['bank'],
            'account' => $data['account'],
        	);*/

        return $this->insert($this->table,$data);
    }
    public function updateCustomer($data,$where) 
    {    
        if ($this->getCustomerByWhere($where)) {
        	/*$data = array(
            'staff_id' => $data['staff_id'],
            'staff_name' => $data['staff_name'],
            'staff_birth' => $data['staff_birth'],
            'staff_gender' => $data['staff_gender'],
            'staff_address' => $data['staff_address'],
            'staff_phone' => $data['staff_phone'],
            'staff_email' => $data['staff_email'],
            'cmnd' => $data['cmnd'],
            'bank' => $data['bank'],
            'account' => $data['account'],
            );*/
	        return $this->update($this->table,$data,$where);
        }
        
    }
    public function deleteCustomer($id){
    	if ($this->getCustomer($id)) {
    		return $this->delete($this->table,array('customer_sub_id'=>$id));
    	}
    }
    public function getCustomer($id){
        return $this->getByID($this->table,$id);
    }
    public function getCustomerByWhere($where){
    	return $this->getByWhere($this->table,$where);
    }
    public function getAllCustomerByWhere($id){
        return $this->query('SELECT * FROM customer_sub WHERE customer_sub_id != '.$id);
    }
    public function queryCustomer($sql){
        return $this->query($sql);
    }
    public function getLastCustomer(){
        return $this->getLast($this->table);
    }
}
?>