

<div class="tab-pane" id="tab_antecedents">
    <div style="border-radius: 0px !important; " class="box box-solid box-primary">
        <div class="box-header" >
            <h3 class="box-title"> <i class="ion ion-ios-medkit-outline"></i>&nbsp;  Les antécédents </h3>
        </div>
        <div class="box-body">
            <form action="includes/functions/controller.php?action=updateInfoMedicalPatient" id="FormUpdateInfoMedicalPatient" >
                <input type="hidden" name="idPatient" value="<?= $dossier['idPatient'] ?>">
                <div class="box-group" id="accordion">

                    <div class="panel box box-primary" style="border-top:0px">
                        <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                            <h4 class="box-title">
                                <a data-toggle="collapse" style="color:#000" data-parent="#accordion" href="#familiaux">
                                    General
                                </a>
                            </h4>
                        </div>
                        <div id="familiaux" class="panel-collapse collapse in">

                        </div>
                    </div>

                    <div class="panel box box-primary" style="border-top:0px">
                        <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                            <h4 class="box-title">
                                <a style="color:#000" data-toggle="collapse" data-parent="#accordion" href="#medicaux">
                                    Médicaux
                                </a>
                            </h4>
                        </div>
                        <div id="medicaux" class="panel-collapse collapse">
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="hta" class="control-label">HTA</label>
                                    <textarea style="overflow-x: hidden;" name="hta" placeholder="Remarques..." class="form-control" id="hta" cols="30" rows="2"><?= $dossier['hta'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="allergies" class="control-label">Allergies</label>
                                    <textarea style="overflow-x: hidden;" name="allergies" placeholder="Remarques..." class="form-control" id="allergies" cols="30" rows="2"><?= $dossier['allergies'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="autresMedicaux" class="control-label">Autres </label>
                                    <textarea style="overflow-x: hidden;" name="autresMedicaux" placeholder="Remarques..." class="form-control" id="autresMedicaux" cols="30" rows="2"><?= $dossier['autresMedicaux'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel box box-primary" style="border-top:0px">
                        <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                            <h4 class="box-title">
                                <a style="color:#000" data-toggle="collapse" data-parent="#accordion" href="#habitudeAlcooloTabagique">
                                    Habitude alcoolo tabagique
                                </a>
                            </h4>
                        </div>
                        <div id="habitudeAlcooloTabagique" class="panel-collapse collapse">
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="tabac" class="control-label">Tabac</label>
                                    <textarea style="overflow-x: hidden;" name="tabac" placeholder="Remarques..." class="form-control" id="tabac" cols="30" rows="2"><?= $dossier['tabac'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="autresTabac" class="control-label">Autres</label>
                                    <textarea style="overflow-x: hidden;" name="autresTabac" placeholder="Remarques..." class="form-control" id="autresTabac" cols="30" rows="2"><?= $dossier['autreshabitudeAlcooloTabagique'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel box box-primary" style="border-top:0px">
                        <div class="box-header with-border box-accordion-header-warning" style="border-bottom:0px !important; background-color: #fff3d3; border-left: 4px solid #f6b73c; border-bottom: 1px solid #f4f4f4; padding: 20px 14px;" >
                            <h4 class="box-title">
                                <a style="color:#000" data-toggle="collapse" data-parent="#accordion" href="#chirurgicauxComplications">
                                    Chirurgicaux complications
                                </a>
                            </h4>
                        </div>
                        <div id="chirurgicauxComplications" class="panel-collapse collapse">
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="appendicectomie" class="control-label">Appendicectomie</label>
                                    <textarea style="overflow-x: hidden;" name="appendicectomie" placeholder="Remarques..." class="form-control" id="appendicectomie" cols="30" rows="2"><?= $dossier['appendicectomie'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="cholecystectomie" class="control-label">Cholécystectomie</label>
                                    <textarea style="overflow-x: hidden;" name="cholecystectomie" placeholder="Remarques..." class="form-control" id="cholecystectomie" cols="30" rows="2"><?= $dossier['cholecystectomie'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="autresChirurgicauxComplication" class="control-label">Autres</label>
                                    <textarea style="overflow-x: hidden;" name="autresChirurgicauxComplication" placeholder="Remarques..." class="form-control" id="autresChirurgicauxComplication" cols="30" rows="2"><?= $dossier['autresChirurgicauxComplication'] ?></textarea>
                                    <span class="help-block"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="box-footer">
            <button type="button" class="btn btn-success btn-flat" id="btnUpdateInfoMedicalPatient" > <i class="fa fa-save"></i>&nbsp; Enregestrer </button>
        </div>
        <div class="overlay" id="loaderUpdateInfoMedicalPatient" style="display:none">
            <i style="font-size:50px" class="fa fa-spinner fa-pulse fa-fw"></i>
        </div>
    </div>
</div>
<!-- /.tab-pane -->