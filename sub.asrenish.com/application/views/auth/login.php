<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">

    <!-- App title -->
    <title>Login</title>

    <!-- External CSS -->
    <link href="<?php echo base_url(); ?>assets/css/login.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Modernizr js -->
    <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>

    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '../../../www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-79190402-1', 'auto');
        ga('send', 'pageview');
    </script>

</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Left Half: Background Image -->
            <div class="col-md-6 left-half" style="background-image: url('<?php echo base_url(); ?>assets/images/Lamp_img.jpg');"></div>


            <!-- Right Half: Login Form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="card shadow-sm p-4 w-100" style="max-width: 400px;">
                    <div class="text-center mb-4">
                        <a class="text-decoration-none">
                            <h4 class="fw-bold">
                                <?php echo isset($company_name) ? $company_name : 'Default Company Name'; ?>
                            </h4>
                        </a>
                    </div>
                    <form id="formid" name="formname" action="#" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" class="form-control form-control-lg" type="text" required placeholder="Enter your username" name="username" data-parsley-minlength="5">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control form-control-lg" type="password" required placeholder="Enter your password" name="password" data-parsley-minlength="5">
                        </div>
                        <div class="d-grid mb-3">
                            <button class="btn btn-success btn-lg" type="submit">Log In</button>
                        </div>
                        <div class="text-center">
                            <a href="pages-recoverpw.html" class="text-muted text-decoration-none"><i class="fa fa-lock me-2"></i>Forgot your password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo base_url() . 'assets/js/jquery.min.js' ?>"></script>
    <script src="<?php echo base_url() . 'assets/js/popper.min.js' ?>"></script>
    <script src="<?php echo base_url() . 'assets/js/bootstrap.min.js' ?>"></script>
    <script src="<?php echo base_url() . 'assets/plugins/parsleyjs/parsley.min.js' ?>"></script>
    <script src="<?php echo base_url() . 'assets/plugins/sweetalert2/sweetalert2.all.js' ?>"></script>

    <script>
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
                dataType: 'json',
                success: function(response) {
                    if (response == true) {
                        window.location.href = "<?php echo base_url(); ?>?username=" + username;
                    } else if (response == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Entered wrong username or password',
                        });
                    }
                },
            });
        });
    </script>
</body>

</html>