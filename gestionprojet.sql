-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 20 août 2024 à 01:19
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestionprojet`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_clients` int(10) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `adress` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_clients`, `client_name`, `company`, `Email`, `phone`, `adress`) VALUES
(2, 'chayma', 'hp', 'kh@gmail.com', '2002958100', 'kkkkkkkkkk'),
(3, 'jhon', 'dynamic', 'jhon@gmail.com', '2002958100', 'ioooooo');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id_facture` int(10) NOT NULL,
  `id_projet` int(10) NOT NULL,
  `id_clients` int(10) NOT NULL,
  `numero_facture` varchar(10) NOT NULL,
  `montant` double NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_emission` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `nom_entreprise` varchar(255) NOT NULL,
  `adresse_entreprise` varchar(255) NOT NULL,
  `nomentrepriseemettrice` varchar(255) NOT NULL,
  `adressentrepriseemettrice` varchar(255) NOT NULL,
  `taxes` double NOT NULL,
  `remises` double NOT NULL,
  `montant_total` double NOT NULL,
  `tasks_json` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id_facture`, `id_projet`, `id_clients`, `numero_facture`, `montant`, `status`, `date_emission`, `description`, `nom_entreprise`, `adresse_entreprise`, `nomentrepriseemettrice`, `adressentrepriseemettrice`, `taxes`, `remises`, `montant_total`, `tasks_json`, `created_at`, `updated_at`) VALUES
(8, 11, 2, '11', 1500, 'Payé', '2024-08-16', 'bbbbb', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 15, 20, 1705, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 12, 2, '2', 1500, 'Payé', '2024-08-15', 'gggggg', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 15, 20, 1705, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 11, 2, '22', 2500, 'Payé', '2024-08-09', 'mmmmmm', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 4, 15, 2585, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 11, 2, '08', 9500, 'Payé', '2024-08-23', 'cc', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 4, 2, 9878, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 14, 2, '08', 9500, 'Payé', '2024-08-23', 'cc', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 4, 2, 9878, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 11, 2, '08', 9500, 'Payé', '2024-08-23', 'cc', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 4, 2, 9878, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 12, 2, '1', 7500, 'Payé', '2024-08-16', 'nn', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 11, 400, 7925, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 13, 2, '2', 10000, 'Payé', '2024-08-22', 'cc', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 5, 21.99, 10478.01, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 14, 2, '1', 20000, 'Payé', '2024-08-21', 'projet', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 5, 2000, 19000, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 15, 3, '11', 20000, 'Payé', '2024-08-21', 'projet', 'dynamic', 'ioooooo', 'dynamix', 'tunis', 5, 1000, 20000, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 13, 2, 'f01', 30000, 'Payé', '2024-08-30', 'projet', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 5, 2000, 29500, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 14, 2, 'f02', 40000, 'Payé', '2024-08-04', 'new project', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 10, 2000, 42000, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 13, 2, '11', 1500, 'Payé', '2024-08-07', 'mmm', 'hp', 'kkkkkkkkkk', 'dynamix', 'tunis', 5, 200, 1375, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 13, 2, '2020', 25000, 'Payé', '2024-08-25', 'cccc', 'hp', 'kkkkkkkkkk', 'dynamix', 'bizerte', 5, 100, 26150, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 16, 3, '2', 25000, 'En attente', '2024-09-26', 'jjj', 'dynamic', 'ioooooo', 'dynamix', 'bizerte', 5, 200, 26050, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 17, 3, '024', 33000, 'En attente', '2024-09-25', 'project', 'dynamic', 'ioooooo', 'actia', 'bizerte', 10, 1999.99, 34300.01, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 17, 3, '015', 8000, 'En attente', '2024-08-20', 'new project', 'dynamic', 'ioooooo', 'actia', 'tunis', 5, 1000, 7400, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 18, 2, 'f11', 15000, 'En attente', '2024-08-22', 'new', 'hp', 'kkkkkkkkkk', 'dynamix', 'bizerte', 5, 1000, 14750, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id_projet` int(10) NOT NULL,
  `projetname` varchar(20) NOT NULL,
  `id_clients` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id_projet`, `projetname`, `id_clients`, `user_id`, `description`, `start_date`, `end_date`) VALUES
(11, 'projet angular', 2, 17, 'projet web et mobile', '2024-08-09', '2024-10-31'),
(12, 'projet python', 2, 21, 'application ', '2024-08-15', '2024-10-31'),
(13, 'projet python', 2, 24, 'projet complet , contient plusieurs taches', '2024-08-16', '2024-10-31'),
(14, 'projet react', 2, 24, 'applicatio web', '2024-08-24', '2024-10-26'),
(15, 'projet php', 3, 19, 'creation d\'une application web', '2024-08-31', '2024-10-31'),
(16, 'projet php', 3, 24, 'application web', '2024-08-15', '2024-10-16'),
(17, 'gestion de projet', 3, 28, 'projet web ', '2024-08-29', '2024-12-28'),
(18, 'gestion de projet', 2, 33, 'new project', '2024-08-25', '2024-10-31');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(3) NOT NULL,
  `nom_role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `nom_role`) VALUES
(1, 'admin'),
(2, 'consultant');

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `id_tache` int(10) NOT NULL,
  `id_projet` int(10) NOT NULL,
  `nomtache` varchar(50) NOT NULL,
  `user_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `descriptiontache` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `hours` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`id_tache`, `id_projet`, `nomtache`, `user_id`, `date`, `descriptiontache`, `status`, `hours`, `created_at`, `updated_at`) VALUES
(4, 13, 'base de donné et partie backend', 24, '2024-08-09', 'creation des tables et relation', 'in progress', 5, '2024-08-05 14:54:04', '2024-08-05 14:54:04'),
(5, 13, 'base de donné', 24, '2024-08-16', 'creation table et relation', 'completed', 3, '2024-08-05 14:55:49', '2024-08-05 14:55:49'),
(6, 14, 'partie back end', 24, '2024-08-22', 'gggggggggggg', 'completed', 2, '2024-08-06 13:19:08', '2024-08-06 13:19:08'),
(7, 16, 'partie back end', 24, '2024-08-30', 'pppppppppppp', 'in progress', 4, '2024-08-06 13:19:47', '2024-08-06 13:19:47'),
(9, 13, 'partie back end et front end', 12, '2023-07-20', 'mmmmmmmm', 'completed', 0, '2024-08-15 22:14:05', '2024-08-15 22:14:05'),
(10, 14, 'llll', 24, '2023-06-15', 'mmmmm', 'in progress', 4, '2024-08-15 22:15:19', '2024-08-15 22:15:19'),
(11, 13, 'nouvelle tache', 24, '2024-10-24', 'dashboard', 'completed', 2, '2024-08-17 22:54:37', '2024-08-17 22:54:37'),
(12, 13, 'ajouter la partie export en excel ', 12, '2024-08-25', 'hhhhhhhh', 'in progress', 6, '2024-08-18 22:14:11', '2024-08-18 22:14:11'),
(13, 13, 'creation factures', 24, '2024-08-14', '2eme partie de projet', 'in progress', 4, '2024-08-19 00:23:36', '2024-08-19 00:23:36'),
(14, 18, 'partie  front end', 33, '2024-08-25', '1er partie', 'completed', 4, '2024-08-19 21:36:57', '2024-08-19 21:36:57'),
(15, 18, 'partie back end', 33, '2024-08-26', '2eme partie', 'in progress', 4, '2024-08-19 21:37:22', '2024-08-19 21:37:22');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `numtel` varchar(8) NOT NULL,
  `dateN` date NOT NULL,
  `adresse` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `id_role` int(3) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `numtel`, `dateN`, `adresse`, `Email`, `password`, `profile_image`, `id_role`, `token`) VALUES
(12, 'cc', 'sara', '44444444', '2024-08-10', 'jjjjjjjjj', 'abidichayma83@gmail.com', '$2y$10$vF5rEHQ3crHMIrVk.UWB3O4cFvuv07hBidFaTYAF50fRvjvDK7WJ.', '', 1, ''),
(17, 'abidi', 'chayma', '44444444', '2024-08-03', 'jjjjjjjjj', 'chaymaabididev@gmail.com', '$2y$10$Q7plf/p9L.Pnv.seAo/cSOrObC/DeYh.ywwbWfxujYcIMSPBA919a', '', 1, ''),
(24, 'marine', 'marin', '55555555', '2024-08-17', 'jjjjjjjjj', 'chayma@gmail.com', '$2y$10$GfcQxgZYe1ppmx5s0Ld9O.Cug4lwHa456mMrdNupxtI6bEYBjhEpG', '', 2, ''),
(33, 'hadded', 'ramzi', '44444444', '1997-09-15', 'ariana', 'ramzihadded@gmail.com', '$2y$10$rQJvCJa2gwMt4WiBfm9eJ.6Y72Sv5jKG.r4PG9KtzKhd8Il1Fgrse', '', 2, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_clients`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `id_projet` (`id_projet`),
  ADD KEY `id_clients` (`id_clients`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id_projet`),
  ADD KEY `id_clients` (`id_clients`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`id_tache`),
  ADD KEY `id_projet` (`id_projet`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_clients` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id_facture` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id_projet` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `id_tache` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id_projet`),
  ADD CONSTRAINT `factures_ibfk_2` FOREIGN KEY (`id_clients`) REFERENCES `clients` (`id_clients`);

--
-- Contraintes pour la table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `projets_ibfk_1` FOREIGN KEY (`id_clients`) REFERENCES `clients` (`id_clients`);

--
-- Contraintes pour la table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `taches_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id_projet`),
  ADD CONSTRAINT `taches_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
