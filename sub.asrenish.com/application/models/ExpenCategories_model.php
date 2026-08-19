<?php
class ExpenCategories_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    /**
     * Parent/subcategory lives in one self-referencing column: 0 means the row
     * IS a parent, anything else is the parent it hangs under. Everything is
     * guarded on the column existing, so the page still works on a database
     * where the v12 update has not been run yet - it just behaves as the old
     * flat list until then.
     */
    protected function _hasParent()
    {
        static $has = null;
        if($has === null){
            $has = in_array('expencat_parent_id', $this->db->list_fields('ezy_pos_expense_cat'));
        }
        return $has;
    }

    public function addExpensePOST()
    {
        $name = trim((string)$this->input->post('expensename'));
        if($name === ''){
            return array('ok' => false, 'msg' => 'Please enter a category name.');
        }

        $parent = 0;
        if($this->_hasParent()){
            $parent = intval($this->input->post('parentcat'));
            if($parent > 0){
                // Only two levels, exactly as asked. A subcategory cannot hang
                // under another subcategory.
                $p = $this->db->get_where('ezy_pos_expense_cat', array('expencat_id' => $parent))->row();
                if(!$p){
                    return array('ok' => false, 'msg' => 'That parent category no longer exists.');
                }
                if(intval($p->expencat_parent_id) !== 0){
                    return array('ok' => false, 'msg' => 'A subcategory cannot be put under another subcategory.');
                }
            }
        }

        // Two categories with the same name under the same parent would be
        // impossible to tell apart in the Expense dropdown. If the name is
        // already there but switched off, switch it back on rather than
        // making a second one.
        $this->db->where('expencat_catname', $name);
        if($this->_hasParent()){ $this->db->where('expencat_parent_id', $parent); }
        $existing = $this->db->get('ezy_pos_expense_cat')->row();
        if($existing){
            if(intval($existing->expencat_status) === 1){
                return array('ok' => false, 'msg' => 'That category already exists.');
            }
            $this->db->where('expencat_id', $existing->expencat_id)
                     ->update('ezy_pos_expense_cat', array('expencat_status' => 1));
            return array('ok' => true, 'msg' => 'That category already existed and has been made active again.');
        }

        $row = array('expencat_catname' => $name, 'expencat_status' => 1);
        if($this->_hasParent()){ $row['expencat_parent_id'] = $parent; }
        $this->db->insert('ezy_pos_expense_cat', $row);
        return array('ok' => true,
                     'msg' => $parent > 0 ? 'Subcategory added.' : 'Parent category added.');
    }

    /**
     * @param bool $includeInactive  true on the management page, so a category
     *                               that was switched off can be switched back
     *                               on. False everywhere else.
     */
    public function showAllExpenses($includeInactive = false){
            if($this->_hasParent()){
                $this->db->select('c.expencat_id, c.expencat_catname, c.expencat_status,
                                   c.expencat_parent_id, p.expencat_catname AS parent_name', false);
                $this->db->from('ezy_pos_expense_cat c');
                $this->db->join('ezy_pos_expense_cat p', 'p.expencat_id = c.expencat_parent_id', 'left');
                if(!$includeInactive){ $this->db->where('c.expencat_status', 1); }
                // Parents first, each followed by its own subcategories, so the
                // table reads as a tree rather than in insert order.
                $this->db->order_by('COALESCE(p.expencat_catname, c.expencat_catname)', 'ASC', false);
                $this->db->order_by('c.expencat_parent_id', 'ASC');
                $this->db->order_by('c.expencat_catname', 'ASC');
                $query = $this->db->get();
            } else {
                $this->db->order_by('expencat_id', 'desc');
                if(!$includeInactive){
                    $this->db->where('expencat_status', 1);
                }
                $query = $this->db->get('ezy_pos_expense_cat');
            }
			if($query->num_rows()>0){
				$rows = $query->result();
				foreach($rows as $r){
					if(!isset($r->expencat_parent_id)){ $r->expencat_parent_id = 0; }
					if(!isset($r->parent_name)){ $r->parent_name = null; }
				}
				return $rows;
			}
			else{
				return false;
			}
    }

    /**
     * Activate / deactivate. Deleting is deliberately not offered: expenses
     * already booked against a category would lose their name.
     *
     * Switching a parent off takes its subcategories out of the Expense screen
     * with it - they are unreachable without their parent - but their own flag
     * is left alone, so switching the parent back on restores exactly what was
     * there before.
     */
    public function setStatus(){
        $id     = intval($this->input->post('id'));
        $status = intval($this->input->post('status')) === 1 ? 1 : 0;
        if($id <= 0){ return array('ok' => false, 'msg' => 'No category chosen.'); }

        $extra = '';
        if($status === 1 && $this->_hasParent()){
            // Turning a subcategory back on is pointless while its parent is
            // off, so say so instead of appearing to do nothing.
            $me = $this->db->get_where('ezy_pos_expense_cat', array('expencat_id' => $id))->row();
            if($me && intval($me->expencat_parent_id) > 0){
                $p = $this->db->get_where('ezy_pos_expense_cat',
                        array('expencat_id' => $me->expencat_parent_id))->row();
                if($p && intval($p->expencat_status) !== 1){
                    $extra = ' Note: its parent "'.$p->expencat_catname.'" is still inactive,'
                           . ' so it will not appear on the Expense screen until that is activated too.';
                }
            }
        }

        $this->db->where('expencat_id', $id)
                 ->update('ezy_pos_expense_cat', array('expencat_status' => $status));
        return array('ok' => true, 'status' => $status,
                     'msg' => ($status ? 'Category activated.' : 'Category deactivated.').$extra);
    }
    public function EditExpenses(){
        $id = $this->input->post('id');
        $this->db->where('expencat_id', $id);
        $query = $this->db->get('ezy_pos_expense_cat');
        if($query->num_rows()>0){
            $row = $query->row();
            if(!isset($row->expencat_parent_id)){ $row->expencat_parent_id = 0; }
            // The screen needs to know whether this one already has children:
            // if it does, it cannot itself be moved under a parent.
            $row->child_count = 0;
            if($this->_hasParent()){
                $row->child_count = $this->db->where('expencat_parent_id', $row->expencat_id)
                                             ->count_all_results('ezy_pos_expense_cat');
            }
            return $row;
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

        $data   = array('expencat_catname' => $name);
        $parent = 0;
        if($this->_hasParent()){
            $parent = intval($this->input->post('edit_parentcat'));
            if($parent === $expen_id){
                return array('ok' => false, 'msg' => 'A category cannot be its own parent.');
            }
            if($parent > 0){
                $p = $this->db->get_where('ezy_pos_expense_cat', array('expencat_id' => $parent))->row();
                if(!$p){
                    return array('ok' => false, 'msg' => 'That parent category no longer exists.');
                }
                if(intval($p->expencat_parent_id) !== 0){
                    return array('ok' => false, 'msg' => 'A subcategory cannot be put under another subcategory.');
                }
                $kids = $this->db->where('expencat_parent_id', $expen_id)
                                 ->count_all_results('ezy_pos_expense_cat');
                if($kids > 0){
                    return array('ok' => false,
                                 'msg' => 'This category has subcategories of its own, so it has to stay a parent category.'
                                        . ' Move its subcategories first if you want to change it.');
                }
            }
            $data['expencat_parent_id'] = $parent;
        }

        $this->db->where('expencat_catname', $name)
                 ->where('expencat_id !=', $expen_id);
        if($this->_hasParent()){ $this->db->where('expencat_parent_id', $parent); }
        $clash = $this->db->get('ezy_pos_expense_cat')->row();
        if($clash){
            return array('ok' => false, 'msg' => 'Another category already has that name under the same parent.');
        }

        $this->db->where('expencat_id', $expen_id);
        $this->db->update('ezy_pos_expense_cat', $data);
        return array('ok' => true, 'msg' => 'Category updated.');
    }
    // Kept only for anything still calling the old address. It never deleted
    // anything - it set the status to 0, which is what Deactivate does now.
    // It also pointed at ezy_pos_expense instead of ezy_pos_expense_cat, so it
    // was switching off an EXPENSE with the same id as the category. Corrected.
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
        // Flat list, still used by the Expense edit box. A subcategory is shown
        // with its parent in front of it ("Transportation > Fuel") so the name
        // on its own is never ambiguous.
        if($this->_hasParent()){
            $this->db->select("c.expencat_id, c.expencat_catname, c.expencat_parent_id,
                               p.expencat_catname AS parent_name,
                               CASE WHEN c.expencat_parent_id > 0
                                    THEN CONCAT(p.expencat_catname, ' > ', c.expencat_catname)
                                    ELSE c.expencat_catname END AS display_name", false);
            $this->db->from('ezy_pos_expense_cat c');
            $this->db->join('ezy_pos_expense_cat p', 'p.expencat_id = c.expencat_parent_id', 'left');
            $this->db->where('c.expencat_status', 1);
            // A subcategory whose parent has been switched off is not offered.
            $this->db->group_start()
                     ->where('c.expencat_parent_id', 0)
                     ->or_where('p.expencat_status', 1)
                     ->group_end();
            $this->db->order_by('display_name', 'ASC', false);
            $query = $this->db->get();
        } else {
            $this->db->select('expencat_id, expencat_catname');
            $this->db->from('ezy_pos_expense_cat');
            $this->db->where('expencat_status', 1);
            $query = $this->db->get();
        }
        if($query->num_rows()>0){
            $rows = $query->result();
            foreach($rows as $r){
                if(!isset($r->expencat_parent_id)){ $r->expencat_parent_id = 0; }
                if(!isset($r->display_name)){ $r->display_name = $r->expencat_catname; }
            }
            return $rows;
        }
        else{
            return false;
        }
    }

    /**
     * The two-dropdown data for the Expense screen: every active parent, and
     * under it every active subcategory. Returned as a plain array so the view
     * can hand it straight to the browser.
     */
    public function getCategoryTree(){
        $rows = $this->getExpenCategories();
        $out  = array();
        if($rows){
            foreach($rows as $r){
                $out[] = array(
                    'id'        => intval($r->expencat_id),
                    'name'      => $r->expencat_catname,
                    'parent_id' => intval($r->expencat_parent_id),
                    'display'   => $r->display_name
                );
            }
        }
        return $out;
    }
}
