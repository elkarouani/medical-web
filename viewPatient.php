
<?php
    if( ! isset($_GET['idPatient']) || $_GET['idPatient'] == '' )
    {
        header('location:patients.php');
    }
    $withLoader = false;
    $title = 'Information';
    $acceUser = ['gestionnaire', 'admin'];
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idPatient = $_GET['idPatient'];
    $sql     = 'SELECT * FROM patient WHERE idPatient = ?';
    $patient = getData($sql, [$idPatient]);

    if(empty($patient['idPatient']) )
    {
        header('location:patients.php');
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
            <li class="active">Blank page </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-info"></i> &nbsp; Informations du patient</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered" id="table-view">
                            <tr>
                                <td>
                                    <b>Numéro carte nationnal</b>
                                </td>
                                <td><?= $patient['cin'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Nom</b>
                                </td>
                                <td><?= $patient['nom'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Prenom</b>
                                </td>
                                <td><?= $patient['prenom'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Sexe</b>
                                </td>
                                <td><span class="badge bg-yellow"><?php if($patient['sexe'] == 'H') echo 'Homme'; else echo 'Femme'; ?></span></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Age</b>
                                </td>
                                <td>
                                    <?php
                                    $bithdayDate = $patient['dateNaissance'];
                                    $date        = new DateTime($bithdayDate);
                                    $now         = new DateTime();
                                    $age         = $now->diff($date);
                                    ?>
                                    <span class="badge"><?= $age->y ?> Ans</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Numéro de téléphone</b>
                                </td>
                                <td><?= $patient['tel'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Email</b>
                                </td>
                                <td><?= $patient['email'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Adresse</b>
                                </td>
                                <td><?= $patient['adresse'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Etat de compte</b>
                                </td>
                                <td>
                                    <?php if($patient['active'] == 1) echo '<span class="badge bg-green">Active</span>'; else echo '<span class="badge bg-red">Désactive</span>'; ?></span>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-cog"></i> &nbsp; Actions
                        </h3>
                    </div>
                    <div class="box-body">
                        <a href="editPatient.php?idPatient=<?= $patient['idPatient'] ?>" class="btn btn-success btn-flat btn-block"> <i class="fa fa-pencil"></i> &nbsp; Modifier ces informations</a>
                        <a href="patients.php" class="btn btn-primary btn-flat btn-block"> <i class="fa fa-users"></i> &nbsp; Vers la Liste Des Patients</a>
                        <hr>
                        <button onclick="DeletePatient(<?= $patient['idPatient'] ?>, true)" class="btn btn-danger btn-flat btn-block"> <i class="fa fa-trash-o"></i> &nbsp; Supprimer</button>
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
<script src="layout/js/patients.js"></script>
