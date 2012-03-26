<?php
class Wenda_View_Helper_Timing extends Zend_View_Helper_Abstract
{
    public function timing($time)
    {
        $date = new Zend_Date();

        $diffTime = $date->getTimestamp() - $time;
        $date = null;

        if ($diffTime < 60) {
            return ($diffTime <= 0  ? 1 : $diffTime) . '秒前';
        }

        $minute = ceil($diffTime/60) ;
        if ($minute < 60) {
            return $minute . '分钟前';
        }

        $minute = ceil($diffTime/3600) ;
        if ($minute < 24) {
            return $minute . '小时前';
        }

        $day = ceil($diffTime/3600/24) ;
        if ($day < 30) {
            return $day .'天前';
        }

        $month = ceil($diffTime/3600/24/30) ;
        if ($month < 12) {
            return $month . '月前';
        }

        $year = floor($diffTime/3600/24/30/12) ;
        return $year . '年前';
    }
}