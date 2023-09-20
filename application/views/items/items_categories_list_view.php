
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
    var item_category_name = "<?= $this->session->flashdata('item_category_name');?>";
    Swal.fire({
        icon: 'success',
        text: '"' + item_category_name + '" has been added',
    })
</script>
<?php } ?>

<!-- Pop up after user edit an existing item-->
<?php if($this->session->flashdata('edit_message')){?>
<script>
    var item_category_name = "<?php echo $this->session->flashdata('item_category_name');?>";
    Swal.fire({
        icon: 'success',
        text: '"' + item_category_name + '" has been edited',
    })
</script>
<?php } ?>
<script>
$('#edit_item_category').on('show.bs.modal', function(e) {
        var item_category_id = $(e.relatedTarget).data('id');
        alert(item_category_id);

        $.ajax({
            url: base_url + "items/Items/fetch_item_category",
            method:"POST",
            data:{ item_category_id:item_category_id},
            success:function(data)
            {
                $('#item_category_name').val(data.item_category_name);
                alert($('#item_category_name').val(data.item_category_name));
            }
        });
    });
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
                    <h1 class="h3 font-weight-bold" style="color: black">Item Categories</h1>
                </div>

                <!-- Breadcrumb -->
                <div class="row" >
                    <div class = "col-xl-12">
                        <div class = "d-flex justify-content-end mb-4">
                        <button type="button" class="btn bluebtn"  data-toggle="modal" data-target="#add_item_category">Add Item Category<i class="fas fa-plus pl-2"></i></button>
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
                                <table id="item_categories_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>Category</th>
                                        <th>Total Subcategories</th>
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
                <!-- Add New Item Category Modal -->
                <div class="modal fade" id="add_item_category" tabindex="-1" role="dialog" aria-labelledby="add_item_categoryLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style = "background-color:#e56b6f;">
                            <h5 class="modal-title" id="add_item_categoryLabel" style ="color:white;">Add Item Category</h5>
                            <button style ="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form method="post" action=" <?= base_url('items/Items/add_item_category'); ?>">
                            <div class="modal-body">
                                <div class="form-row pt-2" style="background-color: #E1DFE2">
                                    <div class="form-group col-md-12 px-4 pr-5">
                                        <label for="item_category_name" class="font-weight-bold">Item Category Name</label>
                                        <input type="text" class="form-control" id="item_category_name" name="item_category_name" placeholder="Enter item category name" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button  type="submit" class="btn" style="background-color: #FF545D; color: white; float:right;" >Submit<i class="fas fa-check pl-2"></i></button>
                                    <button type="button" class="btn btn-secondary float-right ml-2" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- /.Add New Item Category Modal -->
                </div>
                
                <div>
                <!-- Edit Item Category Modal -->
                <div class="modal fade" id="edit_item_category" tabindex="-1" role="dialog" aria-labelledby="edit_item_categoryLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style = "background-color:#3b75f2ff;">
                            <h5 class="modal-title" id="edit_item_categoryLabel" style ="color:white;">Edit Item Category</h5>
                            <button style ="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="edit_item_category_information">

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