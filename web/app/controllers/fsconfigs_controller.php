<?php

class FsconfigsController extends AppController
{

    var $name = 'Fsconfigs';
    var $helpers = array('javascript', 'html', 'AppFsconfigs', 'appGetewaygroup');

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else
        {
            $limit = $this->Session->read('sst_switchSetting');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function init_dis_con($res_id)
    {
        $lists = $this->Fsconfig->find('all', array('conditions' => "resource_id = " . $res_id, "order" => "sip_error_code_id ASC"));
        $CALL_ARGS = !empty($lists[0]['Fsconfig']['return_code_str']) ? $lists[0]['Fsconfig']['return_code_str'] : 'Not Found';
        $SYSTEM_CAP = !empty($lists[1]['Fsconfig']['return_code_str']) ? $lists[1]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $SYSTEM_CPS = !empty($lists[2]['Fsconfig']['return_code_str']) ? $lists[2]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $INGRESS_IP_CHECK = !empty($lists[3]['Fsconfig']['return_code_str']) ? $lists[3]['Fsconfig']['return_code_str'] : 'Forbidden';
        $INGRESS_RESOURCE = !empty($lists[4]['Fsconfig']['return_code_str']) ? $lists[4]['Fsconfig']['return_code_str'] : 'Forbidden';
        $PRODUCT_CHECK = !empty($lists[5]['Fsconfig']['return_code_str']) ? $lists[5]['Fsconfig']['return_code_str'] : 'Forbidden';

        #code
        $CALL_ARGS_code = !empty($lists[0]['Fsconfig']['return_code']) ? $lists[0]['Fsconfig']['return_code'] : '403';
        $SYSTEM_CAP_code = !empty($lists[1]['Fsconfig']['return_code']) ? $lists[1]['Fsconfig']['return_code'] : '503';
        $SYSTEM_CPS_code = !empty($lists[2]['Fsconfig']['return_code']) ? $lists[2]['Fsconfig']['return_code'] : '503';
        $INGRESS_IP_CHECK_code = !empty($lists[3]['Fsconfig']['return_code']) ? $lists[3]['Fsconfig']['return_code'] : '403';
        $INGRESS_RESOURCE_code = !empty($lists[4]['Fsconfig']['return_code']) ? $lists[4]['Fsconfig']['return_code'] : '403';
        $PRODUCT_CHECK_code = !empty($lists[5]['Fsconfig']['return_code']) ? $lists[5]['Fsconfig']['return_code'] : '403';

        $this->set('CALL_ARGS_code', $CALL_ARGS_code);
        $this->set('SYSTEM_CAP_code', $SYSTEM_CAP_code);
        $this->set('SYSTEM_CPS_code', $SYSTEM_CPS_code);
        $this->set('INGRESS_IP_CHECK_code', $INGRESS_IP_CHECK_code);
        $this->set('INGRESS_RESOURCE_code', $INGRESS_RESOURCE_code);
        $this->set('PRODUCT_CHECK_code', $PRODUCT_CHECK_code);


        $this->set('CALL_ARGS', $CALL_ARGS);
        $this->set('SYSTEM_CAP', $SYSTEM_CAP);
        $this->set('SYSTEM_CPS', $SYSTEM_CPS);
        $this->set('INGRESS_IP_CHECK', $INGRESS_IP_CHECK);
        $this->set('INGRESS_RESOURCE', $INGRESS_RESOURCE);
        $this->set('PRODUCT_CHECK', $PRODUCT_CHECK);



        $IN_RESORUCE_CAP = !empty($lists[6]['Fsconfig']['return_code_str']) ? $lists[6]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $IN_RESORUCE_CPS = !empty($lists[7]['Fsconfig']['return_code_str']) ? $lists[7]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $IN_RESORUCE_IP_CAP = !empty($lists[8]['Fsconfig']['return_code_str']) ? $lists[8]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $IN_RESORUCE_IP_CPS = !empty($lists[9]['Fsconfig']['return_code_str']) ? $lists[9]['Fsconfig']['return_code_str'] : 'Service Unavailable';


        #code
        $IN_RESORUCE_CAP_code = !empty($lists[6]['Fsconfig']['return_code']) ? $lists[6]['Fsconfig']['return_code'] : '503';
        $IN_RESORUCE_CPS_code = !empty($lists[7]['Fsconfig']['return_code']) ? $lists[7]['Fsconfig']['return_code'] : '503';
        $IN_RESORUCE_IP_CAP_code = !empty($lists[8]['Fsconfig']['return_code']) ? $lists[8]['Fsconfig']['return_code'] : '503';
        $IN_RESORUCE_IP_CPS_code = !empty($lists[9]['Fsconfig']['return_code']) ? $lists[9]['Fsconfig']['return_code'] : '503';

        $this->set('IN_RESORUCE_CAP_code', $IN_RESORUCE_CAP_code);
        $this->set('IN_RESORUCE_CPS_code', $IN_RESORUCE_CPS_code);
        $this->set('IN_RESORUCE_IP_CAP_code', $IN_RESORUCE_IP_CAP_code);
        $this->set('IN_RESORUCE_IP_CPS_code', $IN_RESORUCE_IP_CPS_code);

        $this->set('IN_RESORUCE_CAP', $IN_RESORUCE_CAP);
        $this->set('IN_RESORUCE_CPS', $IN_RESORUCE_CPS);
        $this->set('IN_RESORUCE_IP_CAP', $IN_RESORUCE_IP_CAP);
        $this->set('IN_RESORUCE_IP_CPS', $IN_RESORUCE_IP_CPS);

        $RESOURCE_CODEC = !empty($lists[13]['Fsconfig']['return_code_str']) ? $lists[10]['Fsconfig']['return_code_str'] : 'Unsupported Media Type';

        #code
        $RESOURCE_CODEC_code = !empty($lists[13]['Fsconfig']['return_code']) ? $lists[10]['Fsconfig']['return_code'] : '415';
        $this->set('RESOURCE_CODEC', $RESOURCE_CODEC);
        $this->set('RESOURCE_CODEC_code', $RESOURCE_CODEC_code);



        $INGRESS_LRN_BLOCK = !empty($lists[14]['Fsconfig']['return_code_str']) ? $lists[11]['Fsconfig']['return_code_str'] : 'Forbidden';
        $INGRESS_RATE = !empty($lists[15]['Fsconfig']['return_code_str']) ? $lists[12]['Fsconfig']['return_code_str'] : 'Forbidden';
        $EGRESS_NOT_FOUND = !empty($lists[16]['Fsconfig']['return_code_str']) ? $lists[13]['Fsconfig']['return_code_str'] : 'Forbidden';

        $EGRESS_RESPONSE404 = !empty($lists[17]['Fsconfig']['return_code_str']) ? $lists[14]['Fsconfig']['return_code_str'] : 'Not Found';
        $EGRESS_RESPONSE486 = !empty($lists[17]['Fsconfig']['return_code_str']) ? $lists[15]['Fsconfig']['return_code_str'] : 'Busy Here';



        #code
        $INGRESS_LRN_BLOCK_code = !empty($lists[14]['Fsconfig']['return_code']) ? $lists[11]['Fsconfig']['return_code'] : '403';
        $INGRESS_RATE_code = !empty($lists[15]['Fsconfig']['return_code']) ? $lists[12]['Fsconfig']['return_code'] : '403';
        $EGRESS_NOT_FOUND_code = !empty($lists[16]['Fsconfig']['return_code']) ? $lists[13]['Fsconfig']['return_code'] : '403';

        $EGRESS_RESPONSE404_code = !empty($lists[17]['Fsconfig']['return_code']) ? $lists[14]['Fsconfig']['return_code'] : '404';
        $EGRESS_RESPONSE486_code = !empty($lists[17]['Fsconfig']['return_code']) ? $lists[15]['Fsconfig']['return_code'] : '408';

        $this->set('INGRESS_LRN_BLOCK_code', $INGRESS_LRN_BLOCK_code);
        $this->set('INGRESS_RATE_code', $INGRESS_RATE_code);
        $this->set('EGRESS_NOT_FOUND_code', $EGRESS_NOT_FOUND_code);
        $this->set('EGRESS_RESPONSE404_code', $EGRESS_RESPONSE404_code);
        $this->set('EGRESS_RESPONSE486_code', $EGRESS_RESPONSE486_code);

        $this->set('INGRESS_LRN_BLOCK', $INGRESS_LRN_BLOCK);
        $this->set('INGRESS_RATE', $INGRESS_RATE);
        $this->set('EGRESS_NOT_FOUND', $EGRESS_NOT_FOUND);
        $this->set('EGRESS_RESPONSE404', $EGRESS_RESPONSE404);
        $this->set('EGRESS_RESPONSE486', $EGRESS_RESPONSE486);



        $EGRESS_RESPONSE487 = !empty($lists[16]['Fsconfig']['return_code_str']) ? $lists[16]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $EGRESS_RESPONSE200 = !empty($lists[17]['Fsconfig']['return_code_str']) ? $lists[17]['Fsconfig']['return_code_str'] : 'OK';
        $ALL_EGRESS_FAILED = !empty($lists[18]['Fsconfig']['return_code_str']) ? $lists[18]['Fsconfig']['return_code_str'] : 'Service Unavailable';


        $INGRESS_RESOURCE_DISABLED = !empty($lists[20]['Fsconfig']['return_code_str']) ? $lists[20]['Fsconfig']['return_code_str'] : 'Forbidden';
        $INGRESS_RESOURCE_DISABLED_CODE = !empty($lists[20]['Fsconfig']['return_code']) ? $lists[20]['Fsconfig']['return_code'] : '403';

        $BALANCE_USE_UP = !empty($lists[21]['Fsconfig']['return_code_str']) ? $lists[21]['Fsconfig']['return_code_str'] : 'Payment Required';
        $BALANCE_USE_UP_CODE = !empty($lists[21]['Fsconfig']['return_code']) ? $lists[21]['Fsconfig']['return_code'] : '402';

        $NO_ROUTING_PLAN_ROUTE = !empty($lists[22]['Fsconfig']['return_code_str']) ? $lists[22]['Fsconfig']['return_code_str'] : 'Forbidden';
        $NO_ROUTING_PLAN_ROUTE_CODE = !empty($lists[22]['Fsconfig']['return_code']) ? $lists[22]['Fsconfig']['return_code'] : '403';


        $NO_ROUTING_PLAN_PREFIX = !empty($lists[23]['Fsconfig']['return_code_str']) ? $lists[23]['Fsconfig']['return_code_str'] : 'Forbidden';
        $NO_ROUTING_PLAN_PREFIX_CODE = !empty($lists[23]['Fsconfig']['return_code']) ? $lists[23]['Fsconfig']['return_code'] : '403';
        $INGRESS_RATE_NO_CONFIGURE = !empty($lists[24]['Fsconfig']['return_code_str']) ? $lists[24]['Fsconfig']['return_code_str'] : 'Forbidden';
        $INGRESS_RATE_NO_CONFIGURE_CODE = !empty($lists[24]['Fsconfig']['return_code']) ? $lists[24]['Fsconfig']['return_code'] : '403';
        
        $Termination_Invalid_Codec_Negotiation = !empty($lists[25]['Fsconfig']['return_code_str']) ? $lists[25]['Fsconfig']['return_code_str'] : 'Unsupported Media Type';
        $Termination_Invalid_Codec_Negotiation_CODE = !empty($lists[25]['Fsconfig']['return_code']) ? $lists[25]['Fsconfig']['return_code'] : '415';
        $No_Codec_Found = !empty($lists[26]['Fsconfig']['return_code_str']) ? $lists[26]['Fsconfig']['return_code_str'] : 'Unsupported Media Type';
        $No_Codec_Found_CODE = !empty($lists[26]['Fsconfig']['return_code']) ? $lists[26]['Fsconfig']['return_code'] : '415';
        $All_egress_no_confirmed = !empty($lists[27]['Fsconfig']['return_code_str']) ? $lists[27]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $All_egress_no_confirmed_CODE = !empty($lists[27]['Fsconfig']['return_code']) ? $lists[27]['Fsconfig']['return_code'] : '503';
        $LRN_response_no_exist_DNIS = !empty($lists[28]['Fsconfig']['return_code_str']) ? $lists[28]['Fsconfig']['return_code_str'] : 'Forbidden';
        $LRN_response_no_exist_DNIS_CODE = !empty($lists[28]['Fsconfig']['return_code']) ? $lists[28]['Fsconfig']['return_code'] : '403';
        $Carrier_CAP_Limit_Exceeded = !empty($lists[29]['Fsconfig']['return_code_str']) ? $lists[29]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $Carrier_CAP_Limit_Exceeded_CODE = !empty($lists[29]['Fsconfig']['return_code']) ? $lists[29]['Fsconfig']['return_code'] : '503';
        $Carrier_CPS_Limit_Exceeded = !empty($lists[30]['Fsconfig']['return_code_str']) ? $lists[30]['Fsconfig']['return_code_str'] : 'Service Unavailable';
        $Carrier_CPS_Limit_Exceeded_CODE = !empty($lists[30]['Fsconfig']['return_code']) ? $lists[30]['Fsconfig']['return_code'] : '503';
        $Host_Alert_Reject = !empty($lists[31]['Fsconfig']['return_code_str']) ? $lists[31]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Host_Alert_Reject_CODE = !empty($lists[31]['Fsconfig']['return_code']) ? $lists[31]['Fsconfig']['return_code'] : '403';
        $Resource_Alert_Reject = !empty($lists[32]['Fsconfig']['return_code_str']) ? $lists[32]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Resource_Alert_Reject_CODE = !empty($lists[32]['Fsconfig']['return_code']) ? $lists[32]['Fsconfig']['return_code'] : '403';
        $Resource_Reject_H323 = !empty($lists[33]['Fsconfig']['return_code_str']) ? $lists[33]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Resource_Reject_H323_CODE = !empty($lists[33]['Fsconfig']['return_code']) ? $lists[33]['Fsconfig']['return_code'] : '403';
        $I180_Negotiation_SDP_Failed = !empty($lists[34]['Fsconfig']['return_code_str']) ? $lists[34]['Fsconfig']['return_code_str'] : 'Unsupported Media Type';
        $I180_Negotiation_SDP_Failed_CODE = !empty($lists[34]['Fsconfig']['return_code']) ? $lists[34]['Fsconfig']['return_code'] : '415';
        $I183_Negotiation_SDP_Failed = !empty($lists[35]['Fsconfig']['return_code_str']) ? $lists[35]['Fsconfig']['return_code_str'] : 'Unsupported Media Type';
        $I183_Negotiation_SDP_Failed_CODE = !empty($lists[35]['Fsconfig']['return_code']) ? $lists[35]['Fsconfig']['return_code'] : '415';
        $I200_Negotiation_SDP_Failed = !empty($lists[36]['Fsconfig']['return_code_str']) ? $lists[36]['Fsconfig']['return_code_str'] : 'Unsupported Media Type';
        $I200_Negotiation_SDP_Failed_CODE = !empty($lists[36]['Fsconfig']['return_code']) ? $lists[36]['Fsconfig']['return_code'] : '415';
        $LRN_Block_Higher_Rate = !empty($lists[37]['Fsconfig']['return_code_str']) ? $lists[37]['Fsconfig']['return_code_str'] : 'Forbidden';
        $LRN_Block_Higher_Rate_CODE = !empty($lists[37]['Fsconfig']['return_code']) ? $lists[37]['Fsconfig']['return_code'] : '403';
        
        $Trunk_Block_ANI = !empty($lists[38]['Fsconfig']['return_code_str']) ? $lists[38]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Trunk_Block_ANI_CODE = !empty($lists[38]['Fsconfig']['return_code']) ? $lists[38]['Fsconfig']['return_code'] : '403';
        $Trunk_Block_DNIS = !empty($lists[39]['Fsconfig']['return_code_str']) ? $lists[39]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Trunk_Block_DNIS_CODE = !empty($lists[39]['Fsconfig']['return_code']) ? $lists[39]['Fsconfig']['return_code'] : '403';
        $Trunk_Block_ALL = !empty($lists[40]['Fsconfig']['return_code_str']) ? $lists[40]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Trunk_Block_ALL_CODE = !empty($lists[40]['Fsconfig']['return_code']) ? $lists[40]['Fsconfig']['return_code'] : '403';
        $Block_ANI = !empty($lists[41]['Fsconfig']['return_code_str']) ? $lists[41]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Block_ANI_CODE = !empty($lists[41]['Fsconfig']['return_code']) ? $lists[41]['Fsconfig']['return_code'] : '403';
        $Block_DNIS = !empty($lists[42]['Fsconfig']['return_code_str']) ? $lists[42]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Block_DNIS_CODE = !empty($lists[42]['Fsconfig']['return_code']) ? $lists[42]['Fsconfig']['return_code'] : '403';
        $Block_ALL = !empty($lists[43]['Fsconfig']['return_code_str']) ? $lists[43]['Fsconfig']['return_code_str'] : 'Forbidden';
        $Block_ALL_CODE = !empty($lists[43]['Fsconfig']['return_code']) ? $lists[43]['Fsconfig']['return_code'] : '403';
        $T38_Reject = !empty($lists[44]['Fsconfig']['return_code_str']) ? $lists[44]['Fsconfig']['return_code_str'] : 'Forbidden';
        $T38_Reject_CODE = !empty($lists[44]['Fsconfig']['return_code']) ? $lists[44]['Fsconfig']['return_code'] : '503';
        
        
        $this->set('Trunk_Block_ANI', $Trunk_Block_ANI);
        $this->set('Trunk_Block_ANI_CODE', $Trunk_Block_ANI_CODE);
        $this->set('Trunk_Block_DNIS', $Trunk_Block_DNIS);
        $this->set('Trunk_Block_DNIS_CODE', $Trunk_Block_DNIS_CODE);
        $this->set('Trunk_Block_ALL', $Trunk_Block_ALL);
        $this->set('Trunk_Block_ALL_CODE', $Trunk_Block_ALL_CODE);
        $this->set('Block_ANI', $Block_ANI);
        $this->set('Block_ANI_CODE', $Block_ANI_CODE);
        $this->set('Block_DNIS', $Block_DNIS);
        $this->set('Block_DNIS_CODE', $Block_DNIS_CODE);
        $this->set('Block_ALL', $Block_ALL);
        $this->set('Block_ALL_CODE', $Block_ALL_CODE);
        $this->set('T38_Reject', $T38_Reject);
        $this->set('T38_Reject_CODE', $T38_Reject_CODE);
        
        $this->set('Termination_Invalid_Codec_Negotiation', $Termination_Invalid_Codec_Negotiation);
        $this->set('Termination_Invalid_Codec_Negotiation_CODE', $Termination_Invalid_Codec_Negotiation_CODE);
        $this->set('No_Codec_Found', $No_Codec_Found);
        $this->set('No_Codec_Found_CODE', $No_Codec_Found_CODE);
        $this->set('All_egress_no_confirmed', $All_egress_no_confirmed);
        $this->set('All_egress_no_confirmed_CODE', $All_egress_no_confirmed_CODE);
        $this->set('LRN_response_no_exist_DNIS', $LRN_response_no_exist_DNIS);
        $this->set('LRN_response_no_exist_DNIS_CODE', $LRN_response_no_exist_DNIS_CODE);
        $this->set('Carrier_CAP_Limit_Exceeded', $Carrier_CAP_Limit_Exceeded);
        $this->set('Carrier_CAP_Limit_Exceeded_CODE', $Carrier_CAP_Limit_Exceeded_CODE);
        $this->set('Carrier_CPS_Limit_Exceeded', $Carrier_CPS_Limit_Exceeded);
        $this->set('Carrier_CPS_Limit_Exceeded_CODE', $Carrier_CPS_Limit_Exceeded_CODE);
        $this->set('Host_Alert_Reject', $Host_Alert_Reject);
        $this->set('Host_Alert_Reject_CODE', $Host_Alert_Reject_CODE);
        $this->set('Resource_Alert_Reject', $Resource_Alert_Reject);
        $this->set('Resource_Alert_Reject_CODE', $Resource_Alert_Reject_CODE);
        $this->set('Resource_Reject_H323', $Resource_Reject_H323);
        $this->set('Resource_Reject_H323_CODE', $Resource_Reject_H323_CODE);
        $this->set('I180_Negotiation_SDP_Failed', $I180_Negotiation_SDP_Failed);
        $this->set('I180_Negotiation_SDP_Failed_CODE', $I180_Negotiation_SDP_Failed_CODE);
        $this->set('I183_Negotiation_SDP_Failed', $I183_Negotiation_SDP_Failed);
        $this->set('I183_Negotiation_SDP_Failed_CODE', $I183_Negotiation_SDP_Failed_CODE);
        $this->set('I200_Negotiation_SDP_Failed', $I200_Negotiation_SDP_Failed);
        $this->set('I200_Negotiation_SDP_Failed_CODE', $I200_Negotiation_SDP_Failed_CODE);
        $this->set('LRN_Block_Higher_Rate', $LRN_Block_Higher_Rate);
        $this->set('LRN_Block_Higher_Rate_CODE', $LRN_Block_Higher_Rate_CODE);
        
        
        $NORMAL = !empty($lists[19]['Fsconfig']['return_code_str']) ? $lists[19]['Fsconfig']['return_code_str'] : 'OK';


        $EGRESS_RESPONSE487_code = !empty($lists[16]['Fsconfig']['return_code']) ? $lists[16]['Fsconfig']['return_code'] : '503';
        $EGRESS_RESPONSE200_code = !empty($lists[17]['Fsconfig']['return_code']) ? $lists[17]['Fsconfig']['return_code'] : '200';
        $ALL_EGRESS_FAILED_code = !empty($lists[18]['Fsconfig']['return_code']) ? $lists[18]['Fsconfig']['return_code'] : '503';
        $NORMAL_code = !empty($lists[19]['Fsconfig']['return_code']) ? $lists[19]['Fsconfig']['return_code'] : '200';


        $this->set('EGRESS_RESPONSE487_code', $EGRESS_RESPONSE487_code);
        $this->set('EGRESS_RESPONSE200_code', $EGRESS_RESPONSE200_code);
        $this->set('ALL_EGRESS_FAILED_code', $ALL_EGRESS_FAILED_code);
        $this->set('NORMAL_code', $NORMAL_code);

        $this->set('INGRESS_RESOURCE_DISABLED', $INGRESS_RESOURCE_DISABLED);
        $this->set('INGRESS_RESOURCE_DISABLED_CODE', $INGRESS_RESOURCE_DISABLED_CODE);

        $this->set('BALANCE_USE_UP', $BALANCE_USE_UP);
        $this->set('BALANCE_USE_UP_CODE', $BALANCE_USE_UP_CODE);

        $this->set('NO_ROUTING_PLAN_ROUTE', $NO_ROUTING_PLAN_ROUTE);
        $this->set('NO_ROUTING_PLAN_ROUTE_CODE', $NO_ROUTING_PLAN_ROUTE_CODE);

        $this->set('NO_ROUTING_PLAN_PREFIX', $NO_ROUTING_PLAN_PREFIX);
        $this->set('NO_ROUTING_PLAN_PREFIX_CODE', $NO_ROUTING_PLAN_PREFIX_CODE);

        $this->set('INGRESS_RATE_NO_CONFIGURE', $INGRESS_RATE_NO_CONFIGURE);
        $this->set('INGRESS_RATE_NO_CONFIGURE_CODE', $INGRESS_RATE_NO_CONFIGURE_CODE);

        $this->set('EGRESS_RESPONSE487', $EGRESS_RESPONSE487);
        $this->set('EGRESS_RESPONSE200', $EGRESS_RESPONSE200);
        $this->set('ALL_EGRESS_FAILED', $ALL_EGRESS_FAILED);
        $this->set('NORMAL', $NORMAL);
    }

    function set_name_gress($resource_id)
    {
        $this->set('resource_id', $resource_id);
        $list = $this->Fsconfig->query("select ingress , egress, alias as name,(select name FROM client WHERE client_id = resource.client_id) as client_name from resource where resource_id=$resource_id");
        $this->set('name', $list[0][0]['name']);
        $this->set('client_name', $list[0][0]['client_name']);
        if ($list[0][0]['ingress'])
        {
            $this->set('gress', 'ingress');
        } else
        {
            $this->set('gress', 'egress');
        }
    }

    public function config_info($res_id = null)
    {
        $res_id = base64_decode($res_id);
        $this->set_name_gress($res_id);
        if (!empty($this->data))
        {
            $this->Fsconfig->query("delete from  sip_error_code  where  resource_id=$res_id");

            #1.2.3.4.5
            $CALL_ARGS = !empty($this->data['CALL_ARGS']) ? $this->data['CALL_ARGS'] : 'Not Found';
            $SYSTEM_CAP = !empty($this->data['SYSTEM_CAP']) ? $this->data['SYSTEM_CAP'] : 'Service Unavailable';
            $SYSTEM_CPS = !empty($this->data['SYSTEM_CPS']) ? $this->data['SYSTEM_CPS'] : 'Service Unavailable';
            $INGRESS_IP_CHECK = !empty($this->data['INGRESS_IP_CHECK']) ? $this->data['INGRESS_IP_CHECK'] : 'Forbidden';
            $INGRESS_RESOURCE = !empty($this->data['INGRESS_RESOURCE']) ? $this->data['INGRESS_RESOURCE'] : 'Forbidden';
            $PRODUCT_CHECK = !empty($this->data['PRODUCT_CHECK']) ? $this->data['PRODUCT_CHECK'] : 'Forbidden';
            #code
            $CALL_ARGS_code = !empty($this->data['CALL_ARGS_code']) ? $this->data['CALL_ARGS_code'] : '404';
            $SYSTEM_CAP_code = !empty($this->data['SYSTEM_CAP_code']) ? $this->data['SYSTEM_CAP_code'] : '503';
            $SYSTEM_CPS_code = !empty($this->data['SYSTEM_CPS_code']) ? $this->data['SYSTEM_CPS_code'] : '503';
            $INGRESS_IP_CHECK_code = !empty($this->data['INGRESS_IP_CHECK_code']) ? $this->data['INGRESS_IP_CHECK_code'] : '403';
            $INGRESS_RESOURCE_code = !empty($this->data['INGRESS_RESOURCE_code']) ? $this->data['INGRESS_RESOURCE_code'] : '403';
            $PRODUCT_CHECK_code = !empty($this->data['PRODUCT_CHECK_code']) ? $this->data['PRODUCT_CHECK_code'] : '403';
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(0,$res_id,$CALL_ARGS_code,'$CALL_ARGS')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(1,$res_id,$SYSTEM_CAP_code,'$SYSTEM_CAP')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(2,$res_id,$SYSTEM_CPS_code,'$SYSTEM_CPS')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(3,$res_id,$INGRESS_IP_CHECK_code,'$INGRESS_IP_CHECK')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(4,$res_id,$INGRESS_RESOURCE_code,'$INGRESS_RESOURCE')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(5,$res_id,$PRODUCT_CHECK_code,'$PRODUCT_CHECK')");

            #6,7,8,9
            $IN_RESORUCE_CAP = !empty($this->data['IN_RESORUCE_CAP']) ? $this->data['IN_RESORUCE_CAP'] : 'Service Unavailable';
            $IN_RESORUCE_CPS = !empty($this->data['IN_RESORUCE_CPS']) ? $this->data['IN_RESORUCE_CPS'] : 'Service Unavailable';
            $IN_RESORUCE_IP_CAP = !empty($this->data['IN_RESORUCE_IP_CAP']) ? $this->data['IN_RESORUCE_IP_CAP'] : 'Service Unavailable';
            $IN_RESORUCE_IP_CPS = !empty($this->data['IN_RESORUCE_IP_CPS']) ? $this->data['IN_RESORUCE_IP_CPS'] : 'Service Unavailable';

            #code
            $IN_RESORUCE_CAP_code = !empty($this->data['IN_RESORUCE_CAP_code']) ? $this->data['IN_RESORUCE_CAP_code'] : '503';
            pr($IN_RESORUCE_CAP_code);
            $IN_RESORUCE_CPS_code = !empty($this->data['IN_RESORUCE_CPS_code']) ? $this->data['IN_RESORUCE_CPS_code'] : '503';
            $IN_RESORUCE_IP_CAP_code = !empty($this->data['IN_RESORUCE_IP_CAP_code']) ? $this->data['IN_RESORUCE_IP_CAP_code'] : '503';
            $IN_RESORUCE_IP_CPS_code = !empty($this->data['IN_RESORUCE_IP_CPS_code']) ? $this->data['IN_RESORUCE_IP_CPS_code'] : '503';

            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(6,$res_id,$IN_RESORUCE_CAP_code,'$IN_RESORUCE_CAP')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(7,$res_id,$IN_RESORUCE_CPS_code,'$IN_RESORUCE_CPS')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(8,$res_id,$IN_RESORUCE_IP_CAP_code,'$IN_RESORUCE_IP_CAP')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(9,$res_id,$IN_RESORUCE_IP_CPS_code,'$IN_RESORUCE_IP_CPS')");
            #10
            $RESOURCE_CODEC = !empty($this->data['RESOURCE_CODEC']) ? $this->data['RESOURCE_CODEC'] : 'Unsupported Media Type';
            $RESOURCE_CODEC_code = !empty($this->data['RESOURCE_CODEC_code']) ? $this->data['RESOURCE_CODEC_code'] : '415';

            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(10,$res_id,$RESOURCE_CODEC_code,'$RESOURCE_CODEC')");

            #11,12,13
            $INGRESS_LRN_BLOCK = !empty($this->data['INGRESS_LRN_BLOCK']) ? $this->data['INGRESS_LRN_BLOCK'] : 'Forbidden';
            $INGRESS_RATE = !empty($this->data['INGRESS_RATE']) ? $this->data['INGRESS_RATE'] : 'Forbidden';
            $EGRESS_NOT_FOUND = !empty($this->data['EGRESS_NOT_FOUND']) ? $this->data['EGRESS_NOT_FOUND'] : 'Forbidden';

            $INGRESS_LRN_BLOCK_code = !empty($this->data['INGRESS_LRN_BLOCK_code']) ? $this->data['INGRESS_LRN_BLOCK_code'] : '403';
            $INGRESS_RATE_code = !empty($this->data['INGRESS_RATE_code']) ? $this->data['INGRESS_RATE_code'] : '403';
            $EGRESS_NOT_FOUND_code = !empty($this->data['EGRESS_NOT_FOUND_code']) ? $this->data['EGRESS_NOT_FOUND_code'] : '403';


            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(11,$res_id,$INGRESS_LRN_BLOCK_code,'$INGRESS_LRN_BLOCK')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(12,$res_id,$INGRESS_RATE_code,'$INGRESS_RATE')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(13,$res_id,$EGRESS_NOT_FOUND_code,'$EGRESS_NOT_FOUND')");

            #14,15
            $EGRESS_RESPONSE404 = !empty($this->data['EGRESS_RESPONSE404']) ? $this->data['EGRESS_RESPONSE404'] : 'Not Found';
            $EGRESS_RESPONSE486 = !empty($this->data['EGRESS_RESPONSE486']) ? $this->data['EGRESS_RESPONSE486'] : 'Busy Here';

            $EGRESS_RESPONSE404_code = !empty($this->data['EGRESS_RESPONSE404']) ? $this->data['EGRESS_RESPONSE404_code'] : '404';
            $EGRESS_RESPONSE486_code = !empty($this->data['EGRESS_RESPONSE486']) ? $this->data['EGRESS_RESPONSE486_code'] : '486';

            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(14,$res_id,$EGRESS_RESPONSE404_code,'$EGRESS_RESPONSE404')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(15,$res_id,$EGRESS_RESPONSE486_code,'$EGRESS_RESPONSE486')");

            #16,17,18,19
            $EGRESS_RESPONSE487 = !empty($this->data['EGRESS_RESPONSE487']) ? $this->data['EGRESS_RESPONSE487'] : 'Service Unavailable';
            $EGRESS_RESPONSE200 = !empty($this->data['EGRESS_RESPONSE200']) ? $this->data['EGRESS_RESPONSE200'] : 'OK';
            $ALL_EGRESS_FAILED = !empty($this->data['ALL_EGRESS_FAILED']) ? $this->data['ALL_EGRESS_FAILED'] : 'Service Unavailable';
            $NORMAL = !empty($this->data['NORMAL']) ? $this->data['NORMAL'] : 'OK';
            #code
            $EGRESS_RESPONSE487_code = !empty($this->data['EGRESS_RESPONSE487_code']) ? $this->data['EGRESS_RESPONSE487_code'] : '503';
            $EGRESS_RESPONSE200_code = !empty($this->data['EGRESS_RESPONSE200_code']) ? $this->data['EGRESS_RESPONSE200_code'] : '200';
            $ALL_EGRESS_FAILED_code = !empty($this->data['ALL_EGRESS_FAILED_code']) ? $this->data['ALL_EGRESS_FAILED_code'] : '503';
            $NORMAL_code = !empty($this->data['NORMAL_code']) ? $this->data['NORMAL_code'] : '200';

            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(16,$res_id,$EGRESS_RESPONSE487_code,'$EGRESS_RESPONSE487')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(17,$res_id,$EGRESS_RESPONSE200_code,'$EGRESS_RESPONSE200')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(18,$res_id,$ALL_EGRESS_FAILED_code,'$ALL_EGRESS_FAILED')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(19,$res_id,$NORMAL_code,'$NORMAL')");


            $INGRESS_RESOURCE_DISABLED = !empty($this->data['INGRESS_RESOURCE_DISABLED']) ? $this->data['INGRESS_RESOURCE_DISABLED'] : 'Forbidden';
            $INGRESS_RESOURCE_DISABLED_CODE = !empty($this->data['INGRESS_RESOURCE_DISABLED_CODE']) ? $this->data['INGRESS_RESOURCE_DISABLED_CODE'] : '403';

            $BALANCE_USE_UP = !empty($this->data['$BALANCE_USE_UP']) ? $this->data['$BALANCE_USE_UP'] : 'Payment Required';
            $BALANCE_USE_UP_CODE = !empty($this->data['BALANCE_USE_UP_CODE']) ? $this->data['BALANCE_USE_UP_CODE'] : '402';

            $NO_ROUTING_PLAN_ROUTE = !empty($this->data['NO_ROUTING_PLAN_ROUTE']) ? $this->data['NO_ROUTING_PLAN_ROUTE'] : 'Forbidden';
            $NO_ROUTING_PLAN_ROUTE_CODE = !empty($this->data['NO_ROUTING_PLAN_ROUTE_CODE']) ? $this->data['NO_ROUTING_PLAN_ROUTE_CODE'] : '403';

            $NO_ROUTING_PLAN_PREFIX = !empty($this->data['NO_ROUTING_PLAN_PREFIX']) ? $this->data['NO_ROUTING_PLAN_PREFIX'] : 'Forbidden';
            $NO_ROUTING_PLAN_PREFIX_CODE = !empty($this->data['NO_ROUTING_PLAN_PREFIX_CODE']) ? $this->data['NO_ROUTING_PLAN_PREFIX_CODE'] : '403';

            $INGRESS_RATE_NO_CONFIGURE = !empty($this->data['INGRESS_RATE_NO_CONFIGURE']) ? $this->data['INGRESS_RATE_NO_CONFIGURE'] : 'Forbidden';
            $INGRESS_RATE_NO_CONFIGURE_CODE = !empty($this->data['INGRESS_RATE_NO_CONFIGURE_CODE']) ? $this->data['INGRESS_RATE_NO_CONFIGURE_CODE'] : '403';

            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(20,$res_id,$INGRESS_RESOURCE_DISABLED_CODE,'$INGRESS_RESOURCE_DISABLED')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(21,$res_id,$BALANCE_USE_UP_CODE,'$BALANCE_USE_UP')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(22,$res_id,$NO_ROUTING_PLAN_ROUTE_CODE,'$NO_ROUTING_PLAN_ROUTE')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(23,$res_id,$NO_ROUTING_PLAN_PREFIX_CODE,'$NO_ROUTING_PLAN_PREFIX')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(24,$res_id,$INGRESS_RATE_NO_CONFIGURE_CODE,'$INGRESS_RATE_NO_CONFIGURE')");

            $Termination_Invalid_Codec_Negotiation = !empty($this->data['Termination_Invalid_Codec_Negotiation']) ? $this->data['Termination_Invalid_Codec_Negotiation'] : 'Unsupported';
            $Termination_Invalid_Codec_Negotiation_CODE = !empty($this->data['Termination_Invalid_Codec_Negotiation_CODE']) ? $this->data['Termination_Invalid_Codec_Negotiation_CODE'] : '415';
            
            $No_Codec_Found = !empty($this->data['No_Codec_Found']) ? $this->data['No_Codec_Found'] : 'Unsupported Media Type';
            $No_Codec_Found_CODE = !empty($this->data['No_Codec_Found_CODE']) ? $this->data['No_Codec_Found_CODE'] : '415';
            
            $All_egress_no_confirmed = !empty($this->data['All_egress_no_confirmed']) ? $this->data['All_egress_no_confirmed'] : 'Service Unavailable';
            $All_egress_no_confirmed_CODE = !empty($this->data['All_egress_no_confirmed_CODE']) ? $this->data['All_egress_no_confirmed_CODE'] : '503';
            
            $LRN_response_no_exist_DNIS = !empty($this->data['LRN_response_no_exist_DNIS']) ? $this->data['LRN_response_no_exist_DNIS'] : 'Forbidden';
            $LRN_response_no_exist_DNIS_CODE = !empty($this->data['LRN_response_no_exist_DNIS_CODE']) ? $this->data['LRN_response_no_exist_DNIS_CODE'] : '403';
            
            $Carrier_CAP_Limit_Exceeded = !empty($this->data['Carrier_CAP_Limit_Exceeded']) ? $this->data['Carrier_CAP_Limit_Exceeded'] : 'Service Unavailable';
            $Carrier_CAP_Limit_Exceeded_CODE = !empty($this->data['Carrier_CAP_Limit_Exceeded_CODE']) ? $this->data['Carrier_CAP_Limit_Exceeded_CODE'] : '503';
            
            $Carrier_CPS_Limit_Exceeded = !empty($this->data['Carrier_CPS_Limit_Exceeded']) ? $this->data['Carrier_CPS_Limit_Exceeded'] : 'Service Unavailable';
            $Carrier_CPS_Limit_Exceeded_CODE = !empty($this->data['Carrier_CPS_Limit_Exceeded_CODE']) ? $this->data['Carrier_CPS_Limit_Exceeded_CODE'] : '503';
            
            $Host_Alert_Reject = !empty($this->data['Host_Alert_Reject']) ? $this->data['Host_Alert_Reject'] : 'Forbidden';
            $Host_Alert_Reject_CODE = !empty($this->data['Host_Alert_Reject_CODE']) ? $this->data['Host_Alert_Reject_CODE'] : '403';
            
            $Resource_Alert_Reject = !empty($this->data['Resource_Alert_Reject']) ? $this->data['Resource_Alert_Reject'] : 'Forbidden';
            $Resource_Alert_Reject_CODE = !empty($this->data['Resource_Alert_Reject_CODE']) ? $this->data['Resource_Alert_Reject_CODE'] : '403';
            
            $Resource_Reject_H323 = !empty($this->data['Resource_Reject_H323']) ? $this->data['Resource_Reject_H323'] : 'Forbidden';
            $Resource_Reject_H323_CODE = !empty($this->data['Resource_Reject_H323_CODE']) ? $this->data['Resource_Reject_H323_CODE'] : '403';
            
            $I180_Negotiation_SDP_Failed = !empty($this->data['I180_Negotiation_SDP_Failed']) ? $this->data['I180_Negotiation_SDP_Failed'] : 'Unsupported Media Type';
            $I180_Negotiation_SDP_Failed_CODE = !empty($this->data['I180_Negotiation_SDP_Failed_CODE']) ? $this->data['I180_Negotiation_SDP_Failed_CODE'] : '415';
            
            $I183_Negotiation_SDP_Failed = !empty($this->data['I183_Negotiation_SDP_Failed']) ? $this->data['I183_Negotiation_SDP_Failed'] : 'Unsupported Media Type';
            $I183_Negotiation_SDP_Failed_CODE = !empty($this->data['I183_Negotiation_SDP_Failed_CODE']) ? $this->data['I183_Negotiation_SDP_Failed_CODE'] : '415';
            
            $I200_Negotiation_SDP_Failed = !empty($this->data['I200_Negotiation_SDP_Failed']) ? $this->data['I200_Negotiation_SDP_Failed'] : 'Unsupported Media Type';
            $I200_Negotiation_SDP_Failed_CODE = !empty($this->data['I200_Negotiation_SDP_Failed_CODE']) ? $this->data['I200_Negotiation_SDP_Failed_CODE'] : '415';
            
            $LRN_Block_Higher_Rate = !empty($this->data['LRN_Block_Higher_Rate']) ? $this->data['LRN_Block_Higher_Rate'] : 'Forbidden';
            $LRN_Block_Higher_Rate_CODE = !empty($this->data['LRN_Block_Higher_Rate_CODE']) ? $this->data['LRN_Block_Higher_Rate_CODE'] : '403';
            
            $Trunk_Block_ANI = !empty($this->data['Trunk_Block_ANI']) ? $this->data['Trunk_Block_ANI'] : 'Forbidden';
            $Trunk_Block_ANI_CODE = !empty($this->data['Trunk_Block_ANI_CODE']) ? $this->data['Trunk_Block_ANI_CODE'] : '403';
            $Trunk_Block_DNIS = !empty($this->data['Trunk_Block_DNIS']) ? $this->data['Trunk_Block_DNIS'] : 'Forbidden';
            $Trunk_Block_DNIS_CODE = !empty($this->data['Trunk_Block_DNIS_CODE']) ? $this->data['Trunk_Block_DNIS_CODE'] : '403';
            $Trunk_Block_ALL = !empty($this->data['Trunk_Block_ALL']) ? $this->data['Trunk_Block_ALL'] : 'Forbidden';
            $Trunk_Block_ALL_CODE = !empty($this->data['Trunk_Block_ALL_CODE']) ? $this->data['Trunk_Block_ALL_CODE'] : '403';
            $Block_ANI = !empty($this->data['Block_ANI']) ? $this->data['Block_ANI'] : 'Forbidden';
            $Block_ANI_CODE = !empty($this->data['Block_ANI_CODE']) ? $this->data['Block_ANI_CODE'] : '403';
            $Block_DNIS = !empty($this->data['Block_DNIS']) ? $this->data['Block_DNIS'] : 'Forbidden';
            $Block_DNIS_CODE = !empty($this->data['Block_DNIS_CODE']) ? $this->data['Block_DNIS_CODE'] : '403';
            $Block_ALL = !empty($this->data['Block_ALL']) ? $this->data['Block_ALL'] : 'Forbidden';
            $Block_ALL_CODE = !empty($this->data['Block_ALL_CODE']) ? $this->data['Block_ALL_CODE'] : '403';
//            $T38_Reject = !empty($this->data['T38_Reject']) ? $this->data['T38_Reject'] : 'Forbidden';
//            $T38_Reject_CODE = !empty($this->data['T38_Reject_CODE']) ? $this->data['T38_Reject_CODE'] : '503';
            
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(25,$res_id,$Termination_Invalid_Codec_Negotiation_CODE,'$Termination_Invalid_Codec_Negotiation')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(26,$res_id,$No_Codec_Found_CODE,'$No_Codec_Found')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(27,$res_id,$All_egress_no_confirmed_CODE,'$All_egress_no_confirmed')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(28,$res_id,$LRN_response_no_exist_DNIS_CODE,'$LRN_response_no_exist_DNIS')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(29,$res_id,$Carrier_CAP_Limit_Exceeded_CODE,'$Carrier_CAP_Limit_Exceeded')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(30,$res_id,$Carrier_CPS_Limit_Exceeded_CODE,'$Carrier_CPS_Limit_Exceeded')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(31,$res_id,$Host_Alert_Reject_CODE,'$Host_Alert_Reject')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(32,$res_id,$Resource_Alert_Reject_CODE,'$Resource_Alert_Reject')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(33,$res_id,$Resource_Reject_H323_CODE,'$Resource_Reject_H323')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(34,$res_id,$I180_Negotiation_SDP_Failed_CODE,'$I180_Negotiation_SDP_Failed')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(35,$res_id,$I183_Negotiation_SDP_Failed_CODE,'$I183_Negotiation_SDP_Failed')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(36,$res_id,$I200_Negotiation_SDP_Failed_CODE,'$I200_Negotiation_SDP_Failed')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(37,$res_id,$LRN_Block_Higher_Rate_CODE,'$LRN_Block_Higher_Rate')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(38,$res_id,$Trunk_Block_ANI_CODE,'$Trunk_Block_ANI')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(39,$res_id,$Trunk_Block_DNIS_CODE,'$Trunk_Block_DNIS')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(40,$res_id,$Trunk_Block_ALL_CODE,'$Trunk_Block_ALL')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(41,$res_id,$Block_ANI_CODE,'$Block_ANI')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(42,$res_id,$Block_DNIS_CODE,'$Block_DNIS')");
            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(43,$res_id,$Block_ALL_CODE,'$Block_ALL')");
//            $this->Fsconfig->query("insert into sip_error_code  (switch_error_code,resource_id, return_code,return_code_str)values(44,$res_id,$T38_Reject_CODE,'$T38_Reject')");
            
            
            $this->Fsconfig->create_json_array('', 201, 'Update  Success');
            $this->Session->write("m", Fsconfig::set_validator());
            $this->redirect("/fsconfigs/config_info/".base64_encode($res_id));
        }
        $this->init_dis_con($res_id);
    }

    public function config_fail_policy()
    {
        Configure::write('debug', 0);
        $id = $_REQUEST['id'];
        $qs = $this->Fsconfig->config_fail($id);
        if ($qs != true)
            echo __('update_fail', true) . "|false";
        else
            echo __('update_suc', true) . "|true";
    }

    public function add_international()
    {
        Configure::write('debug', 0);
        $qs = $this->Fsconfig->add();
        echo $qs;
    }

    public function edit_international()
    {
        Configure::write('debug', 0);
        $qs = $this->Fsconfig->update();
        echo $qs;
    }

    public function del($id, $type)
    {
        $this->Fsconfig->del_it($id, $type);
        $this->redirect('/fsconfigs/config_info');
    }

    public function add_forbidden()
    {
        Configure::write('debug', 0);
        $qs = $this->Fsconfig->add_forbidden();
        echo $qs;
    }

    public function add_forbidden_ani()
    {
        Configure::write('debug', 0);
        $qs = $this->Fsconfig->add_forbidden_ani();
        echo $qs;
    }

    public function edit_forbidden()
    {
        Configure::write('debug', 0);
        $qs = $this->Fsconfig->update_forbidden();
        echo $qs;
    }

    public function edit_forbidden_ani()
    {
        Configure::write('debug', 0);
        $qs = $this->Fsconfig->update_forbidden_ani();
        echo $qs;
    }

}