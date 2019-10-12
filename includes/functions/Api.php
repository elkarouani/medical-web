<?php

require_once "dao.php";

class Api {

    public function __construct(){
    }

    /**
     * Permet de retourner tout les rendez vous d'aujourd'hui
     * @return JSON
     */
    public function getRdvToday()
    {
        $sql = "SELECT *
                FROM   rdv
                WHERE  DATE_FORMAT(rdv.dateRdv, '%Y-%m-%d') = CURDATE() ";
        $data = getDatas( $sql, [] );
        $data = ( !empty( $data ) ) ? $data : [];

        return json_encode( $data );
    }

    /**
     * Permet de retourner tout les patient au salle d'attente d'aujourd'hui
     * @return JSON
     */
    public function getSallAttentToday()
    {
        $sql = "SELECT  sd.*,
                        d.*
                FROM    salledattent sd,
                        dossier d
                WHERE   DATE_FORMAT(sd.dateArr, '%Y-%m-%d') = CURDATE()
                AND     sd.idDossier = d.idDossier";
        $data = getDatas( $sql, [] );
        $data = ( !empty( $data ) ) ? $data : [];

        return json_encode( $data );
    }

    /**
     * Permet de retourner tout les patient enregestrer
     * @return JSON
     */
    public function getAllpatients()
    {
        $sql = "SELECT  p.nom,
                        p.prenom,
                        p.cin,
                        p.dateNaissance,
                        p.sexe
                FROM patient p";
        $data = getDatas( $sql, [] );
        $data = ( !empty( $data ) ) ? $data : [];

        return json_encode( $data );
    }

    /**
     * Permet de retourner tout les patient au enregestrer
     * @return JSON
     */
    public function getAllRdv()
    {
        $sql = "SELECT  r.*,
                        d.titreDossier,
                        d.idDossier,
                        t.libelle,
                        CONCAT( '[ ', pt.cin, ' ] ', pt.prenom, ' ', pt.nom ) AS patient
                FROM    rdv r,
                        dossier d,
                        typerdv t,
                        patient pt
                WHERE   r.idDossier = d.idDossier
                AND     d.idPatient = pt.idPatient
                AND     t.idType = r.typeRdv";
        $data = getDatas( $sql, [] );
        $data = ( !empty( $data ) ) ? $data : [];

        return json_encode( $data );
    }

    /**
     * @param $dateDebut dateTime
     * @param $dateFin dateTime
     * @return JSON
     */
    public function getRdvIntervalDate( $dateDebut, $dateFin )
    {
        $dateDebut = ( $dateFin < $dateDebut ) ? $dateFin : $dateDebut;

        $sql = "SELECT  r.*
                FROM    rdv r
                WHERE   r.dateRdv BETWEEN ( ?, ? )";
        $data = getDatas( $sql, [ $dateDebut, $dateFin ] );
        $data = ( !empty( $data ) ) ? $data : NULL;

        return json_encode( $data );
    }

    /**
     * Permet de retourner tout les consultations efféctuer aujourd'hui
     * @return JSON
     */
    public function getConsultationsToday()
    {
        $sql = "SELECT  c.idConsultation,
                        c.montantNetConsultation,
                        c.remarquesConsultation,
                        t.libelle,
                        d.titreDossier,
                        d.idDossier,
                        CONCAT( '[ ', pt.cin, ' ] ', pt.prenom, ' ', pt.nom ) AS patient
                FROM    consultation c,
                        dossier d,
                        patient pt,
                        typerdv t
                WHERE   DATE_FORMAT(c.dateDebut, '%Y-%m-%d') = CURDATE()
                AND     d.idDossier = c.idDossier
                AND     c.typeConsultation = t.idType
                AND     d.idPatient = d.idPatient";
        $data = getDatas( $sql, [] );
        $data = ( !empty( $data ) ) ? $data : [];

        return json_encode( $data );
    }

    /**
     * Permet de retourner tout les consultations efféctuer aujourd'hui
     * @return JSON
     */
    public function getPaiementsToday()
    {
        $sql = "SELECT  CONCAT( '[ ', pt.cin, ' ] ', pt.prenom, ' ', pt.nom ) AS patient,
                        d.titreDossier,
                        d.idDossier,
                        p.montantPaye,
                        p.type
                FROM    paiement p,
                        dossier d,
                        patient pt
                WHERE   DATE_FORMAT(p.datePaiement, '%Y-%m-%d') = CURDATE()
                AND     p.idDossier = d.idDossier
                AND     d.idPatient = pt.idPatient";

        $data = getDatas( $sql, [] );
        $data = ( !empty( $data ) ) ? $data : [];

        return json_encode( $data );
    }

    /**
     * @param $login string
     * @param $password string
     * @return JSON
     */
    public function loginAdmin( $login, $password )
    {
        $sql = "SELECT * FROM admin WHERE login = ? AND pass = ? LIMIT 1";

        $data = getData( $sql, [ $login, $password ] );
        $data = ( !empty( $data ) ) ? $data : NULL;

        return json_encode( $data );
    }

}