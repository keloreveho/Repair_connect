-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 01:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repairconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `appareil`
--

CREATE TABLE `appareil` (
  `id_appareil` int(11) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `marque` varchar(100) NOT NULL,
  `modele` varchar(100) NOT NULL,
  `date_achat` date DEFAULT NULL,
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appareil`
--

INSERT INTO `appareil` (`id_appareil`, `categorie`, `marque`, `modele`, `date_achat`, `id_client`) VALUES
(1, 'Smartphone', 'Samsung', 'Galaxy S21', '2022-03-15', 1),
(2, 'Ordinateur portable', 'HP', 'Pavilion 15', '2021-09-10', 2),
(3, 'Tablette', 'Apple', 'iPad Air', '2023-01-20', 3),
(4, 'Smartphone', 'Apple', 'iPhone 12', '2021-06-12', 4),
(5, 'Ordinateur portable', 'Dell', 'Inspiron 15', '2022-11-05', 5),
(6, 'Smartphone', 'Xiaomi', 'Redmi Note 11', '2023-04-18', 6),
(7, 'Tablette', 'Samsung', 'Galaxy Tab S7', '2022-07-22', 7),
(8, 'Ordinateur portable', 'Lenovo', 'ThinkPad E14', '2020-12-03', 8),
(9, 'Smartphone', 'Oppo', 'Reno 8', '2023-08-14', 9),
(10, 'Ordinateur portable', 'Asus', 'VivoBook 15', '2022-02-28', 10),
(11, 'Smartphone', 'Samsung', 'Galaxy A52', '2021-10-09', 1),
(12, 'Tablette', 'Apple', 'iPad 9', '2022-05-17', 2),
(13, 'Ordinateur portable', 'Acer', 'Aspire 5', '2023-03-11', 3),
(14, 'Smartphone', 'Google', 'Pixel 7', NULL, 4),
(15, 'Ordinateur portable', 'MSI', 'Modern 14', '2023-09-06', 5),
(16, 'Ordinateur portable', 'itel', 'T14', '2026-11-04', 11);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id_client`, `nom`, `prenom`, `email`, `telephone`) VALUES
(1, 'Diallo', 'Hamidou', 'amadou.diallo@example.com', '0612345601'),
(2, 'Martin', 'Sophie', 'sophie.martin@example.com', '0612345602'),
(3, 'Dupont', 'Lucas', 'lucas.dupont@example.com', '0612345603'),
(4, 'Traore', 'Fatou', 'fatou.traore@example.com', '0612345604'),
(5, 'Bernard', 'Emma', 'emma.bernard@example.com', '0612345605'),
(6, 'Camara', 'Ibrahim', 'ibrahim.camara@example.com', '0612345606'),
(7, 'Petit', 'Chloe', 'chloe.petit@example.com', '0612345607'),
(8, 'Ndiaye', 'Moussa', 'moussa.ndiaye@example.com', '0612345608'),
(9, 'Moreau', 'Hugo', 'hugo.moreau@example.com', '0612345609'),
(10, 'Kone', 'Aminata', 'aminata.kone@example.com', '0612345610'),
(11, 'yankam', 'kelore', 'keloreyankam@gmail.com', '0660588045');

-- --------------------------------------------------------

--
-- Table structure for table `demande_reparation`
--

CREATE TABLE `demande_reparation` (
  `id_demande` int(11) NOT NULL,
  `date_depot` date NOT NULL,
  `description_panne` text NOT NULL,
  `statut` varchar(100) NOT NULL,
  `cout_estime` decimal(10,2) NOT NULL,
  `id_appareil` int(11) NOT NULL
) ;

--
-- Dumping data for table `demande_reparation`
--

INSERT INTO `demande_reparation` (`id_demande`, `date_depot`, `description_panne`, `statut`, `cout_estime`, `id_appareil`) VALUES
(1, '2026-09-01', 'Ecran fissure', 'terminée', 120.00, 1),
(2, '2026-09-02', 'Batterie ne tient plus la charge', 'terminée', 75.00, 2),
(3, '2026-09-03', 'Tablette ne demarre plus', 'en cours', 95.00, 3),
(4, '2026-09-04', 'Probleme de charge', 'en cours', 60.00, 4),
(5, '2026-09-05', 'Clavier defectueux', 'terminée', 85.00, 5),
(6, '2026-09-06', 'Telephone surchauffe', 'en cours', 70.00, 6),
(7, '2026-09-07', 'Ecran tactile ne repond plus', 'en attente', 110.00, 7),
(8, '2026-09-08', 'Ordinateur tres lent', 'terminée', 65.00, 8),
(9, '2026-09-09', 'Camera arriere defectueuse', 'en cours', 90.00, 9),
(10, '2026-09-10', 'Disque dur en panne', 'en attente', 150.00, 10),
(11, '2026-09-11', 'Microphone ne fonctionne plus', 'terminée', 55.00, 11),
(12, '2026-09-12', 'Probleme de connexion Wi-Fi', 'annulée', 45.00, 12),
(13, '2026-09-13', 'Ventilateur bruyant', 'en cours', 80.00, 13),
(14, '2026-09-14', 'Bouton alimentation defectueux', 'en attente', 50.00, 14),
(15, '2026-09-15', 'Ordinateur ne demarre plus', 'en cours', 130.00, 15),
(16, '2026-11-05', 'l\'ordinateur ne charge plus', 'en attente', 64.99, 16);

-- --------------------------------------------------------

--
-- Table structure for table `intervention`
--

CREATE TABLE `intervention` (
  `id_intervention` int(11) NOT NULL,
  `date_intervention` date NOT NULL,
  `description` text NOT NULL,
  `cout_main_oeuvre` decimal(10,2) NOT NULL,
  `id_demande` int(11) NOT NULL,
  `id_technicien` int(11) NOT NULL
) ;

--
-- Dumping data for table `intervention`
--

INSERT INTO `intervention` (`id_intervention`, `date_intervention`, `description`, `cout_main_oeuvre`, `id_demande`, `id_technicien`) VALUES
(1, '2026-09-02', 'Remplacement de l ecran', 50.00, 1, 1),
(2, '2026-09-03', 'Test de fonctionnement de l ecran', 20.00, 1, 1),
(3, '2026-09-03', 'Remplacement de la batterie', 35.00, 2, 1),
(4, '2026-09-04', 'Diagnostic de la carte mere', 40.00, 3, 4),
(5, '2026-09-05', 'Verification du connecteur de charge', 25.00, 4, 1),
(6, '2026-09-06', 'Remplacement du clavier', 45.00, 5, 2),
(7, '2026-09-07', 'Nettoyage du systeme de refroidissement', 30.00, 6, 4),
(8, '2026-09-08', 'Diagnostic de l ecran tactile', 35.00, 7, 3),
(9, '2026-09-09', 'Optimisation du systeme', 40.00, 8, 2),
(10, '2026-09-10', 'Diagnostic du module camera', 30.00, 9, 1),
(11, '2026-09-11', 'Diagnostic du disque dur', 45.00, 10, 2),
(12, '2026-09-12', 'Remplacement du microphone', 30.00, 11, 1),
(13, '2026-09-14', 'Nettoyage du ventilateur', 35.00, 13, 2),
(14, '2026-09-15', 'Verification du bouton alimentation', 25.00, 14, 4),
(15, '2026-09-16', 'Diagnostic du demarrage', 50.00, 15, 5),
(16, '2026-09-17', 'Remplacement du ventilateur', 40.00, 13, 2),
(17, '2026-09-18', 'Verification de la carte mere', 45.00, 15, 5),
(18, '2026-09-19', 'Test du systeme de charge', 20.00, 4, 1),
(19, '2026-09-20', 'Verification des performances', 25.00, 8, 2),
(20, '2026-09-21', 'Test final du clavier', 20.00, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `piece_detachee`
--

CREATE TABLE `piece_detachee` (
  `ref_piece` varchar(30) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `quantite_disponible` int(11) NOT NULL
) ;

--
-- Dumping data for table `piece_detachee`
--

INSERT INTO `piece_detachee` (`ref_piece`, `libelle`, `prix_unitaire`, `quantite_disponible`) VALUES
('P001', 'Ecran Samsung Galaxy S21', 95.00, 8),
('P002', 'Batterie iPhone 12', 45.00, 12),
('P003', 'Connecteur de charge USB-C', 15.00, 25),
('P004', 'Clavier ordinateur portable HP', 55.00, 6),
('P005', 'Ventilateur ordinateur portable', 30.00, 10),
('P006', 'Ecran tactile tablette', 80.00, 7),
('P007', 'Disque SSD 512 Go', 65.00, 15),
('P008', 'Module camera smartphone', 40.00, 9),
('P009', 'Microphone smartphone', 18.00, 20),
('P010', 'Bouton alimentation', 12.00, 18);

-- --------------------------------------------------------

--
-- Table structure for table `technicien`
--

CREATE TABLE `technicien` (
  `id_technicien` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `specialite` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technicien`
--

INSERT INTO `technicien` (`id_technicien`, `nom`, `prenom`, `specialite`) VALUES
(1, 'Dubois', 'Pierre', 'Smartphones'),
(2, 'Lefevre', 'Marie', 'Ordinateurs portables'),
(3, 'Diop', 'Ousmane', 'Tablettes'),
(4, 'Laurent', 'Thomas', 'Electronique'),
(5, 'Ba', 'Aissatou', 'Diagnostic et maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `utiliser`
--

CREATE TABLE `utiliser` (
  `id_intervention` int(11) NOT NULL,
  `ref_piece` varchar(30) NOT NULL,
  `quantite_utilisee` int(11) NOT NULL
) ;

--
-- Dumping data for table `utiliser`
--

INSERT INTO `utiliser` (`id_intervention`, `ref_piece`, `quantite_utilisee`) VALUES
(1, 'P001', 1),
(3, 'P002', 1),
(5, 'P003', 1),
(6, 'P004', 1),
(7, 'P005', 1),
(8, 'P006', 1),
(10, 'P008', 1),
(11, 'P007', 1),
(12, 'P009', 1),
(14, 'P010', 1),
(16, 'P005', 1),
(18, 'P003', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appareil`
--
ALTER TABLE `appareil`
  ADD PRIMARY KEY (`id_appareil`),
  ADD KEY `fk_appareil_client` (`id_client`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `uq_client_email` (`email`);

--
-- Indexes for table `demande_reparation`
--
ALTER TABLE `demande_reparation`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `fk_demande_appareil` (`id_appareil`);

--
-- Indexes for table `intervention`
--
ALTER TABLE `intervention`
  ADD PRIMARY KEY (`id_intervention`),
  ADD KEY `fk_intervention_demande` (`id_demande`),
  ADD KEY `fk_intervention_technicien` (`id_technicien`);

--
-- Indexes for table `piece_detachee`
--
ALTER TABLE `piece_detachee`
  ADD PRIMARY KEY (`ref_piece`);

--
-- Indexes for table `technicien`
--
ALTER TABLE `technicien`
  ADD PRIMARY KEY (`id_technicien`);

--
-- Indexes for table `utiliser`
--
ALTER TABLE `utiliser`
  ADD PRIMARY KEY (`id_intervention`,`ref_piece`),
  ADD KEY `fk_utiliser_piece` (`ref_piece`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appareil`
--
ALTER TABLE `appareil`
  ADD CONSTRAINT `fk_appareil_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Constraints for table `demande_reparation`
--
ALTER TABLE `demande_reparation`
  ADD CONSTRAINT `fk_demande_appareil` FOREIGN KEY (`id_appareil`) REFERENCES `appareil` (`id_appareil`);

--
-- Constraints for table `intervention`
--
ALTER TABLE `intervention`
  ADD CONSTRAINT `fk_intervention_demande` FOREIGN KEY (`id_demande`) REFERENCES `demande_reparation` (`id_demande`),
  ADD CONSTRAINT `fk_intervention_technicien` FOREIGN KEY (`id_technicien`) REFERENCES `technicien` (`id_technicien`);

--
-- Constraints for table `utiliser`
--
ALTER TABLE `utiliser`
  ADD CONSTRAINT `fk_utiliser_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id_intervention`),
  ADD CONSTRAINT `fk_utiliser_piece` FOREIGN KEY (`ref_piece`) REFERENCES `piece_detachee` (`ref_piece`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
