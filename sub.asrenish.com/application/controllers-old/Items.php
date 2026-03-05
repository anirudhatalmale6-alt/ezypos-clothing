<?php
class Items extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privitem')==1){
                        show_404();
                }
                $this->load->model('Items_model');
                $this->load->model('Configs_model');
                $this->load->model('Categories_model');
        }

        public function addItemGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/item/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data = array(
                        'title' => ucfirst($page),          
                        'config' => $this->configs_model->getConfigName()
                );                
                $data2['types'] = $this->categories_model->getTypes_w();
        
                $this->load->view('templates/header', $data);
                $this->load->view('item/'.$page,$data2);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addItemPOST(){
                $this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');

                $this->form_validation->set_rules('code', 'Code', 'is_unique[ezy_pos_items.itm_code]');

                if ($this->form_validation->run() == FALSE)
                {
                        echo json_encode(false);
                }
                else
                {
                        
                $response = $this->items_model->addItemPOST();
                echo json_encode($response);
                }


        }

        public function showAllItems(){
                $result =$this->items_model->getAllItems();		 
		echo json_encode($result);
        }
            
        public function EditItem(){
                $result = $this->items_model->editItem();
		echo json_encode($result);
        }
        public function getmoreInfo(){
                $result = $this->items_model->getmoreInfo();
		echo json_encode($result);
        }

        public function updateItem(){
                $result = $this->items_model->updateItem();
		echo json_encode($result);
        }

        public function DeleteItem(){
        $result = $this->items_model->deleteItem();
                $msg['success'] = false;
                if($result){
                        $msg['success']= true;
                }
                echo json_encode($msg);
        }
        public function getItems(){//get name& id only
                $result = $this->items_model->getItems();
		echo json_encode($result);
        }
}