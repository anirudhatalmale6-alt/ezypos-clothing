<?php
class ExpenCategories_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function addExpensePOST()
    {
        $name = trim((string)$this->input->post('expensename'));
        if($name === ''){
            return array('ok' => false, 'msg' => 'Please enter a category name.');
        }

        // Two categories with the same name would be impossible to tell apart
        // in the Expense dropdown. If the name is already there but switched
        // off, switch it back on rather than making a second one.
        $existing = $this->db->get_where('ezy_pos_expense_cat',
                        array('expencat_catname' => $name))->row();
        if($existing){
            if(intval($existing->expencat_status) === 1){
                return array('ok' => false, 'msg' => 'That category already exists.');
            }
            $this->db->where('expencat_id', $existing->expencat_id)
                     ->update('ezy_pos_expense_cat', array('expencat_status' => 1));
            return array('ok' => true, 'msg' => 'That category already existed and has been made active again.');
        }

        $this->db->insert('ezy_pos_expense_cat', array(
            'expencat_catname' => $name,
            'expencat_status'  => 1
        ));
        return array('ok' => true, 'msg' => 'Category added.');
    }

    /**
     * @param bool $includeInactive  true on the management page, so a category
     *                               that was switched off can be switched back
     *                               on. False everywhere else.
     */
    public function showAllExpenses($includeInactive = false){
            $this->db->order_by('expencat_id', 'desc');
            if(!$includeInactive){
                $this->db->where('expencat_status', 1);
            }
			$query = $this->db->get('ezy_pos_expense_cat');
			if($query->num_rows()>0){
				return $query->result();
			}
			else{
				return false;
			}
    }

    /**
     * Activate / deactivate. Deleting is deliberately not offered: expenses
     * already booked against a category would lose their name.
     */
    public function setStatus(){
        $id     = intval($this->input->post('id'));
        $status = intval($this->input->post('status')) === 1 ? 1 : 0;
        if($id <= 0){ return array('ok' => false, 'msg' => 'No category chosen.'); }

        $this->db->where('expencat_id', $id)
                 ->update('ezy_pos_expense_cat', array('expencat_status' => $status));
        return array('ok' => true, 'status' => $status,
                     'msg' => $status ? 'Category activated.' : 'Category deactivated.');
    }
    public function EditExpenses(){        
        $id = $this->input->post('id');
        $this->db->where('expencat_id', $id);
        $query = $this->db->get('ezy_pos_expense_cat');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateExpenses(){
        $expen_id = intval($this->input->post('hiddenID'));
        $name     = trim((string)$this->input->post('edit_expensename'));
        if($expen_id <= 0 || $name === ''){
            return array('ok' => false, 'msg' => 'Please enter a category name.');
        }

        $clash = $this->db->where('expencat_catname', $name)
                          ->where('expencat_id !=', $expen_id)
                          ->get('ezy_pos_expense_cat')->row();
        if($clash){
            return array('ok' => false, 'msg' => 'Another category already has that name.');
        }

        $this->db->where('expencat_id', $expen_id);
        $this->db->update('ezy_pos_expense_cat', array('expencat_catname' => $name));
        return array('ok' => true, 'msg' => 'Category updated.');
    }
    public function DeleteExpenses(){
        $id = $this->input->post('id');
        $updateData = array(
            'expencat_status' => 0
        );
        $this->db->where('expencat_id', $id);
        $this->db->update('ezy_pos_expense_cat', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getExpenCategories(){// ID & NAME
        $this->db->select('expencat_id, expencat_catname');
        $this->db->from('ezy_pos_expense_cat');
        $this->db->where('expencat_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
}