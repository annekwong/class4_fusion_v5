
            <?php
            $last = (int) $p->getTotalPages();
            if($last <=1) return;
            $web_root = $this->webroot;
            $base_href = $appCommon->base_href();
            $request_string = $appCommon->_request_string(array('page', 'size'));
            $req_str = !empty($request_string) ? "&{$request_string}" : '';
            $size = (int) $p->getPageSize();
            $curr = (int) $p->getCurrPage(); #当前页
            $prev = $curr - 1;
            $next = ($curr + 1) > $last ? $last : ($curr + 1);
            $first_page = <<<EOD
   <ul class="pagination">
  <li><a id="first_page" class="page-first" href="{$web_root}{$base_href}?page=1&size={$size}{$req_str}">&laquo;&laquo;</a></li>
EOD;
            $prev_page = <<<EOD
   		<li><a id="prev_page" class="page-prev"href="{$web_root}{$base_href}?page={$prev}&size={$size}{$req_str}">&laquo;</a></li>
EOD;
            $next_page = <<<EOD
   	<li> <a id="next_page" class="page-next" href="{$web_root}{$base_href}?page={$next}&size={$size}{$req_str}">&raquo;</a></li>
EOD;

            $last_page = <<<EOD
   		<li> 	<a id="last_page" class="page-last"	href="{$web_root}{$base_href}?page={$last}&size={$size}{$req_str}">&raquo;&raquo;</a></li>
EOD;

            echo $first_page;
            echo $prev_page;

            if ($p->getTotalPages() >= 10) {
                ?>
                <?php
                $page_code = $curr;
                $page_code_t = 0;
                if ($curr > $last - 10) {
                    $page_code_t = $last - 10;
                }

                for ($i = 0; $i < 10; $i++) {

                    if ($curr <= $last - 10) {
                        $page_code = $curr + $i;
                    } else {

                        $page_code = $i + 1 + $page_code_t;
                    }

                    if ($page_code > $last) {
                        break;
                    }
                    $k = $page_code;
                    $style = 'class="page"';
                    $active = '';
                    if ($k == $curr) {
                        //$style = 'class="page active"' . "style='color:red'";
                        $active = ' class="active"';
                    }
                    $page_href = <<<EOD
				<li {$active}>	<a {$style}		id="p{$page_code}"		href="{$web_root}{$base_href}?page={$k}&size={$size}{$req_str}">{$page_code}   </a>	</li>

EOD;
                    echo $page_href;
                }
                ?>
            <?php
            } else {
                ?>
                <?php
                for ($i = 0; $i < (int) $p->getTotalPages(); $i++) {
                    $k = $i + 1;
                    $style = 'class="page"';
                    $active = '';
                    if ($k == $curr) {
                        //$style = 'class="page active"' . "style='color:red'";
                        $active = ' class="active"';
                    }
                    $page_href = <<<EOD
				<li $active>	<a   {$style}   id = "p{$k}"			href="{$web_root}{$base_href}?page={$k}&size={$size}{$req_str}" >{$k}</a></li>
EOD;

                    echo $page_href;
                    ?>
                <?php
                }
            }

            echo $next_page;
            echo $last_page;
            $actionurl =
                    "{$web_root}{$base_href}?" . (urldecode($request_string));
            ?>
</ul>