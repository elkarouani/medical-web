<?php

    $title      = 'Ajouter Nouveau Admin';
    $link       = 'admins';
    $subLink    = 'addAdmin';
    $acceUser = ['admin'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Nouveau administrateur
        </h1>
        <ol class="breadcrumb">
            <li><a href="admins.php" class="btn btn-primary"><i class="fa fa-user-md"></i> Liste des administrateurs</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="includes/functions/controller.php?action=addAdmin" method="post" id="FormAddAdmin" enctype="multipart/form-data" >
            <div class="row">
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">
                                <i class="fa fa-user-plus"></i> &nbsp;Ajouter nouveau administrateur
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomAdmin" class="control-label">Nom</label>
                                        <input type="text" id="nomAdmin" name="nomAdmin" class="form-control" placeholder="Nom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomAdmin" class="control-label">Prenom</label>
                                        <input type="text" id="prenomAdmin" name="prenomAdmin" class="form-control" placeholder="Prenom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephoneAdmin" class="control-label">Telephone</label>
                                        <input type="tel" id="telephoneAdmin" name="telephoneAdmin" class="form-control" placeholder="Telephone" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailAdmin" class="control-label">Email</label>
                                        <input type="email" id="emailAdmin" name="emailAdmin" class="form-control" placeholder="Email" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="loginAdmin" class="control-label">Login</label>
                                        <input type="text" id="loginAdmin" name="loginAdmin" class="form-control" placeholder="Login" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passAdmin" class="control-label">Mot de passe</label>
                                        <input type="password" id="passAdmin" name="passAdmin" class="form-control" placeholder="Mot de passe" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ConfirmPassAdmin" class="control-label">Confirmation mot de passe</label>
                                        <input type="password" id="confirmPassAdmin" name="confirmPassAdmin" class="form-control" placeholder="Confirmation mot de pass" >
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
                                                <input type="radio" value="H" name="sexeAdmin" checked > Homme
                                            </label>

                                            <label style="margin-left: 30px">
                                                <input type="radio" value="F" name="sexeAdmin" > Femme
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="button" id="btnAddAdmin" class="btn btn-success btn-flat"> <i class="fa fa-check"></i> Enregestrer </button>
                            <button type="reset" id="btnNewAdmin" class="btn btn-warning btn-flat"> <i class="fa fa-user-plus"></i> Nouveau administrateur </button>
                        </div>
                        <div class="overlay" id="loaderAddAdmin" style="display:none">
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
                            <img id="imageAdmin" class="profile-user-img img-responsive img-circle" style="width: 150px; height: 150px;" src="data/uploades/avatarAdmins/no_image.jpg" >
                        </div>
                        <div class="box-footer">
                            <input type="file" name="imageAdmin" onchange="document.getElementById('imageAdmin').src = window.URL.createObjectURL(this.files[0])" id="inputFileImage" style='display:none' >
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
<!--<script src="layout/js/admins.js"></script>-->
<script>
    /****** début page admin *****/

    $(document).ready(function(){
        getAdmins();

        $(".modal").modal({
            "backdrop"  : "static",
            "keyboard"  : true,
            "show"      : false
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('#btnAddAdmin').click(function(){
            SaveAdmin();
        });

        $('#btnChoisieImage').click(function () {
            $('#inputFileImage').click();
        });

        $('#btnNewAdmin').click(function () {

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


    /* Admin functions */
    // get admins :
    function getAdmins()
    {
        $('#loader2').show();

        $.ajax({

            url : 'includes/functions/controller.php?action=getAllAdmins',

            type : 'GET',

            success : function(res)
            {
                res2 = JSON.parse(res);

                $('#tblAdmin').DataTable({

                    data : res2,

                    destroy: true,

                    "fnRowCallback": function (nRow, aData, iDisplayIndex)
                    {
                        var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                        $("td:last", nRow).html(
                            '<button onclick="DeleteAdmin(' + aData.idAdmin + ', false)" id="btnDeleteAdmin" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                            '<i class="fa fa-trash-o"></i>' +
                            '</button>'

                            +

                            '  <a style="display:inline-block" href="editAdmin.php?idAdmin=' + aData.idAdmin + '" class="btn btn-success btn-sm btn-flat">' +
                            '<i class="fa fa-edit"></i>' +
                            '</a>'

                            +

                            '  <a style="display:inline-block" href="viewAdmin.php?idAdmin=' + aData.idAdmin + '" class="btn btn-info btn-sm btn-flat">' +
                            '<i class="fa fa-eye"></i> Voire les details' +
                            '</a>'
                        )

                        return nRow;
                    },

                    "columns": [
                        {
                            "data": "image",
                            "render": function(data, type, row)
                            {
                                return '<img class="img-circle" width="60" height="60" src="http://localhost/siteCabinetDentaire/admin/data/uploades/avatarAdmins/' + data + '" />';
                            }
                        },
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
                        { "data": "tel" },
                        { "data": "email" },
                        { "data": "login" },
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
                        { "data": "image" }

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
                alert('nooo !!');
            },

            complete : function(resultat, statut){
                $('#loader2').hide();
            }

        });
    }

    //save admin :
    function SaveAdmin()
    {
        $('#loaderAddAdmin').show();
        var elem = document.getElementsByTagName("input");
        var t = 0;
        for (var i = 0; i < elem.length; i++)
        {
            if ( elem[i].parentNode.classList.contains("has-error") )
            {
                elem[i].parentNode.classList.remove('has-error');
                elem[i].parentNode.lastElementChild.innerText = '';
            }
        }

        var fd = new FormData(document.querySelector('#FormAddAdmin'));

        $.ajax({

            url         :$('#FormAddAdmin').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    ShowAlertSuccess('L\'Administrateur est bien ajouter !');
                    $('#btnNewAdmin').click();
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
                $('#loaderAddAdmin').hide();
            }

        });
    }

    // delete admin :
    function DeleteAdmin(idAdmin, reload)
    {
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce admin !",
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

                        url  : 'includes/functions/controller.php?action=deleteAdmin&idAdmin=' + idAdmin,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                if( reload == false )
                                {
                                    getAdmins();
                                }
                                else
                                {
                                    window.location.href = 'admins.php';
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
                        }

                    });
                });
            }
        }]);
    }
    /***** fin page admin *****/

</script>
