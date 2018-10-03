<div style="padding: 10px;">
		<div id="context_right_form_div">
		    <div class="return"><a class="link" href="./prolist.html"><span>Back to Product List</span></a></div>
			<div style="padding: 10px;">
			<div class=" product_delete_style_0">
              <form method="post" action="propt.html?cmd=del" id="productForm">
                
                <div class=" product_delete_style_1">
                  
                  
                  
                  
                  
                  	 Note:Do you want to delete this object ?<br><br>
                  
                  

                
	                <input type="hidden" value="1" name="obtCount">
	                  
	                
	                	<input type="hidden" value="52" name="obt0">
	                	
	                  		If you delete these object,the items included will be delete at the same moment!
	                  		
	                 	
	                 	
	                
                 </div>
                
                <div class=" product_delete_style_2">
                  <div class=" product_delete_style_3">
                    <input type="hidden" value="<?php echo __('submit',true);?>" name="Submit">
                  	<button onclick="document.forms[&quot;productForm&quot;].submit();">Yes</button>

                  
                   
                  

                    <button onclick="javascript:window.location=&quot;http://192.168.1.115:8000/prolist/1.html&quot;;" type="reset">No</button>

                  
                  
                  
                  
					</div>                  
                </div>
                
              </form>
			</div>	
	</div>		
	</div>
			
		</div>