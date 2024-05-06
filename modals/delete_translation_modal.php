<div class="modal fade" id="deleteTranslationModal<?php echo $translation['id']; ?>" tabindex="-1" aria-labelledby="deleteTranslationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTranslationModalLabel"><?php echo $uiTranslations['delete_translation']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="action.php" method="post">
                <input type="hidden" name="id" value="<?php echo $translation['id']; ?>">
                <div class="modal-body">
					<?php echo $uiTranslations['confirm_delete_translation']; ?>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $uiTranslations['cancel']; ?></button>
                    <button type="submit" class="btn btn-danger" name="action" value="delete"><?php echo $uiTranslations['delete']; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
