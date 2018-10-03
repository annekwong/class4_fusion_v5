<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
    <h1> <?php __('Rate Management') ?>&gt;&gt;<?php echo __('Unprocessed/Processed Decks',true);?>
</div>

<div id="container">
    <?php echo $this->element("rate_managements/sub_menu", array('active' => 'process'))?>
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <th><?php __('Received Time')?></th>
                <th><?php __('Carrier')?></th>
                <th><?php __('From Address')?></th>
                <th># <?php __('of Attachment')?></th>
                <th><?php __('Success/Fail Upload')?></th>
                <th><?php __('Action')?></th>
            </tr>
        </thead>
        
        
        <?php 
        $count = count($this->data);
        for($i = 0; $i < $count; $i++): 
        ?>
        <tbody id="resInfo<?php echo $i?>">
            <tr class="row-<?php echo $i%2 +1;?>">
                <td><?php echo $this->data[$i]['RateMailDecks']['received_time'] ?></td>
                <td><?php echo $this->data[$i]['Client']['name'] ?></td>
                <td><?php echo $this->data[$i]['RateMailDecks']['from_address'] ?></td>
                <td><?php echo $this->data[$i]['RateMailDecks']['num_of_attachment'] ?></td>
                <td><?php echo $this->data[$i]['RateMailDecks']['success'] ?>/<?php echo $this->data[$i]['RateMailDecks']['fail'] ?></td>
                <td>
                    <a href="###" title="View Mail Content" class="view_mail_content" control="<?php echo $this->data[$i]['RateMailDecks']['id'] ?>"> 
                        <img src="<?php echo $this->webroot ?>images/view.png"> 
                    </a>
                    <a href="###">                       
                        <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                    </a>
                </td>
            </tr>
            <tr style="height:auto">
                <td colspan="6">
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px"> 
                        <table>
                            <tr>
                                <td><?php __('File Name')?></td>
                                <td><?php __('File Size')?></td>
                                <td><?php __('Status')?></td>
                                <td><?php __('Upload Time')?></td>
                                <td><?php __('Action')?></td>
                            </tr>
                            <?php foreach($this->data[$i]['RateMailDecksFiles'] as $deckfile): ?>
                            <tr>
                                <td><?php echo basename($deckfile['file_name']); ?></td>
                                <td><?php echo @$common->getSymbolByQuantity(filesize($deckfile['file_name'])); ?></td>
                                <td><?php echo $deckfile['status']; ?></td>
                                <td><?php echo $deckfile['upload_time']; ?></td>
                                <td>
                                    <a target="_blank" href="<?php echo $this->webroot ?>rate_managements/upload/<?php echo $deckfile['id'] ?>" title="Upload"> 
                                        <img src="<?php echo $this->webroot ?>images/upload.png"> 
                                    </a>
                                    <a href="###" control="<?php echo $deckfile['id'] ?>" class="view_result" title="View Result"> 
                                        <img src="<?php echo $this->webroot ?>images/log.png"> 
                                    </a>
                                    <a href="###" control="<?php echo $deckfile['id'] ?>" class="email_result" title="Email"> 
                                        <img src="<?php echo $this->webroot ?>images/email.gif"> 
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
        <?php endfor; ?>
    
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
<div id="dd"> </div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
     
    
      $(function() {
        var $dd = $('#dd');
        var $view_mail_content = $('.view_mail_content');
        var $view_result = $('.view_result');
        var $email_result = $(".email_result");
          
        $view_mail_content.click(function() {
            var control = $(this).attr('control');
            
            $dd.dialog({  
                title: 'View Mail Content',  
                width: 500,  
                height: 300,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot?>rate_managements/view_mail_content/' + control,  
                modal: true,
                buttons:[{
                        text:'Close',
                        handler:function(){
                            $dd.dialog('close');
                        }
                }]
            });

            $dd.dialog('refresh', '<?php echo $this->webroot?>rate_managements/view_mail_content/' + control);  
        });
        
        $email_result.click(function() {
            var control = $(this).attr('control');
            var $mail_form = null;
            var $email_type = null;
            var $failure_reasons_layout = null;
            
            $dd.dialog({  
                title: 'Send Mail',  
                width: 500,  
                height: 300,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot?>rate_managements/email/' + control,  
                modal: true,
                buttons:[{
                        text:'Submit',
                        handler:function(){
                            $mail_form.submit();
                        }
                },{
                        text:'Close',
                        handler:function(){
                            $dd.dialog('close');
                        }
                }],
                onLoad: function() {
                    $mail_form = $('#mail_form');
                    $email_type = $('#email_type');
                    $failure_reasons_layout = $('#failure_reasons_layout');
                    $email_type.change(function() {
                        var $this = $(this);
                        var val = parseInt($this.val());
                        if (val == 0)
                        {
                            $failure_reasons_layout.hide();
                        }
                        else
                        {
                            $failure_reasons_layout.show();
                        }
                    }).trigger("change");
                }
            });

            $dd.dialog('refresh', '<?php echo $this->webroot?>rate_managements/email/' + control); 
        });
        
        
        $view_result.click(function() {
            var control = $(this).attr('control');
            
            $dd.dialog({  
                title: 'View Result',  
                width: 600,  
                height: 400,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot?>rate_managements/view_result/' + control,  
                modal: true,
                buttons:[{
                        text:'Refresh',
                        handler:function(){
                            $dd.dialog('refresh', '<?php echo $this->webroot?>rate_managements/view_result/' + control);  
                        }
                },{
                        text:'Close',
                        handler:function(){
                            $dd.dialog('close');
                        }
                }]
            });

            $dd.dialog('refresh', '<?php echo $this->webroot?>rate_managements/view_result/' + control);  
        });
    });
</script>