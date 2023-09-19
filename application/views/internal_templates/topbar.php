<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.min.js"></script>
<link rel="stylesheet" href="sweetalert2.min.css">

<style>
    #nav_line {
        border: none;
        border-left: 1px solid hsla(200, 10%, 50%, 100);
        background-color: grey;
        height: auto;
        width: 1px;
        margin-left: 4px;
    }

    #scroll_notification {
        max-height: 15.0em;
        overflow-y: auto;
    }

    .greenbtn{

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

    .thicker-shadow {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        /* Adjust the spread radius (20px) to make it thicker */
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
</style>

<!-- <php $user_role = $this->session->userdata['user_role'];?> -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top thicker-shadow" style="background-color: #f0f4f7;">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <?php
            //hide notification if the user is IT
            if ($this->session->userdata('user_role') != 'IT') {
                //get notification data
                $notifcation_data = $this->items_model->select_all_sorted_items_low_on_stock();
                $no_notifcation_data = count($notifcation_data);
            ?>
                <!-- Notification button -->
                <li class="nav-item px-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw" style="color:#3b75f2;"></i>
                        <!-- Counter - notification -->
                        <span class="badge badge-secondary badge-counter text-white" style="background-color: #3b75f2;"><?= $no_notifcation_data ?>+</span>
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
                                        <div class="text-primary mb-2" style="font-weight: 800;"><?= $row->item_name ?></div>
                                        <span class="small">Quantity: <div class="badge badge-danger text-wrap mr-3" style="font-size: 1.0em;"><?= $row->item_quantity ?></div><?php if ($row->item_quantity < 10) {
                                                                                                                                                                                    echo " ";
                                                                                                                                                                                } ?><i class="fas fa-chevron-left fa-lg mr-2"></i>
                                            Restock: <div class="badge badge-dark text-wrap" style="font-size: 1.0em"><?= $row->item_restock_level ?></div></span>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                        <a class="dropdown-item text-center small text-gray-500" href="<?= base_url('items/Items/items_low_on_stock'); ?>">Show All</a>
                    </div>
                </li>
            <?php } ?>

            <!-- Logout button -->
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