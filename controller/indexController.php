<?php
Class indexController Extends baseController {
    public function index() {
        $this->view->disableLayout();
            $this->view->data['title'] = 'Transportation Management System';

            $this->view->show('index');
    }

    public function view() {
        /*** set a template variable ***/
            $this->view->data['view'] = 'hehe';
        /*** load the index template ***/
            $this->view->show('index/view');
    }

}
?>