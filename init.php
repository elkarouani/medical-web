<div class="row">
 <div class="col-md-9">
  <div class="box box-primary">
   <div class="box-header">
    <h3 class="box-title">
     <i class="fa fa-user-plus"></i> &nbsp;
     Ajouter Nouveau Gestionnaire
    </h3>
    <a href="Gestionnaires.php" class="text-capitalize pull-right"> Consulter Liste Des Gestionnaire &nbsp;<i class="fa fa-angle-right"></i> </a>
   </div>
   <div class="box-body">
    <div class="row">
     <div class="col-md-6">
      <div class="form-group">
       <label for="nomGestionnaire" class="control-label">Nom</label>
       <input type="text" id="nomGestionnaire" name="nomGestionnaire" class="form-control" placeholder="Nom Gestionnaire" >
       <span class="help-block"></span>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-group">
       <label for="prenomGestionnaire" class="control-label">Prenom</label>
       <input type="text" id="prenomGestionnaire" name="prenomGestionnaire" class="form-control" placeholder="Prenom Gestionnaire" >
       <span class="help-block"></span>
      </div>
     </div>
    </div>
    <div class="row">
     <div class="col-md-6">
      <div class="form-group">
       <label for="telephoneGestionnaire" class="control-label">Telephone</label>
       <input type="tel" id="telephoneGestionnaire" name="telephoneGestionnaire" class="form-control" placeholder="Telephone" >
       <span class="help-block"></span>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-group">
       <label for="emailGestionnaire" class="control-label">Email</label>
       <input type="email" id="emailGestionnaire" name="emailGestionnaire" class="form-control" placeholder="Email" >
       <span class="help-block"></span>
      </div>
     </div>
    </div>
    <div class="row">
     <div class="col-md-6">
      <div class="form-group">
       <label for="roleGestionnaire" class="control-label">Role Affécté</label>
       <select name="roleGestionnaire" id="roleGestionnaire" class="form-control">
        <?php foreach($roles as $role): ?>
         <option value=""> <?= $role['nomRole'] ?> </option>
        <?php endforeach ?>
       </select>
       <span class="help-block"></span>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-group">
       <label for="loginGestionnaire" class="control-label">Login</label>
       <input type="text" id="loginGestionnaire" name="loginGestionnaire" class="form-control" placeholder="Login" >
       <span class="help-block"></span>
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-group">
       <label for="passGestionnaire" class="control-label">Mot De Pass</label>
       <input type="password" id="passGestionnaire" name="passGestionnaire" class="form-control" placeholder="Nom Gestionnaire" >
       <span class="help-block"></span>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-group">
       <label for="ConfirmPassGestionnaire" class="control-label">Confirmation Mot De Pass</label>
       <input type="password" id="ConfirmPassGestionnaire" name="ConfirmPassGestionnaire" class="form-control" placeholder="Confirmation Mot De Pass" >
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
         <input type="radio" value="H" name="sexeGestionnaire" checked > Homme
        </label>

        <label style="margin-left: 30px">
         <input type="radio" value="F" name="sexeGestionnaire" > Femme
        </label>
       </div>
      </div>
     </div>
    </div>

    <div class="form-group">
     <label for="adresseGestionnaire" class="control-label">Adresse</label>
     <textarea name="adresseGestionnaire" class="form-control" id="adresseGestionnaire" placeholder="Adresse..." cols="20" rows="4"></textarea>
     <span class="help-block"></span>
    </div>
   </div>

   <div class="box-footer">
    <button type="button" id="btnAddGestionnaire" class="btn btn-success btn-flat"> <i class="fa fa-check"></i> Enregestrer </button>
    <button type="reset" id="btnNewGestionnaire" class="btn btn-warning btn-flat"> <i class="fa fa-user-plus"></i> Nouveau Gestionnaire </button>
   </div>
  </div>
 </div>
 <div class="col-md-3">
  <div class="box box-primary">
   <div class="box-header">
    <h3 class="box-title"> <i class="fa fa-picture"></i> &nbsp; Image De Profile </h3>
   </div>
   <div class="box-body">
    <img id="imageGestionnaire" class="profile-user-img img-responsive img-circle" style="width: 150px; height: 150px;" src="data/uploades/avatarGestionnaires/no_image.jpg" >
   </div>
   <div class="box-footer">
    <button id="btnAddGestionnaire" type="button" class="btn btn-success btn-flat btn-block">Choisie une image</button>
    <input type="file" name="imageGes" onchange="document.getElementById('imageGestionnaire').src = window.URL.createObjectURL(this.files[0])" id="inputFileImage" style='display:none' >
   </div>
  </div>
 </div>
</div>