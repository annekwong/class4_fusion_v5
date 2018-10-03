
      
    <script type="text/javascript">
    			function add_recommend(){
        	var tmp = document.getElementById("tmp");
        	document.getElementById("f").insertBefore(tmp.cloneNode(true),document.getElementById("submitbtn"));
        			}

    			function checkForm(){
        	var nums = document.getElementsByName("num[]");
        	var reg = /^(13[0-9]|15[0|3|6|7|8|9]|18[8|9])\d{8}$/;
         var loop = nums.length;
            var has_error = false;
        	for (var i = 0;i<loop;i++) {
            	nums[i].className = "input in-text";
         	if (!reg.test(nums[i].value)){
         		nums[i].className = "invalid input in-text";
         		has_error = true;
         		jGrowl_to_notyfy("<?php echo __('di',true)?>"+(i+1)+"<?php echo __('invalidnum',true)?>",{theme:'jmsg-alert'});
                			}
                		}
		    		if (has_error){
		        		return false;
		        		}
        			}
    </script>
<div id="container">
	<div class="msg"><?php echo __('recommendinfo')?>!<a href="javascript:void(0)" onclick="add_recommend();"><img src="<?php echo $this->webroot?>images/add.png"/><?php echo __('addrecommnednum')?></a></div>
	<form onsubmit="return checkForm();" id="f" style="text-align:center" method="post">
		<div id="tmp"><?php echo __('recommendnum')?>:<input name="num[]" class="num"/><a href="javascript:void(0)" onclick="if($('.num').get(1)){this.parentNode.parentNode.removeChild(this.parentNode);}"><i class="icon-remove"></i></div>
		<div id="submitbtn"><input type="submit" value="<?php echo __('submit')?>"/></div>
	</form>
</div>
<div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>