<div class="wrapper">
<div class="container-fluid">

    <?php if(!$ready): ?>
    <div class="row"><div class="col-12">
        <div class="alert alert-warning">
            <strong>Setup needed:</strong> The loyalty database tables are not created yet. Please run
            <code>application/migrations/loyalty_promotions_tables.sql</code> on the database, then reload this page.
        </div>
    </div></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-star"></i> Loyalty Settings</h4>
                <form id="loyaltyForm">
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Loyalty Program</label>
                        <div class="col-5">
                            <select class="form-control" id="loyalty_enabled">
                                <option value="1" <?php echo $settings['loyalty_enabled']=='1'?'selected':''; ?>>Enabled</option>
                                <option value="0" <?php echo $settings['loyalty_enabled']=='0'?'selected':''; ?>>Disabled</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <h5 class="m-b-15">Earning</h5>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Points earned</label>
                        <div class="col-5"><input type="number" step="any" class="form-control" id="loyalty_earn_points" value="<?php echo $settings['loyalty_earn_points']; ?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">... per amount spent (LKR)</label>
                        <div class="col-5"><input type="number" step="any" class="form-control" id="loyalty_earn_amount" value="<?php echo $settings['loyalty_earn_amount']; ?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Minimum bill to earn (LKR)</label>
                        <div class="col-5"><input type="number" step="any" class="form-control" id="loyalty_min_purchase" value="<?php echo $settings['loyalty_min_purchase']; ?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Rounding of earned points</label>
                        <div class="col-5">
                            <select class="form-control" id="loyalty_round">
                                <option value="down" <?php echo $settings['loyalty_round']=='down'?'selected':''; ?>>Round down</option>
                                <option value="nearest" <?php echo $settings['loyalty_round']=='nearest'?'selected':''; ?>>Nearest</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <h5 class="m-b-15">Redemption</h5>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Value of 1 point (LKR)</label>
                        <div class="col-5"><input type="number" step="any" class="form-control" id="loyalty_redeem_value" value="<?php echo $settings['loyalty_redeem_value']; ?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Min. points to redeem</label>
                        <div class="col-5"><input type="number" step="any" class="form-control" id="loyalty_min_redeem" value="<?php echo $settings['loyalty_min_redeem']; ?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Max % of bill payable by points</label>
                        <div class="col-5"><input type="number" step="any" class="form-control" id="loyalty_max_redeem_pct" value="<?php echo $settings['loyalty_max_redeem_pct']; ?>"></div>
                    </div>
                    <button type="button" class="btn btn-success btn-block" id="btn_save_loyalty"><i class="fa fa-save"></i> Save Settings</button>
                </form>
                <p class="text-muted m-t-15" style="font-size:12px;">
                    Example: with the defaults above, a customer earns
                    <strong><?php echo $settings['loyalty_earn_points']; ?></strong> point(s) for every
                    <strong><?php echo $settings['loyalty_earn_amount']; ?></strong> LKR spent, and each point is worth
                    <strong><?php echo $settings['loyalty_redeem_value']; ?></strong> LKR when redeemed at checkout.
                </p>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-users"></i> Customers with Points</h4>
                <div class="table-responsive" style="max-height:520px; overflow-y:auto;">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr><th>Customer</th><th>Contact</th><th class="text-right">Points</th><th>Adjust</th></tr>
                        </thead>
                        <tbody>
                            <?php if(isset($customers) && count($customers)): foreach($customers as $c): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c->cus_name); ?></td>
                                <td><?php echo htmlspecialchars($c->cus_contact); ?></td>
                                <td class="text-right"><strong><?php echo number_format($c->cus_loyalty_points,2); ?></strong></td>
                                <td>
                                    <button class="btn btn-xs btn-info btn-adjust" data-id="<?php echo $c->cus_id; ?>" data-name="<?php echo htmlspecialchars($c->cus_name); ?>">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center text-muted">No customers have points yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <a href="<?php echo base_url('loyalty/report'); ?>" class="btn btn-secondary btn-block m-t-10"><i class="fa fa-list"></i> View Loyalty Ledger / Report</a>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Adjust points modal -->
<div class="modal" id="adjustModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Adjust Points</h5>
      <button type="button" class="close" data-dismiss="modal">&times;</button></div>
    <div class="modal-body">
        <p id="adjust_cus_name" class="font-weight-bold"></p>
        <input type="hidden" id="adjust_cus_id">
        <div class="form-group">
            <label>Points (+ to add, - to deduct)</label>
            <input type="number" step="any" class="form-control" id="adjust_points" placeholder="e.g. 50 or -20">
        </div>
        <div class="form-group">
            <label>Note</label>
            <input type="text" class="form-control" id="adjust_note" placeholder="Reason">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_confirm_adjust">Apply</button>
    </div>
  </div></div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';

$('#btn_save_loyalty').click(function(){
    var data = {
        loyalty_enabled: $('#loyalty_enabled').val(),
        loyalty_earn_points: $('#loyalty_earn_points').val(),
        loyalty_earn_amount: $('#loyalty_earn_amount').val(),
        loyalty_min_purchase: $('#loyalty_min_purchase').val(),
        loyalty_round: $('#loyalty_round').val(),
        loyalty_redeem_value: $('#loyalty_redeem_value').val(),
        loyalty_min_redeem: $('#loyalty_min_redeem').val(),
        loyalty_max_redeem_pct: $('#loyalty_max_redeem_pct').val()
    };
    $.post(BASE_URL + 'loyalty/saveSettings', data, function(res){
        var d = (typeof res === 'string') ? JSON.parse(res) : res;
        if(d.success){ swal({type:'success',title:'Saved',text:'Loyalty settings updated.'}); }
        else { swal({type:'error',title:'Error',text:d.msg || 'Could not save.'}); }
    });
});

$('.btn-adjust').click(function(){
    $('#adjust_cus_id').val($(this).data('id'));
    $('#adjust_cus_name').text($(this).data('name'));
    $('#adjust_points').val('');
    $('#adjust_note').val('');
    $('#adjustModal').modal('show');
});

$('#btn_confirm_adjust').click(function(){
    var pts = parseFloat($('#adjust_points').val());
    if(isNaN(pts) || pts === 0){ alert('Enter a non-zero point value'); return; }
    $.post(BASE_URL + 'loyalty/adjust', {
        cus_id: $('#adjust_cus_id').val(),
        points: pts,
        note: $('#adjust_note').val()
    }, function(res){
        var d = (typeof res === 'string') ? JSON.parse(res) : res;
        if(d.success){ location.reload(); } else { alert('Could not adjust'); }
    });
});
</script>
