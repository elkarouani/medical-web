/******** Debut page consultations dossier *******/

$(document).ready(function(){
    
    getConsultations();
    getRendezVousDossier();

    $('.dateTimepicker').datetimepicker({
        format: 'yyyy-mm-dd HH:mm:ss',
        autoclose: true
    });
    
    $('#etatRdv').on('change', function(){
        if( $(this).is(":checked") )
        {
            $('#etatAlis').text('Activé');
            $('#etatAlis').removeClass('bg-red');
            $('#etatAlis').addClass('bg-green');
        }
        else
        {
            $('#etatAlis').text('Désactivé');
            $('#etatAlis').removeClass('bg-green');
            $('#etatAlis').addClass('bg-red');
        }
    });

    $('#btnUpdateRdv').click(function(){
        updateRdvDossier();
    });

    $('#btnAddRdv').click(function(){
        addRdvDossier();
    });

});

// Delete Dossier :
function deleteDossier(idDossier, idPatient) 
{
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer ce dossier !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Supprimer!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=deleteDossier&idDossier=' + idDossier,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            swal( 'Good !', 'Le dossier est bien supprimer !', 'success' );
                            resolve();
                            window.location.href = "dossiersPatient.php?idPatient=" + idPatient;
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce dossiers !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer ce dossier !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}

// get consultations :
function getConsultations()
{
    var idDossier = document.getElementById('idDossier').value;

    $.ajax({  

        url : 'includes/functions/controller.php?action=getConsultations&idDossier=' + idDossier,

        type : 'GET',

        success : function(res)
        {  
            var res2 = JSON.parse(res);
            $('#tblConsultations').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html(
                        '<button onclick="DeletePatient(' + aData.idDossier + ')"  data-toggle="tooltip" title="Supprimer" id="btnDeletePatient" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-trash-o"></i>' +
                        '</button>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Editer" href="editPatient.php?idPatient=' + aData.idDossier + '" class="btn btn-success btn-sm btn-flat">' +
                        '<i class="fa fa-edit"></i>' +
                        '</a>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Afficher" href="viewPatient.php?idPatient=' + aData.idDossier + '" class="btn btn-info btn-sm btn-flat">' +
                        '<i class="fa fa-eye"></i>' +
                        '</a>'
                    )

                    return nRow;
                },

                "columns": [ 
                    { "data": "idConsultation" },
                    {
                        "data": "idRdv",
                        "render": function(data, type, row)
                        {
                            if( data == null )
                            {
                                return '<span class="badge bg-red"> Null </span>';
                            }
                            else
                            {
                                return '' + data + '';
                            }//num.toFixed(2)
                        }
                    },
                    { "data": "dateDebut" },
                    { "data": "dateFin" },
                    {
                        "data": "motif",
                        "render": function(data, type, row)
                        {
                            var motif = data.substr(0, 50) + ' ...';
                            return motif
                        }
                    },
                    { 
                        "data": "montantNetConsultation",
                        "render": function(data, type, row)
                        {
                            
                            return Number(data).toFixed(2) + ' DH';
                        }
                    },
                    { "data" : "idRdv" }
                ],

                "language":
                {
                    "sProcessing": "Traitement en cours ...",
                    "sLengthMenu": "Afficher _MENU_ lignes",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible",
                    "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                    "sInfoEmpty": "Aucune ligne affichée",
                    "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                    "sInfoPostFix": "",
                    "sSearch": "Chercher:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Chargement...",
                    "oPaginate": {
                        "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                    }
                }

            });        
        },

        error : function(resultat, statut, erreur){
            alert('nooo !!');
        }

    });
}


// get rendez vous patient :
function getRendezVousDossier()
{
    var idDossier = $('#idDossier').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getRdvsDossier&idDossier=' + idDossier,

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);
            
            $('#tblRdvsDossier').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html(
                        '<button data-toggle="tooltip" title="Hooray!" onClick="showModalEditRdv(' + aData.idRdv + ')" data-toggle="modal" data-target="#modalEditRdv" style="display:inline-block"  class="btn btn-success btn-sm btn-flat">' +
                        '<i class="fa fa-edit"> </i>' +
                        '</button>'

                        + 

                        '<button onClick="deleteRdv(' + aData.idRdv + ')" style="display:inline-block; margin-left:2px" class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-times"></i>' +
                        '</button>'

                        + 

                        '  <a data-toggle="tooltip" title="Hooray!" style="display:inline-block; margin-left:2px" href="editAdmin.php?idAdmin=' + aData.idAdmin + '" class="btn btn-primary btn-sm btn-flat">' +
                        '<i class="fa fa-plus"></i> &nbsp;Ajouter Consultation' +
                        '</a>'

                    )

                    return nRow;
                },

                "columns": 
                [
                    { "data": "idRdv" },
                    { "data": "dateRdv" },
                    {
                        "data": "dateRdv",
                        "render": function(data, type, row)
                        {
                            var expire     = new Date(data).getTime() < new Date().getTime();
                            var enAttent   = new Date(data).getTime() >= new Date().getTime();
                            var active     = false;
                            var desactiver = false;
                            var valider    = false;

                            if( row['Etat'] == 'a' ) active = true;
                            
                            else if( row['Etat'] == 'd' ) desactiver = true;
                            
                            else if(  row['Etat'] == 'v' ) valider = true;

                            if( active && expire)
                            {
                                return '<span class="badge bg-yellow"> Expirer </span>';
                            }
                            else if( active && enAttent )
                            {
                                return '<span class="badge bg-blue"> En Attent</span>';
                            }
                            else if( desactiver && enAttent)
                            {
                                return '<span class="badge bg-red"> Desactiver </span>';
                            }
                            else if( expire )
                            {
                                return '<span class="badge bg-yellow"> Expirer </span>';
                            }
                            else if( valider )
                            {
                                return '<span class="badge bg-green"> Valider </span>';
                            }
                        }
                    },
                    { "data": "idRdv" }
                ],

                "language":
                {
                    "sProcessing": "Traitement en cours ...",
                    "sLengthMenu": "Afficher _MENU_ lignes",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible",
                    "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                    "sInfoEmpty": "Aucune ligne affichée",
                    "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                    "sInfoPostFix": "",
                    "sSearch": "Chercher:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Chargement...",
                    "oPaginate": {
                        "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                    }
                }
            });
        },

        error : function(err){
            console.log(err);
        }

    });
}

// Desactiver Rendez Vous : ' + aData.idRdv + '

function desactiverRdv ( idRdv ) {
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment Desactivé Ce Rendez Vous!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Desactivé!',
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=desactiveRdv&idRdv=' + idRdv,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            swal( 'Good !', 'Le Rendez Vous ESt Bien Désactivé !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible De Désactivé Ce Rendez Vous !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible De Désactivé Ce Rendez Vous !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}


function showModalEditRdv(idRdv) 
{
    var idDossier = $('#idDossier').val();
    var rdv;

    $.ajax({

            url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv + '&idDossier=' + idDossier,

            type : 'get',

            success : function (data)
            {
                rdv = JSON.parse(data);

                $('#modalEditRdv .modal-title span').text( rdv.idRdv );
                $('#modalEditRdv #dateRdv').val( rdv.dateRdv );
                $('#modalEditRdv #idRdv').val( rdv.idRdv );
                $("#modalEditRdv").modal({backdrop: "static"});

                if( rdv.Etat === 'd')
                {
                    $('#modalEditRdv #etatRdv').attr( "checked", false ); 
                }
                else 
                {
                    $('#modalEditRdv #etatRdv').attr( "checked", true ); 
                }
            },

            error : function (status)
            {
                console.log(status);
            }

        });
}

function updateRdvDossier()
{
    loaderBtn('btnUpdateRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var dateRdv = document.getElementById('dateRdv');
    dateRdv.parentNode.parentNode.classList.remove('has-error');
    dateRdv.parentNode.parentNode.lastElementChild.innerText = '';

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
                $("#modalEditRdv").modal('hide')
                showAlertSuccess('Le rendez vous est bien modifier !');
                getRendezVousDossier();
            }
            else if( data == '2' )
            {
                var dateRdv = document.getElementById('dateRdv');
                dateRdv.parentNode.parentNode.classList.add('has-error');
                dateRdv.parentNode.parentNode.lastElementChild.innerText = 'Existe déjà un rendez vous avec cette date, vuillez choisie une autre date';

                ShowAlertError('L\'ajout à échoue, existe deja un rendez vous avec cette date vuillez choisie une autre date');
            }
            else if( data == '0' )
            {
                ShowAlertError('La modification à échoue !');
            }
            else if( data == '-1' )
            {
                ShowAlertError('La modification à échoue, tout les champs sont obligatoire !');
                var dateRdv = document.getElementById('dateRdv');
                dateRdv.parentNode.parentNode.classList.add('has-error');
                dateRdv.parentNode.parentNode.lastElementChild.innerText = 'La date de Rendez vous est oblogatoire !';
            }
            else
            {
                ShowAlertError('La modification à échoue, tout les champs sont obligatoire !');

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
            loaderBtn('btnUpdateRdv', '<i class="fa fa-edit"></i> Modifier');
        }

    });
}

function addRdvDossier()
{
    loaderBtn('btnAddRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var dateRdv = document.getElementById('dateAddRdv');
    dateRdv.parentNode.parentNode.classList.remove('has-error');
    dateRdv.parentNode.parentNode.lastElementChild.innerText = '';

    var fd = new FormData(document.querySelector('#FormAddRdv'));

    $.ajax({

        url         :$('#FormAddRdv').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            if( data == '1' )
            {
                $("#modalAddRdv").modal('hide')
                showAlertSuccess('Le rendez vous est bien Ajouter !');
                getRendezVousDossier();
            }
            else if( data == '2' )
            {
                var dateRdv = document.getElementById('dateAddRdv');
                dateRdv.parentNode.parentNode.classList.add('has-error');
                dateRdv.parentNode.parentNode.lastElementChild.innerText = 'Existe déjà un rendez vous avec cette date, vuillez choisie une autre date';

                ShowAlertError('L\'ajout à échoue, existe deja un rendez vous avec cette date vuillez choisie une autre date');
            }
            else if( data == '0' )
            {
                ShowAlertError('L\'ajout à échoue !');
            }
            else if( data == '-1' )
            {
                ShowAlertError('L\'ajout à échoue, tout les champs sont obligatoire !');
                var dateRdv = document.getElementById('dateAddRdv');
                dateRdv.parentNode.parentNode.classList.add('has-error');
                dateRdv.parentNode.parentNode.lastElementChild.innerText = 'La date de Rendez vous est oblogatoire !';
            }
            else
            {
                ShowAlertError('L\'ajout à échoue, tout les champs sont obligatoire !');

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
            loaderBtn('btnAddRdv', '<i class="fa fa-edit"></i> Ajouter');
        }

    });
}

function deleteRdv (idRdv) {
    
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
        preConfirm: function () {
            return new Promise(function (resolve)
            {
                $.ajax({

                    url  : 'includes/functions/controller.php?action=deleteRdvDossier&idRdv=' + idRdv,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            getRendezVousDossier();
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
                    }

                });
            });
        }
    }]);
}

/******** Fin page consultations dossier *******/