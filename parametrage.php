<?php
    $title      = 'Parametrages';
    $link       = 'parametrage';
    $subLink    = 'parametrage';
    $acceUser = ['admin', 'gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";
    $familleMedicament    = getDatas("SELECT * FROM famillemedicament", []);
    $params               = getData("SELECT * FROM parametrage WHERE etat = 1", []);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Parametrages <small>panneau du controle</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <button data-toggle="modal" data-target="#modalAddMedicament" class="btn bg-aqua pull-right" style="margin-left:5px"> <i class="fa fa-calendar-plus-o"></i>&nbsp;Nouv.Medicament</button>
            </li>
        </ol>
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div style="border-radius: 0px !important; border:0px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <i class="fa fa-clock-o"></i>&nbsp;Duree de rendez vous</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="control-label">Duree de rendez vous</label>
                            <select name="dureeRdv" id="dureeRdv" class="select2 form-control">
                                <option <?php if( $params['dureeRdv'] == '00:15:00' ) echo 'selected' ?> value="00:15:00">15:00 mins</option>
                                <option <?php if( $params['dureeRdv'] == '00:30:00' ) echo 'selected' ?> value="00:30:00">30:00 mins</option>
                                <option <?php if( $params['dureeRdv'] == '01:00:00' ) echo 'selected' ?> value="01:00:00">01:00 heur</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button id="btnUpdateDureeRdv" class="btn btn-block btn-success btn-flat"> <i class="fa fa-save"></i> Modifier la duree de Rdv</button>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-flask"></i>&nbsp;Medicaments</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#modalAddMedicament" ><i class="ion ion-plus"></i>&nbsp; Nouveau medicament</button>
                        </div>
                    </div>
                    <!-- /.box-header table-responsive -->
                    <div class="box-body">
                        <table id="tblMedicaments" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Designation</th>
                                <th>Fammille</th>
                                <th>Dosage</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <!-- Start Modal Add Mutuelle -->
    <div id="modalAddEditMedicament" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form  action="includes/functions/controller.php?action=updateMedicament" id="FormEditMedicament" methode="post">
                    <div class="modal-header bg-purple">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-medkit"></i>&nbsp; Nouveau medicament </h4>
                        <input type="hidden" id="idMedicamentForEdit" name="idMedicament" value="">
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
                        <button type="button" id="btnUpdateMedicament" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
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
                            <input type="text" class="form-control" name="designation" id="addDesignation" placeholder="Entrez la designation du medicament...">
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label for="familleMedicament" class="control-label">
                                Famille de medicament <span class="required">*</span>
                            </label>
                            <select name="familleMedicament" id="familleMedicamentForAdd" class="form-control select2">
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
                            <input type="text" class="form-control" name="dosage" id="addDosage" placeholder="Entrez le dosage du medicament...">
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
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script>

    $(document).ready(function ()
    {
        $(document.body).on('hide.bs.modal,hidden.bs.modal', function () {
            $('body').css('padding-right', '0 !important');
        });

        $.fn.modal.Constructor.prototype.setScrollbar = function () {};

        $('.select2').select2({"width": "100%"});

        getMedicaments();

        $('#btnAddMedicament').click(function()
        {
            addMedicament();
        });

        $('#btnUpdateMedicament').click(function()
        {
            updateMedicament();
        });

        $('#btnUpdateDureeRdv').click(function()
        {
            updateDureeRdv();
        });

    });

    var dataMedicaments = [];
    function getMedicaments() {
        $.ajax({

            url: 'includes/functions/controller.php?action=getMedicaments',

            type: 'GET',

            success: function (res)
            {
                var res2 = JSON.parse(res);
                dataMedicaments = res2;
                $('#tblMedicaments').DataTable({

                    data: res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<button class="btn btn-info btn-sm btn-flat" onclick="editMedicament(' + aData.idMedicament + ')" ><i class="fa fa-eye"></i></button> <button class="btn btn-sm btn-danger btn-flat" onclick="deleteMedicament(' + aData.idMedicament + ')"> <i class="fa fa-trash-o"></i></button>'
                        );

                        return nRow;
                    },

                    "columns": [
                        {"data": "designation"},
                        {
                            "data": "libelle",
                            "render": function (data, type, row) {
                                return '<span class="text-blue">' + data + ' </span>';
                            }
                        },
                        {"data": "dosage"},
                        {"data": "idMedicament"}
                    ],

                    "language": {
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
                            "sSortAscending": ": Trier par ordre croissant",
                            "sSortDescending": ": Trier par ordre décroissant"
                        }
                    }

                });
            },

            error: function (resultat, statut, erreur) {
                console.log(resultat);
            }
        });
    }

    function addMedicament()
    {
        var desMed = document.getElementById('addDesignation');
        var dosage = document.getElementById('addDosage');

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
                    getMedicaments();
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

    function updateMedicament()
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

        loaderBtn('btnUpdateMedicament', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var fd = new FormData(document.querySelector('#FormEditMedicament'));
        $.ajax({

            url         :$('#FormEditMedicament').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '0' )
                {
                    ShowAlertError('La modification de medicament à échoue, tout les champs sont obligatoire !');
                }
                else
                {
                    getMedicaments();
                    $("#modalAddEditMedicament").modal('hide');
                    showAlertSuccess('Le Medicament est bien modifier !');
                }

            },
            error : function(status)
            {
                console.log(status);
            },

            complete : function(resultat, statut)
            {
                loaderBtn('btnUpdateMedicament', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
            }

        });
    }

    function editMedicament(idMedicament)
    {
        var med;
        var data = dataMedicaments;
        for( var i = 0; i < data.length; i++ )
        {
            if( data[i].idMedicament == idMedicament )
            {
                med = data[i];
            }
        }
        $('#modalAddEditMedicament #idMedicamentForEdit').val(med.idMedicament);
        $('#modalAddEditMedicament #designation').val(med.designation);
        $('#modalAddEditMedicament #dosage').val(med.dosage);
        $("#modalAddEditMedicament #familleMedicament").val(med.idFamilleMedicament).trigger("change");
        $('#modalAddEditMedicament').modal();
    }

    function deleteMedicament(idMed) {

        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce medicament !",
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

                        url  : 'includes/functions/controller.php?action=deleteMedicament&idMedicament=' + idMed,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                getMedicaments();
                                swal( 'Good !', 'Le medicament est bien supprimer !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer ce medicament !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce medicament !', 'error' ).insertQueueStep(1);
                            resolve();
                        }

                    });
                });
            }
        }]);
    }

    function updateDureeRdv()
    {
        var dureeRdv = $('#dureeRdv').val();
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment modifier la duree de rendez vous !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Modifier!',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve)
                {
                    $.ajax({

                        url  : 'includes/functions/controller.php?action=updateDureeRdv&dureeRdv=' + dureeRdv,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                getMedicaments();
                                swal( 'Good !', 'La duree de rendez vous est bien modifier !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de modifier la duree de rendez vous !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de modifier la duree de rendez vous !', 'error' ).insertQueueStep(1);
                            resolve();
                        }

                    });
                });
            }
        }]);
    }


</script>
<!--<script src="layout/js/admins.js"></script>-->

