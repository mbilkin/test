-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Мар 01 2017 г., 22:53
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `result`
--
CREATE TABLE IF NOT EXISTS `result` (
`i_id` int(11)
,`s_login` varchar(255)
,`t_value` text
);
-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `i_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_login` varchar(255) NOT NULL,
  PRIMARY KEY (`i_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`i_id`, `s_login`) VALUES
(1, 'ivan'),
(2, 'test');

-- --------------------------------------------------------

--
-- Структура таблицы `user_field`
--

CREATE TABLE IF NOT EXISTS `user_field` (
  `i_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_field_path` varchar(255) NOT NULL,
  PRIMARY KEY (`i_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `user_field`
--

INSERT INTO `user_field` (`i_id`, `s_field_path`) VALUES
(1, 'Дата рождения'),
(2, 'Пол');

-- --------------------------------------------------------

--
-- Структура таблицы `user_field_value`
--

CREATE TABLE IF NOT EXISTS `user_field_value` (
  `i_fld_id` int(11) NOT NULL,
  `i_user_id` int(11) NOT NULL,
  `t_value` text NOT NULL,
  KEY `i_fld_id` (`i_fld_id`,`i_user_id`),
  KEY `i_user_id` (`i_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_field_value`
--

INSERT INTO `user_field_value` (`i_fld_id`, `i_user_id`, `t_value`) VALUES
(1, 1, '1985-12-22'),
(1, 2, '1990-01-12');

-- --------------------------------------------------------

--
-- Структура таблицы `zodiac`
--

CREATE TABLE IF NOT EXISTS `zodiac` (
  `i_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`i_id`),
  KEY `name` (`name`),
  KEY `name_2` (`name`,`begin_date`,`end_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `zodiac`
--

INSERT INTO `zodiac` (`i_id`, `name`, `begin_date`, `end_date`) VALUES
(2, 'Козерог', '2000-01-01', '2000-01-20'),
(1, 'Козерог', '2000-12-22', '2000-12-31');

-- --------------------------------------------------------

--
-- Структура для представления `result`
--
DROP TABLE IF EXISTS `result`;

CREATE VIEW `result` AS select `u`.`i_id` AS `i_id`,`u`.`s_login` AS `s_login`,`uv`.`t_value` AS `t_value` from (`user` `u` join `user_field_value` `uv` on(((`uv`.`i_fld_id` = 1) and (`uv`.`i_user_id` = `u`.`i_id`)))) where exists(select 1 from `zodiac` `z` where ((`z`.`name` = 'Козерог') and (str_to_date(concat('2000-',month(`uv`.`t_value`),'-',dayofmonth(`uv`.`t_value`)),'%Y-%m-%d') between `z`.`begin_date` and `z`.`end_date`)));

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `user_field_value`
--
ALTER TABLE `user_field_value`
  ADD CONSTRAINT `user_field_value_ibfk_2` FOREIGN KEY (`i_fld_id`) REFERENCES `user_field` (`i_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_field_value_ibfk_1` FOREIGN KEY (`i_user_id`) REFERENCES `user` (`i_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
