# CalisthenicsHR

CalisthenicsHR je jednostavan PHP/MySQL web site za vijesti i vodiče o kalistenici. Ovaj repozitorij sadrži implementaciju predloška vijesti s administracijom, registracijom korisnika i uploadom slika.

**Napomena:** Ovo je projektni zadatak iz predmeta _Programiranje web aplikacija_.

## Sadržaj repozitorija
- `CalisthenicsHR/` — PHP kod, stilovi i slike web aplikacije.
- `calisthenicshr.sql` — potpuni SQL dump s primjerima vijesti i korisnika (sadrži sample korisnike i hashirane lozinke).
- `calisthenicshr_public.sql` — samo shema baze (bez korisničkih podataka), namijenjena za javni repozitorij.

## Značajke
- Prikaz članaka po kategorijama (`treninzi`, `vjezbe`).
- Pojedinačna stranica članka (`clanak.php?id=...`).
- Administracijski panel za unos/izmjenu/brisanje članaka (`administrator.php`).
- Upload i sigurnosne provjere slika.
- Registracija i prijava korisnika s hashed lozinkama.

## Zahtjevi
- PHP 7.4+ sa `mysqli` i `gd` (preporučeno).
- MySQL ili MariaDB.
- Web server (npr. Apache u XAMPP/WAMP/MAMP) ili PHP built-in server.

## Kako preuzeti
```bash
git clone https://github.com/izscaryu/PWA-predaja.git
cd PWA-predaja/CalisthenicsHR
```

## Instalacija i pokretanje lokalno

1. Import baze podataka

- Ako želite sample podatke (preporučeno za razvoj), uvezite `calisthenicshr.sql`:
```bash
mysql -u root -p < calisthenicshr.sql
```

- Ako želite samo shemu bez podataka, uvezite `calisthenicshr_public.sql` (nalazi se u root repozitorija):
```bash
mysql -u root -p < ../calisthenicshr_public.sql
```

2. Konfiguracija baze

Projekt koristi environment varijable za spajanje na bazu. Postavite ih prije pokretanja servera ili uredite `CalisthenicsHR/connect.php` lokalno:

- Varijable koje se koriste: `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`.

Primjer (Windows PowerShell):
```powershell
setx DB_HOST "localhost"
setx DB_USER "root"
setx DB_PASS ""
setx DB_NAME "calisthenicshr"
```
Otvorite novu PowerShell sesiju da varijable stupe na snagu.

3. Provjerite direktorij `img/` u `CalisthenicsHR/` i stavite sample slike (ili koristite postojeće u repozitoriju).

4. Pokretanje lokalnog servera (brzi test)
```bash
cd CalisthenicsHR
php -S localhost:8000
```
Otvorite u pregledniku: `http://localhost:8000/index.php`.

Ako koristite XAMPP/WAMP premjestite `CalisthenicsHR` u `htdocs` i otvorite `http://localhost/CalisthenicsHR/`.

## Admin pristup
Ako ste uvezli puni `calisthenicshr.sql`, u dumpu se nalaze sample korisnici (uključujući admin account). Provjerite unesene korisnike u tablici `korisnik` i koristite odgovarajuće korisničko ime/lozinku.

## Pokretanje i testiranje
- Početna: `http://localhost:8000/index.php`
- Kategorija: `http://localhost:8000/kategorija.php?id=treninzi`
- Primjer članka: `http://localhost:8000/clanak.php?id=4`

## Troubleshooting
- Ako članak prikazuje “Članak nije pronađen”, provjerite ima li zapis s tim `id` u tablici `vijesti`.
- Ako upload slika ne radi, provjerite PHP postavke (`upload_max_filesize`, `post_max_size`) i prava nad mapom `img/`.
- Ako se ne možete spojiti na bazu, provjerite environment varijable ili unesite kredencijale u `connect.php` za lokalno testiranje.


