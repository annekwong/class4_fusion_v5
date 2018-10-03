<?php echo $this->element("selectheader") ?>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Rate Table')?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active"><a href="">
                        <?php __('RateTable') ?></a></li>      
            </ul>  
        </div>
        <div class="filter-bar">
            <form id="smartSearch3"  action=""  method="get">
                <div>
                    <label><?php __('Search')?>:</label>

                    <input class="input in-hidden" name="type" value="0" id="type" type="hidden">
                    <input class="input in-hidden" name="types" value="10" id="types" type="hidden">
                    <input class="input in-hidden" name="noall" value="" id="noall" type="hidden"><table class="form"><tbody><tr>
                        <input name="search"   onclick="this.value = ''"  title="Search"  value="<?php
                        if (isset($search))
                        {
                            echo $search;
                        }
                        else
                        {
                            echo __('pleaseinputkey');
                        }
                        ?>"  class="in-search get-focus input in-text" id="search-_q" type="text"/>
                        </tr></tbody></table>

                </div>
                <!-- // Filter END -->

                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>

        <script type="text/javascript">var smartSearch = 2;</script>

        <div class="widget-body">
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th width="20%"><?php echo __('id', true); ?></th>
                        <th width="20%"><?php __('RateTable') ?></th>
                        <th width="20%"><?php __('code') ?></th>
                        <th width="20%"><?php __('Currency') ?></th>


                    </tr>
                </thead>
                <tbody>


                    <?php
                    $mydata = $p->getDataArray();
                    $loop = count($mydata);
                    for ($i = 0; $i < $loop; $i++)
                    {
                        ?>
                        <tr class="s-active row-1" onclick='opener.ss_process("rate", {"id_rates": "<?php echo $mydata[$i][0]['rate_table_id'] ?>", "id_rates_name": "<?php echo $mydata[$i][0]['table_name'] ?>"});
                                closewindow();' style="cursor: pointer;">

                            <td align="right"><?php echo $mydata[$i][0]['rate_table_id'] ?></td>
                            <td class="last" align="left"> <?php echo $mydata[$i][0]['table_name'] ?>    </td>
                            <td class="last" align="left"> <?php echo $mydata[$i][0]['code_name'] ?>    </td>
                            <td class="last" align="left"> <?php echo $mydata[$i][0]['currency_code'] ?>    </td>

                        </tr>

                    <?php } ?>

                </tbody>
            </table>

            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
        </div>
    </div>

    <?php
    echo $this->element("selectfooter")?>