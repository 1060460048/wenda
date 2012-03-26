<?php
/**
 * 转换类
 * 
 * @copyright  Copyright (c) 2011 Ricky Feng (http://code.google.com/p/rphp4zf)
 * @license    New BSD License
*/
class RFLib_Utility_Parse
{
    /**
     * 将字符串属性转换为数组形式
     * 
     * @param string $string
     * @return array
     */
    public static function attribToArray($string)
    {
        $options = array();

        $exp = explode(' ', $string);
        foreach ($exp as $key => $value) {
            $arr = explode('=', $value);
            $options[$arr[0]] = $arr[1];
        }

        return $options;
    }
    
    /**
     * 将url转换为ZendFramework可以识别的参数
     * 
     * @param string $url
     * @return array
     */
    public static function urlToParam($url)
    {
        $query      = '';
        $route      = 'default';
        $params     = array();
        $options    = array();

        if (!$url) {
            $url = '/';
        }

        if ($pos = strpos($url, '?')) {
            $query = substr($url, $pos + 1);
            $url = substr($url, 0, $pos);
        }

        if ($url[0] == '@') {
            $route = substr($url, 1);
        } else if (false !== strpos($url, '/')) {
            list($arrayMain['controller'], $arrayMain['action']) = explode('/', $url);
        }

        if ($query) {
            parse_str($query, $options);
        }

        if (isset($arrayMain)) {
            $params = array_merge($arrayMain, $options);
        } else {
            $params = $options;
        }

        return array($route, $params);
    }
}
?>
