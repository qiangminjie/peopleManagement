<?php
/***************************************************************************************************
 * File Description:
 * 用户列表页面中和数据库的交互
 * Method List:
 *
 * Update History:
 * Author       Time            Contennt
 ***************************************************************************************************/

if (!empty($_POST)) {
    //检查cookie
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION["username"])) {
        echo json_encode(array("response" => -1, "info" => "session已过期，请重新登录"));
        return;
    }
    // type参数校验
    if (!checkParamValid($_POST["type"], 1, 1, 0)) {
        echo json_encode(array("response" => -1, "info" => "参数输入不合法"));
        return;
    }
}


/**
 * @param $id
 * @param $name
 * @param $permission
 * @param $order
 * @return int
 */
function getUserList($pdo, $id, $name, $permission, $order, $offset, $length){
    $id = validSQLStr($id);
    $name = validSQLStr($name);
    if ($pdo != null) {
        try {
            $sql = "select
                      a.id          as id,
                      group_concat(c.service_id) as service_id,
                      b.name        as company_name,
                      a.permission  as permission,
                      sub_permission as sub_permission,
                      a.tag         as tag,
                      a.account     as account,
                      a.mobile      as mobile,
                      a.name        as name,
                      a.status      as status,
                      a.memo        as memo,
                      a.create_time as create_time
                    from oes_user a
                      inner join oes_company b on a.company_id = b.id
                      left join oes_user_service_relation c on a.id = c.user_id
                    where  a.permission <> 0 and a.status >=0";
            if ($_SESSION['permission'] == 1) {
                $sql = $sql . " and b.id in (select company_id from oes_price where bill_rate <> -1
                     and service_id in (select service_id from oes_user_service_relation where user_id = " .
                    $_SESSION['user_id'] . "))";
            }
            if ($id != "") {
                $sql = $sql . " and a.id = " . $id;
            }
            if ($name != "") {
                $sql = $sql . " and a.name like '%" . $name . "%'";
            }
            if ($permission != "" && $permission != -1) {
                $sql = $sql . " and a.permission = " . $permission;
            }
            $sql = $sql . " group by a.id";
            if ($order == 0) {
                $sql = $sql . " order by a.create_time desc, a.status";
            } else {
                $sql = $sql . " order by a.status desc, a.create_time desc";
            }
            if ($offset == 0 && $length ==0) {
                //获取当前所有用户
                $stmt = $pdo->prepare($sql);
            } else {
                // 获取指定条数的用户
                $sql = $sql . " limit :offset , :length";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":offset", $offset ,PDO::PARAM_INT);
                $stmt->bindParam(":length", $length ,PDO::PARAM_INT);
            }
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($row != null) {
                return $row;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return -1;
        }
    } else {
        echo "pdo is null";
        return -1;
    }
}

?>