<?php
class Logout extends CI_Controller {
	

	    public function index() 
        {	
        	$this->session->unset_userdata('username');
			$this->session->unset_userdata('userid');
			$this->session->sess_destroy();	
			redirect(base_url('login'));
        }
	
       
}