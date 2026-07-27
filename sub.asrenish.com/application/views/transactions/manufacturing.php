<div class="content">
    <div class="container-fluid">

    <?php if(!$ready): ?>
        <div class="alert alert-warning" style="margin-top:20px;">
            <strong>Setup needed:</strong> Please run the migration
            <code>application/migrations/production_redesign_tables.sql</code> to enable the Production module.
        </div>
    <?php else: ?>

        <!-- ============ Gate Pass selection popup (Step 1) ============ -->
        <div class="modal" id="gpModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background:#34495e;color:#fff;">
                <h5 class="modal-title"><i class="fa fa-clipboard"></i> Select or Create Gate Pass</h5>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Search Existing Gate Pass</label>
                  <input type="text" class="form-control" id="gp_search" placeholder="Type gate pass number...">
                  <input type="hidden" id="gp_selected_id">
                  <div id="gp_search_results" class="list-group" style="max-height:180px;overflow:auto;margin-top:5px;"></div>
                </div>
                <hr>
                <div class="form-group">
                  <label>Or Create New Gate Pass</label>
                  <div class="row">
                    <div class="col-7">
                      <input type="text" class="form-control" id="gp_new_code" placeholder="Enter Gate Pass Number" autocomplete="off">
                    </div>
                    <div class="col-5">
                      <button class="btn btn-outline-primary btn-block" id="btn_create_gp"><i class="fa fa-plus"></i> Create New</button>
                    </div>
                  </div>
                  <small class="text-muted">
                    Source Warehouse:
                    <strong><?php echo $defaultWarehouse ? $defaultWarehouse->store_name : '(none configured)'; ?></strong>
                    (default, auto-selected)
                  </small>
                  <?php if(!$defaultWarehouse): ?>
                  <br><small class="text-danger">No warehouse configured. Mark a store as Warehouse first.</small>
                  <?php endif; ?>
                  <input type="hidden" id="gp_default_store" value="<?php echo $defaultWarehouse ? $defaultWarehouse->store_id : ''; ?>">
                  <input type="hidden" id="gp_default_store_name" value="<?php echo $defaultWarehouse ? htmlspecialchars($defaultWarehouse->store_name, ENT_QUOTES) : ''; ?>">
                </div>
                <div id="gp_chosen_info" style="display:none;background:#eafaf1;padding:8px;border-radius:4px;">
                  <strong>Gate Pass:</strong> <span id="gp_chosen_code"></span>
                  &nbsp; <span class="badge badge-info" id="gp_chosen_status"></span>
                </div>
              </div>
              <div class="modal-footer">
                <a href="<?php echo base_url('show-all-productions'); ?>" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-success" id="btn_gp_next" disabled><i class="fa fa-arrow-right"></i> Next</button>
              </div>
            </div>
          </div>
        </div>

        <!-- ============ Production screen ============ -->
        <div id="prodScreen" style="display:none;">
          <div class="row" style="margin-top:12px;">
            <!-- LEFT: header + raw material -->
            <div class="col-lg-5 col-md-5">
              <div class="card-box">
                <h4 class="header-title m-t-0"><i class="fa fa-industry"></i> Production</h4>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Source Warehouse</label>
                  <div class="col-7"><input type="text" class="form-control" id="hdr_store" readonly></div>
                </div>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Gate Pass</label>
                  <div class="col-7"><input type="text" class="form-control" id="hdr_gp" readonly></div>
                </div>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Production ID</label>
                  <div class="col-7"><input type="text" class="form-control" id="hdr_prod" readonly></div>
                </div>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label"><span id="gp_status_lbl" class="badge badge-secondary">Draft</span></label>
                  <div class="col-7 text-right">
                    <a href="#" id="btn_print_gp" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fa fa-print"></i> Gate Pass</a>
                  </div>
                </div>
                <hr>
                <h5><i class="fa fa-scissors"></i> Raw Material (Fabric)</h5>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Fabric</label>
                  <div class="col-7">
                    <input type="text" class="form-control" id="raw_search" placeholder="Search fabric...">
                    <input type="hidden" id="raw_item_id">
                  </div>
                </div>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Material Qty</label>
                  <div class="col-7">
                    <div class="input-group">
                      <input type="number" step="0.01" class="form-control" id="raw_qty" placeholder="0.00">
                      <div class="input-group-append"><span class="input-group-text" id="raw_uom_lbl">UOM</span></div>
                    </div>
                    <small class="text-muted">Latest GRN cost: <span id="raw_grn_cost_lbl">0.00</span> / <span id="raw_uom_lbl2">UOM</span> &nbsp; | In stock: <span id="raw_stock_lbl">0</span></small>
                  </div>
                </div>
                <input type="hidden" id="raw_grn_cost" value="0">
                <input type="hidden" id="raw_uom" value="">
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Tailor</label>
                  <div class="col-7">
                    <select class="form-control" id="prod_tailor">
                      <option value="">-- Select Tailor --</option>
                      <?php if(!empty($tailors)){ foreach($tailors as $t){ ?>
                      <option value="<?php echo $t->sup_id; ?>"><?php echo htmlspecialchars($t->sup_name); ?></option>
                      <?php }} ?>
                    </select>
                    <?php if(empty($tailors)): ?>
                    <small class="text-muted">No tailors yet. Mark a Supplier as "Is Tailor" to list them here.</small>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group row mb-2">
                  <label class="col-5 col-form-label">Notes</label>
                  <div class="col-7"><textarea class="form-control" id="prod_notes" rows="2"></textarea></div>
                </div>

                <hr>
                <h6>Productions in this Gate Pass</h6>
                <div id="gp_prod_list" style="max-height:150px;overflow:auto;"></div>
                <button class="btn btn-sm btn-outline-primary btn-block m-t-5" id="btn_new_prod"><i class="fa fa-plus"></i> New Production (same gate pass)</button>
              </div>
            </div>

            <!-- RIGHT: outputs + bill -->
            <div class="col-lg-7 col-md-7">
              <div class="card-box">
                <h5 class="header-title m-t-0"><i class="fa fa-cubes"></i> Output Items</h5>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm" id="out_table">
                    <thead>
                      <tr style="background:#eef;">
                        <th style="width:22%;">Output Item</th>
                        <th style="width:12%;">Mat. Used</th>
                        <th style="width:12%;">Qty Produced</th>
                        <th style="width:13%;">Additional</th>
                        <th style="width:13%;">Tailoring</th>
                        <th style="width:16%;">Total</th>
                        <th style="width:6%;"></th>
                      </tr>
                    </thead>
                    <tbody id="out_body"></tbody>
                  </table>
                </div>
                <button class="btn btn-sm btn-primary" id="btn_add_out"><i class="fa fa-plus"></i> Add Output Row</button>

                <hr>
                <h6><i class="fa fa-truck"></i> Overall Production Charges (Transport / Loading / Miscellaneous)</h6>
                <div class="table-responsive">
                  <table class="table table-sm" id="chg_table">
                    <tbody id="chg_body"></tbody>
                  </table>
                </div>
                <button class="btn btn-sm btn-outline-secondary" id="btn_add_chg"><i class="fa fa-plus"></i> Add Charge</button>

                <hr>
                <div class="row">
                  <div class="col-6">
                    <div style="background:#fff8e1;padding:8px;border-radius:4px;">
                      <div>Raw Material Issued: <strong><span id="sum_raw_issued">0</span> <span class="raw_uom_txt">UOM</span></strong></div>
                      <div>Material Used: <strong><span id="sum_used">0</span></strong></div>
                      <div>Remaining Raw: <strong class="text-danger"><span id="sum_remaining">0</span> <span class="raw_uom_txt">UOM</span></strong></div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div style="background:#e8f5e9;padding:8px;border-radius:4px;">
                      <div>Production Bill (items): <strong><span id="sum_subtotal">0.00</span></strong></div>
                      <div>Overall Charges: <strong><span id="sum_overall">0.00</span></strong></div>
                      <div style="font-size:16px;">Final Bill: <strong><span id="sum_final">0.00</span></strong></div>
                    </div>
                  </div>
                </div>

                <hr>
                <div class="text-right" id="action_buttons">
                  <button class="btn btn-primary" id="btn_save_prod"><i class="fa fa-save"></i> Save Production</button>
                  <button class="btn btn-warning" id="btn_dispatch"><i class="fa fa-paper-plane"></i> Dispatch Gate Pass</button>
                  <button class="btn btn-info" id="btn_receive" style="display:none;"><i class="fa fa-download"></i> Receive Outputs</button>
                  <button class="btn btn-success" id="btn_complete" style="display:none;"><i class="fa fa-check"></i> Complete Production</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php endif; ?>

    </div>
</div>

<!-- Receiving popup (Step 15) -->
<div class="modal" id="receiveModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#2980b9;color:#fff;">
        <h5 class="modal-title"><i class="fa fa-download"></i> Receive Output Items</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <p class="text-muted">Confirm the actual quantity received into the warehouse. Received + Damaged must equal Produced.</p>
        <table class="table table-bordered table-sm">
          <thead><tr style="background:#eef;"><th>Item</th><th>Produced</th><th>Received</th><th>Damaged</th><th>Remarks</th></tr></thead>
          <tbody id="recv_body"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="btn_confirm_receive">Confirm & Add to Stock</button>
      </div>
    </div>
  </div>
</div>

<!-- Complete popup (Step 11) -->
<div class="modal" id="completeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#27ae60;color:#fff;">
        <h5 class="modal-title"><i class="fa fa-check"></i> Complete Production</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Remaining raw material: <strong><span id="cmp_remaining">0</span> <span class="raw_uom_txt">UOM</span></strong></p>
        <div class="form-group row">
          <label class="col-6 col-form-label">Quantity to Return to Stock</label>
          <div class="col-6"><input type="number" step="0.01" class="form-control" id="cmp_return" value="0"></div>
        </div>
        <div class="form-group row">
          <label class="col-6 col-form-label">Damaged Quantity</label>
          <div class="col-6"><input type="number" step="0.01" class="form-control" id="cmp_damaged" value="0"></div>
        </div>
        <small class="text-muted">Return + Damaged must equal the remaining quantity. Only the returned quantity goes back into warehouse stock.</small>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="btn_confirm_complete">Complete</button>
      </div>
    </div>
  </div>
</div>

<script>
var MBASE = '<?php echo base_url(); ?>';
var FABRICS = [<?php foreach($fabrics as $f){ echo '{label:"'.addslashes($f->itm_code.' - '.$f->itm_name).'",value:"'.$f->itm_id.'",uom:"'.addslashes($f->itm_uom).'"},'; } ?>];
var FINISHED = [<?php foreach($finishedItems as $f){ echo '{label:"'.addslashes($f->itm_code.' - '.$f->itm_name).'",value:"'.$f->itm_id.'",name:"'.addslashes($f->itm_name).'"},'; } ?>];

var GP = { id:0, code:'', store_id:0, store_name:'', status:'Draft' };
var CUR_PID = 0;          // production being edited
var CUR_STATUS = 'Draft'; // production status

var OPEN_PROD = <?php echo intval($openProd); ?>;

$(function(){
    // ---------- Edit Production: open straight into a production ----------
    if(OPEN_PROD > 0){
        $.get(MBASE+'manufacturing/getProduction/'+OPEN_PROD, function(res){
            var r = JSON.parse(res);
            if(!r.success || !r.production){ $('#gpModal').modal('show'); return; }
            $('#prodScreen').show();
            loadGatePass(r.production.p_gp_id, OPEN_PROD);
        });
        return;
    }
    // ---------- Gate pass popup ----------
    $('#gpModal').modal('show');

    $('#gp_search').on('input', function(){
        var term = $(this).val();
        $.get(MBASE + 'manufacturing/searchGatePasses', {term: term}, function(res){
            var rows = JSON.parse(res); var h='';
            rows.forEach(function(r){
                h += '<a href="#" class="list-group-item list-group-item-action gp-pick" data-id="'+r.gp_id+'" data-code="'+r.gp_code+'" data-store="'+r.gp_store_id+'" data-storename="'+(r.store_name||'')+'" data-status="'+r.gp_status+'">'
                   + r.gp_code + ' <small class="text-muted">('+(r.store_name||'')+' - '+r.gp_status+')</small></a>';
            });
            $('#gp_search_results').html(h || '<div class="text-muted p-2">No matches</div>');
        });
    });

    $(document).on('click', '.gp-pick', function(e){
        e.preventDefault();
        GP.id=$(this).data('id'); GP.code=$(this).data('code'); GP.store_id=$(this).data('store');
        GP.store_name=$(this).data('storename'); GP.status=$(this).data('status');
        $('#gp_chosen_code').text(GP.code); $('#gp_chosen_status').text(GP.status);
        $('#gp_chosen_info').show(); $('#btn_gp_next').prop('disabled', false);
    });

    $('#btn_create_gp').click(function(){
        var code = $.trim($('#gp_new_code').val());
        var store = $('#gp_default_store').val();
        if(!code){ swal({type:'error',title:'Gate Pass number required',text:'Please enter a gate pass number.'}); return; }
        if(!store){ swal({type:'error',title:'No warehouse',text:'No default warehouse is configured. Mark a store as Warehouse first.'}); return; }
        $.post(MBASE + 'manufacturing/createGatePass', {gp_code: code, store_id: store, notes:''}, function(res){
            var r = JSON.parse(res);
            if(!r.success){ swal({type:'error',title:'Error',text:r.msg||'Failed'}); return; }
            GP.id=r.gp_id; GP.code=r.gp_code; GP.store_id=r.store_id;
            GP.store_name=$('#gp_default_store_name').val(); GP.status='Draft';
            $('#gp_chosen_code').text(GP.code); $('#gp_chosen_status').text('Draft');
            $('#gp_chosen_info').show(); $('#btn_gp_next').prop('disabled', false);
        });
    });

    $('#btn_gp_next').click(function(){
        loadGatePass(GP.id);
        $('#gpModal').modal('hide');
        $('#prodScreen').show();
    });

    // ---------- Raw material autocomplete ----------
    $('#raw_search').autocomplete({
        source: FABRICS, minLength: 0,
        select: function(e, ui){
            e.preventDefault();
            $('#raw_search').val(ui.item.label);
            $('#raw_item_id').val(ui.item.value);
            $('#raw_uom').val(ui.item.uom);
            $('#raw_uom_lbl,#raw_uom_lbl2').text(ui.item.uom);
            $('.raw_uom_txt').text(ui.item.uom);
            $.post(MBASE+'manufacturing/fabricInfo', {item_id: ui.item.value, store_id: GP.store_id}, function(res){
                var r = JSON.parse(res);
                $('#raw_grn_cost').val(r.grn_cost);
                $('#raw_grn_cost_lbl').text(parseFloat(r.grn_cost).toFixed(2));
                $('#raw_stock_lbl').text(r.stock);
                recalc();
            });
        }
    }).on('focus', function(){ $(this).autocomplete('search',''); });

    $('#raw_qty').on('input', recalc);

    // ---------- Output rows ----------
    $('#btn_add_out').click(function(){ addOutRow(); });
    $('#btn_add_chg').click(function(){ addChgRow(); });

    // recalc on any output/charge change
    $(document).on('input', '.out-inp, .chg-inp', recalc);
    $(document).on('click', '.out-del', function(){ $(this).closest('tr').remove(); recalc(); });
    $(document).on('click', '.chg-del', function(){ $(this).closest('tr').remove(); recalc(); });

    // ---------- Save ----------
    $('#btn_save_prod').click(saveProduction);
    $('#btn_new_prod').click(function(){ resetProductionForm(); });

    // ---------- Dispatch ----------
    $('#btn_dispatch').click(function(){
        swal({title:'Dispatch gate pass?', text:'Raw materials will be issued out of the warehouse and the gate pass will be locked for editing.',
              type:'warning', showCancelButton:true, confirmButtonText:'Yes, dispatch'}, function(ok){
            if(!ok) return;
            $.post(MBASE+'manufacturing/dispatchGatePass', {gp_id: GP.id}, function(res){
                var r = JSON.parse(res);
                if(!r.success){ swal({type:'error',title:'Error',text:r.msg||'Failed'}); return; }
                swal({type:'success',title:'Dispatched',text:'Raw materials issued. Gate pass locked.'});
                loadGatePass(GP.id);
            });
        });
    });

    // ---------- Receive ----------
    $('#btn_receive').click(openReceive);
    $('#btn_confirm_receive').click(confirmReceive);

    // ---------- Complete ----------
    $('#btn_complete').click(openComplete);
    $('#btn_confirm_complete').click(confirmComplete);
});

function loadGatePass(gp_id, openProdId){
    $.get(MBASE+'manufacturing/getGatePass/'+gp_id, function(res){
        var r = JSON.parse(res);
        if(!r.success) return;
        GP.id=r.gatepass.gp_id; GP.code=r.gatepass.gp_code; GP.store_id=r.gatepass.gp_store_id;
        GP.store_name=r.gatepass.store_name; GP.status=r.gatepass.gp_status;
        $('#hdr_store').val(GP.store_name);
        $('#hdr_gp').val(GP.code);
        $('#gp_status_lbl').text(GP.status).removeClass().addClass('badge '+statusClass(GP.status));
        $('#btn_print_gp').attr('href', MBASE+'mfg-gate-pass-print/'+GP.id);
        renderProdList(r.productions);
        if(openProdId && openProdId > 0){
            loadProduction(openProdId);
        } else {
            resetProductionForm(r.nextProd);
            applyLock();
        }
    });
}

function renderProdList(prods){
    var h='';
    if(!prods || !prods.length){ h='<div class="text-muted">No productions yet.</div>'; }
    prods.forEach(function(p){
        h += '<a href="#" class="list-group-item list-group-item-action prod-pick" data-id="'+p.p_id+'">'
           + p.p_code + ' <small class="text-muted">'+(p.raw_name||'')+' - '+p.p_status+'</small></a>';
    });
    $('#gp_prod_list').html(h);
}
$(document).on('click', '.prod-pick', function(e){ e.preventDefault(); loadProduction($(this).data('id')); });

function resetProductionForm(nextProd){
    CUR_PID = 0; CUR_STATUS='Draft';
    if(nextProd) $('#hdr_prod').val(nextProd);
    $('#raw_search,#raw_item_id,#raw_uom,#prod_notes').val('');
    $('#prod_tailor').val('');
    $('#raw_qty').val(''); $('#raw_grn_cost').val('0'); $('#raw_grn_cost_lbl').text('0.00'); $('#raw_stock_lbl').text('0');
    $('#out_body').empty(); $('#chg_body').empty();
    addOutRow();
    recalc(); applyLock();
}

function loadProduction(p_id){
    $.get(MBASE+'manufacturing/getProduction/'+p_id, function(res){
        var r = JSON.parse(res); if(!r.success) return;
        var p = r.production; CUR_PID = p.p_id; CUR_STATUS = p.p_status;
        $('#hdr_prod').val(p.p_code);
        $('#raw_item_id').val(p.p_raw_item_id);
        $('#raw_search').val((p.raw_code?p.raw_code+' - ':'')+(p.raw_name||''));
        $('#raw_qty').val(parseFloat(p.p_raw_qty).toFixed(2));
        $('#raw_uom').val(p.p_raw_uom); $('#raw_uom_lbl,#raw_uom_lbl2').text(p.p_raw_uom); $('.raw_uom_txt').text(p.p_raw_uom);
        $('#raw_grn_cost').val(p.p_raw_grn_cost); $('#raw_grn_cost_lbl').text(parseFloat(p.p_raw_grn_cost).toFixed(2));
        $('#prod_tailor').val(p.tailor_id || p.p_tailor_id || '');
        $('#prod_notes').val(p.p_notes||'');
        $('#out_body').empty();
        r.outputs.forEach(function(o){ addOutRow(o); });
        if(!r.outputs.length) addOutRow();
        $('#chg_body').empty();
        r.charges.forEach(function(c){ addChgRow(c); });
        recalc(); applyLock();
    });
}

function addOutRow(o){
    var idx = Date.now() + Math.floor(Math.random()*1000);
    var tr = $('<tr>');
    tr.html(
      '<td><input type="text" class="form-control form-control-sm out-search" placeholder="Search item..."><input type="hidden" class="out-item"></td>'+
      '<td><input type="number" step="0.01" class="form-control form-control-sm out-inp out-used" value="0"></td>'+
      '<td><input type="number" step="0.01" class="form-control form-control-sm out-inp out-qty" value="0"></td>'+
      '<td><input type="number" step="0.01" class="form-control form-control-sm out-inp out-add" value="0"></td>'+
      '<td><input type="number" step="0.01" class="form-control form-control-sm out-inp out-tail" value="0"></td>'+
      '<td class="out-total text-right">0.00</td>'+
      '<td><button type="button" class="btn btn-sm btn-danger out-del"><i class="fa fa-times"></i></button></td>'
    );
    $('#out_body').append(tr);
    var si = tr.find('.out-search');
    si.autocomplete({ source: FINISHED, minLength:0, select: function(e,ui){ e.preventDefault(); si.val(ui.item.label); tr.find('.out-item').val(ui.item.value); }})
      .on('focus', function(){ $(this).autocomplete('search',''); });
    if(o){
        tr.find('.out-item').val(o.o_item_id);
        si.val((o.itm_code?o.itm_code+' - ':'')+(o.itm_name||''));
        tr.find('.out-used').val(parseFloat(o.o_material_used).toFixed(2));
        tr.find('.out-qty').val(parseFloat(o.o_qty_produced).toFixed(2));
        tr.find('.out-add').val(parseFloat(o.o_additional).toFixed(2));
        tr.find('.out-tail').val(parseFloat(o.o_tailoring).toFixed(2));
    }
}

function addChgRow(c){
    var tr = $('<tr>');
    tr.html(
      '<td style="width:60%;"><input type="text" class="form-control form-control-sm chg-inp chg-desc" placeholder="e.g. Transport"></td>'+
      '<td><input type="number" step="0.01" class="form-control form-control-sm chg-inp chg-amt" value="0"></td>'+
      '<td style="width:8%;"><button type="button" class="btn btn-sm btn-danger chg-del"><i class="fa fa-times"></i></button></td>'
    );
    $('#chg_body').append(tr);
    if(c){ tr.find('.chg-desc').val(c.c_description); tr.find('.chg-amt').val(parseFloat(c.c_amount).toFixed(2)); }
}

function recalc(){
    var grnCost = parseFloat($('#raw_grn_cost').val())||0;
    var rawQty  = parseFloat($('#raw_qty').val())||0;
    var used=0, subtotal=0, n=0;
    $('#out_body tr').each(function(){
        var mu = parseFloat($(this).find('.out-used').val())||0;
        var add= parseFloat($(this).find('.out-add').val())||0;
        var tl = parseFloat($(this).find('.out-tail').val())||0;
        var matCost = mu*grnCost;
        var total = matCost+add+tl;
        $(this).find('.out-total').text(total.toFixed(2));
        used += mu; subtotal += total; n++;
    });
    var overall=0;
    $('#chg_body tr').each(function(){ overall += parseFloat($(this).find('.chg-amt').val())||0; });
    var remaining = rawQty - used;
    $('#sum_raw_issued').text(rawQty.toFixed(2));
    $('#sum_used').text(used.toFixed(2));
    $('#sum_remaining').text(remaining.toFixed(2));
    $('#sum_subtotal').text(subtotal.toFixed(2));
    $('#sum_overall').text(overall.toFixed(2));
    $('#sum_final').text((subtotal+overall).toFixed(2));
}

function collectOutputs(){
    var arr=[];
    $('#out_body tr').each(function(){
        var id=$(this).find('.out-item').val();
        if(!id) return;
        arr.push({ item_id:id, material_used:$(this).find('.out-used').val()||0, qty_produced:$(this).find('.out-qty').val()||0,
                   additional:$(this).find('.out-add').val()||0, tailoring:$(this).find('.out-tail').val()||0 });
    });
    return arr;
}
function collectCharges(){
    var arr=[];
    $('#chg_body tr').each(function(){
        var amt=parseFloat($(this).find('.chg-amt').val())||0; var d=$(this).find('.chg-desc').val()||'';
        if(amt===0 && d.trim()==='') return;
        arr.push({ description:d, amount:amt });
    });
    return arr;
}

function saveProduction(){
    if(!$('#raw_item_id').val()){ swal({type:'error',title:'Fabric required',text:'Select the raw material (fabric).'}); return; }
    if(!(parseFloat($('#raw_qty').val())>0)){ swal({type:'error',title:'Qty required',text:'Enter the material quantity.'}); return; }
    var outs = collectOutputs();
    if(!outs.length){ swal({type:'error',title:'Outputs required',text:'Add at least one output item.'}); return; }
    $.post(MBASE+'manufacturing/saveProduction', {
        p_id: CUR_PID, gp_id: GP.id, store_id: GP.store_id,
        raw_item_id: $('#raw_item_id').val(), raw_qty: $('#raw_qty').val(),
        raw_uom: $('#raw_uom').val(), raw_grn_cost: $('#raw_grn_cost').val(),
        tailor_id: $('#prod_tailor').val() || 0,
        notes: $('#prod_notes').val(), outputs: JSON.stringify(outs), charges: JSON.stringify(collectCharges())
    }, function(res){
        var r = JSON.parse(res);
        if(!r.success){ swal({type:'error',title:'Error',text:r.msg||'Save failed'}); return; }
        CUR_PID = r.p_id; $('#hdr_prod').val(r.p_code);
        swal({type:'success',title:'Saved',text:'Production '+r.p_code+' saved.'});
        loadGatePass(GP.id); loadProduction(r.p_id);
    });
}

function openReceive(){
    if(!CUR_PID){ swal({type:'error',title:'No production',text:'Open a production first.'}); return; }
    $.get(MBASE+'manufacturing/getProduction/'+CUR_PID, function(res){
        var r = JSON.parse(res); if(!r.success) return;
        var h='';
        r.outputs.forEach(function(o){
            h += '<tr data-oid="'+o.o_id+'" data-produced="'+o.o_qty_produced+'">'
              + '<td>'+(o.itm_name||'')+'</td><td>'+parseFloat(o.o_qty_produced).toFixed(2)+'</td>'
              + '<td><input type="number" step="0.01" class="form-control form-control-sm recv-r" value="'+parseFloat(o.o_qty_produced).toFixed(2)+'"></td>'
              + '<td><input type="number" step="0.01" class="form-control form-control-sm recv-d" value="0"></td>'
              + '<td><input type="text" class="form-control form-control-sm recv-rem"></td></tr>';
        });
        $('#recv_body').html(h);
        $('#receiveModal').modal('show');
    });
}
function confirmReceive(){
    var items=[], ok=true;
    $('#recv_body tr').each(function(){
        var produced=parseFloat($(this).data('produced'))||0;
        var recv=parseFloat($(this).find('.recv-r').val())||0;
        var dmg=parseFloat($(this).find('.recv-d').val())||0;
        if(Math.abs((recv+dmg)-produced)>0.001){ ok=false; }
        items.push({ o_id:$(this).data('oid'), received:recv, damaged:dmg, remarks:$(this).find('.recv-rem').val()||'' });
    });
    if(!ok){ swal({type:'error',title:'Check quantities',text:'Received + Damaged must equal Produced for every row.'}); return; }
    $.post(MBASE+'manufacturing/receiveOutputs', {p_id:CUR_PID, items:JSON.stringify(items)}, function(res){
        var r = JSON.parse(res);
        if(!r.success){ swal({type:'error',title:'Error',text:r.msg||'Failed'}); return; }
        $('#receiveModal').modal('hide');
        swal({type:'success',title:'Received',text:'Finished goods added to warehouse stock.'});
        loadGatePass(GP.id); loadProduction(CUR_PID);
    });
}

function openComplete(){
    if(!CUR_PID){ return; }
    var remaining = parseFloat($('#sum_remaining').text())||0;
    $('#cmp_remaining').text(remaining.toFixed(2));
    $('#cmp_return').val(remaining.toFixed(2)); $('#cmp_damaged').val('0');
    $('#completeModal').modal('show');
}
function confirmComplete(){
    var ret=parseFloat($('#cmp_return').val())||0;
    var dmg=parseFloat($('#cmp_damaged').val())||0;
    var rem=parseFloat($('#cmp_remaining').text())||0;
    if(Math.abs((ret+dmg)-rem)>0.001){ swal({type:'error',title:'Check quantities',text:'Return + Damaged must equal remaining ('+rem.toFixed(2)+').'}); return; }
    $.post(MBASE+'manufacturing/completeProduction', {p_id:CUR_PID, return_qty:ret, damaged_qty:dmg}, function(res){
        var r = JSON.parse(res);
        if(!r.success){ swal({type:'error',title:'Error',text:r.msg||'Failed'}); return; }
        $('#completeModal').modal('hide');
        swal({type:'success',title:'Completed',text:'Production completed.'});
        loadGatePass(GP.id); loadProduction(CUR_PID);
    });
}

// Editing allowed only before dispatch; buttons switch by status
function applyLock(){
    var editable = (GP.status==='Draft' || GP.status==='Ready for Dispatch');
    $('#raw_search,#raw_qty,#prod_notes,#prod_tailor').prop('disabled', !editable);
    $('.out-inp,.out-search,.chg-inp').prop('disabled', !editable);
    $('#btn_add_out,#btn_add_chg,#btn_save_prod,#btn_new_prod').toggle(editable);
    $('#btn_dispatch').toggle(editable);
    // receive available after dispatch and before receiving; complete after received
    var dispatched = (GP.status==='Dispatched' || GP.status==='In Production' || GP.status==='Completed');
    $('#btn_receive').toggle(dispatched && CUR_PID>0 && CUR_STATUS==='Dispatched');
    $('#btn_complete').toggle(dispatched && CUR_PID>0 && CUR_STATUS==='Received');
    $('#gp_status_lbl').text(GP.status).removeClass().addClass('badge '+statusClass(GP.status));
}

function statusClass(s){
    if(s==='Draft') return 'badge-secondary';
    if(s==='Ready for Dispatch') return 'badge-warning';
    if(s==='Dispatched') return 'badge-primary';
    if(s==='In Production') return 'badge-info';
    if(s==='Completed') return 'badge-success';
    return 'badge-secondary';
}
</script>
