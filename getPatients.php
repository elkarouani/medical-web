<?php

    require_once 'includes/functions/dao.php';

    $sql      = 'SELECT P.idPatient, P.cin, P.nom, P.mutuel, P.prenom, P.sexe, P.dateNaissance, P.tel, P.email, P.adresse FROM patient P';
    $patients = getDatas($sql, []);
    //Cin 	Nom 	Prenom 	Sexe 	Date Naissance 	Telehpne 	Email 	Etat 	Dossiers
//    header('content-type:application/json');
    echo json_encode($patients);
?>