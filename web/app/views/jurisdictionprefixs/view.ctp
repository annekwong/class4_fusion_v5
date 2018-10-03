<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<?php echo $this->element("jur_prefix/title") ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a href="<?php echo $this->webroot ?>jurisdictionprefixs/view" class="glyphicons list">
                        <i></i> <?php echo __('List', true); ?>
                    </a>
                </li>
                <?php if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_x']) { ?>
                    <li>
                        <a href="<?php echo $this->webroot ?>uploads/jur_country"  class="glyphicons upload">
                            <i></i> <?php echo __('Import', true); ?>
                        </a>
                    </li> 
                    <li>
                        <a href="<?php echo $this->webroot ?>down/jurisdiction"  class="glyphicons download">
                           <i></i> <?php echo __('Export', true); ?>
                        </a>
                    </li>  
                <?php } ?> 
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" title="<?php __('Search')?>" class="in-search default-value input in-text defaultText" id="search-_qs" placeholder="Search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button class="btn query_btn" name="submit"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <?php echo $this->element("jur_prefix/container") ?>
        </div>
    </div>
</div>