

<?php

$sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
foreach ($sections as $section_key => &$section)
{
    foreach ($section as $item_key => &$item)
    {
        while (strpos($item, '$') !== FALSE && strpos($item, '$') == 3)
        {
            list($key, $value) = explode('.', $item);
            $item = @$sections[$key][trim($value, '$')];
        }
    }
}

$config['client']['name'] = $sections['client']['name'];

//class4脚本配置目录，不要提供末尾目录符
$config['script']['path'] = realpath(ROOT . '/../script/');
$config['script']['conf'] = CONF_PATH;


//LRN 测试功能所执行的二进制文件
$config['lrn_test']['lrn_test_bin'] = APP . "binexec/lrn_test/dnl_lrn_testing";


//费率上传相关配置文件
$config['rateimport']['bin'] = APP . "binexec/dnl_import_rate/dnl_import_rate";    // 费率上传执行的二进制文件,777 权限
#$config['rateimport']['conf'] = APP . "binexec/dnl_import_rate/conf/";             // 二进制文件配置文件目录
$config['rateimport']['conf'] = CONF_PATH;             // 二进制文件配置文件目录
$config['rateimport']['out'] = WWW_ROOT . "upload/rates_log";                      // 上传log件存放目录
$config['rateimport']['put'] = APP . "tmp/upload/csv";                          // 存放上传文件
//生成Invoice(PDF)存放目录
$config['generate_invoice']['path'] = APP . 'webroot' . DS . 'upload' . DS . 'invoice_file';


//Mutual Balance 生成PDF存放目录
$config['generate_balance']['path'] = APP . 'webroot' . DS . 'upload' . DS . 'balance_file';


//后台SOCK server 通讯服务器ip & port,command_api_ip/port
$config['backend']['ip'] = $sections['web_switch']['event_ip'];
$config['backend']['port'] = $sections['web_switch']['event_port'];


/*
 * PCAP API IP
 */
if(isset($sections['pcap']['ip'])) {
    Configure::write("pcap.api.ip", $sections['pcap']['ip']);
} else {
    Configure::write("pcap.api.ip", '');
}

//数据库导出目录,需要数据库机器网络映射
Configure::write('database_export_path', realpath(ROOT . '/../download/')); // 数据库机器上导出的目录
Configure::write('database_actual_export_path', isset($sections['web_path']['db_export_path']) ? $sections['web_path']['db_export_path'] : ''); // 实际WEB机器上目录
//php解释器目录
//Configure::write('php_exe_path', $sections['web_path']['php_interpreter_path']);
Configure::write('php_exe_path', PHP_BINDIR . DS . 'php');
//Configure::write('php_exe_path', exec("which php"));






//系统类型
$config['system']['type'] = 1;      //1 class4 , 2 exchange
//$config['system']['enable_trunk_type'] = true;      // 是否开启 trunk type
//Origination 相关配置
$config['did']['enable'] = $sections['web_feature']['did'];  //开启或关闭 true or false
$config['did']['upload_path'] = APP . 'webroot' . DS . 'upload' . DS . 'did';


//在线支付相关配置
$config['payline']['enable_paypal'] = $sections['web_feature']['paypal'];
$config['payline']['yourpay_enabled'] = $sections['web_feature']['yourpay'];
$config['payline']['yourpay_host'] = 'secure.linkpt.net';
$config['payline']['yourpay_port'] = '1129';
$config['payline']['is_new_window'] = $sections['web_feature']['pay_in_new_window'];  // paypal 是否在新窗口进行支付
$config['active_call']['active_call_server_ip'] = $sections['web_active_call']['active_call_ip']; //active call server IP -h
$config['active_call']['active_call_server_port'] = $sections['web_active_call']['active_call_port']; //active call server port -p



//发送invoice CDR 存放目录

$config['send_invoice']['cdr_path'] = isset($sections['web_path']['send_invoice_cdr']) ? ($sections['web_path']['send_invoice_cdr'] . DS . 'invoice_cdr') : '';   // 注意配置为 x-sendfile path
// Mail Export CDR Path
$config['export_cdr']['path'] = realpath(ROOT . '/../script/storage/cdr_down/');

// 报表分组
$config['statistics']['group_all'] = isset($sections['web_feature']['statistics_group_all']) ? $sections['web_feature']['statistics_group_all'] : '';
$config['statistics']['have_code_rate'] = isset($sections['web_feature']['statistics_have_code_rate']) ? $sections['web_feature']['statistics_have_code_rate'] : '';
// 是否打开命令调试
$config['cmd']['debug'] = $sections['web_base']['cmd_debug'];

// Log文件 Web需要具备rx权限
$config['logfile']['script_log'] = '/tmp/class4';  // 脚本Log目录
// CDR TMP Path
$config['cdr']['tmp'] = isset($sections['web_path']['cdr_backup_path']) ? $sections['web_path']['cdr_backup_path'] : '';



## postgres bin
$config['psql_bin'] = isset($sections['psql']['psql_bin']) ? $sections['psql']['psql_bin'] : '';

##portal 配置
$config['portal']['change_ip'] = isset($sections['portal']['change_ip']) ? $sections['portal']['change_ip'] : '';
$config['portal']['show_switch_ip'] = isset($sections['portal']['show_switch_ip']) ? $sections['portal']['show_switch_ip'] : '';
$config['portal']['build_trunk_from_product'] = isset($sections['portal']['build_trunk_from_product']) ? $sections['portal']['build_trunk_from_product']: '';
$config['portal']['add_ingress'] = isset($sections['portal']['add_ingress']) ? $sections['portal']['add_ingress'] : '';
$config['portal']['add_egress'] = isset($sections['portal']['add_egress']) ? $sections['portal']['add_egress'] : '';


## number to npa nxx xml address
//$config['xml']['get_npanxx'] = $sections['xml']['get_npanxx'];

## URL HTML
$config['web_base']['url'] = $sections['web_base']['url'];

$config['help_link'] = isset($sections['web_feature']['help_link']) ? $sections['web_feature']['help_link'] : '';

$config['pcap_url'] = isset($sections['pcap_api']['url']) ? $sections['pcap_api']['url'] : '';
$config['cdr_url'] = isset($sections['cdr_api']['url']) ? $sections['cdr_api']['url'] : '';
$config['cdr_api']['auth_port'] = $sections['cdr_api']['auth_port'];
$config['cdr_api']['sync_port'] = $sections['cdr_api']['sync_port'];
$config['cdr_api']['async_port'] = $sections['cdr_api']['async_port'];
$config['cdr_api']['agg_port'] = $sections['cdr_api']['agg_port'];
$config['invoice_download_dir'] = $sections['invoice']['invoice_download_dir'];

if(isset($sections['invoice']) && isset($sections['invoice']['url'])) {
    $config['invoice_url'] = $sections['invoice']['url'];
}
if(isset($sections['invoice']) && isset($sections['invoice']['orig_url'])) {
    $config['invoice_orig_url'] = $sections['invoice']['orig_url'];
}
if(isset($sections['import']) && isset($sections['import']['url'])) {
    $config['import_url'] = $sections['import']['url'];
}
if(isset($sections['cdr_archival_url']) && isset($sections['cdr_archival_url']['url'])) {
    $config['archival_url'] = $sections['cdr_archival_url']['url'];
}
if(isset($sections['real_time_and_reporting_url']) && isset($sections['real_time_and_reporting_url']['url'])) {
    $config['rtr_url'] = $sections['cdr_archival_url']['url'];
}

$config['memsql']['enabled'] = 0;

