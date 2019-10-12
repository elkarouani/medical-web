
<?php
$title      = 'Patients';
$withLoader = false;
include_once "includes/templates/header.inc";
$patients = getDatas("select * from patient ", []);
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
			
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" style="margin-top: 7px;"> <i class="fa fa-user-circle"></i> &nbsp; Liste Patients </h3>
				<a href="addPatient.php" class="btn btn-primary btn-sm pull-right btn-flat" > <i class="fa fa-user-plus"></i> Ajouter Nouveau Patient</a>
			</div>
			<br>
			<!-- /.box-header table-responsive -->
			<div class="box-body table-responsive">

			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once "includes/templates/footer.inc"; ?>
<script src="layout/js/jquery.dataTables.min.js"></script>
<script src="layout/js/dataTables.bootstrap.min.js"></script>
<script src="layout/js/patientss.js"></script>
