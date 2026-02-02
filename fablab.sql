-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 01 fév. 2026 à 20:56
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `fablab`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `image_url` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `contenu`, `auteur`, `image_url`, `created_at`, `updated_at`) VALUES
(16, 'Conception d’un robot suiveur de ligne', 'Parmi les premiers projets réalisés au Fablab Robotique, le robot suiveur de ligne occupe une place centrale. Ce projet pédagogique permet d’aborder plusieurs domaines essentiels de la robotique : mécanique, électronique et programmation.\r\n\r\nLe robot a été conçu à partir de pièces imprimées en 3D, assemblées directement au fablab. Il est équipé de capteurs infrarouges lui permettant de détecter une ligne tracée au sol et d’adapter sa trajectoire en conséquence.\r\n\r\nLa programmation du robot a nécessité plusieurs phases de test et d’amélioration. Les membres ont appris à corriger les erreurs, optimiser les déplacements et rendre le comportement du robot plus fluide. Ce projet a montré l’importance de l’expérimentation et de l’itération dans un processus de création.', 'Geko Obed', 'https://i.ytimg.com/vi/InF7YjlOtEw/maxresdefault.jpg?sqp=-oaymwEmCIAKENAF8quKqQMa8AEB-AHUBoAC4AOKAgwIABABGE4gUyhlMA8=&rs=AOn4CLD8VImHcqzxjVff1L9A-I5VCZZ7sw', '2026-01-30 23:12:52', '2026-01-30 23:18:14'),
(15, 'Découvrir la robotique avec Arduino', 'Le premier atelier Arduino du Fablab Robotique a marqué une étape importante dans le lancement du projet. Cet atelier avait pour but d’initier les participants aux bases de l’électronique et de la programmation embarquée.\r\n\r\nLes participants ont appris à utiliser une carte Arduino, à écrire leurs premiers programmes et à interagir avec des composants simples comme des LED, des boutons et des capteurs. Ces exercices pratiques ont permis de comprendre comment un robot perçoit son environnement et réagit à des instructions.\r\n\r\nL’atelier s’est déroulé dans une ambiance conviviale et pédagogique, favorisant les échanges et les questions. Pour beaucoup, c’était une première approche de la robotique, et cette initiation a permis de démystifier des concepts parfois perçus comme complexes.', 'Sylvain Besseron', 'images/articles/art3.png\r\n', '2026-01-30 23:12:20', '2026-02-01 10:36:47'),
(14, 'Naissance du Fablab Robotique', 'Titre : Le Fablab Robotique : un nouvel espace dédié à l’innovation\r\n\r\nDate : 12 janvier 2026\r\n\r\nContenu :\r\nLe Fablab Robotique est né d’une volonté simple : créer un lieu où la technologie devient accessible à tous. Ce projet a pour objectif de rassembler des passionnés de robotique, des étudiants, des développeurs et des curieux autour d’un même espace de création et d’apprentissage.\r\n\r\nLe fablab met à disposition de nombreux équipements : imprimantes 3D, postes de soudure, cartes Arduino, Raspberry Pi, capteurs, moteurs et outils de prototypage. Ces ressources permettent de concevoir des projets variés, allant du simple montage électronique jusqu’à des robots autonomes complexes.\r\n\r\nAu-delà du matériel, le Fablab Robotique se veut un lieu d’échange. Les membres sont encouragés à partager leurs connaissances, à collaborer sur des projets communs et à apprendre les uns des autres. L’objectif n’est pas seulement de fabriquer, mais aussi de comprendre et de progresser ensemble.', 'Sylvain Besseron', 'images/articles/art2.jpg\r\n', '2026-01-30 23:04:16', '2026-01-30 23:09:44'),
(17, 'Un projet concret : le robot suiveur de ligne', 'Parmi les premiers projets réalisés au Fablab Robotique, le robot suiveur de ligne occupe une place centrale. Ce projet pédagogique permet d’aborder plusieurs domaines essentiels de la robotique : mécanique, électronique et programmation.\r\n\r\nLe robot a été conçu à partir de pièces imprimées en 3D, assemblées directement au fablab. Il est équipé de capteurs infrarouges lui permettant de détecter une ligne tracée au sol et d’adapter sa trajectoire en conséquence.\r\n\r\nLa programmation du robot a nécessité plusieurs phases de test et d’amélioration. Les membres ont appris à corriger les erreurs, optimiser les déplacements et rendre le comportement du robot plus fluide. Ce projet a montré l’importance de l’expérimentation et de l’itération dans un processus de création.', 'Geko Obed', 'images/articles/art4.jpg', '2026-01-30 23:16:53', '2026-02-01 10:36:54'),
(18, 'Comment l’impression 3D transforme les projets robotiques', 'L’impression 3D est au cœur des activités du Fablab Robotique. Elle permet de concevoir des pièces personnalisées, parfaitement adaptées aux besoins des projets. Châssis de robots, supports de capteurs ou boîtiers électroniques peuvent être créés rapidement.\r\n\r\nCette technologie offre une grande liberté de conception. Les membres peuvent tester plusieurs versions d’une pièce, améliorer un design et corriger les défauts sans dépendre de pièces industrielles standardisées.\r\n\r\nL’utilisation de l’impression 3D permet également de réduire les coûts et d’accélérer le prototypage. Les idées prennent forme plus rapidement, ce qui encourage l’expérimentation et la créativité au sein du fablab.', 'Mathis DUSSIEL', 'images/articles/art1.png\r\n', '2026-01-30 23:21:34', '2026-02-01 18:16:19'),
(19, 'Vers des projets toujours plus ambitieux', 'Le Fablab Robotique ne compte pas s’arrêter à ses premiers projets. De nombreuses idées sont déjà en préparation : robots connectés à des applications web, initiation à l’intelligence artificielle, ou encore participation à des compétitions robotiques.\r\n\r\nL’objectif est de proposer des projets plus complexes, tout en restant accessibles aux nouveaux membres. Des formations progressives seront mises en place afin que chacun puisse évoluer à son rythme.\r\n\r\nÀ long terme, le fablab souhaite devenir un véritable lieu de référence pour l’apprentissage de la robotique, favorisant l’innovation, la collaboration et le partage des connaissances.', 'Mathis DUSSIEL', 'https://cdn8.futura-sciences.com/a1280/images/Boston-Dynamics-Atlas.jpg', '2026-01-30 23:22:27', '2026-02-01 10:21:25');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `texte` text NOT NULL,
  `user_id` int NOT NULL,
  `video_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_commentaires_user_id` (`user_id`),
  KEY `fk_commentaires_video_id` (`video_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `texte`, `user_id`, `video_id`, `created_at`) VALUES
(4, 'J\'ai bien aimé cette vidéo !', 5, 3, '2026-01-15 16:33:20'),
(6, 'Super ! Merci pour ces explications claires, je ne saisissais pas le concept ahaha', 5, 3, '2026-01-15 16:36:44');

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

DROP TABLE IF EXISTS `connexion`;
CREATE TABLE IF NOT EXISTS `connexion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'Utilisateur',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `connexion`
--

INSERT INTO `connexion` (`id`, `nom`, `email`, `photo`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'Obed GEKO', 'obedgeko4@gmail.com', NULL, '$2y$10$amXPEPf7CvzbCHvn5hHOhOiRK049a7ItRan72gGs/ZVv3LXHgZgOe', 'Admin', '2025-10-14 10:57:13'),
(5, 'Mathis DUSSIEL', 'math.dussiel@gmail.com', NULL, '$2y$10$GXcsJ5chm1Cf39lRbl8WzuLD0L4f1OzvgJao8jWiiNYUEJ0ce0fZK', 'Admin', '2025-11-06 08:40:38'),
(8, 'mushroom', 'math.dsl@gmail.com', NULL, '$2y$10$svcngPaMj9lWrA5KtuGeOuUEYDGc7xzaM8qq3AvR/4KpZfgnDXd4e', 'Utilisateur', '2026-01-10 18:34:46'),
(11, 'Fablabteam', 'fablabteam@gmail.com', NULL, '$2y$10$JvaSIuMHUNuIZ/KnjPnS9OxPaqxXfF2IjFF/yOUOuhCbmZAfkUP3y', 'Admin', '2026-02-01 08:59:14');

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `sujet` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `statut` enum('non_lu','lu','traite') DEFAULT 'non_lu',
  `date_envoi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_lecture` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_statut` (`statut`),
  KEY `idx_date_envoi` (`date_envoi`),
  KEY `idx_email` (`email`),
  KEY `idx_sujet` (`sujet`),
  KEY `idx_statut_date` (`statut`,`date_envoi`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `nom`, `email`, `sujet`, `message`, `statut`, `date_envoi`, `date_lecture`, `ip_address`) VALUES
(9, 'Thomat Leroy', 'thomas.leroy@outlook.fr', 'question', 'Bonjour,\r\nJe suis actuellement étudiant en informatique et je m’intéresse beaucoup aux projets de robotique présentés sur votre site.\r\nJ’aimerais savoir s’il est possible de participer aux activités du Fablab en tant qu’étudiant externe, ou s’il faut obligatoirement être inscrit à AJC Formation.\r\n\r\nMerci d’avance pour votre retour.', 'non_lu', '2026-02-01 17:54:13', NULL, '::1'),
(10, 'Sophie Nguyen', 'sophie.nguyen@orange.com', 'feedback', 'Bonjour,\r\nNous avons découvert votre Fablab dans le cadre d’une veille sur les initiatives de formation en technologies numériques.\r\nLes projets sont très inspirants et reflètent un bon niveau technique. Nous serions intéressés par une éventuelle collaboration ou intervention auprès de vos apprenants.\r\n\r\nCordialement,\r\nSophie Nguyen\r\nResponsable innovation chez : TechSolutions', 'non_lu', '2026-02-01 17:55:39', NULL, '::1'),
(11, 'Julien Moreau', 'julien.moreau@gmail.com', 'question', 'Bonjour,\r\nJe souhaite me reconvertir dans le domaine du développement web et de la robotique. Votre Fablab m’a été recommandé par un ancien apprenant.\r\nPourriez-vous m’indiquer si des journées portes ouvertes ou des démonstrations sont prévues prochainement ?\r\n\r\nMerci pour votre temps.. !', 'lu', '2026-02-01 17:57:22', '2026-02-01 18:03:05', '::1');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `description_detailed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `technologies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `challenges` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `auteur` int NOT NULL DEFAULT '11',
  PRIMARY KEY (`id`),
  KEY `fk_projects_auteur` (`auteur`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `description_detailed`, `technologies`, `image_url`, `features`, `challenges`, `created_at`, `updated_at`, `auteur`) VALUES
(1, 'Robot Tout-Terrain 6 Roues', 'Robot mobile autonome à 6 roues motrices pour navigation sur terrains difficiles.', 'Plateforme robotique robuste équipée de 6 roues tout-terrain avec jantes rouges. Architecture à châssis blanc avec système de suspension indépendante. Le robot intègre un module de contrôle central avec capteur ultrasonique bleu pour la détection d\'obstacles. Conçu pour des missions d\'exploration en extérieur et capable de franchir des obstacles variés grâce à sa configuration 6x6.', 'Châssis 6WD, Moteurs DC avec encodeurs, Arduino/ESP32, Capteur ultrasonique HC-SR04, Pont en H L298N, Batterie LiPo, Structure imprimée 3D', 'image_projet1.png', 'Configuration 6 roues motrices|Suspension indépendante|Détection d\'obstacles ultrasonique|Châssis modulaire imprimé 3D|Haute mobilité tout-terrain|Grande capacité de charge|Alimentation autonome LiPo', 'Synchronisation des 6 moteurs pour une trajectoire rectiligne, gestion de la puissance pour maintenir l\'autonomie avec 6 moteurs actifs, et optimisation du système de suspension pour différents types de terrains.', '2024-01-15 10:00:00', '2026-02-01 10:04:39', 11),
(2, 'Robot Autonome avec Écran LCD', 'Robot mobile intelligent avec système de navigation autonome et interface LCD.', 'Robot compact équipé d\'un écran LCD 20x4 pour affichage en temps réel des données de navigation. Le système intègre des capteurs ultrasoniques montés sur servo-moteur pour balayage panoramique, permettant une détection d\'obstacles à 360°. Châssis rouge imprimé en 3D avec 4 roues motrices. Interface utilisateur complète avec boutons de contrôle et affichage des modes de fonctionnement, distances mesurées et statut du robot.', 'Arduino Uno/Mega, Écran LCD 20x4 I2C, Capteurs ultrasoniques HC-SR04, Servo-moteur pour balayage, Moteurs DC, Pont en H, Boutons de navigation', 'image_projet2.png', 'Écran LCD 20x4 informatif|Balayage ultrasonique 360°|Navigation autonome intelligente|Interface utilisateur avec boutons|Affichage temps réel des distances|Modes de fonctionnement multiples|Châssis compact imprimé 3D', 'Intégration de l\'affichage LCD sans ralentir la boucle de contrôle principale, algorithme de décision pour choisir la meilleure direction selon les obstacles détectés, et gestion de l\'espace limité pour tous les composants.', '2024-02-01 14:30:00', '2026-02-01 10:04:39', 11),
(3, 'Robot Multi-Capteurs Avancé', 'Plateforme robotique avancée avec système de capteurs multiples et traitement complexe.', 'Robot sophistiqué intégrant plusieurs types de capteurs pour une perception environnementale complète. Architecture ouverte montrant Arduino Mega, modules de capteurs ultrasoniques, capteur de ligne au sol, et système de câblage organisé. Châssis rouge transparent permettant une visibilité complète des composants électroniques. Idéal pour l\'apprentissage de la robotique avancée et le prototypage de systèmes autonomes complexes.', 'Arduino Mega 2560, Capteurs ultrasoniques multiples, Capteur de ligne IR, Module Bluetooth HC-05, Pont en H double, Encodeurs de roues, Châssis acrylique', 'image_projet3.png', 'Architecture modulaire ouverte|Multiples capteurs ultrasoniques|Suivi de ligne au sol|Contrôle Bluetooth|Encodeurs pour odométrie|Câblage organisé et accessible|Plateforme éducative complète', 'Gestion de multiples flux de données capteurs simultanés, synchronisation des différents modules de perception, et optimisation du code pour éviter les ralentissements avec l\'Arduino Mega.', '2023-11-10 09:15:00', '2026-02-01 10:04:39', 11),
(4, 'Station IoT Raspberry Pi', 'Centre de contrôle IoT basé sur Raspberry Pi pour monitoring et automation.', 'Station de développement et de contrôle IoT construite autour d\'un Raspberry Pi 4. Le système comprend plusieurs modules connectés : caméra pour vision artificielle, module GPS, capteurs environnementaux et interfaces de communication. Boîtier rouge imprimé 3D avec tous les ports accessibles. Cette station sert de hub central pour collecter, traiter et transmettre des données depuis divers capteurs distribués dans l\'espace du Fablab.', 'Raspberry Pi 4, Caméra Pi v2, Module GPS, Capteurs I2C multiples, WiFi/Ethernet, Python, Node-RED, MQTT', 'image_projet4.png', 'Processing puissant Raspberry Pi 4|Vision artificielle avec caméra|Géolocalisation GPS|Connectivité WiFi et Ethernet|Interface web de monitoring|Communication MQTT|Boîtier modulaire personnalisé', 'Configuration optimale des services système pour démarrage automatique, gestion de la sécurité réseau pour l\'accès à distance, et optimisation de la consommation pour un fonctionnement 24/7.', '2023-11-10 16:45:00', '2026-02-01 10:04:39', 11),
(5, 'Drone FPV Racing Personnalisé', 'Quadricoptère FPV pour course de drones avec frame personnalisé.', 'Station de développement et de contrôle IoT construite autour d\'un Raspberry Pi 4. Le système comprend plusieurs modules connectés : caméra pour vision artificielle, module GPS, capteurs environnementaux et interfaces de communication. Boîtier rouge imprimé 3D avec tous les ports accessibles. Cette station sert de hub central pour collecter, traiter et transmettre des données depuis divers capteurs distribués dans l\'espace du Fablab.', 'Raspberry Pi 4, Caméra Pi v2, Module GPS, Capteurs I2C multiples, WiFi/Ethernet, Python, Node-RED, MQTT', 'image_projet5.png', 'Processing puissant Raspberry Pi 4|Vision artificielle avec caméra|Géolocalisation GPS|Connectivité WiFi et Ethernet|Interface web de monitoring|Communication MQTT|Boîtier modulaire personnalisé', 'Configuration optimale des services système pour démarrage automatique, gestion de la sécurité réseau pour l\'accès à distance, et optimisation de la consommation pour un fonctionnement 24/7.', '2024-03-20 08:30:00', '2026-02-01 10:04:39', 11);

-- --------------------------------------------------------

--
-- Structure de la table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `categorie` varchar(100) DEFAULT NULL,
  `type` enum('local','youtube') DEFAULT 'local',
  `fichier` varchar(255) DEFAULT NULL,
  `youtube_id` varchar(50) DEFAULT NULL,
  `vignette` varchar(255) DEFAULT NULL,
  `vues` int DEFAULT '0',
  `duree` varchar(20) DEFAULT NULL,
  `auteur` varchar(100) DEFAULT NULL,
  `likes` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_categorie` (`categorie`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_vues` (`vues`),
  KEY `idx_type` (`type`),
  KEY `idx_youtube_id` (`youtube_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `videos`
--

INSERT INTO `videos` (`id`, `titre`, `description`, `categorie`, `type`, `fichier`, `youtube_id`, `vignette`, `vues`, `duree`, `auteur`, `likes`, `created_at`) VALUES
(3, 'Introduction au Fablab', 'Présentation générale du FabLab et de ses projets innovants en robotique.', 'Présentation', 'youtube', NULL, 'x8Pc9hqTEO8', 'https://img.youtube.com/vi/x8Pc9hqTEO8/hqdefault.jpg', 135, NULL, 'FabLab Team', 0, '2025-12-12 20:54:33'),
(4, 'Robotique & Impression 3D', 'Projet étudiant de robotique utilisant des pièces imprimées en 3D.', 'Robotique', 'youtube', NULL, 'mSyo25hKnfo', 'https://img.youtube.com/vi/mSyo25hKnfo/hqdefault.jpg', 11, NULL, 'FabLab Team', 0, '2025-12-12 20:54:33'),
(5, 'Impression 3D avancée', 'Techniques d\'impression 3D complexes utilisées dans le FabLab.', 'Impression 3D', 'youtube', NULL, 'Ikownb7GSjE', 'https://img.youtube.com/vi/Ikownb7GSjE/hqdefault.jpg', 2, NULL, 'FabLab Team', 0, '2025-12-12 20:54:33'),
(6, 'Atelier Robotique', 'Démonstration robotique et présentation des projets étudiants associés.', 'Robotique', 'youtube', NULL, 'msY6LTbBc2s', 'https://img.youtube.com/vi/msY6LTbBc2s/hqdefault.jpg', 4, NULL, 'FabLab Team', 0, '2025-12-12 20:54:33');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `fk_commentaires_user_id` FOREIGN KEY (`user_id`) REFERENCES `connexion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_commentaires_video_id` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
