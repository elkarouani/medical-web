<?php

    if( !isset( $_GET['idHoriare'] ) || empty( $_GET['idHoriare']))
    {
        header('location: admins.php');
    }
    else
    {
        $idHoraire = $_GET['idHoriare'];
    }
    $title      = 'Details horaire';
    $link       = 'horaires';
    $subLink    = 'horaires';
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $horaire    = getData("SELECT * FROM horaire WHERE idHoraire = ?", [ $idHoraire ]);
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
                Blank page
                <small>it all starts here</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Blank page</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <form action="includes/functions/controller.php?action=addHoraire" method="post" id="formAddHoraire" >
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-calendar"></i> &nbsp;
                            Ajouter Nouveau Horaire De Travail
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
                            <tr id="lundi">
                                <td>Lundi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiMatinDebut" name="lundiMatinDebut" value="<?= $horaire['mercrediSoireDebut'] ?>" placeholder="De ...">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiMatinFin" name="lundiMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiSoireDebut" name="lundiSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="lundiSoireFin" name="lundiSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="lundiEtatOuvert" checked type="radio" name="lundiHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="lundiEtatFerme" type="radio" name="lundiHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Mardi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiMatinDebut" name="mardiMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiMatinFin" name="mardiMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiSoireDebut" name="mardiSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mardiSoireFin" name="mardiSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="mardiEtatOuvert" checked type="radio" name="mardiHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="mardiEtatFerme" type="radio" name="mardiHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Mercredi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediMatinDebut" name="mercrediMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediMatinFin" name="mercrediMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediSoireDebut" name="mercrediSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="mercrediSoireFin" name="mercrediSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="mercrediEtatOuvert" checked type="radio" name="mercrediHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="mercrediEtatFerme" type="radio" name="mercrediHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Jeudi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiMatinDebut" name="jeudiMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiMatinFin" name="jeudiMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiSoireDebut" name="jeudiSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="jeudiSoireFin" name="jeudiSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="jeudiEtatOuvert" checked type="radio" name="jeudiHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="jeudiEtatFerme" type="radio" name="jeudiHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Vendredi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediMatinDebut" name="vendrediMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediMatinFin" name="vendrediMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediSoireDebut" name="vendrediSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="vendrediSoireFin" name="vendrediSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="vendrediEtatOuvert" checked type="radio" name="vendrediHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="vendrediEtatFerme" type="radio" name="vendrediHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Samedi</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediMatinDebut" name="samediMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediMatinFin" name="samediMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediSoireDebut" name="samediSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="samediSoireFin" name="samediSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="samediEtatOuvert" checked type="radio" name="samediHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="samediEtatFerme" type="radio" name="samediHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Dimanche</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="dimancheMatinDebut" name="dimancheMatinDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="dimancheMatinFin" name="dimancheMatinFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="dimancheSoireDebut" name="dimancheSoireDebut" placeholder="De ....">
                                        </div>
                                        <div class="col-md-2 text-center arrow-right">
                                            <i class="fa fa-exchange"></i>
                                        </div>
                                        <div class="col-md-5 bootstrap-timepicker">
                                            <input class="form-control timepicker" id="dimancheSoireFin" name="dimancheSoireFin" placeholder="à ...">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <label class="radio-inline"><input id="dimancheEtatOuvert" checked type="radio" name="dimancheHoraireEtat">Ouvert</label>
                                    <label class="radio-inline"><input id="dimancheEtatFerme" type="radio" name="dimancheHoraireEtat">Fermé</label>
                                </td>
                            </tr>
                        </table>
                        <br>

                        <div class="form-group">
                            <label for="aliasHoriare" class="control-label">Alias</label>
                            <input id="aliasHoriare" name="aliasHoriare" class="form-control" placeholder="Alis Horaire">
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group">
                            <label for="descriptionHoraire" class="control-label">Description</label>
                            <textarea cols="30" rows="8" id="descriptionHoraire" name="descriptionHoraire" class="form-control" placeholder="Description..."></textarea>
                            <span class="help-block"></span>
                        </div>

                    </div>

                    <div class="box-footer">
                        <button type="button" id="btnAddHoraire" class="btn btn-success btn-flat"> <i class="fa fa-check"></i>&nbsp;Enregestrer </button>
                        <button type="reset" class="btn btn-warning btn-flat"> <i class="fa fa-clone"></i>&nbsp; Vider </button>
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
    <script src="layout/js/horaires.js"></script>
<?php require_once "includes/templates/sousFooter.inc"; ?>