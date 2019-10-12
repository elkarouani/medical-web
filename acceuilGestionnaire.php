<?php
    $title      = 'Acceuil';
    $link       = 'acceuil';
    $subLink    = 'acceuil';
    $acceUser = ['gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";
    $patients = getDatas('SELECT * FROM patient', []);
    $types    = getDatas("SELECT * FROM typerdv", []);
    $params   = getData("SELECT CONCAT(HOUR(dureeRdv), ' h ',MINUTE(dureeRdv), ' minutes') AS dureeRdv, (TIME_TO_SEC(dureeRdv) / 60) AS nbrMinutes FROM parametrage WHERE etat = 1", []);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Acceuil
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="paiementsGestionnaire.php" class="btn bg-red pull-right" style="margin-left:5px"> <i class="fa fa-money"></i>Nouv.Paiement</a>
                <a href="rendezVous.php" class="btn bg-aqua pull-right" style="margin-left:5px"> <i class="fa fa-calendar-plus-o"></i>Nouv.Rendez vous</a>
                <a href="patients.php" class="btn btn-primary pull-right"> <i class="fa fa-user-plus"></i>Nouv.Patient</a>
            </li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="currentDate bg-blue" style="margin-bottom: 20px">
                    <h2 class="text-center" style="margin-top: 0px; margin-bottom: 0px;padding: 15px 5px;text-shadow: 0 1px 0 rgba(0,0,0,0.2);"></h2>
                    <h4 class="text-center" style="padding: 0px 5px 15px;text-shadow: 0 1px 0 rgba(0,0,0,0.2);"></h4>
                </div>
                <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary ">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-tags"></i>&nbsp; Etats d'aujourd'hui</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active"><a href="#tab_salleAttente" data-toggle="tab"><i class="fa fa-clock-o"></i> Salle d'attente <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                            <li><a href="#tab_rdvs" data-toggle="tab"><i class="ion ion-medkit"></i> Rendez vous d'aujourd'hui <span class="pull-right"> <i class="fa fa-angle-right"></i></span></a></li>
                            <li><a href="#tab_consultations" data-toggle="tab"><i class="fa fa-stethoscope"></i> Consulations et controles <span class="pull-right"> <i class="fa fa-angle-right"></i></span> </a></li>
                            <li><a href="#tab_paiements" id="btnNavPaiements" data-toggle="tab"><i class="fa fa-money"></i> Paiemenets <span class="pull-right"> <i class="fa fa-angle-right"></i></span> </a></li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <i class="fa fa-cog"></i>&nbsp; A faire</h3>
                    </div>
                    <div class="box-body">
                        <button data-toggle="modal" data-target="#modalAddRdvSalleAttente" class="btn bg-green bg-flat btn-block" > <i class="fa fa-calendar-check-o"></i> &nbsp;Valider un rendez vous</button>
                        <button data-toggle="modal" data-target="#modalAddPatientSalleAttente" class="btn bg-green btn-flat btn-block" > <i class="ion ion-person-add"></i> &nbsp;Ajouter patient au salle d'attente</button>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-9">

                <div class="nav-tabs-custom">
                    <div class="tab-content" style="padding:0px !important">
                        <div class="tab-pane active" id="tab_salleAttente">
                            <div class="box box-solid box-primary" style="border-radius: 0px">
                                <div class="box-header">
                                    <h3 class="box-title"> <i class="fa fa-clock-o"></i>&nbsp; Salle d'attente </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header table-responsive -->
                                <div class="box-body">
                                    <table id="tbSalledAttente" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>H.Arrivée</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Etat</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_rdvs">
                            <div class="box box-solid box-primary" style="border-radius: 0px">
                                <div class="box-header">
                                    <h3 class="box-title"> <i class="fa fa-calendar"></i>&nbsp; Les rendez vous d'aujourd'hui </h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnRefreshAdmins" ><i class="fa fa-refresh"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.box-header table-responsive -->
                                <div class="box-body">
                                    <table id="tblRdvToday" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>H.Rdv</th>
                                            <th>Etat</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
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
                                    <table id="tblConsultations" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>Heur</th>
                                            <th>Type</th>
                                            <th>Motif</th>
                                            <th>Montant</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_paiements">
                            <div style="border-radius: 0px !important; " class="box box-solid box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"> <i class="fa fa-calendar"></i>&nbsp; Liste des paiements effectuer aujourd'hui </h3>
                                </div>
                                <div class="box-body">
                                    <div class="box-body table-responsive">
                                        <table id="tblPaiements" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Heur</th>
                                                <th>Montant à payer</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Start Modal Edit Rdv -->
    <div id="modalEditRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form  action="includes/functions/controller.php?action=updateRdvDossier" methode="post" id="FormUpdateRdv">
                    <div class="modal-header bg-purple">
                        <input name="idRdv" id="idRdv" type="hidden" value="">
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
                                        <span style="font-size: 15px; display: none;" id="loaderInputEditRdv" class="fa fa-spinner fa-spin form-control-feedback"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label for="typeRdvForEdit" class="control-label">Type de rendez vous </label>
                                    <div class="form-group">
                                        <select name="typeRdv" id="typeRdvForEdit" class="form-control select2">
                                            <?php foreach( $types as $type ): ?>
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

    <!-- Start Modal Add Mutuelle -->
    <div id="modalAddRdvSalleAttente" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form  action="includes/functions/controller.php?action=addRdvSalleAttente" id="FormAddRdvSalleAttente" methode="post">
                    <div class="modal-header bg-purple">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-address-book-o"></i>&nbsp; Valider un rendez vous / Ajouter au salle d'attente </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nomMutuelle" class="control-label"> Choisie un rendez vous <span class="required">*</span>
                            </label>
                            <select name="idRdv" id="idRdv" class="select2 form-control"></select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                        <button type="button" id="btnValidRdv" class="btn btn-flat btn-success" > <i class="fa fa-check"></i>&nbsp; Valider </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Add Mutuelle -->

    <!-- Start Modal Add Mutuelle -->
    <div id="modalAddPatientSalleAttente" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-address-book-o"></i>&nbsp; Ajouter un patient au salle d'attente </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="idPatient" class="control-label">Choisie un patient</label>
                        <select name="patient" class="form-control select2" id="idPatient">
                            <option value="0" selected="" disabled=""> --  vuillez choisie un patient  -- </option>
                            <?php foreach( $patients as $patient ): ?>
                                <option value="<?= $patient['idPatient'] ?>"> <?php if( $patient['cin'] != '' ) echo '[ ' . $patient['cin'] . ' ] ' . $patient['prenom'] . '  ' . $patient['nom']; else echo $patient['prenom'] . '  ' . $patient['nom']; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idDossierModal" class="control-label">Choisie un dossier </label>
                        <div class="form-group has-feedback">
                            <select id="idDossier" class="form-control select2">
                                <option value="0" selected="" disabled=""> --  vuillez choisie un dossier  -- </option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="idDossierModal" class="control-label">Choisie le type de consultation </label>
                        <div class="form-group has-feedback">
                            <select id="typeRdv" class="form-control select2">
                                <?php foreach( $types as $type ): ?>
                                    <option value="<?= $type['idType'] ?>"> <?= $type['libelle'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                    <button type="button" id="btnAddPatientSalleAttent" class="btn btn-flat btn-success" > <i class="fa fa-check"></i>&nbsp; Valider </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Add Mutuelle -->

</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script>

$(document).ready(function (){

    $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
    {
        $('body').css('padding-right','0 !important');
    });

    $.fn.modal.Constructor.prototype.setScrollbar = function () { };

    $('.select2').select2({ "width": "100%" });

    getRdvToday();
    getSalledAttente();
    getRdvValide();
    getConsultations();
    getPaiementToday();

    $('#btnValidRdv').click( function()
    {
        validerRdv();
    });

    $('#btnAddPatientSalleAttent').click( function()
    {
        addPatientSalleAttent();
    });

    $('#btnUpdateRdv').click(function(){
        updateRdvDossier();
    });

    $('#dateEditRdv').change(function(){
        getHorairesDisponibleforEdit();
    });

});

function deleteRdv(idRdv) {

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
                            getRdvToday();
                            getEtatsAcceuilAdmin();
                            getValideRdv();
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

function getConsultations()
{
    $.ajax({

        url : 'includes/functions/controller.php?action=getConsultationsToday',

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            $('#tblConsultations').DataTable({

                data : res2,

                destroy: true,

                "columns":
                    [
                        { "data": "patient" },
                        {
                            "data": "dateConsultation",
                            "render": function(data, type, row)
                            {
                                return '<span class="text-blue">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("hh:mm"); + ' </span>';
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
                        }
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

function getPaiementToday()
{
    $.ajax({

        url : 'includes/functions/controller.php?action=getPaiementToday',

        type : 'GET',

        success : function(res)
        {
            console.log(res);
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
                        { "data": "patient" },
                        {
                            "data": "datePaiement",
                            "render": function(data, type, row)
                            {
                                return '<span class="text-blue text-bold">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("hh:mm"); + ' </span>';
                            }
                        },
                        {
                            "data": "montantPaye",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge-no-circle bg-green"> ' + data + ' DH </span>';
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
                            getPaiementToday();
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
                getRdvToday();
                getSalledAttente();
                getRdvValide();
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

function updateClock() {
    var now = new Date(),
        months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    time = now.getHours() + ':' + now.getMinutes(),
        date = [now.getDate(),
            months[now.getMonth()],
            now.getFullYear()].join(' ');

    $('.currentDate h2').html(date);
    $('.currentDate h4').html(time);

    setTimeout(updateClock, 1000);
}
updateClock();


// get rdv of all today :
function getRdvToday()
{
    $.ajax({

        url : 'includes/functions/controller.php?action=getRdvAujourdhui',

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            $('#tblRdvToday').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html
                    (
                        '<div class="btn-group"><button type="button" class="btn btn-info btn-flat"> Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li class="divider"></li><li><a href="javascript:void(0)" onclick="showModalEditRdv(' + aData.idRdv + ')" class="text-blue"><i class="fa fa-edit"></i>Editer</a></li><li><a href="javascript:void(0)" onclick="deleteRdv(' + aData.idRdv + ')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'
                    );

                    return nRow;
                },

                "columns":
                [
                    { "data": "patient" },
                    {
                        "data": "heurRdv",

                        "render": function(data, type, row)
                        {
                            return "<span class='text-green'> " + moment(data, "HH:mm:ss").format("hh : mm") + " </span>";
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
                    {
                        "data": "typeRdv",

                        "render": function(data, type, row)
                        {
                            return "<span class='badge-no-circle bg-blue'> " + data + " </span>";
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
        },

        error : function(resultat, statut, erreur){
            console.log(resultat);
        }

    });
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

    $.ajax({

        url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,

        type : 'get',

        success : function (data)
        {
            rdv = JSON.parse(data);

            $('#modalEditRdv .modal-title span').text( rdv.idRdv );
            $('#modalEditRdv #idRdv').val(rdv.idRdv);
            $("#modalEditRdv #typeRdvForEdit").val(rdv.typeRdv).trigger("change");
            $('#modalEditRdv #dateEditRdv').val( rdv.dateRdv );
            $('#modalEditRdv #heureRdv').val( moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm") );

            if( rdv.Etat == 'd')
            {
                $('#modalEditRdv #desactiveRdv').attr( "checked", true );
            }
            else if( rdv.Etat == 'a' )
            {
                $('#modalEditRdv #activeRdv').attr( "checked", true );
            }
            else
            {
                $('#modalEditRdv #validerRdv').attr( "checked", true );
            }

            $("#modalEditRdv").modal({backdrop: "static"});
        },

        error : function (status)
        {
            console.log(status);
        }
    });
}


function getDossiers( idPatient )
{
    $.ajax({

        url : 'includes/functions/controller.php?action=getDossiersPatient&idPatient=' + idPatient,

        type : 'GET',

        success : function(res)
        {
            var data = JSON.parse(res);

            $.each(data, function(i, value)
            {
                $('#idDossier').append($('<option>').text(value['titreDossier']).attr('value', value['idDossier']));
            });
        },
        error : function(resultat, statut, erreur){
            console.log(resultat);
        }
    });
}

$('#idPatient').change(function ()
{
    document.getElementById('idDossier').innerHTML = "";
    var idPatient = $('#idPatient').val();
    getDossiers( idPatient );
});

// get rdv of all today :
function getSalledAttente()
{
    $.ajax({

        url : 'includes/functions/controller.php?action=getSalledAttente',

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            $('#tbSalledAttente').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html
                    (
                        '<button class="btn btn-danger btn-flat btn-sm" onclick="deleteSalleAttent(' + aData.idSalleDAttent + ')" ><i class="fa fa-trash-o"></i></button><button onclick="validSalleAttent(' + aData.idSalleDAttent + ')" class="btn btn-success btn-flat btn-sm" style="margin-left: 7px;"><i class="fa fa-check"></i></button>'
                    );

                    return nRow;
                },

                "columns":
                [
                    { "data": "patient" },
                    {
                        "data": "heurArrivee",

                        "render": function(data, type, row)
                        {
                            return "<span class='text-green'> " + moment(data, "YYYY-MM-DD HH:mm:ss").format("hh : mm") + " </span>";
                        }
                    },
                    {
                        "data": "type",

                        "render": function(data, type, row)
                        {
                            return "<span class='badge-no-circle bg-green'>" + data + "</span>";
                        }
                    },
                    {
                        "data": "idRdv",

                        "render": function(data, type, row)
                        {
                            return data != null ? "<span class='badge-no-circle bg-orange'>Avec Rdv</span>" : "<span class='badge-no-circle bg-orange' > Sans Rdv </span>";
                        }
                    },
                    {
                        "data": "etat",

                        "render": function(data, type, row)
                        {
                            return data == 1 ? "<span class='badge-no-circle bg-green'> Validé </span>" : "<span class='badge-no-circle bg-blue' > En attent</span>";
                        }
                    },
                    { "data": "etat" }
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

function validSalleAttent(id)
{
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment valider !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00a65a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Valider!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=validerSalleAtt&idSall=' + id,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            getSalledAttente();
                            swal( 'Good !', 'Bien valider !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de valider !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de valider !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}

function deleteSalleAttent(id)
{
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer !",
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

                    url  : 'includes/functions/controller.php?action=deleteSalleAtt&idSall=' + id,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            getRdvToday();
                            getSalledAttente();
                            getRdvValide();
                            swal( 'Good !', 'Bien supprimer !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}


function addPatientSalleAttent()
{
    var idDossier = $('#modalAddPatientSalleAttente #idDossier').val();
    var idType = $('#modalAddPatientSalleAttente #typeRdv').val();

    loaderBtn('btnAddPatientSalleAttent', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    $.ajax({
        url         : 'includes/functions/controller.php?action=addPatientSalleAttent',
        type        : 'post',
        data        : {idDossier : idDossier, idType : idType},
        success     : function(data)
        {
            if( data == '1' )
            {
                getRdvToday();
                getSalledAttente();
                getRdvValide();
                $("#modalAddPatientSalleAttente").modal('hide');
                showAlertSuccess('Le patient est bien ajouter au salle d\'attente !');
            }
            else
            {
                ShowAlertError('Impossible d\'ajouter ce patient au salle d\'attente !');
            }
        },
        error : function(status)
        {
            console.log(status);
        },
        complete : function()
        {
            loaderBtn('btnAddPatientSalleAttent', '<i class="fa fa-check"></i> &nbsp;Valider');
        }
    });
}


function validerRdv()
{
    var idRdv = $('#modalAddRdvSalleAttente #idRdv').val();
    loaderBtn('btnValidRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    $.ajax({
        url         : 'includes/functions/controller.php?action=addRdvAuSalleDattente',
        type        : 'post',
        data        : {idRdv : idRdv},
        success     : function(data)
        {
            if( data == '1' )
            {
                getRdvToday();
                getSalledAttente();
                getRdvValide();
                $("#modalAddRdvSalleAttente").modal('hide');
                showAlertSuccess('Le patient est bien ajouter au salle d\'attente !');
            }
            else
            {
                ShowAlertError('Impossible ce patient au salle d\'attente !');
            }
        },
        error : function(status)
        {
            console.log(status);
        },
        complete : function()
        {
            loaderBtn('btnValidRdv', '<i class="fa fa-save"></i> &nbsp;Enregestrer');
        }
    });
}
//

function getRdvValide()
{
    $.ajax({

        url         :'includes/functions/controller.php?action=getActiveRdv',
        type        :'GET',
        success     : function(data)
        {
            data = JSON.parse(data);
            document.querySelector('#modalAddRdvSalleAttente #idRdv').innerHTML = "";

            $.each(data, function(i, value) {
                $('#modalAddRdvSalleAttente #idRdv').append($('<option>').text(value['rdv']).attr('value', value['idRdv']));
            });
        },
        error : function(status)
        {
            console.log(status);
        }

    });
}




</script>
<!--<script src="layout/js/admins.js"></script>-->

