-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2023 at 02:46 PM
-- Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 8.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_learning_class`
--
CREATE DATABASE IF NOT EXISTS `my_learning_class` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `my_learning_class`;

-- --------------------------------------------------------

--
-- Table structure for table `CHAPTER`
--

CREATE TABLE `CHAPTER` (
  `idChapter` int(12) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `idCourse` int(10) UNSIGNED NOT NULL,
  `mediaVideo` varchar(30) DEFAULT NULL,
  `mediaRessource` varchar(30) DEFAULT NULL COMMENT 'An additional ressource file for the student',
  `position` int(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'The position of the chapter in the course (start from 1)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CHAPTER`
--

INSERT INTO `CHAPTER` (`idChapter`, `title`, `createdAt`, `updatedAt`, `idCourse`, `mediaVideo`, `mediaRessource`, `position`) VALUES
(1, 'Introduction à HTML et CSS', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, NULL, NULL, 1),
(2, 'Structure de base d\'une page HTML', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, NULL, NULL, 2),
(3, 'Styliser avec CSS', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, NULL, NULL, 3),
(4, 'Introduction à JavaScript', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, NULL, NULL, 1),
(5, 'Variables et opérateurs', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, NULL, NULL, 2),
(6, 'Contrôle de flux et boucles', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, NULL, NULL, 3),
(7, 'Les bases de React JS', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 3, NULL, NULL, 1),
(8, 'Composants et états', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 3, NULL, NULL, 2),
(9, 'Gestion d\'événements', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 3, NULL, NULL, 3),
(10, 'Principes d\'Angular', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 4, NULL, NULL, 1),
(11, 'Composants et directives', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 4, NULL, NULL, 2),
(12, 'Services et injection de dépendances', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 4, NULL, NULL, 3),
(13, 'Vue.js - Présentation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 5, NULL, NULL, 1),
(14, 'Composants Vue.js', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 5, NULL, NULL, 2),
(15, 'Gestion d\'états avec Vuex', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 5, NULL, NULL, 3),
(16, 'Node.js - Introduction', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 6, NULL, NULL, 1),
(17, 'Modules et gestion de paquets NPM', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 6, NULL, NULL, 2),
(18, 'Créer un serveur HTTP avec Node.js', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 6, NULL, NULL, 3),
(19, 'Express.js - Présentation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 7, NULL, NULL, 1),
(20, 'Middleware et routage', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 7, NULL, NULL, 2),
(21, 'Gestion des erreurs', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 7, NULL, NULL, 3),
(22, 'Les bases de PHP', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 8, NULL, NULL, 1),
(23, 'Tableaux et boucles en PHP', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 8, NULL, NULL, 2),
(24, 'Fonctions et gestion des erreurs', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 8, NULL, NULL, 3),
(25, 'Introduction à Laravel', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 9, NULL, NULL, 1),
(26, 'Routage et contrôleurs', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 9, NULL, NULL, 2),
(27, 'Modèles et migrations', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 9, NULL, NULL, 3),
(28, 'Python pour le web', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 10, NULL, NULL, 1),
(29, 'Introduction à Django', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 10, NULL, NULL, 2),
(30, 'Créer une application Flask', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 10, NULL, NULL, 3),
(31, 'Ruby on Rails - Présentation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 11, NULL, NULL, 1),
(32, 'Contrôleurs et vues', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 11, NULL, NULL, 2),
(33, 'Modèles et migrations', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 11, NULL, NULL, 3),
(34, 'Java pour le développement web', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 12, NULL, NULL, 1),
(35, 'Introduction à Spring', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 12, NULL, NULL, 2),
(36, 'Hibernate et JPA', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 12, NULL, NULL, 3),
(37, 'ASP.NET Core - Introduction', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 13, NULL, NULL, 1),
(38, 'Contrôleurs et actions', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 13, NULL, NULL, 2),
(39, 'Modèles et vues Razor', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 13, NULL, NULL, 3),
(40, 'Conception de bases de données', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 14, NULL, NULL, 1),
(41, 'Normalisation et modélisation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 14, NULL, NULL, 2),
(42, 'Requêtes SQL', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 14, NULL, NULL, 3),
(43, 'Bases de données NoSQL - Présentation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 15, NULL, NULL, 1),
(44, 'MongoDB - Installation et utilisation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 15, NULL, NULL, 2),
(45, 'Autres bases de données NoSQL', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 15, NULL, NULL, 3),
(46, 'Développement mobile avec React Native', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 16, NULL, NULL, 1),
(47, 'Composants et navigation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 16, NULL, NULL, 2),
(48, 'Gestion d\'états et API', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 16, NULL, NULL, 3),
(49, 'Démarrer avec HTML5', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 17, NULL, NULL, 1),
(50, 'Éléments multimédias et formulaires', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 17, NULL, NULL, 2),
(51, 'HTML5 Canvas et géolocalisation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 17, NULL, NULL, 3),
(52, 'Introduction à CSS3', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 18, NULL, NULL, 1),
(53, 'Sélecteurs et modèles de boîte', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 18, NULL, NULL, 2),
(54, 'Transitions et animations CSS3', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 18, NULL, NULL, 3),
(55, 'Concepts de base de JavaScript', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 19, NULL, NULL, 1),
(56, 'Objets et fonctions', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 19, NULL, NULL, 2),
(57, 'DOM et événements', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 19, NULL, NULL, 3),
(58, 'Comprendre React', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 20, NULL, NULL, 1),
(59, 'Gestion de l\'état avec les hooks', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 20, NULL, NULL, 2),
(60, 'React Router et performance', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 20, NULL, NULL, 3),
(61, 'Introduction à Angular', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 21, NULL, NULL, 1),
(62, 'Routage et navigation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 21, NULL, NULL, 2),
(63, 'Requêtes HTTP et tests', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 21, NULL, NULL, 3),
(64, 'Vue.js pour les débutants', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 22, NULL, NULL, 1),
(65, 'Gestion des données et méthodes', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 22, NULL, NULL, 2),
(66, 'Routage et mixins Vue.js', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 22, NULL, NULL, 3),
(67, 'Node.js - Les bases', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 23, NULL, NULL, 1),
(68, 'Gestion des fichiers et modules', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 23, NULL, NULL, 2),
(69, 'Création d\'une API REST avec Node.js', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 23, NULL, NULL, 3),
(70, 'Express.js - Introduction', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 24, NULL, NULL, 1),
(71, 'Gestion des requêtes et réponses', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 24, NULL, NULL, 2),
(72, 'Sécurité et authentification', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 24, NULL, NULL, 3),
(73, 'PHP - Les fondamentaux', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 25, NULL, NULL, 1),
(74, 'Formulaires et sessions', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 25, NULL, NULL, 2),
(75, 'Sécurité et bonnes pratiques', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 25, NULL, NULL, 3),
(76, 'Maîtriser Laravel', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 26, NULL, NULL, 1),
(77, 'Eloquent ORM et relations', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 26, NULL, NULL, 2),
(78, 'Blade et middleware', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 26, NULL, NULL, 3),
(79, 'Introduction au développement web Python', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 27, NULL, NULL, 1),
(80, 'Django - Modèles et vues', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 27, NULL, NULL, 2),
(81, 'Flask - Routage et modèles', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 27, NULL, NULL, 3),
(82, 'Ruby on Rails - Les bases', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 28, NULL, NULL, 1),
(83, 'ActiveRecord et validations', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 28, NULL, NULL, 2),
(84, 'ActionView et helpers', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 28, NULL, NULL, 3),
(85, 'Java pour le web', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 29, NULL, NULL, 1),
(86, 'Spring MVC et contrôleurs', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 29, NULL, NULL, 2),
(87, 'Sécurité et tests avec Spring', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 29, NULL, NULL, 3),
(88, 'ASP.NET Core - Les bases', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 30, NULL, NULL, 1),
(89, 'Entity Framework Core', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 30, NULL, NULL, 2),
(90, 'Authentification et autorisation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 30, NULL, NULL, 3),
(91, 'Conception de bases de données', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 31, NULL, NULL, 1),
(92, 'Joins et sous-requêtes SQL', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 31, NULL, NULL, 2),
(93, 'Optimisation et indexation', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 31, NULL, NULL, 3),
(94, 'NoSQL - Introduction', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 32, NULL, NULL, 1),
(95, 'Opérations CRUD avec MongoDB', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 32, NULL, NULL, 2),
(96, 'Cassandra et Couchbase', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 32, NULL, NULL, 3),
(97, 'React Native - Introduction', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 33, NULL, 'pdf01.pdf', 1),
(98, 'Composants mobiles et styles', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 33, 'vid01.mp4', 'pdf01.pdf', 2),
(99, 'API et gestion d\'état avec Redux', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 33, 'vid01.mp4', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `CHAPTER_PROGRESS`
--

CREATE TABLE `CHAPTER_PROGRESS` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idChapter` int(12) UNSIGNED NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1: to do   2: started   3: finished',
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CHAPTER_PROGRESS`
--

INSERT INTO `CHAPTER_PROGRESS` (`idUser`, `idChapter`, `status`, `createdAt`) VALUES
(1, 25, '1', '2023-05-09 15:17:19'),
(1, 26, '1', '2023-05-09 15:17:19'),
(1, 27, '1', '2023-05-09 15:17:19'),
(1, 91, '1', '2023-05-09 15:56:41'),
(1, 92, '1', '2023-05-09 15:56:41'),
(1, 93, '1', '2023-05-09 15:56:41'),
(1, 94, '1', '2023-05-09 11:14:09'),
(1, 95, '1', '2023-05-09 11:14:09'),
(1, 96, '1', '2023-05-09 11:14:09'),
(1, 97, '1', '2023-05-09 13:31:30'),
(1, 98, '1', '2023-05-09 13:31:30'),
(1, 99, '1', '2023-05-09 13:31:30'),
(18, 85, '1', '2023-05-10 08:32:54'),
(18, 86, '1', '2023-05-10 08:32:54'),
(18, 87, '1', '2023-05-10 08:32:54');

-- --------------------------------------------------------

--
-- Table structure for table `COURSE`
--

CREATE TABLE `COURSE` (
  `idCourse` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `visibility` enum('1','2','3') NOT NULL COMMENT '1: draft   2: public   3: private',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `codeCourseCategory` int(4) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL,
  `mediaBanner` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COURSE`
--

INSERT INTO `COURSE` (`idCourse`, `title`, `description`, `visibility`, `createdAt`, `updatedAt`, `codeCourseCategory`, `idUser`, `mediaBanner`) VALUES
(1, 'Fondamentaux d\'HTML et CSS', 'Apprenez les notions fondamentales d\'HTML et CSS pour créer des sites web réactifs et interactifs.', '1', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 1, 'img1.jpg'),
(2, 'Bases de JavaScript', 'Maîtrisez les bases de JavaScript et apprenez à créer des applications web dynamiques.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 2, 'img2.jpg'),
(3, 'Essentiels de React JS', 'Explorez les concepts et fonctionnalités clés de React JS, l\'une des bibliothèques frontales les plus populaires.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 3, 'img3.jpg'),
(4, 'Fondamentaux d\'Angular', 'Découvrez les concepts clés d\'Angular, un framework puissant pour la création d\'applications web modernes.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 4, 'img4.jpg'),
(5, 'Introduction à Vue.js', 'Découvrez Vue.js, un framework JavaScript polyvalent et performant pour la création d\'interfaces utilisateur.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 5, 'img5.jpg'),
(6, 'Crash Course de Node.js', 'Apprenez l\'essentiel de Node.js, un environnement d\'exécution JavaScript puissant pour la création d\'applications back-end évolutives.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 6, 'img1.jpg'),
(7, 'Fondamentaux d\'Express.js', 'Maîtrisez les fondamentaux d\'Express.js, un framework d\'application web populaire pour Node.js.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 7, 'img2.jpg'),
(8, 'Bases de PHP', 'Apprenez les bases de PHP, un langage de script largement utilisé pour la création d\'applications web dynamiques.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 8, 'img3.jpg'),
(9, 'Essentiels de Laravel', 'Découvrez les concepts clés de Laravel, un framework PHP puissant pour la création d\'applications web modernes.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 9, 'img4.jpg'),
(10, 'Python pour le développement web', 'Explorez l\'utilisation de Python dans le développement web et apprenez à créer des sites web dynamiques à l\'aide de Django et Flask.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 1, 'img5.jpg'),
(11, 'Crash Course de Ruby on Rails', 'Découvrez Ruby on Rails, un framework d\'application web full-stack pour la création d\'applications puissantes et évolutives.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 2, 'img1.jpg'),
(12, 'Développement web Java', 'Apprenez les notions essentielles de Java pour le développement web et créez des applications web dynamiques à l\'aide de Spring et Hibernate.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 3, 'img2.jpg'),
(13, 'Fondamentaux d\'ASP.NET Core', 'Découvrez les concepts clés d\'ASP.NET Core, un framework puissant pour la création d\'applications web modernes en utilisant C#.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 4, 'img3.jpg'),
(14, 'Conception de base de données et SQL', 'Apprenez les fondamentaux de la conception de bases de données, de la normalisation et du SQL pour gérer des bases de données relationnelles.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 5, 'img4.jpg'),
(15, 'Bases de données NoSQL', 'Explorez le monde des bases de données NoSQL, telles que MongoDB, et apprenez à stocker et gérer des données non structurées.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 6, 'img5.jpg'),
(16, 'Développement d\'applications mobiles avec React Native', 'Apprenez à créer des applications mobiles multiplateformes à l\'aide de React Native.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 3, 7, 'img1.jpg'),
(17, 'Introduction à HTML5', 'Commencez avec HTML5 et apprenez à créer des sites web réactifs et interactifs à partir de zéro.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 8, 'img1.jpg'),
(18, 'Essentiels de CSS3', 'Maîtrisez les essentiels de CSS3 et améliorez l\'apparence de vos sites web avec des styles et animations personnalisées.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 9, 'img2.jpg'),
(19, 'Fondamentaux de JavaScript', 'Apprenez les concepts de base de JavaScript et créez des applications web dynamiques avec facilité.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 1, 'img3.jpg'),
(20, 'Explorer React', 'Plongez dans React et apprenez à créer des applications web modernes et efficaces avec facilité.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 2, 'img4.jpg'),
(21, 'Démarrer avec Angular', 'Découvrez Angular, un framework puissant pour créer des applications web modernes et évolutives.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 3, 'img5.jpg'),
(22, 'Vue.js pour les débutants', 'Apprenez les bases de Vue.js, un framework JavaScript polyvalent et efficace pour la création d\'interfaces utilisateur.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1, 4, 'img6.jpg'),
(23, 'Les bases de Node.js', 'Saisissez l\'essentiel de Node.js, un environnement d\'exécution JavaScript puissant pour créer des applications back-end évolutives.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 5, 'img7.jpg'),
(24, 'Fondamentaux d\'Express.js', 'Maîtrisez les bases d\'Express.js, un framework d\'application web populaire pour Node.js.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 6, 'img8.jpg'),
(25, 'PHP pour le développement web', 'Apprenez les bases de PHP, un langage de script largement utilisé pour créer des applications web dynamiques.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 7, 'img9.jpg'),
(26, 'Maîtriser Laravel', 'Découvrez les concepts clés de Laravel, un framework PHP puissant pour créer des applications web modernes.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 8, 'img10.jpg'),
(27, 'Développement web avec Python', 'Explorez l\'utilisation de Python dans le développement web et apprenez à créer des sites web dynamiques avec Django et Flask.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 9, 'img11.jpg'),
(28, 'Cours accéléré de Ruby on Rails', 'Apprenez Ruby on Rails, un framework d\'application web full-stack pour créer des applications puissantes et évolutives.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 1, 'img12.jpg'),
(29, 'Développement web avec Java', 'Apprenez les concepts essentiels de Java pour le développement web et créez des applications web dynamiques avec Spring et Hibernate.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 2, 'img13.jpg'),
(30, 'Essentiels d\'ASP.NET Core', 'Découvrez les concepts clés d\'ASP.NET Core, un framework puissant pour créer des applicationsweb modernes en utilisant C#.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 3, 'img14.jpg'),
(31, 'Conception de bases de données et SQL', 'Apprenez les bases de la conception de bases de données, de la normalisation et du SQL pour gérer des bases de données relationnelles.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 4, 'img15.jpg'),
(32, 'Bases de données NoSQL', 'Découvrez le monde des bases de données NoSQL, telles que MongoDB, et apprenez à stocker et gérer des données non structurées.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2, 5, 'img16.jpg'),
(33, 'Développement d\'applications mobiles avec React Native', 'Apprenez à créer des applications mobiles multiplateformes en utilisant React Native.', '2', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 3, 6, 'img17.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `COURSE_BOOKMARK`
--

CREATE TABLE `COURSE_BOOKMARK` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idCourse` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COURSE_BOOKMARK`
--

INSERT INTO `COURSE_BOOKMARK` (`idUser`, `idCourse`) VALUES
(1, 1),
(1, 5),
(2, 2),
(2, 3),
(2, 6),
(2, 7),
(4, 1),
(4, 7),
(5, 3),
(5, 10),
(6, 1),
(6, 6),
(7, 5),
(7, 8),
(8, 2),
(8, 9),
(9, 3),
(9, 5),
(10, 1),
(10, 4),
(11, 2),
(11, 8),
(12, 4),
(12, 7),
(13, 1),
(13, 5),
(14, 2),
(14, 10),
(15, 3),
(15, 6),
(16, 5),
(16, 9),
(17, 1),
(17, 7),
(18, 4),
(18, 10),
(19, 2),
(19, 6),
(20, 3),
(20, 8),
(21, 1),
(21, 5),
(22, 3),
(22, 7),
(23, 2),
(23, 6),
(24, 1),
(24, 7),
(25, 3),
(25, 10),
(26, 1),
(26, 6),
(27, 5),
(27, 8),
(28, 2),
(28, 9),
(29, 3),
(29, 5),
(30, 1),
(30, 4);

-- --------------------------------------------------------

--
-- Table structure for table `COURSE_CATEGORY`
--

CREATE TABLE `COURSE_CATEGORY` (
  `codeCourseCategory` int(4) UNSIGNED NOT NULL,
  `label` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COURSE_CATEGORY`
--

INSERT INTO `COURSE_CATEGORY` (`codeCourseCategory`, `label`) VALUES
(1, 'Front-end'),
(2, 'Back-end'),
(3, 'Mobile');

-- --------------------------------------------------------

--
-- Table structure for table `COURSE_ENROLLMENT`
--

CREATE TABLE `COURSE_ENROLLMENT` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idCourse` int(10) UNSIGNED NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COURSE_ENROLLMENT`
--

INSERT INTO `COURSE_ENROLLMENT` (`idUser`, `idCourse`, `createdAt`) VALUES
(1, 9, '2023-05-09 15:17:19'),
(1, 31, '2023-05-09 15:56:41'),
(1, 32, '2023-05-09 11:14:09'),
(1, 33, '2023-05-09 13:31:30'),
(14, 1, '2023-05-09 06:15:19'),
(14, 4, '2023-05-09 06:15:19'),
(15, 3, '2023-05-09 06:15:19'),
(15, 7, '2023-05-09 06:15:19'),
(16, 2, '2023-05-09 06:15:19'),
(16, 6, '2023-05-09 06:15:19'),
(17, 1, '2023-05-09 06:15:19'),
(17, 7, '2023-05-09 06:15:19'),
(18, 3, '2023-05-09 06:15:19'),
(18, 10, '2023-05-09 06:15:19'),
(18, 29, '2023-05-10 08:32:54'),
(19, 1, '2023-05-09 06:15:19'),
(19, 6, '2023-05-09 06:15:19'),
(20, 5, '2023-05-09 06:15:19'),
(20, 8, '2023-05-09 06:15:19'),
(21, 2, '2023-05-09 06:15:19'),
(21, 9, '2023-05-09 06:15:19'),
(22, 3, '2023-05-09 06:15:19'),
(22, 5, '2023-05-09 06:15:19'),
(23, 1, '2023-05-09 06:15:19'),
(23, 4, '2023-05-09 06:15:19'),
(24, 2, '2023-05-09 06:15:19'),
(24, 8, '2023-05-09 06:15:19'),
(25, 4, '2023-05-09 06:15:19'),
(25, 7, '2023-05-09 06:15:19'),
(26, 1, '2023-05-09 06:15:19'),
(26, 5, '2023-05-09 06:15:19'),
(27, 2, '2023-05-09 06:15:19'),
(27, 10, '2023-05-09 06:15:19'),
(28, 3, '2023-05-09 06:15:19'),
(28, 4, '2023-05-09 06:15:19'),
(28, 6, '2023-05-09 06:15:19'),
(28, 10, '2023-05-09 06:15:19'),
(29, 2, '2023-05-09 06:15:19'),
(29, 5, '2023-05-09 06:15:19'),
(29, 6, '2023-05-09 06:15:19'),
(29, 9, '2023-05-09 06:15:19'),
(30, 1, '2023-05-09 06:15:19'),
(30, 3, '2023-05-09 06:15:19'),
(30, 7, '2023-05-09 06:15:19'),
(30, 8, '2023-05-09 06:15:19');

-- --------------------------------------------------------

--
-- Table structure for table `MEDIA`
--

CREATE TABLE `MEDIA` (
  `filename` varchar(30) NOT NULL COMMENT 'The name of the file of the video stored in the server ',
  `name` varchar(100) NOT NULL COMMENT ' 	The original name of the video, before renaming the file to make it unique ',
  `mimeType` varchar(15) NOT NULL,
  `duration` int(6) UNSIGNED DEFAULT NULL COMMENT 'Only for video : the duration of the video in seconds ',
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MEDIA`
--

INSERT INTO `MEDIA` (`filename`, `name`, `mimeType`, `duration`, `createdAt`) VALUES
('img1.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img10.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img11.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img12.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img13.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img14.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img15.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img16.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img17.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img2.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img3.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img4.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img5.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img6.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img7.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img8.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('img9.jpg', 'my-course-image.jpg', 'image/jpeg', NULL, '2023-05-11 08:56:07'),
('pdf01.pdf', 'course-soluce.pdf', 'application/pdf', NULL, '2023-05-11 08:56:07'),
('vid01.mp4', 'a-chapter-video', 'video/mp4', 5, '2023-05-11 08:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `PERMISSION`
--

CREATE TABLE `PERMISSION` (
  `codePermission` int(4) UNSIGNED NOT NULL,
  `action` enum('create','read','read_own','read_any','update','update_own','update_any','delete','delete_any','delete_own') NOT NULL,
  `ressource` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PERMISSION`
--

INSERT INTO `PERMISSION` (`codePermission`, `action`, `ressource`) VALUES
(1, 'delete_own', 'courseEnrollment'),
(2, 'create', 'course'),
(3, 'update_own', 'course'),
(4, 'delete_own', 'course'),
(5, 'delete_any', 'course'),
(6, 'create', 'user'),
(7, 'delete_any', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `ROLE`
--

CREATE TABLE `ROLE` (
  `codeRole` int(2) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `label` varchar(40) NOT NULL COMMENT 'A more humain-friendly name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ROLE`
--

INSERT INTO `ROLE` (`codeRole`, `name`, `label`) VALUES
(1, 'user', 'Utilisateur'),
(2, 'teacher', 'Enseignant'),
(3, 'admin', 'Administrateur');

-- --------------------------------------------------------

--
-- Table structure for table `ROLE_HAS_PERMISSION`
--

CREATE TABLE `ROLE_HAS_PERMISSION` (
  `codeRole` int(2) UNSIGNED NOT NULL,
  `codePermission` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ROLE_HAS_PERMISSION`
--

INSERT INTO `ROLE_HAS_PERMISSION` (`codeRole`, `codePermission`) VALUES
(1, 1),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 2),
(3, 3),
(3, 5),
(3, 6),
(3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `SESSION`
--

CREATE TABLE `SESSION` (
  `idSession` varchar(64) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `SESSION_DATA`
--

CREATE TABLE `SESSION_DATA` (
  `idSessionData` int(15) UNSIGNED NOT NULL,
  `dataKey` varchar(30) NOT NULL,
  `dataValue` mediumtext DEFAULT NULL COMMENT 'May have serialized content',
  `isFlash` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' If true, the data is temporary. It will be deleted after the first read ',
  `idSession` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `codeRole` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `USER`
--

INSERT INTO `USER` (`idUser`, `firstname`, `lastname`, `email`, `password`, `createdAt`, `updatedAt`, `codeRole`) VALUES
(1, 'Jere', 'Arci', 'existant@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(2, 'Jean', 'Dupont', 'jean.dupont@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(3, 'Marie', 'Martin', 'marie.martin@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(4, 'Nicolas', 'Durand', 'nicolas.durand@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(5, 'Sophie', 'Petit', 'sophie.petit@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(6, 'Lucas', 'Dubois', 'lucas.dubois@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(7, 'Emma', 'Lefebvre', 'emma.lefebvre@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(8, 'Paul', 'Moreau', 'paul.moreau@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(9, 'Chloé', 'Simon', 'chloe.simon@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(10, 'Maxime', 'Laurent', 'maxime.laurent@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(11, 'Sarah', 'Girard', 'sarah.girard@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 2),
(12, 'Emma', 'Martin', 'emma.martin@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(13, 'Lucas', 'Bernard', 'lucas.bernard@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(14, 'Léa', 'Petit', 'lea.petit@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(15, 'Hugo', 'Lefevre', 'hugo.lefevre@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(16, 'Manon', 'Moreau', 'manon.moreau@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(17, 'Romain', 'Girard', 'romain.girard@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(18, 'Marie', 'Dufour', 'marie.dufour@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(19, 'Théo', 'Leroy', 'theo.leroy@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(20, 'Camille', 'Bonnet', 'camille.bonnet@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(21, 'Mael', 'Robin', 'mael.robin@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(22, 'Clément', 'Simon', 'clement.simon@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(23, 'Zoé', 'Garcia', 'zoe.garcia@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(24, 'Adrien', 'Morin', 'adrien.morin@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(25, 'Inès', 'Dubois', 'ines.dubois@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(26, 'Ethan', 'Lopez', 'ethan.lopez@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(27, 'Louise', 'Martinez', 'louise.martinez@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(28, 'Nathan', 'Blanchard', 'nathan.blanchard@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(29, 'Jade', 'David', 'jade.david@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(30, 'Louis', 'Bertrand', 'louis.bertrand@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1),
(31, 'Ambre', 'Morel', 'ambre.morel@example.com', '$2y$10$ihh8mY4latiYOGDG5H5tge2qOYnJKVHeROTJzV.sfvwwvNWtlUwFe', '2023-05-09 06:15:19', '2023-05-09 06:15:19', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `CHAPTER`
--
ALTER TABLE `CHAPTER`
  ADD PRIMARY KEY (`idChapter`),
  ADD KEY `idCourse` (`idCourse`),
  ADD KEY `idNextChapter` (`position`),
  ADD KEY `mediaVideo` (`mediaVideo`),
  ADD KEY `mediaRessource` (`mediaRessource`);

--
-- Indexes for table `CHAPTER_PROGRESS`
--
ALTER TABLE `CHAPTER_PROGRESS`
  ADD PRIMARY KEY (`idUser`,`idChapter`),
  ADD KEY `idChapter` (`idChapter`);

--
-- Indexes for table `COURSE`
--
ALTER TABLE `COURSE`
  ADD PRIMARY KEY (`idCourse`),
  ADD KEY `codeCourseCategory` (`codeCourseCategory`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `filenameBanner` (`mediaBanner`);

--
-- Indexes for table `COURSE_BOOKMARK`
--
ALTER TABLE `COURSE_BOOKMARK`
  ADD PRIMARY KEY (`idUser`,`idCourse`),
  ADD KEY `idCourse` (`idCourse`);

--
-- Indexes for table `COURSE_CATEGORY`
--
ALTER TABLE `COURSE_CATEGORY`
  ADD PRIMARY KEY (`codeCourseCategory`);

--
-- Indexes for table `COURSE_ENROLLMENT`
--
ALTER TABLE `COURSE_ENROLLMENT`
  ADD PRIMARY KEY (`idUser`,`idCourse`),
  ADD KEY `idCouse` (`idCourse`);

--
-- Indexes for table `MEDIA`
--
ALTER TABLE `MEDIA`
  ADD PRIMARY KEY (`filename`);

--
-- Indexes for table `PERMISSION`
--
ALTER TABLE `PERMISSION`
  ADD PRIMARY KEY (`codePermission`);

--
-- Indexes for table `ROLE`
--
ALTER TABLE `ROLE`
  ADD PRIMARY KEY (`codeRole`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `ROLE_HAS_PERMISSION`
--
ALTER TABLE `ROLE_HAS_PERMISSION`
  ADD PRIMARY KEY (`codeRole`,`codePermission`),
  ADD KEY `codePermission` (`codePermission`);

--
-- Indexes for table `SESSION`
--
ALTER TABLE `SESSION`
  ADD PRIMARY KEY (`idSession`);

--
-- Indexes for table `SESSION_DATA`
--
ALTER TABLE `SESSION_DATA`
  ADD PRIMARY KEY (`idSessionData`),
  ADD KEY `idSession` (`idSession`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `codeRole` (`codeRole`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `CHAPTER`
--
ALTER TABLE `CHAPTER`
  MODIFY `idChapter` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `COURSE`
--
ALTER TABLE `COURSE`
  MODIFY `idCourse` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `SESSION_DATA`
--
ALTER TABLE `SESSION_DATA`
  MODIFY `idSessionData` int(15) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CHAPTER`
--
ALTER TABLE `CHAPTER`
  ADD CONSTRAINT `CHAPTER_ibfk_1` FOREIGN KEY (`idCourse`) REFERENCES `COURSE` (`idCourse`),
  ADD CONSTRAINT `CHAPTER_ibfk_3` FOREIGN KEY (`mediaVideo`) REFERENCES `MEDIA` (`filename`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `CHAPTER_ibfk_4` FOREIGN KEY (`mediaRessource`) REFERENCES `MEDIA` (`filename`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `CHAPTER_PROGRESS`
--
ALTER TABLE `CHAPTER_PROGRESS`
  ADD CONSTRAINT `CHAPTER_PROGRESS_ibfk_1` FOREIGN KEY (`idChapter`) REFERENCES `CHAPTER` (`idChapter`),
  ADD CONSTRAINT `CHAPTER_PROGRESS_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`);

--
-- Constraints for table `COURSE`
--
ALTER TABLE `COURSE`
  ADD CONSTRAINT `COURSE_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`),
  ADD CONSTRAINT `COURSE_ibfk_2` FOREIGN KEY (`codeCourseCategory`) REFERENCES `COURSE_CATEGORY` (`codeCourseCategory`),
  ADD CONSTRAINT `COURSE_ibfk_3` FOREIGN KEY (`mediaBanner`) REFERENCES `MEDIA` (`filename`);

--
-- Constraints for table `COURSE_BOOKMARK`
--
ALTER TABLE `COURSE_BOOKMARK`
  ADD CONSTRAINT `COURSE_BOOKMARK_ibfk_1` FOREIGN KEY (`idCourse`) REFERENCES `COURSE` (`idCourse`),
  ADD CONSTRAINT `COURSE_BOOKMARK_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`);

--
-- Constraints for table `COURSE_ENROLLMENT`
--
ALTER TABLE `COURSE_ENROLLMENT`
  ADD CONSTRAINT `COURSE_ENROLLMENT_ibfk_1` FOREIGN KEY (`idCourse`) REFERENCES `COURSE` (`idCourse`),
  ADD CONSTRAINT `COURSE_ENROLLMENT_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`);

--
-- Constraints for table `ROLE_HAS_PERMISSION`
--
ALTER TABLE `ROLE_HAS_PERMISSION`
  ADD CONSTRAINT `ROLE_HAS_PERMISSION_ibfk_1` FOREIGN KEY (`codePermission`) REFERENCES `PERMISSION` (`codePermission`),
  ADD CONSTRAINT `ROLE_HAS_PERMISSION_ibfk_2` FOREIGN KEY (`codeRole`) REFERENCES `ROLE` (`codeRole`);

--
-- Constraints for table `SESSION_DATA`
--
ALTER TABLE `SESSION_DATA`
  ADD CONSTRAINT `SESSION_DATA_ibfk_1` FOREIGN KEY (`idSession`) REFERENCES `SESSION` (`idSession`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `USER`
--
ALTER TABLE `USER`
  ADD CONSTRAINT `USER_ibfk_1` FOREIGN KEY (`codeRole`) REFERENCES `ROLE` (`codeRole`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
