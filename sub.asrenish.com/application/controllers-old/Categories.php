<?php
class Categories extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privcategory')==1){
                        show_404();
                }
                $this->load->model('Categories_model');
                $this->load->model('Subcategories_model');
                $this->load->model('Configs_model');
        }

        public function addCategoryGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/category/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);                
                $data['config'] = $this->configs_model->getConfigName();

                $this->load->view('templates/header', $data);
                $this->load->view('category/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function addSubCategoryPOST(){
                $response = $this->subcategories_model->insertSubCategory();
                echo json_encode($response);
        }
        public function addMainCategoryPOST(){
                $response = $this->categories_model->insertMainCategory();
                echo json_encode($response);
        }
        public function showAllCategories(){
              //  $result =$this->categories_model->showAllCategories_w();		 
               $result =$this->categories_model->showAllCategories();		 
		echo json_encode($result);
        }
        public function showCategorySubCats(){
               $postData = $this->input->post();	 
               $result =$this->subcategories_model->showCategorySubCats_w();		 
	       echo json_encode($result);
        }
        
        public function viewSubCategory(){
                //$result =$this->subcategories_model->getAllSubCategories();		 
                $result =$this->subcategories_model->getAllSubCategories_w();		 
		echo json_encode($result);
        }
        public function getTypes(){
                $result =$this->categories_model->getTypes_w();
                echo json_encode($result);
        }
        public function updateSubCategory_SameParent(){
                $result =$this->subcategories_model->updateSubCategory_SameParent();
                echo json_encode($result);
        }    
        public function updateSubCategory_ParentChanged(){
                $result =$this->subcategories_model->updateSubCategory_ParentChanged();
                echo json_encode($result);
        }
        public function DeleteParentCategory(){
                $result =$this->categories_model->DeleteParentCategory();
                echo json_encode($result);
        }
        public function EditParentCategory(){
                $result =$this->categories_model->EditParentCategory();
                echo json_encode($result);
        }
        public function updateParentCategory(){
                $result =$this->categories_model->updateParentCategory();
                echo json_encode($result);
        }
        public function DeleteSubCategory(){
                $result =$this->subcategories_model->DeleteSubCategory();
                echo json_encode($result);
        }
        public function addCategoryGETbackup($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/category/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page); // Capitalize the first letter
                $data['types'] = $this->categories_model->getTypes();

                $this->load->view('templates/header', $data);
                $this->load->view('category/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function editSubCat($page = 'index'){
                if ( ! file_exists(APPPATH.'views/category/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page); // Capitalize the first letter
                //$data['types'] = $this->categories_model->getTypes();

                $this->load->view('templates/header', $data);
                $this->load->view('category/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }      
        
        public function Listsubcategories(){
                $result =$this->subcategories_model->Listsubcategories();
                echo json_encode($result);
        }
}