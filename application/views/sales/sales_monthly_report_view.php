<!-- Plug in for sweetalert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .period {
        background: #3b75f2;
        color: white;
    }

    .period:hover {
        background: #2652b3;
        color: white
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background: white;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background: #f2f2f2;
    }

    .table-striped thead tr:nth-of-type(odd) {
        background: #292e32;
        color: white;
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
                        <h1 class="h3 font-weight-bold" style="color: black">Monthly Sales Report</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Sales</li>
                                <li class="breadcrumb-item active">Monthly Sales Report</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <!-- Month and year selection -->
                        <div class="col-xl-2">
                            <div class="" style="background-color:#292e32; border-radius:10px; width:9.7em; height:auto;">
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
                                    <a style=" color:white; <?php if ($selected_period == 'weekly') {
                                                                echo 'background:#2652b3; color:white';
                                                            } ?>" id="period2" type="button" href="<?php echo base_url('sales/sales_report/weekly_sales_report/' . date('Y-m-d') . '/40'); ?>" class="btn btn-lg period">Weekly</a>
                                    <a style="color:white; <?php if ($selected_period == 'monthly') {
                                                                echo 'background:#2652b3; color:white';
                                                            } ?>" id="period3" type="button" class="btn btn-lg period">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-xl-9">
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
                        <div class="col-xl-3">
                            <div class="d-flex justify-content-end">
                                <form method="post" action=" <?= base_url('sales/sales_report/export_monthly_report/'.$month.'/'.$year.'/'); ?>">
                                    <button type="submit" style="border-radius:10px; background-color:#1dd3b0; color:white; height:auto; width:auto;" class="btn btn-icon-split pr-1">
                                        <span class="icon text-white-600">
                                            <i class="fas fa-download p-1"></i>
                                        </span>
                                        <span class="text">Export CSV</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row (Start here)-->
                    <div class="row pb-5">
                        <div class="col-xl-12 pb-5">

                            <div class="card shadow">
                                <div class="card-header" style="color:black;">
                                    <h3 class="pt-2" style="font-weight: 800;">
                                        <?php
                                        //Get full name of month based on number of the month
                                        $monthNum  = $month;
                                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                        $monthName = $dateObj->format('F'); 
                                        ?>
                                        <center>Sales Report: (<?= $monthName ?> <?= $year ?>) </center>
                                    </h3>
                                </div>
                                <div class="card-body" style=" color:black;">

                                    <div class="table-responsive">
                                        <table id="table_monthly_sales_report" class="table table-striped">
                                            <thead style="color:white;">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Item ID</th>
                                                    <th>Item Name</th>
                                                    <th>Item Subcategory</th>
                                                    <th>Quantity</th>
                                                    <th>Total Sales (RM)</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color:black;">
                                                <?php
                                                $total_sales = 0;
                                                foreach ($sales_report_data as $row) {  ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><?= $row->item_id ?></td>
                                                        <td><?= $row->item_name ?></td>
                                                        <td><?= $row->item_subcategory_name ?></td>
                                                        <td><?= $row->item_total_quantity ?></td>
                                                        <td><?= $row->item_total_sale ?></td>
                                                    </tr>
                                                <?php
                                                    $total_sales += $row->item_total_sale;
                                                }  ?>
                                            </tbody>
                                            <tfoot style="color:white;">
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td style="background: #3b75f2;">
                                                        <center>Total: RM <?= $total_sales ?></center>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /. Content Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->