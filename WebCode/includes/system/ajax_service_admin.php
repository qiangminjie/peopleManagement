<?php
/***************************************************************************************************
 * File Description:
 *  本文件用于系统管理员添加业务管理员时 JavaScript调用不同的php方法.
 *  页面中用POST方式传入要调用函数的名称
 *  type:
 *    0         添加业务管理员
 *    1         修改业务管理员信息
 *    2         修改业务管理员账号状态
 * Updated History:
 * Author       Date            Content
/**************************************************************************************************/
require_once '../dbconn.php';
require_once 'sql_service_admin.php';

if (!empty($_POST)) {
    // 检验cookie
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION["username"])) {
        echo json_encode(array("response" => -1, "info" => "session已过期，请重新登录"));
        return;
    }
    switch ($_POST["type"]) {
        case 0:
            if ($_POST["service_id"] != null && $_POST["account"] != null && $_POST["sub_permission"] != null &&
                 $_POST["pass"] != null && $_POST["name"] != null && $_POST["tag"] != null) {
                $resp_code = createServiceAdmin($pdo, $_POST["service_id"], $_POST["account"], $_POST["sub_permission"],
                        $_POST["pass"], $_POST['name'], $_POST['mobile'], $_POST['memo'], $_POST['tag']);
                $resp_info = "";
                switch ($resp_code) {
                    case 0:
                        $resp_info = "新建业务管理员账号成功";
                        break;
                    case -1:
                        $resp_info = "新建业务管理员账号失败，请重试！如果此问题重复出现，请联系系统管理员";
                        break;
                    default:
                        break;
                }
                echo json_encode(array("response" => $resp_code, "info" => $resp_info));
            } else {
                echo "-1, empty params.";
            }
            break;
        case 1:
            if ($_POST["service_id"] != null && $_POST["account"] != null && $_POST["sub_permission"] != null &&
                 $_POST["pass"] != null && $_POST["name"] != null && $_POST["user_id"] != null && $_POST["tag"] != null) {
                $resp_code = updateServiceAdmin($pdo, $_POST["user_id"], $_POST["service_id"], $_POST["account"],
                        $_POST["sub_permission"], $_POST["pass"], $_POST['name'], $_POST['mobile'], $_POST['memo'], $_POST['tag']);
                $resp_info = "";
                switch ($resp_code) {
                    case 0:
                        $resp_info = "更新业务管理员账号信息成功";
                        break;
                    case -1:
                        $resp_info = "更新业务管理员账号信息失败，请重试！如果此问题重复出现，请联系系统管理员";
                        break;
                    default:
                        break;
                }
                echo json_encode(array("response" => $resp_code, "info" => $resp_info));
            } else {
                echo "-1, empty params.";
            }
            break;
        case 2:
            if ($_POST['id'] != null && $_POST['status'] != null) {
                $resp_code = updateAccountStatus($pdo, $_POST['id'], $_POST['status']);
                $resp_info = "";
                switch ($resp_code) {
                    case 0:
                        $resp_info = "修改管理员账号状态成功";
                        break;
                    case -1:
                        $resp_info = "修改管理员账号状态失败，请重试！如果此问题重复出现，请联系系统管理员";
                        break;
                    default:
                        break;
                }
                echo json_encode(array("response" => $resp_code, "info" => $resp_info));
            } else {
                echo "-1, empty params.";
            }
            break;
        default:
            break;
    }
}
