<?php if(!empty($xml_data)){?>
<div class="group-title bottom"><a onclick="$('#charts_holder').toggle();return false;" href="#">
<?php echo __('DEBUG XML',true);?></a>


</div>


<div style="display: none;" id="charts_holder">
<pre><?php
echo  	htmlspecialchars($xml_data);?></pre>

</div>

<?php }?>