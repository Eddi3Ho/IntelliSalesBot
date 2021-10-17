<script src="<?php echo base_url()?>/assets/vendor/jquery/jquery.min.js"></script>

<style>
label{
    color:black;
}
.image-preview {
    width: 300px;
    height: 200px;
    border: 2px solid #dddddd;
    margin-top: 15px;

    display: flex;
    align-items:center;
    justify-content:center;
    font-weight: bold;
    color: #cccccc;
}

.image-preview__image{
    display: none;
    width: 295px;
    object-fit: contain;
    height: 145px;
}
</style>

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
                    <h1 class="h3 mb-0 text-gray-800">Edit an Item</h1>
                </div>

                <!-- Breadcrumb -->
                <div class="row" >
                    <div class="breadcrumb-wrapper col-xl-9">
                        <ol class="breadcrumb" style = "background-color:rgba(0, 0, 0, 0);">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('items/Items'); ?>"><i class="fas fa-tachometer-alt"></i> Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('items/Items'); ?>"></i>Items</a>
                            </li>
                            <li class="breadcrumb-item active">Edit an Item</li>
                        </ol>
                    </div>
                    <div class = "col-xl-3">
                        <div class = "d-flex justify-content-end">
                            <a type="button" href = "<?= base_url('items/Items'); ?>" class="btn" style="background-color: #FF545D; color: white;">Back<i class="fas fa-undo pl-1"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->
                <div class="row">
                    <div class="col-xl-12">
                        <!-- Card-->
                        <div class="card ">
                            <div class="card-body">
                            <form method="post" action=" <?=base_url('items/Items/submit_edited_item/'.$item_data->item_id)?>" enctype="multipart/form-data">
                            <?= form_open_multipart('') ?>

                                   <!-- Logo Image Row -->
                                   <div class="form-row pt-4">
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <div class = "pb-3" style = "color:black;">Current Item Picture</div>
                                            <img style=" height:200px; width: 300px; object-fit: contain;" src="<?= base_url("assets/img/items/").$item_data->item_pic?>"  alt="Item picture">
                                        </div>
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <div class = "pb-3" style = "color:black;">New Item Picture</div>
                                            <input type="file" name="item_pic" id = "inpFile" accept="image/*">
                                            <div class="image-preview" id = "imagePreview">
                                                <img src="" alt="Image Preview" class="image-preview__image">
                                                <span class = "image-preview__default-text">Image Preview</span>
                                            </div>
                                            <!-- Js for logo image preview -->
                                            <script>
                                                const inpFile = document.getElementById("inpFile");
                                                const previewContainer = document.getElementById("imagePreview");
                                                const previewImage = previewContainer.querySelector(".image-preview__image");
                                                const previewDefaultText = previewContainer.querySelector(".image-preview__default-text");

                                                inpFile.addEventListener("change", function() {
                                                    const file = this.files[0];

                                                    if (file){
                                                        const reader = new FileReader();

                                                        previewDefaultText.style.display =  "none";
                                                        previewImage.style.display =  "block";

                                                        reader.addEventListener("load", function(){
                                                            previewImage.setAttribute("src", this.result);
                                                        });

                                                        reader.readAsDataURL(file);
                                                    } else {
                                                        previewDefaultText.style.display =  "null";
                                                        previewImage.style.display =  "null";
                                                        previewImage.setAttribute("src", "");
                                                    }
                                                });
                                            </script>
                                        </div>
                                   </div>
                                    <!-- /. Logo Image Row -->

                                    <div class="form-row pt-4">
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <label for="item_subcategory_id">Item Sub-Category</label>
                                            <select name="item_subcategory_id" id="item_subcategory_id" class="form-control form-select border-bottom" required>
                                                <option value="<?=$item_data->item_subcategory_id?>" selected><?=$item_data->item_subcategory_id?></option>
                                                <option value="1">Atlantic Laboratories</option>
                                                <option value="2">Biofer Technologies</option>
                                            </select>
                                            <!-- <select name="item_subcategory_id" id="item_subcategory_id" class="form-control form-select form-select-md">
                                                <option value="" selected disabled>Item Sub-Category</option>
                                                <php
                                                    foreach($item_subcategory_data as $item_subcategory) {
                                                        echo '<option value="'.$item_subcategory->uni_id.'">'.$item_subcategory->item_subcategory_name.'</option>';
                                                    }
                                                ?>
                                            </select> -->
                                        </div>
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <label for="item_supplier">Item Supplier</label>
                                            <select name="item_supplier" id="item_supplier" class="form-control form-select border-bottom" required>
                                                <option value="<?=$item_data->item_supplier?>" selected><?=$item_data->item_supplier?></option>
                                                <option value="Atlantic Laboratories">Atlantic Laboratories</option>
                                                <option value="Biofer Technologies">Biofer Technologies</option>
                                                <option value="CCM Pharmaceuticals">CCM Pharmaceuticals</option>
                                                <option value="Duopharma">Duopharma</option>
                                                <option value="FastAid Medical">FastAid Medical</option>
                                                <option value="Hovid">Hovid</option>
                                                <option value="Quantum Medical Solutions">Quantum Medical Solutions</option>
                                                <option value="SM Pharmaceuticals">SM Pharmaceuticals</option>
                                                <option value="Y.S.P. Industries">Y.S.P. Industries</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row pt-4">
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <label for="emp_title">Item Name</label>
                                            <input type="text" class="form-control" id="item_name" name = "item_name" placeholder="Enter item name" value="<?=$item_data->item_name?>" required>
                                        </div>
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <label for="item_expiry_date">Item Expiry Date (if applicable)</label>
											<input type="date" name="item_expiry_date" id="item_expiry_date" class="form-control border-bottom" value="<?=$item_data->item_expiry_date?>">
                                            <div style = "color:red; font-size:0.9em;">*If not applicable, skip</div>
                                        </div>
                                    </div>
                                    <div class="form-row pt-4">
                                        <div class="form-group col-md-12 px-4 pr-5">
                                            <label for="item_description">Item Description</label>
                                            <textarea type="text" class="form-control" rows="4" id="item_description" name = "item_description" placeholder="Enter item description" value="<?=$item_data->item_description?>" required><?=$item_data->item_description?></textarea>
                                            <!-- <div style = "color:red; font-size:0.9em;">*Can enter more than 1 field</div>   -->
                                        </div>
                                    </div>
                                    <div class="form-row pt-4">
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <label for="emp_title">Item Price Per Unit (RM)</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">RM</span>
                                                <input type="number" class="form-control" id="item_price" name = "item_price" step="0.01" placeholder="Enter item price per unit" value="<?=$item_data->item_price?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 px-4 pr-5">
                                            <label for="emp_title">Item Quantity At Hand</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2">Qty</span>
                                                <input type="number" class="form-control" id="item_quantity" name = "item_quantity" placeholder="Enter item quantity at hand" value="<?=$item_data->item_quantity?>" required>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <!-- Submit button -->
                                    <div class ="pr-4">
                                        <button  type="submit" class="btn" style="background-color: #FF545D; color: white; float:right;" >Submit<i class="fas fa-check pl-2"></i></button>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <!-- /. Card -->

                    </div>                
                </div>
                <!-- /. Content Row -->

                </div>
                <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- <script>
                // File appear on select
                $(".custom-file-input").on("change", function() {
                    var fileName = $(this).val().split("\\").pop();
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
                </script> -->
