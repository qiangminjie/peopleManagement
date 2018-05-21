<?php
/***************************************************************************************************
 * File Description:
 *  本文件用于不同用户通用的JavaScript调用不同的php方法.
 *  页面中用POST方式传入要调用函数的名称
 *  type:
 *    0         验证用户名和密码是否正确
 *    1         修改个人信息
 * Updated History:
 * Author       Date            Content
 * Michale Lee  11/28/2017      type = 1时验证token信息合法， 防范CSRF攻击
/**************************************************************************************************/
require_once 'sql_util.php';
require_once 'dbconn.php';

if (!empty($_POST)) {
    // 如果不是登录,则需要检查cookie
    if ($_POST["type"] != 0) {
        if (!isset($_SESSION)) {
          session_start();
        }
        if (!isset($_SESSION["username"])) {
          echo json_encode(array("response" => -2, "info" => "session已过期，请重新登录。"));
          return;
        }
    }
    // type参数校验

    switch ($_POST["type"]) {
    case 0:
        // 参数校验

        // check Username and password
        // 返回JSON对象, response
        if ($_POST["username"] != null && $_POST["password"] != null && $_POST["time"] != null) {
            $resp_code = checkUserPass($_POST["username"], $_POST["password"], $_POST["time"], $_POST["code"], $pdo);
            $resp_info = "";
            $resp_valid = false;
            switch ($resp_code) {
              case 1:
                $resp_info = "您的系统时钟错误!请重新设置后再次登录";
                break;
              case 2:
                $resp_info = "您输入的手机号码不存在";
                break;
              case 3:
                $resp_info = "您输入的密码错误";
                break;
              case 4:
                $resp_info = "验证码输入错误或者验证码已经过期";
                break;
              case 5:
                $resp_info = "该账号被管理员禁用，如有疑问请联系管理员";
                break;
              case 6:
                $resp_info = "当前账号所属公司已经处于关闭状态，如有疑问请联系系统管理员";
                break;
              default:
                $resp_info = "服务器内部错误";
                break;
          }
          if (!isset($_SESSION)) {
              session_start();
          }
          if ($_SESSION["error_count"] > 2 && $resp_code > 0) {
              $resp_valid = true;
          }
          echo json_encode(array("response" => $resp_code, "resp_valid" => $resp_valid, "info" => $resp_info));
        } else {
            echo "-1, empty params.";
        }
        break;
    case 1:
      if ($_POST["token"] != $_SESSION['form_token']) {
          echo json_encode(array("response" => -1, "info" => "token输入不合法"));
          break;
      }
      // 本次token 1次有效
      $_SESSION['form_token'] = "";
      // 修改个人信息
      if ($_POST["modify_type"] == "0") {
          // 仅修改用户名
          $resp_code = updateAccountName($_SESSION["user_id"], $_POST["account"], $_POST["mobile"], $_POST["name"], $pdo);
      } else {
          // 还包括账号密码
          if ($_POST["origin_pass"] != null && $_POST["new_pass"] != null) {
              $resp_code = updateAccountPassAndName($_SESSION["user_id"], $_POST["mobile"], $_POST["name"], $_POST["account"],
                  $_POST["origin_pass"], $_POST["new_pass"], $pdo);
          } else {
              echo json_encode(array("response" => -1, "info" => "缺少有效参数输入"));
              break;
          }
      }
      $resp_info = "";
      switch ($resp_code) {
        case 1:
          $resp_info = "您输入的手机号已经被注册，请更换后重试";
          break;
        case 2:
          $resp_info = "原始密码错误";
          break;
        case 3:
          $resp_code = "服务器内部错误!";
          break;
        default:
          break;
      }
      echo json_encode(array("response" => $resp_code, "info" => $resp_info));
      break;
    default:
      echo "-1";
      break;
    }
}
