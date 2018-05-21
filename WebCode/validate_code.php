<?php
//先把类包含进来，实际路径根据实际情况进行修改。
require './includes/common_utils/validate_code.class.php';
//实例化一个对象
$_vc = new ValidateCode();
$_vc->doimg();
if (!isset($_SESSION)) {
    session_start();
}
// 验证码保存到SESSION中
$_SESSION['code'] = $_vc->getCode();
// 标识验证码有效，验证过一次后，验证码即失效
$_SESSION['code_valid'] = 1;
