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
                        <h1 class="h3 font-weight-bold" style="color: black">Weekly Sales Report</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Sales</li>
                                <li class="breadcrumb-item active">Weekly Sales Report</li>
                            </ol>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-xl-9">
                            <div class="" style="background-color:#292e32; border-radius:10px; width:35.0em; height:auto;">
                                <div class="px-3 py-2 "> <span style="color:white;">
                                        <form class="form-inline">
                                            <div class="form-group">
                                                <label style="color:white;" class="mr-2 " for="start_date">Start Date</label>
                                                <!-- Date input -->
                                                <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
                                                <input type="date" class="form-control date" id="start_date" value="<?= $start_date ?>">

                                                <label style="color:white;" class="mr-2 ml-5" for="end_date">End Date</label>
                                                <!-- Date input -->
                                                <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
                                                <input type="date" class="form-control date" id="end_date" value="<?= $end_date ?>">
                                            </div>

                                        </form>

                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-3">
                            <!-- Button group for daily, weekly & monthly -->
                            <div class="d-flex justify-content-end">
                                <div class="btn-group" role="group" aria-label="page_chooser">
                                    <a style="color:white; <?php if ($selected_period == 'weekly') {
                                                                echo 'background:#2652b3; color:white';
                                                            } ?>" id="period2" type="button" class="btn btn-lg period">Weekly</a>
                                    <a style="color:white; <?php if ($selected_period == 'monthly') {
                                                                echo 'background:#2652b3; color:white';
                                                            } ?>" id="period3" type="button" href="<?php echo base_url('sales/sales_report/monthly_sales_report/' . date('m') . '/' . date('Y')); ?>" class="btn btn-lg period">Monthly</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-xl-9">
                        </div>
                        <div class="col-xl-3">
                            <div class="d-flex justify-content-end">
                                <form method="post" action=" <?= base_url('sales/sales_report/export_weekly_report/' . $start_date . '/' . $end_date . '/'); ?>">
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
                                <div class="card-header" style="background-color:white; color:black;">
                                    <h3 class="pt-2" style="font-weight: 800;">
                                        <center>Sales Report (<?= $start_date ?>) - (<?= $end_date ?>)</center>
                                    </h3>
                                </div>
                                <div class="card-body" style="background-color: white; color:black;">
                                    <div class="table-responsive">
                                        <table id="table_weekly_sales_report" class="table table-striped">
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