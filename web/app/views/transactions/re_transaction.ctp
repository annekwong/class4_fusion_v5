  <div id="title">
    <h1> <?php __('Finance');?>&gt;&gt;<?php __('Re-Transaction');?> </h1>
</div>

<div class="container">
    <div style="text-align:center">
    <form name="myform" method="post">

            <?php __('Carrier');?>:
            <select name="client">
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id']; ?>"><?php echo $client[0]['name']; ?></option>
                <?php endforeach; ?>
            </select>
            
            <?php __('From');?>: <input type="text" name="from" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />

            <?php __('To');?>:<input type="text" name="to" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />

            <input type="submit" value="<?php __('Submit')?>" />
    </form>
</div>
</div>