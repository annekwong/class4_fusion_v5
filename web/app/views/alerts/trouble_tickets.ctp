<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Trouble Tickets',true);?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Trouble Tickets',true);?></h4>
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
                            <i></i><?php __('Rule'); ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js tag" href="<?php echo $this->webroot; ?>alerts/action">
                            <i></i><?php __('Action'); ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js projector" href="<?php echo $this->webroot; ?>alerts/condition">
                            <i></i><?php __('Condition'); ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_ani">
                            <i></i><?php __('Block'); ?>			
                        </a>
                    </li>
                    <li class="active">
                        <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                            <i></i><?php __('Trouble Tickets'); ?>			
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js cargo" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                            <i></i><?php __('Trouble Tickets Mail Template'); ?>			
                        </a>
                    </li>
                </ul> 
        </div>
        <div class="widget-body">
            
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <select class="input in-select select input-medium" name="search_type">
                            <option selected="selected" value="0" <?php if (isset($_GET['search_type']) && $_GET['search_type'] == 0) echo 'selected="selected"'; ?>><?php __('Destination')?></option>
                            <option value="1" <?php if (isset($_GET['search_type']) && $_GET['search_type'] == 1) echo 'selected="selected"'; ?>><?php __('Rule Name')?></option>
                        </select>	
                    </div>
                    <!-- Filter -->
                    <div>
                        <input type="text" name="search" value="Search" title="Search" class="in-search default-value input in-text defaultText in-input input-small" id="search-_q">
                    </div>
                    <div>
                        <label><?php __('Ingress Trunk'); ?>:</label>
                        <select class="input-medium" name="ingress_trunk">
                               <option value="0"><?php __('All'); ?></option>
                               <?php foreach($ingresses as $key => $ingress): ?>
                               <option value="<?php echo $key; ?>" <?php if(isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] == $key) echo 'selected="selected"'; ?>><?php echo $ingress; ?></option>
                               <?php endforeach; ?>
                           </select>
                    </div>
                    <div>
                        <label><?php __('Egress Trunk'); ?></label>
                        <select class="input-medium" name="egress_trunk">
                              <option value="0"><?php __('All'); ?></option>
                              <?php foreach($egresses as $key => $egress): ?>
                              <option value="<?php echo $key; ?>" <?php if(isset($_GET['egress_trunk']) && $_GET['egress_trunk'] == $key) echo 'selected="selected"'; ?>><?php echo $egress; ?></option>
                              <?php endforeach; ?>
                          </select>
                    </div>
                    <div>
                        <label><?php __('Code'); ?></label>
                        <input type="text" class="input-small" name="code" value="<?php echo isset($_GET['code']) ? $_GET['code'] : ''; ?>" />
                    </div>
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query'); ?></button>
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
                <th><input type="checkbox" id="select_all" /></th>
                <th><?php __('Ingress Trunk'); ?></th>
                <th><?php __('Egress Trunk'); ?></th>
                <th><?php __('Blocked Time'); ?></th>
                <th><?php __('Unblocked Time'); ?></th>
                <th><?php __('Destination'); ?></th>
                <th><?php __('Rule Name'); ?></th>
                <th colspan="2"><?php __('Sample Time'); ?></th>
                <th colspan="2"><?php __('Call Count'); ?></th>
                <th><?php __('Action'); ?></th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php __('Start'); ?></th>
                <th><?php __('End'); ?></th>
                <th><?php __('Call Attempt'); ?></th>
                <th><?php __('Succ. Calls'); ?></th>
                <th>&nbsp;</th>
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
                <th><input type="checkbox" id="select_all" /></th>
                <th><?php __('Ingress Trunk'); ?></th>
                <th><?php __('Egress Trunk'); ?></th>
                <th><?php __('Blocked Time'); ?></th>
                <th><?php __('Unblocked Time'); ?></th>
                <th><?php __('Destination'); ?></th>
                <th><?php __('Rule Name'); ?></th>
                <th colspan="2"><?php __('Sample Time'); ?></th>
                <th colspan="2"><?php __('Call Count'); ?></th>
                <th><?php __('Action'); ?></th>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php __('Start'); ?></th>
                <th><?php __('End'); ?></th>
                <th><?php __('Call Attempt'); ?></th>
                <th><?php __('Succ. Calls'); ?></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><input type="checkbox" class="select_option" value="<?php echo $item['BlockTicket']['code_name']?>" /></td>
                <td><?php echo $item['BlockTicket']['ingress'] ? $resources[$item['BlockTicket']['ingress']] : ''; ?></td>
                <td><?php echo $item['BlockTicket']['egress']? $resources[$item['BlockTicket']['egress']] : ''; ?></td>
                <td><?php echo $item['BlockTicket']['blocked_time']; ?></td>
                <td><?php echo $item['BlockTicket']['unblock_time']; ?></td>
                <td><?php echo $item['BlockTicket']['code_name']; ?></td>
                <td><?php echo $item['BlockTicket']['rule_name']; ?></td>
                <td><?php echo $item['BlockTicket']['start_time']; ?></td>
                <td><?php echo $item['BlockTicket']['end_time']; ?></td>
                <td><?php echo $item['BlockTicket']['calls']; ?></td>
                <td><?php echo $item['BlockTicket']['not_zero_calls']; ?></td>
                <td>
                    <?php if($item['BlockTicket']['block']): ?>
                    <a title="Unblock" class="edit_item" href="<?php echo $this->webroot ?>alerts/trouble_block_ani_change/<?php echo $item['BlockTicket']['code_name']?>/1">
                        <img src="<?php echo $this->webroot?>images/flag-1.png"/>
                    </a>
                    <?php else: ?>
                    <a title="Block" class="edit_item" href="<?php echo $this->webroot ?>alerts/trouble_block_ani_change/<?php echo $item['BlockTicket']['code_name']?>/2">
                        <img src="<?php echo $this->webroot?>images/flag-0.png"/>
                    </a>
                    <?php endif; ?>
                    <a title="Delete"  href="<?php echo $this->webroot ?>alerts/trouble_tickets_delete/<?php echo $item['BlockTicket']['code_name']?>">
                         <i class='icon-remove'></i>
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
                
                $.ajax({
                    'url' : '<?php echo $this->webroot; ?>alerts/trouble_block_unblock_selected/' + $(this).attr('block_type'),
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : ids},
                    'success' : function(data) {
                        jGrowl_to_notyfy('You options were modified sucessfully',{theme:'jmsg-success'});
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
                    'url' : '<?php echo $this->webroot; ?>alerts/trouble_tickets_delete_selected',
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : ids},
                    'success' : function(data) {
                        jGrowl_to_notyfy('You options were deleted succesfully',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }
                });
            }
        });
    });
</script>
    