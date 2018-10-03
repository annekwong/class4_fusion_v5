<?php

class DidAssignController extends DidAppController
{

    var $name = "DidAssign";
    var $uses = array('did.DidAssign', 'did.DidRepos', "prresource.Gatewaygroup", 'did.DidBillingPlan','did.OrigLog');
    var $helpers = array('javascript', 'html', 'Common');

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function index($egress_id = null)
    {
        $this->pageTitle = "Origination/Egress DID Assignment";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'DidAssign.created_time' => 'desc',
            ),
        );
        $total_data = $this->paginate('DidAssign');
        if (empty($total_data))
        {
            $msg = "Client DID";
            $add_url = "create";
            $model_name = "DidRepos";
            $this->to_add_page($model_name,$msg,$add_url);
        }
        $egress_id = base64_decode($egress_id);
        if (isset($_GET['search']) && !empty($_GET['search']))
        {
            $this->paginate['conditions'][] = array("DidAssign.number::text like '%{$_GET['search']}%'");
        }
        if ($egress_id != null)
        {
            $this->paginate['conditions'][] = array("DidAssign.egress_id = {$egress_id}");
            $this->set('vendor_name', $this->DidRepos->get_vendor_name($egress_id));
        }

        if (isset($_GET['advsearch']))
        {
            $ingress_id = $_GET['ingress_id'];
            $egress_id = $_GET['egress_id'];
            $number = $_GET['number'];

            if (!empty($ingress_id))
            {
                $this->paginate['conditions'][] = array("DidAssign.ingress_id = {$ingress_id}");
            }
            if (!empty($egress_id))
            {
                $this->paginate['conditions'][] = array("DidAssign.egress_id = {$egress_id}");
            }
            if (!empty($number))
            {
                $this->paginate['conditions'][] = array("DidAssign.number like %'{$number}'%");
            }
        }

        $this->_get_data();
        $this->data = $this->paginate('DidAssign');
        foreach ($this->data as $key => &$item)
        {
            $item_data = $this->DidRepos->findByNumber($item['DidAssign']['number']);
            if (is_array($item_data))
            {
                $item = array_merge($item, $item_data);
            }
            else
            {
                unset($this->data[$key]);
            }
        }
    }

    public function create($encode_egress_id = '')
    {
        Configure::write('debug', 0);
        $this->_get_data();
        $countries = $this->DidRepos->get_countries();
        $this->set('countries', $countries);
        $this->set('selected_egress_id',base64_decode($encode_egress_id));
    }

    public function search_number()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        //$page     = intval($_POST['page']);
        //$pageSize = 50;
        $offset = ($page - 1) * $pageSize;
        $country = $_POST['country'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $rate_center = $_POST['rate_center'];
        $number = $_POST['number'];
        $data = $this->DidAssign->get_number($country, $state, $city, $rate_center, $number);
        echo json_encode($data);
    }

    public function assign()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $egress_id = $_POST['egress_id'];
        $numbers = $_POST['numbers'];
        $product_id = $this->DidAssign->check_default_static();
        foreach ($numbers as $number)
        {
            $resource = $this->Gatewaygroup->findByResourceId($egress_id);
            $rate_table_id = $resource['Gatewaygroup']['rate_table_id'];
            $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
            $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
            $min_price = $billing_rule['DidBillingPlan']['min_price'];

            $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
            $this->DidRepos->query($sql);

            $item_id = $this->DidAssign->add_new_number($number, $product_id);
            $this->DidAssign->add_new_resouce($item_id, $egress_id);
            $this->DidAssign->add_assign($number, $egress_id);
            $log_detail = "#{$number} assign => {$resource['Gatewaygroup']['alias']}";
            $this->OrigLog->add_orig_log("Egress DID", 1, $log_detail);
        }
        echo json_encode(array('result' => 1));
    }

    public function change_status($number, $status)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        /*
          $product_id = $this->DidAssign->check_default_static();
          if ($status == 0)
          {
          $this->DidAssign->delete_number($number, $product_id);
          }
          else
          {
          $item_id = $this->DidAssign->add_new_number($number, $product_id);
          $this->DidAssign->add_new_resouce($item_id, $egress_id);
          $this->DidAssign->add_assign($number, $egress_id);
          }
         * 
         */
        if ($status == 0)
        {
            $this->Session->write('m', $this->DidAssign->create_json(201, __('The  status of  number  [' . $number . '] is inactived successfully!', true)));
        }
        else
        {
            $this->Session->write('m', $this->DidAssign->create_json(201, __('The  status of  number  [' . $number . '] is actived successfully!', true)));
        }
        $sql = "update did_assign set status = {$status} where number = '{$number}';
        update ingress_did_repository set status = {$status} where number = '{$number}';";
        $this->DidAssign->query($sql);

        $this->xredirect("/did/did_assign/index");
    }

    public function _get_data($client_id = '')
    {
        $this->set('ingresses', $this->DidRepos->get_ingress());
        $this->set('egresses', $this->DidRepos->get_egress($client_id));
    }

    public function action_edit_panel($egress_id = '', $number = null)
    {
        Configure::write('debug', 0);
        if ($egress_id == '0')
            $egress_id = '';

        $client_id = '';
        if (!empty($egress_id))
        {
            $sql = "select client_id from resource where resource_id = {$egress_id}";
            $result = $this->DidAssign->query($sql);
            $client_id = $result[0][0]['client_id'];
        }


        $this->_get_data($client_id);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($number != null)
            {
                $product_id = $this->DidAssign->check_default_static();
                $this->DidAssign->delete_number($number, $product_id);
                $item_id = $this->DidAssign->add_new_number($number, $product_id);
                $this->DidAssign->add_new_resouce($item_id, $this->data['DidAssign']['egress_id']);
                $this->data['DidAssign']['number'] = $number;
                $this->Session->write('m', $this->DidAssign->create_json(201, __('The number of [' . $this->data['DidAssign']['number'] . '] is modified successfully!', true)));

                $old_data = $this->DidAssign->findByNumber($number);
                $data = array_diff_assoc($old_data['DidAssign'], $this->data['DidAssign']);
                $match_arr = array(
                    'egress_id' => 'DID Client',
                );
                $log_detail_arr = array();
                foreach ($data as $diff_key => $value)
                {
                    if (strcmp($diff_key, 'number') && key_exists($diff_key, $match_arr))
                    {
                        if (strcmp($diff_key, 'egress_id'))
                        {
                            $log_detail_arr[] = $match_arr[$diff_key] . "[" . $old_data['DidAssign'][$diff_key] . "=>" . $this->data['DidAssign'][$diff_key] . "]";
                        }
                        else
                        {
                            $old_egress_name = $this->DidAssign->query("select  alias  from  resource where egress=true and resource_id = {$old_data['DidAssign'][$diff_key]}");
                            $new_egress_name = $this->DidAssign->query("select  alias  from  resource where egress=true and resource_id = {$this->data['DidAssign'][$diff_key]}");
                            $log_detail_arr[] = $match_arr[$diff_key] . "[" . $old_egress_name[0][0]['alias'] . "=>" . $new_egress_name[0][0]['alias'] . "]";
                        }
                    }
                }
                $log_detail = implode(";", $log_detail_arr);
                if ($log_detail)
                {//如果有改变才记录到log中
                    $log_detail = "#{$number};" . $log_detail;
                    $log_flg = TRUE;
                    $action = 2;
                }
            }
            else
            {
                $this->Session->write('m', $this->DidAssign->create_json(201, __('The number of [' . $this->data['DidAssign']['number'] . '] is created successfully!', true)));
                $log_flg = TRUE;
                $action = 0;
                $log_detail = "DID [{$this->data['DidAssign']['number']}]";
            }
            $flg = $this->DidAssign->save($this->data);
            if ($flg !== false && isset($log_flg))
            {
                $this->OrigLog->add_orig_log("Egress DID", $action, $log_detail);
            }
            $this->xredirect("/did/did_assign/index/" . $egress_id);
        }
        $this->data = $this->DidAssign->find('first', Array('conditions' => Array('number' => $number)));
    }

    public function listing()
    {
        $client_id = $this->Session->read('sst_client_id');

        $this->pageTitle = "DID Listing";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'DidAssign.created_time' => 'desc',
            ),
            'joins' => array(
                array(
                    'table' => 'resource',
                    'alias' => "Resource",
                    'type' => 'INNER',
                    'conditions' => array(
                        'DidAssign.egress_id = Resource.resource_id',
                    ),
                ),
            ),
            'conditions' => array('Resource.client_id' => $client_id, 'Resource.egress' => true, 'Resource.trunk_type2' => 1),
        );

        if (isset($_GET['search']) && !empty($_GET['search']))
        {
            //$this->paginate['conditions'][] = array("DidAssign.number::text like '%{$_GET['search']}%'");
        }


        $this->_get_data();
        $this->data = $this->paginate('DidAssign');
    }


    public function ajax_page()
    {
        Configure::write('debug', 0);
        $page_now = $this->params['form']['page_now'];
        $total_pages = $this->params['form']['total_pages'];
        if($total_pages > 10)
            $total_pages = 10;
        $next_page = $page_now +1;
        if ($total_pages < $next_page)
            $next_page = $total_pages;
        $prev_page = $page_now -1;
        if ($prev_page < 1)
            $prev_page = $page_now;
        $this->set('page_now',$page_now);
        $this->set('total_pages',$total_pages);
        $this->set('next_page',$next_page);
        $this->set('prev_page',$prev_page);
    }
}

?>
