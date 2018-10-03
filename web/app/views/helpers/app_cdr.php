<?php

#App::import("Model","ImportExportLog");
App::import("Model", "Cdr");

class AppCdrHelper extends AppHelper
{

    var $cdr_data = array();

//$mydata[$i][0]	
    function render_rerate_data($data, $rerate_type)
    {



        #主叫计费
        if ($rerate_type == 'orig_rerate')
        {


            $arr = explode(';', $data['orig_cost']);
            $this->cdr_data['id'] = isset($arr[0]) ? $arr[0] : '';
            $this->cdr_data['ani'] = isset($arr[1]) ? $arr[1] : '';
            $this->cdr_data['dnis'] = isset($arr[2]) ? $arr[2] : '';
            $this->cdr_data['begin_time'] = isset($arr[3]) ? $arr[3] : '';
            $this->cdr_data['end_time'] = isset($arr[4]) ? $arr[4] : '';
            $this->cdr_data['duration'] = isset($arr[5]) ? $arr[5] : '';

            $this->cdr_data['old_orig_rate'] = isset($arr[6]) ? $arr[6] : '';
            $this->cdr_data['old_orig_rate_cost'] = isset($arr[7]) ? $arr[7] : '';
            $this->cdr_data ['old_term_rate'] = isset($arr [8]) ? $arr [8] : '';
            $this->cdr_data ['old_term_rate_cost'] = isset($arr [9]) ? $arr [9] : '';

            $this->cdr_data ['new_orig_rate'] = isset($arr [10]) ? $arr [10] : '';
            $this->cdr_data ['new_orig_rate_cost'] = isset($arr [11]) ? $arr [11] : '';
            $this->cdr_data ['new_term_rate'] = isset($arr [12]) ? $arr [12] : '';
            $this->cdr_data ['new_term_rate_cost'] = isset($arr [13]) ? $arr [13] : '';
            $this->cdr_data ['rerate_time'] = isset($arr [14]) ? $arr [14] : '';
        }



        #被叫计费
        if ($rerate_type == 'term_rerate')
        {
            $arr = explode(';', $data['term_cost']);
            $this->cdr_data['id'] = isset($arr[0]) ? $arr[0] : '';
            $this->cdr_data['ani'] = isset($arr[1]) ? $arr[1] : '';
            $this->cdr_data['dnis'] = isset($arr[2]) ? $arr[2] : '';
            $this->cdr_data['begin_time'] = isset($arr[3]) ? $arr[3] : '';
            $this->cdr_data['end_time'] = isset($arr[4]) ? $arr[4] : '';
            $this->cdr_data['duration'] = isset($arr[5]) ? $arr[5] : '';

            $this->cdr_data['old_orig_rate'] = isset($arr[6]) ? $arr[6] : '';
            $this->cdr_data['old_orig_rate_cost'] = isset($arr[7]) ? $arr[7] : '';
            $this->cdr_data ['old_term_rate'] = isset($arr [8]) ? $arr [8] : '';
            $this->cdr_data ['old_term_rate_cost'] = isset($arr [9]) ? $arr [9] : '';

            $this->cdr_data ['new_orig_rate'] = isset($arr [10]) ? $arr [10] : '';
            $this->cdr_data ['new_orig_rate_cost'] = isset($arr [11]) ? $arr [11] : '';
            $this->cdr_data ['new_term_rate'] = isset($arr [12]) ? $arr [12] : '';
            $this->cdr_data ['new_term_rate_cost'] = isset($arr [13]) ? $arr [13] : '';
            $this->cdr_data ['rerate_time'] = isset($arr [14]) ? $arr [14] : '';
        }




        #主被叫 都计费
        if ($rerate_type == 'orig_term_rerate')
        {
            $arr = explode(';', $data['orig_cost']);

            $term_arr = explode(';', $data['term_cost']);
            $this->cdr_data['id'] = isset($arr[0]) ? $arr[0] : '';
            $this->cdr_data['ani'] = isset($arr[1]) ? $arr[1] : '';
            $this->cdr_data['dnis'] = isset($arr[2]) ? $arr[2] : '';
            $this->cdr_data['begin_time'] = isset($arr[3]) ? $arr[3] : '';
            $this->cdr_data['end_time'] = isset($arr[4]) ? $arr[4] : '';
            $this->cdr_data['duration'] = isset($arr[5]) ? $arr[5] : '';

            $this->cdr_data['old_orig_rate'] = isset($arr[6]) ? $arr[6] : '';
            $this->cdr_data['old_orig_rate_cost'] = isset($arr[7]) ? $arr[7] : '';

            $this->cdr_data ['old_term_rate'] = isset($term_arr [8]) ? $term_arr [8] : '';
            $this->cdr_data ['old_term_rate_cost'] = isset($term_arr [9]) ? $term_arr [9] : '';

            $this->cdr_data ['new_orig_rate'] = isset($arr [10]) ? $arr [10] : '';
            $this->cdr_data ['new_orig_rate_cost'] = isset($arr [11]) ? $arr [11] : '';


            $this->cdr_data ['new_term_rate'] = isset($term_arr [12]) ? $term_arr [12] : '';
            $this->cdr_data ['new_term_rate_cost'] = isset($term_arr [13]) ? $term_arr [13] : '';
            $this->cdr_data ['rerate_time'] = isset($arr [14]) ? $arr [14] : '';
        }
    }

    function get_rerate_data($id)
    {
        if (isset($this->cdr_data[$id]))
        {
            return $this->cdr_data[$id];
        }
        else
        {
            return '';
        }
    }

    function format_cdr_field($f)
    {
        $model = new Cdr ();
        $arr = $model->find_field();
        if (isset($arr[$f]))
        {
            return $arr[$f];
        }
        else
        {
            return $f;
        }
    }



}

?>