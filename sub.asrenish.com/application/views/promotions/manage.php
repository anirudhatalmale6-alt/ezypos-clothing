<div class="wrapper">
<div class="container-fluid">

    <?php if(!$ready): ?>
    <div class="row"><div class="col-12">
        <div class="alert alert-warning">
            <strong>Setup needed:</strong> The promotions database tables are not created yet. Please run
            <code>application/migrations/loyalty_promotions_tables.sql</code> on the database, then reload this page.
        </div>
    </div></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-tags"></i> <span id="form_title">Add Promotion</span></h4>
                <form id="promoForm">
                    <input type="hidden" id="edit_promo_id" value="">
                    <div class="form-group">
                        <label>Promotion Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="p_name" placeholder="e.g. Weekend 10% Off">
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label>Discount Type</label>
                            <select class="form-control" id="p_type">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed (LKR)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Value<span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" id="p_value" placeholder="e.g. 10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Applies To</label>
                        <select class="form-control" id="p_scope">
                            <option value="bill">Whole Bill</option>
                            <option value="item">Specific Item</option>
                            <option value="category">Category</option>
                            <option value="payment">Payment Method</option>
                        </select>
                    </div>
                    <div class="form-group" id="target_item_div" style="display:none;">
                        <label>Item</label>
                        <select class="form-control" id="p_target_item">
                            <option value="">Select item</option>
                            <?php if(isset($items) && $items): foreach($items as $it): ?>
                            <option value="<?php echo $it->itm_id; ?>"><?php echo htmlspecialchars($it->itm_name); ?> (<?php echo $it->itm_code; ?>)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group" id="target_cat_div" style="display:none;">
                        <label>Category</label>
                        <select class="form-control" id="p_target_cat">
                            <option value="">Select category</option>
                            <?php if(isset($categories) && $categories): foreach($categories as $ct): ?>
                            <option value="<?php echo $ct->cat_id; ?>"><?php echo htmlspecialchars($ct->cat_name); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group" id="target_pm_div" style="display:none;">
                        <label>Payment Method</label>
                        <select class="form-control" id="p_target_pm">
                            <option value="">Select method</option>
                            <?php if(isset($payments) && $payments): foreach($payments as $pm): ?>
                            <option value="<?php echo $pm->pm_id; ?>"><?php echo htmlspecialchars($pm->pm_name); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Minimum Bill (LKR)</label>
                        <input type="number" step="any" class="form-control" id="p_min_bill" placeholder="0 = no minimum" value="0">
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label>Start Date</label>
                            <input type="date" class="form-control" id="p_start">
                        </div>
                        <div class="col-6">
                            <label>End Date</label>
                            <input type="date" class="form-control" id="p_end">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label>Priority</label>
                            <input type="number" class="form-control" id="p_priority" value="0">
                        </div>
                        <div class="col-6">
                            <label>Auto Apply</label>
                            <select class="form-control" id="p_auto">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="status_div" style="display:none;">
                        <label>Status</label>
                        <select class="form-control" id="p_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success btn-block" id="btn_save_promo"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-secondary btn-block" id="btn_cancel_promo" style="display:none;">Cancel Edit</button>
                </form>
            </div>
        </div>

        <div class="col-lg-7 col-md-7">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-list"></i> Promotions</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="bg-light">
                            <tr><th>Name</th><th>Discount</th><th>Applies To</th><th>Dates</th><th>Min Bill</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if(isset($promotions) && count($promotions)): foreach($promotions as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p->promo_name); ?><?php echo $p->promo_auto ? '' : ' <span class="badge badge-secondary">manual</span>'; ?></td>
                                <td><?php echo $p->promo_type=='percentage' ? rtrim(rtrim(number_format($p->promo_value,2),'0'),'.').'%' : 'LKR '.number_format($p->promo_value,2); ?></td>
                                <td>
                                    <?php
                                    $scopeLbl = ucfirst($p->promo_scope);
                                    echo $p->promo_scope=='bill' ? 'Whole Bill' : $scopeLbl;
                                    ?>
                                </td>
                                <td style="font-size:11px;">
                                    <?php echo $p->promo_start_date ? date('d/m/y',strtotime($p->promo_start_date)) : '—'; ?>
                                    to
                                    <?php echo $p->promo_end_date ? date('d/m/y',strtotime($p->promo_end_date)) : '—'; ?>
                                </td>
                                <td class="text-right"><?php echo $p->promo_min_bill>0 ? number_format($p->promo_min_bill,2) : '—'; ?></td>
                                <td>
                                    <?php if($p->promo_status==1): ?><span class="badge badge-success">Active</span>
                                    <?php else: ?><span class="badge badge-danger">Inactive</span><?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-info btn-edit-promo"
                                        data-id="<?php echo $p->promo_id; ?>"
                                        data-name="<?php echo htmlspecialchars($p->promo_name); ?>"
                                        data-type="<?php echo $p->promo_type; ?>"
                                        data-value="<?php echo $p->promo_value; ?>"
                                        data-scope="<?php echo $p->promo_scope; ?>"
                                        data-target="<?php echo $p->promo_target_id; ?>"
                                        data-minbill="<?php echo $p->promo_min_bill; ?>"
                                        data-start="<?php echo $p->promo_start_date; ?>"
                                        data-end="<?php echo $p->promo_end_date; ?>"
                                        data-priority="<?php echo $p->promo_priority; ?>"
                                        data-auto="<?php echo $p->promo_auto; ?>"
                                        data-status="<?php echo $p->promo_status; ?>">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <?php if($p->promo_status==1): ?>
                                    <button class="btn btn-xs btn-warning btn-toggle-promo" data-id="<?php echo $p->promo_id; ?>" data-status="0"><i class="fa fa-pause"></i></button>
                                    <?php else: ?>
                                    <button class="btn btn-xs btn-success btn-toggle-promo" data-id="<?php echo $p->promo_id; ?>" data-status="1"><i class="fa fa-play"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No promotions yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted" style="font-size:12px;">
                    Active auto-apply promotions are automatically detected and applied in the Sales screen when their
                    conditions (scope, minimum bill, date range) are met. The best applicable discount per item and the
                    best bill-level promotion are applied.
                </p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';

function toggleTargets(){
    var s = $('#p_scope').val();
    $('#target_item_div').toggle(s=='item');
    $('#target_cat_div').toggle(s=='category');
    $('#target_pm_div').toggle(s=='payment');
}
$('#p_scope').change(toggleTargets);

function currentTarget(){
    var s = $('#p_scope').val();
    if(s=='item') return $('#p_target_item').val();
    if(s=='category') return $('#p_target_cat').val();
    if(s=='payment') return $('#p_target_pm').val();
    return '';
}

$('#btn_save_promo').click(function(){
    var name = $('#p_name').val().trim();
    var value = parseFloat($('#p_value').val());
    if(!name){ alert('Enter a promotion name'); return; }
    if(isNaN(value) || value<=0){ alert('Enter a valid discount value'); return; }
    var scope = $('#p_scope').val();
    if(scope!=='bill' && !currentTarget()){ alert('Please select the target for this scope'); return; }

    var editId = $('#edit_promo_id').val();
    var url = editId ? BASE_URL+'promotions/edit' : BASE_URL+'promotions/add';
    var data = {
        name: name, type: $('#p_type').val(), value: value, scope: scope,
        target_id: currentTarget(), min_bill: $('#p_min_bill').val() || 0,
        start_date: $('#p_start').val(), end_date: $('#p_end').val(),
        priority: $('#p_priority').val() || 0, auto: $('#p_auto').val()
    };
    if(editId){ data.promo_id = editId; data.status = $('#p_status').val(); }
    $.post(url, data, function(res){
        var d = (typeof res==='string') ? JSON.parse(res) : res;
        if(d.success){ location.reload(); } else { alert(d.msg || 'Could not save'); }
    });
});

$('.btn-edit-promo').click(function(){
    var b = $(this);
    $('#edit_promo_id').val(b.data('id'));
    $('#p_name').val(b.data('name'));
    $('#p_type').val(b.data('type'));
    $('#p_value').val(b.data('value'));
    $('#p_scope').val(b.data('scope'));
    toggleTargets();
    var scope = b.data('scope'), target = b.data('target');
    if(scope=='item') $('#p_target_item').val(target);
    if(scope=='category') $('#p_target_cat').val(target);
    if(scope=='payment') $('#p_target_pm').val(target);
    $('#p_min_bill').val(b.data('minbill'));
    $('#p_start').val(b.data('start'));
    $('#p_end').val(b.data('end'));
    $('#p_priority').val(b.data('priority'));
    $('#p_auto').val(b.data('auto'));
    $('#p_status').val(b.data('status'));
    $('#status_div').show();
    $('#form_title').text('Edit Promotion');
    $('#btn_save_promo').html('<i class="fa fa-save"></i> Update');
    $('#btn_cancel_promo').show();
    $('html,body').animate({scrollTop:0},300);
});

$('#btn_cancel_promo').click(function(){
    $('#promoForm')[0].reset();
    $('#edit_promo_id').val('');
    $('#status_div').hide();
    toggleTargets();
    $('#form_title').text('Add Promotion');
    $('#btn_save_promo').html('<i class="fa fa-save"></i> Save');
    $(this).hide();
});

$('.btn-toggle-promo').click(function(){
    $.post(BASE_URL+'promotions/toggle', {promo_id:$(this).data('id'), status:$(this).data('status')}, function(){
        location.reload();
    });
});
</script>
