<style>
.card {
    transition:transform 0.25s ease;
}

.card:hover {
    -webkit-transform:scale(1.1);
    transform:scale(1.1);
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
            <div id="content" class="flex-fill" style="background:white;">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mt-5 font-weight-bold" style="color: black">Items by Category</h1>
                    </div>

                     <!-- Breadcrumb -->
                     <div class="row" >
                        <div class="breadcrumb-wrapper col-xl-12">
                            <ol class="breadcrumb" style = "background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('users/Dashboard/Employee');?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Items by Category</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Content Row. 1 row = 4 cards -->
                    <div class="row px-4">

                    <!-- <php foreach ($items_categories_data as $item_category): var_dump($item_category); ?> hello <br><br> <php endforeach; die;?> -->

                        <?php $x = 0;?>
                        <?php foreach ($items_categories_data as $item_category): ?>
                            <div class="col-lg-3 mb-4">
                                <div class="card mb-4 h-100 shadow-sm"> <!-- h-100 to make cards same height despite some content being lesser than some f5cac3-->
                                    <div class="card-header py-3" style="background-color: #F6FFF8">
                                        <a href="<?= base_url('items/Items/items_in_category/'.$item_category->item_category_id); ?>" class="stretched-link"></a>
                                    </div>
                                    <div class="card-body font-weight-bold text-center" style="color:white; background-color: <?php if ($x % 2 == 0) echo '#284d9e'; else echo '#3b75f2ff' ?>"><?= $item_category->item_category_name?></div>
                                </div>
                            </div>
                            <?php $x++; ?>
                        <?php endforeach; ?>

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- /.content -->
