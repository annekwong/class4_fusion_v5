
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
        <a class="link_back_new btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <i></i><?php __('Back')?> 
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


    
    <?php echo $this->element("alerts/_trouble_tickets_template_form")?>
</div>
        </div>
    </div>