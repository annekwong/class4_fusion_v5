<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <div class="search_title"><img src="<?php echo $this->webroot ?>images/search_title_icon.png" />
        <?php __('search') ?>
    </div>
    <?php echo $this->element('search_report/search_js'); ?>
    <?php echo $this->element('search_report/search_js_show'); ?>
    <?php
    $url = "/" . $this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports/summary_reports/spam/';}else{$url='/cdrreports/summary_reports/';}
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
        'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
    ?>
    <?php echo $appCommon->show_page_hidden(); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
    <table class="form" style="width: 100%">
        <tbody>
            <?php
            echo $this->element('report/form_period', array('group_time' => false, 'gettype' => '<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <option value="xls">Excel XLS</option>
            <option value="email">Email when done</option>
            
          </select>'
            ))
//                    <option value="noemail">Download in Backend</option>
            ?>
        </tbody>
    </table>
    <table class="form" style="width: 100%">
        <tr>

            <td><?php if (count($report_server)): ?><?php echo __('Report-server', true); ?>:<?php endif; ?></td>
            <td><?php if (count($report_server)): ?>
                    <?php //echo $form->input('server_ip', array('options' => $report_server, 'empty' => '',  'default' =>'192.168.1.107', 'label' => false, 'div' => false, 'type' => 'select'));  ?>
                    <select name="report_ip">
                        <?php
                        foreach ($report_server as $key=>$report_server_item)
                        {
                            ?>
                            <option value="<?php echo $report_server_item ?>" <?php if (isset($_GET['report_ip']) && !strcmp($_GET['report_ip'], $report_server_item))
                    {
                                ?> selected="selected"<?php } ?> ><?php echo $key ?></option> 
                    <?php } ?>  
                    </select>
<?php endif; ?>
            </td>
            <td>&nbsp;</td>
            <td><?php //if (count($server) > 1):  ?><?php //echo __('Class4-server', true);  ?><?php //endif;  ?></td>
            <td><?php //if (count($server) > 1):  ?>
                <?php //echo $form->input('server_ip', array('options' => $server, 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select'));    ?>
<?php //endif;     ?>
            </td>
            <td>&nbsp;</td>
            </td>


        </tr>
        <tr>
            <td><?php echo __('ani', true); ?></td>
            <td>
                <input type="text" id="query-src_number"
                       name="query[src_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?>
            </td>
            <td><?php echo __('dnis', true); ?></td>
            <td>
                <input type="text" id="query-dst_number" 
                       name="query[dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?>
            </td>
            <!--<td><?php echo __('ip', true); ?></td>
            <td>
                <input type="text" id="query-dst_number" value="<?php echo isset($_GET ['query'] ['origination_source_host_name']) ? $_GET ['query'] ['origination_source_host_name'] : '' ?>"
                       name="query[origination_source_host_name]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?>
            </td>-->
        </tr>
    </table>
    <input type="hidden" value="" name="send_mail_address" id="real_send_mail_address" />
</fieldset>
<?php echo $form->end(); ?>


<div id="pop-div" title="Set Email" style="display: none;">
    <label style="color:red;"><?php __('You did not set your email address')?>!</label>
    <input type="text" id="send_email" />
    <input type="button" value="submit" id="send_email_btn" />
</div>

<script type="text/javascript">
    function getTechPrefix(obj) {
        $("#CdrRoutePrefix").empty();
        $("#CdrRoutePrefix").append("<option value=''>All</option>");
        if ($(obj).val() != '0') {
            $.post("<?php echo $this->webroot ?>cdrreports/getTechPerfix", {ingId: $(obj).val()},
            function(data) {
                $.each(eval(data),
                        function(index, content) {
                            $("#CdrRoutePrefix").append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                        }
                );
            });

        }
    }

    $(function() {
        $("#report_form").submit(function() {

            if ($('#query-output').val() == 'web')
                loading();
            if ($('#query-output').val() == 'email') {
                var email = $('#real_send_mail_address').val();

                if (email)
                {
                    return true;
                }
                $.ajax({
                    'url': '<?php echo $this->webroot ?>cdrreports/check_email',
                    'type': 'GET',
                    'dataType': 'json',
                    'async': false,
                    'success': function(data) {
                        $('#send_email').val(data.email);
                        $('#pop-div').dialog({buttons: [{text: "Submit", "class": "btn btn-primary", click: function() {
                                        var val = $('#send_email').val();
                                        if (val != '')
                                        {
                                            $('#real_send_mail_address').val(val);
                                            $('#report_form').submit();
                                        }
                                    }}]});
                        return false;
                    }
                });
                return false;
            }
            return true;


        });
    });
</script>