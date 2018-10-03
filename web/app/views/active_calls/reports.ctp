<?php $mydata = $p->getDataArray(); ?>
<style type="text/css">
    .overflow_x{overflow-x:auto; margin-bottom: 10px;}
    input[type="text"]{width:220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>active_calls/reports">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>active_calls/reports">
            <?php echo __('Active Call Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Active Call Report') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons remove" href="<?php echo $this->webroot; ?>active_calls/killAll"><i></i> Kill All</a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">


            <?php
            if ($error_note)
            {
                ?>
                <div class="msg center">
                    <br />
                    <h2><?php echo $error_note; ?></h2>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <?php foreach ($search_show_fields as $index => $field): ?>
                                <th><?php echo $fields[$field]; ?></th>
                            <?php endforeach; ?>
                            <th><?php __('Action')?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($mydata as $row):
                            ?>
                            <tr>

                                <?php
                                $uuid = array_shift($row);
                                foreach ($row as $key => $item):
//                                            $key = $key + 1;
                                    if(($search_show_fields[$key]=='start_time') || ($search_show_fields[$key]=='answer_time')):
                                        $item = date('Y-m-d H:i:s',$item/1000000);
                                    elseif ($search_show_fields[$key]=='ingress_carrier'):
                                        $item = $ingress_clients[$item];
                                    elseif ($search_show_fields[$key]=='ingress_trunk'):
                                        $item = $ingress_resources[$item];
                                    elseif ($search_show_fields[$key]=='egress_carrier'):
                                        $item = $egress_clients[$item];
                                    elseif ($search_show_fields[$key]=='egress_trunk'):
                                        $item = $egress_resources[$item];
                                    endif;
                                    ?>
                                    <td><?php echo $item;?></td>
                                <?php endforeach; ?>
                                <td>
                                    <a title="Delete" onclick="return myconfirm('<?php __('Sure to Kill the Call') ?>', this)" class="delete" href='<?php echo $this->webroot ?>active_calls/kill/<?php echo isset($_GET['switch_name']) ? $_GET['switch_name'] : $switch_names[0]; echo '/' . base64_encode($uuid) ?>'>
                                        <i class="icon-remove"></i>
                                    </a>
                                    <!--                                        <a title="Delete" href="--><?php //echo $this->webroot ?><!--active_calls/kill/--><?php //echo isset($_GET['switch_name']) ? $_GET['switch_name'] : $switch_names[0] . '/' . base64_encode($uuid) ?><!--" style="margin-left: 20px;" id="delete">-->
                                    <!--                                            <i class="icon-remove"></i>-->
                                    <!--                                        </a>-->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
            <?php } ?>
            <?php if(isset($cmd_debug) && $cmd_debug): ?>
                <div><?php echo $info["Systemparam"]["cmd_debug"]; ?></div>
            <?php endif; ?>
            <fieldset class="query-box">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <form action="<?php echo $this->webroot; ?>active_calls/reports" method="get" id="myform">
                    <input type="hidden" name="query" value="1" />
                    <input type="hidden" name="size" value="<?php echo isset($_GET['size']) ? $_GET['size'] : 100 ?>" />
                    <table style="width: 100%">
                        <tbody>
                        <?php if (count($switch_names)): ?>
                            <tr>
                                <td class="align_right padding-r10"><?php echo __('Active Call Server', true); ?></td>
                                <td>
                                    <select name="switch_name">
                                        <?php
                                        foreach ($switch_names as $key => $item)
                                        {
                                            ?>
                                            <option value="<?php echo $item ?>" <?php
                                            if (isset($this->params['url']['switch_name']) && !strcmp($this->params['url']['switch_name'], $item))
                                            {
                                                ?> selected="selected"<?php } ?> ><?php echo $item ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <input type="submit" class="input in-submit btn btn-primary" value="<?php __('Search')?>">
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr class="period-block">
                            <td style="text-align:center;font-size:14px;" colspan="2"><b><?php __('Inbound')?></b></td>
                            <?php if ($outbound_report)
                            {
                                ?>
                                <td style="text-align:center;font-size:14px;" colspan="2"><b><?php __('Outbound')?></b></td>
                            <?php } ?>
                            <td style="text-align:center;font-size:14px;" colspan="2"><b><?php __('Show Fields')?></b></td>
                        </tr>
                        <tr>
                            <td><?php __('Carrier')?></td>
                            <td>
                                <select name="orig_carrier" id="orig_carrier">
                                    <option></option>
                                    <?php foreach ($ingress_clients as $k => $v): ?>
                                        <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('orig_carrier', $k) ?>><?php echo $v; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <?php if ($outbound_report)
                            {
                                ?>
                                <td><?php __('Carrier')?></td>
                                <td>
                                    <select name="term_carrier" id="term_carrier">
                                        <option></option>
                                        <?php foreach ($egress_clients as $k => $v): ?>
                                            <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('term_carrier', $k) ?>><?php echo $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>

                            <?php } ?>
                            <td rowspan="4">
                                <select name="show_fields[]" multiple="multiple" style="width:400px;height:200px;">
                                    <?php foreach ($fields as $k => $v): ?>
                                        <option value="<?php echo $k; ?>" <?php if (in_array($k, $search_show_fields)) echo 'selected="selected"'; ?>>
                                            <?php

                                            $v = str_replace("Ip","IP",$v);
                                            $v = str_replace("Ani","ANI",$v);
                                            $v = str_replace("Dnis","DNIS",$v);
                                            $v = str_replace("Lrn","LRN",$v);
                                            $v = str_replace("rtp","RTP",$v);

                                            echo $v;

                                            ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Ingress')?></td>
                            <td>
                                <select name="ingress" id="ingress_resource">
                                    <option></option>
                                    <?php foreach ($ingress_resources as $k => $v): ?>
                                        <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('ingress', $k) ?>><?php echo $v; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <?php if ($outbound_report)
                            {
                                ?>
                                <td><?php __('Egress')?></td>
                                <td>
                                    <select name="egress" id="egress_resource">
                                        <option></option>
                                        <?php foreach ($egress_resources as $k => $v): ?>
                                            <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('egress', $k) ?>><?php echo $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td><?php __('IP')?></td>
                            <td>
                                <select name="orig_ip" id="orig_ip">
                                    <option></option>
                                    <?php foreach ($ingress_resource_ips as $k => $v): ?>
                                        <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('orig_ip', $k) ?>><?php echo $v; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <?php if ($outbound_report)
                            {
                                ?>
                                <td><?php __('IP')?></td>
                                <td>
                                    <select name="term_ip" id="term_ip">
                                        <option></option>
                                        <?php foreach ($egress_resource_ips as $k => $v): ?>
                                            <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('term_ip', $k) ?>><?php echo $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td><?php __('ANI')?></td>
                            <td>
                                <input type="text" name="ani"  value="<?php echo $common->set_get_value('ani') ?>" />
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <?php if ($outbound_report)
                            {
                                ?>
                                <td><?php __('DNIS')?></td>
                                <td>
                                    <input type="text" name="dnis" value="<?php echo $common->set_get_value('dnis') ?>" />
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                            <?php } ?>
                        </tr>
                        </tbody>
                    </table>
                    <!--<div id="form_footer" class="buttons-group center"><input type="submit" class="input in-submit btn btn-primary" value="Query"></div>-->
                </form>
            </fieldset>
        </div>
        <?php if (isset($send) && !empty($send)): ?>
            <div><?php echo $send; ?></div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        var $orig_carrier = $('#orig_carrier');
        var $term_carrier = $('#term_carrier');
        var $ingress_resource = $('#ingress_resource');
        var $egress_resource = $('#egress_resource');
        var $orig_ip = $('#orig_ip');
        var $term_ip = $('#term_ip');

        $orig_carrier.change(function() {
            var $this = $(this);
            var client_id = $this.val();
            $('option:gt(0)', $ingress_resource).remove();
            $.ajax({
                'url': '<?php echo $this->webroot ?>active_calls/get_resources',
                'type': 'POST',
                'dataType': 'json',
                'data': {'client_id': client_id, 'type': 'ingress'},
                'success': function(data) {
                    $.each(data, function(index, item) {
                        $ingress_resource.append("<option value='" + index + "'>" + item + "</option>");
                    });
                }
            });
        });

        $term_carrier.change(function() {
            var $this = $(this);
            var client_id = $this.val();
            $('option:gt(0)', $egress_resource).remove();
            $.ajax({
                'url': '<?php echo $this->webroot ?>active_calls/get_resources',
                'type': 'POST',
                'dataType': 'json',
                'data': {'client_id': client_id, 'type': 'egress'},
                'success': function(data) {
                    $.each(data, function(index, item) {
                        $egress_resource.append("<option value='" + index + "'>" + item + "</option>");
                    });
                }
            });
        });

        $ingress_resource.change(function() {
            var $this = $(this);
            var resource_id = $this.val();
            $('option:gt(0)', $orig_ip).remove();
            $.ajax({
                'url': '<?php echo $this->webroot ?>active_calls/get_resource_ips',
                'type': 'POST',
                'dataType': 'json',
                'data': {'resource_id': resource_id, 'type': 'ingress'},
                'success': function(data) {
                    $.each(data, function(index, item) {
                        $orig_ip.append("<option value='" + index + "'>" + item + "</option>");
                    });
                }
            });
        });

        $egress_resource.change(function() {
            var $this = $(this);
            var resource_id = $this.val();
            $('option:gt(0)', $term_ip).remove();
            $.ajax({
                'url': '<?php echo $this->webroot ?>active_calls/get_resource_ips',
                'type': 'POST',
                'dataType': 'json',
                'data': {'resource_id': resource_id, 'type': 'egress'},
                'success': function(data) {
                    $.each(data, function(index, item) {
                        $term_ip.append("<option value='" + index + "'>" + item + "</option>");
                    });
                }
            });
        });

        $('#myform').click(function() {
            //loading();
        });
    });
</script>

<script>
    $(function(){

        $('select[name="show_fields[]"]').live('click',function(){
            $.cookie('select_all_columns',1, { path: "/"});
        })


    })
</script>