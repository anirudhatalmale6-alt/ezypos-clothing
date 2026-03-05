<?php
class Staffs extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privstaff')==1){
                        show_404();
                }
                $this->load->model('Staffs_model');
                $this->load->model('Configs_model');
        }

        public function addStaffGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/staff/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('staff/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addStaffPOST(){

                $response = $this->Staffs_model->insertStaff();
                //return response()->json("Added");
                echo json_encode($response);
        }

        public function showAllStaffs(){
                $result =$this->Staffs_model->getAllStaffs();		 
		echo json_encode($result);
        }

        public function EditStaff(){
                $result = $this->Staffs_model->EditStaff();
		echo json_encode($result);
        }

        public function updateStaff(){
                $result = $this->Staffs_model->updateStaff();
		echo json_encode($result);
        }
        public function DeleteStaff(){
                $result = $this->Staffs_model->DeleteStaff();
		echo json_encode($result);
        }
        public function getEmployeeExpense(){
                $result = $this->Staffs_model->getEmployeeExpense();
		echo json_encode($result);
        }
        public function getEmployeeNameID(){
                $result = $this->Staffs_model->getEmployeeNameID();
		echo json_encode($result);
        }
}