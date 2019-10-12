<?php

    $title      = 'Admins';
    $link       = 'admins';
    $subLink    = 'admins';
    $acceUser = ['admin'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $admins = getDatas("select * from admin ", []);
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

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
            Administrateurs
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="addAdmin.php" class="btn btn-primary" > <i class="fa fa-user-plus"></i> Nouveau administrateur </a>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"> <i class="fa fa-user-circle"></i> Liste des administrateurs </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" id="btnRefreshAdmins" ><i class="fa fa-refresh"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
              <table id="tblAdmin" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Sexe</th>
                            <th>Telehpne</th>
                            <th>Email</th>
                            <th>Login</th>
                            <th>Etat</th>
                        </tr>
                    </thead>
              </table>
            </div>
        </div>
            <!-- /.box-body -->
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
                                    return '<span class="badge-no-circle bg-blue"> Homme </span>'
                                }
                                else{
                                    return '<span class="badge-no-circle bg-maroon"> Femme </span>';
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
                                    return '<span class="badge-no-circle bg-green"> active </span>'
                                }
                                else{
                                    return '<span class="badge-no-circle bg-red"> desactive </span>';
                                }
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