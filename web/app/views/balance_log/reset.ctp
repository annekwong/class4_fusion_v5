<form id="synchronize_form" method="post" action="<?php echo $this->webroot ?>balance_log/reset/<?php echo $this->params['pass'][0] ?>">
    <div class="separator bottom"></div>
    <div class="row-fluid">
        <div class="span3 center">
            <label>
            <?php __('Reset Date')?>
            </label>
        </div>
        <div class="span9">
        <input type="text" name="date" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" style="width:120px;">
        </div>
    </div>
</form>