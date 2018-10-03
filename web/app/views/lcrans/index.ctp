<style type="text/css">
    .checkboxdiv {
        float:left;
        margin:10px 10px 10px 150px;
    }
    .checkboxdiv label{
        display:block;
    }
    .checkboxothers {
        float:left;
        margin-top:10px;
        margin-left:150px;
        text-align: left;
        overflow:hidden;
        width: 100%;
    }
    .cb_select label{ float:left;width:230px;}

</style>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LCR Analysis') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('LCR Analysis')?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


        <div class="widget-body">

            <div class="container">
                <?php if (isset($p)): ?>
                    <?php
                    if (!empty($p)) :
                        $data = $p->getDataArray();
                        ?>
                       <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                                <tr>
                                <tr>
                                    <th><?php echo __('code', true); ?></th>
                                    <th><?php echo __('Min', true); ?></th>
                                    <th><?php echo __('max', true); ?></th>
                                    <th><?php echo __('avg', true); ?></th>
                                    <?php for ($i = 0; $i < $maxfields; $i++): ?>
                                        <th><?php __('Trunk')?>-<?php echo $i + 1; ?></th>
                                    <?php endfor; ?>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $item): ?>
                                    <tr>
                                        <?php for ($i = 0; $i <= $maxfields + 3; $i++): ?>
                                            <?php if ($i > 3): ?>
                                                <td><a href="###" class="addtrunk"><?php echo isset($item[$i]) ? $item[$i] : '&nbsp'; ?></a></td>
                                            <?php else: ?>
                                                <td><?php echo isset($item[$i]) ? $item[$i] : '&nbsp'; ?></td>
                                            <?php endif ?>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                    <?php else: ?>
                        <div class="msg" style="margin-bottom: 20px;width:550px;"><?php __('No Egress Found For Specified Code and Rate can be found')?>.</div>
                    <?php endif; ?>
                <?php endif; ?>

                <fieldset class="query-box">
                    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('Search')?></h4>
                    <div class="pull-right" title="Advance">
                        <a id="advance_btn" class="btn" href="###">
                            <i class="icon-long-arrow-down"></i> 
                        </a>
                    </div>

                    <div class="form">
                        <form method="post" name="myform" id="myform">
                            <div class="checkboxothers">
                                <?php __('Type')?>:
                                <select name="type">
                                    <option value="false" <?php if (isset($_POST['type']) && $_POST['type'] == "false") echo 'selected="selected"' ?>><?php __('Standard View')?></option>
                                    <option value="true" <?php if (isset($_POST['type']) && $_POST['type'] == "true") echo 'selected="selected"' ?>><?php __('LCR View')?></option>
                                </select>
                                <?php __('Show Type')?>:
                                <select name="show_type">
                                    <option value="0"><?php __('WEB')?></option>
                                    <option value="1"><?php __('CSV')?></option>
                                </select>&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary margin-bottom10" value="<?php echo __('query', true); ?>" />
                            </div>
                                

                            <div id="advance_panel" class="checkboxdiv" style="display:none">
                                <div class="cb_select input">
                                    <?php foreach ($trunks as $trunk): ?>
                                        <label><input type="checkbox" name="trunks[]" value="<?php echo $trunk[0]['resource_id']; ?>"
                                            <?php if (isset($_POST['trunks'])):if (in_array($trunk[0]['resource_id'], $_POST['trunks'])) echo 'checked="checked"';endif; ?> 

                                                      />&nbsp;<?php echo $trunk[0]['alias'] ?></label>
                                        <?php endforeach; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </fieldset>

            </div>
        </div>
    </div>
</div>

        <script type="text/javascript">
            $('#myform').bind('submit', function() {
                var size = $('input[name=trunks[]][checked]').size();
                if (size == 0) {
                    alert("Please select egress trunks!");
                    return false;
                }
            });

            $('.addtrunk').click(function() {
                var text = $(this).text();
                var textarr = text.split('(');
                text = textarr[0];
                var code = $(this).parent().parent().find("td:first-child").text();
                window.open('<?php echo $this->webroot ?>lcrans/add_trunk/' + code + '/' + text, 'clientcdr',
                        'height=220,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
                return false;
            });
        </script>
