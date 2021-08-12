-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-08-2021 a las 21:24:18
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pdftotext2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mec_mec`
--

CREATE TABLE `mec_mec` (
  `id` int(11) NOT NULL,
  `mec_doc` varchar(20) DEFAULT NULL,
  `mec_sec` varchar(20) DEFAULT NULL,
  `mec_persona` varchar(100) DEFAULT NULL,
  `mec_rev` varchar(20) DEFAULT NULL,
  `mec_fun` varchar(20) DEFAULT NULL,
  `mec_neto` decimal(10,2) DEFAULT NULL,
  `mec_afec` varchar(20) DEFAULT NULL,
  `mec_categ` varchar(20) DEFAULT NULL,
  `mec_hscs` varchar(20) DEFAULT NULL,
  `mec_antig` varchar(20) DEFAULT NULL,
  `mec_suple_doc` varchar(20) DEFAULT NULL,
  `mec_suple_sec` varchar(20) DEFAULT NULL,
  `mec_suple_desde` varchar(20) DEFAULT NULL,
  `mec_suple_hasta` varchar(20) DEFAULT NULL,
  `mec_fecha_planilla` varchar(50) DEFAULT NULL,
  `mec_nro_pesos` varchar(20) DEFAULT NULL,
  `mec_distrito_nro` varchar(20) DEFAULT NULL,
  `mec_distrito_denom` varchar(50) DEFAULT NULL,
  `mec_tiporg_nro` varchar(20) DEFAULT NULL,
  `mec_tiporg_denom` varchar(50) DEFAULT NULL,
  `mec_nroinst` varchar(20) DEFAULT NULL,
  `mec_inst_denom` varchar(50) DEFAULT NULL,
  `mec_rural` varchar(20) DEFAULT NULL,
  `mec_seccs` varchar(20) DEFAULT NULL,
  `mec_turnos` varchar(20) DEFAULT NULL,
  `mec_subvencion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mec_mec`
--

INSERT INTO `mec_mec` (`id`, `mec_doc`, `mec_sec`, `mec_persona`, `mec_rev`, `mec_fun`, `mec_neto`, `mec_afec`, `mec_categ`, `mec_hscs`, `mec_antig`, `mec_suple_doc`, `mec_suple_sec`, `mec_suple_desde`, `mec_suple_hasta`, `mec_fecha_planilla`, `mec_nro_pesos`, `mec_distrito_nro`, `mec_distrito_denom`, `mec_tiporg_nro`, `mec_tiporg_denom`, `mec_nroinst`, `mec_inst_denom`, `mec_rural`, `mec_seccs`, `mec_turnos`, `mec_subvencion`) VALUES
(1, '25435050', '004', 'ACOSTA SILVIA', 'S', 'P', '22892.13', '202101', 'MG', '', '09/05', NULL, NULL, NULL, NULL, 'Enero DE 2021', '0011', '071', 'MERLO', 'PP', 'ESCUELA PRIMARIA', '0389', 'COLEGIO SAN ANTONIO', '00', '29', '02', '100%'),
(2, '29469278', '052', 'NOVO BARBARA MARIA', 'S', 'P', '15651.77', '202101', 'MS', '', '03/04', NULL, NULL, NULL, NULL, 'Enero DE 2021', '0011', '071', 'MERLO', 'PP', 'ESCUELA PRIMARIA', '0389', 'COLEGIO SAN ANTONIO', '00', '29', '02', '100%'),
(3, '29470331', '029', 'RODRIGUEZ GABRIELA', 'S', 'P', '3052.56', '202101', 'MK HS.CS', '2.00', '14/08', NULL, NULL, NULL, NULL, 'Enero DE 2021', '0011', '071', 'MERLO', 'PP', 'ESCUELA PRIMARIA', '0389', 'COLEGIO SAN ANTONIO', '00', '29', '02', '100%'),
(4, '36897669', '010', 'GARCIA MARA AMELIA    S', ' ', ' ', '28605.11', '202101', 'MG', '', '02/05', NULL, NULL, NULL, NULL, 'Enero DE 2021', '0011', '071', 'MERLO', 'PP', 'ESCUELA PRIMARIA', '0389', 'COLEGIO SAN ANTONIO', '00', '29', '02', '100%');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mec_mec2`
--

CREATE TABLE `mec_mec2` (
  `id` int(11) NOT NULL,
  `mec_mec_doc` varchar(20) DEFAULT NULL,
  `mec_mec_sec` varchar(20) DEFAULT NULL,
  `mec_mec_afec` varchar(20) DEFAULT NULL,
  `mec2_cod` varchar(20) DEFAULT NULL,
  `mec2_denom` varchar(100) DEFAULT NULL,
  `mec2_importe` decimal(10,2) DEFAULT NULL,
  `mec2_importe2` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mec_mec2`
--

INSERT INTO `mec_mec2` (`id`, `mec_mec_doc`, `mec_mec_sec`, `mec_mec_afec`, `mec2_cod`, `mec2_denom`, `mec2_importe`, `mec2_importe2`) VALUES
(1, '25435050', '004', '202101', '011.0', 'Nominal', '11797.50', '0.00'),
(2, '25435050', '004', '202101', '022.0', 'Antiguedad', '5072.93', '0.00'),
(3, '25435050', '004', '202101', '043.8', 'Bonif Rem 2014', '4047.27', '0.00'),
(4, '25435050', '004', '202101', '064.1', 'Bonif EGB 1 y 2', '7185.75', '0.00'),
(5, '25435050', '004', '202101', '106.0', 'Ips', '0.00', '-4496.55'),
(6, '25435050', '004', '202101', '128.5', 'Obra Social', '-843.10', '0.00'),
(7, '25435050', '004', '202101', '276.1', 'Gar Marzo/07', '128.33', '0.00'),
(8, '29469278', '052', '202101', '011.0', 'Nominal', '9594.00', '0.00'),
(9, '29469278', '052', '202101', '022.0', 'Antiguedad', '2302.56', '0.00'),
(10, '29469278', '052', '202101', '045.5', 'Bonif Remun Ago', '2739.60', '0.00'),
(11, '29469278', '052', '202101', '064.1', 'Bonif EGB 1 y 2', '3919.50', '0.00'),
(12, '29469278', '052', '202101', '066.8', 'Bon Secretar Ma', '767.52', '0.00'),
(13, '29469278', '052', '202101', '106.0', 'Ips', '0.00', '-3091.71'),
(14, '29469278', '052', '202101', '128.5', 'Obra Social', '-579.70', '0.00'),
(15, '29470331', '029', '202101', '011.0', 'Nominal', '1787.50', '0.00'),
(16, '29470331', '029', '202101', '022.0', 'Antiguedad', '1144.00', '0.00'),
(17, '29470331', '029', '202101', '045.5', 'Bonif Remun Ago', '837.10', '0.00'),
(18, '29470331', '029', '202101', '106.0', 'Ips', '0.00', '-602.98'),
(19, '29470331', '029', '202101', '128.5', 'Obra Social', '-113.06', '0.00'),
(20, '36897669', '010', '202101', '11.0 N', 'ominal', '13406.25', '0.00'),
(21, '36897669', '010', '202101', '022.0', 'Antiguedad', '3217.50', '0.00'),
(22, '36897669', '010', '202101', '043.8', 'Bonif Rem 2014', '4599.17', '0.00'),
(23, '36897669', '010', '202101', '045.5', 'Bonif Remun Ago', '5707.50', '0.00'),
(24, '36897669', '010', '202101', '064.1', 'Bonif EGB 1 y 2', '8165.62', '0.00'),
(25, '36897669', '010', '202101', '106.0', 'Ips', '0.00', '-5615.37'),
(26, '36897669', '010', '202101', '128.5', 'Obra Social', '-1052.88', '0.00'),
(27, '36897669', '010', '202101', '276.1', 'Gar Marzo/07', '177.32', '0.00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mec_mec`
--
ALTER TABLE `mec_mec`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mec_mec2`
--
ALTER TABLE `mec_mec2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mec_mec`
--
ALTER TABLE `mec_mec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mec_mec2`
--
ALTER TABLE `mec_mec2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
