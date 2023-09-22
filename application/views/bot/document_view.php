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

    /* Initial state of the buttons */
    .custom-card .group_buttons {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0);
        /* Start with a fully transparent background */
        opacity: 0;
        /* Initially, set opacity to 0 to hide the buttons */
        transition: background-color 0.3s ease, opacity 0.3s ease;
        /* Add smooth transitions */
        border-radius: 10px;
        /* Match the border radius of the card */
    }

    /* Style for the top button */
    .custom-card .group_buttons .top-button {
        order: 1;
        /* Place it at the top */
        margin-bottom: 10px;
        /* Add spacing between the buttons */
    }

    /* Style for the bottom button */
    .custom-card .group_buttons .bottom-button {
        order: 2;
        /* Place it at the bottom */
    }

    /* Hover state of the buttons */
    .custom-card:hover .group_buttons {
        opacity: 1;
        /* Make the buttons visible with full opacity */
        background-color: rgba(0, 0, 0, 0.7);
        /* Darken the background on hover */
    }

    /* Style for the "View" button */
    .unique-view-button {
        border-radius: 5px;
        padding: 5px 10px;
        margin: 5px;
        cursor: pointer;
        text-decoration: none !important;
        width: 50%;
        transition: background-color 0.3s ease;
    }

    .unique-view-button:hover {
        background-color: #3260c2 !important;
    }

    /* Style for the "Delete" button */
    .unique-delete-button {
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        margin: 5px;
        cursor: pointer;
        width: 50%;
        transition: background-color 0.3s ease;
    }

    .unique-delete-button:hover {
        background-color: #b02c39 !important;
    }
</style>

<?php
// Check if the $pdf_files array is empty
if (empty($pdf_files)) {
    $file_upload = 0;
} else {
    $file_upload = 1;
}
?>

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

    var file_upload = <?php echo $file_upload; ?>;
    var user_role = "<?=$this->session->userdata('user_role')?>";

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
                                <a type="button" href="<?= base_url('items/Items/add_item'); ?>" class="btn bluebtn" data-toggle="modal" data-target="#view_item">Upload Files<i class="fas fa-plus pl-2"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="warning-bar p-2" style="background-color: #f8f3d6ff; color:#956e30ff; border: 2px solid #ded9bb; width:100%;">
                        <i class="fa fa-exclamation-circle warning-icon pl-1"></i> Please provide accurate information to get the best response from the chatbot such as giving examples or specifying the document you are looking for.
                    </div>
                    <!-- Content Row (Start here)-->
                    <div class="row pt-3" style="padding-bottom: 200px;">
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
                                <div class="modal-header" style="background-color:#292e32ff;">
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
                                                <a type="button" onclick="showPdfUploadDialog()" class="btn bluebtn" style="background-color: #3b75f2; color:white;">New File<i class="fas fa-plus pl-2"></i></a>
                                            </div>
                                        </div>
                                        <div class="row" id="file_grid">
                                            <?php foreach ($pdf_files as $pdf_file) : ?>
                                                <div class="col-md-3 pb-4" id="col<?= $pdf_file->doc_id ?>">
                                                    <div class="px-2">
                                                        <div class="custom-card">
                                                            <div class="thumbnail">
                                                                <img src="<?php echo base_url('assets/thumbnail/' . $pdf_file->doc_name . '.png'); ?>" alt="PDF Thumbnail" class="img-responsive">
                                                            </div>
                                                            <div class="caption" style="text-align: center;">
                                                                <h6 class="pt-1 px-1" style="font-weight: 700;"><?php echo $pdf_file->doc_name; ?>.pdf</h6>
                                                                <p class="px-1" style="font-size: 0.7rem;"><?php echo date("F j, Y, g:i a", strtotime($pdf_file->upload_date)); ?></p>
                                                                <div class="group_buttons">
                                                                    <a class="button view-button unique-view-button" style="background-color: #3b75f2; color:white;" href="<?php echo base_url('assets/files/' . $pdf_file->doc_name . '.pdf'); ?>" target="_blank">Open</a>
                                                                    <button class="delete-button unique-delete-button mt-2" onclick="delete_file(<?= $pdf_file->doc_id ?>)" id="button<?= $pdf_file->doc_id ?>" data-id="<?php echo $pdf_file->doc_id; ?>" data-name="<?= $pdf_file->doc_name ?>">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn redbtn" data-dismiss="modal">Close</button>
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