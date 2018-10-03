<style>
   input{width: 220px;margin-bottom: 0;}
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/vendors"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Edit Vendor') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Edit Vendor') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>did/vendors"><i></i>
            <?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="container">
                <form id="myform" method="post" enctype="multipart/form-data">
                    <table class="table dynamicTable tableTools table-bordered  table-white form">
                        <col width="40%">
                        <col width="60%">
                        <tbody>
                            <tr>
                                <td class="right"><?php __('Vendor Name')?></td>
                                <td>
                                    <input type="text" id="vendor_name" name="name" value="<?php echo $client['name'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('IP Addresses')?></td>
                                <td>
                                    <input type="text" id="ip_orig" name="ip_addresses[]" value="<?php echo isset($client['resource_ips'][0]) ? $client['resource_ips'][0] : ''; ?>">
                                    <input type="text" name="ip_port[]" class="width40" maxlength="5" value="<?php echo isset($client['resource_ports'][0]) ? $client['resource_ports'][0] : ''; ?>">
                                    <a href="javascript:void(0)" id="add_ip">
                                        <i class="icon-plus"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td></td>
                                <td>
                                    <input type="text" name="ip_addresses[]">
                                    <input type="text" name="ip_port[]" class="width40" maxlength="5">
                                    <a href="javascript:void(0)" class="ip_delete">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                            $ip_cnt = count($client['resource_ips']);
                            if ($ip_cnt > 1):
                                ?>
                                <?php for ($i = 1; $i < $ip_cnt; $i++): ?>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input type="text" name="ip_addresses[]" value="<?php echo $client['resource_ips'][$i] ?>">
                                            <input type="text" name="ip_port[]" class="width40" maxlength="5" value="<?php echo $client['resource_ports'][$i] ?>">
                                            <a href="javascript:void(0)" class="ip_delete">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
                            <tr>
                                <td class="right"><?php __('Call Limit')?></td>
                                <td>
                                    <input type="text" name="call_limit" id="call_limit"  value="<?php echo $client['call_limit'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10">Enable T38</td>
                                <td>
                                <select name ="t38">
                                    <option value="true" <?php echo isset($client['t38']) && $client['t38'] ? 'selected': ''; ?>>True</option>
                                    <option value="false" <?php echo isset($client['t38']) && !$client['t38'] ? 'selected': ''; ?>>False</option>
                                </select>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Media Type')?></td>
                                <td>
                                    <select name="media_type">
                                        <option value="2" <?php if ($client['media_type'] == 2) echo 'selected="selected"' ?>><?php __('Bypass Media')?></option>
                                        <option value="1" <?php if ($client['media_type'] == 1) echo 'selected="selected"' ?>><?php __('Proxy Media')?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="right">
                                    <?php __('ADD DID'); ?>
                                </td>
                                <td>
                                    <input class="did_number_input validate[custom[integer]]" type="text" name="did_number[]" />
                                    <a href="javascript:void(0)" id="add_did_number">
                                        <i class="icon-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="widget" id="uploadDid" data-toggle="collapse-widget" data-collapse-closed="true">
                        <div class="widget-head"><h4 class="heading"><?php __('Upload DID') ?></h4></div>
                        <div class="widget-body">
                            <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                                <thead></thead>
                                <tr>
                                    <td class="align_right padding-r20"><?php echo __('Duplicate Handling') ?> </td>
                                    <td>
                                        <select name="duplicate_type">
                                            <option value="delete"><?php __('Overwrite')?></option>
                                            <option value="ignore"><?php __('Ignore')?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r20"><?php echo __('Upload File') ?> </td>
                                    <td>
                                        <input type="file" name="file" id="myfile" />
                                        <span id="analysis" style="display:block;">
                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r20"><?php echo __('Example') ?> </td>
                                    <td>
                                        <a target="_blank" href="<?php echo $this->webroot; ?>example/did_number_single.csv"><?php __('show')?></a>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>

                    <p style="text-align: center;">
                        <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                    </p>

                </form>
            </div>
        </div>
    </div>
</div>
<div id="dd" style="display:none;" class="center">
    <?php __('Name'); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="digit_name" name="name" />

</div>


<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">
    $(function() {

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
                        .replace("{1}", (file.size / 1024).toFixed(3))
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

<script>


    $(function() {
        var $add_ip = $('#add_ip');
        var $ip_delete = $('.ip_delete');
        var $myform = $('#myform');
        var $vendor_name = $('#vendor_name');
        var $add_did_number = $("#add_did_number");
        var $remove_inp = $('.remove-inp');


        $add_did_number.click(function() {
            var $this = $(this);
            var $parent = $this.closest('tr');
            var $clone = $parent.next().clone();
            $parent.after($clone);
            $clone.show();
        });

        $remove_inp.live('click', function() {
            $(this).parents('tr').remove();
        });

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

            var name_data = jQuery.ajaxData("<?php echo $this->webroot; ?>clients/check_name/" + name + '/<?php echo $client['client_id'] ?>');
            name_data = name_data.replace(/\n|\r|\t/g, "");
            if (name_data == 'false') {
                jQuery.jGrowlError(name + " is already in use!");
                flag = false;
            }
            var ip_orig = $("#ip_orig").val();
            if(! ip_orig)
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
