<style>
    .nav-link {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .sidelink:hover,
    .sidelink.active,
    .navfa:hover,
    .navfa.active {
        color: white !important;
        background: #3b75f2 !important;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar FF545D-->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #292e32">


                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center my-4" href="<?= base_url('users/Dashboard/Manager'); ?>">
                        <img src="<?php echo base_url('assets/img/intellisalesbot_logo.png'); ?>" height="70" width="160" alt="">
                    </a>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">

                    <!---------- MANAGER'S SIDENAV ---------->
                    <!-- Nav Item - Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link sidelink <?php if ($selected == "dashboard") echo 'active'; ?>" href="<?= base_url('users/Dashboard/Manager'); ?>">
                            <i class="fas navfa fa-tachometer-alt <?php if ($selected == "dashboard") echo 'active'; ?>"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Nav Item - Sales >-->
                    <li class="nav-item">
                        <a class="nav-link sidelink <?php if ($selected == "sales") echo 'active'; ?>" href="<?= base_url('sales/sales'); ?>">
                            <i class="fas navfa fa-dollar-sign <?php if ($selected == "sales") echo 'active'; ?>"></i>
                            <span>Sales</span>
                        </a>
                    </li>

                    <!-- Nav Item - Inventory Collapse Menu -->
                    <li class="nav-item">
                        <a class="nav-link sidelink collapsed <?php if ($selected == "items" || $selected == "stock" || $selected == "items_categories") echo 'active'; ?>" href="#" data-toggle="collapse" data-target="#inventory_collapse" aria-expanded="true" aria-controls="accounts_collapse">
                            <i class="fas navfa fa-dolly-flatbed <?php if ($selected == "items" || $selected == "stock" || $selected == "items_categories") echo 'active'; ?>"></i>
                            <span>Inventory</span>
                        </a>
                        <div id="inventory_collapse" class="collapse py-2" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="<?= base_url('items/Items'); ?>">All Items</a>
                                <a class="collapse-item" href="<?= base_url('items/Items/items_low_on_stock'); ?>">Items Low on Stock</a>
                                <a class="collapse-item" href="<?= base_url('items/Items/items_categories'); ?>" >Item Categories</a>
                            </div>
                        </div>
                    </li>


                    <!-- Nav Item - Reports >-->
                    <li class="nav-item">
                        <a class="nav-link sidelink <?php if ($selected == "sales_report") echo 'active'; ?>" href="<?= base_url('sales/sales_report/weekly_sales_report/' . date('Y-m-d') . '/40') ?>">
                            <i class="fas navfa fa-chart-bar <?php if ($selected == "sales_report") echo 'active'; ?>"></i>
                            <span>Reports</span>
                        </a>
                    </li>

                    <!-- Nav Item - Predictions -->
                    <!-- <li class="nav-item">
                        <a class="nav-link sidelink <?php //if ($selected == "sales_prediction") echo 'active'; ?>" href="<?= base_url('sales/Sales_prediction/') ?>">
                            <i class="fas fa-hourglass-half <?php //if ($selected == "sales_prediction") echo 'active'; ?>"></i>
                            <span>Predictions</span>
                        </a>
                    </li> -->

                    <!-- Nav Item - Predictions -->
                    <li class="nav-item">
                        <a class="nav-link sidelink <?php if ($selected == "chatbot") echo 'active'; ?>" href="<?= base_url('bot/Chatbot/') ?>">
                            <i class="fas navfa fa-robot <?php if ($selected == "chatbot") echo 'active'; ?>"></i>
                            <span>Sales Chatbot</span>
                        </a>
                    </li>

                    <!-- Nav Item - Predictions -->
                    <li class="nav-item">
                        <a class="nav-link sidelink <?php if ($selected == "document") echo 'active'; ?>" href="<?= base_url('bot/document/') ?>">
                            <i class="fas navfa fa-file <?php if ($selected == "document") echo 'active'; ?>"></i>
                            <span>Document Chatbot</span>
                        </a>
                    </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline pt-5">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->