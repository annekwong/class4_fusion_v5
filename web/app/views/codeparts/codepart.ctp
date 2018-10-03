<style type="text/css">
.list {
font-size:1em;
background:url("../images/list-row-1.png") repeat-x scroll center bottom #FDFDFD;
height:37px;
width:100%;
border:0px solid #809DBA;
margin:0 auto 0px;
border-collapse:collapse;
}

.list tbody td {
border-right:1px solid #E3E5E6;
border-left:1px solid #809DBA;
line-height:1.6;
padding:1px 4px;
}
</style>
<?php $w = $session->read('writable');?>
<div id="title">
            <h1>
      号段管理
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search">
        <li><form  action="<?php echo $this->webroot?>clients/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
  
    </ul>
    
        <ul id="title-menu">
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>gatewaygroups/view_ingress">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        <li><a class="link_btn" href="<?php echo $this->webroot?>codeparts/import_rate/"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png">导入</a></li>
        <li><a class="link_btn" href="<?php echo $this->webroot?>codeparts/download_rate/"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/export.png">导出</a></li>
      <!--  <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> List Items</a></li>-->
       <?php if ($w == true) {?><li><a class="link_btn" href="<?php echo $this->webroot?>codeparts/add_codepart">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">新增号段</a></li><?php }?>
       
       <?php if (isset($extraSearch)) {?>
       <li>
    			<a class="link_back" href="<?php echo $this->webroot?>resellers/reseller_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
       <?php }?>
       <!--  <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>-->
        </ul>
        

    </div>

<div id="container">



<div id="toppage"></div>



<?php //*********************表格头*************************************?>
		<div>	
			<table class="list"  style="border:1px solid #809DBA;height: 14px;">
				<col style="width: 5.8%;">
				<col style="width: 8%;">
				<col style="width: 8%;">
				<col style="width: 5%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 9.5%;">
	    <col style="width: 7.5%;">
	     <col style="width: 7.5%;">
	      <col style="width: 7.5%;">
				<col style="width: 12.5%;">

			<thead>
				<tr>

    			<td>	ID</td>
    			<td>开始号码</td>
  <td >结束号段</td>
    <td> 每分钟费用</td>
    <td> 通话费用</td>
  <td> 首次时长</td>
   
 <td> 免费时长</td>
 	<td  >计费周期&nbsp;</td>
 <td> 1分钟多少秒</td>
  <td> 月费</td>
   <td>启动费用</td>
   <td class="last"  style="text-align: center;"><?php echo __('action')?></td>
   
    
    
		</tr>
			</thead>
			<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
			
			
					<tr >
		    <td  align="center">	<?php echo $mydata[$i][0]['code_part_id']?>	</td>
		    <td>	<?php echo $mydata[$i][0]['start_code']?>	</td>
 <td>	<?php echo $mydata[$i][0]['end_code']?>	</td>
		   
		    <td  align="center"><?php echo  number_format($mydata[$i][0]['rate'], 3);?></td>
		     <td ><?php   $my_pi = number_format($mydata[$i][0]['setup_fee'], 3);  echo  $my_pi;?></td>

 <td  align="center"><?php echo  number_format($mydata[$i][0]['min_time'], 0);?></td>
		 <td  align="center"><?php echo  number_format($mydata[$i][0]['grace_time'], 0);?></td>
 <td  align="center"><?php echo  number_format($mydata[$i][0]['interval'], 0);?></td>

 <td  align="center"><?php echo  number_format($mydata[$i][0]['seconds'], 0);?></td>
 <td  align="center"><?php echo  number_format($mydata[$i][0]['month_fee'], 3);?></td>
 <td  align="center"><?php echo  number_format($mydata[$i][0]['active_fee'], 3);?></td>

		    
		    

          
                <td class="last">
 
 <?php if ($w == true) {?>

    
       <a title="<?php echo __('edit')?>"  href="<?php echo $this->webroot?>codeparts/edit/<?php echo $mydata[$i][0]['code_part_id']?>"  style="float: left; margin-left: 40px;">
      <i class="icon-edit"></i></a>
    <a title="<?php echo __('del')?>"  href="<?php echo $this->webroot?>codeparts/del/<?php echo $mydata[$i][0]['code_part_id']?>"><i class="icon-remove"></i></a><?php }?>
          </td>
          

				</tr>


				<?php }?>

	</table>
	</div>


<?php //*****************************************循环输出的动态部分*************************************?>	

	

			<div id="tmppage">
<?php echo $this->element('page');?>

</div>

</div>

    
