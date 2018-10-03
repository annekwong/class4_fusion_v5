<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Get Support') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Client List') ?></h4>
    
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
        <div class="widget-body">
            <form method="post" >
           <table class="form table dynamicTable tableTools table-bordered  table-white">
                        <colgroup>
                            <col width="10%">
                            <col width="90%">
                        </colgroup>

                        <tbody>
                            <tr>
                                
                                <td class="align_right"><?php __('From email')?> </td>
                                <td>
                                    <select name="invoice_from">
                                        <option value=''><?php __('Default')?></option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>"><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Contact E-mail')?> </td>
                                <td>
                                    <input type="text" name="to_email" id="to_email" value="<?=$email?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php echo __('subject')?> </td>
                                <td>
                                    <input class="input in-text" name="invoice_subject" value="<?php echo !empty($tmp[0][0]['invoice_subject'])?$tmp[0][0]['invoice_subject']:'';?>" id="invoice_subject" type="text" >
                                    <p></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php echo __('content')?> </td>
                                <td><textarea class="input in-textarea wysihtml5 invoice_content" name="invoice_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="invoice_content"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
            <div style="text-align: center;">
                <input  class="input in-submit btn btn-primary" value="<?php echo __('Send')?>" type="submit">
            </div>
                    </form>
        </div>
    </div>

</div>
<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<script type="text/javascript">
    
</script>
