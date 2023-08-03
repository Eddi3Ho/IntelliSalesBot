<script src="<?php echo base_url() ?>/assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url() ?>/assets/vendor/chart.js/Chart.min.js"></script>

<body id="page-top">

     <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content" >
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-xl-6 col-md-6">
                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 mt-4 font-weight-bold mt-5" style="color: black">Dashboard</h1>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-md-6">
                            <!-- Current Date & Time -->
                            <div class="align-baseline float-right">
                                <div class="mt-4" style="background-color:white;  width:13.0em; height:auto;">
                                    <div class="px-1 py-auto mb-2">
                                        <h5 class="py-1 mt-5" style="font-weight:600;  border: 2px solid #3b75f2; border-radius:10px;">
                                            <span style="color:#3b75f2;">
                                                <center>DATE: <?php date_default_timezone_set("Asia/Kuala_Lumpur"); echo date('Y-m-d'); ?></center>
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Card 1 - Total Sales -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <a href = "<?=base_url('sales/sales')?>" style = "text-decoration:none">
                                <div class="card border-left-success shadow h-100 py-2" >
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Sales</div>
                                                <div class = "mr-2 h5 mb-0 font-weight-bold text-gray-800 " style="float: left;" >RM</div><div id="sales_counter" style="float: left;" class="h5 mb-0 font-weight-bold text-gray-800 counting_number"></div>
                                            </div>
                                            
                                            <div class="col-auto">
                                                <i class="fas fa-hand-holding-usd fa-2x" style="color: #40916c"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Card 2 - Total Item -->
                        <div class="col-xl-4 col-md-6 mb-4">
                        <a href = "" style = "text-decoration:none">
                            <a href="<?php echo base_url('items/Items/items_categories_log'); ?>" style="text-decoration:none">
                                <div class="card border-left-primary shadow h-100 py-2" >
                                    <div class="card-body" href="">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Items</div>
                                                <div id="items_counter" class="h5 mb-0 font-weight-bold text-gray-800 counting_number">0</div>
                                            </div>
                                            
                                            <div class="col-auto">
                                                <i class="fas fa-prescription-bottle-alt fa-2x" style="color: #1565c0"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!--Card 3 - Items Running Low on Stock -->
                        <div class="col-xl-4 col-md-6 mb-4">
                        <a href = "" style = "text-decoration:none">
                            <a href="<?php echo base_url('items/Items/items_low_on_stock'); ?>" style="text-decoration:none">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body" href="">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color:#ED495D">Items Running Low on Stock</div>
                                            <div id="items_low_on_stock" class="h5 mb-0 font-weight-bold counting_number"style="color:#ED495D">0</div>
                                        </div>
                                        
                                        <div class="col-auto">
                                            <i class="fas fa-cart-arrow-down fa-2x" style="color: #ED495D"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="row mb-4">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card h-100 shadow mb-4">
                                <div class="card-header py-3" style="background-color: #292e32; ">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: white">Items By Category</div>
                            </div>
                            
                            <div class="card-body">
                                <div class="chart-bar">
                                <canvas id="item_by_category_barChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

            <!-- End of Main Content -->

            <script>
                var counter1 = <?=number_format($total_latest_sales[0]->total_sales_today, 2, '.', '')?>;
                var counter2 = <?= $total_items ?>;
                var counter3 = <?= $items_low_on_stock ?>;

                // Bar Chart
                var ctx = document.getElementById("item_by_category_barChart");
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [<?php $counter = 0;
                                    foreach ($total_items_by_category as $row) : ?> "<?php if ($counter < 5) {echo $row['item_category_name'];} $counter++; ?>", <?php endforeach; ?>],
                        datasets: [{
                            label: "Item",
                            backgroundColor: ["#3bceac", "#ff99c8", "#ca7df9", "#758bfd", "#ffc09f"],
                            borderColor: "#4e73df",
                            data: [<?php $counter = 0;
                                    foreach ($total_items_by_category as $row) : ?> "<?php if ($counter < 5) {echo $row['COUNT(items.item_id)'];}$counter++; ?>", <?php endforeach; ?>],
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'month'
                                },
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 5
                                },
                                maxBarThickness: 25,
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: 6,
                                    maxTicksLimit: 5,
                                    padding: 10,
                                },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            }],
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            titleMarginBottom: 10,
                            titleFontColor: '#6e707e',
                            titleFontSize: 14,
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                                }
                            }
                        },
                    }
                });
            </script>
