        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">                    
                        <h4 class="page-title">Category</h4>
                    </div>
                </div>
                <!-- end row -->

            <!-- Add Category Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Category Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="" class="col-3 col-form-label"> Type</label>
                                    <div class="col-9">
                                    <select class="form-control" name="maintype" id="maintypeid">
                                        <option value="">-No Parent Category-</option>
                                        <?php
                                        foreach ($types as $type)
                                            {
                                           print '<option value="'.  $type->cat_id.'"> '. $type->cat_name.'</option>';
                                            }
	                                    ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Category Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="categoryname" id="categorynameid" required>
                                    </div>
                                </div>
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                
                </div>
                <!--End of Add Category Form -->

                <!--Start Table & row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>
                
                 <!-- end Table & row -->


                <!--Start of New subcat show modal-->
                <div class="modal fade" id="myModalShowsubcat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-box table-responsive"> 
                                            <table id="sub_cat_table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Subcategory</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_sub_cat2">                                          
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>                 
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End of New subcat show modal-->

               <!--4 Start of New subcat Edit modal-->
               <div class="modal fade" id="subCatEditModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <h4 class="header-title m-t-0 m-b-30">Edit Category Details</h4>
                            <form id="edit_subcat_id" name="" action="#" method="post">
                            <input type="hidden" name="sub_catID" id="sub_catID" value="0">
                            <input type="hidden" name="oldParent_catID" id="oldParent_catID" value="0">
                            <input type="hidden" name="oldParent_catName" id="oldParent_catName" value="0">
                                <div class="form-group row">
                                    <label for="" class="col-3 col-form-label"> Type</label>
                                    <div class="col-9">
                                        <select class="form-control" name="Edit_maintype" id="Edit_maintypeid">
                                        <option value="">-No Parent Category-</option>                                                                          
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Category Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_categoryname" id="Edit_categorynameid" required>
                                    </div>
                                </div>                             
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button id="btnUpdateSubCat"type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--4 End of New subcat Edit modal-->


                <!-- Edit parent cat modal -->
			    <div class="modal " id="ParentCatEditModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <form id="editFormParentcat" name="" action="#" method="post">
                                <input type="hidden" name="hiddenParentID" id="hiddenParentID" value="0">
                                <div class="form-group row">
                                    <label for="Edit_parent_cat_name" class="col-3 col-form-label">Category Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_parent_cat_name" id="Edit_parent_cat_name" required>
                                    </div>
                                </div>                                                          
                                </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="parentcat_btnsave" type="button" class="btn btn-primary">Save changes</button>
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
        function refreshCategories(){
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('categories/getTypes'); ?>",
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        var len = response.length;
                        $('#maintypeid').empty().append('<option value="">-No Parent Category-</option>');                        
                        for( var i = 0; i<len; i++){
                            var catname = response[i]['cat_name'];
                            var catid = response[i]['cat_id'];                    
                            $("#maintypeid").append("<option value='"+catid+"'>"+catname+"</option>");
                        }
                    },
                    error: function() {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                    }
                });
        }
        refreshCategories();

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
            var maintype_id = $('#maintypeid').val();
            var categoryname = $('#categorynameid').val(); 
            if(maintype_id != "") // A main category is selected & entering a new subcategory
                    {                          
                        $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('categories/addSubCategoryPOST'); ?>",
                                data: data,
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    alert("Record added");
                                    //location.reload();
                                },
                                error: function() {
                                    swal({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'There was an error. Try again please!'
                                    });
                                }
                            });     
                    }
                else {                          
                        $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('categories/addMainCategoryPOST'); ?>",
                                data: data,
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    alert("Record added");
                                    //showAllCategories();
                                    //refreshCategories();
                                    location.reload();
                                },
                                error: function() {
                                    swal({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'There was an error. Try again please!'
                                    });
                                }
                            });            
                    }
            $('#maintypeid').val('');
            $('#categorynameid').val(''); 

        });

        // List all Parent categories
        function showAllCategories(){
            $.ajax({
					type: 'post',
					url:'<?php echo base_url()?>categories/showAllCategories',
					async:false,
					dataType:'json',
					success:function(data){
						var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                                                    
                                        //Sub Category validation start
                                        $x_w = 0;
                                        $.ajax({   
                                        type: 'post',
					url:'<?php echo base_url()?>categories/showCategorySubCats',
					async:false,
					dataType:'json',
                                        data: {category_id:data[i].cat_id },
					success:function(data_s){
                                        if(data_s>0){$x_w=1;}
                                        },
					error: function(){
						alert('error sub data collection');
					}
                                        }); 
                                        //Sub Category validation end 
                                        
                                   if($x_w!=0){
                                   delete_text=""; 
                                   }else{
                                    delete_text= '<a href="javascript:;" class="btn btn-sm btn-danger Parent_cat_Delete" id="'+data[i].cat_id+'">Delete</a>';                
                                   }
                                   
                                    rows+= '<tr>'+
                                    '<td>'+data[i].cat_id+'</td>'+
                                    '<td>'+data[i].cat_name+'</td>'+
                                    '<td>'+                                   
                                    '<a href="javascript:;" class="btn btn-sm btn-info cls-view" id="'+data[i].cat_id+'" name="'+data[i].cat_name+'">View</a>'+
                                    '</td>'+
                                    '<td>'+                                   
                                    '<a href="javascript:;"style="margin-right:20px;" class="btn btn-sm btn-info Parent_cat_Edit" id="'+data[i].cat_id+'">Edit</a>'+
                                    delete_text+
                                    '</td>'+
                                '</tr>';
						}
							$('#tbodyID').html(rows);						
					},
					error: function(){
						alert('error data collection');
					}
				});
        }
        showAllCategories();

        //View sub categories 
        function ShowSubCategoris(cat_id, cat_name,refresh){
                if(refresh==0){ //to avoid modal "show" again
                $('#myModalShowsubcat').modal('show');
                $('#myModalShowsubcat').find('.modal-title').text(cat_name);
                }
				$.ajax({
					type:'post',
					url:'<?php echo base_url()?>Categories/viewSubCategory',
					data: {cat_id: cat_id},
					async:false,
					dataType:'json',
					success:function(data)	{
                        var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                        rows+= '<tr>'+
                                    '<td>'+data[i].sub_cat_id+'</td>'+
                                    '<td>'+data[i].sub_cat_name+'</td>'+
                                    '<td>'+                                   
                                    '<a href="javascript:;"style="margin-right:20px;" class="btn btn-sm btn-info sub_cat_Edit" subCatID="'+data[i].sub_cat_id+'" subCatName="'+data[i].sub_cat_name+'" cat_name="'+cat_name+'" cat_id="'+cat_id+'">Edit</a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger sub_cat_Delete" id="'+data[i].sub_cat_id+'" parent_cat_id="'+cat_id+'" parent_cat_name="'+cat_name+'">Delete</a>'+ 
                                    '</td>'+
                                '</tr>';
						}
							$('#tbody_sub_cat2').html(rows);
                     
					},
					error: function(){
                        swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
					}
				});
        }
            //show subcategories
            $('#tbodyID').on('click', '.cls-view', function(){
                var cat_id = $(this).attr('id');
                var cat_name = $(this).attr('name');
                var refresh=0;
                ShowSubCategoris(cat_id,cat_name,refresh);
            });

            //Edit subcategory subcatEdit
             $('#tbody_sub_cat2').on('click', '.sub_cat_Edit', function(){
                var sub_cat_id = $(this).attr('subCatID');
                var sub_cat_name = $(this).attr('subCatName');
                var cat_name = $(this).attr('cat_name');
                var cat_id = $(this).attr('cat_id');
				$('#subCatEditModel').modal('show');
                $('#subCatEditModel').find('.modal-title').text("Editing...");
               // $('#edit_subcat_id').attr('action', '<?php echo base_url() ?>Categories/updateSubcategory');
				$.ajax({
					method:'post',
					url:'<?php echo base_url()?>Categories/getTypes',
					data: {sub_cat_id: sub_cat_id},
					async:false,
					dataType:'json',
					success:function(data)	{
                        var len = data.length;
                        $('#Edit_maintypeid').empty();//.append('<option value="">-No Parent Category-</option>');                        
                        for( var i = 0; i<len; i++){
                            var catname = data[i]['cat_name'];
                            var catid = data[i]['cat_id'];                    
                            $("#Edit_maintypeid").append("<option value='"+catid+"'>"+catname+"</option>");
                        }
                        $("#Edit_maintypeid").val(cat_id);
						$('input[name=Edit_categoryname]').val(sub_cat_name);
						$('input[name=sub_catID]').val(sub_cat_id); //hidden
                        $('input[name=oldParent_catID]').val(cat_id);//hidden
                        $('input[name=oldParent_catName]').val(cat_name);//hidden
					},
					error: function(){
						alert('Could not Edit Data');
					}
				});
             });

        //update subcategory changes
        $('#btnUpdateSubCat').click(function(){
			var data = $('#edit_subcat_id').serialize();
            var old_maintype_id = $('#oldParent_catID').val();
            var old_maintype_name = $('#oldParent_catName').val();   
            var edit_maintype_id = $('#Edit_maintypeid').val(); 

            if(old_maintype_id == edit_maintype_id){
                // No change in parent catefory, change onlyin sub cat
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('categories/updateSubCategory_SameParent'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            alert("Subcategory Updated");
                            showAllCategories();
                        },
                        error: function() {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                        }
                    });
            }
            else if(old_maintype_id != edit_maintype_id){
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('categories/updateSubCategory_ParentChanged'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            if(response){
                                alert("Category Changes Updated");
                                showAllCategories();
                            }
                            else{
                                alert("Subcategory already exist");
                            }                            
                        },
                        error: function() {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                        }
                    });
            }  
            var refresh=1;
            ShowSubCategoris(old_maintype_id,old_maintype_name,refresh);    
            $('#subCatEditModel').modal('hide');   
        });     

        //Delete subcategory category
        $('#tbody_sub_cat2').on('click', '.sub_cat_Delete', function(){   
            var parent_cat_id = $(this).attr('parent_cat_id');     
            var parent_cat_name = $(this).attr('parent_cat_name');     
            var check = confirm("Press OK to continue delete");
            if (check == true) {
            var id = $(this).attr('id');
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('categories/DeleteSubCategory'); ?>",
                    data:  {id: id},	
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        var refresh=1;
                        ShowSubCategoris(parent_cat_id,parent_cat_name,refresh);
                        alert("Record Deleted");                            
                    },
                    error: function() {
                        swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                    }
                });
            }                 
        });

        //Edit Parent category
            $('#tbodyID').on('click', '.Parent_cat_Edit', function(){ 
                var parent_cat_id = $(this).attr('id');
                $('#ParentCatEditModel').modal('show');
                $('#ParentCatEditModel').find('.modal-title').text("Editing")
                $.ajax({
						type: 'post',
						url: "<?php echo base_url('categories/EditParentCategory'); ?>",
						data:  {parent_cat_id: parent_cat_id},	
						async: false,
						dataType:'json',  
						success: function(data){
                            $('input[name=Edit_parent_cat_name]').val(data.cat_name);
                            $('input[name=hiddenParentID]').val(data.cat_id);
                        },
                        error: function() {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                        }
                    });
            });
        //save parent category
            $('#parentcat_btnsave').click(function(){
            var data = $('#editFormParentcat').serialize();
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('categories/updateParentCategory'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        showAllCategories();
                        alert("Category Updated");   
                        $('#ParentCatEditModel').modal('hide');
                    },
                    error: function() {
                        swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                    }
                });
            });

        //Delete parent category
			$('#tbodyID').on('click', '.Parent_cat_Delete', function(){                
                var check = confirm("Press OK to continue delete");
                if (check == true) {
                var id = $(this).attr('id');
                    $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('categories/DeleteParentCategory'); ?>",
                        data:  {id: id},	
                        async: false,
                        dataType:'json',  
                        success: function(response){
                           // showAllCategories();
                            alert("Record Deleted"); 
                            location.reload();
                        },
                        error: function() {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!'
                            });
                        }
                    });
                }                 
            });
                            //table2.destroy();
                            var table2 = $('#sub_cat_table2').DataTable({
                            buttons: ['copy', 'excel', 'pdf']
                            });
                                                    
                            table2.buttons().container()
                            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

        //Buttons examples            
            var table = $('#datatable-buttons').DataTable({
				"order": [[ 0, "desc" ]],
            buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    } ); 
    $(document)
    
</script> 

