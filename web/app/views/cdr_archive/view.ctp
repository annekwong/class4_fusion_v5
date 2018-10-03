<div id="title">
	<h1>Cdr Archive</h1>
	<?php echo $this->element("search")?>
</div>
<div id="container">

<table class="cols"><col width="60%" /><col width="40%"/><tr>
<td>

<fieldset><legend>Cdr Active</legend>
<?php if(empty($cdr_table)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<?php //echo $this->element("page")?>
        <form action="backup" method="post" id="statistics-form">
	<table class="list" id="table_1">
	<thead>
	<tr>
		<td><input type="checkbox" class="input in-checkbox" name="selector-1" value="1" onchange="switchSelection('all');" id="selector-1">
        </td>
		<td>date</td>
		<td>total legs</td>
		<td><B>rotate</B></td>
		<td>bakeup file</td>
	</tr>
	</thead>
		
	<?php foreach($cdr_table as $k=>$v):?>
	<tbody>
    <tr class="totals " style="font-weight:bold;cursor:pointer;" id="p-<?php echo $k;?>">
        <td align="center">
        <input type="checkbox" class="input in-checkbox" name="selector" value="1" onchange="switchSelection('p-<?php echo $k;?>');">
        <img src="<?php echo $this->webroot;?>images/list-sort-desc.png" width="9" height="9" /></td> 
        <td align="center"><?php echo date("M,Y", strtotime($k) );?></td>       
        <td align="center">0</td>
        <td></td>
        <td align="center"></td>
    </tr>
  </tbody>
  <tbody id="p-<?php echo $k;?>-block" style="display:none;">
 <?php foreach ($v as $k0=>$v0):?>
	<tr>
		<td align="center"><input type="checkbox" class="input in-checkbox" name="sel_date[]" id="ids-2234" value="<?php echo $v0['name'];?>"></input></td>
		<td><?php echo $v0['name'];?></td>
		<td><?php echo 0;?></td>
		<td><B>to rotate</B></td>
		<td><?php if ('' != $v0['filepath']):?>
		<a href="<?php echo $this->webroot;?>invoices/download_file/<?php echo str_replace('/','|',$v0['filepath']); ?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/attached_cdr.gif"></a>
   		 <?php endif;?>
   </td>
	</tr>
	<?php endforeach;?>
	<?php endforeach;?>
	</tbody>
	</table>
	<div class="form-buttons"><input type="submit" value="Process" /></div>
    </form>
    </fieldset>
<?php //echo $this->element("page")?>
<?php endif;?>
</td><td>

    <fieldset><legend>Archived Statistics Packages</legend>
    <?php if(empty($files)):?>
            <div class="msg">There are no archive packages</div>
    <?php else:?>
           <form action="rotate" method="post" id="statistics-form_1">
	<table class="list" id="table_2">
	<thead>
	<tr>
		<td><input type="checkbox" class="input in-checkbox" name="selector_2" value="1" onchange="switchSelection_1('all');" id="selector_2">
        </td>
		<td>date</td>
		<td><B>rotate</B></td>
		<td>bakeup file</td>
	</tr>
	</thead>
		
	<?php foreach($files as $k=>$v):?>
	<tbody>
    <tr class="totals " style="font-weight:bold;cursor:pointer;" id="p1-<?php echo $k;?>">
        <td align="center">
        <input type="checkbox" class="input in-checkbox" name="selector" value="1" onchange="switchSelection_1('p1-<?php echo $k;?>');">
        <img src="<?php echo $this->webroot;?>images/list-sort-desc.png" width="9" height="9" /></td> 
        <td align="center"><?php echo date("M,Y", strtotime($k) );?></td>       
        <td></td>
        <td align="center"></td>
    </tr>
  </tbody>
  <tbody id="p1-<?php echo $k;?>-block" style="display:none;">
 <?php foreach ($v as $k0=>$v0):?>
	<tr>
		<td align="center"><input type="checkbox" class="input in-checkbox" name="sel_date_1[]" id="ids-2234" value="<?php echo str_replace(array('pgsql_', '.sql'), '', $v0);?>"></input>
		</td>
		<td><?php echo str_replace(array('pgsql_', '.sql'), '', $v0);?></td>
		<td><B>to rotate</B></td>
		<td>
		<a href="<?php echo $this->webroot;?>invoices/download_file/<?php echo str_replace('/','|',$v0); ?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/attached_cdr.gif"></a>
   </td>
	</tr>
	<?php endforeach;?>
	<?php endforeach;?>
	</tbody>
	</table>
	<div class="form-buttons"><input type="submit" value="Process" /></div>
    </form>
    </fieldset>
	<?php endif;?>

</td>
</tr></table>
	

</div>
<script type="text/javascript">
//<![CDATA[
$(function () {
    $('.list .totals').unbind('click');
    $('.list .totals').click(function (e) {
        e.stopPropagation();
        $('#'+this.id+'-block').toggle();
    });
    
    $('.list input[type=checkbox]').bind('click', function (e) {
        e.stopPropagation();
    });
    
    $('.list .totals input[name^=to_rotate_global]').bind('change', function (e) {
        var id = $(this).closest('.totals').attr('id');
        $('.list #'+id+'-block input[name^=to_rotate]').attr('checked', $(this).attr('checked'));
        if ($(this).attr('checked')) {
            $('#'+id+'-block').show();
        } else {
            $('#'+id+'-block').hide();
        }
    });
    $('.list .totals input[name^=to_delete_global]').bind('change', function (e) {
        var id = $(this).closest('.totals').attr('id');
        $('.list #'+id+'-block input[name^=to_delete]').attr('checked', $(this).attr('checked'));
        if ($(this).attr('checked')) {
            $('#'+id+'-block').show();
        } else {
            $('#'+id+'-block').hide();
        }
    });
    $('.list .totals input[name^=to_restore_global]').bind('change', function (e) {
        var id = $(this).closest('.totals').attr('id');
        $('.list #'+id+'-block input[name^=to_restore]').attr('checked', $(this).attr('checked'));
        if ($(this).attr('checked')) {
            $('#'+id+'-block').show();
        } else {
            $('#'+id+'-block').hide();
        }
    });
    $('.list .totals input[name^=to_delfile_global]').bind('change', function (e) {
        var id = $(this).closest('.totals').attr('id');
        $('.list #'+id+'-block input[name^=to_delfile]').attr('checked', $(this).attr('checked'));
        if ($(this).attr('checked')) {
            $('#'+id+'-block').show();
        } else {
            $('#'+id+'-block').hide();
        }
    });

    $('#statistics-form').submit(function() {
        var show_alert = 0;
        $("input[name^='to_delete']").each(function() {
            if ($(this).attr('checked')) show_alert = 1;
        });
        if (show_alert) {
            return confirm("Please remember, when you delete statistics package, all respective payments will be removed too and client's balances rolled back appropriately. This will not happen, if you will rotate package instead. Proceed with deleting a package?");
        }
        return true;
    });
        
});

function switchSelection(id)
{
	var t;
	if (id == 'all')
	{
    t = $('#container .list :checkbox');    
		 $('#table_1').find('tbody :checkbox').attr('checked', t.attr('checked'));
    //t.closest('table').find('#'+id+'-block :checkbox').attr('checked', t.attr('checked'));
	}
	else
	{
		t = $('#container .list #'+id+' :checkbox');
	 t.closest('table').find('#'+id+'-block :checkbox').attr('checked', t.attr('checked'));
	}
}

function switchSelection_1(id)
{
	var t1;
	if (id == 'all')
	{
    //t1 = $('#container .list :checkbox');
		 t1 = $('#selector_2');
    $('#table_2').find('tbody :checkbox').attr('checked', t1.attr('checked'));
    //t1.closest('table').find('tbody :checkbox').attr('checked',t1.attr('checked'));
	}
	else
	{
		t1 = $('#container .list #'+id+' :checkbox');
	 t1.closest('table').find('#'+id+'-block :checkbox').attr('checked', t1.attr('checked'));
	}
}

//]]>
</script> 