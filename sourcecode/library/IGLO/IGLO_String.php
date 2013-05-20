<?php
class IGLO_String {
    public static function getFileExtension($strFileName) {
        $path_info = pathinfo($strFileName);
        return $path_info['extension'];
    }

    public static function getTimeSubtracted($strDate1, $strDate2) {
        $intTime   = strtotime($strDate1) - strtotime($strDate2);
        $intHour   = floor($intTime / 3600);
        $intMinute = floor(($intTime - ($intHour * 3600)) / 60);
        $intSecond = $intTime - (($intHour * 3600) + ($intMinute * 60));
        return sprintf("%02s", $intHour) . ":" . sprintf("%02s", $intMinute) . ":" . sprintf("%02s", $intSecond);
    }
}
?>