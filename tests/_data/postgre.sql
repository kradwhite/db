DROP TABLE IF EXISTS "test_create_table-1";
DROP TABLE IF EXISTS "test_create_table-2";
DROP TABLE IF EXISTS "test_create_column";
DROP TABLE IF EXISTS "test_alter_column";
DROP TABLE IF EXISTS "test_rename_column";

CREATE TABLE "test_create_table-1" ("id" serial primary key not null);
CREATE TABLE "test_create_column" ("id" serial primary key not null);
CREATE TABLE "test_alter_column" ("col1" integer);
CREATE TABLE "test_rename_column" ("col1" integer);