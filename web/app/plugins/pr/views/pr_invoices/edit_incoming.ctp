<div id="title">
    <h1>
        <?php  __('Finance')?>
        &gt;&gt;
        <?php __('Invoices')?>
    </h1>
    <ul id="title-menu">
        <li>
            <a class="link_back" href="javascript:history.go(-1)">
                <img width="16" height="16" alt="Back" src="<?php echo $this->webroot?>images/icon_back_white.png">
                &nbsp;<?php echo __('goback',true);?>
            </a>
        </li>
    </ul>
</div>
<div id="container">
    <form name="myform" method="post" action="<?php echo $this->webroot; ?>pr/pr_invoices/edit_incoming/<?php echo $this->params['pass'][0] ?>">
    <table style="width:500px;margin:0 auto;">
        <tr>
            <td><?php echo __('Invoice No',true);?>.</td>
            <td><input type="text" name="invoice_number" value="<?php echo $data[0][0]['invoice_number'] ?>" /></td>
        </tr>
        <tr>
            <td><?php echo __('carrier',true);?></td>
            <td>
                <select name="client_id" style="width:165px;">
                <?php foreach($clients as $client): ?>
                    <option value="<?php echo $client[0]['client_id'] ?>" <?php if($data[0][0]['client_id'] == $client[0]['client_id']) echo 'selected' ?> ><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo __('Starting Period',true);?></td>
            <td><input type="text" value="<?php echo strstr($data[0][0]['invoice_start'], '+', TRUE); ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="start" /></td>
        </tr>
        <tr>
            <td><?php echo __('Ending Period',true);?></td>
            <td><input type="text" value="<?php echo strstr($data[0][0]['invoice_end'], '+', TRUE); ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="end" /></td>
        </tr>
        <tr>
            <td><?php echo __('GMT',true);?></td>
            <td>
                <select name="gmt" style="width:165px;">
                <option value="-1200">GMT -12:00</option>
                <option value="-1100">GMT -11:00</option>
                <option value="-1000">GMT -10:00</option>
                <option value="-0900">GMT -09:00</option>
                <option value="-0800">GMT -08:00</option>
                <option value="-0700">GMT -07:00</option>
                <option value="-0600">GMT -06:00</option>
                <option value="-0500">GMT -05:00</option>
                <option value="-0400">GMT -04:00</option>
                <option value="-0300">GMT -03:00</option>
                <option value="-0200">GMT -02:00</option>
                <option value="-0100">GMT -01:00</option>
                <option selected="selected" value="+0000">GMT +00:00</option>
                <option value="+0100">GMT +01:00</option>
                <option value="+0200">GMT +02:00</option>
                <option value="+0300">GMT +03:00</option>
                <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo __('Invoice Amount',true);?></td>
            <td><input type="text" name="invoice_amount" value="<?php echo $data[0][0]['total_amount']; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo __('Paid Amount',true);?></td>
            <td><input type="text" name="paid_amount" value="<?php echo $data[0][0]['pay_amount']; ?>" /></td>
        </tr>
        <tr>
            <td><?php echo __('Due Date',true);?></td>
            <td><input type="text" value="<?php echo $data[0][0]['due_date']; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" name="due_date" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="<?php echo __('submit',true);?>" /></td>
        </tr>
    </table>
    </form>
</div>