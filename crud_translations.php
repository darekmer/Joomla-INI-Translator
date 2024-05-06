<?php
include 'db.php'; // Dołączenie pliku db.php do połączenia z bazą danych
include 'lang.php';
$perPage = 10; // Ilość wierszy na stronę
$displayPages = 5; // Ilość stron do wyświetlenia po obu stronach bieżącej strony
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $perPage;

// Pobieranie liczby wszystkich tłumaczeń
$totalRows = $pdo->query("SELECT COUNT(*) FROM translations")->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

// Pobieranie tłumaczeń z bazy danych dla danej strony
$stmt = $pdo->prepare("SELECT * FROM translations LIMIT :offset, :perPage");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$translations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" data-lang="<?php echo $_SESSION['lang']; ?>" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $uiTranslations['translator_index']; ?></title>
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
				<h2><?php echo $uiTranslations['manage_translations_header']; ?></h2>
			</div>
<table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo $uiTranslations['key']; ?></th>
                <th><?php echo $uiTranslations['source_string']; ?></th>
                <th><?php echo $uiTranslations['translation']; ?></th>
                <th><?php echo $uiTranslations['actions']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($translations as $translation): ?>
            <tr>
                <td><?php echo htmlspecialchars($translation['id']); ?></td>
                <td><?php echo htmlspecialchars($translation['klucz']); ?></td>
                <td><?php echo htmlspecialchars($translation['ciag_zrodlowy']); ?></td>
                <td><?php echo htmlspecialchars($translation['tlumaczenie']); ?></td>
                <td>
                    <button class="btn btn-primary btn-sm w-100 mb-1" data-bs-toggle="modal" data-bs-target="#editTranslationModal<?php echo $translation['id']; ?>"><?php echo $uiTranslations['edit']; ?></button>
                    <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#deleteTranslationModal<?php echo $translation['id']; ?>"><?php echo $uiTranslations['delete']; ?></button>
                </td>
            </tr>
            <?php include 'modals/edit_translation_modal.php'; ?>
            <?php include 'modals/delete_translation_modal.php'; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
		</div>
	</main>
<footer class="footer bg-body-tertiary border-top">
    <div class="container-fluid">
        <div class="row text-muted align-items-center">
            <div class="col d-flex justify-content-between align-items-center">
                <!-- Mini-logo -->
                <div class="d-flex align-items-center">
                    <p class="small mb-2 mt-2">
                        <a class="mini-logo" href="index.php"><?php echo $uiTranslations['translator_for_joomla']; ?> <span class="fw-bold">Joomla</span></a>
                    </p>
                </div>
                <!-- Dodaj tłumaczenie -->
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTranslationModal">
                        <?php echo $uiTranslations['add_translation']; ?>
                    </button>
                </div>
                <!-- Paginacja -->
                <nav aria-label="navigation" class="d-flex align-items-center">
                    <ul class="pagination pagination-sm mb-2 mt-2">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=1"><<</a></li>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>"><</a></li>
                        <?php endif; ?>
                        <?php
                        for ($i = max($page - $displayPages, 1); $i <= min($page + $displayPages, $totalPages); $i++):
                            echo '<li class="page-item ' . ($i === $page ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                        endfor;
                        ?>
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">></a></li>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $totalPages; ?>">>></a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- Prawa -->
                <div class="d-flex align-items-center">
                    <p class="right small mb-2 mt-2"><?php echo $uiTranslations['footer_rights']; ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>


</div>
<?php include 'modals/add_translation_modal.php'; ?>
<?php include 'modals/check_duplicates_modal.php'; ?>
	<script>
    var translations = {
        noDuplicatesFound: '<?php echo addslashes($uiTranslations['no_duplicates_found']); ?>',
        connectionError: '<?php echo addslashes($uiTranslations['connection_error']); ?>',
        duplicatesRemoved: '<?php echo addslashes($uiTranslations['duplicates_removed']); ?>',
        errorMessage: '<?php echo addslashes($uiTranslations['error_message']); ?>',
		countLabel: '<?php echo addslashes($uiTranslations['count_label']); ?>'
    };
</script>
<?php include 'includes/footer.php'; ?>
