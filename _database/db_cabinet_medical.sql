-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Sam 17 Février 2018 à 00:33
-- Version du serveur :  10.1.21-MariaDB
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `db_cabinet_medical`
--

DELIMITER $$
--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `F_ETAT_RDV` (`ID_RDV` INT) RETURNS CHAR(250) CHARSET latin1 BEGIN
    DECLARE COLOR_RDV CHAR(250);
    DECLARE ETAT_RDV CHAR(250);
    DECLARE EXPIRER TINYINT(1) DEFAULT 0;
    DECLARE EN_ATTENT TINYINT(1) DEFAULT 0;
    DECLARE ANULLER TINYINT(1) DEFAULT 0;
    DECLARE VALIDE TINYINT(1) DEFAULT 0;
    DECLARE ACTIVE TINYINT(1) DEFAULT 0;
    DECLARE DATE_RDV DATETIME;

    SELECT rdv.Etat INTO ETAT_RDV FROM rdv WHERE idRdv = ID_RDV;
    SELECT rdv.dateRdv INTO DATE_RDV FROM rdv WHERE idRdv = ID_RDV;

    SET EXPIRER = DATE_RDV < NOW();
    SET EN_ATTENT = DATE_RDV >= NOW();

    IF ( ETAT_RDV = 'a' ) THEN
        
        SET ACTIVE = 1;

    ELSEIF ( ETAT_RDV = 'd' ) THEN

        SET ANULLER = 1;
        
    ELSEIF ( ETAT_RDV = 'v' ) THEN

        SET VALIDE = 1;
        
    ELSEIF ( ETAT_RDV = 'd' ) THEN

        SET ANULLER = 1;
    
    END IF;

    IF ( ACTIVE AND EXPIRER ) THEN

        SET COLOR_RDV = '#f39c12';

    ELSEIF ( ACTIVE AND EN_ATTENT ) THEN

        SET COLOR_RDV = '#2474f6';

    ELSEIF ( ANULLER AND EN_ATTENT ) THEN

        SET COLOR_RDV = '#d64634';

    ELSEIF ( VALIDE ) THEN

        SET COLOR_RDV = '#35aa47';
        
    ELSEIF ( EXPIRER ) THEN

        SET COLOR_RDV = '#f39c12';

    END IF;

    RETURN COLOR_RDV;

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `F_ETAT_RDVBB` (`ID_RDV` INT) RETURNS CHAR(250) CHARSET latin1 BEGIN
    DECLARE COLOR_RDV CHAR(250);
    DECLARE ETAT_RDV CHAR(250);
    DECLARE EXPIRER TINYINT(1) DEFAULT 0;
    DECLARE EN_ATTENT TINYINT(1) DEFAULT 0;
    DECLARE ANULLER TINYINT(1) DEFAULT 0;
    DECLARE VALIDE TINYINT(1) DEFAULT 0;
    DECLARE ACTIVE TINYINT(1) DEFAULT 0;
    DECLARE DATE_RDV DATETIME;

    SELECT rdv.Etat INTO ETAT_RDV FROM rdv WHERE idRdv = ID_RDV;
    SELECT rdv.dateRdv INTO DATE_RDV FROM rdv WHERE idRdv = ID_RDV;

    SET EXPIRER = DATE_RDV < NOW();
    SET EN_ATTENT = DATE_RDV >= NOW();

    IF ( ETAT_RDV = 'a' ) THEN
        
        SET ACTIVE = 1;

    ELSEIF ( ETAT_RDV = 'd' ) THEN

        SET ANULLER = 1;
        
    ELSEIF ( ETAT_RDV = 'v' ) THEN

        SET VALIDE = 1;
        
    ELSEIF ( ETAT_RDV = 'd' ) THEN

        SET ANULLER = 1;
	
    ELSE 
		SET ACTIVE = 0;
    
    END IF;

    IF ( ACTIVE AND EXPIRER ) THEN

        SET COLOR_RDV = '#ffcd43';

    ELSEIF ( ACTIVE AND EN_ATTENT ) THEN

        SET COLOR_RDV = '#007acc';

    ELSEIF ( ANULLER AND EN_ATTENT ) THEN

        SET COLOR_RDV = '#F00';

    ELSEIF ( VALIDE ) THEN

        SET COLOR_RDV = '#17a15e';
        
    ELSEIF ( EXPIRER ) THEN

        SET COLOR_RDV = '#ffcd43';

    END IF;

    RETURN COLOR_RDV;

END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `mouad` (`idDruzyny` INT) RETURNS VARCHAR(11) CHARSET latin1 BEGIN
/*
DECLARE bramek INT;
DECLARE tmp1 INT;
DECLARE tmp2 INT;

SELECT DISTINCT COALESCE(SUM( b.bramki ), 0) INTO tmp1
FROM LigaBramki b
INNER JOIN LigaZawodnicy z ON b.idZawodnika = z.idZawodnika
INNER JOIN LigaDruzyny d ON z.idDruzyny = d.idDruzyny
INNER JOIN LigaMecze m ON b.idMeczu = m.idMeczu
WHERE m.idDruzyny2 = idDruzyny
AND z.idDruzyny != idDruzyny
AND d.idDruzyny != idDruzyny
AND m.rozegrany = '1';

SELECT DISTINCT COALESCE(SUM( b.bramki ), 0) INTO tmp2
FROM LigaBramki b
INNER JOIN LigaZawodnicy z ON b.idZawodnika = z.idZawodnika
INNER JOIN LigaDruzyny d ON z.idDruzyny = d.idDruzyny
INNER JOIN LigaMecze m ON b.idMeczu = m.idMeczu
WHERE m.idDruzyny1 = idDruzyny
AND z.idDruzyny != idDruzyny
AND d.idDruzyny != idDruzyny
AND m.rozegrany = '1';

SET bramek = tmp1 + tmp2;*/

RETURN 'mouad';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `admin`
--

INSERT INTO `admin` (`idAdmin`, `nom`, `prenom`, `sexe`, `tel`, `email`, `login`, `pass`, `active`, `image`) VALUES
(1, 'ZIANI', 'Mouad', 'H', '0123456789', 'mouad.ziani@gmail.com', 'admin', '123456', 1, '1_avatar.png'),
(2, 'NAIM', 'Issmail', 'H', '0123456789', 'Null', 'admin2', '4607e782c4d86fd5364d7e4508bb10d9', 1, '2_user8-128x128.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `consultation`
--

CREATE TABLE `consultation` (
  `idConsultation` int(11) NOT NULL,
  `montantNetConsultation` float NOT NULL DEFAULT '0',
  `dateDebut` datetime NOT NULL,
  `idDossier` int(11) NOT NULL,
  `typeConsultation` varchar(500) NOT NULL,
  `remarquesConsultation` varchar(500) NOT NULL,
  `douleurs` varchar(500) NOT NULL,
  `symptome` varchar(500) NOT NULL,
  `cardioVasculaire` varchar(500) NOT NULL,
  `pulomnaires` varchar(500) NOT NULL,
  `abdominal` varchar(500) NOT NULL,
  `seins` varchar(500) NOT NULL,
  `osteoArtiCulare` varchar(500) NOT NULL,
  `urogenital` varchar(500) NOT NULL,
  `neurologique` varchar(500) NOT NULL,
  `orl` varchar(500) NOT NULL,
  `taille` float NOT NULL,
  `poids` float NOT NULL,
  `temperature` float NOT NULL,
  `etatGeneral` varchar(500) NOT NULL,
  `imc` varchar(500) NOT NULL,
  `echographie` varchar(500) NOT NULL,
  `radioStrandard` varchar(500) NOT NULL,
  `tdm` varchar(500) NOT NULL,
  `trm` varchar(500) NOT NULL,
  `autreBiologie` varchar(500) NOT NULL,
  `rxPoumon` text NOT NULL,
  `rxRichs` varchar(500) NOT NULL,
  `contenuOrdonnance` longtext NOT NULL,
  `diagnostic` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `consultation`
--

INSERT INTO `consultation` (`idConsultation`, `montantNetConsultation`, `dateDebut`, `idDossier`, `typeConsultation`, `remarquesConsultation`, `douleurs`, `symptome`, `cardioVasculaire`, `pulomnaires`, `abdominal`, `seins`, `osteoArtiCulare`, `urogenital`, `neurologique`, `orl`, `taille`, `poids`, `temperature`, `etatGeneral`, `imc`, `echographie`, `radioStrandard`, `tdm`, `trm`, `autreBiologie`, `rxPoumon`, `rxRichs`, `contenuOrdonnance`, `diagnostic`) VALUES
(12, 0, '2018-01-01 16:50:38', 55, '522', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', ''),
(13, 500, '2018-01-01 17:55:13', 57, '522', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', ''),
(14, 0, '2018-04-11 20:16:11', 54, '522', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 'Artotec 5 mg Comprimer  -  -  par jour x  jour(s)\r\nArtotec 5 mg Comprimer  -  -  par jour x  jour(s)\r\nArtotec 5 mg Comprimer  -  -  par jour x  jour(s)\r\n', ''),
(15, 100, '2018-01-01 21:07:39', 54, '522', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', ''),
(19, 600, '2018-01-04 11:49:38', 64, '522', 'Type de consultation ', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 'Amoxile 1G Comprimer 1 - 1 - 0 par jour x 8 jour(s)\r\nmanef 90mg Cp Efferviscent 1 - 0 - 0 par jour x 30 jour(s)\r\nSystral 2% Pommade 0 - 0 - 1 par jour x 1 mois\r\nArtotec 5 mg Comprimer 1 - 0 - 0 par jour x 7 mois\r\n', 'Diagnostic'),
(20, 500, '2018-01-04 13:01:06', 54, '522', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', ''),
(22, 300, '2018-01-05 13:15:25', 55, '523', 'Remarques ', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 'Amoxile 1G Comprimer 1 - 0 - 1 par jour x 15 jour(s)\r\nAnafragile 50mg Comprimer 1/2 - 0 - 1/2 par jour x 6 mois\r\nDoliprane 1G Cp Efferviscent 1 - 1 - 1 par jour x 8 jour(s)\r\n', 'Diagnostic'),
(23, 1200, '2018-01-05 13:17:39', 64, '523', 'Remarques ', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', ''),
(24, 0, '2018-01-16 03:02:25', 55, '522', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 'Amoxile 1G Comprimer 1 - 1 - 1 par jour x 15 jour(s)\r\nArtotec 5 mg Comprimer 1 - 0 - 1 par jour x 5 jour(s)\r\nInongan 5% Pommade 0 - 0 - 1 par jour x 2 semain(s)\r\n', ''),
(25, 200, '2018-01-31 15:05:17', 67, '523', 'Remarques ', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 'Amoxile 1G Comprimer 1 - 0 - 1 par jour x 8 jour(s)\r\nSystral 2% Pommade 0 - 0 - 1 par jour x 2 semain(s)\r\nDoliprane 1G Cp Efferviscent 1 - 1 - 1 par jour x 4 jour(s)\r\nmanef 90mg Cp Efferviscent 1 - 0 - 0 par jour x 1 mois\r\n', '');

--
-- Déclencheurs `consultation`
--
DELIMITER $$
CREATE TRIGGER `Tr_calc_montant_dossier` AFTER INSERT ON `consultation` FOR EACH ROW BEGIN
DECLARE mtDossier FLOAT;
SELECT SUM(consultation.montantNetConsultation) INTO mtDossier FROM consultation WHERE consultation.idDossier = NEW.idDossier;UPDATE dossier SET dossier.montantDossier = mtDossier WHERE dossier.idDossier = NEW.idDossier;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Tr_calc_montant_dossier_delete` AFTER DELETE ON `consultation` FOR EACH ROW BEGIN
DECLARE mtDossier FLOAT;
SELECT SUM(consultation.montantNetConsultation) INTO mtDossier FROM consultation WHERE consultation.idDossier = OLD.idDossier;
UPDATE dossier SET dossier.montantDossier = mtDossier WHERE dossier.idDossier = OLD.idDossier;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Tr_calc_montant_dossier_update` AFTER UPDATE ON `consultation` FOR EACH ROW BEGIN
DECLARE mtNewDossier FLOAT;
DECLARE mtOldDossier FLOAT;
SELECT SUM(consultation.montantNetConsultation) INTO mtNewDossier FROM consultation WHERE consultation.idDossier = NEW.idDossier;
SELECT SUM(consultation.montantNetConsultation) INTO mtOldDossier FROM consultation WHERE consultation.idDossier = OLD.idDossier;
UPDATE dossier SET dossier.montantDossier = mtNewDossier WHERE dossier.idDossier = NEW.idDossier;
UPDATE dossier SET dossier.montantDossier = mtOldDossier WHERE dossier.idDossier = OLD.idDossier;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `dossier`
--

CREATE TABLE `dossier` (
  `idDossier` int(11) NOT NULL,
  `idPatient` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `montantDossier` float NOT NULL DEFAULT '0',
  `titreDossier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `dossier`
--

INSERT INTO `dossier` (`idDossier`, `idPatient`, `dateCreation`, `montantDossier`, `titreDossier`) VALUES
(54, 132, '2017-12-31 14:33:43', 600, ''),
(55, 132, '2017-06-06 14:40:55', 300, ''),
(57, 134, '2018-01-01 17:02:59', 500, ''),
(60, 132, '2018-01-03 00:10:02', 0, ''),
(61, 134, '2018-01-03 00:11:28', 0, ''),
(63, 136, '2018-04-17 11:35:04', 0, ''),
(64, 137, '2018-01-04 11:46:10', 1800, ''),
(67, 137, '2018-01-05 16:13:48', 200, 'ABBBBB'),
(68, 137, '2018-01-05 16:15:00', 0, 'mmmm'),
(69, 137, '2018-01-05 17:16:54', 0, 'Mouad'),
(70, 137, '2018-01-05 17:17:54', 0, 'tttttttttttttttt'),
(71, 134, '2018-01-05 17:49:09', 0, 'pppppp'),
(73, 137, '2018-01-05 17:53:26', 0, 'pppp'),
(74, 137, '2018-01-05 17:53:50', 0, 'ppopopo'),
(75, 137, '2018-01-05 18:12:38', 0, 'DBD001'),
(76, 137, '2018-01-05 18:26:11', 0, 'abdlll');

-- --------------------------------------------------------

--
-- Structure de la table `famillemedicament`
--

CREATE TABLE `famillemedicament` (
  `idfamille` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `famillemedicament`
--

INSERT INTO `famillemedicament` (`idfamille`, `libelle`) VALUES
(1, 'Comprimer'),
(2, 'Sirop'),
(3, 'Cp Efferviscent'),
(4, 'Gelulle'),
(5, 'Pommade'),
(6, 'Gouttes');

-- --------------------------------------------------------

--
-- Structure de la table `gestionaire`
--

CREATE TABLE `gestionaire` (
  `idGestionaire` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `adresse` varchar(500) NOT NULL,
  `image` varchar(50) NOT NULL,
  `login` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `idRole` int(11) NOT NULL,
  `sexe` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `gestionaire`
--

INSERT INTO `gestionaire` (`idGestionaire`, `nom`, `prenom`, `tel`, `email`, `adresse`, `image`, `login`, `pass`, `active`, `idRole`, `sexe`) VALUES
(7, 'OUNASSER', 'Layla', '0123456789', 'Null', 'lorem lorem lorem lorem', 'OUNASSER_77.jpg', 'OUNASSER', 'e10adc3949ba59abbe56e057f20f883e', 1, 0, 'H');

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `idHoraire` int(11) NOT NULL,
  `lundiMatinDebut` time DEFAULT NULL,
  `lundiSoireDebut` time DEFAULT NULL,
  `mardiMatinDebut` time DEFAULT NULL,
  `mardiSoireDebut` time DEFAULT NULL,
  `mercrediMatinDebut` time DEFAULT NULL,
  `mercrediSoireDebut` time DEFAULT NULL,
  `jeudiMatinDebut` time DEFAULT NULL,
  `jeudiSoireDebut` time DEFAULT NULL,
  `vendrediMatinDebut` time DEFAULT NULL,
  `vendrediSoireDebut` time DEFAULT NULL,
  `samediMatinDebut` time DEFAULT NULL,
  `samediSoireDebut` time DEFAULT NULL,
  `dimancheMatinDebut` time DEFAULT NULL,
  `dimancheSoireDebut` time DEFAULT NULL,
  `nomHoraire` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `descriptionHoriare` varchar(500) DEFAULT NULL,
  `lundiMatinFin` time DEFAULT NULL,
  `lundiSoireFin` time DEFAULT NULL,
  `mardiMatinFin` time DEFAULT NULL,
  `mardiSoireFin` time DEFAULT NULL,
  `mercrediMatinFin` time DEFAULT NULL,
  `mercrediSoireFin` time DEFAULT NULL,
  `jeudiMatinFin` time DEFAULT NULL,
  `jeudiSoireFin` time DEFAULT NULL,
  `vendrediMatinFin` time DEFAULT NULL,
  `vendrediSoireFin` time DEFAULT NULL,
  `samediMatinFin` time DEFAULT NULL,
  `samediSoireFin` time DEFAULT NULL,
  `dimancheMatinFin` time DEFAULT NULL,
  `dimancheSoireFin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `horaire`
--

INSERT INTO `horaire` (`idHoraire`, `lundiMatinDebut`, `lundiSoireDebut`, `mardiMatinDebut`, `mardiSoireDebut`, `mercrediMatinDebut`, `mercrediSoireDebut`, `jeudiMatinDebut`, `jeudiSoireDebut`, `vendrediMatinDebut`, `vendrediSoireDebut`, `samediMatinDebut`, `samediSoireDebut`, `dimancheMatinDebut`, `dimancheSoireDebut`, `nomHoraire`, `active`, `descriptionHoriare`, `lundiMatinFin`, `lundiSoireFin`, `mardiMatinFin`, `mardiSoireFin`, `mercrediMatinFin`, `mercrediSoireFin`, `jeudiMatinFin`, `jeudiSoireFin`, `vendrediMatinFin`, `vendrediSoireFin`, `samediMatinFin`, `samediSoireFin`, `dimancheMatinFin`, `dimancheSoireFin`) VALUES
(27, '08:00:00', '14:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', 'Alias d\'horaire', 0, '', '12:00:00', '19:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00'),
(29, '07:00:00', '14:00:00', '07:00:00', '13:00:00', '07:00:00', '14:30:00', '07:30:00', '14:00:00', '08:15:00', '14:30:00', NULL, NULL, NULL, NULL, 'Horaire par default', 1, 'horiare de travail par default', '12:00:00', '20:00:00', '12:00:00', '20:55:00', '13:00:00', '20:55:00', '12:30:00', '19:30:00', '12:00:00', '20:00:00', NULL, NULL, NULL, NULL),
(30, '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', 'horaire de ramadan', 0, 'horaire de ramadan horaire de ramadan', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00'),
(31, '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', 'Alias d\'horaire', 0, '', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00'),
(32, '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', 'Alias d\'horaire', 0, '', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `medicament`
--

CREATE TABLE `medicament` (
  `idMedicament` int(11) NOT NULL,
  `idFamilleMedicament` int(11) NOT NULL,
  `designation` varchar(500) NOT NULL,
  `dosage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `medicament`
--

INSERT INTO `medicament` (`idMedicament`, `idFamilleMedicament`, `designation`, `dosage`) VALUES
(1, 1, 'Amoxile', '1G'),
(2, 3, 'Doliprane', '1G'),
(3, 5, 'Systral', '2%'),
(8, 1, 'AUGMENTIN', '1 G / 125 MG'),
(12, 1, 'AZIX ', '300 MG'),
(13, 1, 'cataflam', '50'),
(14, 1, 'Stilnox', '5mg'),
(15, 3, 'Aspro', '250mg'),
(16, 1, 'amoxile3', '50'),
(20, 1, 'Xanax', '2mg'),
(22, 5, 'Inongan', '5%'),
(23, 1, 'Anafragile', '50mg'),
(24, 3, 'manef', '90mg');

-- --------------------------------------------------------

--
-- Structure de la table `mutuel`
--

CREATE TABLE `mutuel` (
  `idMutuel` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `mutuel`
--

INSERT INTO `mutuel` (`idMutuel`, `libelle`) VALUES
(1, 'SANS'),
(2, 'CNOPS'),
(3, 'SAHAM'),
(4, 'AXA'),
(5, 'skdrfgksf'),
(6, 'sdfllsgf'),
(7, 'mmmmmmmm'),
(8, 'momomo'),
(9, 'pppppp'),
(10, 'wdsfllwsfg'),
(11, 'sdklglsdflgllslgflsdffff'),
(12, 'mmmmmm'),
(13, 'cnss'),
(14, 'SINYA'),
(15, 'nfghjfhjfhj'),
(16, 'lllllllllllllllllllllllllll'),
(17, 'bbbbb'),
(18, 'ccccc'),
(19, 'mouadZIANI'),
(20, 'CNOPS22'),
(21, 'MMM'),
(22, 'POP'),
(23, 'plom'),
(24, 'Test2'),
(25, 'mpmp'),
(26, 'CNOPS33'),
(27, 'mpmpmp'),
(28, 'loooooook'),
(29, 'aaaaaaaaaaaaaaa'),
(30, 'FAR'),
(31, 'mpmpmoo'),
(32, 'FAR2'),
(33, 'CNOPS2'),
(34, 'sqfdjj'),
(35, 'FFFFF');

-- --------------------------------------------------------

--
-- Structure de la table `ordonnance`
--

CREATE TABLE `ordonnance` (
  `idOrdonnance` int(11) NOT NULL,
  `idConsultation` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `contenu` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `idPaiement` int(11) NOT NULL,
  `idDossier` int(11) NOT NULL,
  `datePaiement` datetime NOT NULL,
  `montantPaye` float NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `motif` varchar(255) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `paiement`
--

INSERT INTO `paiement` (`idPaiement`, `idDossier`, `datePaiement`, `montantPaye`, `etat`, `motif`, `type`) VALUES
(1, 54, '2018-01-01 21:07:58', 50, 0, '', 0),
(11, 57, '2018-01-02 13:24:27', 400, 0, '', 0),
(20, 57, '2018-01-03 01:18:18', 100, 0, '', 0),
(24, 55, '2018-01-05 14:42:06', 200, 0, '', 0),
(25, 64, '2018-01-05 14:42:48', 1700, 0, '', 0),
(26, 67, '2018-01-31 15:05:31', 100, 0, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `parametrage`
--

CREATE TABLE `parametrage` (
  `idParam` int(11) NOT NULL,
  `dureeRdv` time NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `parametrage`
--

INSERT INTO `parametrage` (`idParam`, `dureeRdv`, `etat`) VALUES
(1, '00:30:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

CREATE TABLE `patient` (
  `idPatient` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `dateNaissance` date NOT NULL,
  `adresse` varchar(500) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cin` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `mutuel` int(11) DEFAULT NULL,
  `rhiniteAllergique` varchar(255) DEFAULT NULL,
  `cardiopathie` varchar(255) DEFAULT NULL,
  `autresFamiliaux` varchar(255) DEFAULT NULL,
  `hta` varchar(255) DEFAULT NULL,
  `allergies` varchar(255) DEFAULT NULL,
  `tabac` varchar(255) DEFAULT NULL,
  `autreshabitudeAlcooloTabagique` varchar(255) DEFAULT NULL,
  `appendicectomie` varchar(255) DEFAULT NULL,
  `cholecystectomie` varchar(255) DEFAULT NULL,
  `autresChirurgicauxComplication` varchar(255) DEFAULT NULL,
  `autresMedicaux` varchar(255) DEFAULT NULL,
  `dateajout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `patient`
--

INSERT INTO `patient` (`idPatient`, `nom`, `prenom`, `sexe`, `dateNaissance`, `adresse`, `tel`, `email`, `cin`, `active`, `mutuel`, `rhiniteAllergique`, `cardiopathie`, `autresFamiliaux`, `hta`, `allergies`, `tabac`, `autreshabitudeAlcooloTabagique`, `appendicectomie`, `cholecystectomie`, `autresChirurgicauxComplication`, `autresMedicaux`, `dateajout`) VALUES
(134, 'ALAMI', 'Ahmed', 'H', '2007-07-03', 'lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem', '0123456789', 'ahmed@gmail.com', 'HH123456', 0, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(135, 'AHMADI', 'Issam', 'H', '2016-10-06', 'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm', '0123456789', '', 'KK123456', 0, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(136, 'ALAOUI', 'Rachid', 'H', '1989-01-31', 'mmmmmmmmmmmmmmmmmmmmmm', '0123456789', 'Null', 'OO123456', 1, 2, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00'),
(137, 'BENDAOUD', 'Abdelmounaim', 'H', '1967-07-18', 'lorem lorem lorem lorem lorem lorem lorem lorem', '0123456789', 'Null', 'BB123456', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00');

--
-- Déclencheurs `patient`
--
DELIMITER $$
CREATE TRIGGER `TR_ADD_DOSSIER` AFTER INSERT ON `patient` FOR EACH ROW INSERT INTO dossier ( idDossier, idPatient, dateCreation ) VALUES ( NULL, new.idPatient, NOW() )
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `rdv`
--

CREATE TABLE `rdv` (
  `idRdv` int(11) NOT NULL,
  `idDossier` int(11) NOT NULL,
  `dateRdv` datetime NOT NULL,
  `Etat` varchar(1) NOT NULL DEFAULT 'a',
  `dateFin` datetime NOT NULL,
  `dateValidation` datetime DEFAULT NULL,
  `typeRdv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `rdv`
--

INSERT INTO `rdv` (`idRdv`, `idDossier`, `dateRdv`, `Etat`, `dateFin`, `dateValidation`, `typeRdv`) VALUES
(37, 71, '2018-01-02 11:00:00', 'v', '2018-01-02 11:30:00', '2018-01-05 17:58:40', 522),
(38, 71, '2018-01-04 08:00:00', 'd', '2018-01-04 08:30:00', '0000-00-00 00:00:00', 522),
(40, 61, '2017-07-27 10:00:00', 'v', '2017-07-27 11:00:00', NULL, 522),
(41, 71, '2018-01-05 11:00:00', 'v', '2018-01-05 11:30:00', '2018-01-31 14:59:35', 523),
(42, 64, '2018-01-05 11:30:00', 'd', '2018-01-05 12:00:00', NULL, 522),
(44, 63, '2018-01-05 17:30:00', 'v', '2018-01-05 18:00:00', NULL, 522),
(45, 67, '2018-01-31 15:00:00', 'a', '2018-01-31 15:30:00', NULL, 522),
(46, 67, '2018-02-01 08:30:00', 'a', '2018-02-01 09:00:00', NULL, 522);

--
-- Déclencheurs `rdv`
--
DELIMITER $$
CREATE TRIGGER `TR_CALC_DATE_FIN_RDV` BEFORE INSERT ON `rdv` FOR EACH ROW BEGIN
    DECLARE DUREE FLOAT;
	
    SELECT (TIME_TO_SEC(p.dureeRdv) / 60) INTO DUREE
    FROM parametrage p WHERE p.etat = 1;
    
    SET NEW.dateFin = DATE_ADD(NEW.dateRdv , INTERVAL DUREE MINUTE);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TR_CALC_DATE_FIN_RDV_UPDATE` BEFORE UPDATE ON `rdv` FOR EACH ROW BEGIN
    DECLARE DUREE FLOAT;
	
    SELECT (TIME_TO_SEC(p.dureeRdv) / 60) INTO DUREE
    FROM parametrage p WHERE p.etat = 1;
    
    SET NEW.dateFin = DATE_ADD(NEW.dateRdv , INTERVAL DUREE MINUTE);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `salledattent`
--

CREATE TABLE `salledattent` (
  `idSalleDAttent` int(11) NOT NULL,
  `dateArr` datetime NOT NULL,
  `idDossier` int(11) NOT NULL,
  `etat` tinyint(4) NOT NULL DEFAULT '0',
  `idRdv` int(11) DEFAULT NULL,
  `idType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `salledattent`
--

INSERT INTO `salledattent` (`idSalleDAttent`, `dateArr`, `idDossier`, `etat`, `idRdv`, `idType`) VALUES
(96, '2018-01-01 14:51:52', 55, 1, 3, 522),
(120, '2018-01-03 09:40:27', 57, 0, NULL, 522),
(121, '2018-01-03 09:40:51', 60, 1, NULL, 522),
(122, '2018-01-02 11:12:24', 61, 0, 37, 522),
(123, '2018-01-02 11:17:32', 61, 0, NULL, 522),
(125, '2018-01-04 11:46:28', 64, 1, NULL, 522),
(127, '2018-01-05 14:52:50', 64, 0, 41, 522),
(128, '2018-01-05 16:24:51', 67, 0, NULL, 522),
(129, '2018-01-05 16:47:17', 54, 0, NULL, 522),
(130, '2018-01-05 18:03:22', 63, 0, 44, 522),
(131, '2018-01-05 18:14:12', 75, 0, NULL, 522),
(133, '2018-01-31 15:01:51', 67, 1, NULL, 523);

-- --------------------------------------------------------

--
-- Structure de la table `typepaiement`
--

CREATE TABLE `typepaiement` (
  `idType` int(11) NOT NULL,
  `nomType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `typerdv`
--

CREATE TABLE `typerdv` (
  `idType` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `typerdv`
--

INSERT INTO `typerdv` (`idType`, `libelle`) VALUES
(522, 'Consultation'),
(523, 'Controle');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Index pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`idConsultation`),
  ADD KEY `idDossier` (`idDossier`),
  ADD KEY `montantNetConsultation` (`montantNetConsultation`);

--
-- Index pour la table `dossier`
--
ALTER TABLE `dossier`
  ADD PRIMARY KEY (`idDossier`),
  ADD KEY `idPatient` (`idPatient`);

--
-- Index pour la table `famillemedicament`
--
ALTER TABLE `famillemedicament`
  ADD PRIMARY KEY (`idfamille`);

--
-- Index pour la table `gestionaire`
--
ALTER TABLE `gestionaire`
  ADD PRIMARY KEY (`idGestionaire`),
  ADD KEY `idRole` (`idRole`),
  ADD KEY `idRole_2` (`idRole`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`idHoraire`);

--
-- Index pour la table `medicament`
--
ALTER TABLE `medicament`
  ADD PRIMARY KEY (`idMedicament`),
  ADD KEY `idFamilleMedicament` (`idFamilleMedicament`);

--
-- Index pour la table `mutuel`
--
ALTER TABLE `mutuel`
  ADD PRIMARY KEY (`idMutuel`);

--
-- Index pour la table `ordonnance`
--
ALTER TABLE `ordonnance`
  ADD PRIMARY KEY (`idOrdonnance`),
  ADD KEY `ordonnance_ibfk_1` (`idConsultation`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`idPaiement`),
  ADD KEY `idDossier` (`idDossier`),
  ADD KEY `type` (`type`),
  ADD KEY `type_2` (`type`);

--
-- Index pour la table `parametrage`
--
ALTER TABLE `parametrage`
  ADD PRIMARY KEY (`idParam`);

--
-- Index pour la table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`idPatient`),
  ADD KEY `mutuel` (`mutuel`);

--
-- Index pour la table `rdv`
--
ALTER TABLE `rdv`
  ADD PRIMARY KEY (`idRdv`),
  ADD KEY `idRdv` (`idRdv`),
  ADD KEY `idDossier` (`idDossier`),
  ADD KEY `typeRdv` (`typeRdv`),
  ADD KEY `typeRdv_2` (`typeRdv`),
  ADD KEY `typeRdv_3` (`typeRdv`);

--
-- Index pour la table `salledattent`
--
ALTER TABLE `salledattent`
  ADD PRIMARY KEY (`idSalleDAttent`),
  ADD KEY `idDossier` (`idDossier`),
  ADD KEY `idDossier_2` (`idDossier`),
  ADD KEY `idDossier_3` (`idDossier`),
  ADD KEY `idDossier_4` (`idDossier`),
  ADD KEY `idDossier_5` (`idDossier`),
  ADD KEY `idDossier_6` (`idDossier`);

--
-- Index pour la table `typerdv`
--
ALTER TABLE `typerdv`
  ADD PRIMARY KEY (`idType`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `idConsultation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `dossier`
--
ALTER TABLE `dossier`
  MODIFY `idDossier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT pour la table `famillemedicament`
--
ALTER TABLE `famillemedicament`
  MODIFY `idfamille` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `gestionaire`
--
ALTER TABLE `gestionaire`
  MODIFY `idGestionaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `idHoraire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pour la table `medicament`
--
ALTER TABLE `medicament`
  MODIFY `idMedicament` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT pour la table `mutuel`
--
ALTER TABLE `mutuel`
  MODIFY `idMutuel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT pour la table `ordonnance`
--
ALTER TABLE `ordonnance`
  MODIFY `idOrdonnance` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `idPaiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT pour la table `parametrage`
--
ALTER TABLE `parametrage`
  MODIFY `idParam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `patient`
--
ALTER TABLE `patient`
  MODIFY `idPatient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;
--
-- AUTO_INCREMENT pour la table `rdv`
--
ALTER TABLE `rdv`
  MODIFY `idRdv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT pour la table `salledattent`
--
ALTER TABLE `salledattent`
  MODIFY `idSalleDAttent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT pour la table `typerdv`
--
ALTER TABLE `typerdv`
  MODIFY `idType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=524;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`idDossier`) REFERENCES `dossier` (`idDossier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dossier`
--
ALTER TABLE `dossier`
  ADD CONSTRAINT `dossier_ibfk_1` FOREIGN KEY (`idPatient`) REFERENCES `patient` (`idPatient`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `medicament`
--
ALTER TABLE `medicament`
  ADD CONSTRAINT `medicament_ibfk_1` FOREIGN KEY (`idFamilleMedicament`) REFERENCES `famillemedicament` (`idfamille`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ordonnance`
--
ALTER TABLE `ordonnance`
  ADD CONSTRAINT `ordonnance_ibfk_1` FOREIGN KEY (`idConsultation`) REFERENCES `consultation` (`idConsultation`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`idDossier`) REFERENCES `dossier` (`idDossier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`mutuel`) REFERENCES `mutuel` (`idMutuel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rdv`
--
ALTER TABLE `rdv`
  ADD CONSTRAINT `rdv_ibfk_1` FOREIGN KEY (`idDossier`) REFERENCES `dossier` (`idDossier`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rdv_ibfk_2` FOREIGN KEY (`typeRdv`) REFERENCES `typerdv` (`idType`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `salledattent`
--
ALTER TABLE `salledattent`
  ADD CONSTRAINT `salledattent_ibfk_1` FOREIGN KEY (`idDossier`) REFERENCES `dossier` (`idDossier`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
