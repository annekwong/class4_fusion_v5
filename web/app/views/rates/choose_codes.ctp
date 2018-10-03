
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/base.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/popup.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/shared.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/jquery.jgrowl.css" />
    <link type="text/css" rel="stylesheet" media="print" href="<?php echo $this->webroot?>css/print.css" />
        
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery-1.4.1.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.tooltip.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-functions.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-interface.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>

 <div id="title">
  <script>function closeWithoutLoad(){parent.closeCover('cover_tmp');parent.document.body.removeChild(parent.document.getElementById('infodivv'));}</script>
    <h2><a id="closeA" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="closeWithoutLoad();">
		    			<i class="icon-remove"></i>
		    		</a></h2><h1><?php echo __('code')?></h1>
		    		
		    		<ul id="title-search">
    <li style="list-style:none">
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
  </ul>
</div>
<div class="container">
<div id="toppage"></div>
<table class="list">
<thead>
<tr>
    <td width="40%"><?php echo __('id',true);?></td>
    <td width="25%"><?php echo __('code')?></td>
    <td width="25%"><?php echo __('codenames')?></td>
    <td width="10%"><?php echo __('action')?></td>
</tr>
</thead>
<tbody class="rows"><?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1" ondblclick="var v = this.cells[1].innerHTML;var v1 = this.cells[2].innerHTML;parent.td.value = v;parent.td1.value=v1; closeWithoutLoad();">
		    <td><?php echo $mydata[$i][0]['code_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td><?php echo $mydata[$i][0]['name']?></td>
		    <td style="text-align:center;">
		    		<a style="text-align:center;" href="javascript:void(0)" onclick="var v = this.parentNode.parentNode.cells[1].innerHTML;var v1 = this.parentNode.parentNode.cells[2].innerHTML;parent.td.value = v;parent.td1.value=v1; closeWithoutLoad();">
		    			<img src="<?php echo $this->webroot?>images/icon7.gif" />
		    		</a>
		    </td>
				</tr>
		<?php }?>		
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
<script>document.getElementById("toppage").appendChild(document.getElementById("tmppage").cloneNode(true));</script>
</div>
</div>