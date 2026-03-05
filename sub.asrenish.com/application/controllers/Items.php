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
                        'config' => $this->Configs_model->getConfigName()
                );                
                $data2['types'] = $this->Categories_model->getTypes_w();
        
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
                        
                $response = $this->Items_model->addItemPOST();
                echo json_encode($response);
                }


        }

        public function showAllItems(){
                $result =$this->Items_model->getAllItems();		 
		echo json_encode($result);
        }
            
        public function EditItem(){
                $result = $this->Items_model->editItem();
		echo json_encode($result);
        }
        public function getmoreInfo(){
                $result = $this->Items_model->getmoreInfo();
		echo json_encode($result);
        }

        public function updateItem(){
                $result = $this->Items_model->updateItem();
		echo json_encode($result);
        }

        public function DeleteItem(){
                // Get the item ID
                $id = $this->input->post('id');
                
                // Call the delete function from the model
                $result = $this->Items_model->deleteItem($id);
            
                // Prepare the response
                $msg['status'] = 'failure'; // Default status
                if($result){
                    $msg['status'] = 'success'; // Update to success if deletion is successful
                }
            
                // Return JSON response
                echo json_encode($msg);
            }
            
        public function getItems(){//get name& id only
                $result = $this->Items_model->getItems();
		echo json_encode($result);
        }

//-------------------------------------------NEW FUNCTION-----------------------------------------------

public function fetchItemNames() {
        $items = $this->Stocks_model->getAllItems();
        echo json_encode($items); // Return items in JSON format
    }
    
    public function getItemStockQuantity() {
        $item_id = $this->input->post('item_id');
        $quantity = $this->Stocks_model->getItemStockQuantity($item_id); // Get stock quantity
        echo json_encode(['quantity' => $quantity]); // Return quantity as JSON
    }
    
    public function getWarehouses() {
        // Load the model
        $this->load->model('Items_model');
        
        // Get warehouses from the model
        $warehouses = $this->Items_model->getWarehouses();
        
        // Return the warehouses in JSON format
        echo json_encode($warehouses);
    }
    
    public function saveItemWarehouse() {
        // Get itmID and warehouseID from the POST request
        $itmID = $this->input->post('itmID');
        $warehouseID = $this->input->post('warehouseID');
    
        // Load the model
        $this->load->model('Items_model');
        
        // Save the relationship in the ezy_pos_item_warehouse table
        $result = $this->Items_model->saveItemWarehouse($itmID, $warehouseID);
    
        if ($result) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
    
    public function updateItemWarehouse() {
        $itmID = $this->input->post('itmID');
        $warehouseID = $this->input->post('warehouseID');
    
        $result = $this->Items_model->updateItemWarehouse($itmID, $warehouseID);
        if ($result) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
    
   
    public function getAllItemsForBulkAssign() {
        $items = $this->Items_model->getAllItemsForBulkAssign();
        echo json_encode($items); // Return items in JSON format
    }
    
    public function bulkAssignItemsToWarehouse() {
        $itemIds = $this->input->post('itemIds'); // Array of item ids
        $warehouseId = $this->input->post('warehouseId'); // Warehouse ID
    
        $response = $this->Items_model->bulkAssignItemsToWarehouse($itemIds, $warehouseId);
        echo json_encode($response); // Return response status
    }
    
}