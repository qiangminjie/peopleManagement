<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:40
 */

require_once dirname(__FILE__) . "/../dbconn.php";

function addEmployAdd($title, $type, $desc, $count, $status) {
    //测试修改文件添加commit
    $sql = "insert into employ_add values(null,:title, :type, :desc, :count, :status)";
    $db = $GLOBALS['pdo'];
    //增加注释测试commit退回
    try {
        $db->beginTransaction();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":desc", $desc);
        $stmt->bindParam(":count", $count);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        $res = $db->commit();
        if ($res) {
            return 0;
        }
        return 1;
        //在增加一个
    } catch (PDOException $e) {
        $e->errorInfo();
        $db->rollback();
        return -1;
    }
}