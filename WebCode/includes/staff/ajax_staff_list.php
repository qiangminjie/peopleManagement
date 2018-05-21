<?php
/**
 * File Description:
 *  Ajax for staff_list.php
 * Method List:
 *  code = 0        获取员工列表
 *  code = 1        获取岗位职位级别信息
 *  code = 2        新增员工
 *  code = 3        修改员工信息
 *  code = 4        删除员工信息
 *  code = 5        查询员工信息
 */
require_once dirname(__FILE__) . "/../all_fns.php";
require_once dirname(__FILE__) . "/sql_staff_list.php";

//if (!isset($_SESSION['user_id'])) {
//    $resp_code = 2;
//    $resp_info = "未登录或登录已过期，请重新登录";
//} else {
    switch ($_POST["code"]) {
        case 0:
            // 查询员工列表
            $number = $_POST["number"];
            $name = $_POST["name"];
            $type = $_POST["type"];
            $subtype = $_POST["subtype"];
            $level = $_POST["level"];

            $list = getStaffList($number, $name, $type, $subtype, $level);

            switch ($list) {
                case 1:
                    $resp_code = 1;
                    $resp_info = "未查询到相关记录";
                    break;
                case -1:
                    $resp_code = -1;
                    $resp_info = "服务器内部错误，如果此错误重复出现，请联系网站管理员";
                    break;
                default:
                    $resp_code = 0;
                    $resp_info = $list;
                    break;
            }
        break;
        case 1:
            //查询工种类型、子类型、等级
            $list = getTypeSubtypeLevel();

            switch ($list) {
                case 1 :
                    $resp_code = 1;
                    $resp_info = "未查询到相关记录";
                    break;
                default:
                    $types = array();
                    $subtypes = array();
                    $levels = array();

                    foreach ($list as $row) {
                        $type_id = $row['type_id'];
                        $type_name = $row['type_name'];
                        $subtype_id = $row['subtype_id'];
                        $subtype_name = $row['subtype_name'];
                        $level_id = $row['level_id'];
                        $level_name = $row['level_name'];

                        $type = array(
                            "type_id" => $type_id,
                            "type_name" => $type_name
                        );
                        if (!in_array($type, $types)) {
                            array_push($types,$type);
                        }

                        if ($subtype_id !== null) {
                            $subtype = array(
                                "type_id" => $type_id,
                                "subtype_id" => $subtype_id,
                                "subtype_name" => $subtype_name
                            );
                            if (!in_array($subtype, $subtypes)) {
                                array_push($subtypes, $subtype);
                            }

                            if ($level_id !== null) {
                                $level = array(
                                    "type_id" => $type_id,
                                    "subtype_id" => $subtype_id,
                                    "level_id" => $level_id,
                                    "level_name" => $level_name
                                );
                                if (!in_array($level,$levels)) {
                                    array_push($levels,$level);
                                }
                            }
                        }
                    }

                    $resp_code = 0;
                    $resp_info = array(
                        "types" => $types,
                        "subtypes" => $subtypes,
                        "levels" => $levels
                    );
                    break;
            };
            break;
        case 2:
            $name = cvtnull($_POST['name']);
            $sex = cvtnull($_POST['sex']);
            $education = cvtnull($_POST['education']);
            $school = cvtnull($_POST['school']);
            $major = cvtnull($_POST['major']);
            $birthday = cvtnull($_POST['birthday']);
            $type = cvtnull($_POST['type']);
            $subtype = cvtnull($_POST['subtype']);
            $level = cvtnull($_POST['level']);
            $status = cvtnull($_POST['status']);
            $in_time = cvtnull($_POST['in_time']);
            $out_time = cvtnull($_POST['out_time']);
            $age = cvtnull(getAgeByBirthday($birthday));
            $number = createNumber($type, $subtype, $level);
            if ($age < 0) {
                $resp_code = 2;
            } else {
                $resp_code = addStaff($number, $name, $sex, $education, $school, $major, $birthday, $age, cvtnullto0($type), cvtnullto0($subtype), cvtnullto0($level), $status, $in_time, $out_time);
                switch ($resp_code) {
                    case 0:
                        $resp_info = "操作成功！";
                        break;
                    case -1:
                        $resp_info = "服务器内部错误，如果此错误重复出现，请联系网站管理员";
                        break;
                    case 1:
                        $resp_info = "数据库提交失败";
                        break;
                    case 2:
                        $resp_info = "生日输入不合法，请核对后重试";
                        break;
                }
            }

            break;
        case 3:
            $id = cvtnull($_POST['id']);
            $name = cvtnull($_POST['name']);
            $sex = cvtnull($_POST['sex']);
            $education = cvtnull($_POST['education']);
            $school = cvtnull($_POST['school']);
            $major = cvtnull($_POST['major']);
            $birthday = cvtnull($_POST['birthday']);
            $type = cvtnull($_POST['type']);
            $subtype = cvtnull($_POST['subtype']);
            $level = cvtnull($_POST['level']);
            $status = cvtnull($_POST['status']);
            $in_time = cvtnull($_POST['in_time']);
            $out_time = cvtnull($_POST['out_time']);
            $age = cvtnull(getAgeByBirthday($birthday));
            if ($age < 0) {
                $resp_code = 2;
            } else {
                $resp_code = updateStaff($id, $name, $sex, $education, $school, $major, $birthday, $age, cvtnullto0($type), cvtnullto0($subtype), cvtnullto0($level), $status, $in_time, $out_time);
                switch ($resp_code) {
                    case 0:
                        $resp_info = "修改成功！";
                        break;
                    case -1:
                        $resp_info = "服务器内部错误，如果此错误重复出现，请联系网站管理员";
                        break;
                    case 1:
                        $resp_info = "数据库提交失败";
                        break;
                    case 2:
                        $resp_info = "生日输入不合法，请核对后重试";
                        break;
                }
            }

            break;
        case 4:
            // 删除员工
            $id = $_POST['id'];
            if ($id !== null && $id !== '') {
                $resp_code = deleteStaffById($id);
            } else {
                $resp_code = 2;
            }
            switch ($resp_code) {
                case 0:
                    $resp_info = "删除成功";
                    break;
                case -1:
                    $resp_info = "服务器内部错误，如果此错误重复出现，请联系网站管理员";
                    break;
                case 1:
                    $resp_info = "数据库提交失败";
                    break;
                case 2:
                    $resp_info = "参数错误，id非法";
                    break;
            }
            break;
        case 5:
            //根据id查询员工
            $id = $_POST['id'];
            if ($id !== null && $id !== '') {
                $result = getStaffById($id);
                switch ($result) {
                    case -1:
                        $resp_code = -1;
                        $resp_info = "服务器内部错误，如果此错误重复出现，请联系网站管理员";
                        break;
                    case 1:
                        $resp_code = 1;
                        $resp_info = "未查到该用户";
                    default:
                        $resp_code = 0;
                        $resp_info = $result;
                }
            } else {
                $resp_code = 2;
                $resp_info = "参数错误，id非法";
            }
            break;
        default:
            $resp_code = -2;
            $resp_info = "code不存在，请求非法";
            break;
    }
//}

$response = json_encode(array("resp_code" => $resp_code, "resp_info" => $resp_info));
echo $response;

function createNumber($type, $subtype, $level) {
    if ($type !== null) {
        $type_str = local_number_format($type, 2);
    } else {
        $type_str = '00';
    };
    if ($subtype !== null) {
        $subtype_str = local_number_format($subtype, 2);
    } else {
        $subtype_str = '00';
    };
    if ($level !== null) {
        $level_str = local_number_format($level, 2);
    } else {
        $level_str = '00';
    }
    $arr = getStaffList(null, null, $type, $subtype, $level);
    $num = $arr === 0 ? 0 : count($arr);

    $number_str = local_number_format($num + 1, 2);
    return $type_str . $subtype_str . $level_str . $number_str;
}

function getAgeByBirthday($birthday) {
    $today_year = intval(date('Y'));
    $today_month_day = intval(date('md'));
    $birthday_year = intval(substr($birthday, 0, 4));
    $birthday_month_day = intval(substr($birthday, 4, 4));

    $age = $today_month_day >= $birthday_month_day ? $today_year - $birthday_year : $today_year - $birthday_year - 1;

    return $age;
}

function cvtnullto0($a) {
    return $a === null ? 0 : $a;
 }
