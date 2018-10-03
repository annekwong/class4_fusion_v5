
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CDR Recovery') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('CDR Recovery') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php
            if (empty($data)):
                ?>
                <div class="msg center"><h3><?php echo __('no_data_found', true); ?></h3></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <td><?php __('File Name') ?></td>
                            <td><?php __('File Size') ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo $item[8]; ?></td>
                                <td><?php echo $item[4]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
                <br /><br />
                <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                    <div class="search_title">
                        <img src="<?php echo $this->webroot ?>images/recovery.png">
                        <?php __('Recovery') ?>  
                    </div>
                    <div style="margin:0px auto; text-align:center;">
                        <form id="myform" method="post" name="myform">
                            <?php __('Period') ?>:
                            <input type="text" value="<?php echo date("Y-m-d 00:00:00"); ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" style="width:120px;" name="start" class="input in-text in-input">
                            ~
                            <input type="text" value="<?php echo date("Y-m-d 23:59:59"); ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" style="width:120px;" name="end" class="input in-text in-input">
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit" class="input in-submit btn btn-primary" style="margin-bottom: 10px;">
                        </form>
                    </div>
                </fieldset>
    </div>
</div>