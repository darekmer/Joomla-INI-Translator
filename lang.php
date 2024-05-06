<?php
session_start();

// Ustawienie domyślnego języka
if (!isset($_COOKIE['lang'])) {
    setcookie('lang', 'pl', time() + 3600 * 24 * 30, '/');
    $_SESSION['lang'] = 'pl';
} else {
    $_SESSION['lang'] = $_COOKIE['lang'];
}

// Ładowanie tłumaczeń
function loadUiTranslations($lang) {
    $uiTranslations = parse_ini_file("lang/$lang.ini");
    return $uiTranslations;
}

$uiTranslations = loadUiTranslations($_SESSION['lang']);

// Zmiana języka
if (isset($_POST['language'])) {
    $lang = $_POST['language'];
    setcookie('lang', $lang, time() + 3600 * 24 * 30, '/');
    $_SESSION['lang'] = $lang;
    $uiTranslations = loadUiTranslations($lang);
    header("Location: index.php");
}
?>
