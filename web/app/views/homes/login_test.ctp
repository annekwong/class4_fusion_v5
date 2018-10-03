    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php echo $html->charset ();?>
    <title>Login :: Class4</title>
    <link href="<?php echo $this->webroot?>favicon.ico" type="image/x-icon" rel="shortcut Icon">   
    <link href="<?php echo $this->webroot?>css/shared.css" rel="stylesheet" type="text/css"> 
    <link href="<?php echo $this->webroot?>css/jquery.jgrowl.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->webroot?>css/admin.css" rel="stylesheet" type="text/css">
    <script src="<?php echo $this->webroot?>js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot?>js/jquery.jgrowl.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot?>js/util.js" type="text/javascript"></script>
    <script type="text/javascript">
    	function save_login(){
        	if (document.getElementById('savelogin').checked == true){
	          if (jQuery('#username').val()!= ''){
	          	setCookie('sst_username',jQuery('#username').val());
	          }
	          if (jQuery('#password').val()!= ''){
		        setCookie('sst_password',jQuery('#password').val());
		      }
		      if(jQuery('#username').val()!= '' && jQuery('#password').val()!= ''){
			    setCookie('sst__'+jQuery('#username').val(),jQuery('#password').val());
			  }
			 } else {
        	  setCookie('sst_username','');
        	  setCookie('sst_password','');
        	  setCookie('sst__'+jQuery('#username').val(),'');
        	 }
        }
    	jQuery(document).ready(function(){
			jQuery('#username').keyup(function(){
				if(getCookie('sst__'+jQuery('#username').val()))
				{
					jQuery('#password').val(getCookie('sst__'+jQuery('#username').val()));
				}
			});
        });
    </script>
    </head>
<body>
		<center>	
			<div id="container" style="display:none">
			<form method="post" action="<?php echo $this->webroot?>homes/auth_user" id="login_form" name="login_form"  onsubmit="save_login();">
			    <div id="logo">
			    	<img  alt="" src="<?php echo $this->webroot?>images/logo.png">
			    </div>
			    <label style="float:left"><?php echo __('UserName',true);?>:</label><input type="text" id="username" value="<?php echo $f['username'];?>" name="username" class="input in-text">    
			    <label style="float:left"><?php echo __('Password',true);?>:</label><input type="password" id="password" value="<?php echo $f['password'];?>" name="password" class="input in-password">
			    <div id="captcha">
			        <label style="float:left;padding-top:5px;width:110px;padding-bottom:2px">
			        	<img src="<?php echo $this->webroot ?>homes/validate_code" align="absmiddle"/>
			        </label>
	        		<input type="text" id="auth-captcha" autocomplete="off" value="" name="captcha" class="input in-text">
	        	</div>
			    <label style="float:left"><?php echo __('Language',true);?>:</label><select id="lang" name="lang"  class="input in-select">
			    <option value="chi"><?php echo __('chinese')?></option><option value="eng"  selected="selected">English</option></select>
			    <p style="text-align:center;">
			    	<input type='checkbox'   id="savelogin"/><?php echo __('Save Password',true);?>
			    </p>    
			    <p style="text-align:center;">
			    	<input id="login" type="submit" value="<?php echo __('Login',true);?>" class="input in-submit">&nbsp;&nbsp;
			    	<input  type="reset" <?php echo __('reset',true);?> class="input in-submit">
			    </p>
			</form>
			</div>
			<div id="footer"><strong>ICX</strong> &copy;2010 <a target="_blank" href="">ICX</a> development. All Rights Reserved.</div>
		</center>
		<?php
			$msg = $session->read('login_failed');
			if (!empty($msg)) {
				$session->del('login_failed'); 
		?>
		<?php if(isset($msg)):?>
			<div id="showmessages" style="text-align: center;"></div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery.jGrowl.defaults.position = 'top-center';
					jGrowl_to_notyfy("<?php echo $msg?>",{theme:'jmsg-alert'});
				});
     		</script>
		<?php endif?>
		<?php } ?>
			<script type="text/javascript">
				var s_name = getCookie('sst_username');
				var s_pass = getCookie('sst_password');
				if (s_name && s_pass){
					document.getElementById('username').value = s_name;
					document.getElementById('password').value = s_pass;
				}
			</script>
			<script type="text/javascript">
			jQuery(document).ready(
					function(){
						var m=eval("<?php if(isset($m)){echo $m;}?>");
						if(m!='')
						{
							var params={};
							for(i in m){
								if(m[i].code=='201')
								{
									params.theme='jmsg-success';
								}
								    jGrowl_to_notyfy(m[i].msg,params);
							}
						}
					}
			);
			</script>
			
			<script type="text/javascript"><!--
			jQuery(document).ready(function(){
			
			    if(getCookie('name')!=''&&getCookie('password')!=""){
            jQuery('#username').attr('value',getCookie('name'));
            jQuery('#password').attr('value',getCookie('password'));
			         }
			
			});
			
			
				jQuery('#login').mousedown(function(){
					  if(jQuery('#savelogin').attr('checked')==true){
					       var name=jQuery('#username').attr('value');
					       var password=jQuery('#password').attr('value');
					       setCookie(name,7);setCookie(password,7);
					    }
					  
					
					});
					
					
					
					
					
							
				//存Cookie		
				function setCookie(c_name,value,expiredays)
				{
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie=c_name+ "=" +escape(value)+
				((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
				}
				//查看cookie是否存在,并把取出来
				function getCookie(c_name)
				{
				if (document.cookie.length>0)
				  {
				  c_start=document.cookie.indexOf(c_name + "=");
				  if (c_start!=-1)
				    { 
				    c_start=c_start + c_name.length+1 ;
				    c_end=document.cookie.indexOf(";",c_start);
				    if (c_end==-1) c_end=document.cookie.length;
				    return unescape(document.cookie.substring(c_start,c_end));
				    } 
				  }
				return "";
				}
				
		
		$("#login_form").submit();
			
			
			</script>
			
			
			
			
			
			
			
			
	</body></html>