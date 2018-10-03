<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdr_reconciliation">
        <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdr_reconciliation/add">
        <?php echo __('Create New CDR Reconciliation') ?></a></li>
</ul>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <form id="cdr_form" enctype="multipart/form-data" method="post">
                <div>
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="40%">
                            <col width="60%">
                        </colgroup>
                        <tr>
                            <td class="right"><?php __('Compare Type') ?>:</td>
                            <td>
                                <select id="compare_type" name="compare_type" onchange="check_thead_select();" class="uniform-select">
                                    <option value="0"><?php __('US LRN Non-Jurisdiction') ?></option>
                                    <option value="1"><?php __('US LRN Jurisdiction') ?></option>
                                    <option value="2"><?php __('US DNIS') ?></option>
                                    <option value="3"><?php __('International A-Z') ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="source">
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="40%">
                            <col width="60%">
                        </colgroup>
                        <tr>
                            <td class="right"><?php __('Your CDR') ?>:</td>
                            <td>
                                <input id="source_file" type="file" class="myfile" multiple="true">
                            </td>
                        </tr>
                    </table>

                    <div id="source_file_loading" style="display:none;" >
                        <img src="<?php echo $this->webroot; ?>images/loading.gif"><?php __('loading')?>......
                    </div>

                    <div id="source_file_msg" style="display:none;" >   <!--style="display:none;"-->
                        <input type="hidden" id="source_file_path" name="source_file_path">
                <span class="analysis" style="display:block;">

                </span>

                        <table id="list"  class="form table dynamicTable tableTools table-bordered  table-white">
                            <thead>
                            <tr>
                                <th><?php __('Start line') ?></th>
                                <th><?php __('Time Type') ?></th>
                                <th><?php __('Compare Baseds') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input value="2" id="effective_line" class="in-text" type="text" name="effective_line">
                                </td>
                                <td>
                                    <select  id="source_time_type" class="uniform-select"  name="source_time_type" >
                                        <option value="1"><?php __('Minutes') ?></option>
                                        <option value="2"><?php __('Seconds') ?></option>
                                    </select>
                                </td>

                                <td>
                                    <select  id="source_compare_based" class="uniform-select"  name="source_compare_based" >
                                        <option value="1"><?php __('Compare Based on DNIS only') ?></option>
                                        <option value="2"><?php __('Compare Based on ANI and DNIS') ?></option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="formRow">
                            <strong><?php __('Showing top 10')?></strong>
                        </div>
                        <div class="overflow_x">
                            <table id="source_file_table" class="form table dynamicTable tableTools table-bordered  table-white">
                                <thead>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div id="diff">
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="40%">
                            <col width="60%">
                        </colgroup>
                        <tr>
                            <td class="right"><?php __('Partner’s CDR')?>:</td>
                            <td>
                                <input  id="diff_file" type="file" class="myfile"  multiple="true" >
                            </td>
                        </tr>
                    </table>

                    <div id="diff_file_loading" style="display:none;">
                        <img src="<?php echo $this->webroot; ?>images/loading.gif"><?php __('loading')?>......
                    </div>
                    <div id="diff_file_msg" style="display:none;" >
                        <input type="hidden" id="diff_file_path" name="diff_file_path">
                <span class="analysis" style="display:block;">

                </span>

                        <table id="list" cellspacing="0" cellpadding="0" class="form table dynamicTable tableTools table-bordered  table-white">
                            <thead>
                            <tr>
                                <th><?php __('Start line') ?></th>
                                <th><?php __('Time Type') ?></th>
                                <th><?php __('Compare Baseds') ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>
                                    <input value="2" id="effective_line1" class="in-text" type="text" name="effective_line1">
                                </td>
                                <td>
                                    <select  id="diff_time_type" class="uniform-select"  name="diff_time_type" >
                                        <option value="1"><?php __('Minutes') ?></option>
                                        <option value="2"><?php __('Seconds') ?></option>
                                    </select>
                                </td>

                                <td>
                                    <select  id="diff_compare_based" class="uniform-select"  name="diff_compare_based" >
                                        <option value="1"><?php __('Compare Based on DNIS only') ?></option>
                                        <option value="2"><?php __('Compare Based on ANI and DNIS') ?></option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="formRow">
                            <strong><?php __('Showing top 10')?></strong>
                        </div>
                        <div class="overflow_x">
                            <table id="diff_file_table" cellspacing="0" cellpadding="0" class="form table dynamicTable tableTools table-bordered  table-white">
                                <thead>

                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="center">
                    <p class="stdformbutton" >
                        <button class="btn btn-primary"><?php __('Submit')?></button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>



<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>


<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>


<script type="text/javascript">
    $(function() {



        function check_thead_select(){
            //var select_type = $("#compare_type").val();
            //alert('ww')
            $("#source_file_table").find("thead").find("th").each(function(){
                //alert('a');
                $(this).empty();
                $(this).append(get_select("source"));
            });

            $("#diff_file_table").find("thead").find("td").each(function(){
                $(this).empty();
                $(this).append(get_select("diff"));
            });

            check_select();
        }

        function get_select(type){

            var select_name = (type == "source")?"source_cols_index[]":"diff_cols_index[]";

            var select_val = "<option value=''>Ignore</option>";
            var select_array = new Array();
            var select_type = $("#compare_type").val();

            switch(select_type){
                case "0":
                    /*
                     *ani  dnis  time  lrn  rate cost duration
                     **/
                    select_array = new Array("ani","dnis","time","lrn","rate","cost","duration");
                    break;
                case "1":
                    /* 
                     * duration ani dnis time lrn rate  cost jurisdiction
                     * */
                    select_array = new Array("duration","ani","dnis","time","lrn","rate","cost","jurisdiction");
                    break;
                case "2":
                case "3":
                    /*
                     *ani  dnis time rate cost duration
                     *
                     *
                     **/
                    select_array = new Array("ani","dnis","time","rate","cost","duration");
                    break;
            }

            $.each(select_array,function (index,content){
                select_val += "<option value='"+content+"' >"+content+"</option>";
            });

            if(select_val != ""){
                select_val = "<select name='"+select_name+"' >"+select_val+"</select>";
            }

            return  select_val;
        }


        function get_thead_str(m,type){
            var thead_str = ""
            var select_val = get_select(type);

            for(var i=0;i < m;i++){
                thead_str += "<th>"+select_val+"</th>";
            }

            if(thead_str != ''){
                thead_str = "<tr>"+thead_str+"</tr>";
            }

            return thead_str;
        }

        function get_file_msg(data,obj_table,ajax_loading,ajax_result,type){


            $.ajax({
                'url':'<?php echo $this->webroot; ?>cdr_reconciliation/get_file_top',
                'type':'post',
                'dataType':'json',
                'data':{'file_path':data},
                'beforeSend':function(){
                    ajax_loading.show();
                    ajax_result.hide();
                },
                'success':function(data){
                    ajax_loading.hide();
                    if(data != null && data != ''){
                        if(data['status'] == 'success'){
                            ajax_result.show();
                            obj_table.find("thead").empty();

                            var thead_str = get_thead_str(data['cols'],type);
                            obj_table.find("thead").append(thead_str);

                            obj_table.find("tbody").empty();
                            var tbody_str = "";
                            $.each(data['data'],function(index,content){
                                tbody_str += "<tr>"
                                for(var i=0;i < data['cols'];i++){
                                    if(content.hasOwnProperty(i)){
                                        tbody_str += "<td>"+content[i]+"</td>";
                                    }else{
                                        tbody_str += "<td></td>";
                                    }
                                }
                                tbody_str += "</tr>"
                            });

                            if(tbody_str != ''){
                                obj_table.find("tbody").append(tbody_str);
                            }
                        }
                    }
                }
            });


        }


        $("#source_file").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/cdr_c_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                //alert($(this).html());
                $("input[name$=_filename]", source).val(file.name);
                $("input[name$=_guid]", source).val(response);
                //$(this).parent().find(".analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response + '">Show and modify</a>');
                $("span[id$=_completedMessage]", source).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                );

                get_file_msg(response,$("#source_file_table"),$("#source_file_loading"),$("#source_file_msg"),'source');


            }
        });


        $("#diff_file").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/cdr_c_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                //alert($(this).html());
                $("input[name$=_filename]", diff).val(file.name);
                $("input[name$=_guid]", diff).val(response);
                //$(this).parent().find(".analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response + '">Show and modify</a>');
                $("span[id$=_completedMessage]", diff).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", Math.round(file.size / 1024))
                );

                get_file_msg(response,$("#diff_file_table"),$("#diff_file_loading"),$("#diff_file_msg"),'diff');

            }
        });










        <?php if (isset($upload_id)): ?>



        window.setInterval(function() {
            $.ajax({
                'url': '<?php echo $this->webroot ?>uploads/get_upload_log?id=<?php echo $upload_id; ?>',
                'type': 'POST',
                'dataType': 'text',
                'success': function(data){
                    $('#container').html(data.substr(2));
                }
            });
        }, 2000);

        <?php endif; ?>
    });







</script>
