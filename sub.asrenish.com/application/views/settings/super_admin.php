<!-- ==============================================================
     Super Admin settings.

     One page, one Save. Every switch here decides whether a part of the
     system is reachable for this shop - it never deletes anything. A
     feature switched off keeps its tables, its rows and its history, and
     comes back exactly as it was when the switch goes on again.
     ============================================================== -->
<div class="wrapper">
    <div class="container">

        <?php if (!empty($saved)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Saved.</strong> The menu and the screens follow these settings from now on.
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-12">
                <div class="card-box" style="background:#263238;color:#fff;">
                    <h4 class="m-t-0 m-b-5" style="color:#fff;">
                        <i class="fa fa-sliders"></i> Super Admin Settings
                    </h4>
                    <p class="m-b-0" style="opacity:.85;">
                        What this shop gets. Nothing is deleted by switching something off -
                        the feature keeps its data and comes back as it was when it is
                        switched on again. Only an administrator can open this page, and it
                        is not in the user permission list, so it cannot be given to a shop
                        user by accident.
                    </p>
                </div>
            </div>
        </div>

        <form method="post" action="<?php echo base_url('super-admin/save'); ?>">

            <!-- ------------------------------------------------ main -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-cogs"></i> Main settings</h4>
                        <?php foreach ($features as $key => $f) {
                            if ($f['group'] !== 'main') { continue; }
                            $on = !empty($values[$key]); ?>
                        <div class="row m-b-20" style="border-bottom:1px solid #eee;padding-bottom:14px;">
                            <div class="col-md-4">
                                <div class="checkbox checkbox-custom">
                                    <input id="<?php echo $key; ?>" name="<?php echo $key; ?>"
                                           type="checkbox" value="1" <?php echo $on ? 'checked' : ''; ?>>
                                    <label for="<?php echo $key; ?>" style="font-weight:600;font-size:15px;">
                                        <?php echo htmlspecialchars($f['label']); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div><span class="badge badge-success">ON</span>
                                     <?php echo htmlspecialchars($f['on']); ?></div>
                                <div class="m-t-5"><span class="badge badge-secondary">OFF</span>
                                     <?php echo htmlspecialchars($f['off']); ?></div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------ modules -->
            <div class="row">
                <div class="col-lg-7">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-5"><i class="fa fa-th-large"></i> Functions</h4>
                        <p class="text-muted m-b-20">
                            Switched off, a function disappears for everyone - its menu entry, its
                            dashboard card, its buttons, and the page itself if the address is typed.
                            Switched on, it works exactly as it does now.
                        </p>
                        <?php foreach ($features as $key => $f) {
                            if ($f['group'] !== 'modules') { continue; }
                            $on = !empty($values[$key]); ?>
                        <div class="row m-b-15" style="border-bottom:1px solid #f2f2f2;padding-bottom:10px;">
                            <div class="col-md-5">
                                <div class="checkbox checkbox-custom">
                                    <input id="<?php echo $key; ?>" name="<?php echo $key; ?>"
                                           type="checkbox" value="1" <?php echo $on ? 'checked' : ''; ?>>
                                    <label for="<?php echo $key; ?>" style="font-weight:600;">
                                        <?php echo htmlspecialchars($f['label']); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <small class="text-muted"><?php echo htmlspecialchars($f['on']); ?></small>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- ------------------------------------------- payment methods -->
                <div class="col-lg-5">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-5"><i class="fa fa-credit-card"></i> Payment methods</h4>
                        <p class="text-muted m-b-15">
                            Only the ticked ones are offered anywhere a payment is taken - the
                            sales window, exchanges, tailoring orders. Unticking one does not
                            touch the payments already taken on it, and it keeps appearing in
                            the reports for those.
                        </p>
                        <?php if (empty($paymentMethods)) { ?>
                        <p class="text-muted">No payment methods have been set up yet. Add them under
                           Masters, Payment Methods.</p>
                        <?php } else { foreach ($paymentMethods as $pm) { ?>
                        <div class="checkbox checkbox-custom m-b-10">
                            <input id="pm_<?php echo $pm->pm_id; ?>" name="pm[]" type="checkbox"
                                   value="<?php echo $pm->pm_id; ?>"
                                   <?php echo ($pm->pm_status == 1) ? 'checked' : ''; ?>>
                            <label for="pm_<?php echo $pm->pm_id; ?>">
                                <?php echo htmlspecialchars($pm->pm_name); ?>
                            </label>
                        </div>
                        <?php }} ?>
                        <p class="text-muted m-t-15 m-b-0" style="font-size:12px;">
                            Cash is always available and is not listed here - a till that cannot
                            take cash is not a till.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row m-b-30">
                <div class="col-12">
                    <div class="card-box">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save"></i> Save settings
                        </button>
                        <a href="<?php echo base_url(); ?>" class="btn btn-link">Cancel</a>
                        <span class="text-muted m-l-15">
                            Takes effect immediately. Anyone already signed in sees the change on
                            their next page.
                        </span>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
