<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Logo') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Logo')?></h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>systemparams/view">
            <i></i>
            <?php __('Back')?>
        </a>
    </div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <div style="margin:0 auto;">







                <form enctype="multipart/form-data" name="ilogo_form" id="ilogo_form" method="post" action="<?php echo $this->webroot ?>logos/index">
                    <table class="list table dynamicTable tableTools table-bordered  table-white">
                        <tr>
                            <td><?php echo __('Current Logo', true); ?>:<img width="120px" height="45px" src="<?php echo $this->webroot; ?>logos/ilogo"></td>
                            <td>
                                <input type="file" id="ilogo" name="ilogo" class="input in-file">   
                            </td>
                            <td>
                                <input type="hidden" name="upload" value="upload"/>
                                <?php if ($_SESSION['role_menu']['Configuration']['logos']['model_x']) { ?>
                                    <input type="button"class="btn btn-primary" value="<?php echo __('Save', true); ?>" onclick="file_upload();"/>
                                <?php } ?>
                            </td>

                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<script language="Javascript">
    function file_upload()
    {
        if (confirm("Are You Sure?"))
        {
            document.ilogo_form.submit();
        }
    }
</script>