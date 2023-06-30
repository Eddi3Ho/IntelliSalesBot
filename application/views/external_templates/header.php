<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?= base_url('/assets/img/php_icon.png')?>" type="image/png">

    <title><?=$title?></title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url() ?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <?php  if(isset($bootstrap_css)) {  
        echo $bootstrap_css;
    } else {?>
        <link href="<?php echo base_url() ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <?php } ?>

    <?php
        if (isset($include_css)) {
            echo '<link href="' . base_url() . 'assets/css/' . $include_css . '.css" rel="stylesheet">';
        }
    ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>