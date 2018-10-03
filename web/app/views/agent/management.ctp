<style>
    #search_by + span{
        width: 160px; margin: 0 10px 0px 3px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/management">
            <?php __('Agent') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/management">
            <?php echo $this->pageTitle; ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>agent/save_agent"><i></i><?php __('Create New')?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("agent/tab",array('active' => 'list')); ?>
        </div>
        <div class="widget-body">
            <?php if (count($data)): ?>
                <div class="filter-bar">
                    <form action="" method="get">
                        <div style="padding: 3px 0;">
                            <label><?php __('Agent Name') ?>:</label>
                            <select name="id" id="search_by" style="width: 160px;">
                                <option value="0"></option>
                                <?php foreach ($data as $item): ?>
                                    <option value="<?php echo $item['Agent']['agent_id'];?>">
                                        <?php echo $item['Agent']['agent_name'];?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="padding: 3px 0;">
                            <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                        </div>
                    </form>
                </div>
            <?php endif;?>
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('agent_name', __('Agent Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('email', __('Agent Email', true)) ?></th>
                        <th><?php echo $appCommon->show_order('commission', __('Commission', true)) ?></th>
                        <th><?php echo $appCommon->show_order('method_type', __('Method', true)) ?></th>
                        <th><?php echo $appCommon->show_order('frequency_type', __('Frequency', true)) ?></th>
                        <th><?php __('Referral Key') ?></th>
                        <th><?php __('Last Updated') ?></th>
                        <th><?php __('Update By'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['Agent']['agent_name']; ?></td>
                            <td><?php echo $item['Agent']['email']; ?></td>
                            <td><?php echo $item['Agent']['commission']; ?>%</td>
                            <td><?php echo $method_type[$item['Agent']['method_type']]; ?></td>
                            <td><?php echo $frequency_type[$item['Agent']['frequency_type']]; ?></td>
                            <td>
                                <a href="<?php echo $this->webroot . 'signup/index/' . base64_encode($item['Agent']['agent_id']);?>">
                                    <?php echo base64_encode($item['Agent']['agent_id']); ?>
                                </a>
                            </td>
                            <td><?php echo $item['Agent']['update_on']; ?></td>
                            <td><?php echo $item['Agent']['update_by']; ?></td>
                            <td>
                                <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>agent/save_agent/<?php echo base64_encode($item['Agent']['agent_id']) ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <?php if ($item['Agent']['status']): ?>
                                    <a title="<?php __('inactive')?>" onclick="myconfirm('<?php __('Are you sure to Inactive?') ?>', this);return false;" href="<?php echo $this->webroot ?>agent/dis_able/<?php echo base64_encode($item['Agent']['agent_id']) ?>"><i class="icon-check"></i></a>
                                <?php else: ?>
                                    <a title="<?php __('active')?>" onclick="myconfirm('<?php __('Are you sure to activate?') ?>', this);return false;" href="<?php echo $this->webroot ?>agent/active/<?php echo base64_encode($item['Agent']['agent_id']) ?>"><i class="icon-check-empty"></i></a>
                                <?php endif; ?>
                                <a title="<?php __('Manage Clients') ?>" href="<?php echo $this->webroot; ?>agent/agent_client/<?php echo base64_encode($item['Agent']['agent_id']) ?>" >
                                    <i class="icon-list-alt"></i>
                                </a>
                                <a href="javascript:void(0)" data-id = "<?php echo $item['Agent']['agent_id'] ?>" class="assign_product" title="<?php __('Assign Product')?>">
                                    <i class="icon-plus-sign"></i>
                                </a>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                                   class="delete" href='<?php echo $this->webroot ?>agent/delete_agent/<?php echo base64_encode($item['Agent']['agent_id']) ?>'>
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="myModal_AssignProduct" class="modal hide" style="width:444px;margin-left:-222px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Assign Product'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="button" id="assign_product_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<script type="text/javascript">
    $(function() {
        $(".assign_product").on('click', function(){
            let modal = $('#myModal_AssignProduct'),
                agent_id = $(this).attr('data-id');
            if (agent_id) {

                $.ajax({
                    'url': '<?php echo $this->webroot; ?>agent/get_product_list/'+agent_id,
                    'type': 'GET',
                    'dataType': 'json',
                    'success': function(response) {
                        if(response.status){
                            modal.find('.modal-body').empty();
                            modal.find('.modal-body').html(response.data);
                            modal.modal('show');
                        }
                    }
                });
            }

        });

        let modal = $('#myModal_AssignProduct');
        $(".assign_product").on('click', function(){
            agent_id = $(this).attr('data-id');
            if (agent_id) {

                $.ajax({
                    'url': '<?php echo $this->webroot; ?>agent/get_product_list/'+agent_id,
                    'type': 'GET',
                    'dataType': 'json',
                    'success': function(response) {
                        if(response.status){
                            modal.find('.modal-body').empty();
                            modal.find('.modal-body').html(response.data);
                            modal.modal('show');
                        }
                    }
                });
            }

        });

        $( '#assign_product_submit' ).on('click', function(){
            let data = $( 'form#assign_product' ).serialize();
            modal.modal('hide');
            $.ajax({
                'url': '<?php echo $this->webroot; ?>agent/assign_product',
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'success': function(response) {

                    if(response.status){
                        jGrowl_to_notyfy(response.msg,{theme:'jmsg-success'});
                    } else{
                        jGrowl_to_notyfy('<?php __('Assigning failed!'); ?>',{theme:'jmsg-error'});
                    }
                }
            });
        });

        $('#search_by').select2();
        var search_by = '<?php echo isset($_GET['id']) ? $_GET['id'] : ""?>';
        $('#search_by').val(search_by).select2();
    });
</script>
