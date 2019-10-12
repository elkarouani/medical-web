<?php

    if( ! isset($_GET['idHoraire']) || $_GET['idHoraire'] == '' )
    {
        header('location:horaires.php');
    }

    $idHoraire = $_GET['idHoraire'];
    $title      = 'Edite Horaire';
    $link       = 'horaires';
    $subLink    = 'horaires';
    $acceUser = ['admin', 'gestionnaire'];
    $withLoader = false;
    include_once "includes/templates/header.inc";
    include_once "includes/templates/aside.inc";
    $horaire = getData("SELECT * FROM horaire WHERE idHoraire = ?", [ $idHoraire ]);

    if(empty($horaire['idHoraire']) )
    {
        header('location:horaires.php');
    }

?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Editer l'horaire de travail : <?= $horaire['nomHoraire'] ?>
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    <a href="horaires.php" class="btn btn-primary"><i class="fa fa-clock-o"></i>&nbsp;Listes des horaires</a>
                </li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <form action="includes/functions/controller.php?action=updateHoraire" method="post" id="formUpdateHoraire" >
                <input type="hidden" name="idHoraire" value="<?= $horaire['idHoraire'] ?>">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-calendar"></i>&nbsp;
                            Editer l'horaire de travail : <?= $horaire['nomHoraire'] ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <table id="table-horaire" class="table table-bordered">
                            <tr>
                                <th>Joures</th>
                                <th>Matin</th>
                                <th>Soire</th>
                                <th>Etat</th>
                            </tr>
                            <tr id="lundi" <?php if( $horaire['lundiMatinDebut'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Lundi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiMatinDebut" <?php if( $horaire['lundiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['lundiMatinDebut'] ?>" name="lundiMatinDebut" placeholder="De ...">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiMatinFin" <?php if( $horaire['lundiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['lundiMatinFin'] ?>" name="lundiMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiSoireDebut" <?php if( $horaire['lundiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['lundiSoireDebut'] ?>" name="lundiSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiSoireFin" <?php if( $horaire['lundiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['lundiSoireFin'] ?>" name="lundiSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="lundiEtatOuvert" checked type="radio" name="lundiHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="lundiEtatFerme" type="radio" name="lundiHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr <?php if( $horaire['mardiMatinDebut'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Mardi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiMatinDebut" <?php if( $horaire['mardiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mardiMatinDebut'] ?>" name="mardiMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiMatinFin" <?php if( $horaire['mardiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mardiMatinFin'] ?>" name="mardiMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiSoireDebut" <?php if( $horaire['mardiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mardiSoireDebut'] ?>" name="mardiSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiSoireFin" <?php if( $horaire['mardiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mardiSoireFin'] ?>" name="mardiSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="mardiEtatOuvert" checked type="radio" name="mardiHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="mardiEtatFerme" type="radio" name="mardiHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr <?php if( $horaire['mercrediMatinDebut'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Mercredi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediMatinDebut" <?php if( $horaire['mercrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mercrediMatinDebut'] ?>" name="mercrediMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediMatinFin" <?php if( $horaire['mercrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mercrediMatinFin'] ?>" name="mercrediMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediSoireDebut" <?php if( $horaire['mercrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mercrediSoireDebut'] ?>" name="mercrediSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediSoireDebut" <?php if( $horaire['mercrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['mardiSoireFin'] ?>" name="mercrediSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="mercrediEtatOuvert" checked type="radio" name="mercrediHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="mercrediEtatFerme" type="radio" name="mercrediHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr <?php if( $horaire['jeudiMatinDebut'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Jeudi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiMatinDebut" <?php if( $horaire['jeudiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['jeudiMatinDebut'] ?>" name="jeudiMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiMatinFin" <?php if( $horaire['jeudiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['jeudiMatinFin'] ?>" name="jeudiMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiSoireDebut" <?php if( $horaire['jeudiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['jeudiSoireDebut'] ?>" name="jeudiSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiSoireFin" <?php if( $horaire['jeudiMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['jeudiSoireFin'] ?>" name="jeudiSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="jeudiEtatOuvert" checked type="radio" name="jeudiHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="jeudiEtatFerme" type="radio" name="jeudiHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr <?php if( $horaire['vendrediMatinDebut'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Vendredi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediMatinDebut" <?php if( $horaire['vendrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['vendrediMatinDebut'] ?>" name="vendrediMatinDebut" placeholder="De ...">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediMatinFin" <?php if( $horaire['vendrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['vendrediMatinFin'] ?>" name="vendrediMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediSoireDebut" <?php if( $horaire['vendrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['vendrediSoireDebut'] ?>" name="vendrediSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediSoireFin" <?php if( $horaire['vendrediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['vendrediSoireFin'] ?>" name="vendrediSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="vendrediEtatOuvert" checked type="radio" name="vendrediHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="vendrediEtatFerme" type="radio" name="vendrediHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr <?php if( $horaire['samediMatinDebut'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Samedi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediMatinDebut" <?php if( $horaire['samediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['samediMatinDebut'] ?>" name="samediMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediMatinFin" <?php if( $horaire['samediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['samediMatinFin'] ?>" name="samediMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediSoireDebut" <?php if( $horaire['samediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['samediSoireDebut'] ?>" name="samediSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediSoireFin" <?php if( $horaire['samediMatinDebut'] == null ) echo 'disabled' ?> value="<?= $horaire['samediSoireFin'] ?>" name="samediSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="samediEtatOuvert" checked type="radio" name="samediHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="samediEtatFerme" type="radio" name="samediHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr <?php if( $horaire['dimancheSoireFin'] == null ) echo 'style="background-color:#f7eaea"' ?>>
                                <td>Dimanche</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" <?php if( $horaire['dimancheMatinDebut'] == null ) echo 'disabled' ?>  id="dimancheMatinDebut" value="<?= $horaire['dimancheMatinDebut'] ?>" name="dimancheMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" <?php if( $horaire['dimancheMatinFin'] == null ) echo 'disabled' ?>  id="dimancheMatinFin" value="<?= $horaire['dimancheMatinFin'] ?>" name="dimancheMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" <?php if( $horaire['dimancheSoireDebut'] == null ) echo 'disabled' ?>  id="dimancheSoireDebut" value="<?= $horaire['dimancheSoireDebut'] ?>" name="dimancheSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" <?php if( $horaire['dimancheSoireFin'] == null ) echo 'disabled' ?> id="dimancheSoireFin" value="<?= $horaire['dimancheSoireFin'] ?>" name="dimancheSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="dimancheEtatOuvert" checked type="radio" name="dimancheHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="dimancheEtatFerme" <?php if( $horaire['dimancheMatinFin'] == null ) echo 'checked' ?> type="radio" name="dimancheHoraireEtat">Fermé</label>
                                </td>
                            </tr >
                        </table>
                        <br>

                        <div class="form-group">
                            <label for="aliasHoriare" class="control-label">Alias <span class="required">*</span></label>
                            <input id="aliasHoriare" name="aliasHoriare" value="<?= $horaire['nomHoraire'] ?>" class="form-control" placeholder="Alis Horaire">
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group">
                            <label for="descriptionHoraire" class="control-label">Description</label>
                            <textarea cols="30" rows="3" id="descriptionHoraire" name="descriptionHoraire" class="form-control" placeholder="Description..."><?= $horaire['descriptionHoriare'] ?></textarea>
                            <span class="help-block"></span>
                        </div>

                    </div>

                    <div class="box-footer">
                        <button type="button" id="btnUpdateHoraire" class="btn btn-success btn-flat"> <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
                    </div>
                    <div class="overlay" id="loaderUpdateHoraire" style="display:none">
                        <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
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
<!--    <script src="layout/js/horaires.js"></script>-->
    <script>
        $(document).ready(function ()
        {
            $('.timepicker').timepicker({
                showInputs: false,
                showMeridian: false,
                defaultTime: '08:00'
            });

            $('#btnUpdateHoraire').click(function () {
                updateHoraire()
            });

            // Lundi
            $('#lundiEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('lundiMatinDebut').disabled = false;
                    document.getElementById('lundiMatinFin').disabled = false;
                    document.getElementById('lundiSoireDebut').disabled = false;
                    document.getElementById('lundiSoireFin').disabled = false;
                    document.getElementById('lundiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#lundiEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('lundiMatinDebut').disabled = true;
                    document.getElementById('lundiMatinFin').disabled = true;
                    document.getElementById('lundiSoireDebut').disabled = true;
                    document.getElementById('lundiSoireFin').disabled = true;
                    document.getElementById('lundiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });


            // Mardi
            $('#mardiEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('mardiMatinDebut').disabled = false;
                    document.getElementById('mardiMatinFin').disabled = false;
                    document.getElementById('mardiSoireDebut').disabled = false;
                    document.getElementById('mardiSoireFin').disabled = false;
                    document.getElementById('mardiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#mardiEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('mardiMatinDebut').disabled = true;
                    document.getElementById('mardiMatinFin').disabled = true;
                    document.getElementById('mardiSoireDebut').disabled = true;
                    document.getElementById('mardiSoireFin').disabled = true;
                    document.getElementById('mardiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });

            $('#mercrediEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('mercrediMatinDebut').disabled = false;
                    document.getElementById('mercrediMatinFin').disabled = false;
                    document.getElementById('mercrediSoireDebut').disabled = false;
                    document.getElementById('mercrediSoireFin').disabled = false;
                    document.getElementById('mercrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#mercrediEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('mercrediMatinDebut').disabled = true;
                    document.getElementById('mercrediMatinFin').disabled = true;
                    document.getElementById('mercrediSoireDebut').disabled = true;
                    document.getElementById('mercrediSoireFin').disabled = true;
                    document.getElementById('mercrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });

            $('#jeudiEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('jeudiMatinDebut').disabled = false;
                    document.getElementById('jeudiMatinFin').disabled = false;
                    document.getElementById('jeudiSoireDebut').disabled = false;
                    document.getElementById('jeudiSoireFin').disabled = false;
                    document.getElementById('jeudiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#jeudiEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('jeudiMatinDebut').disabled = true;
                    document.getElementById('jeudiMatinFin').disabled = true;
                    document.getElementById('jeudiSoireDebut').disabled = true;
                    document.getElementById('jeudiSoireFin').disabled = true;
                    document.getElementById('jeudiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });

            $('#vendrediEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('vendrediMatinDebut').disabled = false;
                    document.getElementById('vendrediMatinFin').disabled = false;
                    document.getElementById('vendrediSoireDebut').disabled = false;
                    document.getElementById('vendrediSoireFin').disabled = false;
                    document.getElementById('vendrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#vendrediEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('vendrediMatinDebut').disabled = true;
                    document.getElementById('vendrediMatinFin').disabled = true;
                    document.getElementById('vendrediSoireDebut').disabled = true;
                    document.getElementById('vendrediSoireFin').disabled = true;
                    document.getElementById('vendrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });

            $('#samediEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('samediMatinDebut').disabled = false;
                    document.getElementById('samediMatinFin').disabled = false;
                    document.getElementById('samediSoireDebut').disabled = false;
                    document.getElementById('samediSoireFin').disabled = false;
                    document.getElementById('samediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#samediEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('samediMatinDebut').disabled = true;
                    document.getElementById('samediMatinFin').disabled = true;
                    document.getElementById('samediSoireDebut').disabled = true;
                    document.getElementById('samediSoireFin').disabled = true;
                    document.getElementById('samediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });



            $('#dimancheEtatOuvert').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('dimancheMatinDebut').disabled = false;
                    document.getElementById('dimancheMatinFin').disabled = false;
                    document.getElementById('dimancheSoireDebut').disabled = false;
                    document.getElementById('dimancheSoireFin').disabled = false;
                    document.getElementById('dimancheSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
                }
            });

            $('#dimancheEtatFerme').change(function()
            {
                if( this.checked )
                {
                    document.getElementById('dimancheMatinDebut').disabled = true;
                    document.getElementById('dimancheMatinFin').disabled = true;
                    document.getElementById('dimancheSoireDebut').disabled = true;
                    document.getElementById('dimancheSoireFin').disabled = true;
                    document.getElementById('dimancheSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
                }
            });

        });

        function updateHoraire()
        {

            $('#loaderUpdateHoraire').show();
            var elem = document.querySelectorAll("input, textarea");
            var t = 0;
            for (var i = 0; i < elem.length; i++)
            {
                if ( elem[i].parentNode.parentNode.parentNode.parentNode.classList.contains("has-error") )
                {
                    elem[i].parentNode.parentNode.parentNode.parentNode.classList.remove('has-error');
                }
            }

            var fd = new FormData(document.querySelector('#formUpdateHoraire'));

            $.ajax({

                url         :$('#formUpdateHoraire').attr('action'),
                type        : 'post',
                data        : fd,
                processData : false,
                contentType : false,
                success     : function(data)
                {
                    if( data == '1' )
                    {
                        showAlertSuccess('Les informations est bien enregestrer !');
                        $('#FormAddPatient').trigger("reset");
                    }
                    else if( data == '0' )
                    {
                        ShowAlertError('L\'ajout à échoue !');
                    }
                    else
                    {
                        ShowAlertError('L\'ajout à échoué, Vuillez choisie un date corréect !');

                        errors = JSON.parse(data);

                        for (var err in errors)
                        {
                            var el = document.getElementById(err);
                            el.parentNode.parentNode.parentNode.parentNode.classList.add('has-error');
                        }
                    }
                },

                error : function(status)
                {
                    console.log(status);
                },
                complete : function()
                {
                    $('#loaderUpdateHoraire').hide();
                }

            });
        }



    </script>
<?php require_once "includes/templates/sousFooter.inc"; ?>