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
                <td><lable><?php echo $item[0]['d'];$type_total[$item[0]['b']] += $item[0]['d']; ?></lable></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
   <table class="list">
        <tr>
            <td>payment received total:</td>
            <td><?php echo $type_total[1]; ?></td>
            <td>payment sent total:</td>
            <td><?php echo $type_total[2]; ?></td>
            <td>invoice received:</td>
            <td><?php echo $type_total[3]; ?></td>
            <td>invoice sent:</td>
            <td><?php echo $type_total[4]; ?></td>
            <td>credit note received:</td>
            <td><?php echo $type_total[5]; ?></td>
            <td>credit note sent:</td>
            <td><?php echo $type_total[6]; ?></td>
        </tr>
        <tr>
            <td>debit note received:</td>
            <td><?php echo $type_total[7]; ?></td>
            <td>debit note sent:</td>
            <td><?php echo $type_total[8]; ?></td>
            <td>reset:</td>
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
            <?php echo __('type',true);?>:
            <select id="type" name="type">
                <option value="0">All</option>
                <option value="1">payment received</option>
                <option value="2">payment sent</option>
                <option value="3">invoice received</option>
                <option value="4">invoice sent</option>
                <option value="5">credit note received</option>
                <option value="6">credit note sent</option>
                <option value="7">debit note received</option>
                <option value="8">debit note sent</option>
                <option value="9">reset</option>
            </select>
            <input type="submit" value="<?php echo __('submit',true);?>" />
        </form>
        </div>
   </fieldset>
</div>
<script type="text/javascript">
$(function() {
    <?php
        if(isset($_GET['type']))
            echo "$('#type option[value={$_GET['type']}]').attr('selected', true);\n";
    ?>
});
</script>