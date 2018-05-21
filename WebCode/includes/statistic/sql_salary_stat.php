<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:40
 */

require_once dirname(__FILE__) . "/../dbconn.php";

function getSalaryStat() {
    $sql = "select * from salary_stat";
    $db = $GLOBALS['pdo'];
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($row) {
            return $row;
        }
        return 1;
    } catch (PDOException $e) {
        $e->errorInfo();
        return -1;
    }
}

function getSalaryStat2() {
    $sql = "select max(b.type_name) type, count(a.id) number from staff a right join type b on a.type = b.type_id group by b.type_id order by 2 desc";
    $db = $GLOBALS['pdo'];
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($row) {
            return $row;
        }
        return 1;
    } catch (PDOException $e) {
        $e->errorInfo();
        return -1;
    }
}