<?php
$title   = 'Paiements';
$link    = 'paiements';
$subLink = 'paiements';
$acceUser = ['gestionnaire'];
$withLoader = false;
include_once "includes/templates/header.inc";
$userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";
$dossiersPatients = getDatas('SELECT CONCAT( \'[ \', pt.cin, \' ] \', pt.prenom, pt.nom, \' / \', d.titreDossier ) AS patient, d.* FROM patient pt, dossier d WHERE d.idPatient = pt.idPatient', []);
$patients = getDatas('SELECT pt.idPatient, CONCAT( \'[ \', pt.cin, \' ] \', pt.prenom, pt.nom ) AS patient FROM patient pt', []);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Paiements du patients
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <button class="btn btn-primary"  data-toggle="modal" data-target="#modalAddPaiement" ><i class="fa fa-money"></i>&nbsp; Nouveau paiement</button>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search"></i>&nbsp; Chercher par patient</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="idPatientForFilter" class="control-label">Selectionnez un patient</label>
                                    <select name="patient" class="form-control select2" id="idPatientForFilter">
                                        <option value="0" selected="" disabled="">--  vuillez choisie un patient  --</option>
                                        <?php foreach( $patients as $patient ): ?>
                                            <option value="<?= $patient['idPatient'] ?>"> <?= $patient['patient'] ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label"></label>
                                    <button style="margin-top: 5px;" id="btnFilterWithPatient" class="form-control btn-flat btn btn-primary">Chercher</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search"></i>&nbsp; Chercher par date de création </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="dateDebut" class="control-label">A patire de </label>
                                    <input type="text" data-date-format="yyyy-mm-dd" placeholder="A partire de ..." class="form-control datepicker" id="dateDebut">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="dateFin" class="control-label">Jusqu'à</label>
                                    <input type="text" data-date-format="yyyy-mm-dd" placeholder="Jusqu'à ..." class="form-control datepicker" id="dateFin">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="" class="control-label"></label>
                                    <button style="margin-top: 5px;" id="btnFilterWithdateCreation" class="form-control btn-flat btn btn-primary">Chercher</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary box-solid" style="border-radius: 0px">
            <div class="box-header">
                <h3 class="box-title"> <i class="fa fa-money"></i><span id="title-listePaiements"> Liste des paiements effectue </span></h3>
                <div class="box-tools">
                    <button type="button" data-toggle="modal" data-target="#modalAddPaiement" class="btn btn-box-tool" data-placement="top" title="Nouveau paiement" ><i class="ion ion-plus"></i>&nbsp; Nouveau paiement</button>
                </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
                <table id="tblPaiments" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Titre de dossier</th>
                        <th>Date de paiement</th>
                        <th>Montant à payé</th>
                        <th>Montant de dossier</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </section>
    <!-- /.content -->

    <!-- Start Modal Add Paiements -->
    <div id="modalAddPaiement" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="includes/functions/controller.php?action=AddPaiement" id="FormAddPaiement" methode="post">
                    <div class="modal-header bg-purple">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="ion ion-plus"></i>&nbsp; Nouveau paiement &nbsp;<span></span> </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="idDossier" class="control-label">Patient et leur dossier</label>
                            <select name="idDossier" class="form-control select2" id="idDossier">
                                <option value="0" selected="" disabled=""> --  vuillez choisie un patient  -- </option>
                                <?php foreach( $dossiersPatients as $patient ): ?>
                                    <option value="<?= $patient['idDossier'] ?>"> <?= $patient['patient']; ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="viewInfoPaiementDossier">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="control-label">Montant de dossier</label>
                                        <div class="input-group">
                                            <input type="text" id="montantDossier" disabled class="form-control" placeholder="Montant de dossier" value="">
                                            <span class="input-group-addon" id="basic-addon2">DH</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="control-label">Rest</label>
                                        <div class="input-group">
                                            <input type="text" id="rest" disabled class="form-control" placeholder="Rest" value="">
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
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script>
    /****** Debut page consultations dossier *******/

    $(document).ready(function(){

        $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
        {
            $('body').css('padding-right','0 !important');
        });

        $.fn.modal.Constructor.prototype.setScrollbar = function () { };


        $('.select2').select2({ "width": "100%" });

        $('#modalAddPaiement #idDossier').change(function ()
        {
            $('#viewInfoPaiementDossier').slideUp();
            getDossierWithMontantPaye( $('#modalAddPaiement #idDossier').val() );
        });

        getPaiementsPatient();

        $('#btnAddPaiement').click(function()
        {
            addPaiement();
        });

        $('.dateTimepicker').datetimepicker({
            format: 'yyyy-mm-dd HH',
            autoclose: true
        });

        $('#btnFilterWithdateCreation').click( function() {
            getPaiementsDatePaiement();
        });

        $('#btnFilterWithPatient').click( function() {
            getPaiementsPatient();
        });

        $('#viewInfoPaiementDossier').slideUp();

    });

    function getDossierWithMontantPaye( idDossier )
    {
        $('#viewInfoPaiementDossier').slideDown();
        $.ajax({

            url : 'includes/functions/controller.php?action=getDossierWithSumMontantPaye&idDossier=' + idDossier,

            type : 'GET',

            success : function(res)
            {
                var data = JSON.parse(res);
                var montantPaye = (data.montantPaye != null) ? data.montantPaye : 0;
                var rest = data.montantDossier - montantPaye;

                $('#modalAddPaiement #montantDossier').val(data.montantDossier);
                $('#modalAddPaiement #rest').val(rest);
            },
            error : function(resultat, statut, erreur){
                console.log(resultat);
            }
        });
    }

    var dataPaiements = [];

    // get Dossiers :
    function getPaiementsPatient(all=false)
    {
        var idPatient = '';
        if( all == false )
        {
            idPatient = $('#idPatientForFilter').val();
        }

        $.ajax({

            url : 'includes/functions/controller.php?action=getPaiementsPatient&idPatient=' + idPatient,

            type : 'GET',

            success : function(res)
            {
                var res2 = JSON.parse(res);
                $('#title-listePaiements').text("Liste des paiements effectué");
                $('#tblPaiments').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        dataPaiements.push(aData);
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<button class="btn btn-sm btn-danger btn-flat" onclick="deletePaiement(' + aData.idPaiement + ')"> <i class="fa fa-trash-o"></i>&nbsp; Supprimer</button>'
                        );

                        return nRow;
                    },

                    "columns": [
                        { "data": "patient" },
                        {
                            "data": "datePaiement",
                            "render": function(data, type, row)
                            {
                                return '<span class="text-blue">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm"); + ' </span>';
                            }
                        },
                        {
                            "data": "montantPaye",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge badge-no-circle bg-blue">' + data + ' DH</span>';
                            }
                        },
                        {
                            "data": "montantDossier",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge badge-no-circle bg-green">' + data + ' DH</span>';
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

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function addPaiement()
    {
        if(document.getElementById('montantPayer').parentNode.parentNode.classList.contains('has-error'))
        {
            document.getElementById('montantPayer').parentNode.parentNode.classList.remove('has-error');
            document.getElementById('montantPayer').parentNode.parentNode.lastElementChild.innerText = "";
        }


        var montantPayer = $('#montantPayer').val();
        var rest         = $('#rest').val();

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
                            getPaiementsPatient(true);
                            $('#modalAddPaiement').modal('hide');
                            $('#viewInfoPaiementDossier').slideUp();
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


    function getPaiementsDatePaiement()
    {
        var dateDebut = document.getElementById('dateDebut');
        var dateFin = document.getElementById('dateFin');

        if( dateDebut.parentNode.classList.contains('has-error') )
        {
            dateDebut.parentNode.classList.remove('has-error');
        }
        if( dateFin.parentNode.classList.contains('has-error') )
        {
            dateFin.parentNode.classList.remove('has-error');
        }

        if( dateDebut.value == '' && dateFin.value == '' )
        {
            dateDebut.parentNode.classList.add('has-error');
            dateFin.parentNode.classList.add('has-error');
            ShowAlertError('Impossible de checher, Tout les champs sont obligatoires');
            return false;
        }

        if( dateDebut.value == '' )
        {
            dateDebut.parentNode.classList.add('has-error');
            ShowAlertError('Impossible de chercher, Tout les champs sont obligatoires');
            return false;
        }
        if( dateFin.value == '' )
        {
            dateFin.parentNode.classList.add('has-error');
            ShowAlertError('Impossible de chercher, Tout les champs sont obligatoires');
            return false;
        }

        $.ajax({

            url : 'http://localhost/sitecabinetDentaire/admin/includes/functions/controller.php?action=getPaiementsDatePaiement&dateDebut=' + dateDebut.value + '&dateFin=' + dateFin.value,

            type : 'GET',

            success : function(res)
            {
                $('#title-listePaiements').text("Liste des paiements effectué entre : " + dateDebut.value + " et " + dateFin.value);
                var res2 = JSON.parse(res);

                $('#tblPaiments').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<button class="btn btn-sm btn-danger btn-flat" onclick="deletePaiement(' + aData.idPaiement + ')"> <i class="fa fa-trash-o"></i>&nbsp; Supprimer</button>'
                        );

                        return nRow;
                    },

                    "columns": [
                        { "data": "patient" },
                        {
                            "data": "datePaiement",
                            "render": function(data, type, row)
                            {
                                return '<span class="text-blue">' + moment(data, "YYYY-MM-DD HH:mm:ss").format("DD / MM / YYYY à hh:mm"); + ' </span>';
                            }
                        },
                        {
                            "data": "montantPaye",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge badge-no-circle bg-blue">' + data + ' DH</span>';
                            }
                        },
                        {
                            "data": "montantDossier",
                            "render": function(data, type, row)
                            {
                                return '<span class="badge badge-no-circle bg-green">' + data + ' DH</span>';
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

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function deletePaiement ( idPaiement ) {
        console.log(dataPaiements);
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce paiement ?",
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

                        url  : 'includes/functions/controller.php?action=deletePaiement&idPaiement=' + idPaiement,

                        type : 'get',

                        success : function (data)
                        {
                            console.log(data);
                            if( data == 1 )
                            {
                                getPaiementsPatient(true);
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
</script>
<!--<script src="layout/js/dossiers.js"></script>-->
<?php require_once "includes/templates/sousFooter.inc"; ?>
