<?php

App::import("Model", "ImportExportLog");

class AppImportExportLogHelper extends AppHelper
{

    function format_object($obj, $name)
    {
        if ($obj == 'Productitem')
        {
            return "Static Route [ $name ]";
        }
        if ($obj == 'Code')
        {
            return "Code Deck [ $name ]";
        }

        if ($obj == 'RateTable')
        {
            return "Rate Table [ $name ]";
        }
        if ($obj == 'RateTable')
        {
            return "Rate Table [ $name ]";
        }
        if ($obj == 'DigitTranslation')
        {
            return "Digit Translation [ $name ]";
        }
//        if ($obj == 'JurisdictionUpload')
//        {
//            return "Jurisdiction Upload [ $name ]";
//        }
        if ($obj == 'Route')
        {
            return "Route [ $name ]";
        } else
        {
            return $obj;
        }
    }

    function display_status($value, $file1, $file2)
    {
        /*
        if ($value == 2)
        {
            if (!empty($file1) || !empty($file2))
            {

                return '2/2 completed with error';
            } else
            {

                return '2/2 Done';
            }
        }
        */
        $status = array(
            -5 => 'Database Error',
            -4 => 'CSV Head Error',
            -3 => 'File Open Error',
            -2 => 'Upload Error',
            0 => 'Uploading',
            1 => 'Processing',
            2 => 'Done',
            6 => 'Success',
            8 => 'Processing',
            11 => 'killed'
        );
        
        return $status[$value];
        /*
        switch ($value)
        {
            case 0:return '1/3 Upload files completed';
            case 1:return '2/3 validation data format in progress';
            case 2:return '3/3 Done';
            case -2:return 'upload file open error';
            case -3:return 'upload error file open error';
            case -4:return 'csv head error';
            case -5:return 'database error';
            default : return "Unknown";
        }
         * 
         */
        
    }

    public function display_export_status($value)
    {
        if ($value != 6)
            return 'Progressing!';
        else
            return 'Succeed!';
    }

    function display_status__per($value)
    {
        switch ($value)
        {
            case 1 : return '50%';
            case 2 : return '100%';
            case -2 : return '100%';
            case -3 : return '100%';
            case -4 : return '100%';
            case -5 : return '100%';
            default : return "0%";
        }
    }

    function display_status_html($value)
    {
        $s = '';
        switch ($value)
        {
            case 6 : $s = "<span  style='color: red'>  Database validation completed </span>";
                break;
            case 0 : $s = "<span  style='color: red'>  Upload files to the server... </span>";
                break;
            case 7 : $s = "<span  style='color: red'>  Database validation in progress... </span>";
                break;
            case 8 : $s = "<span  style='color: red'>  File have been verified complete and successfully into the temporary table... </span>";
                break;
            case 9 : $s = "<span  style='color: red'>  processing into temporary table... </span>";
                break;
            case -1 : $s = "<span  style='color: red'>  processing into temporary table... </span>";
                break;
            default : $s = "<span  style='color: red'>  Foreign key does not exist... </span>";
        }

        return <<<EOD
					$s
EOD;
    }

#

    public function display_upload_tip()
    {
        //Configure::write('debug',0);
        $model = new ImportExportLog ();
        return $model->find_all_process_log();
    }

    public function diplay_alert_tip(){
        $sql = "select count(0) as cnt from admin_alert WHERE is_view = 0";
        $model = new ImportExportLog ();
        $cnt = $model->query($sql);
        return intval($cnt[0][0]['cnt']);
    }

}

?>