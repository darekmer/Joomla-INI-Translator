<?php
include 'db.php';

try {
    $sql = "DELETE t1 FROM translations t1 INNER JOIN translations t2 WHERE t1.id > t2.id AND t1.klucz = t2.klucz AND t1.tlumaczenie = t2.tlumaczenie";
    $pdo->exec($sql);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
