<div class="wrapper">
<aside id="sidebar" class="sidebar bg-body-tertiary border border-right">
	<!-- Content For Sidebar -->
	<div class="h-100 flex-shrink-0 pe-4">
		<div class="sidebar-logo">
			<a class="text-logo" href="index.php"><?php echo $uiTranslations['translator_for_joomla']; ?> <span class="fw-bold">Joomla</span></a>
		</div>
		<ul class="sidebar-nav">
			<li class="sidebar-header">
				Menu:
			</li>
			<li class="sidebar-item">
				<a href="index.php" class="sidebar-link">
					<i class="bi bi-house me-2"></i>
					<?php echo $uiTranslations['home']; ?>
				</a>
			</li>
			<li class="sidebar-item">
				<a href="crud_translations.php" class="sidebar-link">
					<i class="bi bi-table me-2"></i>
					<?php echo $uiTranslations['manage_translations']; ?>
				</a>
			</li>
			<li class="sidebar-item">
				<a href="import_translations.php" class="sidebar-link">
					<i class="bi bi-database-down me-2"></i>
					<?php echo $uiTranslations['import_translations']; ?>
				</a>
			</li>

			<li class="sidebar-item">
				<a data-bs-toggle="collapse" class="sidebar-link collapsed" href="#menu-admin" role="button">
					<div class="d-flex justify-content-between">
					  <p class="mb-0"><i class="bi bi-filetype-sql me-2"></i><?php echo $uiTranslations['db_operations']; ?></p>
					  <i class="bi bi-caret-down"></i>
					</div>
				</a>
				<ul class="collapse" id="menu-admin" role="button">
					<a href="export_translations.php" class="d-block ps-2 text-decoration-none sidebar-link"><?php echo $uiTranslations['export_to_sql']; ?></a>
					<a href="import_translations_sql.php" class="d-block ps-2 text-decoration-none sidebar-link"><?php echo $uiTranslations['import_from_sql']; ?></a>
				</ul>
			</li>
			<li class="sidebar-header">
				<?php echo $uiTranslations['settings']; ?>
			</li>
			<li class="sidebar-item">
				<div class="mb-3 ms-3">
					<form method="post" action="">
						<span for="languageSelect" class="chose form-label pe-2"><?php echo $uiTranslations['choose_lang']; ?></span>
						<select class="form-select form-select-sm" name="language" id="languageSelect" onchange="this.form.submit()" style="width: auto; display: inline-block;" <?php echo basename($_SERVER['PHP_SELF']) != 'index.php' ? 'disabled' : ''; ?>>
							<option value="pl" <?php if ($_SESSION['lang'] === 'pl') echo 'selected'; ?>>Polski</option>
							<option value="en" <?php if ($_SESSION['lang'] === 'en') echo 'selected'; ?>>English</option>
						</select>
					</form>
					<div>
						<?php if (basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
							<span class="note text-muted small ms-2"><?php echo $uiTranslations['choose_lang_index']; ?></span>
						<?php endif; ?>
					</div>
				</div>
			</li>
		</ul>
	</div>
</aside>