<?php
session_start();
include 'connect.php';

// Tab "Unos" vidljiv samo administratoru (razina 1); Odjava vidljiva prijavljenima
$navAdmin = (isset($_SESSION['level']) && $_SESSION['level'] == 1);
$loggedIn = isset($_SESSION['username']);

$msg = '';
$registriranKorisnik = false;

if (isset($_POST['username'])) {
    $ime      = $_POST['ime'];
    $prezime  = $_POST['prezime'];
    $username = $_POST['username'];
    $lozinka  = $_POST['pass'];
    $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);
    $razina = 0;

    // Provjera postoji li već korisnik s tim korisničkim imenom (prepared statement)
    $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $msg = 'Korisničko ime već postoji!';
    } else {
        // Registracija novog korisnika (prepared statement)
        $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ssssi', $ime, $prezime, $username, $hashed_password, $razina);
            mysqli_stmt_execute($stmt);
            $registriranKorisnik = true;
        }
    }
    mysqli_close($dbc);
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija — CalisthenicsHR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <div class="brand">
            <h1 class="logo"><a href="index.php">CalisthenicsHR</a></h1>
            <p class="tagline">Trening vlastitim tijelom</p>
        </div>
        <nav>
            <a href="index.php">Početna</a>
            <a href="kategorija.php?id=treninzi">Treninzi</a>
            <a href="kategorija.php?id=vjezbe">Vježbe</a>
<?php if ($navAdmin) { ?><a href="unos.php">Unos</a><?php } ?>
            <a href="administrator.php">Administracija</a>
            <?php if ($loggedIn) { ?><a href="administrator.php?logout=1">Odjava</a><?php } ?>
        </nav>
    </header>

    <main>
        <?php if ($registriranKorisnik == true) { ?>
            <p class="info-msg">Korisnik je uspješno registriran! <a href="administrator.php">Prijava</a></p>
        <?php } else { ?>
        <div class="form-wrap">
            <h2 class="page-title">Registracija</h2>
            <form name="registracija" action="" method="POST">
                <div class="form-item">
                    <span id="porukaIme" class="bojaPoruke"></span>
                    <label for="ime">Ime</label>
                    <input type="text" name="ime" id="ime" class="form-field-textual">
                </div>
                <div class="form-item">
                    <span id="porukaPrezime" class="bojaPoruke"></span>
                    <label for="prezime">Prezime</label>
                    <input type="text" name="prezime" id="prezime" class="form-field-textual">
                </div>
                <div class="form-item">
                    <span id="porukaUsername" class="bojaPoruke"></span>
                    <label for="username">Korisničko ime</label>
                    <?php if ($msg != '') { echo '<span class="bojaPoruke">' . $msg . '</span>'; } ?>
                    <input type="text" name="username" id="username" class="form-field-textual">
                </div>
                <div class="form-item">
                    <span id="porukaPass" class="bojaPoruke"></span>
                    <label for="pass">Lozinka</label>
                    <input type="password" name="pass" id="pass" class="form-field-textual">
                </div>
                <div class="form-item">
                    <span id="porukaPassRep" class="bojaPoruke"></span>
                    <label for="passRep">Ponovite lozinku</label>
                    <input type="password" name="passRep" id="passRep" class="form-field-textual">
                </div>
                <div class="form-item">
                    <button type="submit" id="slanje">Registracija</button>
                </div>
            </form>
        </div>
        <?php } ?>
    </main>

    <footer>
        <p>&copy; 2026 CalisthenicsHR &middot; Trening vlastitim tijelom</p>
    </footer>

    <?php if ($registriranKorisnik != true) { ?>
    <script type="text/javascript">
        document.getElementById("slanje").onclick = function(event) {
            var slanjeForme = true;

            var poljeIme = document.getElementById("ime");
            if (poljeIme.value.length == 0) {
                slanjeForme = false;
                poljeIme.style.border = "1px dashed red";
                document.getElementById("porukaIme").innerHTML = "<br>Unesite ime!<br>";
            } else {
                poljeIme.style.border = "1px solid green";
                document.getElementById("porukaIme").innerHTML = "";
            }

            var poljePrezime = document.getElementById("prezime");
            if (poljePrezime.value.length == 0) {
                slanjeForme = false;
                poljePrezime.style.border = "1px dashed red";
                document.getElementById("porukaPrezime").innerHTML = "<br>Unesite prezime!<br>";
            } else {
                poljePrezime.style.border = "1px solid green";
                document.getElementById("porukaPrezime").innerHTML = "";
            }

            var poljeUsername = document.getElementById("username");
            if (poljeUsername.value.length == 0) {
                slanjeForme = false;
                poljeUsername.style.border = "1px dashed red";
                document.getElementById("porukaUsername").innerHTML = "<br>Unesite korisničko ime!<br>";
            } else {
                poljeUsername.style.border = "1px solid green";
                document.getElementById("porukaUsername").innerHTML = "";
            }

            var poljePass = document.getElementById("pass");
            var poljePassRep = document.getElementById("passRep");
            if (poljePass.value.length == 0 || poljePassRep.value.length == 0 || poljePass.value != poljePassRep.value) {
                slanjeForme = false;
                poljePass.style.border = "1px dashed red";
                poljePassRep.style.border = "1px dashed red";
                document.getElementById("porukaPass").innerHTML = "<br>Lozinke nisu iste!<br>";
                document.getElementById("porukaPassRep").innerHTML = "<br>Lozinke nisu iste!<br>";
            } else {
                poljePass.style.border = "1px solid green";
                poljePassRep.style.border = "1px solid green";
                document.getElementById("porukaPass").innerHTML = "";
                document.getElementById("porukaPassRep").innerHTML = "";
            }

            if (slanjeForme != true) {
                event.preventDefault();
            }
        };
    </script>
    <?php } ?>
</body>
</html>
