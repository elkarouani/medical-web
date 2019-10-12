
<?php
    if( ! isset($_GET['idGestionnaire']) || $_GET['idGestionnaire'] == '' )
    {
        header('location:Gestionnaires.php');
    }

    $title = 'Edit Gestionnaire';
    $withLoader = false;
    $acceUser = ['admin', 'gestionnaire'];
    include_once "includes/templates/header.inc";
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

    $idGestionnaire = $_GET['idGestionnaire'];
    $sql            = 'SELECT * FROM gestionaire WHERE idGestionaire = ?';
    $gestionnaire   = getData($sql, [$idGestionnaire]);

    if(empty($gestionnaire['idGestionaire']) )
    {
        header('location:gestionnaires.php');
    }

    if(  ($userConnect['user'] == 'gestionnaire') && ($_GET['idGestionnaire'] != $userConnect['idGestionaire']) )
    {
        header('location:acceuilGestionnaire.php');
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
                <?php if( $userConnect['user'] == 'admin' ): ?>
                    <a href="gestionnaires.php" class="btn btn-primary btn-flat btn-block"> <i class="fa fa-users"></i> &nbsp;Liste des gestionnaire</a>
                <?php endif; ?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-info"></i> &nbsp; Information Géneral </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Reduire">
                                <i class="fa fa-minus"></i>
                            </button>
                      </div>
                    </div>
                    <div class="box-body">
                        <br>
                        <form class="form-horizontal" action="includes/functions/controller.php?action=updateInfoGestionnaire" id="FormUpdateGestionnaire">
                            <input name="idGestionnaire" id="idGestionnaire" value="<?= $gestionnaire['idGestionaire'] ?>" type="hidden">
                            <div class="form-group">
                                <label for="nomGestionnaire" class="col-sm-3 text-left control-label">Nom <span class="required"> * </span></label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="nomGestionnaire" id="nomGestionnaire" value="<?= $gestionnaire['nom'] ?>" placeholder="Nom" autocomplete="off" type="text">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="prenomGestionnaire" class="col-sm-3 control-label">Prenom <span class="required"> * </span></label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="prenomGestionnaire" id="prenomGestionnaire" value="<?= $gestionnaire['prenom'] ?>" placeholder="Prenom" autocomplete="off" type="text">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="emailAdmin" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="emailGestionnaire" id="emailGestionnaire" value="<?= $gestionnaire['email'] ?>" placeholder="Email" autocomplete="off" type="email">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="telephoneGestionnaire" class="col-sm-3 control-label">Telephone <span class="required"> * </span></label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="telephoneGestionnaire" id="telephoneGestionnaire" value="<?= $gestionnaire['tel'] ?>" placeholder="Telephone" autocomplete="off" type="tel">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sexe</label>
                                <div class="col-sm-offset-3 col-sm-9">
                                    <div class="radio" style="margin-top: -27px;">
                                        <label>
                                            <input type="radio" value="H" name="sexeGestionnaire" <?php if( $gestionnaire['sexe'] == 'H' ) echo 'checked' ?>> Homme
                                        </label>

                                        <label style="margin-left: 30px">
                                            <input type="radio" value="F" name="sexeGestionnaire" <?php if( $gestionnaire['sexe'] != 'H' ) echo 'checked' ?>> Femme
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="adresseGestionnaire" class="col-sm-3 control-label">Adresse <span class="required"> * </span></label>

                                <div class="col-sm-9">
                                    <textarea class="form-control" name="adresseGestionnaire" id="adresseGestionnaire" placeholder="Adresse" cols="30" rows="4" ><?= $gestionnaire['adresse'] ?></textarea>
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" id="btnUpdateInfoGestionnaire" class="btn btn-success btn-flat"> <i class="fa fa-edit"></i> Enregestrer les modifications </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-user-circle-o"></i> &nbsp;Login </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Reduire">
                                <i class="fa fa-minus"></i>
                            </button>
                      </div>
                    </div>

                    <div class="box-body">
                        <br>
                        <form class="form-horizontal"  action="includes/functions/controller.php?action=updateLoginGestionnaire" id="FormUpdateLoginGestionnaire">
                            <input name="idGestionnaire" value="<?= $gestionnaire['idGestionaire'] ?>" type="hidden">
                            <div class="form-group">
                                <label for="loginGestionnaire" class="col-sm-3 control-label">Login <span class="required"> * </span></label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="loginGestionnaire" id="loginGestionnaire" value="<?= $gestionnaire['login'] ?>" placeholder="Login" autocomplete="off" type="text">
                                </div>
                                <span class="help-block col-sm-3 col-lg-offset-3"></span>
                            </div>

                            <div class="col-sm-offset-3">
                                <div class="checkbox" style="margin-left: 7px;">
                                    <label>
                                        <input name="active" <?php if( $gestionnaire['active'] == 1 ) echo 'checked' ?> type="checkbox"> Active
                                    </label>
                                </div>
                            </div>

                            <br>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-3">
                                    <button type="button" id="btnUpdateLoginGestionnaire" class="btn btn-success btn-flat"> <i class="fa fa-edit"></i> Modifier </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-lock"></i> &nbsp;Mot de passe </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Reduire">
                                <i class="fa fa-minus"></i>
                            </button>
                      </div>
                    </div>
                    <div class="box-body">
                        <br>
                        <form class="form-horizontal" action="includes/functions/controller.php?action=changePassGestionnaire" id="FormChangePass" >
                            <input name="idGestionnaire" id="idGestionnaire" value="<?= $gestionnaire['idGestionaire'] ?>" type="hidden">
                            <div class="form-group">
                                <label for="ancienPass" class="col-sm-3 control-label">Mot de passe actuel <span class="required"> * </span></label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="ancienPass" id="ancienPass" placeholder="Ancien Mot De Pass" type="password">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="nouveauPass" class="col-sm-3 control-label">Nouveau mot de pass  <span class="required"> * </span> </label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="nouveauPass" id="nouveauPass" placeholder="Nouveau Mot De Pass" type="password">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>
                            <div class="form-group">
                                <label for="confirmationPass" class="col-sm-3 control-label">Confirmation Mot De Pass  <span class="required"> * </span> </label>

                                <div class="col-sm-9">
                                    <input class="form-control" name="confirmationPass" id="confirmationPass" placeholder="Confirmation Mot De Pass" type="password">
                                </div>
                                <span class="help-block col-sm-9 col-lg-offset-3"></span>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="btnChangePass" type="button" class="btn btn-danger btn-flat"> <i class="fa fa-refresh"></i>  Changer le mot de pass </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-picture-o"></i> &nbsp;Image de profile </h3>
                    </div>
                    <form action="includes/functions/controller.php?action=UpdateImageGestionnaire" id="FormUpdateImageGestionnaire" enctype="multipart/form-data">
                        <div class="box-body box-profile">
                            <br>
                            <input name="idGestionnaire" value="<?= $gestionnaire['idGestionaire'] ?>" type="hidden">
                            <img id="imageUpdateGestionnaire" class="profile-user-img img-responsive img-circle" style="width: 150px; height: 150px;" src="data/uploades/avatarGestionnaires/<?= $gestionnaire['image'] ?>" alt="User profile picture">
                            <div class="text-center">
                                <button id="btnChoisieImage" type="button" style="margin-top: 10px" class="btn btn-warning btn-xs btn-flat"> <i class="fa fa-upload"></i> &nbsp; Choisie une image</button>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button id="btnUpdateImageGestionnaire" type="button" class="btn btn-success btn-flat btn-block">Modifier l'image</button>
                            <input type="file" name="image" onchange="document.getElementById('imageUpdateGestionnaire').src = window.URL.createObjectURL(this.files[0])" id="inputFileImage" style='display:none' >
                        </div>
                    </form>
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
