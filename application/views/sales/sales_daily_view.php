<!-- Plug in for sweetalert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo base_url() ?>/assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<style>
    .table-striped tbody tr:nth-of-type(odd) {
        background: white;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background: #f2f2f2;
    }

    .table-striped thead tr:nth-of-type(odd) {
        background: #292e32;
        color:white;
    }

    .table-striped {
        color: black;
    }

    .period:hover {
        background-color: #2652b3 !important;
    } 
</style>

<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3  font-weight-bold" style="color: black">Daily Sales</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Sales</li>
                                <li class="breadcrumb-item active">Daily Sales</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-xl-3">
                            <div class="" style="background-color:#292e32; border-radius:10px; width:15.2rem; height:auto;">
                                <div class="px-3 py-2 "> <span style="color:white;">
                                        <form class="form-inline">
                                            <div class="form-group">
                                                <label style="color:white;" class="mr-3" for="date">Date:</label>
                                                <!-- Date input -->
                                                <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
                                                <input type="date" class="form-control" id="date" value="<?= $date ?>">
                                            </div>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9">
                            <!-- Button group for daily, weekly & monthly -->
                            <div class="d-flex justify-content-end">
                                <div class="btn-group" role="group" aria-label="page_chooser">
                                    <a style="<?php if ($selected_period == 'daily') {echo 'background:#2652b3; color:white';} else {echo 'background:#3b75f2; color:white';} ?>" id = "period1" type="button" class="btn btn-lg period">Daily</a>
                                    <a style="<?php if ($selected_period == 'weekly') {echo 'background:#2652b3; color:white';} else {echo 'background:#3b75f2; color:white';} ?>" id = "period2" type="button" href="<?php echo base_url('sales/sales/weekly_sales_list/' . date('Y-m-d').'/40'); ?>" class="btn btn-lg period">Weekly</a>
                                    <a style="<?php if ($selected_period == 'monthly') {echo 'background:#2652b3; color:white';} else {echo 'background:#3b75f2; color:white';} ?>" id = "period3" type="button" href="<?php echo base_url('sales/sales/monthly_sales_list/' . date('m') . '/' . date('Y')); ?>" class="btn btn-lg period">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row (Start here)-->
                    <div class="row">
                        <div class="col-xl-12">

                            <div class="row">
                                <div class="col-xl-12">
                                    <!-- Card-->
                                    <div class="card shadow" style="color:black;">
                                        <div class="card-body">

                                            <div class="table-responsive">
                                                <table id="table_daily_sales_list" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Sales ID</th>
                                                            <th>Sales Date</th>
                                                            <th>Sales Total Price (RM)</th>
                                                            <th>Total Discounted Price (RM)</th>
                                                            <th>Person in charge</th>
                                                            <th>Categories</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table_body">
                                                        <?php

                                                        ?>
                                                        <?php foreach ($sales_data as $row) {
                                                            //get category list for each sale in point form
                                                            $sales_category_data = $this->sales_model->get_category_only($row->sale_id);
                                                            $list_html = '';
                                                            foreach ($sales_category_data as $q) {
                                                                $list_html .= '<li>' . $q->item_category_name . '</li>';
                                                            }
                                                        ?>
                                                            <tr>
                                                                <td></td>
                                                                <td><?= $row->sale_id ?></td>
                                                                <td><?= $row->sale_date ?></td>
                                                                <td>RM <?= number_format($row->sale_total_price, 2, '.', '') ?></td>
                                                                <td>RM <?= number_format($row->sale_discounted_price, 2, '.', '') ?></td>
                                                                <td><?= $row->user_fname . " " . $row->user_lname ?></td>
                                                                <td>
                                                                    <ul><?= $list_html ?></ul>
                                                                </td>
                                                                <td><span><button type="button" onclick="view_sale(<?= $row->sale_id ?>)" class="btn icon-btn btn-lg btn-white waves-effect waves-light" data-toggle="modal" data-target="#view_sales"><span style="color:#3b75f2;" class="fas fa-eye"></span></button></span></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- /. Card -->

                                </div>
                            </div>
                            <!-- /. Content Row -->
                            <!-- Modal for viewing sales-->
                            <div class="modal fade" id="view_sales" tabindex="-1" role="dialog" aria-labelledby="view_salesLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#3b75f2;">
                                            <h5 class="modal-title" id="view_salesLabel" style="color:white;">View Sale Detail</h5>
                                            <button style="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="view_sale_model">
                                            <!-- Sales detail will be appended here with ajax upon clicking the view button-->
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of modal for viewing sales-->

                        </div>
                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->