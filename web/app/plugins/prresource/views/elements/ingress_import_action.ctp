<div style="height:10px"></div>
<form id="form3" class="form-inline" action="<?php echo $this->webroot ?>uploads/ingress_action" method="POST" enctype="multipart/form-data">
    <div id="static_div" style="text-align: left; width: 530px;">
        <table class="cols" style="width: 252px; margin: 0px auto;">

        </table>
    </div>
    <table class="cols" style="width:700px;margin:0px auto;">
        <tbody><tr>
                <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File', true); ?>:</td>
                <td style="text-align:left;" class="last"><input type="file" name="file" id="myfile3"></td>
            </tr>
            <tr>
                <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Duplicate', true); ?>:</td>
                <td style="text-align:left;" class="last">
                    <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore">
                    <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label>			  
<!--			<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
                    <label for="duplicate_type_overwrite"><?php echo __('Overwrite', true); ?></label>			  -->
                    <input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"  checked="checked">
                    <label for="duplicate_type_delete"><?php echo __('delete', true); ?></label>
                </td>
            </tr>
            <tr><td colspan="2"  align="center"><span id="analysis_myfile3" class="analysis" style="display:block;"></span></td></tr>
            <tr><td align="right"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/resource_action.csv" target="_blank" title="Show example file"><?php __('show')?></a></td></tr>
            <tr>
                <td style="text-align:right;padding-right:4px;" class="first last"></td>
            </tr>
            <tr style="height:10px"><td colspan=2></td></tr>
            <tr>
                <td colspan="2" class="first last center"><div class="submit"><input type="submit" value="<?php echo __('upload', true); ?>" class="input in-submit btn btn-primary"></div></td>
            </tr>	
        </tbody></table>
</form>
<script type="text/javascript">
    $(function() {
        $("#form3").submit(function() {
            var file = $("#myfile3_completedMessage").html();

            if (!file)
            {
                jQuery.jGrowlError('You should select a file!');
                return false;
            } else {
                return true;
            }

        });

    });


</script>