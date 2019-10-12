<?php

    $title      = 'Acceuil';
    $link       = 'acceuil';
    $subLink    = 'acceuil';
    $acceUser = ['admin'];
    $withLoader = false;
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
            Acceuil
        </h1>
        <ol class="breadcrumb">
            <button class="btn btn-warning"> <i class="fa fa-refresh"></i> &nbsp;Actualiser</button>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title"> <i class="fa fa-bar-chart"></i>&nbsp;Etat d'aujourd'hui</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" id="btnRefreshAdmins" ><i class="fa fa-refresh"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="small-box bg-purple-gradient">
                            <div class="inner">
                                <h3>13</h3>
                                <p>Patients Au Salle D'attent</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-people"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-green-gradient">
                            <div class="inner">
                                <h3>13</h3>
                                <p>Rendez Vous Validé</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-checkbox-outline"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-aqua-gradient">
                            <div class="inner">
                                <h3>10</h3>
                                <p>Rendez Vous En Attent</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-time"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-red-gradient">
                            <div class="inner">
                                <h3>10</h3>
                                <p>Rendez Vous Annulé</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-close-outline"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>9</h3>
                                <p>Consulations Affecté</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-stethoscope"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-yellow-gradient">
                            <div class="inner">
                                <h3>2</h3>
                                <p>Patients Enregestrer</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-maroon-gradient">
                            <div class="inner">
                                <h3>10</h3>
                                <p>Dossiers Ouvert</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-folder"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box bg-teal-gradient">
                            <div class="inner">
                                <h3>1500.00  </h3>
                                <p>Etat De Caisse</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-users"></i>&nbsp; Liste des patients au salle d'attent </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" id="btnRefreshAdmins" ><i class="fa fa-refresh"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header table-responsive -->
                    <div class="box-body table-responsive">
                        <table id="tbValidlRdv" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Heur d'arrivée</th>
                                <th>Dossier</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-calendar"></i>&nbsp; Rendez vous d'aujourd'hui </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" id="btnRefreshAdmins" ><i class="fa fa-refresh"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header table-responsive -->
                    <div class="box-body table-responsive">
                        <table id="tblRdvToday" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Heur Rdv</th>
                                <th>Statut</th>
                                <th>Dossier</th>
                                <th>Patient</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-user-circle"></i> Liste des consulations affecter aujourd'hui </h3>
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
                                <th>Patient</th>
                                <th>Heur d'arrivé</th>
                                <th>Date rendez vous</th>
                                <th>Dossier</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-user-circle"></i> Paiments affecter aujourd'hui </h3>
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
                                <th>Patient</th>
                                <th>Dossier</th>
                                <th>Montant à payé</th>
                                <th>Rest</th>
                                <th>Etat</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>             
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script>

$(document).ready(function (){

    getRdvToday();
    getValideRdv();

});
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

                "columns":
                [

                    { "data": "heurRdv" },
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
                                return '<span class="badge bg-yellow"> Expirer </span>';
                            }
                            else if( active && enAttent )
                            {
                                return '<span class="badge bg-blue"> En Attent</span>';
                            }
                            else if( annule && enAttent)
                            {
                                return '<span class="badge bg-red"> Annulé </span>';
                            }
                            else if( valider  )
                            {
                                return '<span class="badge bg-green"> Validé </span>';
                            }
                            else if( expirer )
                            {
                                return '<span class="badge bg-yellow"> Expirer </span>';
                            }
                        }
                    },
                    {
                        "data": "dossier",

                        "render": function(data, type, row)
                        {
                            return "<a href=dossierPatient.php?idDossier=" + data + "  class='text-green text-bold'> Accéder au dossier &nbsp;<i class='ion ion-ios-arrow-forward'></i> </a>";
                        }
                    },
                    {
                        "data": "patient",

                        "render": function(data, type, row)
                        {
                            return "<a href=viewPatient.php?idPatient=" + row['idPatient'] + ">" + data + "</a>";
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
            consolelog(resultat);
        }

    });
}
// get rdv of all today :
function getValideRdv()
{
    $.ajax({

        url : 'includes/functions/controller.php?action=getValideRdv',

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            $('#tbValidlRdv').DataTable({

                data : res2,

                destroy: true,

                "columns":
                [
                    {
                        "data": "patient",

                        "render": function(data, type, row)
                        {
                            return "<a href=viewPatient.php?idPatient=" + row['idPatient'] + ">" + data + "</a>";
                        }
                    },
                    {
                        "data": "heurArrivee",

                        "render": function(data, type, row)
                        {
                            return "<span class='badge bg-blue' >" + data + "</span>";
                        }
                    },
                    {
                        "data": "dossier",

                        "render": function(data, type, row)
                        {
                            return "<a href=dossierPatient.php?idDossier=" + data + " class='text-green text-bold' >" + "Accéder au dossier &nbsp;<i class='ion ion-ios-arrow-forward'></i> </a>";
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
            consolelog(resultat);
        }

    });
}
//



</script>
<!--<script src="layout/js/admins.js"></script>-->

