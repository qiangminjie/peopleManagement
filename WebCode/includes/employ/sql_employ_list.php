<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:40
 */

require_once dirname(__FILE__) . "/../dbconn.php";

function getEmployList($id, $type, $status) {
    $sql = "select * from employ_add where 1=1";
    if ($id !== "" && $id !== null) {
        $sql .= " and id = :id";
    }
    if ($type !== "" && $type !== null) {
        $sql .= " and type = :type";
    }
    if ($status !== "" && $status !== null) {
        $sql .= " and status = :status";
    }
    try {
        $db = $GLOBALS['pdo'];
        $stmt = $db->prepare($sql);
        if ($id !== "" && $id !== null) {
            $stmt->bindParam(":id", $id);
        }
        if ($type !== "" && $type !== null) {
            $stmt->bindParam(":type", $type);
        }
        if ($status !== "" && $status !== null) {
            $stmt->bindParam(":status", $status);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            return $rows;
        }
        return 1;
    } catch (PDOException $e) {
        $e->errorInfo();
        return -1;
    }
}

function updateEmployStatus($id, $status) {
    $sql = "update employ_add set status = :status where id = :id";
    $db = $GLOBALS['pdo'];
    try {
        $db->beginTransaction();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        $res = $db->commit();
        if ($res) {
            return 0;
        } else {
            $db->rollback();
            return 1;
        };
    } catch (PDOException $e){
        $e->getMessage();
        $db->rollback();
        retun -1;
    }
}