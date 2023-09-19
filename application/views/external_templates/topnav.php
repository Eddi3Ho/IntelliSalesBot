<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.min.js"></script>
<link rel="stylesheet" href="sweetalert2.min.css">
<style>
    .nav-link {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .nav-link:hover{
        color: #3b75f2 !important;
    }
    .nav-link.active {
        color: white !important;
        text-decoration: underline;
    }

    .bluebtn{
        background-color: #3b75f2; 
        color:white;
        transition: background-color 0.3s ease;
    }
    /* hover effect for all blue buttons */
    .bluebtn:hover {
        background-color: #3260c2 !important;
        color:white !important;
    }

    .redbtn
    {
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        margin: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .redbtn:hover {
        background-color: #b02c39 !important;
        color: white !important;
    }

    .dropdown-item {
        color: white;
        font-size: 1.0rem;
        font-weight: 600;
    }

    .dropdown-item>hover {
        background: rgba(255, 255, 255, 0.1) !important;
        color: rgba(255, 237, 109, 1) !important;
    }

    #register_btn>hover {
        opacity: 0.90;
    }

    .navbar-nav>li>a {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }

    .navbar {
        min-height: px !important
    }

    #nav_line {
        border: none;
        border-left: 1px solid hsla(200, 10%, 50%, 100);
        background-color: white;
        height: 5vh;
        width: 1px;
    }

    /* Make it possible for the item image list to scroll */
    #scroll_notification {
        max-height: 18.0em;
        overflow-y: auto;
    }
</style>

<!-- Topbar -->
<nav class="navbar sticky-top navbar-expand topbar" style="background-color: #292e32;">

    <!-- Logo Image-->
    <!-- <nav class="navbar navbar-light bg-light">   -->
    <a class="navbar-brand py-0 ">
        <img src="<?php echo base_url('assets/img/text_only_logo.png'); ?>" height="70" width="160" alt="">
    </a>
    <!-- </nav> -->

    <!-- Float left Group -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item px-2">
            <a class="nav-link <?php if ($selected == 'dashboard') echo 'active'; ?>" href="<?= base_url('users/Dashboard/Employee'); ?>">Dashboard</a>
        </li>

        <li class="nav-item px-2">
            <a class="nav-link <?php if ($selected == 'sales') echo 'active'; ?>" href="<?= base_url('sales/sales/'); ?>">Sales</a>
        </li>

        <li class="nav-item px-2">
            <a class="nav-link <?php if ($selected == 'items') echo 'active'; ?>" href="<?= base_url('items/Items/items_categories_log'); ?>">Items</a>
        </li>

        <li class="nav-item px-2">
            <a class="nav-link <?php if ($selected == 'stock') echo 'active'; ?>" href="<?= base_url('items/Items/items_low_on_stock'); ?>">Items Running Low on Stock</a>
        </li>

        <?php
        //get notification data
        $notifcation_data = $this->items_model->select_all_sorted_items_low_on_stock();
        $no_notifcation_data = count($notifcation_data);
        ?>
        <!-- Notification button -->
        <li class="nav-item px-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - notification -->
                <span class="badge badge-secondary badge-counter text-dark" style="background-color: #FFF1F3;"><?= $no_notifcation_data ?>+</span>
            </a>

            <!-- Dropdown - notification -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header bg-danger">
                    Notification
                </h6>
                <div id="scroll_notification">
                    <?php foreach ($notifcation_data as $row) { ?>
                        <a class="dropdown-item d-flex align-items-center" style="height: 5.0em;">
                            <div class="text-dark">
                                <div class="text-primary mb-2" style="font-weight: 800;"><?=$row->item_name?></div>
                                <span class="small" >Quantity: <div class="badge badge-danger text-wrap mr-3" style="font-size: 1.0em;"><?=$row->item_quantity?></div><?php if($row->item_quantity<10){echo " ";}?><i class="fas fa-chevron-left fa-lg mr-2"></i>
                                Restock: <div class="badge badge-dark text-wrap" style="font-size: 1.0em"><?=$row->item_restock_level?></div></span>
                            </div>
                        </a>
                    <?php } ?>
                </div>
                <a class="dropdown-item text-center small text-gray-500" href="<?= base_url('items/Items/items_low_on_stock');?>">Show All</a>
            </div>
        </li>

        <li class="nav-item pl-1">
            <a class="nav-link" onclick="logout()" ?>
                <button type="button" id="register_btn" class="btn bluebtn" style="background-color: white; color: #3b75f2; font-size: 0.9em; border-radius:15px; font-weight: 800;"> <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>Logout</button>
            </a>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<script>
    function logout() {
        Swal.fire({
            text: 'Are you sure you want to Log Out?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Log Out'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('users/login/logout'); ?>";
            }
        })
    }
</script>