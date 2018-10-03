<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Block',true);?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Block',true);?></h4>
    <div class="buttons pull-right">
        
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                    <li >
                        <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rule">
                            <i></i><?php __('Rule') ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js tag" href="<?php echo $this->webroot; ?>alerts/action">
                            <i></i><?php __('Action') ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js projector" href="<?php echo $this->webroot; ?>alerts/condition">
                            <i></i><?php __('Condition') ?>			
                        </a>
                    </li>
                    <li class="active">
                        <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_ani">
                            <i></i><?php __('Block') ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                            <i></i><?php __('Trouble Tickets') ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js cargo" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                            <i></i><?php __('Trouble Tickets Mail Template') ?>			
                        </a>
                    </li>
                </ul> 
        </div>
        <div class="widget-body">
            
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" name="search" value="Search" title="<?php __('Search')?>" class="in-search default-value input in-text defaultText in-input" id="search-_q">
                    </div>
                    <div>
                        <label><?php __('Ingress Trunk') ?>:</label>
                        <select name="ingress_trunk">
                            <option value="0"><?php __('All') ?></option>
                            <?php foreach($ingresses as $key => $ingress): ?>
                            <option value="<?php echo $key; ?>" <?php if(isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] == $key) echo 'selected="selected"'; ?>><?php echo $ingress; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label><?php __('Egress Trunk') ?></label>
                        <select name="egress_trunk">
                            <option value="0"><?php __('All') ?></option>
                            <?php foreach($egresses as $key => $egress): ?>
                            <option value="<?php echo $key; ?>" <?php if(isset($_GET['egress_trunk']) && $_GET['egress_trunk'] == $key) echo 'selected="selected"'; ?>><?php echo $egress; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            

    <?php
        if(empty($this->data)): 
    ?>
            <div class="separator bottom row-fluid">
    <div class="pagination pagination-large pagination-right margin-none">
    </div> 
</div>
    <h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
        
        <thead>
            <tr>
                <th class="footable-first-column expand" data-class="expand"><input type="checkbox" /></th>
                <th><?php __('ANI') ?></th>
                <th><?php __('Ingress Trunk') ?></th>
                <th><?php __('Egress Trunk') ?></th>
                <th><?php __('ASR(%)') ?></th>
                <th><?php __('ACD(min)') ?></th>
                <th><?php __('Blocked Time') ?></th>
                <th><?php __('Unlocked Time') ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('Rule Name') ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('Email') ?></th>
                <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php __('Action') ?></th>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <div class="separator bottom row-fluid">
    <div class="pagination pagination-large pagination-right margin-none">
        <?php echo $this->element('xpage'); ?>
    </div> 
</div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th class="footable-first-column expand" data-class="expand"><input type="checkbox" id="select_all" /></th>
                <th><?php __('ANI') ?></th>
                <th><?php __('Ingress Trunk') ?></th>
                <th><?php __('Egress Trunk') ?></th>
                <th><?php __('ASR(%)') ?></th>
                <th><?php __('ACD(min)') ?></th>
                <th><?php __('Blocked Time') ?></th>
                <th><?php __('Unlocked Time') ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('Rule Name') ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('Email') ?></th>
                <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php __('Action') ?></th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td class="footable-first-column expand" data-class="expand"><input type="checkbox" class="select_option" value="<?php echo $item['BlockAni']['id']?>" /></td>
                <td><?php echo $item['BlockAni']['ani']; ?></td>
                <td><?php echo $item['BlockAni']['ingress'] ? $resources[$item['BlockAni']['ingress']] : ''; ?></td>
                <td><?php echo $item['BlockAni']['egress'] ? $resources[$item['BlockAni']['egress']] : ''; ?></td>
                <td><?php echo round($item['BlockAni']['asr'] * 100, 2); ?></td>
                <td><?php echo $item['BlockAni']['acd']; ?></td>
                <td><?php echo $item['BlockAni']['blocked_time']; ?></td>
                <td><?php echo $item['BlockAni']['unblock_time']; ?></td>
                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $item['AlertRule']['name']; ?></td>
                <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $item['BlockAni']['email'] ? 'Yes' : 'No'; ?></td>
                <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                    <!--
                    <a title="Exclude ANI" class="exclude_ani" href="<?php echo $this->webroot ?>alerts/put_into_exclude_anis/<?php echo $item['BlockAni']['id']?>" >
                        <img src="<?php echo $this->webroot?>images/unlock.png"/>
                    </a>
                    -->
                    <?php if($item['BlockAni']['block']): ?>
                    <a title="Unblock" class="edit_item" href="<?php echo $this->webroot ?>alerts/block_ani_change/<?php echo $item['BlockAni']['id']?>/1">
                        <i class="icon-check"></i>
                    </a>
                    <?php else: ?>
                    <a title="Block" class="edit_item" href="<?php echo $this->webroot ?>alerts/block_ani_change/<?php echo $item['BlockAni']['id']?>/2">
                        <i class="icon-check-empty"></i>
                    </a>
                    <?php endif; ?>
                    <a title="Delete"  href="<?php echo $this->webroot ?>alerts/block_ani_delete/<?php echo $item['BlockAni']['id']?>">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
    <?php endif; ?>
</div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#select_all').change(function() {
            $('.select_option').attr('checked', $(this).attr('checked'));
        }).trigger('change');
        
        $('.block_unblock_selected').click(function() {
            if (confirm('Are you sure do this?'))
            {
                var $selected_checked = $('.select_option:checked');
                var ids = new Array();
                $selected_checked.each(function(index, item) {
                    ids.push($(this).val());
                });
                
                if (ids.length == 0)
                {
                    jGrowl_to_notyfy('Please select at least one!',{theme:'jmsg-error'});    
                    return;
                }
                
                var block_type = $(this).attr('block_type');
                
                $.ajax({
                    'url' : '<?php echo $this->webroot; ?>alerts/block_unblock_selected/' + block_type,
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : ids},
                    'success' : function(data) {
                        if (block_type == 1)
                        {
                            jGrowl_to_notyfy("<?php __('Your options are unblocked successfully'); ?>",{theme:'jmsg-success'});
                        }
                        else
                        {
                            jGrowl_to_notyfy("<?php __('Your options are blocked successfully'); ?>",{theme:'jmsg-success'});
                        }
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }
                });
            }
        });
        
        $('.delete_selected').click(function() {
            if (confirm('Are you sure do this?'))
            {
                var $selected_checked = $('.select_option:checked');
                var ids = new Array();
                $selected_checked.each(function(index, item) {
                    ids.push($(this).val());
                });
                
                if (ids.length == 0)
                {
                    jGrowl_to_notyfy('Please select at least one!',{theme:'jmsg-error'});    
                    return;
                }
                
                $.ajax({
                    'url' : '<?php echo $this->webroot; ?>alerts/block_ani_delete_selected',
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : ids},
                    'success' : function(data) {
                        jGrowl_to_notyfy("<?php __('Your options are deleted successfully'); ?>",{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }
                });
            }
        });
    });
</script>
    
    
