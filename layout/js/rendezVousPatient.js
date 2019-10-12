$(document).ready(function () 
{
    $('.select2').select2(); 

    $("#btnAddRdv").attr("disabled","disabled");
    
    $('#dateAddRdv').datepicker('setStartDate', new Date());

    $('#dateAddRdv').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });


    $('#loaderInput').hide();

    $('#loaderInputEditRdv').hide();

    $('#horairesDisponible').hide();
    
    $('#horairesDisponibleForEdit').hide();

    moment().format();
  
    getRendezVousDossier();
    
    $('#dossiers').select2({ width: '100%'});
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

    $('#dateEditRdv').change(function(){
        getHorairesDisponibleforEdit();
    });

    $('#dateAddRdv').change(function(){
        getHorairesDisponible();
    });

    $('#btnAddRdv').click(function(){
        addRdvDossier(); 
    }); 

});

// get rendez vous patient :
function getRendezVousDossier(reload)
{
    var idDossier = $('#idDossier').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getRdvsDossier&idDossier=' + idDossier,

        type : 'GET',

        success : function(res)
        {
            res2 = JSON.parse(res);

            if( reload )
            {
                if( res == '0' )
                {
                    location.reload(); 
                }
                else
                {
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
                                '<i class="fa fa-trash"></i>' +
                                '</button>'

                                + 

                                '  <a onClick="viewInfoRdv(' + aData.idRdv + ')" class="btn btn-info btn-sm btn-flat">' +
                                '<i class="fa fa-eye"></i> &nbsp;Voire les détails' +
                                '</a>'

                            )

                            return nRow;
                        },

                        "columns": 
                        [
                            { "data": "idRdv" },
                            { 
                                "data": "dateRdv",
                                 "render": function(data, type, row)
                                {
                                    
                                    return '<span class="badge bg-blue"> ' + data + ' </span>';
                                }
                            },
                            {
                                "data": "dateRdv",

                                "render": function(data, type, row)
                                {
                                    var expirer  = new Date(data).getTime() < new Date().getTime();
                                    var enAttent = new Date(data).getTime() >= new Date().getTime();
                                    var active   = false;
                                    var annule   = false;
                                    var valider  = false;

                                    if( row['Etat'] == 'a' ) 
                                    {
                                        active = true;
                                    }
                                    else if( row['Etat'] == 'd' ) 
                                    {
                                        annule = true;  
                                    }
                                    else if(  row['Etat'] == 'v' ) 
                                    {
                                        valider = true;
                                    }

                                    if( active && expirer)
                                    {
                                        return '<span class="badge bg-yellow"> Expirer </span>';
                                    }
                                    else if( active && enAttent )
                                    {
                                        return '<span class="badge bg-blue"> En Attent</span>';
                                    }
                                    else if( annule && enAttent)
                                    {
                                        return '<span class="badge bg-red"> Annulé </span>';
                                    }
                                    else if( valider  )
                                    {
                                        return '<span class="badge bg-green"> Validé </span>';
                                    }
                                    else if( expirer )
                                    {
                                        return '<span class="badge bg-yellow"> Expirer </span>';
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
                }
            }
            else
            {
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
                            '<i class="fa fa-trash"></i>' +
                            '</button>'

                            + 

                            '  <a onClick="viewInfoRdv(' + aData.idRdv + ')" class="btn btn-info btn-sm btn-flat">' +
                            '<i class="fa fa-eye"></i> &nbsp;Voire les détails' +
                            '</a>'
                        )

                        return nRow;
                    },

                    "columns": 
                    [
                        { "data": "idRdv" },
                        { 
                            "data": "dateRdv",
                             "render": function(data, type, row)
                            {
                                
                                return '<span class="badge bg-blue"> ' + data + ' </span>';
                            }
                        },
                        {
                            "data": "dateRdv",

                            "render": function(data, type, row)
                            {
                                var dateSystem = new Date().getTime();
                                var dateRdv = new Date(data).getTime();
                                var expirer  = dateRdv < dateSystem;
                                var enAttent = dateRdv >= dateSystem;
                                var active   = false;
                                var annule   = false;
                                var valider  = false;

                                if( row['Etat'] == 'a' ) 
                                {
                                    active = true;
                                }
                                else if( row['Etat'] == 'd' ) 
                                {
                                    annule = true;
                                }
                                else if(  row['Etat'] == 'v' ) 
                                {
                                    valider = true;
                                }

                                if( active && expirer)
                                {
                                    return '<span class="badge bg-yellow"> Expirer </span>';
                                }
                                else if( active && enAttent )
                                {
                                    return '<span class="badge bg-blue"> En Attent</span>';
                                }
                                else if( annule && enAttent)
                                {
                                    return '<span class="badge bg-red"> Annulé </span>';
                                }
                                else if( valider  )
                                {
                                    return '<span class="badge bg-green"> Validé </span>';
                                }
                                else if( expirer )
                                {
                                    return '<span class="badge bg-yellow"> Expirer </span>';
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
            }
        },

        error : function(err){
            console.log(err);
        }

    });
}

// Desactiver Rendez Vous :

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
    $('#horairesDisponibleForEdit').slideUp();
    var idDossier = $('#idDossier').val();
    var rdv;
    var matin       = $('#matinEdit');
    var soire       = $('#soireEdit');
    var dureeRdv    = $('#nbrMinutes').val();
    var rdvReserver;
    $('#EtatRdv').show();
    $('#valideRdv').show();

    $.ajax({

        url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,

        type : 'get',

        success : function (data)
        {
            rdv = JSON.parse(data);

            $('#modalEditRdv .modal-title span').text( rdv.idRdv );
            $('#idRdv').val(rdv.idRdv);
            $('#modalEditRdv #dateEditRdv').val( rdv.dateRdv );
            $('#modalEditRdv #heureRdv').val( moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm") );

            if( rdv.Etat === 'd')
            {
                $('#modalEditRdv #desactiveRdv').attr( "checked", true ); 
            }
            else if( rdv.Etat === 'a' ) 
            {
                $('#modalEditRdv #activeRdv').attr( "checked", true ); 
            }
            else 
            {
                $('#modalEditRdv #valideRdv').attr( "checked", true ); 
            }

            var expirer  = new Date(rdv.dateRdv).getTime() < new Date().getTime();
            var enAttent = new Date(rdv.dateRdv).getTime() >= new Date().getTime();
            var active   = false;
            var annule   = false;
            //

            if( rdv.Etat == 'a' ) 
            {
                active = true;
            }
            else if( rdv.Etat == 'd' ) 
            {
                annule = true;  
            }
            else if(  rdv.Etat == 'v' ) 
            {
                valider = true;
            }
            
            if( active && expirer)
            {
                $('#EtatRdv').hide();
            }
            else if(expirer)
            {
                $('#EtatRdv').hide();
            }
            else if( annule )
            {
                $('#valideRdv').hide();
            }
            $("#modalEditRdv").modal({backdrop: "static"});
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
                ShowAlertError('La modification du rendez vous à échoue, vuillez choisie une date corréecte !');
            }
            else
            {
                ShowAlertError('La modification du rendez vous à échoue, La date de rendez vous est obligatoire !');
            }
        },
        error : function(status)
        {
            console.log(status);
        }, 
        complete : function() 
        {
            loaderBtn('btnUpdateRdv', '<i class="fa fa-save"></i>&nbsp; Modifier');
            $('#horairesDisponible').slideUp();
        }

    });
}

function addRdvDossier()
{
    loaderBtn('btnAddRdv', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var dateRdv    = $('#dateAddRdv').val();
    var heureRdv   = $('input[name=heurRdv]:checked').val();
    var dateAddRdv = dateRdv + ' ' + heureRdv + ':00' ;

    var idDossier = $('#idDossier').val();

    $.ajax({

        url         :$('#FormAddRdv').attr('action'),
        type        : 'post',
        data        : {dateRdv : dateAddRdv, idDossier : idDossier},
        success     : function(data)
        {
            if( data == '1' )
            {
                $("#modalAddRdv").modal('hide')
                showAlertSuccess('Le rendez vous est bien Ajouter !');
                getRendezVousDossier();
            }
            else
            {
                ShowAlertError('L\'ajout de rendez vous à échoue, vuillez choisie une date corréecte !');
            }
        },
        error : function(status)
        {
            console.log(status);
        },

        complete : function(resultat, statut)
        {
            loaderBtn('btnAddRdv', '<i class="fa fa-save"></i>&nbsp; Enregestrer');
            
            $('#horairesDisponible').slideUp();
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
                            getRendezVousDossier(true);
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

// get Rdv date : getHorairesDisponibleforEdit
function getHorairesDisponible() 
{

    $('#loaderInput').show();
    $('#horairesDisponible').slideUp();
    $("#btnAddRdv").attr("disabled", true);

    var dateAddRdv  = $('#dateAddRdv').val();
    var matin       = $('#matin');
    var soire       = $('#soire');
    var rdvReserver;
    var dureeRdv    = $('#nbrMinutes').val();

    $.ajax({

        url : 'includes/functions/controller.php?action=getHorairesDisponible&dateAddRdv=' + dateAddRdv,

        type : 'GET',

        success : function(data)
        {
            // get Rdvs Reserver :
            $.ajax({

                url : 'includes/functions/controller.php?action=getRdvsReserver&dateAddRdv=' + dateAddRdv,

                type : 'GET',

                success : function(rdvRserve)
                {
                    $("#btnAddRdv").attr("disabled", false);

                    if( rdvRserve == 0 )
                    {
                        rdvReserver = []; 

                        if( data == 0 )
                        {
                            res2 = [];                
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponible').slideDown();
                            $('#loaderInput').hide();
                            
                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');
                            
                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                radioBoxes += '<div class="radio-inline"><label><input type="radio" id="heurRdv" name="heurRdv"value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start2 = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatin').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');
                            
                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {    
                                radioBoxes += '<div style="margin-left: 10px; margin-bottom: 7px;" class="radio-inline"><label><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoire').html(radioBoxes);
                        }
                    }
                    else
                    {
                        rdvReserver = JSON.parse(rdvRserve);

                        if( data == 0 )
                        {
                            res2 = [];                
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponible').slideDown();
                            $('#loaderInput').hide();
                            
                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');
                            
                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                var time = moment(start2, 'hh:mm:ss').format("HH:mm:ss");
                                
                                var isReserve = false;
                                
                                for (var i = 0; i < rdvReserver.length; i++) 
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                } 
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input id="heurRdv" disabled type="radio">' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }  

                                start2   = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatin').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');
                            
                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {    

                                var time = moment(start, 'hh:mm:ss').format("HH:mm:ss");
                                var isReserve = false;
                                
                                for (var i = 0; i < rdvReserver.length; i++) 
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                } 
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input disabled type="radio" >' + moment(start, 'hh:mm:ss').format("HH:mm") + '</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + ' >' + moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }  

                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoire').html(radioBoxes);
                        }
                    }
                },

                error : function(err)
                {
                    console.log(err);
                }

            }); 
        }, 

        error : function(err)
        {
            console.log(err);
            $('#loaderInput').hide();
        }

    });
}

// get Rdv date : getHorairesDisponibleforEdit
function getHorairesDisponibleforEdit() 
{
    $('#loaderInputEditRdv').show();
    $('#horairesDisponibleForEdit').slideUp();
    $("#btnUpdateRdv").attr("disabled", true);

    var dateEditRdv = $('#dateEditRdv').val();
    var matin       = $('#matinEdit');
    var soire       = $('#soireEdit');
    var dureeRdv    = $('#nbrMinutes').val();
    var rdvReserver;

    $.ajax({

        url : 'includes/functions/controller.php?action=getHorairesDisponible&dateAddRdv=' + dateEditRdv,

        type : 'GET',

        success : function(data)
        {
            // get Rdvs Reserver :
            $.ajax({

                url : 'includes/functions/controller.php?action=getRdvsReserver&dateAddRdv=' + dateEditRdv,

                type : 'GET',

                success : function(rdvRserve)
                {
                    $("#btnUpdateRdv").attr("disabled", false);

                    if( rdvRserve == 0 )
                    {
                        rdvReserver = []; 

                        if( data == 0 )
                        {
                            res2 = [];                
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponibleForEdit').slideDown();
                            $('#loaderInputEditRdv').hide();
                            
                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');
                            
                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                radioBoxes += '<div class="radio-inline"><label><input type="radio" id="heurRdv" name="heurRdv"value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start2 = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatinEdit').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');
                            
                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {    
                                radioBoxes += '<div style="margin-left: 10px; margin-bottom: 7px;" class="radio-inline"><label><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + '>'+ moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoireEdit').html(radioBoxes);
                        }
                    }
                    else
                    {
                        rdvReserver = JSON.parse(rdvRserve);

                        if( data == 0 )
                        {
                            res2 = [];                
                        }
                        else
                        {
                            res2 = JSON.parse(data);

                            $('#horairesDisponibleForEdit').slideDown();
                            $('#loaderInputEditRdv').hide();
                            
                            matin.html(moment(res2.matinDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.matinFin, 'hh:mm:ss').format("HH:mm"));
                            soire.html(moment(res2.soireDebut, 'hh:mm:ss').format("HH:mm") + '&nbsp;&nbsp;<i class="fa fa-long-arrow-right text-black"></i>&nbsp;&nbsp;' + moment(res2.soireFin, 'hh:mm:ss').format("HH:mm"));

                            var matinDebut = moment(res2.matinDebut, 'hh:mm:ss');
                            var matinFin = moment(res2.matinFin, 'hh:mm:ss');
                            
                            var start2 = matinDebut.add(0, 'minutes');
                            var radioBoxes = '';

                            while(start2.isBefore(matinFin))
                            {
                                var time = moment(start2, 'hh:mm:ss').format("HH:mm:ss");
                                
                                var isReserve = false;
                                
                                for (var i = 0; i < rdvReserver.length; i++) 
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                } 
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input id="heurRdv" disabled type="radio">' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input id="heurRdv" checked type="radio" name="heurRdv" value=' + moment(start2, 'hh:mm:ss').format("HH:mm") + '>' + moment(start2, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }  

                                start2   = matinDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colMatinEdit').html(radioBoxes);

                            ///

                            var soireDebut = moment(res2.soireDebut, 'hh:mm:ss');
                            var soireFin = moment(res2.soireFin, 'hh:mm:ss');
                            
                            var start = soireDebut.add(0, 'minutes');
                            radioBoxes = '';

                            while(start.isBefore(soireFin))
                            {    

                                var time = moment(start, 'hh:mm:ss').format("HH:mm:ss");
                                var isReserve = false;
                                
                                for (var i = 0; i < rdvReserver.length; i++) 
                                {
                                    if( time == rdvReserver[i].dateI)
                                    {
                                        isReserve = true;
                                    }
                                } 
                                if( isReserve )
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="text-decoration: line-through; font-size: 15px; color:#f00 !important" disabled ><input disabled type="radio" >' + moment(start, 'hh:mm:ss').format("HH:mm") + '</label> </div>';
                                }
                                else
                                {
                                    radioBoxes += '<div class="radio-inline"><label style="font-size: 15px;"><input type="radio" name="heurRdv" value=' + moment(start, 'hh:mm:ss').format("HH:mm") + ' >' + moment(start, 'hh:mm:ss').format("HH:mm") +'</label> </div>';
                                }  

                                start = soireDebut.add(dureeRdv, 'minutes');
                            }

                            $('#colSoireEdit').html(radioBoxes);
                        }
                    }
                },

                error : function(err)
                {
                    console.log(err);
                }

            });
        }, 

        error : function(err)
        {
            console.log(err);
            $('#loaderInputEditRdv').hide();
        }
    });
}


function viewInfoRdv(idRdv) 
{
    var rdv;
    var rdvReserver;

    $.ajax({

        url  : 'includes/functions/controller.php?action=getRdv&idRdv=' + idRdv,

        type : 'get',

        success : function (data)
        {
            rdv = JSON.parse(data);

            $('#modalViewRdv .modal-title span').text( rdv.idRdv );
            $('#modalViewRdv #viewIdDossier').text( rdv.idDossier );
            $('#modalViewRdv #viewIdRdv').text( rdv.idRdv );
            $('#modalViewRdv #nomPatient a').attr( 'href', 'http://localhost/sitecabinetDentaire/admin/viewPatient.php?idPatient=' + rdv.idPatient );
            $('#modalViewRdv #nomPatient a').text( rdv.prenom + '  ' + rdv.nom );
            $('#modalViewRdv #viewNumTele').text( rdv.tel );
            $('#modalViewRdv #viewDateRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("dddd-MMMM-YYYY"));
            $('#modalViewRdv #viewHeureRdv span').text(moment(rdv.dateRdv, 'yyyy-mm-dd hh:mm:ss').format("HH:mm"));

            var dateSystem = new Date().getTime();
            var dateRdv    = new Date(rdv.dateRdv).getTime();
            var expirer    = dateRdv < dateSystem;
            var enAttent   = dateRdv >= dateSystem;
            var active     = false;
            var annule     = false;
            var valider    = false;

            if( rdv.Etat == 'a' ) 
            {
                active = true;
            }
            else if( rdv.Etat == 'd' ) 
            {
                annule = true;  
            }
            else if(  rdv.Etat == 'v' ) 
            {
                valider = true;
            }

            if( active && expirer)
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge bg-yellow"> Expirer </span>');
            }
            else if( active && enAttent )
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge bg-blue"> En Attent</span>');
            }
            else if( annule && enAttent)
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge bg-red"> Annulé </span>');
            }
            else if( valider  )
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge bg-green"> Validé </span>');
            }
            else if( expirer )
            {
                $('#modalViewRdv #viewEtatRdv').html('<span class="badge bg-yellow"> Expirer </span>');
            }

            $("#modalViewRdv").modal({backdrop: "static"});
        },
        error : function (status)
        {
            console.log(status);
        }

    });
} 