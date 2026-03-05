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
                $this->load->model('Sales_model');  // Add this line to load the Sales_model
                $this->load->model('Expenses_model');  // Load Expenses model
                $this->load->model('Stocks_model');  // Load Stocks model
        }

        public function view($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();

                //-----------------------------------------------------------------

                 // Get user role from session
                $role = $this->session->userdata('userrole');

                // Admin Dashboard: Fetch all data
                if ($role == 1) { 
                        $today_sales = $this->Sales_model->getTotalSalesByDate(date('Y-m-d'));
                        $today_expenses = $this->Expenses_model->get_today_total_expenses();
                        $total_stock = $this->Stocks_model->getStockQuantityByItemId('');

                        $data['today_sales'] = $today_sales;
                        $data['today_expenses'] = $today_expenses;
                        $data['total_stock'] = $total_stock;
                } 
                // User Dashboard: Fetch only today's sales
                else if ($role == 0) { 
                        $user_sales = $this->Sales_model->getTotalSalesByDate(date('Y-m-d'));

                        $data['user_sales'] = $user_sales;
                }
                //---------------------------------------------------------------------

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
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('pages/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
}