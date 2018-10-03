<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
  <h1><?php __('Routing')?>&gt;&gt;<?php echo __('DynamicRouting')?></h1>
  <?php $w = $session->read('writable');?>
		<ul id="title-search">
				<li>
				<?php //****************************模糊搜索**************************?>
					<form  action="<?php echo $this->webroot?>dynamicroutes/view"  method="get">
						<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>" 
						value="<?php if(isset($_POST['search'])){echo $_POST['search'];}else{ echo '';}?>"  onclick="this.value=''" name="search">
					</form>
				</li>
				<li title="<?php echo __('advancedsearch')?>" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    <ul id="title-menu">
     <?php if (isset($edit_return)) {?>
     <li>
    			<a class="link_back" href="<?php echo $this->webroot?>dynamicroutes/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('gobackall')?>
    			</a>
    </li>
     <?php }?>
     <li>
    	<?php echo $this->element("createnew",Array('url'=>'dynamicroutes/add'))?>
     </li>
	</ul>
</div>
	<div id="container">
	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
<form>
<table  style="width: 580px;">
<tbody>
<tr>
    <td style="display:none; "><label> <?php echo __('route_name')?>:</label>
    <input   type="text"   name="name"   />
     		
  </td>
    <td><label><?php echo __('routingrule')?>:</label>
   		<?php 
	   		$arr1=array('4'=>__('routerule1',true),'5'=>__('routerule2',true),'6'=>__('routerule3',true));
	   		echo $form->input('routing_rule',
			 		array('options'=>$arr1,'name'=>'routing_rule','empty'=>'','label'=>false ,
			 		'div'=>false,'type'=>'select'));
		 		?>
    </td>
    <td class="buttons"><input type="submit" value="<?php echo __('Search',true);?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
		<div id="toppage"></div>
		<div style="height:10px"></div>
<?php //*********************表格�?************************************?>
		<div>	
			<table class="list">
				<col style="width: 9.8%;">
				<col style="width: 11.5%;">
				<col style="width: 14.9%;">
				<col style="width: 11.4%;">
				<col style="width: 11.5%;">
				<thead>
					<tr>
			 			<td >
	    				<?php echo __('findegress')?></td>
	    			<td> <?php echo $appCommon->show_order('dynamic_route_id',__('ID',true))?></td>
	    			<td> <?php echo $appCommon->show_order('name',__('Name',true))?></td>
		      <td> <?php echo $appCommon->show_order('routing_rule',__('Routing rule',true))?></td>
	       <td class="last"  style="text-align: center;"><?php echo __('action')?></td>
					</tr>
				</thead>
	<?php //*********************表格�?************************************?>	
		<?php //*********************循环输出的动态部�?************************************?>	
			<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>


				<tbody id="resInfo<?php echo $i?>">
				<tr class="row-<?php echo $i%2 +1;?>">
				   <td  align="center"  style="font-weight: bold;">
			 <img   id="image<?php echo $i; ?>"  		onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   
			 title="<?php echo __('findegress')?>"/>

		</td >
		    <td  align="center">
		    <a  href="<?php echo $this->webroot?>dynamicroutes/edit/<?php echo $mydata[$i][0]['dynamic_route_id']?>"  title="<?php echo __('edit')?>">
		    	<?php echo $mydata[$i][0]['dynamic_route_id']?>	
		    		</a>
		    </td>
		     <td  align="center"  style="font-weight: bold;" >
			 
			 <?php echo $mydata[$i][0]['name']?> 
			 
			 </td >
			    <td align="center">
			        <?php if($mydata[$i][0]['routing_rule']==4){   echo __('routerule1');?>
								   <?php }if($mydata[$i][0]['routing_rule']==5){echo  __('routerule2'); ?>
								   <?php }if($mydata[$i][0]['routing_rule']==6){echo  __('routerule3'); ?>
			    			<?php }?>
			    </td>
         <td style="text-align: center;">
            <?php if ($w == true) {?>
             <a title="Edit" href="<?php echo $this->webroot?>dynamicroutes/edit/<?php echo $mydata[$i][0]['dynamic_route_id']?>"  title="edit">
                  <i class="icon-edit"></i>
		    						</a>
		    				<?php }?>
        			<?php if ($w == true) {?> 
        					<a onclick="return confirm('Are you sure to delete ,Dynamic Routing <?php echo  $mydata[$i][0]['name']  ?> ?');" href="<?php echo $this->webroot?>dynamicroutes/del/<?php echo $mydata[$i][0]['dynamic_route_id']?>/<?php echo $mydata[$i][0]['name']?>" title="<?php echo __('delete')?>">
			        				<i class='icon-remove'></i>
			      </a><?php }?>
          </td>
				</tr>
				<tr style="height:auto">
						<td colspan=5>
								<div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px">
								<script type="text/javascript">
							            createDynTable('<?php echo $this->webroot?>',<?php echo $mydata[$i][0]['dynamic_route_id']?>,<?php echo $i?>);
							  </script>
								</div>
						</td>
				</tr>
		</tbody>



<?php }?>
</table>
</div>
<?php //*****************************************循环输出的动态部�?************************************?>	
<div style="height:10px"></div>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
<div>

<?php }?>
</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
