<form>
    <?php
        $timeProfileOptions = $appProduct->_get_select_options($TimeProfileList, 'TimeProfile', 'time_profile_id', 'name');
        $timeProfileOptions[0] = '';
        asort($timeProfileOptions);
    ?>
    <table>
        <tr class="cloned">
            <td></td>
            <td><input type="text" class="input in-text in-input validate[custom[onlyNumber]]" name="digits" value="<?php echo array_keys_value($this->data, 'StaticRoute.digits') ?>" maxlength="32" id="digits"/></td>
            <td><?php echo $xform->search('strategy', Array('onchange' => 'strategy(this)', 'class' => 'input in-select', 'options' => Array('1' => __('topdown', true), '0' => __('bypercentage', true), '2' => __('roundrobin', true)), 'value' => array_keys_value($this->data, 'StaticRoute.strategy'))) ?></td>
            <td><?php echo $xform->search('time_profile_id', Array('options' => $timeProfileOptions, 'value' => array_keys_value($this->data, 'StaticRoute.time_profile_id'))) ?></td>
            <td colspan=8></td>
            <td><?php echo array_keys_value($this->data, 'StaticRoute.update_at') ?></td>
            <td><a title="Save" href="###" id="save" ><i class="icon-save"></i> </a> <a title="Exit" href="###"  id="delete" ><i class="icon-remove"></i> </a></td>
        </tr>
    </table>
    <table class="table table-condensed">
        <tr class="cloned" style="height:0px">
            <td colspan=15><div style="padding:5px;display:block">
                    <div style="float:left;">
                        <input type="button" value="<?php __('Add')?>" class="btn" id="addbtn" />
                    </div>
                    <div class="clearfix"></div>
                    <br >
                    <table class="table dynamicTable tableTools table-bordered  table-condensed" id="tbl" style="clear:both;">
                        <thead>
                            <tr>
                                <td style="width:15%"><?php echo __('Trunk Number', true); ?></td>
                                <td style="width:20%"><?php echo __('Carriers', true); ?></td>
                                <td style="width:20%"><?php echo __('Trunk', true); ?></td>
                                <td style="width:10%"><?php echo __('Rate', true); ?></td>
                                <td style="width:10%"><?php echo __('Cps Limit', true); ?></td>
                                <td style="width:10%"><?php echo __('Call Limit', true); ?></td>
                                <td style="width:20%"><?php echo __('Percentage', true); ?></td>
                                <td style="width:11%"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($this->data['sel']) && count($this->data['sel']) > 0) {
                                $sel = $this->data['sel'];

                                foreach ($sel as $key => $val) {
                                    ?>
                                    <tr class="row-<?php echo $key + 1 ?>">
                                        <td><?php echo $key + 1 ?></td>
                                        <td><?php echo $xform->search('Carriers1', Array('options' => $appProduct->_get_select_options($ClientList, 'Client', 'client_id', 'name'), 'value' => $val[0]['client_id'], 'onchange' => 'client(this)', 'style' => 'width:280px')) ?></td>
                                        <td><?php echo $xform->search('resource_id[]', Array('style' => 'width:280px', 'options' => isset($client_resources[$val[0]['client_id']]) ? $client_resources[$val[0]['client_id']] : array(), 'empty' => '', 'onchange' => 'get_rate(this)', 'class' => 'resource_list', 'value' => $val[0]['resource_id'])) ?></td>
                                        <td><?php echo $val[0]['rate']; ?></td>
                                        <td><?php echo $val[0]['cps_limit']; ?></td>
                                        <td><?php echo $val[0]['capacity']; ?></td>
                                        <td><?php echo $xform->search('percentage[]', Array('style' => 'display:none', 'class' => 'dd', 'value' => $val[0]['by_percentage'])) ?></td>
                                        <td>
                                            <a href="###" class="changeup"><i class="icon-long-arrow-up"></i></a>
                                            <a href="###" class="changedown"><i class="icon-long-arrow-down"></i></a>
                                            <a href="###" class="delete_tr"><i class="icon-remove"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="row-1">
                                    <td>1</td>
                                    <td><?php echo $xform->search('Carriers1', Array('options' => $appProduct->_get_select_options($ClientList, 'Client', 'client_id', 'name'), 'value' => array_keys_value($this->data, 'client1.client_id'), 'onchange' => 'client(this)', 'style' => 'width:280px')) ?></td>
                                    <td><?php echo $xform->search('resource_id[]', Array('style' => 'width:280px', 'options' => @$client_resources[array_keys_value($this->data, 'client1.client_id')] or array(), 'empty' => '', 'onchange' => 'get_rate(this)', 'value' => array_keys_value($this->data, 'StaticRoute.resource_id_1'))) ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $xform->search('percentage[]', Array('style' => 'display:none', 'class' => 'dd', 'value' => array_keys_value($this->data, 'StaticRoute.percentage_1'))) ?></td>
                                    <td>
                                        <a href="###" class="changeup"><i class="icon-long-arrow-up"></i></a>
                                        <a href="###" class="changedown"><i class="icon-long-arrow-down"></i></a>
                                        <a href="###" class="delete_tr"><i class="icon-remove"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div></td>
        </tr>
    </table>
</form>

