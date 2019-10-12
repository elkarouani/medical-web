<?php
    $title   = 'Dossiers';
    $link    = 'dossiers';
    $subLink = 'dossiers';
    $acceUser = ['admin'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $patients = getDatas('SELECT * FROM patient', []);
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dossier du patients
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <button data-toggle="modal" data-target="#modalAddDossier" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp; Nouveau dossier</button>
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
                                    <label for="idPatient2" class="control-label">Selectionnez un patient</label>
                                    <select name="patient" class="form-control select2" id="idPatient2">
                                        <option value="0" selected="" disabled="">--  vuillez choisie un patient  --</option>
                                        <?php foreach( $patients as $patient ): ?>
                                            <option value="<?= $patient['idPatient'] ?>"> <?php if( $patient['cin'] != '' ) echo '[ ' . $patient['cin'] . ' ] ' . $patient['prenom'] . '  ' . $patient['nom']; else echo $patient['prenom'] . '  ' . $patient['nom']; ?> </option>
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
                <h3 class="box-title"> <i class="fa fa-folder-open"></i>&nbsp; Liste des dossiers</h3>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
                <table id="tblDossiers" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Titre de dossier</th>
                        <th>Date création</th>
                        <th>Patient</th>
                        <th>Montant de dossier</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </section>

    <!-- Start Modal Add Mutuelle -->
    <div id="modalAddDossier" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form  action="includes/functions/controller.php?action=addDossierPatient" id="FormAddDossier" methode="post">
                    <div class="modal-header bg-purple">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="ion ion-folder"></i>&nbsp; Nouveau dossier </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="patient" class="control-label">
                                Patient <span class="required">*</span>
                            </label>
                            <select id="idPatient" name="idPatient" class="form-control select2">
                                <?php foreach( $patients as $patient ): ?>
                                    <option value="<?= $patient['idPatient'] ?>"> <?= '[ ' . $patient['cin'] . ' ] ' . $patient['prenom'] . ' ' . $patient['nom'] ?> </option>
                                <?php endforeach ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="titreDossier">
                                Titre de dossier  <span class="required">*</span>
                            </label>
                            <input type="text" name="titreDossier" id="titreDossier" class="form-control" placeholder="Entrez le titre de dossier...">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                Date de création  <span class="required">*</span>
                            </label>
                            <input type="text" disabled class="form-control datepicker" placeholder="Date de paiement" value="<?= date("d/m/Y H:i");  ?>">
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
    <!-- /.content -->
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

        $('.select2').select2({"width": "100%"});

        getDossiersPatient();

        $('.dateTimepicker').datetimepicker({
            format: 'yyyy-mm-dd HH',
            autoclose: true
        });

        $('#btnFilterWithdateCreation').click( function() {
            getDossiersDateCreation();
        });

        $('#btnFilterWithPatient').click( function() {
            getDossiersPatient();
        });

        $('#btnAddDossier').click( function() {
            addDossier();
        });


    });

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
                    getDossiersPatient();
                    showAlertSuccess('Le dossier est bien jouter !');
                    $("#modalAddDossier").modal('hide');
                    $("#titreDossier").val('');
                }
                else if( data == '0' )
                {
                    ShowAlertError('L\'ajout à échoue !');
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

    // get Dossiers :
    function getDossiersPatient()
    {
        var idPatient = document.getElementById('idPatient2').value;

        $.ajax({

            url : 'includes/functions/controller.php?action=getDossiersPatient&idPatient=' + idPatient,

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
                        );

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
                        { "data": "patient" },
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

    function getDossiersDateCreation()
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

            url : 'http://localhost/sitecabinetDentaire/admin/includes/functions/controller.php?action=getDossiersDateCreation&dateDebut=' + dateDebut.value + '&dateFin=' + dateFin.value,

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
                        { "data": "patient" },
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
                                getDossiersPatient();
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

    /*// Add consultation :
     function addConsultation()
     {
     loaderBtn('btnAddConsultation', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

     var fd = new FormData(document.querySelector('#FormAddConsultation'));

     $.ajax({

     url         :$('#FormAddConsultation').attr('action'),
     type        : 'post',
     data        : fd,
     processData : false,
     contentType : false,
     success     : function(data)
     {
     console.log(data);
     },
     error : function(status)
     {
     console.log(status);
     },

     complete : function(resultat, statut)
     {
     loaderBtn('btnAddConsultation', '<i class="fa fa-check"></i> Enregestrer');
     }

     });
     }


     // Delete Consultation :
     function deleteConsultation(idConsultation)
     {
     swal.queue
     ([{
     title: 'Etes-vous sûr?',
     text: "voulez vous vraiment supprimer cette consultation !",
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
     }*/

    /******** Fin page consultations dossier *****/
</script>
<!--<script src="layout/js/dossiers.js"></script>-->
<?php require_once "includes/templates/sousFooter.inc"; ?>
