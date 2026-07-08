<div class="wrapper">
<div class="container-fluid">
    <div class="row"><div class="col-12">
        <div class="card-box clearfix">
            <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-list"></i> Loyalty Ledger</h4>
            <form method="get" action="<?php echo base_url('loyalty/report'); ?>" class="form-inline m-b-20">
                <label class="m-r-5">Customer</label>
                <select name="cus_id" class="form-control m-r-10">
                    <option value="">All</option>
                    <?php if(isset($customers) && $customers): foreach($customers as $c): ?>
                    <option value="<?php echo $c->cus_id; ?>" <?php echo ($f_cus==$c->cus_id)?'selected':''; ?>><?php echo htmlspecialchars($c->cus_name); ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <label class="m-r-5">From</label>
                <input type="date" name="from" value="<?php echo htmlspecialchars($f_from); ?>" class="form-control m-r-10">
                <label class="m-r-5">To</label>
                <input type="date" name="to" value="<?php echo htmlspecialchars($f_to); ?>" class="form-control m-r-10">
                <button type="submit" class="btn btn-primary m-r-5"><i class="fa fa-filter"></i> Filter</button>
                <a href="<?php echo base_url('loyalty/settings'); ?>" class="btn btn-secondary">Back</a>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th><th>Date</th><th>Customer</th><th>Type</th>
                            <th class="text-right">Points</th><th class="text-right">Amount</th>
                            <th class="text-right">Balance After</th><th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($ledger) && count($ledger)): foreach($ledger as $l): ?>
                        <tr>
                            <td><?php echo $l->ll_id; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($l->ll_createdat)); ?></td>
                            <td><?php echo htmlspecialchars($l->cus_name); ?></td>
                            <td>
                                <?php if($l->ll_type=='earn'): ?><span class="badge badge-success">Earn</span>
                                <?php elseif($l->ll_type=='redeem'): ?><span class="badge badge-warning">Redeem</span>
                                <?php else: ?><span class="badge badge-info">Adjust</span><?php endif; ?>
                            </td>
                            <td class="text-right <?php echo $l->ll_points<0?'text-danger':'text-success'; ?>"><?php echo number_format($l->ll_points,2); ?></td>
                            <td class="text-right"><?php echo number_format($l->ll_amount,2); ?></td>
                            <td class="text-right"><strong><?php echo number_format($l->ll_balance_after,2); ?></strong></td>
                            <td><?php echo htmlspecialchars($l->ll_note); ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center text-muted">No loyalty activity found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div></div>
</div>
</div>
