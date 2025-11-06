-- database: ../test.sqlite
-- Note: Do not delete the line above! It is helpful for testing your init.sql file.
--
-- TODO: entries, tags, and entry_tags table schemas
-- TODO: seed data
DROP TABLE IF EXISTS "course_requests";

CREATE TABLE "restaurants" (
    "id" INTEGER NOT NULL UNIQUE,
    "name" TEXT UNIQUE NOT NULL,
    "address" TEXT UNIQUE NOT NULL,
    PRIMARY KEY ("id" AUTOINCREMENT)
);

CREATE TABLE "rest_types" (
    "id" INTEGER NOT NULL UNIQUE,
    "cuisine_type" TEXT NOT NULL,
    PRIMARY KEY ("id" AUTOINCREMENT)
);

CREATE TABLE "foreign_key" (
    "id" INTEGER NOT NULL UNIQUE,
    "rest_id" INTEGER NOT NULL,
    "cusine_id" TEXT NOT NULL,
    PRIMARY KEY ("id" AUTOINCREMENT) FOREIGN KEY ("rest_id") REFERENCES "restaurants" ("id") FOREIGN KEY ("cuisine_id") REFERENCES "courses" ("id")
);
