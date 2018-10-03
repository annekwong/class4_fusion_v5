<?php
class Download extends AppModel
{
    var $useTable = false;

    public function csv($path)
    {
        Configure::write('debug', 0);
        $filename = basename($path);
        ob_clean();
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header("Content-Length: " . filesize($path));
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($path);

        exit();
    }

    public function xlsFromCsv($path)
    {
        Configure::write('debug', 0);
        App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel/Classes/PHPExcel.php'));
        App::import('Vendor', 'PHPExcel_IOFactory', array('file' => 'PHPExcel/Classes/PHPExcel/IOFactory.php'));

        $csvFile = $path;
        $pathInfo = pathinfo($path);
        $xlsFile = $pathInfo['dirname'] . DS . $pathInfo['filename'] . '.xls';

        if (!class_exists('PHPExcel')) {
            return false;
        }

        $objReader = PHPExcel_IOFactory::createReader('CSV');
        $objReader->setDelimiter(",");
        $objPHPExcel = $objReader->load($csvFile);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($xlsFile);

        ob_clean();
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Length: " . filesize($xlsFile));
        header("Content-Disposition: attachment;filename=" . $pathInfo['filename'] . '.xls');
        readfile($xlsFile);
        exit();

    }
}