<div class="content">
  <div class="container-fluid">
    <div class="row" style="margin-top:15px;">
      <div class="col-lg-8 offset-lg-2">

        <?php if(!$ready): ?>
          <div class="alert alert-warning">
            <strong>Setup needed:</strong> Please run <code>application/migrations/production_batch_a.sql</code> to enable production payments.
          </div>
        <?php endif; ?>

        <div class="card-box">
          <div class="clearfix">
            <h4 class="header-title m-t-0 float-left"><i class="fa fa-money"></i> Manage Payments</h4>
            <a href="<?php echo base_url('show-all-productions'); ?>" class="btn btn-sm btn-secondary float-right"><i class="fa fa-arrow-left"></i> Back to Productions</a>
          </div>

          <table class="table table-sm m-t-10" style="max-width:520px;">
            <tr><td>Production ID</td><td><strong><?php echo $prod->p_code; ?></strong></td></tr>
            <tr><td>Gate Pass</td><td><?php echo $prod->gp_code; ?></td></tr>
            <tr><td>Raw Material</td><td><?php echo $prod->raw_name; ?></td></tr>
            <tr><td>Final Bill</td><td><strong>LKR <span id="pay_final"><?php echo number_format($prod->p_final_bill,2); ?></span></strong></td></tr>
            <tr><td>Paid</td><td class="text-success"><strong>LKR <span id="pay_paid"><?php echo number_format(isset($prod->p_paid)?$prod->p_paid:0,2); ?></span></strong></td></tr>
            <tr><td>Outstanding Balance</td><td class="text-danger"><strong>LKR <span id="pay_balance"><?php echo number_format(isset($prod->p_balance)?$prod->p_balance:$prod->p_final_bill,2); ?></span></strong></td></tr>
          </table>

          <?php if($ready): ?>
          <hr>
          <h5>Add Payment</h5>
          <div class="form-row align-items-end">
            <div class="form-group col-md-3">
              <label>Method</label>
              <select class="form-control" id="pay_method">
                <option value="Cash">Cash</option>
                <option value="Cheque">Cheque</option>
              </select>
            </div>
            <div class="form-group col-md-3" id="pay_ref_group" style="display:none;">
              <label>Cheque No / Reference</label>
              <input type="text" class="form-control" id="pay_ref">
            </div>
            <div class="form-group col-md-3">
              <label>Amount</label>
              <input type="number" step="0.01" class="form-control" id="pay_amount" placeholder="0.00">
            </div>
            <div class="form-group col-md-3">
              <button class="btn btn-success btn-block" id="btn_add_payment"><i class="fa fa-plus"></i> Add Payment</button>
            </div>
          </div>

          <hr>
          <h5>Payment History</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-sm" id="pay_history">
              <thead><tr style="background:#eef;"><th>#</th><th>Date</th><th>Method</th><th>Reference</th><th class="text-right">Amount</th><th>By</th><th></th></tr></thead>
              <tbody id="pay_body"></tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
var PBASE = '<?php echo base_url(); ?>';
var PID = <?php echo intval($prod->p_id); ?>;

$(function(){
  $('#pay_method').change(function(){
    $('#pay_ref_group').toggle($(this).val() === 'Cheque');
    if($(this).val() !== 'Cheque') $('#pay_ref').val('');
  });
  $('#btn_add_payment').click(addPayment);
  loadPayments();
});

function fmt(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

function loadPayments(){
  $.get(PBASE+'manufacturing/getPayments/'+PID, function(res){
    var r = JSON.parse(res); if(!r.success) return;
    $('#pay_final').text(fmt(r.final));
    $('#pay_paid').text(fmt(r.paid));
    $('#pay_balance').text(fmt(r.balance));
    var h='', i=0;
    r.payments.forEach(function(p){
      i++;
      h += '<tr><td>'+i+'</td><td>'+(p.mp_at||'')+'</td><td>'+p.mp_method+'</td><td>'+(p.mp_ref||'')+'</td>'
        + '<td class="text-right">'+fmt(p.mp_amount)+'</td><td>'+(p.created_by_name||'')+'</td>'
        + '<td><button class="btn btn-sm btn-outline-danger pay-del" data-id="'+p.mp_id+'"><i class="fa fa-times"></i></button></td></tr>';
    });
    $('#pay_body').html(h || '<tr><td colspan="7" class="text-center text-muted">No payments yet.</td></tr>');
  });
}

function addPayment(){
  var method = $('#pay_method').val();
  var ref = $('#pay_ref').val();
  var amount = parseFloat($('#pay_amount').val())||0;
  if(amount <= 0){ swal({type:'error',title:'Amount required',text:'Enter a payment amount.'}); return; }
  if(method === 'Cheque' && !ref.trim()){ swal({type:'error',title:'Reference required',text:'Enter the cheque number / reference.'}); return; }
  $.post(PBASE+'manufacturing/addPayment', {p_id:PID, amount:amount, method:method, ref:ref}, function(res){
    var r = JSON.parse(res);
    if(!r.success){ swal({type:'error',title:'Error',text:r.msg||'Failed'}); return; }
    $('#pay_amount').val(''); $('#pay_ref').val('');
    swal({type:'success',title:'Payment added',timer:1200,showConfirmButton:false});
    loadPayments();
  });
}

$(document).on('click', '.pay-del', function(){
  var id = $(this).data('id');
  swal({title:'Remove this payment?', type:'warning', showCancelButton:true, confirmButtonText:'Yes, remove'}, function(ok){
    if(!ok) return;
    $.post(PBASE+'manufacturing/deletePayment', {mp_id:id}, function(res){
      var r = JSON.parse(res); if(!r.success){ return; }
      loadPayments();
    });
  });
});
</script>
