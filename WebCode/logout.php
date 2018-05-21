<?php
/**
 * Created by PhpStorm.
 * User: KaKa
 * Date: 11/10/16
 * Time: 23:52
 */
if(!isset($_SESSION)) {
  session_start();
}
session_destroy();
header("location:login.php");
