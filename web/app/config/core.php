<?php

if (!defined('CONF_PATH') && file_exists(ROOT . '/../etc/dnl_softswitch.ini')) {
    define('CONF_PATH', realpath(ROOT . '/../etc/dnl_softswitch.ini'));
} elseif (!file_exists(ROOT . '/../etc/dnl_softswitch.ini')) {
    ini_set('display_errors', 1);
    trigger_error(__("Couldn't found config file!", true), E_USER_ERROR);
}

$sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
foreach ($sections as $section_key => &$section) {
    foreach ($section as $item_key => &$item) {
        while (strpos($item, '$') !== FALSE) {
            list($key, $value) = explode('.', $item);
            $item = @$sections[$key][trim($value, '$')];
        }
    }
}


Configure::write('debug', $sections['web_base']['debug_level']);
Configure::write('project_name', 'partition');
Configure::write('App.encoding', 'UTF-8');
define('LOG_ERROR', 2);
Configure::write('Session.save', 'php');
Configure::write('Session.cookie', 'CAKEPHP');
Configure::write('Session.timeout', '100000'); //设置session超时时间
Configure::write('Session.start', true);
Configure::write('Session.checkAgent', true);
Configure::write('Security.level', 'medium');
Configure::write('Security.salt', 'DYhG93b0qy7878JfIxWw0FgaC9mi');
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');
Configure::write('sys.token', '123456798');
Configure::write('Config.language', 'eng');
Configure::write('active_call_ip', $sections['web_active_call']['active_call_ip']);
Configure::write('active_call_port', $sections['web_active_call']['active_call_port']);
Configure::write('Cache.disable', true);
Cache::config('default', array('engine' => 'File'));
define('PRI', true);
define('PROJECT', 'class4');
define('CDR_TABLE', 'cdr_report_detail');

ini_set('session.use_cookies',1);
ini_set('session.cookie_lifetime',999999999);
ini_set('session.gc_maxlifetime', "86400");
?>
