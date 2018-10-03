<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('CDRs list') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Mail CDR Log') ?></li>
</ul>
<div class="separator bottom"></div> 
<div class="innerLR">
<div class="widget widget-tabs widget-body-white">
    <div class="widget-head">
    <ul>
        <?php if ($session->read('login_type') == 3): ?>

        <?php echo $this->element('report_db/cdr_report/cdr_report_user_tab', array('active' => 'export_log'))?>
    <?php else: ?>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports_db/summary_reports"  class="glyphicons list">
                <i></i>
                <?php __('CDR Search') ?>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports_db/export_log" class="glyphicons book_open">
                <i></i>
                <?php __('CDR Export Log') ?>
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>cdrreports_db/mail_send_log" class="glyphicons no-js e-mail" >
                <i></i>
                <?php __('Mail CDR Log') ?>
            </a>
        </li>
        <?php endif; ?>
    </ul>
  </div>
  
    
    
    
    <div class="widget-body">
        <div id="container">
            <?php
                $data =$p->getDataArray();
            ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
                <tr>
                    <th></th>
                    <th><?php __('Job ID') ?></th>
                    <th><?php __('Start Time') ?></th>
                    <th><?php __('Finish Time') ?></th>
                    <th><?php __('CDR Start Time') ?></th>
                    <th><?php __('CDR End Time') ?></th>
                    <th># <?php __('of Files') ?></th>
                    <th><?php __('CDR Counts') ?></th>
                    <th><?php __('Action') ?></th>
                </tr>
            </thead>
        <?php 
        $count = count($data);
        for($i = 0; $i < $count; $i++): 
        ?>
        <tbody id="resInfo<?php echo $i?>">
            <tr class="row-<?php echo $i%2 +1;?>">
                <td>
                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                </td>
                <td>#<?php echo $data[$i][0]['id'] ?></td>
                <td><?php echo $data[$i][0]['start_time'] ?></td>
                <td><?php echo $data[$i][0]['finish_time'] ?></td>
                <td><?php echo $data[$i][0]['cdr_start_time'] ?></td>
                <td><?php echo $data[$i][0]['cdr_end_time'] ?></td>
                <td><?php echo $data[$i][0]['file_counts'] ?></td>
                <td><?php echo $data[$i][0]['cdr_counts'] ?></td>
                <td>
                    <a itemvalue="<?php echo $data[$i][0]['id'] ?>" class="emailTo" href="###">
                            <i class="icon-envelope"> </i>
                    </a>
                    <a onclick="return confirm('Are you sure?')" href="<?php echo $this->webroot ?>cdrreports_db/delete_email_log/<?php echo $data[$i][0]['id'] ?>">
                            <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <tr style="height:auto;">
                                <td colspan="9">
                                    <div id="ipInfo<?php echo $i ?>" class=" jsp_resourceNew_style_2" style="padding:5px;display:none;"> 
                                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <tr>
                                <td><?php __('CDR Start Time') ?></td>
                                <td><?php __('CDR End Time') ?></td>
                                <td><?php __('File Name') ?></td>
                                <td># <?php __('of CDR') ?></td>
                                <td><?php __('File Size') ?></td>
                                <td><?php __('Action') ?></td>
                            </tr>
                            <?php foreach($data[$i][0]['details'] as $detail): ?>
                            <tr>
                                <td><?php echo $detail[0]['cdr_start_time'] ?></td>
                                <td><?php echo $detail[0]['cdr_end_time'] ?></td>
                                <td><?php echo $detail[0]['filename'] ?></td>
                                <td><?php echo $detail[0]['order'] ?></td>
                                <td><?php echo round($detail[0]['file_size'] / 1024 , 2) ?>K</td>
                                <td>
                                    <a href="<?php echo $this->webroot ?>cdrreports_db/get_export_file/<?php echo $detail[0]['id'] ?>">
                                            <i class="icon-download-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
        <?php endfor; ?>
    </table>
        </div>
        <div class="row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
        </div> 
    </div>
    </div>
</div>
<div>
<!--<div id="pop-div" closed="true" class="easyui-dialog" title="CDR Email" style="width:400px;height:100px;"  
        data-options="iconCls:'icon-save',resizable:true,modal:true,cache:false">
    <div class="product_list">
        <label>Email Address:</label>
        <input type="text" class="input in-text in-input" id="send_email" />
        <input type="button" id="send_email_btn" value="Submit" />
    </div>
</div> -->

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
$(function() {
    var $dialog = $('#pop-div');
    $('.emailTo').click(function() {
        var id = $(this).attr('itemvalue');
        $dialog.dialog('open');
        var $btn = $('#send_email_btn');
        $btn.unbind('click');
        $btn.click(function() {
              var email = $('#send_email').val();
              if (email != '') 
              {
                   $.ajax({
                       'url' : '<?php echo $this->webroot ?>cdrreports_db/re_sendmail',
                       'type' : 'POST',
                       'dataType' : 'json',
                       'data' : {'id' : id, 'email':email},
                       'success' : function(data) {
                           $dialog.dialog('close');
                           jGrowl_to_notyfy('The email is sent to [' + email + '] successfully!',{theme:'jmsg-success'});
                       }
                   });                   
              }
              return false;
        });
    });
});
</script>