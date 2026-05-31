<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

// Tab "Unos" vidljiv samo administratoru (razina 1); Odjava vidljiva prijavljenima
$navAdmin = (isset($_SESSION['level']) && $_SESSION['level'] == 1);
$loggedIn = isset($_SESSION['username']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Dohvat jednog članka pomoću prepared statementa
$row = null;
$query = "SELECT * FROM vijesti WHERE id=?";
$stmt = mysqli_stmt_init($dbc);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    mysqli_stmt_close($stmt);
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row ? esc($row['naslov']) : 'Članak'; ?> — CalisthenicsHR</title>
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
        <?php if ($row) { ?>
        <section role="main" class="article-full">
            <div class="row">
                <p class="category"><span><?php echo esc($row['kategorija']); ?></span></p>
                <h1 class="title"><?php echo esc($row['naslov']); ?></h1>
                <p class="meta">AUTOR: <span>Uredništvo CalisthenicsHR</span> &nbsp;|&nbsp; OBJAVLJENO: <span><?php echo esc($row['datum']); ?></span></p>
            </div>
            <section class="slika">
                <?php echo '<img src="' . UPLPATH . esc($row['slika']) . '" alt="' . esc($row['naslov']) . '">'; ?>
            </section>
            <section class="about">
                <p><?php echo esc($row['sazetak']); ?></p>
            </section>
            <section class="sadrzaj">
                <p><?php echo nl2br(esc($row['tekst'])); ?></p>
            </section>
        </section>
        <?php } else { ?>
        <p class="info-msg">Članak nije pronađen. <a href="index.php">Povratak na početnu</a>.</p>
        <?php } ?>
    </main>

    <footer>
        <p>&copy; 2026 CalisthenicsHR &middot; Trening vlastitim tijelom</p>
    </footer>
</body>
</html>
