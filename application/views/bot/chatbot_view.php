<style>
    .fixed-bottom-wrapper {
        position: fixed;
        bottom: 0;
    }

    .textarea {
        display: block;
        height: 100%;
        width: 600px;
        overflow: hidden;
        resize: none;
        font-size: 1.1rem;


    }

    .textarea[contenteditable]:empty::before {
        content: "Placeholder still possible";
        color: gray;
    }

    .textarea:focus {
        outline: none;
        box-shadow: none;
    }
</style>
<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
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
                        <h1 class="h3 font-weight-bold" style="color: black">Chatbot</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Chatbot</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Content Row (Start here)-->
                    <div class="row">
                        <div class="col-xl-12">

                            <div class="card shadow">
                                <div class="card-body" style="height: 1000px;">

                                    <div class="row" style="background-color: ;">
                                        <div class="col-xl-4">
                                            <a onclick="" class="btn btn-success mr-auto" ><i class="fas fa-paper-plane"></i></a>
                                        </div>
                                        <div class="col-xl-6">
                                            <p>In this example, we create a custom CSS class called .rounded-card. We set the border-radius property to 10px to apply a rounded corner effect to the card.

                                                By adding the .rounded-card class to your card element, you can customize the border radius and give the card a rounded appearance. Feel free to adjust the border radius value as needed to achieve your desired design.</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xl-12 d-flex justify-content-center">
                            <div class="fixed-bottom-wrapper mb-5">

                                <div class="card shadow" style="border-radius: 15px;">
                                    <div class="card-body d-flex align-items-center" style="padding-top:10px; padding-bottom:10px;">
                                        <span id="user_prompt" class="textarea" role="textbox" contenteditable></span>
                                        <a onclick="" class="btn btn-success ml-4 mt-auto" style="margin-right: -7px;"><i class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            <script>

            </script>