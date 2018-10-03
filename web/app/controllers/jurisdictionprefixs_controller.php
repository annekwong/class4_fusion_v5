<?php

class JurisdictionprefixsController extends AppController
{

    var $uses = array('Jurisdictionprefix');

    function index()
    {
        $this->redirect('view');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_locationPrefixes');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }

        parent::beforeFilter();
    }

    public function validate_jur()
    {
        $flag = 'true';
        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        $size = count($tmp);
        foreach ($tmp as $el)
        {
            $this->data['Jurisdiction'] = $el;
            if ($this->data['Jurisdiction']['alias'] == '')
            {
                $this->Jurisdictioncountry->create_json_array('#ClientOrigRateTableId', 101, 'Please fill \"alais\" field correctly (only  digits allowed).');
                $flag = 'false';
            }
            else
            {
                $c = $this->check_alias($this->data['Jurisdiction']['id'], $this->data['Jurisdiction']['alias']);
                if ($c != 0)
                {
                    $this->create_json_array('#ClientName', 301, __('checkclientname', true));
                    $error_flag = 'false';
                }
            }
            $c = $this->check_name($this->data['Jurisdiction']['id'], $this->data['Jurisdiction']['name']);
            if ($c != 0)
            {
                $this->create_json_array('#ClientName', 301, __('checkclientname', true));
                $error_flag = 'false';
            }

            return $flag;
        }
    }

    public function get_jurisdiction_id($name, $country_id)
    {
        # first add
        $return = 0;
        $list = $this->Jurisdictionprefix->query("select  count(*)   from  jurisdiction;");
        if (count($list[0][0]) == 0 || empty($list[0][0]['count']))
        {
            $return = 0;
        }
        #
        $list = $this->Jurisdictionprefix->query("select  id   from  jurisdiction  where  name='$name' and jurisdiction_country_id = $country_id limit 1;");

        $list_tmp = $this->Jurisdictionprefix->query("select  id   from  jurisdiction  where  name='$name';");


        if ($list && count($list[0][0]) && !empty($list[0][0]['id']))
        {
            $return = $list[0][0]['id'];
        }
        elseif (count($list_tmp[0][0]) && !empty($list_tmp[0][0]['id']))
        {
            $return = -1;
        }
        if ($return == 0)
        {
            $this->data['Jurisdiction']['name'] = $this->data['Jurisdiction']['alias'] = $name;
            $this->data['Jurisdiction']['jurisdiction_country_id'] = $country_id;
            $return_tmp = $this->Jurisdiction->save($this->data ['Jurisdiction']);
            if (!empty($return_tmp))
            {
                $list = $this->Jurisdictionprefix->query("select id from  jurisdiction  where  name='$name' and jurisdiction_country_id = $country_id limit 1;");
                if (count($list[0][0]) && !empty($list[0][0]['id']))
                {
                    $return = $list[0][0]['id'];
                }
            }
        }
        return $return;
    }

    public function get_jurisdiction_country_id($name)
    {
        # first add
        $return = 0;
        $list = $this->Jurisdictionprefix->query("select  count(*)   from  jurisdiction_country;");
        if (count($list[0][0]) == 0 || empty($list[0][0]['count']))
        {
            $return = 0;
        }
        #
        $list = $this->Jurisdictionprefix->query("select  id   from  jurisdiction_country  where  name='$name' limit 1;");
        if ($list && count($list[0][0]) && !empty($list[0][0]['id']))
        {
            $return = $list[0][0]['id'];
        }
//		else{
//			$list=$this->Jurisdictionprefix->query("select  max(jurisdiction_country_id)   from  jurisdiction_prefix ;");
//			$t=$list[0][0]['max'];
//			return intval($t)+1;
//		}
        //var_dump($return);
        if ($return == 0)
        {
            $this->data['Jurisdictioncountry']['name'] = $name;
            $return_tmp = $this->Jurisdictioncountry->save($this->data ['Jurisdictioncountry']); //var_dump($return_tmp);
            if (!empty($return_tmp))
            {
                $list = $this->Jurisdictionprefix->query("select id   from  jurisdiction_country  where  name='$name' limit 1;");
                if (count($list[0][0]) && !empty($list[0][0]['id']))
                {
                    $return = $list[0][0]['id'];
                }
            }
        }
        return $return;
    }

    public function add()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
        {
            $this->redirect_denied();
        }


        $datas = $_POST['rates'];

        $save_succeeded = 0;
//        echo '<pre>';
        foreach ($datas as $item)
        {
            $item['ocn'] = empty($item['ocn']) ? '' : $item['ocn'];
            $item['lata'] = empty($item['lata']) ? '' : $item['lata'];
            $item['block_id'] = $item['block_id'];
            $item['effective_date'] = empty($item['effective_date']) ? '' : "'" . $item['effective_date'] . "'";
            $block_id_len = strlen($item['block_id']);
            if ($block_id_len > 1)
            {
                $result = array('flg' => 4);
            }
            else
            {
                $result = $this->Jurisdictionprefix->save_jurisdictionprefix($item);
            }
//            var_dump($item, $block_id_len, $result);
            if ($result['flg'] != 0)
            {
                break;
            }
            else
            {
                $save_succeeded++;
            }
        }
//        die(1);
        switch ($result['flg'])
        {
            case 1 : $msg = "jurisdiction country name add failed";
                break;
            case 2 : $msg = "jurisdiction name add failed,jurisdiction name is exsit";
                break;
            case 3 : $msg = "the jurisdiction_prefix record add failed";
                break;
            case 4 : $msg = "Block ID should be 1 character";
                break;
            default : $msg = "";
        }
        if ($result['flg'])
        {
            $this->Jurisdictionprefix->create_json_array('#ClientOrigRateTableId', 101, "The {$save_succeeded} item save succeeded,and {$msg}");
        }
        else
        {
            $this->Jurisdictionprefix->create_json_array('#ClientOrigRateTableId', 201, "Successful !"); 
        }

        //$this->Jurisdictionprefix->create_json_array('#ClientOrigRateTableId', 201, 'Jurisdiction, action successfully !');

        $this->Session->write("m", Jurisdictionprefix::set_validator());
        //$this->redirect("/jurisdictionprefixs/view?page={$_GET['page']}&size={$_GET['size']}");
        $this->redirect("/jurisdictionprefixs/view");
    }

    public function delete($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $return_data = $this->Jurisdictionprefix->query("DELETE FROM jurisdiction_prefix WHERE id = '{$id}' returning *");
        if ($return_data)
        {
            $this->Jurisdictionprefix->create_json_array('#JurisdictionprefixId', 201, 'Jurisdiction is deleted successfully !');
        }
        else
        {
            $this->Jurisdictionprefix->create_json_array('#JurisdictionprefixId', 101, 'Jurisdiction is deleted failed !');
        }
        $this->Session->write("m", Jurisdictionprefix::set_validator());
        $this->redirect("/jurisdictionprefixs/view");
    }

    public function view()
    {
        $this->pageTitle = "Switch/US Jurisdiction ";
        $result = $this->Jurisdictionprefix->view($this->_order_condtions(array('id', 'jurisdiction_name', 'prefix', 'jurisdiction_country_name')));

        $this->set('p', $result);
    }

    public function view_rate_table($country_id)
    {
        $this->pageTitle = "Switch/Jurisdiction ";
        $this->set('p', $this->Jurisdictionprefix->view_rate_table($country_id, $this->_order_condtions(array('id', 'jurisdiction_name', 'prefix', 'jurisdiction_country_name'))));
    }

//delete all
    public function del_all_jur()
    {
        if (!$_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->Jurisdictionprefix->begin();
        $qs_c = 0;
        $qs = $this->Jurisdictionprefix->query("delete from jurisdiction_prefix");
        $qs_c += count($qs);
//		$qs = $this->Product->query("delete from resource_product_ref");
//		$qs_c += count($qs);
        if ($qs_c == 0)
        {
            $this->Jurisdictionprefix->create_json_array('', 201, __('delallprosuc', true));
            $this->Jurisdictionprefix->commit();
        }
        else
        {
            $this->Jurisdictionprefix->create_json_array('', 101, __('delallprofail', true));
            $this->Jurisdictionprefix->rollback();
        }
        $this->Session->write('m', Jurisdictionprefix::set_validator());
        $this->redirect('/jurisdictionprefixs/view');
    }

    //select delete
    public function del_selected_jur()
    {
        if (!$_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];

        $this->Jurisdictionprefix->begin();
        $qs_c = 0;
        $qs = $this->Jurisdictionprefix->query("delete from jurisdiction_prefix where id in ($ids)");
        $qs_c += count($qs);
//		$qs =	$this->Product->query("delete from resource_product_ref where product_id in ($ids)");
//		$qs_c += count($qs);
        if ($qs_c == 0)
        {
            $this->Jurisdictionprefix->create_json_array('', 201, __('delselprosuc', true));
            $this->Jurisdictionprefix->commit();
        }
        else
        {
            $this->Jurisdictionprefix->create_json_array('', 101, __('delselprofail', true));
            $this->Jurisdictionprefix->rollback();
        }

        $this->Session->write('m', Jurisdictionprefix::set_validator());
        $this->redirect('/jurisdictionprefixs/view');
    }


    public function ajax_get_country_state_by_did()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $did = $_POST['did'];
        $npa_nxx_arr = $this->get_npa_nxx($did);
        if ($npa_nxx_arr === false)
            return json_encode(array('city' => '','state' => '','lata' => ''));
        $npa = $npa_nxx_arr['npa'];
        $nxx = $npa_nxx_arr['nxx'];
        $result = $this->get_country_state_by_npa_nxx($npa,$nxx);
        return json_encode($result);
    }

    public function ajax_get_unAssignment_did()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $did = $_POST['did'];
        $result = $this->Jurisdictionprefix->get_unAssignment_did($did);
        $return_arr = array();
        foreach ($result as $result_item)
            $return_arr[] = array('number' => $result_item[0]['number']);
        echo json_encode($return_arr);
    }

}

?>
