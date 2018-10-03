<?php

require_once 'class.base.php';
require_once 'class.global_reports.php';

class Report extends Base
{
    /**
     * Data from API.
     * @var array
     */
    private $data;

    /**
     * Object of class GlobalReports.
     * @var GlobalReports
     */
    private $GlobalReports;

    /**
     * Array of keys which contains description and needed fields from API to calculate these values.
     * @var array
     */
    private $keysTemplate;

    /**
     * Group keys from response API
     * @var array
     */
    private $groupFields = array(
        'time', 'orig_code', 'ingress_id', 'egress_id', 'source_number', 'dest_number', 'release_cause', 'orig_sip_resp', 'term_sip_resp'
    );

    /**
     * Contains request for special formulas
     * @var array
     */
    private $request = array();

    /**
     * Contains fields, which can be calculated after finishing creating main list.
     * @var array
     */
    private $additionalFields = array('did_price', 'min_price', 'monthly_charge');

    /**
     * Report constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->data = array();
        $this->GlobalReports = new GlobalReports();
        $this->initKeysTemplate();
    }

    /**
     * @param $data
     * @return mixed (false or response from API)
     */
    public function process($data, $json = true, $logId = null)
    {
        $result = array();
        $useTime = false;
        $fields = $data['fields'];

        if ((($data['end_time'] - $data['start_time']) / 60) % 60 !== 0) {
            if (date('i:s', $data['end_time']) == '59:59') {
                $data['end_time']++;
            }

            $data['start_time'] = strtotime(date('Y-m-d H:00:00', $data['start_time']));
            $data['end_time'] = strtotime(date('Y-m-d H:00:00', $data['end_time']));
        }

        if (!isset($data['step'])) {
            $data['step'] = ($data['end_time'] - $data['start_time']) / 60;
        }

        if (!is_array($fields) && $fields) {
            $fields = array($fields);
        }

        if (isset($data['time'])) {
            $useTime = true;
            array_push($fields, 'time');
            $data['step'] = $data['time'];
            unset($data['time']);
        }
        $data['step'] = round($data['step']);
        $data['fields'] = $this->parseFields($fields);
        $this->request = $data;
        $this->data = $this->GlobalReports->process($data, $useTime);
        if ($this->data['code'] != 200) {
            $result = $this->data;
        } else {
            $result = array();
            $this->data = $this->data['data'];
            $totalRow = array();

            if ($logId) {
                App::import('Model', 'CdrApiExportLog');

                $cdrApiExportLog = new CdrApiExportLog;
                $totalRecords = count($this->data);
                $completedRecords = 0;
                $updateLimit = 100;
                $cdrApiExportLog->save(array(
                    'id' => $logId,
                    'completed_records' => $completedRecords,
                    'total_records' => $totalRecords
                ));
            }

            foreach ($this->additionalFields as $additionalField) {
                App::import('Model', 'did.DidBillingRel');
                $didBillingRel = new DidBillingRel;

                if (in_array($additionalField, $fields)) {
                    foreach ($this->data as $key => $item) {
                        if (!empty($item['dest_number']) && ((!empty($item['egress_id']) && $item['egress_id'] != 'NULL') || (!empty($item['ingress_id']) && $item['ingress_id'] != 'NULL'))) {
                            $conditions = array(
                                'did' => $item['dest_number']
                            );

                            if (!empty($item['egress_id']) && $item['egress_id'] != 'NULL') {
                                $conditions['ingress_res_id'] = $item['egress_id'];
                            }
                            if (!empty($item['ingress_id']) && $item['ingress_id'] != 'NULL') {
                                $conditions['egress_res_id'] = $item['ingress_id'];
                            }

                            if ($additionalField == 'min_price') {
                                $value = $didBillingRel->find('first', array(
                                    'fields' => array("DidBillingPlan.rate_table_id"),
                                    'conditions' => $conditions,
                                    'joins' => array(
                                        array(
                                            'table' => 'did_billing_plan',
                                            'alias' => 'DidBillingPlan',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'DidBillingRel.buy_billing_plan_id = DidBillingPlan.id'
                                            )
                                        )
                                    )
                                ));

                                if (!empty($value)) {
                                    $minPrice = $didBillingRel->query("SELECT min(rate) FROM rate WHERE rate_table_id = {$value['DidBillingPlan']['rate_table_id']}");

                                    if ($minPrice && !empty($minPrice)) {
                                        $this->data[$key]['min_price'] = $minPrice[0][0]['min'];
                                    } else {
                                        $this->data[$key]['min_price'] = '0';
                                    }
                                } else {
                                    $this->data[$key]['min_price'] = '0';
                                }
                            } else {
                                $value = $didBillingRel->find('first', array(
                                    'fields' => array("DidBillingPlan.{$additionalField}"),
                                    'conditions' => $conditions,
                                    'order' => array('DidBillingRel.id DESC'),
                                    'joins' => array(
                                        array(
                                            'table' => 'did_billing_plan',
                                            'alias' => 'DidBillingPlan',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'DidBillingRel.buy_billing_plan_id = DidBillingPlan.id'
                                            )
                                        )
                                    )
                                ));
                                $this->data[$key][$additionalField] = $value ? $value['DidBillingPlan'][$additionalField] ?: '0' : '0';
                            }
                        }
                    }
                }
            }

            foreach ($this->data as $key => $item) {
                foreach ($item as $subKey => $subItem) {
                    if (in_array($subKey, $this->groupFields)) {
                        if (in_array($subKey, array('ingress_id', 'egress_id'))) {
                            if ($subItem == 'NULL') {
                                $subItem = '--';
                            } else {
                                App::import('Model', 'Resource');

                                $resourceModel = new Resource;
                                $resultSelect = $resourceModel->find('first', array(
                                    'fields' => array('Resource.alias'),
                                    'conditions' => array(
                                        'resource_id' => $subItem
                                    )
                                ));
                                $subItem = $resultSelect ? $resultSelect['Resource']['alias'] : '--';
                            }
                        }
                        $result[$key][$subKey] = $subItem;
                        $totalRow[$subKey] = 'Total';
                    }
                }
                foreach ($fields as $field) {
                    $result[$key][$field] = $this->getHumanValue($field, $item) ? : $item[$field];
                    $result[$key][$field] = is_callable($result[$key][$field]) ? $result[$key][$field]() : $result[$key][$field];

                    if ($result[$key][$field] == null) {
                        $result[$key][$field] = 0;
                    }

                    if ($field != 'time') {
                        $totalRow[$field] += $result[$key][$field];
                    }
                    if (isset($this->keysTemplate[$field]['precision'])) {
                        $result[$key][$field] = round($result[$key][$field], $this->keysTemplate[$field]['precision']);
                    }
                    if (isset($this->keysTemplate[$field]['postfix'])) {
                        $result[$key][$field] .= $this->keysTemplate[$field]['postfix'];
                    }
                }
                $completedRecords++;

                if ($logId) {
                    if ($completedRecords % $updateLimit == 0 || $completedRecords == $totalRecords) {
                        $cdrApiExportLog->save(array(
                            'id' => $logId,
                            'completed_records' => $completedRecords
                        ));
                    }
                }
            }
            if ($logId) {
                if ($updateLimit > $totalRecords) {
                    $cdrApiExportLog->save(array(
                        'id' => $logId,
                        'completed_records' => $totalRecords
                    ));
                }
            }


            if (count($this->data) > 1) {
                $countRecords = count($this->data);

                foreach ($totalRow as $key => $item) {
                    if (isset($this->keysTemplate[$key]['isAverageTotal']) && $this->keysTemplate[$key]['isAverageTotal']) {
                        $totalRow[$key] /= $countRecords;
                    }
                }

                $iterator = 0;

                foreach ($totalRow as $key => &$item) {
                    if (strcmp($item, 'Total') == 0 && $iterator > 0) {
                        $item = '';
                    }
                    if (isset($this->keysTemplate[$key]['precision'])) {
                        $item = round($item, $this->keysTemplate[$key]['precision']);
                    }
                    if (isset($this->keysTemplate[$key]['postfix'])) {
                        $item .= $this->keysTemplate[$key]['postfix'];
                    }
                    $iterator++;
                }
                array_push($result, $totalRow);
            }
            $result = array(
                'code' => 200,
                'msg' => '',
                'data' => $result
            );
        }

        return $json ? json_encode($result) : $result;
    }

    /**
     * Init keys
     */
    private function initKeysTemplate()
    {
        $this->keysTemplate = array(
            'asr_global' => array(
                'apiFields' => array('calls', 'non_zero_calls'),
                'precision' => 2,
                'postfix' => '%',
                'isAverageTotal' => true
            ),
            'asr' => array(
                'apiFields' => array('ingress_calls', 'non_zero_calls'),
                'precision' => 2,
                'postfix' => '%',
                'isAverageTotal' => true
            ),
            'asr_term' => array(
                'apiFields' => array('egress_calls', 'non_zero_calls'),
                'precision' => 2,
                'postfix' => '%',
                'isAverageTotal' => true
            ),
            'acd' => array(
                'apiFields' => array('ingress_time', 'non_zero_calls'),
                'precision' => 2,
                'isAverageTotal' => true
            ),
            'acd_term' => array(
                'apiFields' => array('egress_time', 'non_zero_calls'),
                'precision' => 2,
                'isAverageTotal' => true
            ),
            'pdd_global' => array(
                'apiFields' => array('pdd', 'calls'),
                'precision' => 2
            ),
            'pdd' => array(
                'apiFields' => array('pdd', 'ingress_calls'),
                'precision' => 2
            ),
            'pdd_term' => array(
                'apiFields' => array('pdd', 'egress_calls'),
                'precision' => 2
            ),
            'npr_count' => array(
                'apiFields' => array('npr_calls')
            ),
            'npr_global' => array(
                'apiFields' => array('calls', 'npr_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'npr' => array(
                'apiFields' => array('ingress_calls', 'npr_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'npr_term' => array(
                'apiFields' => array('egress_calls', 'npr_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'sd_count' => array(
                'apiFields' => array('non_zero_calls_6')
            ),
            'sdp' => array(
                'apiFields' => array('non_zero_calls_6', 'non_zero_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'revenue' => array(
                'apiFields' => array('ingress_cost'),
                'precision' => 2
            ),
            'revenue_term' => array(
                'apiFields' => array('egress_cost'),
                'precision' => 2
            ),
            'profit' => array(
                'apiFields' => array('ingress_cost', 'egress_cost'),
                'precision' => 2
            ),
            'profit_percent' => array(
                'apiFields' => array('ingress_cost', 'egress_cost'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'margin' => array(
                'apiFields' => array('ingress_cost', 'egress_cost'),
                'precision' => 2
            ),
            'pp_min' => array(
                'apiFields' => array('ingress_cost', 'egress_cost', 'ingress_time'),
                'precision' => 6
            ),
            'pp_k_calls' => array(
                'apiFields' => array('ingress_cost', 'egress_cost', 'non_zero_calls'),
                'precision' => 6
            ),
            'ppka' => array(
                'apiFields' => array('ingress_cost', 'egress_cost', 'ingress_calls'),
                'precision' => 6
            ),
            'total_duration' => array(
                'apiFields' => array('ingress_time'),
                'precision' => 2
            ),
            'total_duration_term' => array(
                'apiFields' => array('egress_time'),
                'precision' => 2
            ),
            'total_billable_time' => array(
                'apiFields' => array('ingress_billed_time'),
                'precision' => 2
            ),
            'total_billable_time_term' => array(
                'apiFields' => array('egress_billed_time'),
                'precision' => 2
            ),
            'total_cost' => array(
                'apiFields' => array('ingress_cost'),
                'precision' => 5
            ),
            'total_cost_term' => array(
                'apiFields' => array('egress_cost'),
                'precision' => 5
            ),
            'inter_cost' => array(
                'apiFields' => array('ingress_call_cost_inter'),
                'precision' => 5
            ),
            'inter_cost_term' => array(
                'apiFields' => array('egress_call_cost_inter'),
                'precision' => 5
            ),
            'intra_cost' => array(
                'apiFields' => array('ingress_call_cost_intra'),
                'precision' => 5
            ),
            'intra_cost_term' => array(
                'apiFields' => array('egress_call_cost_intra'),
                'precision' => 5
            ),
            'local_cost' => array(
                'apiFields' => array('ingress_call_cost_local'),
                'precision' => 5
            ),
            'local_cost_term' => array(
                'apiFields' => array('egress_call_cost_local'),
                'precision' => 5
            ),
            'ij_cost' => array(
                'apiFields' => array('ingress_call_cost_ij'),
                'precision' => 5
            ),
            'ij_cost_term' => array(
                'apiFields' => array('egress_call_cost_ij'),
                'precision' => 5
            ),
            'average_rate' => array(
                'apiFields' => array('ingress_cost', 'ingress_billed_time'),
                'precision' => 5
            ),
            'average_rate_term' => array(
                'apiFields' => array('egress_cost', 'egress_billed_time'),
                'precision' => 5
            ),
            'total_calls' => array(
                'apiFields' => array('ingress_calls')
            ),
            'calls' => array(
                'apiFields' => array('calls')
            ),
            'total_calls_term' => array(
                'apiFields' => array('egress_calls')
            ),
            'not_zero_calls' => array(
                'apiFields' => array('non_zero_calls')
            ),
            'not_zero_calls_6' => array(
                'apiFields' => array('non_zero_calls_6')
            ),
            'not_zero_calls_6_percent' => array(
                'apiFields' => array('non_zero_calls', 'non_zero_calls_6'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'not_zero_calls_30' => array(
                'apiFields' => array('non_zero_calls_30')
            ),
            'not_zero_calls_30_percent' => array(
                'apiFields' => array('non_zero_calls', 'non_zero_calls_30'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'success_calls' => array(
                'apiFields' => array('ingress_success_calls')
            ),
            'busy_calls' => array(
                'apiFields' => array('ingress_busy_calls')
            ),
            'busy_calls_term' => array(
                'apiFields' => array('egress_busy_calls')
            ),
            'total_duration_percent' => array(
                'apiFields' => array('ingress_time'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'total_duration_percent_term' => array(
                'apiFields' => array('egress_time'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'total_calls_percent' => array(
                'apiFields' => array('ingress_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'total_calls_percent_term' => array(
                'apiFields' => array('egress_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'margin_percent' => array(
                'apiFields' => array('egress_cost', 'ingress_cost'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'cancel_calls' => array(
                'apiFields' => array('ingress_cancel_calls')
            ),
            'egress_limit' => array(
                'apiFields' => array('egress_limit')
            ),
            'did_price' => array(
                'apiFields' => array()
            ),
            'min_price' => array(
                'apiFields' => array()
            ),
            'monthly_charge' => array(
                'apiFields' => array()
            ),
            'failed_calls' => array(
                'apiFields' => array('non_zero_calls', 'calls')
            ),
            'nrf_count' => array(
                'apiFields' => array('nrf_calls')
            ),
            'nrf' => array(
                'apiFields' => array('nrf_calls', 'ingress_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'nrf_term' => array(
                'apiFields' => array('nrf_calls', 'egress_calls'),
                'precision' => 2,
                'postfix' => '%'
            ),
            'limited' => array(
                'apiFields' => array('ingress_cap_limit', 'ingress_cps_limit')
            )
        );
    }

    /**
     * Parsing fields from request
     * @param string $fields
     * @return string
     */
    private function parseFields($fields)
    {
        $result = array();

        foreach ($fields as $item) {
            $item = trim($item);
            $apiFields = $this->keysTemplate[$item]['apiFields'];

            foreach ($apiFields as $apiField) {
                $apiField = trim($apiField);

                if (!in_array($apiField, $result)) {
                    $result[$apiField] = 'total';
                }
            }
        }

        return $result;
    }


    private function getHumanValue($key, $item)
    {
        $formulas = array(
            'asr_global' => $item['calls'] == 0 ? 0 : $item['non_zero_calls'] / $item['calls'] * 100,
            'asr' => $item['ingress_calls'] == 0 ? 0 : $item['non_zero_calls'] / $item['ingress_calls'] * 100,
            'asr_term' => $item['egress_calls'] == 0 ? 0 : $item['non_zero_calls'] / $item['egress_calls'] * 100,
            'acd' => $item['non_zero_calls'] == 0 ? 0 : $item['ingress_time'] / $item['non_zero_calls'] / 60,
            'acd_term' => $item['non_zero_calls'] == 0 ? 0 : $item['egress_time'] / $item['non_zero_calls'] / 60,
            'pdd_global' => $item['calls'] == 0 ? 0 : $item['pdd'] / $item['calls'],
            'pdd' => $item['ingress_calls'] == 0 ? 0 : $item['pdd'] / $item['ingress_calls'],
            'pdd_term' => $item['egress_calls'] == 0 ? 0 : $item['pdd'] / $item['egress_calls'],
            'npr_count' => $item['npr_calls'],
            'npr_global' => $item['calls'] == 0 ? 0 : $item['npr_calls'] / $item['calls'] * 100,
            'npr' => $item['ingress_calls'] == 0 ? 0 : $item['npr_calls'] / $item['ingress_calls'] * 100,
            'npr_term' => $item['egress_calls'] == 0 ? 0 : $item['npr_calls'] / $item['egress_calls'] * 100,
            'sd_count' => $item['non_zero_calls_6'],
            'sdp' => $item['non_zero_calls'] == 0 ? 0 : $item['non_zero_calls_6'] / $item['non_zero_calls'] * 100,
            'revenue' => $item['ingress_cost'],
            'revenue_term' => $item['egress_cost'],
            'profit' => abs($item['egress_cost'] - $item['ingress_cost']),
            'profit_percent' => abs($item['egress_cost'] - $item['ingress_cost']) / $item['egress_cost'] * 100,
            'margin' => $item['ingress_cost'] == 0 ? 0 : abs($item['egress_cost'] - $item['ingress_cost']) / $item['ingress_cost'],
            'margin_percent' => $item['ingress_cost'] == 0 ? 0 : abs($item['egress_cost'] - $item['ingress_cost']) / $item['ingress_cost'] * 100,
            'pp_min' => $item['ingress_time'] == 0 ? 0 : abs($item['egress_cost'] - $item['ingress_cost']) / $item['ingress_time'],
            'pp_k_calls' => $item['non_zero_calls'] == 0 ? 0 : abs($item['egress_cost'] - $item['ingress_cost']) / $item['non_zero_calls'] * 1000,
            'ppka' => $item['ingress_calls'] == 0 ? 0 : abs($item['egress_cost'] - $item['ingress_cost']) / $item['ingress_calls'] * 1000,
            'total_duration' => $item['ingress_time'] / 60,
            'total_duration_term' => $item['egress_time'] / 60,
            'total_billable_time' => $item['ingress_billed_time'] / 60,
            'total_billable_time_term' => $item['egress_billed_time'] / 60,
            'total_cost' => $item['ingress_cost'],
            'total_cost_term' => $item['egress_cost'],
            'inter_cost' => $item['ingress_call_cost_inter'],
            'inter_cost_term' => $item['egress_call_cost_inter'],
            'intra_cost' => $item['ingress_call_cost_intra'],
            'intra_cost_term' => $item['egress_call_cost_intra'],
            'local_cost' => $item['ingress_call_cost_local'],
            'local_cost_term' => $item['egress_call_cost_local'],
            'ij_cost' => $item['ingress_call_cost_ij'],
            'ij_cost_term' => $item['egress_call_cost_ij'],
            'average_rate' => $item['ingress_billed_time'] == 0 ? 0 : $item['ingress_cost'] / ($item['ingress_billed_time'] / 60),
            'average_rate_term' => $item['egress_billed_time'] == 0 ? 0 : $item['egress_cost'] / ($item['egress_billed_time'] / 60),
            'calls' => $item['calls'],
            'total_calls' => $item['ingress_calls'],
            'total_calls_term' => $item['egress_calls'],
            'not_zero_calls' => $item['non_zero_calls'],
            'not_zero_calls_6' => $item['non_zero_calls_6'],
            'not_zero_calls_6_percent' => $item['non_zero_calls'] == 0 ? 0 : $item['non_zero_calls_6'] / $item['non_zero_calls'] * 100,
            'not_zero_calls_30' => $item['non_zero_calls_30'],
            'not_zero_calls_30_percent' => $item['non_zero_calls'] == 0 ? 0 : $item['non_zero_calls_30'] / $item['non_zero_calls'] * 100,
            'success_calls' => $item['ingress_success_calls'],
            'busy_calls' => $item['ingress_busy_calls'],
            'busy_calls_term' => $item['egress_busy_calls'],
            'cancel_calls' => $item['ingress_cancel_calls'],
            'egress_limit' => $item['egress_limit'],
            'failed_calls' => $item['calls'] - $item['non_zero_calls'],
            'time' => date('Y-m-d H:i:s', $item['time']),
            'nrf_count' => $item['nrf_calls'],
            'nrf' => $item['ingress_calls'] == 0 ? 0 : $item['nrf_calls'] / $item['ingress_calls'] * 100,
            'nrf_term' => $item['egress_calls'] == 0 ? 0 : $item['nrf_calls'] / $item['egress_calls'] * 100,
            'limited' => $item['ingress_cap_limit'] + $item['ingress_cps_limit'],
            'total_duration_percent' => function() use ($item) {
                $result = 0;
                $data = $this->getTotalValue('ingress_time');

                if (!empty($data)) {
                    $result = $data[0]['ingress_time'] == 0 ? 0 : $item['ingress_time'] / $data[0]['ingress_time'] * 100;
                }
                return $result;
            },
            'total_duration_percent_term' => function() use ($item) {
                $result = 0;
                $data = $this->getTotalValue('egress_time');

                if (!empty($data)) {
                    $result = $data[0]['egress_time'] == 0 ? 0 : $item['egress_time'] / $data[0]['egress_time'] * 100;
                }
                return $result;
            },
            'total_calls_percent' => function() use ($item) {
                $result = 0;
                $data = $this->getTotalValue('ingress_calls');

                if (!empty($data)) {
                    $result = $data[0]['ingress_calls'] == 0 ? 0 : $item['ingress_calls'] / $data[0]['ingress_calls'] * 100;
                }
                return $result;
            },
            'total_calls_percent_term' => function() use ($item) {
                $result = 0;
                $data = $this->getTotalValue('egress_calls');

                if (!empty($data)) {
                    $result = $data[0]['egress_calls'] == 0 ? 0 : $item['egress_calls'] / $data[0]['egress_calls'] * 100;
                }
                return $result;
            }
        );

        return $formulas[$key] ? : null;
    }

    private function getTotalValue($field)
    {
        $result = false;

        if ($this->request) {
            $request = array(
                'start_time' => $this->request['start_time'],
                'end_time' => $this->request['end_time'],
                'step' => $this->request['step'],
                'fields' => array($field => 'total')
            );
            $result = $this->GlobalReports->process($request);
        }

        return $result;
    }
}