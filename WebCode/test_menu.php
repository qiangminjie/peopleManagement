<?php
/***************************************************************************************************
 * File Description:
 *
 * Created by   jieqiangmin            jieqiangmin@microwu.com           15:11/2018/2/11
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 * Method List:
 *  0 =
 *  1 =
 *  2 =
 * Update History:
 * Author       Time            Contennt
 ***************************************************************************************************/

require_once "menu_test.php";

$data[0]['text'] = '会员信息';
$data[0]['icon'] = 'glyph-icon icon-home';
$data[0]['nodes'][0]['text'] = '新增会员';
$data[0]['nodes'][0]['icon'] = 'glyph-icon icon-reorder';
$data[0]['nodes'][1]['text'] = '修改会员';
$data[0]['nodes'][1]['icon'] = 'glyph-icon icon-reorder';

$data[1]['text'] = '销售图表';
$data[1]['icon'] = 'glyph-icon icon-home';
 echo json_encode($data);
