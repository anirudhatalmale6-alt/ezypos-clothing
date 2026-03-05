        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">                    
                        <h4 class="page-title">Starter</h4>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                                <table id="sub_cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subcategory</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_sub_cat">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>

        </div> <!-- container -->

<script>
    $( function() {      
        
   
  
        function showSubCategories(){
          //  var parentCat_id = GetURLParameter('id');
          //  alert(parentCat_id);
          //  var parentCat_name = GetURLParameter('name');
            $.ajax({
					type: 'post',
					url:'<?php echo base_url()?>categories/Listsubcategories',
                   // data: {id: parentCat_id, name:parentCat_name},
					async:false,
					dataType:'json',
					success:function(data){
                       if(data){
						var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                            rows+= '<tr>'+
                                    '<td>'+data[i].sub_cat_id+'</td>'+
                                    '<td>'+data[i].sub_cat_name+'</td>'+
                                    '<td>'+                                   
                                    '<a href="javascript:;"style="margin-right:20px;" class="btn btn-sm btn-info sub_cat_Edit" id="'+1+'" name="'+2+'" cat_name="'+3+'" cat_id="'+4+'">Edit</a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger sub_cat_Delete" id="'+5+'">Delete</a>'+
                                    '</td>'+
                                '</tr>';
						}
							$('#tbody_sub_cat').html(rows);
                       }
                       else{
                           alert("No subcategories found");
                       }						
					},
					error: function(){
						alert('error data collection');
					}
				});
        }
        showSubCategories();

            //Buttons examples
            var table = $('#sub_cat_table').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    } ); 
    $(document)
    
</script> 