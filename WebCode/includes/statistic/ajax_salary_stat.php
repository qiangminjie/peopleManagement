<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:27
 */
require_once dirname(__FILE__) . "/sql_salary_stat.php";

$resp_code = -2;
$resp_desc = "code不存在，请求非法";
switch ($_POST['code']) {
    case "1":
        $row = getSalaryStat();
        switch ($row) {
            case -1:
                $resp_code = -1;
                $resp_desc = "服务器内部错误";
                break;
            case 1:
                $resp_code = 1;
                $resp_desc = "未查询到相关数据";
                break;
            default:
                $resp_code = 0;
                $resp_desc = array();
                foreach ($row as $r) {
                    array_push($resp_desc, array("name" => $r['salary'], "value" => $r['number']));
                }
                break;
        }
        break;
    case "2":
        $row = getSalaryStat2();
        switch ($row) {
            case -1:
                $resp_code = -1;
                $resp_desc = "服务器内部错误";
                break;
            case 1:
                $resp_code = 1;
                $resp_desc = "未查询到相关数据";
                break;
            default:
                $resp_code = 0;
                $types = array();
                $numbers = array();
                foreach ($row as $r) {
                    array_push($types, $r['type']);
                    array_push($numbers, $r['number']);
                }
                $resp_desc = array("types"=>$types, "numbers"=>$numbers);
                break;
        }
        break;
    default:
        break;
}

echo json_encode(array("resp_code" => $resp_code, "resp_desc" => $resp_desc));

