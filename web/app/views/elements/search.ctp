

<ul id="title-search">
<form onsubmit="loading();" method="get">
	<?php 
	$controller=$this->params['controller'];
	if($controller=='clients'){?>
      <li>
		<span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;"><?php echo __('Client type')?>:</span>
		<?php 
		//echo $xform->search('filter_client_type',Array('options'=>Array( Client::CLIENT_CLIENT_TYPE_INGRESS=>'ORIG Carrier',Client::CLIENT_CLIENT_TYPE_EGRESS=>'TERM Carrier'),'empty'=>'All'));
		echo $xform->search('filter_client_type',Array('options'=>Array('all'=>'All', 'ingress'=>'Ingress Trunk Only', 'egress'=>'Egress Trunk Only') ));
				?>
	</li>
    <?php }else{?>
  
    <?php }?>
  <li> 
      <input type="text" name="search" value="Search" title="Search" class="in-search default-value input in-text defaultText in-input" id="search-_q">
  </li>
  <input type="submit" name="submit" value="" class="search_submit"/>
  </form>
  <!--<li id="title-search-adv" onclick="advSearchToggle();" title="advanced search »"></li>-->
</ul>
<script type="text/javascript"> 
//jQuery(document).ready(function(){jQuery('#title-search input[name=search]').val('Search').click(function(){jQuery(this).val('').unbind('click');});});
</script>
