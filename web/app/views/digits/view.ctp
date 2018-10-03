<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<?php echo $this->element("digit/title")?>
<div id="container">
<?php echo $this->element("digit/container")?>
</div>
<script>
	$(document).on('click', '#add', function() {
		$('#save').attr('title', 'Save');
	});

	$(document).ready(function(){
		$('#selectAll').click(function(){
			if($(this).attr('checked')){
				$('#rows input[type="checkbox"]').attr('checked','checked');
			}else{
				$('#rows input[type="checkbox"]').removeAttr('checked');
			}
		});
	});
</script>



