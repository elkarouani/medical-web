<?php
    ob_start();
    session_start();
    require_once 'includes/functions/functions.php';
    require_once 'includes/functions/session.php';

 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= getTitle(); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="layout/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="layout/css/select2.min.css">
    <link rel="stylesheet" href="layout/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="layout/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="layout/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="layout/css/ionicons.min.css">
    <link rel="stylesheet" href="layout/css/animate.css">
    <link rel="stylesheet" href="layout/css/lobiBox.css">
    <link rel="stylesheet" href="layout/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/bootstrap-datepicker.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="layout/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="layout/css/_all-skins.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.all.min.js"></script>-->
    <script src="layout/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="layout/css/sweetalert2.min.css">
    <link rel="stylesheet" href="layout/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="layout/css/fullcalendar.min.css">
    <link rel="stylesheet" href="layout/css/pace.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-purple sidebar-mini" style="">
<?php if( $withLoader) require_once 'loader.php'; ?>
<!-- Site wrapper -->
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><i class="fa fa-user-md"></i>&nbsp;<b>M</b>Soft</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><i class="fa fa-user-md"></i> &nbsp;<b>M</b>EDI-<b>S</b>OFT</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
<!--            <a href="javascript:void(0)" class="sidebar-toggle text-warning"> Le 15/01/2018 08:22:30 </a>-->

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= $userConnect['user'] == 'admin' ? 'data/uploades/avatarAdmins/' . $userConnect['image'] : 'data/uploades/avatarGestionnaires/' . $userConnect['image'] ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?= $userConnect['prenom'] . ' ' . $userConnect['nom'] . ' - ' . ucfirst($userConnect['user']) ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?= $userConnect['user'] == 'admin' ? 'data/uploades/avatarAdmins/' . $userConnect['image'] : 'data/uploades/avatarGestionnaires/' . $userConnect['image'] ?>" class="img-circle" alt="User Image">

                                <p>
                                    <?= $userConnect['prenom'] . ' ' . $userConnect['nom'] ?>
                                    <small><?= ucfirst($userConnect['user']) ?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?= $userConnect['user'] == 'admin' ? 'editAdmin.php?idAdmin=' . $userConnect['idAdmin'] :  'editGestionnaire.php?idGestionnaire=' . $userConnect['idGestionaire'] ?>" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="includes/functions/logOut.php" class="btn btn-default btn-flat">Déconnecter</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
