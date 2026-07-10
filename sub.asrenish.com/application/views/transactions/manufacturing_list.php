<div class="content">
  <div class="container-fluid">
    <div class="row" style="margin-top:15px;">
      <div class="col-12">
        <div class="card-box">
          <div class="clearfix">
            <h4 class="header-title m-t-0 float-left"><i class="fa fa-industry"></i> All Productions</h4>
            <a href="<?php echo base_url('add-production'); ?>" class="btn btn-primary float-right"><i class="fa fa-plus"></i> New / Open Production</a>
          </div>
          <div class="table-responsive m-t-10">
            <table class="table table-striped table-bordered" id="datatable">
              <thead>
                <tr style="background:#C0C0C0;">
                  <th>Production ID</th>
                  <th>Gate Pass</th>
                  <th>Warehouse</th>
                  <th>Raw Material</th>
                  <th>Final Bill</th>
                  <th>Status</th>
                  <th>Created By</th>
                  <th>Date</th>
                  <th>Gate Pass</th>
                </tr>
              </thead>
              <tbody>
                <?php if($productions): foreach($productions as $p): ?>
                <tr>
                  <td><?php echo $p->p_code; ?></td>
                  <td><?php echo $p->gp_code; ?></td>
                  <td><?php echo $p->store_name; ?></td>
                  <td><?php echo $p->raw_name; ?> (<?php echo number_format($p->p_raw_qty,2); ?> <?php echo $p->p_raw_uom; ?>)</td>
                  <td><?php echo number_format($p->p_final_bill,2); ?></td>
                  <td><?php
                      $cls='secondary';
                      if($p->p_status=='Dispatched') $cls='primary';
                      elseif($p->p_status=='Received') $cls='info';
                      elseif($p->p_status=='Completed') $cls='success';
                      ?><span class="badge badge-<?php echo $cls; ?>"><?php echo $p->p_status; ?></span></td>
                  <td><?php echo $p->created_by_name; ?></td>
                  <td><?php echo $p->p_createdat; ?></td>
                  <td><a href="<?php echo base_url('mfg-gate-pass-print/'.$p->p_gp_id); ?>" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fa fa-print"></i></a></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="9" class="text-center text-muted">No productions yet.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){ if($.fn.DataTable){ try{ $('#datatable').DataTable({order:[[0,'desc']]}); }catch(e){} } });
</script>
