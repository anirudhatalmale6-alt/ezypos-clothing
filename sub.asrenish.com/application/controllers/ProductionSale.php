<?php
class ProductionSale extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $this->load->model('ProductionSale_model');
        $this->load->model('Configs_model');
        $this->load->model('Stores_model');
        $this->load->model('Customers_model');
        $this->load->model('Items_model');
    }

    public function addProductionSale($page = 'index')
    {
        if (!file_exists(APPPATH . 'views/transactions/' . $page . '.php')) {
            show_404();
        }
        $data['title'] = ucfirst($page);
        $data['config'] = $this->Configs_model->getConfigName();

        // Load stores based on role
        if ($this->session->userdata('userrole') == 1) {
            $data['storeLoc'] = $this->Stores_model->getAllStores();
        } else {
            $data['storeLoc'] = $this->Stores_model->getAllStoresfornonadmin($_SESSION['userid']);
        }

        $data['customers'] = $this->Customers_model->getAllCustomers();
        $data['items'] = $this->ProductionSale_model->getAllActiveItems();
        $data['nextCode'] = $this->ProductionSale_model->getNextCode();

        $this->load->view('templates/header', $data);
        $this->load->view('transactions/' . $page, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function createOrder()
    {
        $data = array(
            'prodsale_code' => $this->input->post('code'),
            'prodsale_cus_id' => $this->input->post('cus_id'),
            'prodsale_store_id' => $this->input->post('store_id'),
            'prodsale_date' => $this->input->post('order_date'),
            'prodsale_delivery_date' => $this->input->post('delivery_date'),
            'prodsale_tailoring_charge' => $this->input->post('tailoring_charge'),
            'prodsale_notes' => $this->input->post('notes'),
            'prodsale_status' => 'Pending',
            'prodsale_createdby' => $this->session->userdata('userid')
        );
        $id = $this->ProductionSale_model->createOrder($data);
        echo json_encode($id);
    }

    public function addItem()
    {
        $data = array(
            'prodsaleitem_prodsale_id' => $this->input->post('prodsale_id'),
            'prodsaleitem_item_id' => $this->input->post('item_id'),
            'prodsaleitem_qty' => $this->input->post('qty'),
            'prodsaleitem_unit_price' => $this->input->post('unit_price'),
            'prodsaleitem_total' => $this->input->post('qty') * $this->input->post('unit_price'),
            'prodsaleitem_type' => $this->input->post('type')
        );
        $result = $this->ProductionSale_model->addItem($data);
        // Recalculate totals
        $this->ProductionSale_model->recalculateTotals($this->input->post('prodsale_id'));
        echo json_encode($result);
    }

    public function addService()
    {
        $data = array(
            'prodsvc_prodsale_id' => $this->input->post('prodsale_id'),
            'prodsvc_description' => $this->input->post('description'),
            'prodsvc_charge' => $this->input->post('charge')
        );
        $result = $this->ProductionSale_model->addService($data);
        $this->ProductionSale_model->recalculateTotals($this->input->post('prodsale_id'));
        echo json_encode($result);
    }

    public function getItems()
    {
        $id = $this->input->post('prodsale_id');
        $result = $this->ProductionSale_model->getItems($id);
        echo json_encode($result);
    }

    public function getServices()
    {
        $id = $this->input->post('prodsale_id');
        $result = $this->ProductionSale_model->getServices($id);
        echo json_encode($result);
    }

    public function getOrderDetails()
    {
        $id = $this->input->post('prodsale_id');
        $result = $this->ProductionSale_model->getOrderDetails($id);
        echo json_encode($result);
    }

    public function deleteItem()
    {
        $id = $this->input->post('item_id');
        $prodsale_id = $this->input->post('prodsale_id');
        $this->ProductionSale_model->deleteItem($id);
        $this->ProductionSale_model->recalculateTotals($prodsale_id);
        echo json_encode(true);
    }

    public function deleteService()
    {
        $id = $this->input->post('svc_id');
        $prodsale_id = $this->input->post('prodsale_id');
        $this->ProductionSale_model->deleteService($id);
        $this->ProductionSale_model->recalculateTotals($prodsale_id);
        echo json_encode(true);
    }

    public function updateStatus()
    {
        $id = $this->input->post('prodsale_id');
        $status = $this->input->post('status');
        $this->ProductionSale_model->updateStatus($id, $status);
        echo json_encode(true);
    }

    public function addPayment()
    {
        $id = $this->input->post('prodsale_id');
        $amount = $this->input->post('amount');
        $this->ProductionSale_model->addPayment($id, $amount);
        echo json_encode(true);
    }

    public function getItemPrice()
    {
        $itemId = $this->input->post('item_id');
        $this->db->select('itm_sellingprice');
        $this->db->where('itm_id', $itemId);
        $q = $this->db->get('ezy_pos_items');
        $row = $q->row();
        echo json_encode($row ? $row->itm_sellingprice : 0);
    }

    // Listing page
    public function allProductionSales($page = 'index')
    {
        if (!file_exists(APPPATH . 'views/listing/' . $page . '.php')) {
            show_404();
        }
        $data['title'] = ucfirst($page);
        $data['config'] = $this->Configs_model->getConfigName();

        $this->load->view('templates/header', $data);
        $this->load->view('listing/' . $page, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function getAllOrders()
    {
        $result = $this->ProductionSale_model->getAllOrders();
        echo json_encode($result);
    }

    // Print invoice
    public function print_inv($id = 0)
    {
        if ($id == 0) { $id = $this->uri->segment(3); }
        $data['order'] = $this->ProductionSale_model->getOrderDetails($id);
        $data['items'] = $this->ProductionSale_model->getItems($id);
        $data['services'] = $this->ProductionSale_model->getServices($id);
        $data['config'] = $this->Configs_model->getConfigName();
        $this->load->view('transactions/prodsale_invoice', $data);
    }
}
