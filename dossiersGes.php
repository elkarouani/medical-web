<?php

    if( !isset($_GET['idPatient']) || empty($_GET['idPatient']) || !is_numeric($_GET['idPatient']))
    {
        header('location:patients.php');
    }

    $idPatient = $_GET['idPatient'];

    $title = 'Dossiers';
    $acceUser = ['gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";

    $patient = getData("SELECT idPatient, cin, nom, prenom FROM patient WHERE idPatient = ? ", [ $idPatient ]);

    if ( empty( $patient['cin'] ) )
    {
        header('location:patients.php');
    }
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dossiers du patient : <span class="text-bold"><?php echo $patient['prenom'] . '  ' . $patient['nom'] ?></span>
        </h1>
        <ol class="breadcrumb">
            <button data-toggle="modal" data-target="#modalAddDossier" class="btn btn-primary btn-flat pull-right"> <i class="fa fa-plus-circle"></i> &nbsp; Affecter nouveau dossier à ce patient</button>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="box box-primary box-solid" style="border-radius: 0px">
            <div class="box-header">
                <input type="hidden" value="<?= $patient['idPatient'] ?>" id="idPatient">
                <h3 class="box-title"> <i class="fa fa-folder-open"></i> &nbsp; Liste des dossiers du patient : <?php echo $patient['prenom'] . '  ' . $patient['nom'] ?> </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
                <table id="tblDossiers" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Titre de dossier</th>
                        <th>Date création</th>
                        <th>Montant de dossier</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.box-body -->

        <!-- Start Modal Add Mutuelle -->
        <div id="modalAddDossier" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form  action="includes/functions/controller.php?action=addDossierPatient" id="FormAddDossier" methode="post">
                        <div class="modal-header bg-purple">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-folder-open-o"></i>&nbsp; Nouveau Dossier au patient : <?php echo $patient['prenom'] . '  ' . $patient['nom'] ?> </h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idPatient" value="<?= $patient['idPatient'] ?>">
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
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<!--<script src="layout/js/dossiersPatient.js"></script>-->
<script>
    /******** Debut page dossiers *******/

    $(document).ready(function(){
        getDossiers();

        $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
        {
            $('body').css('padding-right','0 !important');
        });

        $.fn.modal.Constructor.prototype.setScrollbar = function () { };

        $('#btnAddDossier').click(function(){
            addDossier();
        });

    });

    // get patients :
    function getDossiers()
    {
        var idPatient = document.getElementById('idPatient').value;

        $.ajax({

            url : 'includes/functions/controller.php?action=getDossiers&idPatient=' + idPatient,

            type : 'GET',

            success : function(res)
            {
                var res2 = JSON.parse(res);

                $('#tblDossiers').DataTable({

                    data : res2,

                    destroy: true,

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

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

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
                    getDossiers();
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
                                getDossiers();
                                swal( 'Good !', 'Le dossier est bien supprimer !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer ce dossiers !', 'error' ).insertQueueStep(1);
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

    /******** Fin page dossiers *******/
</script>
<?php require_once "includes/templates/sousFooter.inc"; ?>
