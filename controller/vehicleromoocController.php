<?php

Class vehicleromoocController Extends baseController {

    public function vehicle() {

        $this->view->setLayout('admin');

        if (!isset($_SESSION['userid_logined'])) {

            return $this->view->redirect('user/login');

        }
        if (!in_array($this->registry->router->controller, json_decode($_SESSION['user_permission'])) && $_SESSION['user_permission'] != '["all"]') {

            return $this->view->redirect('admin');

        }

        $this->view->data['lib'] = $this->lib;

        $this->view->data['title'] = 'Quản lý bảng thay đổi mooc';



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $order_by = isset($_POST['order_by']) ? $_POST['order_by'] : null;

            $order = isset($_POST['order']) ? $_POST['order'] : null;

            $page = isset($_POST['page']) ? $_POST['page'] : null;

            $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : null;

            $limit = isset($_POST['limit']) ? $_POST['limit'] : 18446744073709;

        }

        else{

            $order_by = $this->registry->router->order_by ? $this->registry->router->order_by : 'vehicle_number,start_time';

            $order = $this->registry->router->order_by ? $this->registry->router->order_by : 'ASC';

            $page = $this->registry->router->page ? (int) $this->registry->router->page : 1;

            $keyword = "";

            $limit = 50;

        }


        $vehicle_model = $this->model->get('vehicleModel');
        $vehicles = $vehicle_model->getAllVehicle(array('order_by'=>'vehicle_number','order'=>'ASC'));
        $this->view->data['vehicles'] = $vehicles;

        $romooc_model = $this->model->get('romoocModel');
        $romoocs = $romooc_model->getAllRomooc(array('order_by'=>'romooc_number','order'=>'ASC'));
        $this->view->data['romoocs'] = $romoocs;
        
        $join = array('table'=>'vehicle,romooc','where'=>'vehicle=vehicle_id AND romooc=romooc_id');


        $vehicleromooc_model = $this->model->get('vehicleromoocModel');

        $sonews = $limit;

        $x = ($page-1) * $sonews;

        $pagination_stages = 2;

        

        $tongsodong = count($vehicleromooc_model->getAllVehicle(null,$join));

        $tongsotrang = ceil($tongsodong / $sonews);

        



        $this->view->data['page'] = $page;

        $this->view->data['order_by'] = $order_by;

        $this->view->data['order'] = $order;

        $this->view->data['keyword'] = $keyword;

        $this->view->data['limit'] = $limit;

        $this->view->data['pagination_stages'] = $pagination_stages;

        $this->view->data['tongsotrang'] = $tongsotrang;

        $this->view->data['sonews'] = $sonews;



        $data = array(

            'order_by'=>$order_by,

            'order'=>$order,

            'limit'=>$x.','.$sonews,

            );

        

        if ($keyword != '') {

            $search = '( vehicle_number LIKE "%'.$keyword.'%" OR romooc_number LIKE "%'.$keyword.'%" )';

            $data['where'] = $search;

        }

        

        

        

        $this->view->data['vehicle_romoocs'] = $vehicleromooc_model->getAllVehicle($data,$join);



        $this->view->data['lastID'] = isset($vehicleromooc_model->getLastVehicle()->vehicle_romooc_id)?$vehicleromooc_model->getLastVehicle()->vehicle_romooc_id:0;

        

        $this->view->show('vehicleromooc/vehicle');

    }

    public function index() {

        $this->view->setLayout('admin');

        if (!isset($_SESSION['userid_logined'])) {

            return $this->view->redirect('user/login');

        }
        if (!in_array($this->registry->router->controller, json_decode($_SESSION['user_permission'])) && $_SESSION['user_permission'] != '["all"]') {

            return $this->view->redirect('admin');

        }

        $this->view->data['lib'] = $this->lib;

        $this->view->data['title'] = 'Thay lắp mooc';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $vehicle = isset($_POST['vehicle']) ? $_POST['vehicle'] : null;

            $romooc = isset($_POST['romooc']) ? $_POST['romooc'] : null;

        }

        else{

            $vehicle = 0;

            $romooc = 0;

        }

        $this->view->data['xe'] = $vehicle;
        $this->view->data['mooc'] = $romooc;


        $vehicle_model = $this->model->get('vehicleModel');
        $vehicles = $vehicle_model->getAllVehicle(array('order_by'=>'vehicle_number','order'=>'ASC'));
        $this->view->data['vehicle_lists'] = $vehicles;

        $data = array('order_by'=>'vehicle_number','order'=>'ASC');
        if ($vehicle > 0) {
            $data = array('where'=>'vehicle_id = '.$vehicle);
        }
        $vehicles = $vehicle_model->getAllVehicle($data);
        $this->view->data['vehicles'] = $vehicles;

        $romooc_model = $this->model->get('romoocModel');
        $romoocs = $romooc_model->getAllRomooc(array('order_by'=>'romooc_number','order'=>'ASC'));
        $this->view->data['romooc_lists'] = $romoocs;

        $data = array('order_by'=>'romooc_number','order'=>'ASC');
        if ($romooc > 0) {
            $data = array('where'=>'romooc_id = '.$romooc);
        }
        $romoocs = $romooc_model->getAllRomooc($data);
        $this->view->data['romoocs'] = $romoocs;

        

        $vehicleromooc_model = $this->model->get('vehicleromoocModel');
        $join = array('table'=>'vehicle, romooc','where'=>'vehicle = vehicle_id AND romooc = romooc_id');
        $data = array(
            'where' => '((end_time IS NULL OR end_time = 0) OR end_time >= '.strtotime(date('d-m-Y')).')',
        );
        $vehicle_romoocs = $vehicleromooc_model->getAllVehicle($data,$join);
        $this->view->data['vehicle_romoocs'] = $vehicle_romoocs;

        $this->view->show('vehicleromooc/index');

    }



    public function exchange(){
        $this->view->disableLayout();

        if (!isset($_SESSION['userid_logined'])) {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if (!isset(json_decode($_SESSION['user_permission_action'])->vehicleromooc) && $_SESSION['user_permission_action'] != '["all"]') {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }
        if (isset($_POST['yes'])) {

            $vehicleromooc = $this->model->get('vehicleromoocModel');

            $data = array(

                'vehicle' => trim($_POST['vehicle']),
                'romooc' => trim($_POST['romooc']),
                'start_time' => strtotime(str_replace('/', '-', $_POST['start_time'])),

            );

            $dm1 = $vehicleromooc->queryVehicle('SELECT * FROM vehicle_romooc WHERE romooc='.$data['romooc'].' AND start_time <= '.$data['start_time'].' AND (end_time IS NULL OR end_time > '.$data['start_time'].') ORDER BY start_time DESC LIMIT 1');
            $dm2 = $vehicleromooc->queryVehicle('SELECT * FROM vehicle_romooc WHERE romooc='.$data['romooc'].' AND start_time > '.$data['start_time'].' AND (end_time IS NULL OR end_time > '.$data['start_time'].') ORDER BY start_time ASC LIMIT 1');
            $dm3 = $vehicleromooc->queryVehicle('SELECT * FROM vehicle_romooc WHERE vehicle='.$data['vehicle'].' AND start_time <= '.$data['start_time'].' AND (end_time IS NULL OR end_time > '.$data['start_time'].') ORDER BY start_time DESC LIMIT 1');
            $dm4 = $vehicleromooc->queryVehicle('SELECT * FROM vehicle_romooc WHERE vehicle='.$data['vehicle'].' AND start_time > '.$data['start_time'].' AND (end_time IS NULL OR end_time > '.$data['start_time'].') ORDER BY start_time ASC LIMIT 1');

            if ($dm1 || $dm2 || $dm3 || $dm4) {
                if($dm1){
                    foreach ($dm1 as $row) {
                        $d = array(
                            'end_time' => strtotime(date('d-m-Y',strtotime($_POST['start_time'].' -1 day'))),
                            );
                        $vehicleromooc->updateVehicle($d,array('vehicle_romooc_id'=>$row->vehicle_romooc_id));
                    }
                }
                else if ($dm2) {
                    foreach ($dm2 as $row) {
                        $data['end_time'] = strtotime(date('d-m-Y',strtotime(date('d-m-Y',$row->start_time).' -1 day')));
                    }
                }

                
                if($dm3){
                    foreach ($dm3 as $row) {
                        $d = array(
                            'end_time' => strtotime(date('d-m-Y',strtotime($_POST['start_time'].' -1 day'))),
                            );
                        $vehicleromooc->updateVehicle($d,array('vehicle_romooc_id'=>$row->vehicle_romooc_id));
                    }
                }
                else if ($dm4) {
                    foreach ($dm4 as $row) {
                        $data['end_time'] = strtotime(date('d-m-Y',strtotime(date('d-m-Y',$row->start_time).' -1 day')));
                    }
                }

            }
            
            $vehicleromooc->createVehicle($data);
            
            $text = date('d/m/Y H:i:s')."|".$_SESSION['user_logined']."|"."add"."|".$vehicleromooc->getLastVehicle()->vehicle_romooc_id."|vehicle_romooc|".implode("-",$data)."\n"."\r\n";
            $this->lib->ghi_file("action_logs.txt",$text);


            $user_log_model = $this->model->get('userlogModel');
            $data_log = array(
                'user_log' => $_SESSION['userid_logined'],
                'user_log_date' => time(),
                'user_log_table' => 'vehicle_romooc',
                'user_log_table_name' => 'Thay lắp mooc',
                'user_log_action' => 'Thêm mới',
                'user_log_data' => json_encode($data),
            );
            $user_log_model->createUser($data_log);

            echo "Thay thế thành công";

        }
    }

    public function adddriver(){
        $driver_model = $this->model->get('driverModel');

        if (isset($_POST['driver_start_date'])) {
            if($driver_model->getVehicleByWhere(array('driver_vehicle'=>$_POST['driver_vehicle'],'driver_start_date'=>strtotime(str_replace('/', '-', $_POST['driver_start_date']))))){
                echo 'Thông tin đã tồn tại';
                return false;
            }

            $data = array(
                'driver_start_date' => strtotime(str_replace('/', '-', $_POST['driver_start_date'])),
                'driver_end_date' => $_POST['driver_end_date']!=""?strtotime(str_replace('/', '-', $_POST['driver_end_date'])):null,
                'driver_vehicle' => trim($_POST['driver_vehicle']),
                'driver_staff' => trim($_POST['driver_staff']),
            );

            $ngaytruoc = strtotime(date('d-m-Y',strtotime(str_replace('/', '-', $_POST['driver_start_date']).' -1 day')));

            if ($data['driver_end_date'] == null) {
                $driver_model->queryVehicle('UPDATE driver SET driver_end_date = '.$ngaytruoc.' WHERE driver_vehicle='.$data['driver_vehicle'].' AND (driver_end_date IS NULL OR driver_end_date = 0)');
                $driver_model->createVehicle($data);
            }
            else{
                $dm1 = $driver_model->queryVehicle('SELECT * FROM driver WHERE driver_vehicle='.$data['driver_vehicle'].' AND driver_start_date <= '.$data['driver_start_date'].' AND driver_end_date <= '.$data['driver_end_date'].' AND driver_end_date >= '.$data['driver_start_date'].' ORDER BY driver_end_date ASC LIMIT 1');
                $dm2 = $driver_model->queryVehicle('SELECT * FROM driver WHERE driver_vehicle='.$data['driver_vehicle'].' AND driver_end_date >= '.$data['driver_end_date'].' AND driver_start_date >= '.$data['driver_start_date'].' AND driver_start_date <= '.$data['driver_end_date'].' ORDER BY driver_end_date ASC LIMIT 1');
                $dm3 = $driver_model->queryVehicle('SELECT * FROM driver WHERE driver_vehicle='.$data['driver_vehicle'].' AND driver_start_date <= '.$data['driver_start_date'].' AND driver_end_date >= '.$data['driver_end_date'].' ORDER BY driver_end_date ASC LIMIT 1');

                if ($dm3) {
                    foreach ($dm3 as $row) {
                        $d = array(
                            'driver_end_date' => strtotime(date('d-m-Y',strtotime(str_replace('/', '-', $_POST['driver_start_date']).' -1 day'))),
                            );
                        $driver_model->updateVehicle($d,array('driver_id'=>$row->driver_id));

                        $c = array(
                            'driver_vehicle' => $row->driver_vehicle,
                            'driver_staff' => $row->driver_staff,
                            'driver_start_date' => strtotime(date('d-m-Y',strtotime(str_replace('/', '-', $_POST['driver_end_date']).' +1 day'))),
                            'driver_end_date' => $row->driver_end_date,
                            );
                        $driver_model->createVehicle($c);

                    }
                    $driver_model->createVehicle($data);

                }
                else if ($dm1 || $dm2) {
                    if($dm1){
                        foreach ($dm1 as $row) {
                            $d = array(
                                'driver_end_date' => strtotime(date('d-m-Y',strtotime(str_replace('/', '-', $_POST['driver_start_date']).' -1 day'))),
                                );
                            $driver_model->updateVehicle($d,array('driver_id'=>$row->driver_id));
                        }
                    }
                    if($dm2){
                        foreach ($dm2 as $row) {
                            $d = array(
                                'driver_start_date' => strtotime(date('d-m-Y',strtotime(str_replace('/', '-', $_POST['driver_end_date']).' +1 day'))),
                                );
                            $driver_model->updateVehicle($d,array('driver_id'=>$row->driver_id));
                        }
                    }
                    $driver_model->createVehicle($data);
                }
                else{
                    $driver_model->createVehicle($data);
                }
            }
            

            $text = date('d/m/Y H:i:s')."|".$_SESSION['user_logined']."|"."add"."|".$driver_model->getLastVehicle()->driver_id."|driver|".implode("-",$data)."\n"."\r\n";
            $this->lib->ghi_file("action_logs.txt",$text);


            $user_log_model = $this->model->get('userlogModel');
            $data_log = array(
                'user_log' => $_SESSION['userid_logined'],
                'user_log_date' => time(),
                'user_log_table' => 'driver',
                'user_log_table_name' => 'Bàn giao xe',
                'user_log_action' => 'Thêm mới',
                'user_log_data' => json_encode($data),
            );
            $user_log_model->createUser($data_log);


            echo "Thêm thành công";
        }

    }

    public function add(){

        $this->view->disableLayout();

        if (!isset($_SESSION['userid_logined'])) {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if (!isset(json_decode($_SESSION['user_permission_action'])->driver) && $_SESSION['user_permission_action'] != '["all"]') {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        $this->view->data['title'] = 'Thêm mới bàn giao xe';

        $vehicle = $this->model->get('vehicleModel');

        $this->view->data['vehicles'] = $vehicle->getAllVehicle(array('order_by'=>'vehicle_number','order'=>'ASC'));

        $staff = $this->model->get('staffModel');

        $this->view->data['staffs'] = $staff->getAllStaff(array('order_by'=>'staff_name','order'=>'ASC'));

        return $this->view->show('driver/add');
    }

    public function editdriver(){
        $driver_model = $this->model->get('driverModel');

        if (isset($_POST['driver_id'])) {
            $id = $_POST['driver_id'];
            
            $data = array(
                'driver_start_date' => strtotime(str_replace('/', '-', $_POST['driver_start_date'])),
                'driver_end_date' => $_POST['driver_end_date']!=""?strtotime(str_replace('/', '-', $_POST['driver_end_date'])):null,
                'driver_vehicle' => trim($_POST['driver_vehicle']),
                'driver_staff' => trim($_POST['driver_staff']),
            );

            $driver_model->updateVehicle($data,array('driver_id'=>$id));
            

            $text = date('d/m/Y H:i:s')."|".$_SESSION['user_logined']."|"."edit"."|".$id."|driver|".implode("-",$data)."\n"."\r\n";
            $this->lib->ghi_file("action_logs.txt",$text);


            $user_log_model = $this->model->get('userlogModel');
            $data_log = array(
                'user_log' => $_SESSION['userid_logined'],
                'user_log_date' => time(),
                'user_log_table' => 'driver',
                'user_log_table_name' => 'Bàn giao xe',
                'user_log_action' => 'Cập nhật',
                'user_log_data' => json_encode($data),
            );
            $user_log_model->createUser($data_log);


            echo "Cập nhật thành công";
        }
    }

    public function edit($id){

        $this->view->disableLayout();

        if (!isset($_SESSION['userid_logined'])) {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if (!isset(json_decode($_SESSION['user_permission_action'])->driver) && $_SESSION['user_permission_action'] != '["all"]') {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }
        if (!$id) {

            $this->view->redirect('driver');

        }

        $this->view->data['lib'] = $this->lib;
        $this->view->data['title'] = 'Cập nhật bàn giao xe';

        $driver_model = $this->model->get('driverModel');

        $driver_data = $driver_model->getVehicle($id);

        $this->view->data['driver_data'] = $driver_data;

        if (!$driver_data) {

            $this->view->redirect('driver');

        }

        $vehicle = $this->model->get('vehicleModel');

        $this->view->data['vehicles'] = $vehicle->getAllVehicle(array('order_by'=>'vehicle_number','order'=>'ASC'));

        $staff = $this->model->get('staffModel');

        $this->view->data['staffs'] = $staff->getAllStaff(array('order_by'=>'staff_name','order'=>'ASC'));

        return $this->view->show('driver/edit');

    }

    public function view($id){

        $this->view->disableLayout();

        if (!isset($_SESSION['userid_logined'])) {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if (!in_array($this->registry->router->controller, json_decode($_SESSION['user_permission'])) && $_SESSION['user_permission'] != '["all"]') {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }
        if (!$id) {

            $this->view->redirect('driver');

        }

        $this->view->data['lib'] = $this->lib;
        $this->view->data['title'] = 'Thông tin bàn giao xe';

        $driver_model = $this->model->get('driverModel');

        $driver_data = $driver_model->getVehicle($id);

        $this->view->data['driver_data'] = $driver_data;

        if (!$driver_data) {

            $this->view->redirect('driver');

        }

        $vehicle = $this->model->get('vehicleModel');

        $this->view->data['vehicles'] = $vehicle->getAllVehicle(array('order_by'=>'vehicle_number','order'=>'ASC'));

        $staff = $this->model->get('staffModel');

        $this->view->data['staffs'] = $staff->getAllStaff(array('order_by'=>'staff_name','order'=>'ASC'));

        return $this->view->show('driver/view');

    }

    public function filter(){
        $this->view->disableLayout();

        $this->view->data['lib'] = $this->lib;
        $this->view->data['title'] = 'Lọc dữ liệu';

        $vehicle = $this->model->get('vehicleModel');

        $this->view->data['vehicles'] = $vehicle->getAllVehicle(array('order_by'=>'vehicle_number','order'=>'ASC'));

        $staff = $this->model->get('staffModel');

        $this->view->data['staffs'] = $staff->getAllStaff(array('order_by'=>'staff_name','order'=>'ASC'));

        $this->view->data['page'] = $_GET['page'];
        $this->view->data['order_by'] = $_GET['order_by'];
        $this->view->data['order'] = $_GET['order'];
        $this->view->data['limit'] = $_GET['limit'];
        $this->view->data['keyword'] = $_GET['keyword'];

        return $this->view->show('driver/filter');
    }

    public function delete(){

        if (!isset($_SESSION['userid_logined'])) {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if ((!isset(json_decode($_SESSION['user_permission_action'])->driver) || json_decode($_SESSION['user_permission_action'])->driver != "driver") && $_SESSION['user_permission_action'] != '["all"]') {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $driver_model = $this->model->get('driverModel');
            $user_log_model = $this->model->get('userlogModel');

            if (isset($_POST['xoa'])) {

                $datas = explode(',', $_POST['xoa']);

                foreach ($datas as $data) {

                    $driver_model->deleteVehicle($data);


                        $text = date('d/m/Y H:i:s')."|".$_SESSION['user_logined']."|"."delete"."|".$data."|driver|"."\n"."\r\n";

                        $this->lib->ghi_file("action_logs.txt",$text);



                }


                $data_log = array(
                    'user_log' => $_SESSION['userid_logined'],
                    'user_log_date' => time(),
                    'user_log_table' => 'driver',
                    'user_log_table_name' => 'Bàn giao xe',
                    'user_log_action' => 'Xóa',
                    'user_log_data' => json_encode($datas),
                );
                $user_log_model->createUser($data_log);


                echo "Xóa thành công";
                return true;

            }

            else{

                $driver_model->deleteVehicle($_POST['data']);

                $text = date('d/m/Y H:i:s')."|".$_SESSION['user_logined']."|"."delete"."|".$_POST['data']."|driver|"."\n"."\r\n";

                $this->lib->ghi_file("action_logs.txt",$text);

                $data_log = array(
                    'user_log' => $_SESSION['userid_logined'],
                    'user_log_date' => time(),
                    'user_log_table' => 'driver',
                    'user_log_table_name' => 'Bàn giao xe',
                    'user_log_action' => 'Xóa',
                    'user_log_data' => json_encode($_POST['data']),
                );
                $user_log_model->createUser($data_log);

                echo "Xóa thành công";
                return true;

            }

            

        }

    }

    public function importdriver(){
        if (isset($_FILES['import']['name'])) {
            $total = count($_FILES['import']['name']);
            for( $i=0 ; $i < $total ; $i++ ) {
              $tmpFilePath = $_FILES['import']['name'][$i];
              echo $tmpFilePath;
            }
        }
    }
    public function import(){

        $this->view->disableLayout();

        if (!isset($_SESSION['userid_logined'])) {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        if (!isset(json_decode($_SESSION['user_permission_action'])->driver) && $_SESSION['user_permission_action'] != '["all"]') {

            echo "Bạn không có quyền thực hiện thao tác này";
            return false;

        }

        $this->view->data['title'] = 'Nhập dữ liệu';

       
        return $this->view->show('driver/import');

    }


}

?>