<?php
/***************************************************************************************************
 *
 * File Description:
 *  本文件用于进行数据库的连接,创建一个PDO对象,进行Mysql数据库操作.
 * Created by   Michael Lee            lipeng@microwu.com           2/7/2017, 5:29:23 PM
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 *
 * Updated History:
 * Author       Date            Content
/**************************************************************************************************/
require_once dirname(__FILE__) . "/global_variables.php";
try {
    $pdo = new PDO($GLOBALS['mysql_dns'], $GLOBALS['mysql_name'], $GLOBALS['mysql_pass']);
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}