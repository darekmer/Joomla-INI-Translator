<!-- Modal do sprawdzania duplikatów -->
<div class="modal fade" id="checkDuplicatesModal" tabindex="-1" aria-labelledby="checkDuplicatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkDuplicatesModalLabel"><?php echo $uiTranslations['duplicate_translations']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo $uiTranslations['charging_in_progress']; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $uiTranslations['close']; ?></button>
                <button type="button" class="btn btn-danger" id="deleteDuplicatesBtn"><?php echo $uiTranslations['remove_duplicates']; ?></button>
            </div>
        </div>
    </div>
</div>
