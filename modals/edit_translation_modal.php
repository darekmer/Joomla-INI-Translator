<div class="modal fade" id="editTranslationModal<?php echo $translation['id']; ?>" tabindex="-1" aria-labelledby="editTranslationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTranslationModalLabel"><?php echo $uiTranslations['edit_translation']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="action.php" method="post">
                <input type="hidden" name="id" value="<?php echo $translation['id']; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="key<?php echo $translation['id']; ?>" class="form-label"><?php echo $uiTranslations['key']; ?></label>
                        <input type="text" class="form-control" id="key<?php echo $translation['id']; ?>" name="key" value="<?php echo htmlspecialchars($translation['klucz']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="sourceString<?php echo $translation['id']; ?>" class="form-label"><?php echo $uiTranslations['source_string']; ?></label>
                        <textarea class="form-control" id="sourceString<?php echo $translation['id']; ?>" name="sourceString" rows="2" required><?php echo htmlspecialchars($translation['ciag_zrodlowy']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="translation<?php echo $translation['id']; ?>" class="form-label"><?php echo $uiTranslations['translation']; ?></label>
                        <textarea class="form-control" id="translation<?php echo $translation['id']; ?>" name="translation" rows="2" required><?php echo htmlspecialchars($translation['tlumaczenie']); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $uiTranslations['close']; ?></button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit"><?php echo $uiTranslations['save_changes']; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
