<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $page['title']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url('public/common/libraries/bootstrap_v3/css/bootstrap.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('public/common/libraries/Font-Awesome/web-fonts-with-css/css/fontawesome-all.css') ?>">

    <!-- nprogress -->
    <link rel="stylesheet" href="<?php echo base_url('public/common/plugins/nprogress/css/nprogress.css'); ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('public/common/plugins/iCheck/all.css'); ?>">
    <!-- datatable -->
    <link rel="stylesheet" href="<?php echo base_url('public/common/libraries/DataTables/css/dataTables.bootstrap.css'); ?>">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="<?php echo base_url('public/common/plugins/flatpickr/flatpickr.min.css'); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('public/admin/css/AdminLTE.css'); ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url('public/admin/css/skins/_all-skins.css'); ?>">
    <!-- Admin -->
    <link rel="stylesheet" href="<?php echo base_url('public/admin/css/admin.css'); ?>">

    <!-- jQuery 3.2.1 -->
    <script src="<?php echo base_url('public/common/libraries/jQuery/js/jquery-3.2.1.min.js'); ?>"></script>

    <!-- language js -->
    <script>
        var lang = {
            'add': '<?php echo $this->lang->line('add'); ?>',
            'edit': '<?php echo $this->lang->line('edit'); ?>'
        };
    </script>

</head>