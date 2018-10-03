<style>
    select, input[type="text"], input[type="password"]{width: 220px;margin-bottom: 0;}
</style>
<form id="myform" method="post" enctype="multipart/form-data">
    <table class="table dynamicTable tableTools table-bordered  table-white form" style="font-size: 13px;color:rgb(124,124,124)">
        <col width="40%">
        <col width="60%">
        <tbody>
        <tr>
            <td class="right"><?php __('Vendor Name') ?></td>
            <td>
                <input type="text" id="vendor_name" name="name">
            </td>
        </tr>
        <!--                            <tr>
                <td class="right"><?php __('Login Username') ?></td>
                <td>
                    <input type="text"  class="validate[required]" name="login_username" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td class="right"><?php __('Login Password') ?></td>
                <td>
                    <input type="password" name="login_password" autocomplete="off">
                </td>
            </tr>-->
        <?php if (!$is_ajax): ?>
            <tr>
                <td class="right"><?php __('Pricing Rule') ?></td>
                <td>
                    <select name="pricing_rule" id="pricing_rule">
                        <?php
                        foreach ($routing_rules as $key => $item):
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a href="#myModal_pricing_rule" class="add_pricing_rule" data-toggle="modal">
                        <i class="icon-plus"></i>
                    </a>
                </td>
            </tr>
        <?php else: ?>
            <tr class="hidden"><td><input type="hidden" name="pricing_rule" value="0"  /></td><td></td></tr>
        <?php endif; ?>
        <tr>
            <td class="right"><?php __('IP Addresses') ?></td>
            <td>
                <input type="text" id="ip_orig" name="ip_addresses[]">
                <a href="###" id="add_ip">
                    <i class="icon-plus"></i>
                </a>
            </td>
        </tr>
        <tr style="display:none;">
            <td></td>
            <td>
                <input type="text" name="ip_addresses[]">
                <a href="###" class="ip_delete">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Call Limit') ?></td>
            <td>
                <input type="text" name="call_limit" id="call_limit">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Media Type') ?></td>
            <td>
                <select name="media_type">
                    <option value="2"><?php __('Bypass Media') ?></option>
                    <option value="1"><?php __('Proxy Media') ?></option>
                    <option value="0"><?php __('Transcoding media') ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Auto Invoicing') ?></td>
            <td>
                <input type="checkbox" name="auto_invoicing">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Digit Mapping') ?></td>
            <td>
                <select name="digit_mapping" id="digit_mapping">
                    <option></option>
                    <?php foreach ($digit_mappings as $digit_mapping): ?>
                        <option value="<?php echo $digit_mapping[0]['translation_id'] ?>"><?php echo $digit_mapping[0]['translation_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="javascript:void(0)" id="add_digit">
                    <i class="icon-plus"></i>
                </a>
                <a id="refresh_digit" href="javascript:void(0)" title="Refresh">
                    <i class="icon-refresh"></i>
                </a>
            </td>
        </tr>
        <?php if (!$is_ajax): ?>
            <tr>
                <td class="right">
                    <?php __('Upload DID'); ?>
                </td>
                <td>
                    <input type="checkbox" id="is_upload" name="is_upload" />
                </td>
            </tr>
            <tr class="is_upload hide">
                <td style="text-align:right;"><?php __('Duplicate Handling'); ?></td>
                <td>
                    <select name="duplicate_type">
                        <option value="delete"><?php __('Overwrite')?></option>
                        <option value="ignore"><?php __('Ignore')?></option>
                    </select>
                </td>
            </tr>
            <tr class="is_upload hide">
                <td style="text-align:right;"><?php __('Upload File'); ?></td>
                <td>
                    <input type="file" name="file" id="myfile" />
                                    <span id="analysis" style="display:block;">
                                    </span>
                </td>
            </tr>
            <tr class="is_upload hide">
                <td style="text-align:right;"><?php __('Example'); ?></td>
                <td>
                    <a target="_blank" href="<?php echo $this->webroot; ?>example/did_number_single.csv"><?php __('show')?></a>
                </td>
            </tr>

            <tr style="text-align:center;">
                <td colspan="5" class="button-groups center input in-submit">
                    <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</form>

<div id="dd" style="display:none;" class="center">
    <?php __('Name'); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="digit_name" name="name" />

</div>
<?php if (!$is_ajax): ?>
<div id="myModal_pricing_rule" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Create New Pricing Rule'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default close_btn"><?php __('Close'); ?></a>
    </div>
</div>

    <script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
    <script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
    <script type="text/javascript">
        function refresh_billing_rule(opt,selected) {
            $.ajax({
                'url': '<?php echo $this->webroot; ?>did/wizard/get_billing_rule',
                'type': 'GET',
                'dataType': 'json',
                'success': function(data) {
                    opt.empty();
                    $.each(data, function(index, item) {
                        if(selected == item[0]['id']){
                            opt.append('<option value="' + item[0]['id'] + '" selected="selected">' + item[0]['name'] + '</option>');
                        }else{
                            opt.append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                        }
                    });
                }
            });
        }
        $(function() {
            $("#myModal_pricing_rule").on('shown',function(){
                $(this).find('.modal-body').load('<?php echo $this->webroot ?>did/wizard/ajax_add_billing_rule');
            });
            $("#myModal_pricing_rule").find('.sub').click(function(){
                $.ajax({
                    url: "<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel",
                    type: 'post',
                    dataType: 'text',
                    data: $('#form1').serialize(),
                    success: function(data) {
                        if(data){
                            refresh_billing_rule($("#pricing_rule"),data);
                            jGrowl_to_notyfy('<?php __('Create succeed!'); ?>', {theme: 'jmsg-success'});
                            $("#myModal_pricing_rule").find('.close').click();
                        }else{
                            jGrowl_to_notyfy('<?php __('Create failed!'); ?>', {theme: 'jmsg-error'});
                        }
                    }
                });
            });

            $("#myfile").makeAsyncUploader({
                upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
                flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
                button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
                post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
                file_size_limit: '1024 MB',
                upload_success_handler: function (file, response) {
                    $("#analysis").empty();
                    $("input[name$=_filename]", container).val(file.name);
                    $("input[name$=_guid]", container).val(response);
                    $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/14/' + response + '">Show and modify</a>');
                    $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                            .replace("{0}", file.name)
                            .replace("{1}", Math.round(file.size / 1024))
                    );
                }
            });


            $("#is_upload").click(function(){
                if($(this).is(":checked"))
                    $(".is_upload").show();
                else
                    $(".is_upload").hide();
            });
        });

    </script>
<?php endif; ?>
<script type="text/javascript">


    $(function() {
        var $add_ip = $('#add_ip');
        var $ip_delete = $('.ip_delete');
        var $myform = $('#myform');
        var $vendor_name = $('#vendor_name');



        $add_ip.click(function() {
            var $this = $(this);
            var $parent = $this.parents('tr');
            var $clone = $parent.next().clone();
            $parent.after($clone);
            $clone.show();
        });

        $ip_delete.live('click', function() {
            $(this).parents('tr').remove();
        });

        $myform.submit(function() {
            // check if exists client name
            var name = $vendor_name.val();
            if (!name)
            {
                jQuery.jGrowlError("Name can not be empty!");
                return false;
            }
            var flag = true;

            var name_data = jQuery.ajaxData("<?php echo $this->webroot; ?>clients/check_name/" + name);
            name_data = name_data.replace(/\n|\r|\t/g, "");
            if (name_data == 'false') {
                jQuery.jGrowlError(name + " is already in use!");
                flag = false;
            }

            var ip_orig = $("#ip_orig").val();
            if (!ip_orig)
            {
                jQuery.jGrowlError("IP can not be empty!");
                return false;
            }

//            var call_limit = $("#call_limit").val();
//            if(! call_limit)
//            {
//                jQuery.jGrowlError("Call limit can not be empty!");
//                return false;
//            }

            return true;
        });

        $("#refresh_digit").click(function() {
            $.ajax({
                'url': '<?php echo $this->webroot ?>digits/ajax_refresh_digits',
                'type': 'POST',
                'dataType': 'json',
                'success': function(data) {
                    $("#digit_mapping").html("");
                    $.each(data, function(index, item) {
                        $("#digit_mapping").append("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    });
                }
            });
        });

        $("#add_digit").click(function() {
            $dd = $("#dd");
            $dd.dialog({
                'title': "<?php __('Create new Digit Mapping'); ?>",
                'width': '450px',
                'height': 200,
                'create': function(event, ui) {
                    $form = $('form', $dd);
                    $form.validationEngine();
                },
                'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                    var digit_name = $("#digit_name").val();
                    var reg = /^[0-9a-zA-Z_]+$/;
                    if (!reg.test(digit_name))
                    {
                        $("#digit_name").addClass('invalid');
                        jGrowl_to_notyfy('<?php __('Name,allowed characters:a-z,A-Z,0-9,-,_,space!'); ?>', {theme: 'jmsg-error'});
                    }
                    else
                    {
                        $.ajax({
                            'url': '<?php echo $this->webroot ?>digits/ajax_save_digits',
                            'type': 'POST',
                            'data': "digit_name=" + digit_name,
                            'success': function(data) {
                                if (data == 1)
                                {
                                    $("#refresh_digit").click();
                                }
                                else
                                {
                                    jGrowl_to_notyfy('<?php __('Create new Digit Mapping failed'); ?>', {theme: 'jmsg-error'});
                                }
                            }
                        });
                    }
                    $("#digit_name").val("");
                    $(this).dialog("close");
                }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                    $(this).dialog("close");
                }}]

            });
        });

    });
</script>