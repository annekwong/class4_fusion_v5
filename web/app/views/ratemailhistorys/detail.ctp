<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Delivery History') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Delivery History') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>ratemailhistorys"><i></i><?php echo __('Back') ?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo __('Mail Content', true); ?></th>
                            <th><?php echo __('File List', true); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $data['Ratemailhistory']['mail_content'] ?></td>
                            <td style="width:40% !important;">
                                <div  class="reatemail">
                                    <ul>
                                        <?php
                                        $files = explode(',', $data['Ratemailhistory']['files']);
                                        ?>
                                        <?php foreach ($files as $file): ?>
                                            <li><a href="<?php echo $this->webroot; ?>ratemailhistorys/down/<?php echo base64_encode($file); ?>"><?php echo basename($file); ?></a></li>
                                        <?php endforeach; ?>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
    </div>
</div>