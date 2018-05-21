<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8
 * Time: 14:44
 */

$badLog  = fopen("/yii/badLog.log","a+")or die("Unable to open file!");
fwrite($badLog,"1234"."\n");
fclose($badLog);

echo json_encode(["code" => 1,"msg" => "test"]);