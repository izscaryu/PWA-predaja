-- Sanitized CalisthenicsHR database schema for public repo
-- NOTE: This file contains only the schema (no user data or INSERTs).
-- Import original private dump locally if you need sample data.

CREATE DATABASE IF NOT EXISTS calisthenicshr
    DEFAULT CHARACTER SET utf8 COLLATE utf8_croatian_ci;
USE calisthenicshr;

-- ---------- Tablica vijesti ----------
DROP TABLE IF EXISTS vijesti;
CREATE TABLE vijesti (
    id INT(11) NOT NULL AUTO_INCREMENT,
    datum VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_croatian_ci,
    naslov VARCHAR(64) CHARACTER SET latin2 COLLATE latin2_croatian_ci,
    sazetak TEXT CHARACTER SET latin2 COLLATE latin2_croatian_ci,
    tekst TEXT CHARACTER SET latin2 COLLATE latin2_croatian_ci,
    slika VARCHAR(64) CHARACTER SET latin2 COLLATE latin2_croatian_ci,
    kategorija VARCHAR(64) CHARACTER SET latin2 COLLATE latin2_croatian_ci,
    arhiva TINYINT(1),
    PRIMARY KEY (id)
);

-- ---------- Tablica korisnik ----------
DROP TABLE IF EXISTS korisnik;
CREATE TABLE korisnik (
    id INT(11) NOT NULL AUTO_INCREMENT,
    ime VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_croatian_ci,
    prezime VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_croatian_ci,
    korisnicko_ime VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_croatian_ci UNIQUE,
    lozinka VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci,
    razina TINYINT(1),
    PRIMARY KEY (id)
);

-- Sample placeholder user (replace with real data locally)
-- INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES
-- ('Example', 'User', 'example', '<PLACEHOLDER_HASH>', 0);
