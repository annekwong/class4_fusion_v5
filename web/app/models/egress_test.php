<?php

class EgressTest extends AppModel {

    var $name = 'EgressTest';
    var $useTable = "egress_test";
    var $primaryKey = "id";
    var $hasMany = Array(
        'EgressTestResult',
    );


    public function get_result_pdf_content($id)
    {
        $conditions = array('EgressTest.id' => $id);
        $egress_test_info = $this->find('first',array(
            'fields' => array(
                'EgressTest.start_time','EgressTest.end_time','EgressTest.code_name','EgressTest.success_calls',
                'EgressTest.total_calls','EgressTest.create_by','Resource.alias','EgressTest.egress_id','EgressTest.id'
            ),
            'joins' => array(
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'inner',
                    'conditions' => array(
                        'Resource.resource_id = EgressTest.egress_id'
                    ),
                ),
            ),
            'conditions' => $conditions
        ));

        $stylesheet = <<<EOT
<style type="text/css">
html, body, table {font-size:10px;}
</style>
EOT;

        $logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

        if (file_exists($logo_path))
            $logo = APP . 'webroot' . DS . 'upload/images/logo.png';
        else
            $logo = APP . 'webroot' . DS . 'images/logo.png';

        $logo = "<img src='$logo' />";

        $test_info = <<<TESTINFO
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
	<tr style="background-color:#ddd;text-align:left;">
		<th colspan='4'>Test ID :{$egress_test_info['EgressTest']['id']}</th>
	</tr>
<tr>
    <td style='text-align:right;padding-right:10px;'>Create By:</td>
    <td>{$egress_test_info['EgressTest']['create_by']}</td>
    <td style='text-align:right;padding-right:10px;'>Initiated:</td>
    <td>{$egress_test_info['EgressTest']['total_calls']} Calls</td>
</tr>
<tr>
    <td style='text-align:right;padding-right:10px;'>Create On:</td>
    <td>{$egress_test_info['EgressTest']['start_time']}</td>
    <td style='text-align:right;padding-right:10px;'>Answered</td>
    <td>{$egress_test_info['EgressTest']['success_calls']} Calls</td>
</tr>
<tr>
    <td style='text-align:right;padding-right:10px;'>Trunk:</td>
    <td>{$egress_test_info['Resource']['alias']}</td>
    <td></td>
    <td></td>
</tr>
</table>
TESTINFO;


        $table_html = '';
        if ($egress_test_info)
        {
            $table_tbody = '';
            foreach ($egress_test_info['EgressTestResult'] as $egress_test)
            {
                $table_tbody .= <<<TBODY
<tr><td>{$egress_test['ani']}</td><td>{$egress_test['dnis']}</td><td>{$egress_test['pdd']}</td>
<td>{$egress_test['start_time']}</td><td>{$egress_test['answer_time']}</td><td>{$egress_test['duration']}</td>
<td>{$egress_test['call_result']}</td></tr>
TBODY;
            }

            $table_html = <<<TABLE
<span style="font-size:12px;font-weight:bold">Number detail</span><br />
<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
<tr style="background-color:#ddd;text-align:center;">
<th>Call No</th><th>Called No</th><th>PDD</th><th>Start Time</th><th>Answer Time</th><th>Duration</th><th>Call Result</th>
</tr>
$table_tbody
</table>
TABLE;
        }
        $return = <<<RETRUN
$stylesheet
<div>$logo <b>Check route result</b></div>
<div style="height:10px;"></div>
$test_info
<div style="height:10px;"></div>
$table_html
RETRUN;

        return $return;
    }
}
