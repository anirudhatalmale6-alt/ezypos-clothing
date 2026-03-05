<?php
class Home extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if (!$this->session->userdata('username'))
                { 
                    redirect('login');
                }
                $this->load->model('Configs_model'); 
        }

        public function view($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('pages/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function test($page = 'home')
        {
                if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page); // Capitalize the first letter
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('pages/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
}