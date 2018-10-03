<table class="assign_agent_client table table-striped table-bordered  table-white table-primary" >
    <thead></thead>
    <tr>
        <td><?php __('Agent Name'); ?></td>
        <td>
            <?php if ($agent_id): ?>
                <span class="assigned_agent"><?php echo $agents[$agent_id]; ?></span>
            <?php else:
                echo $form->input('agent',array('class' => 'assigned_agent','label' => false,'div' => false,
                    'type' => 'select','options' => $agents,'name' => 'agent'));
            endif;
            ?>
        </td>
    </tr>
    <tr>
        <td><?php __('Client Name'); ?></td>
        <td>
            <select name="client">
                <?php foreach ($client_data as $key => $item): ?>
                    <?php if(!$agent_id || strcmp($agent_id,$item['AgentClients']['agent_id'])): ?>
                        <option value="<?php echo $item['Clients']['client_id'] ?>"
                                agent="<?php echo $item['AgentClients']['agent_id'] ? $agents[$item['AgentClients']['agent_id']] : ''; ?>"
                            >
                            <?php echo $item['Clients']['name'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php __('Method Type'); ?></td>
        <td>
            <select name="method_type">
                <?php foreach ($method_type as $key => $item): ?>
                        <option value="<?php echo $key; ?>">
                            <?php echo $item ; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>

    <!--tr>
        <td><?php __('Client Agent'); ?></td>
        <td class="agent_name_td"></td>
    </tr-->
</table>
<!--script type="text/javascript">
    function show_agent()
    {
        var agent_name = $(".assign_agent_client").find("select[name='client']").find('option:selected').attr('agent');
        if (agent_name == ''){
            agent_name = '--';
        }
        $('.agent_name_td').html(agent_name);
    }
    $(function(){
        show_agent();
        $(".assign_agent_client").find("select[name='client']").change(function(){
            show_agent();
        });
    });
</script-->