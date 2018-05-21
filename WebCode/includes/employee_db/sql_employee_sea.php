<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:40
 */

require_once dirname(__FILE__) . "/../dbconn.php";

function getDocs($fileName) {
    $sql = "select * from doc where 1=1";
    if ($fileName !== "" && $fileName !== null) {
        $sql .= " and fileName like '%" . $fileName . "%'";
    }
    try {
        $db = $GLOBALS['pdo'];
        $stmt = $db->prepare($sql);
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

function storeDoc($fileName, $path) {
    $sql = "insert into doc(fileName, path, date) values (:fileName, :path, sysdate())";
    $db = $GLOBALS['pdo'];
    try {
        $db->beginTransaction();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":fileName", $fileName);
        $stmt->bindParam(":path", $path);
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