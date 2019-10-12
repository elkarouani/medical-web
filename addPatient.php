<?php

    $title      = 'Information';
    $link       = 'patients';
    $subLink    = 'addPatient';
    $acceUser = ['admin', 'gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";
    $mutuels = getDatas("SELECT * FROM mutuel", []);
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
            Nouveau Patient
        </h1>
        <ol class="breadcrumb">
            <button type="reset" id="btnNewPatient" class="btn btn-primary btn-flat"> <i class="ion ion-person-stalker"></i> &nbsp; Vers la liste des patients </button>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form action="includes/functions/controller.php?action=addPatient" id="FormAddPatient" >
                    <div class="box box-solid box-primary" style="border-radius: 0px !important;" >
                        <div class="box-header">
                            <h3 class="box-title">
                                <i class="fa fa-user-plus"></i> &nbsp;
                                Ajouter Nouveau Patient
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cinPatient" class="control-label">Numéro du carte nationnal<span class="required">*</span></label>
                                        <input type="text" id="cinPatient" name="cinPatient" class="form-control" placeholder="Numéro du carte nationnal" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomPatient" class="control-label">Nom <span class="required">*</span></label>
                                        <input type="text" id="nomPatient" name="nomPatient" class="form-control" placeholder="Nom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomPatient" class="control-label">Prenom <span class="required">*</span></label>
                                        <input type="text" id="prenomPatient" name="prenomPatient" class="form-control" placeholder="Prenom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateNaissancePatient" class="control-label">Date de naissance <span class="required">*</span></label>
                                        <input type="text" required id="dateNaissancePatient"  data-date-format="yyyy-mm-dd" name="dateNaissancePatient" class="form-control datepicker" placeholder="Date de naissance" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephonePatient" class="control-label">Telephone <span class="required">*</span></label>
                                        <input type="tel" id="telephonePatient" name="telephonePatient" class="form-control" placeholder="Telephone" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailPatient" class="control-label">Email</label>
                                        <input type="email" id="emailPatient" name="emailPatient" class="form-control" placeholder="Email" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Sexe <span class="required">*</span></label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="H" name="sexePatient" checked > Homme
                                            </label>

                                            <label style="margin-left: 30px">
                                                <input type="radio" value="F" name="sexePatient" > Femme
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-10" >
                                            <label for="mutuel" class="control-label">
                                                Mutuelle <span class="required">*</span>
                                            </label>
                                            <select name="mutuel" id="listeMutuel" class="form-control select2">
                                                <?php foreach ($mutuels as $mutuel): ?>
                                                    <option value="<?= $mutuel['idMutuel'] ?>" > <?= $mutuel['libelle'] ?> </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2" style="padding-left: 0px;">
                                            <div class="form-group">
                                                <label for=""></label>
                                                <button type="button" id="btnAddMutuel" data-toggle="modal" data-target="#modalAddMutuelle" class="btn btn-block btn-flat btn-primary" style="margin-top: 5px;" ><i class="ion ion-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="adressePatient" class="control-label">Adresse <span class="required">*</span></label>
                                <textarea name="adressePatient" class="form-control" id="adressePatient" placeholder="Adresse..." cols="20" rows="4"></textarea>
                                <span class="help-block"></span>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="button" id="btnAddPatient" class="btn btn-success btn-flat"> <i class="fa fa-save"></i> Enregestrer </button>
                            <button type="reset" id="btnNewPatient" class="btn btn-warning btn-flat"> <i class="fa fa-user-plus"></i> Nouveau Patient </button>
                            <br>
                        </div>
                        <div class="overlay" id="loaderAddPatient" style="display:none">
                            <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
                        </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modals -->
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

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script>

    /****** début page admin *****/

    $(document).ready(function(){

        $('.select2').select2({ "width": "100%" });
        $('#btnAddMutuelle').click(function()
        {
            addMutuelle();
        });

        getPatients();

        $(".modal").modal({
            "backdrop"  : "static",
            "keyboard"  : true,
            "show"      : false
        });

        $('#btnAddPatient').click(function(){
            savePatient();
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('#btnNewPatient').click(function () {

            $('.alert-popup').hide();
            var elem = document.querySelectorAll("input, textarea");
            var t = 0;
            for (var i = 0; i < elem.length; i++)
            {
                if ( elem[i].parentNode.classList.contains("has-error") )
                {
                    elem[i].parentNode.classList.remove('has-error');
                    elem[i].parentNode.lastElementChild.innerText = '';
                }
            }
        });
    });


    /* Patients functions */
    // get patients :
    function getPatients()
    {
        $.ajax({

            url : 'getPatients.php',

            type : 'GET',

            success : function(res){
                var res2 = JSON.parse(res);
                $('#tblPatients').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html(
                            '<button onclick="DeletePatient(' + aData.idPatient + ', ' + false + ')"  data-toggle="tooltip" title="Supprimer" id="btnDeletePatient" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                            '<i class="fa fa-trash-o"></i>' +
                            '</button>'

                            +

                            '  <a style="display:inline-block" data-toggle="tooltip" title="Editer" href="editPatient.php?idPatient=' + aData.idPatient + '" class="btn btn-success btn-sm btn-flat">' +
                            '<i class="fa fa-edit"></i>' +
                            '</a>'

                            +

                            '  <a style="display:inline-block" data-toggle="tooltip" title="Afficher" href="viewPatient.php?idPatient=' + aData.idPatient + '" class="btn btn-info btn-sm btn-flat">' +
                            '<i class="fa fa-eye"></i>' +
                            '</a>'
                        )

                        return nRow;
                    },

                    "columns": [
                        { "data": "cin" },
                        { "data": "nom" },
                        { "data": "prenom" },
                        {
                            "data": "sexe",
                            "render": function(data, type, row)
                            {
                                if( data == 'H' ){
                                    return '<span class="badge bg-blue"> Homme </span>'
                                }
                                else{
                                    return '<span class="badge bg-maroon"> Femme </span>';
                                }
                            }
                        },
                        { "data": "dateNaissance" },
                        { "data": "tel" },
                        { "data": "email" },
                        {
                            "data": "active",
                            "render": function(data, type, row)
                            {
                                if( data == 1 ){
                                    return '<span class="badge bg-green"> active </span>'
                                }
                                else{
                                    return '<span class="badge bg-red"> desactive </span>';
                                }
                            }
                        },
                        {
                            "data": "idPatient",
                            "render": function(data, type, row)
                            {
                                return '<a href="dossiersPatient.php?idPatient=' + data + '" class="text-red"> Dossiers  <i class="fa fa-angle-double-right "></i> </a>';
                            }
                        },
                        { "data": "idPatient" }

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

    //save admin :
    function savePatient()
    {
        $('#loaderAddPatient').show();
        loaderBtn('btnAddPatient', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.querySelectorAll("input, textarea");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.classList.remove('has-error');
                elem[i].parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormAddPatient'));

        $.ajax({

            url         :$('#FormAddPatient').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,

            success     : function(data)
            {
                if( data == "1" )
                {
                    showAlertSuccess('Le patient est bien jouter !');
                }
                else if( data == '0' )
                {
                    ShowAlertError('L\'ajout à échoue !');
                }
                else
                {
                    ShowAlertError('L\'ajout à échoué tout les champs sont obligatoire !');

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
            complete : function()
            {
                $('#loaderAddPatient').hide();
                loaderBtn('btnAddPatient', '<i class="fa fa-save"></i> &nbsp;Enregestrer');
            }

        });
    }

    function DeletePatient(idPatient, reload)
    {
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce patient !",
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

                        url  : 'includes/functions/controller.php?action=deletePatient&idPatient=' + idPatient,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                if( !reload )
                                {
                                    getPatients();
                                }
                                else
                                {
                                    window.location.href = 'patients.php';
                                }

                                swal( 'Good !', 'La suppression est bien effectué !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer ce patient !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce patient !', 'error' ).insertQueueStep(1);
                            resolve();
                        }

                    });
                });
            }
        }]);
    }

    function showAlertSuccess(text)
    {
        document.getElementById("alert-success").lastElementChild.innerHTML = text;
        $('#alert-success').fadeIn(500);
        setTimeout(function(){ $('#alert-success').fadeOut(500); }, 5000);
    }

    function ShowAlertError(text)
    {
        document.getElementById("alert-error").lastElementChild.innerHTML = text;
        $('#alert-error').fadeIn(500);
        setTimeout(function(){ $('#alert-error').fadeOut(500); }, 5000);
    }

    function loaderBtn(idBtn, text)
    {
        document.querySelector('#' + idBtn).innerHTML = text;
    }

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
    /***** fin page admin *****/





</script>
<!--<script src="layout/js/patientss.js"></script>-->
