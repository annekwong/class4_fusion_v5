<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $w = $session->read('writable');?>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Trunk Monitor') ?></li>
</ul>

<?php

    

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Trunk Monitor') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if($type == 'ingress'): ?> class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>gatewaygroups/egress_report/ingress" class="glyphicons right_arrow"><i></i><?php __('Origination'); ?></a></li>
                <?php
                    if($all_termination == 't'){
                ?>
                <li <?php if($type == 'egress'): ?> class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>gatewaygroups/egress_report/egress" class="glyphicons left_arrow"><i></i><?php __('Termination'); ?></a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="server_form">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Switch Server') ?>:</label>
                        <select style="width:180px;" name="server_info" id="server_info" class="input in-select select">
                         <option value=""><?php __('All')?></option>  
                         <?php foreach($server_infos as $server_info) : ?>
                            <option value="<?php echo $server_info[0]['id'] ?>" <?php if($server_info[0]['id'] == $server_id && !empty($_GET['server_info'])) echo 'selected="selected"'; ?>><?php echo $server_info[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Trunk')?>:</label>
                        <select name="id" id="id">
                            <option></option>
                            <?php foreach($resources as $resource_id => $resource_name): ?>
                            <option <?php if(isset($_GET['id']) && $_GET['id'] == $resource_id) echo 'selected' ?>><?php echo $resource_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label><?php __('Carrier')?>:</label>
                        <input type="hidden" id="query-id_clients" value="" name="query[id_clients]" class="input in-hidden">
                        <select name="query[id_clients]">
                            <option></option>
                            <?php foreach($clients as $client_id => $client_name): ?>
                            <option value="<?php echo $client_id ?>" <?php if(isset($_GET['query']['id_clients']) && $_GET['query']['id_clients'] == $client_id) echo 'selected' ?>><?php echo $client_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!--
                    <div class="pull-right">
                        <a href="###" class="filter-advance"></a>
                    </div>
                    -->
                </form>
            </div>


      <?php if (empty($lists)): ?>
      <h2 class="msg center">
          <br />
          <?php  echo __('no_data_found') ?>
      </h2>
      <?php else: ?>
    <table class="list  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
      <thead>
        <tr>
          <th class="footable-first-column expand" data-class="expand"><?php echo __('Host:Port')?>&nbsp;</th>
          <th> <?php echo __('Trunk',true);?></th>
<!--          <th><?php __('GatewayType')?></th>-->
          <th><?php __('Call Capacity')?> <a  class="sort_asc sort_sctive" href="?order=capacity&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" href="?order=capacity&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></th>
          <th><?php __('CPS')?> <a  class="sort_asc sort_sctive"  href="?order=capacity&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" href="?order=capacity&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></th>
          <th><?php echo __('ofingress')?> <a class="sort_asc sort_sctive" onclick="return false;" href="?order=ip_cnt&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" onclick="return false;" href="?order=ip_cnt&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></th>
          <th><?php echo __('usage')?> <a class="sort_asc sort_sctive" onclick="return false;" href="?order=cdr_cnt&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" onclick="return false;" href="?order=cdr_cnt&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></th>
          <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('24Hr Max Calls')?></th>
          <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('24Hr Max CPS')?></th>
          <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('24Hr Max Channel')?></th>
          <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">% <?php __('Capacity Used')?></th>
        </tr>
      </thead>
      <?php 	$mydata =$lists; $flag = true;	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
      <tbody>
        <tr class="row-<?php echo $i%2+1;?>">
          <td  class="footable-first-column expand" data-class="expand" align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>" 	onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)" class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/></td >
          <td  align="center">
              <?php if($type == 'egress'): ?> 
              <a  style="width:80%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo base64_encode($mydata[$i]['Resource']['resource_id']);?>?<?php echo $appCommon->get_request_str()?>"  class="link_width" title="<?php echo __('edit')?>">
					    	<?php echo $mydata[$i]['Resource']['alias']?>	
		 </a>
              <?php else: ?>
              <a  style="width:80%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo base64_encode($mydata[$i]['Resource']['resource_id']);?>?<?php echo $appCommon->get_request_str()?>"  class="link_width" title="<?php echo __('edit')?>">
					    	<?php echo $mydata[$i]['Resource']['alias']?>	
		 </a>
              <?php endif; ?>          
		  </td>
          <!--<td><?php if($mydata[$i]['Resource']['ingress']){__('ingress');}?>
            <?php if($mydata[$i]['Resource']['egress']){__('egress');}?></td>-->
          <td  align="center"><?php  if(empty($mydata[$i]['Resource']['capacity'])) {echo "Unlimited";}else{echo number_format( $mydata[$i]['Resource']['capacity'],0); }?></td>
          <td  align="center"><?php  if(empty($mydata[$i]['Resource']['cps_limit'])) {echo "Unlimited";}else{echo number_format( $mydata[$i]['Resource']['cps_limit'],0); }?></td>
          <td align="center"><?php echo count($lists[$i]['ResourceIp'])?></td>
          <td align="center">
          <!--<td align="center"><a  href="<?php echo $this->webroot?>realcdrreports/summary_reports/<?php echo $type ?>/<?php echo $mydata[$i]['Resource']['resource_id']?>?type=egress_report"  title="<?php echo __("{$type}call")?>"> -->
            <?php 
//            if($flag) {
//            $usage = $common->get_trunk_count($type, $mydata[$i]['Resource']['resource_id'], $ip, $port);
//                if($usage == 'error')
//                {
//                    $flag = false;
//                    echo 0;
//                } else {
//                    echo $usage;
//                }
//            } else {
//                echo 0;
//            }
            
            ?></td>
            <?php
                $sql = "select max(call) as call24, max(cps) as cps24, max(channels) as channel24 from qos_resource where 

res_id = {$mydata[$i]['Resource']['resource_id']} and 

report_time between CURRENT_TIMESTAMP - interval '24 hours'  and CURRENT_TIMESTAMP";

                $result = $Resource->query($sql);
                
                
            ?>
            <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $result[0][0]['call24'] ?></td>
            <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $result[0][0]['cps24'] ?></td>
            <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $result[0][0]['channel24'] ?></td>
            <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                <?php echo empty($mydata[$i]['Resource']['capacity']) ? 'Unlimited' :  $usage /$mydata[$i]['Resource']['capacity']  ?>
            </td>
        </tr>
        <tr>
          <td colspan="20" style="padding:0;line-height:0;border:none;"><div id="ipInfo<?php echo $i?>" style="display:none;margin:10px" class="jsp_resourceNew_style_2" >
              <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <tr>
                  <th><?php echo __('Host',true);?></th>
                  <th><?php echo __('IP',true);?></th>
                  <th><?php echo __('Port',true);?></th>
                  <th><?php echo __('CPS',true);?></th>
                  <th><?php echo __('used',true);?></th>
                </tr>
                <?php $iR=0?>
                <?php foreach($lists[$i]['ResourceIp'] as $resourceIp):?>
                <?php $iR++?>
                <tr>
                  <td><?php echo $iR?></td>
                  <td><?php echo $resourceIp['ip']?></td>
                  <td><?php echo $resourceIp['port']?></td>
                  <?php
//                    if($flag) {
//                         list($cps, $usage) = $common->get_trunk_ip_count($type, $resourceIp['resource_ip_id'], $ip, $port);
//                    } else {
//                         $cps = 0;
//                         $usage = 0;
//                    }
                       
                  ?>
                  <td><?php echo $cps; ?></td>
                  <td><?php echo $usage; ?></td>
                </tr>
                <?php endforeach?>
              </table>
            </div>
            <div style="height: 0px; clear: right;"></div></td>
        </tr>
      </tbody>
      <?php }?>
    </table>
    <div class="bottom row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('xpage'); ?>
        </div> 
     </div>
    <?php endif; ?>
</div>
</div>
</div>
<script type="text/javascript">
//<![CDATA[

var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};

function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}



function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>

$(function() {
    $('#server_info').change(function (){
        $('#server_form').find('button[name=submit]').click();
        //$('#server_form').submit();
    });
});

</script>