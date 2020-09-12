-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-09-2020 a las 06:27:54
-- Versión del servidor: 10.1.19-MariaDB
-- Versión de PHP: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `inicioprocesofecha` date NOT NULL,
  `finprocesofecha` date NOT NULL,
  `obs_bodega` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parcial` int(11) DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0',
  `usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bodega`
--

INSERT INTO `bodega` (`idbodega`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicioprocesofecha`, `finprocesofecha`, `obs_bodega`, `parcial`, `estado`, `usuario`) VALUES
(1, 28, '2020-08-18', '2020-08-24', 5, '2020-09-09', '0000-00-00', '', 12, 4, 3),
(2, 29, '2020-08-27', '2020-08-31', 4, '2020-09-09', '0000-00-00', 'porque si', 0, 1, 3),
(3, 30, '2020-08-25', '2020-09-02', 7, '2020-09-08', '0000-00-00', '', 0, 1, 3),
(4, 31, '2020-08-18', '2020-09-01', 11, '2020-09-08', '0000-00-00', 'falta pedido 30187', 25, 2, 3),
(5, 32, '2020-09-08', '2020-09-15', 6, '0000-00-00', '0000-00-00', NULL, 0, 0, NULL),
(6, 33, '2020-09-08', '2020-09-11', 4, '0000-00-00', '0000-00-00', NULL, 0, 0, NULL),
(7, 34, '2020-09-08', '2020-09-15', 6, '0000-00-00', '0000-00-00', NULL, 0, 0, NULL);

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
  `inicio_proceso` int(11) NOT NULL DEFAULT '0',
  `fin_proceso` int(11) NOT NULL DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bordado`
--

INSERT INTO `bordado` (`idbordado`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 34, '2020-09-16', '2020-09-18', 3, 0, 0, 0);

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
  `inicio_proceso` int(11) NOT NULL DEFAULT '0',
  `fin_proceso` int(11) NOT NULL DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `confeccion`
--

INSERT INTO `confeccion` (`idconfeccion`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 35, '2020-09-14', '2020-09-24', 9, 0, 0, 0);

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
  `inicioprocesofecha` date NOT NULL,
  `finprocesofecha` int(11) NOT NULL,
  `obs_corte` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `oc` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parcial` int(11) DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0',
  `usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `corte`
--

INSERT INTO `corte` (`idcorte`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicioprocesofecha`, `finprocesofecha`, `obs_corte`, `oc`, `parcial`, `estado`, `usuario`) VALUES
(1, 35, '2020-09-04', '2020-09-11', 6, '2020-09-11', 0, '', 'oc1056-oc1', 100, 3, 3);

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
(2, 'Pendiente'),
(3, 'Anulado'),
(4, 'Terminado');

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
  `inicio_proceso` int(11) NOT NULL DEFAULT '0',
  `fin_proceso` int(11) NOT NULL DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estampacion`
--

INSERT INTO `estampacion` (`idestampacion`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 28, '2020-08-28', '2020-09-01', 3, 0, 0, 1),
(2, 32, '2020-09-16', '2020-09-18', 3, 0, 0, 1),
(3, 33, '2020-09-14', '2020-09-15', 2, 0, 0, 0);

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
  `fecha_ingreso` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` int(11) NOT NULL,
  `area_inicio` varchar(13) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idpedido`, `num_pedido`, `cliente`, `asesor`, `fecha_inicio`, `fecha_fin`, `dias_habiles`, `procesos`, `unds`, `fecha_ingreso`, `usuario`, `area_inicio`, `estado`) VALUES
(28, 'w34353', '92093', 'ventas', '2020-08-18', '2020-09-03', 13, 5, 12, '2020-08-30 00:00:00', 3, '0', 1),
(29, '3456', 'william reyes', '123', '2020-08-27', '2020-09-02', 5, 1, 30, '2020-08-30 00:00:00', 3, '0', 1),
(30, '1234', 'William reyws', 'Otro', '2020-08-25', '2020-09-04', 9, 1, 100, '2020-08-31 00:00:00', 3, '0', 1),
(31, '1234', 'william reyes', 'adres', '2020-08-18', '2020-09-04', 14, 1, 50, '2020-08-31 00:00:00', 3, '0', 0),
(32, '123', 'k-amisetas', 'ventas', '2020-09-08', '2020-09-22', 11, 2, 100, '0000-00-00 00:00:00', 3, '0', 0),
(33, '12344', 'k-amisetas', 'ventas', '2020-09-08', '2020-09-16', 7, 2, 123, '0000-00-00 00:00:00', 3, '0', 0),
(34, '5678', 'k-amisetas', 'ventas', '2020-09-08', '2020-09-22', 11, 4, 100, '0000-00-00 00:00:00', 3, '0', 0),
(35, '2345', 'dfgf', 'tyty', '2020-09-04', '2020-09-25', 16, 9, 100, '2020-09-11 22:03:26', 3, '0', 1);

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
  `inicio_proceso` int(11) NOT NULL DEFAULT '0',
  `fin_proceso` int(11) NOT NULL DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0'
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
  `inicio_proceso` int(11) NOT NULL DEFAULT '0',
  `fin_proceso` int(11) NOT NULL DEFAULT '0',
  `estado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `terminacion`
--

INSERT INTO `terminacion` (`idterminacion`, `pedido`, `iniciofecha`, `finfecha`, `dias`, `inicio_proceso`, `fin_proceso`, `estado`) VALUES
(1, 28, '2020-09-02', '2020-09-03', 2, 0, 0, 1),
(2, 29, '2020-09-02', '2020-09-02', 1, 0, 0, 1),
(3, 30, '2020-09-03', '2020-09-04', 2, 0, 0, 1),
(4, 31, '2020-09-02', '2020-09-04', 3, 0, 0, 1),
(5, 32, '2020-09-21', '2020-09-22', 2, 0, 0, 1),
(6, 33, '2020-09-16', '2020-09-16', 1, 0, 0, 0),
(7, 34, '2020-09-21', '2020-09-22', 2, 0, 0, 0),
(8, 35, '2020-09-25', '2020-09-25', 1, 0, 0, 0);

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
  ADD KEY `estado` (`estado`),
  ADD KEY `usuario` (`usuario`);

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
  ADD KEY `pedido` (`pedido`),
  ADD KEY `estado` (`estado`),
  ADD KEY `usuario` (`usuario`);

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
  ADD KEY `usuario` (`usuario`),
  ADD KEY `estado` (`estado`);

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
  MODIFY `idbodega` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `bordado`
--
ALTER TABLE `bordado`
  MODIFY `idbordado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `confeccion`
--
ALTER TABLE `confeccion`
  MODIFY `idconfeccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `corte`
--
ALTER TABLE `corte`
  MODIFY `idcorte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `estampacion`
--
ALTER TABLE `estampacion`
  MODIFY `idestampacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
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
  MODIFY `idterminacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
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
  ADD CONSTRAINT `bodega_ibfk_2` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `bodega_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`);

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
  ADD CONSTRAINT `corte_ibfk_1` FOREIGN KEY (`pedido`) REFERENCES `pedidos` (`idpedido`),
  ADD CONSTRAINT `corte_ibfk_2` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `corte_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`);

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
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`);

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
