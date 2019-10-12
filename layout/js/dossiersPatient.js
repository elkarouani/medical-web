/******** Debut page dossiers *******/

$(document).ready(function(){
    getDossiers();

    //$('#btnAddDossier').click(function()
    //{
    //    addDossier();
    //});

});

// get patients :
function getDossiers()
{
    var idPatient = document.getElementById('idPatient').value;

    $.ajax({  

        url : 'includes/functions/controller.php?action=getDossiers&idPatient=' + idPatient,

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
                            return '<a href="rendezVousPatient.php?idDossier=' + data + '" style="display:inline-block"  class="text-primary"> Rendez Vous <span><i class="fa fa-angle-double-right "></i></span> </a>';
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

//function addDossier()
//{
//    swal.queue
//    ([{
//        title: 'Etes-vous sûr ?',
//        text: "Voulez vous vraiment Ajouter Nouveau Dossier à Ce Patient ?",
//        type: 'warning',
//        showCancelButton: true,
//        confirmButtonColor: '#3085d6',
//        cancelButtonColor: '#d33',
//        confirmButtonText: 'Oui, Ajouter!',
//        showLoaderOnConfirm: true,
//        preConfirm: function ()
//        {
//            return new Promise(function (resolve)
//            {
//                var idPatient = document.getElementById('idPatient').value;
//
//                $.ajax({
//
//                    url  : 'includes/functions/controller.php?action=addDossier&idPatient=' + idPatient,
//
//                    type : 'get',
//
//                    success : function (data)
//                    {
//                        if( data == 1 )
//                        {
//                            getDossiers();
//                            swal( 'Good !', 'L\' affectation du dissier est bien bien effectué !', 'success' ).insertQueueStep(1);
//                            resolve();
//                        }
//                        else
//                        {
//                            swal( 'Erreur !', 'Impossible D\'Ajouter Nouveau Dossiers à Ce Patient !', 'error' ).insertQueueStep(1);
//                            resolve();
//                        }
//                    },
//
//                    error : function (status)
//                    {
//                        swal( 'Erreur !', 'Impossible D\'Ajouter Nouveau Dossiers à Ce Patient !', 'error' ).insertQueueStep(1);
//                        resolve();
//                    }
//
//                });
//            });
//        }
//    }]);
//}

function deleteDossier(idDossier) {
    
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
                            getDossiers();
                            swal( 'Good !', 'Le dossier est bien supprimer !', 'success' ).insertQueueStep(1);
                            resolve();
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









/******** Fin page dossiers *******/