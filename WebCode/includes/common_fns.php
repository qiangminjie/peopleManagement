<?php
/***************************************************************************************************
 * File Description:
 *  通用的函数模块
 * Method List:
 *  redirect                                重定向方法
 *  local_number_format
/**************************************************************************************************/

/**
 * Method Description:
 *  页面跳转,不会产生303错误.
 *  详细信息见: http://stackoverflow.com/a/768472/3822574
 * @param   $url      String    重定向地址
 * @return  null
 */
function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    die();
}

/**
 * Method Description
 *  数字处理成字符串，可设置整数位数和小数位数
 * @param   float   $number
 * @param   int     $integer_len
 * @param   int     $decimal_len
 * @param   string  $dec_point
 * @param   string  $thousands_sep
 * @return  bool|string
 */
function local_number_format($number, $integer_len = 0, $decimal_len = 0) {
    $str = number_format($number, $decimal_len, ".", "");

    if ($number < 0) {
        $str = substr($str, 1, strlen($str) - 1);
    }
    $index = strrpos($str, ".") === false ? strlen($str) : strrpos($str, ".");
    if ($index < $integer_len) {
        for ($i = 0; $i < $integer_len - $index; $i++) {
            $str = "0".$str;
        }
    }
    if ($number < 0) {
        $str = "-" . $str ;
    }
    return $str;
}

/**
 * Method Description:
 *   判断两组ip地址是否有重合的部分
 * @return  String     写入到session中'form_token'字段的token值
 */
function setFormToken() {
    // 检测是否有session
    if (!isset($_SESSION)) {
        session_start();
    }
    // 写入session字段
    $_SESSION['form_token'] = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
    return $_SESSION['form_token'];
}

/**
 * 将空字符串转化为null
 * @param $str
 * @return null
 */
function cvtnull($str) {
    return $str === "" ? null : $str;
}

function local_time_format($str, $rex) {
    $res = '';
    if ($rex === 'Ymd') {
        $datetime = mktime(0, 0, 0, (int)substr($str, 4, 2), (int)substr($str, 6, 2), (int)substr($str, 0, 4));
        $res = date('Y-m-d', $datetime);
    }
    return $res;
}