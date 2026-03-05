
<!DOCTYPE html>
<html>
    
<!-- Mirrored from coderthemes.com/uplon/horizontal/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Dec 2017 14:10:55 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.ico">

        <!-- App title -->
        <title>Login</title>

        <!-- Bootstrap CSS -->
        <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- App CSS -->
        <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <!-- Modernizr js -->
        <script src="<?php echo base_url();?>assets/js/modernizr.min.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','../../../www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-79190402-1', 'auto');
  ga('send', 'pageview');

</script>


    </head>


    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">

        	<div class="account-bg">
                <div class="card-box mb-0">
                    <div class="text-center m-t-20">
                        <a href="index.html" class="logo">
                            <i class="zmdi zmdi-group-work icon-c-logo"></i>
                            <span>AS RENISH(PVT) LTD</span> <?php// if(isset($_SESSION['username'])){echo "user logged";}else{echo "user out";} ?>
                        </a>
                    </div>
                    <div class="m-t-10 p-20">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h6 class="text-muted text-uppercase m-b-0 m-t-0">Sign In</h6>
                            </div>
                        </div>
                        <form class="m-t-20" id="formid" name="formname" action="#" method="post">

                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control" type="text" required="" parsley-trigger="change"
                                     placeholder="username" name="username" data-parsley-minlength="5">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control" type="password" required="" parsley-trigger="change"
                                     placeholder="password" name="password" data-parsley-minlength="5">
                                </div>
                            </div>
                            <!--
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="checkbox checkbox-custom">
                                        <input id="rememberme" name="rememberme" type="checkbox">
                                        <label for="checkbox-signup">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                            </div> 
                            -->
                            <div class="form-group text-center row m-t-10">
                                <div class="col-12">
                                    <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Log In</button>
                                </div>
                            </div>

                            <div class="form-group row m-t-30 mb-0">
                                <div class="col-12">
                                    <a href="pages-recoverpw.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- end card-box-->

        </div>
        <!-- end wrapper page -->

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/popper.min.js'?>"></script><!-- Tether for Bootstrap -->
        <script src="<?php echo base_url().'assets/js/bootstrap.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/detect.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/waves.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/jquery.nicescroll.js'?>"></script>
        <script src="<?php echo base_url().'assets/plugins/switchery/switchery.min.js'?>"></script>

        <!-- Validation js (Parsleyjs) -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/parsleyjs/parsley.min.js"></script>
    
        <!-- App js -->
        <script src="<?php echo base_url().'assets/js/jquery.core.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/jquery.app.js'?>"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('form').parsley();
            });

            $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
            	$.ajax({
						type: 'post',
						url: "<?php echo base_url('userauthentication/login_process'); ?>",
						data: data,
						async: false,
						dataType:'json',  
						success: function(response){
                            alert("Login success");
                            alert(response);                            
                        },
                    });
            $('#itemid').val('');
            $('#brandid').val('');
            $('#manufactureid').val('');
            $('#discriptionid').val('');
            $('#priceid').val('');
            $('#quantityid').val('');
        });
        </script>
    </body>

<!-- Mirrored from coderthemes.com/uplon/horizontal/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Dec 2017 14:10:56 GMT -->
</html>