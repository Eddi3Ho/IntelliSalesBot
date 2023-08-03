<!-- Footer -->
<!-- <footer class="footer fixed-bottom">
                <div class="container py-4">
                    <div class="copyright text-center">
                        <span>Copyright &copy; People Health Pharmacy (PHP) Inc. 2021</span>
                    </div>
                </div>
</footer> -->
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Bootstrap core JavaScript -->
<?php if(isset($bootstrap_js)) {
    echo $bootstrap_js;
    } else { ?>
    
    <script src="<?php echo base_url() ?>/assets/vendor/jquery/jquery.min.js"></script>
    
    <script src="<?php echo base_url() ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <?php } ?>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url()?>/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url()?>/assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo base_url('assets/js/demo/datatables-demo.js')?>"></script>
    
    <!-- Core plugin JavaScript -->
    <script src="<?php echo base_url() ?>/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Custom scripts for all pages -->
    <script src="<?php echo base_url() ?>/assets/js/sb-admin-2.min.js"></script>
    
    <?php if(isset($include_js)) {
        echo '<script src="' . base_url() . 'assets/js/additional/' . $include_js . '.js"></script>'; }
        if(isset($include_js2)) {
            echo $include_js2; }
            if(isset($include_js3)) {
                echo $include_js3; } ?>

</body>
</html>