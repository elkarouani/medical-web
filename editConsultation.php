<?php
if(( ! isset($_GET['idconsultation']) || empty($_GET['idconsultation']) ))
{
    header('location:dossiers.php');
}

$title = 'Détails du consultation';
$acceUser = ['admin'];
$withLoader = false;
include_once "includes/templates/header.inc";
include_once "includes/templates/aside.inc";

$idConsultation       = $_GET['idconsultation'];
$sql                  = 'SELECT C.*, P.nom, P.prenom FROM consultation C, dossier D, patient P WHERE C.idDossier = D.idDossier AND D.idPatient = P.idPatient AND  C.idConsultation = ?';
$consultation         = getData($sql, [ $idConsultation ]);
$typeRdv              = getDatas("SELECT * FROM typerdv", []);

if(!($consultation) && (empty($consultation['idConsultation'])))
{
    header('location:dossiers.php');
}
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
            Consultation N : <?= $idConsultation ?> / du patient : <span class="text-bold labelNomPatient"><?= $consultation['prenom'] . '  ' . $consultation['nom'] ?></span>
        </h1>
        <ol class="breadcrumb">
        </ol>
    </section>

    <!-- object data="pdf_test.pdf" type="application/pdf"
    width="500" height="500" typemustmatch>
    <p>Vous n'avez pas de plugin PDF mais vous pouvez <a href="{link-to-your-pdf-file}">Télécharger le fichier.</a></p>
    </object -->



    <!-- Main content -->
    <section class="content">
        <div style="border-radius: 0px !important; " class="box box-solid box-primary">
            <div class="box-header" >
                <h3 class="box-title"> <i class="fa fa-stethoscope"></i>&nbsp; Consultation numéro : <?= $idConsultation ?> </h3>

                <a href="dossierPatient.php?idDossier=<?= $consultation['idDossier'] ?>" class="pull-right">  Consulter le dossier  &nbsp;<i class="fa fa-angle-right"></i> </a>
            </div>
            <div class="box-body">
                <form action="includes/functions/controller.php?action=updateConsultation" id="FormUpdateConsultation" >
                    <input id="idConsultation" name="idConsultation" value="<?= $idConsultation ?>" type="hidden">
                    <div class="box-group" id="accordion2">

                        <div class="panel box box-primary" style="border-top:0px">
                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                <h4 class="box-title">
                                    <a data-toggle="collapse" style="color:#000" data-parent="#accordion2" href="#infoConsultation">
                                        <i class="fa fa-info-circle"></i> &nbsp;General
                                    </a>
                                </h4>
                            </div>
                            <div id="infoConsultation" class="panel-collapse collapse in">
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dateConsultation" class="control-label">Date de consultation <span class="required">*</span></label>
                                                <input type="text" name="dateConsultation" value="<?= $consultation['dateDebut'] ?>" placeholder="Date de consultation..." class="form-control">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="typeConsultation" class="control-label">Type de consultation <span class="required">*</span></label>
                                                <div class="form-group">
                                                    <select name="typeConsultation" class="form-control select2">
                                                        <?php foreach( $typeRdv as $type ): ?>
                                                            <option value="<?= $type['idType'] ?>" <?php if( $type['idType'] == $consultation['typeConsultation'] ) echo 'selected'  ?> > <?= $type['libelle'] ?> </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="remarquesConsultation" class="control-label">Remarques <span class="required">*</span></label>
                                        <textarea style="overflow-x: hidden;" name="remarquesConsultation" placeholder="Remarques..." class="form-control" id="remarquesConsultation" cols="30" rows="4"><?= $consultation['remarquesConsultation'] ?></textarea>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="montantConsultation" class="control-label">Montant du consultation <span class="required">*</span></label>
                                        <input type="text" name="montantConsultation" value="<?= $consultation['montantNetConsultation'] ?>" placeholder="Entrez le montant du consultation en 'DH'..." value="0.00" class="form-control">
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="contenuOrdonnance" class="control-label">Ordonnance <span class="required">*</span></label>
                                        <textarea style="overflow-x: hidden;" name="contenuOrdonnance" placeholder="Contenu d'ordonnance ..." class="form-control" cols="30" rows="4"><?= $consultation['contenuOrdonnance'] ?></textarea>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="diagnostic" class="control-label">Diagnostic <span class="required">*</span></label>
                                        <textarea style="overflow-x: hidden;" name="diagnostic" placeholder="Diagnostic..." class="form-control" cols="30" rows="4"><?= $consultation['diagnostic'] ?></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel box box-primary" style="border-top:0px">
                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                <h4 class="box-title">
                                    <a data-toggle="collapse" style="color:#000" data-parent="#accordion2" href="#motifConsultation">
                                        <i class="fa fa-clipboard"></i> &nbsp; Motif de consultation
                                    </a>
                                </h4>
                            </div>
                            <div id="motifConsultation" class="panel-collapse collapse">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="douleurs" class="control-label">Douleurs <span class="required">*</span> </label>
                                        <textarea style="overflow-x: hidden;" name="douleurs" placeholder="Remarques..." class="form-control" id="douleurs" cols="30" rows="4"><?= $consultation['douleurs'] ?></textarea>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="symptome" class="control-label">Symptôme</label>
                                        <textarea style="overflow-x: hidden;" name="symptome" placeholder="Remarques..." class="form-control" id="symptome" cols="30" rows="4"><?= $consultation['symptome'] ?></textarea>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel box box-primary" style="border-top:0px">
                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                <h4 class="box-title">
                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion2" href="#examenClinique">
                                        <i class="fa fa-flask"></i>&nbsp; Examen clinique
                                    </a>
                                </h4>
                            </div>
                            <div id="examenClinique" class="panel-collapse collapse">
                                <div class="box-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                    <h3 class="box-title">Examen par appareil</h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cardioVasculaire" class="control-label">Cardio vasculaire</label>
                                                                <textarea  style="overflow-x: hidden;" name="cardioVasculaire" placeholder="Remarques..." class="form-control" id="cardioVasculaire" cols="30" rows="4"><?= $consultation['cardioVasculaire'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="pulomnaires" class="control-label">Pulomnaires</label>
                                                                <textarea style="overflow-x: hidden;" name="pulomnaires" placeholder="Remarques..." class="form-control" id="pulomnaires" cols="30" rows="4"><?= $consultation['pulomnaires'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="abdominal" class="control-label">Abdominal</label>
                                                                <textarea style="overflow-x: hidden;" name="abdominal" placeholder="Remarques..." class="form-control" id="abdominal" cols="30" rows="4"><?= $consultation['abdominal'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="seins" class="control-label">Seins</label>
                                                                <textarea style="overflow-x: hidden;" name="seins" placeholder="Remarques..." class="form-control" id="seins" cols="30" rows="4"><?= $consultation['seins'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="osteoArtiCulare" class="control-label">Ostéo-arti culare</label>
                                                                <textarea style="overflow-x: hidden;" name="osteoArtiCulare" placeholder="Remarques..." class="form-control" id="osteoArtiCulare" cols="30" rows="4"><?= $consultation['osteoArtiCulare'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="urogenital" class="control-label">Urogénital</label>
                                                                <textarea style="overflow-x: hidden;" name="urogenital" placeholder="Remarques..." class="form-control" id="urogenital" cols="30" rows="4"><?= $consultation['urogenital'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="neurologique" class="control-label">Neurologique</label>
                                                                <textarea style="overflow-x: hidden;" name="neurologique" placeholder="Remarques..." class="form-control" id="neurologique" cols="30" rows="4"><?= $consultation['neurologique'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="orl" class="control-label">ORL</label>
                                                                <textarea style="overflow-x: hidden;" name="orl" placeholder="Remarques..." class="form-control" id="orl" cols="30" rows="4"><?= $consultation['orl'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                    <h3 class="box-title">General</h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="taille" class="control-label">Taille</label>
                                                                <input name="taille" placeholder="Taille en 'm'..." value="<?= $consultation['taille'] ?>" class="form-control" id="taille" >
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="poids" class="control-label">Poids</label>
                                                                <input type="text" name="poids" placeholder="Poids en 'Kg'... " value="<?= $consultation['poids'] ?>" class="form-control" id="poids">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="temperature" class="control-label">Temperature</label>
                                                                <input type="text" name="temperature" placeholder="Temperature en '°C'..." value="<?= $consultation['temperature'] ?>" class="form-control" id="temperature" >
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="etatGeneral" class="control-label">Etat general</label>
                                                                <textarea style="overflow-x: hidden;" name="etatGeneral" placeholder="Remarques..." class="form-control" id="etatGeneral" cols="30" rows="4"><?= $consultation['etatGeneral'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="imc" class="control-label">IMC</label>
                                                                <textarea style="overflow-x: hidden;" name="imc" placeholder="Remarques..." class="form-control" id="imc" cols="30" rows="4"><?= $consultation['imc'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel box box-primary" style="border-top:0px">
                            <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #e4eefd; border-left: 4px solid #4a8cf8; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                                <h4 class="box-title">
                                    <a style="color:#000" data-toggle="collapse" data-parent="#accordion2" href="#bilaneParaclinique">
                                        <i class="fa fa-stethoscope"></i>&nbsp; Bilane paraclinique
                                    </a>
                                </h4>
                            </div>
                            <div id="bilaneParaclinique" class="panel-collapse collapse">
                                <div class="box-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                    <h3 class="box-title">Biologie</h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="echographie" class="control-label">Echographie</label>
                                                                <textarea style="overflow-x: hidden;" name="echographie" placeholder="Remarques..." class="form-control" id="echographie" cols="30" rows="4"><?= $consultation['echographie'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="radioStrandard" class="control-label">Radio strandard</label>
                                                                <textarea style="overflow-x: hidden;" name="radioStrandard" placeholder="Remarques..." class="form-control" id="radioStrandard" cols="30" rows="4"><?= $consultation['radioStrandard'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="tdm" class="control-label">TDM</label>
                                                                <textarea style="overflow-x: hidden;" name="tdm" placeholder="Remarques..." class="form-control" id="tdm" cols="30" rows="4"><?= $consultation['tdm'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="trm" class="control-label">TRM</label>
                                                                <textarea style="overflow-x: hidden;" name="trm" placeholder="Remarques..." class="form-control" id="trm" cols="30" rows="4"><?= $consultation['trm'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="autreBiologie" class="control-label">Autres</label>
                                                        <textarea style="overflow-x: hidden;" name="autreBiologie" placeholder="Remarques..." class="form-control" id="autreBiologie" cols="30" rows="4"><?= $consultation['autreBiologie'] ?></textarea>
                                                        <span class="help-block"></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="box box-primary box-solid" style="border-radius: 0px !important; border: 0px" >
                                                <div class="box-header" style="background: #fff3d3; color: #5f5f5f; border-left: 4px solid #f6b73c;">
                                                    <h3 class="box-title">Radiographies standards</h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rxPoumon" class="control-label">Rx Poumon</label>
                                                                <textarea style="overflow-x: hidden;" name="rxPoumon" placeholder="Remarques..." class="form-control" id="rxPoumon" cols="30" rows="4"><?= $consultation['rxPoumon'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rxRichs" class="control-label">Rx Richs</label>
                                                                <textarea style="overflow-x: hidden;" name="rxRichs" placeholder="Remarques..." class="form-control" id="rxRichs" cols="30" rows="4"><?= $consultation['rxRichs'] ?></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="box-footer">
                <button type="button" class="btn btn-success btn-flat" id="btnUpdateConsultation" > <i class="fa fa-save"></i>&nbsp; Enregestrer les modifications </button>
            </div>
            <div class="overlay" id="loaderUpdateConsultation" style="display:none">
                <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
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


$(document).ready(function ()
{
    $(document.body).on('hide.bs.modal,hidden.bs.modal', function ()
    {
        $('body').css('padding-right','0 !important');
    });

    $.fn.modal.Constructor.prototype.setScrollbar = function () { };

    $('.select2').select2({ "width": "100%" });

    $('#btnUpdateConsultation').click(function(){
        updateConsultation();
    });

});

function updateConsultation()
{
    $('#loaderUpdateConsultation').show();
    var fd = new FormData(document.querySelector('#FormUpdateConsultation'));

    $.ajax({

        url         :$('#FormUpdateConsultation').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            console.log(data);
            if( data == '1' )
            {
                showAlertSuccess('La consultation est bien modifier !');
            }
            else if( data == '0' )
            {
                ShowAlertError('La modification du consultation à échoue !');
            }
            else
            {
                ShowAlertError('La modification du consultation à échoue !, vuillez respecter la validation du champs !');

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

        complete : function(resultat, statut)
        {
            $('#loaderUpdateConsultation').hide();
        }

    });
}

</script>


<?php require_once "includes/templates/sousFooter.inc"; ?>
<!-- script src="layout/js/admins.js"></script -->