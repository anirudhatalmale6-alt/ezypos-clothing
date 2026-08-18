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
                                    <th>Name</th>
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
    $( function() {
        $('form').parsley();

        showAllExpenses();

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('ExpenCategories/addExpensePOST'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            alert(response && response.msg ? response.msg : "Record added");
                            if(!response || response.ok !== false){ $('#expenseid').val(''); }
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
                        rows+= '<tr'+(active ? '' : ' style="opacity:.6;"')+'>'+
                                    '<td>'+data[i].expencat_id+'</td>'+
                                    '<td>'+data[i].expencat_catname+'</td>'+
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
                            console.log(data);
                            $('input[name=edit_expensename]').val(data.expencat_catname);
                            $('input[name=hiddenID]').val(data.expencat_id);
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

