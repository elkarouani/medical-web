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

    $title = 'Consultations';
    $withLoader = false;
    include_once "includes/templates/header.inc";

    $dossier = getData("SELECT * FROM dossier WHERE idDossier = ? ", [ $idDossier ]);
    $rdvs    = getDatas("SELECT * FROM rdv WHERE idDossier = ? ", [ $idDossier ]);
    
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
        <div class="box">
            <div class="box-header">
                <h3 class="box-title" style="margin-top: 7px;"> <i class="fa fa-user-md"></i> &nbsp; Liste Des Consultation Du Dossier N° : <?php echo $idDossier ?> </h3>
                <button data-toggle="modal" data-target="#modaladdConsultation" class="btn btn-primary btn-flat btn-sm pull-right"> <i class="fa fa-stethoscope"></i> &nbsp;  Affecter Nouveau Consultation à Ce Dossier</button>
            </div>
            <br>
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
        <!-- /.box-body -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Modal Edit Rendez Vous -->
<div id="modaladdConsultation" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form class="form-horizontal"  action="includes/functions/controller.php?action=addConsultation" id="FormAddConsultation">
                <div class="modal-header bg-aqua">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modification Du Rendez Vous N° : &nbsp;<span></span> </h4>
                </div>
            <div class="modal-body">
                <br>
                <input name="idDossier" id="idDossier" type="hidden" value="<?= $idDossier  ?>"> 

                <div class="form-group">
                    <label for="dateRdv" class="col-sm-3 control-label">ID Rendez Vous : <span class="required">*</span> </label>

                    <div class="col-sm-9">
                        <select id="idRdv" class="form-control select2" name="idRdv" style="width: 100%;">
                            <?php foreach ($rdvs as $rdv) :  ?>
                                <option value="<?= $rdv['idRdv'] ?>" > <?= $rdv['idRdv'] ?> </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                </div>

                <div class="form-group">
                    <label for="dateDebut" class="col-sm-3 control-label">Date Début : <span class="required">*</span> </label>

                    <div class="col-sm-9">
                        <input type="text" id="dateDebut" name="dateDebut" required placeholder="Date Début Consultations" class="form-control dateTimepicker">
                    </div>
                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                </div>

                <div class="form-group">
                    <label for="dateFin" class="col-sm-3 control-label">Date Fin : </label>

                    <div class="col-sm-9">
                        <input type="text" id="dateFin" name="dateFin" required placeholder="Date Fin Consultations" class="form-control dateTimepicker">
                    </div>
                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                </div>

                <div class="form-group">
                    <label for="motif" class="col-sm-3 control-label">Motif : </label>

                    <div class="col-sm-9">
                        <textarea id="motif" name="motif" class="form-control" rows="8" cols="30" placeholder="Motif..." > </textarea>
                    </div>
                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                </div>

                <div class="form-group">
                    <label for="montantNet" class="col-sm-3 control-label">Montant Net : </label>

                    <div class="col-sm-9">
                        <input type="text" id="montantNet" name="montantNet" placeholder="Montant Net Consultations" class="form-control">
                    </div>
                    <span class="help-block col-sm-9 col-lg-offset-3"></span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Annuller</button>
                <button type="button" id="btnAddConsultation" class="btn btn-flat btn-success" > <i class="fa fa-check"></i> Enregestrer </button>
            </div>
            </form>
        </div>
    </div>
</div>



<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script src="layout/js/consultationsDossier.js"></script>
