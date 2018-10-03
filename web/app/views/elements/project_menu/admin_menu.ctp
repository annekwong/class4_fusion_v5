<?php

$login_type = $session->read('login_type');
$project_name = Configure::read('project_name');

if (PRI && $session->read('login_type') == 1) {
    $role_menu = $_SESSION['role_menu'];
    $menu_status = $_SESSION['menu_status'];
    if (!empty($role_menu)) {
        echo "<ul class=\"topnav pull-left\">";
        foreach ($role_menu as $k => $v) {
            if (isset($menu_status[$k]) && !$menu_status[$k]["status"]) {
                 continue;
            }
            if (__($k, TRUE) == 'Payment_Invoice')
                    continue;
            if (Configure::read('system.type') == 1 && __($k, TRUE) == 'Exchange Manage') 
                    continue;
            echo '<li class="dropdown dd-1"><a href="" class="admin_nav" data-toggle="dropdown">'. __($k, TRUE) .' <span class="caret"></span></a>';
            echo "<ul class=\"dropdown-menu pull-left\">";
            foreach ($v as $k1 => $v1) {
                if (!empty(trim($v1['pri_url'])) && $v1['model_r'] == 't'  && $v1['pri_val'] != 'US OCN/LATA') {
                    echo "<li><a href=\"", $this->webroot, trim($v1['pri_url']), "\">", __($v1['pri_val']), "</a></li>";
                }
            }

            echo "</ul>\r\n</li>";
        }
        echo "</ul>";
    }
}
                        