<?php
class Userauthentication extends CI_Controller {

    public function __construct(){
        parent::__construct();  
        if ( $this->session->userdata('username'))
                { 
                    redirect('/');
                }    
        $this->load->library('form_validation');
        $this->load->model('User_model');
    }
   
    public function index($page = 'login')
    {
            if ( ! file_exists(APPPATH.'views/auth/'.$page.'.php'))
            {
                    // Whoops, we don't have a page for that!
                    show_404();
            }    
            $data['title'] = ucfirst($page); // Capitalize the first letter
            $this->load->view('auth/'.$page, $data);        
    }

    /*
    public function validate(){ //not in use
            $usr=$this->input->post('username');
            $pass=$this->input->post('password');            

            if($this->user_model->check_user_exist($usr,$pass))
            {
                $userID = $this->user_model->getUserID($usr);					
						
                $session_data = array(
                'username' => $usr,
                'userid' => $userID
                );
                $this->session->set_userdata($session_data);
                
                $this->session->set_flashdata('logsuccess', 'You are welcome');
                redirect(base_url());
            }
            else{
                $this->session->set_flashdata('loginfailmsg', 'Invalid Username or Password');
	        redirect(base_url("login"));
            }        
    } */
    public function login_process()
    {
            $username = $this->input->post('username');
            $password = md5($this->input->post('password'));
            //  $password = $this->input->post('password');
            $data = array(
                    'username' => $username,
                    'password' => $password
                    );
            $result = $this->user_model->check_login($data);
            if($result == true)
            {
                    $user_info = $this->user_model->read_user_information($username);
                    if($user_info !== false)
                    {
                             $itm = $user_info[0]['priv_item'];
                             $cat=$user_info[0]['priv_category'];
                             $cus=$user_info[0]['priv_customer'];
                             $sup=$user_info[0]['priv_supplier'];
                             $store=$user_info[0]['priv_store'];
                             $staff=$user_info[0]['priv_staff'];
                             $tax=$user_info[0]['priv_tax'];
                             $expense_cat=$user_info[0]['priv_expense_cat'];
                             $register=$user_info[0]['priv_register'];
                             

                             $Master=$itm+$cat+$cus+$sup+$store+$staff+$tax+$expense_cat;
                             $User=$register;

                             $session_data = array(
                                'username'=>$username,
                                'userid'=>$user_info[0]['user_id'],
                                'userrole'=>$user_info[0]['user_role'],
                                'privitem'=>$itm,
                                'privcategory'=>$cat,
                                'privcustomer'=>$cus,
                                'privsupplier'=>$sup,
                                'privstore'=>$store,
                                'privstaff'=>$staff,
                                'privtax'=>$tax,
                                'privregister'=>$register,
                                'privMasters'=>$Master,
                                'privUsers'=>$User
                        );
                                    
                            $this->session->set_userdata($session_data);
                           echo json_encode(true);
                            
                    }
                    else
                    {
                        echo json_encode(false);
                    }
            }
            else
            {
                   
                echo json_encode(false);
            }
    }
    
}