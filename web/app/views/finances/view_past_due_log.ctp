<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('View Past Due Notification Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('View Past Due Notification Log')?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left"  href="javascript:history.go(-1)"><i></i><?php __('Back') ?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">

            <div id="container">

                <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <tbody>
                        <tr>
                            <td class="right"><?php __('Suject') ?> </td>
                            <td><?php echo $data[0][0]['mail_sub'] ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Content') ?> </td>
                            <td><?php echo str_replace("\n", "<br />", $data[0][0]['mail_content']); ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Invoice File') ?></td>
                            <td>
                                <a href="<?php echo $this->webroot; ?>upload/invoice/<?php echo $data[0][0]['pdf_file'] ?>"><?php __('Download') ?></a>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>