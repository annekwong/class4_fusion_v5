<style>
    input{width: 220px;margin-bottom: 0;}

    .hide-upload {
        display: none;
    }

    .hide-input {
        display: none;
    }
</style>
<form id="myform" method="post" enctype="multipart/form-data" class="add_vendor_dialog">
    <table class="table dynamicTable tableTools table-bordered  table-white form" style="font-size: 13px;color:rgb(124,124,124)">
        <col width="40%">
        <col width="60%">
        <tbody>
        <tr>
            <td class="right"><?php __('Vendor Name') ?></td>
            <td>
                <input type="text" id="vendor_name" class="validate[required]" name="name">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('IP Addresses') ?></td>
            <td>
                <input type="text" id="ip_orig" name="ip_addresses[]">/
                <select name="ip_mask[]" style="width: 60px;">
                    <?php for($i = 32; $i >= 24; $i--): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>
                <input style="width: 37px;" class="validate[custom[integer]]" value="5060" type="text" id="ip_port" name="ip_port[]"  class="width40" maxlength="5" >
                <a href="javascript:void(0)" id="add_ip">
                    <i class="icon-plus"></i>
                </a>
            </td>
        </tr>
        <tr style="display:none;">
            <td></td>
            <td>
                <input type="text" name="ip_addresses[]">/
                <select name="ip_mask[]" style="width: 60px;">
                    <?php for($i = 32; $i >= 24; $i--): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>
                <input type="text" name="ip_port[]" value="5060"  class="width40 validate[custom[integer]]" maxlength="5" >
                <a href="javascript:void(0)" class="ip_delete">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Call Limit') ?></td>
            <td>
                <input class="validate[custom[integer]]" type="text" name="call_limit" id="call_limit">
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10">Enable T38</td>
            <td>
                <select name ="t_38">
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
        </tr>
        <tr>
            <td class="right"><?php __('Media Type') ?></td>
            <td>
                <select name="media_type">
                    <option value="2"><?php __('Bypass Media') ?></option>
                    <option value="1"><?php __('Proxy Media') ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r20"><?php echo __('Round Up') ?></td>
            <td>
                <select name="rate_rounding">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Tech Prefix') ?></td>
            <td>
                <input type="text" name="tech_prefix">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('RPID') ?></td>
            <td>
                <select name="rpid" id="">
                    <option value="0">Never</option>
                    <option value="1">Pass through</option>
                    <option value="2">Always</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('PAID') ?></td>
            <td>
                <select name="paid" id="">
                    <option value="0">Never</option>
                    <option value="1">Pass through</option>
                    <option value="2">Always</option>
                </select>
            </td>
        </tr>
        <?php //if (!$is_ajax): ?>
        <tr>
            <td class="right">
                <?php __('DIDs'); ?>
            </td>
            <td>
                <input class="did_number_input validate[custom[integer]]" type="text" name="did_number[]" />
                <select name="billing_rules[]">
                    <option value="" disabled selected>Select Billing Rule</option>
                    <?php foreach ($routing_rules as $key => $item): ?>
                        <option value="<?php echo $key ?>"><?php echo $item ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="javascript:void(0)" id="add_did_number">
                    <i class="icon-plus"></i>
                </a>
            </td>
        </tr>
        <tr style="display:none;">
            <td></td>
            <td>
                <input class="did_number_input validate[custom[integer]]" type="text" name="did_number[]" />
                <select name="billing_rules[]">
                    <option value="" disabled selected>Select Billing Rule</option>
                    <?php foreach ($routing_rules as $key => $item): ?>
                        <option value="<?php echo $key ?>"><?php echo $item ?></option>
                    <?php endforeach; ?>
                </select>
                <a href="javascript:void(0)" class="remove-inp">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        <!--            <tr>-->
        <!--                <td class="right">-->
        <!--                    --><?php //__('Upload DID'); ?>
        <!--                </td>-->
        <!--                <td>-->
        <!--                    <input type="checkbox" id="is_upload" name="is_upload" />-->
        <!--                </td>-->
        <!--            </tr>-->
        <!--            <tr class="is_upload hide">-->
        <!--                <td style="text-align:right;">--><?php //__('Duplicate Handling'); ?><!--</td>-->
        <!--                <td>-->
        <!--                    <select name="duplicate_type">-->
        <!--                        <option value="delete">--><?php //__('Overwrite')?><!--</option>-->
        <!--                        <option value="ignore">--><?php //__('Ignore')?><!--</option>-->
        <!--                    </select>-->
        <!--                </td>-->
        <!--            </tr>-->
        <!--            <tr class="is_upload hide">-->
        <!--                <td style="text-align:right;">--><?php //__('Upload File'); ?><!--</td>-->
        <!--                <td>-->
        <!--                    <input type="file" name="file" id="myfile" />-->
        <!--                                    <span id="analysis" style="display:block;">-->
        <!--                                    </span>-->
        <!--                </td>-->
        <!--            </tr>-->
        <!--            <tr class="is_upload hide">-->
        <!--                <td style="text-align:right;">--><?php //__('Example'); ?><!--</td>-->
        <!--                <td>-->
        <!--                    <a target="_blank" href="--><?php //echo $this->webroot; ?><!--example/did_number_single.csv">--><?php //__('show')?><!--</a>-->
        <!--                </td>-->
        <!--            </tr>-->
        <?php //endif; ?>
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
        $(function() {

            var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

            var element = document.querySelector('#uploadDid');

            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type == "attributes") {
                        if (mutation.target.attributes['data-collapse-closed'].value == 'false') {
                            var uploader = document.createElement('input');
                            uploader.type = 'hidden';
                            uploader.name = 'is_upload';
                            uploader.id = 'is_upload'
                            element.appendChild(uploader);
                        } else {
                            var child = document.querySelector('#is_upload');
                            element.removeChild(child);
                        }
                    }
                });
            });

            observer.observe(element, {
                attributes: true //configure it to listen to attribute changes
            });
        });

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
            $parent.next().after($clone);
            $clone.show();
        });

        $ip_delete.live('click', function() {
            $(this).parents('tr').remove();
        });

        var $add_did_number = $("#add_did_number");
        var $remove_inp = $('.remove-inp');
        $add_did_number.click(function() {
            var $this = $(this);
            var $parent = $this.closest('tr');
            var $clone = $parent.next().clone();
            $parent.next().after($clone);
            $clone.show();
        });

        $remove_inp.live('click', function() {
            $(this).parents('tr').remove();
        });


        $myform.submit(function(e) {
            // check if billing rules selected
            var isContinue = true;

            $('.did_number_input').each(function (key, item) {
                if ($(item).val() != '' && $(item).next().val() == null) {
                    isContinue = false;
                    return false;
                }
            });

            if (!isContinue) {
                jQuery.jGrowlError("Please select billing rule for each DID!");
                return false;
            }

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
                jQuery.jGrowlError("The vendor " + name + " is already in use!");
                e.preventDefault();
                flag = false;
            }

            var ip_orig = $("#ip_orig").val();
            if (!ip_orig)
            {
                jQuery.jGrowlError("IP can not be empty!");
                return false;
            }

            var ip_port = $("#ip_port").val();
            if (!ip_port)
            {
                jQuery.jGrowlError("PORT can not be empty!");
                return false;
            }

            if(!ip_orig.match(/^((\d\d?|1\d\d|2([0-4]\d|5[0-5]))\.){3}(\d\d?|1\d\d|2([0-4]\d|5[0-5]))$/)){
                jQuery.jGrowlError("Wrong IP Adress format!");
                return false;
            }

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

        $('#billing_type').change(function () {
            if($(this).val() == 2) {
                $(".hide-upload").show();
            } else {
                $(".hide-upload").hide();
            }
            $(document).on('click', '.remove-inp', function(){
                $(this).parent().parent().remove();
            });

        });
    });
</script>
