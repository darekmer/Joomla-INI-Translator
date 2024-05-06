CREATE DATABASE IF NOT EXISTS asystent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE asystent;
CREATE TABLE IF NOT EXISTS translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    klucz VARCHAR(255) NOT NULL,
    ciag_zrodlowy TEXT NOT NULL,
    tlumaczenie TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
