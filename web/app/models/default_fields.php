<?php

class DefaultFields extends AppModel
{
    var $useTable = "default_fields";
    var $primaryKey = "report_name";

    public function getFields($reportName)
    {
        $item = $this->find('first', array(
            'conditions' => array(
                'report_name' => $reportName
            )
        ));

        $result = '';

        if ($item && isset($item['DefaultFields']['fields'])) {
            $result = $item['DefaultFields']['fields'];
        }

        return $result;
    }
}