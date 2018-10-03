<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('search')?></h4>
    <div class="clearfix"></div>
    <script
        type="text/javascript">

        //设置每个字段所对应的隐藏域
        var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};
        var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name',	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
        var _ss_ids_client_term = {'id_clients': 'query-id_clients_term', 'id_clients_name': 'query-id_clients_name_term'};
        var _ss_ids_code_name = {'code_name': 'query-code_name'};
        var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
    </script>
    <?php
    $url="/".$this->params['url']['url'];
    //if($rate_type=='spam'){$url='/cdrreports_db/summary_reports/spam/';}else{$url='/cdrreports_db/summary_reports/';}
    echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
        'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
    <?php echo $this->element('search_report/search_js_show')?> <?php echo $appCommon->show_page_hidden();?>
    <input type="hidden" value="searchkey" name="searchkey" />
    <input
        class="input in-hidden" type="hidden" name="query[id_clients_term]"
        value="" id="query-id_clients_term" />
    <input class="input in-hidden"
           name="query[id_clients]" value="" id="query-id_clients" type="hidden">
    <input class="input in-hidden" name="query[id_rates]" value=""
           id="query-id_rates" type="hidden">
    <table class="form" style="width: 100%">
        <tbody>
        <?php echo $this->element('report_db/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <!-- <option value="xls">Excel XLS</option> -->
            <!--<option value="email">Email when done</option>-->
          </select>'))?>
        </tbody>
    </table>
    <table>
    <tbody>
        <tr>
            <td class="align_right padding-r10"><?php echo __('Trunk Name', true); ?></td>
            <td><?php
                echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="align_right padding-r10"><?php echo __('ani', true); ?> </td>
            <td class="">

                <input type="text" id="query-src_number" value="" name="query[src_number]" class="input">
                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
            </td>
            <td class="align_right padding-r10"><?php echo __('dnis', true); ?> </td>
            <td>
                <input type="text" id="query-dst_number" value=""
                name="query[dst_number]" class="input">
                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
            </td>
            <td class="align_right padding-r10"><?php echo __('Duration', true); ?></td>
            <td>
            <select id="query-duration" name="query[duration]"
                        class="input in-select">
                <option value="" selected="selected">all</option>
                <option value="nonzero">non-zero</option>
                <option value="zero">zero</option>
            </select>
            <?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td class="in-out_bound">&nbsp;</td>
        </tr>
    </tbody>
    </table>
</fieldset>
<?php echo $form->end();?>

<div id="pop-div" class="pop-div" style="width: 320px; height: 80px; position: absolute; left: 50%; top: 50%; z-index: 9999; margin-top: 0px;display:none;">
    <label style="color:red;">You did not set your email address!</label>
    <input type="text" id="send_email" />
    <input type="button" value="submit" id="send_email_btn" />
</div>

<script>
    $(function() {
        $('#formquery').click(function() {
            if($('#query-output').val() == 'email') {
                $.ajax({
                    'url'      : '<?php echo $this->webroot ?>cdrreports_db/check_email',
                    'type'     : 'GET',
                    'dataType' : 'text',
                    'async'    : false,
                    'success'  : function(data) {
                        if(data == '0') {
                            $('#pop-div').show();
                            $('#send_email_btn').click(function() {
                                var email = $('#send_email').val();
                                $.ajax({
                                    'url'      : '<?php echo $this->webroot ?>cdrreports_db/update_email',
                                    'type'     : 'POST',
                                    'dataType' : 'text',
                                    'data'     : {'email':email},
                                    'success'  : function(data) {
                                        $('#report_form').submit();
                                    }
                                });
                            });
                        } else {
                            $('#report_form').submit();
                        }
                        return false;
                    }
                });

                return false;
            }
        });
    });
</script>

<style>
    table.in-date {
        width: 100%;
    }
</style>
