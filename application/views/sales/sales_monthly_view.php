<!-- Plug in for sweetalert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                        <h1 class="h3 font-weight-bold" style="color: black">Monthly Sales</h1>
                    </div>

                     <!-- Breadcrumn -->
                     <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Sales</li>
                                <li class="breadcrumb-item active">Monthly Sales</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <!-- Month and year selection -->
                        <div class="col-xl-2">
                            <div class="" style="background-color:#292e32; border-radius:10px; width:9.6em; height:auto;">
                                <div class="px-3 py-auto "> <span style="color:white;">
                                        <form class="form-inline">
                                            <div class="form-group">
                                                <label style="color:white; font-weight:500; " class="mr-3 py-3 " for="year">Year</label>
                                                <select id="year" class="form-control form-select form-select-lg year">
                                                    <option value="<?= $year ?>" selected><?= $year ?></option>
                                                    <option value="2023">2023</option>
                                                    <option value="2022">2022</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2017">2017</option>
                                                    <option value="2016">2016</option>
                                                    <option value="2015">2015</option>
                                                </select>
                                            </div>
                                        </form>
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-10">
                            <!-- Button group for daily, weekly & monthly -->
                            <div class="d-flex justify-content-end">
                                <div class="btn-group" role="group" aria-label="page_chooser" ">
                                    <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
                                    <a style="<?php if ($selected_period == 'daily') {echo 'background:#2652b3; color:white';} else {echo 'background:#3b75f2; color:white';} ?>" id = "period1" type="button" href="<?php echo base_url('sales/sales/daily_sales_list/' . date('Y-m-d')); ?>" class="btn btn-lg period">Daily</a>
                                    <a style="<?php if ($selected_period == 'weekly') {echo 'background:#2652b3; color:white';} else {echo 'background:#3b75f2; color:white';} ?>" id = "period2" type="button" href="<?php echo base_url('sales/sales/weekly_sales_list/' . date('Y-m-d').'/40'); ?>" class="btn btn-lg period">Weekly</a>
                                    <a style="<?php if ($selected_period == 'monthly') {echo 'background:#2652b3; color:white';} else {echo 'background:#3b75f2; color:white';} ?>" id = "period3" type="button" class="btn btn-lg period">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-xl-12">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" onclick="load_table(<?php if ($month == 1) {
                                                                                echo $month;
                                                                            } else {
                                                                                echo $month - 1;
                                                                            } ?>)" class="btn btn-dark month"><span style="" class="fas fa-chevron-left"></span></button>
                                <button type="button" onclick="load_table(1)" class="btn btn-dark <?php if ($month == 1) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Jan</button>
                                <button type="button" onclick="load_table(2)" class="btn btn-dark <?php if ($month == 2) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Feb</button>
                                <button type="button" onclick="load_table(3)" class="btn btn-dark <?php if ($month == 3) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Mar</button>
                                <button type="button" onclick="load_table(4)" class="btn btn-dark <?php if ($month == 4) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Apr</button>
                                <button type="button" onclick="load_table(5)" class="btn btn-dark <?php if ($month == 5) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">May</button>
                                <button type="button" onclick="load_table(6)" class="btn btn-dark <?php if ($month == 6) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Jun</button>
                                <button type="button" onclick="load_table(7)" class="btn btn-dark <?php if ($month == 7) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Jul</button>
                                <button type="button" onclick="load_table(8)" class="btn btn-dark <?php if ($month == 8) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Aug</button>
                                <button type="button" onclick="load_table(9)" class="btn btn-dark <?php if ($month == 9) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Sep</button>
                                <button type="button" onclick="load_table(10)" class="btn btn-dark <?php if ($month == 10) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Oct</button>
                                <button type="button" onclick="load_table(11)" class="btn btn-dark <?php if ($month == 11) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Nov</button>
                                <button type="button" onclick="load_table(12)" class="btn btn-dark <?php if ($month == 12) {
                                                                                                        echo 'active';
                                                                                                    } ?> month">Dec</button>

                                <button type="button" onclick="load_table(<?php if ($month == 12) {
                                                                                echo $month;
                                                                            } else {
                                                                                echo $month + 1;
                                                                            } ?>)" class="btn btn-dark month"><span style="" class="fas fa-chevron-right"></span></button>
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
                                                <table id="table_monthly_sales_list" class="table table-striped">
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