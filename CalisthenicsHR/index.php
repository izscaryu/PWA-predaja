<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

// Tab "Unos" vidljiv samo administratoru (razina 1); Odjava vidljiva prijavljenima
$navAdmin = (isset($_SESSION['level']) && $_SESSION['level'] == 1);
$loggedIn = isset($_SESSION['username']);

// Ispisuje red članaka za zadanu kategoriju koristeći prepared statement
function prikaziKategoriju($dbc, $kategorija, $naslovSekcije, $limit) {
    echo '<section>';
    echo '<h2 class="category-heading">' . $naslovSekcije . '</h2>';
    echo '<div class="articles">';

    $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija=? ORDER BY id DESC LIMIT ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'si', $kategorija, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result)) {
            echo '<article>';
            echo '<img src="' . UPLPATH . esc($row['slika']) . '" alt="' . esc($row['naslov']) . '">';
            echo '<h3 class="title"><a href="clanak.php?id=' . (int) $row['id'] . '">' . esc($row['naslov']) . '</a></h3>';
            echo '<p class="summary">' . esc($row['sazetak']) . '</p>';
            echo '</article>';
        }
        mysqli_stmt_close($stmt);
    }

    echo '</div>';
    echo '</section>';
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CalisthenicsHR — Trening vlastitim tijelom</title>
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
        <?php
        prikaziKategoriju($dbc, 'treninzi', 'Treninzi', 4);
        prikaziKategoriju($dbc, 'vjezbe', 'Vježbe', 4);
        mysqli_close($dbc);
        ?>
    </main>

    <footer>
        <p>&copy; 2026 CalisthenicsHR &middot; Trening vlastitim tijelom</p>
    </footer>
</body>
</html>
