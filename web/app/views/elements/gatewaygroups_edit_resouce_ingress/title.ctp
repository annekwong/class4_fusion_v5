
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <?php if(isset($_GET['query']['id_clients'])):?>
        <a class="text-primary" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo $_GET['query']['id_clients'] ?>&viewtype=client">
            <?php echo __('Carrier'); echo ' ['.$c[$_GET['query']['id_clients']].'] ';?>
        </a>
        <?php else:?>
        <a class="text-primary" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo array_keys_value($post,'Gatewaygroup.client_id') ?>&viewtype=client">
            <?php echo __('Carrier',true);?> [<?php print($c[array_keys_value($post,'Gatewaygroup.client_id')]); ?>]
        </a>
        <?php endif;?>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('edit',true);?> <?php __('Ingress')?> <font  class="editname" title="Name">   <?php echo empty($post['Gatewaygroup']['alias'])||$post['Gatewaygroup']['alias']==''?'':"[".$post['Gatewaygroup']['alias']."]"?> </font></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('edit',true);?> <?php __('Ingress')?> <font  class="editname" title="Name">   <?php echo empty($post['Gatewaygroup']['alias'])||$post['Gatewaygroup']['alias']==''?'':"[".$post['Gatewaygroup']['alias']."]"?> </font></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php $project_name=Configure::read('project_name');
    	if($project_name=='exchange'){
    	?>
        <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>gatewaygroups/view_ingress?<?php echo $this->params['getUrl']?>">
            <i></i> <?php echo __('goback',true);?>
        </a>

        <?php }else{?>
       <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href='#'  onclick="history.go(-1);
                  return false;"><i></i> <?php __('Back') ?></a>
        <?php }?>
    </div>
    <div class="clearfix"></div>