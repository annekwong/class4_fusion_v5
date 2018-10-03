<style type="text/css">
    #container label {width:80px;display:block;float:left;}
    #down_panel {width:80%; margin:0 auto;}
    #option_panel {float:left; width:40%;}
    #field_panel {float:left;margin-left:100px; width:50%;}
    .buttons {text-align:center;}
</style>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <?php foreach ($header as $header_item): ?>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php __($header_item) ?></li>
    <?php endforeach; ?>
</ul>


<div class="heading-buttons">
    <h4 class="heading">Export</h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li><a href="<?php echo $this->webroot ?>codedecks/codes_list/<?php echo base64_encode($code_deck_id); ?>" class="glyphicons list"><i></i><?php __('Code Deck List'); ?></a></li>
                <li><a href="<?php echo $this->webroot ?>uploads/code_deck/<?php echo base64_encode($code_deck_id) ?>" class="glyphicons upload"><i></i><?php __('Import'); ?></a></li>
                <li class="active"><a href="<?php echo $this->webroot ?>down/code_deck/<?php echo base64_encode($code_deck_id) ?>" class="glyphicons download"><i></i><?php __('Export'); ?></a></li>
            </ul>
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
                    <div class="buttons"><input type="submit" class="btn btn-primary" value="Download" /></div>
                </form>
            </div>
        </div>
    </div>
</div>