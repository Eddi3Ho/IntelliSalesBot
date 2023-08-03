<!-- Plug in for sweetalert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Make it possible for the item image list to scroll */
    .scroll {
        max-height: 14.0em;
        overflow-y: auto;
    }

    /* Enlarge image upon hovering over it*/
    .img_item {
        transition: transform 0.25s ease;
    }

    .img_item:hover {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
        border: 2px solid rgba(0, 0, 0, 0.5) !important;
    }

    /* CSS for individual item image and image text labelling */
    .image_container {
        position: relative;
        margin: 0 auto;
    }

    .image_container .img_item {
        vertical-align: middle;
    }

    .image_container .content {
        position: absolute;
        bottom: 0;
        background: rgb(0, 0, 0);
        /* Fallback color */
        background: rgba(0, 0, 0, 0.5);
        /* Black background with 0.5 opacity */
        color: #f1f1f1;
        padding: 0.5em;
        height: auto;
        width: 100%;
    }
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
    var i = <?php echo $no_sales_item_data; ?>;

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
                        <h1 class="h3 mt-5 font-weight-bold" style="color: black">Edit Sales</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('sales/sales/'); ?>"></i>Sales</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Sales</li>
                            </ol>
                        </div>
                        <div class="col-xl-3">
                            <div class="d-flex justify-content-end">
                                <a type="button" href="<?= base_url('sales/sales/'); ?>" style="border: 3px solid #3b75f2; color:#3b75f2; font-weight:bold" class="btn bluebtn">Back<i class="fas fa-undo pl-2"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row (Start here)-->
                    <div class="row pb-5">
                        <div class="col-xl-12">
                            <!-- Display no item message if it exist-->
                            <?= $this->session->userdata('no_item_message') ?>
                            <?php $this->session->unset_userdata('no_item_message'); ?>
                            <!-- Card-->
                            <div class="card ">
                                <div class="card-body">

                                    <!-- Edit sale form -->
                                    <form method="post" action=" <?= base_url('sales/sales/edit_sales/' . $sale_id . ''); ?>">
                                        <div class="modal-body">
                                            <!-- Green box for data and person in charge -->
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-5" style="background-color:#292e32; border-radius:10px; width:13.0em; height:auto;">
                                                        <div class="px-1 py-auto mb-2">
                                                            <h5 class="py-1" style=" font-weight:600; ">
                                                                <span style="color:white;">
                                                                    <center>DATE: <?php date_default_timezone_set("Asia/Kuala_Lumpur");
                                                                                    echo date('Y-m-d'); ?></center>
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex justify-content-end">
                                                        <div class="mb-5" style="background-color:#292e32; border-radius:10px; width:auto; height:auto;">
                                                            <div class="px-3 py-auto ">
                                                                <h5 class=" pt-1" style=" font-weight:600; ">
                                                                    <span style="color:white;">
                                                                        <center>Updated by: <?= $sales_data->user_fname . ' ' . $sales_data->user_lname ?></center>
                                                                    </span>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Subcategory selector and item image picker -->
                                            <div class="row mb-3">
                                                <div class="col-xl-12">
                                                    <div class="card shadow" style="border: 2px solid rgba(0, 0, 0, 0.5);">
                                                        <div class="card-header" style="background-color:rgba(0, 0, 0, 0.1);">
                                                            <div class="row">
                                                                <div class="col-xl-7">

                                                                </div>
                                                                <div class="col-xl-1">
                                                                    <label style="color:black; font-weight:500; " class="pt-1" for="item_subcategory_id">Subcategory</label>
                                                                </div>
                                                                <div class="col-xl-4">
                                                                    <select id="item_subcategory_id" class="form-control form-select form-select-sm ml-2 item_subcategory_id">
                                                                        <?php
                                                                        foreach ($subcategory as $s) {
                                                                            echo '<option value="' . $s->item_subcategory_id . '">' . $s->item_subcategory_name . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body" style="background-color:white;">
                                                            <div class="row scroll py-2" id="item_box">
                                                                <!-- Item images will be implemented here with ajax -->
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Item table -->
                                            <table class="table" id="item_list">
                                                <thead style="color: black;">
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
                                                    <?php
                                                    $counter = 1;
                                                    foreach ($sales_item_data as $row) { ?>
                                                        <tr id="row<?= $counter ?>" class="dynamic-added">
                                                            <td style="width:8%;"><input type="number" name="item_id[]" class="form-control item_id1" value="<?= $row->item_id ?>" readonly /></td>
                                                            <td><input type="text" class="form-control item_name" value="<?= $row->item_name ?>" readonly /></td>
                                                            <td style="width:10%;">
                                                                <div class="input-group-prepend">
                                                                    <input type="number" name="sale_item_quantity[]" placeholder="Enter quantity" class="form-control sale_item_quantity" min="1" max="<?= $row->item_quantity ?>" value="<?= $row->sale_item_quantity ?>" required />
                                                                    <span class="input-group-text" id="basic-addon1">/ <?= $row->item_quantity ?></span>
                                                                </div>
                                                            </td>
                                                            <td style="width:10%;"><input type="number" name="sale_item_discount[]" placeholder="Enter discount" class="form-control sale_item_discount" min="0" max="100" value="<?= $row->sale_item_discount ?>" required /></td>
                                                            <td style="width:15%;">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">RM</span>
                                                                    <input type="number" class="form-control ori_sale_item_price" value="<?= $row->sale_item_quantity * $row->item_price ?>" readonly />
                                                                </div>
                                                            </td>
                                                            <td style="width:15%;">
                                                                <input type="number" style="display:none" class="form-control one_item_price" value="<?= $row->item_price ?>" />
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">RM</span>
                                                                    <input type="number" name="sale_item_price[]" class="form-control sale_item_price" value="<?= $row->sale_item_total_price ?>" readonly />
                                                                </div>
                                                            </td>
                                                            <td style="width:2%;">
                                                                <button type="button" name="remove" id="<?= $counter ?>" class="btn btn-danger btn_remove"><span class="fas fa-times"></span></button>
                                                            </td>
                                                        </tr>
                                                    <?php $counter++;
                                                    } ?>

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
                                                        <input type="number" id="sale_total_price" name="sale_total_price" style="font-weight:600; float:right;" class="form-control sale_total_price" min="0" value="<?= $sales_data->sale_total_price ?>" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-xl-6"></div>
                                                <label for="staticEmail" class="col-xl-2 col-form-label">Discounted Price</label>
                                                <div class="col-xl-4">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">RM</span>
                                                        <input type="number" id="sale_discounted_price" name="sale_discounted_price" style="font-weight:600; float:right;" class="form-control sale_discounted_price" min="0" value="<?= $sales_data->sale_discounted_price ?>" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" style="border: 3px solid #3b75f2; color:#3b75f2; font-weight:700" class="btn bluebtn mb-2">CONFIRM <span class="fas fa-check"></span></button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- /. Card -->

                        </div>
                    </div>
                    <!-- /. Content Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->