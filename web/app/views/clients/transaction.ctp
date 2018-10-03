<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Transaction') ?></li>
</ul>
<div class="innerLR">
    <div class="widget-body">
         <?php
            $data = $p->getDataArray();
        ?>
        <div id="toppage"></div>
        <?php
            if(count($data) == 0){
        ?>
            <div class="msg"><?php echo __('no_data_found')?></div>
        <?php
            }else{
        ?>
        <table class="list">
            <thead>

                <tr>
                    <td><span><?php __('Date')?></span></td>
                    <td><span><?php __('Buy')?></span></td>
                    <td><span><?php __('Sell')?></span></td>
                    <td><span><?php __('Deposit')?></span></td>
                    <td><span><?php __('Withdraw')?></span></td>
                    <td><span><?php __('Balance')?></span></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $item): ?>
                <tr>
                    <td><?php echo $item[0]['date']?></td>
                    <td><?php echo number_format($item[0]['buy'],2)?></td>
                    <td><?php echo number_format($item[0]['sell'],2)?></td>
                    <td><?php echo number_format($item[0]['wire_in'],2)?></td>
                     <td><?php echo number_format($item[0]['wire_out'],2)?></td>
                     <td><?php echo number_format($item[0]['bod_balance'],2)?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="tmppage"> <?php echo $this->element('page');?> </div>
        <?php }?>
    </div>
</div>


