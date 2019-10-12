<?php
    if( ! isset($_GET['idAdmin']) || $_GET['idAdmin'] == '' )
    {
        header('location:admins.php');
    }

    $title = 'Information';
    $acceUser = ['admin'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idAdmin = $_GET['idAdmin'];
    $sql     = 'SELECT * FROM admin WHERE idAdmin = ?';
    $admin   = getData($sql, [$idAdmin]);

    if(empty($admin['idAdmin']) )
    {
        header('location:admins.php');
    }

    if(  ($userConnect['user'] == 'admin') && ($_GET['idAdmin'] != $userConnect['idAdmin']) )
    {
        header('location:acceuilAdmin.php');
    }


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Informations du profile
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="admins.php" class="btn btn-primary"> <i class="fa fa-users"></i> &nbsp;Liste des administrateurs</a>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-info"></i> &nbsp; Informations géneral</h3>
                    </div>
                    <div class="box-body">
                        <br>
                        <form class="form-horizontal" action="includes/functions/controller.php?action=UpdateInfoAdmin" id="frmUpdAdmin">
                            <input name="idAdmin" id="idAdmin" value="<?= $admin['idAdmin'] ?>" type="hidden">
                            <div class="form-group">
                                <label for="nomAdmin" class="col-sm-3 text-left control-label">Nom</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="nomAdmin" id="nomAdmin" value="<?= $admin['nom'] ?>" placeholder="Nom" autocomplete="off" type="text">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="prenomAdmin" class="col-sm-3 control-label">Prenom</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="prenomAdmin" id="prenomAdmin" value="<?= $admin['prenom'] ?>" placeholder="Prenom" autocomplete="off" type="text">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="emailAdmin" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="emailAdmin" id="emailAdmin" value="<?= $admin['email'] ?>" placeholder="Email" autocomplete="off" type="email">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="telephoneAdmin" class="col-sm-3 control-label">Telephone</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="telephoneAdmin" id="telephoneAdmin" value="<?= $admin['tel'] ?>" placeholder="Telephone" autocomplete="off" type="tel">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sexe</label>
                                <div class="col-sm-offset-3 col-sm-9">
                                    <div class="radio" style="margin-top: -27px;">
                                        <label>
                                            <input type="radio" value="H" name="sexeAdmin" <?php if( $admin['sexe'] == 'H' ) echo 'checked' ?>> Homme
                                        </label>

                                        <label style="margin-left: 30px">
                                            <input type="radio" value="F" name="sexeAdmin" <?php if( $admin['sexe'] != 'H' ) echo 'checked' ?>> Femme
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" id="btnUpdateInfoAdmin" class="btn btn-success btn-flat"> <i class="fa fa-edit"></i> Enregestrer les modifications </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-user"></i> &nbsp; Login </h3>
                    </div>
                    <div class="box-body">
                        <br>
                        <form class="form-horizontal"  action="includes/functions/controller.php?action=UpdateLoginAdmin" id="frmUpdateLoginadmin">
                            <input name="idAdmin" value="<?= $admin['idAdmin'] ?>" type="hidden">
                            <div class="form-group">
                                <label for="loginAdmin" class="col-sm-3 control-label">Login</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="loginAdmin" id="loginAdmin" value="<?= $admin['login'] ?>" placeholder="Login" autocomplete="off" type="text">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>

                            <div class="col-sm-offset-3">
                                <div class="checkbox" style="margin-left: 7px;">
                                    <label>
                                        <input name="active" <?php if( $admin['active'] == 1 ) echo 'checked' ?> type="checkbox"> Active
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" id="btnUpdateLoginAdmin" class="btn btn-success btn-flat"> <i class="fa fa-edit"></i> Modifier Le Login </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-lock"></i> &nbsp; Mot de passe </h3>
                    </div>
                    <div class="box-body">
                        <br>
                        <form class="form-horizontal" action="includes/functions/controller.php?action=changePassAdmin" id="FormChangePass" >
                            <input name="idAdmin" id="idAdmin" value="<?= $admin['idAdmin'] ?>" type="hidden">
                            <div class="form-group">
                                <label for="ancienPass" class="col-sm-3 control-label">Ancien mot de pass</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="ancienPass" id="ancienPass" placeholder="Ancien Mot De Pass" type="password">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="nouveauPass" class="col-sm-3 control-label">Nouveau mot de pass</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="nouveauPass" id="nouveauPass" placeholder="Nouveau Mot De Pass" type="password">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="confirmationPass" class="col-sm-3 control-label">Confirmation Mot De Pass</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="confirmationPass" id="confirmationPass" placeholder="Confirmation Mot De Pass" type="password">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="btnChangePass" onclick="ChangePassAdmin()" type="button" class="btn btn-danger btn-flat"> <i class="fa fa-refresh"></i>  Changer le mot de pass </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-md-3">

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="fa fa-picture-o"></i> &nbsp;Image de profile</h3>
                            </div>
                            <form action="includes/functions/controller.php?action=UpdateImage" id="FormUpdateImage" enctype="multipart/form-data">
                                <div class="box-body box-profile">
                                    <br>
                                    <input name="idAdmin" value="<?= $admin['idAdmin'] ?>" type="hidden">
                                    <img id="imageUpdateAdmin" class="profile-user-img img-responsive img-circle" style="width: 150px; height: 150px;" src="data/uploades/avatarAdmins/<?= $admin['image'] ?>" alt="User profile picture">
                                    <div class="text-center">
                                        <button id="btnChoisieImage" type="button" style="margin-top: 10px" class="btn btn-warning btn-xs btn-flat"> <i class="fa fa-upload"></i> &nbsp; Choisie une image</button>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button id="btnUpdateImageAdmin" type="button" onclick="UpdateImageAdmin()" class="btn btn-success btn-flat btn-block">Modifier l'image</button>
                                    <input type="file" name="image" onchange="document.getElementById('imageUpdateAdmin').src = window.URL.createObjectURL(this.files[0])" id="inputFileImage" style='display:none' >
                                </div>
                            </form>
                        </div>
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
<script src="layout/js/updateAdmin.js"></script>
<script src="layout/js/admins.js"></script>
