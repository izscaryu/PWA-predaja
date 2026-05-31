<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

$uspjesnaPrijava = false;
$admin = false;
$imeKorisnika = '';

// ---------- Odjava ----------
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: administrator.php');
    exit;
}

// ---------- Prijava (login) ----------
if (isset($_POST['prijava'])) {
    $prijavaImeKorisnika = $_POST['username'];

    // Dohvat korisnika prepared statementom (zaštita od SQL injectiona)
    $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
        mysqli_stmt_fetch($stmt);
    }

    // Provjera lozinke
    if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($_POST['lozinka'], $lozinkaKorisnika)) {
        $uspjesnaPrijava = true;
        $admin = ($levelKorisnika == 1);
        $_SESSION['username'] = $imeKorisnika;
        $_SESSION['level'] = $levelKorisnika;
    } else {
        $uspjesnaPrijava = false;
    }
    mysqli_stmt_close($stmt);
}

// Je li trenutni posjetitelj administrator (po prijavi ili po sesiji)?
$jeAdmin = ($uspjesnaPrijava && $admin) || (isset($_SESSION['username']) && $_SESSION['level'] == 1);
$loggedIn = isset($_SESSION['username']) || $uspjesnaPrijava;

// ---------- Brisanje zapisa (samo administrator) ----------
if ($jeAdmin && isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $sql = "DELETE FROM vijesti WHERE id = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// ---------- Izmjena zapisa (samo administrator) ----------
if ($jeAdmin && isset($_POST['update'])) {
    $id       = (int) $_POST['id'];
    $title    = $_POST['title'];
    $about    = $_POST['about'];
    $content  = $_POST['content'];
    $category = $_POST['category'];
    $archive  = isset($_POST['archive']) ? 1 : 0;

    // Nova slika samo ako je valjana slika; inače zadrži postojeću (provjerenu)
    $picture = 'placeholder.jpg';
    if (isset($_POST['trenutna_slika'])) {
        $cisto = cistNazivSlike($_POST['trenutna_slika']);
        if ($cisto !== null) {
            $picture = $cisto;
        }
    }
    $spremljena = spremiSliku(isset($_FILES['pphoto']) ? $_FILES['pphoto'] : null, UPLPATH);
    if ($spremljena !== null) {
        $picture = $spremljena;
    }

    $sql = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, slika=?, kategorija=?, arhiva=? WHERE id=?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'sssssii', $title, $about, $content, $picture, $category, $archive, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// ---------- Novi članak (samo administrator) ----------
if ($jeAdmin && isset($_POST['insert'])) {
    $title    = $_POST['title'];
    $about    = $_POST['about'];
    $content  = $_POST['content'];
    $category = $_POST['category'];
    $date     = date('d.m.Y.');
    $archive  = isset($_POST['archive']) ? 1 : 0;

    // Naziv slike: provjereni ručni naziv, pa sigurno učitana datoteka, inače placeholder.
    // Dozvoljene su samo slike (jpg/jpeg/png/gif).
    $picture = 'placeholder.jpg';
    if (isset($_POST['slika_naziv'])) {
        $cisto = cistNazivSlike($_POST['slika_naziv']);
        if ($cisto !== null) {
            $picture = $cisto;
        }
    }
    $spremljena = spremiSliku(isset($_FILES['pphoto']) ? $_FILES['pphoto'] : null, UPLPATH);
    if ($spremljena !== null) {
        $picture = $spremljena;
    }

    $sql = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssssi', $date, $title, $about, $content, $picture, $category, $archive);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracija — CalisthenicsHR</title>
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
<?php if ($jeAdmin) { ?><a href="unos.php">Unos</a><?php } ?>
            <a href="administrator.php">Administracija</a>
            <?php if ($loggedIn) { ?><a href="administrator.php?logout=1">Odjava</a><?php } ?>
        </nav>
    </header>

    <main>
        <?php
        if ($jeAdmin) {
            // -------- Administratorski panel --------
            echo '<h2 class="page-title">Administracija članaka</h2>';
            echo '<p style="margin-bottom:20px;">Prijavljeni ste kao <strong>' . esc($_SESSION['username']) . '</strong>. <a href="administrator.php?logout=1">Odjava</a></p>';

            // -------- Forma za dodavanje novog članka --------
            echo '<form class="admin-record" enctype="multipart/form-data" action="" method="POST">';
            echo '<h3 style="margin-bottom:14px;">Dodaj novi članak</h3>';
            echo '<div class="form-item"><label>Naslov</label>';
            echo '<input type="text" name="title" class="form-field-textual"></div>';
            echo '<div class="form-item"><label>Kratki sažetak</label>';
            echo '<textarea name="about" rows="2" class="form-field-textual"></textarea></div>';
            echo '<div class="form-item"><label>Sadržaj</label>';
            echo '<textarea name="content" rows="6" class="form-field-textual"></textarea></div>';
            echo '<div class="form-item"><label>Naziv slike (npr. zgib.png — datoteku dodaš u img/)</label>';
            echo '<input type="text" name="slika_naziv" class="form-field-textual" placeholder="placeholder.jpg"></div>';
            echo '<div class="form-item"><label>...ili učitaj sliku odmah</label>';
            echo '<input type="file" name="pphoto" accept="image/jpg,image/gif,image/png" class="form-field-textual"></div>';
            echo '<div class="form-item"><label>Kategorija</label>';
            echo '<select name="category" class="form-field-textual">';
            echo '<option value="treninzi">Treninzi</option>';
            echo '<option value="vjezbe">Vježbe</option>';
            echo '</select></div>';
            echo '<div class="form-item"><label><input type="checkbox" name="archive"> Spremi u arhivu</label></div>';
            echo '<button type="reset">Poništi</button>';
            echo '<button type="submit" name="insert">Dodaj članak</button>';
            echo '</form>';

            echo '<h3 style="margin:30px 0 14px;">Postojeći članci</h3>';

            $query = "SELECT * FROM vijesti ORDER BY id DESC";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)) {
                $checked = ($row['arhiva'] == 1) ? 'checked' : '';
                $selTren = ($row['kategorija'] == 'treninzi') ? 'selected' : '';
                $selVjez = ($row['kategorija'] == 'vjezbe') ? 'selected' : '';

                echo '<form class="admin-record" enctype="multipart/form-data" action="" method="POST">';
                echo '<div class="form-item"><label>Naslov</label>';
                echo '<input type="text" name="title" class="form-field-textual" value="' . htmlspecialchars($row['naslov'], ENT_QUOTES) . '"></div>';

                echo '<div class="form-item"><label>Kratki sažetak</label>';
                echo '<textarea name="about" rows="2" class="form-field-textual">' . htmlspecialchars($row['sazetak'], ENT_QUOTES) . '</textarea></div>';

                echo '<div class="form-item"><label>Sadržaj</label>';
                echo '<textarea name="content" rows="6" class="form-field-textual">' . htmlspecialchars($row['tekst'], ENT_QUOTES) . '</textarea></div>';

                echo '<div class="form-item"><label>Slika</label>';
                echo '<input type="file" name="pphoto" accept="image/jpg,image/gif,image/png" class="form-field-textual">';
                echo '<br><img src="' . UPLPATH . esc($row['slika']) . '" width="120" alt="">';
                echo '<input type="hidden" name="trenutna_slika" value="' . htmlspecialchars($row['slika'], ENT_QUOTES) . '"></div>';

                echo '<div class="form-item"><label>Kategorija</label>';
                echo '<select name="category" class="form-field-textual">';
                echo '<option value="treninzi" ' . $selTren . '>Treninzi</option>';
                echo '<option value="vjezbe" ' . $selVjez . '>Vježbe</option>';
                echo '</select></div>';

                echo '<div class="form-item"><label><input type="checkbox" name="archive" ' . $checked . '> Arhivirano</label></div>';

                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<button type="reset">Poništi</button>';
                echo '<button type="submit" name="update">Izmjeni</button>';
                echo '<button type="submit" name="delete">Izbriši</button>';
                echo '</form>';
            }
        } else if ($uspjesnaPrijava == true && $admin == false) {
            // Prijavljen ovog zahtjeva, ali nije administrator
            echo '<p class="info-msg">Bok ' . esc($imeKorisnika) . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
        } else if (isset($_SESSION['username']) && $_SESSION['level'] == 0) {
            // Postoji sesija, ali korisnik nije administrator
            echo '<p class="info-msg">Bok ' . esc($_SESSION['username']) . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
        } else {
            // -------- Forma za prijavu --------
            ?>
            <div class="form-wrap">
                <h2 class="page-title">Prijava</h2>
                <?php if (isset($_POST['prijava']) && $uspjesnaPrijava == false) {
                    echo '<p class="bojaPoruke">Pogrešno korisničko ime ili lozinka.</p>';
                } ?>
                <form name="prijava" action="" method="POST">
                    <div class="form-item">
                        <label for="username">Korisničko ime</label>
                        <input type="text" name="username" id="username" class="form-field-textual">
                    </div>
                    <div class="form-item">
                        <label for="lozinka">Lozinka</label>
                        <input type="password" name="lozinka" id="lozinka" class="form-field-textual">
                    </div>
                    <div class="form-item">
                        <button type="submit" name="prijava">Prijava</button>
                    </div>
                </form>
                <p>Nemate račun? <a href="registracija.php">Registrirajte se</a>.</p>
            </div>
            <?php
        }
        mysqli_close($dbc);
        ?>
    </main>

    <footer>
        <p>&copy; 2026 CalisthenicsHR &middot; Trening vlastitim tijelom</p>
    </footer>
</body>
</html>
