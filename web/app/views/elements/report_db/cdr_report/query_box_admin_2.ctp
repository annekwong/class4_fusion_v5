<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
    <div class="pull-right" title="Advance">
        <a id="advance_btn" class="btn" href="###">
            <i class="icon-long-arrow-down"></i>
        </a>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->element('search_report/search_js');?>
    <?php 	echo $this->element('search_report/search_js_show');?>
    <?php
    $url="/".$this->params['url']['url'];
    //if($rate_type=='spam'){$url='/cdrreports_db/summary_reports/spam/';}else{$url='/cdrreports_db/summary_reports/';}
    echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
        'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
    <?php echo $appCommon->show_page_hidden();?> <?php echo $this->element('search_report/search_hide_input');?>
    <table class="form" style="width: 100%">
        <tbody>
        <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <!-- <option value="xls">Excel XLS</option> -->
            <!--<option value="email">Email when done</option>-->
          </select>'
        ))?>
        </tbody>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
        <div class="separator"></div>
        <div class="row">
            <div class="span4 offset2">
                <?php echo __('ani',true);?>:
                <input type="text" id="query-src_number" name="query[src_number]" class="input in-text" />
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </div>
            <div class="span4">
                <?php echo __('dnis',true);?>:
                <input type="text" id="query-dst_number" name="query[dst_number]" class="input in-text" />
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </div>
            <div class="span4">
                <?php echo __('ip',true);?>:
                <input type="text" id="query-dst_number"  name="query[origination_source_host_name]" class="input in-text"
                       value="<?php echo isset($_GET ['query'] ['origination_source_host_name']) ? $_GET ['query'] ['origination_source_host_name']: '' ?>">
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </div>
        </div>
    </div>
</fieldset>
<?php echo $form->end();?>

<div id="pop-div" class="pop-div" style="width: 320px; height: 80px; position: absolute; left: 50%; top: 50%; z-index: 9999; margin-top: 0px;display:none;">
    <label style="color:red;">You did not set your email address!</label>
    <input type="text" id="send_email" />
    <input type="button" value="submit" id="send_email_btn" />
</div>

<script type="text/javascript">
    function getTechPrefix(obj){
        $("#CdrRoutePrefix").empty();
        $("#CdrRoutePrefix").append("<option value=''>All</option>");
        if($(obj).val() != '0'){
            $.post("<?php echo $this->webroot?>cdrreports_db/getTechPerfix", {ingId:$(obj).val()},
                function(data){
                    $.each(eval(data),
                        function (index,content){
                            $("#CdrRoutePrefix").append("<option value='"+content[0]['tech_prefix']+"'>"+content[0]['tech_prefix']+"</option>");
                        }
                    );
                });

        }
    }

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