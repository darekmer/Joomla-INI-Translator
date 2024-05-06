<?php
include 'db.php'; // Dołączenie pliku db.php

header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="translations_export.sql"');

echo "USE `{$db_name}`;\n";
echo "TRUNCATE TABLE `translations`;\n";

$stmt = $pdo->query("SELECT * FROM translations");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "INSERT INTO `translations` (`id`, `klucz`, `ciag_zrodlowy`, `tlumaczenie`) VALUES (NULL, "
        . $pdo->quote($row['klucz']) . ", "
        . $pdo->quote($row['ciag_zrodlowy']) . ", "
        . $pdo->quote($row['tlumaczenie']) . ");\n";
}
exit;
?>
