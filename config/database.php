<?php
ORM::configure([
  'connection_string' => $env['connection_string'], 
  'username' => $env['username'], 
  'password' => $env['password'] 
]);

/*
$db = ORM::get_db(); 
$db->exec("CREATE DATABASE IF NOT EXISTS `project` CHARACTER SET = 'utf8' COLLATE = 'utf8_general_ci';
USE `project`;
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` ( `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID (автогенерируемый)' , `name` VARCHAR(255) NOT NULL COMMENT 'Название проекта' , `url` VARCHAR(255) NOT NULL COMMENT 'URL сайта' , `platform` INT NOT NULL COMMENT 'Тип платформы (Wordpress, Bitrix, Custom, Other)' , `status` INT NOT NULL COMMENT 'Статус (development, production, maintenance, archived)' , `description` TEXT NULL DEFAULT NULL COMMENT 'Описание (опционально)' , `date_create` DATE NOT NULL COMMENT 'Дата создания' , `date_update` DATE NOT NULL COMMENT 'Дата последнего обновления' , PRIMARY KEY (`id`), INDEX `idx_platform` (`platform`), INDEX `idx_status` (`status`)) ENGINE = MyISAM COMMENT = 'Управление проектами (Projects)';
DROP TABLE IF EXISTS `statuses`;
CREATE TABLE `statuses` ( `id` INT NOT NULL AUTO_INCREMENT , `status_id` INT NOT NULL COMMENT 'Status ID внешний ключ' , `status_name` VARCHAR(255) NOT NULL COMMENT 'Название статуса' , PRIMARY KEY (`id`), INDEX `idx_status_id` (`status_id`), UNIQUE `idx_status` (`status_name`)) ENGINE = MyISAM COMMENT = 'Набор статусов';
DROP TABLE IF EXISTS `platforms`;
CREATE TABLE `platforms` ( `id` INT NOT NULL AUTO_INCREMENT , `platform_id` INT NOT NULL COMMENT 'Platform ID внешний ключ' , `platform_name` VARCHAR(255) NOT NULL  COMMENT 'Название платформы'  , PRIMARY KEY (`id`), INDEX `idx_platform_id` (`platform_id`), UNIQUE `idx_platform` (`platform_name`)) ENGINE = MyISAM COMMENT = 'Набор платформ';


INSERT INTO `projects` (`id`, `name`, `url`, `platform`, `status`, `description`, `date_create`, `date_update`) VALUES (NULL, 'Art Gorka', 'artgorka.ru', '1', '1', 'Описание', '2025-12-07', '2025-12-07'), (NULL, 'Сбербанк', 'sber.ru', '2', '2', NULL, '2025-12-07', '2025-12-07');
INSERT INTO `statuses` (`id`, `status_id`, `status_name`) VALUES (NULL, '1', 'development'), (NULL, '2', 'production');
INSERT INTO `statuses` (`id`, `status_id`, `status_name`) VALUES (NULL, '3', 'maintenance'), (NULL, '4', 'archived');
INSERT INTO `platforms` (`id`, `platform_id`, `platform_name`) VALUES (NULL, '1', 'Wordpress'), (NULL, '2', 'Bitrix');
INSERT INTO `platforms` (`id`, `platform_id`, `platform_name`) VALUES (NULL, '3', 'Custom'), (NULL, '4', 'Other');

");
*/
?>