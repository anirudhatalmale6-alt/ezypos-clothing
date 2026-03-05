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
        $data['config'] = $this->Configs_model->getConfigName(); 
        $data['allStores'] = $this->User_model->getAllStores(); 
        
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
        $userid = $this->User_model->addUser();
        $response = $this->User_model->addPrivileges($userid);
//      while($store_selected=$this->input->post('user_store')){
       if(isset($_POST['user_store'])){ 
        $store_selected=$this->input->post('user_store');
        for($i=0;$i<count($store_selected);$i++){
           $response = $this->User_model->addUserStores($userid,$store_selected[$i]); 
         }
       }
//    }
        echo json_encode(true);
      }

      
    }
    public function showAllUsers(){
        $result =$this->User_model->getAllUsers();		 
        echo json_encode($result);
    }
    
    public function showStores(){
        $result =$this->User_model->getAllStores();		 
        echo json_encode($result);
    }
    public function EditUser(){
        $id = $this->uri->segment('3');
        $data1['title'] = ucfirst("Edit User");
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'userEdit'=>$this->User_model->editUsers($id),
            'id'=>$id,
            'allStores'=>$this->User_model->getAllStores_for_update(),
            'userStores'=>$this->User_model->editUsers_stores($id)
            );
            $this->load->view('templates/header',$data1);
            $this->load->view('auth/register', $data);
            $this->load->view('templates/footer');
            $this->load->view('templates/rightslidebar');
            $this->load->view('templates/footerscripts');
    }
    
    public function updateUser(){
        $userid = $this->User_model->updateUser();
        $id = $this->input->post('hiddenID');
        $response = $this->User_model->updatePrivileges();
        $store_selected=$this->input->post('user_store');
        $this->User_model->deleteUserStores($id);
        if($store_selected){
        foreach ($store_selected as $stores){
           $response2 = $this->User_model->addUserStores($id,$stores); 
        }}
        echo json_encode($response);  
    }
    
    public function DeleteUser(){
        $result =$this->User_model->DeleteUser();		 
		echo json_encode($result);
    }
}
