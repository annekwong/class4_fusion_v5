<?php $pass0 = isset($this->params['pass'][0]) ? $this->params['pass'][0] :''; ?>
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
    <li><a href="<?php echo $this->webroot ?>agent/agent_client">
            <?php echo $this->pageTitle; ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?><?php if(!empty($currentAgent)):?>&nbsp;>>&nbsp;<?php echo $currentAgent['Agent']['agent_name']?> <?php endif;?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="#myModal_AssignAgentClient" data-toggle="modal">
        <i></i><?php __('Create New')?></a>
    <?php if(isset($this->params['pass'][0])): ?>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>agent/index"><i></i><?php __('Back')?></a>
    <?php endif; ?>
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <?php if(!isset($this->params['pass'][0])): ?>
            <div class="widget-head">
                <?php echo $this->element("agent/tab",array('active' => 'detail')); ?>
            </div>
        <?php endif; ?>
        <div class="widget-body">
            <?php if (count($clients)): ?>
                <div class="filter-bar">
                    <form action="" method="get">
                        <div style="padding: 3px 0;">
                            <label><?php __('Clietn Name') ?>:</label>
                            <select name="id" id="search_by" style="width: 160px;">
                                <option value="0"></option>
                                <?php foreach ($clients as $item): ?>
                                    <option value="<?php echo $item['Clients']['client_id'];?>">
                                        <?php echo $item['Clients']['name'];?>
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

            <?php $method_types = AgentClients::get_method_type() ;?>

            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>

                        <th><?php echo $appCommon->show_order('Client.name', __('Client Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('Agent.agent_name', __('Agent Name', true)) ?></th>
                        <!--                        <th>--><?php //echo $appCommon->show_order('commission', __('Commission', true)) ?><!--</th>-->
                        <th><?php __('Commission'); ?></th>
                        <th><?php __('Based On'); ?></th>
                        <th><?php __('Assigned On'); ?></th>
                        <th><?php __('Registered On'); ?></th>
                        <th><?php __('Status'); ?></th>
                        <th><?php __('Assigned By'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>

                            <td><?php echo $item['Client']['name']; ?></td>
                            <td><?php echo $item['Agent']['agent_name']; ?></td>
                            <td>
                                <?php echo $item['AgentClients']['commission'] ? $item['AgentClients']['commission'] : $item['Agent']['commission']; ?>%
                            </td>
                            <td>
                                <?php echo isset($item['AgentClients']['method_type']) ? $method_types[$item['AgentClients']['method_type']] : '' ?>
                            </td>
                            <td><?php echo $item['AgentClients']['update_on']; ?></td>
                            <td><?php echo $item['Signup']['signup_time']; ?></td>
                            <td><?php echo isset($item['Signup']['status'])?$signup_status[$item['Signup']['status']]:''; ?></td>
                            <td><?php echo $item['AgentClients']['update_by']; ?></td>
                            <td>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                                   class="delete" href='<?php echo $this->webroot ?>agent/delete_agent_client/<?php echo base64_encode($item['AgentClients']['id']).'/'.$pass0; ?>'>
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
<form action="<?php echo $this->webroot; ?>agent/assign_agent_client_handle"  method="post">
    <input type="hidden" name="agent" value="<?php echo isset($this->params['pass'][0]) ? base64_decode($this->params['pass'][0]): ''; ?>"/>
    <input type="hidden" name="agent_flg" value="<?php echo isset($this->params['pass'][0]) ? base64_decode($this->params['pass'][0]): ''; ?>"/>
    <div id="myModal_AssignAgentClient" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Assign Agent Client'); ?></h3>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<script type="text/javascript">
    $(function() {

        $('#search_by').select2();
        var search_by = '<?php echo isset($_GET['id']) ? $_GET['id'] : ""?>';
        $('#search_by').val(search_by).select2();

        $("#myModal_AssignAgentClient").on('shown',function(){
            $(this).find('.modal-body').load('<?php echo $this->webroot; ?>agent/assign_agent_client',
                {'agent_id':'<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] :''; ?>'});
        });

        $("#myModal_AssignAgentClient").find('.sub').click(function(){
            /* var agent_name = $("#myModal_AssignAgentClient").find('.agent_name_td').html();
             var $assigned_agent_html = $("#myModal_AssignAgentClient").find('.assigned_agent');
             if ($assigned_agent_html.is('select')){
                 var assigned_agent = $assigned_agent_html.find('option:selected').text();
             }else{
                 var assigned_agent = $assigned_agent_html.html();
             }
             var client_name = $("#myModal_AssignAgentClient").find("select[name='client']").find('option:selected').text();
             if (agent_name == assigned_agent){
                 $("#myModal_AssignAgentClient").find('.sub').next().click();
                 jGrowl_to_notyfy('<?php __('Save successfully'); ?>',{theme:'jmsg-success'});
                return false;
            }
            if (agent_name != '--'){
                var msg = '<?php __('[%s] assign from [%s] to [%s]'); ?>';
                msg = msg.replace('%s',client_name);
                msg = msg.replace('%s',agent_name);
                msg = msg.replace('%s',assigned_agent);
                $("#myModal_AssignAgentClient").find('.sub').next().click();
                bootbox.confirm(msg, function(result) {
                    if(result) {
                        $("#myModal_AssignAgentClient").closest('form').submit();
                    }else{
                        return false;
                    }
                });
            }
            else{
                $("#myModal_AssignAgentClient").closest('form').submit();
            }*/
            $("#myModal_AssignAgentClient").closest('form').submit();
        });
    });
</script>
