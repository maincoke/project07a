-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2019 a las 04:02:24
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.1.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `schedule_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `identevt` smallint(6) NOT NULL,
  `evttitles` tinytext COLLATE latin1_spanish_ci NOT NULL,
  `evtbgindt` date NOT NULL,
  `evtbgintm` time DEFAULT NULL,
  `evtenddat` date DEFAULT NULL,
  `evtendtim` time DEFAULT NULL,
  `evtfulday` tinyint(1) NOT NULL,
  `fk_iduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`identevt`, `evttitles`, `evtbgindt`, `evtbgintm`, `evtenddat`, `evtendtim`, `evtfulday`, `fk_iduser`) VALUES
(1, 'Ensayo para el casting de pelicula', '2019-02-06', '10:00:00', '2019-02-06', '16:00:00', 0, 4),
(2, 'Asistir a consulta de odontología ', '2019-02-04', '10:00:00', '2019-02-04', '12:00:00', 0, 4),
(3, 'Reunión con amigos de club en casa de Robert', '2019-02-03', NULL, NULL, NULL, 1, 4),
(4, 'Asistir a casting al teatro British Hall', '2019-02-04', '14:00:00', '2019-02-04', '18:00:00', 0, 4),
(5, 'Cumpleaños de Aldus y Brian', '2019-02-05', NULL, NULL, NULL, 1, 4),
(6, 'Cita con el doctor Dimitri', '2019-02-07', '09:00:00', '2019-02-07', '10:00:00', 0, 4),
(7, 'Asistir a la Gala de Premios', '2019-02-10', NULL, NULL, NULL, 1, 4),
(8, 'Preparación para la Gala de premios', '2019-02-16', '17:00:00', '2019-02-16', '20:00:00', 0, 4),
(9, 'Reunión con la familia', '2019-02-16', NULL, NULL, NULL, 1, 4),
(10, 'Prueba y ensayo de escena con Vehículo', '2019-02-08', NULL, NULL, NULL, 1, 4),
(11, 'Comprar regalo para Dorothy', '2019-02-13', '09:00:00', '2019-02-13', '14:00:00', 0, 3),
(12, 'Cumpleaños de Dorothy', '2019-02-14', NULL, NULL, NULL, 1, 3),
(13, 'Reunión con mi hija Alice', '2019-02-03', '17:00:00', '2019-02-03', '22:00:00', 0, 3),
(14, 'Asistir a la consulta dermatológica', '2019-02-04', '08:00:00', '2019-02-04', '10:00:00', 0, 3),
(15, 'Cumplir con la clase de Actuación y Doblaje', '2019-02-05', NULL, NULL, NULL, 1, 3),
(16, 'Compras y pagos mensuales', '2019-02-06', NULL, NULL, NULL, 1, 3),
(17, 'Comienzo de rodaje de la Película', '2019-02-07', '08:00:00', '2019-02-08', '18:00:00', 0, 3),
(18, 'Reunión con productores y equipo de riesgo', '2019-02-09', '09:00:00', '2019-02-09', '15:00:00', 0, 3),
(19, 'Rodaje #2 y 3 de película', '2019-02-10', '13:00:00', '2019-02-10', '23:00:00', 0, 3),
(20, 'Reunión con Patric sobre escena de drama y riesgo', '2019-02-12', NULL, NULL, NULL, 1, 3),
(21, 'Asistir al evento Comic-Con de Texas', '2019-02-03', NULL, NULL, NULL, 1, 2),
(22, 'Reunión con Dany y Forrest', '2019-02-09', '08:00:00', '2019-02-09', '16:00:00', 0, 2),
(23, 'Asistir a misa con Clarice', '2019-02-09', '19:00:00', '2019-02-09', '20:30:00', 0, 2),
(24, 'Cita con el kinesiólogo', '2019-02-04', '09:00:00', '2019-02-04', '12:00:00', 0, 2),
(25, 'Reparar y ajustar motor del carro', '2019-02-05', NULL, NULL, NULL, 1, 2),
(26, 'Asistir a la gala de premios', '2019-02-06', NULL, NULL, NULL, 1, 2),
(27, 'Reunión con el asesor financiero y contador', '2019-02-07', '09:00:00', '2019-02-07', '14:00:00', 0, 2),
(28, 'Fiesta cumpleaños de Clark', '2019-02-07', '17:00:00', '2019-02-07', '23:30:00', 0, 2),
(29, 'Asistir cita con Alice y Dany', '2019-02-08', NULL, NULL, NULL, 1, 2),
(30, 'Entrevista con Jimmy y Jay', '2019-02-10', NULL, NULL, NULL, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `identusr` int(11) NOT NULL,
  `namesusr` varchar(40) COLLATE latin1_spanish_ci NOT NULL,
  `dbirthusr` date NOT NULL,
  `emailuser` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `pworduser` varchar(256) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`identusr`, `namesusr`, `dbirthusr`, `emailuser`, `pworduser`) VALUES
(2, 'Harrison Ford', '1970-03-04', 'harrisonford43@nextu.com', '$2y$10$9QYGPLdI9heotNd9AF3rPO767nxABfVFgz7LB.7UiwdzI.nCGuTQi'),
(3, 'Liam Neeson', '1980-08-03', 'liamneeson38@nextu.com', '$2y$10$CQh7zaWWkymx8l5n2aEhpOh4Tg9WrSlh5YEWXFHODOlnLI3XoWxAe'),
(4, 'Jeff Bridges', '1975-10-14', 'jeffbridges1410@nextu.com', '$2y$10$iWC6pd1f95VrGTE9DRQT4OHE9.sEe2RTWi26DXH2YCLy1XWpjrJCC');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`identevt`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`identusr`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `identevt` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `identusr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
