<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:27
 */
require_once dirname(__FILE__) . "/sql_employ_add.php";

$resp_code = -2;
$resp_info = "code不存在，请求非法";
switch ($_POST['code']) {
    case "1":
        $title = $_POST['title'];
        $type = $_POST['type'];
        $desc = $_POST['desc'];
        $count = $_POST['count'];
        $status = $_POST['status'];
        $res = addEmployAdd($title, $type, $desc, $count, $status);
        switch ($res) {
            case -1:
                $resp_code = -1;
                $resp_info = "服务器内部错误";
                break;
            case 1:
                $resp_code = 1;
                $resp_info = "参数违法";
                break;
            default:
                $resp_code = 0;
                $resp_info = "添加成功";
                break;
        }
        break;
    default:
        break;
}

echo json_encode(array("resp_code" => $resp_code, "resp_info" => $resp_info));

