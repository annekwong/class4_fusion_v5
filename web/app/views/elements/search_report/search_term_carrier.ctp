
<td>
  <?php  __('Carriers')?>:</td>
<td id="client_cell">
<input type="text" id="query-id_clients_name_term" ondblclick="showClients_term()" value="" name="query[id_clients_name_term]" class="input in-text"/>
  <!--
  <a href="#"  onclick="showClients_term()"><img width="9" height="9" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/> </a>
  -->
  <a href="#" onclick="ss_clear('client_term', _ss_ids_client_term)">
  <img width="9" height="9"  class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"/></a></td>
