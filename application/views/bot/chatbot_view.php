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
        content: "Write something...";
        color: gray;
    }

    .textarea:focus {
        outline: none;
        box-shadow: none;
    }

    .chatbubble {
        border-radius: 20px;
    }

    .convobody {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-outline-dark:disabled {
        color: #5a5c68;
        border-color: #5a5c68;
        opacity: 1;
    }
</style>
<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var new_chat = "<?= $new_chat ?>";
    var current_con_id = <?= $latest_con_id ?>;
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
                        <h1 class="h3 font-weight-bold" style="color: black">Sales Chatbot</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Sales Chatbot</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Content Row (Start here)-->
                    <div class="row" style="padding-bottom: 200px;">
                        <div class="col-xl-12">

                            <div class="card shadow ">
                                <div class="card-body" style="min-height: 1000px;">

                                    <div class="row ">
                                        <div class="col-xl-2" style="border-right: black;" id="conversation_list">


                                        </div>

                                        <div class="col-xl-10 px-5" id="conversation_body">

                                            <div class="row justify-content-center py-2 pt-5" id="new_chat_info" style="padding-left: 20%; padding-right:20%">
                                                <div class="col-md-4 text-center">
                                                    <i class="fas fa-lightbulb pr-2" style="color:#ffcd0a; font-size: 2.0rem;"></i>
                                                    <div class="pb-2" style="font-weight:bold; font-size: 1.2rem;">Examples</div>
                                                    <button type="button" onclick="enter_prompt('Which month in the past 12 months has been the most profitable?')" class="btn btn-outline-dark mb-2">Which month in the past 12 months has been the most profitable?</button><br>
                                                    <button type="button" onclick="enter_prompt('Name the top 5 highest selling item for the past 5 months')" class="btn btn-outline-dark mb-2">Name the top 5 highest selling item for the past 5 months</button><br>
                                                    <button type="button" onclick="enter_prompt('Give me a sales report for this month?')" class="btn btn-outline-dark">Give me a sales report for this month?</button>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <i class="fas fa-bolt pr-2" style="color:#007AFF; font-size: 2.0rem;"></i>
                                                    <div class="pb-2" style="font-weight:bold; font-size: 1.2rem;">Capabilities</div>
                                                    <button disabled type="button" class="btn btn-outline-dark mb-2">Remembers what user said earlier in the conversation</button><br>
                                                    <button disabled type="button" class="btn btn-outline-dark mb-2">Allows user to provide follow-up corrections</button><br>
                                                    <button disabled type="button" class="btn btn-outline-dark">Trained to decline inappropriate requests</button>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <i class="fas fa-exclamation pr-2" style="color:#FF0000; font-size: 2.0rem;"></i>
                                                    <div class="pb-2" style="font-weight:bold; font-size: 1.2rem;">Limitation</div>
                                                    <button disabled type="button" class="btn btn-outline-dark mb-2">May occasionally generate incorrect information</button><br>
                                                    <button disabled type="button" class="btn btn-outline-dark mb-2">May occasionally produce harmful instructions or biased content</button><br>
                                                    <button disabled type="button" class="btn btn-outline-dark">Limited knowledge of world and events after 2021</button>
                                                </div>
                                            </div>



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
                                        <a onclick="enter_prompt()" class="btn btn-success ml-4 mt-auto" style="margin-right: -7px;"><i class="fas fa-paper-plane"></i></a>
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