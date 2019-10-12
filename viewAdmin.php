<?php
    if( ! isset($_GET['idAdmin']) || $_GET['idAdmin'] == '' )
    {
        header('location:admins.php');
    }

    $withLoader = false;
    $title = 'Information';
    $acceUser = ['admin'];
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idAdmin = $_GET['idAdmin'];
    $sql     = 'SELECT * FROM admin WHERE idAdmin = ?';
    $admin   = getData($sql, [$idAdmin]);

    if(empty($admin['idAdmin']) )
    {
        header("location:admins.php");
    }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="alert alert-success alert-popup">
        <button type="button" id="btn-hide-alert" class="close" >&times;</button>
        <h4><i class="icon fa fa-check-circle"></i>Good !</h4>
        <p>Les informations ont été sauvegardées</p>
    </div>

    <div class="alert alert-success alert-popup">
        <button type="button" id="btn-hide-alert" class="close" >&times;</button>
        <h4><i class="icon fa fa-check-circle"></i>Good !</h4>
        <p>Les informations ont été sauvegardées</p>
    </div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Blank page
            <small>it all starts here</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Blank page</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">
                            Informations du profile
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <br>
                                <img style="width: 150px; height: 150px;" class="profile-user-img img-responsive img-circle" src="data/uploades/avatarAdmins/<?= $admin['image'] ?>" alt="User profile picture">
                                <h3 class="profile-username text-center">
                                    <?= $admin['prenom'] . '  ' . $admin['nom'] ?>
                                </h3>

                                <p class="text-muted text-center"> Administrateur </p>
                            </div>
                            <div class="col-md-9">
                                <table class="table table-bordered" id="table-view">
                                    <tr>
                                        <td>
                                            <b>Nom</b>
                                        </td>
                                        <td><?= $admin['nom'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Prenom</b>
                                        </td>
                                        <td><?= $admin['prenom'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Sexe</b>
                                        </td>
                                        <td><span class="badge bg-yellow"><?php if($admin['sexe'] == 'H') echo 'Homme'; else echo 'Femme'; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Numéro de téléphone</b>
                                        </td>
                                        <td><?= $admin['tel'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Email</b>
                                        </td>
                                        <td><?= $admin['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Login</b>
                                        </td>
                                        <td><?= $admin['login'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Etat de compte</b>
                                        </td>
                                        <td>
                                            <?php if($admin['active'] == 1) echo '<span class="badge bg-green">Active</span>'; else echo '<span class="badge bg-red">Désactive</span>'; ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">
                            Actions
                        </h3>
                    </div>
                    <div class="box-body">
                        <a href="editAdmin.php?idAdmin=<?= $admin['idAdmin'] ?>" class="btn btn-success btn-flat btn-block"> <i class="fa fa-pencil"></i> &nbsp; Modifier ces informations</a>
                        <a href="admins.php" class="btn btn-primary btn-flat btn-block"> <i class="fa fa-users"></i> &nbsp; Vers la liste des administrateurs</a>
                        <hr>
                        <button onclick="DeleteAdmin(<?= $admin['idAdmin'] ?>, true)" id="btnDeleteAdmin" class="btn btn-danger btn-flat btn-block"> <i class="fa fa-trash-o"></i> &nbsp; Supprimer</button>
                     </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script src="layout/js/admins.js"></script>
