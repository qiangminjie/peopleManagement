<?php
/***************************************************************************************************
 * File Description:
 *  We can include this file in all our files.
 *  This way, every file will contain all our functions and exceptions.
 * Created by   Michael Lee            lipeng@microwu.com           10/13/16 14:55
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 *
 * Updated History:
 * Author       Date            Content
/**************************************************************************************************/

include_once dirname(__FILE__) . '/output_fns.php';
include_once dirname(__FILE__) . '/sql_util.php';
include_once dirname(__FILE__) . '/global_variables.php';
include_once dirname(__FILE__) . '/common_fns.php';

if(!isset($_SESSION)) {
  session_start();
}
