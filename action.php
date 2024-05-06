<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;
    $key = $_POST['key'] ?? '';
    $sourceString = $_POST['sourceString'] ?? '';
    $translation = $_POST['translation'] ?? '';

    switch ($action) {
        case 'add':
            $stmt = $pdo->prepare("INSERT INTO translations (klucz, ciag_zrodlowy, tlumaczenie) VALUES (?, ?, ?)");
            $stmt->execute([$key, $sourceString, $translation]);
            break;
        case 'edit':
            $stmt = $pdo->prepare("UPDATE translations SET klucz = ?, ciag_zrodlowy = ?, tlumaczenie = ? WHERE id = ?");
            $stmt->execute([$key, $sourceString, $translation, $id]);
            break;
        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM translations WHERE id = ?");
            $stmt->execute([$id]);
            break;
        default:
            exit('Nieznana operacja!');
    }

    header('Location: crud_translations.php');
    exit();
}
?>
