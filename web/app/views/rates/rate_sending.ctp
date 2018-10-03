<style type="text/css">
    #myform {width:800px; margin:0 auto;}    
    #myform label { float:left;width:200px;}
    #myform select.multiple {width:400px;height:200px;}
    #myform input.input {width:400px;}
    #myform p.submit {text-align:center;}
    #template_panel {width:450px;position:absolute;overflow:hidden;left:50%;top:50%;background:#7FAF00;margin-top:0;border:1px solid #ccc;padding:5px;display:none;border-radius: 5px;}
    .right {text-align:right; }
    .temhead{background: #7FAF00;display: block;height:20px;}
    #template_panel .temhead a {cursor:pointer; }
    #template_panel p{background: #fff;padding:20px 0 20px 120px;}
    #template_panel label {display: block;margin:3px 0 ; }
    #template_panel input, textarea {display:block; }
    #template_panel textarea { width:350px; height:100px;}
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
    input[type="text"]{width: 220px;}
   .widget-head .tabs{border-bottom: 1px solid #ebebeb;width: 100%;font-size: 13px;padding: 0;}
   .widget-head ul.tabs li{border:0px solid #ebebeb;margin: 0;}
   form{margin:0;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate sending') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate sending') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
        <div class="widget-head">
            <ul class="tabs">
                <li class="active">
                    <a href="<?php echo $this->webroot ?>rates/rate_sending" class="glyphicons list">
                        <i></i><?php __('Rate sending ') ?>  		
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_templates" class="glyphicons cogwheel">
                        <i></i><?php __('Template') ?>  		
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>rates/rate_sending_logging" class="glyphicons book_open">
                        <i></i><?php __('Log') ?>  		
                    </a>
                </li>
            </ul>
        </div>
        <div id="container" class="widget-body">
            

            <div id="send_panel">
                <form action="<?php echo $this->webroot ?>rates/rate_sending" method="post" id="myform">	
                    <p>					
                        <label class="right padding-r20" for="rate_table"><?php __('Rate Table'); ?></label>				
                        <select name="rate_table" id="rate_table">
                            <option selected="selected"></option>
                            <?php foreach ($rate_tables as $rate_tables_item): ?>
                                <option value="<?php echo $rate_tables_item[0]['rate_table_id']; ?>"><?php echo $rate_tables_item[0]['name']; ?></option>
                            <?php endforeach; ?>
                        </select>				
                    </p>
                    <p>					
                        <label class="right padding-r20" for="code_names"><?php __('Code Name'); ?></label>	
                        <select name="code_names[]" id="code_name" class="multiple" multiple="multiple">
                        </select>
                        <input type="checkbox" id="is_all_code_name" name="is_all_code_name" /> <?php __('All')?>
                    </p>
                    <p>
                        <label class="right padding-r20" for="effective_date"><?php __('Effective Date'); ?></label>
                        <input type="text" id="effective_date" name="effective_date" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" value="<?php echo date("Y-m-d 00:00:00"); ?>" />
                    </p>
                    <p>
                        <label class="right padding-r20" for="send_method"><?php __('Send to')?></label>				
                        <select name="send_method" id="send_method">
                            <option value="0"><?php __('Manual')?></option>
                            <option value="1"><?php __('From Carrier Listing')?></option>
                            <option value="2"><?php __('From File')?></option>
                        </select>									
                    </p>			
                    <p>
                        <label class="right padding-r20" for="email"><?php __('Email')?></label>
                        <input type="text" name="email" id="email" class="validate[required,custom[email]]" />
                    </p>
                    <p id="file_p">
                        <label class="right padding-r20"><?php __('File')?></label>
                        <input type="file" id="myfile" name="myfile" />
                    </p>
                    <p>
                        <label class="right padding-r20" for="carrier"><?php __('Carrier')?></label>
                        <select name="carrier[]" id="carrier" class="multiple" multiple="multiple">
                            <?php foreach ($carriers as $carrier): ?>
                                <option value="<?php echo $carrier[0]['client_id']; ?>"><?php echo $carrier[0]['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label class="right padding-r20" for="carrier"><?php __('Cc')?></label>
                        <input type="text" name="cc" class="validate[required,custom[email]]" />
                    </p>
                    <p>
                        <label class="right padding-r20" for="filename"><?php __('Attachment Name')?></label>
                        <input type="text" name="filename" class="validate[required]" />
                    </p>
                    <p>
                        <label class="right padding-r20" for="template"><?php __('Template')?></label>
                        <select name="template" id="template">
                            <?php foreach ($templates as $template): ?>
                                <option value="<?php echo $template[0]['id'] ?>"><?php echo $template[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <a id="show_template" class="icon-plus" href="###">
                            <i></i>
                        </a>
                    </p>
                    <p>
                        <label class="right padding-r20" for="columns"><?php __('Fields')?></label>
                        <select id="columns_select" name="columns_select" style="display:block;float:left;width:190px;height:180px;" multiple="multiple">

                        </select>
                        <select id="columns" name="columns[]"  style="display:block;margin-left: 20px;float:left;width:190px;height:180px;" multiple="multiple">
                            <?php foreach ($fields as $field): ?>
                                <option><?php echo $field ?></option>
                            <?php endforeach; ?>
                        </select>
                    <p style="float:left;padding-left: 20px;padding-top:50px;">
                        <a href="###" onclick="moveOption('egress_trunks', 'up');"><i class="icon-long-arrow-up" ></i></a>
                        <br>
                        <br>
                        <a href="###" onclick="moveOption('egress_trunks', 'down');"><i class="icon-long-arrow-down" ></i></a>
                    </p>
                    <br style="clear:both;" />
                    </p>
                    <p class="submit " ><button type="submit" class="btn btn-primary"><?php __('Send')?></button></p>	
                </form>
            </div>
        </div>

        <div id="template_panel">
            <form name="template_form" id="template_form">
                <div class="temhead">
                    <b style="color:#fff;"><?php __('Template')?></b>
                    <span class="panel-tool">
                        <a class="panel-tool-close"></a>
                    </sapn>
                </div>
                <p>
                    <label><b><?php __('Name')?>:</b></label>
                    <input type="text" id="template_name" name="name" />
                    <label><b><?php __('Subject')?>:</b></label>
                    <input type="text" id="template_subject" name="subject" />
                    <label><b><?php __('Content')?>:</b></label>
                    <textarea name="content" id="template_content"></textarea>
                    <input type="button" id="tmpbtn" value="submit" class="btn btn-primary" />
                </p>
            </form>
        </div>
    </div>
</div>
        <script type="text/javascript" src="<?php echo $this->webroot ?>js/fields_sendrate.js"></script>
        <script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
        <script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
        <script>
                            $(function() {

                                (function($) {
                                    jQuery.fn.center = function() {
                                        this.css('position', 'absolute');
                                        this.css('top', ($(window).height() - this.height()) / +$(window).scrollTop() + 'px');
                                        this.css('left', ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + 'px');
                                        return this;
                                    }
                                })(jQuery);  //调用：$("#macoArea").center();

                                var $code_name = $('#code_name');
                                var $rate_table = $('#rate_table');
                                var $send_method = $('#send_method');
                                var $email = $('#email');
                                var $carrier = $('#carrier');
                                var $template_panel = $('#template_panel');
                                var $show_template = $("#show_template");
                                var $tmpbtn = $('#tmpbtn');
                                var $template = $('#template');
                                var $is_all_code_name = $('#is_all_code_name');
                                var $file_p = $('#file_p');

                                $show_template.click(function() {
                                    $template_panel.center().show();
                                });

                                $tmpbtn.click(function() {
                                    var name = $('#template_name').val();
                                    var subject = $("#template_subject").val();
                                    var content = $('#template_content').val();
                                    $.ajax({
                                        'url': '<?php echo $this->webroot; ?>rates/add_template',
                                        'type': 'POST',
                                        'dataType': 'json',
                                        'data': {'name': name, 'subject': subject, 'content': content},
                                        'success': function(data) {
                                            $template.prepend('<option value="' + data[0]['id'] + '" selected="selected">' + name + '</option>');
                                            $template_panel.hide();
                                        }
                                    });
                                });

                                $is_all_code_name.change(function() {
                                    if ($(this).attr('checked')) {
                                        $('option', $code_name).attr('selected', true);
                                    } else {
                                        $('option', $code_name).attr('selected', false);
                                    }
                                });

                                $('a', $template_panel).click(function() {
                                    $template_panel.hide();
                                });

                                $rate_table.change(function() {
                                    var rate_table_id = $(this).val();
                                    $code_name.empty();
                                    if (rate_table_id != '') {
                                        $.ajax({
                                            'url': '<?php echo $this->webroot; ?>rates/getcodenames',
                                            'type': 'POST',
                                            'dataType': 'json',
                                            'data': {'rate_table_id': rate_table_id},
                                            'success': function(data) {
                                                $.each(data, function(index, item) {
                                                    $code_name.append('<option>' + item[0]['code_name'] + '</option>')
                                                });
                                            }
                                        });
                                    }
                                });
                                $send_method.change(function() {
                                    if ($(this).val() == '0') {
                                        $email.parent().show();
                                        $carrier.parent().hide();
                                        $file_p.hide();
                                    } else if ($(this).val() == '1') {
                                        $email.parent().hide();
                                        $carrier.parent().show();
                                        $file_p.hide();
                                    } else {
                                        $email.parent().hide();
                                        $carrier.parent().hide();
                                        $file_p.show();
                                    }
                                });
                                $rate_table.change();
                                $send_method.change();


                                $('#myform').submit(function() {
                                    var flag = false;

                                    if ($rate_table.val() == '') {
                                        jGrowl_to_notyfy('Please select a rate table!', {theme: 'jmsg-error'});
                                        flag = true;
                                    }

                                    if ($('option:selected', $code_name).length == 0) {
                                        jGrowl_to_notyfy('Please select at least one code name!', {theme: 'jmsg-error'});
                                        flag = true;
                                    }

                                    if ($send_method.val() == '0' && $email.val() == '') {
                                        jGrowl_to_notyfy('Please input the email address!', {theme: 'jmsg-error'});
                                        flag = true;
                                    }

                                    if (flag)
                                        return false;

                                    $('#columns option').attr('selected', true);


                                });

                                $("#myfile").makeAsyncUploader({
                                    upload_url: '<?php echo $this->webroot ?>rates/upload_email',
                                    flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
                                    button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
                                    file_size_limit: '1024 MB',
                                    upload_success_handler: function(file, response) {
                                        $("input[name$=_filename]", container).val(file.name);
                                        $("input[name$=_guid]", container).val(response).after('<span id="analysis"><a target="_blank" href="<?php echo $this->webroot; ?>upload/email_list/' + response + '">After the analysis of the results</a></span>');
                                        $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                                .replace("{0}", file.name)
                                                .replace("{1}", (file.size / 1024).toFixed(3))
                                                );
                                    }
                                });


                            });
        </script>
