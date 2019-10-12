<html>
<head>
	<title></title>
	<link href="layout/css/select2.min.css" rel="stylesheet" />
</head>
<body>
	<select id="annee">
		<option value="2017">2017</option>
		<option value="2018">2018</option>
	</select>
	<div style="width: 800px; height: 1000px">
		<canvas id="line-chart"></canvas>
	</div>
	<script type="text/javascript" src="layout/js/jquery.min.js"></script>
	<script src="layout/js/select2.min.js"></script>
	<script src="layout/js/Chart.min.js"></script>
	<script>
		getNbrDossierOuvert();

		$('#annee').change(function(){
			getNbrDossierOuvert();
		});

		function getNbrDossierOuvert()
		{
			var annee = $('#annee').val();
			$.ajax({

				url : 'includes/functions/controller.php?action=getNbrDossierParMois&annee=' + annee,

				type : 'GET',

				success : function(res)
				{
					console.log(res);

					new Chart(document.getElementById("line-chart"), {
						type: 'line',
						data: {
							labels: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],

							datasets:
							[
								{
									fillColor : "rgba(220,220,220,0.5)",
									strokeColor : "rgba(220,220,220,1)",
									pointColor : "rgba(220,220,220,1)",
									pointStrokeColor : "#fff",
									data: res,
									label: "Dossiers",
									borderColor: "#3e95cd"
								}
							]
						},
						options: {
							title: {
								display: true,
								text: 'Nombre de dossier ouvert à : ' + annee
							} // animation duration after a resize
						}
					});
				},

				error : function(resultat, statut, erreur){
					console.log(resultat);
				}

			});
		}


		//		});
//
//
//
////		var ctx = document.getElementById("myChart");
//		var myChart = new Chart(ctx, {
//			type: 'line',
//			data: {
//				labels:  ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
//				datasets: [{
//					label: '# of Votes',
//					data: [30, 12, 19, 3, 5, 2, 3, 0, 0, 0, 0, 0],
//					backgroundColor: [
//						'#f00',
//						'#f00',
//						'#f00',
//						'#f00',
//						'rgba(153, 102, 255, 0.2)',
//						'rgba(255, 159, 64, 0.2)'
//					],
//					borderColor: [
//						'#f00',
//						'rgba(54, 162, 235, 1)',
//						'rgba(255, 206, 86, 1)',
//						'rgba(75, 192, 192, 1)',
//						'rgba(153, 102, 255, 1)',
//						'#00f'
//					],
//					borderWidth: 1
//				}]
//			},

//			options: {
//				scales: {
//					yAxes: [{
//						ticks: {
//							beginAtZero:true
//						}
//					}]
//				}
//			}
	</script>
</body>
</html>