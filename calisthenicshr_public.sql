CREATE DATABASE IF NOT EXISTS calisthenicshr
    DEFAULT CHARACTER SET utf8 COLLATE utf8_croatian_ci;
USE calisthenicshr;

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
