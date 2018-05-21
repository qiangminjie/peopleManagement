<?php
/**
 * ***********************************************
 * File Description:
 *
 *      sql for staff_list.php
 *
 * Method List
 *
 *      getStaffList        获得员工列表
 *
 * ***********************************************
 */
require_once dirname(__FILE__) . "/../dbconn.php";

function addInterviewer($number, $name, $sex, $education, $school, $major, $birthday, $age, $type, $subtype, $level, $status, $in_time) {
    $sql = "INSERT INTO interviewer(number, name, sex, education, school, major, birthday, age, type, subtype, level, status, in_time) 
            VALUES (:number, :name, :sex, :education, :school, :major, :birthday, :age, :type, :subtype, :level, :status, :in_time)";
    try{
        $db = $GLOBALS['pdo'];
        $db->beginTransaction();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':number', $number);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':sex', $sex);
        $stmt->bindParam(':education', $education);
        $stmt->bindParam(':school', $school);
        $stmt->bindParam(':major', $major);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':subtype', $subtype);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':in_time', $in_time);
        $stmt->execute();

        if ($db->commit()) {
            return 0;
        } else {
            return 1;
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
        return -1;
    }
}


function getStaffById($id, $condition='only') {
    try {
        $db = $GLOBALS['pdo'];
        $sql = '';
        switch ($condition) {
            case 'only':
                $sql = "SELECT * FROM interviewer WHERE id = :id";
                break;
            case 'all':
                $sql = 'SELECT a.*, x.type_name, x.subtype_name, x.level_name FROM interviewer a, 
                         (SELECT c.type_id, c.subtype_id, ifnull(d.level_id, 0) level_id, c.type_name, c.subtype_name, ifnull(d.level_name, \'--\') level_name
                            FROM
                              (SELECT a.type_id, a.type_name, b.subtype_id, b.subtype_name FROM type a LEFT JOIN subtype b ON a.type_id = b.type_id) c
                              LEFT JOIN level d ON c.type_id = d.type_id AND c.subtype_id = d.subtype_id
                            ORDER BY c.type_id, c.subtype_id, d.level_id) x
                        WHERE a.id = :id and a.type = x.type_id and a.subtype = x.subtype_id and a.level = x.level_id';
                break;
            default:
                break;
        }
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== null && $row !== array()) {
            return $row;
        }
        return 1;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return -1;
    }
}

function getintervewerList($number, $name, $type, $subtype, $level, $isoffer, $page_flag = false, $page = 1, $pageSize = 10) {

    $sql = "SELECT * FROM interviewer WHERE isoffer =  ".$isoffer;

    if ($number !== null && $number !== "") {
        $sql .= " and number = :number";
    }
    if ($name !== null && $name !== "") {
        $sql .= " and name = :name";
    }
    if ($type !== null && $type !== "") {
        $sql .= " and type = :type";
    }
    if ($subtype !== null && $subtype !== "") {
        $sql .= " and subtype = :subtype";
    }
    if ($number !== null && $level !== "") {
        $sql .= " and level = :level";
    }
    $sql .= " order by id";
    if ($page_flag) {
        $start_index = ($page - 1) * $pageSize;
        $sql .= " limit :start_index, :pageSize";
    }
    try{
        $db = $GLOBALS['pdo'];

        $stmt = $db->prepare($sql);

        if ($number !== null && $number !== "") {
            $stmt->bindParam(":number", $number);
        }
        if ($name !== null && $name !== "") {
            $stmt->bindParam(":name", $name);
        }
        if ($type !== null && $type !== "") {
            $stmt->bindParam(":type", $type);
        }
        if ($subtype !== null && $subtype !== "") {
            $stmt->bindParam(":subtype", $subtype);
        }
        if ($number !== null && $level !== "") {
            $stmt->bindParam(":level", $level);
        }
        if ($page_flag) {
            $stmt->bindParam(":start_index", $start_index);
            $stmt->bindParam(":pageSize", $pageSize);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0) {
            return 0;
        }

        return $rows;

    } catch (PDOException $e) {
        echo $e->getMessage();
        return -1;
    }
}




function getintervewerOfferList($number, $name, $type, $subtype, $level, $isoffer, $page_flag = false, $page = 1, $pageSize = 10) {

    $sql = "SELECT * FROM interviewer WHERE isoffer IN (1,2,3,4)";

    if ($number !== null && $number !== "") {
        $sql .= " and number = :number";
    }
    if ($name !== null && $name !== "") {
        $sql .= " and name = :name";
    }
    if ($type !== null && $type !== "") {
        $sql .= " and type = :type";
    }
    if ($subtype !== null && $subtype !== "") {
        $sql .= " and subtype = :subtype";
    }
    if ($number !== null && $level !== "") {
        $sql .= " and level = :level";
    }
    $sql .= " order by id";
    if ($page_flag) {
        $start_index = ($page - 1) * $pageSize;
        $sql .= " limit :start_index, :pageSize";
    }
    try{
        $db = $GLOBALS['pdo'];

        $stmt = $db->prepare($sql);

        if ($number !== null && $number !== "") {
            $stmt->bindParam(":number", $number);
        }
        if ($name !== null && $name !== "") {
            $stmt->bindParam(":name", $name);
        }
        if ($type !== null && $type !== "") {
            $stmt->bindParam(":type", $type);
        }
        if ($subtype !== null && $subtype !== "") {
            $stmt->bindParam(":subtype", $subtype);
        }
        if ($number !== null && $level !== "") {
            $stmt->bindParam(":level", $level);
        }
        if ($page_flag) {
            $stmt->bindParam(":start_index", $start_index);
            $stmt->bindParam(":pageSize", $pageSize);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0) {
            return 0;
        }

        return $rows;

    } catch (PDOException $e) {
        echo $e->getMessage();
        return -1;
    }
}


function getTypeSubtypeLevel() {
    $sql = "SELECT c.type_id, c.subtype_id, d.level_id, c.type_name, c.subtype_name, d.level_name 
            FROM
            (SELECT a.type_id, a.type_name, b.subtype_id, b.subtype_name FROM type a LEFT JOIN subtype b ON a.type_id = b.type_id) c
            LEFT JOIN level d ON c.type_id = d.type_id AND c.subtype_id = d.subtype_id
            ORDER BY c.type_id, c.subtype_id, d.level_id";

    try {
        $db = $GLOBALS['pdo'];
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0) {
            return 1;
        }
        return $rows;

    } catch (PDOException $e) {
        echo $e->getMessage();
        return -1;
    }
}


function updateOffer($id ,$isoffer){
    $sql = "update interviewer set isoffer =".$isoffer." where id =  ".$id;
    $db = $GLOBALS['pdo'];
    $db->beginTransaction();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($db->commit()) {
        return 0;
    } else {
        return 1;
    }

}