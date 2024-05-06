<?php
session_start();
include 'db.php';
include 'lang.php';
$message = ''; // Komunikat o stanie importu

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['sourceFile'], $_FILES['translationFile'])) {
    $sourceStrings = parse_ini_file($_FILES['sourceFile']['tmp_name']);
    $translatedStrings = parse_ini_file($_FILES['translationFile']['tmp_name']);

    if ($sourceStrings && $translatedStrings) {
        foreach ($sourceStrings as $key => $value) {
            // Sprawdzenie, czy klucz istnieje w tłumaczeniach, czy wartość klucza i klucz nie są puste, oraz czy tłumaczenie różni się od tekstu źródłowego
            if (array_key_exists($key, $translatedStrings) && !empty($translatedStrings[$key]) && !empty($key) && $value !== $translatedStrings[$key]) {
                // Sprawdzenie, czy istnieje już wpis w bazie danych
                $stmt = $pdo->prepare("SELECT * FROM translations WHERE klucz = ?");
                $stmt->execute([$key]);
                $existingTranslation = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingTranslation) {
                    // Aktualizacja istniejącego wpisu, jeśli tłumaczenie różni się od tego w bazie danych
                    $updateStmt = $pdo->prepare("UPDATE translations SET ciag_zrodlowy = ?, tlumaczenie = ? WHERE klucz = ?");
                    $updateStmt->execute([$value, $translatedStrings[$key], $key]);
                } else {
                    // Wstawienie nowego wpisu, jeśli nie istnieje w bazie danych
                    $insertStmt = $pdo->prepare("INSERT INTO translations (klucz, ciag_zrodlowy, tlumaczenie) VALUES (?, ?, ?)");
                    $insertStmt->execute([$key, $value, $translatedStrings[$key]]);
                }
            }
        }
        $_SESSION['message'] = "Import zakończony sukcesem.";
    } else {
        $_SESSION['message'] = "Błąd: Nie udało się załadować plików.";
    }

    header("Location: import_translations.php");
    exit();
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Czyszczenie komunikatu po wyświetleniu
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" data-lang="<?php echo $_SESSION['lang']; ?>" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $uiTranslations['import_translations_title']; ?></title>
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
		<div class="container mt-5">
			<h2><?php echo $uiTranslations['import_translations_title']; ?></h2>
			<?php if ($message): ?>
				<div class="alert <?php echo strpos($message, 'sukcesem') ? 'alert-success' : 'alert-danger'; ?>">
					<?php echo $message; ?>
				</div>
			<?php endif; ?>
			<form action="import_translations.php" method="post" enctype="multipart/form-data">
				<div class="mb-3">
					<label for="sourceFile" class="form-label"><?php echo $uiTranslations['source_file_label']; ?></label>
					<input type="file" class="form-control" id="sourceFile" name="sourceFile" accept=".ini" required>
				</div>
				<div class="mb-3">
					<label for="translationFile" class="form-label"><?php echo $uiTranslations['translation_file_label']; ?></label>
					<input type="file" class="form-control" id="translationFile" name="translationFile" accept=".ini" required>
				</div>
				<button type="submit" class="btn btn-primary"><?php echo $uiTranslations['import_translations_button']; ?></button>
			</form>
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
