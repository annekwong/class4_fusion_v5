<?php

class DownController extends AppController
{

    var $name = "Down";
    var $uses = array('Cdr', 'Pr.Invoice');

    function beforeFilter()
    {
        Configure::load('myconf');
        return true;
    }

    public function index()
    {

    }

    public function block()
    {
        $fields = $this->get_schema_block();

        if ($this->RequestHandler->isPost())
        {
            $table = "resource_block";
            $where = "";
            $this->handle_data($fields, $table, $where, 'Block');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', array('Routing', 'Block List', 'Export'));
    }

    public function routing_plan($id)
    {
        $fields = $this->get_schema_route_plan();
        $id = base64_decode($id);
        if ($this->RequestHandler->isPost())
        {
            $table = "route";

            $where = "where  route_strategy_id  = $id";
            $this->handle_data($fields, $table, $where, 'Routing_Plan');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $sql = "select name from route_strategy where route_strategy_id=$id";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
        $this->set('header', 'Routing &gt;&gt; Routing Plan [' . $name . '] &gt;&gt; Export');
        $this->set('id', $id);
    }

    public function static_route($route_id)
    {
        $fields = $this->get_schema_static_route();

        if ($this->RequestHandler->isPost())
        {
            $table = "product_items_resource  left join product_items on product_items_resource.item_id = product_items.item_id";
            $where = "where product_id = $route_id";
            $this->handle_data($fields, $table, $where, 'Static_Route');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        if ($route_id) {
            $sql = "select name from product where product_id=$route_id";
            $result = $this->Cdr->query($sql);
            $name = $result[0][0]['name'];
        }
        //$this->set('header', 'Routing &gt;&gt; Edit Static Route [' . $name . '] &gt;&gt; Export');    	
        $this->set('header', array('Routing', 'Edit Static Route [' . $name . ']', 'Export'));

        $this->set('route_id', $route_id);
    }

    public function code_deck($code_deck_id)
    {
        $code_deck_id = base64_decode($code_deck_id);
        $fields = $this->get_schema_code_deck();

        if ($this->RequestHandler->isPost())
        {
            $table = "code";
            $where = "where code_deck_id = {$code_deck_id}";
            $this->handle_data($fields, $table, $where, 'Code_Deck');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $sql = "select name from code_deck where code_deck_id=$code_deck_id";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
        //$this->set('header', 'Switch &gt;&gt; Edit Code Deck List [' . $name . '] &gt;&gt; Export');
        $this->set('header', array('Switch', 'Edit Code Deck List [' . $name . ']', 'Export'));
        $this->set('code_deck_id', $code_deck_id);
    }

    public function rate_generation_result($encode_rate_generation_log_id)
    {
        $rate_generation_log_id = base64_decode($encode_rate_generation_log_id);
        $fields = $this->get_schema_rate_generation_result();
        if ($this->RequestHandler->isPost())
        {
            $table = "rate_generation_rate";
            $where = "where rate_generation_history_id = $rate_generation_log_id";
            $this->handle_data($fields, $table, $where, 'rate_generation_result');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', array('Tool', 'Rate Generation Result', 'Export'));
    }

    public function jurisdiction()
    {
        $fields = $this->get_schema_jurisdiction();

        if ($this->RequestHandler->isPost())
        {
            $table = "jurisdiction_prefix";
            $where = "";
            $this->handle_data($fields, $table, $where, 'Jurisdiction');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', array('Switch', 'Jurisdiction'));
    }

    public function us_ocn_lata()
    {
        $fields = $this->get_schema_usocnlata();

        if ($this->RequestHandler->isPost())
        {
            $table = "us_ocn_lata";
            $where = "";
            $this->handle_data($fields, $table, $where, 'US_OCN_LATA');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', array('Switch', 'US OCN/LATA'));
    }

    public function digit_mapping()
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_digit_mapping();

        if ($this->RequestHandler->isPost())
        {
            $table = "resource_translation_ref";
            $where = "";
            $this->handle_data($fields, $table, $where, 'Digits_Mapping');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }

    public function digit_mapping_down($translation_id)
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_digit_mapping_2();

        if ($this->RequestHandler->isPost())
        {
            $table = "translation_item ";
            $where = "where translation_id = {$translation_id}";
            $this->handle_data($fields, $table, $where, 'Digits_Mapping');
        }
        $sql = "select translation_name as name from digit_translation where translation_id = {$translation_id}";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', array('Routing', 'Digit Mapping [' . $name . ']'));
        $this->set('id', $translation_id);
    }

    public function action()
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_action();

        if ($this->RequestHandler->isPost())
        {
            $type = $_POST['type'];
            $table = "resource_direction inner join resource on resource_direction.resource_id = resource.resource_id and $type = true";
            $where = "";
            $this->handle_data($fields, $table, $where, 'Resouce_Action');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }

    public function host()
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_host();

        if ($this->RequestHandler->isPost())
        {
            $type = $_POST['type'];
            $table = "resource_ip inner join resource on resource_ip.resource_id = resource.resource_id and $type = true";
            $where = "";
            $this->handle_data($fields, $table, $where, 'Resource_Host');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }

    public function ingress()
    {
        $fields = $this->get_schema_ingress();

        if ($this->RequestHandler->isPost())
        {
            $table = "resource left join resource_prefix on resource_prefix.resource_id = resource.resource_id";
            $where = "where ingress = true";
            if (isset($_POST['trunks'])) {
                $cond = ' AND (';
                foreach ($_POST['trunks'] as $trunk) {
                    $trunks[] = 'resource.resource_id = '.$trunk;
                }
                $cond .= implode(' OR ', $trunks).')';
                $where .= $cond;
            }
            $this->handle_data($fields, $table, $where, 'Ingress');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }

    public function egress()
    {
        $fields = $this->get_schema_egress();

        if ($this->RequestHandler->isPost())
        {
            $table = "resource";
            $where = "where egress = true";

            if (isset($_POST['trunks'])) {
                //remove empty elements
                $_POST['trunks'] = array_filter($_POST['trunks']);
                $where .= ' AND (resource_id IN (' . implode(',', $_POST['trunks']) . '))';
            }
            $this->handle_data($fields, $table, $where, 'Egress');
        }

        $keys = array_keys($fields);
        $this->set('fields', $keys);
        $this->set('header', 'Routing &gt;&gt; Egress Trunk');
    }

    public function handle_data($schema, $table, $where, $name)
    {
        $with_header = isset($_POST['with_header']) ? 'WITH CSV HEADER' : '';
        $header_text = !empty($_POST['header_text']) ? $_POST['header_text'] : false;
        $footer_text = !empty($_POST['footer_text']) ? $_POST['footer_text'] : false;
        $data_format = $_POST['data_format'];
        $fields = $_POST['fields'];
        $field_arr = array();
        $extension = $data_format == 0 ? '.csv' : '.xls';
        $filename = $name . '_' . date('Y_m_d_H_i_s') . '_' . uniqid();
        $csvFile = $filename . '.csv';
        $xlsFile = $filename . $extension;
        foreach ($fields as $field)
        {
            if (!empty($field) && isset($schema[$field]))
                array_push($field_arr, "{$schema[$field]} as \"{$field}\"");
        }

        if (count($field_arr))
        {
            $this->loadModel('Export');
            $field_arr = $this->prepareFields($table, $field_arr);
            $sql = "insert into import_export_logs
(log_type, file_path, user_id, status, time, obj) values (0, '{$xlsFile}', {$_SESSION['sst_user_id']}, 6, CURRENT_TIMESTAMP(0), '{$name}')";
            $this->Cdr->query($sql);
            $sql = "SELECT " . implode(',', $field_arr) . " FROM {$table} {$where}";
            $filename = Configure::read('database_export_path') . "/{$csvFile}";
            $exportResult = $this->Export->csv($sql, $filename, $with_header, ',', $header_text, $footer_text, $table);
            if($exportResult['error'] == 1) {
                $this->Session->write('m', $this->Export->create_json(101, $exportResult['msg']));
                $lowercaseName = strtolower($name);
                $url = "/down/{$lowercaseName}";

                if ($lowercaseName == 'egress') {
                    $url = "/prresource/gatewaygroups/view_egress#/downloads/egress";
                } else if ($lowercaseName == 'ingress') {
                    $url = "/prresource/gatewaygroups/view_ingress#/downloads/ingress";
                }
                $this->redirect($url);
            }

            $this->loadModel('Download');

            if ($data_format == 0)
            {
                $this->Download->csv($filename);
            }
            else
            {
                $this->Download->xlsFromCsv($filename);
            }
        }
        else
        {
            exit();
        }
    }

    public function summary()
    {

        //$this->params['url']
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $this->Invoice->get_summary_invoice($this->params['url']);
    }

    private function prepareFields($table, $fields){
        switch($table) {
            case 'rate_generation_rate':
                $key = array_search('code as "code"', $fields);
                if (false !== $key) {
                    unset($fields[$key]);
                }
                $fields[$key] = 'DISTINCT on (code)  "code"';
                break;
        }
        sort($fields);
        return $fields;
    }

    public function get_schema_ingress()
    {
        $fields = array(
            'trunk_id' => 'resource.resource_id',
            'profit_type' => 'profit_type',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'cps_limit' => "cps_limit",
            'call_limit' => 'capacity',
//            'protocol' => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'wait_ringtime180',
            'ignore_early_media' => "(case when ignore_ring = false and ignore_early_media = false then 'None' when ignore_ring = true and ignore_early_media = true then '180 and 183' when ignore_ring =true and ignore_early_media = false then '180' when ignore_ring = false and ignore_early_media = true then '183' end)",
            'active' => "(case active when true then 'true' else 'false' end)",
//            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rfc2833' => "(case rfc2833 when 1 then 'true' else 'false' end)",
            'dip_from' => "(case lnp_dipping when true then 'client' else 'server' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
//            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
            'rate_table_name' => '(SELECT name FROM rate_table WHERE rate_table_id = resource_prefix.rate_table_id)',
            'route_strategy_name' => '(SELECT name FROM route_strategy WHERE route_strategy_id = resource_prefix.route_strategy_id)',
            'tech_prefix' => 'resource_prefix.tech_prefix',
            'profit_margin' => 'resource.profit_margin'
        );

        return $fields;
    }

    public function get_schema_egress()
    {
        $fields = array(
            'trunk_id' => 'resource_id',
            'profit_type' => 'profit_type',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'call_limit' => 'capacity',
            'cps_limit' => "cps_limit",
//            'protocol' => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'wait_ringtime180',
            'active' => "(case active when true then 'true' else 'false' end)",
//            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rate_table_name' => '(select name from rate_table where rate_table_id = resource.rate_table_id)',
            'host_route_strategy' => "(case res_strategy when 1 then 'top-down' else 'round-robin' end)",
            'rfc2833' => "(case rfc2833 when 1 then 'true' else 'false' end)",
            'pass_dip_head' => "(case lnp_dipping when true then 'true' else 'false' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
            'profit_margin' => 'resource.profit_margin',
//            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
        );

        return $fields;
    }

    public function get_schema_host()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_ip.resource_id)',
            'ip' => 'ip',
            'port' => "port"
        );

        return $fields;
    }


    public function get_schema_action()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_direction.resource_id)',
            'time_profile_name' => '(select name from time_profile where time_profile_id = resource_direction.time_profile_id)',
            'target' => "(case type when 0 then 'ani' else 'dnis' end)",
            'code' => 'dnis',
            'action' => "(case action when 1 then 'add_prefix' when 2 then 'add_suffix' when 3 then 'del_prefix' when 4 then 'del_suffix' end)",
            'chars' => 'digits',
            'number_type' => "(case number_type when 0 then 'all' when 1 then '>' when 2 then '=' when 3 then '<' end)",
            'number_length' => 'number_length',
        );

        return $fields;
    }


    public function get_schema_rate_generation_result()
    {
        $fields = array(
            'code' => 'code',
            'rate' => 'rate',
            'end_date' => 'end_date',
            'min_time' => 'min_time',
            'interval' => 'interval',
            'code_name' => 'code_name',
            'intra_rate' => 'intra_rate',
            'inter_rate' => 'inter_rate',
            'local_rate' => 'local_rate',
            'country' => 'country',
        );

        return $fields;
    }

    public function get_schema_digit_mapping()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_translation_ref.resource_id)',
            'translation_name' => '(select translation_name from digit_translation where translation_id = resource_translation_ref.translation_id)',
            'time_profile_name' => "(select name from time_profile where time_profile_id = resource_translation_ref.time_profile_id)",
        );

        return $fields;
    }

    public function get_schema_block()
    {
        $fields = array(
            'Block No ANI' => "(case when ani_empty is true then 'Yes' else 'No' end)",
            'ANI' => 'ani_prefix',
            'DNIS' => "digit",
            'Egress Carrier' => '(select client.name FROM client inner join resource on resource.client_id = client.client_id where resource_id = resource_block.engress_res_id)',
            'egress Trunk' => '(select alias from resource where resource_id = resource_block.engress_res_id)',
            'Ingress Carrier' => '(select client.name FROM client inner join resource on resource.client_id = client.client_id where resource_id = resource_block.ingress_res_id)',
            'Ingress Trunk' => '(select alias from resource where resource_id = resource_block.ingress_res_id)',
            'Time Profile' => "(select name from time_profile where time_profile_id = resource_block.time_profile_id)",
            'ANI Length Min' => 'ani_length',
            'ANI Length Max' => 'ani_max_length',
            'DNIS Length Min' => 'dnis_length',
            'DNIS Length Max' => 'dnis_max_length',
            'Type' => 'type'
        );

        return $fields;
    }

    public function get_schema_jurisdiction()
    {
        $fields = array(
            'country' => 'jurisdiction_country_name',
            'state' => 'jurisdiction_name',
            'prefix' => 'prefix',
            'ocn' => 'ocn',
            'lata' => 'lata',
        );
        return $fields;
    }

    public function get_schema_usocnlata()
    {
        $fields = array(
            'ocn' => 'ocn',
            'lata' => 'lata',
            'npa' => 'npa',
            'nxx' => 'nxx',
            'a_block' => 'a_block',
            'effective_time' => 'effective_time'
        );
        return $fields;
    }

    public function get_schema_code_deck()
    {
        $fields = array(
            'code' => 'code',
            'name' => 'name',
            'country' => 'country',
        );

        return $fields;
    }

    public function get_schema_special_code()
    {
        $fields = array(
            'code' => 'Code',
            'pricing' => 'Pricing',
        );

        return $fields;
    }

    public function get_schema_did()
    {
        $fields = array(
            'did' => 'did',
            'vendor' => 'vendor',
            'vendor_billing_plan' => 'vendor_billing_plan',
            'client' => 'client',
            'client_billing_plan' => 'client_billing_plan'
        );

        return $fields;
    }

    public function get_schema_static_route()
    {
        $fields = array(
            'code' => 'digits',
            'strategy' => "(case strategy when 0 then 'percentage' when 1 then 'top-down' when 2 then 'round-robin' end)",
            'trunk_name' => '(SELECT alias from resource where resource_id = product_items_resource.resource_id)',
            'percentage' => 'by_percentage',
            'time_profile_name' => "(select name from time_profile where time_profile_id = product_items.time_profile_id)",
        );

        return $fields;
    }

    public function get_schema_route_plan()
    {
        $fields = array(
            'ani_prefix' => "ani_prefix",
            'ani_min_length' => "ani_min_length",
            'ani_max_length' => "ani_max_length",
            'dnis_prefix' => 'digits',
            'dnis_min_length' => "digits_min_length",
            'dnis_max_length' => "digits_max_length",
            'route_type' => "(case route_type when 1 then 'dynamic' when 2 then 'static' when 3 then 'dynamic-static' when 4 then 'static-dynamic' end)",
            'dynamic_route_name' => "(select name from dynamic_route where dynamic_route.dynamic_route_id = route.dynamic_route_id)",
            'static_route_name' => "(select name from product where product_id = route.static_route_id)",
            'intra_static_route_name' => "(select name from product where product_id = route.intra_static_route_id)",
            'inter_static_route_name' => "(select name from product where product_id = route.inter_static_route_id)",
            'jurisdiction_country' => " (select name from jurisdiction_country where jurisdiction_country.id = route.jurisdiction_country_id)",
        );

        return $fields;
    }

    public function get_schema_reset_balance()
    {
        $fields = array(
            'name' => 'name',
            'begin_date' => 'begin_date',
            'balance' => 'balance',
        );
        return $fields;
    }

    public function get_schema_digit_mapping_2()
    {
        $fields = array(
            'ani_method' => "(case ani_method when 0 then 'ignore' when 1 then 'replace matched prefix' when 2 then 'replace' end)",
            'ani' => 'ani',
            'translated_ani' => 'action_ani',
            'dnis_method' => "(case dnis_method when 0 then 'ignore' when 1 then 'replace matched prefix' when 2 then 'replace' end)",
            'dnis' => 'dnis',
            'translated_dnis' => 'action_dnis',
        );

        return $fields;
    }

    public function get_schema_carrier()
    {
        $fields = array(
            'Name' => 'ani',
            'Mode' => 'dnis',
            'Orig Rate Table Id' => 'action_ani',
            'Term Rate Table Id' => 'action_dnis',
            'Currency Id' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Allowed Credit' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Status' => 'ani',
            'Auto Invoicing' => 'dnis',
            'Payment Term Id' => 'action_ani',
            'Invoice Format' => 'action_dnis',
            'Attach Cdrs List' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Cdr List Format' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Last Invoiced' => 'ani',
            'Notify Client Balance' => 'dnis',
            'Low Balance Notice' => 'action_ani',
            'Company' => 'action_dnis',
            'Address' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Email' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Logo' => 'ani',
            'Login' => 'dnis',
            'Password' => 'action_ani',
            'Is Panelaccess' => 'action_dnis',
            'Is Client Info' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Is Invoices' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Is Rateslist' => 'ani',
            'Is Summaryreport' => 'dnis',
            'Is Cdrslist' => 'action_ani',
            'Is Mutualsettlements' => 'action_dnis',
            'Is Changepassword' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Role Id' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Create Time' => 'ani',
            'Profit Margin' => 'dnis',
            'Enough Balance' => 'action_ani',
            'Service Charge Id' => 'action_dnis',
            'Noc Email' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Billing Email' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Rate Email' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Tax Id' => 'ani',
            'Details' => 'dnis',
            'Invoice Show Details' => 'action_ani',
            'Invoice Past Amount' => 'action_dnis',
            'Is Link Cdr' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Is Daily Balance Notification' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Daily Balance Notification' => 'ani',
            'Daily Balance Recipient' => 'dnis',
            'Is Auto Balance' => 'action_ani',
            'Numer Of Days Balance' => 'action_dnis',
            'Update At' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'Update By' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
        );

        return $fields;
    }

    public function get_schema_payment()
    {
        $fields = array(
            'name' => 'name',
            'datetime' => 'begin_date',
            'note' => 'note',
            'amount' => 'amount',
            'type' => 'type',
            'invoice_numbers' => 'invoice_numbers',
            'invoice_paids' => 'invoice_paids',
        );
        return $fields;
    }

    public function get_schema_failover_rule()
    {
        $fields = array(
            'Route type' => 'route_type',
            'Code' => 'reponse_code',
        );

        return $fields;
    }


    public function get_schema_repalce_action()
    {
        $fields = array(
            'ANI Prefix' => 'ani_prefix',
            'ANI' => 'ani',
            'ANI Min Length'    => 'ani_min_length',
            'ANI Max Length'    => 'ani_max_length',
        );

        return $fields;
    }


    public function get_schema_invoice()
    {
        $fields = array(
            'Carrier' => 'name',
            'Invoice Num' => 'invoice_number',
            'Amount'    => 'invoice_amount',
            'Invoice Period Start'    => 'start',
            'Invoice Period End' => 'end',
            'GMT' => 'gmt',
            'Invoice Date'    => 'invoice_date',
            'Due Date'    => 'due_date',
        );

        return $fields;
    }

    public function get_schema_lrn_special_code()
    {
        $fields = array(
            'ip' => 'ip',
            'port' => 'port',
            'timeout'    => 'timeout',
            'retry'    => 'retry',
            'dynamic_timeout' => 'dynamic_timeout',
            'filter_timeout' => 'filter_timeout',
            'option'    => 'option',
            'option_interval'    => 'option_interval',
        );

        return $fields;
    }

    public function get_schema_random_generation()
    {
        $fields = array(
            'ani'   => 'ani'
        );
        return $fields;
    }
}

?>
