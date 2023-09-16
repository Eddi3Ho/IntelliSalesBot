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

    .thumbnail img {
        width: 100%;
        /* Adjust the image width as needed (e.g., 100% for full width) */
        height: auto;
        /* This ensures the aspect ratio is maintained */
    }

    .custom-card {
        border: 1px solid #ccc;
        border-radius: 10px;
        position: relative;
        /* Ensure relative positioning for absolute elements */
        padding-right: 1px;
        padding-left: 1px;
        padding-top: 3px;
    }

    /* Style the close button */
    .close-button {
        position: absolute;
        top: -13px;
        right: -15px;
        background-color: transparent;
        border: none;
        font-size: 27px;
        cursor: pointer;
        color: #DC3545;
        transition: color 0.3s ease;
        /* Add a smooth transition for color changes */
    }

    .close-button:hover {
        color: #8B0000;
        /* Change to a darker color on hover */
    }
</style>
<!-- Set base url to javascript variable-->
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var new_chat = "<?= $new_chat ?>";
    var current_con_id = <?= $latest_con_id ?>;

    var existing_file_names = []; // Initialize an empty JavaScript array

    <?php foreach ($pdf_files as $pdf_file) : ?>
        <?php
        // Use json_encode to safely pass PHP data to JavaScript
        $pdf_file_json = json_encode($pdf_file->doc_name);
        ?>
        existing_file_names.push(<?php echo $pdf_file_json; ?>); // Push each PHP value into the JavaScript array
    <?php endforeach; ?>

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
                        <h1 class="h3 font-weight-bold" style="color: black">Document Chatbot</h1>
                    </div>

                    <!-- Breadcrumn -->
                    <div class="row">
                        <div class="breadcrumb-wrapper col-xl-9">
                            <ol class="breadcrumb" style="background-color:rgba(0, 0, 0, 0);">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(''); ?>"><i class="fas fa-tachometer-alt pr-2"></i>Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Doument Chatbot</li>
                            </ol>
                        </div>
                        <div class="col-xl-3">
                            <div class="d-flex justify-content-end mb-4">
                                <a type="button" href="<?= base_url('items/Items/add_item'); ?>" class="btn bluebtn" style="border: 3px solid #3b75f2; color:#3b75f2; font-weight:bold" data-toggle="modal" data-target="#view_item">Upload Files<i class="fas fa-plus pl-2"></i></a>
                            </div>
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

                    <!-- File Management Modal -->
                    <div class="modal fade" id="view_item" tabindex="-1" role="dialog" aria-labelledby="view_itemLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#3b75f2;">
                                    <h5 class="modal-title" id="view_itemLabel" style="color:white;">File Management</h5>
                                    <button style="color:white;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="file_body">
                                        <div class="px-2 pb-2">
                                            <div class="warning-bar p-2" style="background-color: #f8f3d6ff; color:#956e30ff; border: 2px solid #ded9bb;">
                                                <i class="fa fa-exclamation-circle warning-icon pl-1"></i> Please refrain from uploading documents that contain similar content, as it may prevent the chatbot from giving you accurate answer.
                                            </div>
                                        </div>
                                        <div class="row p-2 pb-4">
                                            <div class="col-md-12  text-right">
                                                <a type="button" onclick="showPdfUploadDialog()" class="btn bluebtn" style="border: 3px solid #3b75f2; color:#3b75f2; font-weight:bold">New File<i class="fas fa-plus pl-2"></i></a>
                                            </div>
                                        </div>
                                        <div class="row" id = "file_grid">
                                            <?php foreach ($pdf_files as $pdf_file) : ?>
                                                <div class="col-md-3 pb-4">
                                                    <div class="px-2">
                                                        <div class="custom-card">
                                                            <!-- Use your unique class name here -->
                                                            <div class="thumbnail">
                                                                <button class="close-button" data-pdf-id="<?php echo $pdf_file->doc_id; ?>">
                                                                    <i class="fa fa-times-circle"></i>
                                                                </button>
                                                                <a href="<?php echo base_url('assets/files/' . $pdf_file->doc_name . '.pdf'); ?>" target="_blank">
                                                                    <img src="<?php echo base_url('assets/thumbnail/' . $pdf_file->doc_name . '.png'); ?>" alt="PDF Thumbnail" class="img-responsive">
                                                                </a>
                                                            </div>
                                                            <div class="caption" style="text-align: center;">
                                                                <h6 class="pt-1 px-1" style="font-weight: 700;"><?php echo $pdf_file->doc_name; ?>.pdf</h6>
                                                                <p class="px-1" style="font-size: 0.7rem;"><?php echo date("F j, Y, g:i a", strtotime($pdf_file->upload_date)); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn bluebtn" style="border: 3px solid #3b75f2; color:#3b75f2; font-weight:bold" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.Modal -->


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            <script>

            </script>