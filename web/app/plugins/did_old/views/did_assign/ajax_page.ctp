<?php if($total_pages): ?>
    <div class="pagination pagination-large pagination-right margin-none">
        <ul class="pagination">
            <li>
                <a class="page-first" href="javascript:void(0)" value="first" page="1"
                   id="first_page">««</a>
            </li>
            <li>
                <a class="page-prev" href="javascript:void(0)" value="prev" page="<?php echo $prev_page; ?>" id="prev_page">«</a>
            </li>
            <?php for($i = 0;$i < $total_pages; $i++): ?>
                <?php if($i+1 == $page_now): ?>
                    <li class="active">
                <?php else: ?>
                    <li>
                <?php endif; ?>
                <a class="page" href="javascript:void(0)"  value="<?php echo $i+1 ?>" page="<?php echo $i+1 ?>" ><?php echo $i+1 ?></a>
                </li>
            <?php endfor; ?>
            <li>
                <a class="page-next" href="javascript:void(0)" value="next" page="<?php echo $next_page; ?>" id="next_page">»</a>
            </li>
            <li>
                <a class="page-last" href="javascript:void(0)" value="last" page="<?php echo $total_pages; ?>" id="last_page">»»</a>
            </li>
        </ul>
    </div>
<?php endif; ?>