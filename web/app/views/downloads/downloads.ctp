<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Edit <?php echo isset($action) ? $action : __($this->params['action'], true); ?> <font class="editname"><?php echo @empty($name[0][0]['name']) || $name[0][0]['name'] == '' ? '' : "[" . $name[0][0]['name'] . "]" ?></font></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Export') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php  __('Export') ?> </h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php if (isset($back_url) && !empty($back_url)): ?>
            <a class="link_back btn btn-default btn-inverse btn-icon glyphicons left_arrow" href="<?php echo $back_url ?>"><i></i> <?php echo __('Back', true); ?></a>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
            <div class="widget-head"><h4 class="heading">
            <?php echo $this->element('downloads/' . $this->params['action'] . '_tabs') ?></h4>
        </div>
        <div class="widget-body">
            <?php if (isset($exception_msg) && $exception_msg) : ?>
                <?php echo $this->element('common/exception_msg'); ?>		
            <?php endif; ?>

            <form action="" method="POST">
                <table class="cols" style="width:700px;margin:0px auto;">
                    <tr>
                        <td class="first" style="width:50%;" valign="top">
                            <fieldset>
                                <legend><?php echo __('Format Options', true); ?></legend>				
                                <table>
                                    <?php echo $this->element('downloads/' . $this->params['action']) ?>
                                    <?php if ($this->params['action'] == 'carrier') {
                                        
                                    } else { ?>
                                        <tr>
                                            <td style="text-align:right;padding-right:4px;"><?php echo __('download', true); ?>:</td>
                                            <td style="text-align:left;"><?php echo $form->select('', array('effect' => 'Currently Effective', 'all' => "All"), null, array('name' => "type", 'style' => 'width:160px;'), false) ?></td>
                                             <!--<td style="text-align:left;"><?php echo $form->select('', array('all' => "All"), null, array('name' => "type", 'style' => 'width:160px;'), false) ?></td>-->
                                        </tr>
<?php } ?>
                                    <tr>
                                        <td style="text-align:right;padding-right:4px;"><?php echo __('Data Format', true); ?>:</td>
                                        <td style="text-align:left;"><?php echo $form->select('', array('csv' => __("CSV",true), 'xls' => __('XLS',true)), null, array('name' => "format", 'style' => 'width:160px;'), false) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;padding-right:4px;"></td>
                                        <td style="text-align:left;"><input name="with_headers" type="checkbox" id="checkbox_with_headers" checked="checked"><label for="checkbox_with_headers"><?php echo __('With headers row', true); ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;padding-right:4px;"><?php echo __('Header Text', true); ?>:</td>
                                        <td style="text-align:left;"><textarea name="header" style="width:100%"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;padding-right:4px;"><?php echo __('Footer Text', true); ?>:</td>
                                        <td style="text-align:left;"><textarea name="footer" style="width:100%"></textarea></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                        <td class="last"  style="width:50%">
                            <fieldset>
                                <legend><?php echo __('Columns', true); ?></legend>
                                <?php if(isset($default_fields) && !empty($default_fields)) :?>
                                    <?php echo $this->element('common/download_rate_columns', ['default_fields' => $default_fields]) ?>
                                <?php else:?>
                                    <?php echo $this->element('common/download_columns') ?>
                                <?php endif;?>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center">
                            <?php if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_x'] || $_SESSION['role_menu']['Switch']['rates']['model_w'] || $_SESSION['role_menu']['Switch']['codedecks']['model_w']) { ?>
    <?php echo $form->submit("Download", array('div' => false, 'class' => 'input in-submit btn btn-primary')) ?>
<?php } ?>
                            <input type="reset" value="<?php __('reset') ?>" class="input in-submit btn btn-default" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>