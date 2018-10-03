<?php echo $this->element("selectheader")?>
<div id="title"><h1><?php echo __('Add Trunk',true);?></h1></div>
<div class="container"> 
   <form id="myform" name="myform" action="###" method="post">
	<p>
 	<select name="routetype" class="select in-select">
	    <option value="static"><?php __('Static Route')?></option>
	    <option value="dynamic"><?php __('Dynamic Route')?></option>
	</select>
	</p>
	<p>
	<select style="width:250px;" name="name" class="select in-select">

	</select>
	</p>
	<p>
	<input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit" />
	</p>
   </form>
</div>

<script type="text/javascript">
jQuery(function() {
   getstatic();
   $('#myform').submit(function() {
	$.ajax({
	    'url':'<?php echo $this->webroot ?>lcrans/add_trunk_post',
	    'type':'POST',
	    'dataType':'text',
	    'data':{'routetype':$('select[name=routetype]').val(),
	            'name':$('select[name=name]').val(),
		    'code':'<?php echo $this->params['pass'][0]; ?>',
		    'trunk':'<?php echo  $this->params['pass'][1]; ?>'
		   },
	    'success':function(data) {
	       window.opener=null;      
               window.open('','_self');      
               window.close();}
	});
	return false;
   });
   $('select[name=routetype]').change(function() {
       if($(this).val() == 'static') {
           getstatic();
       } else {
	   getdynamic();
       }
   });
});

function getstatic() {
  $.ajax({ 
     'url':'<?php echo  $this->webroot; ?>lcrans/getstatic',
     'type':'GET',
     'dataType':'json',
     'success':function(data) {
	var $select = $('select[name=name]');
	$select.empty();
	$.each(data, function(index, value) {
	  $select.append('<option value="'+value[0].id+'">'+value[0].name+'</option>');
	});
     }
  });
}

function getdynamic() {
  $.ajax({
     'url':'<?php echo $this->webroot; ?>lcrans/getdynamic',
     'type':'GET',
     'dataType':'json',
     'success':function(data) {
	var $select = $('select[name=name]');
        $select.empty();
        $.each(data, function(index, value) {
          $select.append('<option value="'+value[0].id+'">'+value[0].name+'</option>');
        });
     }
  });
}

</script>
<?php echo $this->element("selectfooter")?>

