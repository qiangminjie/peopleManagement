<?php
/***************************************************************************************************
 * File Description:
 *  文件上传通用类
 * Created by   victor                 victorzhou6@microwu.com           10/27/2017, 9:29:08 AM
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 *
 * Updated History:
 * Author       Date            Content
 **************************************************************************************************/

/**
 * 保存文件 成功返回路径，失败则返回''
 * @param $file (array)         上传文件对象  如$_FILES['file']
 * @param $dirPath (string)     目标文件夹相对于服务器根目录的相对路径
 * @param $fileName (string)    保存的文件名
 * @return string
 */
function receiveFile($file, $dirPath, $fileName) {
    $absoluteDirPath = $_SERVER['DOCUMENT_ROOT'].$dirPath;
    if (!is_dir($absoluteDirPath)){
        if(!mkdir($absoluteDirPath,0755,true)) {
            return '';
        }
    }
    $path=$dirPath.'/'.$fileName;
    $absolutePath=$_SERVER['DOCUMENT_ROOT'].$path;
    if (move_uploaded_file($file['tmp_name'],$absolutePath)) {
        return $path;
    } else {
        return '';
    }
}


/**
 * 处理文件名的一种方法，自定义头信息+日期(YYYYmmddhhiiss)+随机六位数
 * @param $file
 * @param $head
 * @return string
 */
function dealFileNameByAddTimeAndRandomNumber($file, $head = '', $x = 6) {
    return $head . getNowDate() . getRand(6) . getExtension($file);
}


/**
 * 返回时间string 默认时区'PRC' 默认格式'YmdHis'
 * @param string $format
 * @param string $timeZone
 * @return string|false
 */
function getNowDate($format='YmdHis',$timeZone='PRC') {
    date_default_timezone_set($timeZone);
    return date($format);
}

/**
 * 获得上传文件文件名
 */
function getFileName($file) {
    return $file['name'];
}

/**
 * 获得上传文件的扩展名
 * @param $file
 * @return bool|string
 */
function getExtension($file) {
    $sourceFileName = $file['name'];
    $pos = strrpos($sourceFileName, '.');
    $extension = substr($sourceFileName, $pos);
    return $extension;
}

/**
 * 获得上传文件扩展名前面的部分
 * @param $file
 * @return bool|string
 */
function getBaseName($file) {
    $sourceFileName = $file['name'];
    $pos = strrpos($sourceFileName, '.');
    $baseName = substr($sourceFileName, 0, $pos);
    return $baseName;
}


/**
 * 得到x位的随机数，不足x位的补0
 * @param $x
 * @return string
 */
function getRand($x) {
    $min = 1;
    $max = pow(10, $x) - 1;
    $num = rand($min, $max);
    return sprintf('%0' . $x . 'd', $num);
}

