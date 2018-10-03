
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Trouble Tickets',true);?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Mail Templates',true);?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Mail Templates',true);?></h4>
    <div class="buttons pull-right">
        <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template_create" title="create action" id="add" class="link_btn btn btn-primary btn-icon glyphicons circle_plus">
            <i></i><?php __('Create New'); ?>            
        </a>
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
                    <li>
                        <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                            <i></i><?php __('Trouble Tickets'); ?>			
                        </a>
                    </li>
                    <li class="active">
                        <a class="glyphicons no-js cargo" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                            <i></i><?php __('Trouble Tickets Mail Template'); ?>			
                        </a>
                    </li>
                </ul> 
        </div>
        <div class="widget-body">
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
                <th><?php __('Name'); ?></th>
                <th><?php __('Title'); ?></th>
                <th><?php __('Created At'); ?></th>
                <th><?php __('Updated At'); ?></th>
                <th><?php __('Created By'); ?></th>
                <th><?php __('Action'); ?></th>
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
                <th><?php __('Name'); ?></th>
                <th><?php __('Created At'); ?></th>
                <th><?php __('Updated At'); ?></th>
                <th><?php __('Created By'); ?></th>
                <th><?php __('Action'); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['TroubleTicketsTemplate']['name']; ?></td>
                <td><?php echo $item['TroubleTicketsTemplate']['created_at']; ?></td>
                <td><?php echo $item['TroubleTicketsTemplate']['updated_at']; ?></td>
                <td><?php echo $item['TroubleTicketsTemplate']['updated_by']; ?></td>
                <td>
                    <a title="Edit"  href="<?php echo $this->webroot ?>alerts/trouble_tickets_template_edit/<?php echo $item['TroubleTicketsTemplate']['id']; ?>">
                        <i class="icon-edit"></i>
                    </a>

                    <a title="Delete" onclick="return myconfirm('Are your sure do this?', this)" class="delete" href='<?php echo $this->webroot ?>alerts/trouble_tickets_template_delete/<?php echo base64_encode($item['TroubleTicketsTemplate']['id']); ?>'>
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