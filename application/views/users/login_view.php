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
                                    <img src="<?= base_url('assets/img/intellisalesbot_circle_logo.png');  ?>" width="320rem" height="320rem" alt="IMG">
                                </div>

                                <form class="user pb-5" method="post" action=" <?= base_url('users/login/verify_users'); ?>">
                                    <span class="login100-form-title">
                                        IntelliSalesBot
                                    </span>
                                    <!-- Display no item message if it exist-->
                                    <?= $this->session->userdata('message') ?>
                                    <?php $this->session->unset_userdata('message'); ?>
                                    
                                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                                        <input class="input100" type="email" name="user_email" placeholder="Email" value="<?= set_value('user_email'); ?>" required>
                                        <?= form_error('user_email', '<small class="text-danger pl-3">', '</small>'); ?>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                                        <input class="input100" type="password" name="user_password" placeholder="Password" required>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="container-login100-form-btn pb-3">
                                        <button class="login100-form-btn">
                                            Login
                                        </button>
                                    </div>
                                    <div class="container-login100-form-btn pb-5">
                                        <p><a href=" <?= base_url('users/register'); ?>">Register an account</a></p>
                                    </div>
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