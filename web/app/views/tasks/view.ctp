<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('Task Schedule')?></h1>  
		<ul id="title-search">
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>alerts/action"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       </ul>
       <ul id="title-menu">
        	<?php if (isset($edit_return)) {?>
        	<li>
    			<a class="link_back" href="<?php echo $this->webroot;?>alerts/action">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        	<?php }?><!--
        	<li>
        		<a title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot?>alerts/add_action">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
       --></ul>
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

<thead>
<tr>
 			<td ><?php echo $appCommon->show_order('name', __('Command Name',true));?> </td>
		 <td > 
		 <?php 
		 echo __('Execution Time'); 
		 echo "<br />(minute hour day_of_month month day_of_week)";
		 ?>  </td>
		 <td > <?php echo __('Active'); ?>  </td>
		</tr>
</thead>
<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td align="center">
			    <a title="" href="<?php echo $this->webroot?>tasks/edit_task/<?php echo $mydata[$i][0]['id']?>">
					<?php echo $mydata[$i][0]['name'];?>
				</a>
			</td>
		  <td>
		  <?php
		  		echo $mydata[$i][0]['cron_minute'], ' ', $mydata[$i][0]['cron_hour'], ' ', $mydata[$i][0]['cron_day'], ' ', $mydata[$i][0]['cron_month'], ' ', $mydata[$i][0]['cron_week'];
		    ?>
		</td>		
		<td > <?php echo $mydata[$i][0]['flag']?'Enable':'Disable'; ?>  </td>
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
