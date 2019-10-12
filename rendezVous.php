<?php
    $title = 'Rendez-Vous';
    $link       = 'rendezVous';
    $subLink    = 'rendezVous';
    $acceUser = ['admin', 'gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $userConnect['user'] == 'gestionnaire' ? include_once "includes/templates/asideGestionnaire.inc" : include_once "includes/templates/aside.inc";

    $params   = getData("SELECT CONCAT(HOUR(dureeRdv), ' h ',MINUTE(dureeRdv), ' minutes') AS dureeRdv, CONCAT(HOUR(dureeRdv), ':',MINUTE(dureeRdv), ':00') AS slotDuration,  (TIME_TO_SEC(dureeRdv) / 60) AS nbrMinutes FROM parametrage WHERE etat = 1", []);
    $patients = getDatas('SELECT * FROM patient', []);
    $typeRdv  = getDatas("SELECT * FROM typerdv", []);

    // get maxTime and miTime :
    $sql = "SELECT * FROM horaire WHERE active = 1";
    $horaire = getData($sql, []);

    $minHoraire = $horaire['lundiMatinDebut'];

    if( ($horaire['lundiMatinDebut'] != null) && $minHoraire > $horaire['lundiMatinDebut'] )
    {
        $minHoraire = $horaire['lundiMatinDebut'];
    }
    if( ($horaire['mardiMatinDebut'] != null) && $minHoraire > $horaire['mardiMatinDebut'] )
    {
        $minHoraire = $horaire['mardiMatinDebut'];
    }
    if( ($horaire['mercrediMatinDebut'] != null) && $minHoraire > $horaire['mercrediMatinDebut'] )
    {
        $minHoraire = $horaire['mercrediMatinDebut'];
    }
    if( ($horaire['jeudiMatinDebut'] != null) && $minHoraire > $horaire['jeudiMatinDebut'] )
    {
        $minHoraire = $horaire['jeudiMatinDebut'];
    }
    if( ($horaire['vendrediMatinDebut'] != null) && $minHoraire > $horaire['vendrediMatinDebut'] )
    {
        $minHoraire = $horaire['vendrediMatinDebut'];
    }
    if( ($horaire['samediMatinDebut'] != null) && $minHoraire > $horaire['samediMatinDebut'] )
    {
        $minHoraire = $horaire['samediMatinDebut'];
    }
    if( ($horaire['dimancheMatinDebut'] != null) &&  $minHoraire > $horaire['dimancheMatinDebut'] )
    {
        $minHoraire = $horaire['dimancheMatinDebut'];
    }

    //
    $maxHoraire = $horaire['lundiSoireFin'];

    if( ($horaire['lundiSoireFin'] != null) && $maxHoraire < $horaire['lundiSoireFin'] )
    {
        $maxHoraire = $horaire['lundiSoireFin'];
    }
    if( ($horaire['mardiSoireFin'] != null) && $maxHoraire < $horaire['mardiSoireFin'] )
    {
        $maxHoraire = $horaire['mardiSoireFin'];
    }
    if( ($horaire['mercrediSoireFin'] != null) && $maxHoraire < $horaire['mercrediSoireFin'] )
    {
        $maxHoraire = $horaire['mercrediSoireFin'];
    }
    if( ($horaire['jeudiSoireFin'] != null) && $maxHoraire < $horaire['jeudiSoireFin'] )
    {
        $maxHoraire = $horaire['jeudiSoireFin'];
    }
    if( ($horaire['vendrediSoireFin'] != null) && $maxHoraire < $horaire['vendrediSoireFin'] )
    {
        $maxHoraire = $horaire['vendrediSoireFin'];
    }
    if( ($horaire['samediSoireFin'] != null) && $maxHoraire < $horaire['samediSoireFin'] )
    {
        $maxHoraire = $horaire['samediSoireFin'];
    }
    if( ($horaire['dimancheSoireFin'] != null) &&  $maxHoraire < $horaire['dimancheSoireFin'] )
    {
        $maxHoraire = $horaire['dimancheSoireFin'];
    }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="alert alert-success alert-popup" id="alert-success">
        <h4><i class="icon fa fa-check-circle"></i>Good !</h4>
        <p></p>
    </div>

    <div class="alert alert-danger alert-popup" id="alert-error">
        <h4><i class="icon fa fa-exclamation-circle"></i>Errors !</h4>
        <p></p>
    </div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Agenda des rendez vous
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-solid box-primary" style="border-radius: 0px">
            <div class="box-header">
                <h3 class="box-title"> <i class="fa fa-calendar"></i> &nbsp;Agenda des rendez vous </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body">
                <div id='calendrierRdv'></div>
            </div>
            <!-- /.box-body -->
            <input id="slotDuration" value="<?= $params['slotDuration'] ?>" type="hidden">
            <input id="minTime" value="<?= $minHoraire ?>" type="hidden">
            <input id="maxTime" value="<?= $maxHoraire ?>" type="hidden">
        </div>
        <!-- /.box-body -->

        <!-- Start Modal Add Rdv -->
        <div id="modalAddRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <form  action="includes/functions/controller.php?action=addRdvCalendar" id="FormAddRdv">
                        <div class="modal-header bg-purple">
                            <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                            <input name="nbrMinutes" id="nbrMinutes" value="<?= $params['nbrMinutes'] ?>" type="hidden">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-calendar-plus-o "></i>&nbsp; Nouveau rendez vous &nbsp;<span></span> </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="idPatient" class="control-label">Selectionnez un patient</label>
                                        <select name="patient" class="form-control select2" id="idPatient">
                                            <option value="0" selected="" disabled=""> --  vuillez choisie un patient  -- </option>
                                            <?php foreach( $patients as $patient ): ?>
                                                <option value="<?= $patient['idPatient'] ?>"> <?php if( $patient['cin'] != '' ) echo '[ ' . $patient['cin'] . ' ] ' . $patient['prenom'] . '  ' . $patient['nom']; else echo $patient['prenom'] . '  ' . $patient['nom']; ?> </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="idDossierModal" class="control-label">Choisie un dossier </label>
                                        <div class="form-group has-feedback">
                                            <select id="idDossierModal" name="idDossierModal" class="form-control select2">
                                                <option value="0" selected="" disabled=""> --  vuillez choisie un dossier  -- </option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dateAddRdv" class="control-label">Date de rendez vous </label>
                                                <div class="form-group has-feedback">
                                                    <input type="text" id="dateAddRdv" disabled name="dateAddRdv" required placeholder="Date Rendez Vous" class="form-control datepicker">
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="heurAddRdv" class="control-label">Heure de rendez vous </label>
                                                <div class="form-group has-feedback">
                                                    <input type="text" id="heurAddRdv" disabled name="heurAddRdv" required placeholder="Heure Rendez Vous" class="form-control datepicker">
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="typeRdv" class="control-label">Type de rendez vous </label>
                                        <div class="form-group">
                                            <select name="typeRdv" id="typeRdv" class="form-control select2">
                                                <?php foreach( $typeRdv as $type ): ?>
                                                    <option value="<?= $type['idType'] ?>"> <?= $type['libelle'] ?> </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" class="control-label">Dureé de rendez vous </label>
                                        <div class="form-group">
                                            <input type="text" id="dureeRdv" disabled class="form-control" value="<?= $params['dureeRdv'] ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                            <button type="button" id="btnAddRdv" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Modal Add Rdv -->

        <!-- Start Modal View Rdv -->
        <div id="modalViewRdv" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-purple">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <input type="hidden" id="idSelectedPatient">
                        <input type="hidden" id="valEtatRdv">
                        <h4 class="modal-title"> <i class="fa fa-info-circle"></i> Information du rendez vous : &nbsp;<span></span> </h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="table-view" style="margin-bottom:0px !important">
                            <input type="hidden" id="viewIdDossier">
                            <tr>
                                <td>
                                    <b>Numéro de rendez vous</b>
                                </td>
                                <td id="viewIdRdv"></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Patient</b>
                                </td>
                                <td id="nomPatient"></td>
                            </tr>
                            <tr>
                                <td style="width: 125px !important; max-width: 200px;">
                                    <b>Numéro de telephone </b>
                                </td>
                                <td id="viewNumTele"></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Date du rendez vous</b>
                                </td>
                                <td id="viewDateRdv"> <span class="badge-no-circle bg-green"></span></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Heure de rendez vous</b>
                                </td>
                                <td id="viewHeureRdv"> <span class="badge-no-circle bg-green"></span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Type de rendez vous</b>
                                </td>
                                <td id="viewTypeRdv"> <span class="badge-no-circle bg-blue"></span> </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Etat du rendez vous</b>
                                </td>
                                <td id="viewEtatRdv"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fermer</button>
                        <button type="button" id="btnEditRdv" class="btn pull-left btn-flat btn-success" > <i class="fa fa-edit"></i>&nbsp; Modifier </button>
                        <button type="button" id="btnDeleteRdv" class="btn btn-flat pull-left btn-danger" > <i class="fa fa-trash-o"></i>&nbsp; Supprimer </button>
                    </div> 
                </div>
            </div>
        </div>
        <!-- End Modal View Rdv -->

        <!-- Start Modal Edit Rdv -->
        <div id="modalEditRdv" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form  action="includes/functions/controller.php?action=updateRdvCalendar" methode="post" id="FormUpdateRdv">
                        <div class="modal-header bg-purple">
                            <input name="idRdv" id="idRdv" type="hidden" value="">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"> <i class="fa fa-edit"></i>&nbsp;Modifier le rendez vous de patient : &nbsp;<span></span> </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="idPatientForEdit" class="control-label">Choisie un patient</label>
                                        <select name="idPatientForEdit" class="form-control select2" id="idPatientForEdit">
                                            <option value="0" selected="" disabled=""> --  vuillez choisie un patient  -- </option>
                                            <?php foreach( $patients as $patient ): ?>
                                                <option value="<?= $patient['idPatient'] ?>"> <?php if( $patient['cin'] != '' ) echo '[ ' . $patient['cin'] . ' ] ' . $patient['prenom'] . '  ' . $patient['nom']; else echo $patient['prenom'] . '  ' . $patient['nom']; ?> </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="idDossierForEdit" class="control-label">Choisie un dossier</label>
                                        <select name="idDossierForEdit" class="form-control selecst2" id="idDossierForEdit">                                                <option value="0" selected="" disabled=""> --  vuillez choisie un dossier  -- </option>
                                            <option selected disabled > --  vuillez choisie un dossier  -- </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="typeRdv" class="control-label">Type de rendez vous </label>
                                        <div class="form-group">
                                            <select name="typeRdv" id="typeRdvForEdit" class="form-control select2">
                                                <?php foreach( $typeRdv as $type ): ?>
                                                    <option value="<?= $type['idType'] ?>"> <?= $type['libelle'] ?> </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-inline" id="EtatRdv">
                                        <label class="control-label" style="margin-right: 20px">Etat de rendez vous</label>
                                        <label class="radio-inline text-blue">
                                            <input type="radio" name="etatRdv" id="activeRdv" value="a"/>
                                            Active
                                        </label>

                                        <label class="radio-inline text-red">
                                            <input type="radio" name="etatRdv" id="desactiveRdv" value="d"/>
                                            Annulé
                                        </label>

                                        <label class="radio-inline text-green">
                                            <input type="radio" name="etatRdv" id="valideRdv" value="v"/>
                                            Validé
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuler</button>
                            <button type="button" id="btnUpdateRdv" class="btn btn-flat btn-success" > <i class="fa fa-save"></i>&nbsp; Modifier </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Modal Edit Rdv -->


    </section>
    <!-- /.content -->
</div>
<!-- /.cùontent-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<!--<script src="layout/js/rendezVous.js"></script>-->
<script>
    $(document).ready(function ()
    {
        //==========================================
        var horaires = {};

        var minTime = $('#minTime').val();
        var maxTime = $('#maxTime').val();

        $.ajax({

            url : 'includes/functions/controller.php?action=getActiveHoraires',

            type : 'GET',

            success : function(res)
            {
                horaires = JSON.parse(res);

                $('#calendrierRdv').fullCalendar
                ({
                    header:
                    {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listWeek'
                    },
                    defaultView: 'agendaWeek',
                    views:
                    {
                        agenda:
                        {
                            axisFormat: 'HH:mm',
                            timezone: 'local',
                            columnFormat: 'dddd D/M'
                        }
                    },
                    locale: 'fr',
                    navLinks: true,
                    editable: false,
                    slotDuration: slotDuration,
                    eventLimit: true,
                    minTime : minTime,
                    maxTime : maxTime,

                    events: "includes/functions/controller.php?action=getRdvs",

                    selectable: true,
                    selectHelper: true,

                    select: function(start, end, allDay)
                    {
                        var dateRdv     = moment(start).format("DD-MM-YYYY");
                        var heurRdv     = moment(start).format("HH:mm");
                        var currentDate = new Date();

                        if( (heurRdv != '00:00' ) && ( start > currentDate ) )
                        {
                            $('#dateAddRdv').val( dateRdv );
                            $('#heurAddRdv').val( heurRdv );
                            $('#modalAddRdv').modal();
                        }
                    },

                    dayRender: function(date, cell)
                    {
                        var currentDate = new Date();
                        if( date < currentDate )
                        {
                            $(cell).css({ backgroundColor : '#f7eaea'});
                            $('.fc-day[data-date="'+ date +'"]').css({ backgroundColor : '#f00'});
                        }
                    },

                    eventClick: function(calEvent, jsEvent, view)
                    {
                        var idRdv = calEvent.title.split("#")[1];
                        viewInfoRdv(idRdv);
                    }
                });
            },
            error : function(resultat, statut, erreur){
                console.log(resultat);
            }
        });

        getHoraires('2017-12-06 10:55:48');

        $('.select2').select2({ "width" : "100%" });

        $('.dateTimepicker').datetimepicker({
            format: 'yyyy-mm-dd HH',
            autoclose: true
        });

        $('#dateAddRdv').datepicker('setStartDate', new Date());

        $('#dateAddRdv').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#idPatient').change(function ()
        {
            document.getElementById('idDossierModal').innerHTML = "";
            var idPatient = $('#idPatient').val();
            getDossiers( idPatient );
        });

        $('#idPatientForEdit').change(function ()
        {
            document.getElementById('idDossierForEdit').innerHTML = "";
            var idDossier = $('#modalViewRdv #viewIdDossier').val();

            $('#idDossierForEdit').select2
            (
                {
                    "width" : "100%",
                    placeholder: 'Vuillez le numéro de dossier ...'
                }
            );
            var idSelectedPatient = $('#idPatientForEdit').val();

            getDossiersForEdit(idSelectedPatient, idDossier);
        });

        $('#btnDeleteRdv').click(function ()
        {
            var idRdv = $('#modalViewRdv #viewIdRdv').text();
            deleteRdv(idRdv);
            $('body').css('background','#f00 !important');
        });

        $('#btnUpdateRdv').click(function ()
        {
            updateRdv();
        });

        $('#btnEditRdv').click(function ()
        {
            var idRdv      = $('#modalViewRdv #viewIdRdv').text();
            var idDossier  = $('#modalViewRdv #viewIdDossier').val();
            var idPatient  = $('#modalViewRdv #idSelectedPatient').val();
            var nomPatient = $('#nomPatient').text();
            var etatRdv    = $('#valEtatRdv').val();
            $("#idPatientForEdit").val(idPatient).trigger("change");

            getDossiersForEdit( idPatient, idDossier );

            $('#modalEditRdv .modal-title span').text(nomPatient);
            $('#idRdv').val(idRdv);

            if( etatRdv == 'd')
            {
                $('#modalEditRdv #desactiveRdv').attr( "checked", true );
                $('#modalEditRdv #activeRdv').attr( "checked", false );
                $('#modalEditRdv #valideRdv').attr( "checked", false );
            }
            else if( etatRdv == 'a' )
            {
                $('#modalEditRdv #activeRdv').attr( "checked", true );
                $('#modalEditRdv #desactiveRdv').attr( "checked", false );
                $('#modalEditRdv #valideRdv').attr( "checked", false );
            }
            else
            {
                $('#modalEditRdv #valideRdv').attr( "checked", true );
                $('#modalEditRdv #desactiveRdv').attr( "checked", false );
                $('#modalEditRdv #activeRdv').attr( "checked", false );
            }

            $('#modalEditRdv').modal();
        });

        $('#btnAddRdv').click( function ()
        {
            var dateRdv  = $('#dateAddRdv').val();
            dateRdv      = moment(dateRdv, 'DD-MM-YYYY').format("YYYY-MM-DD");
            dateRdv      = dateRdv + ' ' + $('#heurAddRdv').val() + ':00';
            var dureeRdv = $('#nbrMinutes').val();
            dureeRdv     = parseInt(dureeRdv);
            addRdv( dateRdv, dureeRdv );
        });

        //============================================

        $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
        {
            $('body').css('padding-right','0 !important');
        });

        $.fn.modal.Constructor.prototype.setScrollbar = function () { };

        var slotDuration = $('#slotDuration').val();
    });

    // functions :
    // get dossiers :
    function getDossiers( idPatient )
    {
        $.ajax({

            url : 'includes/functions/controller.php?action=getDossiersPatient&idPatient=' + idPatient,

            type : 'GET',

            success : function(res)
            {
                var data = JSON.parse(res);

                $.each(data, function(i, value)
                {
                    $('#idDossierModal').append($('<option>').text(value['titreDossier']).attr('value', value['idDossier']));
                });
            },
            error : function(resultat, statut, erreur){
                console.log(resultat);
            }
        });
    }

    function getDossiersForEdit( idPatient, idDossier )
    {
        $.ajax({

            url : 'includes/functions/controller.php?action=getDossiersPatient&idPatient=' + idPatient,

            type : 'GET',

            success : function(res)
            {
                document.getElementById('idDossierForEdit').innerHTML = "";
                var data = JSON.parse(res);

                $.each(data, function(i, value) {
                    $('#idDossierForEdit').append($('<option>').text(value['titreDossier']).attr('value', value['idDossier']));
                });

                if( idDossier != null )
                {
                    $("#idDossierForEdit").val(idDossier).trigger("change");
                }
            },
            error : function(resultat, statut, erreur){
                console.log(resultat);
            }
        });
    }

    function getHoraires( dateHoraire )
    {
        $.ajax({

            url : 'includes/functions/controller.php?action=getActivedHoraires&dateHoraire=' + dateHoraire,

            type : 'GET',

            success : function(res)
            {
                return horaires = JSON.parse(res);
            },
            error : function(resultat, statut, erreur){
                console.log(resultat);
            }
        });
    }

    // add Rdv :
    function addRdv( dateRdv, dureeRdv )
    {
        console.log(dureeRdv + '  ' + dateRdv);
        loaderBtn('btnAddRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');
        $('#btnAddRdv').attr('disabled', true);
        var idDossier = $('#idDossierModal').val();
        var typeRdv  = $('#typeRdv').val();
        $.ajax({

            url     :$('#FormAddRdv').attr('action'),
            type    : 'post',
            data    : {dateRdv : dateRdv, idDossier : idDossier, dureeRdv : dureeRdv, typeRdv : typeRdv},
            success : function(data)
            {
                if( data == '1' )
                {
                    $('#calendrierRdv').fullCalendar( 'refetchEvents' );
                    $("#modalAddRdv").modal('hide');

                    showAlertSuccess('Le rendez vous est bien Ajouter !');
                }
                else if( data == '-1' )
                {
                    ShowAlertError('L\'ajout de rendez vous à échoue, vuillez choisie une date corréecte !');
                }
                else
                {
                    ShowAlertError('L\'ajout de rendez vous à échoue, Tout des champs sont obligatoires !');
                }
            },
            error : function(status)
            {
                console.log(status);
            },

            complete : function(resultat, statut)
            {
                loaderBtn('btnAddRdv', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
                $('#btnAddRdv').attr('disabled', false);
            }
        });
    }

    function viewInfoRdv(idRdv)
    {
        var rdv;

        $.ajax({

            url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,

            type : 'get',

            success : function (data)
            {
                rdv = JSON.parse(data);

                $('#modalViewRdv .modal-title span').text( rdv.prenom + '  ' + rdv.nom  );
                $('#modalViewRdv #viewIdDossier').val( rdv.idDossier );
                $('#modalViewRdv #viewIdRdv').text( rdv.idRdv );
                $('#modalViewRdv #nomPatient a').attr( 'href', 'http://localhost/sitecabinetDentaire/admin/viewPatient.php?idPatient=' + rdv.idPatient );
                $('#modalViewRdv #nomPatient').text( rdv.prenom + '  ' + rdv.nom + ' / ' + rdv.titreDossier );
                $('#modalViewRdv #idSelectedPatient').val(rdv.idPatient);
                $('#modalViewRdv #viewNumTele').text( rdv.tel );
                $('#modalViewRdv #viewDateRdv span').text(moment(rdv.dateRdv, 'YYYY-MM-DD HH:mm:ss').format("DD-MMMM-YYYY"));
                $('#modalViewRdv #viewHeureRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm"));
                $('#modalViewRdv #viewTypeRdv span').text(rdv.libelle);
                $('#modalViewRdv #valEtatRdv').val(rdv.Etat);
                $("#typeRdvForEdit").val(rdv.typeRdv).trigger("change");

                var dateSystem = new Date().getTime();
                var dateRdv    = new Date(rdv.dateRdv).getTime();
                var expirer    = dateRdv < dateSystem;
                var enAttent   = dateRdv >= dateSystem;
                var active     = false;
                var annule     = false;
                var valider    = false;

                if( rdv.Etat == 'a' )
                {
                    active = true;
                }
                else if( rdv.Etat == 'd' )
                {
                    annule = true;
                }
                else if(  rdv.Etat == 'v' )
                {
                    valider = true;
                }

                if( active && expirer)
                {
                    $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-yellow"> Expirer </span>');
                }
                else if( active && enAttent )
                {
                    $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-blue"> En Attent</span>');
                }
                else if( annule && enAttent)
                {
                    $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-red"> Annulé </span>');
                }
                else if( valider  )
                {
                    $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-green"> Validé </span>');
                }
                else if( expirer )
                {
                    $('#modalViewRdv #viewEtatRdv').html('<span class="badge-no-circle bg-yellow"> Expirer </span>');
                }

                $("#modalViewRdv").modal({backdrop: "static"});
            },
            error : function (status)
            {
                console.log(status);
            }
        });
    }

    function updateRdv()
    {
        loaderBtn('btnUpdateRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var fd = new FormData(document.querySelector('#FormUpdateRdv'));

        $.ajax({
            url         :$('#FormUpdateRdv').attr('action'),
            type        : 'post',
            data        : fd,
            processData : false,
            contentType : false,
            success     : function(data)
            {
                if( data == '1' )
                {
                    $('#calendrierRdv').fullCalendar( 'refetchEvents' );

                    $("#modalEditRdv").modal('hide');
                    $("#modalViewRdv").modal('hide');

                    $('#modalEditRdv #activeRdv').attr( "checked", false );
                    $('#modalEditRdv #desactiveRdv').attr( "checked", false );
                    $('#modalEditRdv #valideRdv').attr( "checked", false );

                    showAlertSuccess('Le rendez vous est bien modifier !');
                }
                else
                {
                    ShowAlertError('La modification du rendez vous à échoue, La date de rendez vous est obligatoire !');
                }
            },
            error : function(status)
            {
                console.log(status);
            },
            complete : function()
            {
                loaderBtn('btnUpdateRdv', '<i class="fa fa-save"></i>&nbsp; Modifier');
            }

        });
    }

    function addRdvDossier()
    {
        loaderBtn('btnAddRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

        var dateRdv    = $('#dateAddRdv').val();
        var heureRdv   = $('input[name=heurRdv]:checked').val();
        var dateAddRdv = dateRdv + ' ' + heureRdv + ':00' ;

        var idDossier = $('#idDossier').val();

        $.ajax({

            url         :$('#FormAddRdv').attr('action'),
            type        : 'post',
            data        : {dateRdv : dateAddRdv, idDossier : idDossier},
            success     : function(data)
            {
                if( data == '1' )
                {
                    $("#modalAddRdv").modal('hide');
                    showAlertSuccess('Le rendez vous est bien Ajouter !');
                    getRendezVousDossier();
                }
                else
                {
                    ShowAlertError('L\'ajout de rendez vous à échoue, vuillez choisie une date corréecte !');
                }
            },
            error : function(status)
            {
                console.log(status);
            },

            complete : function(resultat, statut)
            {
                loaderBtn('btnAddRdv', '<i class="fa fa-save"></i>&nbsp; Enregestrer');

                $('#horairesDisponible').slideUp();
            }

        });
    }

    function deleteRdv(idRdv)
    {
        swal.queue
        ([{
            title: 'Etes-vous sûr?',
            text: "voulez vous vraiment supprimer ce rendez vous !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Supprimer!',
            showLoaderOnConfirm: true,
            preConfirm: function ()
            {
                return new Promise(function (resolve)
                {
                    $.ajax({

                        url  : 'includes/functions/controller.php?action=deleteRdvDossier&idRdv=' + idRdv,

                        type : 'get',

                        success : function (data)
                        {
                            if( data == 1 )
                            {
                                $('#calendrierRdv').fullCalendar( 'refetchEvents' );
                                $("#modalViewRdv").modal('hide');
                                swal( 'Good !', 'Le rendez vous est bien supprimer !', 'success' ).insertQueueStep(1);
                                resolve();
                            }
                            else
                            {
                                swal( 'Erreur !', 'Impossible de supprimer ce rendez vous !', 'error' ).insertQueueStep(1);
                                resolve();
                            }
                        },

                        error : function (status)
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce rendez vous !', 'error' ).insertQueueStep(1);
                            resolve();
                        },

                        complete : function ()
                        {
                            $('body').css('padding-right','0 !important');
                        }

                    });
                });
            }
        }]);
    }

</script>
<?php require_once "includes/templates/sousFooter.inc"; ?>