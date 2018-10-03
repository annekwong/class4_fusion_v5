<style type="text/css">
    .ms-container ul.ms-list{
        width: 200px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Create New Check Route') ?></li>
</ul>

<div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:void(0);"onclick="history.back(-1)">
            <i></i>
            <?php __('Back') ?>
        </a>
</div>

<div class="innerLR" style="margin-top: 32px;">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <form action="" method="post">
                <div class="row">
                    <div class="span6 offset2">
                        <div class="widget">
                            <div class="widget-head">
                                <h4 class="heading"><?php __('Egress Trunk'); ?></h4>
                            </div>
                            <div class="widget-body">
                                <select multiple="multiple" id="egress_select" name="egress_id[]" class="width220 validate[required]" >
                                    <?php foreach($egresses_info as $client_name=>$egress_info): ?>
                                        <optgroup label="<?php echo $client_name; ?>">
                                            <?php foreach($egress_info as $egress_id=>$egress_name): ?>
                                                <option value="<?php echo $egress_id ?>"><?php echo $egress_name ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="widget">
                            <div class="widget-head">
                                <h4 class="heading"><?php __('Insert your own numbers in textarea below'); ?></h4>
                            </div>
                            <div class="widget-body">
                                <textarea style="width:100%;height:183px;" class="validate[required]" id="numbers" name="numbers"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="center">
                    <p class="stdformbutton" >
                        <?php __('Call time'); ?>&nbsp;&nbsp;
                        <input type="text" class='width25 validate[required,custom[integer],max[90],min[1]]' maxlength="2" name ='sec'>&nbsp;&nbsp;
                        <?php __('sec'); ?>.
			<?php echo $this->element('common/submit_div'); ?>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>





<script type="text/javascript">
    $(function() {
        $("#egress_select").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });
    });














</script>
