<?php

class SearchfileHelper extends AppHelper {
    
    /*
     *  $path       查找目录
     *  $pattern    模式匹配
     */
    function search($path, $pattern) {
        $return = array();
        $files = scandir($path);
        if(is_array($files) && count($files) > 2 && !empty($pattern)) {
            $pattern = "*{$pattern}*";
            array_shift($files);
            array_shift($files);
            foreach($files as $file) {
                if(fnmatch($pattern, $file)) {
                    array_push($return, $file);
                } 
            }
        }
        return $return;
    }
    
}

?>
