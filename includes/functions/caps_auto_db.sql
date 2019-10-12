-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 20 fév. 2019 à 10:41
-- Version du serveur :  10.1.31-MariaDB
-- Version de PHP :  7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `caps_auto_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `social_reason` varchar(55) NOT NULL,
  `first_name` varchar(55) NOT NULL,
  `last_name` varchar(55) NOT NULL,
  `job` varchar(55) NOT NULL,
  `tel` varchar(22) NOT NULL,
  `fax` varchar(22) NOT NULL,
  `mobile` varchar(22) NOT NULL,
  `email` varchar(255) NOT NULL,
  `was_accepted_first_contract` tinyint(22) NOT NULL,
  `was_accepted_first_contract_date` varchar(55) NOT NULL,
  `was_accepted_second_contract` tinyint(22) NOT NULL,
  `was_accepted_second_contract_date` varchar(55) NOT NULL,
  `user_id` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contracts`
--

INSERT INTO `contracts` (`id`, `social_reason`, `first_name`, `last_name`, `job`, `tel`, `fax`, `mobile`, `email`, `was_accepted_first_contract`, `was_accepted_first_contract_date`, `was_accepted_second_contract`, `was_accepted_second_contract_date`, `user_id`) VALUES
(1, 'social_reason', 'first_name', 'last_name', 'job', 'tel', 'fax', 'mobile', 'email', 1, '15/01/2018', 1, '15/01/2018', 1),
(19, 'fdfssdf', 'sdfgsdfgds', 'ssdfgsdf', 'sdfgsdfgsdfg', 'sdgfsdfgsdfg', 'sdfgsdfgsdfg', 'sdfgsdfgsdgfsdfg', 'sdfgsdfgdsfgsdf@mmm.com', 1, '20/2/2019', 1, '20/2/2019', 2);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `code_mf` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `code_mf`, `password`) VALUES
(1, 'Mouad', '123456'),
(2, 'ZIANI', '123456');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
