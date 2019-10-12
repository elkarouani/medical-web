
<?php

    if( !isset($_GET['idPatient']) || $_GET['idPatient'] == '' )
    {
        header('location:patients.php');
    }

    $title      = 'Information';
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";

    $idPatient = $_GET['idPatient'];
    $sql       = 'SELECT * FROM patient WHERE idPatient = ?';
    $patient   = getData($sql, [$idPatient]);
    $mutuels   = getDatas("SELECT * FROM mutuel", []);

    if(empty($patient['idPatient']) )
    {
        header('location:patients.php');
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
            Patient : <span class="text-bold labelNomPatient"><?= $patient['prenom'] . ' ' . $patient['nom'] ?></span>
        </h1>
        <ol class="breadcrumb">
            <a href="patients.php" class="btn btn-primary btn-flat"> <i class="ion ion-person-stalker"></i> &nbsp; Vers la liste des patients </a>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form action="includes/functions/controller.php?action=UpdateInfoPrsonnelPatient" id="FormUpdatePatient" >
                    <div class="box box-solid box-primary" style="border-radius: 0px">
                        <div class="box-header">
                            <h3 class="box-title">
                                <i class="fa fa-user-circle-o"></i> Information du patient : <span class="text-bold labelNomPatient"><?= $patient['prenom'] . ' ' . $patient['nom'] ?></span>
                            </h3>
                            <a href="viewPatient.php?idPatient=<?= $patient['idPatient'] ?>" class="pull-right">  Consulter ce compte  &nbsp;<i class="fa fa-angle-right"></i> </a>
                        </div>
                        <div class="box-body">
                            <input name="idPatient" id="idPatient" value="<?= $patient['idPatient'] ?>" type="hidden">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cinPatient" class="control-label">Numéro de carte nationnal <span class="required">*</span></label>
                                        <input type="text" id="cinPatient" name="cinPatient" value="<?= $patient['cin'] ?>" class="form-control" placeholder="Numméro de carte nationnal" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomPatient" class="control-label">Nom <span class="required">*</span></label>
                                        <input type="text" id="nomPatient" name="nomPatient" value="<?= $patient['nom'] ?>" class="form-control" placeholder="Nom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenomPatient" class="control-label">Prenom <span class="required">*</span></label>
                                        <input type="text" id="prenomPatient" name="prenomPatient" value="<?= $patient['prenom'] ?>" class="form-control" placeholder="Prenom" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateNaissancePatient" class="control-label">Date de naissance <span class="required">*</span></label>
                                        <input type="text" id="dateNaissancePatient" required data-date-format="yyyy-mm-dd" name="dateNaissancePatient" value="<?= $patient['dateNaissance'] ?>" class="form-control datepicker" placeholder="Date de naissance" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephonePatient" class="control-label">Telephone <span class="required">*</span></label>
                                        <input type="tel" id="telephonePatient" name="telephonePatient" value="<?= $patient['tel'] ?>" class="form-control" placeholder="Telephone" >
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailPatient" class="control-label">Email</label>
                                        <input type="email" id="emailPatient" name="emailPatient" value="<?php  if( $patient['email'] != 'Null' ) echo $patient['email'] ?>" class="form-control" placeholder="Email" >
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
                                                <input type="radio" value="H" name="sexePatient" <?php if( $patient['sexe'] == 'H' ) echo 'checked' ?>> Homme
                                            </label>

                                            <label style="margin-left: 30px">
                                                <input type="radio" value="F" name="sexePatient" <?php if( $patient['sexe'] != 'H' ) echo 'checked' ?>> Femme
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
                                                    <option value="<?= $mutuel['idMutuel'] ?>" <?php if( $patient['mutuel'] == $mutuel['idMutuel'] ) echo 'selected'; ?> > <?= $mutuel['libelle'] ?> </option>
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
                                <textarea name="adressePatient" class="form-control" id="adressePatient" placeholder="Adresse..." cols="20" rows="4"><?= $patient['adresse'] ?></textarea>
                                <span class="help-block"></span>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="button" id="btnUpdatePatient" class="btn btn-success btn-flat"> <i class="fa fa-edit"></i> Enregestrer </button>
                        </div>
                        <div class="overlay" id="loaderUpdatePatient" style="display:none">
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
<!--<script src="layout/js/updatePatient.js"></script>-->
<script>
    /***** Debut Page Edit Admin ******/
    $('.select2').select2({ "width": "100%" });
    $('#btnUpdatePatient').click(function () {
        UpdatePatient();
    });

    $('#btnAddMutuelle').click(function()
    {
        addMutuelle();
    });

    /** functions **/
// modification informations géneral admin :

    function UpdatePatient(){

        var errors = [];
        $('#loaderUpdatePatient').show();
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

        var fd = new FormData(document.querySelector('#FormUpdatePatient'));

        $.ajax({

            url         :$('#FormUpdatePatient').attr('action'),
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
                else
                {
                    var resultats = JSON.parse(data);

                    if("nomCmpltPatient" in resultats)
                    {
                        $('.labelNomPatient').text(resultats['nomCmpltPatient']);
                        showAlertSuccess('Les informations est bien modifier');
                    }
                    else
                    {
                        ShowAlertError("La modification a échoué tout les champs sont obligatoire !");

                        for (var err in resultats)
                        {
                            var el = document.getElementById(err);
                            el.parentNode.classList.add('has-error');
                            el.parentNode.lastElementChild.innerText = resultats[err];
                        }

                        for (var err in resultats)
                        {
                            var el = document.getElementById(err);
                            el.parentNode.classList.add('has-error');
                            el.parentNode.lastElementChild.innerText = resultats[err];
                        }
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
                $('#loaderUpdatePatient').hide();
            }

        });
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
    /***** Fin Page Edit Admin ******/

</script>
<?php require_once "includes/templates/sousFooter.inc"; ?>
