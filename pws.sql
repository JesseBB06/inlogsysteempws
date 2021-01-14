-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 14 jan 2021 om 15:36
-- Serverversie: 5.6.34
-- PHP-versie: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pws`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `account`
--

CREATE TABLE `account` (
  `id` int(10) NOT NULL,
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `telefoonnummer` int(12) NOT NULL,
  `emailadres` varchar(100) NOT NULL,
  `wachtwoord` varchar(100) NOT NULL,
  `secretKey` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `tijd` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `account`
--

INSERT INTO `account` (`id`, `voornaam`, `achternaam`, `telefoonnummer`, `emailadres`, `wachtwoord`, `secretKey`, `code`, `tijd`) VALUES
(1, 'test', 'test', 612345678, 'test@gmail.com', '$2y$10$VQhDIO87kC5AIDEw41zfPue0uw6RXX9M3Sl.pCLCjykZ9mrG6S80.', 'PKJ2EZHR6BTGDIDM', '', '0000-00-00'),
(2, 'oke', 'oke', 12345678, 'oke@gmail.com', '$2y$10$RFP0o8S9hDXu9cJgzhUhYOX21Rh4A180dGl1lGFiclV4ODLKcfGXm', '6EFS6KOJ2XZ6CKC4', '', '0000-00-00');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
