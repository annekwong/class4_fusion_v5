<style type="text/css">
    #note_window {
        border:1px solid #ccc;
        border-radius: 15px;
        background:#fff;
        max-width:500px;
        max-height: 200px;
        width:500px;
        height:200px;
        display:none;
    }

    #note_window p {
        padding:10px;
    }

    #note_window h1 {
        text-align:right;
        padding-right:20px;
        paddign-top:10px;
    }
    .list .jsp_resourceNew_style_2 tbody td {font-size: 12px;}
    .list .jsp_resourceNew_style_2 tbody td:hover {font-size: 12px;}
</style>

<?php echo $this->element('magic_css_three'); ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('payment') ?></li>
</ul>

<?php
if (!isset($this->params['pass'][0]))
{
    $this->params['pass'][0] = 'incoming';
}
?>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('payment') ?></h4>
    
</div>
<div class="separator bottom"></div>
    <div class="clearfix"></div>
<?php $action = isset($_SESSION['sst_statis_smslog']) ? $_SESSION['sst_statis_smslog'] : '';
$w = isset($action['writable']) ? $action['writable'] : '';
?>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if (!isset($this->params['pass'][0]) || $this->params['pass'][0] == 'incoming') echo 'class="active"'; ?>>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot; ?>transactions/payment/incoming">
                        <i></i><?php echo __('Received', true); ?>				
                    </a>
                </li>
                <li <?php if (isset($this->params['pass'][0]) && $this->params['pass'][0] == 'outgoing') echo 'class="active"'; ?>>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot; ?>transactions/payment/outgoing">
                        <i></i><?php echo __('Sent', true); ?>			
                    </a>  
                </li>
                <li <?php if (isset($this->params['pass'][0]) && $this->params['pass'][0] == 'upload') echo 'class="active"'; ?>>
                    <a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot; ?>transactions/payment/upload">
                        <i></i><?php echo __('Upload', true); ?>			
                    </a>  
                </li>
                <li>
                    <a class="glyphicons no-js book_open" href="<?php echo $this->webroot; ?>payment_history">
                        <i></i><?php echo __('Auto Payment Log', true); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <form id="form1" class="form-inline" action="<?php echo $this->webroot?>uploads/analysis_file_head" method="POST" enctype="multipart/form-data">
                <div id="static_div" style="text-align: left; width: 530px;">
                    <table class="cols" style="width: 252px; margin: 0px auto;"></table>
                </div>
                <table class="cols table dynamicTable tableTools table-bordered  table-white" style="margin:0px auto;">
                    <colgroup>
                        <col width="37%">
                        <col width="63%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File', true); ?>:</td>
                            <td style="text-align:left;" class="last">
                                <input id="myfile" type="file" name="file">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Records with duplicated ID', true); ?>:</td>
                            <td style="text-align:left;" class="last">
                                <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" class="">
                                <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label>			  
<!--					<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
                                <label for="duplicate_type_overwrite"><?php echo __('Overwrite', true); ?></label>			  -->
                                <input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete" checked="checked">
                                <label for="duplicate_type_delete"><?php echo __('Overwrite', true); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="first" style="text-align:right;padding-right:4px;">Data Type:</td>
                            <td style="text-align:left;" class="last">
                                <input type="hidden" name="show_type" id="show_type" value="16" />
                                <input type="radio" checked="checked" id="upload_type_1" value="1" name="upload_type" class="border_no">
                                <label for="upload_type_1">Payment Sent</label>
                                <input type="radio" id="upload_type_2" value="2" name="upload_type" class="border_no">
                                <label for="upload_type_2">Payment Received</label>
                            </td>
                        </tr>
<!--                        <tr>-->
<!--                            <td style="text-align:right;padding-right:4px;" class="first"></td>-->
<!--                            <td style="text-align:left;" class="last"   align="center"><span id="analysis_myfile" class="analysis" style="display:block;"></span></td>-->
<!--                        </tr>-->

                        <tr >
                            <td style="text-align:right;padding-right:4px;" class="first" >
                                <?php __("Include Header") ?>:
                            </td>
                            <td style="text-align:left;" class="last">
                                <input checked="checked" type="checkbox" name="with_header" class="border_no" id="include_header"><br>

                            </td>
                        </tr>
                        <tr id="from_line" style="display: none;">
                            <td style="text-align:right;padding-right:4px;" class="first">
                                <?php __("Starting From Line") ?>:
                            </td>
                            <td style="text-align:left;" class="last">
                                <select name="from_line">
                                    <?php for($i=1;$i<=20;$i++): ?>
                                        <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php endfor;?>
                                </select>
                            </td>
                        </tr>
                        <tr id="date_format">
                            <td style="text-align:right;padding-right:4px;" class="first">
                                <?php __("Date Format") ?>:
                            </td>
                            <td style="text-align:left;" class="last">
                                <select name="date_format">
                                    <option value="mm/dd/yyyy">mm/dd/yyyy</option>
                                    <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                                    <option value="dd-mm-yyyy">dd-mm-yyyy</option>
                                    <option value="dd/mm/yyyy">dd/mm/yyyy</option>
                                    <option value="yyyy/mm/dd">yyyy/mm/dd</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td style="text-align:right;padding-right:4px;"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/payment.csv" target="_blank" id="show_example" title=""><?php __('show')?></a></td></tr>
                        <tr>
                            <td colspan="2" style="text-align:right;padding-right:4px;" class="first last"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="first last"><div class="submit center"><input type="submit" value="<?php echo __('upload', true); ?>" class="input in-submit btn btn-primary"></div></td>
                        </tr>	
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<div id="note_window">
    <h1>
        <a href="###" id="note_window_close">
            <i class='icon-remove'></i>
        </a>
    </h1>
    <p>

    </p>
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
            upload_success_handler: function(file, response) {
                var flg = "myfile";
                var show_type = $("#show_type").val();
                var container = $('#content');
                $("#analysis").empty();
                $("input[name=" + flg + "_filename]", container).val(file.name);

                $("input[name=" + flg + "_guid]", container).val(response);
                $("input[name=flg]", container).val(flg);
                //$("#analysis_" + flg).html('<a id="analysis_a" target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/' + show_type + '/' + response + '">Show and modify</a>');
                $("span[id=" + flg + "_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                        );
            }
        });
        $("#form1").submit(function() {
            var file = $("#myfile_completedMessage").html();
            if (!file)
            {
                jQuery.jGrowlError('You should select a file!');
                return false;
            } else {
                return true;
            }

        });
        $("input[name='upload_type']").click(function() {
            var flg = $(this).val();
            var type = '';
            $("#show_type").val('16');
            var show_type = '16';
            type = 'payment.csv';
            var response = $("input[name=myfile_guid]").val();

            switch (flg)
            {
                case '3' :
                    type = 'invoice.csv';
                    $("#show_type").val('17');
                    show_type = '17';
                    break;
            }
            $("#analysis_a").attr('href', "<?php echo $this->webroot; ?>uploads/analysis_file/"+show_type+"/" + response);
            var href = "<?php echo $this->webroot ?>example/" + type;
            $("#show_example").attr('href', href);
        });

        $("#include_header").click(function(){
            var is_from_line = $('#from_line');
            //var is_date_format = $('#date_format');
            if ($("#include_header").attr("checked")){
                is_from_line.show();
                //is_date_format.show();
            } else {
                is_from_line.hide();
                //is_date_format.hide();
            }
        }).trigger('click');
    });
</script>
