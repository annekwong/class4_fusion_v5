<?php echo $this->element("selectheader")?>

<div id="title"><h1><?php echo __('cdr',true);?></h1></div>

<div class="container">
    <div id="toppage"></div>
    <?php
        $mydata = $p->getDataArray();
        if(empty($mydata)) :
            echo '<div class="msg">'.__('no_data_found',true).'</div>';            
        else:
    ?>
    <table class="list">
        <?php foreach($mydata as $data): ?>
        <tr>
            <td><?php echo $data['origination_destination_number']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php
        endif;
    ?>
    <div id="tmppage"> <?php echo $this->element('page');?>
</div>

<?php echo $this->element("selectfooter")?>
