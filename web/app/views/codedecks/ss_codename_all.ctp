<?php echo $this->element("selectheader")?>
<div id="title">
        <h1><?php echo __('code_name',true);?></h1>
</div>
<div class="container">
                
<ul class="tabs">
    <li class="active"><a href="">
    <img src="<?php echo $this->webroot?>images/menuIcon.gif" height="16" width="16"/> <?php echo __('code_name',true);?></a></li>      
</ul>

<script type="text/javascript">var smartSearch = 2;</script>
<div class="panel">
<form id="smartSearch3"  action="<?php echo $this->webroot?>codedecks/ss_codename_all"  method="get">
<input class="input in-hidden" name="type" value="0" id="type" type="hidden">
<input class="input in-hidden" name="types" value="10" id="types" type="hidden">
<input class="input in-hidden" name="noall" value="" id="noall" type="hidden"><table class="form"><tbody><tr>
    <td class="value">
    	<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>"
    	
    	style="float: left;"
    	 value="<?php if (!empty($search)) echo $search;?>" name="search">
   
<!--<span style="float: left;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »"></span>
    --></td>
</tr></tbody></table>
</form>
</div>
<div id="container"><!-- DYNAREA -->
<fieldset class="title-block" id="advsearch"  style="display:none;width: 100%;">
	<form method="get"  action="<?php echo $this->webroot?>codedecks/ss_codename/">
	<input type="hidden" name="adv_search" value="1"/>
<table  style="width: 600px;">
<tbody>
<tr>
    <td><label><?php echo __('code')?>:</label>
    
     <input name="code" id="code"/>
  </td>
    <td><label><?php echo __('code name')?>:</label>
     <input name="code_name" id="code_name"/>
  </td>
  <td><label><?php echo __('Country')?>:</label>
     <input name="country" id="country"/>
  </td>
  <td class="buttons"><input type="submit"   style="margin-top: 19px;" value="<?php echo __('submit')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
<div id="toppage"></div>
<table class="list">
<thead>
<tr>
    <td width="5%"><?php __('code')?></td>
    <td width="30%"><?php echo __('code name')?></td>
    <td class="last" width="30%"><?php __('Country')?></td>
</tr>
</thead>
<tbody>
<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
<tr class="s-active row-1"
 onclick='var codename=jQuery(this).find("td:nth-child(2)").html();codename=jQuery.trim(codename);opener.codename_all_callback(codename);winClose();' style="cursor: pointer;">
    <td align="right"><?php echo $mydata[$i][0]['code']?></td>
    <td class="last" align="left"> <?php echo $mydata[$i][0]['name']?>    </td>
    <td class="last" align="left"> <?php echo $mydata[$i][0]['country']?>    </td>
</tr>
<?php }?>
</tbody>
</table>
			<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
</div>
<?php echo $this->element("selectfooter")?>