<!doctype html>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="robots" content="noindex, nofollow" />

    <title><?=  $title . ' - MPP' ?? 'MPP' ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/logo.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="<?= base_url('assets/fonts/iconify-icons.css'); ?>" />


    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css -->

    <!-- <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" /> -->
    <link rel="stylesheet" href="<?= base_url('assets/libs/node-waves/node-waves.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/core.css'); ?>?v=1">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css'); ?>?v=4">
    <link rel="stylesheet" href="<?= base_url('assets/css/portal-shell.css'); ?>?v=4">

    <link rel="stylesheet" href="<?= base_url('assets/css/demo.css'); ?>">
    <style>
    /* Critical: override core.css sidebar text truncation */
    .menu-vertical .menu-item .menu-link > div:not(.badge) {
      white-space: normal !important;
      overflow: visible !important;
      text-overflow: clip !important;
      line-height: 1.35 !important;
      min-width: 0 !important;
      flex: 1 1 0% !important;
    }
    /* Ensure toggle arrow doesn't overlap wrapped text */
    .menu-vertical .menu-item .menu-toggle {
      padding-inline-end: calc(1.45rem + 1.3475em) !important;
    }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/apex-charts/apex-charts.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css'); ?>">
    <script src="<?= base_url('assets/js/helpers.js'); ?>"></script>
    <script src="<?= base_url('assets/js/config.js'); ?>"></script>

</head>

<body class="portal-admin-app">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
