<div class="modal fade" id="addTranslationModal" tabindex="-1" aria-labelledby="addTranslationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTranslationModalLabel"><?php echo $uiTranslations['add_new_translation']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="action.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="key" class="form-label"><?php echo $uiTranslations['key']; ?></label>
                        <input type="text" class="form-control" id="key" name="key" required>
                    </div>
                    <div class="mb-3">
                        <label for="sourceString" class="form-label"><?php echo $uiTranslations['source_string']; ?></label>
                        <textarea class="form-control" id="sourceString" name="sourceString" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="translation" class="form-label"><?php echo $uiTranslations['translation']; ?></label>
                        <textarea class="form-control" id="translation" name="translation" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $uiTranslations['close']; ?></button>
                    <button type="submit" class="btn btn-primary" name="action" value="add"><?php echo $uiTranslations['add']; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
