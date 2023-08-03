
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
.img_item {
    transition:transform 0.25s ease;
}

.img_item:hover {
    -webkit-transform:scale(2.0);
    transform:scale(2.0);
}
</style>

<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?= base_url();?>";
</script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid" >

                 <!-- Page Heading -->
                 <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 <?php  if ($this->session->userdata('user_role') == 'Employee') echo 'mt-5'; ?> mb-2 font-weight-bold" style="color: black">Items Running Low on Stock</h1>
                </div>

                <?php if (($this->session->userdata('user_role') == 'Employee' )) { ?>
                    <!-- Breadcrumb -->
                    <div class="row" >
                        <div class="breadcrumb-wrapper col-xl-12">
                            <ol class="breadcrumb" style = "background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('users/Dashboard/Employee');?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Items Running Low on Stock</li>
                            </ol>
                        </div>
                    </div>
                <?php } else { ?>
                    <!-- Breadcrumb -->
                    <div class="row" >
                        <div class="breadcrumb-wrapper col-xl-12">
                            <ol class="breadcrumb" style = "background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('users/Dashboard/Employee');?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Items Running Low on Stock</li>
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
                                <table id="items_low_on_stock_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th>No.</th>
                                        <th>Image</th>
                                        <th>Subcategory</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Restock Level</th>
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
                
                </div>
                <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->