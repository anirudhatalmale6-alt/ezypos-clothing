<?php
class Grns extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
               // else if(!$this->session->userdata('privgrn')==1){
                       // show_404();
               // }
               $this->load->model('Suppliers_model');
               $this->load->model('Items_model');
               $this->load->model('Grns_model');
               $this->load->model('CusPayment_model');
               $this->load->model('Configs_model');
               $this->load->model('Stores_model');
        }

        public function addGrnGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/transactions/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page);
                $data1['config'] = $this->Configs_model->getConfigName();
                // GRN goes to warehouse only
                $warehouse = $this->Stores_model->getWarehouse();
                if($warehouse){
                    $storeLoc = array($warehouse);
                } else {
                    // Fallback if warehouse column not yet added
                    if($_SESSION['userrole'] == 1){
                        $storeLoc = $this->Stores_model->getAllStores();
                    } else {
                        $storeLoc = $this->Stores_model->getAllStoresfornonadmin($_SESSION['userid']);
                    }
                }
                $data = array(
                        'suppliers' => $this->Suppliers_model->getSuppliers(),
                        'items' => $this->Items_model->getItems(),
                        'storeLoc' => $storeLoc
                );
        
                $this->load->view('templates/header', $data1);
                $this->load->view('transactions/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getPartyCheques(){
                $response = $this->CusPayment_model->getPartyCheques();
                echo json_encode($response);
        }
        public function addGrnPOST(){
                $response = $this->Grns_model->insertGRN();
                //return response()->json("Added");
                echo json_encode($response);
        }        
        public function addGrnPOST2(){
                $response = $this->Grns_model->addGrnPOST2();
                echo json_encode($response);
        }
        public function insertGrnItemPOST2(){
                $response = $this->Grns_model->insertGrnItemPOST2();
                echo json_encode($response);
        }
        public function loadItems(){ //autocomplte purpose
                $response = $this->Items_model->loadItems();
                echo json_encode($response);
        }
        public function showAllGRN($page){
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page); 
                $data1['config'] = $this->Configs_model->getConfigName(); 
                $data = array(
                        'suppliers' => $this->Suppliers_model->getSuppliers(),
                        'items'     => $this->Items_model->getItems()
                );
                $this->load->view('templates/header', $data1);
                $this->load->view('listing/'.$page,$data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function loadAllGrn(){
                $response = $this->Grns_model->loadAllGrn();
                echo json_encode($response);
        }
        public function loadGrnItems(){
                $response = $this->Grns_model->loadGrnItems();
                echo json_encode($response);
        }
        public function editGrn(){
                $response = $this->Grns_model->editGrn();
                echo json_encode($response);  
        }
        public function updateGrn(){
                $response = $this->Grns_model->updateGrn();
                echo json_encode($response);
        }

        // Item 2: editable GRN item list for the edit modal
        public function grnItemsForEdit(){
                $id = intval($this->input->post('id'));
                echo json_encode($this->Grns_model->getGrnItemsForEdit($id));
        }
        // Item 2: full GRN edit (header + items + stock reconciliation)
        public function updateGrnFull(){
                echo json_encode($this->Grns_model->updateGrnFull());
        }

        // Item 9: printable GRN document (header + line items)
        public function printGrn($id = 0){
                $header = $this->Grns_model->getGrnHeader($id);
                if(!$header){ show_404(); return; }
                $data = array(
                        'grn'     => $header,
                        'items'   => $this->Grns_model->getGrnItemsById($id),
                        'company' => $this->Configs_model->getConfigName()
                );
                $this->load->view('listing/grn_print', $data);
        }
        

}