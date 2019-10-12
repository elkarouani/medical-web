/******** Début page gestionnaires *******/

$(document).ready(function(){

    getGestionnaires();

    $('#btnDeleteGestionnaire').click(function () {
        DeleteGestionnaire(idGestionnaire);
    });

    $('#btnChoisieImage').click(function () {
        $('#inputFileImage').click();
    });

    $('#btnAddGestionnaire').click(function () {
        saveGestionnaire();
    });
});


// get getGestionnaires :
function getGestionnaires()
{
    $.ajax({

        url : 'getGestionnaires.php',

        type : 'GET',

        success : function(res){
            var res2 = JSON.parse(res);
            $('#tblGestionnaires').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html(
                        '<button onclick="DeleteGestionnaire(' + aData.idGestionaire + ')"  id="btnDeleteGestionnaire" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-trash-o"></i>' +
                        '</button>'

                        +

                        '  <a style="display:inline-block" href="editGestionnaire.php?idGestionaire=' + aData.idGestionnaire + '" class="btn btn-success btn-sm btn-flat">' +
                        '<i class="fa fa-edit"></i>' +
                        '</a>'

                        +

                        '  <a style="display:inline-block" href="viewGestionnaire.php?idGestionaire=' + aData.idGestionnaire + '" class="btn btn-info btn-sm btn-flat">' +
                        '<i class="fa fa-eye"></i>' +
                        '</a>'
                    )

                    return nRow;
                },

                "columns": [
                    {
                        "data": "image",
                        "render": function(data, type, row)
                        {
                            return '<img class="img-circle" width="70" heigth="70" src="http://localhost/siteCabinetDentaire/admin/data/uploades/avatarAdmins/' + data + '" />';
                        }
                    },
                    { "data" : "nom" },
                    { "data" : "prenom" },
                    {
                        "data": "sexe",
                        "render": function(data, type, row)
                        {
                            if( data == 'H' ){
                                return '<span class="badge bg-blue"> Homme </span>'
                            }
                            else{
                                return '<span class="badge bg-maroon"> Femme </span>';
                            }
                        }
                    },
                    { "data" : "tel" },
                    { "data" : "email" },
                    { "data" : "login" },
                    {
                        "data": "active",
                        "render": function(data, type, row)
                        {
                            if( data == 1 ){
                                return '<span class="badge bg-green"> active </span>'
                            }
                            else{
                                return '<span class="badge bg-red"> desactive </span>';
                            }
                        }
                    },
                    { "data" : "login" }

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

// Save Gestionnaire :
function saveGestionnaire()
{

    $('.alert-popup').hide();

    loaderBtn('btnAddGestionnaire', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var elem = document.querySelectorAll("input, textarea");
    var t = 0;
    for (var i = 0; i < elem.length; i++)
    {
        if ( elem[i].parentNode.classList.contains("has-error") )
        {
            elem[i].parentNode.classList.remove('has-error');
            elem[i].parentNode.lastElementChild.innerText = '';
        }
    }

    var fd = new FormData(document.querySelector('#FormAddGestionnaire'));

    $.ajax({

        url         :$('#FormAddGestionnaire').attr('action'),
        type        : 'post',
        data        : fd,
        processData: false,
        contentType: false,

        success     : function(data)
        {
            if( data == '1' )
            {
                loaderBtn('btnAddGestionnaire', 'Enregestrer');
                showAlertSuccess('Le Gestionnaire est bien ajouter !');
                $('#FormAddPatient').reset();
            }
            else if( data == '0' )
            {
                loaderBtn('btnAddGestionnaire', 'Enregestrer');
                ShowAlertError('L\'ajout à échoue !');
            }
            else
            {
                loaderBtn('btnAddGestionnaire', 'Enregestrer');
                ShowAlertError('L\'ajout à échoué tout les champs sont obligatoire !');

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
        }

    });
}

// delete admin :
function DeleteGestionnaire(idGestionnaire)
{
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer ce gestionnaire ?",
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

                    url  : 'includes/functions/controller.php?action=deleteGestionnaire&idGestionnaire=' + idGestionnaire,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            getGestionnaires();
                            swal( 'Good !', 'La suppression est bien effectué !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce compte !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer ce compte !', 'error' ).insertQueueStep(1);
                        resolve();
                    },

                    complete : function ()
                    {
                        console.log('mmmmmm');
                    }

                });
            });
        }
    }]);
}




/******** Fin page gestionnaires *******/