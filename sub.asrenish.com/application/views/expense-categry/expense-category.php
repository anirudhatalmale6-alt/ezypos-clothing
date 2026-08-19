        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Add expense Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Expense Category Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Type</label>
                                    <div class="col-9">
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" name="cattype" id="typeParent" value="parent" checked>
                                            <label for="typeParent">Parent Category</label>
                                        </div>
                                        <div class="radio radio-primary form-check-inline">
                                            <input type="radio" name="cattype" id="typeSub" value="sub">
                                            <label for="typeSub">Subcategory</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="parentRow" style="display:none;">
                                    <label for="parentcat" class="col-3 col-form-label">Under</label>
                                    <div class="col-9">
                                        <select class="form-control" name="parentcat" id="parentcat">
                                            <option value="0">-Select parent category-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="expenseid" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Expense Category Name" 
                                        name="expensename" id="expenseid" required>
                                    </div> 
                                </div>
                          
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                </div>
                <!--End of Add expense Form -->

                 <!--Start Table & row -->
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Under</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Active</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>
                
                 <!-- end Table & row -->
              <!-- Item Edit Modal-->
			  <div class="modal " id="EditModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form id="editForm" name="" action="#" method="post">
                            <input type="hidden" name="hiddenID" id="hiddenID" value="0">
                            <div class="form-group row">
                                    <label for="edit_parentcat" class="col-3 col-form-label">Under</label>
                                    <div class="col-9">
                                        <select class="form-control" name="edit_parentcat" id="edit_parentcat">
                                            <option value="0">(none - this is a parent category)</option>
                                        </select>
                                        <small id="edit_parentnote" class="text-muted" style="display:none;">
                                            This category has subcategories of its own, so it has to stay a parent category.
                                        </small>
                                    </div>
                                </div>
                            <div class="form-group row">
                                    <label for="edit_expenseid" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="edit_expensename" id="edit_expenseid" required>
                                    </div> 
                                </div>                                                          
                            </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button id="btnsave" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
                </div>
            </div> <!-- container -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    // Category names are typed by hand, so they are escaped before going
    // anywhere near the table markup.
    function esc(v){
        return $('<div>').text(v === null || v === undefined ? '' : v).html();
    }

    $( function() {
        $('form').parsley();

        // Every category seen on the last refresh, so the parent dropdowns can
        // be rebuilt without a second trip to the server.
        var ALL_CATS = [];

        showAllExpenses();

        // Parent Category / Subcategory. The "Under" box only means anything
        // for a subcategory, so it is out of the way until one is chosen.
        $('input[name=cattype]').change(function(){
            var isSub = $('input[name=cattype]:checked').val() === 'sub';
            $('#parentRow').toggle(isSub);
            if(!isSub){ $('#parentcat').val('0'); }
        });

        // Fill a dropdown with the parent categories (the ones that are not
        // themselves under something). skipId leaves a category out of its own
        // list, so it cannot be made its own parent.
        function fillParentSelect($sel, selectedId, skipId, blankLabel){
            var current = (selectedId === undefined || selectedId === null) ? $sel.val() : String(selectedId);
            $sel.empty().append($('<option>').val('0').text(blankLabel));
            for(var i=0; i<ALL_CATS.length; i++){
                var c = ALL_CATS[i];
                if(parseInt(c.expencat_parent_id, 10) !== 0) continue;      // subcategories cannot be parents
                if(skipId && String(c.expencat_id) === String(skipId)) continue;
                var label = c.expencat_catname + (parseInt(c.expencat_status,10) === 1 ? '' : ' (inactive)');
                $sel.append($('<option>').val(c.expencat_id).text(label));
            }
            if(current){ $sel.val(current); }
            if(!$sel.val()){ $sel.val('0'); }
        }

        $("#formid").submit(function(e) {
            e.preventDefault();
            if($('input[name=cattype]:checked').val() === 'sub' && $('#parentcat').val() === '0'){
                alert('Choose the parent category this one belongs under.');
                return;
            }
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('ExpenCategories/addExpensePOST'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            alert(response && response.msg ? response.msg : "Record added");
                            // The parent stays selected on purpose: adding Fuel,
                            // then Parking, then Delivery under Transportation is
                            // the normal way this screen gets used.
                            if(!response || response.ok !== false){ $('#expenseid').val('').focus(); }
                            showAllExpenses();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
        });

        function showAllExpenses(){
				$.ajax({
					type: 'post',
					url:'<?php echo base_url()?>ExpenCategories/showAllExpenses',
					async:false,
					dataType:'json',
					success:function(data){
						var rows = '';
						var i;
						if(!data){ data = []; }
						ALL_CATS = data;
						// Both parent dropdowns are rebuilt from the same list, so a
						// category added a moment ago can be used as a parent at once.
						fillParentSelect($('#parentcat'), null, null, '-Select parent category-');
						for(i=0; i<data.length; i++){
                        var active = parseInt(data[i].expencat_status) === 1;
                        var badge = active
                            ? '<span class="badge badge-success" style="background:#28a745;color:#fff;">Active</span>'
                            : '<span class="badge badge-secondary" style="background:#868e96;color:#fff;">Inactive</span>';
                        // Deactivate hides the category from the Expense screen but
                        // keeps every expense already booked against it readable.
                        var toggle = active
                            ? '<a href="javascript:;" class="btn btn-sm btn-warning cls-toggle" data="'+data[i].expencat_id+'" data-to="0">Deactivate</a>'
                            : '<a href="javascript:;" class="btn btn-sm btn-success cls-toggle" data="'+data[i].expencat_id+'" data-to="1">Activate</a>';
                        var isSub = parseInt(data[i].expencat_parent_id, 10) > 0;
                        // Subcategories are indented under the parent they belong
                        // to, which the server has already sorted them beneath.
                        var nameCell = isSub
                            ? '<span style="color:#888;">&nbsp;&nbsp;&nbsp;&#8627;&nbsp;</span>' + esc(data[i].expencat_catname)
                            : '<strong>' + esc(data[i].expencat_catname) + '</strong>';
                        var underCell = isSub
                            ? esc(data[i].parent_name || '')
                            : '<span class="badge" style="background:#0d6efd;color:#fff;">Parent</span>';
                        rows+= '<tr'+(active ? '' : ' style="opacity:.6;"')+'>'+
                                    '<td>'+data[i].expencat_id+'</td>'+
                                    '<td>'+nameCell+'</td>'+
                                    '<td>'+underCell+'</td>'+
                                    '<td>'+badge+'</td>'+
                                    '<td>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-info cls-edit" data="'+data[i].expencat_id+'"><i class="fa fa-edit"></i></a>'+
                                    '</td>'+
                                    '<td>'+toggle+'</td>'+
                                '</tr>';
						}
							// Rebuild the table so search, sorting and paging see the
							// new rows instead of the ones that were there at load.
							if($.fn.DataTable.isDataTable('#datatable-buttons')){
								$('#datatable-buttons').DataTable().destroy();
							}
							$('#tbodyID').html(rows);
							initTable();
					},
					error: function(){
						alert('error data collection');
					}
				});
			}

        //Edit -get to view
			$('#tbodyID').on('click', '.cls-edit', function(){
                var id = $(this).attr('data');
                $('#EditModel').modal('show');
                $('#EditModel').find('.modal-title').text("Editing")
                $.ajax({
						type: 'post',
						url: "<?php echo base_url('ExpenCategories/EditExpenses'); ?>",
						data:  {id: id},	
						async: false,
						dataType:'json',  
						success: function(data){
                            $('input[name=edit_expensename]').val(data.expencat_catname);
                            $('input[name=hiddenID]').val(data.expencat_id);
                            var hasKids = parseInt(data.child_count, 10) > 0;
                            fillParentSelect($('#edit_parentcat'), data.expencat_parent_id, data.expencat_id,
                                             '(none - this is a parent category)');
                            // A category with subcategories under it cannot itself
                            // be moved under a parent - that would be three levels.
                            $('#edit_parentcat').prop('disabled', hasKids);
                            $('#edit_parentnote').toggle(hasKids);
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });
            //save
            $('#btnsave').click(function(){
			    var data = $('#editForm').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('ExpenCategories/updateExpenses'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            alert(response && response.msg ? response.msg : "Expense Updated");
                            if(!response || response.ok !== false){ $('#EditModel').modal('hide'); }
                            showAllExpenses();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });

        //Activate / Deactivate. Categories are never deleted, so old expenses
        //keep the category name they were booked under.
			$('#tbodyID').on('click', '.cls-toggle', function(){
                var id = $(this).attr('data');
                var to = $(this).attr('data-to');
                var word = (to == '1') ? 'activate' : 'deactivate';
                if(!confirm('Press OK to ' + word + ' this category.')){ return; }
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('ExpenCategories/setStatus'); ?>",
                        data:  {id: id, status: to},
                        async: false,
                        dataType:'json',
                        success: function(response){
                            showAllExpenses();
                            alert(response && response.msg ? response.msg : 'Saved.');
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });
               
            //Buttons examples. Called again after every refresh above, so the
            //Copy / Excel / PDF buttons keep working on the rows on screen.
            function initTable(){
                var table = $('#datatable-buttons').DataTable({
                    buttons: ['copy', 'excel', 'pdf']
                });
                table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            }

    } );
    $(document)
    
</script> 

