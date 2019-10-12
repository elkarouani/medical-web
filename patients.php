
<?php
    $title      = 'Patients';
    $link       = 'patients';
    $subLink    = 'patients';
    $acceUser = ['admin', 'gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $patients = getDatas("select * from patient ", []);
    $mutuels = getDatas("SELECT * FROM mutuel", []);
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Liste des patients
        </h1>
        <ol class="breadcrumb">
            <button data-toggle="modal" data-target="#modalAddPatient" class="btn btn-primary pull-right" > <i class="fa fa-user-plus"></i> &nbsp; Nouveau patient</button>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary box-solid" style="border-radius: 0px">
            <div class="box-header">
                <h3 class="box-title"> <i class="fa fa-user-circle"></i> &nbsp; Liste Patients </h3>
                <div class="box-tools pull-right">
                    <input type="hidden" value="<?= ($userConnect['user'] == 'gestionnaire') ? 0 : 1  ?>" id="userConnect">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body">
                <table id="tblPatients" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Cin</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Sexe</th>
                        <th>Date Naissance</th>
                        <th>Telehpne</th>
                        <th>Email</th>
                        <th>Dossiers</th>
                        <th >Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box-body -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!--<!-- modal add patient -->
<div class="modal fade" id="modalAddPatient" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="includes/functions/controller.php?action=addPatient" id="FormAddPatient" >
                <div class="modal-header bg-purple">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> &nbsp;Nouveau patient </h4>
                </div>
                <div class="modal-body" style="max-height: 450px;overflow-y: scroll; padding-top: 20px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cinPatient" class="control-label">Numéro de carte nationnal <span class="required">*</span></label>
                                <input type="text" id="cinPatient" name="cinPatient" class="form-control" placeholder="Numéro de carte nationnal..." >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomPatient" class="control-label">Nom <span class="required">*</span></label>
                                        <input type="text" id="nomPatient" name="nomPatient" class="form-control" placeholder="Nom..." >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomPatient" class="control-label">Prenom <span class="required">*</span></label>
                                        <input type="text" id="prenomPatient" name="prenomPatient" class="form-control" placeholder="Prenom..." >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emailPatient" class="control-label">Email</label>
                                <input type="email" id="emailPatient" name="emailPatient" class="form-control" placeholder="Email..." >
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateNaissancePatient" class="control-label">Date de naissance <span class="required">*</span></label>
                                        <input type="text" id="dateNaissancePatient"  data-date-format="yyyy-mm-dd" name="dateNaissancePatient" class="form-control datepicker" placeholder="Date Naissance ( aaaa-mm-jj )" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephonePatient" class="control-label">Telephone <span class="required">*</span></label>
                                        <input type="tel" id="telephonePatient" name="telephonePatient" class="form-control" placeholder="Telephone" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-10" >
                                    <label for="mutuel" class="control-label">
                                        Mutuelle <span class="required">*</span>
                                    </label>
                                    <select name="mutuel" class="form-control listeMutuel select2">
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
                        <label for="adressePatient" class="control-label">Adresse</label>
                        <textarea name="adressePatient" class="form-control" id="adressePatient" placeholder="Adresse..." cols="20" rows="2"></textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                    <button type="button" id="btnAddPatient" class="btn btn-success btn-flat"> <i class="fa fa-save"></i>&nbsp; Enregestrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--<!-- /.modal add patient -->

<!--<!-- modal edit patient -->
<div class="modal fade" id="modalEditPatient" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="includes/functions/controller.php?action=UpdateInfoPrsonnelPatient" id="FormEditPatient" >
                <div class="modal-header bg-purple">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-user"></i> &nbsp;Editer les informations du patient : <span class="text-bold" id="viewNomPatient"></span> </h4>
                    <input type="hidden" id="idPatient" name="idPatient" >
                </div>
                <div class="modal-body" style="max-height: 450px;overflow-y: scroll; padding-top: 20px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cinPatient" class="control-label">Numéro de carte nationnal <span class="required">*</span></label>
                                <input type="text" id="cinPatient" name="cinPatient" class="form-control" placeholder="Numéro de carte nationnal..." >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomPatient" class="control-label">Nom <span class="required">*</span></label>
                                        <input type="text" id="nomPatient" name="nomPatient" class="form-control" placeholder="Nom..." >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomPatient" class="control-label">Prenom <span class="required">*</span></label>
                                        <input type="text" id="prenomPatient" name="prenomPatient" class="form-control" placeholder="Prenom..." >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emailPatient" class="control-label">Email</label>
                                <input type="email" id="emailPatient" name="emailPatient" class="form-control" placeholder="Email..." >
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateNaissancePatient" class="control-label">Date de naissance <span class="required">*</span></label>
                                        <input type="text" id="dateNaissancePatient"  data-date-format="yyyy-mm-dd" name="dateNaissancePatient" class="form-control datepicker" placeholder="Date Naissance ( aaaa-mm-jj )" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephonePatient" class="control-label">Telephone <span class="required">*</span></label>
                                        <input type="tel" id="telephonePatient" name="telephonePatient" class="form-control" placeholder="Telephone" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Sexe <span class="required">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" value="H" id="sexeHomme" name="sexePatient" checked > Homme
                                    </label>

                                    <label style="margin-left: 30px">
                                        <input type="radio" value="F" id="sexeFemme" name="sexePatient" > Femme
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-10" >
                                    <label for="mutuel" class="control-label">
                                        Mutuelle <span class="required">*</span>
                                    </label>
                                    <select name="mutuel" class="form-control listeMutuel select2">
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
                        <label for="adressePatient" class="control-label">Adresse</label>
                        <textarea name="adressePatient" class="form-control" id="adressePatient" placeholder="Adresse..." cols="20" rows="2"></textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                    <button type="button" id="btnUpdatePatient" class="btn btn-success btn-flat"> <i class="fa fa-save"></i>&nbsp; Enregestrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--<!-- /.modal edit patient -->


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

<!--<!-- /.modal -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<!--<script src="layout/js/patientss.js"></script>-->
<script>
    $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
    {
        $('body').css('padding-right','0 !important');
    });

    $.fn.modal.Constructor.prototype.setScrollbar = function () { };



    /****** début page admin *****/

    $(document).ready(function()
    {
        $userConnect = $('#userConnect').val();

        $('.select2').select2({ "width": "100%" });

        $('#btnAddMutuelle').click(function()
        {
            addMutuelle();
        });

        $('#btnUpdatePatient').click(function()
        {
            UpdatePatient();
        });

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
                        Lobibox.notify('warning', { position: 'top right', showClass: 'slideInRight', hideClass: 'slideOutRight', msg: 'e patient est bien enregestrer !', title: 'Success', delay: false});
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
                            $('.listeMutuel').html('');

                            $.each(resultats, function(i, value) {
                                $('.listeMutuel').append($('<option>').text(value['libelle']).attr('value', value['idMutuel']));
                            });

                            $("#modalAddMutuelle").modal('hide');
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

    var dataPatient =[];

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
                        dataPatient.push(aData);
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html
                        (
                            '<div class="btn-group"><button type="button" class="btn btn-info btn-flat"> Action</button><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a href="javascript:void(0)" onclick="editPatient('+aData.idPatient+')" class="text-green"><i class="fa fa-edit"></i>Editer</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="DeletePatient(' + aData.idPatient + ', ' + false + ')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a></li></ul></div>'
                        );

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
                                    return '<span class="badge-no-circle bg-blue"> Homme </span>'
                                }
                                else{
                                    return '<span class="badge-no-circle bg-maroon"> Femme </span>';
                                }
                            }
                        },
                        { "data": "dateNaissance" },
                        { "data": "tel" },
                        { "data": "email" },
                        {
                            "data": "idPatient",
                            "render": function(data, type, row)
                            {
                                if( $userConnect == 1 )
                                    return '<a href="dossiersPatient.php?idPatient=' + data + '" class="text-green"> Leur dossiers  <i class="fa fa-angle-right"></i> </a>';
                                else
                                    return '<a href="dossiersGes.php?idPatient=' + data + '" class="text-green"> Leur dossiers  <i class="fa fa-angle-right"></i> </a>';
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

//    function UpdateInfoPersonnelPatient()
//    {
//        var errors = [];
//
//        var elem = document.querySelectorAll("input,textarea");
//        var t = 0;
//        for (var i = 0; i < elem.length; i++)
//        {
//            if ( elem[i].parentNode.classList.contains("has-error") )
//            {
//                elem[i].parentNode.classList.remove('has-error');
//                elem[i].parentNode.lastElementChild.innerText = '';
//            }
//        }
//
//        var fd = new FormData(document.querySelector('#FormUpdateInfoPersonnelPatient'));
//
//        $.ajax({
//
//            url         :$('#FormUpdateInfoPersonnelPatient').attr('action'),
//            type        : 'post',
//            data        : fd,
//            processData : false,
//            contentType : false,
//            success     : function(data)
//            {
//                if( data == '0' )
//                {
//                    ShowAlertError("La modefication à échoue !");
//                }
//                else if( data == -1 )
//                {
//                    ShowAlertError("La modefication à échoue !, Ce numero de carte national existe déjà !");
//                    var cin = document.getElementById('cinPatient');
//                    cin.parentNode.classList.add('has-error');
//                    cin.parentNode.lastElementChild.innerText = 'Ce numero de carte national existe déjà !';
//                }
//                else
//                {
//                    var resultats = JSON.parse(data);
//
//                    if("nomCmpltPatient" in resultats)
//                    {
//                        $('.labelNomPatient').text(resultats['nomCmpltPatient']);
//                        showAlertSuccess('Les informations est bien modifier');
//                    }
//                    else
//                    {
//                        ShowAlertError("La modification a échoué tout les champs sont obligatoire !");
//
//                        for (var err in resultats)
//                        {
//                            var el = document.getElementById(err);
//                            el.parentNode.classList.add('has-error');
//                            el.parentNode.lastElementChild.innerText = resultats[err];
//                        }
//
//                        for (var err in resultats)
//                        {
//                            var el = document.getElementById(err);
//                            el.parentNode.classList.add('has-error');
//                            el.parentNode.lastElementChild.innerText = resultats[err];
//                        }
//                    }
//                }
//            },
//
//            error : function(status)
//            {
//                console.log(status);
//            },
//
//            complete : function(status)
//            {
//                $('#loaderUpdateInfoPersonnelPatient').hide();
//            }
//
//        });
//    }


    function UpdatePatient(){

        var errors = [];
        $('.alert-popup').hide();

        loaderBtn('btnUpdatePatient', 'Chargement ' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

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

        var fd = new FormData(document.querySelector('#FormEditPatient'));

        $.ajax({

            url         :$('#FormEditPatient').attr('action'),
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
                    var cin = document.querySelector('#modalEditPatient #cinPatient');
                    cin.parentNode.classList.add('has-error');
                    cin.parentNode.lastElementChild.innerText = 'Ce numero de carte national existe déjà !';
                }
                else
                {
                    var resultats = JSON.parse(data);

                    if(!("nomCmpltPatient" in resultats))
                    {
                        ShowAlertError("La modification a échoué tout les champs sont obligatoire !");

                        for (var err in resultats)
                        {
                            var el = document.querySelector('#modalEditPatient #' + err);
                            el.parentNode.classList.add('has-error');
                            el.parentNode.lastElementChild.innerText = resultats[err];
                        }

                        for (var err in resultats)
                        {
                            var el = document.querySelector('#modalEditPatient #' + err);
                            el.parentNode.classList.add('has-error');
                            el.parentNode.lastElementChild.innerText = resultats[err];
                        }
                    }
                    else
                    {
                        getPatients();
                        $('#modalEditPatient').modal('hide');
                        showAlertSuccess('Les informations est bien modifier');
                    }
                }
            },
            error : function(status)
            {
                console.log(status);
            },
            complete : function()
            {
                loaderBtn('btnUpdatePatient', '<i class="fa fa-save"></i>&nbsp; Enregestrer ');
            }

        });
    }

    function editPatient(idPatient)
    {
        var selectedPatient = {};
        for(var i=0; i < dataPatient.length; i++)
        {
            if( dataPatient[i].idPatient == idPatient )
            {
                selectedPatient = dataPatient[i];
            }
        }
        $('#modalEditPatient #idPatient').val(selectedPatient.idPatient);
        $('#modalEditPatient #cinPatient').val(selectedPatient.cin);
        $('#modalEditPatient #nomPatient').val(selectedPatient.nom);
        $('#modalEditPatient #prenomPatient').val(selectedPatient.prenom);
        $('#modalEditPatient #emailPatient').val(selectedPatient.email == 'Null' ? '' : selectedPatient.email );
        $('#modalEditPatient #dateNaissancePatient').val(selectedPatient.dateNaissance);
        $('#modalEditPatient #telephonePatient').val(selectedPatient.tel);
        $('#modalEditPatient #adressePatient').val(selectedPatient.adresse);
        $('#modalEditPatient #viewNomPatient').text(selectedPatient.prenom + ' ' + selectedPatient.nom);

        $('.listeMutuel').val(selectedPatient.mutuel).trigger('change');

        if( selectedPatient.sexe == 'H' )
        {
            $('#modalEditPatient #sexeHomme').attr('checked', true);
            $('#modalEditPatient #sexeFemme').attr('checked', false);
        }
        else
        {
            $('#modalEditPatient #sexeFemme').attr('checked', true);
            $('#modalEditPatient #sexeHomme').attr('checked', false);
        }

        $('#modalEditPatient').modal();
    }

    //save admin :
    function savePatient()
    {
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
                if( data == '1' )
                {
                    getPatients();
                    showAlertSuccess('Le patient est bien jouter !');
                    $("#modalAddPatient").modal('hide');
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
                loaderBtn('btnAddPatient', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
            }

        });
    }

//    $('#btnTe').on( 'click', 'button', function () {
//        var data = table.row( $(this).parents('tr') ).data();
//        alert( data[0] +"'s salary is: "+ data[ 5 ] );
//    } );

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

//    //save admin :
//    function savePatient()
//    {
//        loaderBtn('btnAddPatient', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');
//
//        var elem = document.querySelectorAll("input, textarea");
//        var t = 0;
//        for (var i = 0; i < elem.length; i++)
//        {
//            if ( elem[i].parentNode.classList.contains("has-error") )
//            {
//                elem[i].parentNode.classList.remove('has-error');
//                elem[i].parentNode.lastElementChild.innerText = '';
//            }
//        }
//
//        var fd = new FormData(document.querySelector('#FormAddPatient'));
//
//        $.ajax({
//
//            url         :$('#FormAddPatient').attr('action'),
//            type        : 'post',
//            data        : fd,
//            processData : false,
//            contentType : false,
//
//            success     : function(data)
//            {
//                if( data == "1" )
//                {
//                    showAlertSuccess('Le patient est bien jouter !');
//                }
//                else if( data == '0' )
//                {
//                    ShowAlertError('L\'ajout à échoue !');
//                }
//                else
//                {
//                    ShowAlertError('L\'ajout à échoué tout les champs sont obligatoire !');
//
//                    errors = JSON.parse(data);
//
//                    for (var err in errors)
//                    {
//                        var el = document.getElementById(err);
//                        el.parentNode.classList.add('has-error');
//                        el.parentNode.lastElementChild.innerText = errors[err];
//                    };
//
//                    for (var err in errors)
//                    {
//                        var el = document.getElementById(err);
//                        el.parentNode.classList.add('has-error');
//                        el.parentNode.lastElementChild.innerText = errors[err];
//                    };
//                }
//            },
//
//            error : function(status)
//            {
//                console.log(status);
//            },
//            complete : function()
//            {
//                loaderBtn('btnAddPatient', 'Ajouter');
//            }
//
//        });
//    }

    function showAlertSuccess(text)
    {
        Lobibox.notify('success', { position: 'top right', showClass: 'slideInRight', hideClass: 'slideOutRight', msg: text, title: 'Success'});
    }

    function ShowAlertError(text)
    {
        Lobibox.notify('error', { position: 'top right', showClass: 'slideInRight', hideClass: 'slideOutRight', msg: text, title: 'Erreurs'});
    }

    function loaderBtn(idBtn, text)
    {
        document.querySelector('#' + idBtn).innerHTML = text;
    }


    /***** fin page admin *****/





</script>
<?php require_once "includes/templates/sousFooter.inc"; ?>
