<?php
class Register extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if ( ! $this->session->userdata('username'))
        { 
            redirect('login');
        }
        else if(!$this->session->userdata('privregister')==1){
            show_404();
        }
        $this->load->model('User_model');     
        $this->load->model('Configs_model');  
    }
    public function index($page = 'register'){
        if ( ! file_exists(APPPATH.'views/auth/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }
        $data['title'] = ucfirst($page);
        $data['config'] = $this->configs_model->getConfigName(); 
        $data['allStores'] = $this->user_model->getAllStores(); 
        
        $this->load->view('templates/header', $data);
        $this->load->view('auth/'.$page);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function addUser(){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username','required|is_unique[ezy_pos_users.user_username]');
        $this->form_validation->set_rules('name', 'Name','required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('pass2', 'Password Confirmation', 'required|matches[password]');
      if ($this->form_validation->run() == FALSE)
      {
      // $errors =array(
            $usernameErr=form_error('username');
            $nameErr= form_error('name');
            $passErr=form_error('password');
            $pass2Err=form_error('pass2');
       // );
        $response = false;
        echo json_encode(array($usernameErr,$nameErr,$passErr,$pass2Err));
      }
      else
      {
        $userid = $this->user_model->addUser();
        $response = $this->user_model->addPrivileges($userid);
//        while($store_selected=$this->input->post('user_store')){
        $store_selected=$this->input->post('user_store');
        for($i=0;$i<count($store_selected);$i++){
           $response = $this->user_model->addUserStores($userid,$store_selected[$i]); 
        }
        
//        }
        echo json_encode(true);
      }

      
    }
    public function showAllUsers(){
        $result =$this->user_model->getAllUsers();		 
        echo json_encode($result);
    }
    
    public function showStores(){
        $result =$this->user_model->getAllStores();		 
        echo json_encode($result);
    }
    public function EditUser(){
        $id = $this->uri->segment('3');
        $data1['title'] = ucfirst("Edit User");
        $data1['config'] = $this->configs_model->getConfigName();
        $data = array(
            'userEdit'=>$this->user_model->editUsers($id),
            'id'=>$id
            );		            
            $this->load->view('templates/header',$data1);
            $this->load->view('auth/register', $data);
            $this->load->view('templates/footer');
            $this->load->view('templates/rightslidebar');
            $this->load->view('templates/footerscripts');
    }
    public function updateUser(){
        $userid = $this->user_model->updateUser();
        $response = $this->user_model->updatePrivileges();
        echo json_encode($response);        
    }
    public function DeleteUser(){
        $result =$this->user_model->DeleteUser();		 
		echo json_encode($result);
    }
}
