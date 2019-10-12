<?php

    if( !isset($_GET['idDossier']) || empty($_GET['idDossier']) || !is_numeric($_GET['idDossier']) )
    {
        header('location:patients.php');
    }

    $idDossier = $_GET['idDossier'];

    $title      = 'Rendez Vous';
    $link       = 'rendezVous';
    $subLink    = 'rendezVous';
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $patient = getData('SELECT P.* FROM patient P, dossier D WHERE D.idDossier = ? AND D.idPatient = P.idPatient', [ $idDossier ]);
    $rdvs   = getDatas("SELECT R.*, P.* FROM rdv R, Patient P, Dossier D WHERE R.idDossier = ? AND R.idDossier = D.idDossier AND D.idPatient = P.idPatient", [ $idDossier ]);
    $params = getData("SELECT CONCAT(HOUR(dureeRdv), ' h ',MINUTE(dureeRdv), ' minutes') AS dureeRdv, (TIME_TO_SEC(dureeRdv) / 60) AS nbrMinutes FROM parametrage WHERE etat = 1", []);
    include_once "includes/templates/aside.inc";

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
            Rendez vous Du Patient
            <small>it all starts here</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <button data-toggle="modal" data-target="#modalAddRdv" class="btn btn-primary btn-flat" > <i class="fa fa-calendar-plus-o"></i> &nbsp; Ajouter Nouveau Rendez Vous à Ce Dossier </button>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title" style="margin-top: 7px;"> <i class="fa fa-calendar"></i> &nbsp; Liste des rendez vous du patient : <span class="text-green"> <?= $patient['prenom'] . '  ' . $patient['nom']  ?> </span></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" id="btnRefreshRdvPatient" ><i class="fa fa-refresh"></i>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <br>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
                <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                <table id="tblRdvsDossier" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Rendez Vous</th>
                            <th>Date Rendez Vous </th>
                            <th>Etat</th>
                            <th style="min-width: 107px;">Action</th>
                        </tr>
                    </thead>
                </table>


                <!-- Start Modal Add Rdv -->
                <div id="modalAddRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <form  action="includes/functions/controller.php?action=addRdv" id="FormAddRdv">
                                <div class="modal-header bg-purple">
                                    <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                    <input name="nbrMinutes" id="nbrMinutes" value="<?= $params['nbrMinutes'] ?>" type="hidden">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><i class="fa fa-calendar-plus-o "></i>&nbsp; Affecter nouveau rendez vous &nbsp;<span></span> </h4>
                                </div>
                                <div class="modal-body">
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-9">
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
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-6">
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
                                <h4 class="modal-title">Information du rendez vous N : &nbsp;<span></span> </h4>
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
                                        <b>Etat du rendez vous</b>
                                    </td>
                                    <td id="viewEtatRdv"></td>
                                </tr>
                            </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fermer</button>
                                <button type="button" id="btnUpdateRdv" class="btn btn-flat btn-primary" > <i class="fa fa-print"></i>&nbsp; Imprimer </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal View Rdv -->

            </div>
         </div>
            <!-- /.box-body -->
    </section>
    <!-- /.content -->
</div>

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script src="layout/js/select2.min.js"></script>
<script src="layout/js/rendezVousPatient.js"></script>
<?php require_once "includes/templates/sousFooter.inc"; ?>

<!-- // etats de rendez vous : 
    ==>  Annuler
    ==>  Activeé
    ==>  Expirer
    ==>  En attent -->