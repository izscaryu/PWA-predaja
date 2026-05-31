<?php
header('Content-Type: text/html; charset=utf-8');
$dbc = mysqli_connect('localhost', 'root', '', 'calisthenicshr')
    or die('Error connecting to MySQL server.' . mysqli_connect_error());
mysqli_set_charset($dbc, "utf8");

// Dozvoljene slike (ekstenzija => MIME). Sve ostalo se odbija.
$DOPUSTENE_SLIKE = array(
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif'
);

// Zaštita izlaza od XSS-a
function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// Provjeri i očisti ručno upisani naziv slike.
// Vraća siguran naziv (samo slova/brojevi/_/-/. + dopuštena ekstenzija) ili null.
function cistNazivSlike($naziv) {
    $naziv = basename(trim($naziv));
    if (!preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png|gif)$/i', $naziv)) {
        return null;
    }
    return $naziv;
}

// Sigurno spremanje učitane slike.
// Provjerava ekstenziju (whitelist), stvarni MIME (getimagesize) i čisti ime.
// Vraća naziv spremljene datoteke ili null ako upload nije valjan/dozvoljen.
function spremiSliku($file, $uplPath) {
    global $DOPUSTENE_SLIKE;

    if (!isset($file) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // 1) Whitelist ekstenzije
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!array_key_exists($ext, $DOPUSTENE_SLIKE)) {
        return null;
    }

    // 2) Datoteka mora stvarno biti slika
    $info = getimagesize($file['tmp_name']);
    if ($info === false) {
        return null;
    }

    // 3) Stvarni MIME mora odgovarati dozvoljenima
    if (!in_array($info['mime'], array('image/jpeg', 'image/png', 'image/gif'), true)) {
        return null;
    }

    // 4) Sigurno ime datoteke (sprječava shell.php, dvostruke ekstenzije, razmake)
    $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
    if ($base === '') {
        $base = 'slika';
    }
    $naziv = $base . '_' . time() . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], $uplPath . $naziv)) {
        return null;
    }
    return $naziv;
}
