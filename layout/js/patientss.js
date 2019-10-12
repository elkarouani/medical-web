/****** début page admin *****/

$(document).ready(function(){

    getPatients();

    $(".modal").modal({
        "backdrop"  : "static",
        "keyboard"  : true,
        "show"      : false
    });

    $('#btnAddPatient').click(function(){
        savePatient();
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('#btnNewPatient').click(function () {

        $('.alert-popup').hide();
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
    });
});


/* Patients functions */
// get patients :
function getPatients() 
{ 
    $.ajax({ 

        url : 'getPatients.php',

        type : 'GET',

        success : function(res){ 
            var res2 = JSON.parse(res);
            $('#tblPatients').DataTable({

                data : res2,

                destroy: true,

                "fnRowCallback": function (nRow, aData, iDisplayIndex)
                {
                    var oSettings = (this.fnSettings) ? this.fnSettings() : this;

                    $("td:last", nRow).html(
                        '<button onclick="DeletePatient(' + aData.idPatient + ', ' + false + ')"  data-toggle="tooltip" title="Supprimer" id="btnDeletePatient" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
                        '<i class="fa fa-trash-o"></i>' +
                        '</button>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Editer" href="editPatient.php?idPatient=' + aData.idPatient + '" class="btn btn-success btn-sm btn-flat">' +
                        '<i class="fa fa-edit"></i>' +
                        '</a>'

                        +

                        '  <a style="display:inline-block" data-toggle="tooltip" title="Afficher" href="viewPatient.php?idPatient=' + aData.idPatient + '" class="btn btn-info btn-sm btn-flat">' +
                        '<i class="fa fa-eye"></i>' +
                        '</a>'
                    )

                    return nRow;
                },

                "columns": [
                    { "data": "cin" },
                    { "data": "nom" },
                    { "data": "prenom" },
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
                    { "data": "dateNaissance" },
                    { "data": "tel" },
                    { "data": "email" },
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
                    {
                        "data": "idPatient",
                        "render": function(data, type, row)
                        {
                            return '<a href="dossiersPatient.php?idPatient=' + data + '" class="text-red"> Dossiers  <i class="fa fa-angle-double-right "></i> </a>';
                        }
                    },
                    { "data": "idPatient" }

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

//save admin :
function savePatient()
{
    loaderBtn('btnAddPatient', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

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

    var fd = new FormData(document.querySelector('#FormAddPatient'));

    $.ajax({

        url         :$('#FormAddPatient').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,

        success     : function(data)
        {
            console.log(data);
            if( data == '1' )
            {
                loaderBtn('btnAddPatient', 'Enregestrer');
                showAlertSuccess('Le patient est bien jouter !');
                //$('#FormAddPatient').trigger("reset");
            }
            else if( data == '0' )
            {
                loaderBtn('btnAddPatient', 'Enregestrer');
                ShowAlertError('L\'ajout à échoue !');
            }
            else
            {
                loaderBtn('btnAddPatient', 'Enregestrer');
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

function DeletePatient(idPatient, reload)
{
    swal.queue
    ([{
        title: 'Etes-vous sûr?',
        text: "voulez vous vraiment supprimer ce patient !",
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

                    url  : 'includes/functions/controller.php?action=deletePatient&idPatient=' + idPatient,

                    type : 'get',

                    success : function (data)
                    {
                        if( data == 1 )
                        {
                            if( !reload )
                            {
                                getPatients();
                            }
                            else
                            {
                                window.location.href = 'patients.php';
                            }

                            swal( 'Good !', 'La suppression est bien effectué !', 'success' ).insertQueueStep(1);
                            resolve();
                        }
                        else
                        {
                            swal( 'Erreur !', 'Impossible de supprimer ce patient !', 'error' ).insertQueueStep(1);
                            resolve();
                        }
                    },

                    error : function (status)
                    {
                        swal( 'Erreur !', 'Impossible de supprimer ce patient !', 'error' ).insertQueueStep(1);
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

function showAlertSuccess(text)
{
    document.getElementById("alert-success").lastElementChild.innerHTML = text;
    $('#modalAddAdmin').modal('hide');
    $('#alert-success').fadeIn(500);
    setTimeout(function(){ $('#alert-success').fadeOut(500); }, 5000);
}

function ShowAlertError(text)
{
    document.getElementById("alert-error").lastElementChild.innerHTML = text;
    $('#alert-error').fadeIn(500);
    setTimeout(function(){ $('#alert-error').fadeOut(500); }, 5000);
}

function loaderBtn(idBtn, text)
{
    document.querySelector('#' + idBtn).innerHTML = text;
}

/***** fin page admin *****/




