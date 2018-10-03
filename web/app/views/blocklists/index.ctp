<?php echo $this->element("blocklists/jss")?>
<?php echo $this->element("blocklists/title")?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('blocklists/search')?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Ingress') ?>:</label>
                        <?php echo $xform->search('filter_ingress_res_id', array('options' => $appBlocklists->_get_select_options($IngressList, 'Resource', 'alias', 'alias'), 'empty' => __('All', true), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px')); ?>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Egress') ?>:</label>
                        <?php echo $xform->search('filter_egress_res_id', array('options' => $appBlocklists->_get_select_options($EgressList, 'Resource', 'alias', 'alias'), 'empty' => __('All', true), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px')); ?>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('ANI Prefix') ?>:</label>
                        <?php echo $xform->search('filter_ani_prefix', array('label' => false, 'div' => false, 'type' => 'text')); ?>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('DNIS Prefix') ?>:</label>
                       <?php echo $xform->search('filter_digit', array('label' => false, 'div' => false, 'type' => 'text')); ?>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Blocked By') ?>:</label>
                       <?php echo $xform->search('filter_action_type',  array('options' => $block_action_type, 'empty' => __('All', true), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px')); ?>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>

<?php echo $this->element('blocklists/list')?>
</div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('input.border_no[type="checkbox"]').each(function(){
            if(typeof($(this).attr('id')) == 'undefined'){
                $(this).parent().css('text-align','center');
                $(this).removeAttr('disabled');
            }
        });
    });
</script>