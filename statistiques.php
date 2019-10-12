<?php

    $title      = 'Statistiques';
    $link       = 'statistiques';
    $subLink    = 'statistiques';
    $acceUser = ['admin'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

    $initialAnnee = getData("SELECT YEAR(MIN(dossier.dateCreation)) AS initialAnnee FROM dossier", [])["initialAnnee"];
    $diff = date("Y") - $initialAnnee;
    $annee = [];
    array_push( $annee, $initialAnnee );
    for( $i = 0; $i < $diff; $i++ )
    {
        $initialAnnee += 1;
        array_push( $annee, $initialAnnee );
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-bar-chart"></i>&nbsp;Statistiques
        </h1>
        <ol class="breadcrumb">
            Filtrer par année &nbsp;
            <select style="min-width: 148px;" name="" id="annee" class="select2">
                <?php foreach( $annee as $ann ): ?>
                    <option value="<?= $ann; ?>"><?= $ann; ?></option>
                <?php endforeach; ?>
            </select>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-solid box-primary" style="border-radius: 0px;margin-bottom: 10px;">
            <div class="box-header no-border" style="padding: 12px;">
                <h3 class="box-title"><i class="fa fa-line-chart"></i>&nbsp; Etats</h3>
                <div class="box-tools pull-right">
                    Filtrer par mois : &nbsp;
                    <span class="select-chart">
                        <select name="" id="moisStatiEtats" class="select2">
                            <option value="0">Tout l'année</option>
                            <option value="1">Janvier</option>
                            <option value="2">Février</option>
                            <option value="3">Mars</option>
                            <option value="4">Avril</option>
                            <option value="5">Mai</option>
                            <option value="6">Juin</option>
                            <option value="7">Juillet</option>
                            <option value="8">Juin</option>
                            <option value="8">Août</option>
                            <option value="9">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Décembre</option>
                        </select>
                    </span>
                </div>
            </div>
            <div style="padding: 15px 10px 0px 10px;" class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-blue-gradient">
                            <div class="inner">
                                <h3 id="nbrConsultations">0</h3>
                                <p>Consultations</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-stethoscope"></i>
                            </div>
                            <a href="dossiers.php" class="small-box-footer">Lire Plus&nbsp; <i class="ion ion-android-arrow-forward"></i> </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="margin-bottom: 15px;" class="small-box bg-maroon-gradient">
                            <div class="inner">
                                <h3 id="nbrDossier">0</h3>
                                <p>Dossiers ouvert</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-folder"></i>
                            </div>
                            <a href="dossiers.php" class="small-box-footer">Lire Plus&nbsp; <i class="ion ion-android-arrow-forward"></i> </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="margin-bottom: 15px;" class="small-box bg-green-gradient">
                            <div class="inner">
                                <h3 id="nbrRdvValid">0</h3>
                                <p>Rendez vous validé</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-checkbox-outline"></i>
                            </div>
                            <a href="rendezVous.php" class="small-box-footer">Lire Plus&nbsp; <i class="ion ion-android-arrow-forward"></i> </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="margin-bottom: 15px;" class="small-box bg-red-gradient">
                            <div class="inner">
                                <h3 id="chiffreAffaire">0.00</h3>
                                <p>Chiffre d'affaire</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-social-usd-outline"></i>
                            </div>
                            <a href="paiements.php" class="small-box-footer">Lire Plus&nbsp; <i class="ion ion-android-arrow-forward"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!--        <hr style="border-top: 1px solid #dbdbdb;margin-top: 1px; margin-bottom: 20px">-->

        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid box-danger" style="border-radius: 0px">
                    <div class="box-header with-border" style="padding: 12px;">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i>&nbsp;Statistiques par sexe</h3>
                        <div class="box-tools pull-right">
                            Filtrer par mois : &nbsp;
                            <span class="select-chart">
                                <select name="" id="moisStatiSexePatient" class="select2">
                                    <option value="0">Tout l'année</option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Juin</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chartPourcentagePatientParSexe"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-solid box-success" style="border-radius: 0px">
                    <div class="box-header with-border" style="padding: 12px;">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i>&nbsp; Statistiques par age</h3>
                        <div class="box-tools pull-right">
                            Filtrer par mois : &nbsp;
                            <span class="select-chart">
                                <select style="min-width: 148px;" name="" id="moisStatiAgePatient" class="select2">
                                    <option value="0">Tout l'année</option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Juin</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chartPourcentagePatientParAge"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header with-border" style="padding: 12px;">
                        <h3 class="box-title"> <i class="fa fa-line-chart"></i>&nbsp; Nbr.Dossier ouvert / année</h3>
                        <div class="box-tools pull-right">
                            Filtrer par année: &nbsp;
                            <span class="select-chart">
                                <select style="min-width: 148px;" name="" id="anneeDossierOuvert" class="select2">
                                    <?php foreach( $annee as $ann ): ?>
                                        <option value="<?= $ann; ?>"><?= $ann; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chartDossierOuvert"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header with-border" style="padding: 12px;">
                        <h3 class="box-title"> <i class="fa fa-line-chart"></i>&nbsp; Nbr.Consultations / année</h3>
                        <div class="box-tools pull-right">
                            Filtrer par année: &nbsp;
                            <span class="select-chart">
                                <select style="min-width: 148px;" name="" id="anneeConsultations" class="select2">
                                    <?php foreach( $annee as $ann ): ?>
                                        <option value="<?= $ann; ?>"><?= $ann; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chartConsultations"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header with-border" style="padding: 12px;">
                        <h3 class="box-title"> <i class="fa fa-line-chart"></i>&nbsp; Nombre du rendez vous validé / annullé par mois</h3>
                        <div class="box-tools pull-right">
                            Filtrer par année: &nbsp;
                            <span class="select-chart">
                                <select style="min-width: 148px;" name="" id="anneeRdv" class="select2">
                                    <?php foreach( $annee as $ann ): ?>
                                        <option value="<?= $ann; ?>"><?= $ann; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chartRdv"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-solid box-primary" style="border-radius: 0px">
                    <div class="box-header with-border" style="padding: 12px;">
                        <h3 class="box-title"> <i class="fa fa-line-chart"></i>&nbsp; Chiffre d'affaire par mois</h3>
                        <div class="box-tools pull-right">
                            Filtrer par année: &nbsp;
                            <span class="select-chart">
                                <select style="min-width: 148px;" name="" id="anneePaiements" class="select2">
                                    <?php foreach( $annee as $ann ): ?>
                                        <option value="<?= $ann; ?>"><?= $ann; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chartPaiements"></canvas>
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
<script src="layout/js/Chart.min.js"></script>
<script>

$(document).ready(function ()
{
    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth();

    $("#annee").val(currentYear).trigger("change");
    $("#anneeDossierOuvert").val(currentYear).trigger("change");
    $("#anneeConsultations").val(currentYear).trigger("change");
    $("#anneeRdv").val(currentYear).trigger("change");
    $("#anneePaiements").val(currentYear).trigger("change");
    //
    $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
    {
        $('body').css('padding-right','0 !important');
    });

    $.fn.modal.Constructor.prototype.setScrollbar = function () { };

    $('.select2').select2({ "width": "100%", "width": "120px" });

    getEtats();
    getStatistiquesDossierOuvert();
    getStatistiquesConsultations();
    getStatistiquesRdv();
    getStatistiquesPaiement();
    getStatistiquesPatientParSexe();
    getStatistiquesPatientParAge();

    $('#moisStatiEtats').change(function(){
        getEtats();
    });

    $('#annee').change(function(){
        getEtats();
        getStatistiquesDossierOuvert();
        getStatistiquesConsultations();
        getStatistiquesRdv();
        getStatistiquesPaiement();
        getStatistiquesPatientParSexe();
        getStatistiquesPatientParAge();
    });

    $('#anneeDossierOuvert').change(function(){
        getStatistiquesDossierOuvert();
    });


    $('#anneeConsultations').change(function(){
        getStatistiquesConsultations();
    });

    $('#moisStatiSexePatient').change(function(){
        getStatistiquesPatientParSexe();
    });

    $('#moisStatiAgePatient').change(function(){
        getStatistiquesPatientParAge();
    });

    $('#anneeRdv').change(function(){
        getStatistiquesRdv();
    });

    $('#anneePaiements').change(function(){
        getStatistiquesPaiement();
    });

    function getStatistiquesPatientParSexe()
    {
        var annee = $('#annee').val();
        var mois = ($('#moisStatiSexePatient').val() != 0 ) ? $('#moisStatiSexePatient').val() : '';
        var title = 'Statistiques sexe du patients à l\'année : ' + annee;
        if( mois != "" ){ title = 'Statistiques sexe du patients à l\'année : ' + annee + ' au mois : ' + mois };

        $.ajax({

            url : 'includes/functions/controller.php?action=getPorcentagePatientParSexe&annee=' + annee + "&mois=" + mois,

            type : 'GET',

            success : function(res)
            {
                var nbrHomme = res.nbrHomme;
                var nbrFemme = res.nbrFemme;
                var nonResultat = 0;

                if( nbrFemme == 0 && nbrHomme == 0 )
                {
                    nonResultat = 1;
                }

                new Chart($("#chartPourcentagePatientParSexe"), {
                    type: 'pie',
                    data: {
                        labels: ["Homme", "Femme", "Aucune resultats"],
                        datasets: [
                            {
                                backgroundColor: ["#0073b7", "#d81a5f", "#757575"],
                                data: [nbrHomme, nbrFemme, nonResultat]
                            }
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: title
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function getEtats()
    {
        var annee = $('#annee').val();
        var mois = ($('#moisStatiEtats').val() != 0 ) ? $('#moisStatiEtats').val() : '';
        var nbrDossier = $('#nbrDossier');
        var nbrRdv = $('#nbrRdv');
        var nbrConsultations = $('#nbrConsultations');
        var chiffreAffaire = $('#chiffreAffaire');

        $.ajax({

            url : 'includes/functions/controller.php?action=getEtatsParMois&annee=' + annee + "&mois=" + mois,

            type : 'GET',

            success : function(res)
            {
                nbrDossier.text( res.nbrDossiers );
                nbrConsultations.text( res.nbrConsultations );
                nbrRdv.text( res.nbrRdvValide );
                chiffreAffaire.text( Math.round(res.chiffreAffaire).toFixed(2) + '  DH' );
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function getStatistiquesPatientParAge()
    {
        var annee = $('#annee').val();
        var mois = ($('#moisStatiAgePatient').val() != 0 ) ? $('#moisStatiAgePatient').val() : '';
        var title = 'Statistiques par age du patients à l\'année : ' + annee;
        if( mois != "" ){ title = 'Statistiques par age du patients à l\'année : ' + annee + ' au mois : ' + mois };

        $.ajax({

            url : 'includes/functions/controller.php?action=getPorcentagePatientParAge&annee=' + annee + '&mois=' + mois,

            type : 'GET',

            success : function(res)
            {
                var nbrNourissons = res.nbrNourisson;
                var nbrEnfants    = res.nbrEnfants;
                var nbrAdultes    = res.nbrAdultes;
                var nonResult     = 0;

                if( (nbrNourissons == 0) && (nbrEnfants == 0) && (nbrAdultes == 0) )
                {
                    nonResult = 1;
                }

                new Chart($("#chartPourcentagePatientParAge"), {
                    type: 'pie',
                    data: {
                        labels: ["Nourissons", "Enfants", "Adultes", "Aucun resultats.."],
                        datasets:
                        [
                            {
                                backgroundColor: ["#00c0ef", "#f39b11", "#f56853", "#757575"],
                                data: [nbrNourissons, nbrEnfants, nbrAdultes, nonResult]
                            }
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: title
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }
        });
    }

    function getStatistiquesDossierOuvert()
    {
        var annee = $('#anneeDossierOuvert').val();
        $.ajax({

            url : 'includes/functions/controller.php?action=getNbrDossierParMois&annee=' + annee,

            type : 'GET',

            success : function(res)
            {
                new Chart(document.getElementById("chartDossierOuvert"), {
                    type: 'line',
                    data: {
                        labels: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],

                        datasets:
                            [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,1)",
                                    pointColor : "rgba(220,220,220,1)",
                                    pointStrokeColor : "#fff",
                                    data: res,
                                    label: "Dossiers",
                                    borderColor: "#3e95cd"
                                }
                            ]
                    },
                    options: {
                        title: {
                            display: false,
                            text: 'Nombre de dossier ouvert à : ' + annee
                        }
                    }
                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function getStatistiquesPaiement()
    {
        var annee = $('#anneePaiements').val();
        $.ajax({

            url : 'includes/functions/controller.php?action=getChiffreAffaireParMois&annee=' + annee,

            type : 'GET',

            success : function(res)
            {
                new Chart(document.getElementById("chartPaiements"), {
                    type: 'line',
                    data: {
                        labels: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],

                        datasets:
                            [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,1)",
                                    pointColor : "rgba(220,220,220,1)",
                                    pointStrokeColor : "#fff",
                                    data: res,
                                    label: "Chiffre d'affaire",
                                    borderColor: "#3e95cd"
                                }
                            ]
                    },
                    options: {
                        title: {
                            display: false,
                            text: "Chiffre d'affaire par mois à : " + annee
                        }
                    }
                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function getStatistiquesRdv()
    {
        var annee = $('#anneeRdv').val();
        $.ajax({

            url : 'includes/functions/controller.php?action=getNbrRdvParMois&annee=' + annee,

            type : 'GET',

            success : function(res)
            {
                new Chart(document.getElementById("chartRdv"), {
                    type: 'bar',
                    data: {
                        labels: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],

                        datasets:
                            [
                                {
                                    data: res.rdvAnnule,
                                    backgroundColor: "#dd4b39",
                                    label: "Rendez vous annullé",
                                    fill: false
                                },
                                {
                                    backgroundColor: "#00a65a",
                                    data: res.rdvValid,
                                    label: "Rendez vous validé",
                                    fill: false
                                }
                            ]
                    },
                    options: {
                        title: {
                            display: false,
                            text: 'Nombre de rendez vous validé et annullé à : ' + annee
                        }
                    }
                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

    function getStatistiquesConsultations()
    {
        var annee = $('#anneeConsultations').val();
        $.ajax({

            url : 'includes/functions/controller.php?action=getNbrConsultationsParMois&annee=' + annee,

            type : 'GET',

            success : function(res)
            {
                console.log(res);
                new Chart(document.getElementById("chartConsultations"), {
                    type: 'line',
                    data: {
                        labels: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],

                        datasets:
                            [
                                {
                                    fillColor : "rgba(220,220,220,0.5)",
                                    strokeColor : "rgba(220,220,220,1)",
                                    pointColor : "rgba(220,220,220,1)",
                                    pointStrokeColor : "#fff",
                                    data: res,
                                    label: "Consultation",
                                    borderColor: "#3e95cd"
                                }
                            ]
                    },
                    options: {
                        title: {
                            display: false,
                            text: 'Nombre du consultations à : ' + annee
                        }
                    }
                });
            },

            error : function(resultat, statut, erreur){
                console.log(resultat);
            }

        });
    }

});
//



</script>
<!--<script src="layout/js/admins.js"></script>-->

