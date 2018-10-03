<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rule Execution Log',true);?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rule Execution Log',true);?></h4>
    <div class="buttons pull-right">
        
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
            <div class="separator bottom row-fluid">
    <div class="pagination pagination-large pagination-right margin-none">
    </div> 
</div>
<h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
<?php }else{
?>

<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

<thead>
<tr>
 			<th ><?php echo __('Rule Name',true);?> </th>
 			<th ><?php echo __('start_time',true);?> </th>
 			<th ><?php echo __('end_time',true);?> </th>
		 <th > <?php echo __('Problem Count',true); ?>  </th>
                 <th><?php echo __('Action',true); ?> </th>
		</tr>
</thead>
<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td align="center">			    
					<?php echo $mydata[$i][0]['name']?>
			</td>
		  <td align="center">			    
					<?php echo $mydata[$i][0]['start_time']?>
			</td>
			<td align="center">			    
					<?php echo $mydata[$i][0]['end_time']?>
			</td>
                        <!--
			<td align="center" class="getevent" style="cursor:pointer;" control="<?php echo $mydata[$i][0]['id']?>">			    
					<?php echo $mydata[$i][0]['cnt']?>
			</td>
                        -->
                        <td align="center">			    
					<?php echo $mydata[$i][0]['cnt']?>
			</td>
                        <td>
                            <a href="<?php echo $this->webroot ?>alerts/delete_log/<?php echo $mydata[$i][0]['id']?>" title="Delete">
                                <i class="icon-remove"></i>
                            </a>
                            <a href="###" class="viewDetial" title="View" control="<?php echo $mydata[$i][0]['id']?>">
                                <i class="icon-list"></i>
                            </a>
                        </td>
		</tr>
			<?php }?>
		</tbody>
		</table>
<div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
	</div>
<div>


<?php }?>
</div>

<div id="dd"> </div> 

<div id="dd2" class="easyui-dialog" title="Destination" closed="true" style="width:400px;height:200px;"  
        data-options="iconCls:'icon-save',closed:true,resizable:true">  
    <div id="dd2_content" class="dialog_form">
        
    </div>
</div>  
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
$(function() {
    /*
    $('.getevent').hover(function(e){
        $('.tooltips').remove();
        var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
        var yy = e.originalEvent.y || e.originalEvent.layerY || 0; 
        var eid = $.trim($(this).attr('control'));
        if($.trim($(this).text()) == 0) {
            return;
        }
        $.ajax({
           'url' : '<?php echo $this->webroot; ?>alerts/get_events/' + eid,
           'type' : 'GET',
           'dataType' : 'json',
           'success' : function(data) {
               var $ul = $('<ul />').css({
                   'position': 'absolute',
                   'left' : xx,
                   'top' : yy,
                   'opacity' : 1
               });
               $ul.addClass('tooltips');
               $.each(data, function(index, value) {
                   tmp = '';
                   if(value[0]['event'] == 'email' && value[0]['email_addr'] != 'null') {
                       tmp = ' to ' + value[0]['email_addr']; 
                   }
                   $ul.append('<li>' + value[0]['event'] + tmp + '</li>');
               });
               $('body').append($ul);
              
           }
        });
    }, function(e){
        $('.tooltips').remove();
    }); */
    
  
    var $viewDetial = $('.viewDetial');
    var $view_code_name = $('.view_code_name');
    var $dd = $('#dd');
    var $dd2 = $('#dd2');
    var $dd2_content = $('#dd2_content');
    
    $viewDetial.click(function() {
        var $this = $(this);
        var control = $this.attr('control');
        
        
        $dd.load('<?php echo $this->webroot?>alerts/get_log_info/' + control, 
            {}, 
            function(responseText, textStatus, XMLHttpRequest) {
                $dd.dialog({
                    'width': '850px'
                });
            }
        );
        
        /*
        $dd.dialog({  
            title: 'Rule Execution Log',  
            width: 800,  
            height: 600,  
            closed: false,  
            cache: false,  
            resizable: true,
            href: '<?php echo $this->webroot?>alerts/get_log_info/' + control,  
            modal: true,                
            buttons:[{
                    text:'Close',
                    handler:function(){
                        $dd.dialog('close');
                    }
            }]
        });

        $dd.dialog('refresh', '<?php echo $this->webroot?>alerts/get_log_info/' + control);  
        */
    });
    
    $view_code_name.live('click', function() {
        var $this = $(this);
        var full = $this.attr('full');
        full = full.replace(/,/g, "<br />");
        $dd2_content.html(full);
        $dd2.dialog(); 
    });
    
});
</script>