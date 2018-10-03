<div id="title">
    <h1> <?php echo __('Finance',true);?>&gt;&gt;<?php echo __('Mutual Transaction',true);?> </h1>
</div>

<div class="container">
    <?php
         $type_total = array(0,0,0,0,0,0,0,0,0,0,0,0);
         $data =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <th><?php echo __('Begin Date',true);?></th>
                <td><?php echo $startdate; ?></td>
                <td></td>
                <th></td>
            </tr>
            <tr>
                <td><?php echo __('Date',true);?></td>
                <td><?php echo __('Type',true);?></td>
                <td><?php echo __('Carrier',true);?></td>
                <td><?php echo __('Amount',true);?></td>
            </tr>
        </thead>

        <tbody>
        <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo date("Y-m-d H:i:sO", strtotime($item[0]['a'])); ?></td>
                <td><?php echo $all_type[$item[0]['b']]; ?></td>
                <td><?php echo $item[0]['c']; ?></td>
                <td><lable title="<?php echo $item[0]['e'];?>"><?php echo $item[0]['d'];$type_total[$item[0]['b']] += $item[0]['d']; ?></lable></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
   <table class="list">
        <tr>
            <td><?php __('payment received total')?>:</td>
            <td><?php echo $type_total[1]; ?></td>
            <td><?php __('payment sent total')?>:</td>
            <td><?php echo $type_total[2]; ?></td>
            <td><?php __('invoice received')?>:</td>
            <td><?php echo $type_total[3]; ?></td>
            <td><?php __('invoice sent')?>:</td>
            <td><?php echo $type_total[4]; ?></td>
            <td><?php __('credit note received')?>:</td>
            <td><?php echo $type_total[5]; ?></td>
            <td><?php __('credit note sent')?>:</td>
            <td><?php echo $type_total[6]; ?></td>
        </tr>
        <tr>
            <td><?php __('debit note received')?>:</td>
            <td><?php echo $type_total[7]; ?></td>
            <td><?php __('debit note sent')?>:</td>
            <td><?php echo $type_total[8]; ?></td>
            <td><?php __('reset')?>:</td>
            <td><?php echo $type_total[9]; ?></td>
            <td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>
    <br />
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
          <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php echo __('Search',true);?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get">
            Period:
            <input type="text" name="start" style="width:120px;" onclick="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo $startdate; ?>" />
            ~
            <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo $enddate; ?>" />
            <?php echo __('carrier',true);?>:
            <select id="client" name="client_id">
                <option value="0"><?php __('All')?></option>
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo __('type',true);?>:
            <select id="type" name="type">
                <option value="0"><?php __('All')?></option>
                <option value="1"><?php __('payment received')?></option>
                <option value="2"><?php __('payment sent')?></option>
                <option value="3"><?php __('invoice received')?></option>
                <option value="4"><?php __('invoice sent')?></option>
                <option value="5"><?php __('credit note received')?></option>
                <option value="6"><?php __('credit note sent')?></option>
                <option value="7"><?php __('debit note received')?></option>
                <option value="8"><?php __('debit note sent')?></option>
                <option value="9"><?php __('reset')?></option>
            </select>
            <input type="submit" value="<?php echo __('submit',true);?>" />
        </form>
        </div>
   </fieldset>
</div>

<script type="text/javascript">
$(function() {
    <?php
        if(isset($_GET['client_id']))
            echo "$('#client option[value={$_GET['client_id']}]').attr('selected', true);\n";
        if(isset($_GET['type']))
            echo "$('#type option[value={$_GET['type']}]').attr('selected', true);\n";
    ?>
});
</script>