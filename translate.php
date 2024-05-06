<?php
session_start();
include 'db.php'; // Dołączenie pliku db.php do połączenia z bazą danych
include 'lang.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToTranslate'])) {
    $fileContent = file_get_contents($_FILES['fileToTranslate']['tmp_name']);
    $_SESSION['fileContent'] = $fileContent;
    $iniArray = parse_ini_file($_FILES['fileToTranslate']['tmp_name'], false, INI_SCANNER_RAW);

    // Przygotowanie tablicy do przechowywania tłumaczeń
    $translations = [];
    $translatedKeys = []; // Tablica do przechowywania kluczy, które mają tłumaczenia w bazie danych

    foreach ($iniArray as $key => $value) {
        // Sprawdzenie, czy istnieje przetłumaczony ciąg w bazie danych
        $stmt = $pdo->prepare("SELECT tlumaczenie FROM translations WHERE klucz = ?");
        $stmt->execute([$key]);
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $translations[$key] = $row['tlumaczenie']; // Użycie tłumaczenia z bazy danych, jeśli istnieje
            $translatedKeys[$key] = true; // Oznaczamy, że klucz ma tłumaczenie w bazie
        } else {
            $translations[$key] = $value; // Użycie oryginalnego ciągu, jeśli tłumaczenie nie istnieje
            $translatedKeys[$key] = false; // Oznaczamy, że klucz nie ma tłumaczenia w bazie
        }
    }
} else {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" data-lang="<?php echo $_SESSION['lang']; ?>" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tłumacz dla Joomla</title>
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
			 <div class="search-bar">
				<form class="search-form d-flex align-items-center">
				<input type="text" id="searchInput" placeholder="<?php echo $uiTranslations['search']; ?>" title="<?php echo $uiTranslations['enter_keyword']; ?>">
				<button type="submit" title="<?php echo $uiTranslations['search']; ?>" disabled><i class="bi bi-search"></i></button>
				</form>
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
    <form action="save.php" method="post">
        <div id="translationRows">
			<?php foreach ($iniArray as $key => $value): 
    $translatedClass = $translatedKeys[$key] ? 'translated' : ''; // Dodanie klasy, jeśli jest tłumaczenie z bazy danych
?>
    <div class="row mb-3 translation-row">
        <div class="col-6">
            <strong><?php echo htmlspecialchars($key); ?></strong>
            <p><?php echo htmlspecialchars($value); ?></p>
            <input type="hidden" name="original[<?php echo htmlspecialchars($key); ?>]" value="<?php echo htmlspecialchars($value); ?>">
        </div>
        <div class="col-5">
            <textarea class="form-control <?php echo $translatedClass; ?>" name="translations[<?php echo htmlspecialchars($key); ?>]" rows="3"><?php echo htmlspecialchars($translations[$key] ?? $value); ?></textarea>
        </div>
        <div class="col-1 align-self-center">
            <input type="checkbox" name="sameAsOriginal[<?php echo htmlspecialchars($key); ?>]" value="1">
        </div>
    </div>
<?php endforeach; ?>

        </div>
    </form>
		</div>
	</main>
    <footer class="footer bg-body-tertiary border-top no-gutters">
        <div class="container-fluid">
            <div class="row align-items-center mt-2 mb-2">
                <!-- Logo -->
                <div class="col-auto">
                    <a class="mini-logo small" href="index.php">
                        <?php echo $uiTranslations['translator_for_joomla']; ?> <span class="fw-bold">Joomla</span>
                    </a>
                </div>
                <!-- Pole do wpisywania nazwy pliku -->
                <div class="col">
                    <input class="form-control form-control-sm" type="text" name="filename" placeholder="<?php echo $uiTranslations['file_name']; ?>" required>
                </div>
                <!-- Przycisk -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-success btn-sm"><?php echo $uiTranslations['save_file']; ?></button>
                </div>
                <!-- Prawa autorskie -->
                <div class="col-auto ms-auto">
                    <p class="text-muted small mb-2 mt-2">
                        <?php echo $uiTranslations['footer_rights']; ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pobieramy przycisk zapisywania i formularz główny
    const saveButton = document.querySelector('.footer button[type="submit"]');
    const mainForm = document.querySelector('main form');

    // Dodajemy nasłuch na przycisku "Zapisz"
    saveButton.addEventListener('click', function(event) {
        event.preventDefault();

        // Pobieramy wartości z pola input w footerze
        const filenameInput = document.querySelector('.footer input[name="filename"]');

        // Tworzymy nowe elementy input, które będą przeniesione do głównego formularza
        const filenameField = document.createElement('input');
        filenameField.type = 'hidden';
        filenameField.name = 'filename';
        filenameField.value = filenameInput.value;

        // Dodajemy nowo utworzone elementy do głównego formularza
        mainForm.appendChild(filenameField);

        // Wysyłamy formularz
        mainForm.submit();
    });
});

</script>
<?php include 'includes/footer.php'; ?>
