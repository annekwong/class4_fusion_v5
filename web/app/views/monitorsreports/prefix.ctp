<style type="text/css">
.group-title.bottom {
-moz-border-radius:0 0 6px 6px;
border-top:1px solid #809DBA;
margin:15px auto 10px;
}
.list td.in-decimal {
text-align:center;
}

.value input, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select {
-moz-box-sizing:border-box;
width:100px;;
}

.list {
font-size:1em;
margin:0 auto 20px;
width: 100%;
}

#container .form {
margin:0 auto;
width:750px;
}
</style>
<div id="title">
            <h1>

<?php echo __('Prefix Report',true);?>
                        </h1>
        


	    		
<ul id="title-menu">
<li><font class="fwhite"><?php echo __('Refresh Every',true);?>:</font>
      <select id="changetime">
        <option value="180">3 <?php __('minutes')?></option>
        <option value="300">5 <?php __('minutes')?></option>
        <option value="800">15 <?php __('minutes')?></option>
      </select>
    </li>
		<li>
			<a class="link_back" href="<?php echo $this->webroot; ?>clients/index">
	<img width="16" height="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" alt="Back">
	&nbsp;<?php __('Back')?></a>    	</li>
 	</ul>


    

        

    </div>

<div id="container">
<ul class="tabs">
<li ><a href="<?php echo $this->webroot?>monitorsreports/globalstats"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php echo __('globlestatus')?></a></li>
<li  class="active"><a href="<?php echo $this->webroot?>monitorsreports/productstats"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo __('routestatus')?></a>  </li>
<li><a href="<?php echo $this->webroot?>monitorsreports/carrier/ingress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('ingress')?></a>        </li>
<li  ><a href="<?php echo $this->webroot?>monitorsreports/carrier/egress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('egress')?></a>        </li>
</ul>

<?php echo $this->element('stock/prefix_stock'); ?>   

<?php echo  $this->element('qos/list_table',array('name_param'=>"#",'name_118n'=>'Prefix Route Name'))?>
  
  

 
  
 
    

            
    
               
            
           
           
           
    

       
            
 
 
  
        
        
        

 
        
        


    
    
     

</div>
<div>

</div>

    

<script type="text/javascript">

$(function() {
    var interv = null;

    $('#changetime').change(function() {
        if(interv) 
            window.clearInterval(interv);
        var time = $(this).val() * 1000;
        interv = window.setInterval("loading();window.location.reload()", time); 
    });

    $('#changetime').change();
});
        
</script>