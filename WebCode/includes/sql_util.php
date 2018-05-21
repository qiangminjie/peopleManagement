<?php


/**
 * Method Description:
 *  返回当前的Client ip地址
 * Created by   Michael Lee            lipeng@microwu.com           11/1/16 15:31
 * @param null
 * @return  string
 */
function getClientIP() {
    $ip_address = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ip_address = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip_address = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip_address = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip_address = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip_address = getenv('HTTP_FORWARDED');
    } elseif (getenv('REMOTE_ADDR')) {
        $ip_address = getenv('REMOTE_ADDR');
    } else {
        $ip_address = 'UNKNOWN';
    }
    return $ip_address;
}


function checkUserPass($user, $pass, $time, $code, $pdo) {
    // 响应代码
    $response_code = -1;
    // memo
    $memo = "";

    // 是否需要检测验证码
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION["error_count"]) && $_SESSION["error_count"] > 2) {
        if (!isset($_SESSION['code']) || $_SESSION['code_valid'] == 0 || $code != $_SESSION["code"]) {
            $_SESSION["error_count"] += 1;

            return 4;
        }
    } else if (!isset($_SESSION['error_count'])) {
        $_SESSION["error_count"] = 0;
    }

    // 检验时间戳是否过期
    date_default_timezone_set("Asia/Shanghai");
    $atime = date_create_from_format("YmdHis", $time);
    $atimestamp = date_timestamp_get($atime);

    if (abs($atimestamp - time()) >= 10 * 60) {
        $response_code = 1;
        $memo = $time;
    } else {
        // 检查是否存在用户名
        try {
            $sql = "select a.id, a.pass, a.name, permission, tag, a.staff_id, account, a.status,  sub_permission, a.mobile
                    from oes_user as a where account = :username and permission >= 0 ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":username", $user);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_NUM);
            if ($row) {
                // 获取数据库中密码
                $pass_str = $row[1] . $time;
                if (md5($pass_str) === $pass) {
                        // 成功登录
                        $response_code = 0;
                        // session中写入各个字段
                        $_SESSION["user_id"] = $row[0];
                        $_SESSION["staff_id"] = $row[5];
                        $_SESSION["username"] = $row[2];
                        $_SESSION["permission"] = $row[3];
                        $_SESSION["sub_permission"] = $row[8];
                        $_SESSION["account"] = $row[6];
                        $_SESSION["error_count"] = 0;
                        $_SESSION["tag"] = $row[4];
                        $_SESSION["mobile"] = $row[9];
                } else {
                    // 密码错误
                    $response_code = 3;
                    if (!isset($_SESSION["error_count"])) {
                        $_SESSION["error_count"] = 0;
                    }
                    $_SESSION["error_count"] += 1;
                    $memo = $pass;
                }
            } else {
                // 用户不存在
                $response_code = 2;
                if (!isset($_SESSION["error_count"])) {
                    $_SESSION["error_count"] = 0;
                }
                $_SESSION["error_count"] += 1;
                $memo = $user . " + " . $pass;
            }
        } catch (PDOException $e) {
            return $response_code . " : " . $e->getMessage();
        }
    }
    // record this login action
    $_SESSION['code_valid'] = 0;
    return $response_code;
}

/**
 * Method Description:
 *  向数据库中输入登录行为
 * @param   $user_id    int       当前用户id
 * @param   $account    String    输入的用户名
 * @param   $type       int       事件类型, 0 = 登录事件; 1 = 模板操作事件; 2 = 用户操作事件
 * @param   $event      int       事件结果
 * @param   $ip         String    登录的ip地址
 * @param   $memo       String    如果登录失败,则把失败的密码记录下来,以便后续分析; 如果修改模板,则记录模板id 和模板状态
 * @param   $pdo        object    sql连接pdo对象
 * @return  null
 */
function recordActions($id, $account, $type, $event, $memo, $pdo) {
    try {
        // Begin a transaction, turning off autocommit
        $pdo->beginTransaction();

        $sql = "insert into oes_log(user_id, user_account, event, result, memo, ip_address) "
                . "values (:id, :account, :t_type, :event_type, :memo, :ip_addr)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":account", $account);
        $stmt->bindParam(":t_type", $type);
        $stmt->bindParam(":event_type", $event);
        $ip = getClientIP();
        $stmt->bindParam(":ip_addr", $ip);
        $stmt->bindParam(":memo", $memo);
        $stmt->execute();
        // Commit the changes
        $pdo->commit();
        // Database connection is now back in autocommit mode
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function updateAccountName($id, $account, $mobile, $name, $pdo) {
    try {
        // Begin a transaction, turning off autocommit
        $pdo->beginTransaction();
        $sql = "update oes_user set name = :name, account = :account, mobile = :mobile where id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":mobile", $mobile);
        $stmt->bindParam(":account", $account);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        // Commit the changes
        if ($pdo->commit()) {
            $_SESSION["username"] = $name;
            $_SESSION["account"] = $account;
            $_SESSION["mobile"] = $mobile;
            // 记录日志
        } else {
            $pdo->rollback();
            return -1;
        }
        // Database connection is now back in autocommit mode
        return 0;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            return 1;
        } else {
            return 3;
        }
    }
}

/**
 * Method Description:
 *  用户修改账号名称和密码
 * @param   $id           int       用户id
 * @param   $mobile       String    要修改的手机号
 * @param   $name         String    要修改的名字
 * @param   $account      String    要修改的账号名称
 * @param   $origin_pass  String    原始密码
 * @param   $new_pass     String    新密码
 * @param   $pdo          object    sql连接pdo对象
 * @return  int     校验码
 *                  0 = 修改成功 ; 1 = 手机号重复错误; 2 = 密码错误； 3 = 其他
 */
function updateAccountPassAndName($id, $mobile, $name, $account, $origin_pass, $new_pass, $pdo) {
    try {
        // 验证原始密码是否正确
        $sql = "select pass from oes_user where id = :id ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row && $origin_pass == $row[0]) {
            // Begin a transaction, turning off autocommit
            $pdo->beginTransaction();
            // 如果是系统管理员，不修改tag字段
            if ($_SESSION['permission'] == 0) {
                $sql = "update oes_user set pass = :pass , account = :account, mobile = :mobile, name = :name where id = :id;";
            } else {
                $sql = "update oes_user set pass = :pass , account = :account, mobile = :mobile, name = :name, tag = 1 where id = :id;";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":pass", $new_pass);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":mobile", $mobile);
            $stmt->bindParam(":account", $account);
            $stmt->execute();
            // Commit the changes
            if ($pdo->commit()) {
                $_SESSION["username"] = $name;
                $_SESSION["mobile"] = $mobile;
                $_SESSION["account"] = $account;
                if (isset($_SESSION["permission"]) && $_SESSION["permission"] != 0) {
                    if (isset($_SESSION["tag"])) {
                        $_SESSION["tag"] = 1;
                    }
                }
                // 记录日志
                // Database connection is now back in autocommit mode
                return 0;
            } else {
                $pdo->rollback();
                return 3;
            }
        } else {
            return 2;
        }
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            return 1;
        } else {
            return 3;
        }
    }
}
