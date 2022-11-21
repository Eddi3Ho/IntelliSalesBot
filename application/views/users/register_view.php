<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
</script>

<script>
    //Js to remove alert message after university information is edited
    setTimeout(function() {
        $('#alert_message').fadeOut();
    }, 5000); // <-- time in milliseconds
</script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row (Start here)-->

                    <div class="limiter">
                        <div class="container-login100">
                            <div class="wrap-login100">
                                <div class="login100-pic js-tilt" data-tilt>
                                    <img src="<?= base_url('assets/img/logo-circle.png'); ?>" alt="IMG">
                                </div>

                                <form class="user" method="post" action=" <?= base_url('users/login/verify_users'); ?>">
                                    <span class="login100-form-title">
                                        PHP - SRePS
                                    </span>
                                    
                                                                        
                                    <div class="wrap-input100 validate-input" data-validate="Valid first name is required: ex@abc.xyz">
                                        <input class="input100" type="text" name="user_first_name" placeholder="First Name" value="" maxlength="255" required>
                                        <?= form_error('user_first_name', '<small class="text-danger pl-3">', '</small>'); ?>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="wrap-input100 validate-input" data-validate="Valid last name is required: ex@abc.xyz">
                                        <input class="input100" type="text" name="user_last_name" placeholder="Last Name" value="" maxlength="255" required>
                                        <?= form_error('user_last_name', '<small class="text-danger pl-3">', '</small>'); ?>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-address-card " aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                                        <input class="input100" type="email" name="user_email" placeholder="Email" value="" maxlength="255" required>
                                        <?= form_error('user_email', '<small class="text-danger pl-3">', '</small>'); ?>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                                        <input class="input100" type="password" name="user_password" placeholder="Password" maxlength="255" required>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="container-login100-form-btn">
                                        <button class="login100-form-btn">
                                            Register
                                        </button>
                                    </div>
                                    <div class="container-login100-form-btn">
                                        <p><a href=" <?= base_url('users/login/verify_users'); ?>">Back to Login</a></p>
                                    </div>
                                    <br><br><br><br><br><br><br>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        $('.js-tilt').tilt({
                            scale: 1.1
                        })
                    </script>
                    <!-- /. Content Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->