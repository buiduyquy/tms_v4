<?php

Class bookingModel Extends baseModel {
	protected $table = "booking";

	public function getAllBooking($data = null,$join = null) 
    {
        return $this->fetchAll($this->table,$data,$join);
    }

    public function createBooking($data) 
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
    public function updateBooking($data,$where) 
    {    
        if ($this->getBookingByWhere($where)) {
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
    public function deleteBooking($id){
    	if ($this->getBooking($id)) {
    		return $this->delete($this->table,array('booking_id'=>$id));
    	}
    }
    public function getBooking($id){
        return $this->getByID($this->table,$id);
    }
    public function getBookingByWhere($where){
    	return $this->getByWhere($this->table,$where);
    }
    public function getAllBookingByWhere($id){
        return $this->query('SELECT * FROM booking WHERE booking_id != '.$id);
    }
    public function queryBooking($sql){
        return $this->query($sql);
    }
    public function getLastBooking(){
        return $this->getLast($this->table);
    }
}
?>