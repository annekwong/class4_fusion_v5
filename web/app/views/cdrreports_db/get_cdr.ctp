<?php if (!$this->params['isAjax']): ?>
    <ul class="breadcrumb">
        <li><?php __('You are here')?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php __('Tools') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php echo __('CDR Archive') ?></li>
    </ul>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php endif; ?>
            <table class="form footable table tableTools table-bordered  table-white default ">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Search Process'); ?>
                    </td>
                    <td>
                        <p style="color: red" class="process_msg">query init</p>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Search Status'); ?>
                    </td>
                    <td>
                        <p style="color: red" class="result_msg"></p>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Download CDR'); ?>
                    </td>
                    <td>
                        <input type="button" disabled="disabled" class="download_btn btn btn-primary" style="width: 120px;cursor:not-allowed" value="<?php __('Download'); ?>" />
                        <input type="hidden" class="session_key" />
                        <input type="hidden" class="token" />
                        <input type="hidden" class="download_url" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Generate CDR Public Download Link'); ?>
                    </td>
                    <td class="generate_td">
                        <input disabled="disabled" class="generate_btn btn btn-primary" style="width: 120px;cursor:not-allowed" type="button"  value="<?php __('Generate'); ?>" />
                    </td>
                </tr>
            </table>
            <?php if (!$this->params['isAjax']): ?>
        </div>
    </div>
</div>
<?php endif; ?>
<script type="text/javascript">
    $(function(){

        $.ajax({
            'url' : '<?php echo $this->webroot ?>cdrreports_db/ajax_get_cdr?<?php echo $this->params['getUrl'] ?>',
            'type' : 'get',
            'dataType' : 'json',
            'success' : function(data) {
                $("p.process_msg").html(data.msg);
                if (data.status == 1){
                    $("p.result_msg").html('<?php __('Successfully'); ?>');
                    $(".download_btn,.generate_btn").removeAttr('disabled').css('cursor','pointer');
                    $(".session_key").val(data.session_key);
                    $('.download_url').val(data.download_url);
                    $('.token').val(data.token);
                }else{
                    $("p.result_msg").html('<?php __('Failed'); ?>');
                }

            }
        });


        $(".download_btn").click(function(){
            var download_url = $(".download_url").val();
            if (!download_url){
                jGrowl_to_notyfy($("p.process_msg").html(),{'theme':'jmsg-error'});
                return false;
            }
            var token = $(".token").val();
            var session_key = $(".session_key").val();
            var url = "<?php echo $this->webroot; ?>cdrreports_db/cdr_download?token="+token + '&session_key=' + session_key + '&<?php echo $this->params['getUrl'] ?>';
            window.open(url);
        });

        $(".generate_btn").click(function(){
            var token = $(".token").val();
            if (!token){
                jGrowl_to_notyfy($("p.process_msg").html(),{'theme':'jmsg-error'});
                return false;
            }
            var session_key = $(".session_key").val();
            $.ajax({
                'url' : '<?php echo $this->webroot ?>cdrreports_db/ajax_get_public_cdr_download',
                'type' : 'get',
                'dataType' : 'json',
                'data': {'session_key': session_key,'token': token},
                'success' : function(data) {
                    $("p.process_msg").html(data.msg);
                    if (data.status == 1){
                        $(".generate_td").html(data.url);
                    }else{
                        $("p.result_msg").html('<?php __('Failed'); ?>');
                    }

                }
            });
        });
    })
</script>