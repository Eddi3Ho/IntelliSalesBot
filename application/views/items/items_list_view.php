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

<!-- Pop up after user added a new item-->
<?php if ($this->session->flashdata('insert_message')) { ?>
    <script>
        var item_name = "<?= $this->session->flashdata('item_name'); ?>";
        Swal.fire({
            icon: 'success',
            text: '"' + item_name + '" has been added',
        })
    </script>
<?php } ?>

<!-- Pop up after user edit an existing item-->
<?php if ($this->session->flashdata('edit_message')) { ?>
    <script>
        var item_name = "<?php echo $this->session->flashdata('item_name'); ?>";
        Swal.fire({
            icon: 'success',
            text: '"' + item_name + '" has been edited',
        })
    </script>
<?php } ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php if (($this->session->userdata('user_role') == 'Admin')) { ?>
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <h1 class="h3 font-weight-bold" style="color: black">Items</h1>
                        </div>
                        <!-- Breadcrumb -->
                        <div class="row">
                            <div class="breadcrumb-wrapper col-xl-9">
                                <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                    <li class="breadcrumb-item">
                                        <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">Inventory</li>
                                    <li class="breadcrumb-item active">All item</li>
                                </ol>
                            </div>
                            <div class="col-xl-3">
                                <div class="d-flex justify-content-end mb-4">
                                    <a type="button" href="<?= base_url('items/Items/add_item'); ?>" class="btn bluebtn" >Add Item<i class="fas fa-plus pl-2"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <h1 class="h3 font-weight-bold" style="color: black">All Items</h1>
                        </div>
                        <!-- Breadcrumb -->
                        <div class="row">
                            <div class="breadcrumb-wrapper col-xl-8">
                                <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                    <li class="breadcrumb-item">
                                        <a href="<?= base_url('users/Dashboard/Manager'); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">All Items</li>
                                </ol>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Content Row (Start here)-->
                    <div class="row pb-5">
                        <div class="col-xl-12 pb-5">
                            <!-- Card-->
                            <div class="card shadow">
                                <!-- <=$this->session->flashdata('message')?>  -->
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="items_table" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <!-- <th>No.</th> -->
                                                    <th>No.</th>
                                                    <th>Category</th>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Selling Price</th>
                                                    <th>Buying Price</th>
                                                    <th>Action</th>
                                                    <th>Last Updated</th>
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
                                <div class="modal-header" style="background-color:#292e32ff;">
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
                                    <button type="button" class="btn redbtn"  data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.Modal -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->