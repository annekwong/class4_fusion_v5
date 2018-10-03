
<div id="title">
    <h2><?php echo $code_deck_id;?></h2>    <h1><?php __('download')?> <?php __('code')?></h1>
</div>

<div class="container">
<ul class="tabs">

      <li ><a href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $code_deck_id;?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"> <?php __('codeslist')?></a></li>
      
    <li><a  href="<?php echo $this->webroot?>codedecks/add_code/<?php echo $code_deck_id?>">
    <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('addcode')?></a> 
       </li>
       

      
             <li ><a href="<?php echo $this->webroot?>codedecks/import_code/<?php echo $code_deck_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('upload')?></a>        </li>
     
       <li  class="active"><a href="<?php echo $this->webroot?>codedecks/download/<?php echo $code_deck_id?>">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> <?php __('download')?></a>    </li>
       

       </ul>
       		<form method="post" action="<?php echo  $this->webroot?>codedecks/download_code/">
		<input type="hidden" id="id_code_decks" value="<?php echo $code_deck_id?>" name="id_code_decks" class="input in-hidden">
			<input type="hidden" id="code_name" value="<?php echo $code_name?>" name="code_name" class="input in-hidden">
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php __('codedecks')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;"  readonly="readonly" id="code" value="<?php echo $code_name;?>" name="code" class="input in-text">
					   </td>
					</tr>
					
					<!--<tr>
					    <td class="label label2">Fields delimiter:</td>
					    <td class="value value2">
					    			<select style="float:left;width:300px;" name="delimiter" id="delimiter">
					    			<option  value="1">,</option>
					    				<option  value="2">|</option>
					    				<option  value="3">;</option>
					        </select>
					    </td>
					</tr>
					
					--><tr>
					    <td class="label label2"><?php echo __('With headers row',true);?>:</td>
					    <td class="value value2">
					    			<select style="float:left;width:300px;" name="header" id="header">
					    			<option   value="1"><?php __('yes')?></option>
					    				<option   value="2"><?php __('no')?></option>
					        </select>
					    </td>
					</tr>
					

				</tbody>
			</table>

			<div id="footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>
			
			</form>
       
       
       
</div>