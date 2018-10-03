<div class="product_list">
    <div id="pager">
        <a id="prev-page" href="###">
            <img src="<?php echo $this->webroot ?>images/previous-page.png" />
        </a>
        <a id="next-page" href="###">
            <img src="<?php echo $this->webroot ?>images/next-page.png" />
        </a>
    </div>
    <div id="search_panel_name">
        <input id="pop_search_name" type="text" name="name" class="input in-text in-input" style="width:250px;" />
    </div>
    <ul>
        <?php foreach($products as $product): ?>
        <li><a href="###" class="product_items" itemvalue="<?php echo $product[0]['product_id']; ?>"><?php echo $product[0]['name']; ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>