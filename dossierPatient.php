<?php
    if( ! isset($_GET['idDossier']) || empty($_GET['idDossier']) )
    {
        header('location:dossiers.php');
    }

    $title = 'Détails du dossier';
    $acceUser = ['admin'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idDossier            = $_GET['idDossier'];
    $sql                  = 'SELECT d.*, p.* FROM dossier D, patient P WHERE idDossier = ? AND D.idPatient = P.idPatient';
    $dossier              = getData($sql, [ $idDossier ]);
    $mutuels              = getDatas("SELECT * FROM mutuel", []);
    $consultations        = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE idDossier = ?", [ $idDossier ]);
    $params               = getData("SELECT CONCAT(HOUR(dureeRdv), ' h ',MINUTE(dureeRdv), ' minutes') AS dureeRdv, (TIME_TO_SEC(dureeRdv) / 60) AS nbrMinutes FROM parametrage WHERE etat = 1", []);
    $rdvs                 = getData("SELECT COUNT(*) AS nbrRdvs FROM rdv WHERE idDossier = ?", [ $idDossier ]);
    $totalMontantPaye     = getData("SELECT SUM(montantPaye) AS totalPaye FROM paiement WHERE idDossier = ?", [ $idDossier ])['totalPaye'];
    $rest                 = $dossier['montantDossier'] - $totalMontantPaye;
    $typeRdv              = getDatas("SELECT * FROM typerdv", []);
    $medicaments          = getDatas("SELECT CONCAT(m.designation, ' ', m.dosage, ' ', f.libelle ) AS med FROM medicament m, famillemedicament f WHERE m.idFamilleMedicament = f.idfamille ORDER BY m.designation ASC", []);
    $familleMedicament    = getDatas("SELECT * FROM famillemedicament", []);
    $nbrConsultations     = $consultations['nbrConsultations'];
    $nbrRdv               = $rdvs['nbrRdvs'];
    $groupesSanguin       = [ 'A', 'B', 'O', 'AB', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-' ];

    if( $rest < 0 )
    {
        $rest = 0;
    }

    if( !($dossier) && (empty($dossier['idDossier'])) )
    {
        header('location:dossiers.php');
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Dossier patient : <span class="text-bold labelNomPatient"><?= $dossier['prenom'] . '  ' . $dossier['nom'] ?></span>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <button style="margin-left:5px" data-toggle="modal" data-target="#modalAddRdv" class="btn bg-orange pull-right" > <i class="fa fa-calendar-plus-o"></i>&nbsp; Nouv.Rdv</button>
                <?php if( $rest > 0 ): ?>
                    <button type="button" style="margin-left:5px" data-toggle="modal" data-target="#modalAddPaiement" <?php if( $rest <= 0 ) echo 'disabled'; ?> class="btn bg-purple pull-right"><i class="fa fa-money"></i>&nbsp; Nouv.Paiement </button>
                <?php endif ?>
                <button type="button" id="btnNewConsultation" data-toggle="tab" class="btn btn-danger pull-right"><i class="fa fa-stethoscope"></i>&nbsp; Nouv.Consultation </button>
            </li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <input id="idDossier" value="<?= $dossier['idDossier'] ?>" type="hidden">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-solid box-primary" style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-folder-open-o"></i>&nbsp; Informations du dossier</h3>
                    </div>
                    <div class="box-body">
                        <span>Numéro de dossier</span> <span class="badge bg-blue pull-right"><?= $dossier['titreDossier'] . ' / ' . $dossier['idDossier'] ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Date de création </span> &nbsp; <span class="badge bg-blue pull-right"><?= $dossier['dateCreation'] ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Nombre de rendez vous</span> &nbsp; <span class="badge bg-blue pull-right" id="nbrRdv"><?= $nbrRdv ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Nombre du consulations</span> &nbsp; <span id="nbrCosultations" class="badge bg-blue pull-right"><?= $nbrConsultations ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Montant de dossier </span> &nbsp; <span class="badge bg-blue pull-right"><?= $dossier['montantDossier'] . ' DH' ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Montant à payé </span> &nbsp; <span class="badge bg-blue pull-right"><?= $totalMontantPaye . ' DH' ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Le rest </span> &nbsp; <span class="badge bg-blue pull-right"><?= $rest . ' DH' ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">
                        <span>Etat de paimenent</span> <?php if( $rest > 0 ) echo '<span class="badge bg-red pull-right"> <a href="#tab_paiements" id="btnNavPaiements" data-toggle="tab" style="color:#fff" >Non reglé &nbsp; <i class="ion ion-ios-arrow-thin-right"></i> </a> </span>'; else echo '<span class="badge bg-green pull-right"> Reglé </span>'; ?>
                    </div>
                </div>
                <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary ">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-tags"></i>&nbsp; Historiques du dossier</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active"><a href="#tab_informations" data-toggle="tab"><i class="fa fa-info"></i> Informations du patient <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                            <li><a href="#tab_antecedents" data-toggle="tab"><i class="ion ion-medkit"></i> Les antécédents du patient <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                            <li><a href="#tab_consultations" data-toggle="tab"><i class="fa fa-stethoscope"></i> Consulations <span class="pull-right"> <i class="fa fa-angle-right"></i></span> </a></li>
                            <li><a href="#tab_rdvs" id="btnNavRdv" data-toggle="tab"><i class="fa fa-calendar"></i> Rendez vous <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
    <!--                        <li><a href="#tab_fichiers" data-toggle="tab"><i class="fa fa-file-text-o"></i> Fichiers et rapports <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>-->
                            <li><a href="#tab_paiements" id="btnNavPaiements" data-toggle="tab"><i class="fa fa-money"></i> Paiemenets <span class="pull-right"> <i class="fa fa-angle-right"></i></span> </a></li>
                            <li><a href="#tab_dossiers"  data-toggle="tab"><i class="fa fa-folder-open-o"></i> Autres dossiers <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                            </ul>
                    </div>
                <!-- /.box-body -->
                </div>
                <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <i class="fa fa-cog"></i>&nbsp; Actions</h3>
                    </div>
                    <div class="box-body">
                        <a href="#tab_newConsultation" id="btnReferAddConsultation" data-toggle="tab" class="btn bg-blue btn-flat btn-block"><i class="fa fa-stethoscope"></i>&nbsp; Ajouter nouveau consultation </a>
                        <button data-toggle="modal" data-target="#modalAddRdv" class="btn btn-primary btn-flat btn-block" > <i class="fa fa-calendar-plus-o"></i> &nbsp; Ajouter nouveau rendez vous</button>
                        <?php if( $rest > 0 ): ?>
                            <button  data-toggle="modal" data-target="#modalAddPaiement" <?php if( $rest <= 0 ) echo 'disabled'; ?> class="btn bg-purple btn-flat btn-block"><i class="fa fa-money"></i>&nbsp; Ajouter nouveau paiement </button>
                        <?php endif ?>
                    </div>
                <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-9">

            <div class="nav-tabs-custom">
                <div class="tab-content" style="padding:0px !important">
                    <div class="tab-pane active" id="tab_informations">
                        <div style="border-radius: 0px !important;" class="box box-solid box-primary">
                            <div class="box-header" style="border-bottom:0px !important">
                                <input type="hidden" id="idPatientForAddDossier" value="<?= $dossier['idPatient'] ?>">
                                <h3 class="box-title"> <i class="fa fa-info-circle"></i>&nbsp; Informations du patient : <span class="text-bold labelNomPatient"><?= $dossier['prenom'] . '  ' . $dossier['nom'] ?></span> </h3>
                                <div class="box-tools">
                                    <label  data-toggle="tooltip" data-placement="top" title="Editer les informations du patient" class="checkbox-inline" style="top: 2px;">
                                        <input type="checkbox" id="btnEditer" style="bottom: 2px;" >Editer
                                    </label>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="note note-primary" style="margin-top:5px; margin-bottom:10px">
                                    <h4>Informations personnelles</h4>
                                </div>
                                <form action="includes/functions/controller.php?action=UpdateInfoPrsonnelPatient" id="FormUpdateInfoPersonnelPatient">
                                    <input type="hidden" name="idPatient" value="<?= $dossier['idPatient'] ?>">
                                    <div id="content-info-personnelles" style="padding:5px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cinPatient" class="control-label">
                                                        Numéro du carte nationnal <span class="required">*</span>
                                                    </label>
                                                    <input type="text"  class="form-control" name="cinPatient" id="cinPatient" disabled="true" value="<?= $dossier['cin'] ?>" placeholder="Numero de carte nationnal..." disabled >
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nomPatient" class="control-label">
                                                        Nom <span class="required">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="nomPatient" id="nomPatient" value="<?= $dossier['nom'] ?>" placeholder="Nom..." disabled>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="prenomPatient" class="control-label">
                                                        Prénom <span class="required">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="prenomPatient" id="prenomPatient" value="<?= $dossier['prenom'] ?>" placeholder="Prénom..." disabled>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dateNaissancePatient" class="control-label">
                                                        Date de naissance <span class="required">*</span>
                                                    </label>
                                                    <input type="text" class="form-control datepicker" required data-date-format="yyyy-mm-dd" id="dateNaissancePatient" name="dateNaissancePatient" value="<?= $dossier['dateNaissance'] ?>" placeholder="Date de naissance..." disabled="true" >
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="telephonePatient" class="control-label">
                                                        Telephone <span class="required">*</span>
                                                    </label>
                                                    <input type="tel" class="form-control" name="telephonePatient" id="telephonePatient" value="<?= $dossier['tel'] ?>" placeholder="Téléphone..." disabled>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="emailPatient" class="control-label">
                                                        Email
                                                    </label>
                                                    <input type="email" class="form-control" name="emailPatient" id="emailPatient" value="<?= $dossier['email'] ?>" placeholder="Email..." disabled>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Sexe</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input id="sexeFemme" type="radio" disabled value="H" name="sexePatient"<?php if( $dossier['sexe'] == 'H' ) echo 'checked' ?>> Homme
                                                        </label>
                                                        <label style="margin-left: 30px">
                                                            <input id="sexeHomme" type="radio" disabled value="F" name="sexePatient"<?php if( $dossier['sexe'] == 'F' ) echo 'checked' ?>> Femme
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5" >
                                                <label for="mutuel" class="control-label">
                                                    Mutuelle <span class="required">*</span>
                                                </label>
                                                <select name="mutuel" id="listeMutuel" class="form-control select2" disabled>
                                                    <?php foreach ($mutuels as $mutuel): ?>
                                                        <option value="<?= $mutuel['idMutuel'] ?>" <?php if ($mutuel['idMutuel'] == $dossier['mutuel']) echo 'selected' ?> > <?= $mutuel['libelle'] ?> </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1" style="padding-left: 0px;">
                                                <div class="form-group">
                                                    <label for=""></label>
                                                    <button type="button" id="btnAddMutuel" data-toggle="modal" data-target="#modalAddMutuelle" disabled class="btn btn-block btn-flat btn-primary" style="margin-top: 5px;" ><i class="ion ion-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="adressePatient" class="control-label">
                                                        Adresse <span class="required">*</span>
                                                    </label>
                                                    <textarea style="overflow-x: hidden;" name="adressePatient" class="form-control" id="adressePatient" placeholder="Adresse..." disabled cols="20" rows="4"><?= $dossier['adresse'] ?></textarea>
                                                    <span class="help-block"></span>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-success btn-flat" id="btnModifierInfoPersonnelles" disabled > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                            </div>
                            <div class="overlay" id="loaderUpdateInfoPersonnelPatient" style="display:none">
                                <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_antecedents">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header" >
                                <h3 class="box-title"> <i class="ion ion-ios-medkit-outline"></i>&nbsp;  Les antécédents </h3>
                            </div>
                            <div class="box-body">
                                <form action="includes/functions/controller.php?action=updateInfoMedicalPatient" id="FormUpdateInfoMedicalPatient" >
                                    <input type="hidden" name="idPatient" value="<?= $dossier['idPatient'] ?>">
                                    <div class="box-group" id="accordion">
                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" style="color:#000" data-parent="#accordion" href="#familiaux">
                                                        Familiaux
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="familiaux" class="panel-collapse collapse in">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="rhiniteAllergique" class="control-label">Rhinite allergique</label>
                                                        <textarea style="overflow-x: hidden;" name="rhiniteAllergique" placeholder="Remarques..." class="form-control" id="rhiniteAllergique" cols="30" rows="4"><?= $dossier['rhiniteAllergique'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="cardiopathie" class="control-label">Cardiopathie</label>
                                                        <textarea style="overflow-x: hidden;" name="cardiopathie" placeholder="Remarques..." class="form-control" id="cardiopathie" cols="30" rows="4"><?= $dossier['cardiopathie'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="autresFamiliaux" class="control-label">Autres </label>
                                                        <textarea style="overflow-x: hidden;" name="autresFamiliaux" placeholder="Remarques..." class="form-control" id="autresFamiliaux" cols="30" rows="4"><?= $dossier['autresFamiliaux'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion" href="#medicaux">
                                                        Médicaux
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="medicaux" class="panel-collapse collapse">
                                                <div class="box-body">

                                                    <div class="form-group">
                                                        <label for="hta" class="control-label">HTA</label>
                                                        <textarea style="overflow-x: hidden;" name="hta" placeholder="Remarques..." class="form-control" id="hta" cols="30" rows="4"><?= $dossier['hta'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="allergies" class="control-label">Allergies</label>
                                                        <textarea style="overflow-x: hidden;" name="allergies" placeholder="Remarques..." class="form-control" id="allergies" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="autresMedicaux" class="control-label">Autres </label>
                                                        <textarea style="overflow-x: hidden;" name="autresMedicaux" placeholder="Remarques..." class="form-control" id="autresMedicaux" cols="30" rows="4"><?= $dossier['autresMedicaux'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion" href="#habitudeAlcooloTabagique">
                                                        Habitude alcoolo tabagique
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="habitudeAlcooloTabagique" class="panel-collapse collapse">
                                                <div class="box-body">

                                                    <div class="form-group">
                                                        <label for="tabac" class="control-label">Tabac</label>
                                                        <textarea style="overflow-x: hidden;" name="tabac" placeholder="Remarques..." class="form-control" id="tabac" cols="30" rows="4"><?= $dossier['tabac'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="autresTabac" class="control-label">Autres</label>
                                                        <textarea style="overflow-x: hidden;" name="autresTabac" placeholder="Remarques..." class="form-control" id="autresTabac" cols="30" rows="4"><?= $dossier['autreshabitudeAlcooloTabagique'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion" href="#chirurgicauxComplications">
                                                        Chirurgicaux complications
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="chirurgicauxComplications" class="panel-collapse collapse">
                                                <div class="box-body">

                                                    <div class="form-group">
                                                        <label for="appendicectomie" class="control-label">Appendicectomie</label>
                                                        <textarea style="overflow-x: hidden;" name="appendicectomie" placeholder="Remarques..." class="form-control" id="appendicectomie" cols="30" rows="4"><?= $dossier['appendicectomie'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="cholecystectomie" class="control-label">Cholécystectomie</label>
                                                        <textarea style="overflow-x: hidden;" name="cholecystectomie" placeholder="Remarques..." class="form-control" id="cholecystectomie" cols="30" rows="4"><?= $dossier['cholecystectomie'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="autresChirurgicauxComplication" class="control-label">Autres</label>
                                                        <textarea style="overflow-x: hidden;" name="autresChirurgicauxComplication" placeholder="Remarques..." class="form-control" id="autresChirurgicauxComplication" cols="30" rows="4"><?= $dossier['autresChirurgicauxComplication'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-success btn-flat" id="btnUpdateInfoMedicalPatient" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                            </div>
                            <div class="overlay" id="loaderUpdateInfoMedicalPatient" style="display:none">
                                <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_newConsultation">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header" >
                                <h3 class="box-title"> <i class="fa fa-stethoscope"></i>&nbsp; Nouveau consultation </h3>

                                <div class="box-tools">
                                    <p class="btn-box-tool" style="color:#fff"></p>
                                </div>
                            </div>
                            <div class="box-body">
                                <form action="includes/functions/controller.php?action=addConsultation" id="FormAddConsultation" >
                                    <input type="hidden" name="idDossier" value="<?= $dossier['idDossier'] ?>">
                                    <div class="box-group" id="accordion2">

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" style="color:#000" data-parent="#accordion2" href="#infoConsultation">
                                                        <i class="fa fa-info-circle"></i> &nbsp;General
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="infoConsultation" class="panel-collapse collapse in">
                                                <div class="box-body">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group"> 
                                                                <label for="dateConsultation" class="control-label">Date de consultation <span class="required">*</span></label>
                                                                <input type="text" name="dateConsultation" id="dateConsultation" disabled value="<?= date('d / m / Y'); ?>" placeholder="Date de consultation..." class="form-control">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="typeConsultation" class="control-label">Type de consultation <span class="required">*</span></label>
                                                                <div class="form-group">
                                                                    <select name="typeConsultation" class="form-control select2">
                                                                        <?php foreach( $typeRdv as $type ): ?>
                                                                            <option value="<?= $type['idType'] ?>"> <?= $type['libelle'] ?> </option>
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="remarquesConsultation" class="control-label">Remarques <span class="required">*</span></label>
                                                        <textarea style="overflow-x: hidden;" name="remarquesConsultation" placeholder="Remarques..." class="form-control" id="remarquesConsultation" cols="30" rows="4"></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="montantConsultation" class="control-label">Montant du consultation <span class="required">*</span></label>
                                                        <input type="text" name="montantConsultation" placeholder="Entrez le montant du consultation en 'DH'..." value="0.00" class="form-control">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                        <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                            <h3 class="box-title">Ordonnance</h3>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="row">
                                                                        <div class="col-xs-10">
                                                                            <div class="form-group">
                                                                                <label for="medicamentOrdonnance" class="control-label">Medicament</label>
                                                                                <select name="medicamentOrdonnance" id="medicamentOrdonnance" class="form-control select2">
                                                                                    <?php foreach ($medicaments as $med): ?>
                                                                                        <option value="<?= $med['med'] ?>" > <?= $med['med'] ?> </option>
                                                                                    <?php endforeach ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-2" style="padding-left: 3px">
                                                                            <div class="form-group">
                                                                                <label class="control-label" style="color: #fff;">-</label>
                                                                                <button type="button" data-toggle="modal" data-target="#modalAddMedicament" class="btn btn-sm btn-primary form-control btn-flat" ><i class="ion ion-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="montantConsultation" class="control-label">Posologie par jour</label>
                                                                        <div class="row">
                                                                            <div class="col-md-4" style="padding-right: 3px;">
                                                                                <input type="text" id="posologieMatin" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-4" style="padding-right: 3px">
                                                                                <input type="text" id="posologieMidi" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-4" style="">
                                                                                <input type="text" id="posologieSoire" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="montantConsultation" class="control-label">Pendant</label>
                                                                        <div class="row">
                                                                            <div class="col-md-4" style="padding-right: 3px;">
                                                                                <input type="text" id="duree"class="form-control">
                                                                            </div>

                                                                            <div class="col-md-8"  style="padding-right: 3px;padding-left: 3px;">
                                                                                <select name=""  id="dureePeriod" class="form-control select2">
                                                                                    <option value="jour(s)">Jour(s)</option>
                                                                                    <option value="semain(s)">Semain(s)</option>
                                                                                    <option value="mois">Mois</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <label class="control-label" style="color: #fff;">-</label>
                                                                        <button type="button" id="btnAddMedicamentOrdonnance" class="btn btn-success form-control btn-flat"><i class="ion ion-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <label for="" class="control-label">Contenu d'ordonnance</label>
                                                            <div id="ordonnance"></div>
                                                            <input type="hidden" name="contenuOrdonnance" id="contenuOrdonnance" value="">
                                                        </div>
                                                        <div class="box-footer">
                                                        <form id="formGenererOrdonnance" action="ordonnance.php" method="post">
                                                            <button type="button" id="btnGenererOrdonnance" class="btn btn-primary btn-flat pull-right"><i class="fa fa-print"></i>&nbsp; Generer l'ordonnance </button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                    <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                        <h3 class="box-title">Diagnostic</h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label for="" class="control-label">Diagnostic</label>
                                                            <textarea name="diagnostic" id="" cols="30" rows="4" placeholder="Entrez une diagnostic" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" style="color:#000" data-parent="#accordion2" href="#motifConsultation">
                                                        <i class="fa fa-clipboard"></i> &nbsp; Motif de consultation
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="motifConsultation" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="douleurs" class="control-label">Douleurs <span class="required">*</span> </label>
                                                        <textarea style="overflow-x: hidden;" name="douleurs" placeholder="Remarques..." class="form-control" id="douleurs" cols="30" rows="4"><?= $dossier['rhiniteAllergique'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="symptome" class="control-label">Symptôme</label>
                                                        <textarea style="overflow-x: hidden;" name="symptome" placeholder="Remarques..." class="form-control" id="symptome" cols="30" rows="4"><?= $dossier['cardiopathie'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion2" href="#examenClinique">
                                                        <i class="fa fa-flask"></i>&nbsp; Examen clinique
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="examenClinique" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                                    <h3 class="box-title">Examen par appareil</h3>
                                                                </div>
                                                                <div class="box-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="cardioVasculaire" class="control-label">Cardio vasculaire</label>
                                                                                <textarea  style="overflow-x: hidden;" name="cardioVasculaire" placeholder="Remarques..." class="form-control" id="hta" cols="30" rows="4"><?= $dossier['hta'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="pulomnaires" class="control-label">Pulomnaires</label>
                                                                                <textarea style="overflow-x: hidden;" name="pulomnaires" placeholder="Remarques..." class="form-control" id="pulomnaires" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="abdominal" class="control-label">Abdominal</label>
                                                                                <textarea style="overflow-x: hidden;" name="abdominal" placeholder="Remarques..." class="form-control" id="abdominal" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="seins" class="control-label">Seins</label>
                                                                                <textarea style="overflow-x: hidden;" name="seins" placeholder="Remarques..." class="form-control" id="seins" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="osteoArtiCulare" class="control-label">Ostéo-arti culare</label>
                                                                                <textarea style="overflow-x: hidden;" name="osteoArtiCulare" placeholder="Remarques..." class="form-control" id="osteoArtiCulare" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="urogenital" class="control-label">Urogénital</label>
                                                                                <textarea style="overflow-x: hidden;" name="urogenital" placeholder="Remarques..." class="form-control" id="urogenital" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="neurologique" class="control-label">Neurologique</label>
                                                                                <textarea style="overflow-x: hidden;" name="neurologique" placeholder="Remarques..." class="form-control" id="neurologique" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="orl" class="control-label">ORL</label>
                                                                                <textarea style="overflow-x: hidden;" name="orl" placeholder="Remarques..." class="form-control" id="orl" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                                    <h3 class="box-title">General</h3>
                                                                </div>
                                                                <div class="box-body">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label for="taille" class="control-label">Taille</label>
                                                                                <input name="taille" placeholder="Taille en 'm'..." class="form-control" id="taille" >
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label for="poids" class="control-label">Poids</label>
                                                                                <input type="text" name="poids" placeholder="Poids en 'Kg'... " class="form-control" id="poids">
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="temperature" class="control-label">Temperature</label>
                                                                                <input type="text" name="temperature" placeholder="Temperature en '°C'..." class="form-control" id="temperature" >
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="etatGeneral" class="control-label">Etat general</label>
                                                                                <textarea style="overflow-x: hidden;" name="etatGeneral" placeholder="Remarques..." class="form-control" id="etatGeneral" cols="30" rows="4"><?= $dossier['hta'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="imc" class="control-label">IMC</label>
                                                                                <textarea style="overflow-x: hidden;" name="imc" placeholder="Remarques..." class="form-control" id="imc" cols="30" rows="4"><?= $dossier['hta'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" style="border-top:0px">
                                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                                <h4 class="box-title">
                                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion2" href="#bilaneParaclinique">
                                                        <i class="fa fa-stethoscope"></i>&nbsp; Bilane paraclinique
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="bilaneParaclinique" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                                    <h3 class="box-title">Biologie</h3>
                                                                </div>
                                                                <div class="box-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="echographie" class="control-label">Echographie</label>
                                                                                <textarea style="overflow-x: hidden;" name="echographie" placeholder="Remarques..." class="form-control" id="echographie" cols="30" rows="4"><?= $dossier['hta'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="radioStrandard" class="control-label">Radio strandard</label>
                                                                                <textarea style="overflow-x: hidden;" name="radioStrandard" placeholder="Remarques..." class="form-control" id="radioStrandard" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="tdm" class="control-label">TDM</label>
                                                                                <textarea style="overflow-x: hidden;" name="tdm" placeholder="Remarques..." class="form-control" id="tdm" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="trm" class="control-label">TRM</label>
                                                                                <textarea style="overflow-x: hidden;" name="trm" placeholder="Remarques..." class="form-control" id="trm" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="autreBiologie" class="control-label">Autres</label>
                                                                        <textarea style="overflow-x: hidden;" name="autreBiologie" placeholder="Remarques..." class="form-control" id="autreBiologie" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                        <span class="help-block"></span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                                    <h3 class="box-title">Radiographies standards</h3>
                                                                </div>
                                                                <div class="box-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="rxPoumon" class="control-label">Rx Poumon</label>
                                                                                <textarea style="overflow-x: hidden;" name="rxPoumon" placeholder="Remarques..." class="form-control" id="rxPoumon" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="rxRichs" class="control-label">Rx Richs</label>
                                                                                <textarea style="overflow-x: hidden;" name="rxRichs" placeholder="Remarques..." class="form-control" id="rxRichs" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-success btn-flat" id="btnAddConsultation" > <i class="fa fa-save"></i>&nbsp; Enregestrer la consultation </button>
                            </div>
                            <div class="overlay" id="loaderAddConsultation" style="display:none">
                                <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab_consultations">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="fa fa-stethoscope"></i>&nbsp; Liste des consultations </h3>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="removable"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <input id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                <table id="tblConsultations" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Motif</th>
                                            <th>Montant</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                <!-- Start Rdv -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_rdvs">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="fa fa-calendar"></i>&nbsp; Liste des rendez vous du patient : <span class="text-bold labelNomPatient"><?= ucfirst($dossier['prenom']) . '  ' . strtoupper($dossier['nom']) ?></span> </h3>
                                <input type="hidden" id="nomPatientForOrdonnance" value="<?= ucfirst($dossier['prenom']) . '  ' . strtoupper($dossier['nom']) ?>">
                                <div class="box-tools">
                                    <button type="button"  data-toggle="modal" data-target="#modalAddRdv" class="btn btn-box-tool" data-toggle="tooltip" data-placement="top" title="Nouveau rendez vous" ><i class="ion ion-calendar"></i>&nbsp; Nouveau rendez vous</button>
                                </div>
                            </div>
                            <div class="box-body">
                                <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                <table id="tblRdvsDossier" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date rendez vous </th>
                                            <th>Type de rendez vous</th>
                                            <th>Etat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Start Modal Add Mutuelle -->
                    <div id="modalAddMutuelle" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form  action="includes/functions/controller.php?action=addMutuelle" id="FormAddMutuelle" methode="post">
                                    <div class="modal-header bg-purple">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><i class="ion ion-plus"></i>&nbsp; Nouveau mutuelle </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="nomMutuelle" class="control-label">
                                                Nom du mutuelle <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="nomMutuelle" id="nomMutuelle" placeholder="Entrez le nom du mutuelle...">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                                        <button type="button" id="btnAddMutuelle" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Add Mutuelle -->

                    <!-- Start Modal Add Mutuelle -->
                    <div id="modalAddMedicament" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form  action="includes/functions/controller.php?action=addMedicament" id="FormAddMedicament" methode="post">
                                    <div class="modal-header bg-purple">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><i class="fa fa-medkit"></i>&nbsp; Nouveau medicament </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="designation" class="control-label">
                                                Désignation <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="designation" id="designation" placeholder="Entrez la designation du medicament...">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="familleMedicament" class="control-label">
                                                Famille de medicament <span class="required">*</span>
                                            </label>
                                            <select name="familleMedicament" id="familleMedicament" class="form-control select2">
                                                <?php foreach( $familleMedicament as $fMed ): ?>
                                                    <option value="<?= $fMed['idfamille'] ?>"> <?= $fMed['libelle'] ?> </option>
                                                <?php endforeach ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="dosage" class="control-label">
                                                Dosage <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="dosage" id="dosage" placeholder="Entrez le dosage du medicament...">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                                        <button type="button" id="btnAddMedicament" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Add Mutuelle -->

                    <!-- Start Modal Add Mutuelle -->
                    <div id="modalViewOrdonnance" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-purple">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Ordonnance</h4>
                                </div>
                                <div class="modal-body" style="padding: 10px;"> 
                                    <object id="objViewOrdonnance" data="" type="application/pdf" style="width:100%; max-height:500px; height:500px" ></object>               
                                </div> 
                                <div class="modal-footer"> 
                                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fermer</button>
                                    <button id="btnSaveOrdonnance" class="btn btn-primary btn-flat"><i class="fa fa-save"></i>&nbsp; Enregestrer</button>
                                </div>                 
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Add Mutuelle -->

                    <!-- Start Modal Add Rdv -->
                    <div id="modalAddRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form  action="includes/functions/controller.php?action=addRdv" id="FormAddRdv" methode="post">
                                    <div class="modal-header bg-purple">
                                        <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><i class="fa fa-calendar-plus-o "></i>&nbsp; Affecter nouveau rendez vous &nbsp;<span></span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="dateAddRdv" class="control-label">Choisie la date rendez vous </label>
                                                    <div class="form-group has-feedback">
                                                        <input type="text" id="dateAddRdv" name="dateAddRdv" data-provide="datepicker" data-date-format="yyyy-mm-dd" required placeholder="Date Rendez Vous ( JJ-MM-AAAA )" class="form-control datepicker">
                                                        <span class="help-block"></span>
                                                        <span style="font-size: 15px;" id="loaderInput" class="fa fa-spinner fa-spin form-control-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="typeRdv" class="control-label">Type de rendez vous </label>
                                                    <div class="form-group">
                                                        <select name="typeRdvP" id="typeRdv" class="form-control select2">
                                                            <?php foreach( $typeRdv as $type ): ?>
                                                                <option value="<?= $type['idType'] ?>"> <?= $type['libelle'] ?> </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="" class="control-label">Dureé de rendez vous </label>
                                                    <div class="form-group">
                                                        <input type="text" disabled class="form-control" value="<?= $params['dureeRdv'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="horairesDisponible">
                                            <label class="control-label">Liste des horaires disponible <span class="h5" style="color:#818181"> &nbsp;( vuillez choisie l'heure de rendez vous )</span></label>
                                            <br>
                                            <table id="tblHorairesDisponible" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Matin <span id="matin" class="pull-right text-red">  </span></th>
                                                        <th>Soire <span id="soire" class="pull-right text-red">  </span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%; padding: 10px; vertical-align: baseline !important; text-align: justify;" id="colMatin"></td>
                                                        <td style="width: 50%; padding: 10px; vertical-align: baseline !important; text-align: justify;" id="colSoire"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                                        <button type="button" id="btnAddRdv" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Add Rdv -->

                    <!-- Start Modal Edit Rdv -->
                    <div id="modalEditRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form  action="includes/functions/controller.php?action=updateRdvDossier" methode="post" id="FormUpdateRdv">
                                    <div class="modal-header bg-purple">
                                        <input name="idRdv" id="idRdv" type="hidden" value="">
                                        <input name="idDossier" id="idDossier" type="hidden" value="<?= $idDossier  ?>">
                                        <input name="nbrMinutes" id="nbrMinutes" value="<?= $params['nbrMinutes'] ?>" type="hidden">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Modifier le rendez vous N : &nbsp;<span></span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="dateEditRdv" class="control-label">La date rendez vous </label>
                                                    <div class="form-group has-feedback">
                                                        <input type="text" id="dateEditRdv" name="dateEditRdv" data-date-format="yyyy-mm-dd" required placeholder="Date Rendez Vous ( JJ-MM-AAAA )" class="form-control datepicker">
                                                        <span class="help-block"></span>
                                                        <span style="font-size: 15px;" id="loaderInputEditRdv" class="fa fa-spinner fa-spin form-control-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="typeRdvForEdit" class="control-label">Type de rendez vous </label>
                                                    <div class="form-group">
                                                        <select name="typeRdv" id="typeRdvForEdit" class="form-control select2">
                                                            <?php foreach( $typeRdv as $type ): ?>
                                                            <option value="<?= $type['idType'] ?>"> <?= $type['libelle'] ?> </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="" class="control-label">heure de rendez vous </label>
                                                    <div class="form-group">
                                                        <input type="text" disabled id="heureRdv" name="heureRdv" class="form-control" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="" class="control-label">Dureé de rendez vous </label>
                                                    <div class="form-group">
                                                        <input type="text" disabled id="dureeRdvForEdit" class="form-control" value="<?= $params['dureeRdv'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="horairesDisponibleForEdit">
                                            <label class="control-label">Liste Des Horaires Disponible <span class="h5" style="color:#818181"> &nbsp;( vuillez choisie l'heure de rendez vous )</span></label>
                                            <br>
                                            <table id="tblHorairesDisponible" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Matin <span id="matinEdit" class="pull-right text-red">  </span></th>
                                                        <th>Soire <span id="soireEdit" class="pull-right text-red">  </span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 50%; padding: 10px; vertical-align: baseline !important; text-align: justify;" id="colMatinEdit"></td>
                                                        <td style="width: 50%; padding: 10px; vertical-align: baseline !important; text-align: justify;" id="colSoireEdit"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-inline" id="EtatRdv">
                                                    <label class="control-label" style="margin-right:30px">Etat de rendez vous</label>
                                                    <label class="radio-inline text-blue">
                                                        <input type="radio" name="etatRdv" id="activeRdv" value="a"/>
                                                        Activer le rendez vous
                                                    </label>

                                                    <label class="radio-inline text-red">
                                                        <input type="radio" name="etatRdv" id="desactiveRdv" value="d"/>
                                                        Annuler le rendez vous
                                                    </label>

                                                    <label class="radio-inline text-green" id="valideRdv">
                                                        <input type="radio" name="etatRdv" value="v"/>
                                                        Valider le rendez vous
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                                        <button type="button" id="btnUpdateRdv" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Modifier </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Edit Rdv -->

                    <!-- Start Modal View Rdv -->
                    <div id="modalViewRdv" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-purple">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> &nbsp;Détails du rendez vous</h4>
                                </div>
                                <div class="modal-body" style="padding:5px">
                                    <table class="table table-bordered" id="table-view" style="margin-bottom:0px !important">
                                    <tr>
                                        <td>
                                            <b>Patient</b>
                                        </td>
                                        <td id="nomPatient"><a></a></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 125px !important; max-width: 200px;">
                                            <b>Numéro de telephone </b>
                                        </td>
                                        <td id="viewNumTele"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Date du rendez vous</b>
                                        </td>
                                        <td id="viewDateRdv"> <span class="badge bg-green"></span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Heure de rendez vous</b>
                                        </td>
                                        <td id="viewHeureRdv"> <span class="badge bg-green"></span> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Type de rendez vous</b>
                                        </td>
                                        <td id="viewTypeRdv"> <span class="badge-no-circle bg-blue"></span> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Etat du rendez vous</b>
                                        </td>
                                        <td id="viewEtatRdv"></td>
                                    </tr>
                                </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal View Rdv -->
                <!-- End Rdv -->

                    <!-- /.tab-pane -->
<!--                    <div class="tab-pane" id="tab_fichiers">-->
<!--                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">-->
<!--                            <div class="box-header">-->
<!--                                <h3 class="box-title"> <i class="fa fa-money"></i>&nbsp; Listes des paiements</h3>-->
<!--                                <div class="box-tools">-->
<!--                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
<!--                                    <button type="button" class="btn btn-box-tool" data-widget="removable"><i class="fa fa-times"></i></button>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="box-body table-responsive">-->
<!--                                <input id="idDossier" value="" type="hidden">-->
<!--                                <table id="tblConsultations" class="table table-bordered table-striped">-->
<!--                                    <thead>-->
<!--                                        <tr>-->
<!--                                            <th>ID Consultation</th>-->
<!--                                            <th>ID Rendez Vous</th>-->
<!--                                            <th>Date Début</th>-->
<!--                                            <th>Date Fin</th>-->
<!--                                            <th>Motif</th>-->
<!--                                            <th>Montant Net</th>-->
<!--                                            <th>Action</th>-->
<!--                                        </tr>-->
<!--                                    </thead>-->
<!--                                </table>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_paiements">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="ion ion-folder-outline"></i>&nbsp; Historiques des paiements </h3>
                                <div class="box-tools">
                                    <?php if( $rest > 0 ): ?>
                                        <button type="button" data-toggle="modal" data-target="#modalAddPaiement"   class="btn btn-box-tool" data-toggle="tooltip" data-placement="top" title="Nouveau paiement" ><i class="ion ion-plus"></i>&nbsp; Nouveau paiement</button>
                                    <?php endif?>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="tblPaiements" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date de paiement</th>
                                            <th>Montant à payer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <!-- Start Modal Add Paiements -->
                    <div id="modalAddPaiement" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form action="includes/functions/controller.php?action=AddPaiement" id="FormAddPaiement" methode="post">
                                    <div class="modal-header bg-purple">
                                        <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><i class="ion ion-plus"></i>&nbsp; Nouveau paiement &nbsp;<span></span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="" class="control-label">Montant de dossier</label>
                                                    <div class="input-group">
                                                        <input type="text" disabled class="form-control" placeholder="Montant de dossier" value="<?= $dossier['montantDossier'] ?>">
                                                        <span class="input-group-addon" id="basic-addon2">DH</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="" class="control-label">Rest</label>
                                                    <div class="input-group">
                                                        <input type="text" id="rest" disabled class="form-control" placeholder="Rest" value="<?= $rest ?>">
                                                        <span class="input-group-addon" id="basic-addon2">DH</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="datePaiment" class="control-label">Date de paiement</label>
                                            <input type="text" disabled name="datePaiment" id="datePaiment" class="form-control datepicker" placeholder="Date de paiement" value="<?= date("d/m/Y H:i");  ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="montantPaye" class="control-label">Montant à payé <span class="required">*</span> </label>
                                            <div class="input-group">
                                                <input type="text" name="montantPaye" id="montantPayer" class="form-control" placeholder="Montant à payé " value="0.00">
                                                <span class="input-group-addon" id="basic-addon2">DH</span>
                                            </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                                        <button type="button" id="btnAddPaiement" class="btn btn-flat btn-primary" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Add Rdv -->


                    <div class="tab-pane" id="tab_dossiers">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="ion ion-folder-outline"></i>&nbsp; Les autres Dossiers</h3>
                                <div class="box-tools">
                                    <button type="button"data-toggle="modal" data-target="#modalAddDossier" class="btn btn-box-tool" ><i class="fa fa-folder-open-o"></i>&nbsp; Nouveau dossier</button>
                                </div>
                            </div>
                            <div class="box-body">
                                <table id="tblDossiers" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Titre de dossier</th>
                                        <th>Date création</th>
                                        <th>Montant de dossier</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
                <!-- Start Modal Add Mutuelle -->
                <div id="modalAddDossier" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <form  action="includes/functions/controller.php?action=addDossierPatient" id="FormAddDossier" methode="post">
                                <div class="modal-header bg-purple">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><i class="fa fa-folder-open-o"></i>&nbsp; Nouveau Dossier au patient : <?php echo $dossier['prenom'] . '  ' . $dossier['nom'] ?> </h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="idPatient" value="<?= $dossier['idPatient'] ?>">
                                    <div class="form-group">
                                        <label for="designation" class="control-label">
                                            Titre de dossier <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="titreDossier" id="titreDossier" placeholder="Entrez le titre de dossier...">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                                    <button type="button" id="btnAddDossier" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Add Mutuelle -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>

<script>

    $(document).ready(function()
    {
        $('#btnGenererOrdonnance').hide();

        $('#tblTypes').dataTable();

        $('.btn-collapse-note').click(function()
        {
            $('#' + $(this).attr('data-collapse-note')).slideToggle();
        });

        var inp = 1;

        $('#btnAddMedicamentOrdonnance').click(function()
        {
            var med = $('#medicamentOrdonnance').val();
            var posologieMatin = $('#posologieMatin').val();
            var posologieMidi = $('#posologieMidi').val();
            var posologieSoire = $('#posologieSoire').val();
            var duree = $('#duree').val();
            var dureePeriod = $('#dureePeriod').val();
            var medicamentOrdonnance = med;
            // if( medicamentImprimer ! === \'\' ) {$("#btnGenererOrdonnance").slideDown();}else {$("#btnGenererOrdonnance").slideUp();}
            var posologieOrdonnance = posologieMatin + " - " + posologieMidi + " - " + posologieSoire + " par jour x " + duree + " " + dureePeriod;
            var inputMedicamentOrdonnance = '<div id="inputMedOrdonnance'+inp+'" style="margin-bottom:10px" class="input-group" id="' + inp + '" ><span class="input-group-addon"><input type="checkbox" checked ></span><input class="form-control medOrdonnance" style="width:30%" placeholder="Medicament..." type="text" value="' + medicamentOrdonnance + '"><input class="form-control posologieOrdonnance" style="width:70%; border-left:0px" placeholder="Posologie..." type="text" value="' + posologieOrdonnance + '"><span class="input-group-btn"><button type="button" id="btnRemoveInputOrdonnance' + inp + '" onclick="document.getElementById(\'btnRemoveInputOrdonnance'+inp+'\').parentNode.parentNode.parentNode.innerHTML=\'\';if( medicamentImprimer != \'\' ){$(\'#btnGenererOrdonnance\').slideDown();}else{$(\'#btnGenererOrdonnance\').slideUp();}" class="btn btn-danger btn-flat" ><i class="fa fa-remove"></i></button></span></div>';
            var inputOrdonnance = document.createElement('div');
            inputOrdonnance.innerHTML = inputMedicamentOrdonnance;
            document.getElementById('ordonnance').appendChild(inputOrdonnance);
            inp++;

            contenuOrdonnance = '';
            contentOrdonnanceImprimer = '';
            medicamentImprimer = '';

            var medicaments = $('.medOrdonnance');
            var posologieOrd = $('.posologieOrdonnance');

            for (var i = 0; i < medicaments.length; i++)
            {
                if(medicaments[i].parentNode.firstChild.firstChild.checked)
                {
                    medicamentImprimer += medicaments[i].value + '&' + posologieOrd[i].value + '|';
                }
                contenuOrdonnance += medicaments[i].value + ' ' + posologieOrd[i].value + '\n';
            }

            $('#contenuOrdonnance').val(contenuOrdonnance);

            $('#medicamentOrdonnance').selectedIndex = 0;
            $('#posologieMatin').val('');
            $('#posologieMidi').val('');
            $('#posologieSoire').val('');
            $('#duree').val('');


            if( medicamentImprimer != '' )
            {
                $('#btnGenererOrdonnance').slideDown();
            }
            else
            {
                $('#btnGenererOrdonnance').slideUp();
            }

            if( medicamentImprimer != '' ){$('#btnGenererOrdonnance').slideDown();}else{$('#btnGenererOrdonnance').slideUp();}

        });

        $('#btnEditer').attr('checked', false);

        getRendezVousDossier();
        getPaiementDossier();
        getAutresDossiers();
        getConsultations();

        $('#btnAddDossier').click(function(){
            addDossier();
        });

        var contenuOrdonnance = '';
        var contentOrdonnanceImprimer = '';
        var medicamentImprimer = '';

        $('#btnAddMutuelle').click(function()
        {
            addMutuelle();
        });

        $('#btnNewConsultation').click(function()
        {
            $('#btnReferAddConsultation').click();
        });

        $('#btnAddPaiement').click(function()
        {
            addPaiement();
        });

        $('#btnAddMedicament').click(function()
        {
            addMedicament();
        });

        $('#btnAddConsultation').click(function()
        {
            addConsultation();
        });

        $('#btnEditer').change(function()
        {
            if( this.checked )
            {
                $('#cinPatient').attr('disabled', false);
                $('#nomPatient').attr('disabled', false);
                $('#prenomPatient').attr('disabled', false);
                $('#dateNaissancePatient').attr('disabled', false);
                $('#telephonePatient').attr('disabled', false);
                $('#emailPatient').attr('disabled', false);
                $('#adressePatient').attr('disabled', false);
                $('#sexeHomme').attr('disabled', false);
                $('#sexeFemme').attr('disabled', false);
                $('#listeMutuel').attr('disabled', false);
                $('#groupSanguin').attr('disabled', false);
                $('#allergies').attr('disabled', false);
                $('#antiacedentsPersonnels').attr('disabled', false);
                $('#antiacedentsFamiliaux').attr('disabled', false);
                $('#taille').attr('disabled', false);
                $('#poids').attr('disabled', false);
                $('#btnAddMutuel').attr('disabled', false);
                $('#btnModifierInfoPersonnelles').attr('disabled', false);
            }
            else
            {
                var elem = document.querySelectorAll("input,textarea");
                var t = 0;
                for (var i = 0; i < elem.length; i++)
                {
                    if ( elem[i].parentNode.classList.contains("has-error") )
                    {
                        elem[i].parentNode.classList.remove('has-error');
                        elem[i].parentNode.lastElementChild.innerText = '';
                    }
                }

                $('#cinPatient').attr('disabled', true);
                $('#nomPatient').attr('disabled', true);
                $('#prenomPatient').attr('disabled', true);
                $('#dateNaissancePatient').attr('disabled', true);
                $('#telephonePatient').attr('disabled', true);
                $('#emailPatient').attr('disabled', true);
                $('#adressePatient').attr('disabled', true);
                $('#sexeHomme').attr('disabled', true);
                $('#sexeFemme').attr('disabled', true);
                $('#listeMutuel').attr('disabled', true);
                $('#groupSanguin').attr('disabled', true);
                $('#allergies').attr('disabled', true);
                $('#antiacedentsPersonnels').attr('disabled', true);
                $('#antiacedentsFamiliaux').attr('disabled', true);
                $('#taille').attr('disabled', true);
                $('#poids').attr('disabled', true);
                $('#btnAddMutuel').attr('disabled', true);
                $('#btnModifierInfoPersonnelles').attr('disabled', true);
            }
        });
    });

$('#btnModifierInfoPersonnelles').click(function()
{
    UpdateInfoPersonnelPatient();
});

$('#btnUpdateInfoMedicalPatient').click(function()
{
    UpdateInfoMedicalPatient();
});

//

function addMutuelle()
{
    var el = document.getElementById('nomMutuelle');

    if ( el.parentNode.classList.contains("has-error") )
    {
        el.parentNode.classList.remove('has-error');
        el.parentNode.lastElementChild.innerText = '';
    }

    loaderBtn('btnAddMutuelle', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var nomMutuelle = $('#nomMutuelle').val();

    $.ajax({

        url         :$('#FormAddMutuelle').attr('action'),
        type        : 'post',
        data        : {nomMutuelle : nomMutuelle},
        success     : function(data)
        {
            if( data == '2' )
            {
                ShowAlertError('L\'ajout de mutuelle à échoue, ce mutuel existe déjà !');
                el.parentNode.classList.add('has-error');
                el.parentNode.lastElementChild.innerText = "Ce mutuel existe déjà !";
            }
            else if( data == '0' )
            {
                ShowAlertError('L\'ajout de mutuelle à échoue, le nom du mutuel est obligatoire !');
                el.parentNode.classList.add('has-error');
                el.parentNode.lastElementChild.innerText = "Le nom du mutuel est obligatoire !";
            }
            else
            {
                var resultats = JSON.parse(data);

                if("nomMutuelle" in resultats)
                {
                    el.parentNode.classList.add('has-error');
                    el.parentNode.lastElementChild.innerText = resultats['nomMutuelle'];
                    ShowAlertError("La modification a échoué, " + resultats['nomMutuelle']);
                }
                else
                {
                    nomMutuelle = $('#nomMutuelle').val('');
                    document.getElementById('listeMutuel').innerHTML = "";

                    $.each(resultats, function(i, value) {
                        $('#listeMutuel').append($('<option>').text(value['libelle']).attr('value', value['idMutuel']));
                    });

                    $("#modalAddMutuelle").modal('hide')
                    showAlertSuccess('Le mutuelle est bien Ajouter !');
                }
            }

        },
        error : function(status)
        {
            console.log(status);
        },

        complete : function(resultat, statut)
        {
            loaderBtn('btnAddMutuelle', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
        }

    });
}

function addMedicament()
{
    var desMed = document.getElementById('designation');
    var dosage = document.getElementById('dosage');

    if ( desMed.parentNode.classList.contains("has-error") )
    {
        desMed.parentNode.classList.remove('has-error');
        desMed.parentNode.lastElementChild.innerText = '';
    }

    if ( dosage.parentNode.classList.contains("has-error") )
    {
        dosage.parentNode.classList.remove('has-error');
        dosage.parentNode.lastElementChild.innerText = '';
    }

    loaderBtn('btnAddMedicament', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var fd = new FormData(document.querySelector('#FormAddMedicament'));
    $.ajax({

        url         :$('#FormAddMedicament').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            console.log(data);
            if( data == '2' )
            {
                ShowAlertError('L\'ajout de medicament à échoue, ce medicament existe déjà !');
                desMed.parentNode.classList.add('has-error');
                desMed.parentNode.lastElementChild.innerText = "Ce medicament existe déjà !";
            }
            else if( data == '0' )
            {
                ShowAlertError('L\'ajout de medicament à échoue, tout les champs sont obligatoire !');
                desMed.parentNode.classList.add('has-error');
                desMed.parentNode.lastElementChild.innerText = "La designation du medicament est obligatoire !";
                dosage.parentNode.classList.add('has-error');
                dosage.parentNode.lastElementChild.innerText = "Le dosage de medicament est obligatoire !";
            }
            else
            {
                var resultats = JSON.parse(data);

                if("designation" in resultats)
                {
                    desMed.parentNode.classList.add('has-error');
                    desMed.parentNode.lastElementChild.innerText = resultats['designation'];
                }
                else if("dosage" in resultats)
                {
                    dosage.parentNode.classList.add('has-error');
                    dosage.parentNode.lastElementChild.innerText = resultats['dosage'];
                }
                else
                {
                    document.getElementById('medicamentOrdonnance').innerHTML = "";

                    $.each(resultats, function(i, value) {
                        $('#medicamentOrdonnance').append($('<option>').text(value['med']).attr('value', value['med']));
                    });

                    $("#modalAddMedicament").modal('hide');
                    desMed.value = '';
                    dosage.value = '';
                    showAlertSuccess('Le Medicament est bien enregestrer !');
                }
            }

        },
        error : function(status)
        {
            console.log(status);
        },

        complete : function(resultat, statut)
        {
            loaderBtn('btnAddMedicament', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
        }

    });
}


function UpdateInfoPersonnelPatient()
{
    var errors = [];

    $('#loaderUpdateInfoPersonnelPatient').show();

    var elem = document.querySelectorAll("input,textarea");
    var t = 0;
    for (var i = 0; i < elem.length; i++)
    {
        if ( elem[i].parentNode.classList.contains("has-error") )
        {
            elem[i].parentNode.classList.remove('has-error');
            elem[i].parentNode.lastElementChild.innerText = '';
        }
    }

    var fd = new FormData(document.querySelector('#FormUpdateInfoPersonnelPatient'));

    $.ajax({

        url         :$('#FormUpdateInfoPersonnelPatient').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            if( data == '0' )
            {
                ShowAlertError("La modefication à échoue !");
            }
            else if( data == -1 )
            {
                ShowAlertError("La modefication à échoue !, Ce numero de carte national existe déjà !");
                var cin = document.getElementById('cinPatient');
                cin.parentNode.classList.add('has-error');
                cin.parentNode.lastElementChild.innerText = 'Ce numero de carte national existe déjà !';
            }
            else
            {
                var resultats = JSON.parse(data);

                if("nomCmpltPatient" in resultats)
                {
                    $('.labelNomPatient').text(resultats['nomCmpltPatient']);
                    showAlertSuccess('Les informations est bien modifier');
                }
                else
                {
                    ShowAlertError("La modification a échoué tout les champs sont obligatoire !");

                    for (var err in resultats)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.classList.add('has-error');
                        el.parentNode.lastElementChild.innerText = resultats[err];
                    }

                    for (var err in resultats)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.classList.add('has-error');
                        el.parentNode.lastElementChild.innerText = resultats[err];
                    }
                }
            }
        },

        error : function(status)
        {
            console.log(status);
        },

        complete : function(status)
        {
            $('#loaderUpdateInfoPersonnelPatient').hide();
        }

    });
}

function UpdateInfoMedicalPatient()
{
    $('#loaderUpdateInfoMedicalPatient').show();

    var fd = new FormData(document.querySelector('#FormUpdateInfoMedicalPatient'));

    $.ajax({

        url         :$('#FormUpdateInfoMedicalPatient').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            if( data == '1' )
            {
                showAlertSuccess('Les antécédents est enregestrer !');
            }
            else if( data == '0' )
            {
                ShowAlertError("La modefication à échoue !");
            }
            else
            {
                var resultats = JSON.parse(data);

                ShowAlertError("La modification a échoué !");

                for (var err in resultats)
                {
                    var el = document.getElementById(err);
                    el.parentNode.classList.add('has-error');
                    el.parentNode.lastElementChild.innerText = resultats[err];
                }

                for (var err in resultats)
                {
                    var el = document.getElementById(err);
                    el.parentNode.classList.add('has-error');
                    el.parentNode.lastElementChild.innerText = resultats[err];
                }
            }
        },

        error : function(status)
        {
            console.log(status);
        },

        complete : function(status)
        {
            $('#loaderUpdateInfoMedicalPatient').hide();
        }

    });
}


// Fonctionnes des rendez vous :
// get all rdvs of folder :
function getRendezVousDossier()
{
    var idDossier = $('#idDossier').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getRdvsDossier&idDossier=' + idDossier,

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            if( res2 == 0 )
            {
                res2 = [];
            }

            var nbrRdv = Object.keys(res2).length;

            $('#nbrRdv').text(nbrRdv);

            $('#tblRdvsDossier').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html
                    (
                        '<div class="btn-group"><button type="button" class="btn btn-info btn-flat"> Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="javascript:void(0)" onclick="showModalEditRdv(' + aData.idRdv + ')" class="text-green"><i class="fa fa-edit"></i>Editer</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="deleteRdv(' + aData.idRdv + ')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'
                    )

                    return nRow;
                },

                "columns":
                [
                    {
                        "data": "dateRdv",
                        "render": function(data, type, row)
                        {
                            return '<span class="text-blue text-bold">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm") + ' </span>';
                        }
                    },
                    {
                        "data": "libelle",
                        "render": function(data, type, row)
                        {
                            return '<span class="badge-no-circle bg-green"> ' + data + ' </span>';
                        }
                    },
                    {
                        "data": "dateRdv",

                        "render": function(data, type, row)
                        {
                            var expirer  = new Date(data).getTime() < new Date().getTime();
                            var enAttent = new Date(data).getTime() >= new Date().getTime();
                            var active   = false;
                            var annule   = false;
                            var valider  = false;

                            if( row['Etat'] == 'a' )
                            {
                                active = true;
                            }
                            else if( row['Etat'] == 'd' )
                            {
                                annule = true;
                            }
                            else if(  row['Etat'] == 'v' )
                            {
                                valider = true;
                            }

                            if( active && expirer)
                            {
                                return '<span class="badge-no-circle bg-yellow"> Expirer </span>';
                            }
                            else if( active && enAttent )
                            {
                                return '<span class="badge-no-circle bg-blue"> En Attent</span>';
                            }
                            else if( annule && enAttent)
                            {
                                return '<span class="badge-no-circle bg-red"> Annulé </span>';
                            }
                            else if( valider  )
                            {
                                return '<span class="badge-no-circle bg-green"> Validé </span>';
                            }
                            else if( expirer )
                            {
                                return '<span class="badge-no-circle bg-yellow"> Expirer </span>';
                            }
                        }
                    },
                    { "data": "idRdv" },
                ],

                "language":
                {
                    "sProcessing": "Traitement en cours ...",
                    "sLengthMenu": "Afficher _MENU_ lignes",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible",
                    "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                    "sInfoEmpty": "Aucune ligne affichée",
                    "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                    "sInfoPostFix": "",
                    "sSearch": "Chercher:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Chargement...",
                    "oPaginate": {
                        "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                    }
                }
            });
        },

        error : function(err){
            console.log(err);
        }

    });
}

    // get all rdvs of folder :
function getPaiementDossier()
{
    var idDossier = $('#idDossier').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getPaiementDossier&idDossier=' + idDossier,

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);


            $('#tblPaiements').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html
                    (
                        '<a class="btn btn-flat btn-danger btn-sm" href="javascript:void(0)" onclick="deletePaiement(' + aData.idPaiement + ')" class="text-red"> <i class="fa fa-trash-o"></i>&nbsp; Supprimer</a>'
                    )

                    return nRow;
                },

                "columns":
                    [
                        {
                            "data": "datePaiement",
                            "render": function(data, type, row)
                            {
                                return '<span class="text-blue text-bold">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm"); + ' </span>';
                            }
                        },
                        {
                            "data": "montantPaye",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge bg-green"> ' + data + ' DH </span>';
                            }
                        },
                        { "data": "idPaiement" }
                    ],

                "language":
                {
                    "sProcessing": "Traitement en cours ...",
                    "sLengthMenu": "Afficher _MENU_ lignes",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible",
                    "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                    "sInfoEmpty": "Aucune ligne affichée",
                    "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                    "sInfoPostFix": "",
                    "sSearch": "Chercher:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Chargement...",
                    "oPaginate": {
                        "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                    }
                }
            });
        },

        error : function(err){
            console.log(err);
        }

    });
}


    function getConsultations()
    {
        var idDossier = $('#idDossier').val();

        $.ajax({

            url : 'includes/functions/controller.php?action=getConsultationsDossier&idDossier=' + idDossier,

            type : 'GET',

            success : function(res)
            {
                res2 = JSON.parse(res);

                var nbrCosultations = Object.keys(res2).length;

                $('#nbrCosultations').text(nbrCosultations);

                $('#tblConsultations').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<div class="btn-group"><button type="button" class="btn btn-info btn-flat">Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="editConsultation.php?idconsultation=' + aData.idConsultation + '" class="text-green"><i class="fa fa-edit"></i>Editer</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="deleteConsultation(' + aData.idConsultation + ')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'
                        )

                        return nRow;
                    },

                    "columns":
                        [
                            { data : "idConsultation" },
                            {
                                "data": "dateDebut",
                                "render": function(data, type, row)
                                {
                                    return '<span class="text-blue">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm"); + ' </span>';
                                }
                            },
                            {
                                "data": "libelle",
                                "render": function(data, type, row)
                                {
                                    return '<span class="badge badge-no-circle bg-green bg-green"> ' + data + '</span>';
                                }
                            },
                            { "data": "remarquesConsultation" },
                            {
                                "data": "montantNetConsultation",
                                "render": function(data, type, row)
                                {
                                    return '<span class=""> ' + data + ' DH</span>';
                                }
                            },
                            { "data": "idConsultation" }
                        ],

                    "language":
                    {
                        "sProcessing": "Traitement en cours ...",
                        "sLengthMenu": "Afficher _MENU_ lignes",
                        "sZeroRecords": "Aucun résultat trouvé",
                        "sEmptyTable": "Aucune donnée disponible",
                        "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                        "sInfoEmpty": "Aucune ligne affichée",
                        "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                        "sInfoPostFix": "",
                        "sSearch": "Chercher:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Chargement...",
                        "oPaginate": {
                            "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                        },
                        "oAria": {
                            "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                        }
                    }
                });
            },

            error : function(err){
                console.log(err);
            }

        });
    }

    function getAutresDossiers()
    {
        var idDossier = $('#idDossier').val();
        var idPatient = $('#idPatientForAddDossier').val();

        $.ajax({

            url : 'includes/functions/controller.php?action=getAutresDossiers&idDossier=' + idDossier + '&idPatient=' + idPatient,

            type : 'GET',

            success : function(res)
            {
                var res2 = JSON.parse(res);

                $('#tblDossiers').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<a class="btn btn-info btn-sm btn-flat" href="dossierPatient.php?idDossier=' + aData.idDossier + '" ><i class="fa fa-eye"></i></a> <button class="btn btn-sm btn-danger btn-flat" onclick="deleteDossier(' + aData.idDossier + ')"> <i class="fa fa-trash-o"></i></button>'
                        )

                        return nRow;
                    },

                    "columns": [
                        { "data": "titreDossier" },
                        {
                            "data": "dateCreation",
                            "render": function(data, type, row)
                            {
                                return '<span class="text-blue">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm"); + ' </span>';
                            }
                        },
                        {
                            "data": "montantDossier",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge badge-no-circle bg-green">' + data + ' DH</span>';
                            }
                        },
                        { "data": "idDossier" }
                    ],

                    "language":
                    {
                        "sProcessing": "Traitement en cours ...",
                        "sLengthMenu": "Afficher _MENU_ lignes",
                        "sZeroRecords": "Aucun résultat trouvé",
                        "sEmptyTable": "Aucune donnée disponible",
                        "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                        "sInfoEmpty": "Aucune ligne affichée",
                        "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                        "sInfoPostFix": "",
                        "sSearch": "Chercher:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Chargement...",
                        "oPaginate": {
                            "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                        },
                        "oAria": {
                            "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                        }
                    }

                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function deleteDossier(idDossier) {

        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce dossier !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Supprimer!',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve)
                {
                    $.ajax({

                        url  : 'includes/functions/controller.php?action=deleteDossier&idDossier=' + idDossier,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                getAutresDossiers();
                                swal( 'Good !', 'Le dossier vous est bien supprimer !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer ce dossier !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce dossier !', 'error' ).insertQueueStep(1);
                            resolve();
                        }

                    });
                });
            }
        }]);
    }

$(document).ready(function ()
{
    $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
    {
        $('body').css('padding-right','0 !important');
    });

    $.fn.modal.Constructor.prototype.setScrollbar = function () { };

    $('.select2').select2({ "width": "100%" });

    $("#btnAddRdv").attr("disabled","disabled");

    $('#dateAddRdv').datepicker('setStartDate', new Date());

    $('#dateAddRdv').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });


    $('#loaderInput').hide();

    $('#loaderInputEditRdv').hide();

    $('#horairesDisponible').hide();

    $('#horairesDisponibleForEdit').hide();

    moment().format();

    $('#dossiers').select2({ width: '100%'});
    $('#etatRdv').on('change', function(){
        if( $(this).is(":checked") )
        {
            $('#etatAlis').text('Activé');
            $('#etatAlis').removeClass('bg-red');
            $('#etatAlis').addClass('bg-green');
        }
        else
        {
            $('#etatAlis').text('Désactivé');
            $('#etatAlis').removeClass('bg-green');
            $('#etatAlis').addClass('bg-red');
        }
    });

    $('#btnUpdateRdv').click(function(){
        updateRdvDossier();
    });

    $('#dateEditRdv').change(function(){
        getHorairesDisponibleforEdit();
    });

    $('#dateAddRdv').change(function(){
        getHorairesDisponible();
    });

    $('#btnAddRdv').click(function(){
        addRdvDossier();
    });

    var contenuOrdonnance = '';
    var contentOrdonnanceImprimer = '';
    var doc = new jsPDF();

    $('#btnSaveOrdonnance').click(function(){
        doc.save( nomOrdonnance + '.pdf');
    });
    var nomOrdonnance = 'ordonnance';
    var medicamentImprimer = '';

    $('#btnGenererOrdonnance').click(function()
    {
        doc = new jsPDF();
        var dateOrdonnance = $('#dateConsultation').val();
        var nomPatient = $('#nomPatientForOrdonnance').val();
        var cinPatient = $('#cinPatient').val();
        doc.setFont("times");
        doc.setFontType("bold");
        doc.setFontSize(18);
        doc.text(105, 85, 'Le : ' + dateOrdonnance, null, null, 'center');
        doc.text(105, 100, nomPatient, null, null, 'center');
        doc.setFontSize(16);

        contenuOrdonnance = '';
        contentOrdonnanceImprimer = '';
        medicamentImprimer = '';

        var medicaments = $('.medOrdonnance');
        var posologieOrd = $('.posologieOrdonnance');

        for (var i = 0; i < medicaments.length; i++)
        {
            if(medicaments[i].parentNode.firstChild.firstChild.checked)
            {
                medicamentImprimer += medicaments[i].value + '&' + posologieOrd[i].value + '|';
            }
            contenuOrdonnance += medicaments[i].value + '&' + posologieOrd[i].value + '|';
        }

        //console.log(medicamentImprimer);
        var traitements = medicamentImprimer.split('|');
        var d = 125;
        for (var i = 0; i < traitements.length-1; i++)
        {
            var medicament = traitements[i].split('&');

            doc.setFontType("normal");
            doc.text(30, d, i+1 + ' - ' + medicament[0]);
            d+=10;
            doc.text(50, d, medicament[1]);
            d+=15;
        }
        dateOrdonnance = dateOrdonnance.replace(/ /g, '');
        nomPatient = nomPatient.replace(/ /g, '');
        nomOrdonnance = cinPatient + nomPatient + dateOrdonnance;

        var string = doc.output('datauristring');
        $('#objViewOrdonnance').attr('data', string);
        $('#modalViewOrdonnance').modal('show');
    });
});

// Desactiver Rendez Vous :

function desactiverRdv ( idRdv ) {
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment Desactivé Ce Rendez Vous!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Desactivé!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=desactiveRdv&idRdv=' + idRdv,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            swal( 'Good !', 'Le Rendez Vous ESt Bien Désactivé !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible De Désactivé Ce Rendez Vous !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible De Désactivé Ce Rendez Vous !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}

    function deleteConsultation ( idConsultation ) {
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer cette consultation ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Supprimer!',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve)
                {
                    $.ajax({

                        url  : 'includes/functions/controller.php?action=deleteConsultation&idConsultation=' + idConsultation,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                setTimeout(function (){
                                    window.location.reload();
                                }, 2000);
                                getConsultations();
                                swal( 'Good !', 'La consultation est bien supprimer !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer cette consultation !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de supprimer cette consultation !', 'error' ).insertQueueStep(1);
                            resolve();
                        }

                    });
                });
            }
        }]);
    }


function showModalEditRdv(idRdv)
{
    $('#horairesDisponibleForEdit').slideUp();
    var idDossier = $('#idDossier').val();
    var rdv;
    var matin = $('#matinEdit');
    var soire = $('#soireEdit');
    var dureeRdv = $('#nbrMinutes').val();
    var rdvReserver;
    $('#EtatRdv').show();
    $('#valideRdv').show();

    $.ajax({

        url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,

        type : 'get',

        success : function (data)
        {
            rdv = JSON.parse(data);

            $('#modalEditRdv .modal-title span').text( rdv.idRdv );
            $('#idRdv').val(rdv.idRdv);
            $("#typeRdvForEdit").val(rdv.typeRdv).trigger("change");
            $('#modalEditRdv #dateEditRdv').val( rdv.dateRdv );
            $('#modalEditRdv #heureRdv').val( moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm") );

            if( rdv.Etat === 'd')
            {
                $('#modalEditRdv #desactiveRdv').attr( "checked", true );
            }
            else if( rdv.Etat === 'a' )
            {
                $('#modalEditRdv #activeRdv').attr( "checked", true );
            }
            else
            {
                $('#modalEditRdv #valideRdv').attr( "checked", true );
            }

            var expirer  = new Date(rdv.dateRdv).getTime() < new Date().getTime();
            var enAttent = new Date(rdv.dateRdv).getTime() >= new Date().getTime();
            var active   = false;
            var annule   = false;
            //

            if( rdv.Etat == 'a' )
            {
                active = true;
            }
            else if( rdv.Etat == 'd' )
            {
                annule = true;
            }
            else if(  rdv.Etat == 'v' )
            {
                valider = true;
            }

            if( active && expirer)
            {
                $('#EtatRdv').hide();
            }
            else if(expirer)
            {
                $('#EtatRdv').hide();
            }
            else if( annule )
            {
                $('#valideRdv').hide();
            }
            $("#modalEditRdv").modal({backdrop: "static"});
        },

        error : function (status)
        {
            console.log(status);
        }

    });
}

function updateRdvDossier()
{
    loaderBtn('btnUpdateRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var fd = new FormData(document.querySelector('#FormUpdateRdv'));

    $.ajax({

        url         :$('#FormUpdateRdv').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            if( data == '1' )
            {
                $("#modalEditRdv").modal('hide')
                showAlertSuccess('Le rendez vous est bien modifier !');
                getRendezVousDossier();
            }
            else if( data == '2' )
            {
                ShowAlertError('La modification du rendez vous à échoue, vuillez choisie une date corréecte !');
            }
            else
            {
                ShowAlertError('La modification du rendez vous à échoue, La date de rendez vous est obligatoire !');
            }
        },
        error : function(status)
        {
            console.log(status);
        },
        complete : function()
        {
            loaderBtn('btnUpdateRdv', '<i class="fa fa-save"></i>&nbsp; Modifier');
            $('#horairesDisponible').slideUp();
        }

    });
}

function addRdvDossier()
{
    loaderBtn('btnAddRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var dateRdv    = $('#dateAddRdv').val();
    var heureRdv   = $('input[name=heurRdv]:checked').val();
    var typeRdv    = $('#typeRdv').val();
    var dateAddRdv = dateRdv + ' ' + heureRdv + ':00' ;

    var idDossier = $('#idDossier').val();

    $.ajax({

        url         :$('#FormAddRdv').attr('action'),
        type        : 'post',
        data        : {dateRdv : dateAddRdv, idDossier : idDossier, typeRdv : typeRdv},
        success     : function(data)
        {
            console.log(data);
            if( data == '1' )
            {
                $('#btnNavRdv').click();
                $("#modalAddRdv").modal('hide');
                showAlertSuccess('Le rendez vous est bien Ajouter !');
                getRendezVousDossier();
            }
            else
            {
                ShowAlertError('L\'ajout de rendez vous à échoue, vuillez choisie une date corréecte !');
            }
        },
        error : function(status)
        {
            console.log(status);
        },

        complete : function(resultat, statut)
        {
            loaderBtn('btnAddRdv', '<i class="fa fa-save"></i>&nbsp; Enregestrer');

            $('#horairesDisponible').slideUp();
        }

    });
}

function deleteRdv (idRdv) {

    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer ce rendez vous !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Supprimer!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=deleteRdvDossier&idRdv=' + idRdv,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            getRendezVousDossier();
                            swal( 'Good !', 'Le rendez vous est bien supprimer !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce rendez vous !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer ce rendez vous !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}

// get Rdv date : getHorairesDisponibleforEdit
function getHorairesDisponible()
{

    $('#loaderInput').show();
    $('#horairesDisponible').slideUp();
    $("#btnAddRdv").attr("disabled", true);

    var dateAddRdv  = $('#dateAddRdv').val();
    var matin       = $('#matin');
    var soire       = $('#soire');
    var rdvReserver;
    var dureeRdv    = $('#nbrMinutes').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getHorairesDisponible&dateAddRdv=' + dateAddRdv,

        type : 'GET',

        success : function(data)
        {
            // get Rdvs Reserver :
            $.ajax({

                url : 'includes/functions/controller.php?action=getRdvsReserver&dateAddRdv=' + dateAddRdv,

                type : 'GET',

                success : function(rdvRserve)
                {
                    $("#btnAddRdv").attr("disabled", false);

                    if( rdvRserve == 0 )
                    {
                        rdvReserver = [];

                        if( data == 0 )
                        {
                            res2 = [];
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponible').slideDown();
                            $('#loaderInput').hide();

                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');

                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                radioBoxes += '<div class="radio-inline"><label><input type="radio" id="heurRdv" name="heurRdv"value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start2 = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatin').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');

                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {
                                radioBoxes += '<div style="margin-left: 10px; margin-bottom: 7px;" class="radio-inline"><label><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoire').html(radioBoxes);
                        }
                    }
                    else
                    {
                        rdvReserver = JSON.parse(rdvRserve);

                        if( data == 0 )
                        {
                            res2 = [];
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponible').slideDown();
                            $('#loaderInput').hide();

                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');

                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                var time = moment(start2, 'hh:mm:ss').format("HH:mm:ss");

                                var isReserve = false;

                                for (var i = 0; i < rdvReserver.length; i++)
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                }
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input id="heurRdv" disabled type="radio">' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }

                                start2   = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatin').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');

                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {

                                var time = moment(start, 'hh:mm:ss').format("HH:mm:ss");
                                var isReserve = false;

                                for (var i = 0; i < rdvReserver.length; i++)
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                }
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input disabled type="radio" >' + moment(start, 'hh:mm:ss').format("HH:mm") + '</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + ' >' + moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }

                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoire').html(radioBoxes);
                        }
                    }
                },

                error : function(err)
                {
                    console.log(err);
                }

            });
        },

        error : function(err)
        {
            console.log(err);
            $('#loaderInput').hide();
        }

    });
}

// get Rdv date : getHorairesDisponibleforEdit
function getHorairesDisponibleforEdit()
{
    $('#loaderInputEditRdv').show();
    $('#horairesDisponibleForEdit').slideUp();
    $("#btnUpdateRdv").attr("disabled", true);

    var dateEditRdv = $('#dateEditRdv').val();
    var matin       = $('#matinEdit');
    var soire       = $('#soireEdit');
    var dureeRdv    = $('#nbrMinutes').val();
    var rdvReserver;

    $.ajax({

        url : 'includes/functions/controller.php?action=getHorairesDisponible&dateAddRdv=' + dateEditRdv,

        type : 'GET',

        success : function(data)
        {
            // get Rdvs Reserver :
            $.ajax({

                url : 'includes/functions/controller.php?action=getRdvsReserver&dateAddRdv=' + dateEditRdv,

                type : 'GET',

                success : function(rdvRserve)
                {
                    $("#btnUpdateRdv").attr("disabled", false);

                    if( rdvRserve == 0 )
                    {
                        rdvReserver = [];

                        if( data == 0 )
                        {
                            res2 = [];
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponibleForEdit').slideDown();
                            $('#loaderInputEditRdv').hide();

                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');

                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                radioBoxes += '<div class="radio-inline"><label><input type="radio" id="heurRdv" name="heurRdv"value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start2 = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatinEdit').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');

                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {
                                radioBoxes += '<div style="margin-left: 10px; margin-bottom: 7px;" class="radio-inline"><label><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoireEdit').html(radioBoxes);
                        }
                    }
                    else
                    {
                        rdvReserver = JSON.parse(rdvRserve);

                        if( data == 0 )
                        {
                            res2 = [];
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponibleForEdit').slideDown();
                            $('#loaderInputEditRdv').hide();

                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');

                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                var time = moment(start2, 'hh:mm:ss').format("HH:mm:ss");

                                var isReserve = false;

                                for (var i = 0; i < rdvReserver.length; i++)
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                }
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input id="heurRdv" disabled type="radio">' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }

                                start2   = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatinEdit').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');

                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {

                                var time = moment(start, 'hh:mm:ss').format("HH:mm:ss");
                                var isReserve = false;

                                for (var i = 0; i < rdvReserver.length; i++)
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                }
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input disabled type="radio" >' + moment(start, 'hh:mm:ss').format("HH:mm") + '</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + ' >' + moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }

                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoireEdit').html(radioBoxes);
                        }
                    }
                },

                error : function(err)
                {
                    console.log(err);
                }

            });
        },

        error : function(err)
        {
            console.log(err);
            $('#loaderInputEditRdv').hide();
        }
    });
}


//function viewInfoRdv(idRdv)
//{
//    var rdv;
//    var rdvReserver;
//
//    $.ajax({
//
//        url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,
//
//        type : 'get',
//
//        success : function (data)
//        {
//            rdv = JSON.parse(data);
//            $('#modalViewRdv #nomPatient a').attr( 'href', 'http://localhost/sitecabinetDentaire/admin/viewPatient.php?idPatient=' + rdv.idPatient );
//            $('#modalViewRdv #nomPatient a').text( rdv.prenom + '  ' + rdv.nom );
//            $('#modalViewRdv #viewNumTele').text( rdv.tel );
//            $('#modalViewRdv #viewTypeRdv span').text( rdv.libelle );
//            $('#modalViewRdv #viewDateRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("dddd-MM-YYYY"));
//            $('#modalViewRdv #viewHeureRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm"));
//
//            var dateSystem = new Date().getTime();
//            var dateRdv    = new Date(rdv.dateRdv).getTime();
//            var expirer    = dateRdv < dateSystem;
//            var enAttent   = dateRdv >= dateSystem;
//            var active     = false;
//            var annule     = false;
//            var valider    = false;
//
//            if( rdv.Etat == 'a' )
//            {
//                active = true;
//            }
//            else if( rdv.Etat == 'd' )
//            {
//                annule = true;
//            }
//            else if(  rdv.Etat == 'v' )
//            {
//                valider = true;
//            }
//
//            if( active && expirer)
//            {
//                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-yellow"> Expirer </span>');
//            }
//            else if( active && enAttent )
//            {
//                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-blue"> En Attent</span>');
//            }
//            else if( annule && enAttent)
//            {
//                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-red"> Annulé </span>');
//            }
//            else if( valider  )
//            {
//                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-green"> Validé </span>');
//            }
//            else if( expirer )
//            {
//                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-yellow"> Expirer </span>');
//            }
//
//            $("#modalViewRdv").modal({backdrop: "static"});
//        },
//        error : function (status)
//        {
//            console.log(status);
//        }
//
//    });
//}


/* Dossier fonctions */
    function addDossier()
    {
        loaderBtn('btnAddDossier', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.querySelectorAll("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.classList.remove('has-error');
                elem[i].parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormAddDossier'));

        $.ajax({

            url         :$('#FormAddDossier').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,

            success     : function(data)
            {
                console.log(data);
                if( data == '1' )
                {
                    getAutresDossiers();
                    showAlertSuccess('Le dossier est bien jouter !');
                    $("#modalAddDossier").modal('hide');
                    $("#titreDossier").val('');
                }
                else if( data == '0' )
                {
//                    ShowAlertError('L\'ajout à échoue !');
                }
                else
                {
                    ShowAlertError('L\'ajout à échoué ce champ est obligatoire !');

                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.classList.add('has-error');
                        el.parentNode.lastElementChild.innerText = errors[err];
                    }
                }
            },

            error : function(status)
            {
                console.log(status);
            },

            complete : function()
            {
                loaderBtn('btnAddDossier', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
            }

        });
    }

// add Paiement
function addPaiement()
{
    if(document.getElementById('montantPayer').parentNode.parentNode.classList.contains('has-error'))
    {
        document.getElementById('montantPayer').parentNode.parentNode.classList.remove('has-error');
        document.getElementById('montantPayer').parentNode.parentNode.lastElementChild.innerText = "";
    }


    var montantPayer = $('#montantPayer').val();
    var rest         = $('#rest').val();

    console.log('rest : ' + rest + '  mPaye : ' + montantPayer);

    if( isNaN(montantPayer) )
    {
        document.getElementById('montantPayer').parentNode.parentNode.classList.add('has-error');
        document.getElementById('montantPayer').parentNode.parentNode.lastElementChild.innerText = "Vuillez entrer un montant correct !";
    }
    else
    {
        if( eval(montantPayer) > eval(rest) || eval(montantPayer) <= 0 )
        {
            document.getElementById('montantPayer').parentNode.parentNode.classList.add('has-error');
            document.getElementById('montantPayer').parentNode.parentNode.lastElementChild.innerText = "Vuillez entrer un montant correct !";
        }
        else
        {
            loaderBtn('btnAddPaiement', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

            var fd = new FormData(document.querySelector('#FormAddPaiement'));

            $.ajax({

                url         :$('#FormAddPaiement').attr('action'),
                type        : 'post',
                data        : fd,
                processData : false,
                contentType : false,
                success     : function(data)
                {
                    if( data == '1' )
                    {
                        setTimeout(function (){
                            window.location.reload();
                        }, 2000);
                        getPaiementDossier();
                        $('#btnNavPaiements').click();
                        $('#modalAddPaiement').modal('hide');
                        showAlertSuccess('Le paiement est bien jouter !');
                    }
                    else if( data == '0' )
                    {
                        ShowAlertError('L\'ajout à de paiment échoue !');
                    }
                    else
                    {
                        ShowAlertError('L\'ajout de paiement à échoué, vuillez respecter le format de paiements !');

                        errors = JSON.parse(data);

                        for (var err in errors)
                        {
                            var el = document.getElementById(err);
                            el.parentNode.classList.add('has-error');
                            el.parentNode.lastElementChild.innerText = errors[err];
                        };

                        for (var err in errors)
                        {
                            var el = document.getElementById(err);
                            el.parentNode.classList.add('has-error');
                            el.parentNode.lastElementChild.innerText = errors[err];
                        };
                    }
                },
                error : function(status)
                {
                    console.log(status);
                },

                complete : function(resultat, statut)
                {
                    loaderBtn('btnAddPaiement', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
                }

            });
        }
    }
}

function deletePaiement (idPaiement) {

    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer ce paiements !",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Supprimer!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=deletePaiementDossier&idPaiement=' + idPaiement,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            setTimeout(function (){
                                window.location.reload();
                            }, 2000);
                            getPaiementDossier();
                            swal( 'Good !', 'Le paiement est bien supprimer !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce paiement !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer ce paiement !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}

//add Consultation
function addConsultation()
{
    $('#loaderAddConsultation').show();
    var fd = new FormData(document.querySelector('#FormAddConsultation'));

    $.ajax({

        url         :$('#FormAddConsultation').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            if( data == '1' )
            {
                setTimeout(function (){
                    window.location.reload();
                }, 2000);
                getConsultations();
                showAlertSuccess('La consultation est bien ajouter !');
            }
            else if( data == '0' )
            {
                ShowAlertError('L\'ajout à de consultation à échoue !');
            }
            else
            {
                ShowAlertError('L\'ajout à de consultation à échoue !, vuillez respecter la validation du champs !');

                errors = JSON.parse(data);

                for (var err in errors)
                {
                    var el = document.getElementById(err);
                    el.parentNode.classList.add('has-error');
                    el.parentNode.lastElementChild.innerText = errors[err];
                };

                for (var err in errors)
                {
                    var el = document.getElementById(err);
                    el.parentNode.classList.add('has-error');
                    el.parentNode.lastElementChild.innerText = errors[err];
                };
            }
        },
        error : function(status)
        {
            console.log(status);
        },

        complete : function(resultat, statut)
        {
            $('#loaderAddConsultation').hide();
        }

    });
}

//function addMutuelle(){var e=document.getElementById("nomMutuelle");e.parentNode.classList.contains("has-error")&&(e.parentNode.classList.remove("has-error"),e.parentNode.lastElementChild.innerText=""),loaderBtn("btnAddMutuelle",'Chargement  &nbsp;<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');var t=$("#nomMutuelle").val();$.ajax({url:$("#FormAddMutuelle").attr("action"),type:"post",data:{nomMutuelle:t},success:function(n){if("2"==n)ShowAlertError("L'ajout de mutuelle à échoue, ce mutuel existe déjà !"),e.parentNode.classList.add("has-error"),e.parentNode.lastElementChild.innerText="Ce mutuel existe déjà !";else if("0"==n)ShowAlertError("L'ajout de mutuelle à échoue, le nom du mutuel est obligatoire !"),e.parentNode.classList.add("has-error"),e.parentNode.lastElementChild.innerText="Le nom du mutuel est obligatoire !";else{var s=JSON.parse(n);"nomMutuelle"in s?(e.parentNode.classList.add("has-error"),e.parentNode.lastElementChild.innerText=s.nomMutuelle,ShowAlertError("La modification a échoué, "+s.nomMutuelle)):(t=$("#nomMutuelle").val(""),document.getElementById("listeMutuel").innerHTML="",$.each(s,function(e,t){$("#listeMutuel").append($("<option>").text(t.libelle).attr("value",t.idMutuel))}),$("#modalAddMutuelle").modal("hide"),showAlertSuccess("Le mutuelle est bien Ajouter !"))}},error:function(e){console.log(e)},complete:function(e,t){loaderBtn("btnAddMutuelle",'<i class="fa fa-save"></i>&nbsp; Enregestrer')}})}function addMedicament(){var e=document.getElementById("designation"),t=document.getElementById("dosage");e.parentNode.classList.contains("has-error")&&(e.parentNode.classList.remove("has-error"),e.parentNode.lastElementChild.innerText=""),t.parentNode.classList.contains("has-error")&&(t.parentNode.classList.remove("has-error"),t.parentNode.lastElementChild.innerText=""),loaderBtn("btnAddMedicament",'Chargement  &nbsp;<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');var n=new FormData(document.querySelector("#FormAddMedicament"));$.ajax({url:$("#FormAddMedicament").attr("action"),type:"post",data:n,processData:!1,contentType:!1,success:function(n){if(console.log(n),"2"==n)ShowAlertError("L'ajout de medicament à échoue, ce medicament existe déjà !"),e.parentNode.classList.add("has-error"),e.parentNode.lastElementChild.innerText="Ce medicament existe déjà !";else if("0"==n)ShowAlertError("L'ajout de medicament à échoue, tout les champs sont obligatoire !"),e.parentNode.classList.add("has-error"),e.parentNode.lastElementChild.innerText="La designation du medicament est obligatoire !",t.parentNode.classList.add("has-error"),t.parentNode.lastElementChild.innerText="Le dosage de medicament est obligatoire !";else{var s=JSON.parse(n);"designation"in s?(e.parentNode.classList.add("has-error"),e.parentNode.lastElementChild.innerText=s.designation):"dosage"in s?(t.parentNode.classList.add("has-error"),t.parentNode.lastElementChild.innerText=s.dosage):(document.getElementById("medicamentOrdonnance").innerHTML="",$.each(s,function(e,t){$("#medicamentOrdonnance").append($("<option>").text(t.med).attr("value",t.med))}),$("#modalAddMedicament").modal("hide"),e.value="",t.value="",showAlertSuccess("Le Medicament est bien enregestrer !"))}},error:function(e){console.log(e)},complete:function(e,t){loaderBtn("btnAddMedicament",'<i class="fa fa-save"></i>&nbsp; Enregestrer')}})}function UpdateInfoPersonnelPatient(){$("#loaderUpdateInfoPersonnelPatient").show();for(var e=document.querySelectorAll("input,textarea"),t=0;t<e.length;t++)e[t].parentNode.classList.contains("has-error")&&(e[t].parentNode.classList.remove("has-error"),e[t].parentNode.lastElementChild.innerText="");var n=new FormData(document.querySelector("#FormUpdateInfoPersonnelPatient"));$.ajax({url:$("#FormUpdateInfoPersonnelPatient").attr("action"),type:"post",data:n,processData:!1,contentType:!1,success:function(e){if("0"==e)ShowAlertError("La modefication à échoue !");else if(-1==e){ShowAlertError("La modefication à échoue !, Ce numero de carte national existe déjà !");var t=document.getElementById("cinPatient");t.parentNode.classList.add("has-error"),t.parentNode.lastElementChild.innerText="Ce numero de carte national existe déjà !"}else{var n=JSON.parse(e);if("nomCmpltPatient"in n)$(".labelNomPatient").text(n.nomCmpltPatient),showAlertSuccess("Les informations est bien modifier");else{ShowAlertError("La modification a échoué tout les champs sont obligatoire !");for(var s in n)(r=document.getElementById(s)).parentNode.classList.add("has-error"),r.parentNode.lastElementChild.innerText=n[s];for(var s in n){var r=document.getElementById(s);r.parentNode.classList.add("has-error"),r.parentNode.lastElementChild.innerText=n[s]}}}},error:function(e){console.log(e)},complete:function(e){$("#loaderUpdateInfoPersonnelPatient").hide()}})}function UpdateInfoMedicalPatient(){$("#loaderUpdateInfoMedicalPatient").show();var e=new FormData(document.querySelector("#FormUpdateInfoMedicalPatient"));$.ajax({url:$("#FormUpdateInfoMedicalPatient").attr("action"),type:"post",data:e,processData:!1,contentType:!1,success:function(e){if("1"==e)showAlertSuccess("Les antécédents est enregestrer !");else if("0"==e)ShowAlertError("La modefication à échoue !");else{var t=JSON.parse(e);ShowAlertError("La modification a échoué !");for(var n in t)(s=document.getElementById(n)).parentNode.classList.add("has-error"),s.parentNode.lastElementChild.innerText=t[n];for(var n in t){var s=document.getElementById(n);s.parentNode.classList.add("has-error"),s.parentNode.lastElementChild.innerText=t[n]}}},error:function(e){console.log(e)},complete:function(e){$("#loaderUpdateInfoMedicalPatient").hide()}})}function getRendezVousDossier(){var e=$("#idDossier").val();$.ajax({url:"includes/functions/controller.php?action=getRdvsDossier&idDossier="+e,type:"GET",success:function(e){res2=JSON.parse(e),0==res2&&(res2=[]);var t=Object.keys(res2).length;$("#nbrRdv").text(t),$("#tblRdvsDossier").DataTable({data:res2,destroy:!0,fnRowCallback:function(e,t,n){this.fnSettings&&this.fnSettings();return $("td:last",e).html('<div class="btn-group"><button type="button" class="btn btn-info btn-flat"> Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="javascript:void(0)" onclick="showModalEditRdv('+t.idRdv+')" class="text-green"><i class="fa fa-edit"></i>Editer</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="deleteRdv('+t.idRdv+')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'),e},columns:[{data:"dateRdv",render:function(e,t,n){return'<span class="text-blue text-bold">'+moment(e,"YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm")+" </span>"}},{data:"libelle",render:function(e,t,n){return'<span class="badge-no-circle bg-green"> '+e+" </span>"}},{data:"dateRdv",render:function(e,t,n){var s=new Date(e).getTime()<(new Date).getTime(),r=new Date(e).getTime()>=(new Date).getTime(),o=!1,a=!1,i=!1;return"a"==n.Etat?o=!0:"d"==n.Etat?a=!0:"v"==n.Etat&&(i=!0),o&&s?'<span class="badge-no-circle bg-yellow"> Expirer </span>':o&&r?'<span class="badge-no-circle bg-blue"> En Attent</span>':a&&r?'<span class="badge-no-circle bg-red"> Annulé </span>':i?'<span class="badge-no-circle bg-green"> Validé </span>':s?'<span class="badge-no-circle bg-yellow"> Expirer </span>':void 0}},{data:"idRdv"}],language:{sProcessing:"Traitement en cours ...",sLengthMenu:"Afficher _MENU_ lignes",sZeroRecords:"Aucun résultat trouvé",sEmptyTable:"Aucune donnée disponible",sInfo:"Lignes _START_ à _END_ sur _TOTAL_",sInfoEmpty:"Aucune ligne affichée",sInfoFiltered:"(Filtrer un maximum de_MAX_)",sInfoPostFix:"",sSearch:"Chercher:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Chargement...",oPaginate:{sFirst:"Premier",sLast:"Dernier",sNext:"Suivant",sPrevious:"Précédent"},oAria:{sSortAscending:": Trier par ordre croissant",sSortDescending:": Trier par ordre décroissant"}}})},error:function(e){console.log(e)}})}function getPaiementDossier(){var e=$("#idDossier").val();$.ajax({url:"includes/functions/controller.php?action=getPaiementDossier&idDossier="+e,type:"GET",success:function(e){res2=JSON.parse(e),$("#tblPaiements").DataTable({data:res2,destroy:!0,fnRowCallback:function(e,t,n){this.fnSettings&&this.fnSettings();return $("td:last",e).html('<a class="btn btn-flat btn-danger btn-sm" href="javascript:void(0)" onclick="deletePaiement('+t.idPaiement+')" class="text-red"> <i class="fa fa-trash-o"></i>&nbsp; Supprimer</a>'),e},columns:[{data:"datePaiement",render:function(e,t,n){return'<span class="text-blue text-bold">'+moment(e,"YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm")}},{data:"montantPaye",render:function(e,t,n){return'<span class="badge bg-green"> '+e+" DH </span>"}},{data:"idPaiement"}],language:{sProcessing:"Traitement en cours ...",sLengthMenu:"Afficher _MENU_ lignes",sZeroRecords:"Aucun résultat trouvé",sEmptyTable:"Aucune donnée disponible",sInfo:"Lignes _START_ à _END_ sur _TOTAL_",sInfoEmpty:"Aucune ligne affichée",sInfoFiltered:"(Filtrer un maximum de_MAX_)",sInfoPostFix:"",sSearch:"Chercher:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Chargement...",oPaginate:{sFirst:"Premier",sLast:"Dernier",sNext:"Suivant",sPrevious:"Précédent"},oAria:{sSortAscending:": Trier par ordre croissant",sSortDescending:": Trier par ordre décroissant"}}})},error:function(e){console.log(e)}})}function getConsultations(){var e=$("#idDossier").val();$.ajax({url:"includes/functions/controller.php?action=getConsultationsDossier&idDossier="+e,type:"GET",success:function(e){res2=JSON.parse(e);var t=Object.keys(res2).length;$("#nbrCosultations").text(t),$("#tblConsultations").DataTable({data:res2,destroy:!0,fnRowCallback:function(e,t,n){this.fnSettings&&this.fnSettings();return $("td:last",e).html('<div class="btn-group"><button type="button" class="btn btn-info btn-flat">Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="editConsultation.php?idconsultation='+t.idConsultation+'" class="text-green"><i class="fa fa-edit"></i>Editer</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="deleteConsultation('+t.idConsultation+')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'),e},columns:[{data:"idConsultation"},{data:"dateDebut",render:function(e,t,n){return'<span class="text-blue">'+moment(e,"YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm")}},{data:"libelle",render:function(e,t,n){return'<span class="badge badge-no-circle bg-green bg-green"> '+e+"</span>"}},{data:"remarquesConsultation"},{data:"montantNetConsultation",render:function(e,t,n){return'<span class=""> '+e+" DH</span>"}},{data:"idConsultation"}],language:{sProcessing:"Traitement en cours ...",sLengthMenu:"Afficher _MENU_ lignes",sZeroRecords:"Aucun résultat trouvé",sEmptyTable:"Aucune donnée disponible",sInfo:"Lignes _START_ à _END_ sur _TOTAL_",sInfoEmpty:"Aucune ligne affichée",sInfoFiltered:"(Filtrer un maximum de_MAX_)",sInfoPostFix:"",sSearch:"Chercher:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Chargement...",oPaginate:{sFirst:"Premier",sLast:"Dernier",sNext:"Suivant",sPrevious:"Précédent"},oAria:{sSortAscending:": Trier par ordre croissant",sSortDescending:": Trier par ordre décroissant"}}})},error:function(e){console.log(e)}})}function getAutresDossiers(){var e=$("#idDossier").val(),t=$("#idPatientForAddDossier").val();$.ajax({url:"includes/functions/controller.php?action=getAutresDossiers&idDossier="+e+"&idPatient="+t,type:"GET",success:function(e){var t=JSON.parse(e);$("#tblDossiers").DataTable({data:t,destroy:!0,fnRowCallback:function(e,t,n){this.fnSettings&&this.fnSettings();return $("td:last",e).html('<a class="btn btn-info btn-sm btn-flat" href="dossierPatient.php?idDossier='+t.idDossier+'" ><i class="fa fa-eye"></i></a> <button class="btn btn-sm btn-danger btn-flat" onclick="deleteDossier('+t.idDossier+')"> <i class="fa fa-trash-o"></i></button>'),e},columns:[{data:"idDossier"},{data:"dateCreation",render:function(e,t,n){return'<span class="text-blue">'+moment(e,"YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm")}},{data:"patient"},{data:"montantDossier",render:function(e,t,n){return'<span class="badge badge-no-circle bg-green">'+e+" DH</span>"}},{data:"idDossier"}],language:{sProcessing:"Traitement en cours ...",sLengthMenu:"Afficher _MENU_ lignes",sZeroRecords:"Aucun résultat trouvé",sEmptyTable:"Aucune donnée disponible",sInfo:"Lignes _START_ à _END_ sur _TOTAL_",sInfoEmpty:"Aucune ligne affichée",sInfoFiltered:"(Filtrer un maximum de_MAX_)",sInfoPostFix:"",sSearch:"Chercher:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Chargement...",oPaginate:{sFirst:"Premier",sLast:"Dernier",sNext:"Suivant",sPrevious:"Précédent"},oAria:{sSortAscending:": Trier par ordre croissant",sSortDescending:": Trier par ordre décroissant"}}})},error:function(e,t,n){console.log(e)}})}function deleteDossier(e){swal.queue([{title:"Etes-vous sûr?",text:"voulez vous vraiment supprimer ce dossier !",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, Supprimer!",showLoaderOnConfirm:!0,preConfirm:function(){return new Promise(function(t){$.ajax({url:"includes/functions/controller.php?action=deleteDossier&idDossier="+e,type:"get",success:function(e){1==e?(getAutresDossiers(),swal("Good !","Le dossier vous est bien supprimer !","success").insertQueueStep(1),t()):(swal("Erreur !","Impossible de supprimer ce dossier !","error").insertQueueStep(1),t())},error:function(e){swal("Erreur !","Impossible de supprimer ce dossier !","error").insertQueueStep(1),t()}})})}}])}function desactiverRdv(e){swal.queue([{title:"Etes-vous sûr?",text:"voulez vous vraiment Desactivé Ce Rendez Vous!",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, Desactivé!",showLoaderOnConfirm:!0,preConfirm:function(){return new Promise(function(t){$.ajax({url:"includes/functions/controller.php?action=desactiveRdv&idRdv="+e,type:"get",success:function(e){1==e?(swal("Good !","Le Rendez Vous ESt Bien Désactivé !","success").insertQueueStep(1),t()):(swal("Erreur !","Impossible De Désactivé Ce Rendez Vous !","error").insertQueueStep(1),t())},error:function(e){swal("Erreur !","Impossible De Désactivé Ce Rendez Vous !","error").insertQueueStep(1),t()}})})}}])}function deleteConsultation(e){swal.queue([{title:"Etes-vous sûr?",text:"voulez vous vraiment supprimer cette consultation ?",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, Supprimer!",showLoaderOnConfirm:!0,preConfirm:function(){return new Promise(function(t){$.ajax({url:"includes/functions/controller.php?action=deleteConsultation&idConsultation="+e,type:"get",success:function(e){1==e?(setTimeout(function(){window.location.reload()},2e3),getConsultations(),swal("Good !","La consultation est bien supprimer !","success").insertQueueStep(1),t()):(swal("Erreur !","Impossible de supprimer cette consultation !","error").insertQueueStep(1),t())},error:function(e){swal("Erreur !","Impossible de supprimer cette consultation !","error").insertQueueStep(1),t()}})})}}])}function showModalEditRdv(e){$("#horairesDisponibleForEdit").slideUp();var t;$("#idDossier").val(),$("#matinEdit"),$("#soireEdit"),$("#nbrMinutes").val();$("#EtatRdv").show(),$("#valideRdv").show(),$.ajax({url:"includes/functions/controller.php?action=getRdv&idRdv="+e,type:"get",success:function(e){t=JSON.parse(e),$("#modalEditRdv .modal-title span").text(t.idRdv),$("#idRdv").val(t.idRdv),$("#typeRdvForEdit").val(t.typeRdv).trigger("change"),$("#modalEditRdv #dateEditRdv").val(t.dateRdv),$("#modalEditRdv #heureRdv").val(moment(t.dateRdv,"yyyy-mm-dd hh:mm:ss").format("HH:mm")),"d"===t.Etat?$("#modalEditRdv #desactiveRdv").attr("checked",!0):"a"===t.Etat?$("#modalEditRdv #activeRdv").attr("checked",!0):$("#modalEditRdv #valideRdv").attr("checked",!0);var n=new Date(t.dateRdv).getTime()<(new Date).getTime(),s=(new Date(t.dateRdv).getTime(),(new Date).getTime(),!1),r=!1;"a"==t.Etat?s=!0:"d"==t.Etat?r=!0:"v"==t.Etat&&(valider=!0),s&&n?$("#EtatRdv").hide():n?$("#EtatRdv").hide():r&&$("#valideRdv").hide(),$("#modalEditRdv").modal({backdrop:"static"})},error:function(e){console.log(e)}})}function updateRdvDossier(){loaderBtn("btnUpdateRdv",'Chargement  &nbsp;<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');var e=new FormData(document.querySelector("#FormUpdateRdv"));$.ajax({url:$("#FormUpdateRdv").attr("action"),type:"post",data:e,processData:!1,contentType:!1,success:function(e){"1"==e?($("#modalEditRdv").modal("hide"),showAlertSuccess("Le rendez vous est bien modifier !"),getRendezVousDossier()):"2"==e?ShowAlertError("La modification du rendez vous à échoue, vuillez choisie une date corréecte !"):ShowAlertError("La modification du rendez vous à échoue, La date de rendez vous est obligatoire !")},error:function(e){console.log(e)},complete:function(){loaderBtn("btnUpdateRdv",'<i class="fa fa-save"></i>&nbsp; Modifier'),$("#horairesDisponible").slideUp()}})}function addRdvDossier(){loaderBtn("btnAddRdv",'Chargement  &nbsp;<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');var e=$("#dateAddRdv").val(),t=$("input[name=heurRdv]:checked").val(),n=$("#typeRdv").val(),s=e+" "+t+":00",r=$("#idDossier").val();$.ajax({url:$("#FormAddRdv").attr("action"),type:"post",data:{dateRdv:s,idDossier:r,typeRdv:n},success:function(e){console.log(e),"1"==e?($("#btnNavRdv").click(),$("#modalAddRdv").modal("hide"),showAlertSuccess("Le rendez vous est bien Ajouter !"),getRendezVousDossier()):ShowAlertError("L'ajout de rendez vous à échoue, vuillez choisie une date corréecte !")},error:function(e){console.log(e)},complete:function(e,t){loaderBtn("btnAddRdv",'<i class="fa fa-save"></i>&nbsp; Enregestrer'),$("#horairesDisponible").slideUp()}})}function deleteRdv(e){swal.queue([{title:"Etes-vous sûr?",text:"voulez vous vraiment supprimer ce rendez vous !",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, Supprimer!",showLoaderOnConfirm:!0,preConfirm:function(){return new Promise(function(t){$.ajax({url:"includes/functions/controller.php?action=deleteRdvDossier&idRdv="+e,type:"get",success:function(e){1==e?(getRendezVousDossier(),swal("Good !","Le rendez vous est bien supprimer !","success").insertQueueStep(1),t()):(swal("Erreur !","Impossible de supprimer ce rendez vous !","error").insertQueueStep(1),t())},error:function(e){swal("Erreur !","Impossible de supprimer ce rendez vous !","error").insertQueueStep(1),t()}})})}}])}function getHorairesDisponible(){$("#loaderInput").show(),$("#horairesDisponible").slideUp(),$("#btnAddRdv").attr("disabled",!0);var e,t=$("#dateAddRdv").val(),n=$("#matin"),s=$("#soire"),r=$("#nbrMinutes").val();$.ajax({url:"includes/functions/controller.php?action=getHorairesDisponible&dateAddRdv="+t,type:"GET",success:function(o){$.ajax({url:"includes/functions/controller.php?action=getRdvsReserver&dateAddRdv="+t,type:"GET",success:function(t){if($("#btnAddRdv").attr("disabled",!1),0==t)if(e=[],0==o)res2=[];else{res2=JSON.parse(o),$("#horairesDisponible").slideDown(),$("#loaderInput").hide(),n.html(moment(res2.matinDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.matinFin,"hh:mm:ss").format("HH:mm")),s.html(moment(res2.soireDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.soireFin,"hh:mm:ss").format("HH:mm"));for(var a=moment(res2.matinDebut,"hh:mm:ss"),i=moment(res2.matinFin,"hh:mm:ss"),d=a.add(0,"minutes"),l="";d.isBefore(i);)l+='<div class="radio-inline"><label><input type="radio" id="heurRdv" name="heurRdv"value='+moment(d,"hh:mm:ss").format("HH:mm")+">"+moment(d,"hh:mm:ss").format("HH:mm")+"</label> </div>",d=a.add(r,"minutes");$("#colMatin").html(l);var m=moment(res2.soireDebut,"hh:mm:ss"),c=moment(res2.soireFin,"hh:mm:ss"),u=m.add(0,"minutes");for(l="";u.isBefore(c);)l+='<div style="margin-left: 10px; margin-bottom: 7px;" class="radio-inline"><label><input id="heurRdv" checked type="radio" name="heurRdv" value='+moment(u,"hh:mm:ss").format("HH:mm")+">"+moment(u,"hh:mm:ss").format("HH:mm")+"</label> </div>",u=m.add(r,"minutes");$("#colSoire").html(l)}else if(e=JSON.parse(t),0==o)res2=[];else{res2=JSON.parse(o),$("#horairesDisponible").slideDown(),$("#loaderInput").hide(),n.html(moment(res2.matinDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.matinFin,"hh:mm:ss").format("HH:mm")),s.html(moment(res2.soireDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.soireFin,"hh:mm:ss").format("HH:mm"));for(var a=moment(res2.matinDebut,"hh:mm:ss"),i=moment(res2.matinFin,"hh:mm:ss"),d=a.add(0,"minutes"),l="";d.isBefore(i);){for(var p=moment(d,"hh:mm:ss").format("HH:mm:ss"),f=!1,h=0;h<e.length;h++)p==e[h].dateI&&(f=!0);l+=f?'<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input id="heurRdv" disabled type="radio">'+moment(d,"hh:mm:ss").format("HH:mm")+"</label> </div>":'<div class="radio-inline"><label style="font-size: 15px;"><input id="heurRdv" checked type="radio" name="heurRdv" value='+moment(d,"hh:mm:ss").format("HH:mm")+">"+moment(d,"hh:mm:ss").format("HH:mm")+"</label> </div>",d=a.add(r,"minutes")}$("#colMatin").html(l);var m=moment(res2.soireDebut,"hh:mm:ss"),c=moment(res2.soireFin,"hh:mm:ss"),u=m.add(0,"minutes");for(l="";u.isBefore(c);){for(var p=moment(u,"hh:mm:ss").format("HH:mm:ss"),f=!1,h=0;h<e.length;h++)p==e[h].dateI&&(f=!0);l+=f?'<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input disabled type="radio" >'+moment(u,"hh:mm:ss").format("HH:mm")+"</label> </div>":'<div class="radio-inline"><label style="font-size: 15px;"><input type="radio" name="heurRdv" value='+moment(u,"hh:mm:ss").format("HH:mm")+" >"+moment(u,"hh:mm:ss").format("HH:mm")+"</label> </div>",u=m.add(r,"minutes")}$("#colSoire").html(l)}},error:function(e){console.log(e)}})},error:function(e){console.log(e),$("#loaderInput").hide()}})}function getHorairesDisponibleforEdit(){$("#loaderInputEditRdv").show(),$("#horairesDisponibleForEdit").slideUp(),$("#btnUpdateRdv").attr("disabled",!0);var e,t=$("#dateEditRdv").val(),n=$("#matinEdit"),s=$("#soireEdit"),r=$("#nbrMinutes").val();$.ajax({url:"includes/functions/controller.php?action=getHorairesDisponible&dateAddRdv="+t,type:"GET",success:function(o){$.ajax({url:"includes/functions/controller.php?action=getRdvsReserver&dateAddRdv="+t,type:"GET",success:function(t){if($("#btnUpdateRdv").attr("disabled",!1),0==t)if(e=[],0==o)res2=[];else{res2=JSON.parse(o),$("#horairesDisponibleForEdit").slideDown(),$("#loaderInputEditRdv").hide(),n.html(moment(res2.matinDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.matinFin,"hh:mm:ss").format("HH:mm")),s.html(moment(res2.soireDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.soireFin,"hh:mm:ss").format("HH:mm"));for(var a=moment(res2.matinDebut,"hh:mm:ss"),i=moment(res2.matinFin,"hh:mm:ss"),d=a.add(0,"minutes"),l="";d.isBefore(i);)l+='<div class="radio-inline"><label><input type="radio" id="heurRdv" name="heurRdv"value='+moment(d,"hh:mm:ss").format("HH:mm")+">"+moment(d,"hh:mm:ss").format("HH:mm")+"</label> </div>",d=a.add(r,"minutes");$("#colMatinEdit").html(l);var m=moment(res2.soireDebut,"hh:mm:ss"),c=moment(res2.soireFin,"hh:mm:ss"),u=m.add(0,"minutes");for(l="";u.isBefore(c);)l+='<div style="margin-left: 10px; margin-bottom: 7px;" class="radio-inline"><label><input id="heurRdv" checked type="radio" name="heurRdv" value='+moment(u,"hh:mm:ss").format("HH:mm")+">"+moment(u,"hh:mm:ss").format("HH:mm")+"</label> </div>",u=m.add(r,"minutes");$("#colSoireEdit").html(l)}else if(e=JSON.parse(t),0==o)res2=[];else{res2=JSON.parse(o),$("#horairesDisponibleForEdit").slideDown(),$("#loaderInputEditRdv").hide(),n.html(moment(res2.matinDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.matinFin,"hh:mm:ss").format("HH:mm")),s.html(moment(res2.soireDebut,"hh:mm:ss").format("HH:mm")+'&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;'+moment(res2.soireFin,"hh:mm:ss").format("HH:mm"));for(var a=moment(res2.matinDebut,"hh:mm:ss"),i=moment(res2.matinFin,"hh:mm:ss"),d=a.add(0,"minutes"),l="";d.isBefore(i);){for(var p=moment(d,"hh:mm:ss").format("HH:mm:ss"),f=!1,h=0;h<e.length;h++)p==e[h].dateI&&(f=!0);l+=f?'<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input id="heurRdv" disabled type="radio">'+moment(d,"hh:mm:ss").format("HH:mm")+"</label> </div>":'<div class="radio-inline"><label style="font-size: 15px;"><input id="heurRdv" checked type="radio" name="heurRdv" value='+moment(d,"hh:mm:ss").format("HH:mm")+">"+moment(d,"hh:mm:ss").format("HH:mm")+"</label> </div>",d=a.add(r,"minutes")}$("#colMatinEdit").html(l);var m=moment(res2.soireDebut,"hh:mm:ss"),c=moment(res2.soireFin,"hh:mm:ss"),u=m.add(0,"minutes");for(l="";u.isBefore(c);){for(var p=moment(u,"hh:mm:ss").format("HH:mm:ss"),f=!1,h=0;h<e.length;h++)p==e[h].dateI&&(f=!0);l+=f?'<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input disabled type="radio" >'+moment(u,"hh:mm:ss").format("HH:mm")+"</label> </div>":'<div class="radio-inline"><label style="font-size: 15px;"><input type="radio" name="heurRdv" value='+moment(u,"hh:mm:ss").format("HH:mm")+" >"+moment(u,"hh:mm:ss").format("HH:mm")+"</label> </div>",u=m.add(r,"minutes")}$("#colSoireEdit").html(l)}},error:function(e){console.log(e)}})},error:function(e){console.log(e),$("#loaderInputEditRdv").hide()}})}function addDossier(){swal.queue([{title:"Etes-vous sûr ?",text:"Voulez vous vraiment Ajouter Nouveau Dossier à Ce Patient ?",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, Ajouter!",showLoaderOnConfirm:!0,preConfirm:function(){return new Promise(function(e){var t=document.getElementById("idPatientForAddDossier").value;$.ajax({url:"includes/functions/controller.php?action=addDossier&idPatient="+t,type:"get",success:function(t){1==t?(getAutresDossiers(),swal("Good !","L' affectation du dissier est bien bien effectué !","success").insertQueueStep(1),e()):(swal("Erreur !","Impossible D'Ajouter Nouveau Dossiers à Ce Patient !","error").insertQueueStep(1),e())},error:function(t){swal("Erreur !","Impossible D'Ajouter Nouveau Dossiers à Ce Patient !","error").insertQueueStep(1),e()}})})}}])}function addPaiement(){document.getElementById("montantPayer").parentNode.parentNode.classList.contains("has-error")&&(document.getElementById("montantPayer").parentNode.parentNode.classList.remove("has-error"),document.getElementById("montantPayer").parentNode.parentNode.lastElementChild.innerText="");var montantPayer=$("#montantPayer").val(),rest=$("#rest").val();if(console.log("rest : "+rest+"  mPaye : "+montantPayer),isNaN(montantPayer))document.getElementById("montantPayer").parentNode.parentNode.classList.add("has-error"),document.getElementById("montantPayer").parentNode.parentNode.lastElementChild.innerText="Vuillez entrer un montant correct !";else if(eval(montantPayer)>eval(rest)||eval(montantPayer)<=0)document.getElementById("montantPayer").parentNode.parentNode.classList.add("has-error"),document.getElementById("montantPayer").parentNode.parentNode.lastElementChild.innerText="Vuillez entrer un montant correct !";else{loaderBtn("btnAddPaiement",'Chargement  &nbsp;<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');var fd=new FormData(document.querySelector("#FormAddPaiement"));$.ajax({url:$("#FormAddPaiement").attr("action"),type:"post",data:fd,processData:!1,contentType:!1,success:function(e){if("1"==e)setTimeout(function(){window.location.reload()},2e3),getPaiementDossier(),$("#btnNavPaiements").click(),$("#modalAddPaiement").modal("hide"),showAlertSuccess("Le paiement est bien jouter !");else if("0"==e)ShowAlertError("L'ajout à de paiment échoue !");else{ShowAlertError("L'ajout de paiement à échoué, vuillez respecter le format de paiements !"),errors=JSON.parse(e);for(var t in errors)(n=document.getElementById(t)).parentNode.classList.add("has-error"),n.parentNode.lastElementChild.innerText=errors[t];for(var t in errors){var n=document.getElementById(t);n.parentNode.classList.add("has-error"),n.parentNode.lastElementChild.innerText=errors[t]}}},error:function(e){console.log(e)},complete:function(e,t){loaderBtn("btnAddPaiement",'<i class="fa fa-save"></i>&nbsp; Enregestrer')}})}}function deletePaiement(e){swal.queue([{title:"Etes-vous sûr?",text:"voulez vous vraiment supprimer ce paiements !",type:"question",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, Supprimer!",showLoaderOnConfirm:!0,preConfirm:function(){return new Promise(function(t){$.ajax({url:"includes/functions/controller.php?action=deletePaiementDossier&idPaiement="+e,type:"get",success:function(e){1==e?(setTimeout(function(){window.location.reload()},2e3),getPaiementDossier(),swal("Good !","Le paiement est bien supprimer !","success").insertQueueStep(1),t()):(swal("Erreur !","Impossible de supprimer ce paiement !","error").insertQueueStep(1),t())},error:function(e){swal("Erreur !","Impossible de supprimer ce paiement !","error").insertQueueStep(1),t()}})})}}])}function addConsultation(){$("#loaderAddConsultation").show();var e=new FormData(document.querySelector("#FormAddConsultation"));$.ajax({url:$("#FormAddConsultation").attr("action"),type:"post",data:e,processData:!1,contentType:!1,success:function(e){if("1"==e)setTimeout(function(){window.location.reload()},2e3),getConsultations(),showAlertSuccess("La consultation est bien ajouter !");else if("0"==e)ShowAlertError("L'ajout à de consultation à échoue !");else{ShowAlertError("L'ajout à de consultation à échoue !, vuillez respecter la validation du champs !"),errors=JSON.parse(e);for(var t in errors)(n=document.getElementById(t)).parentNode.classList.add("has-error"),n.parentNode.lastElementChild.innerText=errors[t];for(var t in errors){var n=document.getElementById(t);n.parentNode.classList.add("has-error"),n.parentNode.lastElementChild.innerText=errors[t]}}},error:function(e){console.log(e)},complete:function(e,t){$("#loaderAddConsultation").hide()}})}$(document).ready(function(){$("#btnGenererOrdonnance").hide(),$("#tblTypes").dataTable(),$(".btn-collapse-note").click(function(){$("#"+$(this).attr("data-collapse-note")).slideToggle()});var e=1;$("#btnAddMedicamentOrdonnance").click(function(){var r=$("#medicamentOrdonnance").val(),o=$("#posologieMatin").val(),a=$("#posologieMidi").val(),i=$("#posologieSoire").val(),d=$("#duree").val(),l=$("#dureePeriod").val(),m='<div id="inputMedOrdonnance'+e+'" style="margin-bottom:10px" class="input-group" id="'+e+'" ><span class="input-group-addon"><input type="checkbox" checked ></span><input class="form-control medOrdonnance" style="width:30%" placeholder="Medicament..." type="text" value="'+r+'"><input class="form-control posologieOrdonnance" style="width:70%; border-left:0px" placeholder="Posologie..." type="text" value="'+(o+" - "+a+" - "+i+" par jour x "+d+" "+l)+'"><span class="input-group-btn"><button type="button" id="btnRemoveInputOrdonnance'+e+'" onclick="document.getElementById(\'btnRemoveInputOrdonnance'+e+"').parentNode.parentNode.parentNode.innerHTML='';if( medicamentImprimer != '' ){$('#btnGenererOrdonnance').slideDown();}else{$('#btnGenererOrdonnance').slideUp();}\" class=\"btn btn-danger btn-flat\" ><i class=\"fa fa-remove\"></i></button></span></div>",c=document.createElement("div");c.innerHTML=m,document.getElementById("ordonnance").appendChild(c),e++,t="",n="",s="";for(var u=$(".medOrdonnance"),p=$(".posologieOrdonnance"),f=0;f<u.length;f++)u[f].parentNode.firstChild.firstChild.checked&&(s+=u[f].value+"&"+p[f].value+"|"),t+=u[f].value+" "+p[f].value+"\n";$("#contenuOrdonnance").val(t),$("#medicamentOrdonnance").selectedIndex=0,$("#posologieMatin").val(""),$("#posologieMidi").val(""),$("#posologieSoire").val(""),$("#duree").val(""),""!=s?$("#btnGenererOrdonnance").slideDown():$("#btnGenererOrdonnance").slideUp(),""!=s?$("#btnGenererOrdonnance").slideDown():$("#btnGenererOrdonnance").slideUp()}),$("#btnEditer").attr("checked",!1),getRendezVousDossier(),getPaiementDossier(),getAutresDossiers(),getConsultations(),$("#btnAddDossier").click(function(){addDossier()});var t="",n="",s="";$("#btnAddMutuelle").click(function(){addMutuelle()}),$("#btnNewConsultation").click(function(){$("#btnReferAddConsultation").click()}),$("#btnAddPaiement").click(function(){addPaiement()}),$("#btnAddMedicament").click(function(){addMedicament()}),$("#btnAddConsultation").click(function(){addConsultation()}),$("#btnEditer").change(function(){if(this.checked)$("#cinPatient").attr("disabled",!1),$("#nomPatient").attr("disabled",!1),$("#prenomPatient").attr("disabled",!1),$("#dateNaissancePatient").attr("disabled",!1),$("#telephonePatient").attr("disabled",!1),$("#emailPatient").attr("disabled",!1),$("#adressePatient").attr("disabled",!1),$("#sexeHomme").attr("disabled",!1),$("#sexeFemme").attr("disabled",!1),$("#listeMutuel").attr("disabled",!1),$("#groupSanguin").attr("disabled",!1),$("#allergies").attr("disabled",!1),$("#antiacedentsPersonnels").attr("disabled",!1),$("#antiacedentsFamiliaux").attr("disabled",!1),$("#taille").attr("disabled",!1),$("#poids").attr("disabled",!1),$("#btnAddMutuel").attr("disabled",!1),$("#btnModifierInfoPersonnelles").attr("disabled",!1);else{for(var e=document.querySelectorAll("input,textarea"),t=0;t<e.length;t++)e[t].parentNode.classList.contains("has-error")&&(e[t].parentNode.classList.remove("has-error"),e[t].parentNode.lastElementChild.innerText="");$("#cinPatient").attr("disabled",!0),$("#nomPatient").attr("disabled",!0),$("#prenomPatient").attr("disabled",!0),$("#dateNaissancePatient").attr("disabled",!0),$("#telephonePatient").attr("disabled",!0),$("#emailPatient").attr("disabled",!0),$("#adressePatient").attr("disabled",!0),$("#sexeHomme").attr("disabled",!0),$("#sexeFemme").attr("disabled",!0),$("#listeMutuel").attr("disabled",!0),$("#groupSanguin").attr("disabled",!0),$("#allergies").attr("disabled",!0),$("#antiacedentsPersonnels").attr("disabled",!0),$("#antiacedentsFamiliaux").attr("disabled",!0),$("#taille").attr("disabled",!0),$("#poids").attr("disabled",!0),$("#btnAddMutuel").attr("disabled",!0),$("#btnModifierInfoPersonnelles").attr("disabled",!0)}})}),$("#btnModifierInfoPersonnelles").click(function(){UpdateInfoPersonnelPatient()}),$("#btnUpdateInfoMedicalPatient").click(function(){UpdateInfoMedicalPatient()}),$(document).ready(function(){$(document.body).on("hide.bs.modal,hidden.bs.modal",function(){$("body").css("padding-right","0 !important")}),$.fn.modal.Constructor.prototype.setScrollbar=function(){},$(".select2").select2({width:"100%"}),$("#btnAddRdv").attr("disabled","disabled"),$("#dateAddRdv").datepicker("setStartDate",new Date),$("#dateAddRdv").datepicker({format:"yyyy-mm-dd",autoclose:!0}),$("#loaderInput").hide(),$("#loaderInputEditRdv").hide(),$("#horairesDisponible").hide(),$("#horairesDisponibleForEdit").hide(),moment().format(),$("#dossiers").select2({width:"100%"}),$("#etatRdv").on("change",function(){$(this).is(":checked")?($("#etatAlis").text("Activé"),$("#etatAlis").removeClass("bg-red"),$("#etatAlis").addClass("bg-green")):($("#etatAlis").text("Désactivé"),$("#etatAlis").removeClass("bg-green"),$("#etatAlis").addClass("bg-red"))}),$("#btnUpdateRdv").click(function(){updateRdvDossier()}),$("#dateEditRdv").change(function(){getHorairesDisponibleforEdit()}),$("#dateAddRdv").change(function(){getHorairesDisponible()}),$("#btnAddRdv").click(function(){addRdvDossier()});var e="",t="",n=new jsPDF;$("#btnSaveOrdonnance").click(function(){n.save(s+".pdf")});var s="ordonnance",r="";$("#btnGenererOrdonnance").click(function(){n=new jsPDF;var o=$("#dateConsultation").val(),a=$("#nomPatientForOrdonnance").val(),i=$("#cinPatient").val();n.setFont("times"),n.setFontType("bold"),n.setFontSize(18),n.text(105,85,"Le : "+o,null,null,"center"),n.text(105,100,a,null,null,"center"),n.setFontSize(16),e="",t="",r="";for(var d=$(".medOrdonnance"),l=$(".posologieOrdonnance"),m=0;m<d.length;m++)d[m].parentNode.firstChild.firstChild.checked&&(r+=d[m].value+"&"+l[m].value+"|"),e+=d[m].value+"&"+l[m].value+"|";for(var c=r.split("|"),u=125,m=0;m<c.length-1;m++){var p=c[m].split("&");n.setFontType("normal"),n.text(30,u,m+1+" - "+p[0]),u+=10,n.text(50,u,p[1]),u+=15}o=o.replace(/ /g,""),a=a.replace(/ /g,""),s=i+a+o;var f=n.output("datauristring");$("#objViewOrdonnance").attr("data",f),$("#modalViewOrdonnance").modal("show")})});


</script>


<?php require_once "includes/templates/sousFooter.inc"; ?>
<!-- script src="layout/js/admins.js"></script -->