<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Create New Check Route') ?></li>
</ul>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <form id="cdr_form" enctype="multipart/form-data" method="post">
                <table class="table table-bordered">
                    <colgroup>
                        <col width="40%" >
                        <col width="60%" >
                    </colgroup>
                    <tr>
                        <td class="right"><?php __('egress') ?>:</td>
                        <td>
                            <select multiple="multiple" id="egress_id" name="egress_id[]" style="width: 50%;height:250px;" >
                                <?php
                                foreach($egress as $key=>$value){
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('insert your own numbers in textarea below') ?>:</td>
                        <td><textarea style="width:100%;height:150px;" id="numbers" name="numbers"></textarea></td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Second') ?>:</td>
                        <td><input type="text" name ='sec'></td>
                    </tr>
                </table>

                <div class="center">
                    <p class="stdformbutton" >
                        <button class="btn btn-primary"><?php __('Submit')?></button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>





<script type="text/javascript">
    $(function() {});














</script>
