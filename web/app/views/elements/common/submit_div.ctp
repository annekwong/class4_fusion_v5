<?php $have_div = isset($div) ? $div : true; ?>
<?php if ($have_div): ?>
    <div class="form-buttons center separator">
<?php endif; ?>
    <input class="btn btn-primary input in-submit" <?php if(isset($submit_id)): ?>id="<?php echo $submit_id; ?>"<?php endif; ?> type="submit"  value="<?php echo 'Submit'; ?>">
    <input type="reset" value="<?php echo 'Reset'; ?>" class="btn btn-inverse" <?php if(isset($reset_id)): ?>id="<?php echo $reset_id; ?>"<?php endif; ?>>
<?php if ($have_div): ?>
    </div>
<?php endif; ?>