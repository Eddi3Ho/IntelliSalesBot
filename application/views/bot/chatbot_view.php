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

    .chatbubble {
        border-radius: 20px;
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
                                <div class="card-body" style="min-height: 1000px;" id="conversation_body">
                                    It was a dark and stormy night, and the full moon illuminated a dense fog that hung in the air. Jack had a bad feeling about the evening ahead, but he forced himself to move forward bravely into the unknown. Jack took a deep breath, then knocked on the door of the ancient castle. He could hear the scurrying of small feet inside, but nobody answered his knock. Uncertain as to what he should do next, Jack held his breath in anticipation


                                    <div class="row justify-content-center py-2" id="new_chat_info">
                                        <div class="col-xl-7 py-2">
                                            <div class="card shadow chatbubble" style=" color: black;">
                                                <div class="card-body">
                                                    Ask the chatbot about something
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-7 py-2">
                                            <div class="card shadow chatbubble" style="color: black;">
                                                <div class="card-body">
                                                    Do not know where to start? Try asking these question!

                                                    <div class="card my-2" style="color: black; background-color: #F2F0F0; border-radius: 40px; width: 50%; padding-top:0px; padding: bottom 0px;">
                                                        <div class="card-body">
                                                            List out the top 5 most profitable item sold in the past 12 months
                                                        </div>
                                                    </div>
                                                    <div class="card my-2" style="color: black; background-color: #F2F0F0; border-radius: 40px; width: 50%; padding-top:0px; padding: bottom 0px;">
                                                        <div class="card-body">
                                                            What are the company’s policies on vacation time and sick leave?
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="row py-2 mr-5 my-1 ml-2">
                                        <div class="card chatbubble mr-4" style="background-color: #eaeaea; color: black; ">
                                            <div class="card-body">
                                                test
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                                        <div class="card chatbubble ml-4" style="background-color:  #007aff; color: white;">
                                            <div class="card-body">
                                                test
                                            </div>
                                        </div>
                                    </div> -->

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