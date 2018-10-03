<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('DID Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('My DID Number') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('My DID Number') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
<!--    <a id="add" onclick="return judge_billing_rule(this);" class="btn btn-primary btn-icon glyphicons circle_plus" href="--><?php //echo $this->webroot ?><!--did/clients/add"><i></i>-->
<!--        --><?php //__('Create New') ?>
<!--    </a>-->
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="container">
                <?php
                if (empty($this->data)):
                    ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
                <?php else: ?>

                    <table class="footable table table-striped tableTools table-bordered  table-white table-primary table_page_num" id="key_list" >
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th><?php __('DID Number') ?></th>
                                <th><?php __('Country') ?></th>
                                <th><?php __('State') ?></th>
                                <th><?php __('Activation Date') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                        </thead>
                        <tbody id="my_did_tbody">
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><input type="checkbox" class="multi_select" value="<?php echo $item['ProductItems']['item_id']; ?>"></td>
                                <td><?php echo $item['ProductItems']['digits']; ?></td>
                                <td><?php echo $item['Rate']['country']; ?></td>
                                <td><?php echo $item['Rate']['code_name']; ?></td>
                                <td><?php echo $item['ProductItems']['update_at']; ?></td>
                                <td>
                                    <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>agent/save_agent/<?php echo base64_encode($item['ProductItems']['item_id']) ?>" >
                                        <i class="icon-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $("#selectAll").click(function(){
            var checked = $(this).is(':checked');
            $("#my_did_tbody").find('.multi_select').attr('checked',checked);
        });
    });
</script>