<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Upload ANI Log',true);?></h1>
</div>

<div id="container">
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" id="mylist" style="display:none;">
        
        <thead>
            <tr>
                <td><?php __('Upload Time'); ?></td>
                <td><?php __('Upload By'); ?></td>
                <td><?php __('Action'); ?></td>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list" id="mylist">
        <thead>
            <tr>
                <td><?php __('Upload Time'); ?></td>
                <td><?php __('Upload By'); ?></td>
                <td><?php __('Action'); ?></td>
            </tr>
        </thead>
            <tbody>
                <?php foreach ($this->data as $item): ?> 
                <tr>
                    <td><?php echo $item['UploadAniLog']['upload_time'] ?></td>
                    <td><?php echo $item['UploadAniLog']['upload_by'] ?></td>
                    <td>
                        <a href="<?php echo $this->webroot ?>upload_ani/down_ani_file/<?php echo $item['UploadAniLog']['id'] ?>">
                            <img src="<?php echo $this->webroot ?>images/export.png">
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
    
    