<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('block_list')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Import')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Upload just ANI / DNIS')?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Import')?></h4>

</div>
<div class="separator bottom"></div>

<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li class="active"><a class="glyphicons list" href="<?php echo $this->webroot ?>blocklists/index"><i></i> <?php echo __('List', true); ?></a></li>
                <li class="active"> <a class="glyphicons upload" href="<?php echo $this->webroot ?>blocklists/upload_number"><i></i> <?php echo __('Import', true); ?></a></li>
                <li><a  class="glyphicons download" href="<?php echo $this->webroot ?>down/block"><i></i> <?php echo __('Export', true); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">


            <?php if (isset($exception_msg) && $exception_msg) : ?>
                <?php echo $this->element('common/exception_msg'); ?>		
            <?php endif; ?>
            <ul class="">   
                <li class="glyphicons">
                    <a class="btn btn-primary" href="<?php echo $this->webroot; ?>uploads/block_list"><?php __('Complete Field Upload')?></a>
                </li>
                <li class="glyphicons">
                    <a class="btn btn-primary disabled"><?php __('Upload just ANI / DNIS')?></a>
                </li>
            </ul>
            <form id="improt_form" action="" method="POST" enctype="multipart/form-data">
                <div  id="static_div"   style="text-align: left; width: 530px;">
                    <table class="cols" style="width: 252px; margin: 0px auto;"  >
                        <?php if (isset($statistics) && $statistics) : ?>
                            <caption><?php echo __('Upload Statistics', true); ?>    

                                <span style="color: red;;font-size:11px;"> </span>
                            </caption>
                            <?php foreach (array('success', 'failure', 'duplicate') as $col): ?>
                                <?php if (isset($statistics[$col])): ?>
                                    <tr>
                                        <td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize($col) ?>:</td>
                                        <td style="text-align:left;color:red;"><?php echo $statistics[$col] ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php
                            if (isset($statistics['failure']) && $statistics['failure'] > 0 &&
                                    isset($statistics['error_file']) && !empty($statistics['error_file']) &&
                                    isset($statistics['log_id']) && $statistics['log_id'] > 0
                            ):
                                ?>
                                <tr>
                                    <td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize("error_file") ?>:</td>
                                    <td style="text-align:left;"><a href="<?php echo $this->webroot ?>uploads/download_error_file/<?php echo $statistics['log_id'] ?>"><?php echo __('download', true); ?></a></td>
                                </tr>
                            <?php endif; ?>
                            <tr><td>&nbsp;</td><td></td></tr>
                            <tr><td>&nbsp;</td><td></td></tr>
                        <?php endif; ?>

                    </table>
                </div>
                <table class="cols" style="width:700px;margin:0px auto;" cellpadding="10">
                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php echo __('Import File', true); ?>:</td>
                        <td style="text-align:left;">
                            <input type="file" id="myfile" name="file" />
                            <span id="analysis" style="display:block;">

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php __('Number Type')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="number_type" id="number_type">
                                <option value="1"><?php __('ANI')?></option>
                                <option value="2"><?php __('DNIS')?></option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php __('Clients Type')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="carrier_type" id="carrier_type">
                                <option value="1"><?php __('Termination')?></option>
                                <option value="2"><?php __('Origination')?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php __('Type')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="type" id="type">
                                <option value="1"><?php __('Block By Trunk')?></option>
                                <option value="2"><?php __('Block By Trunk Group')?></option>
                            </select>
                        </td>
                    </tr>

                    <tr class="termination_select">
                        <td style="text-align:right;padding-right:4px;"><?php __('Carrier')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="egress_carrier" id="egress_carrier" class="client_options_egress">
                                <option value=""></option>
                                <?php foreach ($egress_clients as $id => $name)
                                { ?>
                                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="termination_select">
                        <td style="text-align:right;padding-right:4px;"><?php __('Egress Trunk')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="egress" id="egress" class="trunk_options_egress">
                                <?php foreach ($ingress_trunks as $id => $name)
                                { ?>
                                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="egress_group" style="display: none;">
                        <td style="text-align:right;padding-right:4px;">
                            Egress Trunk Group:
                        </td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="egress_group_id" id="egress_group_id">
                                <option value=""></option>
                                <?php foreach ($egress_group as $item)
                                { ?>
                                    <option value="<?php echo $item['TrunkGroup']['group_id'] ?>"><?php echo $item['TrunkGroup']['group_name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="origination_select" style="display:none;">
                        <td style="text-align:right;padding-right:4px;"><?php __('Carrier')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="ingress_carrier" id="ingress_carrier" class="client_options_ingress">
                                <option value=""></option>
                                <?php foreach ($ingress_clients as $id => $name)
                                { ?>
                                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="ingress_group" style="display: none;">
                        <td style="text-align:right;padding-right:4px;">
                            Ingress Trunk Group:
                        </td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="ingress_group_id" id="ingress_group_id">
                                <option value=""></option>
                                <?php foreach ($ingress_group as $item)
                                { ?>
                                    <option value="<?php echo $item['TrunkGroup']['group_id'] ?>"><?php echo $item['TrunkGroup']['group_name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr class="origination_select" style="display:none;">
                        <td style="text-align:right;padding-right:4px;"><?php __('Ingress Trunk')?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <select name="ingress" id="ingress" class="trunk_options_ingress">
<?php foreach ($ingress_trunks as $id => $name)
{ ?>
                                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                    <?php } ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr><td align="right"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/block_only_number.csv" target="_blank" title="Show example file"><?php __('show')?></a></td></tr>
                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php echo __('Method', true); ?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" />
                            <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label><!--			  
                            <input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite"/>
                            <label for="duplicate_type_overwrite">Overwrite</label>			  
                            --><input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"     checked="checked"/>
                            <label for="duplicate_type_delete"><?php echo __('delete', true); ?></label>
                        </td>
                    </tr>
                        <tr class="center">
                            <td colspan="2"><?php echo $form->submit('upload', array('class' => 'btn btn-primary', 'id' => 'upload_submit')) ?></td>
                        </tr>	
                </table>
            </form>
        </div>
    </div>
</div>
<?php
if (!empty($statistics['log_id']))
{
    ?>
    <script  type="text/javascript">

        (function(div_id, status) {
            var _div_id = $(div_id);
            var _status = 0;
            var _timeoutHander = null;


            var test = function() {
                _timeoutHander = setTimeout(doStartCap, 2000);
            }
            var doStartCap = function() {

                $.post('<?php echo $this->webroot ?>uploads/get_upload_log?id=<?php echo $statistics['log_id']; ?>', {},
                                    function(data) {
                                        var s = data.substring(0, 2);
                                        //if(/\d/.test(s)){
                                        _div_id.html(data.substring(2));
                                        if (s == 6) {
                                            clearTimeout(_timeoutHander);

                                        }
                                        //}
                                        _timeoutHander = setTimeout(doStartCap, 2000);
                                    }
                            );

                        }


                        jQuery(document).ready(doStartCap);


                    })('#static_div', '#upload_status');
    </script>

<?php } ?>

<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>


<script type="text/javascript">
                $(function() {

                    function change() {
                        var carrier_type = $("#carrier_type").val();
                        var type = $("#type").val();

                        $(".termination_select").hide();
                        $(".origination_select").hide();
                        $(".ingress_group").hide();
                        $(".egress_group").hide();

                        if (carrier_type == 1)
                        {
                            if (type == 1) {
                                $(".termination_select").show();
                            } else {
                                $(".egress_group").show();
                            }
                        }
                        else
                        {
                            if (type == 1) {
                                $(".origination_select").show();
                            } else {
                                $(".ingress_group").show();
                            }
                        }
                    }

                    $("#carrier_type").change(change);
                    $("#type").change(change);

                    $("#improt_form").submit(function() {
                        var file = $("#myfile_completedMessage").html();
                        if (!file)
                        {
                            jQuery.jGrowlError('You should select a file!');
                            return false;
                        } else {
                            return true;
                        }

                    });

                    $('#custom_date').hide();
                    $('#is_custom_enddate').click(function() {
                        if ($(this).attr('checked')) {
                            $('#custom_date').show();
                        } else {
                            $('#custom_date').hide();
                        }
                    });

                    $('.client_options_ingress').live('change', function() {
                        var $this = $(this);
                        value = $this.val();
                        var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=ingress&trunk_type2=0'});
                        data = eval(data);
                        var temp1 = $('.trunk_options_ingress').val();

                        $('.trunk_options_ingress').html('');
                        jQuery('<option>').appendTo($('.trunk_options_ingress'));
                        for (var i in data) {
                            var temp = data[i];
                            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($('.trunk_options_ingress'));
                        }
                        $('.trunk_options_ingress').val(temp1);
                    });

                    $('.client_options_egress').live('change', function() {
                        var $this = $(this);
                        value = $this.val();
                        var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=egress&trunk_type2=0'});
                        data = eval(data);
                        var temp1 = $('.trunk_options_ingress').val();

                        $('.trunk_options_egress').html('');
                        jQuery('<option>').appendTo($('.trunk_options_egress'));
                        for (var i in data) {
                            var temp = data[i];
                            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($('.trunk_options_egress'));
                        }
                        $('.trunk_options_egress').val(temp1);
                    });


                    $("#myfile").makeAsyncUploader({
                        upload_url: '<?php echo $this->webroot ?>uploads/async_upload/b_number',
                        flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
                        button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
                        post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
                        file_size_limit: '1024 MB',
                        upload_success_handler: function(file, response) {
                            var container = $('#content');
                            $("#analysis").empty();
                            $("input[name$=_filename]", container).val(file.name);
                            $("input[name$=_guid]", container).val(response);
                            $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                    .replace("{0}", file.name)
                                    .replace("{1}", (file.size / 1024).toFixed(3))
                                    );
                        }
                    });
                });
</script>
