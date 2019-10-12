/****** Debut page consultations dossier *******/

$(document).ready(function(){

    $('.select2').select2();

    getDossiersPatient();

    $('.dateTimepicker').datetimepicker({
        format: 'yyyy-mm-dd HH',
        autoclose: true
    });

    $('#btnFilterWithdateCreation').click( function() {
        getDossiersDateCreation();
    });

    $('#btnFilterWithPatient').click( function() {
        getDossiersPatient();
    });

});

// get Dossiers :
function getDossiersPatient()
{
    var idPatient = document.getElementById('idPatient').value;

    $.ajax({

        url : 'includes/functions/controller.php?action=getDossiersPatient&idPatient=' + idPatient,

        type : 'GET',

        success : function(res)
        {
            var res2 = JSON.parse(res);

            $('#tblDossiers').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html
                    (
                        '<a class="btn btn-info btn-sm" href="detailsDossier.php?idDossier=' + aData.idDossier + '" class="text-green"><i class="fa fa-eye"></i>&nbsp; Voire les détails</a> <a href="javascript:void(0)" onclick="deleteDossier(' + aData.idDossier + ')" class="text-red"> <i class="fa fa-trash-o"></i>Supprimer</a>'
                    )

                    return nRow;
                },

                "columns": [
                    { "data": "idDossier" },
                    { "data": "dateCreation" },
                    { "data": "patient" },
                    {
                        "data": "montantDossier",
                        "render": function(data, type, row)
                        {
                            return '<span class="badge badge-no-circle bg-green">' + data + ' DH</span>';
                        }
                    },
                    { "data": "idDossier" }
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
            console.log(resultat);
        }

    });
}

function getDossiersDateCreation()
{
    var dateDebut = document.getElementById('dateDebut');
    var dateFin = document.getElementById('dateFin');

    if( dateDebut.parentNode.classList.contains('has-error') )
    {
        dateDebut.parentNode.classList.remove('has-error');
    }
    if( dateFin.parentNode.classList.contains('has-error') )
    {
        dateFin.parentNode.classList.remove('has-error');
    }

    if( dateDebut.value == '' && dateFin.value == '' )
    {
        dateDebut.parentNode.classList.add('has-error');
        dateFin.parentNode.classList.add('has-error');
        ShowAlertError('Impossible de checher, Tout les champs sont obligatoires');
        return false;
    }

    if( dateDebut.value == '' )
    {
        dateDebut.parentNode.classList.add('has-error');
        ShowAlertError('Impossible de chercher, Tout les champs sont obligatoires');
        return false;
    }
    if( dateFin.value == '' )
    {
        dateFin.parentNode.classList.add('has-error');
        ShowAlertError('Impossible de chercher, Tout les champs sont obligatoires');
        return false;
    }

    $.ajax({

        url : 'http://localhost/sitecabinetDentaire/admin/includes/functions/controller.php?action=getDossiersDateCreation&dateDebut=' + dateDebut.value + '&dateFin=' + dateFin.value,

        type : 'GET',

        success : function(res)
        {
            var res2 = JSON.parse(res);
            $('#tblDossiers').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html(
                        '<button onclick="deleteDossier(' + aData.idDossier + ')" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-trash-o"></i>' +
                        '</button>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Afficher" href="detailsDossier.php?idDossier=' + aData.idDossier + '" class="btn btn-info btn-sm btn-flat">' +
                        ' Voire Les Détails &nbsp; <i class="fa fa-arrow-right"></i>' +
                        '</a>'
                    )

                    return nRow;
                },

                "columns": [
                    { "data": "idDossier" },
                    { "data": "dateCreation" },
                    {
                        "data": "idDossier",
                        "render": function(data, type, row)
                        {
                            return '<a href="rendezVousPatient.php?idDossier=' + data + '" style="display:inline-block"  class="text-primary"> Rendez vous <span><i class="fa fa-angle-double-right "></i></span> </a>';
                        }
                    },
                    {
                        "data": "idDossier",
                        "render": function(data, type, row)
                        {
                            return '<a href="consultationsDossier.php?idDossier=' + data + '" style="display:inline-block"  class="text-green"> Consultations <span><i class="fa fa-angle-double-right "></i></span></a>';
                        }
                    },
                    {
                        "data": "idDossier",
                        "render": function(data, type, row)
                        {
                            return '<a href="paiementsDossier.php?idDossier=' + data + '" style="display:inline-block"  class="text-yellow"> Paiements <span><i class="fa fa-angle-double-right "></i></span></a>';
                        }
                    },
                    { "data": "idDossier" }
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
            console.log(resultat);
        }

    });
}

/*// Add consultation :
function addConsultation()
{
    loaderBtn('btnAddConsultation', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var fd = new FormData(document.querySelector('#FormAddConsultation'));

    $.ajax({

        url         :$('#FormAddConsultation').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {
            console.log(data);
        },
        error : function(status)
        {
            console.log(status);
        },

        complete : function(resultat, statut)
        {
            loaderBtn('btnAddConsultation', '<i class="fa fa-check"></i> Enregestrer');
        }

    });
}


// Delete Consultation :
function deleteConsultation(idConsultation)
{
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer cette consultation !",
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

                    url  : 'includes/functions/controller.php?action=deleteConsultation&idConsultation=' + idConsultation,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            swal( 'Good !', 'La consultation est bien supprimer !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer cette consultation !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer cette consultation !', 'error' ).insertQueueStep(1);
                        resolve();
                    }

                });
            });
        }
    }]);
}*/

/******** Fin page consultations dossier *****/