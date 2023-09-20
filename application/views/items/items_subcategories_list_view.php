
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
</style>
<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?= base_url();?>";
</script>

<!-- Pop up after user added a new item-->
<?php if($this->session->flashdata('insert_message')){?>
<script>
    var item_subcategory_name = "<?= $this->session->flashdata('item_subcategory_name');?>";
    Swal.fire({
        icon: 'success',
        text: '"' + item_subcategory_name + '" has been added',
    })
</script>
<?php } ?>

<!-- Pop up after user edit an existing item-->
<?php if($this->session->flashdata('edit_message')){?>
<script>
    var item_subcategory_name = "<?php echo $this->session->flashdata('item_subcategory_name');?>";
    Swal.fire({
        icon: 'success',
        text: '"' + item_subcategory_name + '" has been edited',
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

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 font-weight-bold" style="color: black"><?= $item_category_data->item_category_name ?></h1>
                   
                            <a type="button" href="<?= base_url('items/Items/items_categories');?>" class="btn bluebtn" >Back<i class="fas fa-undo pl-1"></i></a>
                        
                </div>

                <!-- Breadcrumb -->
                <div class="row" >
                    <div class="breadcrumb-wrapper col-xl-8">
                        <ol class="breadcrumb" style = "background-color:rgba(0, 0, 0, 0);">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('items/Items/items_categories');?>"><i class="fas fa-tags mr-2"></i>Item Categories</a>
                            </li>
                            <li class="breadcrumb-item active">Subcategory: <?= $item_category_data->item_category_name ?></li>
                        </ol>
                    </div>
                    <div class = "col-xl-4">
                        <div class = "d-flex justify-content-end">
                            <button type="button" class="btn bluebtn" data-toggle="modal" data-target="#add_item_subcategory">Add Item Subcategory<i class="fas fa-plus pl-2"></i></button>
                        </div>
                    </div>
                </div>
                <!-- Content Row (Start here)-->
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Card-->
                        <div class="card ">
                        <!-- <=$this->session->flashdata('message')?>  -->
                            <div class="card-body">
                            
                            <div class="table-responsive">
                                <table id="item_subcategories_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>Subcategory</th>
                                        <th>Total Items</th>
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

                <div>
                <!-- Add New Item Subcategory Modal -->
                <div class="modal fade" id="add_item_subcategory" tabindex="-1" role="dialog" aria-labelledby="add_item_subcategoryLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style = "background-color:#3b75f2ff;">
                            <h5 class="modal-title" id="add_item_subcategoryLabel" style ="color:white;">Add Item Subcategory</h5>
                            <button style ="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form method="post" action=" <?= base_url('items/Items/add_item_subcategory/'.$item_category_data->item_category_id); ?>">
                            <div class="modal-body">

                                <div class="form-row pt-2" style="background-color: #E1DFE2">
                                    <div class="form-group col-md-12 px-4 pr-5">
                                        <label for="item_subcategory_name" class="font-weight-bold">Item Subcategory Name</label>
                                        <input type="text" class="form-control" id="item_subcategory_name" name="item_subcategory_name" placeholder="Enter item subcategory name" required>
                                    </div>
                                </div>

                            <div class="modal-footer">
                                <button  type="submit" class="btn btn-success" style="float:right;" >Submit<i class="fas fa-check pl-2"></i></button>
                                <button type="button" class="btn btn-secondary float-right ml-2" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- /.Add New Item Subcategory Modal -->
                </div>
                
                <div>
                <!-- Edit Item Subcategory Modal -->
                <div class="modal fade" id="edit_item_subcategory" tabindex="-1" role="dialog" aria-labelledby="edit_item_subcategoryLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style = "background-color:#3b75f2ff;">
                            <h5 class="modal-title" id="edit_item_subcategoryLabel" style ="color:white;">Edit Item Subcategory</h5>
                            <button style ="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="edit_item_subcategory_information">
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </div>
                <!-- /.Edit Item Category Modal -->
                </div>
                
                </div>
                <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->