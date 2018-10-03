<div class="dialog_form">
    <form method="post" id="synchronize_form" action="<?php echo $this->webroot ?>finances/synchronize/<?php echo $client_id ?>/<?php echo $balance_history_id ?>">
        <label>
            <?php __('The date of Actual Balance')?>:
        </label>
        <p>
          <input type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" style="width:120px;" name="reset_time" class="input in-text in-input">  
        </p>
    </form>
</div>