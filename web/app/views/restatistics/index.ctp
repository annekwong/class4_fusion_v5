  <div id="title">
    <h1> <?php __('Tools')?>&gt;&gt;<?php __('Re-Statistics')?> </h1>
</div>

<div class="container">
    <div style="text-align:center">
    <form name="myform" method="post">
            
            <?php __('From')?>: <input type="text" name="from" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />

            <?php __('To')?>:<input type="text" name="to" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />

            <input type="submit" value="<?php __('Submit')?>" />
    </form>
</div>
</div>