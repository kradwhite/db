DROP TABLE IF EXISTS "test_create_table-1";
DROP TABLE IF EXISTS "test_create_table-2";
DROP TABLE IF EXISTS "test_create_column";
DROP TABLE IF EXISTS "test_alter_column";
DROP TABLE IF EXISTS "test_rename_column";
DROP TABLE IF EXISTS "new_table_name";
DROP TABLE IF EXISTS "test_rename_table";
DROP TABLE IF EXISTS "test_drop_column";
DROP TABLE IF EXISTS "test_create_index";
DROP TABLE IF EXISTS "test_rename_index";
DROP TABLE IF EXISTS "test_drop_index";
DROP TABLE IF EXISTS "test_create_foreign_key_target";
DROP TABLE IF EXISTS "test_create_foreign_key_source";
DROP TABLE IF EXISTS "test_rename_foreign_key_target";
DROP TABLE IF EXISTS "test_rename_foreign_key_source";
DROP TABLE IF EXISTS "test_drop_foreign_key_target";
DROP TABLE IF EXISTS "test_drop_foreign_key_source";
DROP TABLE IF EXISTS "test_drop_table";
DROP TABLE IF EXISTS "test_insert";

CREATE TABLE "test_create_table-1" ("id" serial primary key not null);
CREATE TABLE "test_create_column" ("id" serial primary key not null);
CREATE TABLE "test_alter_column" ("col1" integer);
CREATE TABLE "test_rename_column" ("col1" integer);
CREATE TABLE "test_rename_table" ("col1" integer);
CREATE TABLE "test_drop_column" ("col1" integer, "col2" integer);
CREATE TABLE "test_create_index" ("col1" integer);
CREATE TABLE "test_rename_index" ("col1" integer);
CREATE INDEX "test_rename_index_col1_idx" ON "test_rename_index" ("col1");
CREATE TABLE "test_drop_index" ("col1" integer);
CREATE INDEX "test_drop_index_col1_idx" ON "test_drop_index" ("col1");
CREATE TABLE "test_create_foreign_key_source" ("id" serial primary key not null);
CREATE TABLE "test_create_foreign_key_target" ("source_id" integer not null);
CREATE TABLE "test_rename_foreign_key_source" ("id" serial primary key not null);
CREATE TABLE "test_rename_foreign_key_target" ("source_id" integer not null);
ALTER TABLE "test_rename_foreign_key_target"
    ADD CONSTRAINT "fk_test_rename_foreign_key_target_source_id"
    FOREIGN KEY ("source_id") REFERENCES "test_rename_foreign_key_source" ("id");
CREATE TABLE "test_drop_foreign_key_source" ("id" serial primary key not null);
CREATE TABLE "test_drop_foreign_key_target" ("source_id" integer not null);
ALTER TABLE "test_drop_foreign_key_target"
    ADD CONSTRAINT "fk_test_drop_foreign_key_target_source_id"
    FOREIGN KEY ("source_id") REFERENCES "test_drop_foreign_key_source" ("id");
CREATE TABLE "test_drop_table"("id" SERIAL);
CREATE TABLE "test_insert"(
    "id" SERIAL PRIMARY KEY NOT NULL,
    "test_bool" bool NOT NULL,
    "test_integer" integer,
    "test_string" VARCHAR(10),
    "test_double" DOUBLE PRECISION
);