<?php


	require_once 'functions.php';
	
	if(( isset( $_GET['action'] ) && $_GET['action'] != '' ))
	{
		$action = $_GET['action'];


		switch ($action)
		{
			/**
			 * admin actions
			 */
			case 'getAllAdmins':

				$sql    = 'select * from admin';
				$admins = getDatas($sql, []);

				echo json_encode($admins);

			break;

			case 'addAdmin':


				$errosAddAdmin = [];

				if(isset($_POST['nomAdmin']) && isset($_POST['prenomAdmin'])
						&& isset($_POST['telephoneAdmin']) && isset($_POST['sexeAdmin'])
						&& isset($_POST['loginAdmin']) && isset($_POST['passAdmin'])
						&& isset($_POST['confirmPassAdmin']))
				{
					$nomAdmin	          = $_POST['nomAdmin'];
					$prenomAdmin           = $_POST['prenomAdmin'];
					$telephoneAdmin        = $_POST['telephoneAdmin'];
					$sexeAdmin 	          = $_POST['sexeAdmin'];
					$loginAdmin            = $_POST['loginAdmin'];
					$passAdmin             = $_POST['passAdmin'];
					$confirmPassAdmin      = $_POST['confirmPassAdmin'];
					$tmp                   = $_FILES['imageAdmin']['tmp_name'];
					$nomImage              = $nomAdmin . '_' . $_FILES['imageAdmin']['name'];
					$errosAddAdmin         = [];

					// Validation format image :
					if( $_FILES['imageAdmin']['name'] != '' )
					{
						$info        = new SplFileInfo($_FILES['imageAdmin']['name']);
						$extension   = $info->getExtension();
						$extension   = strtoupper($extension);

						if( $extension == 'PNG' || $extension == 'JPG' || $extension == 'JPEG' || $extension == 'GIF')
						{
						}
						else
						{
							$errosAddAdmin['imageAdmin'] = 'Le format d\'image est incorrect !';
						}
					}
					else
					{
						$nomImage = 'no_image.jpg';
					}

					// validation email :
					if(isset($_POST['emailAdmin']) && $_POST['emailAdmin'] != '' )
					{
						$email = $_POST['emailAdmin'];

						if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
						{
							$errosAddAdmin['emailAdmin'] = "Le format d'email est incorrect !";
						}
					}
					else
					{
						$email = 'Null';
					}

					// validation inputs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $nomAdmin) )
					{
						$errosAddAdmin['nomAdmin'] = "Le nom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $prenomAdmin) )
					{
						$errosAddAdmin['prenomAdmin'] = "Le prenom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[0-9]{10}$#", $telephoneAdmin) )
					{
						$errosAddAdmin['telephoneAdmin'] = "Respecter le format de telephone !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $loginAdmin) )
					{
						$errosAddAdmin['loginAdmin'] = "Le login doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z0-9\_\-\àéè\s@]{6,100}$#", $passAdmin) )
					{
						$errosAddAdmin['passAdmin'] = "L emot de pass doit contenir au moins 6 caractères !";
					}
					if(  $passAdmin != $confirmPassAdmin )
					{
						$errosAddAdmin['confirmPassAdmin'] = "Le mot de pass est incorrect !";
					}

					$passAdmin = md5($passAdmin);

					$gestionnaires = getData("SELECT * FROM gestionaire WHERE login = ?", [ $loginAdmin ]);
					$admins = getData("SELECT * FROM admin WHERE login = ?", [ $loginAdmin ]);
					if( !empty($gestionnaires) || !empty($admins) )
					{
						$errosAddAdmin['loginAdmin'] = "Ce login deja utiliser, vuillez choisie un autre login !";
					}

					if(empty($errosAddAdmin))
					{
						$resUpload = UploadFile($tmp, '../../data/uploades/avatarAdmins/' . $nomImage);
						$sql    = "INSERT INTO admin (idAdmin, nom, prenom, sexe, tel, email, login, pass, active, image) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
						$params = [$nomAdmin, $prenomAdmin, $sexeAdmin, $telephoneAdmin, $email, $loginAdmin, $passAdmin, $nomImage];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosAddAdmin);
					}

				}
				else
				{
					echo "0";
				}

			break;

			// update info admin :
			case 'UpdateInfoAdmin':
			
				if( isset($_POST['idAdmin']) &&isset($_POST['nomAdmin']) && isset($_POST['prenomAdmin']) && isset($_POST['telephoneAdmin']) && isset($_POST['sexeAdmin']))
				{
					$idAdmin 		= $_POST['idAdmin'];
					$nomAdmin 		= $_POST['nomAdmin'];
					$prenomAdmin 	= $_POST['prenomAdmin'];
					$telephoneAdmin = $_POST['telephoneAdmin'];
					$sexeAdmin 		= $_POST['sexeAdmin'];
					$errosAddAdmin  = [];

					if(isset($_POST['emailAdmin']) && $_POST['emailAdmin'] != '' )
					{
						$email = $_POST['emailAdmin'];

						if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
						{
							$errosAddAdmin['emailAdmin'] = "Le format d'email est incorrect !";
						}
					}
					else
					{
						$email = 'Null';
					}

					// validation du champs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $nomAdmin) )
					{
						$errosAddAdmin['nomAdmin'] = "Le nom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $prenomAdmin) )
					{
						$errosAddAdmin['prenomAdmin'] = "Le prenom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[0-9]{10}$#", $telephoneAdmin) )
					{
						$errosAddAdmin['telephoneAdmin'] = "Respecter le format de telephone !";
					}

					if(empty($errosAddAdmin))
					{
						$sql    = "UPDATE admin SET nom = ?, prenom = ?, sexe = ?, tel = ?, email = ? WHERE idAdmin = ?";
						$params = [$nomAdmin, $prenomAdmin, $sexeAdmin, $telephoneAdmin, $email, $idAdmin];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosAddAdmin);
					}
				}

				break;

			// update login admin :
			case 'UpdateLoginAdmin':
			
				if( isset($_POST['loginAdmin']) && isset($_POST['idAdmin']))
				{
					$idAdmin 		= $_POST['idAdmin'];
					$loginAdmin		= $_POST['loginAdmin'];
					$a              = 0;
					if(isset( $_POST['active'] )) $a = 1;
					$errosAddAdmin  = [];

					// validation du champs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $loginAdmin) )
					{
						$errosAddAdmin['loginAdmin'] = "Le login doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}

					$gestionnaires = getData("SELECT * FROM gestionaire WHERE login = ?", [ $loginAdmin ]);
					$admins = getData("SELECT * FROM admin WHERE login = ? AND idAdmin != ?", [ $loginAdmin, $idAdmin ]);
					if( !empty($gestionnaires) || !empty($admins) )
					{
						$errosAddAdmin['loginAdmin'] = "Ce login deja utiliser, vuillez choisie un autre login !";
					}

					if(empty($errosAddAdmin))
					{
						$sql    = "UPDATE admin SET login = ?, active = ? WHERE idAdmin = ?";
						$params = [$loginAdmin, $a, $idAdmin];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosAddAdmin);
					}
				}

			break;

			// update password admin :
			case 'changePassAdmin':
			
				if( isset($_POST['idAdmin']) && isset($_POST['ancienPass']) && isset($_POST['nouveauPass']) && isset($_POST['confirmationPass']))
				{
					$idAdmin 		  = $_POST['idAdmin'];
					$ancienPass		  = $_POST['ancienPass'];
					$nouveauPass	  = $_POST['nouveauPass'];
					$confirmationPass = $_POST['confirmationPass'];

					$errosAddAdmin  = [];

					// validation du champs :
					if( !preg_match("#^[A-Za-z0-9à@\s\_\-]{6,20}$#", $ancienPass) )
					{
						$errosAddAdmin['ancienPass'] = "Le format de mot de passe est incorrect !";
					}
					if( !preg_match("#^[A-Za-z0-9à@\s\_\-]{6,20}$#", $nouveauPass) )
					{
						$errosAddAdmin['nouveauPass'] = "Le format de mot de passe est incorrect !";
					}
					if( !preg_match("#^[A-Za-z0-9à@\s\_\-]{6,20}$#", $confirmationPass) )
					{
						$errosAddAdmin['confirmationPass'] = "Le format de mot de passe est incorrect !";
					}

					$ancienPass		  = md5($ancienPass);
					$nouveauPass	  = md5($nouveauPass);
					$confirmationPass = md5($confirmationPass);

					$sql   = "SELECT * FROM admin WHERE idAdmin = ?";
					$admin = getData($sql, [$idAdmin]);

					if( $admin )
					{
						$ancpass = $admin['pass'];

						if( $ancpass != $ancienPass )
						{
							$errosAddAdmin['ancienPass'] = "Ce mot de pass n'est pas valide !";
						}
					}
					else
					{
						$errosAddAdmin['ancienPass'] = "Ce mot de pass n'est pas valide !";
					}

					if( $nouveauPass != $confirmationPass)
					{
						$errosAddAdmin['confirmationPass'] = "Le mot de pass est incorrect !";
					}

					if(empty($errosAddAdmin))
					{
						$sql    = "UPDATE admin SET pass = ? WHERE idAdmin = ?";
						$params = [$nouveauPass, $idAdmin];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosAddAdmin);
					}
				}

			break;

			// Update image admin :
			case 'UpdateImage':
			
				if( isset($_POST['idAdmin']))
				{
					$idAdmin  = $_POST['idAdmin'];
					$tmp      = $_FILES['image']['tmp_name'];
					$nomImage = $idAdmin . '_' . $_FILES['image']['name'];

					if( $_FILES['image']['name'] != '' )
					{
						$info        = new SplFileInfo($_FILES['image']['name']);
						$extension   = $info->getExtension();
						$extension   = strtoupper($extension);

						if( $extension == 'PNG' || $extension == 'JPG' || $extension == 'JPEG' || $extension == 'GIF')
						{
							$resUpload = UploadFile($tmp, '../../data/uploades/avatarAdmins/' .  $nomImage);

							if($resUpload == 1)
							{
								$sql = "UPDATE admin SET image = ? WHERE idAdmin = ?";
								$res = setData( $sql, [ $nomImage, $idAdmin ] );
								if( $res )
								{
									echo '1';
								}
								else
								{
									echo '0';
								}
							}
							else
							{
								echo '0';
							}
						}
						else
						{
							echo "-1";
						}
					}
					else
					{
						echo '2';
					}

				}
				else
				{
					echo '0';
				}

			break;

			/****************************** END ADMIN ACTIONS *************************/


			case 'getNbrDossierParMois':
				header('Content-Type: application/json');
				$nbrsDossiers = [];

				if( isset($_GET['annee']) && !empty($_GET['annee']) )
				{
					$annee = $_GET['annee'];

					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrDossier = getData("SELECT count(idDossier) AS nbrDossier FROM dossier WHERE YEAR( dateCreation ) = ? AND	MONTH( dateCreation ) = ?", [ $annee, $i ])['nbrDossier'];
						array_push($nbrsDossiers, $nbrDossier);
					}
				}
				else
				{
					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrDossier = getData("SELECT count(idDossier) AS nbrDossier FROM dossier WHERE YEAR( dateCreation ) = YEAR(CURDATE()) AND	MONTH( dateCreation ) = ?", [ $i ])['nbrDossier'];
						array_push($nbrsDossiers, $nbrDossier);
					}
				}

				echo json_encode($nbrsDossiers);

			break;

			case 'getNbrDossierParMois':
				header('Content-Type: application/json');
				$nbrsDossiers = [];

				if( isset($_GET['annee']) && !empty($_GET['annee']) )
				{
					$annee = $_GET['annee'];

					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrDossier = getData("SELECT count(idDossier) AS nbrDossier FROM dossier WHERE YEAR( dateCreation ) = ? AND MONTH( dateCreation ) = ?", [ $annee, $i ])['nbrDossier'];
						array_push($nbrsDossiers, $nbrDossier);
					}
				}
				else
				{
					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrDossier = getData("SELECT count(idDossier) AS nbrDossier FROM dossier WHERE YEAR( dateCreation ) = YEAR(CURDATE()) AND	MONTH( dateCreation ) = ?", [ $i ])['nbrDossier'];
						array_push($nbrsDossiers, $nbrDossier);
					}
				}

				echo json_encode($nbrsDossiers);

			break;

			case 'getNbrConsultationsParMois':

				header('Content-Type: application/json');
				$nbrConsultations = [];

				if( isset($_GET['annee']) && !empty($_GET['annee']) )
				{
					$annee = $_GET['annee'];

					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrConsultation = getData("SELECT count(idConsultation) AS nbrConsultation FROM consultation WHERE YEAR( dateDebut ) = ? AND MONTH( dateDebut ) = ?", [ $annee, $i ])['nbrConsultation'];
						array_push($nbrConsultations, $nbrConsultation);
					}
				}
				else
				{
					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrConsultation = getData("SELECT count(idConsultation) AS nbrConsultation FROM consultation WHERE YEAR( dateDebut ) = YEAR(CURDATE()) AND	MONTH( dateDebut ) = ?", [ $i ])['nbrConsultation'];
						array_push($nbrConsultations, $nbrConsultation);
					}
				}

				echo json_encode($nbrConsultations);

			break;



			case 'getNbrRdvParMois':

				header('Content-Type: application/json');
				$nbrRdvs = [];
				$nbrRdvsValid = [];
				$nbrRdvsAnulle = [];

				if( isset($_GET['annee']) && !empty($_GET['annee']) )
				{
					$annee = $_GET['annee'];

					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrR = getData("SELECT count(*) AS nbrRdvValid FROM rdv WHERE YEAR( dateRdv ) = ? AND MONTH( dateRdv ) = ? AND Etat = 'v'", [ $annee, $i ])['nbrRdvValid'];
						array_push($nbrRdvsValid, $nbrR);

						$nbrR = getData("SELECT count(*) AS nbrRdvAnnule FROM rdv WHERE YEAR( dateRdv ) = ? AND MONTH( dateRdv ) = ? AND Etat = 'd'", [ $annee, $i ])['nbrRdvAnnule'];
						array_push($nbrRdvsAnulle, $nbrR);
					}
				}
				else
				{
					for	( $i = 1; $i < 13; $i++ )
					{
						$nbrR = getData("SELECT count(*) AS nbrRdvValid FROM rdv WHERE YEAR( dateRdv ) = YEAR(CURDATE()) AND MONTH( dateRdv ) = ? AND Etat = 'v'", [ $i ])['nbrRdvValid'];
						array_push($nbrRdvsValid, $nbrR);

						$nbrR = getData("SELECT count(*) AS nbrRdvAnnule FROM rdv WHERE YEAR( dateRdv ) = YEAR(CURDATE()) AND MONTH( dateRdv ) = ? AND Etat = 'd'", [ $i ])['nbrRdvAnnule'];
						array_push($nbrRdvsAnulle, $nbrR);
					}
				}

				$nbrRdvs = [ 'rdvValid' => $nbrRdvsValid, 'rdvAnnule' => $nbrRdvsAnulle ];
				echo json_encode($nbrRdvs);

			break;

			case 'getEtatsParMois':

				header('Content-Type: application/json');

				$nbrConsultations = 0;
				$nbrDossiers      = 0;
				$nbrRdvValide     = 0;
				$chiffreAffaire   = 0;

				if( isset( $_GET['mois'] ) && !empty( $_GET['mois'] ) && is_numeric( $_GET['mois'] ) )
				{
					$mois = $_GET['mois'];

					if( isset($_GET['annee']) && !empty($_GET['annee']) )
					{
						$annee = $_GET['annee'];

						$nbrConsultations = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE YEAR( dateDebut ) = ? AND	MONTH( dateDebut ) = ?", [ $annee, $mois ])['nbrConsultations'];
						$nbrDossiers      = getData("SELECT COUNT(*) AS nbrDossiers FROM dossier WHERE YEAR( dateCreation ) = ? AND	MONTH( dateCreation ) = ?", [ $annee, $mois ])['nbrDossiers'];
						$nbrRdvValide     = getData("SELECT COUNT(*) AS nbrRdvValide FROM rdv WHERE YEAR( rdv.dateRdv ) = ? AND rdv.Etat = 'v' AND	MONTH( rdv.dateRdv ) = ?", [ $annee, $mois ])['nbrRdvValide'];
						$chiffreAffaire   = getData("SELECT SUM(paiement.montantPaye) AS chiffreAffaire FROM paiement WHERE YEAR( paiement.datePaiement ) = ? AND	MONTH( paiement.datePaiement ) = ?", [ $annee, $mois ])['chiffreAffaire'];
					}
					else
					{
						$nbrConsultations = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE YEAR( dateDebut ) = YEAR(CURDATE())", [])['nbrConsultations'];
						$nbrDossiers      = getData("SELECT COUNT(*) AS nbrDossiers FROM dossier WHERE YEAR( dateCreation ) = YEAR(CURDATE())", [])['nbrDossiers'];
						$nbrRdvValide     = getData("SELECT COUNT(*) AS nbrRdvValide FROM rdv WHERE YEAR( rdv.dateRdv ) = YEAR(CURDATE()) AND rdv.Etat = 'v'", [])['nbrRdvValide'];
						$chiffreAffaire   = getData("SELECT SUM(paiement.montantPaye) AS chiffreAffaire FROM paiement WHERE YEAR( paiement.datePaiement ) = YEAR(CURDATE())", [])['chiffreAffaire'];
					}
				}
				else
				{
					if( isset($_GET['annee']) && !empty($_GET['annee']) )
					{
						$annee = $_GET['annee'];

						$nbrConsultations = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE YEAR( dateDebut ) = ?", [ $annee ])['nbrConsultations'];
						$nbrDossiers      = getData("SELECT COUNT(*) AS nbrDossiers FROM dossier WHERE YEAR( dateCreation ) = ?", [ $annee ])['nbrDossiers'];
						$nbrRdvValide     = getData("SELECT COUNT(*) AS nbrRdvValide FROM rdv WHERE YEAR( rdv.dateRdv ) = ? AND rdv.Etat = 'v'", [ $annee ])['nbrRdvValide'];
						$chiffreAffaire   = getData("SELECT SUM(paiement.montantPaye) AS chiffreAffaire FROM paiement WHERE YEAR( paiement.datePaiement ) = ?", [ $annee ])['chiffreAffaire'];
					}
					else
					{
						$nbrConsultations = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation WHERE YEAR( dateDebut ) = YEAR(CURDATE())", [])['nbrConsultations'];
						$nbrDossiers      = getData("SELECT COUNT(*) AS nbrDossiers FROM dossier WHERE YEAR( dateCreation ) = YEAR(CURDATE())", [])['nbrDossiers'];
						$nbrRdvValide     = getData("SELECT COUNT(*) AS nbrRdvValide FROM rdv WHERE YEAR( rdv.dateRdv ) = YEAR(CURDATE()) AND rdv.Etat = 'v'", [])['nbrRdvValide'];
						$chiffreAffaire   = getData("SELECT SUM(paiement.montantPaye) AS chiffreAffaire FROM paiement WHERE YEAR( paiement.datePaiement ) = YEAR(CURDATE())", [])['chiffreAffaire'];
					}

				}
				$chiffreAffaire = ( $chiffreAffaire != null ) ? $chiffreAffaire : "0";

				$data = [ 'nbrConsultations' => $nbrConsultations, 'nbrDossiers' => $nbrDossiers, 'nbrRdvValide' => $nbrRdvValide, 'chiffreAffaire' => $chiffreAffaire];

				echo json_encode($data);

			break;

			case 'getChiffreAffaireParMois':

				header('Content-Type: application/json');
				$chiffreAffaireParMois = [];

				if( isset($_GET['annee']) && !empty($_GET['annee']) )
				{
					$annee = $_GET['annee'];

					for	( $i = 1; $i < 13; $i++ )
					{
						$chiffreAffaire = getData("SELECT SUM(paiement.montantPaye) AS chiffreAffaire FROM paiement WHERE YEAR( paiement.datePaiement ) = ? AND MONTH( paiement.datePaiement ) = ? ", [ $annee, $i ])['chiffreAffaire'];
						$chiffreAffaire = ( $chiffreAffaire != null ) ? $chiffreAffaire : 0;
						array_push($chiffreAffaireParMois, $chiffreAffaire);
					}
				}
				else
				{
					for	( $i = 1; $i < 13; $i++ )
					{
						$chiffreAffaire = getData("SELECT SUM(paiement.montantPaye) AS chiffreAffaire FROM paiement WHERE YEAR( paiement.datePaiement ) =  YEAR(CURDATE()) AND MONTH( paiement.datePaiement ) = ? ", [ $i ])['chiffreAffaire'];
						$chiffreAffaire = ( $chiffreAffaire != null ) ? $chiffreAffaire : 0;
						array_push($chiffreAffaireParMois, $chiffreAffaire);
					}
				}

				echo json_encode($chiffreAffaireParMois);

			break;

			case 'getPorcentagePatientParSexe':

				header('Content-Type: application/json');

				if( isset( $_GET['mois'] ) && !empty($_GET['mois']) )
				{
					$mois = $_GET['mois'];

					if( isset($_GET['annee']) && !empty($_GET['annee']) )
					{
						$annee = $_GET['annee'];

						$porcentageHomme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'H' AND YEAR( c.dateDebut ) = ? AND MONTH( c.dateDebut ) = ?", [ $annee, $mois ])['nbrPt'];
						$porcentageFemme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'F' AND YEAR( c.dateDebut ) = ? AND MONTH( c.dateDebut ) = ?", [ $annee, $mois ])['nbrPt'];
					}
					else
					{
						$porcentageHomme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'H' AND YEAR( c.dateDebut ) = YEAR(CURDATE()) AND MONTH( c.dateDebut ) = ?", [ $mois ])['nbrPt'];
						$porcentageFemme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'F' AND YEAR( c.dateDebut ) = YEAR(CURDATE()) AND MONTH( c.dateDebut ) = ?", [ $mois ])['nbrPt'];
					}
				}
				else
				{
					if( isset($_GET['annee']) && !empty($_GET['annee']) )
					{
						$annee = $_GET['annee'];

						$porcentageHomme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'H' AND YEAR( c.dateDebut ) = ?", [ $annee ])['nbrPt'];
						$porcentageFemme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'F' AND YEAR( c.dateDebut ) = ?", [ $annee ])['nbrPt'];
					}
					else
					{
						$porcentageHomme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'H' AND YEAR( c.dateDebut ) = YEAR(CURDATE())", [])['nbrPt'];
						$porcentageFemme = getData("SELECT COUNT(*) AS nbrPt FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND p.sexe = 'F' AND YEAR( c.dateDebut ) = YEAR(CURDATE())", [])['nbrPt'];
					}
				}

				$porcentageHomme = ( empty($porcentageHomme) ) ? 0 : $porcentageHomme ;
				$porcentageFemme = ( empty($porcentageFemme) ) ? 0 : $porcentageFemme ;

				$data = ['nbrHomme' => $porcentageHomme, 'nbrFemme' => $porcentageFemme];

				echo json_encode($data);

			break;

			case 'getPorcentagePatientParAge':

				header('Content-Type: application/json');

				$nbrNourisson = 0;
				$nbrEnfants   = 0;
				$nbrAdultes   = 0;
				$agesPatients = [];

				if( isset( $_GET['mois'] ) && !empty($_GET['mois']) )
				{
					$mois = $_GET['mois'];

					if (isset($_GET['annee']) && !empty($_GET['annee']))
					{
						$annee = $_GET['annee'];

						$agesPatients = getDatas("SELECT p.nom, TIMESTAMPDIFF(YEAR, p.dateNaissance, c.dateDebut) AS age FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND YEAR( c.dateDebut ) = ? AND MONTH( c.dateDebut ) = ?", [ $annee, $mois ]);
					}
					else
					{
						$agesPatients = getDatas("SELECT p.nom, TIMESTAMPDIFF(YEAR, p.dateNaissance, c.dateDebut) AS age, p.dateNaissance FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND YEAR( c.dateDebut ) = YEAR(CURDATE()) AND MONTH( c.dateDebut ) = ?", [ $mois ]);
					}

					for ($i = 0; $i < count($agesPatients); $i++)
					{
						$age = $agesPatients[$i]['age'];
						if ($age >= 0 && $age <= 2)
						{
							$nbrNourisson++;
						} else if ($age > 2 && $age <= 15)
						{
							$nbrEnfants++;
						}
						else
						{
							$nbrAdultes++;
						}
					}
				}
				else
				{
					if (isset($_GET['annee']) && !empty($_GET['annee'])) {
						$annee = $_GET['annee'];

						$agesPatients = getDatas("SELECT p.nom, TIMESTAMPDIFF(YEAR, p.dateNaissance, c.dateDebut) AS age FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND YEAR( c.dateDebut ) = ?", [$annee]);
					} else {
						$agesPatients = getDatas("SELECT p.nom, TIMESTAMPDIFF(YEAR, p.dateNaissance, c.dateDebut) AS age, p.dateNaissance FROM patient p, dossier d, consultation c WHERE p.idPatient = d.idPatient AND d.idDossier = c.idDossier AND YEAR( c.dateDebut ) = YEAR(CURDATE())", []);
					}

					for ($i = 0; $i < count($agesPatients); $i++) {
						$age = $agesPatients[$i]['age'];
						if ($age >= 0 && $age <= 2) {
							$nbrNourisson++;
						} else if ($age > 2 && $age <= 15) {
							$nbrEnfants++;
						} else {
							$nbrAdultes++;
						}
					}
				}

				$data = ['nbrNourisson' => $nbrNourisson, 'nbrEnfants' => $nbrEnfants,'nbrAdultes' => $nbrAdultes];
				echo json_encode( $data );

			break;


			// Delete Admin :
			case 'deleteAdmin':
			
				if(isset($_GET['idAdmin']))
				{
					$idAdmin = $_GET['idAdmin'];
					$sql     = "DELETE FROM admin WHERE idAdmin = ?";
					$result  = setData($sql, [ $idAdmin ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;


			//========================================== Patient Methodes ==============================================//

			// Add Patient :
			case 'addPatient':


				$errosAddPatient = [];

				if( isset($_POST['cinPatient']) && isset($_POST['nomPatient']) && isset($_POST['prenomPatient']) && isset($_POST['telephonePatient']) && isset($_POST['sexePatient']) && isset($_POST['dateNaissancePatient']) && isset($_POST['adressePatient']))
				{

					$cinPatient 	      = $_POST['cinPatient'];
					$nomPatient	          = $_POST['nomPatient'];
					$prenomPatient 	      = $_POST['prenomPatient'];
					$telephonePatient     = $_POST['telephonePatient'];
					$sexePatient 	      = $_POST['sexePatient'];
					$adressePatient       = $_POST['adressePatient'];
					$dateNaissancePatient = $_POST['dateNaissancePatient'];
					$mutuel               = $_POST['mutuel'];
					$errosAddPatient   = [];

					if(isset($_POST['emailPatient']) && $_POST['emailPatient'] != '' )
					{
						$email = $_POST['emailPatient'];

						if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
						{
							$errosAddPatient['emailPatient'] = "Le format d'email est incorrect !";
						}
					}
					else
					{
						$email = 'Null';
					}

					// validation du champs :
					if( !preg_match("#^[a-zA-Z]{1,2}[a-zA-Z0-9\_]{7,10}$#", $cinPatient) )
					{
						$errosAddPatient['cinPatient'] = "Le format de numero de carte nationnal est incorrect !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $nomPatient) )
					{
						$errosAddPatient['nomPatient'] = "Le nom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $prenomPatient) )
					{
						$errosAddPatient['prenomPatient'] = "Le prenom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[0-9]{10}$#", $telephonePatient) )
					{
						$errosAddPatient['telephonePatient'] = "Respecter le format de telephone !";
					}
					if( !preg_match("#^[a-zA-Z0-9\_\-\àéè\s]{10,200}$#", $adressePatient) )
					{
						$errosAddPatient['adressePatient'] = "L'adresse doit contenir au moins 10 caractères !";
					}
					if( !preg_match("#^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$#",$dateNaissancePatient) )
					{
						$errosAddPatient['dateNaissancePatient'] = "Le format de date naissance est incorrect ( aaaa-mm-jj ) !";
					}

					if(empty($errosAddPatient))
					{
						$sql    = "INSERT INTO patient (idPatient, cin, nom, prenom, sexe, tel, email, dateNaissance, adresse, mutuel, active) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
						$params = [$cinPatient, $nomPatient, $prenomPatient, $sexePatient, $telephonePatient, $email, $dateNaissancePatient, $adressePatient, $mutuel];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0 11";
						}

					}
					else
					{
						echo json_encode($errosAddPatient);
					}

				}
				else
				{
					echo "0";
				}

			break;

			case 'getConsultationsDossier':
				if( isset($_GET['idDossier']) && !empty($_GET['idDossier']) )
				{
					$idDossier = $_GET['idDossier'];

					$sql ="SELECT C.idConsultation, C.dateDebut, t.libelle, CONCAT(SUBSTRING(C.remarquesConsultation,1,10), '...') AS remarquesConsultation , C.montantNetConsultation   FROM consultation C, dossier D, typerdv t WHERE C.idDossier = D.idDossier AND  C.typeConsultation = t.idType AND D.idDossier = ?";
					$consltations = getDatas($sql, [ $idDossier ]);

					echo json_encode($consltations);
				}

			break;

			case 'getConsultationsToday':

				$sql ="SELECT P.idPatient AS idPatient, CONCAT('[', P.cin,'] - ', p.prenom, ' ', p.nom, ' / ', D.titreDossier ) AS patient, D.idDossier, C.idConsultation, C.dateDebut AS dateConsultation, C.dateDebut, t.libelle, CONCAT(SUBSTRING(C.remarquesConsultation,1,10), '...') AS remarquesConsultation , C.montantNetConsultation
  						FROM consultation C, dossier D, typerdv t, patient P
  						WHERE C.idDossier = D.idDossier
  						AND  P.idPatient = D.idPatient
  						AND  C.typeConsultation = t.idType
  						AND DATE_FORMAT(C.dateDebut, '%Y-%m-%d') = CURDATE()";
				$consltations = getDatas($sql, []);

				echo empty($consltations) ? json_encode([]) : json_encode($consltations);

			break;

			//Update Patient :
			case 'UpdateInfoPrsonnelPatient':


				if( isset($_POST['idPatient']) && isset($_POST['cinPatient']) && isset($_POST['nomPatient']) && isset($_POST['prenomPatient']) && isset($_POST['telephonePatient']) && isset($_POST['sexePatient']) && isset($_POST['dateNaissancePatient']) && isset($_POST['adressePatient']))
				{
					$idPatient 		      = $_POST['idPatient'];
					$cinPatient 	      = $_POST['cinPatient'];
					$nomPatient	          = $_POST['nomPatient'];
					$prenomPatient 	      = $_POST['prenomPatient'];
					$telephonePatient     = $_POST['telephonePatient'];
					$sexePatient 	      = $_POST['sexePatient'];
					$adressePatient       = $_POST['adressePatient'];
					$dateNaissancePatient = $_POST['dateNaissancePatient'];
					$mutuel 	            = '';
					$errosUpdatePatient     = [];
					$successUpdatePatient   = [];

					$existe = getData("SELECT * FROM patient WHERE cin = ? AND idPatient != ?", [$cinPatient, $idPatient]);

					if(!empty($existe))
					{
						echo -1;
						die();
					}


					if(isset($_POST['mutuel']) && $_POST['mutuel'] != '' )
					{
						$mutuel = $_POST['mutuel'];
					}

					if(isset($_POST['emailPatient']) && $_POST['emailPatient'] != '' )
					{
						$email = $_POST['emailPatient'];

						if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
						{
							$errosUpdatePatient['emailPatient'] = "Le format d'email est incorrect !";
						}
					}
					else
					{
						$email = '';
					}

					$active = 0;

					if( isset($_POST['active']) && $_POST['active'] )
					{
						$active = 1;
					}

					// validation du champs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $nomPatient) )
					{
						$errosUpdatePatient['nomPatient'] = "Le nom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $prenomPatient) )
					{
						$errosUpdatePatient['prenomPatient'] = "Le prenom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[0-9]{10}$#", $telephonePatient) )
					{
						$errosUpdatePatient['telephonePatient'] = "Respecter le format de telephone !";
					}
					if( !preg_match("#^[a-zA-Z]{1,2}[a-zA-Z0-9\_]{7,10}$#", $cinPatient) )
					{
						$errosUpdatePatient['cinPatient'] = "Le format de numero de carte nationnal est incorrect !";
					}
					if( (strlen($adressePatient) < 10) || (strlen($adressePatient) > 200) )
					{
						$errosUpdatePatient['adressePatient'] = "L'adresse doit contenir au moins 10 caractères !";
					}

					if(empty($errosUpdatePatient))
					{
						$sql    = "UPDATE patient SET cin = ?, nom = ?, prenom = ?, sexe = ?, tel = ?, email = ?, dateNaissance = ?, adresse = ?, active = ?, mutuel = ? WHERE idPatient = ?";
						$params = [$cinPatient, $nomPatient, $prenomPatient, $sexePatient, $telephonePatient, $email, $dateNaissancePatient, $adressePatient, $active, $mutuel, $idPatient ];

						if(setData($sql, $params))
						{
							$successUpdatePatient['nomCmpltPatient'] = $prenomPatient . ' ' . $nomPatient ;
							echo json_encode($successUpdatePatient);
						}
						else
						{
							echo "0 11";
						}

					}
					else
					{
						echo json_encode($errosUpdatePatient);
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'updateInfoMedicalPatient':


				if( isset($_POST['idPatient']) && !empty($_POST['idPatient']) )
				{
					$idPatient = $_POST['idPatient'];

					$rhiniteAllergique = (isset($_POST['rhiniteAllergique']) && $_POST['rhiniteAllergique'] != '' ) ? $_POST['rhiniteAllergique'] : '';

					$cardiopathie = (isset($_POST['cardiopathie']) && $_POST['cardiopathie'] != '' ) ? $_POST['cardiopathie'] : '';

					$autresFamiliaux = (isset($_POST['autresFamiliaux']) && $_POST['autresFamiliaux'] != '' ) ? $_POST['autresFamiliaux'] : '';

					$hta = (isset($_POST['hta']) && $_POST['hta'] != '' ) ?  $_POST['hta'] : '';

					$allergies = (isset($_POST['allergies']) && $_POST['allergies'] != '' ) ?  $_POST['allergies'] : '';

					$autresMedicaux = (isset($_POST['autresMedicaux']) && $_POST['autresMedicaux'] != '' ) ? $_POST['autresMedicaux'] : '';

					$tabac = (isset($_POST['tabac']) && $_POST['tabac'] != '' ) ?  $_POST['tabac'] : '';

					$autresTabac = (isset($_POST['autresTabac']) && $_POST['autresTabac'] != '' ) ?  $_POST['autresTabac'] : '';

					$appendicectomie = (isset($_POST['appendicectomie']) && $_POST['appendicectomie'] != '' ) ? $_POST['appendicectomie'] : '';

					$cholecystectomie = (isset($_POST['cholecystectomie']) && $_POST['cholecystectomie'] != '' ) ? $_POST['cholecystectomie'] : '';

					$autresChirurgicauxComplication = (isset($_POST['autresChirurgicauxComplication']) && $_POST['autresChirurgicauxComplication'] != '' ) ?  $_POST['autresChirurgicauxComplication'] : '';
					//
					$sql    = "UPDATE patient SET rhiniteAllergique = ?, cardiopathie = ?, autresFamiliaux = ?, hta = ?, allergies = ?, autresMedicaux = ?, tabac = ?, autreshabitudeAlcooloTabagique = ?, appendicectomie = ?, cholecystectomie = ?, autresChirurgicauxComplication = ? WHERE idPatient = ?";
					$params = [ $rhiniteAllergique, $cardiopathie, $autresFamiliaux, $hta, $allergies, $autresMedicaux, $tabac, $autresTabac, $appendicectomie, $cholecystectomie, $autresChirurgicauxComplication, $idPatient ];

					if(setData($sql, $params))
					{
						echo '1';
					}
					else
					{
						echo "0";
					}
				}
				else
				{
					echo '0';
				}

			break;

			// Delete Patient :
			case 'deletePatient':


				if(isset($_GET['idPatient']))
				{
					$idPatient = $_GET['idPatient'];
					$sql     = "DELETE FROM patient WHERE idPatient = ?";
					$result  = setData($sql, [ $idPatient ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'deleteConsultation':


				if(isset($_GET['idConsultation']))
				{
					$idConsultation = $_GET['idConsultation'];
					$sql     = "DELETE FROM consultation WHERE idConsultation = ?";
					$result  = setData($sql, [ $idConsultation ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			//====================================== Gestionnaire Methodes ============================================//

			// Add Gestionnaire :
			case 'addGestionnaire':


				$errosAddGestionnaire = [];

				if(isset($_POST['nomGestionnaire']) && isset($_POST['prenomGestionnaire'])
						&& isset($_POST['telephoneGestionnaire']) && isset($_POST['sexeGestionnaire'])
						&& isset($_POST['loginGestionnaire']) && isset($_POST['passGestionnaire'])
						&& isset($_POST['confirmPassGestionnaire']) && isset($_POST['adresseGestionnaire']))
				{
					$nomGestionnaire	          = $_POST['nomGestionnaire'];
					$prenomGestionnaire           = $_POST['prenomGestionnaire'];
					$telephoneGestionnaire        = $_POST['telephoneGestionnaire'];
					$sexeGestionnaire 	          = $_POST['sexeGestionnaire'];
					$adresseGestionnaire          = $_POST['adresseGestionnaire'];
					$loginGestionnaire            = $_POST['loginGestionnaire'];
					$passGestionnaire             = $_POST['passGestionnaire'];
					$confirmPassGestionnaire      = $_POST['confirmPassGestionnaire'];
					$tmp                          = $_FILES['imageGestionnaire']['tmp_name'];
					$nomImage                     = $nomGestionnaire . '_' . $_FILES['imageGestionnaire']['name'];
					$errosAddGestionnaire         = [];

					// Validation format image :
 					if( $_FILES['imageGestionnaire']['name'] != '' )
					{
						$info        = new SplFileInfo($_FILES['imageGestionnaire']['name']);
						$extension   = $info->getExtension();
						$extension   = strtoupper($extension);

						if( $extension == 'PNG' || $extension == 'JPG' || $extension == 'JPEG' || $extension == 'GIF')
						{
							$errosAddPatient['imageGestionnaire'] = '';
						}
						else
						{
							$errosAddGestionnaire['imageGestionnaire'] = 'Le format d\'image est incorrect !';
						}
					}
					else
					{
						$nomImage = 'no_image.jpg';
					}

					// validation email :
					if(isset($_POST['emailGestionnaire']) && $_POST['emailGestionnaire'] != '' )
					{
						$email = $_POST['emailGestionnaire'];

						if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
						{
							$errosAddGestionnaire['emailGestionnaire'] = "Le format d'email est incorrect !";
						}
					}
					else
					{
						$email = 'Null';
					}

					// validation inputs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $nomGestionnaire) )
					{
						$errosAddGestionnaire['nomGestionnaire'] = "Le nom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $prenomGestionnaire) )
					{
						$errosAddGestionnaire['prenomGestionnaire'] = "Le prenom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[0-9]{10}$#", $telephoneGestionnaire) )
					{
						$errosAddGestionnaire['telephoneGestionnaire'] = "Respecter le format de telephone !";
					}
					if( !preg_match("#^[a-zA-Z0-9\_\-\àéè\s]{10,200}$#", $adresseGestionnaire) )
					{
						$errosAddGestionnaire['adresseGestionnaire'] = "L'adresse doit contenir au moins 10 caractères !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $loginGestionnaire) )
					{
						$errosAddGestionnaire['loginGestionnaire'] = "Le login doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z0-9\_\-\àéè\s@]{6,100}$#", $passGestionnaire) )
					{
						$errosAddGestionnaire['passGestionnaire'] = "Lemot de pass doit contenir au moins 6 caractères !";
					}
					if(  $passGestionnaire != $confirmPassGestionnaire )
					{
						$errosAddGestionnaire['confirmPassGestionnaire'] = "Le mot de pass est incorrect !";
					}

					$passGestionnaire = md5($passGestionnaire );

					$gestionnaires = getData("SELECT * FROM gestionaire WHERE login = ?", [ $loginGestionnaire ]);
					$admins = getData("SELECT * FROM admin WHERE login = ?", [ $loginGestionnaire ]);
					if( !empty($gestionnaires) || !empty($admins) )
					{
						$errosAddGestionnaire['loginGestionnaire'] = "Ce login deja utiliser, vuillez choisie un autre login !";
					}

					if(empty($errosAddGestionnaire))
					{
						$sql       = "INSERT INTO gestionaire(idGestionaire, nom, prenom, tel, email, adresse, image, login, pass, sexe, active)
													  VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1);";
						$params    = [$nomGestionnaire, $prenomGestionnaire, $telephoneGestionnaire, $email, $adresseGestionnaire, $nomImage, $loginGestionnaire, $passGestionnaire, $sexeGestionnaire];

						if(setData($sql, $params))
						{
							$resUpload = UploadFile($tmp, '../../data/uploades/avatarGestionnaires/' . $nomImage);
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosAddGestionnaire);
					}

				}
				else
				{
					echo "0 inputes";
				}

			break;

			// Delete gestionnaire :
			case 'deleteGestionnaire':


				if(isset($_GET['idGestionnaire']))
				{
					$idGestionnaire = $_GET['idGestionnaire'];
					$sql            = "DELETE FROM gestionaire WHERE idGestionaire = ?";
					$result         = setData($sql, [ $idGestionnaire ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			// update info admin :
			case 'updateInfoGestionnaire':
			
				if( isset($_POST['idGestionnaire']) && isset($_POST['nomGestionnaire']) && isset($_POST['prenomGestionnaire']) && isset($_POST['telephoneGestionnaire']) && isset($_POST['sexeGestionnaire']) && isset($_POST['adresseGestionnaire']))
				{
					$idGestionnaire 		       = $_POST['idGestionnaire'];
					$nomGestionnaire 		       = $_POST['nomGestionnaire'];
					$prenomGestionnaire 	       = $_POST['prenomGestionnaire'];
					$telephoneGestionnaire         = $_POST['telephoneGestionnaire'];
					$sexeGestionnaire 		       = $_POST['sexeGestionnaire'];
					$adresseGestionnaire	       = $_POST['adresseGestionnaire'];
					$errosUpdateInfoGestionnaire   = [];

					if(isset($_POST['emailGestionnaire']) && $_POST['emailGestionnaire'] != '' )
					{
						$email = $_POST['emailGestionnaire'];

						if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
						{
							$errosUpdateInfoGestionnaire['emailGestionnaire'] = "Le format d'email est incorrect !";
						}
					}
					else
					{
						$email = 'Null';
					}

					// validation du champs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $nomGestionnaire) )
					{
						$errosUpdateInfoGestionnaire['nomGestionnaire'] = "Le nom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $prenomGestionnaire) )
					{
						$errosUpdateInfoGestionnaire['prenomGestionnaire'] = "Le prenom doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}
					if( !preg_match("#^[0-9]{10}$#", $telephoneGestionnaire) )
					{
						$errosUpdateInfoGestionnaire['telephoneGestionnaire'] = "Respecter le format de telephone !";
					}
					if( !preg_match("#^[a-zA-Z0-9\_\-\àéè\s]{10,200}$#", $adresseGestionnaire) )
					{
						$errosUpdateInfoGestionnaire['adresseGestionnaire'] = "L'adresse doit contenir au moins 10 caractères !";
					}

					if(empty($errosUpdateInfoGestionnaire))
					{
						$sql    = "UPDATE gestionaire SET nom = ?, prenom = ?, sexe = ?, tel = ?, email = ?, adresse = ? WHERE idGestionaire = ?";
						$params = [$nomGestionnaire, $prenomGestionnaire, $sexeGestionnaire, $telephoneGestionnaire, $email, $adresseGestionnaire, $idGestionnaire];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}
					}
					else
					{
						echo json_encode($errosUpdateInfoGestionnaire);
					}
				}

			break;

			// update password gestionnaire :
			case 'changePassGestionnaire':
			
				if( isset($_POST['idGestionnaire']) && isset($_POST['ancienPass']) && isset($_POST['nouveauPass']) && isset($_POST['confirmationPass']))
				{
					$idGestionnaire               = $_POST['idGestionnaire'];
					$ancienPass		              = $_POST['ancienPass'];
					$nouveauPass	              = $_POST['nouveauPass'];
					$confirmationPass             = $_POST['confirmationPass'];
					$errosChangePassGestionnaire  = [];

					// validation du champs :
					if( !preg_match("#^[A-Za-z0-9à@\s\_\-]{6,20}$#", $ancienPass) )
					{
						$errosChangePassGestionnaire['ancienPass'] = "Le format de mot de passe est incorrect !";
					}
					if( !preg_match("#^[A-Za-z0-9à@\s\_\-]{6,20}$#", $nouveauPass) )
					{
						$errosChangePassGestionnaire['nouveauPass'] = "Le format de mot de passe est incorrect !";
					}
					if( !preg_match("#^[A-Za-z0-9à@\s\_\-]{6,20}$#", $confirmationPass) )
					{
						$errosChangePassGestionnaire['confirmationPass'] = "Le format de mot de passe est incorrect !";
					}
					$ancienPass		   = md5($ancienPass);
					$nouveauPass	   = md5($nouveauPass);
					$confirmationPass  = md5($confirmationPass);

					$sql          = "SELECT * FROM gestionaire WHERE idGestionaire = ?";
					$gestionnaire = getData($sql, [$idGestionnaire]);

					if( $gestionnaire )
					{
						$ancpass = $gestionnaire['pass'];

						if( $ancpass != $ancienPass )
						{
							$errosChangePassGestionnaire['ancienPass'] = "Ce mot de pass n'est pas valide !";
						}
					}
					else
					{
						$errosChangePassGestionnaire['ancienPass'] = "Ce mot de pass n'est pas valide !";
					}

					if( $nouveauPass != $confirmationPass)
					{
						$errosChangePassGestionnaire['confirmationPass'] = "Le mot de pass est incorrect !";
					}

					if(empty($errosChangePassGestionnaire))
					{
						$sql    = "UPDATE gestionaire SET pass = ? WHERE idGestionaire = ?";
						$params = [$nouveauPass, $idGestionnaire];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosChangePassGestionnaire);
					}
				}

			break;

			// update login gestionnaire :
			case 'updateLoginGestionnaire':
			
				if( isset($_POST['loginGestionnaire']) && isset($_POST['idGestionnaire']))
				{
					$idGestionnaire 		= $_POST['idGestionnaire'];
					$loginGestionnaire		= $_POST['loginGestionnaire'];
					$a              = 0;
					if(isset( $_POST['active'] )) $a = 1;
					$errosUpdateLoginGestionnaire  = [];

					// validation du champs :
					if( !preg_match("#^[a-zA-Z]{1}[a-zA-Z0-9\_]{3,15}$#", $loginGestionnaire) )
					{
						$errosUpdateLoginGestionnaire['loginGestionnaire'] = "Le login doit contenir au moins 4 caractères et 15 caractères au maximun !";
					}

					$gestionnaires = getData("SELECT * FROM gestionaire WHERE login = ?", [ $loginGestionnaire ]);
					$admins = getData("SELECT * FROM admin WHERE login = ? AND idGestionaire != ?", [ $loginGestionnaire, $idGestionnaire ]);
					if( !empty($gestionnaires) || !empty($admins) )
					{
						$errosUpdateLoginGestionnaire['loginGestionnaire'] = "Ce login deja utiliser, vuillez choisie un autre login !";
					}

					if(empty($errosUpdateLoginGestionnaire))
					{
						$sql    = "UPDATE gestionaire SET login = ?, active = ? WHERE idGestionaire = ?";
						$params = [$loginGestionnaire, $a, $idGestionnaire];

						if(setData($sql, $params))
						{
							echo "1";
						}
						else
						{
							echo "0";
						}

					}
					else
					{
						echo json_encode($errosUpdateLoginGestionnaire);
					}
				}

			break;

			// Update image gestionnaire :
			case 'UpdateImageGestionnaire':

			

				if( isset($_POST['idGestionnaire']))
				{
					$gestionnaire    = getData('SELECT * FROM gestionaire WHERE idGestionaire = ?', [$_POST['idGestionnaire']]);
					$idGestionnaire  = $_POST['idGestionnaire'];
					$tmp             = $_FILES['image']['tmp_name'];
					$nomImage        = $gestionnaire['nom'] . '_' . $_FILES['image']['name'];

					if( $_FILES['image']['name'] != '' )
					{
						$info        = new SplFileInfo($_FILES['image']['name']);
						$extension   = $info->getExtension();
						$extension   = strtoupper($extension);

						if( $extension == 'PNG' || $extension == 'JPG' || $extension == 'JPEG' || $extension == 'GIF')
						{
							$resUpload = UploadFile($tmp, '../../data/uploades/avatarGestionnaires/' .  $nomImage);

							if($resUpload == 1)
							{
								$sql = "UPDATE gestionaire SET image = ? WHERE idGestionaire = ?";
								$res = setData( $sql, [ $nomImage, $idGestionnaire ] );
								if( $res )
								{
									echo '1';
								}
								else
								{
									echo '0';
								}
							}
							else
							{
								echo '0';
							}
						}
						else
						{
							echo "-1";
						}
					}
					else
					{
						echo '2';
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'getAllRdvs':

				$sql    = 'SELECT r.dateRdv AS start, r.dateFin AS end, p.idPatient + \'  \' + p.nom + \'  \' + p.prenom AS title FROM rdv r, patient p, dossier d WHERE r.idDossier = d.idDossier AND p.idPatient = d.idPatient';
				$rdvs   = getDatas($sql, []);
				 echo json_encode($rdvs);

			break;

			/*============================================= Dossiers Methodes ====================================================*/
			// Get All Dossiers of patient :
			case 'getDossiers':

				if (isset($_GET['idPatient']) && !empty($_GET['idPatient']))
				{
					$idPatient = $_GET['idPatient'];
					$sql       = "SELECT d.* FROM dossier d, patient pt WHERE d.idPatient = pt.idPatient AND pt.idPatient = ?";
					$dossiers  = getDatas($sql, [ $idPatient ]);
					echo json_encode($dossiers);
				}
				else
				{
					echo 0;
				}

			break;

			case 'getAllDossiers':

				$sql      = "SELECT * FROM dossier";
				$dossiers = getDatas($sql, []);
				echo json_encode($dossiers);

			break;

			case 'getMedicaments':

				$sql      = "SELECT m.*, f.libelle FROM medicament m, famillemedicament f WHERE m.idFamilleMedicament = f.idfamille";
				$medicaments = getDatas($sql, []);
				echo json_encode($medicaments);

			break;

			// Add Dossier :
			case 'addDossier':

				if (isset($_GET['idPatient']) && !empty($_GET['idPatient']))
				{
					$idPatient = $_GET['idPatient'];
					$sql       = "INSERT INTO dossier (idDossier, idPatient, dateCreation) VALUES (NULL, ?, NOW());";
					$result    = setData($sql, [ $idPatient ]);

					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'getPaiementsPatient':

				if( isset($_GET['idPatient']) && is_numeric($_GET['idPatient']) && !empty($_GET['idPatient']) )
				{
					$idPatient = $_GET['idPatient'];
					$sql       = "SELECT CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom, ' / ', d.titreDossier) AS patient, p.*, d.montantDossier FROM paiement p, patient pt, dossier d WHERE d.idPatient = pt.idPatient AND p.idDossier = d.idDossier AND pt.idPatient = ? ORDER BY p.datePaiement DESC";
					$paiements = getDatas($sql, [ $idPatient ]);
					echo json_encode($paiements);
				}
				else
				{
					$sql       = "SELECT CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom, ' / ', d.titreDossier) AS patient, p.*, d.montantDossier FROM paiement p, patient pt, dossier d WHERE d.idPatient = pt.idPatient AND p.idDossier = d.idDossier ORDER BY p.datePaiement DESC";
					$paiements = getDatas($sql, []);
					echo json_encode($paiements);
				}

				break;

			case 'getDossiersPatient':

				if( isset($_GET['idPatient']) && is_numeric($_GET['idPatient']) && !empty($_GET['idPatient']) )
				{
					$idPatient = $_GET['idPatient'];
					$sql       = "SELECT d.*, CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom) AS patient FROM dossier d, patient pt WHERE d.idPatient = pt.idPatient AND pt.idPatient = ? ORDER BY d.dateCreation DESC";
					$dossiers  = getDatas($sql, [ $idPatient ]);
					echo json_encode($dossiers);
				}
				else
				{
					$sql       = "SELECT d.*, CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom) AS patient FROM dossier d, patient pt WHERE d.idPatient = pt.idPatient ORDER BY d.dateCreation DESC";
					$dossiers  = getDatas($sql, [ ]);
					echo json_encode($dossiers);
				}

				break;

			case 'getAutresDossiers':

				if(( isset($_GET['idDossier']) && is_numeric($_GET['idDossier']) && !empty($_GET['idDossier']) ) && ( isset($_GET['idPatient']) && is_numeric($_GET['idPatient']) && !empty($_GET['idPatient']) ))
				{
					$idDossier = $_GET['idDossier'];
					$idPatient = $_GET['idPatient'];
					$sql       = "SELECT d.*, CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom) AS patient FROM dossier d, patient pt WHERE d.idPatient = pt.idPatient AND d.idDossier != ?  AND pt.idPatient = ? ORDER BY d.dateCreation DESC";
					$dossiers  = getDatas($sql, [ $idDossier, $idPatient ]);
					echo json_encode($dossiers);
				}
				else
				{
					echo json_encode([]);
				}

			break;

			case 'getActiveRdv':

				$sql = "SELECT P.idPatient AS idPatient, R.idRdv, CONCAT('[', P.cin,'] ', p.prenom, ' ', p.nom, ' / ', D.titreDossier, ' à : ', TIME(R.dateRdv), ' / ', trdv.libelle ) AS rdv, R.dateRdv AS dateRdv, Etat AS Etat, D.idDossier AS dossier FROM rdv R, dossier D, patient P, typerdv trdv WHERE DATE_FORMAT(R.dateRdv, '%Y-%m-%d') = CURDATE() AND R.idDossier = D.idDossier AND D.idPatient = P.idPatient AND R.Etat = 'a' AND trdv.idType = R.typeRdv";
				$rdvActive = getDatas( $sql, [] );
				echo json_encode($rdvActive);

			break;

			//getPaiementsDatePaiement

			case 'getPaiementsDatePaiement':

				if( (isset($_GET['dateDebut']) && !empty($_GET['dateDebut'])) && (isset($_GET['dateFin']) && !empty($_GET['dateFin'])))
				{
					$dateDebut = $_GET['dateDebut'] . ' 00:00:00';
					$dateFin   = $_GET['dateFin'] . ' 00:00:00';
					$sql       = "SELECT CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom, ' / ', d.titreDossier) AS patient, d.montantDossier, p.* FROM dossier d, patient pt, paiement p WHERE d.idPatient = pt.idPatient AND p.datePaiement BETWEEN ? and ?";

					if($_GET['dateDebut'] < $_GET['dateFin'])
					{
						$paiements  = getDatas($sql, [ $dateDebut, $dateFin ]);
					}
					else
					{
						$paiements  = getDatas($sql, [ $dateFin, $dateDebut ]);
					}
					echo json_encode($paiements);
				}
				else
				{
					echo '0';
				}

			break;

			case 'getDossiersDateCreation':

				if( (isset($_GET['dateDebut']) && !empty($_GET['dateDebut'])) && (isset($_GET['dateFin']) && !empty($_GET['dateFin'])))
				{
					$dateDebut = $_GET['dateDebut'] . ' 00:00:00';
					$dateFin   = $_GET['dateFin'] . ' 00:00:00';
					$sql       = "SELECT d.*, CONCAT('[ ', pt.cin, ' ] ', pt.prenom, ' ',pt.nom) AS patient FROM dossier d, patient pt WHERE d.idPatient = pt.idPatient AND d.dateCreation BETWEEN ? and ? ";

					if($_GET['dateDebut'] < $_GET['dateFin'])
					{
						$dossiers  = getDatas($sql, [ $dateDebut, $dateFin ]);
					}
					else
					{
						$dossiers  = getDatas($sql, [ $dateFin, $dateDebut ]);
					}
					echo json_encode($dossiers);
				}
				else
				{
					echo '0';
				}

			break;

//				$sql = "SELECT P.idPatient AS idPatient, R.idRdv, CONCAT('[', P.cin,'] ', p.prenom, ' ', p.nom, ' / dossier : ', R.idDossier, ' à : ', TIME(R.dateRdv), ' / ', trdv.libelle ) AS rdv, R.dateRdv AS dateRdv, Etat AS Etat, D.idDossier AS dossier FROM rdv R, dossier D, patient P, typerdv trdv WHERE DATE_FORMAT(R.dateRdv, '%Y-%m-%d') = CURDATE() AND R.idDossier = D.idDossier AND D.idPatient = P.idPatient AND R.Etat = 'a' AND trdv.idType = R.typeRdv";
//				$rdvActive = getDatas( $sql, [] );

			//=========================================== Rndez Vous Patient =============================================//
			// Get Rendez Vous Patient :
			case 'getRdvsDossier':

				if (isset($_GET['idDossier']) && !empty($_GET['idDossier']))
				{
					$idDossier = $_GET['idDossier'];
					$sql       = " SELECT R.*, T.libelle FROM rdv R, typerdv T, dossier D WHERE R.idDossier = D.idDossier AND R.typeRdv = T.idType AND D.idDossier = ? ORDER BY R.dateRdv asc";
					$rdvs      = getDatas( $sql, [$idDossier] );

					if( !empty($rdvs) )
					{
						echo json_encode($rdvs);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'getDossierWithSumMontantPaye':

				if (isset($_GET['idDossier']) && !empty($_GET['idDossier']))
				{
					$idDossier = $_GET['idDossier'];
					$sql       = "SELECT SUM(p.montantPaye) AS montantPaye, d.* FROM dossier d, paiement p WHERE d.idDossier = ? AND p.idDossier = d.idDossier";
					$rdvs      = getData( $sql, [$idDossier] );

					if( !empty($rdvs) )
					{
						echo json_encode($rdvs);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

				break;

			case 'getSalledAttente':

				$sql       = "SELECT sd.etat, sd.dateArr AS heurArrivee, sd.idSalleDAttent, sd.idDossier, p.idPatient, t.libelle AS type, sd.idRdv, CONCAT( '[ ', p.cin, ' ] ', p.prenom,' ', p.nom, ' / ', d.titreDossier ) AS patient FROM salledattent sd, patient p, dossier d, typerdv t WHERE sd.idDossier = d.idDossier AND d.idPatient = p.idPatient AND t.idType = sd.idType AND DATE(sd.dateArr) = CURDATE() ORDER BY sd.dateArr ASC";
				$salledAttente = getDatas( $sql, [] );

				if( $salledAttente )
				{
					echo json_encode($salledAttente);
				}
				else
				{
					echo json_encode([]);
				}

			break;

			case 'validerSalleAtt':

				if ( isset( $_GET['idSall'] ) && !empty( $_GET['idSall'] ) )
				{
					$idSallAttent = $_GET['idSall'];
					$sql = "UPDATE salledattent SET etat = 1 WHERE idSalleDAttent = ?";
					$res = setData( $sql, [ $idSallAttent ] );

					if( $res )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'deleteSalleAtt':

				if ( isset( $_GET['idSall'] ) && !empty( $_GET['idSall'] ) )
				{
					$idSallAttent = $_GET['idSall'];
					$sallAtt = getData('SELECT * FROM salledattent WHERE idSalleDAttent = ?', [ $idSallAttent ]);

					if( !empty( $sallAtt ) && $sallAtt['idRdv'] != null )
					{
						$sql = "UPDATE rdv SET Etat = 'a' WHERE idRdv = ?";
						$res = setData( $sql, [ $sallAtt['idRdv'] ] );

						if( !$res )
						{
							echo 0;
							die();
						}
					}

					$sql = "DELETE FROM salledattent WHERE idSalleDAttent = ?";
					$res = setData( $sql, [ $idSallAttent ] );

					if( $res )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;


			case 'addRdvAuSalleDattente':

				if( isset($_POST['idRdv']) && is_numeric($_POST['idRdv']) && !empty($_POST['idRdv']) )
				{
					$idRdv = $_POST['idRdv'];

					$sql = "UPDATE rdv SET Etat = 'v' WHERE idRdv = ?";
					$res1 = setData($sql, [ $idRdv ]);

					$sql = "SELECT * FROM rdv WHERE idRdv = ?";
					$rdv = getData($sql, [ $idRdv ]);

					$sql = "INSERT INTO salledattent (`idSalleDAttent`, `dateArr`, `idRdv`, `idType`, `idDossier`, `etat`) VALUES (NULL, NOW(), ?, ?, ?, '0');";
					$res2 = setData($sql, [ $rdv['idRdv'], $rdv['typeRdv'], $rdv['idDossier'] ]);

					echo ($res1 && $res2) ? 1 : 0;
				}
				else
				{
					echo 0;
				}

				break;

			case 'addPatientSalleAttent':

				if( ( isset($_POST['idDossier']) && is_numeric($_POST['idDossier']) && !empty($_POST['idDossier']) ) &&
					( isset($_POST['idType']) && is_numeric($_POST['idType']) && !empty($_POST['idType']) ))
				{
					$idDossier = $_POST['idDossier'];
					$idType = $_POST['idType'];

					$sql = "INSERT INTO salledattent (`idSalleDAttent`, `dateArr`, `idRdv`, `idType`, `idDossier`, `etat`) VALUES (NULL, NOW(), NULL, ?, ?, '0');";
					$res = setData($sql, [ $idType, $idDossier ]);

					echo $res ? 1 : 0;
				}
				else
				{
					echo 0;
				}

			break;

			// getRdv With Date :
			case 'getRdvDate':

				if( isset($_GET['dateRdv']) && !empty($_GET['dateRdv']) )
				{
					$dateRdv   = $_GET['dateRdv'];
					$sql       = "SELECT R.*, concat(P.prenom, ' ',P.nom) AS nomPatient, D.* FROM rdv R, patient P, dossier D WHERE R.idDossier = D.idDossier AND P.idPatient = D.idPatient AND month(R.dateRdv) = month(?) and year(R.dateRdv) = year(?) and day(R.dateRdv) = day(?)";
					$rdvs      = getDatas( $sql, [ $dateRdv, $dateRdv, $dateRdv ] );

					if( $rdvs )
					{
						echo json_encode($rdvs);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			// Desactive Rendez Vous Patient :
			case 'desactiveRdv':

				if (isset($_GET['idRdv']) && !empty($_GET['idRdv']))
				{
					$idRdv     = $_GET['idRdv'];
					$sql       = "UPDATE rdv SET Etat = 'd' WHERE rdv.idRdv = ?";;
					$res       = setData( $sql, [$idRdv] );

					if( $res )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'getRdv':

				if ((isset($_GET['idRdv']) && !empty($_GET['idRdv'])))
				{
					$idRdv     = $_GET['idRdv'];
					$sql       = "SELECT r.*, d.idDossier, d.titreDossier, p.nom, p.prenom, p.idPatient, p.tel, t.libelle FROM rdv r, dossier d, typerdv t, patient p WHERE idRdv = ? AND r.idDossier = d.idDossier AND d.idPatient = p.idPatient AND r.typeRdv = t.idType";
					$res       = getData( $sql, [$idRdv] );


					if( $res )
					{
						echo json_encode($res);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			// Get Horaires Rdv :
			case 'getHorairesDisponible':

			

				if( isset($_GET['dateAddRdv']) && !empty($_GET['dateAddRdv']))
				{
					$dateAddRdv = $_GET['dateAddRdv'];
					$dayNumber  = date('N', strtotime($dateAddRdv));
					$sql = '';

					if( $dayNumber == 1 )
					{
						$sql = "SELECT lundiMatinDebut AS matinDebut, lundiMatinFin AS matinFin, lundiSoireDebut AS soireDebut, lundiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 2 )
					{
						$sql = "SELECT mardiMatinDebut AS matinDebut, mardiMatinFin AS matinFin, mardiSoireDebut AS soireDebut, mardiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 3 )
					{
						$sql = "SELECT mercrediMatinDebut AS matinDebut, mercrediMatinFin AS matinFin, mercrediSoireDebut AS soireDebut, mercrediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 4 )
					{
						$sql = "SELECT jeudiMatinDebut AS matinDebut, jeudiMatinFin AS matinFin, jeudiSoireDebut AS soireDebut, jeudiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 5 )
					{
						$sql = "SELECT vendrediMatinDebut AS matinDebut, vendrediMatinFin AS matinFin, vendrediSoireDebut AS soireDebut, vendrediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 6 )
					{
						$sql = "SELECT samediMatinDebut AS matinDebut, samediMatinFin AS matinFin, samediSoireDebut AS soireDebut, samediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 7 )
					{
						$sql = "SELECT dimancheMatinDebut AS matinDebut, dimancheMatinFin AS matinFin, dimancheSoireDebut AS soireDebut, dimancheSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else
					{
						echo $dayNumber;
					}

					$horaire  = getData( $sql, [] );

					if( $horaire )
					{
						echo json_encode($horaire);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'getRdvsReserver':

				if (isset($_GET['dateAddRdv']) && !empty($_GET['dateAddRdv']))
				{
					$dateAddRdv = $_GET['dateAddRdv'];
					$sql        = "SELECT TIME(dateRdv) AS dateI FROM rdv WHERE DATE( dateRdv ) = DATE( ? ) AND (Etat != 'v' AND Etat != 'd' ) ";
					$rdvsReserver = getDatas( $sql, [ $dateAddRdv ]);

					if( $rdvsReserver )
					{
						echo json_encode($rdvsReserver);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'getEtatsAcceuiAdmin':

				$nbrRdvValid = getData("SELECT COUNT(*) AS nbrRdvValid FROM rdv R WHERE DATE_FORMAT(R.dateRdv, '%Y-%m-%d') = CURDATE() AND R.Etat = 'v'", [])['nbrRdvValid'];
				$nbrRdvEnAttent = getData("SELECT COUNT(*) AS nbrRdvEnAttent FROM rdv R WHERE DATE_FORMAT(R.dateRdv, '%Y-%m-%d') = CURDATE() AND R.Etat = 'a'", [])['nbrRdvEnAttent'];
				$nbrAnuller  = getData("SELECT COUNT(*) AS nbrRdvAnnuler FROM rdv R WHERE DATE_FORMAT(R.dateRdv, '%Y-%m-%d') = CURDATE() AND R.Etat = 'd'", [])['nbrRdvAnnuler'];
				$nbrConsultations = getData("SELECT COUNT(*) AS nbrConsultations FROM consultation C WHERE DATE_FORMAT(C.dateDebut, '%Y-%m-%d') = CURDATE()", [])['nbrConsultations'];
				$nbrDossierOuvert = getData("SELECT COUNT(*) AS nbrDossierOuvert FROM dossier D WHERE DATE_FORMAT(D.dateCreation, '%Y-%m-%d') = CURDATE()", [])['nbrDossierOuvert'];
				$nbrPatientSalledAttente = getData("SELECT COUNT(*) AS nbrPatientAuSalleAttent FROM salledattent sd WHERE DATE_FORMAT(sd.dateArr, '%Y-%m-%d') = CURDATE() AND sd.etat = 0", [])['nbrPatientAuSalleAttent'];
				$nbrPatientEnregestrer = getData("SELECT COUNT(*) AS nbrPatientEnregestrer FROM patient p WHERE DATE_FORMAT(p.dateajout, '%Y-%m-%d') = CURDATE()", [])['nbrPatientEnregestrer'];
				$etatDeCaisse = getData("SELECT SUM( p.montantPaye ) AS etatDeCaisse FROM paiement p WHERE DATE_FORMAT(p.datePaiement, '%Y-%m-%d') = CURDATE()", [])['etatDeCaisse'];

				$etatsAcceuilAdmin['nbrRdvValid'] = $nbrRdvValid;
				$etatsAcceuilAdmin['nbrRdvEnAttent'] = $nbrRdvEnAttent;
				$etatsAcceuilAdmin['nbrAnuller'] = $nbrAnuller;
				$etatsAcceuilAdmin['nbrConsultations'] = $nbrConsultations;
				$etatsAcceuilAdmin['nbrDossierOuvert'] = $nbrDossierOuvert;
				$etatsAcceuilAdmin['nbrPatientSalledAttente'] = $nbrPatientSalledAttente;
				$etatsAcceuilAdmin['nbrPatientEnregestrer'] = $nbrPatientEnregestrer;
				$etatsAcceuilAdmin['etatDeCaisse'] = $etatDeCaisse;

				echo json_encode($etatsAcceuilAdmin);

			break;

			//
			case 'updateRdvDossier':

			

				if((isset($_POST['idRdv']) && !empty($_POST['idRdv'])) && (isset($_POST['etatRdv']) && !empty($_POST['etatRdv'])) && (isset($_POST['typeRdv']) && !empty($_POST['typeRdv'])))
				{
					$idRdv          = $_POST['idRdv'];
					$etat           = $_POST['etatRdv'];
					$typeRdv        = $_POST['typeRdv'];
					$sql            = '';
					$params         = [];
					$dateValidation = NULL;

					if( $etat == 'v' )
					{
						$dateValidation = date("Y-m-d H:i:s");
					}

					if ((isset($_POST['heurRdv']) && !empty($_POST['heurRdv'])) && (isset($_POST['dateEditRdv']) && !empty($_POST['dateEditRdv'])))
					{
						$dateRdv = $_POST['dateEditRdv'] . ' ' . $_POST['heurRdv'] . ':00';
						$sql     = "UPDATE rdv SET dateRdv = ?, Etat = ?, dateValidation = ?, typeRdv = ? WHERE idRdv = ?";
						$params  = [ $dateRdv, $etat, $dateValidation, $typeRdv, $idRdv ];
					}
					else
					{
						$sql     = "UPDATE rdv SET Etat = ?, dateValidation = ?, typeRdv = ? WHERE idRdv = ?";
						$params = [ $etat, $dateValidation, $typeRdv, $idRdv ];
					}

					if(setData($sql, $params))
					{
						echo '1';
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo '-1';
				}

			break;



			case 'updateRdvCalendar':

			

				if((isset($_POST['idDossierForEdit']) && !empty($_POST['idDossierForEdit'])) && (isset($_POST['etatRdv']) && !empty($_POST['etatRdv'])) && (isset($_POST['idRdv']) && !empty($_POST['idRdv'])) && (isset($_POST['typeRdv']) && !empty($_POST['typeRdv'])))
				{
					$idRdv     = $_POST['idRdv'];
					$idDossier = $_POST['idDossierForEdit'];
					$etat      = $_POST['etatRdv'];
					$typeRdv   = $_POST['typeRdv'];
					$sql       = '';
					$params    = [];

					if( $etat == 'v' )
					{
						$sql = "UPDATE rdv SET Etat = ?, idDossier = ?, typeRdv = ?, dateValidation = NOW() WHERE idRdv = ?";
					}
					else
					{
						$sql = "UPDATE rdv SET Etat = ?, idDossier = ?, typeRdv = ?, dateValidation = '' WHERE idRdv = ?";
					}

					$params = [ $etat, $idDossier, $typeRdv, $idRdv ];

					if(setData($sql, $params))
					{
						echo '1';
					}
					else
					{
						echo '0';
					}

				}
				else
				{
					echo '-1';
				}

			break;

			case 'addRdvDossier':

			

				if((isset($_POST['idDossier']) && !empty($_POST['idDossier'])) && (isset($_POST['dateAddRdv']) && !empty($_POST['dateAddRdv'])))
				{
					$idDossier      = $_POST['idDossier'];
					$dateRdv        = $_POST['dateAddRdv'];
					$etat           = 'd';

					$exists = getdata("SELECT * FROM rdv WHERE dateRdv = ?", [ $dateRdv]);

					if( isset($exists['dateRdv']) )
					{
						echo '2';
						die();
					}
					if( isset($_POST['etat']) )
					{
						$etat = 'a';
					}

					if( !preg_match("#^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$#", $dateRdv) )
					{
						$errosUpdateRdv['dateAddRdv'] = "Le format de date rendez vous est incorrect ( aaaa-mm-jj hh:mm-ss ) !";
					}

					$errosUpdateRdv = [];

					if( empty($errosUpdateRdv) )
					{
						$sql    = "INSERT INTO rdv( idRdv, idDossier, dateRdv, Etat ) VALUES( NULL, ?, ?, ? )";
						$params = [ $idDossier, $dateRdv, $etat ];

						if(setData($sql, $params))
						{
							echo '1';
						}
						else
						{
							echo '0';
						}
					}
					else
					{
						echo json_encode($errosUpdateRdv);
					}
				}
				else
				{
					echo '-1';
				}

			break;

			case 'addMutuelle':

			

				if( isset($_POST['nomMutuelle']) && !empty($_POST['nomMutuelle']) )
				{
					$nomMutuelle = $_POST['nomMutuelle'];
					$errosAddMutuelle = [];

					$exists = getdata("SELECT * FROM mutuel WHERE libelle = ?", [ $nomMutuelle]);

					if( !empty($exists) )
					{
						echo '2';
						die();
					}

					if( strlen($nomMutuelle) < 3 )
					{
						$errosAddMutuelle['nomMutuelle'] = "Le nom de mutuelle doit conteniren au moins 3 caractéres !";
					}

					if( empty($errosAddMutuelle) )
					{
						$sql    = "INSERT INTO mutuel ( idMutuel, libelle ) VALUES( NULL, ? )";
						$params = [ $nomMutuelle ];

						if(setData($sql, $params))
						{
							$mutuelles = getDatas("SELECT * FROM mutuel", []);
							echo json_encode($mutuelles);
						}
						else
						{
							echo '0';
						}
					}
					else
					{
						echo json_encode($errosAddMutuelle);
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'addMedicament':

			

				if((isset($_POST['designation']) && !empty($_POST['designation'])) && (isset($_POST['familleMedicament']) && !empty($_POST['familleMedicament'])) && (isset($_POST['dosage']) && !empty($_POST['dosage'])))
				{
					$designation        = $_POST['designation'];
					$familleMedicament  = $_POST['familleMedicament'];
					$dosage             = $_POST['dosage'];
					$errosAddMedicament = [];

					if( strlen($designation) < 2 )
					{
						$errosAddMedicament['designation'] = "Le nom de medicament doit contenire au moins 2 caractéres !";
					}

					if( strlen($dosage) < 2 )
					{
						$errosAddMedicament['dosage'] = "Le dosage de medicament doit contenire au moins 2 caractéres !";
					}

					if( empty($errosAddMedicament) )
					{
						$sql    = "INSERT INTO medicament ( idMedicament, idFamilleMedicament, designation, dosage ) VALUES( NULL, ?, ?, ? )";
						$params = [ $familleMedicament, $designation, $dosage ];

						if(setData($sql, $params))
						{
							$medicaments = getDatas("SELECT CONCAT(m.designation, ' ', m.dosage, ' ', f.libelle ) AS med FROM medicament m, famillemedicament f WHERE m.idFamilleMedicament = f.idfamille ORDER BY m.designation ASC", []);
							echo json_encode($medicaments);
						}
						else
						{
							echo '0';
						}
					}
					else
					{
						echo json_encode($errosAddMedicament);
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'updateMedicament':

			

				if((isset($_POST['idMedicament']) && !empty($_POST['idMedicament'])) && (isset($_POST['designation']) && !empty($_POST['designation'])) && (isset($_POST['familleMedicament']) && !empty($_POST['familleMedicament'])) && (isset($_POST['dosage']) && !empty($_POST['dosage'])))
				{
					$idMedicament       = $_POST['idMedicament'];
					$designation        = $_POST['designation'];
					$familleMedicament  = $_POST['familleMedicament'];
					$dosage             = $_POST['dosage'];
					$errosAddMedicament = [];

					if( strlen($designation) < 2 )
					{
						$errosAddMedicament['designation'] = "Le nom de medicament doit contenire au moins 2 caractéres !";
					}

					if( strlen($dosage) < 2 )
					{
						$errosAddMedicament['dosage'] = "Le dosage de medicament doit contenire au moins 2 caractéres !";
					}

					if( empty($errosAddMedicament) )
					{
						$sql    = "UPDATE medicament SET idFamilleMedicament = ?, designation = ?, dosage = ? WHERE idMedicament = ?";
						$params = [ $familleMedicament, $designation, $dosage, $idMedicament ];

						if(setData($sql, $params))
						{
							echo 1;
						}
						else
						{
							echo '0';
						}
					}
					else
					{
						echo json_encode($errosAddMedicament);
					}
				}
				else
				{
					echo '0';
				}

				break;

			case 'types':

			$sql    = "SELECT * FROM typeRdv";
			$types = getDatas($sql, []);
			if( $types )
			{
				echo json_encode($types);
			}
			else
			{
				echo '0';
			}

			break;

			case 'addDossierPatient':

			

				if((isset($_POST['idPatient']) && !empty($_POST['idPatient'])) && (isset($_POST['titreDossier'])))
				{
					$idPatient    = $_POST['idPatient'];
					$titreDossier = $_POST['titreDossier'];

					$errosAddDossier = [];

					if( strlen($titreDossier) < 2 )
					{
						$errosAddDossier['titreDossier'] = "Le titre de dossier doit contenire au moins 2 caractéres !";
					}

					if( empty($errosAddDossier) )
					{
						$sql    = "INSERT INTO dossier ( idDossier, idPatient, titreDossier, dateCreation ) VALUES( NULL, ?, ?, NOW() )";
						$params = [ $idPatient, $titreDossier ];

						if(setData($sql, $params))
						{
							echo '1';
						}
						else
						{
							echo '0';
						}
					}
					else
					{
						echo json_encode($errosAddDossier);
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'addRdv':

			

				if((isset($_POST['dateRdv']) && !empty($_POST['dateRdv'])) && (isset($_POST['idDossier']) && !empty($_POST['idDossier']) && is_numeric($_POST['idDossier']) )&& (isset($_POST['typeRdv']) && !empty($_POST['typeRdv']) && is_numeric($_POST['typeRdv']) ))
				{
					$dateRdv   = $_POST['dateRdv'];
					$idDossier = $_POST['idDossier'];
					$typeRdv   = $_POST['typeRdv'];
					$etat = 'a';

					if( !preg_match("#^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$#", $dateRdv) )
					{
						echo '-1';
						die();
					}

					$sql    = "INSERT INTO rdv( idRdv, idDossier, dateRdv, typeRdv, Etat ) VALUES( NULL, ?, ?, ?, ? )";
					$params = [ $idDossier, $dateRdv, $typeRdv, $etat ];
					if(setData($sql, $params))
					{
						echo '1';
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo '-1';
				}

			break;

			case 'deleteRdvDossier':

			

				if(isset($_GET['idRdv']))
				{
					$idRdv = $_GET['idRdv'];
					$sql     = "DELETE FROM rdv WHERE idRdv = ?";
					$result  = setData($sql, [ $idRdv ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;


			case 'deleteMedicament':

			

				if(isset($_GET['idMedicament']))
				{
					$idMed = $_GET['idMedicament'];
					$sql     = "DELETE FROM medicament WHERE idMedicament = ?";
					$result  = setData($sql, [ $idMed ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'updateDureeRdv':

			

				if(isset($_GET['dureeRdv']))
				{
					$dureeRdv = $_GET['dureeRdv'];
					$sql     = "UPDATE parametrage SET dureeRdv = ? WHERE etat = 1";
					$result  = setData($sql, [ $dureeRdv ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;


			case 'getRdvs':

				$sql = "SELECT CONCAT( p.prenom, '  ', p.nom, ' #', r.idRdv ) as title, r.dateRdv as start, r.dateFin AS end, F_ETAT_RDV (idRdv) AS backgroundColor, F_ETAT_RDV (idRdv) AS borderColor FROM rdv r, patient p, dossier d WHERE r.idDossier = d.idDossier AND d.idPatient = p.idPatient";
				$rdvs = getDatas( $sql, [] );
				echo json_encode($rdvs);
			break;

			// get all rdv of today :
			case 'getRdvAujourdhui':

				$sql = "SELECT P.idPatient AS idPatient, R.idRdv, CONCAT('[', P.cin,'] - ', p.prenom, ' ', p.nom, ' / ', D.titreDossier ) AS patient, R.idDossier, TIME(R.dateRdv) AS heurRdv, trdv.libelle AS typeRdv, R.dateRdv AS dateRdv, Etat AS Etat, D.idDossier AS dossier FROM rdv R, dossier D, patient P, typerdv trdv WHERE DATE_FORMAT(R.dateRdv, '%Y-%m-%d') = CURDATE() AND R.idDossier = D.idDossier AND D.idPatient = P.idPatient AND trdv.idType = R.typeRdv";
				$rdvs = getDatas( $sql, [] );
				echo json_encode($rdvs);

			break;

			// get all rdv of today :
			case 'getValideRdv':

				$sql = "SELECT P.idPatient AS idPatient,  CONCAT('[', P.cin,'] - ', p.prenom, ' ', p.nom ) AS patient, TIME(R.dateValidation) AS heurArrivee, R.dateRdv AS dateRdv, D.idDossier AS dossier FROM rdv R, dossier D, patient P WHERE DATE_FORMAT(R.dateValidation, '%Y-%m-%d') = CURDATE() AND R.idDossier = D.idDossier AND D.idPatient = P.idPatient ORDER BY R.dateValidation ASC";
				$rdvs = getDatas( $sql, [] );
				echo json_encode($rdvs);

			break;

			// with calendar :
			case 'addRdvCalendar':

				if((isset($_POST['dateRdv']) && !empty($_POST['dateRdv'])) && (isset($_POST['idDossier']) && !empty($_POST['idDossier']) && is_numeric($_POST['idDossier']) ) && (isset($_POST['dureeRdv']) && !empty($_POST['dureeRdv']) && (isset($_POST['typeRdv']) && !empty($_POST['typeRdv']))))
				{
					$dateRdv   = $_POST['dateRdv'];
					$heurRdv   = date("H:i:s",strtotime($dateRdv));
					$idDossier = $_POST['idDossier'];
					$dureeRdv  = $_POST['dureeRdv'];
					$typeRdv   = $_POST['typeRdv'];
					$etat = 'a';
					$isValid = false;

					if( !preg_match("#^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$#", $dateRdv) )
					{
						echo '0';
						die();
					}

					if( $dateRdv < date('Y-m-d H:i:s') )
					{
						echo '-1';
						die();
					}
					// horaires disponible :
					$dayNumber  = date('N', strtotime($dateRdv));
					$sql = '';

					if( $dayNumber == 1 )
					{
						$sql = "SELECT lundiMatinDebut AS matinDebut, lundiMatinFin AS matinFin, lundiSoireDebut AS soireDebut, lundiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 2 )
					{
						$sql = "SELECT mardiMatinDebut AS matinDebut, mardiMatinFin AS matinFin, mardiSoireDebut AS soireDebut, mardiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 3 )
					{
						$sql = "SELECT mercrediMatinDebut AS matinDebut, mercrediMatinFin AS matinFin, mercrediSoireDebut AS soireDebut, mercrediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 4 )
					{
						$sql = "SELECT jeudiMatinDebut AS matinDebut, jeudiMatinFin AS matinFin, jeudiSoireDebut AS soireDebut, jeudiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 5 )
					{
						$sql = "SELECT vendrediMatinDebut AS matinDebut, vendrediMatinFin AS matinFin, vendrediSoireDebut AS soireDebut, vendrediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 6 )
					{
						$sql = "SELECT samediMatinDebut AS matinDebut, samediMatinFin AS matinFin, samediSoireDebut AS soireDebut, samediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else
					{
						$sql = "SELECT dimancheMatinDebut AS matinDebut, dimancheMatinFin AS matinFin, dimancheSoireDebut AS soireDebut, dimancheSoireFin AS soireFin FROM horaire WHERE active = 1";
					}

					$horaire  = getData( $sql, [] );

					$HorairesDesponible = [];

					if( $horaire )
					{
						if( ( ($horaire['matinDebut'] <= $heurRdv) && ($horaire['matinFin'] > $heurRdv) ) || ( ($horaire['soireDebut'] <= $heurRdv) && ($horaire['soireFin'] > $heurRdv) ) )
						{
							$isValid = true;
						}
						else
						{
							$isValid = false;
						}

						if( $isValid )
						{
							$sql = "SELECT * FROM rdv WHERE dateRdv = ? AND (Etat != 'v' AND Etat != 'd' )";
							$rdvs = getDatas( $sql, [ $dateRdv ] );

							if( empty( $rdvs ) )
							{
								$sql    = "INSERT INTO rdv( idRdv, idDossier, dateRdv, typeRdv, Etat ) VALUES( NULL, ?, ?, ?, ? )";
								$params = [ $idDossier, $dateRdv, $typeRdv, $etat ];
								if(setData($sql, $params))
								{
									echo '1';
								}
								else
								{
									echo '-1';
								}
							}
							else
							{
								echo '-1';
								die();
							}
						}
						else
						{
							echo '-1';
							die();
						}

					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo '0';
				}

			break;

			/*========================================= Consultations Dossier ===============================================*/
			// Get Consultations
			case 'getConsultations':

				if( isset($_GET['idDossier']) && !empty($_GET['idDossier']) )
				{
					$idDossier = $_GET['idDossier'];
					$sql       = "SELECT * FROM consultation WHERE idDossier = ?";
					$res       = getDatas( $sql, [$idDossier] );

					if( $res )
					{
						echo json_encode($res);
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'deleteDossier':

			

				if(isset($_GET['idDossier']))
				{
					$idDossier = $_GET['idDossier'];
					$sql     = "DELETE FROM dossier WHERE idDossier = ?";
					$result  = setData($sql, [ $idDossier ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			//deletePaiement
			case 'deletePaiement':
			
				if(isset($_GET['idPaiement']))
				{
					$idPaiement   = $_GET['idPaiement'];
					$sql            = "DELETE FROM paiement WHERE idPaiement = ?";
					$result         = setData($sql, [ $idPaiement ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			//deleteHoraire
			case 'deleteHoraire':

				if(isset($_GET['idHoraire']))
				{
					$idHoraire      = $_GET['idHoraire'];
					$sql            = "DELETE FROM horaire WHERE idHoraire = ?";
					$result         = setData($sql, [ $idHoraire ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			//activeHoraire
			case 'activeHoraire':

				if(isset($_GET['idHoraire']) && !empty($_GET['idHoraire']))
				{
					$idHoraire      = $_GET['idHoraire'];
					$sql            = "UPDATE horaire SET active = '0'";
					$result         = setData($sql, []);

					$sql            = "UPDATE horaire SET active = '1' WHERE idHoraire = ?";
					$result         = setData($sql, [ $idHoraire ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}
			break;

			// Delete Consultation :
			case 'deleteConsultation':
			
				if(isset($_GET['idConsultation']))
				{
					$idConsultation = $_GET['idConsultation'];
					$sql            = "DELETE FROM consultation WHERE idConsultation = ?";
					$result         = setData($sql, [ $idConsultation ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			// Add Horaire :
			case 'addHoraire':

			

				$errorsAddHoraire = [];

				if( isset($_POST['lundiMatinDebut']) && isset($_POST['lundiMatinFin']) && isset($_POST['lundiSoireDebut']) && isset($_POST['lundiSoireFin']))
				{
					// lundi
					$lundiMatinDebut = $_POST['lundiMatinDebut'] . ':00';
					$lundiMatinFin   = $_POST['lundiMatinFin'] . ':00';

					$lundiSoireDebut = $_POST['lundiSoireDebut'] . ':00';
					$lundiSoireFin   = $_POST['lundiSoireFin'] . ':00';

					if(!(($lundiMatinDebut <= $lundiMatinFin) && ($lundiMatinFin <= $lundiSoireDebut) && ($lundiSoireDebut <= $lundiSoireFin)))
					{
						$errorsAddHoraire ['lundiMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}

				}
				else
				{
					// lundi
					$lundiMatinDebut = Null;
					$lundiMatinFin   = Null;

					$lundiSoireDebut = Null;
					$lundiSoireFin   = Null;
				}

				if( isset($_POST['mardiMatinDebut']) && isset($_POST['mardiMatinFin']) && isset($_POST['mardiSoireDebut']) && isset($_POST['mardiSoireFin']))
				{
					// mardi
					$mardiMatinDebut  = $_POST['mardiMatinDebut'] . ':00';
					$mardiMatinFin    = $_POST['mardiMatinFin'] . ':00';

					$mardiSoireDebut  = $_POST['mardiSoireDebut'] . ':00';
					$mardiSoireFin    = $_POST['mardiSoireFin'] . ':00';

					if(!(($mardiMatinDebut <= $mardiMatinFin) && ($mardiMatinFin <= $mardiSoireDebut) && ($mardiSoireDebut <= $mardiSoireFin)))
					{
						$errorsAddHoraire ['lundiMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}
				}
				else
				{
					// mardi
					$mardiMatinDebut  = Null;
					$mardiMatinFin    = Null;

					$mardiSoireDebut  = Null;
					$mardiSoireFin    = Null;
				}

				if( isset($_POST['mercrediMatinDebut']) && isset($_POST['mercrediMatinFin']) && isset($_POST['mercrediSoireDebut']) && isset($_POST['mercrediSoireFin']))
				{
					// mercredi
					$mercrediMatinDebut = $_POST['mercrediMatinDebut'] . ':00';
					$mercrediMatinFin   = $_POST['mercrediMatinFin'] . ':00';

					$mercrediSoireDebut = $_POST['mercrediSoireDebut'] . ':00';
					$mercrediSoireFin   = $_POST['mercrediSoireFin'] . ':00';

					if(!(($mercrediMatinDebut <= $mercrediMatinFin) && ($mercrediMatinFin <= $mercrediSoireDebut) && ($mercrediSoireDebut <= $mercrediSoireFin)))
					{
						$errorsAddHoraire ['mercrediMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}
				}
				else
				{
					// mercredi
					$mercrediMatinDebut = Null;
					$mercrediMatinFin   = Null;

					$mercrediSoireDebut = Null;
					$mercrediSoireFin   = Null;
				}

				if( isset($_POST['jeudiMatinDebut']) && isset($_POST['jeudiMatinFin']) && isset($_POST['jeudiSoireDebut']) && isset($_POST['jeudiSoireFin']))
				{
					// jeudi
					$jeudiMatinDebut = $_POST['jeudiMatinDebut'] . ':00';
					$jeudiMatinFin   = $_POST['jeudiMatinFin'] . ':00';

					$jeudiSoireDebut = $_POST['jeudiSoireDebut'] . ':00';
					$jeudiSoireFin   = $_POST['jeudiSoireFin'] . ':00';

					if(!(($jeudiMatinDebut <= $jeudiMatinFin) && ($jeudiMatinFin <= $jeudiSoireDebut) && ($jeudiSoireDebut <= $jeudiSoireFin)))
					{
						$errorsAddHoraire ['jeudiMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}
				}
				else
				{
					// jeudi
					$jeudiMatinDebut = Null;
					$jeudiMatinFin   = Null;

					$jeudiSoireDebut = Null;
					$jeudiSoireFin   = Null;
				}

				if( isset($_POST['vendrediMatinDebut']) && isset($_POST['vendrediMatinFin']) && isset($_POST['vendrediSoireDebut']) && isset($_POST['vendrediSoireFin']))
				{
					// vendredi
					$vendrediMatinDebut = $_POST['vendrediMatinDebut'] . ':00';
					$vendrediMatinFin   = $_POST['vendrediMatinFin'] . ':00';

					$vendrediSoireDebut = $_POST['vendrediSoireDebut'] . ':00';
					$vendrediSoireFin   = $_POST['vendrediSoireFin'] . ':00';

					if(!(($vendrediMatinDebut <= $vendrediMatinFin) && ($vendrediMatinFin <= $vendrediSoireDebut) && ($vendrediSoireDebut <= $vendrediSoireFin)))
					{
						$errorsAddHoraire ['vendrediMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}
				}
				else
				{
					// vendredi
					$vendrediMatinDebut = Null;
					$vendrediMatinFin   = Null;

					$vendrediSoireDebut = Null;
					$vendrediSoireFin   = Null;
				}

				if( isset($_POST['samediMatinDebut']) && isset($_POST['samediMatinFin']) && isset($_POST['samediSoireDebut']) && isset($_POST['samediSoireFin']))
				{
					// samedi
					$samediMatinDebut = $_POST['samediMatinDebut'] . ':00';
					$samediMatinFin   = $_POST['samediMatinFin'] . ':00';

					$samediSoireDebut = $_POST['samediSoireDebut'] . ':00';
					$samediSoireFin   = $_POST['samediSoireFin'] . ':00';

					if(!(($samediMatinDebut <= $samediMatinFin) && ($samediMatinFin <= $samediSoireDebut) && ($samediSoireDebut <= $samediSoireFin)))
					{
						$errorsAddHoraire ['samediMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}
				}
				else
				{
					// samedi
					$samediMatinDebut = Null;
					$samediMatinFin   = Null;

					$samediSoireDebut = Null;
					$samediSoireFin   = Null;
				}

				if( isset($_POST['dimancheMatinDebut']) && isset($_POST['dimancheMatinFin']) && isset($_POST['dimancheSoireDebut']) && isset($_POST['dimancheSoireFin']))
				{
					// dimanche
					$dimancheMatinDebut = $_POST['dimancheMatinDebut'] . ':00';
					$dimancheMatinFin   = $_POST['dimancheMatinFin'] . ':00';

					$dimancheSoireDebut = $_POST['dimancheSoireDebut'] . ':00';
					$dimancheSoireFin   = $_POST['dimancheSoireFin'] . ':00';

					if(!(($dimancheMatinDebut <= $dimancheMatinFin) && ($dimancheMatinFin <= $dimancheSoireDebut) && ($dimancheSoireDebut <= $dimancheSoireFin)))
					{
						$errorsAddHoraire ['dimancheMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
					}
				}
				else
				{
					// dimanche
					$dimancheMatinDebut = Null;
					$dimancheMatinFin   = Null;

					$dimancheSoireDebut = Null;
					$dimancheSoireFin   = Null;
				}

				$aliasHoriare       = ($_POST['aliasHoriare'] == '')?"Alias d'horaire" : $_POST['aliasHoriare'];
				$descriptionHoraire = $_POST['descriptionHoraire'];

				$sql = "INSERT INTO `horaire` (idHoraire, lundiMatinDebut, lundiSoireDebut, mardiMatinDebut, mardiSoireDebut, mercrediMatinDebut, mercrediSoireDebut, jeudiMatinDebut, jeudiSoireDebut, vendrediMatinDebut, vendrediSoireDebut, samediMatinDebut, samediSoireDebut, dimancheMatinDebut, dimancheSoireDebut, nomHoraire, active, descriptionHoriare, lundiMatinFin, lundiSoireFin, mardiMatinFin, mardiSoireFin, mercrediMatinFin, mercrediSoireFin, jeudiMatinFin, jeudiSoireFin, vendrediMatinFin, vendrediSoireFin, samediMatinFin, samediSoireFin, dimancheMatinFin, dimancheSoireFin)
						VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

				$params = [ $lundiMatinDebut, $lundiSoireDebut, $mardiMatinDebut, $mardiSoireDebut, $mercrediMatinDebut, $mercrediSoireDebut, $jeudiMatinDebut, $jeudiSoireDebut, $vendrediMatinDebut, $vendrediSoireDebut, $samediMatinDebut, $samediSoireDebut, $dimancheMatinFin, $dimancheSoireDebut, $aliasHoriare, $descriptionHoraire, $lundiMatinFin, $lundiSoireFin, $mardiMatinFin, $mardiSoireFin, $mercrediMatinFin, $mercrediSoireFin, $jeudiMatinFin, $jeudiSoireFin, $vendrediMatinFin, $vendrediSoireFin, $samediMatinFin, $samediSoireFin, $dimancheMatinDebut, $dimancheSoireFin ];

				if( empty($errorsAddHoraire) )
				{
					$res = setData($sql, $params);
					if( $res )
					{
						echo '1';
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo json_encode($errorsAddHoraire);
				}

			break;

			// Add Horaire :
			case 'updateHoraire':

			

				$errorsAddHoraire = [];

				if( isset($_POST['idHoraire']) && !empty($_POST['idHoraire']) )
				{
					$idHoraire = $_POST['idHoraire'];
					if( isset($_POST['lundiMatinDebut']) && isset($_POST['lundiMatinFin']) && isset($_POST['lundiSoireDebut']) && isset($_POST['lundiSoireFin']))
					{
						// lundi
						$lundiMatinDebut = $_POST['lundiMatinDebut'] . ':00';
						$lundiMatinFin   = $_POST['lundiMatinFin'] . ':00';

						$lundiSoireDebut = $_POST['lundiSoireDebut'] . ':00';
						$lundiSoireFin   = $_POST['lundiSoireFin'] . ':00';

						if(!(($lundiMatinDebut <= $lundiMatinFin) && ($lundiMatinFin <= $lundiSoireDebut) && ($lundiSoireDebut <= $lundiSoireFin)))
						{
							$errorsAddHoraire ['lundiMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}

					}
					else
					{
						// lundi
						$lundiMatinDebut = Null;
						$lundiMatinFin   = Null;

						$lundiSoireDebut = Null;
						$lundiSoireFin   = Null;
					}

					if( isset($_POST['mardiMatinDebut']) && isset($_POST['mardiMatinFin']) && isset($_POST['mardiSoireDebut']) && isset($_POST['mardiSoireFin']))
					{
						// mardi
						$mardiMatinDebut  = $_POST['mardiMatinDebut'] . ':00';
						$mardiMatinFin    = $_POST['mardiMatinFin'] . ':00';

						$mardiSoireDebut  = $_POST['mardiSoireDebut'] . ':00';
						$mardiSoireFin    = $_POST['mardiSoireFin'] . ':00';

						if(!(($mardiMatinDebut <= $mardiMatinFin) && ($mardiMatinFin <= $mardiSoireDebut) && ($mardiSoireDebut <= $mardiSoireFin)))
						{
							$errorsAddHoraire ['lundiMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}
					}
					else
					{
						// mardi
						$mardiMatinDebut  = Null;
						$mardiMatinFin    = Null;

						$mardiSoireDebut  = Null;
						$mardiSoireFin    = Null;
					}

					if( isset($_POST['mercrediMatinDebut']) && isset($_POST['mercrediMatinFin']) && isset($_POST['mercrediSoireDebut']) && isset($_POST['mercrediSoireFin']))
					{
						// mercredi
						$mercrediMatinDebut = $_POST['mercrediMatinDebut'] . ':00';
						$mercrediMatinFin   = $_POST['mercrediMatinFin'] . ':00';

						$mercrediSoireDebut = $_POST['mercrediSoireDebut'] . ':00';
						$mercrediSoireFin   = $_POST['mercrediSoireFin'] . ':00';

						if(!(($mercrediMatinDebut <= $mercrediMatinFin) && ($mercrediMatinFin <= $mercrediSoireDebut) && ($mercrediSoireDebut <= $mercrediSoireFin)))
						{
							$errorsAddHoraire ['mercrediMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}
					}
					else
					{
						// mercredi
						$mercrediMatinDebut = Null;
						$mercrediMatinFin   = Null;

						$mercrediSoireDebut = Null;
						$mercrediSoireFin   = Null;
					}

					if( isset($_POST['jeudiMatinDebut']) && isset($_POST['jeudiMatinFin']) && isset($_POST['jeudiSoireDebut']) && isset($_POST['jeudiSoireFin']))
					{
						// jeudi
						$jeudiMatinDebut = $_POST['jeudiMatinDebut'] . ':00';
						$jeudiMatinFin   = $_POST['jeudiMatinFin'] . ':00';

						$jeudiSoireDebut = $_POST['jeudiSoireDebut'] . ':00';
						$jeudiSoireFin   = $_POST['jeudiSoireFin'] . ':00';

						if(!(($jeudiMatinDebut <= $jeudiMatinFin) && ($jeudiMatinFin <= $jeudiSoireDebut) && ($jeudiSoireDebut <= $jeudiSoireFin)))
						{
							$errorsAddHoraire ['jeudiMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}
					}
					else
					{
						// jeudi
						$jeudiMatinDebut = Null;
						$jeudiMatinFin   = Null;

						$jeudiSoireDebut = Null;
						$jeudiSoireFin   = Null;
					}

					if( isset($_POST['vendrediMatinDebut']) && isset($_POST['vendrediMatinFin']) && isset($_POST['vendrediSoireDebut']) && isset($_POST['vendrediSoireFin']))
					{
						// vendredi
						$vendrediMatinDebut = $_POST['vendrediMatinDebut'] . ':00';
						$vendrediMatinFin   = $_POST['vendrediMatinFin'] . ':00';

						$vendrediSoireDebut = $_POST['vendrediSoireDebut'] . ':00';
						$vendrediSoireFin   = $_POST['vendrediSoireFin'] . ':00';

						if(!(($vendrediMatinDebut <= $vendrediMatinFin) && ($vendrediMatinFin <= $vendrediSoireDebut) && ($vendrediSoireDebut <= $vendrediSoireFin)))
						{
							$errorsAddHoraire ['vendrediMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}
					}
					else
					{
						// vendredi
						$vendrediMatinDebut = Null;
						$vendrediMatinFin   = Null;

						$vendrediSoireDebut = Null;
						$vendrediSoireFin   = Null;
					}

					if( isset($_POST['samediMatinDebut']) && isset($_POST['samediMatinFin']) && isset($_POST['samediSoireDebut']) && isset($_POST['samediSoireFin']))
					{
						// samedi
						$samediMatinDebut = $_POST['samediMatinDebut'] . ':00';
						$samediMatinFin   = $_POST['samediMatinFin'] . ':00';

						$samediSoireDebut = $_POST['samediSoireDebut'] . ':00';
						$samediSoireFin   = $_POST['samediSoireFin'] . ':00';

						if(!(($samediMatinDebut <= $samediMatinFin) && ($samediMatinFin <= $samediSoireDebut) && ($samediSoireDebut <= $samediSoireFin)))
						{
							$errorsAddHoraire ['samediMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}
					}
					else
					{
						// samedi
						$samediMatinDebut = Null;
						$samediMatinFin   = Null;

						$samediSoireDebut = Null;
						$samediSoireFin   = Null;
					}

					if( isset($_POST['dimancheMatinDebut']) && isset($_POST['dimancheMatinFin']) && isset($_POST['dimancheSoireDebut']) && isset($_POST['dimancheSoireFin']))
					{
						// dimanche
						$dimancheMatinDebut = $_POST['dimancheMatinDebut'] . ':00';
						$dimancheMatinFin   = $_POST['dimancheMatinFin'] . ':00';

						$dimancheSoireDebut = $_POST['dimancheSoireDebut'] . ':00';
						$dimancheSoireFin   = $_POST['dimancheSoireFin'] . ':00';

						if(!(($dimancheMatinDebut <= $dimancheMatinFin) && ($dimancheMatinFin <= $dimancheSoireDebut) && ($dimancheSoireDebut <= $dimancheSoireFin)))
						{
							$errorsAddHoraire ['dimancheMatinDebut'] = 'Erreur : Vuillez choisie un date corréect !';
						}
					}
					else
					{
						// dimanche
						$dimancheMatinDebut = Null;
						$dimancheMatinFin   = Null;

						$dimancheSoireDebut = Null;
						$dimancheSoireFin   = Null;
					}

					$aliasHoriare       = ($_POST['aliasHoriare'] == '')?"Alias d'horaire" : $_POST['aliasHoriare'];
					$descriptionHoraire = $_POST['descriptionHoraire'];

					$sql = "UPDATE `horaire` SET lundiMatinDebut = ?, lundiSoireDebut = ?, mardiMatinDebut = ?, mardiSoireDebut = ?,
 												 mercrediMatinDebut = ?, mercrediSoireDebut = ?, jeudiMatinDebut = ?, jeudiSoireDebut = ?,
 												 vendrediMatinDebut = ?, vendrediSoireDebut = ?, samediMatinDebut = ?, samediSoireDebut = ?,
 												 dimancheMatinDebut = ?, dimancheSoireDebut = ?, nomHoraire = ?, descriptionHoriare = ?,
 												 lundiMatinFin = ?, lundiSoireFin = ?, mardiMatinFin = ?, mardiSoireFin = ?, mercrediMatinFin = ?,
 												 mercrediSoireFin = ?, jeudiMatinFin = ?, jeudiSoireFin = ?, vendrediMatinFin = ?, vendrediSoireFin = ?,
 												 samediMatinFin = ?, samediSoireFin = ?, dimancheMatinFin = ?, dimancheSoireFin = ?
 												 WHERE idHoraire = ?";

					$params = [ $lundiMatinDebut, $lundiSoireDebut, $mardiMatinDebut, $mardiSoireDebut, $mercrediMatinDebut, $mercrediSoireDebut, $jeudiMatinDebut, $jeudiSoireDebut, $vendrediMatinDebut, $vendrediSoireDebut, $samediMatinDebut, $samediSoireDebut, $dimancheMatinFin, $dimancheSoireDebut, $aliasHoriare, $descriptionHoraire, $lundiMatinFin, $lundiSoireFin, $mardiMatinFin, $mardiSoireFin, $mercrediMatinFin, $mercrediSoireFin, $jeudiMatinFin, $jeudiSoireFin, $vendrediMatinFin, $vendrediSoireFin, $samediMatinFin, $samediSoireFin, $dimancheMatinDebut, $dimancheSoireFin, $idHoraire ];

					if( empty($errorsAddHoraire) )
					{
						$res = setData($sql, $params);
						if( $res )
						{
							echo '1';
						}
						else
						{
							echo '0 111';
						}
					}
					else
					{
						echo json_encode($errorsAddHoraire);
					}
				}
				else
				{
					echo 0;
				}

			break;

			// Get Horaires :
			case 'getHoraires':

				$sql       = "SELECT * FROM horaire";
				$horaires  = getDatas($sql, []);
				echo json_encode($horaires);

			break;

			// Get Horaires :
			case 'getMinMaxHoraire':

				$sql = "SELECT * FROM horaire WHERE active = 1";
				$horaire = getData($sql, []);

				$minHoraire = $horaire['lundiMatinDebut'];

				if( ($horaire['lundiMatinDebut'] != null) && $minHoraire > $horaire['lundiMatinDebut'] )
				{
					$minHoraire = $horaire['lundiMatinDebut'];
				}
				if( ($horaire['mardiMatinDebut'] != null) && $minHoraire > $horaire['mardiMatinDebut'] )
				{
					$minHoraire = $horaire['mardiMatinDebut'];
				}
				if( ($horaire['mercrediMatinDebut'] != null) && $minHoraire > $horaire['mercrediMatinDebut'] )
				{
					$minHoraire = $horaire['mercrediMatinDebut'];
				}
				if( ($horaire['jeudiMatinDebut'] != null) && $minHoraire > $horaire['jeudiMatinDebut'] )
				{
					$minHoraire = $horaire['jeudiMatinDebut'];
				}
				if( ($horaire['vendrediMatinDebut'] != null) && $minHoraire > $horaire['vendrediMatinDebut'] )
				{
					$minHoraire = $horaire['vendrediMatinDebut'];
				}
				if( ($horaire['samediMatinDebut'] != null) && $minHoraire > $horaire['samediMatinDebut'] )
				{
					$minHoraire = $horaire['samediMatinDebut'];
				}
				if( ($horaire['dimancheMatinDebut'] != null) &&  $minHoraire > $horaire['dimancheMatinDebut'] )
				{
					$minHoraire = $horaire['dimancheMatinDebut'];
				}

				//
				$maxHoraire = $horaire['lundiSoireFin'];

				if( ($horaire['lundiSoireFin'] != null) && $maxHoraire < $horaire['lundiSoireFin'] )
				{
					$maxHoraire = $horaire['lundiSoireFin'];
				}
				if( ($horaire['mardiSoireFin'] != null) && $maxHoraire < $horaire['mardiSoireFin'] )
				{
					$maxHoraire = $horaire['mardiSoireFin'];
				}
				if( ($horaire['mercrediSoireFin'] != null) && $maxHoraire < $horaire['mercrediSoireFin'] )
				{
					$maxHoraire = $horaire['mercrediSoireFin'];
				}
				if( ($horaire['jeudiSoireFin'] != null) && $maxHoraire < $horaire['jeudiSoireFin'] )
				{
					$maxHoraire = $horaire['jeudiSoireFin'];
				}
				if( ($horaire['vendrediSoireFin'] != null) && $maxHoraire < $horaire['vendrediSoireFin'] )
				{
					$maxHoraire = $horaire['vendrediSoireFin'];
				}
				if( ($horaire['samediSoireFin'] != null) && $maxHoraire < $horaire['samediSoireFin'] )
				{
					$maxHoraire = $horaire['samediSoireFin'];
				}
				if( ($horaire['dimancheSoireFin'] != null) &&  $maxHoraire < $horaire['dimancheSoireFin'] )
				{
					$maxHoraire = $horaire['dimancheSoireFin'];
				}

				echo $minHoraire . '-' . $maxHoraire;

			break;

			case 'getActiveHoraires':

				$sql       = "SELECT * FROM horaire WHERE active = 1";
				$horaires  = getData($sql, []);
				echo json_encode($horaires);

			break;

			case 'getActivedHoraires':
				if( isset( $_GET['dateHoraire'] ) && !empty( $_GET['dateHoraire'] ) )
				{
					$dateHoraire = $_GET['dateHoraire'];
					$dayNumber  = date('N', strtotime($dateHoraire));
					$sql = '';

					if( $dayNumber == 1 )
					{
						$sql = "SELECT lundiMatinDebut AS matinDebut, lundiMatinFin AS matinFin, lundiSoireDebut AS soireDebut, lundiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 2 )
					{
						$sql = "SELECT mardiMatinDebut AS matinDebut, mardiMatinFin AS matinFin, mardiSoireDebut AS soireDebut, mardiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 3 )
					{
						$sql = "SELECT mercrediMatinDebut AS matinDebut, mercrediMatinFin AS matinFin, mercrediSoireDebut AS soireDebut, mercrediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 4 )
					{
						$sql = "SELECT jeudiMatinDebut AS matinDebut, jeudiMatinFin AS matinFin, jeudiSoireDebut AS soireDebut, jeudiSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 5 )
					{
						$sql = "SELECT vendrediMatinDebut AS matinDebut, vendrediMatinFin AS matinFin, vendrediSoireDebut AS soireDebut, vendrediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else if( $dayNumber == 6 )
					{
						$sql = "SELECT samediMatinDebut AS matinDebut, samediMatinFin AS matinFin, samediSoireDebut AS soireDebut, samediSoireFin AS soireFin FROM horaire WHERE active = 1";
					}
					else
					{
						$sql = "SELECT dimancheMatinDebut AS matinDebut, dimancheMatinFin AS matinFin, dimancheSoireDebut AS soireDebut, dimancheSoireFin AS soireFin FROM horaire WHERE active = 1";
					}

					$horaire = getData( $sql, [] );

					if( $horaire )
					{
						echo json_encode($horaire);
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo '0';
				}
			break;

			case 'getPaiements':

				if( isset( $_GET['idDossier'] ) && !empty( $_GET['idDossier'] ) && is_numeric( $_GET['idDossier'] ) )
				{
					$idDossier = $_GET['idDossier'];
					$sql       = "SELECT * FROM paiement WHERE idDossier = ?";
					$paiements = getDatas( $sql, [ $idDossier ] );

					if( $paiements )
					{
						echo json_encode( $paiements );
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'getPaiementToday':

				$sql = "SELECT CONCAT('[', Pt.cin,'] - ', Pt.prenom, ' ', Pt.nom, ' / ', D.titreDossier ) AS patient, Pt.idPatient, P.*
						FROM paiement P, dossier D, patient Pt
						WHERE P.idDossier = D.idDossier
						  AND D.idPatient = Pt.idPatient
						  AND DATE_FORMAT(P.datePaiement, '%Y-%m-%d') = CURDATE()";
				$paiements = getDatas( $sql, [] );

				echo empty($paiements) ? json_encode([]) : json_encode($paiements);

				break;

			case 'AddPaiement':

				if( (isset( $_POST['idDossier'] ) && !empty( $_POST['idDossier'] ) && is_numeric( $_POST['idDossier'] )) && (isset( $_POST['montantPaye'] ) && !empty( $_POST['montantPaye'] )) )
				{
					$idDossier   = $_POST['idDossier'];
					$montantPaye = $_POST['montantPaye'];
					$errosAddPaiement = [];

					if( !is_numeric( $montantPaye ) )
					{
						$errosAddPaiement['montantPaye'] = "Le format de champ est incorrect !";
					}

					if( !empty($errosAddPaiement) )
					{
						echo json_encode($errosAddPaiement);
					}
					else
					{
						$sql = "INSERT INTO paiement ( idPaiement, idDossier, datePaiement, montantPaye ) VALUES ( NULL, ?, NOW(), ? ) ";

						if( setData( $sql, [ $idDossier, $montantPaye ] ) )
						{
							echo '1';
						}
						else
						{
							echo '0';
						}
					}
				}
				else
				{
					echo '0';
				}

			break;

			case 'getPaiementDossier':

				if (isset($_GET['idDossier']) && !empty($_GET['idDossier']))
				{
					$idDossier = $_GET['idDossier'];
					$sql       = "SELECT P.*, P.datePaiement AS datePaiement2 FROM paiement P, dossier D WHERE P.idDossier = D.idDossier AND D.idDossier = ? ORDER BY P.datePaiement ASC ";
					$paiements = getDatas( $sql, [ $idDossier ] );

					echo json_encode($paiements);
				}
				else
				{
					echo '0 12';
				}

			break;

			case 'deletePaiementDossier':

			

				if(isset($_GET['idPaiement']))
				{
					$idPaiement = $_GET['idPaiement'];
					$sql     = "DELETE FROM paiement WHERE idPaiement = ?";
					$result  = setData($sql, [ $idPaiement ]);
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
				}
				else
				{
					echo 0;
				}

			break;

			case 'addConsultation':

			

				if (isset($_POST['idDossier']) && !empty($_POST['idDossier']) && is_numeric($_POST['idDossier']))
				{
					$idDossier             = $_POST['idDossier'];
					$typeConsultation      = $_POST['typeConsultation'];
					$remarquesConsultation = $_POST['remarquesConsultation'];
					$contenuOrdonnace      = $_POST['contenuOrdonnance'];
					$diagnistic            = $_POST['diagnostic'];
					$montantConsultation   = $_POST['montantConsultation'];
					$douleurs              = $_POST['douleurs'];
					$symptome              = $_POST['symptome'];
					$cardioVasculaire      = $_POST['cardioVasculaire'];
					$pulomnaires           = $_POST['pulomnaires'];
					$abdominal             = $_POST['abdominal'];
					$seins                 = $_POST['seins'];
					$osteoArtiCulare       = $_POST['osteoArtiCulare'];
					$urogenital            = $_POST['urogenital'];
					$neurologique          = $_POST['neurologique'];
					$orl                   = $_POST['orl'];
					$taille                = $_POST['taille'];
					$poids                 = $_POST['poids'];
					$temperature           = $_POST['temperature'];
					$etatGeneral           = $_POST['etatGeneral'];
					$imc                   = $_POST['imc'];
					$echographie           = $_POST['echographie'];
					$radioStrandard        = $_POST['radioStrandard'];
					$tdm                   = $_POST['tdm'];
					$trm                   = $_POST['trm'];
					$autreBiologie         = $_POST['autreBiologie'];
					$rxPoumon              = $_POST['rxPoumon'];
					$rxRichs               = $_POST['rxRichs'];

					$sql = "INSERT INTO `consultation`(`idConsultation`, `montantNetConsultation`, `dateDebut`, `idDossier`, `typeConsultation`, `contenuOrdonnance`, `diagnostic`, `remarquesConsultation`, `douleurs`, `symptome`, `cardioVasculaire`, `pulomnaires`, `abdominal`, `seins`, `osteoArtiCulare`, `urogenital`, `neurologique`, `orl`, `taille`, `poids`, `temperature`, `etatGeneral`, `imc`, `echographie`, `radioStrandard`, `tdm`, `trm`, `autreBiologie`, `rxPoumon`, `rxRichs`)
							VALUES (NULL, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

					$params = [$montantConsultation, $idDossier , $typeConsultation, $contenuOrdonnace, $diagnistic, $remarquesConsultation, $douleurs, $symptome, $cardioVasculaire, $pulomnaires, $abdominal, $seins, $osteoArtiCulare, $urogenital, $neurologique, $orl, $taille, $poids, $temperature, $etatGeneral, $imc, $echographie, $radioStrandard, $tdm, $trm, $autreBiologie, $rxPoumon, $rxRichs ];

					$result = setData( $sql, $params );
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo 0;
				}

				break;

			case 'updateConsultation':

			

				if (isset($_POST['idConsultation']) && !empty($_POST['idConsultation']) && is_numeric($_POST['idConsultation']))
				{
					$idConsultation        = $_POST['idConsultation'];
					$dateConsultation      = $_POST['dateConsultation'];
					$typeConsultation      = $_POST['typeConsultation'];
					$remarquesConsultation = $_POST['remarquesConsultation'];
					$contenuOrdonnace      = $_POST['contenuOrdonnance'];
					$diagnistic            = $_POST['diagnostic'];
					$montantConsultation   = $_POST['montantConsultation'];
					$douleurs              = $_POST['douleurs'];
					$symptome              = $_POST['symptome'];
					$cardioVasculaire      = $_POST['cardioVasculaire'];
					$pulomnaires           = $_POST['pulomnaires'];
					$abdominal             = $_POST['abdominal'];
					$seins                 = $_POST['seins'];
					$osteoArtiCulare       = $_POST['osteoArtiCulare'];
					$urogenital            = $_POST['urogenital'];
					$neurologique          = $_POST['neurologique'];
					$orl                   = $_POST['orl'];
					$taille                = $_POST['taille'];
					$poids                 = $_POST['poids'];
					$temperature           = $_POST['temperature'];
					$etatGeneral           = $_POST['etatGeneral'];
					$imc                   = $_POST['imc'];
					$echographie           = $_POST['echographie'];
					$radioStrandard        = $_POST['radioStrandard'];
					$tdm                   = $_POST['tdm'];
					$trm                   = $_POST['trm'];
					$autreBiologie         = $_POST['autreBiologie'];
					$rxPoumon              = $_POST['rxPoumon'];
					$rxRichs               = $_POST['rxRichs'];

					$sql = "UPDATE `consultation` SET `montantNetConsultation` = ?, `dateDebut` = ?, `typeConsultation` = ?, `contenuOrdonnance` = ?, `diagnostic` = ?, `remarquesConsultation` = ?, `douleurs` = ?, `symptome` = ?,`cardioVasculaire` = ?, `pulomnaires` = ?, `abdominal` = ?, `seins` = ?, `osteoArtiCulare` = ?, `urogenital` = ?, `neurologique` = ?, `orl` = ?, `taille` = ?, `poids` = ?, `temperature` = ?, `etatGeneral` = ?, `imc` = ?, `echographie` = ?, `radioStrandard` = ?, `tdm` = ?, `trm` = ?, `autreBiologie` = ?, `rxPoumon` = ?, `rxRichs` = ? WHERE idConsultation = ?";

					$params = [$montantConsultation, $dateConsultation, $typeConsultation, $contenuOrdonnace, $diagnistic, $remarquesConsultation, $douleurs, $symptome, $cardioVasculaire, $pulomnaires, $abdominal, $seins, $osteoArtiCulare, $urogenital, $neurologique, $orl, $taille, $poids, $temperature, $etatGeneral, $imc, $echographie, $radioStrandard, $tdm, $trm, $autreBiologie, $rxPoumon, $rxRichs, $idConsultation ];

					$result = setData( $sql, $params );
					if( $result )
					{
						echo 1;
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo 0;
				}

				break;


			default:
				$daynum = date("N", strtotime("2017/11/27"));
			break;
		}

	}

// 1  : bien ajouter
// 0  : ajout a échoué
// -1 : champs invalide

 ?>
