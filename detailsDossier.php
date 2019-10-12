<?php
    
    if( !isset($_GET['idDossier']) || empty($_GET['idDossier']) || !is_numeric($_GET['idDossier']))
    {
        if (isset($_SERVER["HTTP_REFERER"])) 
        {
            header("location: " . $_SERVER["HTTP_REFERER"]);
        }
        else
        {
            header('location: patients.php');
        }
    }

    $idDossier = $_GET['idDossier'];

    $title = 'Détails Dossier';
    $withLoader = false;
    include_once "includes/templates/header.inc";
    $sql = "SELECT d.* FROM dossier d WHERE d.idDossier = ?";

    $dossier = getData($sql, [ $idDossier ]);
    
    if ( empty( $dossier['idDossier'] ) ) 
    {
        if (isset($_SERVER["HTTP_REFERER"])) 
        {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        else
        {
            header('location: patients.php');
        }
    }

    $patient          = getData("SELECT * FROM patient WHERE idPatient = ?", [ $dossier['idPatient'] ]);
    $consultations    = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE idDossier = ?", [ $idDossier ]);
    $rdvs             = getData("SELECT COUNT(*) AS nbrRdvs FROM rdv WHERE idDossier = ?", [ $idDossier ]);
    $nbrConsulattions = $consultations['nbrConsultations'];
    $nbrRdv           = $rdvs['nbrRdvs'];

    include_once "includes/templates/aside.inc";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="alert alert-success alert-popup" id="alert-popup-success">
        <button type="button" id="btn-hide-alert" class="close" >&times;</button>
        <h4><i class="icon fa fa-check-circle"></i>Good !</h4>
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

         <div class="row">
            <div class="col-md-8">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-info"></i> &nbsp; Informations Du Dossier N° : <?= $idDossier ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered" id="table-view">
                            <tr>
                                <td>
                                    <b>ID Dossier</b>
                                </td>
                                <td><?= $dossier['idDossier'] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Patient</b>
                                </td>
                                <td><a href="viewPatient.php?idPatient=<?= $dossier["idPatient"] ?>"> <?= $patient["prenom"] . ' ' . $patient["nom"] ?> </a></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Date Création</b>
                                </td>
                                <td><?= $dossier["dateCreation"] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Nombre Du Consultations</b>
                                </td>
                                <td><span class="badge bg-aqua"><?= $nbrConsulattions ?></span></td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <b>Nombre Du Rendez Vous</b>
                                </td>
                                <td><span class="badge bg-aqua"><?= $nbrRdv ?></span></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                    <br>
                    <!-- /.box-body -->
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-cog"></i> &nbsp; Actions
                        </h3>
                    </div>
                    <div class="box-body">
                        <a href="editPatient.php?idPatient=<?= $dossier['idPatient'] ?>" class="btn btn-success btn-flat btn-block"> <i class="fa fa-pencil"></i> &nbsp; Modifier Ce Dossier</a>
                        <a href="patients.php" class="btn btn-primary btn-flat btn-block"> <i class="fa fa-folder-open"></i> &nbsp; Vers La Liste Des Dossier</a>
                        <button onclick="deleteDossier(<?= $idDossier ?>, <?= $dossier['idPatient'] ?>)" class="btn btn-danger btn-flat btn-block"> <i class="fa fa-trash-o"></i> &nbsp; Supprimer Ce Dossier</button>
                        <hr>
                        <button onclick="deleteDossier(<?= $idDossier ?>, true)" class="btn btn-warning btn-flat btn-block"> <i class="fa fa-calendar-plus-o"></i> &nbsp; Affecter Nouveau Rendez Vous</button>
                        <button onclick="DeletePatient(<?= $patient['idPatient'] ?>, true)" class="btn btn-success btn-flat btn-block"> <i class="fa fa-user-md"></i> &nbsp; Affecter Nouveau Consultation</button>
                        <button onclick="DeletePatient(<?= $patient['idPatient'] ?>, true)" class="btn btn-primary btn-flat btn-block"> <i class="fa fa-money"></i> &nbsp; Affecter Nouveau Paiement</button>
                    </div>
                </div>
            </div>

         </div>


         <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title" style="margin-top: 7px;"> <i class="fa fa-user-circle"></i> &nbsp; Liste Des Rendez Vous De patient : <p class="text-red"></p></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
                <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                <table id="tblRdvsDossier" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Rendez Vous</th>
                            <th>Date Rendez Vous </th>
                            <th>Etat</th>
                            <th style="min-width: 107px;">Action</th>
                        </tr>
                    </thead>
                </table>

                <!-- Modal Edit Rendez Vous -->
                <div id="modalEditRdv" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <form class="form-horizontal"  action="includes/functions/controller.php?action=updateRdvDossier" id="FormUpdateRdv">
                                <div class="modal-header bg-aqua">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Modification Du Rendez Vous N° : &nbsp;<span></span> </h4>
                                </div>
                            <div class="modal-body">
                                <br>
                                <input name="idRdv" id="idRdv" type="hidden" value="">
                                <input name="idDossier" id="idDossier" type="hidden" value="<?= $idDossier  ?>"> 

                                <div class="form-group">
                                    <label for="dateRdv" class="col-sm-3 control-label">Date Rendez Vous : </label>

                                    <div class="col-sm-9">
                                        <input type="text" id="dateRdv" name="dateRdv" required placeholder="Date Rendez Vous ( JJ-MM-AAAA )" class="form-control dateTimepicker">
                                    </div>
                                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                                </div>

                                <div class="form-group">
                                    <label for="dateDebut" class="col-sm-3 control-label">Etat : </label>

                                    <div class="col-sm-offset-3">
                                        <div class="checkbox" style="margin-left: 17px;">
                                        <label>
                                            <input name="etat" id="etatRdv" type="checkbox"> Active 
                                        </label>
                                        <span id="etatAlis" style="margin-left: 20px; margin-top: -5px;" class="badge"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                                <button type="button" id="btnUpdateRdv" class="btn btn-flat btn-success" > <i class="fa fa-check"></i> Modifier</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div> 

                <!-- Modal Add Rendez Vous -->
                <div id="modalAddRdv" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <form class="form-horizontal"  action="includes/functions/controller.php?action=addRdvDossier" id="FormAddRdv">
                                <div class="modal-header bg-aqua">
                                    <input name="idDossier" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Ajouter Nouveau Rendez Vous &nbsp;<span></span> </h4>
                                </div>
                            <div class="modal-body">
                                <br>

                                <div class="form-group">
                                    <label for="dateAddRdv" class="col-sm-3 control-label">Date Rendez Vous : </label>

                                    <div class="col-sm-9">
                                        <input type="text" id="dateAddRdv" name="dateAddRdv" required placeholder="Date Rendez Vous ( JJ-MM-AAAA )" class="form-control dateTimepicker">
                                    </div>
                                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                                </div>

                                <div class="form-group">
                                    <label for="dateDebut" class="col-sm-3 control-label">Etat : </label>

                                    <div class="col-sm-offset-3">
                                        <div class="checkbox" style="margin-left: 17px;">
                                        <label>
                                            <input name="etat" id="etatAddRdv" type="checkbox"> Active 
                                        </label>
                                        <span id="etatAlis" style="margin-left: 20px; margin-top: -5px;" class="badge"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                                <button type="button" id="btnAddRdv" class="btn btn-flat btn-primary" > <i class="fa fa-plus"></i>&nbsp; Ajouter </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
         </div>

         <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title" style="margin-top: 7px;"> <i class="fa fa-user-md"></i> &nbsp; Liste Des Consultation </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <!-- /.box-header table-responsive -->
            <div class="box-body table-responsive">
                <input name="idPatient" id="idDossier" value="<?= $idDossier ?>" type="hidden">
                <table id="tblConsultations" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Consultation</th>
                            <th>ID Rendez Vous</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Motif</th>
                            <th>Montant Net</th>
                            <th>Action</th>
                        </tr>
                    </thead>
              </table>
            </div>
         </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script src="layout/js/detailsDossier.js"></script>
