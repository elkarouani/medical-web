/******* Debut page consultations dossier *******/

$(document).ready(function(){
    
    getConsultations();

    $('.dateTimepicker').datetimepicker({
        format: 'yyyy-mm-dd HH',
        autoclose: true
    });

    $('#btnAddConsultation').click( function() {
        addConsultation();
    });

});

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
                        '<button onclick="deleteConsultation(' + aData.idConsultation + ')"  data-toggle="tooltip" title="Supprimer" id="btnDeletePatient" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-trash-o"></i>' +
                        '</button>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Editer" href="editPatient.php?idPatient=' + aData.idDossier + '" class="btn btn-success btn-sm btn-flat">' +
                        '<i class="fa fa-edit"></i>' +
                        '</a>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Afficher" href="viewPatient.php?idPatient=' + aData.idDossier + '" class="btn btn-info btn-sm btn-flat">' +
                        '<i class="fa fa-eye"></i> &nbsp; Voire Les Détails' +
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

// Add consultation :
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
}

/******** Fin page consultations dossier ******/