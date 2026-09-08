<!-- Past Advance Returns. Read-only: a completed return is a record, not a draft. -->
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title"><i class="fa fa-list"></i> Advance Returns</h4>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-4">
                        <select class="form-control" id="al_store">
                            <option value="all">All Branches</option>
                            <?php foreach ($stores as $s) {
                                $sid = is_array($s) ? $s['store_id'] : $s->store_id;
                                $sn  = is_array($s) ? $s['store_name'] : $s->store_name; ?>
                                <option value="<?php echo $sid; ?>"><?php echo htmlspecialchars($sn); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-3"><input class="form-control datepic" id="al_from" placeholder="From.."></div>
                    <div class="col-3"><input class="form-control datepic" id="al_to" placeholder="To.."></div>
                    <div class="col-2"><button class="btn btn-primary" id="al_search"><i class="fa fa-search"></i></button></div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Ref</th><th>Date</th><th>Branch</th><th>Customer</th>
                                <th>Bill</th><th>Lines</th><th>Qty</th><th>Refund</th><th>As</th><th>Slip</th>
                            </tr>
                        </thead>
                        <tbody id="al_body"><tr><td colspan="10" class="text-center text-muted">Press search.</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    function esc(v){
        if(v === null || typeof v === 'undefined') return '';
        return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function money(v){ return (parseFloat(v)||0).toFixed(2); }

    $('.datepic').datepicker({ dateFormat: 'yy-mm-dd' });

    function load(){
        $.ajax({
            type:'POST',
            url:'<?php echo base_url("AdvanceReturn/getList"); ?>',
            data:{ from: $('#al_from').val(), to: $('#al_to').val(), store_id: $('#al_store').val() },
            dataType:'json',
            success:function(rows){
                if(!rows || rows.length === 0){
                    $('#al_body').html('<tr><td colspan="10" class="text-center text-muted">Nothing found.</td></tr>');
                    return;
                }
                var html = '';
                for(var i=0; i<rows.length; i++){
                    var r = rows[i];
                    html += '<tr>'
                         +  '<td>'+esc(r.adv_ref_no)+'</td>'
                         +  '<td>'+esc(r.adv_created_at)+'</td>'
                         +  '<td>'+esc(r.store_name || '-')+'</td>'
                         +  '<td>'+esc(r.cus_name || '-')+'</td>'
                         +  '<td>'+(r.adv_without_bill == 1 ? '<span class="text-muted">no bill</span>' : esc(r.adv_bill_ref))+'</td>'
                         +  '<td style="text-align:right;">'+esc(r.line_count)+'</td>'
                         +  '<td style="text-align:right;">'+money(r.qty_total)+'</td>'
                         +  '<td style="text-align:right;">'+money(r.adv_total)+'</td>'
                         +  '<td>'+(r.adv_refund_mode === 'store_credit' ? 'Store credit' : 'Cash')+'</td>'
                         +  '<td><a class="btn btn-sm btn-info" target="_blank" href="<?php echo base_url("AdvanceReturn/slip/"); ?>'+r.adv_id+'"><i class="fa fa-print"></i></a></td>'
                         +  '</tr>';
                }
                $('#al_body').html(html);
            },
            error:function(){ $('#al_body').html('<tr><td colspan="10" class="text-center text-danger">Could not load.</td></tr>'); }
        });
    }

    $('#al_search').click(load);
    load();
});
</script>
