-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 12-Jan-2015 às 08:22
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `casamento_prudente`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) NOT NULL,
  `image` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `image`) VALUES
(1, 'Categoria 1', 'images/categorias/1420833369.png'),
(2, 'Categoria 2', 'images/categorias/1420833334.png'),
(3, 'Categoria 3', 'images/categorias/1420833339.png'),
(4, 'Cerimonial e Assessoria', 'images/categorias/1420854742.png'),
(5, 'SalÃµes de Beleza', 'images/categorias/1420854930.png'),
(6, 'Buffet', 'images/categorias/1420852153.png'),
(7, 'Trajes', 'images/categorias/1420854199.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_empresa` varchar(150) NOT NULL,
  `endereco_empresa` varchar(500) NOT NULL,
  `telefone_empresa` varchar(15) NOT NULL,
  `email_empresa` varchar(100) NOT NULL,
  `site_empresa` varchar(150) DEFAULT NULL,
  `facebook_empresa` varchar(200) DEFAULT NULL,
  `categoria1_empresa` int(11) NOT NULL,
  `categoria2_empresa` int(11) DEFAULT NULL,
  `categoria3_empresa` int(11) DEFAULT NULL,
  `bannersimples_empresa` int(11) NOT NULL,
  `bannergrande_empresa` int(11) NOT NULL,
  `logo_empresa` varchar(300) DEFAULT NULL,
  `logogrande_empresa` varchar(300) DEFAULT NULL,
  `ativo_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `empresas`
--

INSERT INTO `empresas` (`id`, `nome_empresa`, `endereco_empresa`, `telefone_empresa`, `email_empresa`, `site_empresa`, `facebook_empresa`, `categoria1_empresa`, `categoria2_empresa`, `categoria3_empresa`, `bannersimples_empresa`, `bannergrande_empresa`, `logo_empresa`, `logogrande_empresa`, `ativo_empresa`) VALUES
(1, 'CaffeineDev', 'Rua Dr. Jacintho F. da Silva\r\nParque Furquim', '1891112369', 'douglhorvath@gmail.com', 'http://www.caffeinedev.com.br', 'Caffeine Dev', 1, 2, 3, 1, 0, 'images/simples/1420744974.png', '', 1),
(2, 'BlueNote', 'Rua Manoel de Jesus, 49\r\nVila Jesus', '1888073328', 'isispvpalma@gmail.com', '', '', 3, 1, 2, 1, 1, 'images/simples/1420839837.png', 'images/grande/1420839997.png', 1),
(3, 'Isis Vasconcellos', 'Rua Manoel de Jesus, 49\r\nVila Jesus', '1888073328', 'isispvpalma@gmail.com', 'http://www.caffeinedev.com.br', 'https://www.facebook.com/isispryscilla', 4, 3, -1, 1, 1, 'images/simples/1420750230.png', 'images/grande/1420750230.png', 1),
(5, 'Maquiagem Isis', 'Rua Manoel de Jesus, 49, Vila Jesus', '88073328', 'isispvpalma@gmail.com', 'http://www.caffeinedev.com.br', '', 1, -1, -1, 1, 1, 'images/simples/1420850553.png', 'images/grande/1420850553.png', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sys_admin`
--

CREATE TABLE IF NOT EXISTS `sys_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `senha` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `sys_admin`
--

INSERT INTO `sys_admin` (`id`, `login`, `senha`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
