$(document).ready(function () {

	getHoraires();

	$('.timepicker').timepicker({
	  showInputs: false,
	  showMeridian: false,
	  defaultTime: '08:00'
	});

	$('#btnAddHoraire').click(function () {
		addHoraire()
	});	

	// Lundi
	$('#lundiEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('lundiMatinDebut').disabled = false;
			document.getElementById('lundiMatinFin').disabled = false;
			document.getElementById('lundiSoireDebut').disabled = false;
			document.getElementById('lundiSoireFin').disabled = false;
			document.getElementById('lundiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#lundiEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('lundiMatinDebut').disabled = true;
			document.getElementById('lundiMatinFin').disabled = true;
			document.getElementById('lundiSoireDebut').disabled = true;
			document.getElementById('lundiSoireFin').disabled = true;
			document.getElementById('lundiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});


	// Mardi
	$('#mardiEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('mardiMatinDebut').disabled = false;
			document.getElementById('mardiMatinFin').disabled = false;
			document.getElementById('mardiSoireDebut').disabled = false;
			document.getElementById('mardiSoireFin').disabled = false;
			document.getElementById('mardiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#mardiEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('mardiMatinDebut').disabled = true;
			document.getElementById('mardiMatinFin').disabled = true;
			document.getElementById('mardiSoireDebut').disabled = true;
			document.getElementById('mardiSoireFin').disabled = true;
			document.getElementById('mardiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});

	$('#mercrediEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('mercrediMatinDebut').disabled = false;
			document.getElementById('mercrediMatinFin').disabled = false;
			document.getElementById('mercrediSoireDebut').disabled = false;
			document.getElementById('mercrediSoireFin').disabled = false;
			document.getElementById('mercrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#mercrediEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('mercrediMatinDebut').disabled = true;
			document.getElementById('mercrediMatinFin').disabled = true;
			document.getElementById('mercrediSoireDebut').disabled = true;
			document.getElementById('mercrediSoireFin').disabled = true;
			document.getElementById('mercrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});

	$('#jeudiEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('jeudiMatinDebut').disabled = false;
			document.getElementById('jeudiMatinFin').disabled = false;
			document.getElementById('jeudiSoireDebut').disabled = false;
			document.getElementById('jeudiSoireFin').disabled = false;
			document.getElementById('jeudiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#jeudiEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('jeudiMatinDebut').disabled = true;
			document.getElementById('jeudiMatinFin').disabled = true;
			document.getElementById('jeudiSoireDebut').disabled = true;
			document.getElementById('jeudiSoireFin').disabled = true;
			document.getElementById('jeudiSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});

	$('#vendrediEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('vendrediMatinDebut').disabled = false;
			document.getElementById('vendrediMatinFin').disabled = false;
			document.getElementById('vendrediSoireDebut').disabled = false;
			document.getElementById('vendrediSoireFin').disabled = false;
			document.getElementById('vendrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#vendrediEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('vendrediMatinDebut').disabled = true;
			document.getElementById('vendrediMatinFin').disabled = true;
			document.getElementById('vendrediSoireDebut').disabled = true;
			document.getElementById('vendrediSoireFin').disabled = true;
			document.getElementById('vendrediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});

	$('#samediEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('samediMatinDebut').disabled = false;
			document.getElementById('samediMatinFin').disabled = false;
			document.getElementById('samediSoireDebut').disabled = false;
			document.getElementById('samediSoireFin').disabled = false;
			document.getElementById('samediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#samediEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('samediMatinDebut').disabled = true;
			document.getElementById('samediMatinFin').disabled = true;
			document.getElementById('samediSoireDebut').disabled = true;
			document.getElementById('samediSoireFin').disabled = true;
			document.getElementById('samediSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});



	$('#dimancheEtatOuvert').change(function()
	{
		if( this.checked )
		{
			document.getElementById('dimancheMatinDebut').disabled = false;
			document.getElementById('dimancheMatinFin').disabled = false;
			document.getElementById('dimancheSoireDebut').disabled = false;
			document.getElementById('dimancheSoireFin').disabled = false;
			document.getElementById('dimancheSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#fff';
		}
	});

	$('#dimancheEtatFerme').change(function()
	{
		if( this.checked )
		{
			document.getElementById('dimancheMatinDebut').disabled = true;
			document.getElementById('dimancheMatinFin').disabled = true;
			document.getElementById('dimancheSoireDebut').disabled = true;
			document.getElementById('dimancheSoireFin').disabled = true;
			document.getElementById('dimancheSoireDebut').parentNode.parentNode.parentNode.parentNode.style.background = '#f7eaea';
		}
	});

});

// get horaires :
function getHoraires()
{
	$('#loader2').show();

	$.ajax({

		url : 'includes/functions/controller.php?action=getHoraires',

		type : 'GET',

		success : function(res)
		{
			res2 = JSON.parse(res);

			$('#tblHoraires').DataTable({

				data : res2,

				destroy: true,

				"fnRowCallback": function (nRow, aData, iDisplayIndex)
				{
					var oSettings = (this.fnSettings) ? this.fnSettings() : this;

					$("td:last", nRow).html(
							'<button onclick="DeleteAdmin(' + aData.idHoraire + ', false)" id="btnDeleteAdmin" style="display:inline-block"  class="btn btn-danger btn-sm btn-flat">' +
							'<i class="fa fa-trash-o"></i>' +
							'</button>'
					)

					return nRow;
				},

				"columns":
				[
					{ "data": "idHoraire" },
					{ "data": "nomHoraire" },
					{ "data": "descriptionHoriare" },
					{ "data": "active" }
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


//save admin :
function addHoraire()
{

    $('.alert-popup').hide();

    loaderBtn('btnAddHoraire', 'Chargement  &nbsp;' + '<i style="font-size: 13px;" class="fa fa-spinner fa-1x fa-spin"></i>');

    var elem = document.querySelectorAll("input, textarea");
    var t = 0;
    for (var i = 0; i < elem.length; i++)
    {
        if ( elem[i].parentNode.parentNode.parentNode.parentNode.classList.contains("has-error") )
        {
            elem[i].parentNode.parentNode.parentNode.parentNode.classList.remove('has-error');
        }
    }

    var fd = new FormData(document.querySelector('#formAddHoraire'));

    $.ajax({

        url         :$('#formAddHoraire').attr('action'),
        type        : 'post',
        data        : fd,
        processData : false,
        contentType : false,
        success     : function(data)
        {

            if( data == '1' )
            {
                loaderBtn('btnAddHoraire', 'Enregestrer');
                showAlertSuccess('Les informations est bien enregestrer !');
                $('#FormAddPatient').trigger("reset");
            }
            else if( data == '0' )
            {
                loaderBtn('btnAddHoraire', 'Enregestrer');
                ShowAlertError('L\'ajout à échoue !');
            }
            else
            {
                loaderBtn('btnAddHoraire', 'Enregestrer');
                ShowAlertError('L\'ajout à échoué, Vuillez choisie un date corréect !');

                errors = JSON.parse(data);

                for (var err in errors)
                {
                    var el = document.getElementById(err);
                    el.parentNode.parentNode.parentNode.parentNode.classList.add('has-error');
                };
            }
        },

        error : function(status)
        {
            console.log(status);
        }

    });
}


