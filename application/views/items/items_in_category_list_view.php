<style>
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

    .table-striped {
        color: black;
    }

    .img_item {
        transition: transform 0.25s ease;
    }

    .img_item:hover {
        -webkit-transform: scale(2.0);
        transform: scale(2.0);
    }
</style>
<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?= base_url(); ?>";
</script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid" style="background:white;">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mt-5 mb-2 font-weight-bold" style="color: black"><?= $items_category_data->item_category_name ?></h1>
                    </div>

                    <!-- Breadcrumb -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-8">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('users/Dashboard/Employee'); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('items/Items/items_categories_log'); ?>"></i>Item Categories</a>
                                </li>
                                <li class="breadcrumb-item active"><?= $items_category_data->item_category_name ?></li>
                            </ol>
                        </div>
                        <!-- Employee Item Categories Page (select an Item Category) -->
                        <div class="col-xl-4">
                            <div class="d-flex justify-content-end">
                                <a type="button" href="<?= base_url('items/Items/items_categories_log'); ?>" class="btn bluebtn" >Back<i class="fas fa-undo pl-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row (Start here)-->
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- Card-->
                            <div class="card shadow">
                                <!-- <=$this->session->flashdata('message')?>  -->
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="items_in_category_table" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Image</th>
                                                    <th>Subcategory</th>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Restock Level</th>
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

                    <!-- Modal -->
                    <div class="modal fade" id="view_item" tabindex="-1" role="dialog" aria-labelledby="view_itemLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#3b75f2ff;">
                                    <h5 class="modal-title" id="view_itemLabel" style="color:white;">Item Information</h5>
                                    <button style="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="item_information">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="redbtn" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.Modal -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->