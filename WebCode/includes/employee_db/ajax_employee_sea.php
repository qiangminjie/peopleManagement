<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 2018/5/1
 * Time: 下午5:27
 */
require_once dirname(__FILE__) . "/sql_employee_sea.php";
require_once '../common_utils/file_upload.class.php';

$resp_code = -2;
$resp_info = "code不存在，请求非法";
switch ($_REQUEST['code']) {
    case "1":
        $fileName = $_POST['fileName'];
        $res = getDocs($fileName);
        if ($res === 1) {
            $resp_code = 1;
            $resp_info = "无查询记录";
        } else if ($res === "-1") {
            $resp_code = -1;
            $resp_info = "系统内部错误，请联系管理员";
        } else {
            $resp_info = $res;
            $resp_code = 0;
        }
        break;
    case "2":
        $file = $_FILES['file'];
        $fileName = getFileName($file);
        $path = receiveFile($file, "/file", $fileName);

        if ($res !== '') {
            $resp_code = storeDoc($fileName, $path);
            if ($resp_code === 0) {
                $resp_info = "文件上传成功";
            }
        } else {
            $resp_info = '文件接收失败';
        }
        break;
    default:
        break;
}

echo json_encode(array("resp_code" => $resp_code, "resp_info" => $resp_info));
