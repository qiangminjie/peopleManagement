<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:27
 */
require_once dirname(__FILE__) . "/sql_employ_list.php";

$resp_code = -2;
$resp_info = "code不存在，请求非法";
switch ($_POST['code']) {
    case "1":
        $id = $_POST['id'];
        $type = $_POST['type'];
        $status = $_POST['status'];
        $row = getEmployList($id, $type, $status);
        switch ($row) {
            case -1:
                $resp_code = -1;
                $resp_info = "服务器内部错误";
                break;
            case 1:
                $resp_code = 1;
                $resp_info = "未查询到相关记录";
                break;
            default:
                $resp_code = 0;
                $resp_info = $row;
                break;
        }
        break;
    case "2":
        $id = $_POST['id'];
        $status = $_POST['status'];
        $res = updateEmployStatus($id, $status);
        switch ($row) {
            case -1:
                $resp_code = -1;
                $resp_info = "服务器内部错误";
                break;
            case 1:
                $resp_code = 1;
                $resp_info = "更新失败";
                break;
            default:
                $resp_code = 0;
                $resp_info = "修改状态成功";
                break;
        }
        break;
    default:
        break;
}

echo json_encode(array("resp_code" => $resp_code, "resp_info" => $resp_info));
