<?php
    if( ! isset($_GET['idDossier']) || empty($_GET['idDossier']) )
    {
        header('location:dossiers.php');
    }

    $title = 'Détails du dossier';
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idDossier        = $_GET['idDossier'];
    $sql              = 'SELECT * FROM dossier D, patient P WHERE idDossier = ? AND D.idPatient = P.idPatient';
    $dossier          = getData($sql, [ $idDossier ]);
    $mutuels          = getDatas("SELECT * FROM mutuel", []);
    $consultations    = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE idDossier = ?", [ $idDossier ]);
    $params           = getData("SELECT CONCAT(HOUR(dureeRdv), ' h ',MINUTE(dureeRdv), ' minutes') AS dureeRdv, (TIME_TO_SEC(dureeRdv) / 60) AS nbrMinutes FROM parametrage WHERE etat = 1", []);
    $rdvs             = getData("SELECT COUNT(*) AS nbrRdvs FROM rdv WHERE idDossier = ?", [ $idDossier ]);
    $typeRdv          = getDatas("SELECT * FROM typerdv", []);
    $nbrConsultations = $consultations['nbrConsultations'];
    $nbrRdv           = $rdvs['nbrRdvs'];
    $groupesSanguin   = [ 'A', 'B', 'O', 'AB', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-' ];

    if( !($dossier) && (empty($dossier['idDossier'])) )
    {
        header('location:dossiers.php');
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="alert alert-success alert-popup" id="alert-success">
        <h4><i class="icon fa fa-check-circle"></i>Good !</h4>
        <p></p>
    </div>

    <div class="alert alert-danger alert-popup" id="alert-error">
        <h4><i class="icon fa fa-remove"></i>Errors !</h4>
        <p></p>
    </div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Dossier patient : <?= $dossier['prenom'] . '  ' . $dossier['nom'] ?>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
            <button id="btnAddDossier" class="btn btn-primary btn-flat pull-right"> <i class="fa fa-plus-circle"></i> &nbsp; Ajouter nouveau dossier à ce patient</button>
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
                        <span>Numéro de dossier</span> <span class="badge bg-blue pull-right"><?= $dossier['idDossier'] ?></span> 
                        <hr style="margin-top: 6px; margin-bottom: 6px;">     
                        <span>Date de création </span> &nbsp; <span class="badge bg-blue pull-right"><?= $dossier['dateCreation'] ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">     
                        <span>Nombre de rendez vous</span> &nbsp; <span class="badge bg-blue pull-right"><?= $nbrRdv ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">     
                        <span>Nombre du consulations</span> &nbsp; <span class="badge bg-blue pull-right"><?= $nbrConsultations ?></span>
                        <hr style="margin-top: 6px; margin-bottom: 6px;">     
                        <span>Etat de paimenent</span> &nbsp; <span class="badge bg-red pull-right"><?= $nbrRdv ?></span>
                    </div>
                </div>
            <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary ">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-tags"></i>&nbsp; Navigations</h3>   
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#tab_informations" data-toggle="tab"><i class="fa fa-info"></i> Informations personnelles <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                    <li><a href="#tab_consultations" data-toggle="tab"><i class="fa fa-stethoscope"></i> Consulations <span class="pull-right"> <i class="fa fa-angle-right"></i></span> </a></li>
                    <li><a href="#tab_rdvs" data-toggle="tab"><i class="fa fa-calendar"></i> Rendez vous <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                    <li><a href="#tab_fichiers" data-toggle="tab"><i class="fa fa-file-text-o"></i> Fichiers et rapports <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                    <li><a href="#tab_paiements" data-toggle="tab"><i class="fa fa-money"></i> Paiemenets <span class="pull-right"> <i class="fa fa-angle-right"></i></span> </a></li>
                    <li><a href="#tab_dossiers" data-toggle="tab"><i class="fa fa-folder-open-o"></i> Autres dossiers <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                    </ul>
                </div>
            <!-- /.box-body -->
            </div>
                <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <i class="fa fa-cog"></i>&nbsp; Actions</h3>
                    </div>
                    <div class="box-body">
                        <button class="btn bg-blue btn-flat btn-block"><i class="fa fa-stethoscope"></i>&nbsp; Ajouter nouveau consultation </button>
                        <button data-toggle="modal" data-target="#modalAddRdv" class="btn btn-primary btn-flat btn-block" > <i class="fa fa-calendar-plus-o"></i> &nbsp; Ajouter nouveau rendez vous</button>
                        <button class="btn bg-orange btn-flat btn-block"><i class="fa fa-folder-o"></i>&nbsp; Ajouter nouveau dossier </button>
                        <button class="btn bg-purple btn-flat btn-block"><i class="fa fa-money"></i>&nbsp; Ajouter nouveau paiement </button>
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
                                <h3 class="box-title"> <i class="fa fa-info-circle"></i>&nbsp; Informations du patient : <span class="text-bold"><?= $dossier['prenom'] . '  ' . $dossier['nom'] ?></span> </h3>
                                <div class="box-tools">
                                    <label class="checkbox-inline" style="top: 2px;">
                                        <input type="checkbox" id="btnEditer" style="bottom: 2px;" >Editer
                                    </label>
                                </div>
                            </div>
                            <div class="box-body">
                            <form action="includes/functions/controller.php?action=UpdatePatient" id="FormUpdatePatient" >
                                <input type="hidden" name="idPatient" value="<?= $dossier['idPatient'] ?>">
                                <div class="note note-primary">
                                    <h4>Informations personnelles</h4>
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">        
                                        <div class="form-group">
                                            <label for="cinPatient" class="control-label">
                                                Numéro du carte nationnal <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="cinPatient" id="cinPatient" disabled="true" value="<?= $dossier['cin'] ?>" placeholder="Numero de carte nationnal..." disabled >
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
                                    <select name="mutuel" id="mutuel" class="form-control select2" disabled>
                                        <?php foreach ($mutuels as $mutuel): ?>
                                            <option value="<?= $mutuel['idMutuel'] ?>" <?php if ($mutuel['idMutuel'] == $dossier['mutuel']) echo 'selected' ?> > <?= $mutuel['libelle'] ?> </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-1" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label for=""></label>
                                        <button type="button" id="btnAddMutuel" disabled class="btn btn-block btn-flat btn-primary" style="margin-top: 5px;" ><i class="ion ion-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="adressePatient" class="control-label">
                                            Adresse <span class="required">*</span>
                                        </label>
                                        <textarea name="adressePatient" class="form-control" id="adressePatient" placeholder="Adresse..." disabled cols="20" rows="4"><?= $dossier['adresse'] ?></textarea>
                                        <span class="help-block"></span>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="note note-primary">
                                <h4>Informations supplémentaire</h4>
                            </div> 
                            <div class="form-group">
                                <label for="groupSanguin" class="control-label">Groupe sanguin</label>
                                <select name="groupSanguin" id="groupSanguin" disabled class="form-control select2">
                                    <?php for ($i=0; $i < count($groupesSanguin); $i++): ?>
                                        <option value="<?= $groupesSanguin[$i] ?>" <?php if ($groupesSanguin[$i] == $dossier['groupeSanguin']) echo 'selected' ?> > <?= $groupesSanguin[$i] ?> </option>
                                    <?php endfor ?>         
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="allergies" class="control-label">Les allergies</label>
                                <textarea name="allergies" placeholder="Entrez les allergies du patient..." disabled class="form-control" id="allergies" cols="30" rows="4"><?= $dossier['allergies'] ?></textarea>
                                <span class="help-block"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="antiacedentsPersonnels" class="control-label">Les antécédents personnels</label>
                                <textarea name="antiacedentsPersonnels" placeholder="Entrez les antécédents personnels du patient..." disabled class="form-control" id="antiacedentsPersonnels" cols="30" rows="4"><?= $dossier['antecedentsPersonnel'] ?></textarea>
                                <span class="help-block"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="antiacedentsFamiliaux" class="control-label">Les antécédents familiaux</label>
                                <textarea name="antiacedentsFamiliaux" placeholder="Entrez les antécédents familiaux du patient..." disabled class="form-control" id="antiacedentsFamiliaux" cols="30" rows="4"><?= $dossier['antecedentsFamiliaux'] ?></textarea>
                                <span class="help-block"></span>
                            </div>
                            <hr>  
                            <button type="button" class="btn btn-success btn-flat" id="btnModifierInfoPersonnelles" disabled > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                            </form>
                            </div>
                            <div class="overlay" id="loaderUpdatePatient" style="display:none">
                                <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_consultations">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="ion ion-folder-outline"></i>&nbsp; Dossier du patient : <span class="text-white">Mouad ZIANI</span> </h3>
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
                                            <th>ID Consultation</th>
                                            <th>ID Rendez Vous</th>
                                            <th>Date Début</th>
                                            <th>Date Fin</th>
                                            <th>Motif</th>
                                            <th>Montant Net</th>
                                            <th>Action</th>
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
                                <h3 class="box-title"> <i class="fa fa-calendar"></i>&nbsp; Liste des rendez vous du patient : <span class="text-bold"><?= $dossier['prenom'] . '  ' . $dossier['nom'] ?></span> </h3>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="removable"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                <table id="tblRdvsDossier" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:145px !important;">Numero du rendez vous</th>
                                            <th>Date rendez vous </th>
                                            <th>Etat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Start Modal Add Rdv -->
                    <div id="modalAddRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form  action="includes/functions/controller.php?action=addRdv" id="FormAddRdv">
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
                                                        <select name="typeRdv" id="typeRdv" class="form-control select2">
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
                                                    <label for="typeRdv" class="control-label">Type de rendez vous </label>
                                                    <div class="form-group">
                                                        <select name="typeRdv" id="typeRdv" class="form-control select2">
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
                                    <h4 class="modal-title"> <i class="fa fa-info-o"></i> &nbsp;Information du rendez vous N : &nbsp;<span></span> </h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered" id="table-view" style="margin-bottom:0px !important">
                                    <tr>
                                        <td style="width: 125px !important; max-width: 200px;">
                                            <b>Numéro de dossier</b>
                                        </td>
                                        <td id="viewIdDossier"> 12 </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Numéro de rendez vous</b>
                                        </td>
                                        <td id="viewIdRdv"> 25 </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Nom et prenom du patient</b>
                                        </td>
                                        <td id="nomPatient"><a></a></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 125px !important; max-width: 200px;">
                                            <b>Numéro de telephone </b>
                                        </td>
                                        <td id="viewNumTele"> 12 </td>
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
                                        <td id="viewIdRdv"> 25 </td>
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
                    <div class="tab-pane" id="tab_fichiers">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="ion ion-folder-outline"></i>&nbsp; Dossier du patient : <span class="text-white">Mouad ZIANI</span> </h3>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="removable"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <input id="idDossier" value="" type="hidden">
                                <table id="tblConsultations" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID Consultation</th>
                                            <th>ID Rendez Vous</th>
                                            <th>Date Début</th>
                                            <th>Date Fin</th>
                                            <th>Motif</th>
                                            <th>Montant Net</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_paiements">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="ion ion-folder-outline"></i>&nbsp; Dossier du patient : <span class="text-white">Mouad ZIANI</span> </h3>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="removable"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="tblPaiements" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Numero de paiement </th>
                                            <th>Date paiement</th>
                                            <th>Montant à payer</th>
                                            <th>Motif</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_dossiers">
                        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="ion ion-folder-outline"></i>&nbsp; Dossier du patient : <span class="text-white">Mouad ZIANI</span> </h3>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="removable"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                Autres dossiers
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
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

<script>

    $(document).ready(function()
    {
        getRendezVousDossier();

        $('#btnAddDossier').click(function()
        {
            addDossier();
        })
        
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
                $('#mutuel').attr('disabled', false);
                $('#groupSanguin').attr('disabled', false);
                $('#allergies').attr('disabled', false);
                $('#antiacedentsPersonnels').attr('disabled', false);
                $('#antiacedentsFamiliaux').attr('disabled', false);
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
                $('#mutuel').attr('disabled', true);
                $('#groupSanguin').attr('disabled', true);
                $('#allergies').attr('disabled', true);
                $('#antiacedentsPersonnels').attr('disabled', true);
                $('#antiacedentsFamiliaux').attr('disabled', true);
                $('#btnAddMutuel').attr('disabled', true);
                $('#btnModifierInfoPersonnelles').attr('disabled', true);
            }
        });
    });

$('#btnModifierInfoPersonnelles').click(function() 
{
    UpdatePatient();
});

function UpdatePatient()
{
    var errors = [];

    $('#loaderUpdatePatient').show();

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

    var fd = new FormData(document.querySelector('#FormUpdatePatient'));

    $.ajax({

        url         :$('#FormUpdatePatient').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            //console.log(data);
            if( data == '1' )
            {
                $('#loaderUpdatePatient').hide();
                showAlertSuccess('Les informations est bien modifier');
            }
            else if( data == '0' )
            {
                $('#loaderUpdatePatient').hide();
                ShowAlertError("La modefication à échoue !");
            }
            else
            {
                $('#loaderUpdatePatient').hide();
                ShowAlertError("La modification a échoué tout les champs sont obligatoire !");
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
        complete : function(status)
        {
            $('#loaderUpdatePatient').hide();
        } 

    });
}


// Fonctionnes des rendez vous :
// get all rdvs of folder :
function getRendezVousDossier()
{
    //var idDossier = $('#idDossier').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getRdvsDossier&idDossier=3',

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            if( res == '0' )
            {
                location.reload();
            }
            else
            {
                $('#tblRdvsDossier').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<div class="btn-group"><button type="button" class="btn btn-info btn-flat">Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="javascript:void(0)" onclick="showModalEditRdv(' + aData.idRdv + ')" class="text-green"><i class="fa fa-edit"></i>Editer</a></li><li><a href="javascript:void(0)" onclick="viewInfoRdv(' + aData.idRdv + ')" class="text-blue"><i class="fa fa-eye"></i>Voire les détails</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="deleteRdv(' + aData.idRdv + ')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'
                        )

                        return nRow;
                    },

                    "columns":
                    [
                        { "data": "idRdv" },
                        {
                            "data": "dateRdv",
                             "render": function(data, type, row)
                            {
                                return '<span class="badge bg-blue"> ' + data + ' </span>';
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
                        { "data": "idRdv" }
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
            }
        },

        error : function(err){
            console.log(err);
        }

    });
}

$(document).ready(function () 
{
    $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
    {
        $('body').css('padding-right','0 !important');
    });

    $.fn.modal.Constructor.prototype.setScrollbar = function () { };

    $('.select2').select2(); 

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


function showModalEditRdv(idRdv) 
{
    $('#horairesDisponibleForEdit').slideUp();
    var idDossier   = $('#idDossier').val();
    var rdv;
    var matin       = $('#matinEdit');
    var soire       = $('#soireEdit');
    var dureeRdv    = $('#nbrMinutes').val();
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
    var dateAddRdv = dateRdv + ' ' + heureRdv + ':00' ;

    var idDossier = $('#idDossier').val();

    $.ajax({

        url         :$('#FormAddRdv').attr('action'),
        type        : 'post',
        data        : {dateRdv : dateAddRdv, idDossier : idDossier},
        success     : function(data)
        {
            if( data == '1' )
            {
                $("#modalAddRdv").modal('hide')
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


function viewInfoRdv(idRdv) 
{
    var rdv;
    var rdvReserver;

    $.ajax({

        url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,

        type : 'get',

        success : function (data)
        {
            rdv = JSON.parse(data);

            $('#modalViewRdv .modal-title span').text( rdv.idRdv );
            $('#modalViewRdv #viewIdDossier').text( rdv.idDossier );
            $('#modalViewRdv #viewIdRdv').text( rdv.idRdv );
            $('#modalViewRdv #nomPatient a').attr( 'href', 'http://localhost/sitecabinetDentaire/admin/viewPatient.php?idPatient=' + rdv.idPatient );
            $('#modalViewRdv #nomPatient a').text( rdv.prenom + '  ' + rdv.nom );
            $('#modalViewRdv #viewNumTele').text( rdv.tel );
            $('#modalViewRdv #viewDateRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("dddd-MMMM-YYYY"));
            $('#modalViewRdv #viewHeureRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm"));

            var dateSystem = new Date().getTime();
            var dateRdv    = new Date(rdv.dateRdv).getTime();
            var expirer    = dateRdv < dateSystem;
            var enAttent   = dateRdv >= dateSystem;
            var active     = false;
            var annule     = false;
            var valider    = false;

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
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-yellow"> Expirer </span>');
            }
            else if( active && enAttent )
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-blue"> En Attent</span>');
            }
            else if( annule && enAttent)
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-red"> Annulé </span>');
            }
            else if( valider  )
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-green"> Validé </span>');
            }
            else if( expirer )
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-yellow"> Expirer </span>');
            }

            $("#modalViewRdv").modal({backdrop: "static"});
        },
        error : function (status)
        {
            console.log(status);
        }

    });
} 


/* Dossier fonctions */
function addDossier()
{
    swal.queue
    ([{
        title: 'Etes-vous sûr ?',
        text: "Voulez vous vraiment Ajouter Nouveau Dossier à Ce Patient ?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Ajouter!',
        showLoaderOnConfirm: true,
        preConfirm: function () 
        {
            return new Promise(function (resolve)
            {
                var idPatient = document.getElementById('idPatientForAddDossier').value;

                $.ajax({

                    url  : 'includes/functions/controller.php?action=addDossier&idPatient=' + idPatient,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            swal( 'Good !', 'L\' affectation du dissier est bien bien effectué !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible D\'Ajouter Nouveau Dossiers à Ce Patient !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible D\'Ajouter Nouveau Dossiers à Ce Patient !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}


</script>


<?php require_once "includes/templates/sousFooter.inc"; ?>
<!-- script src="layout/js/admins.js"></script -->