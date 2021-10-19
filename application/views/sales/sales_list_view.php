<!-- Plug in for sweetalert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.min.js"></script>
<link rel="stylesheet" href="sweetalert2.min.css">

<style>
    /* tr:nth-child(even) {
  background-color: #E4C2C1;
  
} */
    /* css for hiding select dropdown area */
</style>

<?php

//putting passed subcategory_data array into new array
$subcategory = $subcategory_data;

$subcategory_options = "";
foreach ($subcategory as $s) {
    $subcategory_options .= '<option value="' . $s->item_subcategory_id . '">' . $s->item_subcategory_name . '</option>';
}
?>

<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var subcategory_list = '<?php echo $subcategory_options; ?>';

    //Alert message fades out in 5 seconds
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Sales</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt"></i> Home</a>
                                </li>
                                <li class="breadcrumb-item active">Sales</li>
                            </ol>
                        </div>
                        <div class="col-xl-3 ">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light" data-toggle="modal" data-target="#add_sales">Add Sale <span class="fas fa-plus"></span></button>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row (Start here)-->
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- Display no item message if it exist-->
                            <?=$this->session->userdata('no_item_message') ?>
                            <?php $this->session->unset_userdata('no_item_message');; ?>
                            <!-- Card-->
                            <div class="card ">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="table_sales_list" class="table ">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Sales ID</th>
                                                    <th>Sales Date</th>
                                                    <th>Sales Total Price(RM)</th>
                                                    <th>Person in charge</th>
                                                    <th>Items</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <!-- /. Card -->

                        </div>
                    </div>
                    <!-- /. Content Row -->

                    <!-- Modal for adding sales-->
                    <div class="modal fade" id="add_sales" tabindex="-1" role="dialog" aria-labelledby="add_salesLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#FF545D;">
                                    <h5 class="modal-title" id="add_salesLabel" style="color:white;">Add a sale </h5>
                                    <button style="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <!-- Add sale form -->
                                <form method="post" action=" <?= base_url('sales/sales/add_sales'); ?>">
                                    <div class="modal-body">
                                        <div class="mb-5" style="background-color:#1dd3b0; border-radius:10px; width:13.0em; height:auto;">
                                            <div class="px-1 py-auto mb-2">
                                                <h5 class="py-1" style=" font-weight:600; ">
                                                    <span style="color:white;">
                                                        <center>DATE: <?php date_default_timezone_set("Asia/Kuala_Lumpur");
                                                                        echo date('Y-m-d'); ?></center>
                                                    </span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-xl-4">
                                                <h6>Subcategory</h6>
                                            </div>
                                            <div class="col-xl-4">
                                                <h6>Item</h6>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-xl-4">
                                                <select id="item_subcategory_id" class="form-control form-select form-select-md item_subcategory_id">
                                                    <?php
                                                    foreach ($subcategory as $s) {
                                                        echo '<option value="' . $s->item_subcategory_id . '">' . $s->item_subcategory_name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <select id="item_id" class="form-control form-select form-select-md item_id">

                                                </select>
                                            </div>
                                            <div class="col-xl-1">
                                                <button type="button" name="add" id="add" class="btn btn-success"><span class="fas fa-plus"></span></button>
                                            </div>
                                            <div class="col-xl-3" id = "repeat_message">
                                                
                                            </div>
                                        </div>
                                        <table class="table" id="item_list">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Item</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Discount</th>
                                                    <th scope="col">Original Price</th>
                                                    <th scope="col">Final Price</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_body">
                                                <tr>

                                                </tr>

                                            </tbody>
                                        </table>
                                        <hr class="mt-5">
                                        <!-- Sale total price-->
                                        <div class="form-group row">
                                            <div class="col-xl-6"></div>
                                            <label for="staticEmail" class="col-xl-2 col-form-label">Total Price</label>
                                            <div class="col-xl-4">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">RM</span>
                                                    <input type="number" id="sale_total_price" name="sale_total_price" style="font-weight:600; float:right;" class="form-control sale_total_price" min="0" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xl-6"></div>
                                            <label for="staticEmail" class="col-xl-2 col-form-label">Discounted Price</label>
                                            <div class="col-xl-4">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon2">RM</span>
                                                    <input type="number" id="sale_discounted_price" name="sale_discounted_price" style="font-weight:600; float:right;" class="form-control sale_discounted_price" min="0" value="0" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary mb-2">CONFIRM</button>
                                    </div>
                                </form>
                                <!-- End of add sale form -->
                            </div>
                        </div>
                    </div>
                    <!-- End of modal for adding sales-->

                    <!-- Modal for adding sales-->
                    <div class="modal fade" id="view_sales" tabindex="-1" role="dialog" aria-labelledby="view_salesLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#FF545D;">
                                    <h5 class="modal-title" id="view_salesLabel" style="color:white;">View Sale Detail</h5>
                                    <button style="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                </div>


                                <div class="modal-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of modal for adding sales-->


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php


            ?>

            <script type="text/javascript">
            </script>