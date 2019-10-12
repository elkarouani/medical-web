<?php

$title      = 'Ajouter Nouveau Gestionnaire';
$link       = 'gestionnaires';
$subLink    = 'addGestionnaire';
$acceUser = ['admin'];
$withLoader = false;
$acceUser = ['admin'];
include_once "includes/templates/header.inc";
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
            Nouveau gestionnaire
        </h1>
        <ol class="breadcrumb">
            <li><a href="gestionnaires.php" class="btn btn-primary"><i class="fa fa-users"></i> Liste des gestionnaires</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="includes/functions/controller.php?action=addGestionnaire" method="post" id="FormAddGestionnaire" enctype="multipart/form-data" >
            <div class="row">
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">
                                <i class="fa fa-user-plus"></i>&nbsp;Ajouter nouveau gestionnaire
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomGestionnaire" class="control-label">Nom</label>
                                        <input type="text" id="nomGestionnaire" name="nomGestionnaire" class="form-control" placeholder="Nom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomGestionnaire" class="control-label">Prenom</label>
                                        <input type="text" id="prenomGestionnaire" name="prenomGestionnaire" class="form-control" placeholder="Prenom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephoneGestionnaire" class="control-label">Telephone</label>
                                        <input type="tel" id="telephoneGestionnaire" name="telephoneGestionnaire" class="form-control" placeholder="Telephone" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailGestionnaire" class="control-label">Email</label>
                                        <input type="email" id="emailGestionnaire" name="emailGestionnaire" class="form-control" placeholder="Email" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="loginGestionnaire" class="control-label">Login</label>
                                <input type="text" id="loginGestionnaire" name="loginGestionnaire" class="form-control" placeholder="Login" >
                                <span class="help-block"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passGestionnaire" class="control-label">Mot de passe</label>
                                        <input type="password" id="passGestionnaire" name="passGestionnaire" class="form-control" placeholder="Mot de passe" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ConfirmPassGestionnaire" class="control-label">Confirmation mot de pass</label>
                                        <input type="password" id="confirmPassGestionnaire" name="confirmPassGestionnaire" class="form-control" placeholder="Confirmation mot de pass" >
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
                                                <input type="radio" value="H" name="sexeGestionnaire" checked > Homme
                                            </label>

                                            <label style="margin-left: 30px">
                                                <input type="radio" value="F" name="sexeGestionnaire" > Femme
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="adresseGestionnaire" class="control-label">Adresse</label>
                                <textarea name="adresseGestionnaire" class="form-control" id="adresseGestionnaire" placeholder="Adresse..." cols="20" rows="4"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="button" id="btnAddGestionnaire" class="btn btn-success btn-flat"> <i class="fa fa-check"></i> Enregestrer </button>
                            <button type="reset" id="btnNewGestionnaire" class="btn btn-warning btn-flat"> <i class="fa fa-user-plus"></i> Nouveau Gestionnaire </button>
                        </div>
                        <div class="overlay" id="loaderAddGestionnaire" style="display:none">
                            <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"> <i class="fa fa-picture"></i> &nbsp; Image de profile </h3>
                        </div>
                        <div class="box-body">
                            <img id="imageGestionnaire" class="profile-user-img img-responsive img-circle" style="width: 150px; height: 150px;" src="data/uploades/avatarGestionnaires/no_image.jpg" >
                        </div>
                        <div class="box-footer">
                            <input type="file" name="imageGestionnaire" onchange="document.getElementById('imageGestionnaire').src = window.URL.createObjectURL(this.files[0])" id="inputFileImage" style='display:none' >
                            <button id="btnChoisieImage" type="button" class="btn btn-primary btn-sm btn-block btn-flat"> <i class="fa fa-upload"></i> &nbsp; Choisie une image</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<!--<script src="layout/js/gestionaires.js"></script>-->
<script>
    /******** Début page gestionnaires *******/

    $(document).ready(function(){

        getGestionnaires();

        $('#btnChoisieImage').click(function () {
            $('#inputFileImage').click();
        });

        $('#btnAddGestionnaire').click(function () {
            saveGestionnaire();
        });

        $('#btnUpdateInfoGestionnaire').click(function () {
            updateInfoGenGestionnaire();
        });

        $('#btnChangePass').click(function () {
            ChangePassGestionnaire();
        });

        $('#btnUpdateLoginGestionnaire').click(function () {
            UpdateLoginGestionnaire();
        });

        $('#btnUpdateImageGestionnaire').click(function () {
            UpdateImageGestionnaire();
        });

        $('#btnNewGestionnaire').click(function () {
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

    // get getGestionnaires :
    function getGestionnaires()
    {
        $.ajax({

            url : 'getGestionnaires.php',

            type : 'GET',

            success : function(res){
                var res2 = JSON.parse(res);
                $('#tblGestionnaires').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html(
                            '<button onclick="DeleteGestionnaire(' + aData.idGestionaire + ',' + false + ')"  id="btnDeleteGestionnaire" style="display:inline-block"  class="btn btn-danger btn-sm">' +
                            '<i class="fa fa-trash-o"></i>' +
                            '</button>'

                            +

                            '  <a style="display:inline-block" href="editGestionnaire.php?idGestionnaire=' + aData.idGestionaire + '" class="btn btn-success btn-sm">' +
                            '<i class="fa fa-edit"></i>' +
                            '</a>'

                            +

                            '  <a style="display:inline-block" href="viewGestionnaire.php?idGestionnaire=' + aData.idGestionaire + '" class="btn btn-info btn-sm">' +
                            '<i class="fa fa-eye"></i> &nbsp;Voire les détials' +
                            '</a>'
                        );

                        return nRow;
                    },

                    "columns": [
                        {
                            "data": "image",
                            "render": function(data, type, row)
                            {
                                return '<img class="img-circle" width="70" heigth="70" src="http://localhost/siteCabinetDentaire/admin/data/uploades/avatarGestionnaires/' + data + '" />';
                            }
                        },
                        { "data" : "nom" },
                        { "data" : "prenom" },
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
                        { "data" : "tel" },
                        { "data" : "email" },
                        { "data" : "login" },
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
                        { "data" : "login" }

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

    // Save Gestionnaire :
    function saveGestionnaire()
    {
        $('#loaderAddGestionnaire').show();

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

        var fd = new FormData(document.querySelector('#FormAddGestionnaire'));

        $.ajax({

            url         :$('#FormAddGestionnaire').attr('action'),
            type        : 'post',
            data        : fd,
            processData: false,
            contentType: false,

            success     : function(data)
            {
                if( data == '1' )
                {
                    showAlertSuccess('Le Gestionnaire est bien ajouter !');
                    $('#btnNewGestionnaire').click();
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
                    }

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
                $('#loaderAddGestionnaire').hide();
            }

        });
    }

    // delete admin :
    function DeleteGestionnaire(idGestionnaire, reload)
    {
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce gestionnaire ?",
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

                        url  : 'includes/functions/controller.php?action=deleteGestionnaire&idGestionnaire=' + idGestionnaire,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                if( !reload )
                                {
                                    getGestionnaires();
                                }
                                else
                                {
                                    window.location.href = 'gestionnaires.php';
                                }
                                swal( 'Good !', 'La suppression est bien effectué !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer ce compte !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce compte !', 'error' ).insertQueueStep(1);
                            resolve();
                        },

                        complete : function ()
                        {
                        }

                    });
                });
            }
        }]);
    }

    // update infos general gestionnaire :
    function updateInfoGenGestionnaire()
    {
        var errors = [];

        $('.alert-popup').hide();

        loaderBtn('btnUpdateInfoGestionnaire', 'Chargement ' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.querySelectorAll("input, texearea");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.parentNode.classList.remove('has-error');
                elem[i].parentNode.parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormUpdateGestionnaire'));

        $.ajax({

            url         :$('#FormUpdateGestionnaire').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    loaderBtn('btnUpdateInfoGestionnaire', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                    showAlertSuccess('Les informations est bien modifier');
                }
                else if( data == '0' )
                {
                    loaderBtn('btnUpdateInfoGestionnaire', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                    ShowAlertError("l'ajout a echoue !");
                }
                else
                {
                    loaderBtn('btnUpdateInfoGestionnaire', '<i class="fa fa-edit"></i>Enregestrer les modifications');
                    ShowAlertError("La modification a échoué tout les champs sont obligatoire !");

                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    }

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    }
                }
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

    // methode change password gestionnaire :
    function ChangePassGestionnaire(){

        var errors = [];
        $('.alert-popup').hide();
        loaderBtn('btnChangePass', 'Chargement...' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.getElementsByTagName("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.parentNode.classList.remove('has-error');
                elem[i].parentNode.parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormChangePass'));

        $.ajax({

            url         :$('#FormChangePass').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    loaderBtn('btnChangePass', '<i class="fa fa-edit"></i> Changer le mot de pass');
                    //getAdmins();
                    showAlertSuccess('Le mot de pass est bien changer !');
                }
                else if( data == '0' )
                {
                    loaderBtn('btnChangePass', '<i class="fa fa-edit"></i> Changer le mot de pass');
                    ShowAlertError("Le mot de pass n'est pas été changer !");
                }
                else
                {
                    loaderBtn('btnChangePass', '<i class="fa fa-edit"></i> Changer le mot de pass');
                    ShowAlertError("Le mot de pass n'est pas été changer !");
                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };
                }
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

    // modification login gestionnaire :
    function UpdateLoginGestionnaire(){

        var errors = [];
        $('.alert-popup').hide();
        loaderBtn('btnUpdateLoginGestionnaire', 'Chargement...' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var elem = document.getElementsByTagName("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.parentNode.classList.remove('has-error');
                elem[i].parentNode.parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormUpdateLoginGestionnaire'));

        $.ajax({

            url         :$('#FormUpdateLoginGestionnaire').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    loaderBtn('btnUpdateLoginGestionnaire', '<i class="fa fa-edit"></i> Modifier');
                    showAlertSuccess('Les informations est bien modifier');
                }
                else if( data == '0' )
                {
                    loaderBtn('btnUpdateLoginGestionnaire', '<i class="fa fa-edit"></i> Modifier');
                    ShowAlertError("Respecter le format de login !");
                }
                else
                {
                    loaderBtn('btnUpdateLoginGestionnaire', '<i class="fa fa-edit"></i> Modifier');
                    ShowAlertError("Respecter le format de login !");
                    errors = JSON.parse(data);

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };

                    for (var err in errors)
                    {
                        var el = document.getElementById(err);
                        el.parentNode.parentNode.classList.add('has-error');
                        el.parentNode.parentNode.lastElementChild.innerText = errors[err];
                    };
                }
            },
            error : function(status)
            {
                console.log(status);
            }

        });
    }

    // methode change image gestionnaire :
    function UpdateImageGestionnaire()
    {
        $('.alert-popup').hide();

        loaderBtn('btnUpdateImageGestionnaire', 'Chargement ' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var fd = new FormData(document.querySelector('#FormUpdateImageGestionnaire'));

        $.ajax({

            url         :$('#FormUpdateImageGestionnaire').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '-1' )
                {
                    ShowAlertError("Le format d'image est incorrect !");
                }
                else if ( data == '0' )
                {
                    ShowAlertError("Impossible de modifier l'image !");
                }
                else if ( data == '1' )
                {
                    showAlertSuccess('L\'image à été bien enregestrer !');
                }
                else if ( data == '2' )
                {
                    ShowAlertError("Impossible de modifier l'image, Choiosie une image !");
                }
                else
                {
                    console.log('noooo');
                }
            },
            error : function(status)
            {
                console.log(status);
            },
            complete : function ()
            {
                loaderBtn('btnUpdateImageGestionnaire', 'Modifier l\'image');
            }
        });
    }



    /******** Fin page gestionnaires *******/
</script>
