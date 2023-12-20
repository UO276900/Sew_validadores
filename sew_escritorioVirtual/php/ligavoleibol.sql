SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `ligavoleibol` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `ligavoleibol`;

CREATE TABLE `entrenadores` (
  `idEntrenador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `idEquipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `entrenadores` (`idEntrenador`, `nombre`, `apellido`, `edad`, `idEquipo`) VALUES
(1, 'Maria', 'Corral', 35, 1),
(2, 'Juan', 'González', 40, 2),
(3, 'Luisa', 'Fernández', 38, 3),
(4, 'Javier', 'Rodríguez', 42, 4),
(5, 'Elena', 'Martínez', 37, 5),
(6, 'Alejandro', 'Gómez', 39, 6),
(7, 'Andrea', 'López', 41, 7),
(8, 'Roberto', 'Sánchez', 36, 8),
(9, 'Carmen', 'Fernández', 40, 9),
(10, 'Pablo', 'Martín', 37, 10);

CREATE TABLE `equipos` (
  `idEquipo` int(11) NOT NULL,
  `nombreEquipo` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `puntos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `equipos` (`idEquipo`, `nombreEquipo`, `ciudad`, `categoria`, `puntos`) VALUES
(1, 'CV Oviedo', 'Oviedo', 'Primera Nacional Masculina', 0),
(2, 'Galdakao', 'Bilbao', 'Primera Nacional Masculina', 0),
(3, 'VP Madrid', 'Madrid', 'Primera Nacional Masculina', 0),
(4, 'Rivas', 'Madrid', 'Primera Nacional Masculina', 0),
(5, 'Coslada', 'Madrid', 'Primera Nacional Masculina', 0),
(6, 'Chamartín', 'Madrid', 'Primera Nacional Masculina', 0),
(7, 'Burgas', 'A Coruña', 'Primera Nacional Masculina', 0),
(8, 'Parla', 'Madrid', 'Primera Nacional Masculina', 0),
(9, 'Arona', 'Tenerife', 'Primera Nacional Masculina', 0),
(10, 'CV Mostoles', 'Madrid', 'Primera Nacional Masculina', 0),
(11, 'Burgos', 'Burgos', 'Primera Nacional Masculina', 0);

CREATE TABLE `estadisticas` (
  `idEstadistica` int(11) NOT NULL,
  `idJugador` int(11) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `bloqueos` int(11) DEFAULT NULL,
  `ataquesExitosos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `jugadores` (
  `idJugador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `altura` decimal(5,2) DEFAULT NULL,
  `posicion` varchar(50) DEFAULT NULL,
  `idEquipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `jugadores` (`idJugador`, `nombre`, `apellido`, `edad`, `altura`, `posicion`, `idEquipo`) VALUES
(1, 'Nicolas', 'Navarro', 22, 1.84, 'Central', 1),
(2, 'Rafael', 'Rodriguez', 23, 1.78, 'Receptor', 1),
(3, 'Pelayo', 'Fernandez', 20, 1.81, 'Central', 1),
(4, 'Carlos', 'Perez', 21, 2.06, 'Central', 1),
(5, 'Diego', 'Morales', 15, 1.74, 'Receptor', 1),
(6, 'Alejandro', 'Espinosa', 20, 1.81, 'Central', 1),
(7, 'Lucas', 'Castro', 20, 1.75, 'Libero', 1),
(8, 'Alejandro', 'Maguregui', 20, 1.92, 'Colocador', 1),
(9, 'Marcos', 'Arguelles', 19, 1.85, 'Opuesto', 1),
(10, 'Mateo', 'Rodriguez', 17, 1.70, 'Libero', 1),
(11, 'Diego', 'Charro', 21, 1.88, 'Central', 1),
(12, 'Nicolas', 'Nicolez', 15, 1.73, 'Colocador', 1),
(13, 'Pelayo', 'Rodriguez', 20, 1.78, 'Receptor', 1),
(14, 'Santos', 'Bravo', 21, 1.82, 'Colocador', 1),
(15, 'Samuel', 'Mesa', 20, 1.81, 'Opuesto', 1),
(16, 'Javier', 'Kelly', 22, 1.86, 'Central', 1),
(17, 'Jorge', 'Jorginez', 17, 1.73, 'Colocador', 1),
(18, 'Adrian', 'Estrada', 22, 1.75, 'Receptor', 1),
(19, 'Juan', 'González', 22, 1.84, 'Central', 2),
(20, 'Pedro', 'Martínez', 24, 1.81, 'Central', 2),
(21, 'Diego', 'Gómez', 26, 1.81, 'Opuesto', 2),
(22, 'Carlos', 'Sánchez', 28, 1.92, 'Colocador', 2),
(23, 'Alberto', 'Ortega', 30, 1.70, 'Libero', 2),
(24, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 2),
(25, 'Miguel', 'García', 25, 1.88, 'Central', 2),
(26, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 2),
(27, 'Javier', 'Gómez', 29, 1.86, 'Central', 2),
(28, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 2),
(29, 'Pablo', 'Hernández', 22, 1.79, 'Colocador', 3),
(30, 'Javier', 'Fernández', 24, 1.82, 'Opuesto', 3),
(31, 'Alejandro', 'Martínez', 26, 1.88, 'Central', 3),
(32, 'Raúl', 'Gómez', 28, 1.91, 'Colocador', 3),
(33, 'Alberto', 'Ortega', 30, 1.70, 'Libero', 3),
(34, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 3),
(35, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 3),
(36, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 3),
(37, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 3),
(38, 'Sergio', 'Gómez', 29, 1.86, 'Central', 3),
(39, 'Ignacio', 'Sánchez', 22, 1.82, 'Receptor', 4),
(40, 'Miguel', 'González', 24, 1.89, 'Colocador', 4),
(41, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 4),
(42, 'Silvia', 'Sánchez', 29, 1.78, 'Opuesto', 4),
(43, 'Javier', 'Fernández', 24, 1.82, 'Opuesto', 4),
(44, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 4),
(45, 'Laura', 'Pérez', 31, 1.87, 'Central', 4),
(46, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 4),
(47, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 4),
(48, 'Miguel', 'García', 25, 1.88, 'Central', 4),
(49, 'Marcos', 'Martínez', 22, 1.80, 'Colocador', 5),
(50, 'Daniel', 'Serrano', 24, 1.88, 'Central', 5),
(51, 'Javier', 'Fernández', 26, 1.89, 'Receptor', 5),
(52, 'Luis', 'González', 28, 1.91, 'Colocador', 5),
(53, 'Jorge', 'García', 30, 1.83, 'Central', 5),
(54, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 5),
(55, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 5),
(56, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 5),
(57, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 5),
(58, 'Marta', 'Rodríguez', 31, 1.84, 'Libero', 5),
(59, 'Diego', 'Hernández', 22, 1.78, 'Libero', 6),
(60, 'Ángel', 'Serrano', 23, 1.85, 'Opuesto', 6),
(61, 'Alberto', 'Martínez', 25, 1.88, 'Central', 6),
(62, 'Raúl', 'Gómez', 28, 1.91, 'Colocador', 6),
(63, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 6),
(64, 'Pablo', 'Hernández', 22, 1.79, 'Colocador', 6),
(65, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 6),
(66, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 6),
(67, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 6),
(68, 'Miguel', 'García', 25, 1.88, 'Central', 6),
(69, 'Alejandro', 'González', 22, 1.84, 'Central', 7),
(70, 'Sergio', 'Martínez', 24, 1.81, 'Colocador', 7),
(71, 'David', 'Fernández', 26, 1.89, 'Receptor', 7),
(72, 'Laura', 'Rodríguez', 28, 1.91, 'Libero', 7),
(73, 'Carlos', 'Gómez', 30, 1.83, 'Central', 7),
(74, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 7),
(75, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 7),
(76, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 7),
(77, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 7),
(78, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 7),
(79, 'Ana', 'González', 22, 1.76, 'Receptor', 8),
(80, 'Pablo', 'Martínez', 24, 1.82, 'Colocador', 8),
(81, 'Carlos', 'Fernández', 26, 1.88, 'Receptor', 8),
(82, 'Laura', 'Rodríguez', 28, 1.91, 'Libero', 8),
(83, 'Sergio', 'Gómez', 30, 1.83, 'Central', 8),
(84, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 8),
(85, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 8),
(86, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 8),
(87, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 8),
(88, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 8),
(89, 'Miguel', 'González', 22, 1.78, 'Receptor', 9),
(90, 'Sergio', 'Martínez', 24, 1.85, 'Opuesto', 9),
(91, 'Alberto', 'Fernández', 26, 1.88, 'Colocador', 9),
(92, 'Laura', 'Rodríguez', 28, 1.91, 'Libero', 9),
(93, 'Carlos', 'Gómez', 30, 1.83, 'Central', 9),
(94, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 9),
(95, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 9),
(96, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 9),
(97, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 9),
(98, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 9),
(99, 'Ana', 'González', 22, 1.76, 'Receptor', 10),
(100, 'Pablo', 'Martínez', 24, 1.82, 'Colocador', 10),
(101, 'Carlos', 'Fernández', 26, 1.88, 'Receptor', 10),
(102, 'Laura', 'Rodríguez', 28, 1.91, 'Libero', 10),
(103, 'Sergio', 'Gómez', 30, 1.83, 'Central', 10),
(104, 'Hugo', 'Muñoz', 30, 1.77, 'Libero', 10),
(105, 'Gonzalo', 'Fernández', 26, 1.83, 'Central', 10),
(106, 'Jorge', 'Jiménez', 30, 1.81, 'Libero', 10),
(107, 'Diego', 'Rodríguez', 32, 1.78, 'Receptor', 10),
(108, 'Adrián', 'Fernández', 27, 1.75, 'Receptor', 10);

CREATE TABLE `partidos` (
  `idPartido` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `idEquipoLocal` int(11) DEFAULT NULL,
  `idEquipoVisitante` int(11) DEFAULT NULL,
  `idEquipoGanador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


ALTER TABLE `entrenadores`
  ADD PRIMARY KEY (`idEntrenador`),
  ADD KEY `idEquipo` (`idEquipo`);

ALTER TABLE `equipos`
  ADD PRIMARY KEY (`idEquipo`);

ALTER TABLE `estadisticas`
  ADD PRIMARY KEY (`idEstadistica`),
  ADD KEY `idJugador` (`idJugador`);

ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`idJugador`),
  ADD KEY `idEquipo` (`idEquipo`);

ALTER TABLE `partidos`
  ADD PRIMARY KEY (`idPartido`),
  ADD KEY `idEquipoLocal` (`idEquipoLocal`),
  ADD KEY `idEquipoVisitante` (`idEquipoVisitante`),
  ADD KEY `idEquipoGanador` (`idEquipoGanador`);


ALTER TABLE `entrenadores`
  MODIFY `idEntrenador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `equipos`
  MODIFY `idEquipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `estadisticas`
  MODIFY `idEstadistica` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `jugadores`
  MODIFY `idJugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

ALTER TABLE `partidos`
  MODIFY `idPartido` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `entrenadores`
  ADD CONSTRAINT `entrenadores_ibfk_1` FOREIGN KEY (`idEquipo`) REFERENCES `equipos` (`idEquipo`);

ALTER TABLE `estadisticas`
  ADD CONSTRAINT `estadisticas_ibfk_1` FOREIGN KEY (`idJugador`) REFERENCES `jugadores` (`idJugador`);

ALTER TABLE `jugadores`
  ADD CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`idEquipo`) REFERENCES `equipos` (`idEquipo`);

ALTER TABLE `partidos`
  ADD CONSTRAINT `partidos_ibfk_1` FOREIGN KEY (`idEquipoLocal`) REFERENCES `equipos` (`idEquipo`),
  ADD CONSTRAINT `partidos_ibfk_2` FOREIGN KEY (`idEquipoVisitante`) REFERENCES `equipos` (`idEquipo`),
  ADD CONSTRAINT `partidos_ibfk_3` FOREIGN KEY (`idEquipoGanador`) REFERENCES `equipos` (`idEquipo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
