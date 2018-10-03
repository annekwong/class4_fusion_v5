<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>import_rule/view">
        <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $this->pageTitle; ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">
        <?php if ($this->data['rule_name'])
        { ?>
        <?php echo __('Edit Rule', true); echo "[".$this->data['rule_name']."]"; ?>
<?php }else{ ?>
        <?php echo __('Add Rule', true); ?>
<?php } ?>
    </h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
<?php echo $this->element('xback', Array('backUrl' => 'import_rule/view')) ?>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="wizard">
        <div class="widget widget-tabs widget-tabs-double widget-tabs-gray widget-body-white">
            <div class="widget-head">
                <ul>
                    <li class="active">
                        <a class="glyphicons paperclip step" id="step1"  data-toggle="tab" href="#tab1-2">
                            <i></i>
                            <span class="strong"><?php __('Step 1'); ?></span>
                            <span><?php __('Base Info'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js projector step" id="step2" hit="" data-toggle="tab" href="#tab2-2" >
                            <i></i>
                            <span class="strong"><?php __('Step 2'); ?></span>
                            <span><?php __('Attachment Handling'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js tag step" id="step3"  hit=""   data-toggle="tab" href="#tab3-2">
                            <i></i>
                            <span class="strong"><?php __('Step 3'); ?></span>
                            <span><?php __('Violation Check'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a class="glyphicons no-js tint step" id="step4" data-toggle="tab" href="#tab4-2">
                            <i></i>
                            <span class="strong"><?php __('Step 4'); ?></span>
                            <span><?php __('Special Handling'); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="widget-body">
                <form method="post" id="rule_form" >
                    <div class="tab-content">
                        <div id="tab1-2" class="tab-pane active">
                            <?php echo $this->element("import_rule/step1") ?>
                        </div>
                        <div id="tab2-2" class="tab-pane">
                            <?php echo $this->element("import_rule/step2") ?>
                        </div>
                        <div id="tab3-2" class="tab-pane">
                            <?php echo $this->element("import_rule/step3") ?>
                        </div>

                        <div id="tab4-2" class="tab-pane">
                            <?php echo $this->element("import_rule/step4") ?>
                        </div>

                    </div>
                    <input type="hidden" id="step_" value="1" />

                </form>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
   $(document).ready(function(){
        $('#myform').find('select:not(.no-select2)').select2();
        $('#read_effective_rate_from_subject').on('change',function(){
            if($(this).val() == 2){
                $('#effect_rate_keyword').closest('tr').show();
            }else{
                $('#effect_rate_keyword').closest('tr').hide();
            }
        }).trigger('change');

        $('.date_pattern').on('change',function(){
            if($(this).val()){
                $('#date_pattern').val($(this).val()).hide();
                $('#date_pattern').next().hide();
            }else{
                $('#date_pattern').show();
                $('#date_pattern').next().show();
            }
        }).trigger('change');

        $('#is_link').on('change',function(){
            if($(this).is(':checked')){
                $('#link_text').show();
            }else{
                $('#link_text').val('').hide();
            }
        }).trigger('change');

        $('#special').on('change',function(){
            if($(this).val() == 'true'){
                $('#special_rule_case').closest('tr').show();
            }else{
                $('#special_rule_case').closest('tr').hide();
            }
        }).trigger('change');

        $('#violation_action').on('change',function(){
            if($(this).val() == 1){
                $('#min_lead_time').show().next().text('days');
            }else{
                $('#min_lead_time').hide().next().text('');
            }
        }).trigger('change');

        $('#multiple_codes').on('change',function(){
            if($(this).val() == 'true'){
                $('#code_delimiter').show();
            }else{
                $('#code_delimiter').hide();
            }
        }).trigger('change');

   });

</script>

