
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Domestic Exchange') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Domestic Exchange</h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" title="<?php echo __('createroletitle') ?>"  href="<?php echo $this->webroot ?>dexs/add"><?php echo __('createnew') ?> 
            <i></i></a>
        <a class="btn btn-default btn-icon glyphicons btn-inverse left_arrow" onclick="history.go(-1);">
            <i></i>
            Back
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <!-- <div id="toppage"></div>-->
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)) {
                ?>
                <div class="msg"><?php echo __('no_data_found', true); ?></div>
            <?php } else {
                ?>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th width="15%"><span> <?php echo __('DEX Name', true); ?> </span></th>
                            <th><span> <?php echo __('egress', true); ?> </span></th>
                            <th width="10%"><span><?php echo $appCommon->show_order('dex_prefix', __('Prefix', true)) ?> </span></th>
                            <th width="10%"><span> <?php echo __('action', true); ?> </span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            ?>
                            <tr>
                                <td><span><?php echo $mydata[$i][0]['dex_name']; ?></span></a></td>
                                <td><div class="table_li">
                                        <ul>
                                            <li><?php echo $mydata[$i][0]['resource_alias']; ?></li>

                                        </ul>
                                    </div></td>
                                <td><span><?php echo $mydata[$i][0]['dex_prefix']; ?></span></td>

                                <td>
                                    <a href="<?php echo $this->webroot; ?>dexs/add/<?php echo $mydata[$i][0]['id']; ?>" title="Edit"><i class="icon-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo $this->webroot; ?>dexs/ex_dele_dex_resource/<?php echo $mydata[$i][0]['dex_resource_id']; ?>" title="Del Resource"><i class='icon-remove'></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>
        </div>
    </div>
</div>
