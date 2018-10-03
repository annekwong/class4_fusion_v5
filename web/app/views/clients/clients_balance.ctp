<div id="title">
  <h1>
    <?php __('Management')?>
    &gt;&gt;
    <?php __('Balance')?>
  </h1>
</div>

<?php
    $balance = $begin_balance;
    $begin_balance_1 = number_format($balance, 3);
?>

<div id="container">
    <table class="list">
        <thead>
            <tr>
                <td colspan="5">
                    <?php __('Beginning Balance on')?> <?php echo $start_time ?> <?php __('is')?> <?php echo $begin_balance_1; ?>
                </td>
                <td colspan="5">
                    <?php __('Ending Balance on')?> <?php echo $end_time ?> <?php __('is')?> <span id="ending_balance"></span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4"><?php __('Buy')?></td>
                <td colspan="4"><?php __('Sell')?></td>
                <td></td>
            </tr>
            <tr>
                <td><?php __('Date')?></td>
                <td><?php __('Payment')?></td>
                <td><?php __('Credit')?></td>
                <td><?php __('Debit')?></td>
                <td><?php __('Orig. Usage')?></td>
                <td><?php __('Payment')?></td>
                <td><?php __('Credit')?></td>
                <td><?php __('Debit')?></td>
                <td><?php __('Term. Usage')?></td>
                <td><?php __('Balance')?></td>
            </tr>
        </thead>  
        
        <tbody>
            <?php $ending_balance = $begin_balance_1; ?>
            <?php foreach($data as $key => $item): ?>
            <tr>
                <td><?php echo $key; ?></td>
                <td><?php echo $temp1=round((isset($item[1]) ? $item[1] : 0), 3); ?></td>
                <td><?php echo $temp2=round((isset($item[2]) ? $item[2] : 0), 3); ?></td>
                <td><?php echo $temp3=round((isset($item[3]) ? $item[3] : 0), 3); ?></td>
                <td><?php echo $temp4=round((isset($item[4]) ? $item[4] : 0), 3); ?></td>
                <td><?php echo $temp5=round((isset($item[5]) ? $item[5] : 0), 3); ?></td>
                <td><?php echo $temp6=round((isset($item[6]) ? $item[6] : 0), 3); ?></td>
                <td><?php echo $temp7=round((isset($item[7]) ? $item[7] : 0), 3); ?></td>
                <td><?php echo $temp8=round((isset($item[8]) ? $item[8] : 0), 3); ?></td>
                <td>
                    <?php
                        echo $ending_balance = number_format($common->total_client_balance($temp1, $temp2, $temp3, $temp4, $temp5, $temp6, $temp7, $temp8, $balance), 3);
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <div class="search_title">
            <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php __('Search')?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form method="get" name="myform">
            <?php __('Period')?>:
            <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            <input type="submit" value="<?php __('Submit')?>" class="input in-submit">
        </form>
        </div>
   </fieldset>    
</div>

<script>
$(function() {
    $('#ending_balance').text('<?php echo $ending_balance; ?>');
});
</script>