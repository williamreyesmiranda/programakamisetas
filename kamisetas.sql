-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-09-2020 a las 20:46:34
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kamisetas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodega`
--

CREATE TABLE `bodega` (
  `idbodega` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicioprocesofecha` date DEFAULT NULL,
  `finprocesofecha` date DEFAULT NULL,
  `obs_bodega` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parcial` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bodega`
--

INSERT INTO `bodega` (`idbodega`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicioprocesofecha`, `finprocesofecha`, `obs_bodega`, `parcial`, `estado`) VALUES
(1, 28, '2020-08-18', '2020-08-24', 5, NULL, NULL, 'ensayo', 4, 1),
(2, 29, '2020-08-27', '2020-08-31', 4, NULL, NULL, NULL, NULL, 0),
(3, 30, '2020-08-25', '2020-09-02', 7, NULL, NULL, NULL, NULL, 0),
(4, 31, '2020-08-18', '2020-09-01', 11, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bordado`
--

CREATE TABLE `bordado` (
  `idbordado` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicio_proceso` int(11) NOT NULL DEFAULT 0,
  `fin_proceso` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `confeccion`
--

CREATE TABLE `confeccion` (
  `idconfeccion` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicio_proceso` int(11) NOT NULL DEFAULT 0,
  `fin_proceso` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte`
--

CREATE TABLE `corte` (
  `idcorte` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicio_proceso` int(11) NOT NULL DEFAULT 0,
  `fin_proceso` int(11) NOT NULL DEFAULT 0,
  `oc` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parcial` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(0, 'En Espera'),
(1, 'En Proceso'),
(2, 'Terminado'),
(3, 'Anulado'),
(4, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estampacion`
--

CREATE TABLE `estampacion` (
  `idestampacion` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicio_proceso` int(11) NOT NULL DEFAULT 0,
  `fin_proceso` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estampacion`
--

INSERT INTO `estampacion` (`idestampacion`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 28, '2020-08-28', '2020-09-01', 3, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idpedido` int(11) NOT NULL,
  `num_pedido` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cliente` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `asesor` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias_habiles` int(11) NOT NULL,
  `procesos` int(11) NOT NULL,
  `unds` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `area_inicio` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idpedido`, `num_pedido`, `cliente`, `asesor`, `fecha_inicio`, `fecha_fin`, `dias_habiles`, `procesos`, `unds`, `fecha_ingreso`, `usuario`, `area_inicio`) VALUES
(28, 'w34353', '92093', 'ventas', '2020-08-18', '2020-09-03', 13, 5, 12, '2020-08-30', 3, '0'),
(29, '3456', 'william reyes', '123', '2020-08-27', '2020-09-02', 5, 1, 30, '2020-08-30', 3, '0'),
(30, '1234', 'William reyws', 'Otro', '2020-08-25', '2020-09-04', 9, 1, 100, '2020-08-31', 3, '0'),
(31, '1234', 'william reyes', 'adres', '2020-08-18', '2020-09-04', 14, 1, 50, '2020-08-31', 3, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE `procesos` (
  `idproceso` int(11) NOT NULL,
  `siglas` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `1` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `2` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `3` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `4` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `5` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `6` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `tiempo1` decimal(10,2) NOT NULL,
  `tiempo2` decimal(10,2) NOT NULL,
  `tiempo3` decimal(10,2) DEFAULT NULL,
  `tiempo4` decimal(10,2) DEFAULT NULL,
  `tiempo5` decimal(10,2) DEFAULT NULL,
  `tiempo6` decimal(10,2) DEFAULT NULL,
  `dias_habiles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `procesos`
--

INSERT INTO `procesos` (`idproceso`, `siglas`, `1`, `2`, `3`, `4`, `5`, `6`, `tiempo1`, `tiempo2`, `tiempo3`, `tiempo4`, `tiempo5`, `tiempo6`, `dias_habiles`) VALUES
(1, 'BT', 'BODEGA', 'TERMINACION', '', '', '', '', '0.80', '0.20', '0.00', '0.00', '0.00', NULL, 5),
(2, 'BET', 'BODEGA', 'ESTAMPACION', 'TERMINACION', '', '', '', '0.50', '0.30', '0.20', '0.00', '0.00', NULL, 10),
(3, 'BST', 'BODEGA', 'SUBLIMACION', 'TERMINACION', '', '', '', '0.50', '0.30', '0.20', '0.00', '0.00', NULL, 10),
(4, 'BVT', 'BODEGA', 'BORDADO', 'TERMINACION', '', '', '', '0.50', '0.30', '0.20', '0.00', '0.00', NULL, 10),
(5, 'BSET', 'BODEGA', 'SUBLIMACION', 'ESTAMPACION', 'TERMINACION', '', '', '0.38', '0.23', '0.23', '0.17', '0.00', NULL, 13),
(6, 'BEVT', 'BODEGA', 'ESTAMPACION', 'BORDADO', 'TERMINACION', '', '', '0.38', '0.23', '0.23', '0.17', '0.00', NULL, 13),
(7, 'BSVT', 'BODEGA', 'SUBLIMACION', 'BORDADO', 'TERMINACION', '', '', '0.38', '0.23', '0.23', '0.17', '0.00', NULL, 13),
(8, 'BSEVT', 'BODEGA', 'SUBLIMACION', 'ESTAMPACION', 'BORDADO', 'TERMINACION', '', '0.31', '0.19', '0.19', '0.19', '0.12', NULL, 16),
(9, 'CT', 'CORTE', 'CONFECCION', 'TERMINACION', '', '', '', '0.38', '0.57', '0.05', '0.00', '0.00', '0.00', 14),
(10, 'CET', 'CORTE', 'CONFECCION', 'ESTAMPACION', 'TERMINACION', '', '', '0.27', '0.39', '0.20', '0.14', '0.00', '0.00', 17),
(11, 'CST', 'CORTE', 'CONFECCION', 'SUBLIMACION', 'TERMINACION', '', '', '0.27', '0.39', '0.20', '0.14', '0.00', '0.00', 17),
(12, 'CVT', 'CORTE', 'CONFECCION', 'BORDADO', 'TERMINACION', '', '', '0.27', '0.39', '0.20', '0.14', '0.00', '0.00', 17),
(13, 'CSET', 'CORTE', 'CONFECCION', 'SUBLIMACION', 'ESTAMPACION', 'TERMINACION', '', '0.22', '0.33', '0.17', '0.17', '0.11', '0.00', 20),
(14, 'CEVT', 'CORTE', 'CONFECCION', 'ESTAMPACION', 'BORDADO', 'TERMINACION', '', '0.22', '0.33', '0.17', '0.17', '0.11', '0.00', 20),
(15, 'CSVT', 'CORTE', 'CONFECCION', 'SUBLIMACION', 'BORDADO', 'TERMINACION', '', '0.22', '0.33', '0.17', '0.17', '0.11', '0.00', 20),
(16, 'CSEVT', 'CORTE', 'CONFECCION', 'SUBLIMACION', 'ESTAMPACION', 'BORDADO', 'TERMINACION', '0.20', '0.28', '0.14', '0.14', '0.14', '0.10', 23),
(17, 'VT', 'BORDADO', 'TERMINACION', '', '', '', '', '0.80', '0.20', '0.00', '0.00', '0.00', '0.00', 5),
(18, 'ST', 'SUBLIMACION', 'TERMINACION', '', '', '', '', '0.80', '0.20', '0.00', '0.00', '0.00', '0.00', 5),
(19, 'ET', 'ESTAMPACION', 'TERMINACION', '', '', '', '', '0.80', '0.20', '0.00', '0.00', '0.00', '0.00', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `obs` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`, `obs`) VALUES
(1, 'Administrador', ''),
(2, 'Confección', ''),
(3, 'Corte', ''),
(4, 'Bodega', ''),
(5, 'Estampación', ''),
(6, 'Bordado', ''),
(7, 'Terminación', ''),
(8, 'Comercial', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sublimacion`
--

CREATE TABLE `sublimacion` (
  `idsublimacion` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicio_proceso` int(11) NOT NULL DEFAULT 0,
  `fin_proceso` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sublimacion`
--

INSERT INTO `sublimacion` (`idsublimacion`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 28, '2020-08-25', '2020-08-27', 3, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terminacion`
--

CREATE TABLE `terminacion` (
  `idterminacion` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `iniciofecha` date NOT NULL,
  `finfecha` date NOT NULL,
  `dias` int(11) NOT NULL,
  `inicio_proceso` int(11) NOT NULL DEFAULT 0,
  `fin_proceso` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `terminacion`
--

INSERT INTO `terminacion` (`idterminacion`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 28, '2020-09-02', '2020-09-03', 2, 0, 0, 1),
(2, 29, '2020-09-02', '2020-09-02', 1, 0, 0, 1),
(3, 30, '2020-09-03', '2020-09-04', 2, 0, 0, 1),
(4, 31, '2020-09-02', '2020-09-04', 3, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clave` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `cedula` int(11) DEFAULT NULL,
  `estatus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `sexo`, `usuario`, `clave`, `rol`, `cedula`, `estatus`) VALUES
(3, 'William Reyes', 'hombre', 'william', '81dc9bdb52d04dc20036dbd8313ed055', 1, 1124023751, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bodega`
--
ALTER TABLE `bodega`
  ADD PRIMARY KEY (`idbodega`),
  ADD KEY `pedido` (`pedido`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `bordado`
--
ALTER TABLE `bordado`
  ADD PRIMARY KEY (`idbordado`),
  ADD KEY `pedido` (`pedido`);

--
-- Indices de la tabla `confeccion`
--
ALTER TABLE `confeccion`
  ADD PRIMARY KEY (`idconfeccion`),
  ADD KEY `pedido` (`pedido`);

--
-- Indices de la tabla `corte`
--
ALTER TABLE `corte`
  ADD PRIMARY KEY (`idcorte`),
  ADD KEY `pedido` (`pedido`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estampacion`
--
ALTER TABLE `estampacion`
  ADD PRIMARY KEY (`idestampacion`),
  ADD KEY `pedido` (`pedido`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `procesos` (`procesos`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `procesos`
--
ALTER TABLE `procesos`
  ADD PRIMARY KEY (`idproceso`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `sublimacion`
--
ALTER TABLE `sublimacion`
  ADD PRIMARY KEY (`idsublimacion`),
  ADD KEY `pedido` (`pedido`);

--
-- Indices de la tabla `terminacion`
--
ALTER TABLE `terminacion`
  ADD PRIMARY KEY (`idterminacion`),
  ADD KEY `pedido` (`pedido`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bodega`
--
ALTER TABLE `bodega`
  MODIFY `idbodega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `bordado`
--
ALTER TABLE `bordado`
  MODIFY `idbordado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `confeccion`
--
ALTER TABLE `confeccion`
  MODIFY `idconfeccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `corte`
--
ALTER TABLE `corte`
  MODIFY `idcorte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estampacion`
--
ALTER TABLE `estampacion`
  MODIFY `idestampacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `procesos`
--
ALTER TABLE `procesos`
  MODIFY `idproceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sublimacion`
--
ALTER TABLE `sublimacion`
  MODIFY `idsublimacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `terminacion`
--
ALTER TABLE `terminacion`
  MODIFY `idterminacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bodega`
--
ALTER TABLE `bodega`
  ADD CONSTRAINT `bodega_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`),
  ADD CONSTRAINT `bodega_ibfk_2` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `bordado`
--
ALTER TABLE `bordado`
  ADD CONSTRAINT `bordado_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`);

--
-- Filtros para la tabla `confeccion`
--
ALTER TABLE `confeccion`
  ADD CONSTRAINT `confeccion_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`);

--
-- Filtros para la tabla `corte`
--
ALTER TABLE `corte`
  ADD CONSTRAINT `corte_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`);

--
-- Filtros para la tabla `estampacion`
--
ALTER TABLE `estampacion`
  ADD CONSTRAINT `estampacion_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`procesos`) REFERENCES `procesos` (`idproceso`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `sublimacion`
--
ALTER TABLE `sublimacion`
  ADD CONSTRAINT `sublimacion_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`);

--
-- Filtros para la tabla `terminacion`
--
ALTER TABLE `terminacion`
  ADD CONSTRAINT `terminacion_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
