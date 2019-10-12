<?php
    if( ! isset($_GET['idGestionnaire']) || $_GET['idGestionnaire'] == '' )
    {
        header('location:gestionnaires.php');
    }

    $withLoader = false;
    $title = 'Information';
    $acceUser = ['admin', 'gestionnaire'];
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idGestionnaire = $_GET['idGestionnaire'];
    $sql            = 'SELECT * FROM gestionaire WHERE idGestionaire = ?';
    $gestionnaire   = getData($sql, [$idGestionnaire]);

    if(empty($gestionnaire['idGestionaire']) )
    {
        header("location:gestionnaires.php");
    }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
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
                                <img style="width: 150px; height: 150px;" class="profile-user-img img-responsive img-circle" src="data/uploades/avatarGestionnaires/<?= $gestionnaire['image'] ?>" alt="User profile picture">
                                <h3 class="profile-username text-center">
                                    <?= $gestionnaire['prenom'] . '  ' . $gestionnaire['nom'] ?>
                                </h3>

                                <p class="text-muted text-center"> Gestionnaire </p>
                            </div>
                            <div class="col-md-9">
                                <table class="table table-bordered" id="table-view">
                                    <tr>
                                        <td>
                                            <b>Nom</b>
                                        </td>
                                        <td><?= $gestionnaire['nom'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Prenom</b>
                                        </td>
                                        <td><?= $gestionnaire['prenom'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Sexe</b>
                                        </td>
                                        <td><span class="badge bg-yellow"><?php if($gestionnaire['sexe'] == 'H') echo 'Homme'; else echo 'Femme'; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Numéro de téléphone</b>
                                        </td>
                                        <td><?= $gestionnaire['tel'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Email</b>
                                        </td>
                                        <td><?= $gestionnaire['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Adresse</b>
                                        </td>
                                        <td><?= $gestionnaire['adresse'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Login</b>
                                        </td>
                                        <td><?= $gestionnaire['login'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Etat de compte</b>
                                        </td>
                                        <td>
                                            <?php if($gestionnaire['active'] == 1) echo '<span class="badge bg-green">Active</span>'; else echo '<span class="badge bg-red">Désactive</span>'; ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                        </div>
                        <br>
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
                        <a href="editGestionnaire.php?idGestionnaire=<?= $gestionnaire['idGestionaire'] ?>" class="btn btn-success btn-flat btn-block"> <i class="fa fa-pencil"></i> &nbsp; Modifier ces informations</a>
                        <a href="gestionnaires.php" class="btn btn-primary btn-flat btn-block"> <i class="fa fa-users"></i> &nbsp; Vers la liste des gestionnaire</a>
                        <hr>
                        <button onclick="DeleteGestionnaire(<?= $gestionnaire['idGestionaire'] ?>, true)" class="btn btn-danger btn-flat btn-block"> <i class="fa fa-trash-o"></i> &nbsp; Supprimer</button>
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
<script src="layout/js/gestionaires.js"></script>
