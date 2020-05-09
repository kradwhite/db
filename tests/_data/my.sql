DROP TABLE IF EXISTS `test_create_table-1`;
DROP TABLE IF EXISTS `test_create_table-2`;
DROP TABLE IF EXISTS `test_create_column`;
DROP TABLE IF EXISTS `test_alter_column`;
DROP TABLE IF EXISTS `test_rename_column`;
DROP TABLE IF EXISTS `new_table_name`;
DROP TABLE IF EXISTS `test_rename_table`;
DROP TABLE IF EXISTS `test_drop_column`;
DROP TABLE IF EXISTS `test_create_index`;
DROP TABLE IF EXISTS `test_rename_index`;
DROP TABLE IF EXISTS `test_drop_index`;
DROP TABLE IF EXISTS `test_create_foreign_key_target`;
DROP TABLE IF EXISTS `test_create_foreign_key_source`;
DROP TABLE IF EXISTS `test_rename_foreign_key_target`;
DROP TABLE IF EXISTS `test_rename_foreign_key_source`;
DROP TABLE IF EXISTS `test_drop_foreign_key_target`;
DROP TABLE IF EXISTS `test_drop_foreign_key_source`;
DROP TABLE IF EXISTS `test_drop_table`;
DROP TABLE IF EXISTS `test_insert`;
DROP TABLE IF EXISTS `test_delete`;
DROP TABLE IF EXISTS `test_update`;
DROP TABLE IF EXISTS `test_insert_multiple`;
DROP TABLE IF EXISTS `test_select_multiple`;
DROP TABLE IF EXISTS `test_select_one`;
DROP TABLE IF EXISTS `test_query_builder`;
DROP VIEW IF EXISTS `test_view_select_view`;
DROP TABLE IF EXISTS `test_table_select_view`;

CREATE TABLE `test_create_table-1` (`id` integer primary key not null);
CREATE TABLE `test_create_column` (`id` integer primary key not null);
CREATE TABLE `test_alter_column` (`col1` integer);
CREATE TABLE `test_rename_column` (`col1` integer);
CREATE TABLE `test_rename_table` (`col1` integer);
CREATE TABLE `test_drop_column` (`col1` integer, `col2` integer);
CREATE TABLE `test_create_index` (`col1` integer);
CREATE TABLE `test_rename_index` (`col1` integer);
CREATE INDEX `test_rename_index_col1_idx` ON `test_rename_index` (`col1`);
CREATE TABLE `test_drop_index` (`col1` integer);
CREATE INDEX `test_drop_index_col1_idx` ON `test_drop_index` (`col1`);
CREATE TABLE `test_create_foreign_key_source` (`id` integer primary key not null);
CREATE TABLE `test_create_foreign_key_target` (`source_id` integer not null);
CREATE TABLE `test_rename_foreign_key_source` (`id` integer primary key not null);
CREATE TABLE `test_rename_foreign_key_target` (`source_id` integer not null);
ALTER TABLE `test_rename_foreign_key_target`
    ADD CONSTRAINT `fk_test_rename_foreign_key_target_source_id` FOREIGN KEY (`source_id`)
    REFERENCES `test_rename_foreign_key_source` (`id`);
CREATE TABLE `test_drop_foreign_key_source` (`id` integer primary key not null);
CREATE TABLE `test_drop_foreign_key_target` (`source_id` integer not null);
ALTER TABLE `test_drop_foreign_key_target`
    ADD CONSTRAINT `fk_test_drop_foreign_key_target_source_id` FOREIGN KEY (`source_id`)
    REFERENCES `test_drop_foreign_key_source` (`id`);
CREATE TABLE `test_drop_table`(`id` integer);
CREATE TABLE `test_insert`(
    `id` integer PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `test_bool` bool NOT NULL,
    `test_integer` integer,
    `test_string` VARCHAR(10),
    `test_double` DOUBLE PRECISION
);
CREATE TABLE `test_delete` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR (256), `value` BOOLEAN);
INSERT INTO `test_delete` (`name`, `value`) VALUES ('name 1', true), ('name 2', false);
CREATE TABLE `test_update` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR (256), `value` INTEGER, `value2` BOOLEAN);
INSERT INTO `test_update` (`name`, `value`, `value2`) VALUES ('name 1', 22, true), ('name 2', 33, false);
CREATE TABLE `test_insert_multiple` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR (256), `value` INTEGER, `value2` BOOLEAN);
CREATE TABLE `test_select_multiple` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR (256), `value` INTEGER, `value2` BOOLEAN);
INSERT INTO `test_select_multiple` (`name`, `value`, `value2`) VALUES ('name 1', 22, false), ('name 2', 33, true), ('name 3', 44, true);
CREATE TABLE `test_select_one` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR (256), `value` INTEGER, `value2` BOOLEAN);
INSERT INTO `test_select_one` (`name`, `value`, `value2`) VALUES ('name 1', 22, false), ('name 2', 33, true), ('name 1', 22, false);
CREATE TABLE `test_query_builder` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR (256), `value` INTEGER, `value2` BOOLEAN);
INSERT INTO `test_query_builder` (`name`, `value`, `value2`) VALUES ('name 1', 22, false), ('name 2', 33, true);
CREATE TABLE `test_table_select_view` (`id` INTEGER PRIMARY KEY AUTO_INCREMENT);
CREATE VIEW `test_view_select_view` AS SELECT * FROM `test_table_select_view`;