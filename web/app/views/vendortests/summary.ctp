<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_back" href="<?php echo $this->webroot ?>vendortests">
                <img width="16" height="16" src="/Class4/images/icon_back_white.png" alt="<?php echo __('goback',true);?>">
                &nbsp;<?php echo __('goback',true);?> 
            </a>
        </li>
    </ul>
</div>

<div class="container">
    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('start_time',true);?></td>
                <td><?php echo __('end_time',true);?></td>
                <td><?php echo __('code_name',true);?></td>
                <td><?php echo __('Call Count',true);?></td>
                <td> <?php echo __('FAS',true);?>%</td>
                <td><?php echo __('asr',true);?></td>
                <td><?php echo __('pdd',true);?></td>
                <td><?php echo __('action',true);?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item) {?>
            <tr>
                <td><?php echo intval($item[0]['start_epoch']) == 0 ? 0 : date('Y-m-d H:i:s',intval($item[0]['start_epoch'])); ?></td>
                <td><?php echo intval($item[0]['end_epoch']) == 0 ? 0 : date('Y-m-d H:i:s',intval($item[0]['end_epoch'])); ?></td>
                <td><?php echo $item[0]['code_name']; ?></td>
                <td><?php echo $item[0]['call_count']; ?></td>
                <td><?php echo $item[0]['fas']; ?></td>
                <td><?php echo $item[0]['asr']*100; ?></td>
                <td><?php echo $item[0]['pdd']; ?></td>
                <td><a href="<?php echo $this->webroot ?>vendortests/delete_code/<?php echo( $this->params['pass'][0]) ?>/<?php echo $item[0]['code_id']; ?>"><?php echo __('delete',true);?></a> 
                    <a href="<?php echo $this->webroot ?>vendortests/report/<?php echo $item[0]['code_id']; ?>">Report </a>
                    <a href="<?php echo $this->webroot ?>vendortests/detail/<?php echo $item[0]['code_id']; ?>?resultid=<?php echo array_keys_value($this->params,'pass.0'); ?>"><?php echo __('detail',true);?></a></td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>