<form>
  <table class="list">
    <tr class="cloned">
      <td></td>      
      <td>
          <!--<input type="text" class="input in-text in-input" style="width:80px" name="digits" value="<?php echo array_keys_value($this->data,'StaticRoute.digits')?>" maxlength="32" id="digits"/>-->
          
      
      <?php
      
            if(!empty($this->data['StaticRoute']['code_name'])){
                echo trim($this->data['StaticRoute']['code_name']);
            }else{
                echo "<select name='code_name' class='select in-select'>";
                echo '<option value=""></option>';
                foreach($codes as $code){
                    echo '<option value="'.$code[0]["name"].'">'.$code[0]["name"].'</option>';
                }
                echo "</select>";
            }
      
      ?>
      </td>
      <td><?php echo $xform->search('strategy',Array('style'=>'width:80px','onchange'=>'strategy(this)','class'=>'input in-select','options'=>Array('1'=>__('topdown',true),'0'=> __('bypercentage',true),'2'=>__('roundrobin',true)),'value'=>array_keys_value($this->data,'StaticRoute.strategy')))?></td>
      <td><?php echo $xform->search('time_profile_id',Array('options'=>$appProduct->_get_select_options($TimeProfileList,'TimeProfile','time_profile_id','name'),'value'=>array_keys_value($this->data,'StaticRoute.time_profile_id')))?></td>
      <td colspan=8></td>
      <td><?php echo array_keys_value($this->data,'StaticRoute.update_at')?></td>
      <td class="last"><a title="Save" href="#%20" id="save" > <img style="float: left; margin-left: 20px;" src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16"> </a> <a title="Exit" href="#%20" style="margin-left: 20px;" id="delete" > <i class="icon-remove"></i> </a></td>
    </tr>
  </table>
  <table class="list">
    <tr style="height:0px" class="cloned">
      <td colspan=15><div style="padding:5px;display:block">
          <div style="float:left;">
            <input type="button" value="Add" id="addbtn" />
          </div>
          <table class="list" id="tbl" style="clear:both;">
            <thead>
              <tr>
                <td style="width:15%"><?php echo __('Trunk Number',true);?></td>
                <td style="width:20%"><?php echo __('Carriers',true);?></td>
                <td style="width:20%"><?php echo __('Trunk',true);?></td>
                <!--<td style="width:10%"><?php echo __('Rate',true);?></td>-->
                <td style="width:20%"><?php echo __('Percentage',true);?></td>
                <td style="width:11%"></td>
              </tr>
            </thead>
            <tbody>
              <?php
                  if(isset($this->data['sel']) && count($this->data['sel']) > 0) {
                  $sel = $this->data['sel'];
                  foreach ($sel as $key=>$val) {
                    ?>
              <tr class="row-1">
                <td><?php echo $key+1 ?></td>
                <td><?php echo $xform->search('Carriers1',Array('options'=>$appProduct->_get_select_options($ClientList,'Client','client_id','name'),'value'=>$val[0]['client_id'],'onchange'=>'client(this)','style'=>'width:280px'))?></td>
                <td><?php echo $xform->search('resource_id[]',Array('style'=>'width:280px','options'=>$appProduct->_get_select_options($ResourceList,'Resource','resource_id','alias'),'empty'=>'','onchange'=>'get_rate(this)','class'=>'resource_list','value'=>$val[0]['resource_id']))?></td>
                <!--<td><?php echo $val[0]['rate']; ?></td>-->
                <td><?php echo $xform->search('percentage[]',Array('style'=>'display:none','class'=>'dd','value'=>$val[0]['by_percentage']))?></td>
                <td><img style="cursor:pointer;float: left; margin-left: 10px;" class="changeup" src="<?php echo $this->webroot; ?>images/sort-up.gif" /><img style="cursor:pointer;float: left; margin-left: 20px;" class="changedown" src="<?php echo $this->webroot; ?>images/sort-down.gif" /></td>
                <td>
                    <a href="javascript:void(0);" class="delete_tr">
                      <i class="icon-remove"></i>
                    </a>
                </td>
              </tr>
              <?php
					}
					}else{
				?>
              <tr class="row-1">
                <td>1</td>
                <td><?php echo $xform->search('Carriers1',Array('options'=>$appProduct->_get_select_options($ClientList,'Client','client_id','name'),'value'=>array_keys_value($this->data,'client1.client_id'),'onchange'=>'client(this)','style'=>'width:280px'))?></td>
                <td><?php echo $xform->search('resource_id[]',Array('style'=>'width:280px','options'=>$appProduct->_get_select_options($ResourceList,'Resource','resource_id','alias'),'empty'=>'','value'=>array_keys_value($this->data,'StaticRoute.resource_id_1')))?></td>
                <!--<td></td>-->
                <td><?php echo $xform->search('percentage[]',Array('style'=>'display:none','class'=>'dd','value'=>array_keys_value($this->data,'StaticRoute.percentage_1')))?></td>
                <td><img style="cursor:pointer;float: left; margin-left: 10px;" class="changeup" src="<?php echo $this->webroot; ?>images/sort-up.gif" /><img style="cursor:pointer;float: left; margin-left: 20px;" class="changedown" src="<?php echo $this->webroot; ?>images/sort-down.gif" /></td>
                <td>
                    <a href="javascript:void(0);" class="delete_tr">
                      <i class="icon-remove"></i>
                    </a>
                </td>
              </tr>
              <?php
				}
				?>
            </tbody>
          </table>
        </div></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
			function strategy(obj,val){
				var value =jQuery(obj).val();
				if(val){
					value=val;
				}
       if(value==1||value==2){
           jQuery('.dd').hide().val('');
                   }
       if(value==0){
           jQuery('.dd').show();
                  }
			}
			function client(obj){
				value=jQuery(obj).val();
				var data=jQuery.ajaxData('<?php echo $this->webroot?>trunks/ajax_options?filter_id='+value+'&type=egress');
				data=eval(data);
				var temp=jQuery(obj).parent().parent().find('select').eq(1).val();
				jQuery(obj).parent().parent().find('select').eq(1).html('');
				jQuery('<option>').appendTo(jQuery(obj).parent().parent().find('select').eq(1));
				for(var i in data){
					var temp=data[i];
					jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo(jQuery(obj).parent().parent().find('select').eq(1));
				}
				jQuery(obj).parent().parent().find('select').eq(1).val(temp);
                                
			}
	</script> 
<script type="text/javascript">
			
			$(document).ready(function() {
				$('#addbtn').on('click',function() {
						var $tr = $('#tbl tbody tr:last-child');
						var row = $tr.clone(true);
						if($tr.hasClass('row-1')) {
							row.removeClass('row-1').addClass('row-2');	
						} else {
							row.removeClass('row-2').addClass('row-1');
						}
						var num = $('td:first-child', row).text();
						$('td:first-child', row).text(++num);
//						$('#tbl tbody').append(row);
						
				});
			});
                        
                        $(document).ready(function() {
                            $('.changeup').live('click', function() {
                                var $pr = $(this).parent().parent().prev();
                                var $tr = $(this).parent().parent().insertBefore($pr);
                            });
                            $('.changedown').live('click', function() {
                                var $pr = $(this).parent().parent().next();
                                var $tr = $(this).parent().parent().insertAfter($pr);
                            });
                        });
                        
function get_rate(obj) {
    var val = $(obj).val();
    var prefix = $("input[name=digits]").val();
    //if(val != '') {
        var data=jQuery.ajaxData('<?php echo $this->webroot?>products/get_rate/' + val + '/' + prefix);
        if(isNaN(data)) data = 0;
        $(obj).parent().next().text(new Number(data).toFixed(5));
    //}
}

	</script>

<script>
  $(function () {
    $("a.delete_tr").click(function () {
      let row = $(this).parent().parent();
      var num = $('td:first-child', row).text();

      if (num != 1) {
        row.remove();
      }
    });
  });
</script>