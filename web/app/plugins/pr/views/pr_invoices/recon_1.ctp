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
    <form action="<?php echo $this->webroot; ?>pr/pr_invoices/recon/<?php echo $this->params['pass'][0] ?>" method="post" enctype="multipart/form-data" name="myform">
        <select name="type">
            <option value="0"><?php __('Client')?></option>
            <option value="1"><?php __('Vendor')?></option>
        </select>
        <input type="file" name="upfile" />
        <input type="submit" value="<?php __('Submit')?>" style="height:25px;" />
    </form>
    <br />
    <?php
        if(!empty($data)):
    ?>
    <table class="list">
        <thead>
           <tr>
                <td><?php echo __('Code Name',true);?></td>
                <td><?php echo __('Dur',true);?></td>
                <td><?php echo __('Rate',true);?></td>
                <td><?php echo __('Cost',true);?></td>
                <td><?php echo __('Dur',true);?></td>
                <td><?php echo __('Rate',true);?></td>
                <td><?php echo __('Cost',true);?></td>
                <td><?php echo __('Dur',true);?></td>
                <td><?php echo __('Rate',true);?></td>
                <td><?php echo __('Cost',true);?></td>
           </tr>   
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item['code_name'] ?></td>
                <td><?php echo $item['dur1'] ?></td>
                <td><?php echo $item['rate1'] ?></td>
                <td><?php echo $item['cost1'] ?></td>
                <td><?php echo $item['dur2'] ?></td>
                <td><?php echo $item['rate2'] ?></td>
                <td><?php echo $item['cost2'] ?></td>
                <td><?php echo $item['dur3_1'] ?>(<?php echo $item['dur3'] ?>%)</td>
                <td><?php echo $item['rate3_1'] ?>(<?php echo $item['rate3'] ?>%)</td>
                <td><?php echo $item['cost3_1'] ?>(<?php echo $item['cost3'] ?>%)</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
        endif;
    ?>
</div>