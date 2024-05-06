<?php
include 'db.php';

try {
    // Zapytanie wybierające duplikaty na podstawie klucza i tłumaczenia
    $sql = "SELECT klucz, ciag_zrodlowy, tlumaczenie, COUNT(*) as count FROM translations GROUP BY klucz, tlumaczenie HAVING count > 1";
    $stmt = $pdo->query($sql);

    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($duplicates) > 0) {
        // Duplikaty zostały znalezione
        echo json_encode([
            'status' => 'success',
            'duplicates' => $duplicates
        ]);
    } else {
        // Brak duplikatów
        echo json_encode([
            'status' => 'none'
        ]);
    }
} catch (PDOException $e) {
    // Obsługa błędu
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
