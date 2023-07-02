<style>
    .container {
        flex: 1;
        margin: 0 auto;
    }

    .chat-card {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .chat-header {
        background-color: #f2f2f2;
        padding: 10px;
        font-weight: bold;
        text-align: center;
        color: #333333;
    }

    .chat-container {
        flex: 1;
        padding: 10px;
        max-height: calc(100% - 100px);
        overflow-y: auto;
    }

    .user-message {
        background-color: #e9e9e9;
        color: #333333;
        border-radius: 8px;
        padding: 8px;
        margin-bottom: 10px;
    }

    .chatbot-message {
        background-color: #d5e8d4;
        color: #333333;
        border-radius: 8px;
        padding: 8px;
        margin-bottom: 10px;
    }

    .chat-footer {
        background-color: #f2f2f2;
        padding: 10px;
        display: flex;
        align-items: center;
    }

    .input-group {
        flex: 1;
        margin-bottom: 0;
    }

    .form-control:focus {
        box-shadow: none;
    }

    .fixed-bottom-wrapper {
        position: fixed;
        bottom: 0;
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

                            <div class="container">
                                <div class="chat-card">
                                    <div class="chat-header">
                                        Chatbot
                                    </div>
                                    <div class="chat-container">
                                        <div class="chat-box" id="chat-box" style="height: 1000px;">
                                            <!-- Sample chat messages -->
                                            <div class="chatbot-message">Hello! How can I assist you today?</div>
                                            <div class="user-message">I have a question about your products.</div>
                                            <div class="chatbot-message">Sure, I'll be happy to help. What do you want to know?</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xl-12">

                                    <div class="fixed-bottom-wrapper ">
                                        <div class="input-group" style="width: 500px;">
                                            <input type="text" class="form-control" placeholder="Type your message..." id="user-input" >
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="send-btn">Send</button>
                                            </div>
                                        </div>
                                    </div>


                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-xl-12">

                            <div class="container">
                                <div class="chat-card" style="width: 100%;">
                                    <div class="chat-footer fixed-bottom-wrapper ">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Type your message..." id="user-input">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="send-btn">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div> -->

                    <!-- /. Content Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->