<?php
header('Content-Type: text/html; charset=utf-8');

$DB_CONNECT_ERROR = false;
mysqli_report(MYSQLI_REPORT_OFF);
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'calisthenicshr';

try {
    $dbc = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$dbc) {
        $DB_CONNECT_ERROR = true;
    } else {
        mysqli_set_charset($dbc, "utf8");
    }
} catch (mysqli_sql_exception $e) {
    $DB_CONNECT_ERROR = true;
    $dbc = null;
}

$DOPUSTENE_SLIKE = array(
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif'
);

function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function cistNazivSlike($naziv) {
    $naziv = basename(trim($naziv));
    if (!preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png|gif)$/i', $naziv)) {
        return null;
    }
    return $naziv;
}

function spremiSliku($file, $uplPath) {
    global $DOPUSTENE_SLIKE;

    if (!isset($file) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!array_key_exists($ext, $DOPUSTENE_SLIKE)) {
        return null;
    }

    $info = getimagesize($file['tmp_name']);
    if ($info === false) {
        return null;
    }

    if (!in_array($info['mime'], array('image/jpeg', 'image/png', 'image/gif'), true)) {
        return null;
    }

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
