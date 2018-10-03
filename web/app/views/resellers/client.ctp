
<div id="title">
    <h1><?php echo __('Dialer Management',true);?>&gt;&gt;<?php echo __('Client Management',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        
        <thead>
            <tr>
               <td><?php __('Login ID')?></td>
               <td><?php __('Password')?></td>
               <?php if ($login_type == 1): ?>
               <td><?php __('Reseller')?></td>
               <?php endif; ?>
               <td><?php __('Balance')?></td>
               <td><?php __('Email')?></td>
               <td><?php __('Action')?></td>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Login ID')?></td>
                <td><?php __('Password')?></td>
                <?php if ($login_type == 1): ?>
                <td><?php __('Reseller')?></td>
                <?php endif; ?>
                <td><?php __('Balance')?></td>
                <td><?php __('Email')?></td>
                <td><?php __('Last Login')?></td>
                <td><?php __('Action')?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['ResellerClient']['login_id']; ?></td>
                <td>******</td>
                <?php if ($login_type == 1): ?>
                <td><?php echo $resellers[$item['ResellerClient']['reseller_id']] ?></td>
                <?php endif; ?>
                <td><?php echo $item['Balance']['balance']; ?></td>
                <td><?php echo $item['ResellerClient']['email']; ?></td>
                <td><?php echo $item['User']['last_login_time']; ?></td>
                <td>
                    <?php if ($item['Client']['status']==1){?>
                    <a   onclick="return confirm('Are you sure to inactive the selected <?php echo $item['ResellerClient']['login_id'] ?>?')"   href="<?php echo $this->webroot?>resellers/disable/<?php echo $item['ResellerClient']['id'] ?>" > <img  title=" <?php echo 'Click to inactive';?>" src="<?php echo $this->webroot?>images/flag-1.png"> </a>
                    <?php }else{?>
                    <a  onclick="return confirm('Are you sure to active the selected <?php echo $item['ResellerClient']['login_id'] ?>?')" href="<?php echo  $this->webroot?>resellers/enable/<?php echo $item['ResellerClient']['id'] ?>"  > <img  title=" <?php echo 'Click to active';?>" src="<?php echo $this->webroot?>images/flag-0.png" static="0" > </a>
                    <?php }?>
                    
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['ResellerClient']['id']?>" >
                        <i class="icon-edit"></i>
                    </a>
                    <a href="<?php echo $this->webroot?>homes/auth_user?client_id=<?php echo $item['Client']['client_id'] ?>&lang=eng"> 
                        <img width="16" height="16" src="<?php echo $this->webroot?>images/bLogins.gif"> 
                    </a>
                    <a title="Delete" class="delete" href='<?php echo $this->webroot ;?>resellers/delete/<?php echo $item['ResellerClient']['id']?>'>
                        <i class='icon-remove'></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
    
<script>
    jQuery(function() {
        jQuery('#add').click(function(){
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax:"<?php echo $this->webroot ?>resellers/client_action_edit_panel",
                action:"<?php echo $this->webroot ?>resellers/client_action_edit_panel",
                removeCallback:function(){
                    if(jQuery('table.list tr').size()==1){
                        jQuery('table.list').hide();
                    }
                }
            });
            jQuery(this).parent().parent().show();
        });
        
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>resellers/client_action_edit_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>resellers/client_action_edit_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>
    