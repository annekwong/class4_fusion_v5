
<td>
  <?php  __('Carriers')?>:</td>
<td id="client_cell" ><input type="text"  
				id="query-id_clients_name" ondblclick="showClients()"  
				 value=""
				name="query[id_clients_name]" class="input in-text"/>
 <!--
  <a href="#" onclick="showClients()" ><img width="9"
				height="9" class="img-button"
				src="<?php echo $this->webroot?>images/search-small.png"/></a>-->
                <a href="#" onclick="ss_clear('client', _ss_ids_client)" ><img width="9" height="9"
				class="img-button"
				src="<?php echo $this->webroot?>images/delete-small.png"/></a></td>
