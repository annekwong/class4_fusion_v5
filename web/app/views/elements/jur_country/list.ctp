<script type="text/javascript">
<!--
    function checkName(){
                        

          }
//-->
</script>
<div id="toppage" style="display:none;"><?php echo $this->element('page');?></div>
<div>
<div id="noRows" class="msg"><?php echo __('no_data_found',true);?></div>
<form id="objectForm" method="post" action="<?php echo $this->webroot?>jurisdictioncountrys/add?page=<?php echo $p->getCurrPage()?>&size=<?php echo $p->getPageSize()?>">
<input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
<input type="hidden" value="1" name="page" class="input in-hidden">
<table style="display: none;" class="list list-form">
<col style="width: 10%;">
<col style="width: 45%;">
<col style="width: 45%;">


<thead>
		<tr>
		   <td> <?php echo $appCommon->show_order('id',__('ID',true))?></td>
		    <td> <?php echo $appCommon->show_order('name',__('Name',true))?></td>
		    <td  class="last"><?php echo __('action')?></td>
		</tr>
</thead>

<tbody id="rows">
<tr id="tpl">
    <td class="value">
     <small id="tpl-id-text"><?php echo __('code_name',true);?></small>
    <input type="hidden" name="id" /> </td>
    <td class="value"><input type="text" rel="format_number" name="name" style="font-weight:bold;text-align:right;" maxLength="16;"/></td>
    <td class="value">
     <a href="#" id="tpl-view-row" title="Jurisdiction"><img src="<?php echo $this->webroot?>images/bOrigTariffs.gif" width="16" height="16" /></a>
    	<a href="#" id="tpl-delete-row" title="delete"><i class='icon-remove'></i></a>
    </td>
 </tr>
</tbody>
</table>
</form>
</div>
<div id="tmppages" style="display:none"><?php echo $this->element('page');?></div>
<script language="JavaScript">
var lastId = 0;
var eRows = $('#rows');
var eTpl = $('#tpl');
function addItem(row, append) 
{
	lastId++;
    // hide and show
    // defaults
    if (!row || !row['id']) {
        row = {
            'effective_date': '2010-12-26 00:00:00+0100',
            'time_profile_id': '',
            'rate': '0.0000',
            'min_time': '1',
            'seconds': '60',
            'interval': '1',
            'grace_time': '0',
            'intra_rate': '0.0000',
            'inter_rate': '0.0000',
            'local_rate': '0.0000'
        };
    }
    // fix row values
    for (k in row) { if (row[k] == null) row[k] = ''; }
    // prepare row
	var prefixId = 'row-'+lastId;
	var prefixName = 'rates['+lastId+']';
  var tRow = eTpl.clone(true).attr('id', prefixId);//临时准备的行
  // which of   link view   we need
  if (row['id']) {
  } else {
      tRow.find('#tpl-view-row').unbind().remove();
 	 }
    // set names / values
    tRow.find('input,select').each(function () {
		var el = $(this);
        var field = el.attr('name');
		el.attr({id: prefixId+'-'+field, name: prefixName+'['+field+']'}).val(row[field]);
    });
	// set text labels
	tRow.find('#tpl-id-text').text(row['id'] ? row['id'] : '');

    if (row['id']) {
        tRow.appendTo(eRows);
    } else {
        tRow.prependTo(eRows);
    	 }
    
    // styles
    if (!row['id']) {
        initForms(tRow); 
        initList();
    }
    tRow.find('input[rel]').xkeyvalidate({type:'strNum'});
    jQuery('#noRows').hide();
    jQuery('table.list-form').show();
    jQuery('#toppage').show();
    jQuery('#tmppages').show();
}






//$('#rows').find('input[rel*=format_number]').live('keyup',function(){filter_chars(this);});
$('#rows #tpl-delete-row').live('click', function () {
	var del_rate_id=$(this).closest('tr').find('input[name*=id]').val();
	if(del_rate_id!=null&& del_rate_id!=''){
		var del_val=$('#delete_rate_id').val()+","+del_rate_id;
		$('#delete_rate_id').val(del_val);
		}

	 if(confirm("Whether to delete ?")){
  $(this).closest('tr').remove();
    //记录删除的id
	 }
	 if($('#rows tr').size()==0){
		 jQuery('table.list-form').hide();
		 jQuery('#noRows').show();
		 jQuery('#toppage').hide();
		 jQuery('#tmppages').hide();
	 }
    return false;
});


$('#rows #tpl-view-row').live('click', function () {
	var del_rate_id=$(this).closest('tr').find('input[name*=id]').val();
	if(del_rate_id!=null&& del_rate_id!=''){
		location="<?php echo $this->webroot?>systemlimits/jurisdiction_view/"+del_rate_id;
		}
   // return false;
});
// fill itesm


<?php 
		$mydata =$p->getDataArray();
		foreach ($mydata  as  $key =>$value){
			
			$id=!empty($value[0]['id'])?$value[0]['id']:'';
			$name=!empty($value[0]['name'])?$value[0]['name']:'';
		 echo "addItem({\"id\":\"$id\",\"name\":\"$name\"}, 1);\n"; 
		
	}
	?>
eRows.hide();
eRows.show();
</script>

