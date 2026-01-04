-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 04 jan. 2026 à 11:32
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
-- Base de données : `mini_mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Maquillage Visage', 'Fond de teint, poudre, blush, highlighter et autres produits pour le visage', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(2, 'Maquillage Yeux', 'Mascara, eyeliner, fard à paupières, crayons et produits pour les yeux', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(3, 'Maquillage Lèvres', 'Rouges à lèvres, gloss, crayons à lèvres et soins pour les lèvres', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(4, 'Soins Visage', 'Crèmes hydratantes, sérums, masques et soins quotidiens du visage', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(5, 'Soins Corps', 'Huiles, laits, gels douche et produits de soin pour le corps', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(6, 'Soins Cheveux', 'Shampoings, après-shampoings, masques et produits capillaires', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(7, 'Parfums', 'Eaux de toilette, parfums et eaux de Cologne', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(8, 'Accessoires', 'Pinceaux, éponges, miroirs et outils de maquillage', '2026-01-04 11:13:44', '2026-01-04 11:13:44');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `statut` enum('en_attente','validee','annulee') DEFAULT 'en_attente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `user_id`, `statut`, `total`, `created_at`, `updated_at`) VALUES
(1, 2, 'validee', 63.97, '2026-01-04 11:23:12', '2026-01-04 11:23:12');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`id`, `commande_id`, `product_id`, `quantite`, `prix_unitaire`, `created_at`) VALUES
(1, 1, 5, 1, 50.02, '2026-01-04 11:23:12'),
(2, 1, 8, 1, 13.95, '2026-01-04 11:23:12');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `prix`, `stock`, `image_url`, `categorie_id`, `created_at`, `updated_at`) VALUES
(1, 'Fino Premium Touch Hair Mask', 'Shiseido Fino Premium Touch Hair Mask est un masque capillaire intensif conçu pour réparer les dommages, idéal pour les cheveux secs, colorés, permanentés ou abîmés par le soleil et les agressions quotidiennes.\r\nFormulé avec gelée royale EX, PCA et Lipidure EX, il hydrate, renforce et nourrit le cuir chevelu pour des cheveux doux, brillants et protégés de la racine aux pointes.\r\nGrâce à un complexe de sept essences ciblées, le soin optimise l’absorption des nutriments, régule l’hydratation, et confère à la chevelure brillance, volume et éclat longue durée', 17.95, 12, 'https://sevenyoung.com/cdn/shop/files/SSD1000.jpg?v=1766165009&width=1946', 6, '2026-01-04 11:15:06', '2026-01-04 11:15:06'),
(2, 'Heartleaf Calming Trial Kit', 'ABIB Heartleaf Calming Trial Kit propose une routine de soins en 4 étapes conçue pour calmer, hydrater et revitaliser les peaux sensible grâce à l’extrait de Houttuynia Cordata.\r\n\r\nLe kit comprend:\r\n\r\nAcne Foam Cleanser Heartleaf Foam 30mL : Un nettoyant doux au pH neutre avec de l\'acide salicylique pour cibler les imperfections\r\nHeartleaf Calming Toner Skin Booster 30mL : Un toner apaisant offrant une hydratation intense et contrôle l\'excès de sébum\r\nHeartleaf TECA Capsule Serum Calming Drop 15mL : Un sérum apaisant enrichi en TECA conçu pour régénérer, hydrater et réduire les imperfections\r\nHeartleaf Crème Calming Tube 20mL : Une crème hydratante qui calme les rougeurs sans laisser de sensation collante', 22.00, 6, 'https://sevenyoung.com/cdn/shop/files/ABC1000.jpg?v=1766601571&width=1946', 8, '2026-01-04 11:16:02', '2026-01-04 11:16:02'),
(3, 'Bundle Reset & Glow', 'Offrez à votre peau un reset complet et un éclat immédiat avec le Bundle Reset & Glow, une routine experte à la vitamine C et au collagène, pensée pour revitaliser, lisser et illuminer le teint en profondeur.\r\nIdéal pour commencer l’année avec une peau fraîche, rebondie et visiblement plus nette.\r\n\r\nCe bundle comprend :\r\n\r\nVitamin C Boosting Serum 30 mL – Dr. Althea : un sérum concentré qui booste l’éclat naturel de la peau tout en douceur.\r\n\r\nDeep Vita C Capsule Cream 55 g – Medicube : une crème innovante enrichie en capsules de vitamine C qui s’activent à l’application pour illuminer le teint, atténuer les taches, unifier et lisser la texture de la peau.\r\n\r\nCollagen Pore Tight Up Hydrogel Mask (1pcs) – Eqqualberry : un masque soin ciblé pour une peau visiblement plus ferme et des pores resserrés.\r\n\r\n✨ Le combo parfait pour repartir sur de bonnes bases et révéler l’éclat naturel de votre peau dès les premières utilisations.', 42.00, 8, 'https://sevenyoung.com/cdn/shop/files/BunddleReset_Glow.jpg?v=1767103503&width=1946', 4, '2026-01-04 11:17:07', '2026-01-04 11:17:07'),
(4, '345 Relief Cream Mist 60mL', 'Dr.Althea 345 Relief Cream Mist est une brume hydratante ultra-fine conçue pour offrir un soulagement immédiat à la peau sèche ou sensibilisée.\r\nElle associe une phase crème enrichie d\'eau de son de riz nourrissante, d\'acide hyaluronique et de panthénol et une phase essence hydratante concentrée en madécassoside apaisant, eau de feuille d\'aloe vera et extrait de centella, pour offrir à la fois une nutrition riche et une hydratation rafraîchissante.', 19.95, 24, 'https://sevenyoung.com/cdn/shop/files/345_Relief_Cream_Mist_60mL.jpg?v=1764786274&width=1946', 4, '2026-01-04 11:17:55', '2026-01-04 11:17:55'),
(5, 'Bundle Makeup', 'Découvrez un trio maquillage incontournable pour un look lumineux, naturel et élégant. Ce bundle réunit trois best-sellers pour sublimer votre teint, illuminer votre regard et apporter une touche nude irrésistible à vos lèvres.\r\n\r\nLe bundle comprend: \r\n- Better Than Palette 00 Light & Glitter Garden \r\n- True Dimension Radiance Balm LT001 Light 10g\r\n- Glasting Melting Balm | Dusty On The Nude Series n°13', 50.02, 8, 'https://sevenyoung.com/cdn/shop/files/BundleMakeup.jpg?v=1765117799&width=1946', 8, '2026-01-04 11:18:39', '2026-01-04 11:23:12'),
(6, 'Shadow Palette', 'Dasique Shadow Palette propose une harmonie de neuf teintes soigneusement choisies par des experts, avec des finis mats, scintillants et pailletés. Idéale pour passer d’un maquillage naturel du quotidien à un look glamour sophistiqué, sa texture ultra-fine assure une application fluide, une excellente adhérence et une tenue prolongée, tout en minimisant les résidus.', 24.95, 28, 'https://sevenyoung.com/cdn/shop/files/DSM1000-11.jpg?v=1764071951&width=1946', 2, '2026-01-04 11:19:26', '2026-01-04 11:19:26'),
(7, 'Curl Fix Mascara 01 Black 8g', 'Curl Fix Mascara gonfle et courbe chaque cil, pour un résultat visible toute la journée.\r\nLa technologie Curl 24HR soulève et courbe durablement les cils, tandis que le double pinceau gel doux recouvre magistralement chaque cil.\r\nUn mascara waterproof qui ne coule pas et qui ne forme pas de paquets, quelques gestes suffisent pour que le film à triple couche apporte définition et volume tout en légèreté.', 16.95, 35, 'https://sevenyoung.com/cdn/shop/files/Design-sans-titre-2024-12-22T182535.138.jpg?v=1764071775&width=1946', 2, '2026-01-04 11:20:24', '2026-01-04 11:20:24'),
(8, 'Juicy Lasting Tint Spring Fever Series', 'Rom&nd; Juicy Lasting Tint Spring Fever est un gloss, un rouge à lèvres et une teinte pour les lèvres en un seul produit. Cette teinte ajoute une couleur vibrante à vos lèvres avec une belle finition brillante et transparente.', 13.95, 23, 'https://sevenyoung.com/cdn/shop/files/romnd-JUICY-LASTING-TINT-SPRING-FEVER-5.5g-1.jpg?v=1764071724&width=1946', 3, '2026-01-04 11:21:10', '2026-01-04 11:23:12'),
(9, 'Art Class By Rodin Shading #01 Classic', 'Cette palette contouring de Too Cool For School composée de 3 teintes peut être utilisée pour unifier le teint et réaliser un contouring de zones du visage et du corps.\r\nLes teintes peuvent être appliquée séparément ou mélangées pour obtenir la teinte souhaitée !\r\n\r\nLivraison gratuite dès 50€', 18.95, 8, 'https://sevenyoung.com/cdn/shop/files/Design-sans-titre-67_1d163698-726a-4a76-9d12-7fe459996bb3.jpg?v=1764064876&width=1946', 1, '2026-01-04 11:22:19', '2026-01-04 11:22:19');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'admin@votre-boutique.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-04 11:13:44', '2026-01-04 11:13:44'),
(2, 'd', 'D@gmail.com', '$2y$10$OTVjl6FG7FOdtUwJA1916.8DEcTqW4HVFC4EzkM8OqOgYAoU9FGdy', '2026-01-04 11:22:48', '2026-01-04 11:22:48');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nom` (`nom`),
  ADD KEY `idx_prix` (`prix`),
  ADD KEY `idx_categorie` (`categorie_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `commande_produit_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_produit_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
