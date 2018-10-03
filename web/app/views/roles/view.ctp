<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('Roles')?></h1>  
		<ul id="title-search">
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot?>roles/view"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       </ul>
       <ul id="title-menu">
        	<?php if (isset($edit_return)) {?>
        	<li>
    			<a class="link_back" href="<?php echo $this->webroot?>roles/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        	<?php }?>
            <?php  if ($_SESSION['role_menu']['Configuration']['roles']['model_w']) {?>
        	<li>
        		<a class="link_btn" title="<?php echo __('createroletitle')?>"  href="<?php echo $this->webroot?>roles/add_role">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
            <?php }?>
       	</ul>
    </div>
<div id="container">
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>
<div id="toppage"></div>
<table class="list">
<col width="16%">
<col width="18%">
<col width="12%">
<col width="8%">
<thead>
<tr>
 			<td ><?php echo $appCommon->show_order('role_name',__('RolesName',true))?> </td>
		 <td > <?php echo $appCommon->show_order('active',__('Rellerusable',true)) ?>  </td>
		 <td > <?php echo $appCommon->show_order('role_cnt',__('usercount',true)) ?>	</td>
		    <td  class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		    <td align="center">
			   <a style="width:80%;display:block" title="<?php __('viewfunc')?>" href="<?php echo $this->webroot?>roles/add_role/<?php echo $mydata[$i][0]['role_id']?>" class="link_width">
						<?php echo $mydata[$i][0]['role_name']?>
					</a>
				</td>
		    <td>
		    <?php
				if(!empty($mydata[$i][0]['active'])){
					echo 'active';
				}else{
					echo  'disable';
				}
		    ?>
		</td>
		<td>
		    <?php
				if(empty($mydata[$i][0]['role_cnt'])){
					echo 0;
				}else{
					echo $mydata[$i][0]['role_cnt'];
				}
			?>
		</td>

		      <td class="last"  >
              <?php  if ($_SESSION['role_menu']['Configuration']['roles']['model_w']) {?>
		      <?php 	if(!empty($mydata[$i][0]['active'])){?>
		       	<a   title="Active" onclick="return confirm('Are you sure to inactive the selected <?php echo  $mydata[$i][0]['role_name'] ?> ?')"  href="<?php echo $this->webroot?>roles/dis_role/<?php echo $mydata[$i][0]['role_id']?>/<?php echo $mydata[$i][0]['role_name']?>">
		      		<img width="16" height="16" src="<?php echo $this->webroot?>images/flag-1.png">
		      	</a>
		      <?php }else{?>
		        	<a   title="Active"  onclick="return confirm('Are you sure to active the selected <?php echo $mydata[$i][0]['role_name'] ?> ?')" href="<?php echo $this->webroot?>roles/active_role/<?php echo $mydata[$i][0]['role_id']?>/<?php echo $mydata[$i][0]['role_name']?>">
		      		<img width="16" height="16" src="<?php echo $this->webroot?>images/flag-0.png">
		      	</a>
		      <?php }?>
		  
		      	<a   title="<?php echo __('editrole')?>"  href="<?php echo $this->webroot?>roles/add_role/<?php echo $mydata[$i][0]['role_id']?>">
		      		<i class="icon-edit"></i>
		      	</a>
		       <?php if(empty($mydata[$i][0]['role_cnt'])){?>
		        <a title="<?php echo __('del')?>" onclick="return confirm('Are you sure to delete, roles <?php echo $mydata[$i][0]['role_name'] ?> ? ');" href="<?php echo $this->webroot?>roles/del/<?php echo $mydata[$i][0]['role_id']?>/<?php echo $mydata[$i][0]['role_name']?>">
					    <i class="icon-remove"></i>
				     </a>
					<?php }else if($mydata[$i][0]['role_cnt'] >= 1){ ?>
				
				<a  onclick="return alert('<?php echo __('Are you sure to delete, roles %s', false, $mydata[$i][0]['role_name'])?>');" href="<?php echo $this->webroot?>roles/del/<?php echo $mydata[$i][0]['role_id']?>/<?php echo $mydata[$i][0]['role_name']?>">
					    <i class="icon-remove"></i>
				  </a>
				<?php }else if($mydata[$i][0]['role_cnt']>1){?>
				
				<?php }?>
		       <?php }?>
		      </td>
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
<div>
<div id="tmppage">

<?php echo $this->element('page');?>
</div>
<?php }?>
</div>

