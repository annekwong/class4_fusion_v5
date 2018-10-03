<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
    <div title="Advance" class="pull-right">
        <a href="###" class="btn" id="advance_btn">
            <i class="icon-long-arrow-down"></i> 
        </a>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->element('search_report/search_js'); ?>
    <?php echo $this->element('search_report/search_js_show'); ?>
    <?php
    $url = "/" . $this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports_db/summary_reports/spam/';}else{$url='/cdrreports_db/summary_reports/';}
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
        'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
    ?>
    <?php echo $appCommon->show_page_hidden(); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
    <input type="hidden" name="open_callmonitor" value="<?php echo isset($_GET['open_callmonitor']) and $_GET['open_callmonitor'] == 1 ? 1 : 0; ?>">
    <input type="hidden" name="min_start_date" value="<?php echo isset($_GET['min_start_date']) ? $_GET['min_start_date'] : 0; ?>">
    <input type="hidden" name="min_start_time" value="<?php echo isset($_GET['min_start_time']) ? $_GET['min_start_time'] : 0; ?>">
    <input type="hidden" name="max_stop_date" value="<?php echo isset($_GET['max_stop_date']) ? $_GET['max_stop_date'] : 0; ?>">
    <input type="hidden" name="max_stop_time" value="<?php echo isset($_GET['max_stop_time']) ? $_GET['max_stop_time'] : 0; ?>">
    <input type="hidden" id="real_send_mail_address" name="send_mail_address" />
    <table class="form" style="width: 100%">
        <tbody>

            <?php
            if (!($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'))
            {
                ?>

                <?php
                echo $this->element('report/form_period', array('group_time' => false, 'gettype' => '<select id="query-output"
                                        onchange="repaintOutput();" name="query[output]"
                                        class="input in-select">
                    <option value="web">Web</option>
                    <option value="csv">Excel CSV</option>
                    <option value="xls">Excel XLS</option>
                    <!--<option value="email">Email when done</option>-->
                  </select>'
                ))
                ?>

                <?php
            }
            else
            {
                ?>

                <?php
                echo $this->element('report/form_period', array('group_time' => false, 'gettype' => '<select id="query-output"
                                        onchange="repaintOutput();" name="query[output]"
                                        class="input in-select">
                    <option value="web">Web</option>
                  </select>'
                ))
                ?>

                <?php
            }
            ?>


        </tbody>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray" style="display: none;">
        <table class="form" style="width:100%">
            <tbody>
                <tr> <?php echo $this->element('search_report/orig_carrier_select'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                    <td  valign="top" rowspan="9" colspan="2"
                         style="padding-left: 10px;width:25%;">

                        <?php
                        if (!($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'))
                        {
                            ?>

                            <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                            <?php
                            echo $form->select('Cdr.field', $cdr_field, $show_field_array, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                            ?>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php echo __('Ingress', true); ?></td>
                    <td><?php
                        echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                        ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>

                </tr>

                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php echo __('ani', true); ?> </td>
                    <td class="value"><input type="text" id="query-src_number" value=""
                                             name="query[src_number]" class="input width220"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                </tr>

                <tr>
                    <td class="align_right padding-r10"><?php echo __('dnis', true); ?> </td>
                    <td><input type="text" id="query-dst_number" value=""
                               name="query[dst_number]" class="input width220"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</fieldset>

<?php echo $form->end(); ?>

<!--<div id="pop-div" closed="true" class="easyui-dialog" title="CDR Email When Done" style="width:400px;height:100px;"  
        data-options="iconCls:'icon-save',resizable:true,modal:true">
    <div class="product_list">
        <label>Email Address:</label>
        <input type="text" class="input in-text in-input" id="send_email" />
        <input type="button" id="send_email_btn" value="Submit" />
    </div>
</div> -->


<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
<scirpt type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.center.js"></scirpt>

<script type="text/javascript">
                                   function getTechPrefix(obj) {
                                       $("#CdrRoutePrefix").empty();
                                       $("#CdrRoutePrefix").append("<option value=''>All</option>");
                                       if ($(obj).val() != '0') {
                                           $.post("<?php echo $this->webroot ?>cdrreports_db/getTechPerfix", {ingId: $(obj).val()},
                                           function(data) {
                                               $.each(data['prefixes'],
                                                       function(index, content) {
                                                           $("#CdrRoutePrefix").append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                                                       }
                                               );
                                           }, 'json');

                                       }
                                   }

                                   $(function() {
                                       $('#formquery').click(function() {
                                           if ($('#query-output').val() == 'email') {
                                               $.ajax({
                                                   'url': '<?php echo $this->webroot ?>cdrreports_db/check_email',
                                                   'type': 'GET',
                                                   'dataType': 'json',
                                                   'async': false,
                                                   'success': function(data) {
                                                       $('#send_email').val(data.email);
                                                       $('#pop-div').dialog('open');
                                                       $('#send_email_btn').click(function() {
                                                           var val = $('#send_email').val();
                                                           if (val != '')
                                                           {
                                                               $('#real_send_mail_address').val(val);
                                                               $('#report_form').submit();
                                                           }
                                                       });
                                                       return false;
                                                   }
                                               });
                                               return false;
                                           }
                                       });
                                   });
</script>