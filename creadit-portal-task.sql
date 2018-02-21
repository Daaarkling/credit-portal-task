-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `thread_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `author_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:uuid)',
  `posted` datetime NOT NULL,
  `text` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CE2904019` (`thread_id`),
  KEY `IDX_9474526CF675F31B` (`author_id`),
  CONSTRAINT `FK_9474526CE2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`),
  CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `comment` (`id`, `thread_id`, `author_id`, `posted`, `text`, `deleted`) VALUES
('3200f71b-7135-4062-8708-3ca400e8c341',	'44c3e198-ec82-41f0-aadf-3dd115ecebab',	'8c9c90d5-24ad-4e53-abc4-bee42a834f0e',	'2018-02-21 18:15:04',	'Hustý :-)',	NULL),
('37ad14e5-4153-4e4b-b7fb-0bfcf9c8816d',	'44c3e198-ec82-41f0-aadf-3dd115ecebab',	'c08d8bb2-d079-49c9-9421-868de2d93ba8',	'2018-02-20 18:50:48',	'Snad to bude fungovat.\n\n\nFunguje to :-)',	NULL),
('7a6eab7c-6cf3-4dc0-8e3c-789030bcdee5',	'44c3e198-ec82-41f0-aadf-3dd115ecebab',	'c08d8bb2-d079-49c9-9421-868de2d93ba8',	'2018-02-20 23:10:51',	'In sit amet condimentum ligula. \r\n\r\nQuisque sollicitudin felis ultricies venenatis mollis. Donec lobortis turpis vel massa vehicula, at sagittis tortor dictum. Duis vitae venenatis ipsum. Nunc convallis ultrices nisl quis rhoncus. Nunc ut purus volutpat, ',	'2018-02-21 11:19:43');

DROP TABLE IF EXISTS `thread`;
CREATE TABLE `thread` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_31204C83989D9B62` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `thread` (`id`, `name`, `description`, `slug`, `deleted`) VALUES
('44c3e198-ec82-41f0-aadf-3dd115ecebab',	'Obecná diskuse',	'Pokec o Nette, novinky, instalace, nastavení, nápady, dotazy.',	'obecna-diskuse',	NULL),
('c317a31a-3032-4201-bc90-3146332837ab',	'RFC',	'Chci přispět do kódu',	'pravidla-diskuse',	NULL),
('cc686e8d-9ccd-43d1-b16e-0aec5f3d365d',	'Pokec',	'Co tě napadne',	'pokec',	NULL);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `search_idx` (`name`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `deleted`, `disabled`) VALUES
('8c9c90d5-24ad-4e53-abc4-bee42a834f0e',	'Member Memberovič',	'member@member.member',	'$2y$10$aTg/gQGwnM4Qf0Uzt1ttn.SQ9oj71jhv.7zyEn2bnWHYCeQHYLJdW',	'member',	NULL,	0),
('c08d8bb2-d079-49c9-9421-868de2d93ba8',	'Admin Adminovič',	'admin@admin.admin',	'$2y$10$xfNXdRijAumR9ayAcKeSVOUhahJaOfyM8wJiBtn4BvAMPaZe.CTUe',	'admin',	NULL,	0);

-- 2018-02-21 20:16:14
