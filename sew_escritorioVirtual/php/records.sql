-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-12-2023 a las 04:17:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `records`
--
CREATE DATABASE IF NOT EXISTS `records` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `records`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `nombre` varchar(32) NOT NULL,
  `apellidos` varchar(32) NOT NULL,
  `nivel` varchar(32) NOT NULL,
  `tiempo` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`nombre`, `apellidos`, `nivel`, `tiempo`) VALUES
('Adrian', 'Estrada', 'facil', '00:00:18'),
('Lucas', 'Martinez', 'facil', '00:00:56'),
('Adrian', 'Estrada', 'facil', '00:00:19'),
('Lucas', 'Santamarina', 'facil', '00:00:16'),
('Adrian', 'Estrada', 'facil', '00:00:21'),
('Adrian', 'Estrada', 'facil', '00:00:42'),
('Ruben ', 'Castro', 'facil', '00:01:10');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
