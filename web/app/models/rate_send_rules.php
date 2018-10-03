<?php
class RateSendRules extends AppModel{

    var $name = 'RateSendRules';
    var $useTable = 'rate_send_rules';
    var $primaryKey = 'id';

    public function effective_date_from(){
        return [
            0 => 'None',
            1 => 'Subject',
            2 => 'Content'
        ];
    }

    public function start_from(){
        return array_merge([''=> ''],range(0, 50));
    }

    public function position(){
        return range(0, 20);
    }

    public function position_opt(){
        return array_merge([''=> ''], range(0, 20));
    }

    public function file_format(){
        return [
            0 => 'XLS',
            1 => 'XLSX',
            2 => 'CSV',
            3 => 'ZIP'
        ];
    }

    public function date_pattern(){
        return [
            '%Y/%b/%d',
            '%Y-%b-%d',
            '%Y/%m/%d',
            '%Y-%m-%d',
            '%d/%m/%Y'
        ];
    }

    public function violation_action(){
        return [
            'Reject entire rate deck',
            'Accept entire rate deck and block trunk for',
            'Reject the violated codes only'
        ];
    }

    public function multiple_sheet(){
        return [
            true => 'Yes',
            false => 'No'
        ];
    }


    public function tab_index(){
        return [
            0 => 'Stardard',
            1 => 'Special',
            2 => 'Premium',
        ];
    }

    public function filter_by(){
        return [
            0 => 'No filter',
            1 => 'By sheet',
            2 => 'By column'
        ];
    }

}
