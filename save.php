<?php
session_start();
include 'db.php'; // Dołączenie pliku db.php
include 'lang.php'; // Załadowanie tłumaczeń

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['translations'], $_POST['filename'], $_POST['original'])) {
    $fileContent = $_SESSION['fileContent'] ?? '';
    $translations = $_POST['translations'];
    $originals = $_POST['original'];
    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['filename']) . '.ini';

    if (!empty($filename) && !empty($fileContent)) {
        $translatedContent = '';
        $lines = explode("\n", $fileContent);

        foreach ($lines as $line) {
            if (preg_match('/^;/', $line)) {
                $translatedContent .= $line . "\n";
            } else if (preg_match('/^(\w+)\s*=\s*(.*)$/', $line, $matches)) {
                $key = $matches[1];
                $value = $translations[$key] ?? $matches[2];
                $translatedContent .= "$key=\"$value\"\n";

                $sameAsOriginal = isset($_POST['sameAsOriginal'][$key]);
                
                if ($sameAsOriginal || (isset($translations[$key]) && $value !== $originals[$key])) {
                    // Aktualizacja bazy danych
                    $stmt = $pdo->prepare("SELECT * FROM translations WHERE klucz = ?");
                    $stmt->execute([$key]);
                    if ($stmt->rowCount() > 0) {
                        $update = $pdo->prepare("UPDATE translations SET ciag_zrodlowy = ?, tlumaczenie = ? WHERE klucz = ?");
                        $update->execute([$originals[$key], $value, $key]);
                    } else {
                        $insert = $pdo->prepare("INSERT INTO translations (klucz, ciag_zrodlowy, tlumaczenie) VALUES (?, ?, ?)");
                        $insert->execute([$key, $originals[$key], $value]);
                    }
                }
            } else {
                $translatedContent .= $line . "\n";
            }
        }

        if (file_put_contents("translations/$filename", $translatedContent) !== false) {
            $message = $uiTranslations['file_saved'] . $filename;
        } else {
            $message = $uiTranslations['file_save_error'];
        }
    } else {
        $message = $uiTranslations['filename_or_content_empty'];
    }
} else {
    $message = $uiTranslations['no_data_to_save'];
}

// Zresetowanie zawartości pliku przechowywanej w sesji
$_SESSION['fileContent'] = null;
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" data-lang="<?php echo $_SESSION['lang']; ?>" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $uiTranslations['save_translation_title']; ?></title>
	<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><circle cx='50' cy='50' r='40' fill='black' /><rect x='40' y='20' width='20' height='60' fill='white'/><rect x='25' y='20' width='50' height='20' fill='white'/></svg>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="main">
	<nav class="navbar bg-body-tertiary navbar-expand px-3 border-bottom">
		<div class="navbar-start d-flex align-items-center justify-content-start">
			<div class="toggle-sidebar-btn" id="sidebar-toggle">
				<span class="bi bi-list toggle-sidebar-btn"></span>
			</div>
		</div>
		<div class="navbar-end d-flex align-items-center justify-content-end ms-auto">
			<div class="d-none d-md-block text-end">
				<div class="d-flex align-items-center">
					<div class="lh-sm">
					  <div class="f6 clock-time" id="clock">Fri, May 19 2023, 13:48:16</div>
					</div>
						<div class="mx-2">|</div>
						<i id="fullscreenToggle" class="bi bi-arrows-fullscreen" style="cursor:pointer;" onclick="toggleFullScreen();"></i>
						<div class="mx-2">|</div>
						<i id="themeToggle" class="bi bi-brightness-high-fill" style="cursor:pointer; margin-right: 10px;"></i>
				</div>
			</div>
		</div>
	</nav>
	<main class="content px-3 py-2">
		<div class="container-fluid">
			<div class="mb-3">
				<h4></h4>
			</div>
<div class="container mt-5">
    <h2><?php echo $uiTranslations['translation_result_heading']; ?></h2>
    <p class="alert <?php echo (strpos($message, $uiTranslations['file_saved']) === 0) ? 'alert-success' : 'alert-danger'; ?>">
        <?php echo $message; ?>
    </p>
    <a href="index.php" class="btn btn-primary"><?php echo $uiTranslations['return_to_home']; ?></a>
</div>
		</div>
	</main>
	<footer class="footer bg-body-tertiary border-top">
		<div class="container-fluid">
			<div class="row text-muted align-items-center">
				<div class="col-6 text-start small">
					<p class="mb-2 mt-2"><a class="mini-logo" href="index.php"><?php echo $uiTranslations['translator_for_joomla']; ?> <span class="fw-bold">Joomla</span></a></p>
				</div>
				<div class="col-6 text-end small">
					<p class="right mb-2 mt-2"><?php echo $uiTranslations['footer_rights']; ?></p>
				</div>
			</div>
		</div>
	</footer>
</div>
<?php include 'includes/footer.php'; ?>
