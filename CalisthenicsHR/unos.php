<?php
session_start();
include 'connect.php';
$navAdmin = (isset($_SESSION['level']) && $_SESSION['level'] == 1);
$loggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unos članka — CalisthenicsHR</title>
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
        <?php if ($navAdmin) { ?>
        <div class="form-wrap">
            <h2 class="page-title">Unos novog članka</h2>
            <form name="unos" action="skripta.php" method="POST" enctype="multipart/form-data">
                <div class="form-item">
                    <span id="porukaTitle" class="bojaPoruke"></span>
                    <label for="title">Naslov članka</label>
                    <input type="text" name="title" id="title" class="form-field-textual">
                </div>
                <div class="form-item">
                    <span id="porukaAbout" class="bojaPoruke"></span>
                    <label for="about">Kratki sažetak (do 50 znakova)</label>
                    <textarea name="about" id="about" rows="3" class="form-field-textual"></textarea>
                </div>
                <div class="form-item">
                    <span id="porukaContent" class="bojaPoruke"></span>
                    <label for="content">Sadržaj članka</label>
                    <textarea name="content" id="content" rows="8" class="form-field-textual"></textarea>
                </div>
                <div class="form-item">
                    <label for="slika_naziv">Naziv slike (npr. zgib.png — datoteku dodaš u img/)</label>
                    <input type="text" name="slika_naziv" id="slika_naziv" placeholder="placeholder.jpg" class="form-field-textual">
                </div>
                <div class="form-item">
                    <label for="pphoto">...ili učitaj sliku odmah</label>
                    <input type="file" name="pphoto" id="pphoto" accept="image/jpg,image/gif,image/png" class="form-field-textual">
                </div>
                <div class="form-item">
                    <label for="category">Kategorija</label>
                    <select name="category" id="category" class="form-field-textual">
                        <option value="treninzi">Treninzi</option>
                        <option value="vjezbe">Vježbe</option>
                    </select>
                </div>
                <div class="form-item">
                    <label><input type="checkbox" name="archive"> Spremi u arhivu</label>
                </div>
                <div class="form-item">
                    <button type="reset">Poništi</button>
                    <button type="submit" id="slanje">Prihvati</button>
                </div>
            </form>
        </div>
        <?php } else { ?>
        <p class="info-msg">Unos članaka je dostupan samo administratorima. <a href="administrator.php">Prijavite se</a>.</p>
        <?php } ?>
    </main>

    <footer>
        <p>&copy; 2026 CalisthenicsHR &middot; Trening vlastitim tijelom</p>
    </footer>

    <?php if ($navAdmin) { ?>
    <script type="text/javascript">
        document.getElementById("slanje").onclick = function(event) {
            var slanjeForme = true;

            // Naslov ne smije biti prazan
            var poljeTitle = document.getElementById("title");
            if (poljeTitle.value.length == 0) {
                slanjeForme = false;
                poljeTitle.style.border = "1px dashed red";
                document.getElementById("porukaTitle").innerHTML = "<br>Unesite naslov!<br>";
            } else {
                poljeTitle.style.border = "1px solid green";
                document.getElementById("porukaTitle").innerHTML = "";
            }

            // Sažetak ne smije biti prazan
            var poljeAbout = document.getElementById("about");
            if (poljeAbout.value.length == 0) {
                slanjeForme = false;
                poljeAbout.style.border = "1px dashed red";
                document.getElementById("porukaAbout").innerHTML = "<br>Unesite sažetak!<br>";
            } else {
                poljeAbout.style.border = "1px solid green";
                document.getElementById("porukaAbout").innerHTML = "";
            }

            // Sadržaj ne smije biti prazan
            var poljeContent = document.getElementById("content");
            if (poljeContent.value.length == 0) {
                slanjeForme = false;
                poljeContent.style.border = "1px dashed red";
                document.getElementById("porukaContent").innerHTML = "<br>Unesite sadržaj!<br>";
            } else {
                poljeContent.style.border = "1px solid green";
                document.getElementById("porukaContent").innerHTML = "";
            }

            if (slanjeForme != true) {
                event.preventDefault();
            }
        };
    </script>
    <?php } ?>
</body>
</html>
