<?php echo $this->element("selectheader")?>
<div id="title">
   <h1><?php  __('Carriers')?></h1>
</div>
<div class="container">
<ul class="tabs">
    <li class="active"><a href="">
    <img src="<?php echo $this->webroot?>images/menuIcon.gif" height="16" width="16"/> <?php  __('Carriers')?></a></li>      
</ul>
<ul >
	<li>
			<?php echo __('Client Type',true);?><select id="client_type"><option value=''><?php  __('all')?></option><option value=1><?php  __('Manual Invoice')?></option><option value=2><?php  __('Auto-invoice')?></option></select>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php __('Select All')?><input type="checkbox" id="selectType" />			
			&nbsp;&nbsp;
			<input type="button" id="actionSelectAll" value='select' onclick="actionAll()" style="display:none"/>
	</li>
</ul>

<script type="text/javascript">var smartSearch = 2;</script>
<div class="panel">
<form id="smartSearch3"  action="<?php echo $this->webroot?>clients/ss_client"  method="get">
		<input class="input in-hidden" name="type" value="0" id="type" type="hidden">
		<input class="input in-hidden" name="types" value="10" id="types" type="hidden">
		<input class="input in-hidden" name="noall" value="" id="noall" type="hidden"><table class="form"><tbody><tr>
    <td class="value">
     <input name="search"  style="width:500px" onclick="this.value=''"  title="<?php if(isset($search)){echo $search;}else{ echo __('pleaseinputkey');}?>"  value="<?php if(isset($_GET['search'])){echo $_GET['search'];}else{ echo __('pleaseinputkey');}?>"  class="in-search get-focus input in-text"  type="text"/>
    </td>
   </tr></tbody></table>
</form>
</div>
<div id="container"><!-- DYNAREA -->
<div id="toppage"></div>
<table class="list">
<thead>
<tr>
		 <td><input id="selectAll" type="checkbox" disabled="disabled"/></td>
    <td width="30%"><?php echo __('id',true);?></td>
    <td class="last" width="70%"><?php echo __('name',true);?></td>
</tr>
</thead>
<tbody>


<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
<tr class="s-active row-1" onclick='tr_click(this)' style="cursor: pointer;">
		 <td><input  class="select" type="checkbox" disabled="disabled" value='<?php echo $mydata[$i][0]['client_id']?>'/></td>
    <td align="right"><?php echo $mydata[$i][0]['client_id']?></td>
    <td class="last" align="left">
      <?php echo $mydata[$i][0]['name']?>
    </td>
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