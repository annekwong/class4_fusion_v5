
<div id="cover"></div>
<div id="title">
  <h1>
    
  验证导入数据的格式
   <!--  <a title="add to smartbar" href="/admin/_view/sbAdd?link=/rate_tables/list">
   	<img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png" />
   </a>-->
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <!--  <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
  </ul>
    
  <ul id="title-menu">
    <li>
    			<a class="link_back" href="<?php echo $this->webroot?>codedecks/codedeck_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
    		

    		    <li>
    			<a class="link_btn" href="#"   onclick="ajax_check_upload('<?php echo $this->webroot?>');">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;开始验证
    			</a>
    		</li>
    		
    
    <!--  <li>
  <form action="<?php echo $this->webroot?>products/upload" enctype="multipart/form-data" method="post">
  <input type="submit" value="Upload" class="input in-button"/>
  			<input type="file" class="input in-text" name="MyFile"/>
  </form>
  	 </li>-->
  </ul>
</div>



		    <div id="uploadcode"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <form action="<?php echo $this->webroot?>codedecks/upload_code/<?php echo $code_deck_id?>" method="post" enctype="multipart/form-data" id="productFile">	
			    <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
			    <div style="height: 100px;" class="up_panel_upload">
			    <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
			    <div style="margin-top:20px;">
			    			<input type="radio" title="<?php echo __('upload_overwrite')?>" checked value="1" name="handleStyle">
					    <span><?php echo __('overwrite')?></span>
					    <input style="margin-left:10px;" type="radio" title="<?php echo __('upload_remove')?>" value="2" name="handleStyle">
					    <span><?php echo __('remove')?></span>
					    <input style="margin-left:10px;" type="radio" title="<?php echo __('upload_refresh')?>" value="3" name="handleStyle">
					    <span><?php echo __('clearrefresh')?></span>
			       <input style="margin-left:10px;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
			       <input type="hidden" value="true" name="isRoll" id="isRoll"/>
			       <span><?php echo __('rollbackonfail')?> </span>   
			    </div>   
			    </div>
			    <div class="form_panel_button_upload">
			    			<span style="float:left"> <?php echo __('downloadtempfile')?><a href="<?php echo $this->webroot?>products/downloadtemplate/f" style="color:red"><?php echo __('clickhere')?></a></span>
			    			<input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
			    			<input type="button" onclick="closeCover('uploadcode')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
			    </div>  
			    </form>
		    </div>
		    
		    
		    <div id="uploadcode_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured')?>:</span>
			    <div style="height: auto;text-align:left;" id="code_upload_errorMsg" class="up_panel_upload"></div>    
			    <div class="form_panel_button_upload">
			    			<span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate/f" style="color:red"><?php echo __('clickhere')?></a></span>
			    			<input type="button" onclick="closeCover('uploadcode_error')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('close')?>"/>
			    </div>  
		    </div>

<div id="container">
<ul class="tabs">

      <li    ><a href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $code_deck_id;?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"> <?php __('codeslist')?></a></li>
      
    <li><a  href="<?php echo $this->webroot?>codedecks/add_code/<?php echo $code_deck_id?>">
    <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('addcode')?></a> 
       </li>
       

      
       <li  ><a href="<?php echo $this->webroot?>codedecks/import_code/<?php echo $code_deck_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('upload')?></a>        </li>
     
     
       <li ><a href="<?php echo $this->webroot?>codedecks/download/<?php echo $code_deck_id?>">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> <?php __('download')?></a>    </li>
       
                   <li class="active"><a     href="<?php echo $this->webroot?>codedecks/check_uploadcodes/<?php echo $code_deck_id?>/<?php echo $code_name?>">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> 导入数据验证</a>  
       		<input type="hidden" id="id_code_decks" value="<?php echo $code_deck_id?>" name="id_code_decks" class="input in-hidden">
			<input type="hidden" id="code_name" value="<?php echo $code_name?>" name="code_name" class="input in-hidden">
         </li>
       </ul>

<table class="list">
	<col style="width: 12%;">
	<col style="width: 15%;">
	<col style="width: 22%;">
	<col style="width: 24%;">

	<thead>
		<tr>
    <td><a href="javascript:void(0)" onclick="my_sort('code_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;号码组 &nbsp;<a href="javascript:void(0)" onclick="my_sort('code_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('code')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('city','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('city')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('city','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('state','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('state')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('state','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('country','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Country',true)?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('country','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
 
		</tr>
	</thead>
	<tbody id="producttab">
	
		<?php 
			
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td><?php echo $code_name?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td><?php echo $mydata[$i][0]['city']?></td>
		    <td><?php echo $mydata[$i][0]['state']?></td>
		    <td><?php echo $mydata[$i][0]['country']?></td>

				</tr>
		<?php }?>		
		
	</tbody>
	<tbody>
</tbody>
</table>

</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>