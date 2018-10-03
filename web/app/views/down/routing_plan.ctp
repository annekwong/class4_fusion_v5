<style type="text/css">
    #container label {width:80px;display:block;float:left;}
    #down_panel {width:80%; margin:0 auto;}
    #option_panel {float:left; width:40%;}
    #field_panel {float:left;margin-left:100px; width:50%;}
    .buttons {text-align:center;}
</style>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing Plan') ?><font  class="editname" title="Name">
        <?php echo empty($rs_name[0][0]['name']) || $rs_name[0][0]['name'] == '' ? '' : "[" . $rs_name[0][0]['name'] . "]"; ?>
        </font></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Routing Plan') ?><font  class="editname" title="Name">
        <?php echo empty($rs_name[0][0]['name']) || $rs_name[0][0]['name'] == '' ? '' : "[" . $rs_name[0][0]['name'] . "]"; ?>
        </font></h4>
    
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <?php echo $this->element('uploads/route_plan_tabs',array('active' => 'download')) ?>
        </div>
        <div class="widget-body">
            <div id="down_panel">
                <form name="myform" method="post">
                    <div id="option_panel">
                        <fieldset>
                            <legend>Format Options</legend>
                            <p>
                                <label>Data Format:</label>
                                <select name="data_format">
                                    <option value="0">CSV</option>
                                    <option value="1">XLS</option>
                                </select>
                            </p>
                            <p>
                                <label>&nbsp;</label>
                                <input type="checkbox" name="with_header" checked="checked" />With headers row
                            </p>
                            <p>
                                <label>Header Text:</label>
                                <textarea rows="3" cols="10" name="header_text" style="width:220px;"></textarea>
                            </p>
                            <p>
                                <label>Footer Text:</label>
                                <textarea rows="3" cols="10" name="footer_text" style="width:220px;"></textarea>
                            </p>	

                        </fieldset>
                    </div>
                    <div  id="field_panel">
                        <fieldset>
                            <legend>Columns</legend>
                            <?php
                            $size = count($fields);
                            for ($i = 0; $i < $size; $i++):
                                ?>
                                <p>
                                    <label>Column #<?php echo $i + 1; ?>:</label>
                                    <select name="fields[]">
                                        <option></option>
                                        <?php for ($j = 0; $j < $size; $j++): ?>
                                            <option <?php echo $j == $i ? 'selected' : '' ?>><?php echo $fields[$j]; ?></option>
                                <?php endfor; ?>
                                    </select>
                                </p>
<?php endfor; ?>
                        </fieldset>
                    </div>
                    <br style="clear:both;" />	
                    <div class="buttons"><input type="submit" value="Download" class="btn btn-primary" /></div>
                </form>
            </div>
        </div>
    </div>
</div>