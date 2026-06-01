<?php
session_start();
include 'connect.php';
define('UPLPATH', 'img/');

$navAdmin = (isset($_SESSION['level']) && $_SESSION['level'] == 1);
$loggedIn = isset($_SESSION['username']);
if (!$navAdmin) {
    mysqli_close($dbc);
    header('Location: administrator.php');
    exit;
}

$title    = isset($_POST['title']) ? $_POST['title'] : '';
$about    = isset($_POST['about']) ? $_POST['about'] : '';
$content  = isset($_POST['content']) ? $_POST['content'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$date     = date('d.m.Y.');
$archive  = isset($_POST['archive']) ? 1 : 0;

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

$query = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($dbc);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'ssssssi', $date, $title, $about, $content, $picture, $category, $archive);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc($title); ?> — CalisthenicsHR</title>
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
        <section role="main" class="article-full">
            <div class="row">
                <p class="category"><span><?php echo esc($category); ?></span></p>
                <h1 class="title"><?php echo esc($title); ?></h1>
                <p class="meta">AUTOR: <span>Uredništvo CalisthenicsHR</span> &nbsp;|&nbsp; OBJAVLJENO: <span><?php echo esc($date); ?></span></p>
            </div>
            <section class="slika">
                <?php echo '<img src="' . UPLPATH . esc($picture) . '" alt="' . esc($title) . '">'; ?>
            </section>
            <section class="about">
                <p><?php echo esc($about); ?></p>
            </section>
            <section class="sadrzaj">
                <p><?php echo nl2br(esc($content)); ?></p>
            </section>
        </section>
        <p style="text-align:center; margin-top:20px;">
            <a href="index.php">Povratak na početnu</a> &nbsp;|&nbsp; <a href="unos.php">Unesi još jedan članak</a>
        </p>
    </main>

    <footer>
        <p>&copy; 2026 CalisthenicsHR &middot; Trening vlastitim tijelom</p>
    </footer>
</body>
</html>
