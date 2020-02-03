-- --------------------------------------------------------
-- Host:                         maicol07.ml
-- Server version:               5.7.29-0ubuntu0.18.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;

-- Dump structure of table scheduled_exams.classrooms
CREATE TABLE IF NOT EXISTS `classrooms`
(
    `ID`          int(11)    NOT NULL AUTO_INCREMENT,
    `name`        mediumtext NOT NULL,
    `description` mediumtext,
    `image`       mediumtext,
    `users`       json DEFAULT NULL,
    `code`        char(5)    NOT NULL,
    `admins`      json DEFAULT NULL,
    `students`    json DEFAULT NULL,
    PRIMARY KEY (`ID`),
    UNIQUE KEY `UNIQUE` (`ID`, `code`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8;

-- Dump structure of table scheduled_exams.lists
CREATE TABLE IF NOT EXISTS `lists`
(
    `ID`           int(10) unsigned                         NOT NULL AUTO_INCREMENT,
    `classroom_id` int(10)                                  NOT NULL,
    `name`         mediumtext                               NOT NULL,
    `description`  mediumtext                               NOT NULL,
    `image`        mediumtext                               NOT NULL,
    `type`         enum ('AUTO','FROM_START_DATE','MANUAL') NOT NULL DEFAULT 'MANUAL',
    `start_date`   date                                              DEFAULT NULL,
    `weekdays`     text COMMENT 'Serialized array',
    `quantity`     int(10)                                           DEFAULT NULL,
    `code`         char(5)                                  NOT NULL,
    PRIMARY KEY (`ID`),
    KEY `FK_lists_classrooms` (`classroom_id`),
    CONSTRAINT `FK_lists_classrooms` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`ID`) ON DELETE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 28
  DEFAULT CHARSET = utf8;

-- Dump structure of table scheduled_exams.lists_rows
CREATE TABLE IF NOT EXISTS `lists_rows`
(
    `ID`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `list_id`    int(10) unsigned NOT NULL,
    `student_id` int(10)          NOT NULL,
    `date`       date DEFAULT NULL,
    PRIMARY KEY (`ID`),
    KEY `FK_lists_rows_lists` (`list_id`),
    CONSTRAINT `FK_lists_rows_lists` FOREIGN KEY (`list_id`) REFERENCES `lists` (`ID`) ON DELETE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 33
  DEFAULT CHARSET = latin1 COMMENT ='Rows of a list';

-- Dump structure of table scheduled_exams.users
CREATE TABLE IF NOT EXISTS `users`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `username` char(50) DEFAULT NULL,
    `locale`   char(5)  DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8;

/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
