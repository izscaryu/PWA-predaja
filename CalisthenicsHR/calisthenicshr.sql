-- ============================================================
--  CalisthenicsHR - baza podataka
--  Uvoz: phpMyAdmin -> Import -> odaberi ovu datoteku
-- ============================================================

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

-- ---------- Primjeri vijesti ----------
-- Slike: u stupcu "slika" je naziv datoteke koju ubaciš u mapu img/.
-- Tekst koristi prijelome redaka koji se na stranici prikazuju kao novi redovi.
INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES
('26.05.2026.', '100 sklekova na dan - 30-day challenge',
 'Sto sklekova kroz dan nije magija, nego razbijanje broja na manje serije.',
 'Sto push-upova u nizu zvuči nemoguće dok ne shvatiš trik: razbij broj na manje serije kroz cijeli dan.\n\nKako izgleda mjesec:\nTjedan 1 - 5 serija po 40% maxa, pauza 90 s.\nTjedan 2 - 6 serija, pauza skraćena na 75 s.\nTjedan 3 - ubaci slow negatives, 3 sekunde spuštanja.\nTjedan 4 - gusti volumen plus jedna duga serija navečer.\n\nForma > brojka. Prsa do poda, ruke do kraja ispružene, trup napet ko daska. Čim forma popusti, stani - ego ponovke ne računaju.',
 'sklekovi.png', 'treninzi', 0),

('25.05.2026.', 'Pull-up od nule do 10',
 'Prvi čisti zgib je najteži. Evo puta od mrtvog visa do deset ponovki.',
 'Skoro svi zapnu na prvom zgibu. Problem rijetko je snaga - češće je tehnika i nedostatak strpljenja.\n\nProgresija, korak po korak:\nMrtvi vis 30 s - naviknite hvat i ramena.\nScapular pulls 3x8 - nauči spustiti lopatice prije povlačenja.\nNegative pull-ups 4x5 - spuštaj se polako, 3 do 5 sekundi.\nBand-assisted zgibovi - guma skida dio težine.\n\nTreniraj 3x tjedno s danom odmora između. Kad osjetiš da vučeš leđima, a ne samo rukama, prvih deset dolazi brže nego što misliš.',
 'zgibovi.png', 'treninzi', 0),

('24.05.2026.', 'Push Pull Legs - bodyweight verzija',
 'Klasični PPL split bez tereta, samo vlastito tijelo.',
 'PPL dijeli trening na guranje, povlačenje i noge. Radi jednako dobro i bez utega.\n\nPush: push-ups, dips, pike push-ups.\nPull: pull-ups, australski zgibovi, face pull s gumom.\nLegs: čučnjevi, lunges, pistol progresije, podizanje na prste.\n\nIde 3 ili 6 dana tjedno. Svaka skupina dobije dovoljno volumena i pun oporavak.\n\nProgress: dodaj ponovku ili težu varijantu svaki tjedan. To je progressive overload, samo s vlastitom težinom umjesto pločica.',
 'ppl.png', 'treninzi', 0),

('23.05.2026.', 'Mobility za ramena prije treninga',
 'Pet minuta koje ti čuvaju ramena i otključavaju handstand.',
 'Ako te ramena stežu u handstandu ili overhead pokretima, problem je najčešće mobility, ne snaga.\n\nKratka rutina prije treninga:\nKruženje rukama - 2x15.\nShoulder dislocates s palicom ili gumom - 3x10.\nMrtvi vis - 3x20 s.\nWall slides (zid-anđeli) - 3x10.\n\nSve polako i kontrolirano, bez trzaja. Pet do osam minuta dnevno i razlika se vidi za par tjedana.',
 'mobilnost-ramena.png', 'treninzi', 0),

('22.05.2026.', 'Prehrana za kalisteniju',
 'Koliko proteina, kalorija i kad jesti - bez bullshita.',
 'Kalistenija voli snagu uz nisku težinu, pa prehrana radi pola posla.\n\nOsnove:\nProtein - 1.6 do 2.2 g po kg tjelesne težine dnevno.\nMršavljenje - lagani deficit, 300 do 500 kcal ispod održavanja.\nMasa - lagani suficit, ne pretjeruj.\n\nUgljikohidrati pune energiju za eksplozivne setove, masti drže hormone. Obrok sat-dva prije treninga.\n\nNajpodcjenjeniji dio: san i voda. Bez njih napredak stoji, ma koliko dobro jeo. Suplementi su zadnji na listi - hrana prvo.',
 'prehrana.png', 'treninzi', 0),

('21.05.2026.', '5 grešaka početnika',
 'Stvari koje te koče, a lako ih je popraviti.',
 'Većina početnika radi iste greške. Evo ih pet i kako ih riješiti.\n\n1. Preskakanje progresija - preteška varijanta uništi formu.\n2. Zanemarivanje pulla - previše prsa, premalo leđa.\n3. Premalo odmora - mišić raste u oporavku, ne na treningu.\n4. Ego ponovke - pola amplitude se ne broji.\n5. Nema plana - bez bilježenja ne znaš napreduješ li.\n\nRješenje je dosadno ali radi: drži se progresija, zapisuj serije, spavaj 7-9 h i budi strpljiv. Konzistentnost mjesecima pobjeđuje tjedan ludila.',
 'greske-pocetnika.png', 'treninzi', 0),

('20.05.2026.', 'Weekly plan za napredne (6 dana)',
 'Raspored za one koji su prešli osnove i žele skill rad.',
 'Šestodnevni PPL x2 s naglaskom na vještine.\n\nPON Push, UTO Pull, SRI Legs.\nČET Push, PET Pull, SUB Legs.\nNED odmor.\n\nNa početak svakog dana stavi jedan skill dok si svjež: muscle up, handstand ili front lever, 4-5 kvalitetnih pokušaja. Zatim glavni volumen, 3-4 vježbe po 3-4 serije.\n\nDeload svaki 5. ili 6. tjedan - prepolovi volumen. Ako snaga pada više dana zaredom, uzmi extra odmor. To nije lijenost, to je pamet.',
 'tjedni-plan.png', 'treninzi', 0),

('19.05.2026.', 'Muscle Up: tehnika i progresije',
 'Spoj eksplozivnog zgiba i dipa u jedan pokret.',
 'Muscle up je trenutak kad kalistenija postane efektna. Ali traži bazu.\n\nPrije nego krećeš: 10+ čistih zgibova i 10+ dipova.\n\nProgresije:\nExplosive pull-ups do prsa.\nHigh pulls s povlačenjem prema naprijed (luk, ne ravno gore).\nBand-assisted muscle up.\nNegative muscle up - spuštaj polako, 4x3.\n\nNauči false grip za stabilan prijelaz. Najčešća greška: premali zamah i prerano savijanje laktova. Timing pobjeđuje snagu.',
 'muscle-up.png', 'vjezbe', 0),

('18.05.2026.', 'Pistol squat - single leg čučanj',
 'Snaga, balans i mobilnost gležnja u jednoj vježbi.',
 'Pistol izgleda kao trik, ali je ozbiljan test snage nogu.\n\nNajčešće greške:\nPeta se diže - radi na mobility gležnja.\nKoljeno upada prema unutra.\nLeđa se grbe na dnu.\n\nProgresija:\nČučanj na klupu (box squat) na jednoj nozi.\nPistol uz oslonac rukom za okvir vrata.\nSpori eccentric, 3-5 s spuštanja.\nPuni pistol.\n\nCilj: 3x5 po nozi čisto. Jedna jaka noga vuče gore sve ostale vježbe za noge.',
 'pistol-squat.png', 'vjezbe', 0),

('17.05.2026.', 'Handstand Push-Up program',
 'Put do sklekova naglavačke, korak po korak.',
 'HSPU gradi ramena kakva teretana rijetko vidi. Ali prvo treba stabilan stoj.\n\nPreduvjet: handstand uza zid, 30 s držanja.\n\nProgresija:\nPike push-ups - 3x8.\nElevated pike push-ups (noge na povišenju) - 4x6.\nDjelomični HSPU uza zid.\nPuni HSPU uza zid, glava do poda.\n\nRavna linija tijela, ruke u širini ramena. Spuštaj sporo, guraj eksplozivno. Dvaput tjedno - rame voli volumen, ali traži i oporavak.',
 'handstand.png', 'vjezbe', 0),

('16.05.2026.', 'Dips - kompletni vodič',
 'Temeljna push vježba za prsa i tricepse.',
 'Dip je jedna od najboljih push vježbi, a svatko je radi malo drugačije ovisno o cilju.\n\nNagib trupa naprijed = naglasak na prsa.\nUspravan trup = naglasak na tricepse.\n\nSpuštaj dok ramena ne dođu u razinu laktova. Ne idi predubok ni prebrz - zglob ramena to ne voli.\n\nProgresija:\nDip na podlakticama.\nNegative dips, 4x5.\nBand-assisted dip.\nPuni dip, 3x8 do 12.\n\nKad postane lako, dodaj slow reps ili težinu oko struka.',
 'dip.png', 'vjezbe', 0),

('15.05.2026.', 'Planche - progresije za početnike',
 'Od tuck planche do full planche, dugačak ali vrijedan put.',
 'Planche je vrhunac statičke snage - cijelo tijelo paralelno s podom, samo na rukama.\n\nPut tamo:\nPlanche lean - naginjanje naprijed u plank poziciji, 5x10 s.\nTuck planche - koljena uz prsa.\nAdvanced tuck.\nStraddle planche.\n\nKljuč su jako protrahirane lopatice i pripremljena zapešća. Radi kratke holdove, 5 do 10 s, s punim oporavkom.\n\nNapredak je spor i mjeri se mjesecima, ne tjednima. Uvijek zagrij zapešća prije. Strpljenje je ovdje pola vještine.',
 'planche.png', 'vjezbe', 0),

('14.05.2026.', 'Front Lever - progresije',
 'Vodoravni hold koji gradi monstruozna leđa.',
 'Front lever: visiš pod šipkom, tijelo ravno i paralelno s podom, licem gore.\n\nProgresija:\nTuck front lever - 5x10 s.\nAdvanced tuck.\nSingle leg.\nStraddle.\nFull front lever.\n\nRuke ostaju ravne, lopatice spuštene i stisnute (depression + retraction). Trup i guza napeti da tijelo ne propada na dno.\n\nRadi holdove plus front lever raises za dinamiku. Dvaput tjedno, dovoljno odmora. Ovo je skill, ne kondicija - kvaliteta ponovki je sve.',
 'front-lever.png', 'vjezbe', 0),

('13.05.2026.', 'L-sit od nule do 30 sekundi',
 'Statički hold za core, kukove i tricepse.',
 'L-sit izgleda jednostavno dok ga ne probaš - sjediš u zraku, ruke ravne, noge ispružene naprijed.\n\nProgresija:\nFoot-supported - guraj se s poda i podigni guzu, 3x10 s.\nOne foot up.\nTuck L-sit - koljena savijena.\nFull L-sit.\n\nRadi na paraletama ili šipkama, ima više prostora za noge. Drži ramena spuštena i guraj pod sobom.\n\nTrening svaki drugi dan, skupi oko 60 s ukupno po sesiji. Fleksibilnost stražnje lože pomaže da noge ostanu ravne.',
 'l-sit.png', 'vjezbe', 0);

-- ---------- Korisnici ----------
-- Admin (lozinka: admin123)
INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES
('Admin', 'User', 'admin', '$2y$10$pM4oHhjkVfE53DLO4WASeua7j/4bGVLm5g3Qifdk8N2bnNtnIoDVC', 1);

-- Primjeri korisnika (razina 1 = administrator, 0 = obični)
-- ivan/ivan123 (admin), marko/marko123 (obični), ana/ana123 (obični), petra/petra123 (admin)
INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES
('Ivan',  'Horvat', 'ivan',  '$2y$10$tpr0RC0tdWJBJ1Bx/AQyluKA61ARVF49WD4neMf8fuh1IadaejC.i', 1),
('Marko', 'Marić',  'marko', '$2y$10$AAAaAETMi.7t.26g74f0mer0DC3C.azrykVAZWreaPTlLQOhAM5p2', 0),
('Ana',   'Kovač',  'ana',   '$2y$10$artVjTw4kljz/9ZPvOVXHe3HuF0H/FdgtTNuqdmJI95EgjQpuK2KO', 0),
('Petra', 'Novak',  'petra', '$2y$10$IyQ./wtUQdQw0WZq8WZ6sORt92h04/qZRcHHHasNNNrqIZDvQEnuW', 1);
